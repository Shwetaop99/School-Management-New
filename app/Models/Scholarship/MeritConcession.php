<?php

namespace App\Models\Scholarship;

use Illuminate\Database\Eloquent\Model;

class MeritConcession extends Model
{
    protected $table = 'merit_concessions';

    protected $fillable = [
        'academic_year',
        'minimum_percentage',
        'scholarship_percentage',
        'description',
        'status',
    ];

    protected $casts = [
        'minimum_percentage' => 'decimal:2',
        'scholarship_percentage' => 'decimal:2',
    ];
}