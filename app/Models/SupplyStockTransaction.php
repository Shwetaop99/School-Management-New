<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SupplyStockTransaction extends Model
{
    protected $table = 'supply_stock_transactions';

    protected $fillable = [
        'supply_item_id',
        'academic_year',
        'transaction_type',
        'quantity',
        'balance_quantity',
        'reference_type',
        'reference_id',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'balance_quantity' => 'integer',
        'reference_id' => 'integer',
        'created_by' => 'integer',
    ];

    public function supplyItem(): BelongsTo
    {
        return $this->belongsTo(
            SupplyItem::class,
            'supply_item_id'
        );
    }

    public function stockTransactions(): HasMany
{
    return $this->hasMany(
        SupplyStockTransaction::class,
        'supply_item_id'
    );
}
}