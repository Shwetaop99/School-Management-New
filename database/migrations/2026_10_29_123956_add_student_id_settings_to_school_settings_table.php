<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('school_settings', 'student_id_format')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->string('student_id_format')
                    ->default('STU{YYYY}{####}');
            });
        }

        if (!Schema::hasColumn('school_settings', 'student_id_start')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->unsignedInteger('student_id_start')->default(1);
            });
        }

        if (!Schema::hasColumn('school_settings', 'student_id_length')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->unsignedInteger('student_id_length')->default(4);
            });
        }
    }

    public function down(): void
    {
        // Intentionally leave these columns in place
        // to avoid deleting existing school settings.
    }
};