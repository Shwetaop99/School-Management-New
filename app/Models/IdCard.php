<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdCard extends Model
{
    use HasFactory;

    protected $table = 'id_cards';

    protected $fillable = [
        'student_id',
        'card_number',
        'template',
        'academic_year',
        'issued_date',
        'status',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    /**
     * Student attached to this ID card.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /**
     * Template used for this ID card.
     *
     * The template column contains the template slug.
     */
    public function idCardTemplate(): BelongsTo
    {
        return $this->belongsTo(
            IdCardTemplate::class,
            'template',
            'slug'
        );
    }
}