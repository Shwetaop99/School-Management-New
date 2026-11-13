
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // audit_logs is already created by the November 6 migration.
    }

    public function down(): void
    {
        // Keep the audit_logs table intact.
    }
};
