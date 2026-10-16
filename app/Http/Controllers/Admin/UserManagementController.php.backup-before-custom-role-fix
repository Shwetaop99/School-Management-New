<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display all user accounts.
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('login_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Active roles for filter/dropdowns
        $roles = Role::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        // Statistics
        $totalUsers = User::count();

        $activeUsers = User::where('status', 'Active')
            ->count();

        $inactiveUsers = User::where('status', 'Inactive')
            ->count();

        return view(
            'admin.settings.users.index',
            compact(
                'users',
                'roles',
                'totalUsers',
                'activeUsers',
                'inactiveUsers'
            )
        );
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        $roles = Role::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        return view(
            'admin.settings.users.create',
            compact('roles')
        );
    }

    /**
     * Store a new user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'profile_photo' => [
                'nullable',
                'string',
                'max:2048',
            ],

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

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'profile_photo' => $validated['profile_photo'] ?? null,
            'login_id' => $validated['login_id'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.settings.users.index')
            ->with(
                'success',
                'User account created successfully.'
            );
    }

    /**
     * Display a specific user account.
     */
    public function show(User $user)
    {
        $user->load('role');

        return view(
            'admin.settings.users.show',
            compact('user')
        );
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        $roles = Role::where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        return view(
            'admin.settings.users.edit',
            compact('user', 'roles')
        );
    }

    /**
     * Update an existing user account.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'profile_photo' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'login_id' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'login_id')
                    ->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        $user->name = $validated['name'];

        // Update profile photo only if a new photo was uploaded
        if (!empty($validated['profile_photo'])) {
            $user->profile_photo = $validated['profile_photo'];
        }

        $user->login_id = $validated['login_id'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->status = $validated['status'];

        // Change password only when a new password is entered
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.settings.users.index')
            ->with(
                'success',
                'User account updated successfully.'
            );
    }

    /**
     * Delete a user account.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the currently logged-in account
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        // Prevent deleting Super Admin accounts
        if ($user->hasRole('super_admin')) {
            return back()->with(
                'error',
                'Super Admin accounts cannot be deleted.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User account deleted successfully.'
        );
    }
}