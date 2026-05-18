<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BiomarkerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = BiomarkerCategory::latest()->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        }

        return view('admin.category.index', compact('categories'));
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
        try {
            $validatedData = $request->validate([
                'id'          => 'nullable|exists:biomarker_categories,id',
                'title'       => 'required|string|max:255|unique:biomarker_categories,title,' . $request->id,
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation Failed',
                    'errors'  => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $category = BiomarkerCategory::updateOrCreate(
            ['id' => $request->id],
            [
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => 1, 
            ]
        );

        $isUpdate = $request->filled('id');
        $message  = $isUpdate ? 'Category updated successfully' : 'Category created successfully';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status'  => 'success',
                'message' => $message,
                'data'    => $category
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
    public function destroy(Request $request, $id)
    {
        $category = BiomarkerCategory::find($id);

        if (!$category) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found'
                ], 404);
            }
            return back()->with('error', 'Category not found');
        }

        $category->delete();

        $message = 'Category deleted successfully';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ], 200);
        }

        return back()->with('success', $message);
    }
}
