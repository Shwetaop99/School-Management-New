<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Publishing fields are added by the later
        // 2026_09_26_103257_add_published_fields_to_results_table migration.
    }

    public function down(): void
    {
        // Nothing to rollback.
    }
};
