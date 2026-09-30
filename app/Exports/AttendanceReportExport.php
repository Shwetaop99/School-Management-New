<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $attendance;

    public function __construct(Collection $attendance)
    {
        $this->attendance = $attendance;
    }

    public function collection()
    {
        return $this->attendance;
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Academic Year',
            'Class',
            'Section',
            'Date',
            'Status',
            'Remarks',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->student_id ?? '-',

            $attendance->student
                ? $attendance->student->first_name . ' ' .
                  $attendance->student->last_name
                : '-',

            $attendance->academic_year ?? '-',

            $attendance->class ?? '-',

            $attendance->section ?? '-',

            $attendance->attendance_date
                ? $attendance->attendance_date->format('d M Y')
                : '-',

            $attendance->status ?? '-',

            $attendance->remarks ?? '-',
        ];
    }
}
