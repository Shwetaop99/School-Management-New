<?php

namespace App\Support;

class Access
{
    /**
     * Map route names to their required permissions.
     */
    public static function permissionFor(string $routeName, string $method = 'GET'): ?string
    {
        $name = preg_replace('/^admin\./', '', $routeName);

        // Routes that should not require module permissions.
        $publicRoutes = [
            'logout',
            '2fa.setup',
            '2fa.verify',
            '2fa.security',
            '2fa.enable',
            '2fa.disable',
            'locations.',
        ];

        foreach ($publicRoutes as $route) {
            if ($name === $route || str_starts_with($name, $route)) {
                return null;
            }
        }

        $modules = [
            'dashboard' => 'dashboard',
            'students.' => 'students',
            'student-' => 'students',
            'student-' => 'students',
            'id-card.' => 'students',
            'bonafide.' => 'students',
            'school-leaving-certificate.' => 'students',
            'age-report.' => 'students',
            'caste-report.' => 'students',
            'supply-items.' => 'students',
            'kit-templates.' => 'students',
            'government-schemes.' => 'students',

            'teachers.' => 'faculty',
            'leave-applications.' => 'faculty',
            'other-staff.' => 'staff',
            'salary.' => 'payroll',
            'teachers.salary.' => 'payroll',

            'timetable.' => 'timetable',
            'attendance.' => 'attendance',
            'teachers.attendance.' => 'attendance',

            'fees.' => 'fees',
            'fee-structures.' => 'fees',

            'classes.' => 'classes',
            'subjects.' => 'classes',

            'exams.' => 'exams',
            'exam-' => 'exams',
            'results.' => 'results',
            'marks.' => 'results',

            'scholarship.' => 'scholarship',
            'library.' => 'library',
            'notices.' => 'notices',
            
            'meals.' => 'meals',

            'reports.' => 'reports',
            'class-reports.' => 'reports',

            'backup.' => 'settings',
            'restore.' => 'settings',
            'activity-log.' => 'settings',
        ];

        // Transport reports
if (
    $name === 'reports.transport' ||
    str_starts_with($name, 'reports.transport.') ||
    $name === 'transport.reports' ||
    str_starts_with($name, 'transport.reports.')
) {
    return 'transport.reports.view';
}


// Sports reports
if (str_starts_with($name, 'reports.sports.')) {
    if (str_starts_with($name, 'reports.sports.achievements')) {
        return 'sports.achievements.view';
    }

    if (str_starts_with($name, 'reports.sports.equipment')) {
        return 'sports.equipment.view';
    }

    return 'sports.games.view';
}

// Sports achievements
if (str_starts_with($name, 'sports.achievements.')) {
    $action = match (strtoupper($method)) {
        'POST' => 'create',
        'PUT', 'PATCH' => 'edit',
        'DELETE' => 'delete',
        default => 'view',
    };

    return 'sports.achievements.' . $action;
}

// Sports equipment
if (str_starts_with($name, 'sports.equipment.')) {
    $action = match (strtoupper($method)) {
        'POST' => 'create',
        'PUT', 'PATCH' => 'edit',
        'DELETE' => 'delete',
        default => 'view',
    };

    return 'sports.equipment.' . $action;
}

// Sports games
if (
    $name === 'sports' ||
    str_starts_with($name, 'sports.games.')
) {
    $action = match (strtoupper($method)) {
        'POST' => 'create',
        'PUT', 'PATCH' => 'edit',
        'DELETE' => 'delete',
        default => 'view',
    };

    return 'sports.games.' . $action;
}

// Sports reports landing page
if ($name === 'reports.sports') {
    return 'sports.games.view';
}

// Backup & Recovery permissions
if ($name === 'backup') {
    return 'backup.view';
}

if ($name === 'backup.create') {
    return 'backup.create';
}

if ($name === 'backup.download') {
    return 'backup.download';
}

if ($name === 'backup.destroy') {
    return 'backup.delete';
}

if (
    $name === 'backup.restore' ||
    str_starts_with($name, 'backup.restore.') ||
    $name === 'backup.validate'
) {
    return 'backup.restore';
}

if (
    $name === 'backup' ||
    $name === 'backup.index'
) {
    return 'backup.view';
}


        $permissionGroup = null;

        foreach ($modules as $prefix => $module) {
            if ($name === $prefix || str_starts_with($name, $prefix)) {
                $permissionGroup = $module;
                break;
            }
        }

        if (!$permissionGroup) {
            return null;
        }

        $action = match (strtoupper($method)) {
            'GET', 'HEAD' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'edit',
            'DELETE' => 'delete',
            default => 'view',
        };

        return $permissionGroup . '.' . $action;
    }

    /**
     * Check whether the user has the required permission.
     */
    public static function allows($user, string $permission): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermission($permission);
    }

    /**
     * Return routes denied by the user's permissions.
     */
    public static function deniedRoutes($user): array
    {
        return [];
    }
}
