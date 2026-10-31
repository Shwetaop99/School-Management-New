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
        'internal_max_marks',
        'theory_max_marks',
        'maximum_marks',
        'passing_marks',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'internal_max_marks' => 'integer',
        'theory_max_marks' => 'integer',
        'maximum_marks' => 'integer',
        'passing_marks' => 'integer',
        'duration_minutes' => 'integer',
        'status' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}