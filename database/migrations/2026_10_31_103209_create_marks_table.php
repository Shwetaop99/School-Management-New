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
    Schema::create('marks', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
              ->constrained('students')
              ->onDelete('cascade');

        $table->foreignId('subject_id')
              ->constrained('subjects')
              ->onDelete('cascade');

        $table->string('exam_type');          // midterm, final, quiz, etc.
        $table->decimal('marks', 5, 2);
        $table->decimal('max_marks', 5, 2)->default(100);
        $table->string('academic_year')->nullable();
        $table->text('remarks')->nullable();

        $table->timestamps();

        // Prevent same student getting marks twice for same subject + exam
        $table->unique(
            ['student_id', 'subject_id', 'exam_type', 'academic_year'],
            'unique_student_subject_exam'
        );
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
