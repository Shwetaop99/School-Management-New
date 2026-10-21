<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();

            /*
             * Exam
             */
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            /*
             * Student
             */
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            /*
             * Exam class configuration
             */
            $table->foreignId('exam_class_id')
                ->nullable()
                ->constrained('exam_classes')
                ->nullOnDelete();

            /*
             * Subject
             *
             * Keep this nullable because the existing
             * exam_subjects structure may use its own
             * subject relationship.
             */
            $table->unsignedBigInteger('subject_id')->nullable();

            /*
             * Marks
             */
            $table->decimal('internal_marks', 8, 2)
                ->default(0);

            $table->decimal('theory_marks', 8, 2)
                ->default(0);

            $table->decimal('practical_marks', 8, 2)
                ->default(0);

            /*
             * Maximum marks for this subject in this exam.
             *
             * This is NOT fixed to 100 because different
             * exams/classes/subjects may have different
             * maximum marks.
             */
            $table->decimal('max_marks', 8, 2)
                ->default(100);

            /*
             * Automatically calculated:
             *
             * internal + theory + practical
             */
            $table->decimal('total_marks', 8, 2)
                ->default(0);

            /*
             * Student status for this subject.
             */
            $table->enum('status', [
                'present',
                'absent',
                'na'
            ])->default('present');

            $table->text('remarks')->nullable();

            $table->timestamps();

            /*
             * A student should have only one marks record
             * for a particular exam + subject.
             */
            $table->unique(
                ['exam_id', 'student_id', 'subject_id'],
                'exam_marks_exam_student_subject_unique'
            );

            /*
             * Indexes for faster result generation.
             */
            $table->index(
                ['exam_id', 'student_id'],
                'exam_marks_exam_student_index'
            );

            $table->index(
                ['exam_id', 'subject_id'],
                'exam_marks_exam_subject_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};