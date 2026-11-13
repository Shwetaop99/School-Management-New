
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
        // Preserve the existing exams table.
        if (Schema::hasTable('exams')) {
            return;
        }

        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->string('academic_year', 20);
            $table->string('exam_name');
            $table->string('exam_type', 100);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'completed',
            ])->default('active');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('academic_year');
            $table->index('exam_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally do not drop the existing exams table.
        // It is managed by the newer exam migration structure.
    }
};
