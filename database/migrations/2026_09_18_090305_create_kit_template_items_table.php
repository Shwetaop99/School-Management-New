<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('kit_template_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('kit_template_id')
            ->constrained('kit_templates')
            ->cascadeOnDelete();

        $table->foreignId('supply_item_id')
            ->constrained('supply_items')
            ->restrictOnDelete();

        $table->unsignedInteger('quantity')->default(1);

        $table->string('remarks')->nullable();

        $table->timestamps();

        $table->unique([
            'kit_template_id',
            'supply_item_id'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kit_template_items');
    }
};
