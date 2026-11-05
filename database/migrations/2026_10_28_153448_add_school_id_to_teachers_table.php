
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('teachers', 'school_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')
                    ->nullable()
                    ->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'school_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropIndex(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
