<?php

namespace App\Exports;

use App\Models\OtherStaff;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffProfileReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected OtherStaff $staff;
    protected Collection $assignedVehicles;
    protected Collection $assignedRoutes;

    public function __construct(
        OtherStaff $staff,
        Collection $assignedVehicles,
        Collection $assignedRoutes
    ) {
        $this->staff = $staff;
        $this->assignedVehicles = $assignedVehicles;
        $this->assignedRoutes = $assignedRoutes;
    }

    public function headings(): array
    {
        return [
            'Staff ID',
            'Name',
            'Designation',
            'Department',
            'Qualification',
            'Gender',
            'Date of Birth',
            'Phone',
            'Email',
            'Address',
            'Joining Date',
            'Status',
            'Licence Number',
            'Licence Expiry',
            'Assigned Vehicles',
            'Assigned Routes',
        ];
    }

    public function array(): array
    {
        $staff = $this->staff;

        $vehicles = $this->assignedVehicles
            ->map(function ($vehicle) {
                return implode(' | ', array_filter([
                    $vehicle->vehicle_number,
                    $vehicle->vehicle_type,
                    $vehicle->vehicle_model,
                    $vehicle->capacity !== null
                        ? 'Capacity: ' . $vehicle->capacity
                        : null,
                ]));
            })
            ->implode("\n");

        $routes = $this->assignedRoutes
            ->map(function ($route) {
                $vehicleNumber = $route->vehicle?->vehicle_number
                    ?? $route->assigned_vehicle;

                return implode(' | ', array_filter([
                    $route->route_number,
                    $route->route_name,
                    $route->starting_point && $route->destination
                        ? $route->starting_point . ' to ' . $route->destination
                        : ($route->starting_point ?: $route->destination),
                    $vehicleNumber,
                    $route->status,
                ]));
            })
            ->implode("\n");

        return [
            [
                $staff->staff_id,
                $staff->name,
                $staff->designation,
                $staff->department,
                $staff->qualification,
                $staff->gender,
                $staff->date_of_birth?->format('d M Y'),
                $staff->phone,
                $staff->email,
                $staff->address,
                $staff->joining_date?->format('d M Y'),
                $staff->status,
                $staff->license_number,
                $staff->license_expiry?->format('d M Y'),
                $vehicles,
                $routes,
            ],
        ];
    }
}
