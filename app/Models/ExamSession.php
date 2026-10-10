<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;

    protected $table = 'exam_sessions';

    protected $fillable = [
        'exam_id',
        'session_name',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Exam this session belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}