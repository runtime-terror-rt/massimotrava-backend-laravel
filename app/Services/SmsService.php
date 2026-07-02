<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function sendSms($to, $message)
    {
        if (!$to) return false;

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_NUMBER');

        try {
            $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post($url, [
                    'To'   => $to,
                    'From' => $from,
                    'Body' => $message, 
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error("Twilio SMS Failed: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Twilio Service Error: " . $e->getMessage());
            return false;
        }
    }
}