<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('exam_subject_id')
                ->constrained('exam_subjects')
                ->cascadeOnDelete();

            $table->decimal('marks_obtained', 8, 2)
                ->nullable();

            $table->string('grade', 10)
                ->nullable();

            $table->string('remarks', 255)
                ->nullable();

            $table->timestamps();

            $table->unique(
                ['exam_id', 'student_id', 'exam_subject_id'],
                'results_exam_student_subject_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};