<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendOtpEmail;
use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{


    public function login(Request $request)
    {
        try
        {
            // ✅ ১. ভ্যালিডেশন
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:5',
            ], [
                'email.required' => 'Email field is required.',
                'password.min' => 'Password must be at least 5 characters.',
            ]);

            // ✅ ২. লগইন চেষ্টা (Web Session + API Token এর জন্য প্রস্তুত করা)
            // Auth::attempt ব্যবহার করলে এটি স্বয়ংক্রিয়ভাবে পাসওয়ার্ড চেক করে এবং Web Session তৈরি করে
            $remember = $request->has('remember');
            
            if (!Auth::attempt($credentials, $remember)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
                }
                return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            $user = Auth::user();

            // ✅ ৩. টোকেন জেনারেট (শুধুমাত্র API রিকোয়েস্ট বা স্পেসিফিক রিকোয়েস্টের জন্য)
            $expiresAt = $remember ? now()->addDays(30) : now()->addHours(2);
            $tokenResult = $user->createToken('auth_token_' . $user->id);
            
            // Expiry সেট করা
            $tokenResult->accessToken->expires_at = $expiresAt;
            $tokenResult->accessToken->save();

            // ✅ ৪. রেসপন্স হ্যান্ডলিং
            if ($request->expectsJson()) {
                // API এর জন্য JSON রেসপন্স
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful.',
                    'data' => [
                        'user' => [
                            'id' => $user->id,
                            'email' => $user->email,
                            'role' => $user->getRoleNames()->first() ?? null,
                        ],
                        'token' => $tokenResult->plainTextToken,
                        'token_type' => 'Bearer',
                    ]
                ], 200);
            }

            // Web এর জন্য রিডাইরেক্ট (Dashboard এ)
            return redirect()->intended('/dashboard')->with('success', 'Welcome back!');

        } 
        catch (\Illuminate\Validation\ValidationException $e) 
        {
            $firstError = collect($e->errors())->flatten()->first();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $firstError], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Login Error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
            }
            return back()->with('error', 'Something went wrong. Please try again.');
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
            if ($request->user() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            }

            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully.'
                ], 200);
            }

            return redirect('/login')->with('success', 'You have been logged out.');

        } catch (\Exception $e) {
            \Log::error('Logout Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to logout. Please try again.',
                ], 500);
            }

            return back()->with('error', 'Something went wrong during logout.');
        }
    }
}
