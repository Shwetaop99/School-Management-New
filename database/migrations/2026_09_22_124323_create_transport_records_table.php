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
        Schema::create('transport_records', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Transport Details
            |--------------------------------------------------------------------------
            */

            $table->string('route')->nullable();

            $table->string('vehicle')->nullable();

            $table->string('pickup_point')->nullable();

            $table->string('drop_point')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Transport Status
            |--------------------------------------------------------------------------
            */

            $table->enum('transport_status', [
                'active',
                'inactive'
            ])->default('active');


            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Transport History / Dates
            |--------------------------------------------------------------------------
            */

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_records');
    }
};