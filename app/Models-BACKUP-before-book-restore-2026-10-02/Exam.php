<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'academic_year',
        'exam_name',
        'exam_type',
        'start_date',
        'end_date',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * User who created the exam.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Classes assigned to this exam.
     *
     * We will use this relationship in STEP 2.
     */
    public function examClasses(): HasMany
    {
        return $this->hasMany(ExamClass::class);
    }
}