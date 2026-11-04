<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merit_concession_slabs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('merit_concession_id')
                ->constrained('merit_concessions')
                ->cascadeOnDelete();

            $table->decimal('minimum_percentage', 5, 2);
            $table->decimal('maximum_percentage', 5, 2)->nullable();
            $table->decimal('scholarship_percentage', 5, 2);

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['merit_concession_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merit_concession_slabs');
    }
};
