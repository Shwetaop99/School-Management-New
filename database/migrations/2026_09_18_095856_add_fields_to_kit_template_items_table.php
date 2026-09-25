<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD
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
=======
        // Fields are already created in
        // create_kit_template_items_table migration.
>>>>>>> origin/feature/roles-permissions
    }

    public function down(): void
    {
<<<<<<< HEAD
        if (Schema::hasColumn('kit_template_items', 'unit')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }
=======
        // Nothing to reverse.
>>>>>>> origin/feature/roles-permissions
    }
};