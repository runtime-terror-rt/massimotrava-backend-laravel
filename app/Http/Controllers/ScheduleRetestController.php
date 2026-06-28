<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ScheduleRetest;
use App\Models\Kit; 
use App\Mail\RetestScheduledMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ScheduleRetestController extends Controller
{
    /**
     * Display a listing of the retest schedules for the Authenticated User.
     */
    public function index()
    {
        $userId = auth()->id();

        $feed = ScheduleRetest::with(['kit'])
            ->where('user_id', $userId)
            ->orderBy('retest_date', 'asc')
            ->paginate(15);
        
        $kits = \App\Models\Kit::where('user_id', $userId)->get(); 

        return view('user.retests.index', compact('feed', 'kits'));
    }

    /**
     * Store a newly scheduled retest session via User Dashboard (Blade)
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'kit_id'            => 'required|exists:kits,id',
            'original_inv_code' => 'nullable|string', 
            'retest_date'       => 'required|date|after_or_equal:today',
            'retest_time'       => 'nullable',
            'user_notes'        => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $schedule = new ScheduleRetest();
                $schedule->user_id           = auth()->id(); 
                $schedule->kit_id            = $request->kit_id;
                $schedule->original_inv_code = $request->original_inv_code;
                $schedule->retest_date       = $request->retest_date;
                $schedule->retest_time       = $request->retest_time;
                $schedule->user_notes        = $request->user_notes;
                $schedule->admin_notes       = null; 
                $schedule->status            = 1; 
                $schedule->save();

                $user = auth()->user();
                if ($user && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $schedule->load('kit');
                    Mail::to($user->email)->send(new RetestScheduledMail($schedule));
                }
            });

            return redirect()->back()->with('success', 'Your Biomarker Retest Session has been successfully scheduled!');
        } catch (\Exception $e) {
            Log::error('User Retest Store Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to schedule retest: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified retest schedule via User Dashboard Modal (Blade)
     */
    public function updateWeb(Request $request, $id)
    {
        $request->validate([
            'kit_id'            => 'required|exists:kits,id',
            'original_inv_code' => 'nullable|string', 
            'retest_date'       => 'required|date|after_or_equal:today',
            'retest_time'       => 'nullable',
            'user_notes'        => 'nullable|string|max:1000',
        ]);

        try {
            $schedule = ScheduleRetest::where('user_id', auth()->id())->findOrFail($id);
            
            DB::transaction(function () use ($request, $schedule) {
                $schedule->kit_id            = $request->kit_id;
                $schedule->original_inv_code = $request->original_inv_code;
                $schedule->retest_date       = $request->retest_date;
                $schedule->retest_time       = $request->retest_time;
                $schedule->user_notes        = $request->user_notes;
                $schedule->save();
            });

            return redirect()->back()->with('success', 'Your retest schedule has been updated successfully!');
        } catch (\Exception $e) {
            Log::error('User Retest Update Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update retest schedule: ' . $e->getMessage());
        }
    }

    /**
     * Delete/Cancel method for User context
     */
    public function destroyWeb($id)
    {
        try {
            $schedule = ScheduleRetest::where('user_id', auth()->id())->findOrFail($id);
            $schedule->delete();
            return redirect()->back()->with('success', 'Retest schedule cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error cancelling schedule: ' . $e->getMessage());
        }
    }


    /* ==================== EXISTING API FUNCTIONS ==================== */

    /**
     * Store a newly scheduled retest session and send email notice
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'kit_id'            => 'required|exists:kits,id',
            'original_inv_code' => 'nullable|string', 
            'retest_date'       => 'required|date|after_or_equal:today',
            'retest_time'       => 'nullable|date_format:H:i',
            'admin_notes'       => 'nullable|string|max:1000',
            'user_notes'        => 'nullable|string|max:1000',
        ]);

        try {
            $schedule = new ScheduleRetest();
            $schedule->user_id           = $request->user_id;
            $schedule->kit_id            = $request->kit_id;
            $schedule->original_inv_code = $request->original_inv_code;
            $schedule->retest_date       = $request->retest_date;
            $schedule->retest_time       = $request->retest_time;
            $schedule->admin_notes       = $request->admin_notes;
            $schedule->user_notes        = $request->user_notes;
            $schedule->status            = 1;

            DB::transaction(function () use ($schedule) {
                $schedule->save();
            });

            $schedule->refresh(); 
            $schedule->load(['user', 'kit']);

            $recipient = $schedule->user;
            if ($recipient && filter_var($recipient->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($recipient->email)->send(new RetestScheduledMail($schedule));
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Biomarker Retest Session scheduled & Notification Email dispatched!',
                'data'    => $schedule
            ], 201);

        } catch (\Exception $e) {
            Log::error('Retest Controller Operation Exception: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to log retest schedule tracker block: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific active schedules for authenticated user context 
     */
    public function myRetestSchedules()
    {
        $userId = auth()->id() ?? request()->user_id; 
        
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'User identity trace required.'], 401);
        }

        $schedules = ScheduleRetest::with(['kit'])
            ->where('user_id', $userId)
            ->orderBy('retest_date', 'asc')
            ->paginate(15);

        return response()->json([
            'status'  => 'success',
            'data'    => $schedules
        ], 200);
    }
}