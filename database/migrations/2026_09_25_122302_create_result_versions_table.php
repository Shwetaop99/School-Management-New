<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_versions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('result_id');

            $table->unsignedInteger('version_number')->default(1);

            $table->string('version_label', 100)->nullable();

            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index(
                ['result_id', 'version_number'],
                'result_versions_result_version_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_versions');
    }
};