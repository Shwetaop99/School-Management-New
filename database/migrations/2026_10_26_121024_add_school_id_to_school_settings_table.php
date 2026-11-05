<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('school_settings', 'school_id')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
            });
        }

        Schema::table('school_settings', function (Blueprint $table) {
            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });

        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }
};