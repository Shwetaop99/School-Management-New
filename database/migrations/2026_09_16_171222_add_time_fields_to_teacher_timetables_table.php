<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {

            $table->unsignedInteger('duration_minutes')
                ->nullable()
                ->after('end_time');

            $table->string('lecture_type')
                ->default('regular')
                ->after('duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {

            $table->dropColumn([
                'duration_minutes',
                'lecture_type',
            ]);
        });
    }
};