
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'school_type' => fn (Blueprint $table) => $table->string('school_type')->nullable(),
            'address_line' => fn (Blueprint $table) => $table->string('address_line')->nullable(),
            'city' => fn (Blueprint $table) => $table->string('city')->nullable(),
            'district' => fn (Blueprint $table) => $table->string('district')->nullable(),
            'state' => fn (Blueprint $table) => $table->string('state')->nullable(),
            'postal_code' => fn (Blueprint $table) => $table->string('postal_code', 20)->nullable(),
            'country' => fn (Blueprint $table) => $table->string('country')->default('India'),
            'email' => fn (Blueprint $table) => $table->string('email')->nullable(),
            'phone' => fn (Blueprint $table) => $table->string('phone', 30)->nullable(),
            'website' => fn (Blueprint $table) => $table->string('website')->nullable(),
            'principal_name' => fn (Blueprint $table) => $table->string('principal_name')->nullable(),
            'education_board' => fn (Blueprint $table) => $table->string('education_board')->nullable(),
            'registration_number' => fn (Blueprint $table) => $table->string('registration_number')->nullable(),
            'establishment_year' => fn (Blueprint $table) => $table->unsignedSmallInteger('establishment_year')->nullable(),
        ];

        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn('schools', $column)) {
                Schema::table('schools', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        }
    }

    public function down(): void
    {
        // Preserve existing school data and columns.
    }
};
