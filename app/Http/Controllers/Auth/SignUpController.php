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
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    Password::min(8)->letters()->mixedCase()->numbers()->symbols()
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            ], [
                'name.required' => 'Name field is required.',
                'email.required' => 'Email field is required.',
                'email.unique' => 'Email is already registered.',
                'password.required' => 'Password is required.',
            ]);

            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('user');

            if (!$request->expectsJson()) {
                Auth::login($user);
            }

            if ($request->expectsJson()) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful.',
                    'data' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->getRoleNames()->first(),
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ]
                ], 201);
            }

            return redirect('/admin/dashboard')->with('success', 'Registration successful! Welcome.');

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
     * Verify OTP
     */


}
