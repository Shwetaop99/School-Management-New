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

    /**
     * Student who owns this result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Exam for which this result was generated.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Subject-wise result details.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ResultDetail::class);
    }

    /**
     * Historical generated result versions.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(ResultVersion::class);
    }

    /**
     * User who published the result.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Check whether result is generated.
     */
    public function isGenerated(): bool
    {
        return $this->publication_status === 'generated';
    }

    /**
     * Check whether result is verified.
     */
    public function isVerified(): bool
    {
        return $this->publication_status === 'verified';
    }

    /**
     * Check whether result is approved.
     */
    public function isApproved(): bool
    {
        return $this->publication_status === 'approved';
    }

    /**
     * Check whether result is published.
     */
    public function isPublished(): bool
    {
        return $this->publication_status === 'published';
    }

   public function notifications(): HasMany
{
    return $this->hasMany(ResultNotification::class);
}

/**
 * WhatsApp notification records for this result.
 */
public function whatsappNotifications()
{
    return $this->hasMany(
        ResultWhatsappNotification::class,
        'result_id'
    );
}
}