<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class TeacherTimetable extends Model
{
    protected $fillable = [
        'teacher_id',
        'timetable_date',
        'academic_year',
        'day',
        'period_number',
        'period_type',
        'lecture_type',
        'class',
        'section',
        'subject',
        'subject_type',
        'start_time',
        'end_time',
        'duration_minutes',
        'room',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}