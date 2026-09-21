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
        Schema::create('bonafide_certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('certificate_no')->unique();

            $table->date('issue_date');

            $table->string('reason', 500);

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            $table->index('student_id');
            $table->index('issue_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonafide_certificates');
    }
};