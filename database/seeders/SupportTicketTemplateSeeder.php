<?php

namespace Database\Seeders;

use App\Models\SupportTicketTemplate;
use Illuminate\Database\Seeder;

class SupportTicketTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // Technical Issues
            [
                'title' => 'Page is not loading',
                'category' => 'technical',
                'priority' => 'high',
                'description' => 'A page in the school management system is not loading properly. Please investigate this issue.',
            ],
            [
                'title' => 'Something is not working correctly',
                'category' => 'technical',
                'priority' => 'medium',
                'description' => 'A feature or functionality is not working as expected. Please check and resolve the issue.',
            ],

            // Login & Security
            [
                'title' => 'Unable to log in',
                'category' => 'login',
                'priority' => 'high',
                'description' => 'We are unable to log in to our school account. Please investigate and help us regain access.',
            ],
            [
                'title' => 'Password reset issue',
                'category' => 'login',
                'priority' => 'medium',
                'description' => 'We are facing an issue while resetting our account password. Please assist us.',
            ],

            // Fees & Payments
            [
                'title' => 'Fee receipt is not generating',
                'category' => 'billing',
                'priority' => 'high',
                'description' => 'The fee receipt is not generating after a successful fee payment. Please investigate this issue.',
            ],
            [
                'title' => 'Incorrect fee amount displayed',
                'category' => 'billing',
                'priority' => 'high',
                'description' => 'The fee amount displayed for a student appears to be incorrect. Please verify the fee records.',
            ],

            // Student Management
            [
                'title' => 'Unable to update student details',
                'category' => 'student_management',
                'priority' => 'medium',
                'description' => 'We are unable to update student information. Please investigate and resolve this issue.',
            ],

            // Backup & Recovery
            [
                'title' => 'Backup creation failed',
                'category' => 'backup',
                'priority' => 'high',
                'description' => 'We are unable to create a database backup. Please investigate the backup process.',
            ],
            [
                'title' => 'Unable to restore database',
                'category' => 'backup',
                'priority' => 'urgent',
                'description' => 'We are facing an issue while restoring our database backup. Please investigate this urgently.',
            ],

            // General
            [
                'title' => 'General query',
                'category' => 'general',
                'priority' => 'low',
                'description' => 'We have a general question regarding the school management system. Please assist us.',
            ],
        ];

        foreach ($templates as $template) {
            SupportTicketTemplate::updateOrCreate(
                ['title' => $template['title']],
                array_merge($template, ['is_active' => true])
            );
        }
    }
}