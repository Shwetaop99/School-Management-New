<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Remove section_id column
        |--------------------------------------------------------------------------
        |
        | The old section_id foreign key was already removed manually because
        | the database was preventing the column from being dropped.
        |
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropColumn('section_id');
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Add correct exam_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Fix class_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign([
                'class_id',
            ]);

            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Recreate exam_subject_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign([
                'exam_subject_id',
            ]);

            $table->foreign('exam_subject_id')
                ->references('id')
                ->on('exam_subjects')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Recreate session_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign([
                'session_id',
            ]);

            $table->foreign('session_id')
                ->references('id')
                ->on('exam_sessions')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Create correct unique index
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->unique(
                [
                    'exam_id',
                    'class_id',
                    'exam_subject_id',
                    'exam_date',
                    'session_id',
                ],
                'exam_timetable_unique'
            );
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Remove foreign keys
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign([
                'exam_id',
            ]);

            $table->dropForeign([
                'class_id',
            ]);

            $table->dropForeign([
                'exam_subject_id',
            ]);

            $table->dropForeign([
                'session_id',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Remove unique index
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropUnique('exam_timetable_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Restore section_id
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')
                ->nullable()
                ->after('class_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Restore old foreign keys
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->cascadeOnDelete();

            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->cascadeOnDelete();

            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnDelete();

            $table->foreign('exam_subject_id')
                ->references('id')
                ->on('exam_subjects')
                ->cascadeOnDelete();

            $table->foreign('session_id')
                ->references('id')
                ->on('exam_sessions')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | Restore original unique index
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->unique(
                [
                    'exam_id',
                    'class_id',
                    'section_id',
                    'exam_subject_id',
                    'exam_date',
                    'session_id',
                ],
                'exam_timetable_unique'
            );
        });
    }
};