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

    public function getUserReports(Request $request)
    {
       if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $userId = auth()->id();

        $allReports = BiomarkerReport::with([
                'kit', 
                'biomarkerSubcategory.category',
                'biomarkerSubcategory',
                'user'
            ])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $lastReport = BiomarkerReport::with([
                'kit', 
                'biomarkerSubcategory.category',
                'biomarkerSubcategory',
                'user'
            ])
            ->where('user_id', $userId)
            ->latest()
            ->first();

        return response()->json([
            'status'  => 'success',
            'message' => 'User reports retrieved successfully.',
            'data'    => [
                'last_report' => $lastReport ?? null, 
                'all_reports' => $allReports
            ]
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
            'inv_code'                 => 'nullable|string|exists:biomarker_reports,inv_code', // Update er jonno lagbe
            'user_id'                  => 'required|exists:users,id',
            'kit_id'                   => 'required|exists:kits,id',
            'biomarker_category_id'    => 'required|exists:biomarker_categories,id',
            'reports'                  => 'required|array',
            'reports.*.subcategory_id' => 'required|exists:biomarker_subcategories,id',
            'reports.*.value'          => 'required|numeric',
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

        foreach ($request->reports as $reportData) {
            $subcategory = BiomarkerSubcategory::find($reportData['subcategory_id']);

            $report = BiomarkerReport::create([
                'user_id'                  => $request->user_id,
                'kit_id'                   => $request->kit_id,
                'biomarker_category_id'    => $request->biomarker_category_id, 
                'biomarker_subcategory_id' => $reportData['subcategory_id'],
                'value'                    => $reportData['value'],
                'unit'                     => $subcategory->unit ?? null,
                'status'                   => 1,
                'inv_code'                 => $inv_code
            ]);

            $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
        }

        // API Response
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

        // Web Response
        return back()->with('success', $message);
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
<<<<<<< HEAD
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
=======
        $query = BiomarkerReport::with(['user', 'biomarkerSubcategory']);

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $reports = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('reports'));

        return $pdf->download('biomarker-reports.pdf');
    }
>>>>>>> 360d112091f148740643b8c31e15d73461dd6a1e
}