<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create / update admin user
        User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'School Administrator',
                'password' => 'Admin@12345',
            ]
        );

        // Seed roles and permissions
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}