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
            'biomarker_category_id' => 'required|exists:biomarker_categories,id',
            'reports' => 'required|array',
            'reports.*.subcategory_id' => 'required|exists:biomarker_subcategories,id',
            'reports.*.value' => 'required|numeric',
        ]);

        $savedReports = [];
        $inv_code = $request->inv_code ?? 'INV-' . strtoupper(Str::random(10));

        foreach ($request->reports as $reportData) {
            $subcategory = BiomarkerSubcategory::find($reportData['subcategory_id']);

            $report = BiomarkerReport::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'kit_id' => $request->kit_id,
                    'biomarker_subcategory_id' => $reportData['subcategory_id'],
                ],
                [
                    'biomarker_category_id' => $request->biomarker_category_id, 
                    'value' => $reportData['value'],
                    'unit' => $subcategory->unit ?? null,
                    'status' => 1,
                    'inv_code' => $inv_code
                ]
            );
            $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Reports saved successfully', 'data' => $savedReports]);
        }

        return back()->with('success', 'Reports saved successfully');
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
            return $request->expectsJson() 
                ? response()->json(['message' => 'Report not found'], 404) 
                : back()->with('error', 'Report not found');
        }

        $report->delete();

        return $request->expectsJson() 
            ? response()->json(['message' => 'Report deleted successfully']) 
            : back()->with('success', 'Report deleted successfully');
    }


    public function downloadPdf(Request $request)
    {
    $query = BiomarkerReport::with(['user', 'biomarkerSubcategory']);

    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    $reports = $query->get();

    $pdf = Pdf::loadView('admin.reports.pdf', compact('reports'));

    return $pdf->download('biomarker-reports.pdf');
}
}