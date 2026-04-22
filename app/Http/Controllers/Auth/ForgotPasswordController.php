<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Jobs\SendOtpEmail;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        try {
            // ✅ Validation with custom message
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


            // ✅ Generate 5-digit OTP
            $otp = random_int(1000, 9999);

            $user->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5),
            ]);

            SendOtpEmail::dispatch($user->id, 'forgot', $otp);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email for password reset.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Show first validation error as message
            $firstError = collect($e->errors())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Forgot Password Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            // ✅ Validation with custom messages
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|digits:4',
            ], [
                'email.required' => 'Email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'otp.required' => 'OTP field is required.',
                'otp.digits' => 'OTP must be 4 digits.',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found.'
                ], 404);
            }

            if (!$user->otp || !$user->otp_expire_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'No OTP found. Please request a new OTP.'
                ], 400);
            }

            $otpExpire = Carbon::parse($user->otp_expire_at);

            if ($otpExpire->lt(Carbon::now())) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ], 400);
            }

            if ((string)$user->otp !== (string)$request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ], 400);
            }

            // OTP is valid → verify email and clear OTP
            $user->update([
                'email_verified_at' => $user->email_verified_at ?? Carbon::now(),
                'otp' => null,
                'otp_expire_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Show first validation error as message
            $firstError = collect($e->errors())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Verify OTP Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed. Please try again.',
            ], 500);
        }
    }
    /**
     * Reset Password (No OTP)
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'new_password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->letters()->mixedCase()->numbers()->symbols()
                ],
            ], [
                'email.required' => 'Email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.exists' => 'Email not found.',
                'new_password.required' => 'New password is required.',
                'new_password.confirmed' => 'New password confirmation does not match.',
            ]);

            $user = User::where('email', $request->email)->first();

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully.',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError,
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Reset Password Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password. Please try again.',
            ], 500);
        }
    }


}
