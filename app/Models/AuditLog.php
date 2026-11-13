<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
    'user_id',
    'actor_type',
    'school_id',
    'action',
    'module',
    'description',
    'subject_type',
    'subject_id',
    'ip_address',
    'user_agent',
    'old_values',
    'new_values',
];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    /**
     * User who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * School associated with the activity.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
