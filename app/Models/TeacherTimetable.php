<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTimetable extends Model
{
    protected $fillable = [
        'teacher_id',
        'timetable_date',
        'academic_year',
        'day',
        'period_number',
        'period_type',
        'class',
        'section',
        'subject',
        'subject_type',
        'start_time',
        'end_time',
        'room',
        'duration_minutes',
        'lecture_type',
    ];

    protected $casts = [
        'timetable_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}