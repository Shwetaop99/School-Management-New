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
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('roles', 'name'),
            ],
            'display_name'   => ['required', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:500'],
            'status'         => ['required', Rule::in(['Active', 'Inactive'])],
            'permissions'    => ['nullable', 'array'],
            'permissions.*'  => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

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
        if ($blocked = $this->blockedMessage($role)) {
            return redirect()
                ->route('admin.settings.roles.index')
                ->with('error', $blocked);
        }

        $validated = $request->validate([
            'display_name'   => ['required', 'string', 'max:100'],
            'description'    => ['nullable', 'string', 'max:500'],
            'status'         => ['required', Rule::in(['Active', 'Inactive'])],
            'permissions'    => ['nullable', 'array'],
            'permissions.*'  => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

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

    /**
     * Modules in which the current user has at least one permission.
     * Non-Super Admin users can manage permissions only within these modules.
     */
    private function grantableModuleNames(): Collection
    {
        if ($this->isSuperAdmin()) {
            return Permission::query()
                ->distinct()
                ->pluck('module');
        }

        return Permission::whereIn('id', $this->myPermissionIds())
            ->whereNotNull('module')
            ->pluck('module')
            ->unique()
            ->values();
    }

    /**
     * Returns a message when this role must not be changed, otherwise null.
     */
    private function blockedMessage(Role $role): ?string
    {
        if ($role->name === 'super_admin') {
            return 'The Super Admin role cannot be edited.';
        }

        if ($this->isSuperAdmin()) {
            return null;
        }

        // A user may manage roles only when all their permission modules
        // are within the modules that the current user is allowed to manage.
        $roleModules = $role->permissions()
            ->whereNotNull('permissions.module')
            ->pluck('permissions.module')
            ->unique();

        $allowedModules = $this->grantableModuleNames();

        if ($roleModules->diff($allowedModules)->isNotEmpty()) {
            return 'You cannot manage a role that has permissions outside the modules available to your role.';
        }

        return null;
    }

    /**
     * Prevent assigning permissions outside the modules available to this user.
     */
    private function assertCanGrant(array $permissionIds): void
    {
        if ($this->isSuperAdmin() || empty($permissionIds)) {
            return;
        }

        $allowedModules = $this->grantableModuleNames();

        $hasUnauthorizedPermission = Permission::whereIn('id', $permissionIds)
            ->whereNotIn('module', $allowedModules)
            ->exists();

        if ($hasUnauthorizedPermission) {
            throw ValidationException::withMessages([
                'permissions' => 'You cannot assign permissions outside the modules available to your role.',
            ]);
        }
    }

    /**
     * Permissions shown on the create and edit pages.
     * If a user has access to a module, all permissions in that module are shown.
     */
    private function grantablePermissions()
    {
        $query = Permission::query()
            ->orderBy('module')
            ->orderBy('display_name');

        if (!$this->isSuperAdmin()) {
            $allowedModules = $this->grantableModuleNames();

            $query->whereIn('module', $allowedModules);
        }

        return $query->get()->groupBy('module');
    }
}
