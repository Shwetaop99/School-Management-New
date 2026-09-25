<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transport_records', function (Blueprint $table) {

            // Travel Details
            $table->string('transport_type')
                ->nullable()
                ->after('end_date');

            $table->time('pickup_time')
                ->nullable()
                ->after('transport_type');

            $table->time('drop_time')
                ->nullable()
                ->after('pickup_time');

            // Fee Information
            $table->decimal('transport_fee', 10, 2)
                ->nullable()
                ->after('drop_time');

            $table->enum('fee_frequency', [
                'monthly',
                'quarterly',
                'yearly'
            ])
                ->nullable()
                ->after('transport_fee');

            $table->enum('payment_status', [
                'paid',
                'pending',
                'partially_paid'
            ])
                ->default('pending')
                ->after('fee_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('transport_records', function (Blueprint $table) {

            $table->dropColumn([
                'transport_type',
                'pickup_time',
                'drop_time',
                'transport_fee',
                'fee_frequency',
                'payment_status',
            ]);

        });
    }
};