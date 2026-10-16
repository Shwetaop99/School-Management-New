<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'section_id',
        'academic_year',
    ];
}