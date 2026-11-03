<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudentFee extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'academic_year',
        'total_amount',
        'discount_amount',
        'fine_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'due_date',
        'remarks',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['paid_amount', 'balance_amount', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}