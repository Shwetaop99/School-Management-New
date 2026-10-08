<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentScheme extends Model
{
    use HasFactory;

    protected $table = 'government_schemes';

    protected $fillable = [
        'scheme_name',
        'scheme_code',
        'government',
        'department',
        'academic_year',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function kitTemplates()
    {
        return $this->hasMany(
            KitTemplate::class,
            'scheme_id'
        );
    }
}