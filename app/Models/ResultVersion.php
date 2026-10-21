<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultVersion extends Model
{
    use HasFactory;

    protected $table = 'result_versions';

    protected $fillable = [
        'result_id',
        'version_no',
        'student_id_snapshot',
        'student_name_snapshot',
        'exam_name_snapshot',
        'academic_year',
        'class_name',
        'section',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result_status',
        'remarks',
        'generated_at',
    ];

    protected $casts = [
        'total_marks'    => 'float',
        'obtained_marks' => 'float',
        'percentage'     => 'float',
        'generated_at'   => 'datetime',
    ];

    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }

    public function details()
    {
        return $this->hasMany(
            ResultVersionDetail::class,
            'result_version_id'
        );
    }
}
