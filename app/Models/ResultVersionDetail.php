<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultVersionDetail extends Model
{
    use HasFactory;

    protected $table = 'result_version_details';

    protected $fillable = [
        'result_version_id',
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
        'status',
        'remarks',
    ];

    protected $casts = [
        'max_marks'       => 'float',
        'internal_marks'  => 'float',
        'theory_marks'    => 'float',
        'practical_marks' => 'float',
        'total_marks'     => 'float',
        'obtained_marks'  => 'float',
        'grade_point'     => 'float',
    ];

    public function resultVersion()
    {
        return $this->belongsTo(
            ResultVersion::class,
            'result_version_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}
