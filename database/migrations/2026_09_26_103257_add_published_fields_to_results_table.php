<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Publishing fields were already added by
        // 2026_09_26_102029_add_publishing_fields_to_results_table.
    }

    public function down(): void
    {
        // Do not remove the fields here because they belong
        // to the earlier publishing-fields migration.
    }
};