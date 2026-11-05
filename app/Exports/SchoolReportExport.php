<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SchoolReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $schools;

    public function __construct(Collection $schools)
    {
        $this->schools = $schools;
    }

    public function collection()
    {
        return $this->schools;
    }

    public function headings(): array
    {
        return [
            'School Name',
            'School Code',
            'City',
            'District',
            'State',
            'School Type',
            'Education Board',
            'Total Users',
            'Active Users',
            'Inactive Users',
            'Status',
            'Registered Date',
        ];
    }

    public function map($school): array
    {
        return [
            $school->name ?? 'N/A',
            $school->school_code ?? 'N/A',
            $school->city ?? 'N/A',
            $school->district ?? 'N/A',
            $school->state ?? 'N/A',
            $school->school_type ?? 'N/A',
            $school->education_board ?? 'N/A',
            $school->total_users ?? 0,
            $school->active_users ?? 0,
            $school->inactive_users ?? 0,
            ucfirst($school->status ?? 'Unknown'),
            $school->created_at
                ? $school->created_at->format('d M Y')
                : 'N/A',
        ];
    }
}
