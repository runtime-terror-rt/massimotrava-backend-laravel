<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmNotificationService
{
    
    public function sendPush($userId, $title, $body, $customData = [])
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            return false;
        }

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
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ], array_map('strval', $customData)),
                    ],
                ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('FCM Native Push Service Failed: ' . $e->getMessage());
            return false;
        }
    }
}