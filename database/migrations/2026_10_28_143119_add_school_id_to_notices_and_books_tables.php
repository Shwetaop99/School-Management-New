
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('notices', 'school_id')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')
                    ->nullable()
                    ->index();
            });
        }

        if (!Schema::hasColumn('books', 'school_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')
                    ->nullable()
                    ->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('notices', 'school_id')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropIndex(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        if (Schema::hasColumn('books', 'school_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
