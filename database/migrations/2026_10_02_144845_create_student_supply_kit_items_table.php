<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_supply_kit_items', function (Blueprint $table) {
            $table->id();

            // Student supply kit record
            $table->foreignId('student_supply_kit_id')
                ->constrained('student_supply_kits')
                ->cascadeOnDelete();

            // Supply item
            $table->foreignId('supply_item_id')
                ->constrained('supply_items')
                ->restrictOnDelete();

            // Quantity planned in the kit
            $table->unsignedInteger('quantity')
                ->default(1);

            // Quantity actually issued
            $table->unsignedInteger('issued_quantity')
                ->default(0);

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            $table->unique(
    ['student_supply_kit_id', 'supply_item_id'],
    'sski_kit_item_unique'
);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_supply_kit_items');
    }
};