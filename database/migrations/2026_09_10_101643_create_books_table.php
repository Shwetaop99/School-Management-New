<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable()->unique();

            $table->string('category');
            $table->string('publisher')->nullable();

            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('available_quantity')->default(1);

            $table->string('shelf_number')->nullable();
            $table->string('language')->nullable();

            $table->date('publication_date')->nullable();

            $table->string('cover_image')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', [
                'Available',
                'Unavailable'
            ])->default('Available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};