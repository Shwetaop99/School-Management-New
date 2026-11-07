<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFeature
{
    /**
     * Match route names to subscription feature keys.
     * This method is also used by the sidebar.
     */
    public function featureFor(string $routeName): ?string
    {
        $name = preg_replace('/^admin\./', '', $routeName);

        // School Profile is a specific settings page.

         if ($name === 'dashboard') {
        return 'dashboard';
    }
    
        if ($name === 'settings.index') {
            return 'school_profile';
        }

        $rules = [
            // Staff and faculty
            'teachers.salary.' => 'staff_management',
            'salary.' => 'staff_management',
            'other-staff.' => 'staff_management',
            'teachers.attendance.' => 'attendance',
            'teachers.' => 'teachers',
            'leave-applications.' => 'teachers',

            // Students
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

            // Classes
            'classes.' => 'classes',
            'subjects.' => 'classes',

            // Attendance
            'attendance.' => 'attendance',

            // Fees
            'fees.' => 'fees',

            // Examinations
            'exams.' => 'examinations',
            'exam-' => 'examinations',

            // Results
            'results.' => 'results',
            'marks.' => 'results',
            'reports.results.' => 'results',

            // Reports
            'reports.' => 'basic_reports',

            // Other modules
            'notices.' => 'notices',
            'library.' => 'library',
            'transport.' => 'transport',
            'sports.' => 'sports',

            // Backup and recovery
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

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Super Admin is not restricted.
        if (!$user || $user->hasRole('super_admin')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        $feature = $this->featureFor($routeName);

        // Routes without a feature mapping remain unchanged.
        if (!$feature) {
            return $next($request);
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

        if (
            !$subscription ||
            !$subscription->plan ||
            !$subscription->plan->is_active
        ) {
            abort(
                403,
                'Your subscription is expired or inactive. Please contact your school administrator or Super Admin to renew it.'
            );
        }

        $features = $subscription->plan->features ?? [];

        if (!in_array($feature, $features, true)) {
            abort(
                403,
                'This feature is not included in your current subscription plan. Please upgrade your plan to access it.'
            );
        }

        return $next($request);
    }
}
