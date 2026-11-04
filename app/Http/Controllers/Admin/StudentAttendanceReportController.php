<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StudentAttendanceReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE REPORT - CLASS DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $classValues = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->pluck('class');

        $classes = $classValues
            ->map(function ($class) {
                $normalized = $this->normalizeClass($class);

                return [
                    'value' => $normalized,
                    'label' => is_numeric($normalized)
                        ? 'Class ' . $normalized
                        : $normalized,
                ];
            })
            ->unique('value')
            ->sortBy('label')
            ->values();

        $classes = $classes->map(function ($class) {

            $count = Student::query()
                ->whereRaw(
                    "LOWER(TRIM(REPLACE(LOWER(class), 'class', ''))) = ?",
                    [strtolower($class['value'])]
                )
                ->count();

            return array_merge($class, [
                'student_count' => $count,
            ]);
        });

        return view(
            'allReports.studentAttendanceReport.index',
            compact('classes')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS - DIVISION - ACADEMIC YEAR - STUDENT LIST
    |--------------------------------------------------------------------------
    */

    public function classStudents(Request $request)
    {
        $selectedClass = $this->normalizeClass(
            $request->input('class', '')
        );

        $baseQuery = Student::query()
            ->whereRaw(
                "LOWER(TRIM(REPLACE(LOWER(class), 'class', ''))) = ?",
                [strtolower($selectedClass)]
            );

        $sections = (clone $baseQuery)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $academicYears = (clone $baseQuery)
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $selectedSection = $request->input('section');
        $selectedAcademicYear = $request->input('academic_year');
        $search = $request->input('search');

        $students = collect();

        if ($selectedSection && $selectedAcademicYear) {

            $studentQuery = (clone $baseQuery)
                ->where('section', $selectedSection)
                ->where('academic_year', $selectedAcademicYear);

            if ($search) {
                $studentQuery->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('roll_number', 'like', "%{$search}%");
                });
            }

            $students = $studentQuery
                ->with([
                    'attendances' => function ($query) use ($selectedAcademicYear) {
                        $query->where(
                            'academic_year',
                            $selectedAcademicYear
                        );
                    }
                ])
                ->orderBy('roll_number')
                ->orderBy('first_name')
                ->get();

            $students->each(function ($student) {
                $student->attendance_summary =
                    $this->calculateAttendanceSummary(
                        $student->attendances
                    );
            });
        }

        return view(
            'allReports.studentAttendanceReport.class',
            compact(
                'selectedClass',
                'sections',
                'academicYears',
                'selectedSection',
                'selectedAcademicYear',
                'search',
                'students'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL STUDENT ATTENDANCE PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile(Request $request, $student)
    {
        $student = Student::findOrFail($student);

        $academicYear = $request->input(
            'academic_year',
            $student->academic_year
        );

        $filters = $this->getAttendanceFilters($request);

        $attendanceRecords = $this->getStudentAttendance(
            $student,
            $academicYear,
            $filters['fromDate'],
            $filters['toDate']
        );

        $attendanceSummary = $this->calculateAttendanceSummary(
            $attendanceRecords
        );

        $schoolSetting = SchoolSetting::query()->first();

        $month = $filters['month'];
        $fromDate = $filters['fromDate'];
        $toDate = $filters['toDate'];

        return view(
            'allReports.studentAttendanceReport.profile',
            compact(
                'student',
                'attendanceRecords',
                'attendanceSummary',
                'schoolSetting',
                'academicYear',
                'month',
                'fromDate',
                'toDate'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL STUDENT ATTENDANCE PDF
    |--------------------------------------------------------------------------
    */

    public function profilePdf(Request $request, $student)
    {
        $student = Student::findOrFail($student);

        $academicYear = $request->input(
            'academic_year',
            $student->academic_year
        );

        $filters = $this->getAttendanceFilters($request);

        $attendanceRecords = $this->getStudentAttendance(
            $student,
            $academicYear,
            $filters['fromDate'],
            $filters['toDate']
        );

        $attendanceSummary = $this->calculateAttendanceSummary(
            $attendanceRecords
        );

        $schoolSetting = SchoolSetting::query()->first();

        $month = $filters['month'];
        $fromDate = $filters['fromDate'];
        $toDate = $filters['toDate'];

        $pdf = Pdf::loadView(
            'allReports.studentAttendanceReport.profile-pdf',
            compact(
                'student',
                'attendanceRecords',
                'attendanceSummary',
                'schoolSetting',
                'academicYear',
                'month',
                'fromDate',
                'toDate'
            )
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'student-attendance-' .
            ($student->student_id ?? $student->id) .
            '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL STUDENT ATTENDANCE EXCEL
    |--------------------------------------------------------------------------
    */

    public function profileExcel(Request $request, $student)
    {
        $student = Student::findOrFail($student);

        $academicYear = $request->input(
            'academic_year',
            $student->academic_year
        );

        $filters = $this->getAttendanceFilters($request);

        $attendanceRecords = $this->getStudentAttendance(
            $student,
            $academicYear,
            $filters['fromDate'],
            $filters['toDate']
        );

        $attendanceSummary = $this->calculateAttendanceSummary(
            $attendanceRecords
        );

        $rows = [
            [
                $student->student_id ?? $student->id,
                $student->full_name,
                $student->class,
                $student->section,
                $academicYear,
                $filters['fromDate'] ?? 'All',
                $filters['toDate'] ?? 'All',
                $attendanceSummary['present'],
                $attendanceSummary['absent'],
                $attendanceSummary['percentage'] . '%',
            ],
        ];

        $export = new class($rows) implements FromArray, WithHeadings, ShouldAutoSize {

            private array $rows;

            public function __construct(array $rows)
            {
                $this->rows = $rows;
            }

            public function array(): array
            {
                return $this->rows;
            }

            public function headings(): array
            {
                return [
                    'Student ID',
                    'Student Name',
                    'Class',
                    'Division',
                    'Academic Year',
                    'From Date',
                    'To Date',
                    'Total Present Days',
                    'Absent Days',
                    'Attendance Percentage',
                ];
            }
        };

        return Excel::download(
            $export,
            'student-attendance-' .
            ($student->student_id ?? $student->id) .
            '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE AND PREPARE ATTENDANCE DATE FILTERS
    |--------------------------------------------------------------------------
    */

    private function getAttendanceFilters(Request $request): array
    {
        $validated = $request->validate([
            'month' => [
                'nullable',
                'date_format:Y-m',
            ],
            'from_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'to_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from_date',
            ],
        ]);

        $month = $validated['month'] ?? null;
        $fromDate = $validated['from_date'] ?? null;
        $toDate = $validated['to_date'] ?? null;

        // If a month is selected, fill any missing date boundaries.
        if ($month) {

            $monthStart = Carbon::createFromFormat(
                'Y-m',
                $month
            )->startOfMonth();

            if (!$fromDate) {
                $fromDate = $monthStart->toDateString();
            }

            if (!$toDate) {
                $toDate = $monthStart
                    ->copy()
                    ->endOfMonth()
                    ->toDateString();
            }
        }

        return [
            'month' => $month,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET STUDENT ATTENDANCE RECORDS
    |--------------------------------------------------------------------------
    */

    private function getStudentAttendance(
        Student $student,
        $academicYear,
        $fromDate = null,
        $toDate = null
    ): Collection {
        return Attendance::query()
            ->where('student_id', $student->id)
            ->when(
                $academicYear,
                fn ($query) => $query->where(
                    'academic_year',
                    $academicYear
                )
            )
            ->when(
                $fromDate,
                fn ($query) => $query->whereDate(
                    'attendance_date',
                    '>=',
                    $fromDate
                )
            )
            ->when(
                $toDate,
                fn ($query) => $query->whereDate(
                    'attendance_date',
                    '<=',
                    $toDate
                )
            )
            ->orderByDesc('attendance_date')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE SUMMARY CALCULATION
    |--------------------------------------------------------------------------
    */

    private function calculateAttendanceSummary(
        Collection $attendanceRecords
    ): array {
        $workingDays = $attendanceRecords->whereIn('status', [
            'present',
            'late',
            'half_day',
            'absent',
        ]);

        $presentDays = $workingDays
            ->where('status', 'present')
            ->count();

        $lateDays = $workingDays
            ->where('status', 'late')
            ->count();

        $halfDays = $workingDays
            ->where('status', 'half_day')
            ->count();

        $absentDays = $workingDays
            ->where('status', 'absent')
            ->count();

        $totalWorkingDays = $workingDays->count();

        $attendedDays = $presentDays
            + $lateDays
            + ($halfDays * 0.5);

        $attendancePercentage = $totalWorkingDays > 0
            ? round(
                ($attendedDays / $totalWorkingDays) * 100,
                2
            )
            : 0;

        return [
            'present' => $presentDays,
            'absent' => $absentDays,
            'percentage' => $attendancePercentage,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS VALUE NORMALIZATION
    |--------------------------------------------------------------------------
    */

    private function normalizeClass($class): string
    {
        return trim(
            preg_replace(
                '/^class\s*/i',
                '',
                trim((string) $class)
            )
        );
    }
}
