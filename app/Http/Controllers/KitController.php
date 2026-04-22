<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KitController extends Controller
{
    public function index()
    {
        $kits = Kit::get();
        
        return response()->json([
            'status' => 'success',
            'data' => $kits
        ]);
    }
    public function activateKit(Request $request)
    {
        $kit = Kit::where('activation_code', $request->activation_code)->first();

        if (!$kit) {
            $kit = Kit::create([
                'user_id' => auth()->id(),
                'activation_code' => $request->activation_code,
                'inv_code' => 'INV-' . strtoupper(Str::random(10)),
                'status' => 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New kit registered and activated!',
                'inv_code' => $kit->inv_code
            ]);
        }

        if ($kit->status == 1) {
            return response()->json(['message' => 'Already active'], 400);
        }

        $kit->update([
            'user_id' => auth()->id(),
            'status' => 1,
            'inv_code' => $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10)),
        ]);

        
        return response()->json([
            'status' => 'success',
            'message' => 'Kit activated successfully!',
            'inv_code' => $kit->inv_code
        ]);
    }
    
    // public function activateKit(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'activation_code' => 'required|string|exists:kits,activation_code',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid Code.'], 422);
    //     }

    //     $kit = Kit::where('activation_code', $request->activation_code)->first();

    //     if ($kit->status == 1) {
    //         return response()->json(['status' => 'error', 'message' => 'Already activated.'], 400);
    //     }

    //     $kit->update([
    //         'user_id' => auth()->id(),
    //         'status' => 1,
    //         'inv_code' => $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10)),
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Kit activated successfully!',
    //         'inv_code' => $kit->inv_code
    //     ]);
    // }

    public function myKits()
    {
        $kits = Kit::with('user:id,name,email')
                ->where('user_id', auth()->id())
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kits
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kit = Kit::find($id);

        if (!$kit) {
            return response()->json(['message' => 'Kit not found'], 404);
        }

        if ($kit->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $kit->delete();

        return response()->json(['message' => 'Kit deleted successfully']);
    }
}
