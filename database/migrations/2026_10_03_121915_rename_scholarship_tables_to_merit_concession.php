
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Keep the existing scholarship table names.
     * This migration intentionally performs no changes.
     */
    public function up(): void
    {
        // No table renaming is required.
    }

    /**
     * Nothing to reverse.
     */
    public function down(): void
    {
        // No changes were made.
    }
};
