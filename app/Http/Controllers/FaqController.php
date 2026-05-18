<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * API User-er jonno: Shob active FAQ list fetch korbe.
     * Web-e jodi bebohar korte chan, ekhane wantsJson use kora hoyeche.
     */
    public function index(Request $request)
    {
        $faqs = Faq::where('is_active', true)->latest()->get();
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $faqs
            ]);
        }

        // Optional: Web context-er frontend view thakle (na thakle problem nai, API kaj korbe)
        return view('frontend.faq', compact('faqs'));
    }

    /**
     * Web Admin Panel-er jonno: List and Selected/Edit FAQ handle korbe.
     */
    public function adminIndex(Request $request)
    {
        $faqs = Faq::latest()->get();
        
        // Form field-e automatic data populate korar jonno (Edit context tracker)
        $selectedFaq = $request->id ? Faq::find($request->id) : null;
                    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'all_faqs' => $faqs,
                    'selected_faq' => $selectedFaq
                ]
            ]);
        }

        return view('admin.faq.index', compact('faqs', 'selectedFaq'));
    }

    /**
     * Create and Update Function (Uboy Web and API support).
     */
    public function storeOrUpdate(Request $request)
    {
        // 1. Role / Authorization Check
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only an Admin can manage FAQs.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // 2. Request Validation
        $request->validate([
            'id'        => 'nullable|exists:faqs,id', 
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string',
            'is_active' => 'nullable', // Dropdown field ba checkbox field handling
        ]);

        // Status filter: Checkbox/Dropdown structure data normalize kora
        $isActive = true;
        if ($request->has('is_active')) {
            $isActive = (bool) $request->is_active;
        }

        // 3. Update or Create Logic
        $faq = Faq::updateOrCreate(
            ['id' => $request->id], 
            [
                'question'  => $request->question,
                'answer'    => $request->answer,
                'is_active' => $isActive,
            ]
        );

        $message = $request->id ? 'FAQ updated successfully!' : 'FAQ created successfully!';

        // 4. Response Based on Request Type (API vs Web)
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $faq
            ]);
        }

        return redirect()->route('admin.faq.index')->with('success', $message);
    }

    /**
     * Single FAQ Details (Uboy API/Web JSON fallback).
     */
    public function show(Request $request, $id)
    {
        $faq = Faq::find($id);
        
        if (!$faq) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'FAQ Not Found'], 404);
            }
            return redirect()->route('admin.faq.index')->with('error', 'FAQ not found.');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $faq]);
        }

        return view('admin.faq.show', compact('faq'));
    }

    /**
     * Record Delete Function (Uboy Web Form and API support).
     */
    public function destroy(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only an Admin can delete FAQs.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $faq = Faq::find($id);
        
        if (!$faq) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
            }
            return redirect()->route('admin.faq.index')->with('error', 'FAQ not found.');
        }

        $faq->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'FAQ deleted successfully']);
        }

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully!');
    }

    /**
     * Status Toggle Switch (Uboy Web Link/Form and API support).
     */
    public function toggleActive(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only an Admin can manage FAQs.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $faq = Faq::find($id);
        
        if (!$faq) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
            }
            return redirect()->route('admin.faq.index')->with('error', 'FAQ not found.');
        }

        // Dynamic boolean inversion toggle
        $faq->is_active = !$faq->is_active;
        $faq->save();

        $statusMessage = 'FAQ status changed to ' . ($faq->is_active ? 'Active' : 'Inactive');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => $statusMessage, 
                'data' => $faq
            ]);
        }

        return redirect()->back()->with('success', $statusMessage);
    } 
}