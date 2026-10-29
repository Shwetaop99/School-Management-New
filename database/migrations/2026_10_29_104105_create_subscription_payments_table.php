
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->string('type', 20)->default('paid');
            $table->string('status', 20)->default('pending');

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            // Actual agreed price, including negotiated pricing.
            $table->decimal('agreed_price', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index(['status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
