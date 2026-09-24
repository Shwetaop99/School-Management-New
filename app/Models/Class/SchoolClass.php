<?php

namespace App\Models\Class;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'class_name',
        'section',
        'academic_year',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}