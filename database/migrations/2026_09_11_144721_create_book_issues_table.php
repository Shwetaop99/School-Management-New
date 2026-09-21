<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();

            // Book being issued
            $table->foreignId('book_id')
                ->constrained('books')
                ->cascadeOnDelete();

            // Student who receives the book
            // We will connect this to the Student table later.
            $table->unsignedBigInteger('student_id');

            $table->date('issue_date');
            $table->date('due_date');

            $table->date('return_date')->nullable();

            $table->enum('status', [
                'Issued',
                'Returned',
                'Overdue'
            ])->default('Issued');

            $table->decimal('fine', 10, 2)->default(0);

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};