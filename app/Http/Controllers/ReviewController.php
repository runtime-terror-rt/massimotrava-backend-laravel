<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::latest()->get();
        
        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $reviews], 200);
        }
        return view('admin.reviews.index', compact('reviews'));
    }

    public function FrontIndex()
    {
        $reviews = Review::latest()->get();
        
        return view('admin.review.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string',
            'author_name' => 'required|string|max:255',
            'is_verified' => 'nullable|boolean',
            'author_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $validated['is_verified'] = $request->has('is_verified') ? true : false;

        if ($image = $request->file('author_image')) {
            $destinationPath = 'images/reviews/';

            if (!File::isDirectory(public_path($destinationPath))) {
                File::makeDirectory(public_path($destinationPath), 0755, true);
            }
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path($destinationPath), $imageName);
            
            $validated['author_image'] = $imageName;
        }

        $review = Review::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Review added successfully.', 'data' => $review], 201);
        }
        return redirect()->back()->with('success', 'Review added successfully.');
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'author_name' => 'required|string|max:255',
            'text' => 'required|string',
            'author_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'is_verified' => 'nullable|boolean' 
        ]);

        $validated['is_verified'] = $request->has('is_verified') ? true : false;

        if ($image = $request->file('author_image')) {
            if ($review->author_image && File::exists(public_path('images/reviews/' . $review->author_image))) {
                File::delete(public_path('images/reviews/' . $review->author_image));
            }

            $destinationPath = 'images/reviews/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path($destinationPath), $imageName);
            $validated['author_image'] = $imageName;
        }

        $review->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Review updated successfully.', 'data' => $review], 200);
        }
        return redirect()->back()->with('success', 'Review updated successfully.');
    }

    public function destroy(Request $request, Review $review)
    {
        if ($review->author_image && File::exists(public_path('images/reviews/' . $review->author_image))) {
            File::delete(public_path('images/reviews/' . $review->author_image));
        }
        
        $review->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Review deleted successfully.'], 200);
        }
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }

    public function toggleStatus(Request $request, Review $review)
    {
        $review->status = $review->status == 1 ? 0 : 1;
        $review->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Review status updated successfully.', 
                'current_status' => $review->status
            ], 200);
        }

        return redirect()->back()->with('success', 'Review status updated successfully.');
    }
}