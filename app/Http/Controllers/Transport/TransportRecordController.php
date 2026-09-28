<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Student;
use App\Models\Transport\TransportRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportRecordController extends Controller
{
    /**
     * Display transport records.
     */
    public function index()
    {
        $transportRecords = TransportRecord::with('student')
            ->latest()
            ->paginate(10);

        /*
         * Blade view also uses $records.
         * Keep it as the same paginator object.
         */
        $records = $transportRecords;

        /*
         * Total number of transport records.
         */
        $totalRecords = $transportRecords->total();

        /*
         * Total active records.
         */
        $activeRecords = TransportRecord::where(
            'transport_status',
            'active'
        )->count();

        /*
         * Total records having an assigned student.
         */
        $assignedStudents = TransportRecord::whereNotNull(
            'student_id'
        )->count();

        /*
         * Total inactive records.
         */
        $inactiveRecords = TransportRecord::where(
            'transport_status',
            'inactive'
        )->count();

        return view(
            'admin.transport.records.index',
            compact(
                'transportRecords',
                'records',
                'totalRecords',
                'activeRecords',
                'assignedStudents',
                'inactiveRecords'
            )
        );
    }

    /**
     * Show create transport record form.
     */
    public function create()
    {
        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view(
            'admin.transport.records.create',
            compact('classes')
        );
    }

    /**
     * Get students belonging to the selected class.
     */
    public function studentsByClass(Request $request)
    {
        /*
         * ---------------------------------------------------------
         * VALIDATE CLASS ID
         * ---------------------------------------------------------
         */
        $validated = $request->validate([
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
        ]);

        /*
         * ---------------------------------------------------------
         * GET SELECTED SCHOOL CLASS
         * ---------------------------------------------------------
         */
        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        /*
         * ---------------------------------------------------------
         * NORMALIZE CLASS NAME
         * ---------------------------------------------------------
         *
         * Supports:
         *
         *     Class 1
         *     class 1
         *     CLASS 1
         *     1
         */
        $className = trim(
            (string) $schoolClass->class_name
        );

        /*
         * Class 1 -> 1
         * class 1 -> 1
         * CLASS 1 -> 1
         */
        $normalizedClassName = preg_replace(
            '/^class\s*/i',
            '',
            $className
        );

        $normalizedClassName = trim(
            (string) $normalizedClassName
        );

        /*
         * Create possible class values.
         */
        $classValues = array_values(
            array_unique(
                array_filter([
                    strtolower($className),
                    strtolower($normalizedClassName),
                ])
            )
        );

        /*
         * ---------------------------------------------------------
         * NORMALIZE SECTION
         * ---------------------------------------------------------
         */
        $section = trim(
            (string) $schoolClass->section
        );

        /*
         * ---------------------------------------------------------
         * BUILD BASE STUDENT QUERY
         * ---------------------------------------------------------
         */
        $buildStudentQuery = function () use (
            $classValues,
            $section
        ) {
            return Student::query()
                ->where(function ($query) use ($classValues) {
                    foreach (
                        $classValues as $index => $classValue
                    ) {
                        if ($index === 0) {
                            $query->whereRaw(
                                'LOWER(TRIM(`class`)) = ?',
                                [$classValue]
                            );
                        } else {
                            $query->orWhereRaw(
                                'LOWER(TRIM(`class`)) = ?',
                                [$classValue]
                            );
                        }
                    }
                })
                ->where(function ($query) use ($section) {
                    /*
                     * If selected class has no section,
                     * accept NULL or empty student section.
                     */
                    if ($section === '') {
                        $query
                            ->whereNull('section')
                            ->orWhere('section', '');

                        return;
                    }

                    $query->whereRaw(
                        'LOWER(TRIM(`section`)) = ?',
                        [strtolower($section)]
                    );
                });
        };

        /*
         * ---------------------------------------------------------
         * GET STUDENTS
         * ---------------------------------------------------------
         *
         * Academic year is intentionally NOT required.
         */
        $students = $buildStudentQuery()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'class',
                'section',
                'father_name',
                'father_phone',
                'address',
            ])
            ->map(function ($student) {
                /*
                 * Build complete student name.
                 */
                $fullName = trim(
                    collect([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name,
                    ])
                        ->filter(function ($value) {
                            return $value !== null
                                && trim((string) $value) !== '';
                        })
                        ->implode(' ')
                );

                return [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'roll_number' => $student->roll_number,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'full_name' => $fullName,
                    'class' => $student->class,
                    'section' => $student->section,
                    'father_name' => $student->father_name,
                    'father_phone' => $student->father_phone,
                    'address' => $student->address,
                ];
            })
            ->values();

        /*
         * ---------------------------------------------------------
         * RETURN JSON RESPONSE
         * ---------------------------------------------------------
         */
        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count(),
        ]);
    }

    /**
     * Store a new transport record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'class_filter' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],

            'route' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'drop_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'transport_status' => [
                'required',
                'in:active,inactive',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'transport_type' => [
                'nullable',
                'in:school_bus,van,private,other',
            ],

            'pickup_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'drop_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'transport_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fee_frequency' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending,partially_paid',
            ],
        ]);

        /*
         * class_filter is only used to find the student.
         * It is not stored in transport_records.
         */
        unset($validated['class_filter']);

        TransportRecord::create($validated);

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record added successfully.'
            );
    }

    /**
     * Show a transport record.
     */
    public function show(
        TransportRecord $transportRecord
    ) {
        $transportRecord->load('student');

        return view(
            'admin.transport.records.show',
            compact('transportRecord')
        );
    }

    /**
     * Download transport record as PDF.
     *
     * The PDF uses school information from the
     * school_settings table.
     */
    public function downloadPdf(
        TransportRecord $transportRecord
    ) {
        /*
         * Load the student relationship used by the PDF.
         */
        $transportRecord->load('student');

        /*
         * Get school settings.
         */
        $school = DB::table('school_settings')->first();

        /*
         * Get school name from School Settings.
         */
        $schoolName = $school?->school_name
            ?: 'Gurukul Vidyalaya';

        /*
         * IMPORTANT:
         * The school_settings table contains the column
         * "logo", NOT "logo_url".
         */
        $schoolLogo = $school?->logo
            ?: asset('images/gurukullogo.png');

        /*
         * Generate PDF.
         */
        $pdf = Pdf::loadView(
            'admin.transport.records.pdf',
            [
                'transportRecord' => $transportRecord,
                'school' => $school,
                'schoolName' => $schoolName,
                'schoolLogo' => $schoolLogo,
            ]
        );

        /*
         * A4 portrait PDF.
         */
        $pdf->setPaper('a4', 'portrait');

        /*
         * Download filename.
         */
        $studentName = 'Transport-Record';

        if ($transportRecord->student) {
            $studentName = trim(
                collect([
                    $transportRecord->student->first_name ?? null,
                    $transportRecord->student->middle_name ?? null,
                    $transportRecord->student->last_name ?? null,
                ])
                    ->filter(function ($value) {
                        return $value !== null
                            && trim((string) $value) !== '';
                    })
                    ->implode('-')
            );

            if ($studentName === '') {
                $studentName = 'Transport-Record';
            }
        }

        /*
         * Remove characters that are unsafe in filenames.
         */
        $studentName = preg_replace(
            '/[^A-Za-z0-9\-_]/',
            '',
            $studentName
        );

        return $pdf->download(
            $studentName . '-Transport-Record.pdf'
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        TransportRecord $transportRecord
    ) {
        /*
         * Get active school classes.
         */
        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        /*
         * Load the student currently assigned
         * to this transport record.
         */
        $transportRecord->load('student');

        /*
         * FIX:
         * The edit Blade uses $students.
         *
         * Load all students required by the edit form
         * and keep the same fields used by the
         * studentsByClass() response.
         */
        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'class',
                'section',
                'father_name',
                'father_phone',
                'address',
            ]);

        return view(
            'admin.transport.records.edit',
            compact(
                'transportRecord',
                'classes',
                'students'
            )
        );
    }

    /**
     * Update a transport record.
     */
    public function update(
        Request $request,
        TransportRecord $transportRecord
    ) {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'class_filter' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],

            'route' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'drop_point' => [
                'nullable',
                'string',
                'max:255',
            ],

            'transport_status' => [
                'required',
                'in:active,inactive',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'transport_type' => [
                'nullable',
                'in:school_bus,van,private,other',
            ],

            'pickup_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'drop_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'transport_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fee_frequency' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending,partially_paid',
            ],
        ]);

        /*
         * class_filter is not a transport_records field.
         */
        unset($validated['class_filter']);

        $transportRecord->update($validated);

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record updated successfully.'
            );
    }

    /**
     * Delete a transport record.
     */
    public function destroy(
        TransportRecord $transportRecord
    ) {
        $transportRecord->delete();

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record deleted successfully.'
            );
    }
}
