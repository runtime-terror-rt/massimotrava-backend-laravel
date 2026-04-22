<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
