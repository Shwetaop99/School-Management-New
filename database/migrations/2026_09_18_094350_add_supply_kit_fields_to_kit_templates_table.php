<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kit_templates', function (Blueprint $table) {

            $table->string('kit_name')
                ->after('id');

            $table->string('class')
                ->after('kit_name');

            $table->string('academic_year')
                ->after('class');

            $table->text('description')
                ->nullable()
                ->after('academic_year');

            $table->enum('status', [
                'active',
                'inactive',
            ])
                ->default('active')
                ->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('kit_templates', function (Blueprint $table) {
            $table->dropColumn([
                'kit_name',
                'class',
                'academic_year',
                'description',
                'status',
            ]);
        });
    }
};
