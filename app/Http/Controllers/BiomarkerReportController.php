<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerReport;
use Illuminate\Http\Request;
use App\Models\BiomarkerCategory;
use App\Models\BiomarkerSubcategory;
use Illuminate\Support\Str;

class BiomarkerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = BiomarkerReport::with([
            'kit', 
            'biomarkerSubcategory.category',
            'biomarkerSubcategory'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $reports
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

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
                    'inv_code' => $request->inv_code ?? 'INV-' . strtoupper(Str::random(10))
                ]
            );
            $savedReports[] = $report->load(['biomarkerCategory', 'biomarkerSubcategory']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reports saved successfully',
            'data' => $savedReports
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = BiomarkerReport::find($id);
        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

    public function getReports(Request $request)
    {
        $request->validate([
            'user_id'  => 'nullable|exists:users,id',
            'inv_code' => 'nullable|string'
        ]);

        $reports = BiomarkerReport::with([
            'biomarkerSubcategory.category:id,title',
            'biomarkerSubcategory:id,biomarker_category_id,title,min_range,max_range,unit'
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
            return response()->json([
                'status'  => 'error',
                'message' => 'No reports found for the given criteria.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $reports
        ], 200);
    }
}
