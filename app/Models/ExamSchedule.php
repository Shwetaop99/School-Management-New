<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id',
        'exam_class_id',
        'exam_class_section_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'max_marks',
        'pass_marks',
        'room_no',
        'instructions',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'max_marks' => 'integer',
        'pass_marks' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Exam
    |--------------------------------------------------------------------------
    */

    public function exam(): BelongsTo
    {
        return $this->belongsTo(
            Exam::class,
            'exam_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Exam Class
    |--------------------------------------------------------------------------
    */

    public function examClass(): BelongsTo
    {
        return $this->belongsTo(
            ExamClass::class,
            'exam_class_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Exam Class Section
    |--------------------------------------------------------------------------
    */

    public function examClassSection(): BelongsTo
    {
        return $this->belongsTo(
            ExamClassSection::class,
            'exam_class_section_id'
        );
    }


    
}
