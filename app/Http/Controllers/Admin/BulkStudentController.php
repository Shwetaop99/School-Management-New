<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSetting;
use App\Models\Class\SchoolClass;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class BulkStudentController extends Controller
{
    /**
     * Display the bulk student entry form.
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('admin.students.bulk-create', compact('classes'));
    }

    /**
     * Save multiple new students or update existing students.
     */
    public function store(Request $request)
    {

    Log::info('BulkStudentController store method reached');

        $validated = $request->validate([
            'students' => ['required', 'array', 'min:1', 'max:100'],

            'students.*.student_id' => ['nullable', 'string', 'max:100'],
            'students.*.first_name' => ['required', 'string', 'max:100'],
            'students.*.middle_name' => ['nullable', 'string', 'max:100'],
            'students.*.last_name' => ['required', 'string', 'max:100'],
            'students.*.marathi_name' => ['nullable', 'string', 'max:255'],
            'students.*.gender' => ['nullable', 'string', 'max:20'],
            'students.*.date_of_birth' => ['nullable', 'date'],
            'students.*.birth_place' => ['nullable', 'string', 'max:255'],
            'students.*.aadhar_card_no' => ['nullable', 'digits:12'],
           'students.*.phone' => [
    'nullable',
    'string',
    'regex:/^(?:\+91[\s-]?)?[6-9][0-9]{9}$/',
],
            'students.*.email' => ['nullable', 'email', 'max:255'],
            'students.*.profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'students.*.academic_year' => ['nullable', 'string', 'max:20'],
            'students.*.class' => ['nullable', 'string', 'max:20'],
            'students.*.current_class' => ['nullable', 'string', 'max:20'],
            'students.*.admission_class' => ['nullable', 'string', 'max:20'],
            'students.*.section' => ['nullable', 'string', 'max:20'],
            'students.*.admission_date' => ['nullable', 'date'],
            'students.*.roll_number' => ['nullable', 'string', 'max:50'],
            'students.*.register_no' => ['nullable', 'string', 'max:100'],
            'students.*.book_no' => ['nullable', 'string', 'max:100'],
            'students.*.appar_id' => ['nullable', 'string', 'max:100'],
            'students.*.pen_no' => ['nullable', 'string', 'max:100'],
            'students.*.saral_id' => ['nullable', 'string', 'max:100'],
            'students.*.medium' => ['nullable', 'string', 'max:50'],

            'students.*.mother_tongue' => ['nullable', 'string', 'max:100'],
            'students.*.nationality' => ['nullable', 'string', 'max:100'],
            'students.*.religion' => ['nullable', 'string', 'max:100'],
            'students.*.caste' => ['nullable', 'string', 'max:100'],
            'students.*.sub_caste' => ['nullable', 'string', 'max:100'],
            'students.*.status' => ['nullable', 'in:active,inactive'],

            'students.*.father_name' => ['nullable', 'string', 'max:150'],
            'students.*.father_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/',
            ],
            'students.*.father_occupation' => ['nullable', 'string', 'max:150'],
            'students.*.mother_name' => ['nullable', 'string', 'max:150'],
            'students.*.mother_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/',
            ],
            'students.*.mother_occupation' => ['nullable', 'string', 'max:150'],
            'students.*.guardian_name' => ['nullable', 'string', 'max:150'],
            'students.*.guardian_relation' => ['nullable', 'string', 'max:100'],
            'students.*.guardian_phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/',
            ],

            'students.*.previous_school_name' => [
                'nullable', 'string', 'max:255',
            ],
            'students.*.previous_school_address' => [
                'nullable', 'string', 'max:500',
            ],
            'students.*.previous_school_class' => [
                'nullable', 'string', 'max:100',
            ],
            'students.*.previous_school_medium' => [
                'nullable', 'string', 'max:100',
            ],
            'students.*.previous_school_board' => [
                'nullable', 'string', 'max:100',
            ],
            'students.*.board' => ['nullable', 'string', 'max:100'],
            'students.*.previous_school_result' => [
                'nullable', 'string', 'max:100',
            ],
            'students.*.previous_school_remarks' => [
                'nullable', 'string', 'max:1000',
            ],

            'students.*.address' => ['nullable', 'string', 'max:1000'],
            'students.*.country' => ['nullable', 'string', 'max:100'],
            'students.*.state' => ['nullable', 'string', 'max:100'],
            'students.*.district' => ['nullable', 'string', 'max:100'],
            'students.*.taluka' => ['nullable', 'string', 'max:100'],
            'students.*.city_village' => ['nullable', 'string', 'max:150'],
            'students.*.pincode' => ['nullable', 'digits:6'],
        ]);


        $created = 0;
        $updated = 0;

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                &$created,
                &$updated
            ) {
                foreach ($validated['students'] as $index => $data) {
                    $photo = $request->file(
                        "students.{$index}.profile_image"
                    );

                    Log::info('Bulk student processing started', [
    'index' => $index,
    'first_name' => $data['first_name'] ?? null,
    'student_id' => $data['student_id'] ?? null,
]);

                    unset($data['profile_image']);

                    // Use the supplied Student ID to find an existing student.
                    $student = null;
                    $studentId = trim((string) ($data['student_id'] ?? ''));
                    $saralId = trim((string) ($data['saral_id'] ?? ''));

                    if ($studentId !== '') {
                        $student = Student::where(
                            'student_id',
                            $studentId
                        )->first();

                        // Do not silently create a new record if the ID
                        // belongs to a soft-deleted student.
                        if (!$student && Student::withTrashed()
                            ->where('student_id', $studentId)
                            ->exists()) {
                            throw ValidationException::withMessages([
                                "students.{$index}.student_id" =>
                                    "Student ID {$studentId} belongs to a deleted student.",
                            ]);
                        }
                    }

                    // If no Student ID was supplied, match by SARAL ID.
                    if (!$student && $studentId === '' && $saralId !== '') {
                        $student = Student::where('saral_id', $saralId)
                            ->first();
                    }

                    // Do not overwrite a different existing record's ID.
                    if (
                        $student &&
                        $studentId !== '' &&
                        $student->student_id !== $studentId
                    ) {
                        throw ValidationException::withMessages([
                            "students.{$index}.student_id" =>
                                'The supplied Student ID does not match the existing record.',
                        ]);
                    }

                    $data['country'] = $data['country'] ?? 'India';
                    $data['state'] = $data['state'] ?? 'Maharashtra';
                    $data['status'] = $data['status'] ?? 'active';

                    $data['admission_class'] =
                        $data['admission_class'] ?? null;

                    // Keep class consistent with the admission class,
                    // matching the existing single-student registration.
                    if (!empty($data['admission_class'])) {
                        $data['class'] = $data['admission_class'];
                    } elseif (!empty($data['class'])) {
                        $data['admission_class'] = $data['class'];
                    }

                    $data['previous_school_board'] =
                        $data['previous_school_board']
                        ?? $data['board']
                        ?? null;

                    unset($data['board'], $data['current_class']);

                    if ($student) {
                        // Preserve the existing ID when the field is blank.
                        $data['student_id'] = $student->student_id;

                        if ($photo) {
                            $data['profile_image'] = $this->uploadToCloudinary(
                                $photo,
                                'student_' . Str::uuid()
                            );
                        }

                        $student->fill($data);
                        $student->save();

                        $updated++;
                    } else {
                        // Generate an ID only for a new student without one.
                        if ($studentId === '') {
                            $data['student_id'] = $this->generateStudentId();
                        } else {
                            $data['student_id'] = $studentId;

                            if (Student::withTrashed()
                                ->where('student_id', $studentId)
                                ->exists()) {
                                throw ValidationException::withMessages([
                                    "students.{$index}.student_id" =>
                                        'This Student ID is already in use.',
                                ]);
                            }
                        }

                        if ($photo) {
                            $data['profile_image'] = $this->uploadToCloudinary(
                                $photo,
                                'student_' . Str::uuid()
                            );
                        } else {
                            $data['profile_image'] = null;
                        }

                        Student::create($data);
                        Log::info('Bulk student created successfully', [
    'index' => $index,
    'student_id' => $data['student_id'],
]);
                        $created++;
                    }
                }
            });

            return redirect()
                ->route('admin.students.bulk-create')
                ->with(
                    'success',
                    "Bulk save completed. New students: {$created}; updated students: {$updated}."
                );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Bulk save failed. No database changes were committed. Check the Laravel log for details.'
                );
        }
    }

    /**
     * Generate a unique Student ID using the school's configured format.
     */
    private function generateStudentId(): string
    {
        $school = SchoolSetting::query()->lockForUpdate()->first();

        $format = $school?->student_id_format ?: 'STU-{YEAR}-{NUMBER}';
        $startNumber = (int) ($school?->student_id_start ?: 1);
        $length = (int) ($school?->student_id_length ?: 4);

        $year = now()->year;
        $shortYear = substr((string) $year, -2);

        preg_match('/\{NUMBER(?::(\d+))?\}/', $format, $matches);
        $numberLength = isset($matches[1])
            ? (int) $matches[1]
            : $length;

        $number = $startNumber;

        do {
            $id = str_replace(
                ['{YEAR}', '{YY}', '{NUMBER}', '{NUMBER:' . $numberLength . '}'],
                [
                    (string) $year,
                    $shortYear,
                    str_pad((string) $number, $numberLength, '0', STR_PAD_LEFT),
                    str_pad((string) $number, $numberLength, '0', STR_PAD_LEFT),
                ],
                $format
            );

            $number++;
        } while (Student::withTrashed()
            ->where('student_id', $id)
            ->exists());

        return $id;
    }

    /**
     * Upload a student's photo to the existing Cloudinary folder.
     */
    private function uploadToCloudinary($file, string $publicId): string
    {
        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('The uploaded student photo is invalid.');
        }

        $cloudinaryUrl =
            config('cloudinary.cloud_url')
            ?? config('filesystems.disks.cloudinary.url')
            ?? env('CLOUDINARY_URL');

        if (!$cloudinaryUrl) {
            throw new \RuntimeException(
                'Cloudinary is not configured. Check your Cloudinary environment settings.'
            );
        }

        $cloudinary = new Cloudinary($cloudinaryUrl);

        $result = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => 'school-management-db/students',
                'public_id' => $publicId,
                'resource_type' => 'image',
            ]
        );

        $response = is_object($result) && method_exists($result, 'toArray')
            ? $result->toArray()
            : (array) $result;

        $secureUrl = $response['secure_url']
            ?? $response['url']
            ?? (is_object($result) ? ($result->secure_url ?? null) : null);

        if (
            !is_string($secureUrl) ||
            !filter_var($secureUrl, FILTER_VALIDATE_URL)
        ) {
            Log::error('Bulk student Cloudinary upload returned no valid URL.');
            throw new \RuntimeException(
                'Cloudinary did not return a valid student photo URL.'
            );
        }

        return $secureUrl;
    }
}