<?php

namespace App\Exports;

use App\Models\Transport\TransportVehicle;
use App\Models\Transport\TransportRecord;
use App\Models\Transport\TransportRoute;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleIndividualReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $vehicleId;

    public function __construct($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function collection()
    {
        $vehicle = TransportVehicle::with('driver')
            ->findOrFail($this->vehicleId);

        $vehicleNumber = trim((string) $vehicle->vehicle_number);

        $routes = TransportRoute::where(function ($query) use ($vehicle) {
            $query->where('vehicle_id', $vehicle->id)
                ->orWhere('assigned_vehicle', $vehicle->vehicle_number);
        })->pluck('route_name')->filter()->unique()->implode(', ');

        $records = $vehicleNumber !== ''
            ? TransportRecord::with('student')
                ->whereRaw(
                    'LOWER(TRIM(vehicle)) = ?',
                    [mb_strtolower($vehicleNumber)]
                )
                ->latest()
                ->get()
            : collect();

        if ($records->isEmpty()) {
            return collect([
                (object) [
                    'vehicle' => $vehicle,
                    'routes' => $routes,
                    'record' => null,
                ],
            ]);
        }

        return $records->map(function ($record) use ($vehicle, $routes) {
            return (object) [
                'vehicle' => $vehicle,
                'routes' => $routes,
                'record' => $record,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Vehicle Number',
            'Vehicle Type',
            'Model',
            'Color',
            'Capacity',
            'Driver Name',
            'Driver Contact',
            'Driver License',
            'Vehicle Status',
            'Insurance Expiry',
            'Fitness Expiry',
            'Permit Expiry',
            'Assigned Routes',
            'Student ID',
            'Student Name',
            'Transport Status',
            'Pickup Point',
            'Drop Point',
            'Transport Fee',
            'Payment Status',
        ];
    }

    public function map($row): array
    {
        $vehicle = $row->vehicle;
        $record = $row->record;
        $student = $record?->student;

        $studentName = $student
            ? trim(collect([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ])->filter()->implode(' '))
            : '';

        return [
            $vehicle->vehicle_number,
            $vehicle->vehicle_type,
            $vehicle->vehicle_model,
            $vehicle->vehicle_color,
            $vehicle->capacity,
            $vehicle->driver?->name ?? $vehicle->driver_name,
            $vehicle->driver?->phone ?? $vehicle->driver_contact,
            $vehicle->driver_license_number,
            ucfirst($vehicle->status ?? ''),
            $vehicle->insurance_expiry?->format('d-m-Y'),
            $vehicle->fitness_expiry?->format('d-m-Y'),
            $vehicle->permit_expiry?->format('d-m-Y'),
            $row->routes,
            $student?->student_id,
            $studentName,
            ucfirst($record?->transport_status ?? ''),
            $record?->pickup_point,
            $record?->drop_point,
            $record?->transport_fee,
            ucfirst($record?->payment_status ?? ''),
        ];
    }
}