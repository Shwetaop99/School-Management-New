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
        Schema::create('meal_stock_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meal_item_id')
                ->constrained('meal_items')
                ->cascadeOnDelete();

            $table->enum('transaction_type', [
                'stock_in',
                'stock_out',
            ]);

            $table->decimal('quantity', 10, 2);

            $table->string('unit', 50);

            $table->decimal('rate', 10, 2)
                ->nullable();

            $table->decimal('total_amount', 12, 2)
                ->nullable();

            $table->date('transaction_date');

            $table->string('supplier')
                ->nullable();

            $table->string('reason')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->timestamps();

            /*
             * Indexes for faster Stock In / Stock Out
             * and month-wise Logs filtering.
             */
            $table->index(
                ['transaction_type', 'transaction_date'],
                'meal_stock_type_date_index'
            );

            $table->index(
                ['meal_item_id', 'transaction_date'],
                'meal_stock_item_date_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_stock_transactions');
    }
};
