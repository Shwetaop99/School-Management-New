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
        Schema::create('exam_schedules', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Exam
            |--------------------------------------------------------------------------
            */

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Exam Class
            |--------------------------------------------------------------------------
            */

            $table->foreignId('exam_class_id')
                ->constrained('exam_classes')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Exam Class Section
            |--------------------------------------------------------------------------
            */

            $table->foreignId('exam_class_section_id')
                ->nullable()
                ->constrained('exam_class_sections')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Subject
            |--------------------------------------------------------------------------
            |
            | Subject will be connected later when the Class Module
            | and Subject structure are created.
            |
            | We intentionally do NOT create subject_id foreign key
            | at this stage.
            |
            */

            $table->unsignedBigInteger('subject_id')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Examination Date
            |--------------------------------------------------------------------------
            */

            $table->date('exam_date');


            /*
            |--------------------------------------------------------------------------
            | Examination Time
            |--------------------------------------------------------------------------
            */

            $table->time('start_time');

            $table->time('end_time');


            /*
            |--------------------------------------------------------------------------
            | Marks
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('max_marks')
                ->default(100);

            $table->unsignedInteger('pass_marks')
                ->default(35);


            /*
            |--------------------------------------------------------------------------
            | Room / Hall
            |--------------------------------------------------------------------------
            */

            $table->string('room_no', 50)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Instructions / Notes
            |--------------------------------------------------------------------------
            */

            $table->text('instructions')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'scheduled',
                'completed',
                'cancelled',
            ])->default('scheduled');


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('exam_id');

            $table->index('exam_class_id');

            $table->index('exam_class_section_id');

            $table->index('subject_id');

            $table->index('exam_date');

            $table->index('status');


            /*
            |--------------------------------------------------------------------------
            | Composite Index
            |--------------------------------------------------------------------------
            |
            | Useful for timetable queries:
            | Exam + Class + Section + Date
            |
            */

            $table->index(
    [
        'exam_id',
        'exam_class_id',
        'exam_class_section_id',
        'exam_date',
    ],
    'exam_schedule_lookup_index'
);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};
