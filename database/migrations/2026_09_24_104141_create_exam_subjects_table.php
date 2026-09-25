<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();

            // Exam
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            // Existing class
            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            // Existing subject from Subjects module
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            // Exam-specific configuration
            $table->unsignedInteger('maximum_marks');
            $table->unsignedInteger('passing_marks');
            $table->unsignedInteger('duration_minutes');

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            // Same subject should not be added twice
            // for the same exam and class.
            $table->unique(
                ['exam_id', 'class_id', 'subject_id'],
                'exam_class_subject_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_subjects');
    }
};