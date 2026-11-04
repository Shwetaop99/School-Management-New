<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminRole;
use App\Models\SuperAdminPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    /**
     * Display all platform roles and permissions.
     */
    public function index()
    {
        $roles = SuperAdminRole::with('permissions')
            ->orderBy('id')
            ->get();

        $permissions = SuperAdminPermission::orderBy('module')
            ->orderBy('display_name')
            ->get()
            ->groupBy('module');

        return view('super-admin.roles.index', compact(
            'roles',
            'permissions'
        ));
    }

    /**
     * Create a new platform role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:super_admin_roles,name',
            ],
            'display_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:super_admin_permissions,id',
        ]);

        $role = SuperAdminRole::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
            'status' => 'Active',
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Update a platform role.
     */
    public function update(Request $request, SuperAdminRole $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('super_admin_roles', 'name')->ignore($role->id),
            ],
            'display_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:super_admin_permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete a platform role.
     */
    public function destroy(SuperAdminRole $role)
    {
        if ($role->is_system) {
            return back()->with(
                'error',
                'System roles cannot be deleted.'
            );
        }

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'This role is assigned to users and cannot be deleted.'
            );
        }

        $role->delete();

        return redirect()
            ->route('super-admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}