<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Student::query();

        // Student Search
        if (!empty($this->filters['search'])) {

            $search = $this->filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('aadhar_card_no', 'like', "%{$search}%");

            });
        }

        // Academic Year
        if (!empty($this->filters['academic_year'])) {
            $query->where(
                'academic_year',
                $this->filters['academic_year']
            );
        }

        // Class
        if (!empty($this->filters['class'])) {
            $query->where(
                'class',
                $this->filters['class']
            );
        }

        // Section
        if (!empty($this->filters['section'])) {
            $query->where(
                'section',
                $this->filters['section']
            );
        }

        // Gender
        if (!empty($this->filters['gender'])) {
            $query->where(
                'gender',
                $this->filters['gender']
            );
        }

        // Status
        if (!empty($this->filters['status'])) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }

        return $query
            ->orderBy('class')
            ->orderBy('section')
            ->orderBy('roll_number');
    }

    public function headings(): array
    {
        return [
            '#',
            'Student Name',
            'Student ID',
            'Academic Year',
            'Class',
            'Section',
            'Roll No.',
            'Admission Date',
            'Gender',
            'Phone',
            'Status',
        ];
    }

    public function map($student): array
    {
        static $number = 0;

        $number++;

        return [
            $number,
            $student->full_name,
            $student->student_id,
            $student->academic_year,
            $student->class,
            $student->section,
            $student->roll_number,
            $student->admission_date
                ? $student->admission_date->format('d M Y')
                : '-',
            $student->gender ?? '-',
            $student->phone ?? '-',
            ucfirst($student->status ?? '-'),
        ];
    }
}