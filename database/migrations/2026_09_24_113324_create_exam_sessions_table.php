<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();

            // Exam this session belongs to
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            // Session name
            $table->string('session_name');

            // Session start and end time
            $table->time('start_time');
            $table->time('end_time');

            // Active/inactive
            $table->boolean('status')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};