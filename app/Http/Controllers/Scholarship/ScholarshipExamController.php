<?php

namespace App\Http\Controllers\Scholarship;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Scholarship\ScholarshipExam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScholarshipExamController extends Controller
{
    /**
     * Display all scholarship exam records.
     */
    public function index(Request $request)
    {
        $query = ScholarshipExam::with('classes')
            ->withCount('passedStudents')
            ->latest('exam_date');

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('exam_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhere('exam_type', 'like', "%{$search}%")
                    ->orWhere('conducted_by', 'like', "%{$search}%");
            });
        }

        // Academic year filter
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $exams = $query
            ->paginate(10)
            ->withQueryString();

        // Available academic years
        $academicYears = ScholarshipExam::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        // Dashboard statistics
        $statistics = [
            'total_exams' => ScholarshipExam::count(),

            'total_applied' => ScholarshipExam::sum(
                'total_applied'
            ),

            'total_appeared' => ScholarshipExam::sum(
                'total_appeared'
            ),

            'total_passed' => ScholarshipExam::sum(
                'total_passed'
            ),

            'total_scholarships' => DB::table(
                'scholarship_exam_passed_students'
            )
                ->where('scholarship_received', true)
                ->count(),

            'total_scholarship_amount' => DB::table(
                'scholarship_exam_passed_students'
            )
                ->where('scholarship_received', true)
                ->sum('scholarship_amount'),
        ];

        return view(
            'admin.scholarship.exams.index',
            compact(
                'exams',
                'academicYears',
                'statistics'
            )
        );
    }

    /**
     * Show create exam record page.
     */
    public function create()
    {
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view(
            'admin.scholarship.exams.create',
            compact('classes')
        );
    }

    /**
     * Store a new scholarship exam record.
     *
     * Student records are submitted as:
     *
     * student_records[student_id] = applied|appeared|passed
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_name' => [
                'required',
                'string',
                'max:255',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'exam_date' => [
                'required',
                'date',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'class_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'class_ids.*' => [
                'integer',
                'exists:school_classes,id',
            ],

            'student_records' => [
                'nullable',
                'array',
            ],

            'student_records.*' => [
                'in:not_applied,applied,appeared,passed',
            ],
        ]);

        /*
         * Get all eligible students belonging to
         * the selected classes and academic year.
         */
        $eligibleStudents = $this->getStudentsForClasses(
            $validated['class_ids'],
            $validated['academic_year']
        );

        $eligibleStudentIds = $eligibleStudents
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        $studentRecords = $validated['student_records'] ?? [];

        /*
         * Only allow students who actually belong
         * to the selected classes.
         */
        $studentRecords = collect($studentRecords)
            ->filter(function ($status, $studentId) use (
                $eligibleStudentIds
            ) {
                return $eligibleStudentIds->contains(
                    (int) $studentId
                );
            })
            ->mapWithKeys(function ($status, $studentId) {
                return [
                    (int) $studentId => $status,
                ];
            });

        /*
         * Count participation automatically.
         *
         * applied:
         *     applied + appeared + passed
         *
         * appeared:
         *     appeared + passed
         *
         * passed:
         *     passed
         */
        $totalEligible = $eligibleStudents->count();

        $totalApplied = $studentRecords
            ->filter(
                fn ($status) =>
                    in_array(
                        $status,
                        ['applied', 'appeared', 'passed'],
                        true
                    )
            )
            ->count();

        $totalAppeared = $studentRecords
            ->filter(
                fn ($status) =>
                    in_array(
                        $status,
                        ['appeared', 'passed'],
                        true
                    )
            )
            ->count();

        $totalPassed = $studentRecords
            ->filter(
                fn ($status) =>
                    $status === 'passed'
            )
            ->count();

        /*
         * Validate logical participation flow.
         */
        if ($totalPassed > $totalAppeared) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_records' =>
                        'Passed students cannot be greater than appeared students.',
                ]);
        }

        if ($totalAppeared > $totalApplied) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_records' =>
                        'Appeared students cannot be greater than applied students.',
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $studentRecords,
            $totalEligible,
            $totalApplied,
            $totalAppeared,
            $totalPassed
        ) {
            $exam = ScholarshipExam::create([
                'exam_name' => $validated['exam_name'],
                'academic_year' => $validated['academic_year'],
                'exam_date' => $validated['exam_date'],
                'exam_type' => $validated['exam_type'],
                'conducted_by' =>
                    $validated['conducted_by'] ?? null,

                'total_eligible' => $totalEligible,
                'total_applied' => $totalApplied,
                'total_appeared' => $totalAppeared,
                'total_passed' => $totalPassed,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'status' =>
                    $validated['status'] ?? true,
            ]);

            /*
             * Attach selected classes.
             */
            $exam->classes()->sync(
                $validated['class_ids']
            );

            /*
             * Save student participation records.
             */
            foreach ($studentRecords as $studentId => $status) {
                $exam->applications()->create([
                    'student_id' => $studentId,
                    'status' => $status,
                    'remarks' => null,
                ]);
            }

            /*
             * Create passed-student records for every
             * student marked as passed.
             *
             * Marks and scholarship information can
             * be added later from the Edit/Result section.
             */
            foreach (
                $studentRecords->filter(
                    fn ($status) =>
                        $status === 'passed'
                ) as $studentId => $status
            ) {
                $exam->passedStudents()->create([
                    'student_id' => $studentId,
                    'marks' => null,
                    'percentage' => null,
                    'scholarship_received' => false,
                    'scholarship_amount' => null,
                    'remarks' => null,
                ]);
            }
        });

        return redirect()
            ->route('admin.scholarship.exams.index')
            ->with(
                'success',
                'Scholarship exam record created successfully.'
            );
    }

    /**
     * Display a single scholarship exam.
     */
    public function show(ScholarshipExam $exam)
    {
        $exam->load([
            'classes',

            'applications.student',

            'passedStudents.student',
        ]);

        return view(
            'admin.scholarship.exams.show',
            compact('exam')
        );
    }

    /**
     * Show edit exam record page.
     */
    public function edit(ScholarshipExam $exam)
    {
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        $exam->load([
            'classes',
            'applications.student',
            'passedStudents.student',
        ]);

        $selectedClassIds = $exam
            ->classes
            ->pluck('id')
            ->toArray();

        /*
         * Existing student statuses for the edit page.
         *
         * Example:
         * [
         *     15 => 'applied',
         *     18 => 'appeared',
         *     25 => 'passed',
         * ]
         */
        $studentStatuses = $exam
            ->applications
            ->pluck('status', 'student_id')
            ->toArray();

        return view(
            'admin.scholarship.exams.edit',
            compact(
                'exam',
                'classes',
                'selectedClassIds',
                'studentStatuses'
            )
        );
    }

    /**
     * Update scholarship exam record.
     *
     * Student totals are recalculated from
     * individual student records.
     */
    public function update(
        Request $request,
        ScholarshipExam $exam
    ) {
        $validated = $request->validate([
            'exam_name' => [
                'required',
                'string',
                'max:255',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'exam_date' => [
                'required',
                'date',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'class_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'class_ids.*' => [
                'integer',
                'exists:school_classes,id',
            ],

            'student_records' => [
                'nullable',
                'array',
            ],

            'student_records.*' => [
                'in:not_applied,applied,appeared,passed',
            ],
        ]);

        /*
         * Get eligible students for the selected
         * classes and academic year.
         */
        $eligibleStudents = $this->getStudentsForClasses(
            $validated['class_ids'],
            $validated['academic_year']
        );

        $eligibleStudentIds = $eligibleStudents
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        $studentRecords = $validated['student_records'] ?? [];

        /*
         * Keep only students belonging to the
         * selected classes.
         */
        $studentRecords = collect($studentRecords)
            ->filter(function ($status, $studentId) use (
                $eligibleStudentIds
            ) {
                return $eligibleStudentIds->contains(
                    (int) $studentId
                );
            })
            ->mapWithKeys(function ($status, $studentId) {
                return [
                    (int) $studentId => $status,
                ];
            });

        $totalEligible = $eligibleStudents->count();

        $totalApplied = $studentRecords
            ->filter(
                fn ($status) =>
                    in_array(
                        $status,
                        ['applied', 'appeared', 'passed'],
                        true
                    )
            )
            ->count();

        $totalAppeared = $studentRecords
            ->filter(
                fn ($status) =>
                    in_array(
                        $status,
                        ['appeared', 'passed'],
                        true
                    )
            )
            ->count();

        $totalPassed = $studentRecords
            ->filter(
                fn ($status) =>
                    $status === 'passed'
            )
            ->count();

        if ($totalPassed > $totalAppeared) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_records' =>
                        'Passed students cannot be greater than appeared students.',
                ]);
        }

        if ($totalAppeared > $totalApplied) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_records' =>
                        'Appeared students cannot be greater than applied students.',
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $exam,
            $studentRecords,
            $totalEligible,
            $totalApplied,
            $totalAppeared,
            $totalPassed
        ) {
            /*
             * Update exam information.
             */
            $exam->update([
                'exam_name' => $validated['exam_name'],
                'academic_year' => $validated['academic_year'],
                'exam_date' => $validated['exam_date'],
                'exam_type' => $validated['exam_type'],
                'conducted_by' =>
                    $validated['conducted_by'] ?? null,

                'total_eligible' => $totalEligible,
                'total_applied' => $totalApplied,
                'total_appeared' => $totalAppeared,
                'total_passed' => $totalPassed,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'status' =>
                    $validated['status'] ?? true,
            ]);

            /*
             * Update classes.
             */
            $exam->classes()->sync(
                $validated['class_ids']
            );

            /*
             * Existing application records are replaced
             * by the current student selection.
             */
            $exam->applications()->delete();

            foreach ($studentRecords as $studentId => $status) {
                $exam->applications()->create([
                    'student_id' => $studentId,
                    'status' => $status,
                    'remarks' => null,
                ]);
            }

            /*
             * Remove passed-student records for students
             * who are no longer marked as passed.
             *
             * Existing passed records for still-passed
             * students are preserved, including marks,
             * percentage and scholarship information.
             */
            $passedStudentIds = $studentRecords
                ->filter(
                    fn ($status) =>
                        $status === 'passed'
                )
                ->keys()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->toArray();

            if (empty($passedStudentIds)) {
                $exam->passedStudents()->delete();
            } else {
                $exam->passedStudents()
                    ->whereNotIn(
                        'student_id',
                        $passedStudentIds
                    )
                    ->delete();

                /*
                 * Create passed records only when they
                 * don't already exist.
                 */
                foreach ($passedStudentIds as $studentId) {
                    $exists = $exam
                        ->passedStudents()
                        ->where(
                            'student_id',
                            $studentId
                        )
                        ->exists();

                    if (!$exists) {
                        $exam->passedStudents()->create([
                            'student_id' => $studentId,
                            'marks' => null,
                            'percentage' => null,
                            'scholarship_received' => false,
                            'scholarship_amount' => null,
                            'remarks' => null,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route(
                'admin.scholarship.exams.show',
                $exam
            )
            ->with(
                'success',
                'Scholarship exam record updated successfully.'
            );
    }

    /**
     * Delete scholarship exam record.
     */
    public function destroy(ScholarshipExam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('admin.scholarship.exams.index')
            ->with(
                'success',
                'Scholarship exam record deleted successfully.'
            );
    }

    /**
     * Fetch existing students for scholarship exam selection.
     *
     * Students are NOT created here.
     * Existing Student Module records are fetched only.
     */
    public function students(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
        ]);

        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        $students = $this->getStudentsForClass(
            $schoolClass,
            $validated['academic_year']
        );

        return response()->json([
            'success' => true,

            'class' => $schoolClass->class_name,

            'section' => $schoolClass->section,

            'academic_year' =>
                $validated['academic_year'],

            'count' => $students->count(),

            'students' => $students,
        ]);
    }

    /**
     * Get students for multiple selected classes.
     */
    private function getStudentsForClasses(
        array $classIds,
        string $academicYear
    ) {
        $schoolClasses = SchoolClass::query()
            ->whereIn('id', $classIds)
            ->get();

        $studentIds = collect();

        foreach ($schoolClasses as $schoolClass) {
            $students = $this->getStudentsForClass(
                $schoolClass,
                $academicYear
            );

            $studentIds = $studentIds
                ->merge(
                    $students->pluck('id')
                );
        }

        $studentIds = $studentIds
            ->unique()
            ->values();

        if ($studentIds->isEmpty()) {
            return collect();
        }

        return Student::query()
            ->whereIn('id', $studentIds)
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'marathi_name',
                'academic_year',
                'class',
                'section',
            ]);
    }

    /**
     * Get students belonging to one SchoolClass.
     */
    private function getStudentsForClass(
        SchoolClass $schoolClass,
        string $academicYear
    ) {
        /*
         * Normalize:
         *
         * Class Module:
         *     10
         *     class 10
         *
         * Student Module:
         *     10
         *     class 10
         */
        $className = strtolower(
            trim($schoolClass->class_name)
        );

        $classNumber = preg_replace(
            '/^class\s*/i',
            '',
            $className
        );

        return Student::query()
            ->where(
                'academic_year',
                $academicYear
            )
            ->where(
                'section',
                $schoolClass->section
            )
            ->where(
                'status',
                'active'
            )
            ->where(function ($query) use ($classNumber) {

                $query
                    ->whereRaw(
                        'LOWER(TRIM(class)) = ?',
                        [$classNumber]
                    )
                    ->orWhereRaw(
                        'LOWER(TRIM(class)) = ?',
                        ['class ' . $classNumber]
                    );
            })
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'marathi_name',
                'academic_year',
                'class',
                'section',
            ]);
    }
}
