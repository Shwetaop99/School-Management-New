<?php

namespace App\Models\Transport;

use App\Models\OtherStaff;
use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    protected $table = 'transport_vehicles';

    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'vehicle_model',
        'vehicle_color',
        'capacity',

        // Driver relationship
        'driver_id',

        // Stored driver details
        'driver_name',
        'driver_contact',
        'driver_license_number',

        'insurance_expiry',
        'fitness_expiry',
        'permit_expiry',
        'status',
        'remarks',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'insurance_expiry' => 'date',
        'fitness_expiry' => 'date',
        'permit_expiry' => 'date',
    ];

    /**
     * Driver assigned to this vehicle.
     */
    public function driver()
    {
        return $this->belongsTo(
            OtherStaff::class,
            'driver_id'
        );
    }
}