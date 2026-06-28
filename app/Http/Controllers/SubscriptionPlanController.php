<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $type = $request->query('type'); 
            $billing = strtolower($request->query('billing', 'monthly'));

            $query = SubscriptionPlan::query(); 

            if ($type && in_array($type, ['individual', 'professional'])) {
                $query->where('plan_type', $type);
            }

            $plans = $query->latest()->get();

            $data = $plans->map(function ($plan) use ($billing) {
                return [
                    'id'             => $plan->id,
                    'name'           => $plan->name,
                    'plan_type'      => $plan->plan_type,
                    'billing_cycle'  => $plan->billing_cycle,
                    'duration'       => $plan->duration,
                    'member_limit'   => $plan->member_limit,
                    'features'       => $plan->features,
                    'status'         => $plan->status,
                    'price'          => $billing === 'annual' ? $plan->annual_price : $plan->price,
                    'projection_limit' => $plan->projection_limit,
                    'status_label'     => $plan->status ? 'Active' : 'Inactive',
                ];
            });

            return response()->json([
                'success' => true,
                'count'   => $data->count(),
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch all plans: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showPricingPage(Request $request)
    {
        try {
            $billing = strtolower($request->query('billing', 'monthly'));
            
            $plans = SubscriptionPlan::where('status', true)->latest()->get();

            $data = $plans->map(function ($plan) use ($billing) {
                return [
                    'id'            => $plan->id,
                    'name'           => $plan->name,
                    'plan_type'      => $plan->plan_type,
                    'billing_cycle'  => $plan->billing_cycle,
                    'duration'       => $plan->duration,
                    'features'       => $plan->features,
                    'price'          => $billing === 'annual' ? $plan->annual_price : $plan->price,
                ];
            });

            return view('pricing', compact('data'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function storeOrUpdatePlan(Request $request)
    {
        $request->validate([
            'id'           => 'nullable|integer|exists:subscription_plans,id',
            'name'         => 'required|string|max:255',
            'billing_cycle'=> 'required|in:monthly,annual',
            'price'        => 'required|numeric|min:0',
            'duration'     => 'nullable|integer',
            'features'     => 'nullable|array',
            'status'       => 'boolean',
        ]);
    
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
            $isUpdate  = $request->filled('id');
            $existingPlan = $isUpdate ? SubscriptionPlan::find($request->id) : null;
    
            $priceChanged = $isUpdate
                && $existingPlan
                && (float) $existingPlan->price !== (float) $request->price;
    
            $stripePriceId   = $existingPlan?->stripe_price_id;
            $stripeProductId = $existingPlan?->stripe_product_id;
    
            $intervalMap = [
                'monthly' => ['interval' => 'month', 'count' => 1],
                'annual'  => ['interval' => 'year',  'count' => 1],
            ];
            $interval = $intervalMap[$request->billing_cycle] ?? ['interval' => 'month', 'count' => 1];
    
            if (!$isUpdate || !$stripePriceId || $priceChanged) {
    
                if ($stripeProductId) {
                    \Stripe\Product::update($stripeProductId, [
                        'name' => $request->name,
                    ]);
                } else {
                    $product = \Stripe\Product::create([
                        'name'        => $request->name,
                        'description' => "Vyralabs {$request->name} plan",
                    ]);
                    $stripeProductId = $product->id;
                }
    
                if ($stripePriceId && $priceChanged) {
                    \Stripe\Price::update($stripePriceId, ['active' => false]);
                }
    
                $stripePrice = \Stripe\Price::create([
                    'product'       => $stripeProductId,
                    'unit_amount'   => (int) round($request->price * 100), // cents
                    'currency'      => 'usd',
                    'recurring'     => [
                        'interval'       => $interval['interval'],
                        'interval_count' => $interval['count'],
                    ],
                ]);
    
                $stripePriceId = $stripePrice->id;
            }
    
            // ── DB save ─────────────────────────────────────────────────────
            $plan = SubscriptionPlan::updateOrCreate(
                ['id' => $request->id],
                [
                    'name'              => $request->name,
                    'user_id'           => $request->user()->id,
                    'billing_cycle'     => $request->billing_cycle,
                    'price'             => $request->price,
                    'duration'          => $request->duration,
                    'features'          => $request->features,
                    'status'            => $request->status ?? true,
                    'stripe_price_id'   => $stripePriceId,
                    'stripe_product_id' => $stripeProductId,
                ]
            );
    
            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Plan updated successfully.' : 'Plan created successfully.',
                'data'    => $plan,
            ], $isUpdate ? 200 : 201);
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe Plan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Plan Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show single plan
     */
    public function show(Request $request, $id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);

            $billing = strtolower($request->query('billing', 'monthly')); // monthly/annual default
            if (!in_array($billing, ['monthly', 'annual'])) {
                $billing = 'monthly';
            }

            $data = [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'plan_type'     => $plan->plan_type,
                'billing_cycle' => $plan->billing_cycle,
                'duration'      => $plan->duration,
                'member_limit'  => $plan->member_limit,
                'features'      => $plan->features,
                'status'        => $plan->status,
                'price'         => $billing === 'annual' ? $plan->annual_price : $plan->price,
            ];

            return response()->json([
                'success' => true,
                'data'    => $data
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle plan status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $plan = SubscriptionPlan::findOrFail($id);
            $plan->status = !$plan->status;
            $plan->save();

            return response()->json([
                'success' => true,
                'message' => 'Plan status updated successfully',
                'data' => [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'status' => $plan->status ? 'Active' : 'Inactive'
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plan status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete plan
     */
    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) return response()->json(['success' => false, 'message' => 'Plan not found'], 404);

        // Prevent deleting fixed plans
        if (in_array($plan->id, [1,2,3,4,5,6,7,8])) {
            return response()->json([
                'success' => false,
                'message' => 'This plan is fixed and cannot be deleted.'
            ], 403);
        }

        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Plan deleted successfully'], 200);
    }

    public function checkout($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $user = auth()->user();

        try {
            Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

            $checkoutSession = StripeSession::create([
                'customer_email' => $user->email,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $plan->name . ' - Longevity Suite',
                        ],
                        'unit_amount' => $plan->price * 100,
                        'recurring' => [
                            'interval' => $plan->billing_cycle === 'annual' ? 'year' : 'month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('dashboard') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pricing.page') ?? url('/'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ],
            ]);

            return redirect()->away($checkoutSession->url);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Stripe checkout failed: ' . $e->getMessage());
        }
    }
}
