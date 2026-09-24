<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'attendance_date',
        'status',
        'overtime_hours',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'overtime_hours' => 'decimal:2',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}