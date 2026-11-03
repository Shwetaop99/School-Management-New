<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // UPDATE 1: check the filters first
        $request->validate([
            'search'  => ['nullable', 'string', 'max:100'],
            'role_id' => ['nullable', 'integer'],
            'status'  => ['nullable', Rule::in(['Active', 'Inactive'])],
        ]);

        $query = User::with('role');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('login_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        $totalUsers    = User::count();
        $activeUsers   = User::where('status', 'Active')->count();
        $inactiveUsers = User::where('status', 'Inactive')->count();

        return view(
            'admin.settings.users.index',
            compact('users', 'roles', 'totalUsers', 'activeUsers', 'inactiveUsers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        // UPDATE 2: only roles the logged-in person is allowed to give
        $roles = $this->assignableRoles();

        return view('admin.settings.users.create', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // UPDATE 2: cannot give a role stronger than your own
        $this->assertCanAssignRole((int) $validated['role_id']);

        User::create([
            'name'          => trim($validated['name']),
            'profile_photo' => $validated['profile_photo'] ?? null,
            'login_id'      => $validated['login_id'],
            'email'         => $validated['email'],
            'password'      => $validated['password'],
            'role_id'       => $validated['role_id'],
            'status'        => $validated['status'],
        ]);

        return redirect()
            ->route('admin.settings.users.index')
            ->with('success', 'User account created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW / EDIT
    |--------------------------------------------------------------------------
    */

    public function show(User $user)
    {
        // UPDATE 3: only a stronger or equal manager can open this account
        $this->guardTarget($user);

        $user->load('role');

        return view('admin.settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->guardTarget($user);

        $roles = $this->assignableRoles();

        return view('admin.settings.users.edit', compact('user', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, User $user)
    {
        $this->guardTarget($user);

        $validated = $request->validate($this->rules($user));

        $newRoleId = (int) $validated['role_id'];
        $roleChanged = $newRoleId !== (int) $user->role_id;
        $statusChanged = $validated['status'] !== $user->status;

        // UPDATE 4: nobody can change their own role or status
        if ($user->id === auth()->id() && ($roleChanged || $statusChanged)) {
            throw ValidationException::withMessages([
                'role_id' => 'You cannot change your own role or status.',
            ]);
        }

        // Only check the new role when it really changes
        if ($roleChanged) {
            $this->assertCanAssignRole($newRoleId);
        }

        // UPDATE 5: the last active Super Admin must stay a Super Admin
        if ($this->isSuperAdmin($user) && $user->status === 'Active') {

            $newRole = Role::find($newRoleId);

            $staysSuperAdmin =
                $newRole &&
                $newRole->name === 'super_admin' &&
                $validated['status'] === 'Active';

            if (!$staysSuperAdmin && $this->activeSuperAdminCount() <= 1) {
                throw ValidationException::withMessages([
                    'role_id' => 'This is the last active Super Admin. It cannot be disabled or changed.',
                ]);
            }
        }

        $user->name     = trim($validated['name']);
        $user->login_id = $validated['login_id'];
        $user->email    = $validated['email'];
        $user->role_id  = $newRoleId;
        $user->status   = $validated['status'];

        // Change profile photo only when a new one is sent
        if (!empty($validated['profile_photo'])) {
            $user->profile_photo = $validated['profile_photo'];
        }

        $passwordChanged = !empty($validated['password']);

        if ($passwordChanged) {
            $user->password = $validated['password'];
        }

        $user->save();

        // UPDATE 6: password changes are written in the activity log
        if ($passwordChanged) {
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->log('Password changed by administrator');
        }

        // UPDATE 7: disabled users and changed passwords log the person out
        if ($passwordChanged || $statusChanged || $roleChanged) {
            $this->endSessions($user);
        }

        return redirect()
            ->route('admin.settings.users.index')
            ->with('success', 'User account updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        $this->guardTarget($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($this->isSuperAdmin($user)) {
            return back()->with('error', 'Super Admin accounts cannot be deleted.');
        }

        $this->endSessions($user);

        $user->delete();

        return back()->with('success', 'User account deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function rules(?User $user = null): array
    {
        return [
            // letters, marks, spaces, dot, apostrophe and dash only (no < > )
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{M}\s.\'-]+$/u',
            ],

            // a simple storage path such as profile_photos/abc.jpg
            'profile_photo' => [
                'nullable',
                'string',
                'max:2048',
                'regex:/^(?!.*\.\.)[A-Za-z0-9_\-\/]+\.(jpe?g|png|webp)$/i',
            ],

            'login_id' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9._@-]+$/',
                $user
                    ? Rule::unique('users', 'login_id')->ignore($user->id)
                    : Rule::unique('users', 'login_id'),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                $user
                    ? Rule::unique('users', 'email')->ignore($user->id)
                    : Rule::unique('users', 'email'),
            ],

            // stronger password: 10+ characters with letters and numbers
            'password' => [
                $user ? 'nullable' : 'required',
                'confirmed',
                Password::min(10)->letters()->numbers(),
            ],

            // role must exist and be Active
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where('status', 'Active'),
            ],

            'status' => ['required', Rule::in(['Active', 'Inactive'])],
        ];
    }

    private function isSuperAdmin(?User $user): bool
    {
        return $user && $user->hasRole('super_admin');
    }

    private function permissionIds(?Role $role): Collection
    {
        return $role
            ? $role->permissions()->pluck('permissions.id')
            : collect();
    }

    // Can the logged-in person manage someone who has this role?
    // Super Admin: yes. Others: only the same or fewer permissions than their own.
    private function canManageRole(?Role $role): bool
    {
        $me = auth()->user();

        if ($this->isSuperAdmin($me)) {
            return true;
        }

        if ($role && $role->name === 'super_admin') {
            return false;
        }

        return $this->permissionIds($role)
            ->diff($this->permissionIds($me->role))
            ->isEmpty();
    }

    private function guardTarget(User $target): void
    {
        abort_unless(
            $this->canManageRole($target->role),
            403,
            'You cannot manage an account with more permissions than your own.'
        );
    }

    private function assertCanAssignRole(int $roleId): void
    {
        $role = Role::find($roleId);

        if (!$this->canManageRole($role)) {
            throw ValidationException::withMessages([
                'role_id' => 'You cannot give a role with more permissions than your own.',
            ]);
        }
    }

    // Roles shown in the dropdown
    private function assignableRoles()
    {
        return Role::where('status', 'Active')
            ->orderBy('display_name')
            ->get()
            ->filter(fn ($role) => $this->canManageRole($role))
            ->values();
    }

    private function activeSuperAdminCount(): int
    {
        return User::where('status', 'Active')
            ->whereHas('role', function ($query) {
                $query->where('name', 'super_admin');
            })
            ->count();
    }

    // Works when sessions are stored in the database
    private function endSessions(User $user): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        try {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->delete();
        } catch (\Throwable $e) {
            // never stop the update because of this
        }
    }
    /**
 * Bulk delete multiple records.
 */

}