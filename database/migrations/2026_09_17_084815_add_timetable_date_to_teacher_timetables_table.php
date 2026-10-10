<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {
            $table->date('timetable_date')
                ->nullable()
                ->after('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_timetables', function (Blueprint $table) {
            $table->dropColumn('timetable_date');
        });
    }
};