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
}
