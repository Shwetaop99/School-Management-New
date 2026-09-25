<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamHoliday extends Model
{
    use HasFactory;

    protected $table = 'exam_holidays';

    protected $fillable = [
        'exam_id',
        'holiday_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'holiday_date' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Exam this holiday belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}