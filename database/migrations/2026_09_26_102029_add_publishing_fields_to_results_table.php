
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
                $table->string('publication_status', 20)
                    ->default('draft');
            });
        }

        if (!Schema::hasColumn('results', 'published_at')) {
            Schema::table('results', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });
        }

        if (!Schema::hasColumn('results', 'published_by')) {
            Schema::table('results', function (Blueprint $table) {
                $table->unsignedBigInteger('published_by')->nullable();
            });
        }

        if (!Schema::hasIndex(
            'results',
            'results_publication_status_index'
        )) {
            Schema::table('results', function (Blueprint $table) {
                $table->index(
                    'publication_status',
                    'results_publication_status_index'
                );
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('results')) {
            return;
        }

        if (Schema::hasIndex(
            'results',
            'results_publication_status_index'
        )) {
            Schema::table('results', function (Blueprint $table) {
                $table->dropIndex('results_publication_status_index');
            });
        }

        $columns = array_filter([
            'published_by' => Schema::hasColumn('results', 'published_by')
                ? 'published_by' : null,
            'published_at' => Schema::hasColumn('results', 'published_at')
                ? 'published_at' : null,
            'publication_status' => Schema::hasColumn('results', 'publication_status')
                ? 'publication_status' : null,
        ]);

        if (!empty($columns)) {
            Schema::table('results', function (Blueprint $table) use ($columns) {
                $table->dropColumn(array_values($columns));
            });
        }
    }
};
