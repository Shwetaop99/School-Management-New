<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {

            $table->string('academic_year')
                ->nullable()
                ->after('teacher_id');

            $table->unsignedInteger('period_number')
                ->nullable()
                ->after('day');

            $table->string('period_type')
                ->default('Regular')
                ->after('period_number');

            $table->string('subject_type')
                ->default('Academic')
                ->after('subject');

        });
    }

    public function down(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {

            $table->dropColumn([
                'academic_year',
                'period_number',
                'period_type',
                'subject_type',
            ]);

        });
    }
};