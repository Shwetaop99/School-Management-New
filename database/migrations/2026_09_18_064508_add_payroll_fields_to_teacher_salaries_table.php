<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_salaries', function (Blueprint $table) {

            // Attendance
            $table->integer('working_days')->default(0)->after('salary_month');
            $table->integer('present_days')->default(0)->after('working_days');
            $table->integer('absent_days')->default(0)->after('present_days');
            $table->integer('half_days')->default(0)->after('absent_days');

            // Leave
            $table->integer('leave_days')->default(0)->after('half_days');

            // Overtime
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('leave_days');
            $table->decimal('overtime_amount', 10, 2)->default(0)->after('overtime_hours');

            // Attendance-based payroll
            $table->decimal('attendance_allowance', 10, 2)
                  ->default(0)
                  ->after('allowances');

            $table->decimal('attendance_deduction', 10, 2)
                  ->default(0)
                  ->after('deductions');

            // Payroll totals
            $table->decimal('gross_salary', 10, 2)
                  ->default(0)
                  ->after('attendance_deduction');

            // Disbursal
            $table->string('payment_method')->nullable()->after('payment_date');

            $table->string('transaction_reference')->nullable()->after('payment_method');

            $table->dateTime('disbursed_at')->nullable()->after('transaction_reference');

            // Payslip / Receipt
            $table->string('payslip_number')->nullable()->unique()->after('disbursed_at');

            $table->string('receipt_number')->nullable()->unique()->after('payslip_number');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_salaries', function (Blueprint $table) {
            $table->dropColumn([
                'working_days',
                'present_days',
                'absent_days',
                'half_days',
                'leave_days',
                'overtime_hours',
                'overtime_amount',
                'attendance_allowance',
                'attendance_deduction',
                'gross_salary',
                'payment_method',
                'transaction_reference',
                'disbursed_at',
                'payslip_number',
                'receipt_number',
            ]);
        });
    }
};