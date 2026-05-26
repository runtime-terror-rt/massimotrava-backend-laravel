<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $billing = strtolower($request->query('billing', 'monthly'));

            $query = SubscriptionPlan::query(); 
            if (in_array($billing, ['monthly', 'annual'])) {
                $query->where('billing_cycle', $billing);
            }
            $plansCollection = $query->latest()->get();

            $plans = $plansCollection->map(function ($plan) {
                return [
                    'id'               => $plan->id,
                    'name'             => $plan->name,
                    'billing_cycle'    => $plan->billing_cycle,
                    'duration'         => $plan->duration,
                    'member_limit'     => $plan->member_limit,
                    'features'         => is_string($plan->features) ? json_decode($plan->features, true) : (is_array($plan->features) ? $plan->features : []),
                    'status'           => $plan->status,
                    'price'            => $plan->price,
                    'projection_limit' => $plan->projection_limit,
                    'status_label'     => $plan->status ? 'Active' : 'Inactive',
                ];
            });

            $userSubscriptions = Subscription::with(['user', 'plan'])
                ->latest()
                ->paginate(10);

            return view('admin.payments.index', compact('plans', 'userSubscriptions'));

        } catch (\Exception $e) {
            \Log::error('Failed to load plans view: ' . $e->getMessage());
            throw $e; 
        }
    }

    
    public function subscribeUser(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $plan = SubscriptionPlan::findOrFail($request->plan_id);
            $userId = $request->user_id;

            Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $startsAt = Carbon::now();
            if ($plan->billing_cycle === 'annual') {
                $endsAt = Carbon::now()->addYear();
            } else {
                $endsAt = $plan->duration ? Carbon::now()->addDays($plan->duration) : Carbon::now()->addMonth();
            }

            $subscription = Subscription::create([
                'user_id'              => $userId,
                'subscription_plan_id' => $plan->id,
                'billing_cycle'        => $plan->billing_cycle,
                'price'                => $plan->price,
                'status'               => 'active',
                'starts_at'            => $startsAt,
                'ends_at'              => $endsAt,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User subscribed successfully.',
                'data'    => $subscription
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Subscription Activation Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id'       => 'nullable|integer|exists:subscription_plans,id',
            'name'          => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,annual',
            'price'         => 'required|numeric|min:0',
            'duration'      => 'nullable|integer',
            'features'      => 'nullable|string',
        ]);

        try {
            $featuresArray = [];
            if ($request->filled('features')) {
                $featuresArray = array_map('trim', explode(',', $request->features));
            }

            $userId = auth()->id() ?: (\App\Models\User::first()->id ?? 1);

            $plan = \App\Models\SubscriptionPlan::updateOrCreate(
                ['id' => $request->input('plan_id')], 
                [
                    'name'          => $request->name,
                    'user_id'       => $userId,
                    'billing_cycle' => $request->billing_cycle,
                    'price'         => $request->price,
                    'duration'      => $request->duration,
                    'features'      => $featuresArray,
                    'status'        => true,
                ]
            );

            $message = $request->filled('plan_id') ? 'Plan updated successfully.' : 'Plan created successfully.';

            return redirect()->route('admin.payments.index')->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Failed to save subscription plan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Display the specified subscription plan.
     */
    public function show($id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);
            $features = is_string($plan->features) ? json_decode($plan->features, true) : ($plan->features ?? []);

            return view('admin.plans.show', compact('plan', 'features'));
        } catch (\Exception $e) {
            \Log::error('Failed to show plan: ' . $e->getMessage());
            return redirect()->route('admin.plans.index')->with('error', 'Plan not found.');
        }
    }

    /**
     * Show the form for editing the specified subscription plan.
     */
    public function edit($id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);
            $featuresString = is_array($plan->features) ? implode(', ', $plan->features) : '';

            return view('admin.plans.edit', compact('plan', 'featuresString'));
        } catch (\Exception $e) {
            \Log::error('Failed to edit plan: ' . $e->getMessage());
            return redirect()->route('admin.plans.index')->with('error', 'Plan not found.');
        }
    }

    /**
     * Remove the specified subscription plan from storage.
     */
    public function destroy($id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);
            $plan->delete();

            return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete plan: ' . $e->getMessage());
            return redirect()->route('admin.plans.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}