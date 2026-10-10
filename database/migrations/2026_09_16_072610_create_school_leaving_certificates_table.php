<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_leaving_certificates', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('certificate_no')->nullable()->unique();

            $table->date('leaving_date')->nullable();

            $table->string('progress')->nullable();

            $table->string('conduct')->nullable();

            $table->string('class_studying_since')->nullable();

            $table->string('reason_for_leaving')->nullable();

            $table->string('remarks')->nullable();

            $table->date('certificate_date')->nullable();

            $table->string('class_teacher_name')->nullable();

            $table->string('principal_name')->nullable();

            $table->string('status')
                ->default('issued');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_leaving_certificates');
    }
};