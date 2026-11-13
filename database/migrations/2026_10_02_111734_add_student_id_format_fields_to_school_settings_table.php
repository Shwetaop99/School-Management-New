<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->string('student_id_format')
                ->default('STU-{YEAR}-{NUMBER}')
                ->after('school_code');

            $table->unsignedInteger('student_id_start')
                ->default(1)
                ->after('student_id_format');

            $table->unsignedInteger('student_id_length')
                ->default(4)
                ->after('student_id_start');
        });
    }

    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn([
                'student_id_format',
                'student_id_start',
                'student_id_length',
            ]);
        });
    }
};