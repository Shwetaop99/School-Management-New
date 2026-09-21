<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSupplyKitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_supply_kit_id',
        'supply_item_id',
        'quantity',
        'condition',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Student supply kit.
     */
    public function studentSupplyKit(): BelongsTo
    {
        return $this->belongsTo(StudentSupplyKit::class);
    }

    /**
     * Supply item.
     */
    public function supplyItem(): BelongsTo
    {
        return $this->belongsTo(SupplyItem::class);
    }
}