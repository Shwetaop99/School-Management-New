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
        Schema::create('id_card_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('slug', 100)
                ->unique();

            $table->string('template_image')
                ->nullable();

            $table->json('field_positions')
                ->nullable();

            $table->string('academic_year', 20)
                ->nullable();

            $table->string('status', 20)
                ->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_card_templates');
    }
};