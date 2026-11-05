<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SuperAdminRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Only platform staff accounts are managed here.
     *
     * The primary Super Admin is identified by the existing
     * super_admin role and a NULL super_admin_role_id.
     */
    private function staffQuery()
    {
        return User::whereNotNull('super_admin_role_id')
            ->whereHas('role', function ($query) {
                $query->where('name', 'super_admin');
            });
    }

    /**
     * Display platform staff.
     */
    public function index(Request $request)
    {
        $query = $this->staffQuery()
            ->with('superAdminRole');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('login_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('super_admin_role_id')) {
            $query->where(
                'super_admin_role_id',
                $request->super_admin_role_id
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $roles = SuperAdminRole::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        $totalUsers = $this->staffQuery()->count();

        $activeUsers = $this->staffQuery()
            ->where('status', 'Active')
            ->count();

        $inactiveUsers = $this->staffQuery()
            ->where('status', 'Inactive')
            ->count();

        return view('super-admin.users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'activeUsers',
            'inactiveUsers'
        ));
    }

    /**
     * Show the create staff form.
     */
    public function create()
    {
        $roles = SuperAdminRole::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        return view('super-admin.users.create', compact('roles'));
    }

    /**
     * Create a platform staff account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'login_id' => [
                'required',
                'string',
                'max:100',
                'unique:users,login_id',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'super_admin_role_id' => [
                'required',
                'integer',
                Rule::exists('super_admin_roles', 'id')
                    ->where('status', 'Active'),
            ],
            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],
        ]);

        // Use the existing super_admin role only as an
        // account-type marker. Platform permissions come
        // exclusively from SuperAdminRole.
        $superAdminMarker = \App\Models\Role::where(
            'name',
            'super_admin'
        )->firstOrFail();

        User::create([
            'name' => $validated['name'],
            'login_id' => $validated['login_id'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $superAdminMarker->id,
            'super_admin_role_id' => $validated['super_admin_role_id'],
            'school_id' => null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Platform staff account created successfully.');
    }

    /**
     * Display a platform staff member.
     */
    public function show(User $user)
    {
        $this->ensurePlatformStaff($user);

        $user->load('superAdminRole');

        return view('super-admin.users.show', compact('user'));
    }

    /**
     * Show the edit form.
     */
    public function edit(User $user)
    {
        $this->ensurePlatformStaff($user);

        $roles = SuperAdminRole::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        return view('super-admin.users.edit', compact(
            'user',
            'roles'
        ));
    }

    /**
     * Update platform staff.
     */
    public function update(Request $request, User $user)
    {
        $this->ensurePlatformStaff($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'login_id' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'login_id')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'super_admin_role_id' => [
                'required',
                'integer',
                Rule::exists('super_admin_roles', 'id')
                    ->where('status', 'Active'),
            ],
            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],
        ]);

        $user->name = $validated['name'];
        $user->login_id = $validated['login_id'];
        $user->email = $validated['email'];
        $user->super_admin_role_id = $validated['super_admin_role_id'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Platform staff updated successfully.');
    }

    /**
     * Delete platform staff.
     */
    public function destroy(User $user)
    {
        $this->ensurePlatformStaff($user);

        if ($user->id === auth('super_admin')->id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        $user->delete();

        return redirect()
            ->route('super-admin.users.index')
            ->with('success', 'Platform staff deleted successfully.');
    }

    /**
     * Ensure the target is a platform staff account.
     */
    private function ensurePlatformStaff(User $user): void
    {
        abort_unless(
            $user->super_admin_role_id !== null &&
            $user->hasRole('super_admin'),
            404,
            'Platform staff account not found.'
        );
    }
}
