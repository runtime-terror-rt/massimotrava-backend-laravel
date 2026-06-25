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
}
