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
        Schema::create('exam_classes', function (Blueprint $table) {
            $table->id();

            // Examination
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            // Existing class from the Classes module
            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->timestamps();

            // Same class cannot be selected twice for the same exam
            $table->unique(
                ['exam_id', 'class_id'],
                'exam_class_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_classes');
    }
};