<?php

namespace Database\Seeders;

use App\Models\IdCardTemplate;
use Illuminate\Database\Seeder;

class IdCardTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' =>
                    'Classic school ID card design with student photo and essential information.',
                'status' => 'active',
            ],

            [
                'name' => 'Compact',
                'slug' => 'compact',
                'description' =>
                    'Small and clean ID card design suitable for compact student cards.',
                'status' => 'active',
            ],

            [
                'name' => 'Modern',
                'slug' => 'modern',
                'description' =>
                    'Professional modern design with a clean and attractive appearance.',
                'status' => 'active',
            ],

            [
                'name' => 'Front & Back',
                'slug' => 'front_back',
                'description' =>
                    'Complete ID card design containing both front and back information.',
                'status' => 'active',
            ],

        ];


        foreach ($templates as $template) {

            IdCardTemplate::updateOrCreate(
                [
                    'slug' => $template['slug'],
                ],
                $template
            );

        }
    }
}
