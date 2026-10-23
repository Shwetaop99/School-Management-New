
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('results')) {
            return;
        }

        if (!Schema::hasColumn('results', 'publication_status')) {
            Schema::table('results', function (Blueprint $table) {
                $table->string('publication_status')
                    ->default('generated');
            });
        }
    }

    public function down(): void
    {
        // Preserve existing data; do not drop the column automatically.
    }
};
