<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_templates', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('category');
            $table->string('priority')->default('medium');

            $table->text('description');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_templates');
    }
};