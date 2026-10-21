<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSupplyKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'kit_template_id',
        'academic_year',
        'issue_date',
        'status',
        'remarks',
        'issued_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Student who received the kit.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Kit template used.
     */
    public function kitTemplate(): BelongsTo
    {
        return $this->belongsTo(KitTemplate::class);
    }

    /**
     * Items issued to the student.
     */
    public function items(): HasMany
    {
        return $this->hasMany(StudentSupplyKitItem::class);
    }
}