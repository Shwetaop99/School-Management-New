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
        Schema::create('meal_items', function (Blueprint $table) {
            $table->id();

            $table->string('item_name');
            $table->string('category')->nullable();

            $table->decimal('current_stock', 10, 2)
                ->default(0);

            $table->string('unit', 50);

            $table->decimal('minimum_stock', 10, 2)
                ->default(0);

            $table->string('status')
                ->default('active');

            $table->text('description')
                ->nullable();

            $table->timestamps();

            /*
             * Indexes for item listing and active-item filtering.
             */
            $table->index('item_name', 'meal_items_name_index');
            $table->index('category', 'meal_items_category_index');
            $table->index('status', 'meal_items_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_items');
    }
};
