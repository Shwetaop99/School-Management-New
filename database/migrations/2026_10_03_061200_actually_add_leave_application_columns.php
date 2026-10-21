<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->after('id');
            $table->string('leave_type')->after('teacher_id');
            $table->date('from_date')->after('leave_type');
            $table->date('to_date')->after('from_date');
            $table->unsignedInteger('total_days')->after('to_date');
            $table->text('reason')->after('total_days');
            $table->string('status')->default('Pending')->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn([
                'teacher_id',
                'leave_type',
                'from_date',
                'to_date',
                'total_days',
                'reason',
                'status',
            ]);
        });
    }
};
