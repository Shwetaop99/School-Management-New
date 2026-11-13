<?php

namespace Database\Seeders;

use App\Models\SuperAdminRole;
use App\Models\SuperAdminPermission;
use Illuminate\Database\Seeder;

class SuperAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            'platform_manager' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'roles.view',
                'roles.create',
                'roles.edit',
                'roles.delete',
                'schools.view',
                'schools.create',
                'schools.edit',
                'schools.delete',
                'subscriptions.view',
                'subscriptions.create',
                'subscriptions.edit',
                'subscriptions.manage',
            ],

            'school_manager' => [
                'schools.view',
                'schools.create',
                'schools.edit',
                'schools.delete',
            ],

            'subscription_manager' => [
                'subscriptions.view',
                'subscriptions.create',
                'subscriptions.edit',
                'subscriptions.manage',
            ],
        ];

        $roles = [
            'platform_manager' => [
                'display_name' => 'Platform Manager',
                'description' => 'Manages platform-level operations.',
            ],
            'school_manager' => [
                'display_name' => 'School Manager',
                'description' => 'Manages school registrations and records.',
            ],
            'subscription_manager' => [
                'display_name' => 'Subscription Manager',
                'description' => 'Manages subscription plans and payments.',
            ],
        ];

        foreach ($roles as $name => $details) {
            $role = SuperAdminRole::updateOrCreate(
                ['name' => $name],
                [
                    ...$details,
                    'is_system' => true,
                    'status' => 'Active',
                ]
            );

            $permissionIds = SuperAdminPermission::whereIn(
                'name',
                $rolePermissions[$name]
            )->pluck('id')->all();

            $role->permissions()->sync($permissionIds);
        }
    }
}