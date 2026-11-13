
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // leave_applications was already created
        // by the September 18 migration.
    }

    public function down(): void
    {
        // Preserve the existing leave_applications table.
    }
};
