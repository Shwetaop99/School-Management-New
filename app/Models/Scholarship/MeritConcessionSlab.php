<?php

namespace App\Models\Scholarship;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeritConcessionSlab extends Model
{
    protected $table = 'merit_concession_slabs';

    protected $fillable = [
        'merit_concession_id',
        'minimum_percentage',
        'maximum_percentage',
        'scholarship_percentage',
        'sort_order',
    ];

    protected $casts = [
        'minimum_percentage' => 'decimal:2',
        'maximum_percentage' => 'decimal:2',
        'scholarship_percentage' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function meritConcession(): BelongsTo
    {
        return $this->belongsTo(MeritConcession::class, 'merit_concession_id');
    }
}
