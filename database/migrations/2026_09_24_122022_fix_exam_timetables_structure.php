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
        | 1. Drop existing foreign keys first
        |--------------------------------------------------------------------------
        |
        | The existing composite unique index is being used by these
        | foreign keys. Therefore, foreign keys must be removed first.
        |
        */

        Schema::table('exam_timetables', function (Blueprint $table) {

            $table->dropForeign('exam_timetables_exam_id_foreign');

            $table->dropForeign('exam_timetables_class_id_foreign');

            $table->dropForeign('exam_timetables_section_id_foreign');

            $table->dropForeign('exam_timetables_exam_subject_id_foreign');

            $table->dropForeign('exam_timetables_session_id_foreign');
        });


        /*
        |--------------------------------------------------------------------------
        | 2. Drop old unique index
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

        if (Schema::hasColumn('exam_timetables', 'section_id')) {

            Schema::table('exam_timetables', function (Blueprint $table) {

                $table->dropColumn('section_id');
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Recreate exam_id foreign key
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
        | 5. Recreate class_id foreign key
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Class Management uses school_classes.
        |
        */

        Schema::table('exam_timetables', function (Blueprint $table) {

            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | 6. Recreate exam_subject_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {

            $table->foreign('exam_subject_id')
                ->references('id')
                ->on('exam_subjects')
                ->cascadeOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | 7. Recreate session_id foreign key
        |--------------------------------------------------------------------------
        */

        Schema::table('exam_timetables', function (Blueprint $table) {

            $table->foreign('session_id')
                ->references('id')
                ->on('exam_sessions')
                ->cascadeOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | 8. Create new unique index
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

        if (!Schema::hasColumn('exam_timetables', 'section_id')) {

            Schema::table('exam_timetables', function (Blueprint $table) {

                $table->unsignedBigInteger('section_id')
                    ->nullable()
                    ->after('class_id');
            });
        }


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
        | Restore old unique index
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