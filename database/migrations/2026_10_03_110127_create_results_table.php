<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The results table was already created by
     * the September 25 migration.
     */
    public function up(): void
    {
        // Intentionally empty: preserve the existing results table.
    }

    /**
     * Preserve the existing results table on rollback.
     */
    public function down(): void
    {
        // Intentionally empty.
    }
};
