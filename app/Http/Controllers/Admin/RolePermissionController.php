<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display all roles.
     */
    public function index()
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->orderBy('display_name')
            ->get();

        return view('admin.settings.roles.index', compact('roles'));
    }

    /**
     * Show create role page.
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('display_name')
            ->get()
            ->groupBy('module');

        return view('admin.settings.roles.create', compact('permissions'));
    }

    /**
     * Store a new role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:roles,name',
            ],
            'display_name' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                'in:Active,Inactive',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $role->permissions()->sync(
            $validated['permissions'] ?? []
        );

        return redirect()
            ->route('admin.settings.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show edit role page.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('display_name')
            ->get()
            ->groupBy('module');

        $role->load('permissions');

        return view(
            'admin.settings.roles.edit',
            compact('role', 'permissions')
        );
    }

    /**
     * Update an existing role.
     */
   public function update(Request $request, Role $role)
{
    $validated = $request->validate([
        'display_name' => [
            'required',
            'string',
            'max:100',
        ],

        'description' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'in:Active,Inactive',
        ],

        'permissions' => [
            'nullable',
            'array',
        ],

        'permissions.*' => [
            'exists:permissions,id',
        ],
    ]);

    $role->update([
        'display_name' => $validated['display_name'],
        'description' => $validated['description'] ?? null,
        'status' => $validated['status'],
    ]);

    $role->permissions()->sync(
        $validated['permissions'] ?? []
    );

    return redirect()
        ->route('admin.settings.roles.index')
        ->with('success', 'Role updated successfully.');
}

    /**
     * Delete a role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->with(
                'error',
                'Super Admin role cannot be deleted.'
            );
        }

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'This role cannot be deleted because users are assigned to it.'
            );
        }

        $role->delete();

        return back()->with(
            'success',
            'Role deleted successfully.'
        );
    }
}