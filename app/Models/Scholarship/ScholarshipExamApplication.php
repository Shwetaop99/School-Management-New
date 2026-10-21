<?php

namespace App\Models\Scholarship;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipExamApplication extends Model
{
    use HasFactory;

    protected $table = 'scholarship_exam_applications';

    protected $fillable = [
        'scholarship_exam_id',
        'student_id',
        'status',
        'remarks',
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
     * Existing Student Module record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }
}