<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.profile.users_profile', compact('user'));
    }

    public function getLabUsers(Request $request)
    {
        $labs = Lab::get('id', 'name');
        $labUsers = User::role('lab')->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $labUsers
            ], 200);
        }

        return view('admin.laboratorian.index', compact('labUsers','labs'));
    }

    public function getUsers(Request $request)
    {
        $users = User::role('user')->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        }

        return view('admin.users.index', compact('users'));
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (Auth::id() == $user->id) {
                return $this->responseHandler($request, false, "You cannot delete yourself!", 403);
            }

            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }

            $user->delete();

            return $this->responseHandler($request, true, "User deleted successfully.");

        } catch (\Exception $e) {
            return $this->responseHandler($request, false, "Failed to delete user.", 500);
        }
    }

    private function responseHandler($request, $success, $message, $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $status);
        }
        return back()->with($success ? 'success' : 'error', $message);
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
