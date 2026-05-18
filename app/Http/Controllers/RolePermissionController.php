<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        // Fetch relationships safely mapping via Spatie cache layer
        $roles = Role::with('permissions')->latest()->get();
        $permissions = Permission::all();
        
        // Single row edit tracking filter matrix logic
        $selectedRole = $request->id ? Role::with('permissions')->find($request->id) : null;

        return view('admin.role-permission.index', compact('roles', 'permissions', 'selectedRole'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'id'            => 'nullable|exists:roles,id',
            'name'          => 'required|string|unique:roles,name,' . $request->id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Clean technical slug name generator structure configuration map
        $technicalSlug = strtolower(str_replace(' ', '-', $request->name));

        // 1. Persist/Sync Role Model Entity registry
        $role = Role::updateOrCreate(
            ['id' => $request->id],
            [
                'name'       => $technicalSlug,
                'guard_name' => 'web' 
            ]
        );

        // 2. Clear & Bind updated capabilities to the Spatie dynamic registry
        if ($request->has('permissions')) {
            $permissionModels = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissionModels);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.role-permission.index')
            ->with('success', $request->id ? 'Role configurations updated!' : 'New system role deployed successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Safety guard barrier to protect administrative superuser access
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'The core system Administrator role cannot be removed.');
        }

        $role->delete();
        return redirect()->route('admin.role-permission.index')->with('success', 'Role removed safely.');
    }
}