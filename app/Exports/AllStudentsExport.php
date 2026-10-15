<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Exports\AllStudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class AllStudentsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithColumnFormatting
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query(): Builder
    {
        $query = Student::query();

        if (!empty($this->filters['search'])) {
            $search = trim($this->filters['search']);

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

        foreach (['class', 'section', 'academic_year', 'status'] as $field) {
            if (
                isset($this->filters[$field]) &&
                $this->filters[$field] !== ''
            ) {
                $query->where($field, $this->filters[$field]);
            }
        }

        return $query->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Marathi Name',
            'Gender',
            'Date of Birth',
            'Birth Place',
            'Aadhaar Number',
            'Phone',
            'Email',
            'Roll Number',
            'Academic Year',
            'Class',
            'Section',
            'Admission Class',
            'Admission Date',
            'Register Number',
            'Book Number',
            'APPAR ID',
            'PEN Number',
            'SARAL ID',
            'Medium',
            'Mother Tongue',
            'Nationality',
            'Religion',
            'Caste',
            'Sub Caste',
            'Status',
            'Father Name',
            'Father Phone',
            'Father Occupation',
            'Mother Name',
            'Mother Phone',
            'Mother Occupation',
            'Guardian Name',
            'Guardian Relation',
            'Guardian Phone',
            'Previous School Name',
            'Previous School Address',
            'Previous School Class',
            'Previous School Medium',
            'Previous School Board',
            'Previous School Result',
            'Previous School Remarks',
            'Address',
            'Country',
            'State',
            'District',
            'Taluka',
            'City / Village',
            'Pincode',
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_id,
            $student->first_name,
            $student->middle_name,
            $student->last_name,
            $student->marathi_name,
            $student->gender,
            $student->date_of_birth?->format('Y-m-d'),
            $student->birth_place,
            $student->aadhar_card_no,
            $student->phone,
            $student->email,
            $student->roll_number,
            $student->academic_year,
            $student->class,
            $student->section,
            $student->admission_class,
            $student->admission_date?->format('Y-m-d'),
            $student->register_no,
            $student->book_no,
            $student->appar_id,
            $student->pen_no,
            $student->saral_id,
            $student->medium,
            $student->mother_tongue,
            $student->nationality,
            $student->religion,
            $student->caste,
            $student->sub_caste,
            $student->status,
            $student->father_name,
            $student->father_phone,
            $student->father_occupation,
            $student->mother_name,
            $student->mother_phone,
            $student->mother_occupation,
            $student->guardian_name,
            $student->guardian_relation,
            $student->guardian_phone,
            $student->previous_school_name,
            $student->previous_school_address,
            $student->previous_school_class,
            $student->previous_school_medium,
            $student->previous_school_board,
            $student->previous_school_result,
            $student->previous_school_remarks,
            $student->address,
            $student->country,
            $student->state,
            $student->district,
            $student->taluka,
            $student->city_village,
            $student->pincode,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A'  => NumberFormat::FORMAT_TEXT,
            'I'  => NumberFormat::FORMAT_TEXT,
            'J'  => NumberFormat::FORMAT_TEXT,
            'K'  => NumberFormat::FORMAT_TEXT,
            'L'  => NumberFormat::FORMAT_TEXT,
            'R'  => NumberFormat::FORMAT_TEXT,
            'S'  => NumberFormat::FORMAT_TEXT,
            'T'  => NumberFormat::FORMAT_TEXT,
            'U'  => NumberFormat::FORMAT_TEXT,
            'V'  => NumberFormat::FORMAT_TEXT,
            'AE' => NumberFormat::FORMAT_TEXT,
            'AH' => NumberFormat::FORMAT_TEXT,
            'AL' => NumberFormat::FORMAT_TEXT,
            'AZ' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:AZ1')->getFont()->setBold(true);

        $sheet->getStyle('A1:AZ1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('D9EAD3');

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:AZ1');

        return [];
    }

    public function export(Request $request)
{
    $filters = $request->only([
        'search',
        'class',
        'section',
        'academic_year',
        'status',
    ]);

    return Excel::download(
        new AllStudentsExport($filters),
        'all_students_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
    );
}
}
