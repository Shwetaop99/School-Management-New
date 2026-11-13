<?php

namespace Database\Seeders;

use App\Models\SuperAdminPermission;
use Illuminate\Database\Seeder;

class SuperAdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'User Management'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'User Management'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'User Management'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'User Management'],

            // Roles & Permissions
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'module' => 'Roles & Permissions'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'module' => 'Roles & Permissions'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'module' => 'Roles & Permissions'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'module' => 'Roles & Permissions'],

            // School Management
            ['name' => 'schools.view', 'display_name' => 'View Schools', 'module' => 'School Management'],
            ['name' => 'schools.create', 'display_name' => 'Create Schools', 'module' => 'School Management'],
            ['name' => 'schools.edit', 'display_name' => 'Edit Schools', 'module' => 'School Management'],
            ['name' => 'schools.delete', 'display_name' => 'Delete Schools', 'module' => 'School Management'],

            // Subscription Management
            ['name' => 'subscriptions.view', 'display_name' => 'View Subscriptions', 'module' => 'Subscription Management'],
            ['name' => 'subscriptions.create', 'display_name' => 'Create Subscriptions', 'module' => 'Subscription Management'],
            ['name' => 'subscriptions.edit', 'display_name' => 'Edit Subscriptions', 'module' => 'Subscription Management'],
            ['name' => 'subscriptions.manage', 'display_name' => 'Manage Subscriptions', 'module' => 'Subscription Management'],
        ];

        foreach ($permissions as $permission) {
            SuperAdminPermission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'module' => $permission['module'],
                ]
            );
        }
    }
}
