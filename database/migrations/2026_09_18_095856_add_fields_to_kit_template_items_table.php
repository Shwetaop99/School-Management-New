<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kit_template_items', function (Blueprint $table) {
            $table->foreignId('kit_template_id')
                ->after('id')
                ->constrained('kit_templates')
                ->cascadeOnDelete();

            $table->foreignId('supply_item_id')
                ->after('kit_template_id')
                ->constrained('supply_items')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity')
                ->default(1)
                ->after('supply_item_id');

            $table->string('remarks', 500)
                ->nullable()
                ->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('kit_template_items', function (Blueprint $table) {
            $table->dropForeign(['kit_template_id']);
            $table->dropForeign(['supply_item_id']);

            $table->dropColumn([
                'kit_template_id',
                'supply_item_id',
                'quantity',
                'remarks',
            ]);
        });
    }
};
