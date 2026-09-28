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

            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->nullOnDelete();

            $table->string('subject_name');

            $table->decimal('max_marks', 8, 2)->default(0);

            $table->decimal('internal_marks', 8, 2)->default(0);

            $table->decimal('theory_marks', 8, 2)->default(0);

            $table->decimal('practical_marks', 8, 2)->default(0);

            $table->decimal('total_marks', 8, 2)->default(0);

            $table->decimal('obtained_marks', 8, 2)->default(0);

            $table->string('grade')->nullable();

            $table->decimal('grade_point', 5, 2)->nullable();

            $table->string('status')->default('present');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index([
                'result_version_id',
                'subject_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_version_details');
    }
};
