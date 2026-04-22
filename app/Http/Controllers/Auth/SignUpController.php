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
        // ✅ Validation with custom messages
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],

        ], [
            'name.required' => 'Name field is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already registered.'
            ], 409);
        }

        // Create user
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        // Assign role
        $user->assignRole('user');

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
            ]
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // ✅ Show first validation error as message
        $firstError = collect($e->errors())->flatten()->first();

        return response()->json([
            'success' => false,
            'message' => $firstError,
        ], 422);

    } catch (\Exception $e) {
        \Log::error('Registration Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.',
        ], 500);
    }
}

    /**
     * Verify OTP
     */


}
