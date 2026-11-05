<?php

namespace App\Exports;

use App\Models\Transport\TransportRoute;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransportRouteReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $routeId;

    public function __construct($routeId = null)
    {
        $this->routeId = $routeId;
    }

    public function collection()
    {
        $query = TransportRoute::with(['vehicle', 'driver'])
            ->orderBy('route_number');

        if ($this->routeId) {
            $query->where('id', $this->routeId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Route Number',
            'Route Name',
            'Starting Point',
            'Destination',
            'Stops',
            'Vehicle Number',
            'Vehicle Type',
            'Driver Name',
            'Driver Contact',
            'Status',
            'Remarks',
        ];
    }

    public function map($route): array
    {
        return [
            $route->route_number,
            $route->route_name,
            $route->starting_point,
            $route->destination,
            $route->stops,
            $route->vehicle?->vehicle_number ?? $route->assigned_vehicle,
            $route->vehicle?->vehicle_type,
            $route->driver?->name ?? $route->driver_name,
            $route->driver?->phone ?? $route->driver_contact,
            ucfirst($route->status ?? ''),
            $route->remarks,
        ];
    }
}
