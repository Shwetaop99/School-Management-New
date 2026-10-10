<?php

namespace App\Exports;

use App\Models\Transport\TransportRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Get vehicle report data.
     */
    public function collection()
    {
        return TransportRecord::query()
            ->selectRaw('
                COALESCE(vehicle, "Not Assigned") as vehicle,
                COALESCE(transport_type, "other") as transport_type,
                COUNT(*) as assigned_students,

                SUM(
                    CASE
                        WHEN transport_status = "active"
                        THEN 1
                        ELSE 0
                    END
                ) as active_students,

                SUM(
                    CASE
                        WHEN transport_status = "inactive"
                        THEN 1
                        ELSE 0
                    END
                ) as inactive_students,

                COALESCE(SUM(transport_fee), 0) as total_fees
            ')
            ->groupBy(
                'vehicle',
                'transport_type'
            )
            ->orderBy('vehicle')
            ->get();
    }

    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        return [
            'Vehicle',
            'Transport Type',
            'Assigned Students',
            'Active Students',
            'Inactive Students',
            'Total Fees',
        ];
    }

    /**
     * Format each Excel row.
     */
    public function map($vehicle): array
    {
        return [
            $vehicle->vehicle,

            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $vehicle->transport_type
                )
            ),

            $vehicle->assigned_students,

            $vehicle->active_students,

            $vehicle->inactive_students,

            $vehicle->total_fees,
        ];
    }
}