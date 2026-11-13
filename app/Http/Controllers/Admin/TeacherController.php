<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Exports\TeachersExport;
use App\Exports\TeacherImportTemplateExport;
use Barryvdh\DomPDF\Facade\Pdf;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class TeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DISPLAY ALL TEACHERS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $schoolId = $this->schoolId();
        $schoolTeachers = Teacher::where('school_id', $schoolId);

        $teachers = (clone $schoolTeachers)->latest()->get();
        $totalTeachers = (clone $schoolTeachers)->count();
        $activeTeachers = (clone $schoolTeachers)->where('status', 'Active')->count();
        $inactiveTeachers = (clone $schoolTeachers)->where('status', 'Inactive')->count();

        return view('admin.teachers.index', compact(
            'teachers',
            'totalTeachers',
            'activeTeachers',
            'inactiveTeachers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TEACHER
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.teachers.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TEACHER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email',
            'phone' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'subject' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $teacher = new Teacher();
        $teacher->school_id = $this->schoolId();

        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->email = $validated['email'];
        $teacher->phone = $validated['phone'] ?? null;
        $teacher->date_of_birth = $validated['date_of_birth'] ?? null;
        $teacher->gender = $validated['gender'] ?? null;
        $teacher->qualification = $validated['qualification'] ?? null;
        $teacher->joining_date = $validated['joining_date'] ?? null;
        $teacher->address = $validated['address'] ?? null;
        $teacher->subject = $validated['subject'] ?? null;
        $teacher->status = $validated['status'];

        if ($request->hasFile('profile_image')) {
            $upload = Cloudinary::uploadApi()->upload(
                $request->file('profile_image')->getRealPath(),
                ['folder' => 'school-management/teachers']
            );

            $teacher->profile_image = $upload['secure_url'] ?? null;
        }

        DB::transaction(function () use ($teacher) {
            $teacher->save();

            $teacher->teacher_id = 'TCH-' . str_pad(
                $teacher->id,
                5,
                '0',
                STR_PAD_LEFT
            );

            $teacher->save();
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher added successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW TEACHER
    |--------------------------------------------------------------------------
    */

    public function show(Teacher $teacher)
    {
        $this->ensureTeacherBelongsToSchool($teacher);
        return view('admin.teachers.show', compact('teacher'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT TEACHER
    |--------------------------------------------------------------------------
    */

    public function edit(Teacher $teacher)
    {
        $this->ensureTeacherBelongsToSchool($teacher);
        return view('admin.teachers.edit', compact('teacher'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TEACHER
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Teacher $teacher)
    {
        $this->ensureTeacherBelongsToSchool($teacher);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^[6-9][0-9]{9}$/',
                Rule::unique('teachers', 'phone')->ignore($teacher->id),
            ],

            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'qualification' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('profile_image')) {
            $upload = Cloudinary::uploadApi()->upload(
                $request->file('profile_image')->getRealPath(),
                ['folder' => 'school-management/teachers']
            );

            $validated['profile_image'] =
                $upload['secure_url'] ?? $teacher->profile_image;
        }

        $teacher->update($validated);

        if (empty($teacher->teacher_id)) {
            $teacher->teacher_id = 'TCH-' . str_pad(
                $teacher->id,
                5,
                '0',
                STR_PAD_LEFT
            );

            $teacher->save();
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TEACHER
    |--------------------------------------------------------------------------
    */

    public function destroy(Teacher $teacher)
    {
        $this->ensureTeacherBelongsToSchool($teacher);
        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT TEACHERS TO EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel()
    {
        // Temporarily block this endpoint until TeachersExport is updated to
        // query only the authenticated school's teachers. This prevents a
        // school admin from exporting another school's records.
        abort(503, 'Teacher Excel export is temporarily unavailable while school-level access is being secured.');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT TEACHERS TO PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf()
    {
        $teachers = Teacher::where('school_id', $this->schoolId())
            ->orderBy('first_name')
            ->get();

        $pdf = Pdf::loadView(
            'admin.teachers.pdf',
            compact('teachers')
        )->setPaper('a4', 'landscape');

        return $pdf->download('teachers.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD IMPORT TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function importTemplate()
    {
        return Excel::download(
            new TeacherImportTemplateExport(),
            'teacher_import_template.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE GENDER
    |--------------------------------------------------------------------------
    */

    /**
     * Return the authenticated school admin's school ID.
     * School dashboard accounts without a school assignment must not access this module.
     */
    private function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        abort_if(!$schoolId, 403, 'Your account is not assigned to a school.');

        return (int) $schoolId;
    }

    /**
     * Prevent changing or viewing a teacher owned by another school.
     */
    private function ensureTeacherBelongsToSchool(Teacher $teacher): void
    {
        abort_unless(
            (int) $teacher->school_id === $this->schoolId(),
            404
        );
    }

    private function normalizeGender($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim((string) $value));

        if ($value === '') {
            return null;
        }

        return match ($value) {
            'male', 'm', 'man' => 'Male',
            'female', 'f', 'woman' => 'Female',
            'other', 'others' => 'Other',
            default => '__INVALID__',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATE
    |--------------------------------------------------------------------------
    | Supports Excel serial numbers, date cells, and common text dates.
    |--------------------------------------------------------------------------
    */

    private function normalizeExcelDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            $number = (float) $value;

            // Excel serial dates are positive numbers.
            if ($number <= 0 || $number > 2958465) {
                throw new \InvalidArgumentException('Invalid Excel date.');
            }

            return ExcelDate::excelToDateTimeObject($number)
                ->format('Y-m-d');
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        // Accept common spreadsheet date formats.
        foreach ([
            'Y-m-d',
            'd/m/Y',
            'd-m-Y',
            'd.m.Y',
            'm/d/Y',
            'm-d-Y',
            'd/m/y',
            'd-m-y',
        ] as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $dateErrors = \DateTime::getLastErrors();

            if (
                $date !== false
                && (
                    $dateErrors === false
                    || (
                        $dateErrors['warning_count'] === 0
                        && $dateErrors['error_count'] === 0
                    )
                )
                && $date->format($format) === $value
            ) {
                return $date->format('Y-m-d');
            }
        }

        // Also accept readable dates such as 15 Oct 2026.
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Invalid date.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT TEACHERS FROM EXCEL
    |--------------------------------------------------------------------------
    */

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $errors = [];
        $imported = 0;

        try {
            $spreadsheet = IOFactory::load(
                $request->file('file')->getRealPath()
            );

            $sheet = $spreadsheet->getActiveSheet();

            // Keep raw cell values so Excel serial dates remain detectable.
            $rows = $sheet->toArray(null, true, false, false);

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            if (count($rows) < 2) {
                return redirect()
                    ->route('admin.teachers.index')
                    ->with('error', 'The Excel file has no teacher data.');
            }

            // Normalize headings.
            $headers = array_map(function ($header) {
                $header = strtolower(trim((string) $header));
                $header = preg_replace('/[^a-z0-9]+/', '_', $header);

                return trim($header, '_');
            }, $rows[0]);

            $aliases = [
                'firstname' => 'first_name',
                'teacher_first_name' => 'first_name',
                'lastname' => 'last_name',
                'teacher_last_name' => 'last_name',
                'teacher_email' => 'email',
                'email_address' => 'email',
                'mobile' => 'phone',
                'mobile_number' => 'phone',
                'phone_number' => 'phone',
                'contact_number' => 'phone',
                'dob' => 'date_of_birth',
                'dateofbirth' => 'date_of_birth',
                'birth_date' => 'date_of_birth',
                'date_of_joining' => 'joining_date',
                'joiningdate' => 'joining_date',
                'date_of_birth' => 'date_of_birth',
                'qualification_degree' => 'qualification',
                'subject_name' => 'subject',
                'teacher_status' => 'status',
                'gender_name' => 'gender',
            ];

            foreach ($headers as &$header) {
                if (isset($aliases[$header])) {
                    $header = $aliases[$header];
                }
            }
            unset($header);

            foreach (['first_name', 'last_name', 'email'] as $required) {
                if (!in_array($required, $headers, true)) {
                    return redirect()
                        ->route('admin.teachers.index')
                        ->with(
                            'error',
                            "Missing Excel column: {$required}. Download a fresh Import Template and keep its column headings."
                        );
                }
            }

            foreach (array_slice($rows, 1) as $index => $values) {
                $rowNumber = $index + 2;

                // Skip blank rows.
                $hasData = collect($values)->contains(
                    fn ($value) => $value !== null
                        && trim((string) $value) !== ''
                );

                if (!$hasData) {
                    continue;
                }

                $row = [];

                foreach ($headers as $columnIndex => $header) {
                    if ($header !== '') {
                        $row[$header] = $values[$columnIndex] ?? null;
                    }
                }

                foreach ([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'qualification',
                    'address',
                    'subject',
                    'status',
                ] as $field) {
                    if (isset($row[$field])) {
                        $row[$field] = trim((string) $row[$field]);
                    }
                }

                // Normalize gender before validation.
                $gender = $this->normalizeGender($row['gender'] ?? null);

                if ($gender === '__INVALID__') {
                    $errors[] =
                        "Excel row {$rowNumber}: Gender must be Male, Female, Other, M, F, or blank.";

                    continue;
                }

                $row['gender'] = $gender;

                // Normalize both date fields independently.
                $dateInvalid = false;

                foreach (['date_of_birth', 'joining_date'] as $field) {
                    try {
                        $row[$field] = $this->normalizeExcelDate(
                            $row[$field] ?? null
                        );
                    } catch (\Throwable $e) {
                        $errors[] =
                            "Excel row {$rowNumber}: Invalid date in {$field}. Use YYYY-MM-DD or DD/MM/YYYY.";

                        $dateInvalid = true;
                    }
                }

                if ($dateInvalid) {
                    continue;
                }

                // Empty optional values should be stored as null.
                foreach ([
                    'phone',
                    'date_of_birth',
                    'gender',
                    'qualification',
                    'joining_date',
                    'address',
                    'subject',
                ] as $field) {
                    if (
                        !isset($row[$field])
                        || trim((string) $row[$field]) === ''
                    ) {
                        $row[$field] = null;
                    }
                }

                // Default status if the Excel cell is blank.
                if (empty($row['status'])) {
                    $row['status'] = 'Active';
                } else {
                    $status = strtolower(trim($row['status']));

                    $row['status'] = match ($status) {
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        default => $row['status'],
                    };
                }

                $validator = Validator::make($row, [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'nullable|string|max:10',
                    'date_of_birth' => 'nullable|date',
                    'gender' => 'nullable|in:Male,Female,Other',
                    'qualification' => 'nullable|string|max:255',
                    'joining_date' => 'nullable|date',
                    'address' => 'nullable|string|max:1000',
                    'subject' => 'nullable|string|max:255',
                    'status' => 'required|in:Active,Inactive',
                ]);

                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $errors[] = "Excel row {$rowNumber}: {$message}";
                    }

                    continue;
                }

                // Check duplicate email before inserting.
                if (Teacher::where('school_id', $this->schoolId())
                    ->where('email', $row['email'])
                    ->exists()) {
                    $errors[] =
                        "Excel row {$rowNumber}: Email {$row['email']} already exists.";

                    continue;
                }

                // Insert the teacher and generated ID together.
                $schoolId = $this->schoolId();

                DB::transaction(function () use ($row, $schoolId) {
                    $teacher = new Teacher();
                    $teacher->school_id = $schoolId;

                    $teacher->first_name = $row['first_name'];
                    $teacher->last_name = $row['last_name'];
                    $teacher->email = $row['email'];
                    $teacher->phone = $row['phone'] ?? null;
                    $teacher->date_of_birth = $row['date_of_birth'] ?? null;
                    $teacher->gender = $row['gender'] ?? null;
                    $teacher->qualification = $row['qualification'] ?? null;
                    $teacher->joining_date = $row['joining_date'] ?? null;
                    $teacher->address = $row['address'] ?? null;
                    $teacher->subject = $row['subject'] ?? null;
                    $teacher->status = $row['status'];

                    $teacher->save();

                    $teacher->teacher_id = 'TCH-' . str_pad(
                        $teacher->id,
                        5,
                        '0',
                        STR_PAD_LEFT
                    );

                    $teacher->save();
                });

                $imported++;
            }
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.teachers.index')
                ->with(
                    'error',
                    'Unable to read the Excel file. Please download a fresh .xlsx template and try again.'
                );
        }

        if ($imported === 0) {
            return redirect()
                ->route('admin.teachers.index')
                ->with('error', 'No teachers were imported. Please correct the Excel rows and try again.')
                ->with('import_errors', $errors);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', "{$imported} teacher(s) imported successfully.")
            ->with('import_errors', $errors);
    }
}
