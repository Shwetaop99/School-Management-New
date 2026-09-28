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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // Student and exam
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            // Snapshot information
            $table->string('academic_year', 20);
            $table->string('class_name', 50);
            $table->string('section', 20)->nullable();

            // Overall result
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('obtained_marks', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);

            $table->string('grade', 10)->nullable();

            $table->enum('result_status', [
                'pass',
                'fail',
                'absent',
                'pending'
            ])->default('pending');

            $table->text('remarks')->nullable();

            // When this result was generated
            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            // Prevent duplicate result for same student + exam
            $table->unique(
                ['student_id', 'exam_id'],
                'results_student_exam_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};