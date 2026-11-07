
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

            // User who performed the activity
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // School Admin or Super Admin
            $table->string('actor_type', 30);

            // Associated school (null for platform-wide activity)
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            // Activity information
            $table->string('action', 50);
            $table->string('module', 100);
            $table->text('description');

            // Request information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Optional before/after data
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->timestamps();

            // Indexes for filtering and reports
            $table->index('actor_type');
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
            $table->index(['school_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
