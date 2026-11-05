<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    private const FEATURES = [
        'dashboard' => 'Dashboard',
        'school_profile' => 'School Profile',
        'students' => 'Student Management',
        'teachers' => 'Teacher Management',
        'classes' => 'Class Management',
        'attendance' => 'Attendance',
        'basic_reports' => 'Basic Reports',
        'fees' => 'Fee Management',
        'examinations' => 'Examinations',
        'results' => 'Results',
        'notices' => 'Notice Management',
        'library' => 'Library Management',
        'staff_management' => 'Staff Management',
        'advanced_reports' => 'Advanced Reports',
        'report_exports' => 'PDF and Excel Exports',
        'backups' => 'Database Backups',
        'restore' => 'Database Restore',
        'analytics' => 'Analytics',
    ];

    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')
            ->orderBy('id')
            ->get();

        $totalPlans = $plans->count();
        $activePlans = $plans->where('is_active', true)->count();

        return view('super-admin.subscriptions.plans.index', compact(
            'plans',
            'totalPlans',
            'activePlans'
        ));
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view(
            'super-admin.subscriptions.plans.edit',
            [
                'plan' => $subscriptionPlan,
                'availableFeatures' => self::FEATURES,
            ]
        );
    }

    public function update(
        Request $request,
        SubscriptionPlan $subscriptionPlan
    ) {
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'description' => ['nullable', 'string', 'max:1000'],
            'features' => ['nullable', 'array'],
            'features.*' => [
                'string',
                Rule::in(array_keys(self::FEATURES)),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        // The free trial must remain free and last exactly 30 days.
        if ($subscriptionPlan->is_trial) {
            $validated['price'] = 0;
            $validated['duration_days'] = 30;
            $validated['features'] = array_keys(self::FEATURES);
        }

        $subscriptionPlan->update([
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'description' => $validated['description'] ?? null,
            'features' => array_values($validated['features'] ?? []),
            'is_active' => $validated['is_active'],
        ]);

        return redirect()
            ->route('super-admin.subscription-plans.index')
            ->with('success', $subscriptionPlan->name . ' plan updated successfully.');
    }
}
