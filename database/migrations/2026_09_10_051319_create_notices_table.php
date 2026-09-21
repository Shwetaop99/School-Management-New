<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('content');

            $table->string('category')->default('General');

            $table->date('publish_date')->nullable();

            $table->date('expiry_date')->nullable();

            $table->enum('status', [
                'Draft',
                'Published',
                'Expired'
            ])->default('Draft');

            $table->enum('priority', [
                'Normal',
                'Important',
                'Urgent'
            ])->default('Normal');

            $table->string('attachment')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};