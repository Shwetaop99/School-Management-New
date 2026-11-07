<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('actor_type')->nullable(); // super_admin or school_admin

            // School associated with the activity
            $table->unsignedBigInteger('school_id')->nullable();

            // Activity information
            $table->string('action'); // created, updated, deleted, login, logout
            $table->string('module'); // users, schools, subscriptions, etc.
            $table->text('description')->nullable();

            // Record affected by the activity
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            // Store changes made to records
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes for faster filtering
            $table->index('user_id');
            $table->index('school_id');
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};