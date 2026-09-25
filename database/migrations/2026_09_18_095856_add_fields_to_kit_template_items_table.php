<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The base migration already creates:
        // kit_template_id, supply_item_id, quantity, remarks.

        // Add only fields that are missing.
        if (!Schema::hasColumn('kit_template_items', 'unit')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->string('unit')
                    ->nullable()
                    ->after('quantity');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kit_template_items', 'unit')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }
    }
};