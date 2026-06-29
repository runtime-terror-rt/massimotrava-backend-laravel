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
        try {
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string|min:5',
            ], [
                'email.required' => 'Email field is required.',
                'password.min'   => 'Password must be at least 5 characters.',
            ]);

            $remember = $request->has('remember');

            if (!Auth::attempt($credentials, $remember)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
                }
                return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            $user = Auth::user();
            $role = $user->getRoleNames()->first() ?? 'user';

            // ── Determine redirect based on role ──
            $redirectUrl = match(true) {
                in_array($role, ['admin', 'lab']) => '/admin/dashboard',
                default                           => '/user/action-item',
            };

            $expiresAt   = $remember ? now()->addDays(30) : now()->addHours(2);
            $tokenResult = $user->createToken('auth_token_' . $user->id);
            $tokenResult->accessToken->expires_at = $expiresAt;
            $tokenResult->accessToken->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful.',
                    'data'    => [
                        'user' => [
                            'id'    => $user->id,
                            'email' => $user->email,
                            'role'  => $role,
                        ],
                        'token'        => $tokenResult->plainTextToken,
                        'token_type'   => 'Bearer',
                        'redirect_url' => $redirectUrl,   // ← frontend uses this
                    ],
                ], 200);
            }

            return redirect($redirectUrl)->with('success', 'Welcome back!');

        } catch (\Illuminate\Validation\ValidationException $e) {
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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current Password Password not correct');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password Successfully Updated');
    }
}
