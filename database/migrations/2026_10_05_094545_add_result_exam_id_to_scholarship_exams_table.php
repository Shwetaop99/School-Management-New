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
        Schema::table('scholarship_exams', function (Blueprint $table) {
            $table->foreignId('result_exam_id')
                ->nullable()
                ->after('exam_type')
                ->constrained('exams')
                ->nullOnDelete();

            $table->index('result_exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarship_exams', function (Blueprint $table) {
            $table->dropForeign([
                'result_exam_id',
            ]);

            $table->dropIndex([
                'result_exam_id',
            ]);

            $table->dropColumn('result_exam_id');
        });
    }
};