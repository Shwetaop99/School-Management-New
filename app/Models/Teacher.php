<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'teacher_id',
        'qualification',
        'subject',
        'joining_date',
        'address',
        'profile_image',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];

    public function timetables()
{
    return $this->hasMany(TeacherTimetable::class);
}
}