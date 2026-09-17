<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSalary extends Model
{
    protected $fillable = [
        'teacher_id',
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
    'payment_date' => 'date',
];
    /**
     * Salary belongs to one teacher.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}