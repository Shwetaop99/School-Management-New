<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_stock_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supply_item_id')
                ->constrained('supply_items')
                ->cascadeOnDelete();

            $table->string('academic_year', 20);

            $table->enum('transaction_type', [
                'receive',
                'issue',
                'return',
                'damaged',
                'adjustment',
            ]);

            $table->unsignedInteger('quantity');

            $table->unsignedInteger('balance_quantity')->default(0);

            $table->string('reference_type', 50)->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index(
                ['supply_item_id', 'academic_year'],
                'supply_stock_transactions_item_year_index'
            );

            $table->index(
                ['reference_type', 'reference_id'],
                'supply_stock_transactions_reference_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_stock_transactions');
    }
};