<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('scholarship_exams')) {
            Schema::rename(
                'scholarship_exams',
                'merit_concessions'
            );
        }

        if (Schema::hasTable('scholarship_exam_applications')) {
            Schema::rename(
                'scholarship_exam_applications',
                'merit_concession_applications'
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('merit_concessions')) {
            Schema::rename(
                'merit_concessions',
                'scholarship_exams'
            );
        }

        if (Schema::hasTable('merit_concession_applications')) {
            Schema::rename(
                'merit_concession_applications',
                'scholarship_exam_applications'
            );
        }
    }
};