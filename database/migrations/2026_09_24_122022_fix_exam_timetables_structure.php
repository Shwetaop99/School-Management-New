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
        | 1. Drop the existing exam_id foreign key
        |--------------------------------------------------------------------------
        |
        | MySQL is currently using the old unique index to support this FK.
        | Therefore the FK must be removed BEFORE dropping the unique index.
        |
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropForeign('exam_timetables_exam_id_foreign');
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Drop the old unique index
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropUnique('exam_timetable_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Remove section_id
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {
            $table->dropColumn('section_id');
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Add correct foreign keys
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {

            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->cascadeOnDelete();

            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
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
        | 5. Create new unique index
        |--------------------------------------------------------------------------
        |
        | section_id is no longer needed because section comes from
        | school_classes.section.
        |
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
            $table->dropForeign('exam_timetables_exam_id_foreign');
            $table->dropForeign('exam_timetables_class_id_foreign');
            $table->dropForeign('exam_timetables_exam_subject_id_foreign');
            $table->dropForeign('exam_timetables_session_id_foreign');
        });

        /*
        |--------------------------------------------------------------------------
        | Remove new unique index
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
