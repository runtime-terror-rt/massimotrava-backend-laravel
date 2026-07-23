<?php
namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\Kit;
use Illuminate\Http\Request;

class PickupRequestController extends Controller
{
    public function adminIndex(Request $request)
    {
        $query = PickupRequest::with(['user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pickups = $query->paginate(10);

        return view('admin.pickup.index', compact('pickups'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kit_id'      => 'required|integer|exists:kits,id',
            'pickup_date' => 'required|date|after:today',
            'time_slot'   => 'required|string|max:50',
            'address'     => 'required|string|max:255',
            'notes'       => 'nullable|string|max:500',
        ]);

        $kit = Kit::where('id', $request->kit_id)
            ->where('user_id', auth()->id())
            ->where('status', 'activated')
            ->first();

        if (!$kit) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid or unavailable kit selected.');
        }

        if ($kit->pickupRequest()->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A pickup request already exists for this kit.');
        }

        PickupRequest::create([
            'user_id'     => auth()->id(),
            'kit_id'      => $kit->id,
            'kit_name'    => $kit->activation_code,
            'kit_icon'    => '🧬',
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
            ->whereIn('status', ['pending', 'scheduled'])
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