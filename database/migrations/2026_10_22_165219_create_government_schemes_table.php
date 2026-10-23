
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('student_supply_kits', 'distribution_date')) {
            Schema::table('student_supply_kits', function (Blueprint $table) {
                $table->date('distribution_date')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('student_supply_kits', 'distribution_date')) {
            Schema::table('student_supply_kits', function (Blueprint $table) {
                $table->dropColumn('distribution_date');
            });
        }
    }
};
