<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_supply_kits', function (Blueprint $table) {
            $table->id();

            // Student receiving the kit
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Kit template assigned to the student
            $table->foreignId('kit_template_id')
                ->constrained('kit_templates')
                ->restrictOnDelete();

            $table->string('academic_year', 20);

            $table->date('distribution_date')
                ->nullable();

            $table->enum('status', [
                'pending',
                'issued',
                'cancelled',
            ])->default('pending');

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            $table->index([
                'academic_year',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_supply_kits');
    }
};