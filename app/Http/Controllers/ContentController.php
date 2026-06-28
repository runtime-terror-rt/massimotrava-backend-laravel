<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Content::with('user')->where('status', 'published');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $feed = $query->latest()->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data'   => $feed
            ], 200);
        }

        return view('admin.contents.index', compact('feed'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:post,video',
            'title'          => 'required|string|max:255',
            'status'         => 'required|in:draft,published',
            'body'           => 'required_if:type,post|nullable|string',
            'video_url'      => 'required_if:type,video|nullable|url',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', 
            'duration'       => 'nullable|integer|min:0',    
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/contents'), $filename);
                $imagePath = 'uploads/contents/' . $filename;
            }

            $contentData = [
                'user_id'        => auth()->id() ?? 1, 
                'type'           => $request->type,
                'title'          => $request->title,
                'slug'           => Str::slug($request->title) . '-' . time(),
                'status'         => $request->status,
                'featured_image' => $imagePath,
                'published_at'   => $request->status == 'published' ? now() : null,
            ];

            if ($request->type === 'post') {
                $contentData['body']      = $request->body;
                $contentData['video_url'] = null;
                $contentData['duration']  = null;
            } else {
                $contentData['body']      = null;
                $contentData['video_url'] = $request->video_url;
                $contentData['duration']  = $request->duration ?? null;
            }

            $content = Content::create($contentData);

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => ucfirst($request->type) . ' successfully generated into the stream.',
                    'data'    => $content
                ], 201);
            }

            return redirect()->route('admin.contents.index')->with('success', 'Content created successfully!');

        } catch (\Exception $e) {
            \Log::error('Content Creation Pipeline Crash: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'error', 
                    'message' => 'Backend Process Exception: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to store resource. Database message: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.contents.create');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'type'           => 'required|in:post,video',
            'title'          => 'required|string|max:255',
            'status'         => 'required|in:draft,published',
            'body'           => 'required_if:type,post|nullable|string',
            'video_url'      => 'required_if:type,video|nullable|url',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', 
            'duration'       => 'nullable|integer|min:0',    
        ]);

        try {
            $imagePath = $content->featured_image;

            if ($request->hasFile('featured_image')) {
                if ($imagePath && file_exists(public_path($imagePath))) {
                    @unlink(public_path($imagePath));
                }

                $file = $request->file('featured_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/contents'), $filename);
                $imagePath = 'uploads/contents/' . $filename;
            }

            $contentData = [
                'type'           => $request->type,
                'title'          => $request->title,
                'slug'           => Str::slug($request->title) . '-' . time(), // প্রয়োজনে ওল্ড স্লাগ রাখতে চাইলে এই লাইন বাদ দিতে পারেন
                'status'         => $request->status,
                'featured_image' => $imagePath,
                'published_at'   => $request->status == 'published' ? ($content->published_at ?? now()) : null,
            ];

            if ($request->type === 'post') {
                $contentData['body']      = $request->body;
                $contentData['video_url'] = null;
                $contentData['duration']  = null;
            } else {
                $contentData['body']      = null;
                $contentData['video_url'] = $request->video_url;
                $contentData['duration']  = $request->duration ?? null;
            }

            $content->update($contentData);

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => ucfirst($request->type) . ' successfully updated.',
                    'data'    => $content
                ], 200);
            }

            return redirect()->route('admin.contents.index')->with('success', 'Content updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Content Update Pipeline Crash: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'error', 
                    'message' => 'Backend Process Exception: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to update resource. Database message: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $content = Content::findOrFail($id);

            if ($content->featured_image && file_exists(public_path($content->featured_image))) {
                @unlink(public_path($content->featured_image));
            }

            $content->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Resource successfully removed from stream.'
                ], 200);
            }

            return redirect()->route('admin.contents.index')->with('success', 'Content deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Content Destruction Pipeline Crash: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'error', 
                    'message' => 'Backend Process Exception: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete resource. Database message: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('admin.contents.edit', compact('content'));
    }
}
