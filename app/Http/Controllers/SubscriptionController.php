<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SubscriptionController extends Controller
{
    
    public function createCheckoutSession($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to subscribe.');
        }

        $stripePriceId = $plan->stripe_price_id; 
        if (!$stripePriceId) {
            return redirect()->back()->with('error', 'Stripe Price ID is missing in your database for this plan.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $billingCycle = strtolower($plan->billing_cycle);
            $isSubscription = in_array($billingCycle, ['monthly', 'annual', 'year', 'month', 'weekly']);
            $mode = $isSubscription ? 'subscription' : 'payment';

            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $stripePriceId,
                    'quantity' => 1,
                ]],
                'mode' => $mode,
                'success_url' => route('user.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.subscription.cancel'),
                'customer_email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id
                ]
            ]);

            return redirect()->away($checkoutSession->url);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }

    
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret', env('STRIPE_SECRET')));
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
        try {
            $session = $event->data->object;

            $userId = $session->metadata->user_id;
            $planId = $session->metadata->subscription_plan_id;
            $stripeSubId = $session->subscription ?? null;

            $plan = SubscriptionPlan::find($planId);
            if (!$plan) {
                Log::error('[STRIPE WEBHOOK] Plan not found: ' . $planId);
                return response()->json(['error' => 'Plan not found'], 404);
            }

            UserSubscription::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $endsAt = now()->addMonth();
            if (in_array(strtolower($plan->billing_cycle), ['annual', 'year'])) {
                $endsAt = now()->addYear();
            }

            $userSubscription = UserSubscription::create([
                'user_id' => $userId,
                'subscription_plan_id' => $planId,
                'stripe_subscription_id' => $stripeSubId,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => $endsAt,
            ]);

            Payment::create([
                'user_id' => $userId,
                'user_subscription_id' => $userSubscription->id,
                'stripe_charge_id' => $session->payment_intent ?? $session->id,
                'amount' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency),
                'payment_status' => 'succeeded',
                'payment_method' => 'card',
            ]);

            Log::info('[STRIPE WEBHOOK] Subscription created for user ' . $userId);

        } catch (\Throwable $e) {
            Log::error('[STRIPE WEBHOOK] Failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

        return response()->json(['status' => 'success'], 200);
    }
}