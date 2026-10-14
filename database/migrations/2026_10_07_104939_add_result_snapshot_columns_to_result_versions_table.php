<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('result_versions', function (Blueprint $table) {

            $table->string('student_id_snapshot', 100)
                ->nullable()
                ->after('result_id');

            $table->string('student_name_snapshot', 255)
                ->nullable()
                ->after('student_id_snapshot');

            $table->string('exam_name_snapshot', 255)
                ->nullable()
                ->after('student_name_snapshot');

            $table->string('academic_year', 20)
                ->nullable()
                ->after('exam_name_snapshot');

            $table->string('class_name', 100)
                ->nullable()
                ->after('academic_year');

            $table->string('section', 50)
                ->nullable()
                ->after('class_name');

            $table->decimal('total_marks', 10, 2)
                ->nullable()
                ->after('section');

            $table->decimal('obtained_marks', 10, 2)
                ->nullable()
                ->after('total_marks');

            $table->decimal('percentage', 5, 2)
                ->nullable()
                ->after('obtained_marks');

            $table->string('grade', 20)
                ->nullable()
                ->after('percentage');

            $table->string('result_status', 50)
                ->nullable()
                ->after('grade');

            $table->timestamp('generated_at')
                ->nullable()
                ->after('result_status');
        });
    }

    public function down(): void
    {
        Schema::table('result_versions', function (Blueprint $table) {

            $table->dropColumn([
                'student_id_snapshot',
                'student_name_snapshot',
                'exam_name_snapshot',
                'academic_year',
                'class_name',
                'section',
                'total_marks',
                'obtained_marks',
                'percentage',
                'grade',
                'result_status',
                'generated_at',
            ]);
        });
    }
};