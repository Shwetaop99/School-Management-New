<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonafideCertificate extends Model
{
    use HasFactory;

    protected $table = 'bonafide_certificates';

    protected $fillable = [
        'student_id',
        'certificate_no',
        'issue_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Certificate belongs to a student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}