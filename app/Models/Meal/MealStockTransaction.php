<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealStockTransaction extends Model
{
    use HasFactory;

    protected $table = 'meal_stock_transactions';

    protected $fillable = [
        'meal_item_id',
        'transaction_type',
        'quantity',
        'unit',
        'rate',
        'total_amount',
        'transaction_date',
        'supplier',
        'reason',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Meal item related to this transaction.
     */
    public function mealItem()
    {
        return $this->belongsTo(
            MealItem::class,
            'meal_item_id'
        );
    }

    /**
     * Scope: Stock In transactions.
     */
    public function scopeStockIn($query)
    {
        return $query->where('transaction_type', 'stock_in');
    }

    /**
     * Scope: Stock Out transactions.
     */
    public function scopeStockOut($query)
    {
        return $query->where('transaction_type', 'stock_out');
    }
}
