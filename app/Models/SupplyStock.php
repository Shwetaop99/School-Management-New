<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplyStock extends Model
{
    protected $table = 'supply_stocks';

    protected $fillable = [
        'supply_item_id',
        'academic_year',
        'quantity',
        'minimum_quantity',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'minimum_quantity' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Supply item relationship.
     */
    public function supplyItem(): BelongsTo
    {
        return $this->belongsTo(
            SupplyItem::class,
            'supply_item_id'
        );
    }

    /**
     * Stock transaction history for this item and academic year.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            SupplyStockTransaction::class,
            'supply_item_id',
            'supply_item_id'
        )->where(
            'academic_year',
            $this->academic_year
        );
    }

    /**
     * Check whether stock is low.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->minimum_quantity;
    }
}
