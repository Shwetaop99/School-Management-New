<?php

namespace App\Models\Class;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }
}