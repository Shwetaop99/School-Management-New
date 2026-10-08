<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kit_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scheme_id')
                ->nullable()
                ->constrained('government_schemes')
                ->nullOnDelete();

            $table->string('kit_name');

            $table->string('class')->nullable();

            $table->string('academic_year');

            $table->text('description')->nullable();

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            $table->index(['academic_year', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kit_templates');
    }
};