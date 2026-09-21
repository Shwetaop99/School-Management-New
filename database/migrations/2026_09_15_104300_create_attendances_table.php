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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            // Student
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Academic information
            $table->string('academic_year', 20);

            $table->string('class', 50);

            $table->string('section', 10);

            // Attendance date
            $table->date('attendance_date');

            // Attendance status
            $table->enum('status', [
                'present',
                'absent',
                'leave',
                'half_day',
                'late',
            ]);

            // Optional remarks
            $table->text('remarks')->nullable();

            // User who marked attendance
            $table->foreignId('marked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // One attendance record per student per day
            $table->unique(
                ['student_id', 'attendance_date'],
                'student_attendance_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};