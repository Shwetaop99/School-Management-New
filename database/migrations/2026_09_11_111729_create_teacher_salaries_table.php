<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_salaries', 'staff_type')) {
                $table->string('staff_type', 30)->nullable()->after('id');
            }

            if (!Schema::hasColumn('teacher_salaries', 'other_staff_id')) {
                $table->unsignedBigInteger('other_staff_id')->nullable()->after('teacher_id');
            }
        });

        // Non-teaching staff have no teacher_id, so it must allow NULL.
        // (Laravel 10+ supports ->change() natively. On older versions install doctrine/dbal.)
        Schema::table('teacher_salaries', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
        });

        // Back-fill old rows: anything with a teacher_id is a Teacher salary.
        DB::table('teacher_salaries')
            ->whereNull('staff_type')
            ->whereNotNull('teacher_id')
            ->update(['staff_type' => 'Teacher']);
    }

    public function down(): void
    {
        Schema::table('teacher_salaries', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_salaries', 'other_staff_id')) {
                $table->dropColumn('other_staff_id');
            }

            if (Schema::hasColumn('teacher_salaries', 'staff_type')) {
                $table->dropColumn('staff_type');
            }
        });
    }
};