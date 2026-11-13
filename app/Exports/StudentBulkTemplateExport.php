<?php

namespace App\Exports;

use App\Models\SchoolSetting;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class StudentBulkTemplateExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    public function array(): array
    {
        $school = SchoolSetting::query()->first();

        $format = $school?->student_id_format ?: 'STU-{YEAR}-{NUMBER}';
        $startNumber = (int) ($school?->student_id_start ?: 1);
        $numberLength = (int) ($school?->student_id_length ?: 4);

        $year = now()->year;
        $shortYear = substr((string) $year, -2);

        // Get existing IDs, including soft-deleted students.
        $existingIds = Student::withTrashed()
            ->whereNotNull('student_id')
            ->pluck('student_id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $usedIds = array_fill_keys($existingIds, true);

        // Find the next available number for the configured format.
        $patternParts = preg_split(
            '/(\{YEAR\}|\{YY\}|\{CLASS\}|\{SECTION\}|\{NUMBER\})/',
            $format,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $regex = '';

        foreach ($patternParts as $part) {
            $regex .= match ($part) {
                '{YEAR}' => preg_quote((string) $year, '/'),
                '{YY}' => preg_quote($shortYear, '/'),
                '{CLASS}', '{SECTION}' => '.*?',
                '{NUMBER}' => '([0-9]+)',
                default => preg_quote($part, '/'),
            };
        }

        $pattern = '/^' . $regex . '$/';

        $nextNumber = $startNumber;

        foreach ($existingIds as $existingId) {
            if (preg_match($pattern, $existingId, $matches)) {
                if (isset($matches[1]) && is_numeric($matches[1])) {
                    $nextNumber = max(
                        $nextNumber,
                        ((int) $matches[1]) + 1
                    );
                }
            }
        }

        $rows = [];

        // Prepare 100 rows with pre-generated Student IDs.
        for ($i = 0; $i < 100; $i++) {
            do {
                $number = str_pad(
                    (string) $nextNumber,
                    $numberLength,
                    '0',
                    STR_PAD_LEFT
                );

                $studentId = str_replace(
                    ['{YEAR}', '{YY}', '{CLASS}', '{SECTION}', '{NUMBER}'],
                    [$year, $shortYear, '', '', $number],
                    $format
                );

                $nextNumber++;
            } while (isset($usedIds[$studentId]));

            $usedIds[$studentId] = true;

            // Keep the Student ID in the first column.
            $rows[] = array_merge(
                [$studentId],
                array_fill(0, count($this->headings()) - 1, null)
            );
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'student_id',
            'first_name',
            'middle_name',
            'last_name',
            'marathi_name',
            'gender',
            'date_of_birth',
            'birth_place',
            'aadhar_card_no',
            'phone',
            'email',
            'roll_number',
            'academic_year',
            'class',
            'section',
            'admission_class',
            'admission_date',
            'register_no',
            'book_no',
            'appar_id',
            'pen_no',
            'saral_id',
            'medium',
            'mother_tongue',
            'nationality',
            'religion',
            'caste',
            'sub_caste',
            'status',
            'father_name',
            'father_phone',
            'father_occupation',
            'mother_name',
            'mother_phone',
            'mother_occupation',
            'guardian_name',
            'guardian_relation',
            'guardian_phone',
            'previous_school_name',
            'previous_school_address',
            'previous_school_class',
            'previous_school_medium',
            'previous_school_board',
            'previous_school_result',
            'previous_school_remarks',
            'address',
            'country',
            'state',
            'district',
            'taluka',
            'city_village',
            'pincode',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        $sheet->getStyle('1:1')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()
            ->setRGB('D9EAF7');

        // Preserve IDs as text in Excel.
        $sheet->getStyle('A2:A101')
            ->getNumberFormat()
            ->setFormatCode('@');

        $sheet->freezePane('A2');

        return [];
    }
}
