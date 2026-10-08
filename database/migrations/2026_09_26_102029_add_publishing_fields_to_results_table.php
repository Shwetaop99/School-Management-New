<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->string('publication_status', 20)
                ->default('draft')
                ->after('result_status');

            $table->timestamp('published_at')
                ->nullable()
                ->after('publication_status');

            $table->unsignedBigInteger('published_by')
                ->nullable()
                ->after('published_at');

            $table->index(
                'publication_status',
                'results_publication_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex('results_publication_status_index');

            $table->dropColumn([
                'publication_status',
                'published_at',
                'published_by',
            ]);
        });
    }
};