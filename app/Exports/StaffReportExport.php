<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $staff;

    public function __construct(Collection $staff)
    {
        $this->staff = $staff;
    }

    public function collection()
    {
        return $this->staff;
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
            'Phone',
            'Email',
            'Date of Birth',
            'Joining Date',
            'Status',
            'Address',
        ];
    }

    public function map($staff): array
    {
        return [
            $staff->staff_id,
            $staff->name,
            $staff->designation,
            $staff->department,
            $staff->qualification,
            $staff->gender,
            $staff->phone,
            $staff->email,
            $staff->date_of_birth?->format('d M Y'),
            $staff->joining_date?->format('d M Y'),
            $staff->status,
            $staff->address,
        ];
    }
}