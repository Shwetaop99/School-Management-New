<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class SupplyItem extends Model
{
    use HasFactory;

    protected $table = 'supply_items';

    protected $fillable = [
        'item_name',
        'item_code',
        'unit',
        'description',
        'status',
    ];

    public function kitTemplateItems()
    {
        return $this->hasMany(
            KitTemplateItem::class,
            'supply_item_id'
        );
    }

    public function stock(): HasOne
{
    return $this->hasOne(
        SupplyStock::class,
        'supply_item_id'
    );
}
}
