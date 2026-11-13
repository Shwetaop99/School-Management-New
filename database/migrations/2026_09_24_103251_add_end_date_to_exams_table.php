
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('exams', 'end_date')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->date('end_date')
                    ->nullable()
                    ->after('start_date');
            });
        }
    }

    public function down(): void
    {
        // Preserve the existing end_date column.
    }
};
