<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('status')
                ->default('draft')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert statuses that the old enum does not support.
        DB::table('exams')
            ->whereNotIn('status', ['active', 'inactive', 'completed'])
            ->update(['status' => 'active']);

        Schema::table('exams', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'completed'])
                ->default('active')
                ->change();
        });
    }
};
