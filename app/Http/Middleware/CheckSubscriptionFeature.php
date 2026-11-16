<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFeature
{
    /**
     * Map route names to subscription feature keys.
     */
    public function featureFor(string $routeName): ?string
    {
        $name = preg_replace('/^admin\./', '', $routeName);

        if ($name === 'dashboard') {
            return 'dashboard';
        }

        if ($name === 'settings.index') {
            return 'school_profile';
        }

        $rules = [
            'teachers.attendance.' => 'attendance',
            'teachers.salary.' => 'staff_management',
            'salary.' => 'staff_management',
            'other-staff.' => 'staff_management',
            'teachers.' => 'teachers',
            'leave-applications.' => 'teachers',

            'students.' => 'students',
            'student-' => 'students',
            'student-profile.' => 'students',
            'student-documents.' => 'students',
            'student-general-register.' => 'students',
            'student-supply-kits.' => 'students',
            'student-health.' => 'students',
            'id-card.' => 'students',
            'bonafide.' => 'students',
            'school-leaving-certificate.' => 'students',
            'age-report.' => 'students',
            'caste-report.' => 'students',
            'supply-items.' => 'students',
            'kit-templates.' => 'students',
            'government-schemes.' => 'students',

            'classes.' => 'classes',
            'subjects.' => 'classes',
            'attendance.' => 'attendance',
            'fees.' => 'fees',

            'exams.' => 'examinations',
            'exam-' => 'examinations',

            'results.' => 'results',
            'marks.' => 'results',
            'reports.results.' => 'results',
            'reports.' => 'basic_reports',

            'notices.' => 'notices',
            'library.' => 'library',
            'transport.' => 'transport',
            'sports.' => 'sports',
            'backup.' => 'backups',
            'restore.' => 'restore',
        ];

        foreach ($rules as $prefix => $feature) {
            if (str_starts_with($name, $prefix)) {
                return $feature;
            }
        }

        return null;
    }

    /**
     * Check whether the logged-in school has access to a feature.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Super Admin has unrestricted access.
        if ($user && $user->hasRole('super_admin')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        $feature = $this->featureFor($routeName);

        // Allow routes that are not mapped to subscription features.
        if (!$feature) {
            return $next($request);
        }

        if (!$user || !$user->school_id) {
            abort(403, 'Your account is not assigned to a school.');
        }

        $subscription = Subscription::query()
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->whereNotNull('starts_at')
            ->whereNotNull('ends_at')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->with('plan')
            ->orderByDesc('starts_at')
            ->orderByDesc('created_at')
            ->first();

        if (!$subscription || !$subscription->plan || !$subscription->plan->is_active) {
            abort(403, 'Your subscription is inactive or expired. Please renew your plan.');
        }

        $features = $subscription->plan->features ?? [];

        if (!is_array($features)) {
            $features = [];
        }

        if (!in_array($feature, $features, true)) {
            abort(403, 'This module is not included in your current subscription plan. Please upgrade your plan.');
        }

        return $next($request);
    }
}

