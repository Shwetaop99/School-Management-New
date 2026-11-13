
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The correct class_id foreign key is created
        // in the exam_classes migration.
    }

    public function down(): void
    {
        // No changes to roll back.
    }
};
