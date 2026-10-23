
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('result_versions')) {
            return;
        }

        $columns = [
            'student_id_snapshot'   => fn (Blueprint $table) => $table->string('student_id_snapshot', 100)->nullable(),
            'student_name_snapshot' => fn (Blueprint $table) => $table->string('student_name_snapshot', 255)->nullable(),
            'exam_name_snapshot'    => fn (Blueprint $table) => $table->string('exam_name_snapshot', 255)->nullable(),
            'academic_year'         => fn (Blueprint $table) => $table->string('academic_year', 20)->nullable(),
            'class_name'            => fn (Blueprint $table) => $table->string('class_name', 100)->nullable(),
            'section'               => fn (Blueprint $table) => $table->string('section', 50)->nullable(),
            'total_marks'           => fn (Blueprint $table) => $table->decimal('total_marks', 10, 2)->nullable(),
            'obtained_marks'        => fn (Blueprint $table) => $table->decimal('obtained_marks', 10, 2)->nullable(),
            'percentage'            => fn (Blueprint $table) => $table->decimal('percentage', 5, 2)->nullable(),
            'grade'                 => fn (Blueprint $table) => $table->string('grade', 20)->nullable(),
            'result_status'         => fn (Blueprint $table) => $table->string('result_status', 50)->nullable(),
            'generated_at'          => fn (Blueprint $table) => $table->timestamp('generated_at')->nullable(),
        ];

        foreach ($columns as $name => $definition) {
            if (!Schema::hasColumn('result_versions', $name)) {
                Schema::table('result_versions', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        }
    }

    public function down(): void
    {
        // Intentionally leave columns in place to avoid removing
        // snapshot data that may already exist.
    }
};
