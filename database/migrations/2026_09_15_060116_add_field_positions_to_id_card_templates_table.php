<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('id_card_templates', 'field_positions')) {
            Schema::table('id_card_templates', function (Blueprint $table) {
                $table->json('field_positions')
                    ->nullable()
                    ->after('template_image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('id_card_templates', 'field_positions')) {
            Schema::table('id_card_templates', function (Blueprint $table) {
                $table->dropColumn('field_positions');
            });
        }
    }
};