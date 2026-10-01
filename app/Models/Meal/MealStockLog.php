<?php

namespace App\Models\Meal;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockTransaction;

class MealStockLog extends Model
{
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealStockLog extends Model
{
    use HasFactory;

>>>>>>> 0a09c488f4a20273ecd9ae676f586922e6c95631
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

<<<<<<< HEAD
    /**
     * Meal item associated with this stock log.
     */
=======
>>>>>>> 0a09c488f4a20273ecd9ae676f586922e6c95631
    public function mealItem()
    {
        return $this->belongsTo(
            MealItem::class,
            'meal_item_id'
        );
    }

<<<<<<< HEAD
    /**
     * Stock transaction associated with this log.
     */
=======
>>>>>>> 0a09c488f4a20273ecd9ae676f586922e6c95631
    public function stockTransaction()
    {
        return $this->belongsTo(
            MealStockTransaction::class,
            'stock_transaction_id'
        );
    }
}