<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use HasFactory;

    protected $table = 'id_card_templates';

    protected $fillable = [
        'name',
        'slug',
        'template_image',
        'field_positions',
        'academic_year',
        'status',
    ];

    protected $casts = [
        'field_positions' => 'array',
        'status' => 'string',
    ];
}