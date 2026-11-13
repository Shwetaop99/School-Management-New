<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultNotification extends Model
{
    protected $fillable = [
        'result_id',
        'student_id',
        'phone_number',
        'channel',
        'message',
        'result_url',
        'status',
        'provider_message_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}