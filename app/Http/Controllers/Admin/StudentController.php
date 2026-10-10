<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;
use Illuminate\Validation\ValidationException;
use Throwable;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Student::query();

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
            $query->where(
                'academic_year',
                $request->input('academic_year')
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $defaultClasses = [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        $dbClasses = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->pluck('class')
            ->toArray();

        $classes = array_values(
            array_unique(
                array_merge($defaultClasses, $dbClasses)
            )
        );

        $defaultSections = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];

        $dbSections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section')
            ->toArray();

        $sections = array_values(
            array_unique(
                array_merge($defaultSections, $dbSections)
            )
        );

        $totalStudents = Student::count();

        $totalActiveStudents = Student::where(
            'status',
            'active'
        )->count();

        $totalInactiveStudents = Student::where(
            'status',
            '!=',
            'active'
        )->count();

        $totalMaleStudents = Student::where(
            'gender',
            'Male'
        )->count();

        $totalFemaleStudents = Student::where(
            'gender',
            'Female'
        )->count();

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

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $classes = [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        $sections = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];

        return view(
            'admin.students.create',
            compact(
                'classes',
                'sections'
            )
        );
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

            $validated['country'] = $request->input(
                'country',
                'India'
            );

            $validated['state'] = $request->input(
                'state',
                'Maharashtra'
            );

            $validated['status'] = $request->input(
                'status',
                'active'
            );

            /*
            |--------------------------------------------------------------------------
            | CLASS
            |--------------------------------------------------------------------------
            */

            $validated['class'] = $request->input(
                'class',
                $request->input('current_class')
            );

            /*
            |--------------------------------------------------------------------------
            | MEDIUM
            |--------------------------------------------------------------------------
            */

            $validated['medium'] = $request->input(
                'medium'
            );

            /*
            |--------------------------------------------------------------------------
            | PREVIOUS SCHOOL BOARD
            |--------------------------------------------------------------------------
            */

            $validated['previous_school_board'] =
                $request->input(
                    'previous_school_board',
                    $request->input('board')
                );

            /*
            |--------------------------------------------------------------------------
            | DISTRICT - TEXT
            |--------------------------------------------------------------------------
            */

            $validated['district'] =
                $request->input(
                    'district',
                    $request->input('district_id')
                );

            /*
            |--------------------------------------------------------------------------
            | TALUKA - TEXT
            |--------------------------------------------------------------------------
            */

            $validated['taluka'] =
                $request->input(
                    'taluka',
                    $request->input('taluka_id')
                );

            /*
            |--------------------------------------------------------------------------
            | STUDENT ID
            |--------------------------------------------------------------------------
            */

            $validated['student_id'] =
                $this->generateStudentId();

            /*
            |--------------------------------------------------------------------------
            | ROLL NUMBER
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled('class') ||
                $request->filled('current_class')
            ) {

                $studentClass = $request->input(
                    'class',
                    $request->input('current_class')
                );

                $validated['roll_number'] =
                    $this->generateRollNumber(
                        $studentClass,
                        $request->input('section'),
                        $request->input('academic_year')
                    );

            } else {

                $validated['roll_number'] = null;
            }

            /*
            |--------------------------------------------------------------------------
            | REGISTER NUMBER
            |--------------------------------------------------------------------------
            */

            $validated['register_no'] =
                $this->generateRegisterNumber();

            /*
            |--------------------------------------------------------------------------
            | BOOK NUMBER
            |--------------------------------------------------------------------------
            */

            $validated['book_no'] =
                $this->generateBookNumber();

            /*
            |--------------------------------------------------------------------------
            | APAAR INTERNAL REFERENCE
            |--------------------------------------------------------------------------
            */

            $validated['appar_id'] =
                $this->generateAparId();

            /*
            |--------------------------------------------------------------------------
            | PEN INTERNAL REFERENCE
            |--------------------------------------------------------------------------
            */

            $validated['pen_no'] =
                $this->generatePenNumber();

            /*
            |--------------------------------------------------------------------------
            | PROFILE IMAGE
            |--------------------------------------------------------------------------
            */

            $validated['profile_image'] = null;

            if ($request->hasFile('profile_image')) {

                $validated['profile_image'] =
                    $this->uploadToCloudinary(
                        $request->file('profile_image'),
                        'student_' . uniqid()
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE STUDENT
            |--------------------------------------------------------------------------
            */

            $student = Student::create($validated);

            DB::commit();

            return redirect()
                ->route(
                    'admin.students.show',
                    $student
                )
                ->with(
                    'success',
                    'Student registered successfully. Student ID: ' .
                    $student->student_id
                );

        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Student registration failed: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Student $student)
    {
        return view(
            'admin.students.show',
            compact('student')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Student $student)
    {
        $classes = [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        $sections = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];

        return view(
            'admin.students.edit',
            compact(
                'student',
                'classes',
                'sections'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([

            'first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'last_name' => [
                'required',
                'string',
                'max:100'
            ],

            'marathi_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'gender' => [
                'required',
                'in:Male,Female,Other'
            ],

            'date_of_birth' => [
                'required',
                'date'
            ],

            'birth_place' => [
                'nullable',
                'string',
                'max:255'
            ],

            'aadhar_card_no' => [
                'nullable',
                'digits:12'
            ],

            'phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20'
            ],

            'class' => [
                'required',
                'string',
                'max:50'
            ],

            'section' => [
                'nullable',
                'string',
                'max:10'
            ],

            'admission_class' => [
                'nullable',
                'string',
                'max:50'
            ],

            'admission_date' => [
                'nullable',
                'date'
            ],

            'medium' => [
                'nullable',
                'string',
                'max:50'
            ],

            'mother_tongue' => [
                'nullable',
                'string',
                'max:100'
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100'
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100'
            ],

            'caste' => [
                'nullable',
                'string',
                'max:100'
            ],

            'sub_caste' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'nullable',
                'in:active,inactive'
            ],

            'father_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'father_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'father_occupation' => [
                'nullable',
                'string',
                'max:255'
            ],

            'mother_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'mother_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'mother_occupation' => [
                'nullable',
                'string',
                'max:255'
            ],

            'guardian_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'guardian_relation' => [
                'nullable',
                'string',
                'max:100'
            ],

            'guardian_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'previous_school_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'previous_school_address' => [
                'nullable',
                'string'
            ],

            'previous_school_class' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_medium' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_board' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_result' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_remarks' => [
                'nullable',
                'string'
            ],

            'address' => [
                'nullable',
                'string'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'district' => [
                'nullable',
                'string',
                'max:100'
            ],

            'taluka' => [
                'nullable',
                'string',
                'max:100'
            ],

            'city_village' => [
                'nullable',
                'string',
                'max:150'
            ],

            'pincode' => [
                'nullable',
                'digits:6'
            ],

        ], [

            'aadhar_card_no.digits' =>
                'Aadhar number must contain exactly 12 digits.',

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
                'Profile image must not exceed 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | KEEP GENERATED VALUES
        |--------------------------------------------------------------------------
        */

        $validated['student_id'] =
            $student->student_id;

        $validated['roll_number'] =
            $student->roll_number;

        $validated['register_no'] =
            $student->register_no;

        $validated['book_no'] =
            $student->book_no;

        $validated['appar_id'] =
            $student->appar_id;

        $validated['pen_no'] =
            $student->pen_no;

        $validated['saral_id'] =
            $student->saral_id;

        /*
        |--------------------------------------------------------------------------
        | DEFAULT VALUES
        |--------------------------------------------------------------------------
        */

        $validated['country'] =
            $request->input(
                'country',
                $student->country ?? 'India'
            );

        $validated['state'] =
            $request->input(
                'state',
                $student->state ?? 'Maharashtra'
            );

        /*
        |--------------------------------------------------------------------------
        | DISTRICT AS TEXT
        |--------------------------------------------------------------------------
        */

        $validated['district'] =
            $request->input(
                'district',
                $student->district
            );

        /*
        |--------------------------------------------------------------------------
        | TALUKA AS TEXT
        |--------------------------------------------------------------------------
        */

        $validated['taluka'] =
            $request->input(
                'taluka',
                $student->taluka
            );

        $validated['status'] =
            $request->input(
                'status',
                $student->status ?? 'active'
            );

        /*
        |--------------------------------------------------------------------------
        | PROFILE IMAGE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | PHP creates a temporary file such as:
        |
        | C:\xampp82\tmp\php57F3.tmp
        |
        | We NEVER save that path.
        |
        | The temporary file is uploaded to Cloudinary.
        | Only the Cloudinary HTTPS URL is saved.
        |
        */

        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');

            if (!$file->isValid()) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' =>
                            'The uploaded profile image is invalid.'
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | USE THE SAME CLOUDINARY HELPER AS STORE()
            |--------------------------------------------------------------------------
            */

            $validated['profile_image'] =
                $this->uploadToCloudinary(
                    $file,
                    'student_' .
                    $student->student_id .
                    '_' .
                    time()
                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | NO NEW IMAGE
            |--------------------------------------------------------------------------
            |
            | Keep existing Cloudinary URL.
            |
            */

            $validated['profile_image'] =
                $student->profile_image;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $student->update($validated);

        return redirect()
            ->route(
                'admin.students.show',
                $student->id
            )
            ->with(
                'success',
                'Student updated successfully.'
            );
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
            ->with(
                'success',
                'Student deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX - NEXT ROLL NUMBER
    |--------------------------------------------------------------------------
    */

    public function nextRollNumber(Request $request)
{
    $validated = $request->validate([
        'class' => [
            'required',
            'string',
            'max:50',
        ],

        'section' => [
            'nullable',
            'string',
            'max:20',
        ],

        'academic_year' => [
            'nullable',
            'string',
            'max:20',
        ],
    ]);

    $class = trim($validated['class']);
    $section = isset($validated['section'])
        ? trim($validated['section'])
        : null;

    $academicYear = isset($validated['academic_year'])
        ? trim($validated['academic_year'])
        : null;

    $rollNumber = $this->generateRollNumber(
        $class,
        $section,
        $academicYear
    );

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

            'student_id' => [
                'required',
                'string',
                'max:100'
            ],
        ]);

        $student = Student::query()
            ->where(
                'student_id',
                $request->input('student_id')
            )
            ->where(
                'status',
                'active'
            )
            ->first();

        if (!$student) {

            return response()->json([
                'success' => false,
                'message' => 'Student not found.',
            ], 404);
        }

        $studentName = trim(
            implode(
                ' ',
                array_filter([
                    $student->first_name,
                    $student->middle_name,
                    $student->last_name,
                ])
            )
        );

        return response()->json([

            'success' => true,

            'student_id' =>
                $student->student_id,

            'student_name' =>
                $studentName,

            'class' =>
                $student->class,

            'section' =>
                $student->section,

            'date_of_birth' =>
                $student->date_of_birth,

            'parent' =>
                $student->father_name
                ??
                $student->mother_name
                ??
                $student->guardian_name,

            'father_name' =>
                $student->father_name,

            'mother_name' =>
                $student->mother_name,

            'guardian_name' =>
                $student->guardian_name,

            'educational_year' =>
                $student->academic_year,

            'academic_year' =>
                $student->academic_year,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CLOUDINARY UPLOAD
    |--------------------------------------------------------------------------
    */

    private function uploadToCloudinary(
        $file,
        string $publicId
    ): string {

        try {

            /*
            |--------------------------------------------------------------------------
            | CHECK FILE
            |--------------------------------------------------------------------------
            */

            if (!$file) {

                throw new \RuntimeException(
                    'No profile image was received.'
                );
            }

            if (!$file->isValid()) {

                throw new \RuntimeException(
                    'Uploaded profile image is invalid.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK TEMPORARY FILE
            |--------------------------------------------------------------------------
            */

            $realPath = $file->getRealPath();

            if (
                empty($realPath) ||
                !file_exists($realPath)
            ) {

                throw new \RuntimeException(
                    'Temporary uploaded image file could not be found.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY CONFIGURATION
            |--------------------------------------------------------------------------
            */

            $cloudUrl =
                config('cloudinary.cloud_url')
                ??
                config('filesystems.disks.cloudinary.url')
                ??
                env('CLOUDINARY_URL');

            if (empty($cloudUrl)) {

                throw new \RuntimeException(
                    'Cloudinary configuration is missing. Please check CLOUDINARY_URL in your .env file.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE CLOUDINARY CLIENT
            |--------------------------------------------------------------------------
            */

            $cloudinary = new Cloudinary();

            /*
            |--------------------------------------------------------------------------
            | UPLOAD
            |--------------------------------------------------------------------------
            */

            $result = $cloudinary
                ->uploadApi()
                ->upload(
                    $realPath,
                    [
                        'folder' =>
                            'school-management-db/students',

                        'public_id' =>
                            $publicId,

                        'resource_type' =>
                            'image',
                    ]
                );

            /*
            |--------------------------------------------------------------------------
            | DEBUG CLOUDINARY RESPONSE TYPE
            |--------------------------------------------------------------------------
            */

            Log::info(
                'Cloudinary student image upload response',
                [
                    'type' =>
                        is_object($result)
                            ? get_class($result)
                            : gettype($result),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | GET SECURE URL
            |--------------------------------------------------------------------------
            */

            $secureUrl = null;

            /*
            |--------------------------------------------------------------------------
            | CASE 1: ARRAY RESPONSE
            |--------------------------------------------------------------------------
            */

            if (is_array($result)) {

                $secureUrl =
                    $result['secure_url']
                    ??
                    $result['url']
                    ??
                    null;
            }

            /*
            |--------------------------------------------------------------------------
            | CASE 2: OBJECT RESPONSE
            |--------------------------------------------------------------------------
            */

            if (
                empty($secureUrl) &&
                is_object($result)
            ) {

                /*
                |----------------------------------------------------------------------
                | getSecurePath()
                |----------------------------------------------------------------------
                */

                if (
                    method_exists(
                        $result,
                        'getSecurePath'
                    )
                ) {

                    $secureUrl =
                        $result->getSecurePath();
                }

                /*
                |----------------------------------------------------------------------
                | secure_url property
                |----------------------------------------------------------------------
                */

                if (
                    empty($secureUrl) &&
                    isset($result->secure_url)
                ) {

                    $secureUrl =
                        $result->secure_url;
                }

                /*
                |----------------------------------------------------------------------
                | url property
                |----------------------------------------------------------------------
                */

                if (
                    empty($secureUrl) &&
                    isset($result->url)
                ) {

                    $secureUrl =
                        $result->url;
                }

                /*
                |----------------------------------------------------------------------
                | toArray()
                |----------------------------------------------------------------------
                */

                if (
                    empty($secureUrl) &&
                    method_exists(
                        $result,
                        'toArray'
                    )
                ) {

                    $response =
                        $result->toArray();

                    if (is_array($response)) {

                        $secureUrl =
                            $response['secure_url']
                            ??
                            $response['url']
                            ??
                            null;
                    }
                }

                /*
                |----------------------------------------------------------------------
                | getArrayCopy()
                |----------------------------------------------------------------------
                */

                if (
                    empty($secureUrl) &&
                    method_exists(
                        $result,
                        'getArrayCopy'
                    )
                ) {

                    $response =
                        $result->getArrayCopy();

                    if (is_array($response)) {

                        $secureUrl =
                            $response['secure_url']
                            ??
                            $response['url']
                            ??
                            null;
                    }
                }

                /*
                |----------------------------------------------------------------------
                | JSON RESPONSE
                |----------------------------------------------------------------------
                */

                if (
                    empty($secureUrl) &&
                    method_exists(
                        $result,
                        '__toString'
                    )
                ) {

                    $json =
                        (string) $result;

                    $decoded =
                        json_decode(
                            $json,
                            true
                        );

                    if (is_array($decoded)) {

                        $secureUrl =
                            $decoded['secure_url']
                            ??
                            $decoded['url']
                            ??
                            null;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | FINAL URL CHECK
            |--------------------------------------------------------------------------
            */

            if (
                empty($secureUrl) ||
                !filter_var(
                    $secureUrl,
                    FILTER_VALIDATE_URL
                )
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOG RESPONSE FOR DEBUGGING
                |--------------------------------------------------------------------------
                */

                Log::error(
                    'Cloudinary upload completed but URL was not found.',
                    [
                        'response_type' =>
                            is_object($result)
                                ? get_class($result)
                                : gettype($result),

                        'response' =>
                            is_object($result)
                                ? print_r(
                                    $result,
                                    true
                                )
                                : $result,
                    ]
                );

                throw new \RuntimeException(
                    'Cloudinary upload completed, but the image URL could not be read from the response.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            Log::info(
                'Student profile image uploaded successfully.',
                [
                    'url' => $secureUrl,
                ]
            );

            return $secureUrl;

        } catch (Throwable $e) {

            Log::error(
                'Cloudinary student profile image upload failed.',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $file?->getClientOriginalName(),

                    'mime' =>
                        $file?->getMimeType(),

                    'size' =>
                        $file?->getSize(),
                ]
            );

            throw ValidationException::withMessages([
                'profile_image' =>
                    'Profile image upload failed: ' .
                    $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
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

            'first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'last_name' => [
                'required',
                'string',
                'max:100'
            ],

            'marathi_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20'
            ],

            'date_of_birth' => [
                'nullable',
                'date'
            ],

            'birth_place' => [
                'nullable',
                'string',
                'max:255'
            ],

            'aadhar_card_no' => [
                'nullable',
                'digits:12'
            ],

            'phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            /*
            |--------------------------------------------------------------------------
            | ACADEMIC
            |--------------------------------------------------------------------------
            */

            'academic_year' => [
                'nullable',
                'string',
                'max:20'
            ],

            'class' => [
                'nullable',
                'string',
                'max:20'
            ],

            'current_class' => [
                'nullable',
                'string',
                'max:20'
            ],

            'section' => [
                'nullable',
                'string',
                'max:20'
            ],

            /*
            |--------------------------------------------------------------------------
            | GENERATED FIELDS
            |--------------------------------------------------------------------------
            */

            'roll_number' => [
                'nullable',
                'string',
                'max:50'
            ],

            'admission_class' => [
                'nullable',
                'string',
                'max:20'
            ],

            'admission_date' => [
                'nullable',
                'date'
            ],

            'register_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'book_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'appar_id' => [
                'nullable',
                'string',
                'max:100'
            ],

            'pen_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            /*
            |--------------------------------------------------------------------------
            | OTHER INFORMATION
            |--------------------------------------------------------------------------
            */

            'medium' => [
                'nullable',
                'string',
                'max:50'
            ],

            'mother_tongue' => [
                'nullable',
                'string',
                'max:100'
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100'
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100'
            ],

            'caste' => [
                'nullable',
                'string',
                'max:100'
            ],

            'sub_caste' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive',
                ])
            ],

            /*
            |--------------------------------------------------------------------------
            | FATHER
            |--------------------------------------------------------------------------
            */

            'father_name' => [
                'nullable',
                'string',
                'max:150'
            ],

            'father_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'father_occupation' => [
                'nullable',
                'string',
                'max:150'
            ],

            /*
            |--------------------------------------------------------------------------
            | MOTHER
            |--------------------------------------------------------------------------
            */

            'mother_name' => [
                'nullable',
                'string',
                'max:150'
            ],

            'mother_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'mother_occupation' => [
                'nullable',
                'string',
                'max:150'
            ],

            /*
            |--------------------------------------------------------------------------
            | GUARDIAN
            |--------------------------------------------------------------------------
            */

            'guardian_name' => [
                'nullable',
                'string',
                'max:150'
            ],

            'guardian_relation' => [
                'nullable',
                'string',
                'max:100'
            ],

            'guardian_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            /*
            |--------------------------------------------------------------------------
            | PREVIOUS SCHOOL
            |--------------------------------------------------------------------------
            */

            'previous_school_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'previous_school_address' => [
                'nullable',
                'string',
                'max:500'
            ],

            'previous_school_class' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_medium' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_board' => [
                'nullable',
                'string',
                'max:100'
            ],

            'board' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_result' => [
                'nullable',
                'string',
                'max:100'
            ],

            'previous_school_remarks' => [
                'nullable',
                'string',
                'max:1000'
            ],

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            'address' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            /*
            |--------------------------------------------------------------------------
            | DISTRICT - TEXT
            |--------------------------------------------------------------------------
            */

            'district' => [
                'nullable',
                'string',
                'max:100'
            ],

            /*
            |--------------------------------------------------------------------------
            | TALUKA - TEXT
            |--------------------------------------------------------------------------
            */

            'taluka' => [
                'nullable',
                'string',
                'max:100'
            ],

            'city_village' => [
                'nullable',
                'string',
                'max:150'
            ],

            'pincode' => [
                'nullable',
                'digits:6'
            ],

        ], [

            'aadhar_card_no.digits' =>
                'Aadhar number must contain exactly 12 digits.',

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
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE STUDENT ID
    |--------------------------------------------------------------------------
    */

    private function generateStudentId(): string
    {
        $lastStudentId = Student::withTrashed()
            ->where(
                'student_id',
                'like',
                'STU%'
            )
            ->orderByRaw(
                "CAST(SUBSTRING(student_id, 4) AS UNSIGNED) DESC"
            )
            ->value('student_id');

        $nextNumber = 1;

        if (
            $lastStudentId &&
            preg_match(
                '/^STU(\d+)$/',
                $lastStudentId,
                $matches
            )
        ) {

            $nextNumber =
                ((int) $matches[1]) + 1;
        }

        do {

            $studentId =
                'STU' .
                str_pad(
                    $nextNumber,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Student::withTrashed()
                ->where(
                    'student_id',
                    $studentId
                )
                ->exists();

            if ($exists) {
                $nextNumber++;
            }

        } while ($exists);

        return $studentId;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE REGISTER NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateRegisterNumber(): string
    {
        $last = Student::withTrashed()
            ->where(
                'register_no',
                'like',
                'REG%'
            )
            ->orderByRaw(
                "CAST(SUBSTRING(register_no, 4) AS UNSIGNED) DESC"
            )
            ->value('register_no');

        $next = 1;

        if (
            $last &&
            preg_match(
                '/^REG(\d+)$/',
                $last,
                $matches
            )
        ) {

            $next =
                ((int) $matches[1]) + 1;
        }

        do {

            $number =
                'REG' .
                str_pad(
                    $next,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Student::withTrashed()
                ->where(
                    'register_no',
                    $number
                )
                ->exists();

            if ($exists) {
                $next++;
            }

        } while ($exists);

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE BOOK NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateBookNumber(): string
    {
        $last = Student::withTrashed()
            ->where(
                'book_no',
                'like',
                'BOOK%'
            )
            ->orderByRaw(
                "CAST(SUBSTRING(book_no, 5) AS UNSIGNED) DESC"
            )
            ->value('book_no');

        $next = 1;

        if (
            $last &&
            preg_match(
                '/^BOOK(\d+)$/',
                $last,
                $matches
            )
        ) {

            $next =
                ((int) $matches[1]) + 1;
        }

        do {

            $number =
                'BOOK' .
                str_pad(
                    $next,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Student::withTrashed()
                ->where(
                    'book_no',
                    $number
                )
                ->exists();

            if ($exists) {
                $next++;
            }

        } while ($exists);

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE APAAR INTERNAL REFERENCE
    |--------------------------------------------------------------------------
    */

    private function generateAparId(): string
    {
        $last = Student::withTrashed()
            ->where(
                'appar_id',
                'like',
                'APAAR%'
            )
            ->orderByRaw(
                "CAST(SUBSTRING(appar_id, 6) AS UNSIGNED) DESC"
            )
            ->value('appar_id');

        $next = 1;

        if (
            $last &&
            preg_match(
                '/^APAAR(\d+)$/',
                $last,
                $matches
            )
        ) {

            $next =
                ((int) $matches[1]) + 1;
        }

        do {

            $number =
                'APAAR' .
                str_pad(
                    $next,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Student::withTrashed()
                ->where(
                    'appar_id',
                    $number
                )
                ->exists();

            if ($exists) {
                $next++;
            }

        } while ($exists);

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE PEN INTERNAL REFERENCE
    |--------------------------------------------------------------------------
    */

    private function generatePenNumber(): string
    {
        $last = Student::withTrashed()
            ->where(
                'pen_no',
                'like',
                'PEN%'
            )
            ->orderByRaw(
                "CAST(SUBSTRING(pen_no, 4) AS UNSIGNED) DESC"
            )
            ->value('pen_no');

        $next = 1;

        if (
            $last &&
            preg_match(
                '/^PEN(\d+)$/',
                $last,
                $matches
            )
        ) {

            $next =
                ((int) $matches[1]) + 1;
        }

        do {

            $number =
                'PEN' .
                str_pad(
                    $next,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Student::withTrashed()
                ->where(
                    'pen_no',
                    $number
                )
                ->exists();

            if ($exists) {
                $next++;
            }

        } while ($exists);

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE ROLL NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateRollNumber(
    string $class,
    ?string $section = null,
    ?string $academicYear = null
): string {

    $class = trim($class);
    $section = $section !== null
        ? trim($section)
        : null;

    $academicYear = $academicYear !== null
        ? trim($academicYear)
        : null;

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | Roll number is generated ONLY from:
    |
    | Current Class
    | Current Section
    | Academic Year
    |
    | previous_school_class is NOT used.
    |--------------------------------------------------------------------------
    */

    $query = Student::query()
        ->where('class', $class);

    if ($section !== null && $section !== '') {
        $query->where('section', $section);
    }

    if ($academicYear !== null && $academicYear !== '') {
        $query->where('academic_year', $academicYear);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND HIGHEST EXISTING ROLL NUMBER
    |--------------------------------------------------------------------------
    */

    $lastRoll = $query
        ->whereNotNull('roll_number')
        ->where('roll_number', '!=', '')
        ->whereRaw("roll_number REGEXP '^[0-9]+$'")
        ->orderByRaw(
            "CAST(roll_number AS UNSIGNED) DESC"
        )
        ->value('roll_number');

    $nextNumber = 1;

    if ($lastRoll !== null && is_numeric($lastRoll)) {
        $nextNumber = ((int) $lastRoll) + 1;
    }

    /*
    |--------------------------------------------------------------------------
    | MAKE SURE ROLL NUMBER DOES NOT ALREADY EXIST
    |--------------------------------------------------------------------------
    */

    do {

        $rollNumber = str_pad(
            $nextNumber,
            2,
            '0',
            STR_PAD_LEFT
        );

        $existsQuery = Student::query()
            ->where('class', $class)
            ->where('roll_number', $rollNumber);

        if ($section !== null && $section !== '') {
            $existsQuery->where(
                'section',
                $section
            );
        }

        if ($academicYear !== null && $academicYear !== '') {
            $existsQuery->where(
                'academic_year',
                $academicYear
            );
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

            $query->where(
                'academic_year',
                $request->input('academic_year')
            );
        }

        if ($request->filled('class')) {

            $query->where(
                'class',
                $request->input('class')
            );
        }

        if ($request->filled('section')) {

            $query->where(
                'section',
                $request->input('section')
            );
        }

        if ($request->filled('gender')) {

            $query->where(
                'gender',
                $request->input('gender')
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        $students = $query
            ->orderByRaw(
                "CASE
                    WHEN roll_number REGEXP '^[0-9]+$'
                    THEN CAST(roll_number AS UNSIGNED)
                    ELSE 999999
                 END ASC"
            )
            ->orderBy('last_name')
            ->paginate(25)
            ->withQueryString();

        $countQuery = clone $query;

        $totalStudents =
            (clone $countQuery)->count();

        $totalBoys =
            (clone $countQuery)
                ->where(
                    'gender',
                    'Male'
                )
                ->count();

        $totalGirls =
            (clone $countQuery)
                ->where(
                    'gender',
                    'Female'
                )
                ->count();

        $totalActive =
            (clone $countQuery)
                ->where(
                    'status',
                    'active'
                )
                ->count();

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where(
                'academic_year',
                '!=',
                ''
            )
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = Student::query()
            ->whereNotNull('class')
            ->where(
                'class',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $sections = Student::query()
            ->whereNotNull('section')
            ->where(
                'section',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view(
            'admin.students.class-wise',
            compact(
                'students',
                'academicYears',
                'classes',
                'sections',
                'totalStudents',
                'totalBoys',
                'totalGirls',
                'totalActive'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS-WISE PRINT
    |--------------------------------------------------------------------------
    */

    public function classWisePrint(
        Request $request
    ) {

        $query = Student::query();

        $selectedClass =
            $request->input('class');

        $selectedSection =
            $request->input('section');

        $selectedGender =
            $request->input('gender');

        $selectedStatus =
            $request->input('status');

        $academicYear =
            $request->input('academic_year');

        if (!empty($academicYear)) {

            $query->where(
                'academic_year',
                $academicYear
            );
        }

        if (!empty($selectedClass)) {

            $query->where(
                'class',
                $selectedClass
            );
        }

        if (!empty($selectedSection)) {

            $query->where(
                'section',
                $selectedSection
            );
        }

        if (!empty($selectedGender)) {

            $query->where(
                'gender',
                $selectedGender
            );
        }

        if (!empty($selectedStatus)) {

            $query->where(
                'status',
                $selectedStatus
            );
        }

        $students = $query
            ->orderBy('class')
            ->orderBy('section')
            ->orderByRaw(
                "CASE
                    WHEN roll_number REGEXP '^[0-9]+$'
                    THEN CAST(roll_number AS UNSIGNED)
                    ELSE 999999
                 END ASC"
            )
            ->orderBy('last_name')
            ->get();

        $totalStudents =
            $students->count();

        $totalBoys =
            $students
                ->where(
                    'gender',
                    'Male'
                )
                ->count();

        $totalGirls =
            $students
                ->where(
                    'gender',
                    'Female'
                )
                ->count();

        $totalActive =
            $students
                ->where(
                    'status',
                    'active'
                )
                ->count();

        $academicYear =
            $academicYear
            ??
            $students->first()?->academic_year
            ??
            (
                date('Y') .
                '-' .
                (date('Y') + 1)
            );

        return view(
            'admin.students.class-wise-print',
            compact(
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
            )
        );
    }
}