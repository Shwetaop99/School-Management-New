<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();

            // Route Information
            $table->string('route_number')->unique();
            $table->string('route_name');

            // Journey Details
            $table->string('starting_point');
            $table->string('destination');

            // Stops
            $table->text('stops')->nullable();

            // Vehicle & Driver
            $table->string('assigned_vehicle')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();

            // Route Status
            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            // Additional Information
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_routes');
    }
};