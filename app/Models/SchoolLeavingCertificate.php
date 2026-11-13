<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolLeavingCertificate extends Model
{
    protected $fillable = [
        'student_id',
        'certificate_no',
        'issue_date',
        'leaving_date',
        'leaving_reason',
        'conduct',
        'progress',
        'last_class',
        'last_division',
        'academic_year',
        'remarks',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'leaving_date' => 'date',
    ];

    /**
     * Certificate belongs to one student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}