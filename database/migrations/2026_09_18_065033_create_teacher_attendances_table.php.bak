<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('status', [
                'Present',
                'Absent',
                'Half Day',
                'Late'
            ])->default('Present');

            $table->decimal('overtime_hours', 5, 2)->default(0);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique(['teacher_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};