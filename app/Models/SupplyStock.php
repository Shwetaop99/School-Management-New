<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /*
    |--------------------------------------------------------------------------
    | SUPPLY ITEM
    |--------------------------------------------------------------------------
    */

    public function supplyItem(): BelongsTo
    {
        return $this->belongsTo(
            SupplyItem::class,
            'supply_item_id'
        );
    }
}