<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_timetables', function (Blueprint $table) {
            $table->id();

            /*
             * Exam
             */
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            /*
             * Existing Class
             */
            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            /*
             * Existing Section
             */
            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            /*
             * Exam Subject
             *
             * This connects to exam_subjects so we automatically
             * get the subject, marks and duration configured
             * for this particular exam.
             */
            $table->foreignId('exam_subject_id')
                ->constrained('exam_subjects')
                ->cascadeOnDelete();

            /*
             * Exam Session
             */
            $table->foreignId('session_id')
                ->constrained('exam_sessions')
                ->cascadeOnDelete();

            /*
             * Actual exam date
             */
            $table->date('exam_date');

            /*
             * Actual scheduled time
             *
             * These are stored separately because the timetable
             * may eventually need to preserve the exact generated
             * start/end time.
             */
            $table->time('start_time');
            $table->time('end_time');

            /*
             * Snapshot of exam configuration.
             *
             * If marks/duration are later changed in exam_subjects,
             * the already-generated timetable should still retain
             * what was scheduled.
             */
            $table->unsignedInteger('maximum_marks');
            $table->unsignedInteger('duration_minutes');

            /*
             * Timetable status
             */
            $table->string('status')->default('scheduled');

            $table->timestamps();

            /*
             * Prevent the same subject from being scheduled twice
             * for the same class/section/date/session.
             */
            $table->unique(
                [
                    'exam_id',
                    'class_id',
                    'section_id',
                    'exam_subject_id',
                    'exam_date',
                    'session_id'
                ],
                'exam_timetable_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_timetables');
    }
};