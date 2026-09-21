<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {

            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');

            $table->string('email')->unique();
            $table->string('phone')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'Male',
                'Female',
                'Other'
            ])->nullable();


            // Professional Information
            $table->string('employee_id')->unique();

            $table->string('designation')->nullable();

            $table->string('department')->nullable();

            $table->string('qualification')->nullable();

            $table->date('joining_date')->nullable();


            // Address
            $table->text('address')->nullable();


            // Profile Image
            $table->string('profile_image')->nullable();


            // Status
            $table->enum('status', [
                'Active',
                'Inactive'
            ])->default('Active');


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};