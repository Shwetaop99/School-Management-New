
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

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->foreignId('exam_subject_id')
                ->constrained('exam_subjects')
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('exam_sessions')
                ->cascadeOnDelete();

            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedInteger('maximum_marks');
            $table->unsignedInteger('duration_minutes');

            $table->string('status')->default('scheduled');

            $table->timestamps();

            $table->unique(
                [
                    'exam_id',
                    'class_id',
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
