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
        Schema::create('meal_stock_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meal_item_id')
                ->constrained('meal_items')
                ->cascadeOnDelete();

            $table->foreignId('stock_transaction_id')
                ->constrained('meal_stock_transactions')
                ->cascadeOnDelete();

            $table->enum('action', [
                'stock_in',
                'stock_out',
            ]);

            $table->decimal('quantity', 10, 2);

            $table->string('unit', 50);

            $table->decimal('previous_stock', 10, 2);

            $table->decimal('updated_stock', 10, 2);

            $table->unsignedBigInteger('performed_by')
                ->nullable();

            $table->string('reason')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
             * Indexes for Logs filtering.
             */
            $table->index(
                ['action', 'created_at'],
                'meal_logs_action_date_index'
            );

            $table->index(
                ['meal_item_id', 'created_at'],
                'meal_logs_item_date_index'
            );

            $table->index(
                'stock_transaction_id',
                'meal_logs_transaction_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_stock_logs');
    }
};
