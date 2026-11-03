
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
        // Preserve the existing exam_classes table.
        if (Schema::hasTable('exam_classes')) {
            return;
        }

        Schema::create('exam_classes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->string('class_name', 50);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();

            $table->unique([
                'exam_id',
                'class_name',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally do not drop the existing exam_classes table.
        // It is managed by the newer exam migration structure.
    }
};
