
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasTable('kit_templates') ||
            Schema::hasColumn('kit_templates', 'class_id')
        ) {
            return;
        }

        Schema::table('kit_templates', function (Blueprint $table) {
            $table->foreignId('class_id')
                ->nullable()
                ->constrained('school_classes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Preserve the existing database structure and data.
    }
};
