<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The teacher_attendances table is handled by
     * the October 3 migration.
     */
    public function up(): void
    {
        // Intentionally empty to prevent duplicate table creation.
    }

    /**
     * Do not drop the shared table during rollback.
     */
    public function down(): void
    {
        // Intentionally empty to preserve existing attendance data.
    }
};
