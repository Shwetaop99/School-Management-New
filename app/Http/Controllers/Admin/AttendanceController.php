<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Exports\StudentAttendanceExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MAIN INDEX PAGE (Classes & Sections List)
    |--------------------------------------------------------------------------
    | This loads student-index.blade.php
    */

    public function index(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');

        try {
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthDate = now()->startOfMonth();
            $selectedMonth = $monthDate->format('Y-m');
        }

        $selectedAcademicYear = $request->academic_year;

        // Default academic year
        if (!$selectedAcademicYear) {
            $selectedAcademicYear = Student::query()
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year');
        }

        $students = Student::query()
            ->where('status', 'Active')
            ->when($selectedAcademicYear, function ($query) use ($selectedAcademicYear) {
                $query->where('academic_year', $selectedAcademicYear);
            })
            ->orderBy('class')
            ->orderBy('section')
            ->orderBy('first_name')
            ->get();

        $today = Carbon::today();

        $classCards = $students
            ->groupBy(function ($student) {
                return $student->class . '|' . $student->section . '|' . $student->academic_year;
            })
            ->map(function ($group) use ($today) {

                $firstStudent = $group->first();

                $class         = $firstStudent->class;
                $section       = $firstStudent->section;
                $academicYear  = $firstStudent->academic_year;

                $attendance = Attendance::query()
                    ->where('academic_year', $academicYear)
                    ->where('class', $class)
                    ->where('section', $section)
                    ->whereDate('attendance_date', $today)
                    ->get();

                $present = $attendance->where('status', 'Present')->count();
                $absent  = $attendance->where('status', 'Absent')->count();

                $teacherName = 'Not Assigned';

                if (!empty($firstStudent->class_id) && !empty($firstStudent->section_id)) {
                    $assignment = ClassTeacherAssignment::query()
                        ->with('teacher')
                        ->where('academic_year', $academicYear)
                        ->where('class_id', $firstStudent->class_id)
                        ->where('section_id', $firstStudent->section_id)
                        ->first();

                    if ($assignment && $assignment->teacher) {
                        $teacher = $assignment->teacher;
                        $teacherName = trim(
                            ($teacher->first_name ?? '') . ' ' .
                            ($teacher->middle_name ?? '') . ' ' .
                            ($teacher->last_name ?? '')
                        );
                    }
                }

                return (object) [
                    'class'          => $class,
                    'section'        => $section,
                    'academic_year'  => $academicYear,
                    'student_count'  => $group->count(),
                    'present_count'  => $present,
                    'absent_count'   => $absent,
                    'class_teacher'  => $teacherName,
                ];
            })
            ->values();

        $totalStudents = $students->count();
        $totalPresent  = $classCards->sum('present_count');
        $totalAbsent   = $classCards->sum('absent_count');
        $month         = $selectedMonth;

        return view('admin.attendance.student-index', compact(
            'classCards',
            'totalStudents',
            'totalPresent',
            'totalAbsent',
            'month'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | MARK ATTENDANCE SHEET (student.blade.php)
    |--------------------------------------------------------------------------
    */

    public function sheet(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');

        try {
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthDate = now()->startOfMonth();
            $selectedMonth = $monthDate->format('Y-m');
        }

        $selectedAcademicYear = $request->academic_year;
        $selectedClass        = $request->class;
        $selectedSection      = $request->section;

        // Default academic year
        if (!$selectedAcademicYear) {
            $selectedAcademicYear = Student::query()
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year');
        }

        $students = Student::query()
            ->where('status', 'Active')
            ->when($selectedAcademicYear, function ($query) use ($selectedAcademicYear) {
                $query->where('academic_year', $selectedAcademicYear);
            })
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->where('class', $selectedClass);
            })
            ->when($selectedSection, function ($query) use ($selectedSection) {
                $query->where('section', $selectedSection);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $startDate = $monthDate->copy()->startOfMonth();
        $endDate   = $monthDate->copy()->endOfMonth();

        $attendanceRecords = Attendance::query()
            ->whereBetween('attendance_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->when($selectedAcademicYear, function ($query) use ($selectedAcademicYear) {
                $query->where('academic_year', $selectedAcademicYear);
            })
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->where('class', $selectedClass);
            })
            ->when($selectedSection, function ($query) use ($selectedSection) {
                $query->where('section', $selectedSection);
            })
            ->get();

        $attendanceMap = [];

        foreach ($attendanceRecords as $record) {
            $dateKey = Carbon::parse($record->attendance_date)->format('Y-m-d');
            $attendanceMap[$record->student_id][$dateKey] = $record->status;
        }

        $month = $selectedMonth;

        return view('admin.attendance.student', compact(
            'students',
            'attendanceMap',
            'month',
            'selectedMonth',
            'selectedAcademicYear',
            'selectedClass',
            'selectedSection'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - CLASSES
    |--------------------------------------------------------------------------
    */

    public function classes(Request $request)
    {
        $academicYear = $request->academic_year;

        if (!$academicYear) {
            $academicYear = Student::query()
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year');
        }

        $classes = Student::query()
            ->where('status', 'Active')
            ->when($academicYear, function ($query) use ($academicYear) {
                $query->where('academic_year', $academicYear);
            })
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        return response()->json($classes);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - SECTIONS
    |--------------------------------------------------------------------------
    */

    public function sections(Request $request)
    {
        $academicYear = $request->academic_year;
        $class        = $request->class;

        $sections = Student::query()
            ->where('status', 'Active')
            ->when($academicYear, function ($query) use ($academicYear) {
                $query->where('academic_year', $academicYear);
            })
            ->when($class, function ($query) use ($class) {
                $query->where('class', $class);
            })
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return response()->json($sections);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - STUDENTS
    |--------------------------------------------------------------------------
    */

    public function students(Request $request)
    {
        $students = Student::query()
            ->where('status', 'Active')
            ->when($request->academic_year, function ($query) use ($request) {
                $query->where('academic_year', $request->academic_year);
            })
            ->when($request->class, function ($query) use ($request) {
                $query->where('class', $request->class);
            })
            ->when($request->section, function ($query) use ($request) {
                $query->where('section', $request->section);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return response()->json($students);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE STUDENT ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'attendance' => ['required', 'array'],
        ]);

        $attendanceData = $request->input('attendance', []);

        foreach ($attendanceData as $studentId => $dates) {

            if (!is_array($dates)) {
                continue;
            }

            $student = Student::find($studentId);

            if (!$student) {
                continue;
            }

            foreach ($dates as $date => $status) {

                if (!$date) {
                    continue;
                }

                try {
                    $attendanceDate = Carbon::parse($date);
                } catch (\Throwable $e) {
                    continue;
                }

                $status = ucfirst(strtolower(trim((string) $status)));

                if (!in_array($status, ['Present', 'Absent', 'Holiday'], true)) {
                    Attendance::query()
                        ->where('student_id', $studentId)
                        ->whereDate('attendance_date', $attendanceDate)
                        ->delete();
                    continue;
                }

                // Holiday protection
                $holidayName = $this->getHolidayName($attendanceDate);

                if ($attendanceDate->isSunday() || $holidayName) {
                    $status = 'Holiday';
                }

                Attendance::updateOrCreate(
                    [
                        'student_id'      => $studentId,
                        'attendance_date' => $attendanceDate->format('Y-m-d'),
                    ],
                    [
                        'academic_year' => $student->academic_year,
                        'class'         => $student->class,
                        'section'       => $student->section,
                        'status'        => $status,
                    ]
                );
            }
        }

        $redirectParams = array_filter([
            'month'         => $request->month,
            'academic_year' => $request->academic_year,
            'class'         => $request->class,
            'section'       => $request->section,
        ]);

        return redirect()
            ->route('admin.attendance.student.sheet', $redirectParams)
            ->with('success', 'Student attendance saved successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SINGLE ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:Present,Absent,Holiday'],
        ]);

        $date = Carbon::parse($attendance->attendance_date);
        $holidayName = $this->getHolidayName($date);

        if ($date->isSunday() || $holidayName) {
            $attendance->status = 'Holiday';
        } else {
            $attendance->status = $validated['status'];
        }

        $attendance->save();

        return redirect()
            ->back()
            ->with('success', 'Attendance updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE REPORT
    |--------------------------------------------------------------------------
    */

    public function report(Request $request)
    {
        $query = Attendance::query()->with('student');

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        $attendance = $query->orderByDesc('attendance_date')->get();

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year')
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

        return view('admin.attendance.report', compact(
            'attendance',
            'academicYears',
            'classes',
            'sections'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT REPORT
    |--------------------------------------------------------------------------
    */

    public function studentReport($studentId, Request $request)
    {
        $student = Student::findOrFail($studentId);

        $query = Attendance::query()->where('student_id', $studentId);

        if ($request->filled('from')) {
            $query->whereDate('attendance_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('attendance_date', '<=', $request->to);
        }

        $attendance = $query->orderBy('attendance_date')->get();

        return view('admin.attendance.student-report', compact(
            'student',
            'attendance'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | MONTHLY REPORT
    |--------------------------------------------------------------------------
    */

    public function monthlyReport(Request $request)
    {
        $academicYear = $request->academic_year;
        $month        = $request->month ?? Carbon::now()->month;
        $year         = $request->year ?? Carbon::now()->year;

        $query = Attendance::query()
            ->with('student')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year);

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $attendance = $query->orderBy('attendance_date')->get();

        return view('admin.attendance.monthly', compact(
            'attendance',
            'academicYear',
            'month',
            'year'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT REPORT
    |--------------------------------------------------------------------------
    */

    public function printReport(Request $request)
    {
        $query = Attendance::query()->with('student');

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        $attendance = $query->orderByDesc('attendance_date')->get();

        return view('admin.attendance.report-print', compact('attendance'));
    }


    /*
    |--------------------------------------------------------------------------
    | PDF REPORT
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        $query = Attendance::query()->with('student');

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        $attendance = $query->orderByDesc('attendance_date')->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.attendance.report-pdf', compact('attendance'));

        return $pdf->download('attendance-report.pdf');
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL REPORT
    |--------------------------------------------------------------------------
    */

    public function excel(Request $request)
    {
        return Excel::download(
            new StudentAttendanceExport($request),
            'attendance-report.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT NAME
    |--------------------------------------------------------------------------
    */

    public function studentName($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return 'Unknown Student';
        }

        return trim(
            ($student->first_name ?? '') . ' ' .
            ($student->middle_name ?? '') . ' ' .
            ($student->last_name ?? '')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NATIONAL HOLIDAYS
    |--------------------------------------------------------------------------
    */

    private function nationalHolidays(): array
    {
        return [
            '01-26' => 'Republic Day',
            '08-15' => 'Independence Day',
            '10-02' => 'Gandhi Jayanti',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GET HOLIDAY NAME
    |--------------------------------------------------------------------------
    */

    private function getHolidayName(Carbon $date): ?string
    {
        if ($date->isSunday()) {
            return 'Sunday Holiday';
        }

        $holidays = $this->nationalHolidays();
        $key = $date->format('m-d');

        return $holidays[$key] ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | CLASS KEY
    |--------------------------------------------------------------------------
    */

    private function makeClassKey($academicYear, $class, $section)
    {
        return implode('|', [$academicYear, $class, $section]);
    }
}