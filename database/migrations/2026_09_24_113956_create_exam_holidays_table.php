<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_holidays', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->date('holiday_date');

            $table->string('reason')->nullable();

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            $table->unique(
                ['exam_id', 'holiday_date'],
                'exam_holiday_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_holidays');
    }
};