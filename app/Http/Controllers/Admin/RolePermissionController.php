<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolePermissionController extends Controller
{
    /**
     * Display role permissions management interface.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('module');

        return view('admin.role-permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Show permissions for a specific role.
     */
    public function show(Role $role)
    {
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();
        $permissions = Permission::all()->groupBy('module');

        return view('admin.role-permissions.show', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Update permissions for a role.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Sync permissions (removes old ones and adds new ones)
        $permissionIds = $validated['permissions'] ?? [];
        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.role-permissions.show', $role)
            ->with('success', "Permissions updated for role '{$role->name}'");
    }

    /**
     * Get permissions for a specific module (AJAX).
     */
    public function getModulePermissions(string $module)
    {
        $permissions = Permission::where('module', $module)->get();

        return response()->json($permissions);
    }

    /**
     * Get role permissions in JSON format (AJAX).
     */
    public function getRolePermissions(Role $role)
    {
        $permissions = $role->permissions()->pluck('permissions.id')->toArray();

        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Bulk update permissions for multiple roles.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        $roles = Role::whereIn('id', $validated['roles'])->get();

        foreach ($roles as $role) {
            $role->permissions()->sync($permissionIds);
        }

        return redirect()->route('admin.role-permissions.index')
            ->with('success', "Permissions updated for " . count($roles) . " role(s)");
    }

    /**
     * Reset a role to default permissions.
     */
    public function reset(Role $role)
    {
        // Define default permissions for each role
        $defaults = [
            'admin' => Permission::pluck('id')->toArray(),
            'data_entry' => Permission::whereIn('module', ['dashboard', 'patients', 'countries', 'states', 'districts', 'talukas'])
                ->orWhere('name', 'view_dashboard')
                ->pluck('id')
                ->toArray(),
            'user' => Permission::where('name', 'view_dashboard')->pluck('id')->toArray(),
        ];

        if (!isset($defaults[$role->name])) {
            return redirect()->back()->with('error', "No default permissions defined for role '{$role->name}'");
        }

        $role->permissions()->sync($defaults[$role->name]);

        return redirect()->route('admin.role-permissions.show', $role)
            ->with('success', "Permissions for role '{$role->name}' have been reset to defaults");
    }
}
