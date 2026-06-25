<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PrivacyPolicyController extends Controller
{
    public function index(Request $request)
    {
        $policies = PrivacyPolicy::latest()->get();
        
        $selectedPolicy = $request->id ? PrivacyPolicy::find($request->id) : PrivacyPolicy::first() ?? new PrivacyPolicy();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'list' => $policies,
                    'active_policy' => $selectedPolicy
                ]
            ]);
        }

        return view('admin.privacy_policy.index', compact('policies', 'selectedPolicy'));
    }

    public function frontIndex(){
        $policy = PrivacyPolicy::where('is_active', true)
                                ->latest()
                                ->first();

        $metaTitle = $policy ? $policy->title . " | Vyralabs" : "Privacy Policy | Vyralabs";
        $lastUpdated = $policy ? $policy->updated_at->format('M d, Y') : now()->format('M d, Y');

        return view('user.privacy', compact('policy', 'metaTitle', 'lastUpdated'));
    }

    /**
     * Create and Update 
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'              => 'nullable|exists:privacy_policies,id',
            'title'           => 'required|string|max:255',
            'is_active'       => 'required|boolean',
            'items'           => 'required|array|min:1', 
            'items.*.heading' => 'required|string',
            'items.*.content' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $policy = PrivacyPolicy::updateOrCreate(
                ['id' => $request->id], 
                [
                    'title'     => $request->title,
                    'content'   => $request->items, // Ensure $casts = ['content' => 'array'] in Model
                    'is_active' => $request->is_active,
                ]
            );

            $isUpdate = $request->filled('id');
            $message = $isUpdate ? 'Privacy Policy updated.' : 'Privacy Policy created.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data'    => $policy
                ]);
            }

            return redirect()->route('admin.privacy-policy.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Policy Error: " . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error saving policy'], 500);
            }
            return back()->with('error', 'Something went wrong.')->withInput();
        }
    }
}