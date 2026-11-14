<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The results table was already created by
     * the September 25 migration.
     */
    public function up(): void
    {
        // Intentionally empty: preserve the existing results table.
        // Avoid creating a table that already exists.
        if (Schema::hasTable('results')) {
            return;
        }

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

    /**
     * Preserve the existing results table on rollback.
     */
    public function down(): void
    {
        // Intentionally empty.
        // Preserve the existing results table and its data.
    }
};
