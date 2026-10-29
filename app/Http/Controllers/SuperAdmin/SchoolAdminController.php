<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolAdminController extends Controller
{
    public function create()
    {
        $schools = School::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'school_code']);

        return view('super-admin.schools.admin-create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => [
                'required',
                'integer',
                Rule::exists('schools', 'id')
                    ->where('status', 'active'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'login_id' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:users,login_id',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $adminRole = Role::where('name', 'admin')
            ->where('status', 'Active')
            ->first();

        if (!$adminRole) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'role' => 'The active admin role was not found. Please check the roles table.',
                ]);
        }

        DB::transaction(function () use ($validated, $adminRole) {
            $user = new User();

            $user->name = trim($validated['name']);
            $user->school_id = $validated['school_id'];
            $user->role_id = $adminRole->id;
            $user->login_id = trim($validated['login_id']);
            $user->email = strtolower(trim($validated['email']));
            $user->password = $validated['password'];
            $user->status = 'Active';

            // User model's "hashed" cast hashes the plain password.
            $user->save();
        });

        return redirect()
            ->route('super-admin.schools.admin.create')
            ->with('success', 'School administrator account created successfully.');
    }
}
