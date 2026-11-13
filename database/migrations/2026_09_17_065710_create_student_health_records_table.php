<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_health_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Checkup Information
            $table->string('academic_year', 20);
            $table->date('checkup_date');
            $table->string('checkup_type', 100)->nullable();
            $table->string('doctor_name', 150)->nullable();
            $table->string('health_center', 200)->nullable();
            $table->string('conducted_by', 150)->nullable();

            // Physical Measurements
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 6, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->unsignedSmallInteger('pulse_rate')->nullable();
            $table->string('blood_pressure', 30)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();

            // Vision
            $table->string('vision_right', 50)->nullable();
            $table->string('vision_left', 50)->nullable();
            $table->string('near_vision_right', 50)->nullable();
            $table->string('near_vision_left', 50)->nullable();
            $table->boolean('uses_spectacles')->default(false);
            $table->string('spectacle_power', 100)->nullable();

            // Dental
            $table->string('dental_status', 100)->nullable();
            $table->string('dental_caries', 100)->nullable();
            $table->string('gum_problem', 100)->nullable();
            $table->string('oral_hygiene', 100)->nullable();

            // ENT
            $table->string('right_ear', 100)->nullable();
            $table->string('left_ear', 100)->nullable();
            $table->string('hearing_problem', 100)->nullable();
            $table->string('nose_status', 100)->nullable();
            $table->string('throat_status', 100)->nullable();

            // General Health
            $table->string('general_health', 100)->nullable();
            $table->string('skin_status', 100)->nullable();
            $table->string('respiratory_status', 100)->nullable();
            $table->string('heart_status', 100)->nullable();
            $table->string('abdomen_status', 100)->nullable();
            $table->string('musculoskeletal_status', 100)->nullable();

            // Nutrition
            $table->string('nutritional_status', 100)->nullable();
            $table->string('anemia_screening', 100)->nullable();

            // Medical History
            $table->text('known_health_condition')->nullable();
            $table->text('allergy')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('medical_history')->nullable();

            // Referral / Follow-up
            $table->boolean('referral_required')->default(false);
            $table->string('referral_to', 200)->nullable();
            $table->date('referral_date')->nullable();
            $table->text('treatment_advised')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('follow_up_status', 100)->nullable();
            $table->text('follow_up_remarks')->nullable();

            // Final Assessment
            $table->string('overall_health_status', 100)->nullable();
            $table->text('doctor_remarks')->nullable();
            $table->text('teacher_remarks')->nullable();
            $table->text('parent_remarks')->nullable();

            $table->timestamps();

            // One annual health record per student
            $table->unique(
                ['student_id', 'academic_year'],
                'student_health_year_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_health_records');
    }
};