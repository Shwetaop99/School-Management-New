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
        Schema::create('id_cards', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Student Relationship
            |--------------------------------------------------------------------------
            */
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ID Card Information
            |--------------------------------------------------------------------------
            */
            $table->string('card_number', 50)
                ->unique();

            $table->string('template', 50)
                ->default('standard');

            $table->string('academic_year', 20)
                ->nullable();

            $table->date('issued_date')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('student_id');
            $table->index('academic_year');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_cards');
    }
};