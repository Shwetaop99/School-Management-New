<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultWhatsappNotification extends Model
{
    protected $table = 'result_whatsapp_notifications';

    protected $fillable = [
        'result_id',
        'student_id',
        'phone',
        'message',
        'status',
        'provider_message_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Result associated with this WhatsApp notification.
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(
            Result::class,
            'result_id'
        );
    }

    /**
     * Student associated with this WhatsApp notification.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }
}