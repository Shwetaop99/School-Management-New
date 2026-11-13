<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockTransaction;

class MealStockLog extends Model
{
    protected $table = 'meal_stock_logs';

    protected $fillable = [
        'meal_item_id',
        'stock_transaction_id',
        'action',
        'quantity',
        'unit',
        'previous_stock',
        'updated_stock',
        'performed_by',
        'reason',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'previous_stock' => 'decimal:2',
        'updated_stock' => 'decimal:2',
    ];

    /**
     * Meal item associated with this stock log.
     */
    public function mealItem()
    {
        return $this->belongsTo(
            MealItem::class,
            'meal_item_id'
        );
    }

    /**
     * Stock transaction associated with this log.
     */
    public function stockTransaction()
    {
        return $this->belongsTo(
            MealStockTransaction::class,
            'stock_transaction_id'
        );
    }
}