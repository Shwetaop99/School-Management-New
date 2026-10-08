<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_schemes', function (Blueprint $table) {
            $table->id();

            $table->string('scheme_name');
            $table->string('scheme_code')->nullable()->unique();

            $table->string('government')->nullable();
            $table->string('department')->nullable();

            $table->string('academic_year');
            $table->text('description')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_schemes');
    }
};