<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_subjects', function (Blueprint $table) {
            $table->unsignedInteger('internal_max_marks')
                ->default(0)
                ->after('subject_id');

            $table->unsignedInteger('theory_max_marks')
                ->default(0)
                ->after('internal_max_marks');
        });
    }

    public function down(): void
    {
        Schema::table('exam_subjects', function (Blueprint $table) {
            $table->dropColumn([
                'internal_max_marks',
                'theory_max_marks',
            ]);
        });
    }
};