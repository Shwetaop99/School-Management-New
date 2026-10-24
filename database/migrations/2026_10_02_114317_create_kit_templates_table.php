
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kit_templates')) {
            return;
        }

        Schema::create('kit_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scheme_id')
                ->nullable()
                ->constrained('government_schemes')
                ->nullOnDelete();

            $table->string('kit_name')->nullable();
            $table->string('class')->nullable();
            $table->string('academic_year')->nullable();
            $table->text('description')->nullable();

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            $table->index(['academic_year', 'status']);
        });
    }

    public function down(): void
    {
        // Preserve existing data. Do not drop the table here.
    }
};
