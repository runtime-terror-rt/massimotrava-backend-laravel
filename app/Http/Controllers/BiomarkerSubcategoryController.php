<?php

namespace App\Http\Controllers;

use App\Models\BiomarkerSubcategory;
use App\Models\BiomarkerCategory;
use Illuminate\Http\Request;

class BiomarkerSubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $subcategories = BiomarkerSubcategory::with('category')->latest()->get();
        $categories = BiomarkerCategory::all();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['status' => 'success', 'data' => $subcategories]);
        }

        return view('admin.sub-category.index', compact('subcategories', 'categories'));
    }

    public function getSubcategories(Request $request, $categoryId)
    {
        $subcategories = BiomarkerSubcategory::where('biomarker_category_id', $categoryId)
            ->where('status', 1)
            ->get();

        return response()->json(['status' => 'success', 'data' => $subcategories]);
    }

    public function storeSubcategory(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:biomarker_subcategories,id',
            'biomarker_category_id' => 'required|exists:biomarker_categories,id',
            'title' => 'required|string|max:255',
            'min_range' => 'required|numeric',
            'max_range' => 'required|numeric',
            'unit' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $subcategory = BiomarkerSubcategory::updateOrCreate(
            ['id' => $request->id],
            [
                'biomarker_category_id' => $request->biomarker_category_id,
                'title' => $request->title,
                'description' => $request->description,
                'min_range' => $request->min_range,
                'max_range' => $request->max_range,
                'unit' => $request->unit,
                'status' => 1,
            ]
        );

        $isUpdate = $request->filled('id');
        $message = $isUpdate ? 'Subcategory updated successfully' : 'Subcategory created successfully';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $subcategory
            ], $isUpdate ? 200 : 201);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Request $request, $id)
    {
        $subcategory = BiomarkerSubcategory::find($id);

        if (!$subcategory) {
            $errorMsg = 'Subcategory not found';
            return ($request->wantsJson() || $request->is('api/*')) 
                ? response()->json(['status' => 'error', 'message' => $errorMsg], 404) 
                : back()->with('error', $errorMsg);
        }

        $subcategory->delete();
        $message = 'Subcategory deleted successfully';

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}