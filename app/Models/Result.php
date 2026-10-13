<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ResultWhatsappNotification;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'academic_year',
        'class_name',
        'section',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result_status',

        // Online result publication workflow
        'publication_status',
        'published_at',
        'published_by',

        'remarks',
        'generated_at',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'generated_at' => 'datetime',
        'published_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }


    /*
    |--------------------------------------------------------------------------
    | EXAM
    |--------------------------------------------------------------------------
    */

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }


    /*
    |--------------------------------------------------------------------------
    | RESULT DETAILS
    |--------------------------------------------------------------------------
    */

    public function details(): HasMany
    {
        return $this->hasMany(ResultDetail::class);
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORICAL RESULT VERSIONS
    |--------------------------------------------------------------------------
    */

    public function versions(): HasMany
    {
        return $this->hasMany(ResultVersion::class);
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISHED BY
    |--------------------------------------------------------------------------
    */

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }


    /*
    |--------------------------------------------------------------------------
    | RESULT STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isGenerated(): bool
    {
        return $this->publication_status === 'generated';
    }

    public function isVerified(): bool
    {
        return $this->publication_status === 'verified';
    }

    public function isApproved(): bool
    {
        return $this->publication_status === 'approved';
    }

    public function isPublished(): bool
    {
        return $this->publication_status === 'published';
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function notifications(): HasMany
    {
        return $this->hasMany(ResultNotification::class);
    }


    /*
    |--------------------------------------------------------------------------
    | WHATSAPP NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function whatsappNotifications(): HasMany
    {
        return $this->hasMany(
            ResultWhatsappNotification::class,
            'result_id'
        );
    }
}
