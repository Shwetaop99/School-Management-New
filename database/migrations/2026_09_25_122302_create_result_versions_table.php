<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_version_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('result_version_id')
                ->constrained('result_versions')
                ->cascadeOnDelete();

            /*
             * Subject ID is intentionally not a foreign key.
             *
             * This protects historical results if a subject is
             * later edited, deleted, or replaced.
             */
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->string('subject_name', 150);

            $table->decimal('max_marks', 8, 2)->default(0);

            $table->decimal('internal_marks', 8, 2)->default(0);

            $table->decimal('theory_marks', 8, 2)->default(0);

            $table->decimal('practical_marks', 8, 2)->default(0);

            $table->decimal('total_marks', 8, 2)->default(0);

            $table->decimal('obtained_marks', 8, 2)->default(0);

            $table->string('grade', 10)->nullable();

            $table->decimal('grade_point', 5, 2)->nullable();

            /*
             * Store the student's status for this subject.
             * Example: present / absent / na
             */
            $table->enum('status', [
                'present',
                'absent',
                'na'
            ])->default('present');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(
                ['result_version_id', 'subject_id'],
                'result_version_details_version_subject_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_version_details');
    }
};
