<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    /**
     * Database table.
     */
    protected $table = 'school_settings';

    /**
     * Fields allowed for mass assignment.
     */
    protected $fillable = [
        'school_name',
        'school_code',
        'udise_code',
        'address',
        'city',
        'district',
        'state',
        'pincode',
        'phone',
        'email',
        'website',
        'logo',
        'principal_name',
        'established_year',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'established_year' => 'integer',
    ];

    /**
     * Get the school logo URL.
     *
     * The logo field contains the Cloudinary secure URL.
     */
    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo)) {
            return $this->logo;
        }

        return asset('images/gurukullogo.png');
    }
}
