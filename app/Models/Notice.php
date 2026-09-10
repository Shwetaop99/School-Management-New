<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
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
}