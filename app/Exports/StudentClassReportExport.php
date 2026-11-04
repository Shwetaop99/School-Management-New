<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentClassReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected string $class;
    protected string $section;
    protected string $academicYear;

    public function __construct(
        string $class,
        string $section,
        string $academicYear
    ) {
        $this->class = trim($class);
        $this->section = trim($section);
        $this->academicYear = trim($academicYear);
    }

    public function query(): Builder
    {
        $normalizedClass = strtolower(
            preg_replace('/^class\s*/i', '', $this->class)
        );

        return Student::query()
            ->whereRaw(
                "LOWER(TRIM(REPLACE(class, 'class', ''))) = ?",
                [$normalizedClass]
            )
            ->where('section', $this->section)
            ->where('academic_year', $this->academicYear)
            ->orderBy('roll_number')
            ->orderBy('first_name');
    }

    public function headings(): array
    {
        return [
            '#',
            'Roll Number',
            'Student ID',
            'Student Name',
            'Class',
            'Section',
            'Academic Year',
            'Gender',
            'Phone',
            'Email',
        ];
    }

    public function map($student): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $student->roll_number ?? '-',
            $student->student_id ?? '-',
            $student->full_name
                ?: trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
            $this->class,
            $student->section ?? '-',
            $student->academic_year ?? '-',
            $student->gender ?? '-',
            $student->phone ?? '-',
            $student->email ?? '-',
        ];
    }
}
