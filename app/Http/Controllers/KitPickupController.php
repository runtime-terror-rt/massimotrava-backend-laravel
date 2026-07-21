<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\KitPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KitPickupController extends Controller
{
    
    public function schedule(Request $request, Kit $kit)
    {
        $user = auth()->user();

        if ($kit->user_id !== $user->id) {
            abort(403, 'This kit does not belong to you.');
        }

        if ($kit->status !== 'activated') {
            return back()->with('error', 'The kit must be activated before scheduling a pickup.');
        }

        if ($kit->pickup()->exists()) {
            return back()->with('error', 'A pickup request already exists for this kit.');
        }

        $validated = $request->validate([
            'preferred_date'       => 'required|date|after_or_equal:tomorrow',
            'preferred_time_slot'  => 'nullable|string|max:100',
            'pickup_address'       => 'required|string|max:500',
            'contact_phone'        => 'required|string|max:20',
        ]);

        $pickup = KitPickup::create([
            'kit_id'              => $kit->id,
            'preferred_date'      => $validated['preferred_date'],
            'preferred_time_slot' => $validated['preferred_time_slot'],
            'pickup_address'      => $validated['pickup_address'],
            'contact_phone'       => $validated['contact_phone'],
            'status'              => 'requested',
        ]);

        $kit->updateStatus('pickup_scheduled');

        Log::info('[PICKUP SCHEDULED] User ' . $user->id . ' scheduled pickup for kit ' . $kit->activation_code);

        return back()->with('success', 'Your sample pickup request has been submitted successfully. The courier will contact you soon.');
    }

    
    public function reschedule(Request $request, KitPickup $pickup)
    {
        if ($pickup->kit->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($pickup->status, ['requested'])) {
            return back()->with('error', 'Rescheduling is not possible after a courier has been assigned. Please contact support.');
        }

        $validated = $request->validate([
            'preferred_date'      => 'required|date|after_or_equal:tomorrow',
            'preferred_time_slot' => 'nullable|string|max:100',
        ]);

        $pickup->update($validated);

        return back()->with('success', 'Pickup time has been changed.');
    }

    // Admin Methods

    public function index(Request $request)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        $query = KitPickup::with(['kit.user', 'kit.userSubscription.plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pickups = $query->latest()->paginate(15);

        return view('admin.pickups.index', compact('pickups'));
    }

    
    public function assign(Request $request, KitPickup $pickup)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        $request->validate([
            'assigned_courier_name'  => 'required|string|max:255',
            'assigned_courier_phone' => 'required|string|max:20',
            'admin_notes'            => 'nullable|string',
        ]);

        try {
            $pickup->updateStatus('assigned', [
                'assigned_courier_name'  => $request->assigned_courier_name,
                'assigned_courier_phone' => $request->assigned_courier_phone,
                'assigned_by_admin_id'   => auth()->id(),
                'admin_notes'            => $request->admin_notes,
            ]);

            $pickup->kit->updateStatus('pickup_assigned');

            Log::info('[PICKUP ASSIGNED] Admin ' . auth()->id() . ' assigned courier for pickup ' . $pickup->id);

            return back()->with('success', 'Courier has been assigned successfully.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    
    public function markCollected(Request $request, KitPickup $pickup)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        try {
            $pickup->updateStatus('collected', [
                'collected_at' => now(),
            ]);

            $pickup->kit->updateStatus('sample_collected');

            return back()->with('success', 'Sample collection has been confirmed.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

   
    public function markDeliveredToLab(Request $request, KitPickup $pickup)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        if ($pickup->status !== 'collected') {
            return back()->with('error', 'Sample must be marked as collected first.');
        }

        try {
            $pickup->updateStatus('delivered_to_lab', [
                'delivered_to_lab_at' => now(),
            ]);

            $pickup->kit->updateStatus('received_at_lab');

            return back()->with('success', 'Sample has been marked as delivered to the lab.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    
    public function markFailed(Request $request, KitPickup $pickup)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        $request->validate(['failure_reason' => 'required|string']);

        try {
            $pickup->updateStatus('failed', [
                'failure_reason' => $request->failure_reason,
            ]);

            $pickup->kit->updateStatus('activated');

            return back()->with('success', 'Pickup has been marked as failed. The user can schedule again.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function markCompleted(Request $request, KitPickup $pickup)
    {
        if (!$this->checkIsAdminOrLab(auth()->user())) {
            abort(403);
        }

        if ($pickup->status !== 'delivered_to_lab') {
            return back()->with('error', 'Sample must be delivered to lab first.');
        }

        try {
            $pickup->updateStatus('completed');
            $pickup->kit->updateStatus('completed');

            return back()->with('success', 'Kit process has been completed.');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Check if user is admin or lab
     */
    private function checkIsAdminOrLab($user): bool
    {
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'lab']);
        } elseif (method_exists($user, 'roles')) {
            return $user->roles()->whereIn('name', ['admin', 'lab'])->exists();
        }
        return $user->can('manage-kits');
    }
}