<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'class_name',
        'section',
        'academic_year',
        'status',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
}
