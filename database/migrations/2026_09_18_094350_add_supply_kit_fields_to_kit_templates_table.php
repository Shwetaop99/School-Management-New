<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('kit_templates', 'kit_name')) {
            Schema::table('kit_templates', function (Blueprint $table) {
                $table->string('kit_name')
                    ->nullable()
                    ->after('id');
            });
        }

        if (!Schema::hasColumn('kit_template_items', 'kit_template_id')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->foreignId('kit_template_id')
                    ->after('id')
                    ->constrained('kit_templates')
                    ->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('kit_template_items', 'item_name')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->string('item_name')
                    ->after('kit_template_id');
            });
        }

        if (!Schema::hasColumn('kit_template_items', 'quantity')) {
            Schema::table('kit_template_items', function (Blueprint $table) {
                $table->decimal('quantity', 10, 2)
                    ->default(1)
                    ->after('item_name');
            });
        }

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
        if (Schema::hasColumn('kit_templates', 'kit_name')) {
            Schema::table('kit_templates', function (Blueprint $table) {
                $table->dropColumn('kit_name');
            });
        }
    }
};