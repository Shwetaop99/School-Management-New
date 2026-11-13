
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The exam_subjects migration already references school_classes.
        // No additional foreign-key change is required.
    }

    public function down(): void
    {
        // Preserve the correct foreign-key relationship.
    }
};
