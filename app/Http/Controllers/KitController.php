<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\Notification;
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

    public function activateKit(Request $request, FcmNotificationService $fcmService)
    {
        $request->validate([
            'activation_code' => 'required|string'
        ]);

        $userId = auth()->id();
        $kit = Kit::where('activation_code', $request->activation_code)->first();

        if ($kit && $kit->status == 1) {
            $message = ($kit->user_id === $userId) 
                ? 'You have already activated this kit!' 
                : 'This activation code has already been used by another account.';

            return $request->expectsJson() 
                ? response()->json(['status' => 'error', 'message' => $message], 400) 
                : back()->with('error', $message);
        }

        if ($kit && $kit->user_id && $kit->user_id !== $userId) {
            $message = 'This activation code is reserved for another account.';
            return $request->expectsJson() 
                ? response()->json(['status' => 'error', 'message' => $message], 403) 
                : back()->with('error', $message);
        }

        try {
            $isNewKit = !$kit;
            $invCode = $kit->inv_code ?? 'INV-' . strtoupper(Str::random(10));
            
            $dbNotification = null;
            $notificationMsg = '';

            DB::transaction(function () use (&$kit, &$dbNotification, &$notificationMsg, $request, $userId, $invCode, $isNewKit) {
                if ($isNewKit) {
                    $kit = Kit::create([
                        'user_id'         => $userId,
                        'activation_code' => $request->activation_code,
                        'inv_code'        => $invCode,
                        'status'          => 1,
                    ]);
                    $notificationMsg = 'Your test kit (' . $kit->activation_code . ') has been registered and activated successfully.';
                } else {
                    $kit->update([
                        'user_id'  => $userId,
                        'status'   => 1,
                        'inv_code' => $invCode,
                    ]);
                    $notificationMsg = 'Your test kit (' . $kit->activation_code . ') has been activated successfully.';
                }

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

            $successMessage = $isNewKit ? 'New kit registered and activated!' : 'Kit activated successfully!';
            return $this->responseHandler($request, $successMessage, $kit->inv_code);

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