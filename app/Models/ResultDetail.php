<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Class\Subject;

class ResultDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'subject_id',
        'subject_name',
        'max_marks',
        'internal_marks',
        'theory_marks',
        'practical_marks',
        'total_marks',
        'obtained_marks',
        'grade',
        'grade_point',
    ];

    protected $casts = [
        'max_marks' => 'decimal:2',
        'internal_marks' => 'decimal:2',
        'theory_marks' => 'decimal:2',
        'practical_marks' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'grade_point' => 'decimal:2',
    ];

    /**
     * Main result.
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    /**
     * Subject.
     *
     * subject_id is nullable because the result stores
     * subject_name as a historical snapshot.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}