<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleRetest;
use App\Mail\RetestReminderMail;
use App\Mail\RetestTodayAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendRetestReminders extends Command
{
    protected $signature = 'app:send-retest-reminders';
    protected $description = 'Send auto-reminders (1 day before) and final alerts (on the test day) at 9:00 AM';

    public function handle()
    {
        $this->info('Initializing unified retest reminder scheduler...');

        $todayDate    = Carbon::today()->toDateString();
        $tomorrowDate = Carbon::tomorrow()->toDateString();

        //  PART A: JADER TEST AGAMIKAL (1 Day Before Reminder)
        
        $tomorrowSchedules = ScheduleRetest::with(['user', 'kit'])
            ->where('retest_date', $tomorrowDate)
            ->where('status', 1)
            ->get();

        foreach ($tomorrowSchedules as $schedule) {
            if ($schedule->user && filter_var($schedule->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($schedule->user->email)->send(new RetestReminderMail($schedule));
                    $this->info("Tomorrow Reminder sent to: {$schedule->user->email}");
                } catch (\Exception $e) {
                    Log::error("Tomorrow Reminder Error for {$schedule->user->email}: " . $e->getMessage());
                }
            }
        }

        //  PART B: JADER TEST AAJKE (Same Day Final Alert)

        $todaySchedules = ScheduleRetest::with(['user', 'kit'])
            ->where('retest_date', $todayDate)
            ->where('status', 1)
            ->get();

        foreach ($todaySchedules as $schedule) {
            if ($schedule->user && filter_var($schedule->user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($schedule->user->email)->send(new RetestTodayAlertMail($schedule));
                    $this->info("Today Final Alert sent to: {$schedule->user->email}");
                } catch (\Exception $e) {
                    Log::error("Today Alert Error for {$schedule->user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('All scheduled notifications processed successfully.');
        return Command::SUCCESS;
    }
}