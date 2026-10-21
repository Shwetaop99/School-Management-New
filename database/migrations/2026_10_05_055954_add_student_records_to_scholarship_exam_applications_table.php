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
        Schema::create('scholarship_exam_applications', function (Blueprint $table) {

            $table->id();

            // Scholarship Exam
            $table->foreignId('scholarship_exam_id')
                ->constrained('scholarship_exams')
                ->cascadeOnDelete();

            // Existing Student Module
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Student participation status
            $table->enum('status', [
                'applied',
                'appeared',
                'passed',
            ])->default('applied');

            // Additional information
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Prevent duplicate student record
            // for the same scholarship exam.
            $table->unique(
                ['scholarship_exam_id', 'student_id'],
                'scholarship_exam_application_unique'
            );

            // Useful indexes
            $table->index('student_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_exam_applications');
    }
};