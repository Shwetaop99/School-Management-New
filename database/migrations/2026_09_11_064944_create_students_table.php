<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // =========================================================
            // BASIC INFORMATION
            // =========================================================
            $table->string('student_id')->unique();
            $table->string('roll_number')->nullable();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('marathi_name')->nullable();

            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('aadhar_card_no', 12)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email')->nullable();

            $table->string('profile_image')->nullable();

            // =========================================================
            // ACADEMIC INFORMATION
            // =========================================================
            $table->string('academic_year')->nullable();

            $table->string('class')->nullable();
            $table->string('section')->nullable();

            $table->string('admission_class')->nullable();
            $table->date('admission_date')->nullable();

            $table->string('register_no')->nullable();
            $table->string('book_no')->nullable();
            $table->string('appar_id')->nullable();
            $table->string('pen_no')->nullable();

            $table->string('medium')->nullable();
            $table->string('mother_tongue')->nullable();

            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('sub_caste')->nullable();

            $table->string('status')->default('active');

            // =========================================================
            // PARENTS / GUARDIAN INFORMATION
            // =========================================================
            $table->string('father_name')->nullable();
            $table->string('father_phone', 15)->nullable();
            $table->string('father_occupation')->nullable();

            $table->string('mother_name')->nullable();
            $table->string('mother_phone', 15)->nullable();
            $table->string('mother_occupation')->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_phone', 15)->nullable();

            // =========================================================
            // PREVIOUS SCHOOL INFORMATION
            // =========================================================
            $table->string('previous_school_name')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('previous_school_class')->nullable();

            $table->string('previous_school_medium')->nullable();
            $table->string('previous_school_board')->nullable();

            $table->string('previous_school_result')->nullable();
            $table->text('previous_school_remarks')->nullable();

            // =========================================================
            // ADDRESS INFORMATION
            // =========================================================
            $table->text('address')->nullable();

            $table->string('country')->default('India');
            $table->string('state')->default('Maharashtra');
            $table->string('district')->nullable();
            $table->string('taluka')->nullable();

            $table->string('city_village')->nullable();
            $table->string('pincode', 10)->nullable();

            $table->timestamps();

            // Indexes
            $table->index('class');
            $table->index('section');
            $table->index('academic_year');
            $table->index('status');
            $table->index('district');
            $table->index('taluka');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};