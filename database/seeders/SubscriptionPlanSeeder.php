<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free-trial',
                'description' => '30-day free trial with full Platinum access.',
                'price' => 0,
                'duration_days' => 30,
                'features' => [
                    'dashboard', 'school_profile', 'students', 'teachers',
                    'classes', 'attendance', 'basic_reports', 'fees',
                    'examinations', 'results', 'notices', 'library',
                    'staff_management', 'advanced_reports', 'report_exports',
                    'backups', 'restore', 'analytics', 'transport', 'sports',
                ],
                'is_trial' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'description' => 'Essential school management features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => [
                    'dashboard', 'school_profile', 'students', 'teachers',
                    'classes', 'attendance', 'basic_reports',
                ],
                'is_trial' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Advanced school management features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => [
                    'dashboard', 'school_profile', 'students', 'teachers',
                    'classes', 'attendance', 'basic_reports', 'fees',
                    'examinations', 'results', 'notices', 'library',
                    'staff_management', 'advanced_reports', 'report_exports',
                ],
                'is_trial' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Platinum',
                'slug' => 'platinum',
                'description' => 'Full access to all configured features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => [
                    'dashboard', 'school_profile', 'students', 'teachers',
                    'classes', 'attendance', 'basic_reports', 'fees',
                    'examinations', 'results', 'notices', 'library',
                    'staff_management', 'advanced_reports', 'report_exports',
                    'backups', 'restore', 'analytics', 'transport', 'sports',
                ],
                'is_trial' => false,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}