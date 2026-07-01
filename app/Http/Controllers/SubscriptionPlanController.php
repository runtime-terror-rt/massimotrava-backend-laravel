<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class SubscriptionPlanController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Display plans + active subscriptions list.
     */
    public function index(Request $request)
    {
        $billing = $request->query('billing', 'monthly');

        $plans = SubscriptionPlan::where('billing_cycle', $billing)
            ->orderBy('price', 'asc')
            ->get();

        $userSubscriptions = Subscription::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('plans', 'userSubscriptions'));
    }

    /**
     * Store a new plan and create it on Stripe.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,annual',
            'price'         => 'required|numeric|min:0',
            'duration'      => 'nullable|integer|min:1',
            'features'      => 'nullable|array',
            'features.*'    => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $stripeProductId = null;
            $stripePriceId   = null;

            if ((float) $validated['price'] > 0) {
                $product = $this->stripe->products->create([
                    'name'        => $validated['name'],
                    'description' => $validated['name'] . ' subscription plan (' . $validated['billing_cycle'] . ')',
                ]);

                $price = $this->stripe->prices->create([
                    'product'     => $product->id,
                    'unit_amount' => (int) round($validated['price'] * 100),
                    'currency'    => 'usd',
                    'recurring'   => [
                        'interval' => $validated['billing_cycle'] === 'annual' ? 'year' : 'month',
                    ],
                ]);

                $stripeProductId = $product->id;
                $stripePriceId   = $price->id;
            }

            $plan = SubscriptionPlan::create([
                'name'              => $validated['name'],
                'user_id'           => Auth::id(),
                'billing_cycle'     => $validated['billing_cycle'],
                'price'             => $validated['price'],
                'duration'          => $validated['duration'] ?? null,
                'features'          => array_values(array_filter($request->input('features', []))),
                'stripe_product_id' => $stripeProductId,
                'stripe_price_id'   => $stripePriceId,
                'status'            => true,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Subscription plan "' . $plan->name . '" created successfully.');

        } catch (ApiErrorException $e) {
            DB::rollBack();
            Log::error('[PLAN STORE] Stripe error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Stripe error: ' . $e->getMessage());

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[PLAN STORE] Error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing plan, syncing Stripe Price/Product as needed.
     */
    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,annual',
            'price'         => 'required|numeric|min:0',
            'duration'      => 'nullable|integer|min:1',
            'features'      => 'nullable|array',
            'features.*'    => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $newPrice     = (float) $validated['price'];
            $priceChanged = $newPrice != (float) $plan->price;
            $cycleChanged = $validated['billing_cycle'] !== $plan->billing_cycle;
            $nameChanged  = $validated['name'] !== $plan->name;

            $stripeProductId = $plan->stripe_product_id;
            $stripePriceId   = $plan->stripe_price_id;

            if ($newPrice > 0) {
                if (!$stripeProductId) {
                    $product = $this->stripe->products->create([
                        'name'        => $validated['name'],
                        'description' => $validated['name'] . ' subscription plan (' . $validated['billing_cycle'] . ')',
                    ]);
                    $stripeProductId = $product->id;
                } elseif ($nameChanged) {
                    $this->stripe->products->update($stripeProductId, [
                        'name' => $validated['name'],
                    ]);
                }

                if ($priceChanged || $cycleChanged || !$stripePriceId) {
                    $newStripePrice = $this->stripe->prices->create([
                        'product'     => $stripeProductId,
                        'unit_amount' => (int) round($newPrice * 100),
                        'currency'    => 'usd',
                        'recurring'   => [
                            'interval' => $validated['billing_cycle'] === 'annual' ? 'year' : 'month',
                        ],
                    ]);

                    if ($stripePriceId) {
                        try {
                            $this->stripe->prices->update($stripePriceId, ['active' => false]);
                        } catch (ApiErrorException $e) {
                            Log::warning('[PLAN UPDATE] Could not archive old price: ' . $e->getMessage());
                        }
                    }

                    $stripePriceId = $newStripePrice->id;
                }
            } else {
                if ($stripePriceId) {
                    try {
                        $this->stripe->prices->update($stripePriceId, ['active' => false]);
                    } catch (ApiErrorException $e) {
                        Log::warning('[PLAN UPDATE] Could not archive price for free plan: ' . $e->getMessage());
                    }
                }
                $stripePriceId = null;
            }

            $plan->update([
                'name'              => $validated['name'],
                'billing_cycle'     => $validated['billing_cycle'],
                'price'             => $newPrice,
                'duration'          => $validated['duration'] ?? null,
                'features'          => array_values(array_filter($request->input('features', []))),
                'stripe_product_id' => $stripeProductId,
                'stripe_price_id'   => $stripePriceId,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Subscription plan "' . $plan->name . '" updated successfully.');

        } catch (ApiErrorException $e) {
            DB::rollBack();
            Log::error('[PLAN UPDATE] Stripe error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Stripe error: ' . $e->getMessage());

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[PLAN UPDATE] Error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Delete a plan: archive Stripe Price/Product, soft-delete locally.
     */
    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $activeSubscribers = Subscription::where('subscription_plan_id', $plan->id)
            ->where('status', 'active')
            ->count();

        if ($activeSubscribers > 0) {
            return back()->with('error', 'Cannot delete "' . $plan->name . '" — it has ' . $activeSubscribers . ' active subscriber(s).');
        }

        DB::beginTransaction();

        try {
            if ($plan->stripe_price_id) {
                try {
                    $this->stripe->prices->update($plan->stripe_price_id, ['active' => false]);
                } catch (ApiErrorException $e) {
                    Log::warning('[PLAN DESTROY] Could not archive price: ' . $e->getMessage());
                }
            }

            if ($plan->stripe_product_id) {
                try {
                    $this->stripe->products->update($plan->stripe_product_id, ['active' => false]);
                } catch (ApiErrorException $e) {
                    Log::warning('[PLAN DESTROY] Could not archive product: ' . $e->getMessage());
                }
            }

            $plan->delete();

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Subscription plan "' . $plan->name . '" deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[PLAN DESTROY] Error: ' . $e->getMessage());

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}