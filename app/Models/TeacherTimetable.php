<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTimetable extends Model
{
    protected $table = 'teacher_timetables';

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
        'duration_minutes',
        'lecture_type',
        'room',
    ];

    protected $casts = [
        'timetable_date' => 'date',
        'period_number' => 'integer',
        'duration_minutes' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}