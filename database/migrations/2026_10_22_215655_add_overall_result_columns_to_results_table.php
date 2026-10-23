
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'academic_year')) {
                $table->string('academic_year', 20)->nullable();
            }

            if (!Schema::hasColumn('results', 'class_name')) {
                $table->string('class_name', 50)->nullable();
            }

            if (!Schema::hasColumn('results', 'section')) {
                $table->string('section', 20)->nullable();
            }

            if (!Schema::hasColumn('results', 'total_marks')) {
                $table->decimal('total_marks', 8, 2)->default(0);
            }

            if (!Schema::hasColumn('results', 'obtained_marks')) {
                $table->decimal('obtained_marks', 8, 2)->default(0);
            }

            if (!Schema::hasColumn('results', 'percentage')) {
                $table->decimal('percentage', 5, 2)->default(0);
            }

            if (!Schema::hasColumn('results', 'result_status')) {
                $table->enum('result_status', [
                    'pass',
                    'fail',
                    'absent',
                    'pending',
                ])->default('pending');
            }

            if (!Schema::hasColumn('results', 'generated_at')) {
                $table->timestamp('generated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $columns = [
                'academic_year',
                'class_name',
                'section',
                'total_marks',
                'obtained_marks',
                'percentage',
                'result_status',
                'generated_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('results', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
