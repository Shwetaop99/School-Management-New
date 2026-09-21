<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update default admin user
        User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'School Administrator',
                'password' => 'Admin@12345',
            ]
        );

        // Seed ID Card Templates
        $this->call([
            IdCardTemplateSeeder::class,
        ]);
    }
}
