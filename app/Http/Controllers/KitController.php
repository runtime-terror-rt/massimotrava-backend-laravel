<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KitController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $isAdminOrLab = false;

        if (method_exists($user, 'hasRole')) {
            $isAdminOrLab = $user->hasRole(['admin', 'lab']);
        } else if (method_exists($user, 'roles')) {
            $isAdminOrLab = $user->roles()->whereIn('name', ['admin', 'lab'])->exists();
        } else {
            $isAdminOrLab = $user->can('manage-kits');
        }

        if ($isAdminOrLab) {
            $query = Kit::with('user:id,name,email')->latest();
        } else {
            $query = Kit::where('user_id', $user->id)->latest();
        }

        $kits = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'data' => $kits]);
        }

        return view('admin.kits.index', [
            'kits' => $kits,
            'isAdmin' => $isAdminOrLab
        ]);
    }

    public function activateKit(Request $request)
    {
        $request->validate([
            'activation_code' => 'required|string'
        ]);

        $kit = Kit::where('activation_code', $request->activation_code)->first();

        if (!$kit) {
            $kit = Kit::create([
                'user_id' => auth()->id(),
                'activation_code' => $request->activation_code,
                'inv_code' => 'INV-' . strtoupper(Str::random(10)),
                'status' => 1,
            ]);

            return $this->responseHandler($request, 'New kit registered and activated!', $kit->inv_code);
        }

        if ($kit->status == 1) {
            return $request->expectsJson() 
                ? response()->json(['message' => 'Already active'], 400) 
                : back()->with('error', 'This kit is already active!');
        }

        $kit->update([
            'user_id' => auth()->id(),
            'status' => 1,
            'inv_code' => $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10)),
        ]);

        return $this->responseHandler($request, 'Kit activated successfully!', $kit->inv_code);
    }
   
    public function myKits(Request $request)
    {
        $kits = Kit::with('user:id,name,email')
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

        if (Auth::user()->role !== 'admin' && $kit->user_id !== auth()->id()) {
            return $this->responseHandler($request, 'Unauthorized', null, false, 403);
        }

        $kit->delete();

        return $this->responseHandler($request, 'Kit deleted successfully');
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

    public function getUserKits(Request $request)
    {
        $userId = $request->user_id;
        $kits = Kit::where('user_id', $userId)
            ->where('status', 1)
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
}