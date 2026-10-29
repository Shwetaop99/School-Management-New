<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class MenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
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

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    [
                        'display_name' => ucfirst($module) . ' ' . ucfirst($action),
                        'module'       => $module,
                    ]
                );
            }
        }
    }
}