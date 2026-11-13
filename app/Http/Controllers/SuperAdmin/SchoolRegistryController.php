<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolRegistryController extends Controller
{
    /**
     * Display registered schools.
     */
    public function index(Request $request)
    {
        $query = School::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->whereRaw(
                'LOWER(status) = ?',
                [strtolower($request->input('status'))]
            );
        }

        $schools = $query->latest()->paginate(10)->withQueryString();

        $totalSchools = School::count();

        $activeSchools = School::whereRaw(
            'LOWER(status) = ?', ['active']
        )->count();

        $inactiveSchools = School::whereRaw(
            'LOWER(status) = ?', ['inactive']
        )->count();

        return view('super-admin.schools.index', compact(
            'schools',
            'totalSchools',
            'activeSchools',
            'inactiveSchools'
        ));
    }

    /**
     * Show the school registration form.
     */
    public function create()
    {
        return view('super-admin.schools.create');
    }

    /**
     * Register a school, its administrator, and its free trial.
     */
    public function store(Request $request)
    {
        $request->merge([
            'school_code' => strtoupper(
                trim((string) $request->input('school_code'))
            ),
        ]);

        $validated = $request->validate([
            // School details
            'name' => ['required', 'string', 'max:255'],

            'school_code' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                'unique:schools,school_code',
            ],

            'school_type' => [
                'required',
                Rule::in([
                    'Pre-Primary',
                    'Primary',
                    'Upper Primary',
                    'Secondary',
                    'Senior Secondary',
                    'Other',
                ]),
            ],

            'education_board' => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],

            'establishment_year' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . date('Y'),
            ],

            'email' => ['nullable', 'email', 'max:255'],

            'phone' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]{7,20}$/',
            ],

            'website' => ['nullable', 'url', 'max:255'],

            // Address
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],

            'postal_code' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\s-]{3,12}$/',
            ],

            'country' => ['required', 'string', 'max:100'],
            'principal_name' => ['required', 'string', 'max:255'],

            // School administrator
            'admin_name' => ['required', 'string', 'max:255'],

            'admin_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'admin_login_id' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'alpha_dash',
                'unique:users,login_id',
            ],

            'admin_phone' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]{7,20}$/',
            ],

            'admin_password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
            ],
        ]);

        // Find the existing school administrator role.
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            return back()
                ->withInput()
                ->withErrors([
                    'admin_name' =>
                        'The school administrator role was not found. '
                        . 'Please check the roles table.',
                ]);
        }

        // Find the configured 30-day trial plan.
        $trialPlan = SubscriptionPlan::query()
            ->where('slug', 'free-trial')
            ->where('is_active', true)
            ->where('is_trial', true)
            ->first();

        if (!$trialPlan || (int) $trialPlan->duration_days !== 30) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' =>
                        'The active 30-day Free Trial plan was not found. '
                        . 'Please configure it before registering a school.',
                ]);
        }

        $validated['name'] = trim($validated['name']);
        $validated['country'] = trim($validated['country']);

        // All three records succeed together or roll back together.
        DB::transaction(function () use (
            $validated,
            $adminRole,
            $trialPlan
        ) {
            // 1. Create the school.
            $school = School::create([
                'name' => $validated['name'],
                'school_code' => $validated['school_code'],
                'status' => 'active',
                'school_type' => $validated['school_type'],
                'education_board' =>
                    $validated['education_board'] ?? null,
                'registration_number' =>
                    $validated['registration_number'] ?? null,
                'establishment_year' =>
                    $validated['establishment_year'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'website' => $validated['website'] ?? null,
                'address_line' => $validated['address_line'],
                'city' => $validated['city'],
                'district' => $validated['district'] ?? null,
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'principal_name' => $validated['principal_name'],
            ]);

            // 2. Create the school administrator.
            User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'login_id' => $validated['admin_login_id'],
                'phone' => $validated['admin_phone'],
                'password' => $validated['admin_password'],
                'role_id' => $adminRole->id,
                'school_id' => $school->id,
                'status' => 'Active',
            ]);

            // 3. Automatically assign the 30-day trial.
            $trialStart = now();

            Subscription::create([
                'school_id' => $school->id,
                'subscription_plan_id' => $trialPlan->id,
                'type' => 'trial',
                'status' => 'active',
                'starts_at' => $trialStart,
                'ends_at' => $trialStart->copy()->addDays(30),
                'agreed_price' => 0,
                'notes' =>
                    'Automatically assigned during school registration.',
            ]);
        });

        return redirect()
            ->route('super-admin.schools.index')
            ->with(
                'success',
                'School, administrator, and 30-day free trial '
                . 'registered successfully.'
            );
    }

    /**
     * View school details.
     */
    public function show(School $school)
    {
        $school->load('administrator');

        return view('super-admin.schools.show', compact('school'));
    }

    /**
     * Show the school edit form.
     */
    public function edit(School $school)
    {
        return view('super-admin.schools.edit', compact('school'));
    }

    /**
     * Update basic school details.
     */
    public function update(Request $request, School $school)
    {
        $request->merge([
            'school_code' => strtoupper(
                trim((string) $request->input('school_code'))
            ),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'school_code' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('schools', 'school_code')
                    ->ignore($school->id),
            ],
        ]);

        $school->update($validated);

        return redirect()
            ->route('super-admin.schools.index')
            ->with('success', 'School details updated successfully.');
    }

    /**
     * Activate or deactivate a school.
     */
    public function updateStatus(Request $request, School $school)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $school->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'School status updated successfully.');
    }
}
