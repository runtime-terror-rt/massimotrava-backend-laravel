<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerCategory;
use App\Models\BiomarkerReport;
use App\Models\BiomarkerSubcategory;
use App\Models\Kit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
 use App\Mail\BiomarkerReportMail;
use App\Models\Notification;
use App\Services\FcmNotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;


class BiomarkerReportController extends Controller
{
    public function index(Request $request)
    {
        $query = BiomarkerReport::with([
            'kit', 
            'biomarkerSubcategory.category',
            'biomarkerSubcategory',
            'user'
        ]);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reports = $query->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $reports]);
        }

        $specificUser = $request->has('user_id') ? User::find($request->user_id) : null;

        return view('admin.reports.index', compact('reports', 'specificUser'));
    }

    /**
    * Get all paginated reports with optional kit_id filtering
    */
    public function getAllReports(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $userId = auth()->id();

        $query = BiomarkerReport::with([
                'kit', 
                'biomarkerCategory',
                'biomarkerSubcategory'
            ])
            ->where('user_id', $userId);

        if ($request->has('kit_id') && !empty($request->kit_id)) {
            $query->where('kit_id', $request->kit_id);
        }

        $rawReports = $query->latest()->get();

        // Testing-frequency modifier: distinct test batches (inv_code) in the last 6 months
        $recentBatchCount = $rawReports
            ->where('created_at', '>=', now()->subMonths(6))
            ->pluck('inv_code')
            ->unique()
            ->count();

        $groupedCollection = $rawReports->groupBy('inv_code')->map(function ($reports, $invCode) use ($recentBatchCount) {
            $firstReport = $reports->first();

            // Evaluate every biomarker row once, reuse for both the score and the label
            $evaluated = $reports->map(fn ($row) => array_merge(
                ['row' => $row],
                $this->evaluateBiomarker($row)
            ));

            return [
                'inv_code'          => $invCode,
                'created_at'        => $firstReport->created_at,
                'user'              => auth()->user(),
                'longevity_score'   => $this->calculateLongevityScore($evaluated, $recentBatchCount),
                'kit'               => $firstReport->kit,
                'metrics'           => $evaluated->map(function ($item) {
                    $row = $item['row'];
                    return [
                        'id'                    => $row->id,
                        'biomarker_category_id' => $row->biomarker_category_id,
                        'category_title'        => $row->biomarkerCategory->title ?? null,
                        'subcategory_id'        => $row->biomarker_subcategory_id,
                        'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                        'value'                 => $row->value,
                        'unit'                  => $row->unit,
                        'test_status'           => $item['label'],   // dynamically derived (Optimal/Good/Fair/At Risk/Critical)
                        'status'                => $row->status,
                    ];
                })
            ];
        })->values();

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $groupedCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedReports = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            $groupedCollection->count(), 
            $perPage, 
            $currentPage, 
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'All user reports packages retrieved successfully.',
            'data'    => $paginatedReports
        ], 200);
    }

    /**
     * Longevity Score = Σ (weight_i × normalized_category_score_i) + modifiers
     * Spec: Vyralabs Formulas & Business Logic Documentation v1.0
     *
     * $evaluated is a collection of ['row' => BiomarkerReport, 'score' => float, 'label' => string]
     */
    private function calculateLongevityScore($evaluated, int $recentBatchCount = 0): int
    {
        // Fuzzy keyword match so this works regardless of exact spelling/casing/punctuation
        // in your biomarker_categories.title column (e.g. "Inflammation & Immunity",
        // "Inflammation and Immunity", "inflammation_immunity" all match).
        $categoryWeightKeywords = [
            'metabolic'      => 25,
            'cardiovascular' => 22,
            'inflammat'      => 18,
            'immun'          => 18,
            'hormon'         => 20,
            'organ'          => 15,
            'nutrient'       => 15,
        ];

        $categoryScores = $evaluated
            ->groupBy(fn ($item) => $item['row']->biomarker_category_id)
            ->map(function ($itemsInCategory) {
                return [
                    'title' => optional($itemsInCategory->first()['row']->biomarkerCategory)->title ?? '',
                    'score' => $itemsInCategory->avg('score'),
                ];
            });

        $weightedSum = 0;
        $totalWeight = 0;
        $matchedAny  = false;

        foreach ($categoryScores as $cat) {
            $weight = $this->matchCategoryWeight($cat['title'], $categoryWeightKeywords);
            if ($weight === null) {
                continue; // category title didn't match any known keyword
            }
            $matchedAny   = true;
            $weightedSum += $weight * $cat['score'];
            $totalWeight += $weight;
        }

        // Renormalized weighted average if we matched known categories,
        // otherwise fall back to a plain average so the score is never just "0 + modifiers"
        $baseScore = $matchedAny && $totalWeight > 0
            ? ($weightedSum / $totalWeight)
            : ($categoryScores->avg('score') ?? 60.0);

        // --- Modifiers (per spec) ---
        if ($recentBatchCount >= 3) {
            $baseScore += 5;
        }

        $bmi = optional(auth()->user())->bmi; // adjust accessor if BMI lives elsewhere in your schema
        if ($bmi !== null && ($bmi < 18.5 || $bmi > 30)) {
            $baseScore -= 8;
        }

        $baseScore = max(0, min(100, $baseScore));

        return (int) round($baseScore);
    }

    private function matchCategoryWeight(string $title, array $weightKeywords): ?int
    {
        $normalized = strtolower($title);
        foreach ($weightKeywords as $keyword => $weight) {
            if (str_contains($normalized, $keyword)) {
                return $weight;
            }
        }
        return null;
    }

    /**
     * Determines a biomarker's normalized 0–100 score AND its human-readable status label
     * by comparing $row->value against whatever min/max reference-range columns actually
     * exist on the related biomarker_subcategories row — it auto-detects the column names
     * (anything containing min/low/lower and max/high/upper), so this keeps working no
     * matter what you've named them.
     */
    private function evaluateBiomarker($row): array
    {
        $value = is_numeric($row->value) ? (float) $row->value : null;
        $sub   = $row->biomarkerSubcategory;

        if ($value === null || !$sub) {
            return ['score' => 60.0, 'label' => 'Unknown'];
        }

        $attrs = $sub->getAttributes();
        $min   = $this->findRangeValue($attrs, ['min', 'low', 'lower']);
        $max   = $this->findRangeValue($attrs, ['max', 'high', 'upper']);

        if ($min === null || $max === null || $max <= $min) {
            return ['score' => 60.0, 'label' => 'Unknown']; // no usable range columns found
        }

        $span   = $max - $min;
        $coreLo = $min + $span * 0.2;
        $coreHi = $max - $span * 0.2;

        if ($value >= $coreLo && $value <= $coreHi) {
            return ['score' => 97.5, 'label' => 'Optimal'];
        }

        if ($value >= $min && $value <= $max) {
            return ['score' => 87.0, 'label' => 'Good'];
        }

        $deviation = $value < $min ? ($min - $value) / $span : ($value - $max) / $span;

        return match (true) {
            $deviation <= 0.15 => ['score' => 69.5, 'label' => 'Fair'],
            $deviation <= 0.40 => ['score' => 49.5, 'label' => 'At Risk'],
            default            => ['score' => 19.5, 'label' => 'Critical'],
        };
    }

    private function findRangeValue(array $attrs, array $keywords): ?float
    {
        foreach ($attrs as $key => $val) {
            if ($val === null || !is_numeric($val)) {
                continue;
            }
            foreach ($keywords as $kw) {
                if (str_contains(strtolower($key), $kw)) {
                    return (float) $val;
                }
            }
        }
        return null;
    }

    public function getUserReports(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $userId = auth()->id();

        $rawReports = BiomarkerReport::with(['kit', 'biomarkerCategory', 'biomarkerSubcategory'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Testing-frequency modifier: distinct test batches (inv_code) in the last 6 months
        $recentBatchCount = $rawReports
            ->where('created_at', '>=', now()->subMonths(6))
            ->pluck('inv_code')
            ->unique()
            ->count();

        $groupedCollection = $rawReports->groupBy('inv_code')->map(function ($reports, $invCode) use ($recentBatchCount) {
            $firstReport = $reports->first();

            $evaluated = $reports->map(fn ($row) => array_merge(
                ['row' => $row],
                $this->evaluateBiomarker($row)
            ));

            $metrics = $evaluated->map(function ($item) {
                $row = $item['row'];
                return [
                    'id'                    => $row->id,
                    'biomarker_category_id' => $row->biomarker_category_id,
                    'category_title'        => $row->biomarkerCategory->title ?? null,
                    'subcategory_id'        => $row->biomarker_subcategory_id,
                    'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                    'value'                 => $row->value,
                    'unit'                  => $row->unit,
                    'test_status'           => $item['label'],
                    'status'                => $row->status,
                ];
            });

            return [
                'inv_code'        => $invCode,
                'created_at'      => $firstReport->created_at,
                'user'            => auth()->user(),
                'kit'             => $firstReport->kit,
                'metrics'         => $metrics,
                'longevity_score' => $this->calculateLongevityScore($evaluated, $recentBatchCount),
            ];
        })->values();

        $lastReportBundle = $groupedCollection->first();

        return response()->json([
            'status'  => 'success',
            'message' => 'User reports bundle packages retrieved successfully.',
            'data'    => [
                'last_report' => $lastReportBundle ?? null,
            ]
        ], 200);
    }

    /**
     * Get a specific report bundle package by inv_code
     */
    public function getReportByInvoice(Request $request, $invCode = null)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }
        $invoiceReference = $invCode ?? $request->query('inv_code');

        if (empty($invoiceReference)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invoice code parameter is required.'
            ], 400);
        }

        $userId = auth()->id();

        $rawReports = BiomarkerReport::with([
                'kit',
                'biomarkerCategory',
                'biomarkerSubcategory'
            ])
            ->where('user_id', $userId)
            ->where('inv_code', $invoiceReference)
            ->get();

        if ($rawReports->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No biomarker report package found matching the provided invoice code.'
            ], 404);
        }

        $firstReport = $rawReports->first();

        $recentBatchCount = BiomarkerReport::where('user_id', $userId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->distinct()
            ->pluck('inv_code')
            ->count();

        $evaluated = $rawReports->map(fn ($row) => array_merge(
            ['row' => $row],
            $this->evaluateBiomarker($row)
        ));

        $metrics = $evaluated->map(function ($item) {
            $row = $item['row'];
            return [
                'id'                    => $row->id,
                'biomarker_category_id' => $row->biomarker_category_id,
                'category_title'        => $row->biomarkerCategory->title ?? null,
                'subcategory_id'        => $row->biomarker_subcategory_id,
                'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                'value'                 => $row->value,
                'unit'                  => $row->unit,
                'test_status'           => $item['label'],
                'status'                => $row->status,
            ];
        });

        $structuredBundle = [
            'inv_code'        => $invoiceReference,
            'created_at'      => $firstReport->created_at,
            'user'            => auth()->user(),
            'kit'             => $firstReport->kit,
            'metrics'         => $metrics,
            'longevity_score' => $this->calculateLongevityScore($evaluated, $recentBatchCount),
        ];

        return response()->json([
            'status'  => 'success',
            'message' => 'Target biomarker invoice report package retrieved successfully.',
            'data'    => $structuredBundle
        ], 200);
    }
    
    public function create()
    {
        $users = User::all();
        $kits = Kit::all();
        $categories = BiomarkerCategory::all();
        $subcategories = BiomarkerSubcategory::all();

        return view('admin.reports.create', compact('users', 'kits', 'categories', 'subcategories'));
    }


    public function storeReport(Request $request, FcmNotificationService $fcmService)
    {
        $request->validate([
            'inv_code'              => 'nullable|string|exists:biomarker_reports,inv_code',
            'user_id'               => 'required|exists:users,id',
            'kit_id'                => 'required|exists:kits,id',
            'categories'            => 'required|array|min:1',
            'categories.*.id'       => 'required|exists:biomarker_categories,id',
            'categories.*.reports'  => 'required|array|min:1',
            'categories.*.reports.*.subcategory_id' => 'required|exists:biomarker_subcategories,id',
            'categories.*.reports.*.value'          => 'required|numeric',
        ]);

        $savedReports = [];
        
        if ($request->filled('inv_code')) {
            $inv_code = $request->inv_code;
            BiomarkerReport::where('inv_code', $inv_code)->delete();
            $message = 'Reports updated successfully';
        } else {
            $inv_code = 'INV-' . strtoupper(Str::random(10));
            $message = 'Reports saved successfully';
        }

        try {
            DB::transaction(function () use ($request, $inv_code, &$savedReports) {
                foreach ($request->categories as $categoryData) {
                    $categoryId = $categoryData['id'];

                    foreach ($categoryData['reports'] as $reportData) {
                        $subcategory = BiomarkerSubcategory::find($reportData['subcategory_id']);

                        $report = BiomarkerReport::create([
                            'user_id'                  => $request->user_id,
                            'kit_id'                   => $request->kit_id,
                            'biomarker_category_id'    => $categoryId,
                            'biomarker_subcategory_id' => $reportData['subcategory_id'],
                            'value'                    => $reportData['value'],
                            'unit'                     => $subcategory->unit ?? null,
                            'status'                   => 1,
                            'inv_code'                 => $inv_code
                        ]);

                        $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
                    }
                }
            });

            $generateReportSetting = DB::table('notification_settings')
                ->where('user_id', $request->user_id)
                ->value('generate_report');

            if ($generateReportSetting == 1) {
                $dbNotification = Notification::create([
                    'user_id' => $request->user_id,
                    'type'    => 'health_insight',
                    'title'   => 'Your Biomarker Report is Ready',
                    'message' => 'Your latest lab report has been processed and is now available to view.',
                    'link'    => route('user.show.reports', $inv_code),
                    'is_read' => false,
                ]);

                $fcmService->sendPush(
                    $request->user_id,
                    'Your Biomarker Report is Ready',
                    'Your latest lab report has been processed and is now available to view.',
                    [
                        'type'            => 'health_insight',
                        'inv_code'        => $inv_code,
                        'notification_id' => (string) $dbNotification->id
                    ]
                );
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'success', 
                    'message' => $message, 
                    'data'    => [
                        'inv_code' => $inv_code,
                        'reports'  => $savedReports
                    ]
                ]);
            }

            $userSettings = DB::table('notification_settings')
                ->where('user_id', $request->user_id)
                ->first();

            if ($userSettings && $userSettings->sms_notification == 1) {
                $userPhone = DB::table('users')->where('id', $request->user_id)->value('phone');
                
                if ($userPhone) {
                    $smsMessage = "Hello, your Biomarker Report (Code: {$inv_code}) is ready at VyraLabs. Please log in to check your health insights.";
                    
                    \App\Services\SmsService::sendSms($userPhone, $smsMessage);
                }
            }

            return redirect()->route('admin.reports.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Biomarker Store Failed Error Trace: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to save reports data bundle structure: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Something went wrong while saving records values matrix!');
        }
    }
   
    public function userReportShow($inv_code)
    {
        $reports = BiomarkerReport::with(['biomarkerCategory', 'biomarkerSubcategory', 'user', 'kit'])
        ->where('inv_code', $inv_code)
        ->where('user_id', auth()->id())
        ->get();

        if ($reports->isEmpty()) {
            abort(403, 'Unauthorized action or report not found.');
        }

        $mainReport = $reports->first();
        $specificUser = $mainReport->user;

        return view('user.show-report', compact('reports', 'mainReport', 'specificUser'));
    }

    public function getReports(Request $request)
    {
        $request->validate([
            'user_id'  => 'nullable|exists:users,id',
            'inv_code' => 'nullable|string'
        ]);

        $reports = BiomarkerReport::with([
                'biomarkerSubcategory.category:id,title',
                'biomarkerSubcategory:id,biomarker_category_id,title,min_range,max_range,unit',
                'user:id,name,email'
            ])
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->when($request->inv_code, function ($query) use ($request) {
                return $query->where('inv_code', $request->inv_code);
            })
            ->latest()
            ->get();

        if ($reports->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No reports found for the given criteria.'
                ], 404);
            }
            return back()->with('error', 'No reports found.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data'   => $reports
            ], 200);
        }

        $specificUser = $request->user_id ? \App\Models\User::find($request->user_id) : null;
        return view('admin.reports.index', compact('reports', 'specificUser'));
    }

    public function destroy(Request $request, $id)
    {
        $report = BiomarkerReport::find($id);

        if (!$report) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Report not found'
                ], 404);
            }
            return back()->with('error', 'Report not found');
        }

        $report->delete();

        $message = 'Report deleted successfully';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        }

        return back()->with('success', $message);
    }


    public function downloadPdf(Request $request)
    {
        $query = BiomarkerReport::with(['user', 'kit', 'biomarkerCategory', 'biomarkerSubcategory']);

        if ($request->has('user_id') && $request->user_id != null) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('inv_code') && $request->inv_code != null) {
            $query->where('inv_code', $request->inv_code);
        }

        if ($request->has('date') && $request->date != null) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'No reports found for the selected criteria.');
        }

        $pdf = Pdf::loadView('admin.reports.pdf', compact('reports'));

        $fileName = 'report-' . ($request->inv_code ?? date('d-m-Y')) . '.pdf';

        return $pdf->download($fileName);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'inv_code' => 'required'
        ]);

        $reports = BiomarkerReport::with(['user', 'kit', 'biomarkerCategory', 'biomarkerSubcategory'])
                    ->where('inv_code', $request->inv_code)
                    ->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'Report not found for invoice: ' . $request->inv_code);
        }

        $user = $reports->first()->user;

        if (!$user || !$user->email) {
            return back()->with('error', 'User or user email address is missing.');
        }

        try {
            $data = [
                'reports' => $reports,
                'specificUser' => $user
            ];
            
            $pdf = Pdf::loadView('admin.reports.pdf', $data);

            Mail::to($user->email)->send(new BiomarkerReportMail($reports, $user, $pdf));

            return back()->with('success', 'Medical report successfully sent to ' . $user->email);

        } catch (\Exception $e) {
            Log::error('Email Sending Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified biomarker report batch dashboard layout view.
     */
    public function show($parameter)
    {
        if (is_numeric($parameter)) {
            $referenceRow = BiomarkerReport::find($parameter);
            $inv_code = $referenceRow ? $referenceRow->inv_code : null;
        } else {
            $inv_code = $parameter;
        }

        $reports = BiomarkerReport::with([
            'user', 
            'kit', 
            'biomarkerCategory', 
            'biomarkerSubcategory'
        ])
        ->where('inv_code', $inv_code)
        ->get();

        if ($reports->isEmpty()) {
            abort(404, 'Biomarker report packet not found.');
        }

        $mainReport = $reports->first();

        return view('admin.reports.show', compact('reports', 'mainReport'));
    }

    public function showUserReport($parameter)
    {
        if (is_numeric($parameter)) {
            $referenceRow = BiomarkerReport::find($parameter);
            $inv_code = $referenceRow ? $referenceRow->inv_code : null;
        } else {
            $inv_code = $parameter;
        }

        $reports = BiomarkerReport::with(['user', 'kit', 'biomarkerCategory', 'biomarkerSubcategory'])
            ->where('inv_code', $inv_code)
            ->where('user_id', auth()->id()) 
            ->get();

        if ($reports->isEmpty()) {
            abort(403, 'Unauthorized action or report not found.');
        }

        $mainReport = $reports->first();
        return view('admin.reports.show', compact('reports', 'mainReport')); 
    }

    /**
     * Show the form for editing the specified biomarker report batch grid matrix.
     */
    public function edit($inv_code)
    {
        $allRelatedReports = BiomarkerReport::with(['biomarkerSubcategory'])
            ->where('inv_code', $inv_code)
            ->get();

        if ($allRelatedReports->isEmpty()) {
            abort(404, 'Target biomarker dataset not found.');
        }

        $users = \App\Models\User::all();
        $categories = \App\Models\BiomarkerCategory::all();
        $reportDetails = [];
        
        $groupedByCategory = $allRelatedReports->groupBy('biomarker_category_id');

        foreach ($groupedByCategory as $catId => $reportsCollection) {
            $category = \App\Models\BiomarkerCategory::find($catId);
            
            $formattedSubcategories = $reportsCollection->map(function($rep) {
                return (object)[
                    'subcategory_id' => $rep->biomarker_subcategory_id,
                    'value'          => $rep->value
                ];
            });

            $reportDetails[] = [
                'category_id'      => $catId,
                'subcategories'    => $formattedSubcategories,
                'all_subs_options' => $category ? $category->subcategories : []
            ];
        }

        $report = $allRelatedReports->first();

        return view('admin.reports.edit', compact('report', 'users', 'categories', 'reportDetails'));
    }

    public function update(Request $request, $inv_code)
    {
        // Validate request payloads and verify that the target inv_code exists
        $request->validate([
            'user_id'                               => 'required|exists:users,id',
            'kit_id'                                => 'required|exists:kits,id',
            'categories'                            => 'required|array|min:1',
            'categories.*.id'                       => 'required|exists:biomarker_categories,id',
            'categories.*.reports'                  => 'required|array|min:1',
            'categories.*.reports.*.subcategory_id' => 'required|exists:biomarker_subcategories,id',
            'categories.*.reports.*.value'          => 'required|numeric',
        ]);

        $savedReports = [];

        try {
            \DB::transaction(function () use ($request, $inv_code, &$savedReports) {
                // Step 1: Wipe out existing entries belonging to this specific batch code safely
                BiomarkerReport::where('inv_code', $inv_code)->delete();

                // Step 2: Loop through dynamic entries and insert fresh data sets
                foreach ($request->categories as $categoryData) {
                    $categoryId = $categoryData['id'];

                    if (!isset($categoryData['reports']) || !is_array($categoryData['reports'])) {
                        continue;
                    }

                    foreach ($categoryData['reports'] as $reportData) {
                        $subcategory = BiomarkerSubcategory::find($reportData['subcategory_id']);

                        $report = BiomarkerReport::create([
                            'user_id'                  => $request->user_id,
                            'kit_id'                   => $request->kit_id,
                            'biomarker_category_id'    => $categoryId,
                            'biomarker_subcategory_id' => $reportData['subcategory_id'],
                            'value'                    => $reportData['value'],
                            'unit'                     => $subcategory->unit ?? null,
                            'status'                   => 1,
                            'inv_code'                 => $inv_code // Preserve the same batch identifier code
                        ]);

                        $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
                    }
                }
            });

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'success', 
                    'message' => 'Reports updated successfully', 
                    'data'    => [
                        'inv_code' => $inv_code,
                        'reports'  => $savedReports
                    ]
                ]);
            }

            return redirect()->route('admin.reports.index')->with('success', 'Reports updated successfully');

        } catch (\Exception $e) {
            \Log::error('Biomarker Update Method Failed: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Processing error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Something went wrong while managing biomarker row updates!');
        }
    }

}