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
        Schema::table('exam_classes', function (Blueprint $table) {
            // Remove the incorrect foreign key pointing to classes
            $table->dropForeign(['class_id']);
        });

        Schema::table('exam_classes', function (Blueprint $table) {
            // Point class_id to the actual Classes module table
            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_classes', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
        });

        Schema::table('exam_classes', function (Blueprint $table) {
            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->cascadeOnDelete();
        });
    }
};