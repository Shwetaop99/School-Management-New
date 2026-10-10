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
        Schema::table('other_staff', function (Blueprint $table) {
            $table->string('license_number')
                ->nullable()
                ->after('qualification');

            $table->date('license_expiry')
                ->nullable()
                ->after('license_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_staff', function (Blueprint $table) {
            $table->dropColumn([
                'license_number',
                'license_expiry',
            ]);
        });
    }
};