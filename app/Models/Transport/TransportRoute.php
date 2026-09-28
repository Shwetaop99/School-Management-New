<?php

namespace App\Models\Transport;

use App\Models\OtherStaff;
use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $table = 'transport_routes';

    protected $fillable = [
        'route_number',
        'route_name',
        'starting_point',
        'destination',
        'stops',
        'assigned_vehicle',
        'vehicle_id',
        'driver_id',
        'status',
        'remarks',
    ];

    /**
     * Assigned Driver
     */
    public function driver()
    {
        return $this->belongsTo(OtherStaff::class, 'driver_id');
    }

    /**
     * Assigned Vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(
            TransportVehicle::class,
            'vehicle_id'
        );
    }
}