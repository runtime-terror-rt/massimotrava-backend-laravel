<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'message' => 'User profile retrieved successfully',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'age' => $user->age,
                    'gender' => $user->gender,
                    'height' => $user->height,
                    'weight' => $user->weight,
                    'image' =>  $user->image ? asset('storage/' . $user->image) : null,
                ]
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user profile'
            ]);
        }
    }

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

                $path = $request->file('image')->store('profiles', 'public');
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
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }



}
