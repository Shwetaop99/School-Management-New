<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentTransportReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $studentId;

    public function __construct(int $studentId)
    {
        $this->studentId = $studentId;
    }

    public function collection()
    {
        return Student::query()
            ->whereKey($this->studentId)
            ->with('transportRecords')
            ->get()
            ->flatMap(function ($student) {
                return $student->transportRecords;
            });
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Class',
            'Section',
            'Academic Year',
            'Transport Status',
            'Route',
            'Vehicle',
            'Transport Type',
            'Pickup Point',
            'Drop Point',
            'Pickup Time',
            'Drop Time',
            'Transport Fee',
            'Fee Frequency',
            'Payment Status',
            'Start Date',
            'End Date',
            'Remarks',
        ];
    }

    public function map($record): array
    {
        $student = $record->student;

        return [
            $student?->student_id,
            $student?->full_name,
            $student?->class,
            $student?->section,
            $student?->academic_year,
            ucfirst($record->transport_status ?? ''),
            $record->route,
            $record->vehicle,
            $record->transport_type,
            $record->pickup_point,
            $record->drop_point,
            $record->pickup_time?->format('h:i A'),
            $record->drop_time?->format('h:i A'),
            $record->transport_fee,
            ucfirst($record->fee_frequency ?? ''),
            ucfirst($record->payment_status ?? ''),
            $record->start_date?->format('d-m-Y'),
            $record->end_date?->format('d-m-Y'),
            $record->remarks,
        ];
    }
}
