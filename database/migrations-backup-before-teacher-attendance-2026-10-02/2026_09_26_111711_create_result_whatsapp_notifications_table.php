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
        Schema::create('result_whatsapp_notifications', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            $table->foreignId('result_id')
                ->constrained('results')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | WhatsApp Number
            |--------------------------------------------------------------------------
            */

            $table->string('phone', 20);


            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            $table->text('message')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Delivery Status
            |--------------------------------------------------------------------------
            |
            | pending
            | sent
            | failed
            |
            */

            $table->string('status')
                ->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Provider Response
            |--------------------------------------------------------------------------
            */

            $table->text('provider_message_id')
                ->nullable();


            $table->text('error_message')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Sent Time
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')
                ->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'result_id',
                'status',
            ]);

            $table->index('student_id');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'result_whatsapp_notifications'
        );
    }
};