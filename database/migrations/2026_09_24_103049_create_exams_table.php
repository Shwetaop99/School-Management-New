
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The exams table is already created by
        // 2026_09_19_053428_create_exams_table.
        // This duplicate migration is intentionally a no-op.
    }

    public function down(): void
    {
        // Intentionally do not drop the existing exams table.
    }
};
