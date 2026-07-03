<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function edit(): JsonResponse
    {
        $settings = NotificationSetting::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'email_alerts' => true,
                'lab_kit_updates' => true,
                'weekly_analytics' => false,
            ]
        );

        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'setting_name' => 'required|string|in:email_alerts,generate_report,sms_notification,lab_kit_updates,weekly_analytics,push_notification,email_notification',
            'value' => 'required|boolean',
        ]);

        $settings = NotificationSetting::firstOrCreate(['user_id' => Auth::id()]);

        $field = $request->setting_name;
        $settings->$field = $request->value;
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification preference updated successfully!'
        ]);
    }
}