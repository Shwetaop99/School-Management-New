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
    Schema::create('supply_items', function (Blueprint $table) {
        $table->id();

        $table->string('item_code')->unique();
        $table->string('item_name');
        $table->string('category')->nullable();
        $table->text('description')->nullable();

        $table->string('unit')->default('Piece');

        $table->unsignedInteger('quantity_in_stock')->default(0);
        $table->unsignedInteger('minimum_stock')->default(0);

        $table->decimal('unit_price', 10, 2)->default(0);

        $table->enum('status', ['active', 'inactive'])
            ->default('active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_items');
    }
};
