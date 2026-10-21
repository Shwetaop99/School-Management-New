<?php

namespace App\Models\Scholarship;

use App\Models\Class\SchoolClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipExamClass extends Model
{
    use HasFactory;

    protected $table = 'scholarship_exam_classes';

    protected $fillable = [
        'scholarship_exam_id',
        'school_class_id',
    ];

    /**
     * Scholarship exam.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(
            ScholarshipExam::class,
            'scholarship_exam_id'
        );
    }

    /**
     * Existing Class Module record.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(
            SchoolClass::class,
            'school_class_id'
        );
    }
}