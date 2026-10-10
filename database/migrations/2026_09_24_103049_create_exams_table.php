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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            // Academic year of the examination
            $table->string('academic_year', 20);

            // Examination name
            $table->string('exam_name');

            // Example: Unit Test, Mid Term, Final, Annual
            $table->string('exam_type');

            // First possible examination date
            $table->date('start_date');

            // draft, scheduled, generated, finalized
            $table->string('status')->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};