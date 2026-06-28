<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use App\Jobs\SendOtpEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SignUpController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    Password::min(8)->letters()->mixedCase()->numbers()->symbols()
                ],
                'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'role'     => 'nullable|string|exists:roles,name',
            ], [
                'name.required'     => 'Name field is required.',
                'email.required'    => 'Email field is required.',
                'email.unique'      => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'role.exists'       => 'The selected role is invalid.',
            ]);

            // Image Upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profiles', 'public');
            }

            // Generate OTP
            $otp       = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $otpExpiry = Carbon::now()->addMinutes(10);

            // Create User
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'image'          => $imagePath,
                'otp'            => $otp,
                'otp_expire_at'  => $otpExpiry,
                'terms_accepted' => $request->boolean('terms_accepted'),
                'status'         => true,
            ]);

            // Role Assignment — request role or default 'user'
            $assignedRole = $request->input('role', 'user');
            $user->assignRole($assignedRole);

            // ── API Response ──────────────────────────────────────────────
            if ($request->expectsJson()) {
                // Send OTP via job
                dispatch(new SendOtpEmail($user->id, 'verify', (int) $otp));

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful. Please verify your email with the OTP sent.',
                    'data'    => [
                        'id'         => $user->id,
                        'email'      => $user->email,
                        'role'       => $user->getRoleNames()->first(),
                        'token'      => $token,
                        'token_type' => 'Bearer',
                    ]
                ], 201);
            }

            // ── Web Flow ──────────────────────────────────────────────────
            // Send OTP for email verification
            dispatch(new SendOtpEmail($user->id, 'verify', (int) $otp));

            // Store user id in session for OTP verification step
            session(['otp_user_id' => $user->id]);

            // Redirect to OTP verification page
            return redirect()->route('otp.verify.form')
                ->with('success', 'Account created! Please check your email for the verification code.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $firstError,
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Registration Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again.',
                ], 500);
            }

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Show OTP verification form (Web)
     */
    public function showOtpForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('register');
        }

        return view('user.auth.otp-verify');
    }

    /**
     * Verify OTP (Web + API)
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|size:5',
            ]);

            // Web: user id from session | API: from request body
            $userId = $request->expectsJson()
                ? $request->input('user_id')
                : session('otp_user_id');

            if (!$userId) {
                $msg = 'Session expired. Please register again.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->route('register')->with('error', $msg);
            }

            $user = User::findOrFail($userId);

            // OTP expired check
            if (Carbon::now()->isAfter($user->otp_expire_at)) {
                $msg = 'OTP has expired. Please request a new one.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->with('error', $msg);
            }

            // OTP mismatch check
            if ($user->otp !== $request->otp) {
                $msg = 'Invalid OTP. Please try again.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->withErrors(['otp' => $msg]);
            }

            // Mark email verified, clear OTP
            $user->update([
                'email_verified_at' => Carbon::now(),
                'otp'               => null,
                'otp_expire_at'     => null,
            ]);

            // ── API Response ──────────────────────────────────────────────
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully.',
                    'data'    => [
                        'id'    => $user->id,
                        'email' => $user->email,
                        'role'  => $user->getRoleNames()->first(),
                    ]
                ], 200);
            }

            // ── Web Flow: login & role-based redirect ─────────────────────
            session()->forget('otp_user_id');

            Auth::login($user);

            return $this->redirectByRole($user);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first(),
                ], 422);
            }
            return back()->withErrors($e->errors());

        } catch (\Exception $e) {
            \Log::error('OTP Verify Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
            }
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        try {
            $userId = $request->expectsJson()
                ? $request->input('user_id')
                : session('otp_user_id');

            if (!$userId) {
                $msg = 'Session expired. Please register again.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->route('register')->with('error', $msg);
            }

            $user      = User::findOrFail($userId);
            $otp       = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $otpExpiry = Carbon::now()->addMinutes(10);

            $user->update([
                'otp'            => $otp,
                'otp_expire_at'  => $otpExpiry,
            ]);

            dispatch(new SendOtpEmail($user->id, 'verify', (int) $otp));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email.',
                ], 200);
            }

            return back()->with('success', 'A new OTP has been sent to your email.');

        } catch (\Exception $e) {
            \Log::error('Resend OTP Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
            }
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Role-based redirect after login/verify
     */
    private function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome to Admin Control Center.');
        }

        if ($user->hasRole('lab')) {
            return redirect('/lab/dashboard')
                ->with('success', 'Laboratory Environment Initialized.');
        }

        if ($user->hasRole('inspector')) {
            return redirect('/inspector/dashboard')
                ->with('success', 'Welcome, Inspector!');
        }

        // Default: regular user
        return redirect('/user/dashboard')
            ->with('success', 'Welcome to Vyralabs!');
    }

    /**
     * Profile Update
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name'   => 'required|string|max:255',
                'phone'  => 'nullable|string|max:20',
                'age'    => 'nullable|integer|min:1',
                'gender' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $user->name   = $request->name;
            $user->phone  = $request->phone;
            $user->age    = $request->age;
            $user->gender = $request->gender;
            $user->height = $request->height;
            $user->weight = $request->weight;

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $path       = $request->file('image')->store('profiles', 'public');
                $user->image = $path;
            }

            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully.',
                    'data'    => $user
                ], 200);
            }

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}