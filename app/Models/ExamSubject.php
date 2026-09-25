<?php

namespace App\Models;

use App\Models\Class\Subject;
use App\Models\Class\SchoolClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $table = 'exam_subjects';

    protected $fillable = [
        'exam_id',
        'class_id',
        'subject_id',
        'maximum_marks',
        'passing_marks',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'maximum_marks' => 'integer',
        'passing_marks' => 'integer',
        'duration_minutes' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Existing class from Classes module
     */
    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }

    /**
     * Existing subject from Subjects module
     */
    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}