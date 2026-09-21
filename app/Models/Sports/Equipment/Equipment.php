<?php

namespace App\Models\Sports\Equipment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'sports_equipment';

    protected $fillable = [
        'equipment_name',
        'category',
        'brand',
        'model',
        'quantity',
        'available_quantity',
        'unit',
        'purchase_price',
        'purchase_date',
        'supplier',
        'location',
        'description',
        'condition',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
    ];
}