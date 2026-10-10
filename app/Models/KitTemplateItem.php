<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitTemplateItem extends Model
{
    use HasFactory;

    protected $table = 'kit_template_items';

    protected $fillable = [
        'kit_template_id',
        'supply_item_id',
        'quantity',
        'remarks',
    ];

    public function kitTemplate()
    {
        return $this->belongsTo(
            KitTemplate::class,
            'kit_template_id'
        );
    }

    public function supplyItem()
    {
        return $this->belongsTo(
            SupplyItem::class,
            'supply_item_id'
        );
    }
}
