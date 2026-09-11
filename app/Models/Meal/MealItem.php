<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealItem extends Model
{
    use HasFactory;

    protected $table = 'meal_items';

    protected $fillable = [
        'item_name',
        'category',
        'current_stock',
        'unit',
        'minimum_stock',
        'status',
        'description',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
    ];

    /**
     * All stock movements for this meal item.
     *
     * Includes both:
     * - Stock In
     * - Stock Out
     */
    public function stockTransactions()
    {
        return $this->hasMany(
            MealStockTransaction::class,
            'meal_item_id'
        );
    }

    /**
     * Stock In transactions.
     */
    public function stockIns()
    {
        return $this->hasMany(
            MealStockTransaction::class,
            'meal_item_id'
        )->where('transaction_type', 'stock_in');
    }

    /**
     * Stock Out transactions.
     */
    public function stockOuts()
    {
        return $this->hasMany(
            MealStockTransaction::class,
            'meal_item_id'
        )->where('transaction_type', 'stock_out');
    }
}

