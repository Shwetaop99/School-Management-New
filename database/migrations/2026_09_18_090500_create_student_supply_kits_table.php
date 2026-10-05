<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('student_supply_kits', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
            ->constrained('students')
            ->cascadeOnDelete();

        $table->foreignId('kit_template_id')
            ->nullable()
            ->constrained('kit_templates')
            ->nullOnDelete();

        $table->string('academic_year')->nullable();

        $table->date('issue_date')->nullable();

        $table->enum('status', [
            'pending',
            'issued',
            'partially_issued',
            'cancelled'
        ])->default('pending');

        $table->text('remarks')->nullable();

        $table->unsignedBigInteger('issued_by')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_supply_kits');
    }
};
