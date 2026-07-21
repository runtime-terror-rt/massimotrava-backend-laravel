<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\Payment;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
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

        try {
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event->data->object);
                    break;
                    
                case 'invoice.payment_succeeded':
                    $this->handleRenewalPayment($event->data->object);
                    break;
                    
                case 'invoice.payment_failed':
                    $this->handleFailedPayment($event->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    $this->handleCancellation($event->data->object);
                    break;
                    
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
            }
        } catch (\InvalidArgumentException $e) {
            Log::warning('[STRIPE WEBHOOK] Expected error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            Log::error('[STRIPE WEBHOOK] Failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle initial checkout completion
     */
    private function handleCheckoutCompleted($session)
    {
        $userId = $session->metadata->user_id;
        $planId = $session->metadata->subscription_plan_id;
        $stripeSubId = $session->subscription ?? null;

        // ✅ IDEMPOTENCY: Skip if already processed
        if ($stripeSubId) {
            $existing = UserSubscription::where('stripe_subscription_id', $stripeSubId)->first();
            if ($existing) {
                Log::info('[STRIPE WEBHOOK] Duplicate checkout, skipping: ' . $session->id);
                return;
            }
        }

        $plan = SubscriptionPlan::find($planId);
        if (!$plan) {
            Log::error('[STRIPE WEBHOOK] Plan not found: ' . $planId);
            throw new \InvalidArgumentException('Plan not found');
        }

        // Expire previous active subscriptions
        UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Calculate end date
        $endsAt = now()->addMonth();
        if (in_array(strtolower($plan->billing_cycle), ['annual', 'year'])) {
            $endsAt = now()->addYear();
        }

        // Create new subscription
        $userSubscription = UserSubscription::create([
            'user_id' => $userId,
            'subscription_plan_id' => $planId,
            'stripe_subscription_id' => $stripeSubId,
            'stripe_price_id' => $plan->stripe_price_id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
        ]);

        // Record payment
        $amount = isset($session->amount_total) ? $session->amount_total / 100 : 0;
        
        Payment::create([
            'user_id' => $userId,
            'user_subscription_id' => $userSubscription->id,
            'stripe_charge_id' => $session->payment_intent ?? $session->id,
            'amount' => $amount,
            'currency' => strtoupper($session->currency),
            'payment_status' => 'succeeded',
            'payment_method' => 'card',
        ]);

        Log::info('[STRIPE WEBHOOK] Subscription created for user ' . $userId);
    }

    /**
     * Handle recurring payment success
     */
    private function handleRenewalPayment($invoice)
    {
        $stripeSubId = $invoice->subscription;
        
        $subscription = UserSubscription::where('stripe_subscription_id', $stripeSubId)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            Log::warning('[STRIPE WEBHOOK] Subscription not found for renewal: ' . $stripeSubId);
            return;
        }

        // Extend end date from current end date
        $plan = $subscription->plan;
        $newEndsAt = $subscription->ends_at->addMonth();
        if (in_array(strtolower($plan->billing_cycle), ['annual', 'year'])) {
            $newEndsAt = $subscription->ends_at->addYear();
        }

        $subscription->update(['ends_at' => $newEndsAt]);

        // Record payment
        $amount = isset($invoice->amount_paid) ? $invoice->amount_paid / 100 : 0;
        
        Payment::create([
            'user_id' => $subscription->user_id,
            'user_subscription_id' => $subscription->id,
            'stripe_charge_id' => $invoice->payment_intent ?? $invoice->id,
            'amount' => $amount,
            'currency' => strtoupper($invoice->currency),
            'payment_status' => 'succeeded',
            'payment_method' => 'card',
        ]);

        Log::info('[STRIPE WEBHOOK] Renewal payment recorded for user ' . $subscription->user_id);
    }

    /**
     * Handle failed payment
     */
    private function handleFailedPayment($invoice)
    {
        $stripeSubId = $invoice->subscription;
        
        UserSubscription::where('stripe_subscription_id', $stripeSubId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        Log::warning('[STRIPE WEBHOOK] Payment failed for subscription: ' . $stripeSubId);
    }

    /**
     * Handle subscription cancellation
     */
    private function handleCancellation($subscription)
    {
        $stripeSubId = $subscription->id;
        
        UserSubscription::where('stripe_subscription_id', $stripeSubId)
            ->whereIn('status', ['active', 'trialing'])
            ->update(['status' => 'cancelled']);

        Log::info('[STRIPE WEBHOOK] Subscription cancelled: ' . $stripeSubId);
    }

    /**
     * Handle subscription updates (plan changes, etc.)
     */
    private function handleSubscriptionUpdated($subscription)
    {
        $stripeSubId = $subscription->id;
        
        $userSubscription = UserSubscription::where('stripe_subscription_id', $stripeSubId)->first();
        
        if (!$userSubscription) {
            Log::warning('[STRIPE WEBHOOK] Subscription not found for update: ' . $stripeSubId);
            return;
        }

        // Update status if changed
        $stripeStatus = $subscription->status; // active, canceled, past_due, unpaid, etc.
        $mappedStatus = match($stripeStatus) {
            'active' => 'active',
            'canceled' => 'cancelled',
            'past_due' => 'expired',
            'unpaid' => 'expired',
            default => $userSubscription->status,
        };

        if ($mappedStatus !== $userSubscription->status) {
            $userSubscription->update(['status' => $mappedStatus]);
            Log::info('[STRIPE WEBHOOK] Subscription ' . $stripeSubId . ' status updated to ' . $mappedStatus);
        }
    }
}