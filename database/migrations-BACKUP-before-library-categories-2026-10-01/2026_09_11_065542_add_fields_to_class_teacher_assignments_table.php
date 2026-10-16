<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_teacher_assignments', function (Blueprint $table) {

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('academic_year')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('class_teacher_assignments', function (Blueprint $table) {

            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['section_id']);

            $table->dropColumn([
                'teacher_id',
                'class_id',
                'section_id',
                'academic_year',
            ]);
        });
    }
};