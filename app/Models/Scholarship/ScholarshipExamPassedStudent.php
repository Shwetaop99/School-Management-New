<?php

namespace App\Models\Scholarship;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipExamPassedStudent extends Model
{
    use HasFactory;

    protected $table = 'scholarship_exam_passed_students';

    protected $fillable = [
        'scholarship_exam_id',
        'student_id',
        'marks',
        'percentage',
        'scholarship_received',
        'scholarship_amount',
        'remarks',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'scholarship_received' => 'boolean',
        'scholarship_amount' => 'decimal:2',
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