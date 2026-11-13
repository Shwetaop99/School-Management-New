<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model
{
    use HasFactory;

    protected $table = 'exam_timetables';

    /**
     * Fields allowed for mass assignment.
     */
    protected $fillable = [
        'exam_id',
        'class_id',
        'exam_subject_id',
        'session_id',
        'teacher_id',
        'exam_date',
        'start_time',
        'end_time',
        'maximum_marks',
        'duration_minutes',
        'status',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'exam_date' => 'date',
        'maximum_marks' => 'integer',
        'duration_minutes' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Exam.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * School class.
     */
    public function schoolClass()
    {
        return $this->belongsTo(
            \App\Models\Class\SchoolClass::class,
            'class_id'
        );
    }

    /**
     * Exam subject.
     */
    public function examSubject()
    {
        return $this->belongsTo(
            ExamSubject::class,
            'exam_subject_id'
        );
    }

    /**
     * Exam session.
     */
    public function session()
    {
        return $this->belongsTo(
            ExamSession::class,
            'session_id'
        );
    }

    /**
     * Teacher / supervisor.
     */
    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }
}