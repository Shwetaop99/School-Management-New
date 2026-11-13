<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('leave_applications', function (Blueprint $table) {
        if (!Schema::hasColumn('leave_applications', 'teacher_id')) {
            $table->unsignedBigInteger('teacher_id')->after('id');
        }

        if (!Schema::hasColumn('leave_applications', 'leave_type')) {
            $table->string('leave_type')->after('teacher_id');
        }

        if (!Schema::hasColumn('leave_applications', 'from_date')) {
            $table->date('from_date')->after('leave_type');
        }

        if (!Schema::hasColumn('leave_applications', 'to_date')) {
            $table->date('to_date')->after('from_date');
        }

        if (!Schema::hasColumn('leave_applications', 'total_days')) {
            $table->unsignedInteger('total_days')->after('to_date');
        }

        if (!Schema::hasColumn('leave_applications', 'reason')) {
            $table->text('reason')->after('total_days');
        }

        if (!Schema::hasColumn('leave_applications', 'status')) {
            $table->string('status')->default('Pending')->after('reason');
        }
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
