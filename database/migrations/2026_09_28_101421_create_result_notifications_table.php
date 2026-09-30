<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('result_id')
                ->constrained('results')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('phone_number', 30);

            $table->string('channel', 30)
                ->default('whatsapp');

            $table->text('message')->nullable();

            $table->text('result_url')->nullable();

            $table->string('status', 30)
                ->default('pending');

            $table->string('provider_message_id')->nullable();

            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index(['result_id', 'channel']);
            $table->index(['student_id', 'channel']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_notifications');
    }
};