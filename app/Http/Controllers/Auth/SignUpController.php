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
                'role' => 'nullable|string|exists:roles,name',
            ], [
                'name.required' => 'Name field is required.',
                'email.required' => 'Email field is required.',
                'email.unique' => 'Email is already registered.',
                'password.required' => 'Password is required.',
                'role.exists' => 'The selected system role is invalid.',
            ]);

            // Handle Image Upload if present
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profiles', 'public');
            }

            // Create User Entity
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imagePath, // Storing the handled image if column exists
            ]);

            // Dynamic Role Assignment Layer
            // Request-e valid role thakle sheti nibe, na thakle fallback 'user' role dibe
            $assignedRole = $request->input('role', 'user');
            $user->assignRole($assignedRole);

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
                        'role' => $user->getRoleNames()->first(), // Returns the dynamically assigned role
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ]
                ], 201);
            }

            // Web Redirect Matrix Strategy
            // Role wise redirect standard: 'admin' ba 'lab' hole sequential panel array processing jabe
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome to Admin Control Center.');
            } elseif ($user->hasRole('lab')) {
                return redirect('/lab/dashboard')->with('success', 'Laboratory Environment Initialized.');
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
