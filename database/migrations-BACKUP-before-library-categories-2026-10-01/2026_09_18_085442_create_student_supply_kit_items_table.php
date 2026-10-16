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
    Schema::create('student_supply_kit_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_supply_kit_id')
            ->constrained('student_supply_kits')
            ->cascadeOnDelete();

        $table->foreignId('supply_item_id')
            ->constrained('supply_items')
            ->restrictOnDelete();

        $table->unsignedInteger('quantity')->default(1);

        $table->enum('condition', [
            'new',
            'good',
            'used',
            'damaged'
        ])->default('new');

        $table->string('remarks')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_supply_kit_items');
    }
};
