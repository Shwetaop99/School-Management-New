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
        Schema::create('scholarship_exams', function (Blueprint $table) {
            $table->id();

            // Basic Exam Information
            $table->string('exam_name');
            $table->string('academic_year', 20);
            $table->date('exam_date')->nullable();
            $table->string('exam_type')->nullable();
            $table->string('conducted_by')->nullable();

            // Exam Statistics
            $table->unsignedInteger('total_eligible')->default(0);
            $table->unsignedInteger('total_applied')->default(0);
            $table->unsignedInteger('total_appeared')->default(0);
            $table->unsignedInteger('total_passed')->default(0);

            // Additional Information
            $table->text('remarks')->nullable();

            // Record Status
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Useful indexes for filtering/history
            $table->index('academic_year');
            $table->index('exam_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_exams');
    }
};