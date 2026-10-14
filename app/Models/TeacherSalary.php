<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
    protected $fillable = [
        'teacher_id',
        'salary_month',

        // Salary
        'basic_salary',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',

        // Attendance
        'working_days',
        'present_days',
        'absent_days',
        'half_days',
        'leave_days',

        // Attendance Payroll
        'attendance_allowance',
        'attendance_deduction',

        // Overtime
        'overtime_hours',
        'overtime_amount',

        // Payment
        'payment_status',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'disbursed_at',

        // Remarks / Receipts
        'remarks',
        'payslip_number',
        'receipt_number',
    ];

    protected $casts = [
        'salary_month' => 'date',

        'payment_date' => 'date',

        'disbursed_at' => 'datetime',

        // Salary
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',

        // Attendance
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'half_days' => 'integer',
        'leave_days' => 'integer',

        // Attendance Payroll
        'attendance_allowance' => 'decimal:2',
        'attendance_deduction' => 'decimal:2',

        // Overtime
        'overtime_hours' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | TEACHER RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

