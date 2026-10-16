<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_class_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_class_id')
                ->constrained('exam_classes')
                ->cascadeOnDelete();

            $table->string('section_name', 20);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();

            $table->unique([
                'exam_class_id',
                'section_name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_class_sections');
    }
};
