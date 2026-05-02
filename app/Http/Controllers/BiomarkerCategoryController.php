<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerCategory;
use Illuminate\Http\Request;

class BiomarkerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BiomarkerCategory::get();
        return response()->json(['status' => 'success', 'data' => $categories]); 
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
    
    public function storeCategory(Request $request)
    {
        $request->validate([
            'id'    => 'nullable|exists:biomarker_categories,id',
            'title' => 'required|string|unique:biomarker_categories,title,' . $request->id,
            'description' => 'nullable|string',
        ]);

        $category = BiomarkerCategory::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $request->title,
                'description' => $request->description,
                'status' => 1, 
            ]
        );

        $isUpdate = $request->filled('id');
        $message = $isUpdate ? 'Category updated successfully' : 'Category created successfully';

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $category
            ], $isUpdate ? 200 : 201);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(BiomarkerCategory $biomarkerCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiomarkerCategory $biomarkerCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiomarkerCategory $biomarkerCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = BiomarkerCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
