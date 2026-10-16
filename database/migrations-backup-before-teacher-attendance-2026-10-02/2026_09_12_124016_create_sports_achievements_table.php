<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports_achievements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('student_name')->nullable();
            $table->string('sport_name');
            $table->string('achievement_type')->nullable();

            $table->string('academic_year')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();

            $table->string('competition_name')->nullable();
            $table->string('position')->nullable();

            $table->date('achievement_date')->nullable();
            $table->string('venue')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sports_achievements');
    }
};