<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use Illuminate\Http\Request;

// ── ADMIN CONTROLLER ──────────────────────────────────────────────────────
class AdminPickupRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PickupRequest::with('user')->latest();
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->whereHas('user', fn($q) =>
                $q->where('name','like',"%{$request->search}%")
                  ->orWhere('email','like',"%{$request->search}%")
            )->orWhere('kit_name','like',"%{$request->search}%");
        }

        $pickupRequests = $query->get();
        $stats = [
            'total'     => PickupRequest::count(),
            'pending'   => PickupRequest::where('status','pending')->count(),
            'scheduled' => PickupRequest::where('status','scheduled')->count(),
            'collected' => PickupRequest::where('status','collected')->count(),
            'cancelled' => PickupRequest::where('status','cancelled')->count(),
        ];
        return view('admin.courier.pickup-requests', compact('pickupRequests','stats'));
    }

    public function schedule(Request $request, int $id)
    {
        $request->validate([
            'pickup_date' => 'required|date',
            'time_slot'   => 'required|string|max:50',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        PickupRequest::findOrFail($id)->update([
            'pickup_date' => $request->pickup_date,
            'time_slot'   => $request->time_slot,
            'admin_notes' => $request->admin_notes,
            'status'      => 'scheduled',
        ]);

        return redirect()->route('admin.pickup.index')
            ->with('success', 'Pickup scheduled successfully!');
    }

    public function collect(int $id)
    {
        PickupRequest::findOrFail($id)->update([
            'status'       => 'collected',
            'collected_at' => now(),
        ]);

        return redirect()->route('admin.pickup.index')
            ->with('success', 'Marked as collected.');
    }

    public function cancel(int $id)
    {
        PickupRequest::findOrFail($id)->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('admin.pickup.index')
            ->with('success', 'Request cancelled.');
    }

    public function show(int $id)
    {
        $req = PickupRequest::with('user')->findOrFail($id);
        return view('admin.courier.pickup-detail', compact('req'));
    }
}
