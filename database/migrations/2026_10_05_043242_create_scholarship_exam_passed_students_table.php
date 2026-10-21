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
        Schema::create('scholarship_exam_passed_students', function (Blueprint $table) {
            $table->id();

            // Scholarship Exam
            $table->foreignId('scholarship_exam_id')
                ->constrained('scholarship_exams')
                ->cascadeOnDelete();

            // Existing Student Module
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Result Information
            $table->decimal('marks', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();

            // Scholarship Information
            $table->boolean('scholarship_received')->default(false);
            $table->decimal('scholarship_amount', 10, 2)->nullable();

            // Additional Information
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Prevent the same student from being recorded twice
            // for the same scholarship exam.
            $table->unique(
                ['scholarship_exam_id', 'student_id'],
                'scholarship_exam_student_unique'
            );

            // Useful for student-wise history
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_exam_passed_students');
    }
};
