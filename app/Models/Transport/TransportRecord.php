<?php

namespace App\Models\Transport;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportRecord extends Model
{
    protected $table = 'transport_records';

    protected $fillable = [
        'student_id',
        'route',
        'vehicle',
        'pickup_point',
        'drop_point',
        'transport_status',
        'remarks',
        'start_date',
        'end_date',

        // Travel Details
        'transport_type',
        'pickup_time',
        'drop_time',

        // Fee Information
        'transport_fee',
        'fee_frequency',
        'payment_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'pickup_time' => 'datetime:H:i',
        'drop_time' => 'datetime:H:i',
        'transport_fee' => 'decimal:2',
    ];

    /**
     * Student assigned to this transport record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}