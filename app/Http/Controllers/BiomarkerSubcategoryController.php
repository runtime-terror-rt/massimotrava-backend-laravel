<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerSubcategory;
use Illuminate\Http\Request;

class BiomarkerSubcategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = BiomarkerSubcategory::get();
        return response()->json(['status' => 'success', 'data' => $subcategories]);
    }
    
    public function getSubcategories($categoryId)
    {
        $subcategories = BiomarkerSubcategory::with('category')->where('biomarker_category_id', $categoryId)
            ->where('status', 1)
            ->get();
        return response()->json(['status' => 'success', 'data' => $subcategories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'biomarker_category_id' => 'required|exists:biomarker_categories,id',
            'title' => 'required|string',
            'min_range' => 'required|numeric',
            'max_range' => 'required|numeric',
            'unit' => 'required|string',
        ]);

        $subcategory = BiomarkerSubcategory::updateOrCreate(
            [
                'biomarker_category_id' => $request->biomarker_category_id,
                'title' => $request->title,
            ],
            [
                'description' => $request->description,
                'min_range' => $request->min_range,
                'max_range' => $request->max_range,
                'unit' => $request->unit,
                'status' => 1,
            ]
        );

        $statusCode = $subcategory->wasRecentlyCreated ? 201 : 200;

        return response()->json([
            'status' => 'success',
            'message' => $subcategory->wasRecentlyCreated ? 'Subcategory created' : 'Subcategory updated',
            'data' => $subcategory
        ], $statusCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(BiomarkerSubcategory $biomarkerSubcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiomarkerSubcategory $biomarkerSubcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiomarkerSubcategory $biomarkerSubcategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcategory = BiomarkerSubcategory::find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subcategory->delete();

        return response()->json(['message' => 'Subcategory deleted successfully']);
    }
}
