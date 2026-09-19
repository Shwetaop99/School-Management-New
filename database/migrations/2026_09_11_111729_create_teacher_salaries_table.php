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
        Schema::create('teacher_salaries', function (Blueprint $table) {
    $table->id();

    $table->foreignId('teacher_id')
          ->constrained('teachers')
          ->cascadeOnDelete();

    $table->string('salary_month');

    $table->decimal('basic_salary', 10, 2)->default(0);

    $table->decimal('allowances', 10, 2)->default(0);

    $table->decimal('deductions', 10, 2)->default(0);

    $table->decimal('net_salary', 10, 2)->default(0);

    $table->enum('payment_status', [
        'Pending',
        'Paid'
    ])->default('Pending');

    $table->date('payment_date')->nullable();

    $table->text('remarks')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_salaries');
    }
};
