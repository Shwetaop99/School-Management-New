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
        Schema::create('transport_vehicles', function (Blueprint $table) {
            $table->id();

            // Vehicle Details
            $table->string('vehicle_number')->unique();
            $table->string('vehicle_type');
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_color')->nullable();

            // Capacity
            $table->unsignedInteger('capacity')->default(0);

            // Driver Details
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            $table->string('driver_license_number')->nullable();

            // Documents
            $table->date('insurance_expiry')->nullable();
            $table->date('fitness_expiry')->nullable();
            $table->date('permit_expiry')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'inactive',
                'maintenance'
            ])->default('active');

            // Additional Information
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_vehicles');
    }
};