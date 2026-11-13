
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The exam_subjects migration already references school_classes.
    }

    public function down(): void
    {
        // Preserve the correct foreign-key relationship.
    }
};
