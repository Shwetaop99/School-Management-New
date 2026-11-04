<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Access
{
    // Actions that exist for each module (same as your seeder)
    private const MODULES = [
        'dashboard'   => ['view'],
        'students'    => ['view', 'create', 'edit', 'delete'],
        'faculty'     => ['view', 'create', 'edit', 'delete'],
        'staff'       => ['view', 'create', 'edit', 'delete'],
        'timetable'   => ['view', 'create', 'edit'],
        'attendance'  => ['view', 'create', 'edit'],
        'fees'        => ['view', 'create', 'edit', 'delete'],
        'classes'     => ['view', 'create', 'edit', 'delete'],
        'exams'       => ['view', 'create', 'edit', 'delete'],
        'scholarship' => ['view', 'create', 'edit'],
        'results'     => ['view', 'create', 'edit'],
        'library'     => ['view', 'create', 'edit', 'delete'],
        'notices'     => ['view', 'create', 'edit', 'delete'],
        'transport'   => ['view', 'create', 'edit', 'delete'],
        'payroll'     => ['view', 'create', 'edit'],
        'meals'       => ['view', 'create', 'edit'],
        'sports'      => ['view', 'create', 'edit'],
        'reports'     => ['view'],
        'settings'    => ['view', 'edit'],
    ];

    // Route name (without "admin.") starts with => module. First match wins.
    // false = open to every logged-in user.
    private const RULES = [
        'login'           => false,
        'logout'          => false,
        '2fa.'            => false,
        'locations.'      => false,
        'settings.roles.' => false, // already protected in routes/web.php
        'settings.users.' => false, // already protected in routes/web.php

        'dashboard' => 'dashboard',

        // reports follow their own module
        'reports.students'           => 'students',
        'reports.staff'              => 'staff',
        'reports.attendance'         => 'attendance',
        'reports.teacher-attendance' => 'attendance',
        'reports.fees'               => 'fees',
        'reports.meal'               => 'meals',
        'reports.results'            => 'results',
        'reports.exams'              => 'exams',
        'reports.'                   => 'reports',

        'teachers.attendance' => 'attendance',
        'teachers.salary'     => 'payroll',
        'teachers.'           => 'faculty',
        'leave-applications.' => 'faculty',
        'other-staff.'        => 'staff',

        'students.'                    => 'students',
        'student-'                     => 'students',
        'id-card.'                     => 'students',
        'bonafide.'                    => 'students',
        'school-leaving-certificate.'  => 'students',
        'age-report.'                  => 'students',
        'caste-report.'                => 'students',
        'supply-items.'                => 'students',
        'kit-templates.'               => 'students',
        'government-schemes.'          => 'students',

        'timetable.'   => 'timetable',
        'attendance.'  => 'attendance',
        'fees.'        => 'fees',
        'classes.'     => 'classes',
        'subjects.'    => 'classes',
        'exams.'       => 'exams',
        'exam-'        => 'exams',
        'scholarship.' => 'scholarship',
        'results.'     => 'results',
        'marks.'       => 'results',
        'library.'     => 'library',
        'notices.'     => 'notices',
        'transport.'   => 'transport',
        'salary.'      => 'payroll',
        'meal.'        => 'meals',
        'meals.'       => 'meals',
        'sports.'      => 'sports',
        'settings.'    => 'settings',
    ];

    /**
     * Which permission does this route need?
     * null = no permission needed.
     */
    public static function permissionFor(string $routeName, string $method): ?string
    {
        $name = preg_replace('/^admin\./', '', $routeName);

        // backup & restore: only people who can edit settings
       if (str_starts_with($name, 'backup.')) {
    return 'backup.manage';
}
        $module  = null;
        $matched = false;

        foreach (self::RULES as $prefix => $value) {
            if (str_starts_with($name, $prefix)) {
                $module  = $value;
                $matched = true;
                break;
            }
        }

        // route we don't know about: nobody except super_admin
        if (!$matched) {
            return 'unmapped.' . $name;
        }

        if ($module === false) {
            return null;
        }

        $last = substr($name, (int) strrpos($name, '.') + 1);

        $action = match (true) {
            in_array($last, ['create', 'store'], true) => 'create',
            in_array($last, ['edit', 'update'], true)  => 'edit',
            $last === 'destroy'                        => 'delete',
            default => match (strtoupper($method)) {
                'POST'         => 'create',
                'PUT', 'PATCH' => 'edit',
                'DELETE'       => 'delete',
                default        => 'view',
            },
        };

        // module without that action? fall back to edit, then view
        $allowed = self::MODULES[$module];
        if (!in_array($action, $allowed, true)) {
            $action = ($action !== 'view' && in_array('edit', $allowed, true)) ? 'edit' : 'view';
        }

        return "{$module}.{$action}";
    }

    /**
     * Does this user have this permission? (loaded once per request)
     */
    public static function allows($user, string $permission): bool
    {
        if (!$user || !$user->role) {
            return false;
        }

        if ($user->role->name === 'super_admin') {
            return true;
        }

        static $cache = [];
        $cache[$user->id] ??= $user->role->permissions()->pluck('name')->all();

        return in_array($permission, $cache[$user->id], true);
    }

    /**
     * Every route this user is NOT allowed to open (used to hide buttons).
     */
    public static function deniedRoutes($user): array
    {
        $denied = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if (!$name || !str_starts_with($name, 'admin.')) {
                continue;
            }

            foreach ($route->methods() as $method) {
                if ($method === 'HEAD') {
                    continue;
                }

                $permission = self::permissionFor($name, $method);

                if ($permission === null || self::allows($user, $permission)) {
                    continue;
                }

                $parts = array_map(
                    fn ($seg) => preg_match('/^\{.+\}$/', $seg) ? '[^/]+' : preg_quote($seg, '/'),
                    explode('/', $route->uri())
                );

                $denied[] = ['m' => $method, 're' => '^/' . implode('/', $parts) . '/?$'];
            }
        }

        return $denied;
    }
}