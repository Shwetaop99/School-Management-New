<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports_equipment', function (Blueprint $table) {
            $table->id();

            $table->string('equipment_name');
            $table->string('category')->nullable();

            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('available_quantity')->default(0);

            $table->string('unit')->nullable();

            $table->decimal('purchase_price', 10, 2)->nullable();

            $table->date('purchase_date')->nullable();

            $table->string('supplier')->nullable();

            $table->string('location')->nullable();

            $table->text('description')->nullable();

            $table->enum('condition', [
                'new',
                'good',
                'fair',
                'damaged'
            ])->default('good');

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sports_equipment');
    }
};