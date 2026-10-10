<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitTemplate extends Model
{
    use HasFactory;

    protected $table = 'kit_templates';

    protected $fillable = [
        'kit_name',
        'class',
        'academic_year',
        'description',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(
            KitTemplateItem::class,
            'kit_template_id'
        );
    }

    public function studentSupplyKits()
    {
        return $this->hasMany(
            StudentSupplyKit::class,
            'kit_template_id'
        );
    }
}
