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

        $table->string('name', 150);

        $table->string('template_image')->nullable();

        $table->string('academic_year', 20)->nullable();

        $table->enum('status', [
            'active',
            'inactive'
        ])->default('active');

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
