<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->nullable()
                ->after('id')
                ->constrained('schools')
                ->restrictOnDelete();

            $table->index(['school_id', 'academic_year']);
        });

        // Existing students belong to Gurukul Vidyalaya (school ID 1).
        DB::table('students')
            ->whereNull('school_id')
            ->update(['school_id' => 1]);
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'academic_year']);
            $table->dropConstrainedForeignId('school_id');
        });
    }
};
