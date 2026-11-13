<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->timestamp('published_at')
                ->nullable()
                ->after('publication_status');

            $table->unsignedBigInteger('published_by')
                ->nullable()
                ->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn([
                'published_at',
                'published_by',
            ]);
        });
    }
};