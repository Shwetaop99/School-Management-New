<?php

namespace App\Models;

use App\Models\Class\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamMark extends Model
{
    use HasFactory;

    protected $table = 'exam_marks';

    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_class_id',
        'subject_id',
        'internal_marks',
        'theory_marks',
        'practical_marks',
        'max_marks',
        'total_marks',
        'status',
        'remarks',
    ];

    protected $casts = [
        'internal_marks' => 'decimal:2',
        'theory_marks' => 'decimal:2',
        'practical_marks' => 'decimal:2',
        'max_marks' => 'decimal:2',
        'total_marks' => 'decimal:2',
    ];

    /**
     * Exam
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Exam Class
     */
    public function examClass(): BelongsTo
    {
        return $this->belongsTo(ExamClass::class);
    }

    /**
     * Subject from existing Subjects module
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    /**
     * Exam-specific subject configuration
     *
     * exam_subjects uses:
     * - exam_id
     * - class_id
     * - subject_id
     *
     * exam_marks uses:
     * - exam_id
     * - exam_class_id
     * - subject_id
     */
    public function examSubject()
    {
        return ExamSubject::query()
            ->whereColumn(
                'exam_subjects.exam_id',
                'exam_marks.exam_id'
            )
            ->whereColumn(
                'exam_subjects.subject_id',
                'exam_marks.subject_id'
            )
            ->whereIn(
                'exam_subjects.class_id',
                ExamClass::query()
                    ->select('class_id')
                    ->whereColumn(
                        'exam_classes.id',
                        'exam_marks.exam_class_id'
                    )
            );
    }

    /**
 * Get the exam-specific subject configuration.
 */
public function getExamSubject()
{
    if (!$this->exam_id || !$this->exam_class_id || !$this->subject_id) {
        return null;
    }

    $examClass = ExamClass::find($this->exam_class_id);

    if (!$examClass) {
        return null;
    }

    return ExamSubject::where('exam_id', $this->exam_id)
        ->where('class_id', $examClass->class_id)
        ->where('subject_id', $this->subject_id)
        ->first();
}
}