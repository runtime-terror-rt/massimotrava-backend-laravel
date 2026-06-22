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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        $groupedCollection = $rawReports->groupBy('inv_code')->map(function ($reports, $invCode) {
            $firstReport = $reports->first();
            
            return [
                'inv_code'          => $invCode,
                'created_at'        => $firstReport->created_at,
                'user'              => auth()->user(),
                'longevity_score'   => "84",
                'kit'               => $firstReport->kit,
                'metrics'           => $reports->map(function($row) {
                    return [
                        'id'                    => $row->id,
                        'biomarker_category_id' => $row->biomarker_category_id,
                        'category_title'        => $row->biomarkerCategory->title ?? null,
                        'subcategory_id'        => $row->biomarker_subcategory_id,
                        'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                        'value'                 => $row->value,
                        'unit'                  => $row->unit,
                        'test_status'           => "Good",
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

    public function getUserReports(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $userId = auth()->id();

        // Pull only recent package records to optimize execution queries speeds
        $rawReports = BiomarkerReport::with(['kit', 'biomarkerCategory', 'biomarkerSubcategory'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $groupedCollection = $rawReports->groupBy('inv_code')->map(function ($reports, $invCode) {
            $firstReport = $reports->first();
            
            return [
                'inv_code'   => $invCode,
                'created_at' => $firstReport->created_at,
                'user'       => auth()->user(),
                'kit'        => $firstReport->kit,
                'metrics'    => $reports->map(function($row) {
                    return [
                        'id'                    => $row->id,
                        'biomarker_category_id' => $row->biomarker_category_id,
                        'category_title'        => $row->biomarkerCategory->title ?? null,
                        'subcategory_id'        => $row->biomarker_subcategory_id,
                        'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                        'value'                 => $row->value,
                        'unit'                  => $row->unit,
                        'test_status'           => "Good",
                        'status'                => $row->status,
                    ];
                })
            ];
        })->values();

        // Pull the top/most recent report bundle package out
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
        
        $structuredBundle = [
            'inv_code'   => $invoiceReference,
            'created_at' => $firstReport->created_at,
            'user'       => auth()->user(), 
            'kit'        => $firstReport->kit,
            'metrics'    => $rawReports->map(function($row) {
                return [
                    'id'                    => $row->id,
                    'biomarker_category_id' => $row->biomarker_category_id,
                    'category_title'        => $row->biomarkerCategory->title ?? null,
                    'subcategory_id'        => $row->biomarker_subcategory_id,
                    'subcategory_title'     => $row->biomarkerSubcategory->title ?? null,
                    'value'                 => $row->value,
                    'unit'                  => $row->unit,
                    'test_status'           => "Good",
                    'status'                => $row->status,
                ];
            })
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


    public function storeReport(Request $request)
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
            $inv_code = 'INV-' . strtoupper(\Str::random(10));
            $message = 'Reports saved successfully';
        }

        try {
            \DB::transaction(function () use ($request, $inv_code, &$savedReports) {
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

            return redirect()->route('admin.reports.index')->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Biomarker Store Failed Error Trace: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to save reports data bundle structure: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Something went wrong while saving records values matrix!');
        }
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
        return view('user.reports.show', compact('reports', 'mainReport')); 
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