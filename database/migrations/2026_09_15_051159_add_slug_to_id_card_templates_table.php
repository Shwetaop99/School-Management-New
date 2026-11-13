<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('id_card_templates', 'slug')) {
            Schema::table('id_card_templates', function (Blueprint $table) {
                $table->string('slug', 100)
                    ->nullable()
                    ->unique()
                    ->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('id_card_templates', 'slug')) {
            Schema::table('id_card_templates', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};