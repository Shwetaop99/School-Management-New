<?php

namespace App\Models\Scholarship;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeritConcession extends Model
{
    protected $table = 'merit_concessions';

    protected $fillable = [
        'academic_year',
        'minimum_percentage',
        'scholarship_percentage',
        'description',
        'status',
    ];

    protected $casts = [
        'minimum_percentage' => 'decimal:2',
        'scholarship_percentage' => 'decimal:2',
    ];

    public function slabs(): HasMany
    {
        return $this->hasMany(MeritConcessionSlab::class, 'merit_concession_id')
            ->orderBy('sort_order');
    }
}
