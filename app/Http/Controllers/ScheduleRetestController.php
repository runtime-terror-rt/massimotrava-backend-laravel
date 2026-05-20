<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ScheduleRetest;
use App\Mail\RetestScheduledMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ScheduleRetestController extends Controller
{
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