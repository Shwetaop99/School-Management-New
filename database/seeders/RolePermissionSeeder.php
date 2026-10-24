<?php

namespace Database\Seeder;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Default roles
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

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Permission definition helper
        |--------------------------------------------------------------------------
        */

        $permissions = [];

        $addPermission = function (
            string $name,
            string $displayName,
            string $module,
            ?string $description = null
        ) use (&$permissions): void {
            $permissions[$name] = [
                'name' => $name,
                'display_name' => $displayName,
                'module' => $module,
                'description' => $description,
            ];
        };

        /*
        |--------------------------------------------------------------------------
        | Existing permissions — names preserved
        |--------------------------------------------------------------------------
        */

        $existingPermissions = [
            ['dashboard.view', 'View Dashboard', 'Dashboard'],

            ['students.view', 'View Students', 'Students'],
            ['students.create', 'Add Students', 'Students'],
            ['students.edit', 'Edit Students', 'Students'],
            ['students.delete', 'Delete Students', 'Students'],

            ['faculty.view', 'View Faculty', 'Faculty'],
            ['faculty.create', 'Add Faculty', 'Faculty'],
            ['faculty.edit', 'Edit Faculty', 'Faculty'],
            ['faculty.delete', 'Delete Faculty', 'Faculty'],

            ['staff.view', 'View Other Staff', 'Other Staff'],
            ['staff.create', 'Add Other Staff', 'Other Staff'],
            ['staff.edit', 'Edit Other Staff', 'Other Staff'],
            ['staff.delete', 'Delete Other Staff', 'Other Staff'],

            ['library.view', 'View Library', 'Library'],
            ['library.books', 'Manage Books', 'Library'],
            ['library.issue', 'Issue Books', 'Library'],
            ['library.return', 'Return Books', 'Library'],
            ['library.fines', 'Manage Fines', 'Library'],
            ['library.reports', 'View Library Reports', 'Library'],

            ['notices.view', 'View Notices', 'Notices'],
            ['notices.create', 'Create Notices', 'Notices'],
            ['notices.edit', 'Edit Notices', 'Notices'],
            ['notices.delete', 'Delete Notices', 'Notices'],

            ['attendance.view', 'View Attendance', 'Attendance'],
            ['attendance.manage', 'Manage Attendance', 'Attendance'],

            ['fees.view', 'View Fees', 'Fees'],
            ['fees.manage', 'Manage Fees', 'Fees'],

            ['exams.view', 'View Exams', 'Exams'],
            ['exams.manage', 'Manage Exams', 'Exams'],

            ['reports.view', 'View Reports', 'Reports'],

            ['settings.view', 'View Settings', 'Settings'],
            ['roles.view', 'View Roles & Permissions', 'Settings'],
            ['roles.manage', 'Manage Roles & Permissions', 'Settings'],
        ];

        foreach ($existingPermissions as [$name, $label, $module]) {
            $addPermission($name, $label, $module);
        }

        /*
        |--------------------------------------------------------------------------
        | Common CRUD permissions for school modules
        |--------------------------------------------------------------------------
        */

        $crudModules = [
            'Classes & Sections' => 'classes',
            'Subjects' => 'subjects',
            'Timetable' => 'timetable',
            'Student ID Cards' => 'student-id-cards',
            'Student Health Records' => 'student-health',
            'Student Documents' => 'student-documents',
            'Student Register' => 'student-register',
            'Leaving Certificates' => 'leaving-certificates',
            'Government Schemes' => 'government-schemes',
            'Supply Items' => 'supply-items',
            'Student Supply Kits' => 'supply-kits',
            'Fee Structures' => 'fee-structures',
            'Fee Types' => 'fee-types',
            'Student Payments' => 'payments',
            'Exam Sessions' => 'exam-sessions',
            'Exam Schedules' => 'exam-schedules',
            'Exam Results' => 'results',
            'Transport Records' => 'transport.records',
            'Transport Routes' => 'transport.routes',
            'Transport Vehicles' => 'transport.vehicles',
            'Meal Items' => 'meal.items',
            'Meal Stock' => 'meal.stock',
            'Sports Games & Events' => 'sports.games',
            'Sports Achievements' => 'sports.achievements',
            'Sports Equipment' => 'sports.equipment',
            'Scholarship Exams' => 'scholarship.exams',
            'Merit Concessions' => 'merit-concessions',
            'Payroll & Salary' => 'payroll',
            'User Management' => 'users',
            'School Profile' => 'school-profile',
            'Security' => 'security',
            'Backup & Recovery' => 'backup',
            'Location Management' => 'locations',
        ];

        $crudActions = [
            'view' => 'View',
            'create' => 'Add',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ];

        foreach ($crudModules as $module => $prefix) {
            foreach ($crudActions as $action => $label) {
                $addPermission(
                    "{$prefix}.{$action}",
                    "{$label} {$module}",
                    $module
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Student-specific actions
        |--------------------------------------------------------------------------
        */

        $specialPermissions = [
            // Students
            ['students.import', 'Import Students', 'Students'],
            ['students.export', 'Export Students', 'Students'],
            ['students.print', 'Print Student Records', 'Students'],
            ['students.bulk_create', 'Bulk Create Students', 'Students'],
            ['students.documents', 'Manage Student Documents', 'Students'],
            ['students.health', 'Manage Student Health Records', 'Students'],
            ['students.id_cards', 'Manage Student ID Cards', 'Students'],
            ['students.leaving_certificate', 'Manage Leaving Certificates', 'Students'],
            ['students.bonafide', 'Manage Bonafide Certificates', 'Students'],
            ['students.caste_report', 'View Caste Reports', 'Students'],
            ['students.age_report', 'View Age Reports', 'Students'],

            // Faculty and staff
            ['faculty.import', 'Import Faculty', 'Faculty'],
            ['faculty.export', 'Export Faculty', 'Faculty'],
            ['faculty.attendance', 'Manage Faculty Attendance', 'Faculty'],
            ['faculty.salary', 'Manage Faculty Salary', 'Faculty'],
            ['staff.import', 'Import Other Staff', 'Other Staff'],
            ['staff.export', 'Export Other Staff', 'Other Staff'],

            // Classes, subjects and timetable
            ['classes.assign', 'Assign Classes and Sections', 'Classes & Sections'],
            ['subjects.assign', 'Assign Subjects', 'Subjects'],
            ['timetable.print', 'Print Timetable', 'Timetable'],
            ['timetable.import', 'Import Timetable', 'Timetable'],
            ['timetable.export', 'Export Timetable', 'Timetable'],

            // Attendance
            ['attendance.students.view', 'View Student Attendance', 'Attendance'],
            ['attendance.students.mark', 'Mark Student Attendance', 'Attendance'],
            ['attendance.students.edit', 'Edit Student Attendance', 'Attendance'],
            ['attendance.students.reports', 'View Student Attendance Reports', 'Attendance'],
            ['attendance.students.print', 'Print Student Attendance', 'Attendance'],
            ['attendance.teachers.view', 'View Teacher Attendance', 'Attendance'],
            ['attendance.teachers.mark', 'Mark Teacher Attendance', 'Attendance'],
            ['attendance.teachers.edit', 'Edit Teacher Attendance', 'Attendance'],
            ['attendance.teachers.reports', 'View Teacher Attendance Reports', 'Attendance'],
            ['attendance.export', 'Export Attendance', 'Attendance'],

            // Fees and payments
            ['fees.create', 'Create Fee Records', 'Fees'],
            ['fees.edit', 'Edit Fee Records', 'Fees'],
            ['fees.delete', 'Delete Fee Records', 'Fees'],
            ['fees.collect', 'Collect Student Fees', 'Fees'],
            ['fees.refund', 'Manage Fee Refunds', 'Fees'],
            ['fees.receipts', 'Print Fee Receipts', 'Fees'],
            ['fees.reports', 'View Fee Reports', 'Fees'],
            ['payments.view_history', 'View Payment History', 'Student Payments'],
            ['payments.print_receipt', 'Print Payment Receipts', 'Student Payments'],

            // Exams and results
            ['exams.create', 'Create Exams', 'Exams'],
            ['exams.edit', 'Edit Exams', 'Exams'],
            ['exams.delete', 'Delete Exams', 'Exams'],
            ['exams.schedules', 'Manage Exam Schedules', 'Exams'],
            ['exams.holidays', 'Manage Exam Holidays', 'Exams'],
            ['results.view', 'View Exam Results', 'Exam Results'],
            ['results.create', 'Enter Exam Results', 'Exam Results'],
            ['results.edit', 'Edit Exam Results', 'Exam Results'],
            ['results.publish', 'Publish Exam Results', 'Exam Results'],
            ['results.print', 'Print Exam Results', 'Exam Results'],
            ['results.notifications', 'Manage Result Notifications', 'Exam Results'],
            ['results.reports', 'View Result Reports', 'Exam Results'],

            // Library
            ['library.books.view', 'View Books', 'Library'],
            ['library.books.create', 'Add Books', 'Library'],
            ['library.books.edit', 'Edit Books', 'Library'],
            ['library.books.delete', 'Delete Books', 'Library'],
            ['library.issues.view', 'View Book Issues', 'Library'],
            ['library.issues.manage', 'Manage Book Issues', 'Library'],
            ['library.returns.manage', 'Manage Book Returns', 'Library'],
            ['library.reports.export', 'Export Library Reports', 'Library'],

            // Notices and communication
            ['notices.publish', 'Publish Notices', 'Notices'],
            ['notices.whatsapp', 'Send Notice WhatsApp Notifications', 'Notices'],

            // Transport
            ['transport.records.print', 'Print Transport Records', 'Transport Records'],
            ['transport.records.export', 'Export Transport Records', 'Transport Records'],
            ['transport.reports.view', 'View Transport Reports', 'Transport Records'],

            // Meal management
            ['meal.items.stock', 'Manage Meal Item Stock', 'Meal Items'],
            ['meal.stock.in', 'Record Meal Stock In', 'Meal Stock'],
            ['meal.stock.out', 'Record Meal Stock Out', 'Meal Stock'],
            ['meal.stock.logs', 'View Meal Stock Logs', 'Meal Stock'],
            ['meal.reports.view', 'View Meal Reports', 'Meal Stock'],
            ['meal.reports.export', 'Export Meal Reports', 'Meal Stock'],

            // Sports
            ['sports.games.manage_participants', 'Manage Event Participants', 'Sports Games & Events'],
            ['sports.achievements.verify', 'Verify Student Achievements', 'Sports Achievements'],
            ['sports.equipment.stock', 'Manage Sports Equipment Stock', 'Sports Equipment'],

            // Scholarships
            ['scholarship.exams.manage', 'Manage Scholarship Exams', 'Scholarship Exams'],
            ['merit-concessions.approve', 'Approve Merit Concessions', 'Merit Concessions'],

            // Payroll
            ['payroll.view', 'View Payroll', 'Payroll & Salary'],
            ['payroll.process', 'Process Payroll', 'Payroll & Salary'],
            ['payroll.approve', 'Approve Payroll', 'Payroll & Salary'],
            ['payroll.reports', 'View Payroll Reports', 'Payroll & Salary'],

            // Reports
            ['reports.students', 'View Student Reports', 'Reports'],
            ['reports.staff', 'View Staff Reports', 'Reports'],
            ['reports.attendance', 'View Attendance Reports', 'Reports'],
            ['reports.exams', 'View Exam Reports', 'Reports'],
            ['reports.results', 'View Result Reports', 'Reports'],
            ['reports.fees', 'View Fee Reports', 'Reports'],
            ['reports.transport', 'View Transport Reports', 'Reports'],
            ['reports.meals', 'View Meal Reports', 'Reports'],
            ['reports.export', 'Export Reports', 'Reports'],
            ['reports.print', 'Print Reports', 'Reports'],

            // Settings and administration
            ['roles.create', 'Create Roles', 'Roles & Permissions'],
            ['roles.edit', 'Edit Roles', 'Roles & Permissions'],
            ['roles.delete', 'Delete Roles', 'Roles & Permissions'],
            ['roles.assign', 'Assign Roles and Permissions', 'Roles & Permissions'],
            ['users.view', 'View Users', 'User Management'],
            ['users.create', 'Create Users', 'User Management'],
            ['users.edit', 'Edit Users', 'User Management'],
            ['users.delete', 'Delete Users', 'User Management'],
            ['users.assign_roles', 'Assign User Roles', 'User Management'],
            ['school_profile.view', 'View School Profile', 'School Profile'],
            ['school_profile.edit', 'Edit School Profile', 'School Profile'],
            ['security.view', 'View Security Settings', 'Security'],
            ['security.manage', 'Manage Security Settings', 'Security'],
            ['security.2fa.manage', 'Manage Two-Factor Authentication', 'Security'],
            ['backup.view', 'View Backups', 'Backup & Recovery'],
            ['backup.create', 'Create Backups', 'Backup & Recovery'],
            ['backup.restore', 'Restore Backups', 'Backup & Recovery'],
            ['backup.download', 'Download Backups', 'Backup & Recovery'],

            // Location records
            ['locations.view', 'View Locations', 'Location Management'],
            ['locations.create', 'Add Locations', 'Location Management'],
            ['locations.edit', 'Edit Locations', 'Location Management'],
            ['locations.delete', 'Delete Locations', 'Location Management'],
        ];

        foreach ($specialPermissions as [$name, $label, $module]) {
            $addPermission($name, $label, $module);
        }

        /*
        |--------------------------------------------------------------------------
        | Save permissions without deleting existing records
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin receives every permission
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::where('name', 'super_admin')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(
                Permission::pluck('id')->all()
            );
        }
    }
}
