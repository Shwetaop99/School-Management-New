<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            $table->enum('fine_type', [
                'None',
                'Late Return',
                'Book Damaged',
                'Book Lost',
                'Pages Damaged',
                'Cover Damaged',
                'Other',
            ])->default('None')->after('fine');

            $table->text('fine_reason')
                ->nullable()
                ->after('fine_type');
        });
    }

    public function down(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            $table->dropColumn([
                'fine_type',
                'fine_reason',
            ]);
        });
    }
};