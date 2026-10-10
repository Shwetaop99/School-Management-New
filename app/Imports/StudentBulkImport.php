<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\SchoolSetting;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;



class StudentBulkImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    \Maatwebsite\Excel\Concerns\SkipsEmptyRows{
    /**
     * Fields allowed during bulk import.
     */
    private array $fields = [
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


    public function isEmptyWhen(array $row): bool
{
    // Ignore rows where only the pre-generated student ID exists.
    foreach ($row as $key => $value) {
        if ($key === 'student_id') {
            continue;
        }

        if ($value !== null && trim((string) $value) !== '') {
            return false;
        }
    }

    return true;
}
    /**
     * Import one spreadsheet row.
     */
    public function model(array $row)
    {
        $row = $this->normalizeRow($row);

        $studentId = trim((string) ($row['student_id'] ?? ''));

        $student = $this->findExistingStudent($row, $studentId);

        $data = [];

        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $row)) {
                continue;
            }

            $value = $row[$field];

            // Blank spreadsheet cells must not erase existing values.
            if ($value === null || (is_string($value) && trim($value) === '')) {
                continue;
            }

            if (in_array($field, ['date_of_birth', 'admission_date'], true)) {
                $value = $this->normalizeDate($value, $field);
            }

            $data[$field] = $value;
        }

        if ($student) {
            // Never change the existing student's ID during an update.
            unset($data['student_id']);

            $student->fill($data);

            return $student;
        }

        // Required for new admissions because student_id is NOT NULL.
        if ($studentId === '') {
            $studentId = $this->generateStudentId(
                (string) ($row['class'] ?? ''),
                (string) ($row['section'] ?? '')
            );
        }

        $data['student_id'] = $studentId;

        // The database defaults country/state/status, but set them explicitly
        // for predictable imports.
        $data['country'] = $data['country'] ?? 'India';
        $data['state'] = $data['state'] ?? 'Maharashtra';
        $data['status'] = $data['status'] ?? 'active';

        return new Student($data);
    }

    /**
     * Match existing students using reliable identifiers.
     *
     * student_id is checked first. saral_id is unique in the database.
     * Non-unique identifiers are used only when they identify exactly one
     * student and do not conflict with another identifier.
     */
    private function findExistingStudent(array $row, string $studentId): ?Student
    {
        $matches = [];

        $identifiers = [
            'student_id',
            'saral_id',
            'pen_no',
            'appar_id',
            'register_no',
        ];

        foreach ($identifiers as $field) {
            $value = trim((string) ($row[$field] ?? ''));

            if ($value === '') {
                continue;
            }

            $query = Student::query();

            if ($field === 'student_id') {
                $query->where('student_id', $value);
            } else {
                $query->where($field, $value);
            }

            $found = $query->get();

            if ($found->count() > 1) {
                throw ValidationException::withMessages([
                    'student_id' => "Import stopped: identifier '{$field}' value '{$value}' matches multiple students.",
                ]);
            }

            if ($found->isNotEmpty()) {
                $matches[$found->first()->id] = $found->first();
            }
        }

        if (count($matches) > 1) {
            throw ValidationException::withMessages([
                'student_id' => 'Import stopped: identifiers in this row point to different students. Please correct the spreadsheet.',
            ]);
        }

        return count($matches) === 1 ? array_values($matches)[0] : null;
    }

    /**
     * Generate an ID using the school's configured format.
     *
     * Supported placeholders: {YEAR}, {YY}, {CLASS}, {SECTION}, {NUMBER}.
     */
    private function generateStudentId(?string $class, ?string $section): string
    {
        $school = SchoolSetting::query()->first();

        $format = $school?->student_id_format ?: 'STU-{YEAR}-{NUMBER}';
        $startNumber = (int) ($school?->student_id_start ?: 1);
        $numberLength = (int) ($school?->student_id_length ?: 4);

        $year = now()->year;
        $shortYear = substr((string) $year, -2);

        $class = trim((string) $class);
        $section = trim((string) $section);

        // Scan all students, including soft-deleted records, so an old ID
        // is never reused.
        $existingIds = Student::withTrashed()
            ->whereNotNull('student_id')
            ->pluck('student_id');

        $nextNumber = $startNumber;

        foreach ($existingIds as $existingId) {
            $pattern = preg_quote($format, '/');

            $pattern = str_replace('\{NUMBER\}', '([0-9]+)', $pattern);
            $pattern = str_replace('\{YEAR\}', preg_quote((string) $year, '/'), $pattern);
            $pattern = str_replace('\{YY\}', preg_quote($shortYear, '/'), $pattern);
            $pattern = str_replace('\{CLASS\}', '.*?', $pattern);
            $pattern = str_replace('\{SECTION\}', '.*?', $pattern);

            if (preg_match('/^' . $pattern . '$/', (string) $existingId, $matches)) {
                if (isset($matches[1]) && is_numeric($matches[1])) {
                    $nextNumber = max($nextNumber, ((int) $matches[1]) + 1);
                }
            }
        }

        do {
            $number = str_pad((string) $nextNumber, $numberLength, '0', STR_PAD_LEFT);

            $studentId = str_replace(
                ['{YEAR}', '{YY}', '{CLASS}', '{SECTION}', '{NUMBER}'],
                [$year, $shortYear, $class, $section, $number],
                $format
            );

            $exists = Student::withTrashed()
                ->where('student_id', $studentId)
                ->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $studentId;
    }

    /**
     * Normalize dates from Excel serial numbers or text.
     */
    private function normalizeDate($value, string $field): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)
                    ->format('Y-m-d');
            }

            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                $field => "Invalid date '{$value}'. Use YYYY-MM-DD.",
            ]);
        }
    }

    
public function prepareForValidation($data, $index): array
{
    return $this->normalizeRow($data);
}


    /**
     * Normalize heading values and empty cells.
     */
    
private function normalizeRow(array $row): array
{
    // Excel may return numeric cells as integers or floats.
    // Convert identifier and phone fields to strings before validation.
    $stringFields = [
        'student_id',
        'phone',
        'father_phone',
        'mother_phone',
        'guardian_phone',
        'aadhar_card_no',
        'pen_no',
        'appar_id',
        'saral_id',
        'register_no',
        'book_no',
        'roll_number',
        'pincode',
    ];

    foreach ($row as $key => $value) {
        if ($value === null) {
            continue;
        }

        if (is_string($value)) {
            $row[$key] = trim($value);
            continue;
        }

        if (in_array($key, $stringFields, true) && is_numeric($value)) {
            // Avoid unnecessary ".0" for whole-number Excel values.
            $row[$key] = is_float($value) && floor($value) === $value
                ? (string) (int) $value
                : (string) $value;
        }
    }

    return $row;
}


    /**
     * Validate spreadsheet rows.
     */
    public function rules(): array
    {
        return [
            '*.student_id' => ['nullable', 'string', 'max:255'],
            '*.first_name' => ['required', 'string', 'max:255'],
            '*.middle_name' => ['nullable', 'string', 'max:255'],
            '*.last_name' => ['required', 'string', 'max:255'],
            '*.gender' => ['nullable', 'string', 'max:50'],
            '*.date_of_birth' => ['nullable'],
            '*.admission_date' => ['nullable'],
            '*.email' => ['nullable', 'email', 'max:255'],
            '*.phone' => ['nullable', 'string', 'max:15'],
            '*.father_phone' => ['nullable', 'string', 'max:15'],
            '*.mother_phone' => ['nullable', 'string', 'max:15'],
            '*.guardian_phone' => ['nullable', 'string', 'max:15'],
            '*.aadhar_card_no' => ['nullable', 'string', 'max:12'],
            '*.academic_year' => ['nullable', 'string', 'max:255'],
            '*.class' => ['nullable', 'string', 'max:255'],
            '*.section' => ['nullable', 'string', 'max:255'],
            '*.status' => ['nullable', 'in:active,inactive,transferred,graduated'],
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }
}
