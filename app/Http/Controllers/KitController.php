<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\KitPickup;
use App\Models\Notification;
use App\Models\PickupRequest;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KitController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdminOrLab = $this->checkIsAdminOrLab($user);

        if ($isAdminOrLab) {
            $query = Kit::with(['user:id,name,email', 'userSubscription.plan', 'pickup'])->latest();
        } else {
            $query = Kit::with('pickup')->where('user_id', $user->id)->latest();
        }

        $kits = $query->paginate(10);

        $activeSubscriptions = $isAdminOrLab
            ? UserSubscription::with(['user', 'plan'])
                ->withCount(['kits as used_kits_count' => function ($q) {
                    $q->where('status', '!=', 'cancelled');
                }])
                ->where('status', 'active')
                ->latest()
                ->get()
            : collect();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $kits]);
        }

        return view('admin.kits.index', [
            'kits'                => $kits,
            'isAdmin'             => $isAdminOrLab,
            'activeSubscriptions' => $activeSubscriptions,
        ]);
    }

    /**
     * [ADMIN] Show a subscription's kit history (used by admin.kits.show).
     */
    public function show(Request $request, $subscriptionId)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            abort(403);
        }

        $subscription = UserSubscription::with(['user', 'plan', 'kits' => function ($q) {
            $q->latest();
        }])->findOrFail($subscriptionId);

        return view('admin.kits.show', compact('subscription'));
    }

    /**
     * [ADMIN] Dispatch a new kit for a user based on their active subscription.
     * activation_code MUST be supplied by the admin (scanned/typed from the
     * physical manufacturer kit box) — it is never auto-generated here.
     */
    public function dispatchKit(Request $request)
    {
        $user = $request->user();
        if (!$this->checkIsAdminOrLab($user)) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $request->validate([
            'user_subscription_id' => 'required|exists:user_subscriptions,id',
            'activation_code'      => 'required|string|max:100|unique:kits,activation_code',
            'courier_name'         => 'nullable|string|max:255',
            'admin_notes'          => 'nullable|string',
        ]);

        $subscription = UserSubscription::with('plan')->findOrFail($request->user_subscription_id);

        if ($subscription->status !== 'active') {
            return $this->responseHandler($request, 'This subscription is not active.', null, false, 422);
        }

        $limit = $subscription->plan->kit_limit ?? null;
        if (!is_null($limit)) {
            $used = Kit::where('user_subscription_id', $subscription->id)
                ->where('status', '!=', 'cancelled')
                ->count();
            if ($used >= $limit) {
                return $this->responseHandler($request, 'The annual kit limit for this plan has been reached.', null, false, 422);
            }
        }

        try {
            $invCode = $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10));
            $kit = Kit::create([
                'user_id'              => $subscription->user_id,
                'user_subscription_id' => $subscription->id,
                'added_by_admin_id'    => $user->id,
                'inv_code'             => $invCode,
                'activation_code'      => $request->activation_code,
                'status'               => 'processing',
                'courier_name'         => $request->courier_name,
                'admin_notes'          => $request->admin_notes,
            ]);

            Notification::create([
                'user_id' => $subscription->user_id,
                'type'    => 'kit_status',
                'title'   => 'Kit Dispatched',
                'message' => 'Your kit is being processed and will be shipped via courier soon.',
                'link'    => route('user.kits.index'),
                'is_read' => false,
            ]);

            return $this->responseHandler($request, 'Kit dispatch started successfully.', $kit->activation_code);

        } catch (\Exception $e) {
            Log::error('Kit Dispatch Failed: ' . $e->getMessage());
            return $this->responseHandler($request, 'Failed to dispatch kit. Please try again.', null, false, 500);
        }
    }

    /**
     * [ADMIN] Update dispatch status. NOTE: "activated" is intentionally
     * excluded here — activation must go through activateKit(), which is
     * the only path that validates ownership and records inv_code.
     */
    public function updateDispatchStatus(Request $request, $id)
    {
        $user = $request->user();
        if (!$this->checkIsAdminOrLab($user)) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $request->validate([
            'status'          => 'required|in:processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'courier_name'    => 'nullable|string|max:255',
        ]);

        $kit = Kit::findOrFail($id);

        try {
            $extraData = [
                'tracking_number' => $request->tracking_number ?? $kit->tracking_number,
                'courier_name'    => $request->courier_name ?? $kit->courier_name,
            ];

            $kit->updateStatus($request->status, $extraData);

            return $this->responseHandler($request, 'Kit dispatch status updated.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    /**
     * [USER] Activate their kit using the activation code (typed or QR-scanned).
     */
    public function activateKit(Request $request, FcmNotificationService $fcmService)
    {
        $request->validate([
            'activation_code' => 'required|string'
        ]);

        $userId = auth()->id();
        $kit = Kit::where('activation_code', $request->activation_code)->first();

        if (!$kit) {
            $message = 'No kit found for this activation code.';
            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => $message], 404)
                : back()->with('error', $message);
        }

        if ($kit->status === 'activated') {
            $message = ($kit->user_id === $userId)
                ? 'You have already activated this kit!'
                : 'This activation code has already been used by another account.';

            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => $message], 400)
                : back()->with('error', $message);
        }

        if ($kit->user_id && $kit->user_id !== $userId) {
            $message = 'This activation code is reserved for another account.';
            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => $message], 403)
                : back()->with('error', $message);
        }

        try {
            $invCode = $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10));
            $dbNotification = null;
            $notificationMsg = 'Your test kit (' . $kit->activation_code . ') has been activated successfully.';

            DB::transaction(function () use (&$kit, &$dbNotification, $notificationMsg, $userId, $invCode) {
                if (!$kit->canTransitionTo('activated')) {
                    throw new \InvalidArgumentException("Cannot activate kit with status '{$kit->status}'");
                }

                $kit->update([
                    'user_id'      => $userId,
                    'status'       => 'activated',
                    'inv_code'     => $invCode,
                    'activated_at' => now(),
                ]);

                $dbNotification = Notification::create([
                    'user_id' => $userId,
                    'type'    => 'kit_status',
                    'title'   => 'Kit Activated',
                    'message' => $notificationMsg,
                    'link'    => route('user.kits.index'),
                    'is_read' => false,
                ]);
            });

            if ($dbNotification) {
                $fcmService->sendPush(
                    $userId,
                    'Kit Activated',
                    $notificationMsg,
                    [
                        'type'            => 'kit_status',
                        'inv_code'        => $invCode,
                        'notification_id' => (string) $dbNotification->id
                    ]
                );
            }

            return $this->responseHandler($request, 'Kit activated successfully!', $kit->inv_code);

        } catch (\Exception $e) {
            Log::error('Kit Activation Failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to activate kit. Please try again later.'
                ], 500);
            }

            return back()->withInput()->with('error', 'Something went wrong while activating the kit!');
        }
    }

    /**
     * [USER] Schedule a sample pickup for an activated kit.
     */
    public function schedulePickup(Request $request, $id)
    {
        $userId = auth()->id();
        $kit = Kit::findOrFail($id);

        if ($kit->user_id !== $userId) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        if ($kit->status !== 'activated') {
            return $this->responseHandler($request, 'You must activate the kit before scheduling a pickup.', null, false, 422);
        }

        if ($kit->pickup()->exists()) {
            return $this->responseHandler($request, 'A pickup request already exists for this kit.', null, false, 422);
        }

        $validated = $request->validate([
            'preferred_date'      => 'required|date|after_or_equal:tomorrow',
            'preferred_time_slot' => 'nullable|string|max:100',
            'pickup_address'      => 'required|string|max:500',
            'contact_phone'       => 'required|string|max:20',
        ]);

        try {
            DB::transaction(function () use ($kit, $validated) {
                KitPickup::create(array_merge($validated, ['kit_id' => $kit->id, 'status' => 'requested']));
                $kit->updateStatus('pickup_scheduled');
            });

            return $this->responseHandler($request, 'Sample pickup request submitted successfully.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    /**
     * [ADMIN] List of pickup requests.
     */
    public function pickupIndex(Request $request)
    {
        $user = $request->user();
        
        if (!$this->checkIsAdminOrLab($user)) {
            abort(403);
        }
        $query = PickupRequest::with(['user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pickups = $query->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $pickups]);
        }

        return view('admin.pickups.index', compact('pickups'));
    }
    
    /**
     * [ADMIN] Assign a courier to a pickup request.
     */
    public function assignPickup(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $request->validate([
            'assigned_courier_name'  => 'required|string|max:255',
            'assigned_courier_phone' => 'required|string|max:20',
            'admin_notes'            => 'nullable|string',
        ]);

        $pickup = KitPickup::with('kit')->findOrFail($id);

        try {
            DB::transaction(function () use ($pickup, $request) {
                $pickup->update([
                    'assigned_courier_name'  => $request->assigned_courier_name,
                    'assigned_courier_phone' => $request->assigned_courier_phone,
                    'assigned_by_admin_id'   => auth()->id(),
                    'admin_notes'            => $request->admin_notes,
                    'status'                 => 'assigned',
                ]);
                $pickup->kit->updateStatus('pickup_assigned');
            });

            return $this->responseHandler($request, 'Courier assigned successfully.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    /**
     * [ADMIN] Mark sample as collected by the courier.
     */
    public function markPickupCollected(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $pickup = PickupRequest::findOrFail($id);

        try {
            DB::transaction(function () use ($pickup) {
                $pickup->update([
                    'status' => 'collected', 
                    'collected_at' => now()
                ]);
                
                if ($pickup->kit) {
                    $pickup->kit->updateStatus('sample_collected');
                }
            });

            return redirect()->back()->with('success', 'Sample collection confirmed.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * [ADMIN] Mark sample as delivered to lab.
     */
    public function markPickupDeliveredToLab(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $pickup = PickupRequest::find($id);

        if (!$pickup) {
            return redirect()->back()->with('error', 'Pickup request not found with ID: ' . $id);
        }

        if ($pickup->status !== 'collected') {
            return redirect()->back()->with('error', 'Sample must be marked as collected first.');
        }

        try {
            DB::transaction(function () use ($pickup) {
                $pickup->update([
                    'status' => 'delivered_to_lab', 
                    'delivered_to_lab_at' => now()
                ]);

                if ($pickup->kit) {
                    $pickup->kit->updateStatus('received_at_lab');
                }
            });

            return redirect()->back()->with('success', 'Sample marked as delivered to lab.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * [ADMIN] Mark a pickup attempt as failed.
     */
    public function markPickupFailed(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $request->validate(['failure_reason' => 'required|string']);

        $pickup = KitPickup::with('kit')->findOrFail($id);

        try {
            DB::transaction(function () use ($pickup, $request) {
                $pickup->update(['status' => 'failed', 'failure_reason' => $request->failure_reason]);
                $pickup->kit->updateStatus('activated'); // allow user to reschedule
            });

            return $this->responseHandler($request, 'Pickup marked as failed.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    public function myKits(Request $request)
    {
        $kits = Kit::with(['user:id,name,email', 'pickup'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $kits]);
        }

        return view('user.kits.my_kits', compact('kits'));
    }

    public function destroy(Request $request, $id)
    {
        $kit = Kit::find($id);

        if (!$kit) {
            return $this->responseHandler($request, 'Kit not found', null, false, 404);
        }

        // Use the same authorization helper as the rest of the controller
        // instead of a raw ->role check, which may not exist depending on
        // how roles are implemented (Spatie hasRole(), custom roles(), etc.)
        if (!$this->checkIsAdminOrLab($request->user()) && $kit->user_id !== auth()->id()) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $kit->delete();

        return $this->responseHandler($request, 'Kit deleted successfully');
    }

    /**
     * Returns a user's activatable kits (used e.g. by a biomarker-report
     * creation form). Restricted so a caller can only fetch their own kits
     * unless they are admin/lab.
    */

    public function getUserKits(Request $request)
    {
        $requestedUserId = $request->user_id;
        $authUserId = auth()->id();

        if ((int) $requestedUserId !== (int) $authUserId && !$this->checkIsAdminOrLab($request->user())) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $kits = Kit::where('user_id', $requestedUserId)
            ->where('status', 'activated')
            ->whereDoesntHave('biomarkerReports')
            ->get(['id', 'activation_code', 'inv_code']);

        return response()->json($kits);
    }

    public function getSubcategories(Request $request)
    {
        $subcategories = \App\Models\BiomarkerSubcategory::where('biomarker_category_id', $request->category_id)
            ->get(['id', 'title', 'unit']);

        return response()->json($subcategories);
    }

    private function checkIsAdminOrLab($user): bool
    {
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'lab']);
        } elseif (method_exists($user, 'roles')) {
            return $user->roles()->whereIn('name', ['admin', 'lab'])->exists();
        }
        return $user->can('manage-kits');
    }

    private function responseHandler($request, $message, $invCode = null, $success = true, $status = 200)
    {
        if ($request->expectsJson()) {
            $res = ['status' => $success ? 'success' : 'error', 'message' => $message];
            if ($invCode) $res['inv_code'] = $invCode;
            return response()->json($res, $status);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }

    /**
     * [LAB] Mark kit as processing at lab
     */
    public function markProcessingAtLab(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $kit = Kit::findOrFail($id);

        try {
            $kit->updateStatus('processing_at_lab');
            return $this->responseHandler($request, 'Kit marked as processing at lab.');
        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    /**
     * [LAB] Mark results as ready
     */
    public function markResultsReady(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $kit = Kit::findOrFail($id);

        try {
            $kit->updateStatus('results_ready');

            Notification::create([
                'user_id' => $kit->user_id,
                'type'    => 'kit_status',
                'title'   => 'Results Ready',
                'message' => 'Your biomarker test results are ready for viewing.',
                'link'    => route('user.kits.index'),
                'is_read' => false,
            ]);

            return $this->responseHandler($request, 'Results marked as ready.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }

    /**
     * [ADMIN/LAB] Mark kit as completed
     */
    public function markKitCompleted(Request $request, $id)
    {
        if (!$this->checkIsAdminOrLab($request->user())) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $kit = Kit::with('pickup')->findOrFail($id);

        try {
            DB::transaction(function () use ($kit) {
                if ($kit->pickup) {
                    $kit->pickup->updateStatus('completed');
                }
                $kit->updateStatus('completed');
            });

            return $this->responseHandler($request, 'Kit marked as completed.');

        } catch (\InvalidArgumentException $e) {
            return $this->responseHandler($request, $e->getMessage(), null, false, 422);
        }
    }
}