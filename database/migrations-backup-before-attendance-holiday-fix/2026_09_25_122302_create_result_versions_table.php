<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('result_id')
                ->constrained('results')
                ->cascadeOnDelete();

            $table->unsignedInteger('version_no')->default(1);

            /*
             * Student snapshot
             */
            $table->string('student_id_snapshot')->nullable();
            $table->string('student_name_snapshot')->nullable();

            /*
             * Exam snapshot
             */
            $table->string('exam_name_snapshot')->nullable();

            /*
             * Academic / class snapshot
             */
            $table->string('academic_year')->nullable();
            $table->string('class_name')->nullable();
            $table->string('section')->nullable();

            /*
             * Marks / result snapshot
             */
            $table->decimal('total_marks', 10, 2)->default(0);
            $table->decimal('obtained_marks', 10, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);

            $table->string('grade')->nullable();

            $table->string('result_status')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['result_id', 'version_no'],
                'result_versions_result_version_unique'
            );

            $table->index(
                ['result_id', 'version_no'],
                'result_versions_result_version_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_versions');
    }
};