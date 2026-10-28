<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSalary extends Model
{
    protected $fillable = [
        'staff_type',
        'teacher_id',
        'other_staff_id',
        'salary_month',
        'basic_salary',
        'allowances',
        'deductions',
        'deduction_rate',
        'net_salary',
        'payment_status',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'basic_salary'   => 'float',
        'allowances'     => 'float',
        'deductions'     => 'float',
        'deduction_rate' => 'float',
        'net_salary'     => 'float',
        'payment_date'   => 'date',
    ];

    /**
     * Salary belongs to one teacher (only when staff_type = Teacher).
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Salary belongs to one non-teaching staff member
     * (Driver, Peon, Librarian, Accountant, Receptionist, OtherStaff).
     */
    public function otherStaff(): BelongsTo
    {
        return $this->belongsTo(OtherStaff::class, 'other_staff_id');
    }

    /**
     * Staff name for any staff type: $salary->staff_name
     */
    public function getStaffNameAttribute(): string
    {
        if ($this->staff_type === 'Teacher' || (empty($this->staff_type) && $this->teacher_id)) {
            return trim(($this->teacher->first_name ?? '') . ' ' . ($this->teacher->last_name ?? '')) ?: '-';
        }

        return $this->otherStaff->name ?? '-';
    }
}
