<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_supply_kits', function (Blueprint $table) {
            $table->unique(
                ['student_id', 'academic_year'],
                'student_supply_kits_student_year_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('student_supply_kits', function (Blueprint $table) {
            $table->dropUnique(
                'student_supply_kits_student_year_unique'
            );
        });
    }
};