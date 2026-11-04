<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FeePayment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_fee_id',
        'receipt_number',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}