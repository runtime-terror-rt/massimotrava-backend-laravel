<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;
use App\Services\FcmNotificationService;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read successfully.'
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications have been marked as read.'
        ]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.'
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user(); 
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'App FCM Token updated successfully.'
        ]);
    }

    public function sendPushAndDbNotification($userId, $title, $body, $customData = [])
    {
        $dbNotification = Notification::create([
            'user_id' => $userId,
            'title'   => $title, 
            'message' => $body, 
            'type'    => $customData['type'] ?? 'push',
            'is_read' => false,
        ]);

        $user = User::find($userId);

        if ($user && $user->fcm_token) {
            try {
                $jsonPath = storage_path('app/firebase-auth.json'); 

                $credentials = new ServiceAccountCredentials(
                    'https://www.googleapis.com/auth/cloud-platform',
                    $jsonPath
                );
                $accessToken = $credentials->fetchAuthToken()['access_token'];

                $firebaseConfig = json_decode(file_get_contents($jsonPath), true);
                $projectId = $firebaseConfig['project_id'];

                $response = Http::withToken($accessToken)
                    ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                        'message' => [
                            'token' => $user->fcm_token,
                            'notification' => [
                                'title' => $title,
                                'body'  => $body,
                            ],
                            'data' => array_merge([
                                'click_action'    => 'FLUTTER_NOTIFICATION_CLICK',
                                'notification_id' => (string) $dbNotification->id,
                            ], array_map('strval', $customData)),
                        ],
                    ]);

                if (!$response->successful()) {
                    \Log::error('FCM API Error: ' . $response->body());
                }

            } catch (\Exception $e) {
                \Log::error('FCM Native Push Failed: ' . $e->getMessage());
            }
        }

        return $dbNotification;
    }

    public function testSendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string',
            'message'    => 'required|string',
        ]);

        $this->sendPushAndDbNotification(
            $request->user_id,
            $request->title,
            $request->message,
            ['type' => 'test_alert'] 
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification stored in DB and pushed to Flutter App successfully!'
        ]);
    }
}