
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarship_exam_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('scholarship_exam_applications', 'scholarship_exam_id')) {
                $table->foreignId('scholarship_exam_id')
                    ->nullable()
                    ->constrained('scholarship_exams')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('scholarship_exam_applications', 'student_id')) {
                $table->foreignId('student_id')
                    ->nullable()
                    ->constrained('students')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('scholarship_exam_applications', 'status')) {
                $table->enum('status', ['applied', 'appeared', 'passed'])
                    ->default('applied');
            }

            if (!Schema::hasColumn('scholarship_exam_applications', 'remarks')) {
                $table->text('remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Preserve existing scholarship application data.
    }
};
