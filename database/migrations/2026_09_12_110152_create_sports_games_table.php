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
        Schema::create('sports_games', function (Blueprint $table) {

            $table->id();

            // Basic Game / Event Information
            $table->string('title');
            $table->string('sport_name');
            $table->string('event_type')->nullable();

            // Academic Information
            $table->string('academic_year')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();

            // Schedule
            $table->date('event_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Location
            $table->string('venue')->nullable();

            // Game Details
            $table->string('organizer')->nullable();
            $table->text('description')->nullable();

            // Status
            $table->enum('status', [
                'upcoming',
                'ongoing',
                'completed',
                'cancelled'
            ])->default('upcoming');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_games');
    }
};