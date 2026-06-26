<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class SubscriptionController extends Controller
{
    public function createCheckoutSession($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = auth()->user();

        $features = json_decode($plan->features, true);
        $stripePriceId = $features['stripe_price_id'] ?? null; 

        if (!$stripePriceId) {
            return redirect()->back()->with('error', 'Stripe Price ID not configured for this plan.');
        }

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $stripePriceId,
                'quantity' => 1,
            ]],
            'mode' => $plan->billing_cycle === 'monthly' || $plan->billing_cycle === 'annual' ? 'subscription' : 'payment',
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscription.cancel'),
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id
            ]
        ]);

        return redirect()->away($checkoutSession->url);
    }


    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
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
            $session = $event->data->object;
            
            $userId = $session->metadata->user_id;
            $planId = $session->metadata->subscription_plan_id;
            $stripeSubId = $session->subscription ?? null;

            \App\Models\UserSubscription::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $userSubscription = \App\Models\UserSubscription::create([
                'user_id' => $userId,
                'subscription_plan_id' => $planId,
                'stripe_subscription_id' => $stripeSubId,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(), 
            ]);

            \App\Models\Payment::create([
                'user_id' => $userId,
                'user_subscription_id' => $userSubscription->id,
                'stripe_charge_id' => $session->payment_intent ?? $session->id,
                'amount' => $session->amount_total / 100, 
                'currency' => strtoupper($session->currency),
                'payment_status' => 'succeeded',
                'payment_method' => 'card',
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }
}