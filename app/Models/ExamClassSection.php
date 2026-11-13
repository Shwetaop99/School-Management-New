<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamClassSection extends Model
{
    protected $fillable = [
        'exam_class_id',
        'section_name',
        'sort_order',
        'status',
    ];

    public function examClass(): BelongsTo
    {
        return $this->belongsTo(ExamClass::class);
    }
}
