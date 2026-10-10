<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherStaff extends Model
{
    protected $table = 'other_staff';

    protected $fillable = [
        'staff_id',
        'name',
        'profile_photo',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'designation',
        'department',
        'qualification',
        'license_number',
        'license_expiry',
        'joining_date',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'license_expiry' => 'date',
    ];
}