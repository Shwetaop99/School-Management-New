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
        Schema::create('scholarship_exam_classes', function (Blueprint $table) {
            $table->id();

            // Scholarship Exam
            $table->foreignId('scholarship_exam_id')
                ->constrained('scholarship_exams')
                ->cascadeOnDelete();

            // Existing Class Module
            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->timestamps();

            // Prevent the same class from being added twice
            // Custom short index name for MySQL compatibility
            $table->unique(
                ['scholarship_exam_id', 'school_class_id'],
                'scholarship_exam_class_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_exam_classes');
    }
};
