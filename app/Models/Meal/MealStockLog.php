<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealStockLog extends Model
{
    use HasFactory;

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

    public function mealItem()
    {
        return $this->belongsTo(
            MealItem::class,
            'meal_item_id'
        );
    }

    public function stockTransaction()
    {
        return $this->belongsTo(
            MealStockTransaction::class,
            'stock_transaction_id'
        );
    }
}