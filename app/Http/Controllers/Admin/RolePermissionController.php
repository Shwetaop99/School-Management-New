<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RolePermissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->orderBy('display_name')
            ->get();

        return view('admin.settings.roles.index', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $permissions = $this->grantablePermissions();

        return view('admin.settings.roles.create', compact('permissions'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            // UPDATE 1: simple role names only (small letters, numbers, _)
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('roles', 'name'),
            ],
            'display_name'  => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:500'],
            'status'        => ['required', Rule::in(['Active', 'Inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        // UPDATE 2: cannot give permissions you do not have yourself
        $this->assertCanGrant($permissionIds);

        $role = Role::create([
            'name'         => $validated['name'],
            'display_name' => trim(strip_tags($validated['display_name'])),
            'description'  => isset($validated['description'])
                ? trim(strip_tags($validated['description']))
                : null,
            'status'       => $validated['status'],
        ]);

        $role->permissions()->sync($permissionIds);

        // UPDATE 3: write it in the activity log
        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->withProperties([
                'permissions' => $role->permissions()->pluck('name')->all(),
            ])
            ->log('Role created');

        return redirect()
            ->route('admin.settings.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Role $role)
    {
        if ($blocked = $this->blockedMessage($role)) {
            return redirect()
                ->route('admin.settings.roles.index')
                ->with('error', $blocked);
        }

        $permissions = $this->grantablePermissions();

        $role->load('permissions');

        return view('admin.settings.roles.edit', compact('role', 'permissions'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Role $role)
    {
        // UPDATE 4: Super Admin role and stronger roles cannot be edited
        if ($blocked = $this->blockedMessage($role)) {
            return redirect()
                ->route('admin.settings.roles.index')
                ->with('error', $blocked);
        }

        $validated = $request->validate([
            'display_name'  => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:500'],
            'status'        => ['required', Rule::in(['Active', 'Inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        // UPDATE 5: a role that still has users cannot be made Inactive
        if ($validated['status'] === 'Inactive' && $role->users()->exists()) {
            throw ValidationException::withMessages([
                'status' => 'This role still has users. Move them to another role first.',
            ]);
        }

        $permissionIds = $validated['permissions'] ?? [];

        $this->assertCanGrant($permissionIds);

        $role->update([
            'display_name' => trim(strip_tags($validated['display_name'])),
            'description'  => isset($validated['description'])
                ? trim(strip_tags($validated['description']))
                : null,
            'status'       => $validated['status'],
        ]);

        $before = $role->permissions()->pluck('name')->all();

        $role->permissions()->sync($permissionIds);

        $after = $role->permissions()->pluck('name')->all();

        // UPDATE 3: write exactly what was added and removed
        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->withProperties([
                'added'   => array_values(array_diff($after, $before)),
                'removed' => array_values(array_diff($before, $after)),
            ])
            ->log('Role updated');

        return redirect()
            ->route('admin.settings.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        if ($blocked = $this->blockedMessage($role)) {
            return back()->with('error', $blocked);
        }

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'This role cannot be deleted because users are assigned to it.'
            );
        }

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->withProperties(['role' => $role->name])
            ->log('Role deleted');

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function isSuperAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }

    private function myPermissionIds(): Collection
    {
        $role = auth()->user()?->role;

        return $role
            ? $role->permissions()->pluck('permissions.id')
            : collect();
    }

    // Returns a message when this role must not be changed, otherwise null
    private function blockedMessage(Role $role): ?string
    {
        if ($this->isSuperAdmin()) {
            // even a Super Admin does not edit the Super Admin role here
            return $role->name === 'super_admin'
                ? 'The Super Admin role cannot be edited.'
                : null;
        }

        if ($role->name === 'super_admin') {
            return 'The Super Admin role cannot be edited.';
        }

        // Others can only manage roles with the same or fewer permissions
        $roleIds = $role->permissions()->pluck('permissions.id');

        if ($roleIds->diff($this->myPermissionIds())->isNotEmpty()) {
            return 'You cannot manage a role with more permissions than your own.';
        }

        return null;
    }

    private function assertCanGrant(array $permissionIds): void
    {
        if ($this->isSuperAdmin()) {
            return;
        }

        $notMine = collect($permissionIds)
            ->map(fn ($id) => (int) $id)
            ->diff($this->myPermissionIds());

        if ($notMine->isNotEmpty()) {
            throw ValidationException::withMessages([
                'permissions' => 'You cannot give permissions that you do not have yourself.',
            ]);
        }
    }

    // Permissions shown on the create and edit pages
    private function grantablePermissions()
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('display_name')
            ->get();

        if (!$this->isSuperAdmin()) {
            $mine = $this->myPermissionIds();

            $permissions = $permissions
                ->filter(fn ($permission) => $mine->contains($permission->id))
                ->values();
        }

        return $permissions->groupBy('module');
    }
    /**
 * Bulk delete multiple records.
 */

}