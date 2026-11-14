<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Notice extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'category',
        'publish_date',
        'expiry_date',
        'status',
        'priority',
        'attachment',
        'created_by',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'category', 'status', 'priority', 'publish_date', 'expiry_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}