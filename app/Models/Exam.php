<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'academic_year',
        'exam_name',
        'exam_type',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Classes included in this exam
     */
    public function examClasses()
    {
        return $this->hasMany(ExamClass::class);
    }

    /**
     * Subjects configured for this exam
     */
    public function examSubjects()
    {
        return $this->hasMany(ExamSubject::class);
    }

    public function examSessions()
{
    return $this->hasMany(ExamSession::class);
}

public function examHolidays()
{
    return $this->hasMany(ExamHoliday::class);
}

public function examTimetables()
{
    return $this->hasMany(ExamTimetable::class);
}
}