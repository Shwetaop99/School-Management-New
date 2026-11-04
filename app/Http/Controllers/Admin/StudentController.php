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

<<<<<<< HEAD
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
=======
        $totalStudents = Student::count();
        $totalActiveStudents = Student::where('status', 'active')->count();
        $totalInactiveStudents = Student::where('status', '!=', 'active')->count();
        $totalMaleStudents = Student::where('gender', 'Male')->count();
        $totalFemaleStudents = Student::where('gender', 'Female')->count();

        $students = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.students.index', compact(
            'students',
            'totalStudents',
            'totalActiveStudents',
            'totalInactiveStudents',
            'totalMaleStudents',
            'totalFemaleStudents',
            'academicYears',
            'classes',
            'sections'
        ));
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
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

        DB::beginTransaction();

        try {
            $validated['country'] = $request->input('country', 'India');
            $validated['state'] = $request->input('state', 'Maharashtra');
            $validated['status'] = $request->input('status', 'active');

<<<<<<< HEAD
    $schoolId = auth()->user()->school_id;

if (!$schoolId) {
    abort(403, 'Your account is not assigned to a school.');
}

$validated['school_id'] = $schoolId;

    DB::beginTransaction();
=======
            $validated['admission_class'] = $request->input('admission_class');
            $validated['class'] = $request->input('admission_class');
            $validated['section'] = $request->input('section');
            $validated['medium'] = $request->input('medium');
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c

            $validated['previous_school_board'] = $request->input('previous_school_board', $request->input('board'));
            $validated['district'] = $request->input('district', $request->input('district_id'));
            $validated['taluka'] = $request->input('taluka', $request->input('taluka_id'));

            $validated['student_id'] = $this->generateStudentId(
                $validated['admission_class'] ?? null,
                $request->input('section'),
                $request->input('academic_year')
            );

            $validated['roll_number'] = $request->input('roll_number');
            $validated['register_no'] = $request->input('register_no');
            $validated['book_no'] = $request->input('book_no');
            $validated['appar_id'] = $request->input('appar_id');
            $validated['pen_no'] = $request->input('pen_no');
            $validated['saral_id'] = $request->input('saral_id');
            $validated['birth_place'] = $request->input('birth_place');

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

            return back()
                ->withInput()
                ->with('error', 'Student registration failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
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

        return view('admin.students.edit', compact('student', 'classes', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Student $student)
    {
        $validated = $this->validateStudent($request, $student);

        // Keep generated values
        $validated['student_id'] = $student->student_id;
        $validated['roll_number'] = $student->roll_number;
        $validated['register_no'] = $student->register_no;
        $validated['book_no'] = $student->book_no;
        $validated['appar_id'] = $student->appar_id;
        $validated['pen_no'] = $student->pen_no;
        $validated['saral_id'] = $student->saral_id;

        $validated['country'] = $request->input('country', $student->country ?? 'India');
        $validated['state'] = $request->input('state', $student->state ?? 'Maharashtra');
        $validated['district'] = $request->input('district', $student->district);
        $validated['taluka'] = $request->input('taluka', $student->taluka);
        $validated['status'] = $request->input('status', $student->status ?? 'active');

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            if (!$file->isValid()) {
                return back()->withInput()->withErrors([
                    'profile_image' => 'The uploaded profile image is invalid.'
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

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
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
    private function validateStudent(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            // Basic Information
            'first_name'     => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.]+$/'],
            'middle_name'    => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.]+$/'],
            'last_name'      => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.]+$/'],
            'marathi_name'   => ['nullable', 'string', 'max:255'],
            'gender'         => ['required', 'in:Male,Female,Other'],
            'date_of_birth'  => ['required', 'date', 'before:today'],
            'birth_place'    => ['nullable', 'string', 'max:255'],
            'aadhar_card_no' => ['nullable', 'digits:12'],
            'phone'          => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'email'          => ['nullable', 'email', 'max:255'],

            // Profile Image
            'profile_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Academic
            'academic_year'  => ['required', 'string', 'max:20'],
            'admission_class'=> ['required', 'string', 'max:50'],
            'class'          => ['nullable', 'string', 'max:50'],
            'section'        => ['nullable', 'string', 'max:20'],
            'medium'         => ['nullable', 'string', 'max:50'],
            'admission_date' => ['nullable', 'date'],

            // Manual Numbers
            'roll_number'    => ['nullable', 'string', 'max:50'],
            'register_no'    => ['nullable', 'string', 'max:100'],
            'book_no'        => ['nullable', 'string', 'max:100'],
            'appar_id'       => ['nullable', 'string', 'max:100'],
            'pen_no'         => ['nullable', 'string', 'max:100'],
            'saral_id'       => ['nullable', 'string', 'max:100'],

            // Other Info
            'mother_tongue'  => ['nullable', 'string', 'max:100'],
            'nationality'    => ['nullable', 'string', 'max:100'],
            'religion'       => ['nullable', 'string', 'max:100'],
            'caste'          => ['nullable', 'string', 'max:100'],
            'sub_caste'      => ['nullable', 'string', 'max:100'],
            'status'         => ['nullable', 'in:active,inactive'],

            // Father
            'father_name'       => ['nullable', 'string', 'max:150'],
            'father_phone'      => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'father_occupation' => ['nullable', 'string', 'max:150'],

            // Mother
            'mother_name'       => ['nullable', 'string', 'max:150'],
            'mother_phone'      => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],
            'mother_occupation' => ['nullable', 'string', 'max:150'],

            // Guardian
            'guardian_name'     => ['nullable', 'string', 'max:150'],
            'guardian_relation' => ['nullable', 'string', 'max:100'],
            'guardian_phone'    => ['nullable', 'regex:/^[6-9][0-9]{9}$/'],

            // Previous School
            'previous_school_name'    => ['nullable', 'string', 'max:255'],
            'previous_school_address' => ['nullable', 'string', 'max:500'],
            'previous_school_class'   => ['nullable', 'string', 'max:100'],
            'previous_school_medium'  => ['nullable', 'string', 'max:100'],
            'previous_school_board'   => ['nullable', 'string', 'max:100'],
            'previous_school_result'  => ['nullable', 'string', 'max:100'],
            'previous_school_remarks' => ['nullable', 'string', 'max:1000'],

            // Address
            'address'      => ['nullable', 'string', 'max:1000'],
            'country'      => ['nullable', 'string', 'max:100'],
            'state'        => ['nullable', 'string', 'max:100'],
            'district'     => ['nullable', 'string', 'max:100'],
            'taluka'       => ['nullable', 'string', 'max:100'],
            'city_village' => ['nullable', 'string', 'max:150'],
            'pincode'      => ['nullable', 'digits:6'],
        ], [
            'aadhar_card_no.digits' => 'Aadhar number must be exactly 12 digits.',
            'phone.regex'           => 'Please enter a valid 10-digit mobile number.',
            'father_phone.regex'    => 'Please enter a valid 10-digit father mobile number.',
            'mother_phone.regex'    => 'Please enter a valid 10-digit mother mobile number.',
            'guardian_phone.regex'  => 'Please enter a valid 10-digit guardian mobile number.',
            'pincode.digits'        => 'Pincode must be exactly 6 digits.',
            'profile_image.max'     => 'Profile image must not exceed 2 MB.',
            'first_name.regex'      => 'First name can only contain letters and spaces.',
            'last_name.regex'       => 'Last name can only contain letters and spaces.',
            'middle_name.regex'     => 'Middle name can only contain letters and spaces.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE STUDENT ID
    |--------------------------------------------------------------------------
    */
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
    public function downloadBulkTemplate()
    {
        $headings = [
            'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
            'academic_year', 'class', 'section', 'admission_date', 'phone', 'email',
            'father_name', 'father_phone', 'mother_name', 'mother_phone',
            'address', 'city_village', 'district', 'taluka', 'pincode',
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

    public function bulkCreate()
    {
        $classes = SchoolClass::query()->orderBy('class_name')->get();
        return view('admin.students.bulk-create', compact('classes'));
    }
    /**
 * Bulk delete multiple records.
 */
public function bulkDestroy(Request $request)
{
    $request->validate([
        'ids'   => 'required|array|min:1',
        'ids.*' => 'integer|exists:teachers,id',   // ← change table name
    ]);

    $count = Teacher::whereIn('id', $request->ids)->delete();  // ← change Model

    return redirect()
        ->route('admin.teachers.index')                          // ← change route
        ->with('success', $count . ' teacher(s) deleted successfully.');
}
}