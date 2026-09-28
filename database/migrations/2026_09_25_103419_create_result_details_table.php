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
        Schema::create('result_details', function (Blueprint $table) {
            $table->id();

            // Main result
            $table->foreignId('result_id')
                ->constrained('results')
                ->cascadeOnDelete();

            // Subject
            $table->unsignedBigInteger('subject_id')->nullable();

            // Save subject name as a snapshot
            $table->string('subject_name', 150);

            // Marks configuration
            $table->decimal('max_marks', 8, 2)->default(0);

            $table->decimal('internal_marks', 8, 2)->default(0);
            $table->decimal('theory_marks', 8, 2)->default(0);
            $table->decimal('practical_marks', 8, 2)->default(0);

            // Subject total
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('obtained_marks', 8, 2)->default(0);

            // Grade
            $table->string('grade', 10)->nullable();
            $table->decimal('grade_point', 5, 2)->nullable();

            $table->timestamps();

            // Same subject should not appear twice in one result
            $table->unique(
                ['result_id', 'subject_id'],
                'result_details_result_subject_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_details');
    }
};