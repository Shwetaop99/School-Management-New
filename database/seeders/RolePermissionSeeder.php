<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full access to the entire school management system.',
                'status' => 'Active',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Manages day-to-day school administration.',
                'status' => 'Active',
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Manages teaching, attendance and academic activities.',
                'status' => 'Active',
            ],
            [
                'name' => 'librarian',
                'display_name' => 'Librarian',
                'description' => 'Manages books, issues, returns and library records.',
                'status' => 'Active',
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Manages fees and financial records.',
                'status' => 'Active',
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Receptionist',
                'description' => 'Manages reception and basic administrative activities.',
                'status' => 'Active',
            ],
            [
                'name' => 'other_staff',
                'display_name' => 'Other Staff',
                'description' => 'Access based on assigned staff responsibilities.',
                'status' => 'Active',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'Dashboard'],

            // Students
            ['name' => 'students.view', 'display_name' => 'View Students', 'module' => 'Students'],
            ['name' => 'students.create', 'display_name' => 'Add Students', 'module' => 'Students'],
            ['name' => 'students.edit', 'display_name' => 'Edit Students', 'module' => 'Students'],
            ['name' => 'students.delete', 'display_name' => 'Delete Students', 'module' => 'Students'],

            // Faculty
            ['name' => 'faculty.view', 'display_name' => 'View Faculty', 'module' => 'Faculty'],
            ['name' => 'faculty.create', 'display_name' => 'Add Faculty', 'module' => 'Faculty'],
            ['name' => 'faculty.edit', 'display_name' => 'Edit Faculty', 'module' => 'Faculty'],
            ['name' => 'faculty.delete', 'display_name' => 'Delete Faculty', 'module' => 'Faculty'],

            // Other Staff
            ['name' => 'staff.view', 'display_name' => 'View Other Staff', 'module' => 'Other Staff'],
            ['name' => 'staff.create', 'display_name' => 'Add Other Staff', 'module' => 'Other Staff'],
            ['name' => 'staff.edit', 'display_name' => 'Edit Other Staff', 'module' => 'Other Staff'],
            ['name' => 'staff.delete', 'display_name' => 'Delete Other Staff', 'module' => 'Other Staff'],

            // Library
            ['name' => 'library.view', 'display_name' => 'View Library', 'module' => 'Library'],
            ['name' => 'library.books', 'display_name' => 'Manage Books', 'module' => 'Library'],
            ['name' => 'library.issue', 'display_name' => 'Issue Books', 'module' => 'Library'],
            ['name' => 'library.return', 'display_name' => 'Return Books', 'module' => 'Library'],
            ['name' => 'library.fines', 'display_name' => 'Manage Fines', 'module' => 'Library'],
            ['name' => 'library.reports', 'display_name' => 'View Library Reports', 'module' => 'Library'],

            // Notices
            ['name' => 'notices.view', 'display_name' => 'View Notices', 'module' => 'Notices'],
            ['name' => 'notices.create', 'display_name' => 'Create Notices', 'module' => 'Notices'],
            ['name' => 'notices.edit', 'display_name' => 'Edit Notices', 'module' => 'Notices'],
            ['name' => 'notices.delete', 'display_name' => 'Delete Notices', 'module' => 'Notices'],

            // Attendance
            ['name' => 'attendance.view', 'display_name' => 'View Attendance', 'module' => 'Attendance'],
            ['name' => 'attendance.manage', 'display_name' => 'Manage Attendance', 'module' => 'Attendance'],

            // Fees
            ['name' => 'fees.view', 'display_name' => 'View Fees', 'module' => 'Fees'],
            ['name' => 'fees.manage', 'display_name' => 'Manage Fees', 'module' => 'Fees'],

            // Exams
            ['name' => 'exams.view', 'display_name' => 'View Exams', 'module' => 'Exams'],
            ['name' => 'exams.manage', 'display_name' => 'Manage Exams', 'module' => 'Exams'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'Reports'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'Settings'],
            ['name' => 'roles.view', 'display_name' => 'View Roles & Permissions', 'module' => 'Settings'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles & Permissions', 'module' => 'Settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Super Admin gets every permission
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::where('name', 'super_admin')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }
    }
}