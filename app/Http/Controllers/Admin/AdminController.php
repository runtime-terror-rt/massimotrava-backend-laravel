<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            if (!$request->user()->hasRole('admin')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $users = User::select(
                    'users.id', 
                    'users.name', 
                    'users.email', 
                    'users.created_at as joined_date', 
                    'users.image',
                    DB::raw('CASE WHEN users.status = 1 THEN "Active" ELSE "Inactive" END as account_status')
                )
                ->latest('users.created_at')
                ->get();

            return response()->json([
                'success' => true,
                'users_table' => $users
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Internal Server Error', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserById(Request $request, $id)
    {
        try {
            if (!$request->user()->hasRole('admin')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $user->image = $user->image && $user->image 
                ? asset('storage/' . $user->image) 
                : asset('assets/default-avatar.png');

            $user->account_status_text = $user->status ? 'Active' : 'Inactive';

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'member_since' => $user->created_at->format('Y-m-d'),
                    'image' => $user->image,
                    'account_status' => $user->account_status_text
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Internal Server Error', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            if (!$request->user()->hasRole('admin')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function toggleActiveUser($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->status = !$user->status;
            $user->save();
            $user->account_status_text = $user->status ? 'Active' : 'Inactive';
            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' =>  $user->account_status_text
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status. Error: '.$e->getMessage()
            ], 500);
        }
    }

    public function storeLabUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'lab_id'   => 'required|exists:labs,id',
            'gender'   => 'nullable|string',
            'age'      => 'nullable|integer',
        ]);

        try {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'password'          => \Hash::make($request->password),
                'lab_id'            => $request->lab_id,
                'age'               => $request->age,
                'gender'            => $request->gender,
                'status'            => true,
                'email_verified_at' => now(), 
            ]);

            $user->assignRole('lab'); 

            $message = 'Lab user created successfully for ' . $user->name;

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'success',
                    'message' => $message,
                    'data'    => $user->load('lab') 
                ], 201);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to create lab user: ' . $e->getMessage());
        }
    }

    public function labUsersDestroy(Request $request, $id)
    {
        try {
            if (!$request->user()->hasRole('admin')) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }
                return back()->with('error', 'Unauthorized action.');
            }

            $user = User::find($id);
            if (!$user) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['success' => false, 'message' => 'User not found'], 404);
                }
                return back()->with('error', 'User not found.');
            }

            $user->delete();

            $message = 'Lab user deleted successfully.';

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['success' => true, 'message' => $message], 200);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete lab user: ' . $e->getMessage());
        }
    }
}
