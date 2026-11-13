<?php

namespace App\Models\Scholarship;

use App\Models\Class\SchoolClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScholarshipExam extends Model
{
    use HasFactory;

    protected $table = 'scholarship_exams';

    protected $fillable = [
        'exam_name',
        'academic_year',
        'exam_date',
        'exam_type',
        'conducted_by',
        'total_eligible',
        'total_applied',
        'total_appeared',
        'total_passed',
        'remarks',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'total_eligible' => 'integer',
        'total_applied' => 'integer',
        'total_appeared' => 'integer',
        'total_passed' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Classes included in this scholarship exam.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            SchoolClass::class,
            'scholarship_exam_classes',
            'scholarship_exam_id',
            'school_class_id'
        );
    }

    /**
     * Students who applied, appeared, or passed
     * this scholarship exam.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(
            ScholarshipExamApplication::class,
            'scholarship_exam_id'
        );
    }

    /**
     * Students who passed this scholarship exam.
     */
    public function passedStudents(): HasMany
    {
        return $this->hasMany(
            ScholarshipExamPassedStudent::class,
            'scholarship_exam_id'
        );
    }

    /**
     * Number of students who failed.
     */
    public function getTotalFailedAttribute(): int
    {
        return max(
            0,
            (int) $this->total_appeared - (int) $this->total_passed
        );
    }

    /**
     * Pass percentage.
     */
    public function getPassPercentageAttribute(): float
    {
        if ((int) $this->total_appeared === 0) {
            return 0;
        }

        return round(
            ((int) $this->total_passed / (int) $this->total_appeared) * 100,
            2
        );
    }
}
