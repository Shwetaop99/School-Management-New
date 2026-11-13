<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitTemplate extends Model
{
    use HasFactory;

    protected $table = 'kit_templates';

    protected $fillable = [
        'scheme_id',
        'class_id',
        'kit_name',
        'academic_year',
        'description',
        'status',
    ];

    /**
     * Government Scheme
     */
    public function scheme()
    {
        return $this->belongsTo(
            GovernmentScheme::class,
            'scheme_id'
        );
    }

    /**
     * School Class
     */
    public function schoolClass()
    {
        return $this->belongsTo(
            \App\Models\Class\SchoolClass::class,
            'class_id'
        );
    }

    /**
     * Kit Items
     */
    public function items()
    {
        return $this->hasMany(
            KitTemplateItem::class,
            'kit_template_id'
        );
    }

    /**
     * Student Supply Kits
     */
    public function studentSupplyKits()
    {
        return $this->hasMany(
            StudentSupplyKit::class,
            'kit_template_id'
        );
    }
}
