<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamClass extends Model
{
    protected $fillable = [
        'exam_id',
        'class_name',
        'sort_order',
        'status',
    ];

    /**
     * Exam relationship
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(
            Exam::class,
            'exam_id'
        );
    }

    /**
     * Sections belonging to this exam class
     */
    public function sections(): HasMany
    {
        return $this->hasMany(
            ExamClassSection::class,
            'exam_class_id'
        );
    }
}
