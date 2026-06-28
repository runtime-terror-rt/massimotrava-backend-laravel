<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Content;
use App\Models\Faq;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Models\SubscriptionPlan;

class UserController extends Controller
{
    public function userDashboard()
    {
        return view('user.dashboard');
    }

    public function userHome()
    {
        $campaigns = Campaign::where('status', 'active')->orderBy('id', 'desc')->get();
        $faqs = Faq::where('is_active', true)->orderBy('id', 'desc')->get();
        $contents = Content::where('status', 'published')
                        ->latest('published_at')
                        ->get();

        $data = SubscriptionPlan::where('status', true)->latest()->get();

        return view('user.home', compact('contents', 'faqs', 'campaigns', 'data'));
    }
    

    public function helthInsight()
    {
        return view('user.health-insight', [
            'longevityScore'   => 84,
            'previousScore'    => 74,
            'scoreImprovement' => 12,
            'scoreSince'       => 'Oct 2025',
            'testDate'         => 'Mar 25, 2026',
            'kitNumber'        => '23281',
            'updatedAgo'       => '2 days ago',
        ]);
    }

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
        $labs = Lab::select('id', 'name')->where('status', true)->get();
        
        $labUsers = User::role('lab')->latest()->with('lab')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $labUsers
            ], 200);
        }

        return view('admin.laboratorian.index', compact('labUsers', 'labs'));
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
                'email'  => 'required|email|unique:users,email,' . $user->id,
                'phone'  => 'nullable|string|max:20',
                'age'    => 'nullable|integer|min:1',
                'gender' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            // ── Required fields ──
            $user->name  = $request->name;
            $user->email = $request->email;

            $user->phone  = $request->phone  ?? $user->phone;
            $user->age    = $request->age    ?? $user->age;
            $user->gender = $request->gender ?? $user->gender;
            $user->height = $request->height ?? $user->height;
            $user->weight = $request->weight ?? $user->weight;

            // ── Image Upload ──
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                $file     = $request->file('image');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('profiles', $filename, 'public');

                $user->image = $path;
            }

            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully.',
                    'data'    => [
                        'user'      => $user,
                        'image_url' => $user->image ? asset('storage/' . $user->image) : null,
                    ],
                ], 200);
            }

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Profile Update Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong.',
                ], 500);
            }
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
