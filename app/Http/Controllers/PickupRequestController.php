<?php
namespace App\Http\Controllers;

use App\Models\PickupRequest;
use Illuminate\Http\Request;

class PickupRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PickupRequest::where('user_id', auth()->id())->latest();
        if ($request->status) $query->where('status', $request->status);

        $pickupRequests = $query->get();
        $stats = [
            'total'     => PickupRequest::where('user_id', auth()->id())->count(),
            'pending'   => PickupRequest::where('user_id', auth()->id())->where('status','pending')->count(),
            'scheduled' => PickupRequest::where('user_id', auth()->id())->where('status','scheduled')->count(),
            'collected' => PickupRequest::where('user_id', auth()->id())->where('status','collected')->count(),
        ];
        return view('user.pickup.index', compact('pickupRequests','stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kit_name'    => 'required|string|max:100',
            'pickup_date' => 'required|date|after:today',
            'time_slot'   => 'required|string|max:50',
            'address'     => 'required|string|max:255',
            'notes'       => 'nullable|string|max:500',
        ]);

        $icons = [
            'Longevity Panel' => '🧬', 'Cardio Panel' => '🫀',
            'Vitamin Panel' => '☀️', 'Hormone Panel' => '⚗️',
            'Full Body Panel' => '🩺',
        ];

        PickupRequest::create([
            'user_id'     => auth()->id(),
            'kit_name'    => $request->kit_name,
            'kit_icon'    => $icons[$request->kit_name] ?? '🧬',
            'pickup_date' => $request->pickup_date,
            'time_slot'   => $request->time_slot,
            'address'     => $request->address,
            'notes'       => $request->notes,
            'status'      => 'pending',
        ]);

        return redirect()->route('user.pickup.index')
            ->with('success', 'Pickup request submitted successfully!');
    }

    public function reschedule(Request $request, int $id)
    {
        $request->validate([
            'pickup_date' => 'required|date|after:today',
            'time_slot'   => 'required|string|max:50',
        ]);

        $req = PickupRequest::where('user_id', auth()->id())->findOrFail($id);
        $req->update([
            'pickup_date' => $request->pickup_date,
            'time_slot'   => $request->time_slot,
            'status'      => 'scheduled',
        ]);

        return redirect()->route('user.pickup.index')
            ->with('success', 'Pickup rescheduled successfully!');
    }

    public function cancel(int $id)
    {
        $req = PickupRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending','scheduled'])
            ->findOrFail($id);
        $req->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return redirect()->route('user.pickup.index')
            ->with('success', 'Pickup request cancelled.');
    }

    public function show(int $id)
    {
        $req = PickupRequest::where('user_id', auth()->id())->findOrFail($id);
        return view('user.pickup.pickup-detail', compact('req'));
    }
}



