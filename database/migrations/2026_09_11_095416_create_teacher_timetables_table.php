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
    Schema::create('teacher_timetables', function (Blueprint $table) {

        $table->id();

        // Teacher
        $table->foreignId('teacher_id')
            ->constrained('teachers')
            ->cascadeOnDelete();

        // Timetable details
        $table->string('day');

        $table->string('class');

        $table->string('section')->nullable();

        $table->string('subject');

        // Time
        $table->time('start_time');

        $table->time('end_time');

        // Optional room information
        $table->string('room')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_timetables');
    }
};
