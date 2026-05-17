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
            'user_id' => 'required|exists:users,id',
            'kit_id' => 'required|exists:kits,id',
            'categories' => 'required|array|min:1',
            'categories.*.id' => 'required|exists:biomarker_categories,id',
            'categories.*.reports' => 'required|array|min:1',
            'categories.*.reports.*.subcategory_id' => 'required|exists:biomarker_subcategories,id',
            'categories.*.reports.*.value' => 'required|numeric',
        ]);

        $savedReports = [];
        $inv_code = $request->inv_code ?? 'INV-' . strtoupper(Str::random(10));

        foreach ($request->categories as $categoryData) {
            $categoryId = $categoryData['id'];

            foreach ($categoryData['reports'] as $reportData) {
                $subcategory = BiomarkerSubcategory::find($reportData['subcategory_id']);

                $report = BiomarkerReport::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'kit_id' => $request->kit_id,
                        'biomarker_subcategory_id' => $reportData['subcategory_id'],
                        'inv_code' => $inv_code 
                    ],
                    [
                        'biomarker_category_id' => $categoryId, 
                        'value' => $reportData['value'],
                        'unit' => $subcategory->unit ?? null,
                        'status' => 1,
                    ]
                );
                
                $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'All category reports saved successfully', 
                'inv_code' => $inv_code,
                'data' => $savedReports
            ]);
        }

        return back()->with('success', 'Reports saved successfully with Invoice: ' . $inv_code);
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
}