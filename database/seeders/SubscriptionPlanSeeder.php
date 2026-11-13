<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Silver: essential school management features.
        $silverFeatures = [
            'dashboard',
            'school_profile',
            'students',
            'teachers',
            'classes',
            'attendance',
            'basic_reports',
        ];

        // Gold: Silver features plus advanced modules.
        $goldFeatures = array_merge($silverFeatures, [
            'fees',
            'examinations',
            'results',
            'notices',
            'library',
            'staff_management',
            'advanced_reports',
            'report_exports',
        ]);

        // Platinum: every feature in the subscription system.
        $platinumFeatures = [
            'dashboard',
            'school_profile',
            'students',
            'teachers',
            'classes',
            'attendance',
            'basic_reports',
            'fees',
            'examinations',
            'results',
            'notices',
            'library',
            'staff_management',
            'advanced_reports',
            'report_exports',
            'backups',
            'restore',
            'analytics',
        ];

        $plans = [
            [
                'name' => 'Free Trial',
                'slug' => 'free-trial',
                'description' => '30-day free trial with full Platinum access.',
                'price' => 0,
                'duration_days' => 30,
                'features' => $platinumFeatures,
                'is_trial' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'description' => 'Essential school management features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => $silverFeatures,
                'is_trial' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Advanced school management features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => $goldFeatures,
                'is_trial' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Platinum',
                'slug' => 'platinum',
                'description' => 'Full access to all configured features.',
                'price' => 0,
                'duration_days' => 365,
                'features' => $platinumFeatures,
                'is_trial' => false,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
