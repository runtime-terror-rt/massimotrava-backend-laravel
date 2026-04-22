<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendOtpEmail;
use Illuminate\Support\Carbon;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // ✅ Validation
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:5',
            ], [
                'email.required' => 'Email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Password field is required.',
                'password.string' => 'Password must be a string.',
                'password.min' => 'Password must be at least 5 characters.',
            ]);

            // ✅ Find user by email
            $user = User::where('email', $request->email)->first();

            // ✅ Check credentials
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            // ✅ Decide expiry based on "remember me"
            $expiresAt = $request->remember
                ? now()->addDays(30)   // long-lived token
                : now()->addHours(2);  // short-lived token

            // ✅ Generate token
            $token = $user->createToken('auth_token_' . $user->id);

            // Save expiry in DB (personal_access_tokens table has `expires_at` if you add it)
            $token->accessToken->expires_at = $expiresAt;
            $token->accessToken->save();



            // ✅ Return success response
            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->getRoleNames()->first() ?? null,

                    ],

                    'token' => $token->plainTextToken,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Validation error: first message only
            $firstError = collect($e->errors())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ], [
                'email.required' => 'Email field is required.',
                'email.email' => 'Please enter a valid email address.',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found.'
                ], 404);
            }


            $otp = random_int(1000, 9999);

            $user->update([
                'otp' => $otp,
                'otp_expire_at' => Carbon::now()->addMinutes(5),
            ]);

            SendOtpEmail::dispatch($user->id, 'verify', $otp);

            return response()->json([
                'success' => true,
                'message' => 'A new OTP has been sent to your email.',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Resend OTP Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.',
            ], 500);
        }
    }


    /**
     * Logout user (current token)
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Logout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout. Please try again.',
            ], 500);
        }
    }
}
