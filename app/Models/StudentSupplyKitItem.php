<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSupplyKitItem extends Model
{
    use HasFactory;

    protected $table = 'student_supply_kit_items';

    protected $fillable = [
        'student_supply_kit_id',
        'supply_item_id',
        'quantity',
        'issued_quantity',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'issued_quantity' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Student Supply Kit
    |--------------------------------------------------------------------------
    */

    public function studentSupplyKit()
    {
        return $this->belongsTo(
            StudentSupplyKit::class,
            'student_supply_kit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Supply Item
    |--------------------------------------------------------------------------
    */

    public function supplyItem()
    {
        return $this->belongsTo(
            SupplyItem::class,
            'supply_item_id'
        );
    }
}