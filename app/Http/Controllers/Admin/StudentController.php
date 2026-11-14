<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Class\SchoolClass;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Exports\AllStudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT LIST
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;

if (!$schoolId) {
    abort(403, 'Your account is not assigned to a school.');
}

$query = Student::query()
    ->where('school_id', $schoolId);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

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

        if ($request->filled('class')) {
            $query->where('class', $request->input('class'));
        }

        if ($request->filled('section')) {
            $query->where('section', $request->input('section'));
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $academicYears = Student::query()
    ->where('school_id', $schoolId)
    ->whereNotNull('academic_year')
    ->where('academic_year', '!=', '')
    ->distinct()
    ->orderByDesc('academic_year')
    ->pluck('academic_year');

        $classes = SchoolClass::query()
            ->where('status', true)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->select('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name')
            ->values()
            ->toArray();

        $sections = SchoolClass::query()
            ->where('status', true)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section')
            ->values()
            ->toArray();

        $totalStudents = Student::where('school_id', $schoolId)->count();

        $totalActiveStudents = Student::where('school_id', $schoolId)
    ->where('status', 'active')
    ->count();

$totalInactiveStudents = Student::where('school_id', $schoolId)
    ->where('status', '!=', 'active')
    ->count();

$totalMaleStudents = Student::where('school_id', $schoolId)
    ->where('gender', 'Male')
    ->count();

$totalFemaleStudents = Student::where('school_id', $schoolId)
    ->where('gender', 'Female')
    ->count();

        $students = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.students.index',
            compact(
                'students',
                'totalStudents',
                'totalActiveStudents',
                'totalInactiveStudents',
                'totalMaleStudents',
                'totalFemaleStudents',
                'academicYears',
                'classes',
                'sections'
            )
        );
    }

    public function create()
    {
        $schoolClasses = SchoolClass::query()
            ->where('status', true)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->orderBy('class_name')
            ->orderBy('section')
            ->get(['class_name', 'section', 'academic_year']);

        $classes = $schoolClasses->pluck('class_name')->filter()->unique()->values()->toArray();

        $sectionsByClass = $schoolClasses
            ->groupBy('class_name')
            ->map(function ($rows) {
                return $rows->pluck('section')->filter()->unique()->values()->toArray();
            })
            ->toArray();

        return view('admin.students.create', compact('classes', 'sectionsByClass'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);
        $schoolId = auth()->user()?->school_id;

        if (!$schoolId) {
            abort(403, 'Your account is not assigned to a school.');
        }

        $validated['school_id'] = $schoolId;
        DB::beginTransaction();

        try {
            $validated['country'] = $request->input('country', 'India');
            $validated['state'] = $request->input('state', 'Maharashtra');
            $validated['status'] = $request->input('status', 'active');
            $validated['admission_class'] = $request->input('admission_class');
            $validated['class'] = $request->input('admission_class');
            $validated['section'] = $request->input('section');
            $validated['medium'] = $request->input('medium');
            $validated['previous_school_board'] = $request->input('previous_school_board', $request->input('board'));
            $validated['district'] = $request->input('district', $request->input('district_id'));
            $validated['taluka'] = $request->input('taluka', $request->input('taluka_id'));

            $validated['student_id'] = $this->generateStudentId(
                $validated['admission_class'] ?? null,
                $request->input('section'),
                $request->input('academic_year')
            );

            foreach (['roll_number', 'register_no', 'book_no', 'appar_id', 'pen_no', 'saral_id', 'birth_place'] as $field) {
                $validated[$field] = $request->input($field);
            }

            $validated['profile_image'] = null;
            if ($request->hasFile('profile_image')) {
                $validated['profile_image'] = $this->uploadToCloudinary(
                    $request->file('profile_image'),
                    'student_' . uniqid()
                );
            }

            $student = Student::create($validated);
            DB::commit();

            return redirect()
                ->route('admin.students.show', $student)
                ->with('success', 'Student registered successfully. Student ID: ' . $student->student_id);
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->with(
                'error',
                'Student registration failed. Please check the application logs.'
            );
        }
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Student $student)
    {
        $classSections = SchoolClass::query()
            ->where('status', true)
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->orderBy('class_name')
            ->orderBy('section')
            ->get(['class_name', 'section']);

        $classes = $classSections->pluck('class_name')->unique()->values();
        $sections = $classSections
            ->where('class_name', old('class', $student->class))
            ->pluck('section')
            ->unique()
            ->values();

        return view('admin.students.edit', compact('student', 'classes', 'sections', 'classSections'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $this->validateStudent($request, $student);

        foreach (['student_id', 'roll_number', 'register_no', 'book_no', 'appar_id', 'pen_no', 'saral_id'] as $field) {
            $validated[$field] = $student->{$field};
        }

        $validated['country'] = $request->input('country', $student->country ?? 'India');
        $validated['state'] = $request->input('state', $student->state ?? 'Maharashtra');
        $validated['district'] = $request->input('district', $student->district);
        $validated['taluka'] = $request->input('taluka', $student->taluka);
        $validated['status'] = $request->input('status', $student->status ?? 'active');

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            if (!$file->isValid()) {
                return back()->withInput()->withErrors([
                    'profile_image' => 'The uploaded profile image is invalid.',
                ]);
            }

            $validated['profile_image'] = $this->uploadToCloudinary(
                $file,
                'student_' . $student->student_id . '_' . time()
            );
        } else {
            $validated['profile_image'] = $student->profile_image;
        }

        $student->update($validated);

        return redirect()
            ->route('admin.students.show', $student->id)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX - NEXT ROLL NUMBER
    |--------------------------------------------------------------------------
    */
    public function nextRollNumber(Request $request)
    {
        $validated = $request->validate([
            'class' => ['required', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:20'],
            'academic_year' => ['nullable', 'string', 'max:20'],
        ]);

        $class = trim($validated['class']);
        $section = isset($validated['section']) ? trim($validated['section']) : null;
        $academicYear = isset($validated['academic_year']) ? trim($validated['academic_year']) : null;

        $rollNumber = $this->generateRollNumber($class, $section, $academicYear);

        return response()->json([
            'success' => true,
            'roll_number' => $rollNumber,
            'class' => $class,
            'section' => $section,
            'academic_year' => $academicYear,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH STUDENT
    |--------------------------------------------------------------------------
    */
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'string', 'max:100'],
        ]);

        $student = Student::query()
            ->where('student_id', $request->input('student_id'))
            ->where('status', 'active')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.',
            ], 404);
        }

        $studentName = trim(implode(' ', array_filter([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])));

        return response()->json([
            'success' => true,
            'student_id' => $student->student_id,
            'student_name' => $studentName,
            'class' => $student->class,
            'section' => $student->section,
            'date_of_birth' => $student->date_of_birth,
            'parent' => $student->father_name ?? $student->mother_name ?? $student->guardian_name,
            'father_name' => $student->father_name,
            'mother_name' => $student->mother_name,
            'guardian_name' => $student->guardian_name,
            'educational_year' => $student->academic_year,
            'academic_year' => $student->academic_year,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CLOUDINARY UPLOAD
    |--------------------------------------------------------------------------
    */
    private function uploadToCloudinary($file, string $publicId): string
    {
        try {
            if (!$file || !$file->isValid()) {
                throw new \RuntimeException('Uploaded profile image is invalid.');
            }

            $realPath = $file->getRealPath();

            if (empty($realPath) || !file_exists($realPath)) {
                throw new \RuntimeException('Temporary uploaded image file could not be found.');
            }

            $cloudUrl = config('cloudinary.cloud_url')
                ?? config('filesystems.disks.cloudinary.url')
                ?? env('CLOUDINARY_URL');

            if (empty($cloudUrl)) {
                throw new \RuntimeException('Cloudinary configuration is missing.');
            }

            $cloudinary = new Cloudinary();

            $result = $cloudinary->uploadApi()->upload($realPath, [
                'folder' => 'school-management-db/students',
                'public_id' => $publicId,
                'resource_type' => 'image',
            ]);

            $secureUrl = null;

            if (is_array($result)) {
                $secureUrl = $result['secure_url'] ?? $result['url'] ?? null;
            }

            if (empty($secureUrl) && is_object($result)) {
                if (method_exists($result, 'getSecurePath')) {
                    $secureUrl = $result->getSecurePath();
                }
                if (empty($secureUrl) && isset($result->secure_url)) {
                    $secureUrl = $result->secure_url;
                }
                if (empty($secureUrl) && isset($result->url)) {
                    $secureUrl = $result->url;
                }
                if (empty($secureUrl) && method_exists($result, 'toArray')) {
                    $response = $result->toArray();
                    $secureUrl = $response['secure_url'] ?? $response['url'] ?? null;
                }
            }

            if (empty($secureUrl) || !filter_var($secureUrl, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('Cloudinary upload completed, but the image URL could not be read.');
            }

            return $secureUrl;

        } catch (Throwable $e) {
            Log::error('Cloudinary student profile image upload failed.', [
                'message' => $e->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'profile_image' => 'Profile image upload failed: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION (Strong & Secure)
    |--------------------------------------------------------------------------
    */

    
private function validateStudent(
    Request $request,
    ?Student $student = null
): array {
    return $request->validate([
        /*
        |--------------------------------------------------------------------------
        | BASIC INFORMATION
        |--------------------------------------------------------------------------
        */
        'first_name' => ['required', 'string', 'max:100'],
        'middle_name' => ['nullable', 'string', 'max:100'],
        'last_name' => ['required', 'string', 'max:100'],
        'marathi_name' => ['nullable', 'string', 'max:255'],
        'gender' => ['nullable', 'string', 'max:20'],
        'date_of_birth' => ['nullable', 'date'],
        'birth_place' => ['nullable', 'string', 'max:255'],

        /*
        |--------------------------------------------------------------------------
        | AADHAAR
        |--------------------------------------------------------------------------
        */
        'aadhar_card_no' => [
            'nullable',
            'string',
            'regex:/^[2-9][0-9]{11}$/',
            Rule::unique('students', 'aadhar_card_no')
                ->ignore($student?->id),
            function ($attribute, $value, $fail) {
                if (
                    filled($value) &&
                    !$this->isValidAadhaarVerhoeff((string) $value)
                ) {
                    $fail('Please enter a valid Aadhaar number.');
                }
            },
        ],

        'phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
        'email' => ['nullable', 'email', 'max:255'],

        'profile_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],

        /*
        |--------------------------------------------------------------------------
        | ACADEMIC INFORMATION
        |--------------------------------------------------------------------------
        */
        'academic_year' => ['nullable', 'string', 'max:20'],
        'class' => ['nullable', 'string', 'max:50'],
        'current_class' => ['nullable', 'string', 'max:50'],
        'section' => ['nullable', 'string', 'max:20'],
        'admission_class' => ['nullable', 'string', 'max:50'],
        'admission_date' => ['nullable', 'date'],

        /*
        |--------------------------------------------------------------------------
        | STUDENT IDENTIFICATION NUMBERS
        |--------------------------------------------------------------------------
        */

        // APAAR ID: exactly 12 digits.
        'appar_id' => [
            'nullable',
            'string',
            'regex:/^[0-9]{12}$/',
            Rule::unique('students', 'appar_id')
                ->ignore($student?->id),
        ],

        // PEN Number: 11 to 14 digits.
        'pen_no' => [
            'nullable',
            'string',
            'regex:/^[0-9]{11,14}$/',
            Rule::unique('students', 'pen_no')
                ->ignore($student?->id),
        ],

        // Saral ID: state-specific; no universal fixed length.
        'saral_id' => [
            'nullable',
            'string',
            'max:50',
            'regex:/^[a-zA-Z0-9\/_-]+$/',
            Rule::unique('students', 'saral_id')
                ->ignore($student?->id),
        ],

        // Register Number: institutional identifier.
        'register_no' => [
            'required',
            'string',
            'max:50',
            Rule::unique('students', 'register_no')
                ->ignore($student?->id),
        ],

        // Book Number: institutional identifier.
        'book_no' => [
            'required',
            'string',
            'max:50',
            Rule::unique('students', 'book_no')
                ->ignore($student?->id),
        ],

        'roll_number' => ['nullable', 'string', 'max:50'],

        /*
        |--------------------------------------------------------------------------
        | OTHER INFORMATION
        |--------------------------------------------------------------------------
        */
        'medium' => ['nullable', 'string', 'max:50'],
        'mother_tongue' => ['nullable', 'string', 'max:100'],
        'nationality' => ['nullable', 'string', 'max:100'],
        'religion' => ['nullable', 'string', 'max:100'],
        'caste' => ['nullable', 'string', 'max:100'],
        'sub_caste' => ['nullable', 'string', 'max:100'],

        'status' => [
            'nullable',
            Rule::in(['active', 'inactive']),
        ],

        /*
        |--------------------------------------------------------------------------
        | FATHER
        |--------------------------------------------------------------------------
        */
        'father_name' => ['nullable', 'string', 'max:150'],
        'father_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
        'father_occupation' => ['nullable', 'string', 'max:150'],

        /*
        |--------------------------------------------------------------------------
        | MOTHER
        |--------------------------------------------------------------------------
        */
        'mother_name' => ['nullable', 'string', 'max:150'],
        'mother_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
        'mother_occupation' => ['nullable', 'string', 'max:150'],

        /*
        |--------------------------------------------------------------------------
        | GUARDIAN
        |--------------------------------------------------------------------------
        */
        'guardian_name' => ['nullable', 'string', 'max:150'],
        'guardian_relation' => ['nullable', 'string', 'max:100'],
        'guardian_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],

        /*
        |--------------------------------------------------------------------------
        | PREVIOUS SCHOOL
        |--------------------------------------------------------------------------
        */
        'previous_school_name' => ['nullable', 'string', 'max:255'],
        'previous_school_address' => ['nullable', 'string', 'max:500'],
        'previous_school_class' => ['nullable', 'string', 'max:100'],
        'previous_school_medium' => ['nullable', 'string', 'max:100'],
        'previous_school_board' => ['nullable', 'string', 'max:100'],
        'board' => ['nullable', 'string', 'max:100'],
        'previous_school_result' => ['nullable', 'string', 'max:100'],
        'previous_school_remarks' => ['nullable', 'string', 'max:1000'],

        /*
        |--------------------------------------------------------------------------
        | ADDRESS
        |--------------------------------------------------------------------------
        */
        'address' => ['nullable', 'string', 'max:1000'],
        'country' => ['nullable', 'string', 'max:100'],
        'state' => ['nullable', 'string', 'max:100'],
        'district' => ['nullable', 'string', 'max:100'],
        'taluka' => ['nullable', 'string', 'max:100'],
        'city_village' => ['nullable', 'string', 'max:150'],
        'pincode' => ['nullable', 'digits:6'],

    ], [
        'first_name.required' =>
            'First name is required.',

        'phone.regex' =>
            'Please enter a valid 10-digit mobile number.',

        'father_phone.regex' =>
            'Please enter a valid 10-digit father mobile number.',

        'mother_phone.regex' =>
            'Please enter a valid 10-digit mother mobile number.',

        'guardian_phone.regex' =>
            'Please enter a valid 10-digit guardian mobile number.',

        'pincode.digits' =>
            'Pincode must contain exactly 6 digits.',

        'profile_image.image' =>
            'Please upload a valid image file.',

        'profile_image.mimes' =>
            'Profile image must be JPG, JPEG, PNG or WEBP.',

        'profile_image.max' =>
            'Profile image must not exceed 2 MB.',

        'aadhar_card_no.regex' =>
            'Aadhaar must contain exactly 12 digits and cannot start with 0 or 1.',

        'aadhar_card_no.unique' =>
            'This Aadhaar number is already assigned to another student.',

        'appar_id.regex' =>
            'APAAR ID must contain exactly 12 digits.',

        'appar_id.unique' =>
            'This APAAR ID is already assigned to another student.',

        'pen_no.regex' =>
            'PEN Number must contain 11 to 14 digits.',

        'pen_no.unique' =>
            'This PEN Number is already assigned to another student.',

        'saral_id.regex' =>
            'Saral ID can contain letters, numbers, slash, hyphen and underscore only.',

        'saral_id.unique' =>
            'This Saral ID is already assigned to another student.',

        'register_no.required' =>
            'Register Number is required.',

        'register_no.max' =>
            'Register Number cannot exceed 50 characters.',

        'register_no.unique' =>
            'This Register Number is already assigned to another student.',

        'book_no.required' =>
            'Book Number is required.',

        'book_no.max' =>
            'Book Number cannot exceed 50 characters.',

        'book_no.unique' =>
            'This Book Number is already assigned to another student.',
    ]);
}


        private function generateStudentId(?string $class = null, ?string $section = null, ?string $academicYear = null): string
    {
        $school = SchoolSetting::query()->lockForUpdate()->first();

        $format = $school?->student_id_format ?: 'STU-{YEAR}-{NUMBER}';
        $startNumber = (int) ($school?->student_id_start ?: 1);
        $numberLength = (int) ($school?->student_id_length ?: 4);

        $year = now()->year;
        $shortYear = substr((string) $year, -2);

        $class = trim((string) ($class ?? ''));
        $section = trim((string) ($section ?? ''));

        $students = Student::withTrashed()->whereNotNull('student_id')->pluck('student_id');

        $nextNumber = $startNumber;

        foreach ($students as $existingId) {
            $pattern = preg_quote($format, '/');
            $pattern = str_replace('\{NUMBER\}', '([0-9]+)', $pattern);
            $pattern = str_replace('\{YEAR\}', preg_quote((string) $year, '/'), $pattern);
            $pattern = str_replace('\{YY\}', preg_quote($shortYear, '/'), $pattern);
            $pattern = str_replace('\{CLASS\}', '.*?', $pattern);
            $pattern = str_replace('\{SECTION\}', '.*?', $pattern);

            if (preg_match('/^' . $pattern . '$/', $existingId, $matches)) {
                if (isset($matches[1]) && is_numeric($matches[1])) {
                    $existingNumber = (int) $matches[1];
                    if ($existingNumber >= $nextNumber) {
                        $nextNumber = $existingNumber + 1;
                    }
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

            $exists = Student::withTrashed()->where('student_id', $studentId)->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $studentId;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE ROLL NUMBER
    |--------------------------------------------------------------------------
    */
    private function generateRollNumber(string $class, ?string $section = null, ?string $academicYear = null): string
    {
        $class = trim($class);
        $section = $section !== null ? trim($section) : null;
        $academicYear = $academicYear !== null ? trim($academicYear) : null;

        $query = Student::query()->where('class', $class);

        if ($section !== null && $section !== '') {
            $query->where('section', $section);
        }

        if ($academicYear !== null && $academicYear !== '') {
            $query->where('academic_year', $academicYear);
        }

        $lastRoll = $query
            ->whereNotNull('roll_number')
            ->where('roll_number', '!=', '')
            ->whereRaw("roll_number REGEXP '^[0-9]+$'")
            ->orderByRaw("CAST(roll_number AS UNSIGNED) DESC")
            ->value('roll_number');

        $nextNumber = 1;

        if ($lastRoll !== null && is_numeric($lastRoll)) {
            $nextNumber = ((int) $lastRoll) + 1;
        }

        do {
            $rollNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            $existsQuery = Student::query()
                ->where('class', $class)
                ->where('roll_number', $rollNumber);

            if ($section !== null && $section !== '') {
                $existsQuery->where('section', $section);
            }

            if ($academicYear !== null && $academicYear !== '') {
                $existsQuery->where('academic_year', $academicYear);
            }

            $exists = $existsQuery->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $rollNumber;
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS-WISE STUDENTS
    |--------------------------------------------------------------------------
    */
    public function classWise(Request $request)
    {
        $query = Student::query();

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }
        if ($request->filled('class')) {
            $query->where('class', $request->input('class'));
        }
        if ($request->filled('section')) {
            $query->where('section', $request->input('section'));
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query
            ->orderByRaw("CASE WHEN roll_number REGEXP '^[0-9]+$' THEN CAST(roll_number AS UNSIGNED) ELSE 999999 END ASC")
            ->orderBy('last_name')
            ->paginate(25)
            ->withQueryString();

        $countQuery = clone $query;

        $totalStudents = (clone $countQuery)->count();
        $totalBoys = (clone $countQuery)->where('gender', 'Male')->count();
        $totalGirls = (clone $countQuery)->where('gender', 'Female')->count();
        $totalActive = (clone $countQuery)->where('status', 'active')->count();

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $sections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view('admin.students.class-wise', compact(
            'students',
            'academicYears',
            'classes',
            'sections',
            'totalStudents',
            'totalBoys',
            'totalGirls',
            'totalActive'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS-WISE PRINT
    |--------------------------------------------------------------------------
    */
    public function classWisePrint(Request $request)
    {
        $query = Student::query();

        $selectedClass = $request->input('class');
        $selectedSection = $request->input('section');
        $selectedGender = $request->input('gender');
        $selectedStatus = $request->input('status');
        $academicYear = $request->input('academic_year');

        if (!empty($academicYear)) {
            $query->where('academic_year', $academicYear);
        }
        if (!empty($selectedClass)) {
            $query->where('class', $selectedClass);
        }
        if (!empty($selectedSection)) {
            $query->where('section', $selectedSection);
        }
        if (!empty($selectedGender)) {
            $query->where('gender', $selectedGender);
        }
        if (!empty($selectedStatus)) {
            $query->where('status', $selectedStatus);
        }

        $students = $query
            ->orderBy('class')
            ->orderBy('section')
            ->orderByRaw("CASE WHEN roll_number REGEXP '^[0-9]+$' THEN CAST(roll_number AS UNSIGNED) ELSE 999999 END ASC")
            ->orderBy('last_name')
            ->get();

        $totalStudents = $students->count();
        $totalBoys = $students->where('gender', 'Male')->count();
        $totalGirls = $students->where('gender', 'Female')->count();
        $totalActive = $students->where('status', 'active')->count();

        $academicYear = $academicYear ?? $students->first()?->academic_year ?? (date('Y') . '-' . (date('Y') + 1));

        return view('admin.students.class-wise-print', compact(
            'students',
            'academicYear',
            'selectedClass',
            'selectedSection',
            'selectedGender',
            'selectedStatus',
            'totalStudents',
            'totalBoys',
            'totalGirls',
            'totalActive'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */
    public function export(Request $request)
    {
        $filters = $request->only(['search', 'class', 'section', 'academic_year', 'status']);

        return Excel::download(
            new AllStudentsExport($filters),
            'all_students_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BULK TEMPLATE
    |--------------------------------------------------------------------------
    */
    public function bulkCreate()
    {
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view('admin.students.bulk-create', compact('classes'));
    }

    public function downloadBulkTemplate()
    {
        $headings = [
            'student_id', 'first_name', 'middle_name', 'last_name', 'marathi_name',
            'gender', 'date_of_birth', 'birth_place', 'aadhar_card_no',
            'academic_year', 'class', 'admission_class', 'section', 'admission_date',
            'roll_number', 'register_no', 'book_no', 'appar_id', 'pen_no', 'saral_id',
            'phone', 'email', 'father_name', 'father_phone', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_occupation', 'guardian_name',
            'guardian_relation', 'guardian_phone', 'medium', 'mother_tongue',
            'nationality', 'religion', 'caste', 'sub_caste', 'previous_school_name',
            'previous_school_address', 'previous_school_class', 'previous_school_medium',
            'previous_school_board', 'previous_school_result', 'previous_school_remarks',
            'address', 'country', 'state', 'district', 'taluka', 'city_village',
            'pincode', 'status',
        ];

        $export = new class($headings) implements
            \Maatwebsite\Excel\Concerns\FromArray,
            \Maatwebsite\Excel\Concerns\WithHeadings
        {
            public function __construct(private array $headings) {}
            public function array(): array { return []; }
            public function headings(): array { return $this->headings; }
        };

        return Excel::download($export, 'bulk_student_template.xlsx');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:students,id'],
        ]);

        $schoolId = auth()->user()?->school_id;
        if (!$schoolId) {
            abort(403, 'Your account is not assigned to a school.');
        }

        $count = Student::where('school_id', $schoolId)
            ->whereIn('id', $validated['ids'])
            ->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', $count . ' student(s) deleted successfully.');
    }

public function import(Request $request)
{
    $request->validate([
        'file' => [
            'required',
            'file',
            'mimes:xlsx,xls,csv',
            'max:10240',
        ],
    ]);

    $imported = 0;
    $errors = [];

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $request->file('file')->getRealPath()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray(null, true, true, false);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        if (count($rows) < 2) {
            return back()->with(
                'error',
                'The spreadsheet is empty or contains only headings.'
            );
        }

        // Normalize headings, e.g. "First Name" becomes "first_name".
        $headers = array_map(function ($heading) {
            $heading = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                trim((string) $heading)
            );

            return strtolower(
                preg_replace('/[^a-zA-Z0-9]+/', '_', $heading)
            );
        }, $rows[0]);

        if (
            count(array_filter($headers)) !== count($headers) ||
            count($headers) !== count(array_unique($headers))
        ) {
            return back()->with(
                'error',
                'The spreadsheet contains blank or duplicate column headings.'
            );
        }

        foreach (array_slice($rows, 1) as $index => $row) {
            $rowNumber = $index + 2;

            // Ignore completely blank rows.
            if (!array_filter($row, fn ($value) =>
                $value !== null && trim((string) $value) !== ''
            )) {
                continue;
            }

            $record = [];

            foreach ($headers as $column => $heading) {
                $record[$heading] = isset($row[$column])
                    ? (is_string($row[$column])
                        ? trim($row[$column])
                        : $row[$column])
                    : null;
            }

            // Support Excel date serial numbers and common date formats.
            foreach (['date_of_birth', 'admission_date'] as $dateField) {
                $value = $record[$dateField] ?? null;

                if ($value === null || $value === '') {
                    $record[$dateField] = null;
                    continue;
                }

                try {
                    if (is_numeric($value)) {
                        $record[$dateField] =
                            \PhpOffice\PhpSpreadsheet\Shared\Date::
                                excelToDateTimeObject((float) $value)
                                ->format('Y-m-d');
                    } else {
                        $parsed = null;
                        $dateValue = trim((string) $value);

                        foreach (
                            ['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'm/d/Y']
                            as $format
                        ) {
                            $candidate = \DateTime::createFromFormat(
                                '!' . $format,
                                $dateValue
                            );

                            $dateErrors = \DateTime::getLastErrors();

                            if (
                                $candidate &&
                                (
                                    $dateErrors === false ||
                                    (
                                        $dateErrors['warning_count'] === 0 &&
                                        $dateErrors['error_count'] === 0
                                    )
                                ) &&
                                $candidate->format($format) === $dateValue
                            ) {
                                $parsed = $candidate->format('Y-m-d');
                                break;
                            }
                        }

                        $record[$dateField] = $parsed ?? $dateValue;
                    }
                } catch (\Throwable $e) {
                    // Leave invalid values for validation to report below.
                    $record[$dateField] = (string) $value;
                }
            }

            $data = [
                'first_name' => $record['first_name'] ?? null,
                'middle_name' => $record['middle_name'] ?? null,
                'last_name' => $record['last_name'] ?? null,
                'gender' => $record['gender'] ?? null,
                'date_of_birth' => $record['date_of_birth'] ?? null,
                'academic_year' => $record['academic_year'] ?? null,
                'admission_class' => $record['admission_class']
                    ?? $record['class']
                    ?? null,
                'class' => $record['admission_class']
                    ?? $record['class']
                    ?? null,
                'section' => $record['section'] ?? null,
                'admission_date' => $record['admission_date'] ?? null,
                'phone' => $record['phone'] ?? null,
                'email' => $record['email'] ?? null,
                'father_name' => $record['father_name'] ?? null,
                'father_phone' => $record['father_phone'] ?? null,
                'mother_name' => $record['mother_name'] ?? null,
                'mother_phone' => $record['mother_phone'] ?? null,
                'address' => $record['address'] ?? null,
                'city_village' => $record['city_village'] ?? null,
                'district' => $record['district'] ?? null,
                'taluka' => $record['taluka'] ?? null,
                'pincode' => $record['pincode'] ?? null,
                'roll_number' => $record['roll_number'] ?? null,
                'register_no' => $record['register_no'] ?? null,
                'book_no' => $record['book_no'] ?? null,
                'appar_id' => $record['appar_id'] ?? null,
                'pen_no' => $record['pen_no'] ?? null,
                'saral_id' => $record['saral_id'] ?? null,
                'marathi_name' => $record['marathi_name'] ?? null,
                'birth_place' => $record['birth_place'] ?? null,
                'aadhar_card_no' => $record['aadhar_card_no'] ?? null,
                'medium' => $record['medium'] ?? null,
                'mother_tongue' => $record['mother_tongue'] ?? null,
                'nationality' => $record['nationality'] ?? null,
                'religion' => $record['religion'] ?? null,
                'caste' => $record['caste'] ?? null,
                'sub_caste' => $record['sub_caste'] ?? null,
                'father_occupation' => $record['father_occupation'] ?? null,
                'mother_occupation' => $record['mother_occupation'] ?? null,
                'guardian_name' => $record['guardian_name'] ?? null,
                'guardian_relation' => $record['guardian_relation'] ?? null,
                'guardian_phone' => $record['guardian_phone'] ?? null,
                'previous_school_name' => $record['previous_school_name'] ?? null,
                'previous_school_address' => $record['previous_school_address'] ?? null,
                'previous_school_class' => $record['previous_school_class'] ?? null,
                'previous_school_medium' => $record['previous_school_medium'] ?? null,
                'previous_school_board' => $record['previous_school_board'] ?? null,
                'previous_school_result' => $record['previous_school_result'] ?? null,
                'previous_school_remarks' => $record['previous_school_remarks'] ?? null,
                'country' => ($record['country'] ?? null) ?: 'India',
'state'   => ($record['state'] ?? null) ?: 'Maharashtra',
'status'  => ($record['status'] ?? null) ?: 'active',
            ];

            // Blank spreadsheet cells should become null values.
            foreach ($data as $key => $value) {
                if (is_string($value) && trim($value) === '') {
                    $data[$key] = null;
                }
            }

            $validator = \Illuminate\Support\Facades\Validator::make(
                $data,
                [
                    'first_name' => 'required|string|max:100',
                    'middle_name' => 'nullable|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'gender' => 'nullable|string|max:20',
                    'date_of_birth' => 'nullable|date',
                    'admission_date' => 'nullable|date',
                    'academic_year' => 'nullable|string|max:20',
                    'admission_class' => 'nullable|string|max:20',
                    'section' => 'nullable|string|max:20',
                    'phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
                    'email' => 'nullable|email|max:255',
                    'father_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
                    'mother_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
                    'guardian_phone' => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
                    // APAAR ID: exactly 12 digits.
'appar_id' => [
    'nullable',
    'string',
    'regex:/^[0-9]{12}$/',
    Rule::unique('students', 'appar_id'),
],

// PEN Number: 11 to 14 digits.
'pen_no' => [
    'nullable',
    'string',
    'regex:/^[0-9]{11,14}$/',
    Rule::unique('students', 'pen_no'),
],

// Saral ID: state-specific format.
'saral_id' => [
    'nullable',
    'string',
    'max:50',
    'regex:/^[a-zA-Z0-9\/_-]+$/',
    Rule::unique('students', 'saral_id'),
],

// Institutional Register Number.
'register_no' => [
    'required',
    'string',
    'max:50',
    Rule::unique('students', 'register_no'),
],

// Institutional Book Number.
'book_no' => [
    'required',
    'string',
    'max:50',
    Rule::unique('students', 'book_no'),
],

'aadhar_card_no' => [
    'nullable',
    'string',
    'regex:/^[2-9][0-9]{11}$/',
    Rule::unique('students', 'aadhar_card_no'),
    function ($attribute, $value, $fail) {
        if (
            filled($value) &&
            !$this->isValidAadhaarVerhoeff((string) $value)
        ) {
            $fail('Please enter a valid Aadhaar number.');
        }
    },
],
                ]
            );

            if ($validator->fails()) {
                $errors[] = 'Row ' . $rowNumber . ': ' .
                    implode(' ', $validator->errors()->all());

                continue;
            }

            try {
                DB::transaction(function () use ($data) {
                    $data['school_id'] = auth()->user()?->school_id;
                    // Use the same automatic ID generator as normal registration.
                    $data['student_id'] = $this->generateStudentId(
                        $data['admission_class'],
                        $data['section'],
                        $data['academic_year']
                    );

                    Student::create($data);
                });

                $imported++;
            } catch (\Throwable $e) {
                report($e);

                $errors[] = 'Row ' . $rowNumber .
                    ': The student could not be saved. Check the data and any duplicate IDs.';
            }
        }
    } catch (\Throwable $e) {
        report($e);

        return back()->with(
            'error',
            'Unable to read the spreadsheet. Please check that it is a valid Excel or CSV file.'
        );
    }

    if (count($errors) > 0) {
        return redirect()
            ->route('admin.students.index')
            ->with(
                $imported > 0 ? 'warning' : 'error',
                "Imported {$imported} student(s). " .
                    count($errors) . ' row(s) need attention.'
            )
            ->with('student_import_errors', array_slice($errors, 0, 50));
    }

    return redirect()
        ->route('admin.students.index')
        ->with('success', "Successfully imported {$imported} student(s).");
}

/**
 * Validate an Aadhaar number using the Verhoeff checksum.
 */
private function isValidAadhaarVerhoeff(string $number): bool
{
    $number = trim($number);

    if (!preg_match('/^[2-9][0-9]{11}$/', $number)) {
        return false;
    }

    $multiplication = [
        [0,1,2,3,4,5,6,7,8,9],
        [1,2,3,4,0,6,7,8,9,5],
        [2,3,4,0,1,7,8,9,5,6],
        [3,4,0,1,2,8,9,5,6,7],
        [4,0,1,2,3,9,5,6,7,8],
        [5,9,8,7,6,0,4,3,2,1],
        [6,5,9,8,7,1,0,4,3,2],
        [7,6,5,9,8,2,1,0,4,3],
        [8,7,6,5,9,3,2,1,0,4],
        [9,8,7,6,5,4,3,2,1,0],
    ];

    $permutation = [
        [0,1,2,3,4,5,6,7,8,9],
        [1,5,7,6,2,8,3,0,9,4],
        [5,8,0,3,7,9,6,1,4,2],
        [8,9,1,6,0,4,3,5,2,7],
        [9,4,5,3,1,2,6,8,7,0],
        [4,2,8,6,5,7,3,9,0,1],
        [2,7,9,3,8,0,6,4,1,5],
        [7,0,4,6,9,1,3,2,5,8],
    ];

    $checksum = 0;
    $digits = array_reverse(str_split($number));

    foreach ($digits as $index => $digit) {
        $checksum = $multiplication[
            $checksum
        ][
            $permutation[$index % 8][(int) $digit]
        ];
    }

    return $checksum === 0;
}
}