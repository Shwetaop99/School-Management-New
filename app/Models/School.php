<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_code',
        'status',
        'school_type',
        'address_line',
        'city',
        'district',
        'state',
        'postal_code',
        'country',
        'email',
        'phone',
        'website',
        'principal_name',
        'education_board',
        'registration_number',
        'establishment_year',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'school_id');
    }

    public function administrator(): HasOne
    {
        return $this->hasOne(User::class, 'school_id')
            ->whereHas('role', function ($query) {
                $query->where('name', 'admin');
            });
    }

    public function settings(): HasOne
    {
        return $this->hasOne(SchoolSetting::class);
    }
}
