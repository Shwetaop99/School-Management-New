
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('exam_classes', 'class_id')) {
            Schema::table('exam_classes', function (Blueprint $table) {
                $table->foreignId('class_id')
                    ->nullable()
                    ->constrained('school_classes')
                    ->cascadeOnDelete();
            });
        }

        if (
            Schema::hasColumn('exam_classes', 'class_name')
        ) {
            Schema::table('exam_classes', function (Blueprint $table) {
                $table->string('class_name')->nullable()->change();
            });
        }

        if (!Schema::hasIndex('exam_classes', 'exam_class_unique')) {
            Schema::table('exam_classes', function (Blueprint $table) {
                $table->unique(
                    ['exam_id', 'class_id'],
                    'exam_class_unique'
                );
            });
        }
    }

    public function down(): void
    {
        // Preserve the existing exam_classes structure.
    }
};
