<?php

namespace App\Exports;

use App\Models\TeacherAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeacherAttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $date
    ) {
    }

    public function collection()
    {
        return TeacherAttendance::with('teacher')
            ->whereDate('attendance_date', $this->date)
            ->orderBy('teacher_id')
            ->get()
            ->map(function ($attendance) {
                return [
                    'Faculty ID'       => $attendance->teacher?->teacher_id ?? '-',
                    'Faculty Name'     => trim(
                        ($attendance->teacher?->first_name ?? '') . ' ' .
                        ($attendance->teacher?->last_name ?? '')
                    ),
                    'Subject'          => $attendance->teacher?->subject ?? '-',
                    'Attendance Date'  => $attendance->attendance_date
                        ? $attendance->attendance_date->format('d-m-Y')
                        : '-',
                    'Status'           => $attendance->status ?? '-',
                    'Overtime Hours'   => $attendance->overtime_hours ?? 0,
                    'Remarks'          => $attendance->remarks ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Faculty ID',
            'Faculty Name',
            'Subject',
            'Attendance Date',
            'Status',
            'Overtime Hours',
            'Remarks',
        ];
    }
}