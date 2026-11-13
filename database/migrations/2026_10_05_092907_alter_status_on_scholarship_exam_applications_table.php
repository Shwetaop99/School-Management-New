<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarship_exam_applications', function (Blueprint $table) {
            $table->enum('status', [
                'not_applied',
                'applied',
                'appeared',
                'passed',
            ])->default('applied')->change();
        });
    }

    public function down(): void
    {
        Schema::table('scholarship_exam_applications', function (Blueprint $table) {
            $table->enum('status', [
                'applied',
                'appeared',
                'passed',
            ])->default('applied')->change();
        });
    }
};
