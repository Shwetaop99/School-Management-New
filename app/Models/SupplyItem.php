<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'category',
        'description',
        'unit',
        'quantity_in_stock',
        'minimum_stock',
        'unit_price',
        'status',
    ];

    protected $casts = [
        'quantity_in_stock' => 'integer',
        'minimum_stock' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Kit template items using this supply item.
     */
    public function kitTemplateItems(): HasMany
    {
        return $this->hasMany(KitTemplateItem::class);
    }

    /**
     * Student supply kit items using this supply item.
     */
    public function studentSupplyKitItems(): HasMany
    {
        return $this->hasMany(StudentSupplyKitItem::class);
    }
}