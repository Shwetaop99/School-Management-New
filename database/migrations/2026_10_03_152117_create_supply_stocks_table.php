<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supply_item_id')
                ->constrained('supply_items')
                ->cascadeOnDelete();

            $table->string('academic_year', 20);

            $table->unsignedInteger('quantity')->default(0);

            $table->unsignedInteger('minimum_quantity')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->unique([
                'supply_item_id',
                'academic_year',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_stocks');
    }
};