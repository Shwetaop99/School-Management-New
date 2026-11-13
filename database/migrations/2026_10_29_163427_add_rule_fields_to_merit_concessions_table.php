<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('merit_concessions')) {
            Schema::create('merit_concessions', function (Blueprint $table) {
                $table->id();
                $table->string('academic_year', 20);
                $table->decimal('minimum_percentage', 5, 2);
                $table->decimal('scholarship_percentage', 5, 2);
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('merit_concessions', 'academic_year')) {
            Schema::table('merit_concessions', function (Blueprint $table) {
                $table->string('academic_year', 20)->nullable();
            });
        }

        if (!Schema::hasColumn('merit_concessions', 'minimum_percentage')) {
            Schema::table('merit_concessions', function (Blueprint $table) {
                $table->decimal('minimum_percentage', 5, 2)->nullable();
            });
        }

        if (!Schema::hasColumn('merit_concessions', 'scholarship_percentage')) {
            Schema::table('merit_concessions', function (Blueprint $table) {
                $table->decimal('scholarship_percentage', 5, 2)->nullable();
            });
        }

        if (!Schema::hasColumn('merit_concessions', 'description')) {
            Schema::table('merit_concessions', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }

        if (!Schema::hasColumn('merit_concessions', 'status')) {
            Schema::table('merit_concessions', function (Blueprint $table) {
                $table->string('status', 20)->default('active');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('merit_concessions')) {
            return;
        }

        $columns = [
            'academic_year',
            'minimum_percentage',
            'scholarship_percentage',
            'description',
            'status',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('merit_concessions', $column)) {
                Schema::table('merit_concessions', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
