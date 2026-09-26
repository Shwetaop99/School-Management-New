<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kit_templates', function (Blueprint $table) {

            if (!Schema::hasColumn('kit_templates', 'kit_name')) {
                $table->string('kit_name')->after('id');
            }

            if (!Schema::hasColumn('kit_templates', 'class')) {
                $table->string('class')->after('kit_name');
            }

            if (!Schema::hasColumn('kit_templates', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('class');
            }

            if (!Schema::hasColumn('kit_templates', 'description')) {
                $table->text('description')->nullable()->after('academic_year');
            }

            if (!Schema::hasColumn('kit_templates', 'status')) {
                $table->enum('status', [
                    'active',
                    'inactive',
                ])->default('active')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kit_templates', function (Blueprint $table) {

            $columns = [
                'kit_name',
                'class',
                'academic_year',
                'description',
                'status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('kit_templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};