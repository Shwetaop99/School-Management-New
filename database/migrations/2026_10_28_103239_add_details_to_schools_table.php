<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('school_type')->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country')->default('India');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website')->nullable();
            $table->string('principal_name')->nullable();
            $table->string('education_board')->nullable();
            $table->string('registration_number')->nullable();
            $table->unsignedSmallInteger('establishment_year')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'school_type',
                'address_line',
                'city',
                'district',
                'state',
                'postal_code',
                'country',
                'email',
                'phone',
                'website',
                'principal_name',
                'education_board',
                'registration_number',
                'establishment_year',
            ]);
        });
    }
};
