<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSupplyKit extends Model
{
    use HasFactory;

    protected $table = 'student_supply_kits';

    protected $fillable = [
        'student_id',
        'kit_template_id',
        'academic_year',
        'distribution_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'distribution_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Kit Template
    |--------------------------------------------------------------------------
    */

    public function kitTemplate()
    {
        return $this->belongsTo(
            KitTemplate::class,
            'kit_template_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Kit Items
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(
            StudentSupplyKitItem::class,
            'student_supply_kit_id'
        );
    }
}