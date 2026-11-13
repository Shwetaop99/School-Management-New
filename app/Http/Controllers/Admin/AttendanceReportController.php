<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\LeaveApplication;
use App\Models\TeacherSalary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE REPORT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Attendance::with('student');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('student_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendance = $query
            ->orderByDesc('attendance_date')
            ->paginate(20)
            ->withQueryString();

        $academicYears = Attendance::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = Attendance::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $sections = Attendance::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $statuses = Attendance::query()
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_id',
                'first_name',
                'last_name',
                'academic_year',
                'class',
                'section',
            ]);

        return view(
            'allReports.attendanceReport.index',
            compact(
                'attendance',
                'academicYears',
                'classes',
                'sections',
                'statuses',
                'students'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE PDF
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        $query = Attendance::with('student');

        if ($request->filled('student_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendance = $query
            ->orderByDesc('attendance_date')
            ->get();

        $schoolSetting = SchoolSetting::query()->first();

        return Pdf::loadView(
            'allReports.attendanceReport.pdf',
            compact('attendance', 'schoolSetting')
        )
            ->setPaper('a4', 'landscape')
            ->download('attendance-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE EXCEL
    |--------------------------------------------------------------------------
    */

    public function excel(Request $request)
    {
        $query = Attendance::with('student');

        if ($request->filled('student_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendance = $query
            ->orderByDesc('attendance_date')
            ->get();

        return Excel::download(
            new \App\Exports\AttendanceReportExport($attendance),
            'attendance-report.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY ATTENDANCE REPORT
    |--------------------------------------------------------------------------
    */

    public function teacher(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $monthDate = Carbon::createFromFormat('!Y-m', $month);

        $startDate = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : $monthDate->copy()->startOfMonth();

        $endDate = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : $monthDate->copy()->endOfMonth();

        $teacherQuery = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $teacherQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('teacher_id', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $allTeacherIds = (clone $teacherQuery)->pluck('id');

        $teachers = $teacherQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20)
            ->withQueryString();

        $attendanceQuery = TeacherAttendance::with('teacher')
            ->whereIn('teacher_id', $allTeacherIds)
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $attendanceQuery->where('status', $request->status);
        }

        $attendanceRecords = $attendanceQuery
            ->orderByDesc('attendance_date')
            ->get();

        $attendanceByTeacher = $attendanceRecords->groupBy('teacher_id');

        foreach ($teachers as $teacher) {
            $records = $attendanceByTeacher->get(
                $teacher->id,
                collect()
            );

            $present = $records->where('status', 'Present')->count();
            $absent = $records->where('status', 'Absent')->count();
            $halfDays = $records->where('status', 'Half Day')->count();
            $leave = $records->where('status', 'Leave')->count();

            $workingDays = $present + $absent + $halfDays;
            $effectivePresent = $present + ($halfDays * 0.5);

            $teacher->attendance_records = $records;

            $teacher->attendance_summary = [
                'present' => $present,
                'absent' => $absent,
                'half_days' => $halfDays,
                'leave' => $leave,
                'working_days' => $workingDays,
                'overtime_hours' => $records->sum('overtime_hours'),
                'percentage' => $workingDays > 0
                    ? round(($effectivePresent / $workingDays) * 100, 2)
                    : 0,
            ];
        }

        $summary = [
            'present' => $attendanceRecords->where('status', 'Present')->count(),
            'absent' => $attendanceRecords->where('status', 'Absent')->count(),
            'half_days' => $attendanceRecords->where('status', 'Half Day')->count(),
            'leave' => $attendanceRecords->where('status', 'Leave')->count(),
            'overtime_hours' => $attendanceRecords->sum('overtime_hours'),
        ];

        $schoolSetting = SchoolSetting::query()->first();

        return view(
            'allReports.facultyAttendanceReport.index',
            compact(
                'teachers',
                'attendanceRecords',
                'summary',
                'month',
                'startDate',
                'endDate',
                'schoolSetting'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY ATTENDANCE PDF
    |--------------------------------------------------------------------------
    */

    public function teacherPdf(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $monthDate = Carbon::createFromFormat('!Y-m', $month);

        $startDate = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : $monthDate->copy()->startOfMonth();

        $endDate = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : $monthDate->copy()->endOfMonth();

        $teacherQuery = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $teacherQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('teacher_id', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $teacherIds = $teacherQuery->pluck('id');

        $attendanceQuery = TeacherAttendance::with('teacher')
            ->whereIn('teacher_id', $teacherIds)
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $attendanceQuery->where('status', $request->status);
        }

        $attendanceRecords = $attendanceQuery
            ->orderByDesc('attendance_date')
            ->get();

        $summary = [
            'present' => $attendanceRecords->where('status', 'Present')->count(),
            'absent' => $attendanceRecords->where('status', 'Absent')->count(),
            'half_days' => $attendanceRecords->where('status', 'Half Day')->count(),
            'leave' => $attendanceRecords->where('status', 'Leave')->count(),
            'overtime_hours' => $attendanceRecords->sum('overtime_hours'),
        ];

        $schoolSetting = SchoolSetting::query()->first();

        return Pdf::loadView(
            'allReports.facultyAttendanceReport.pdf',
            compact(
                'attendanceRecords',
                'summary',
                'startDate',
                'endDate',
                'schoolSetting'
            )
        )
            ->setPaper('a4', 'landscape')
            ->download('faculty-attendance-report.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY ATTENDANCE EXCEL
    |--------------------------------------------------------------------------
    */

    public function teacherExcel(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $monthDate = Carbon::createFromFormat('!Y-m', $month);

        $startDate = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : $monthDate->copy()->startOfMonth();

        $endDate = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : $monthDate->copy()->endOfMonth();

        $teacherQuery = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $teacherQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('teacher_id', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $teacherIds = $teacherQuery->pluck('id');

        $attendanceQuery = TeacherAttendance::with('teacher')
            ->whereIn('teacher_id', $teacherIds)
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $attendanceQuery->where('status', $request->status);
        }

        $attendanceRecords = $attendanceQuery
            ->orderByDesc('attendance_date')
            ->get();

        $rows = [];

        foreach ($attendanceRecords as $record) {
            $rows[] = [
                $record->teacher?->teacher_id ?? '',
                trim(
                    ($record->teacher?->first_name ?? '') . ' ' .
                    ($record->teacher?->last_name ?? '')
                ),
                $record->teacher?->subject ?? '',
                $record->attendance_date
                    ? Carbon::parse($record->attendance_date)->format('d-m-Y')
                    : '',
                $record->status,
                $record->overtime_hours,
                $record->remarks,
            ];
        }

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
                    'Faculty ID',
                    'Faculty Name',
                    'Subject',
                    'Attendance Date',
                    'Status',
                    'Overtime Hours',
                    'Remarks',
                ];
            }
        };

        return Excel::download(
            $export,
            'faculty-attendance-report.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL FACULTY PDF EXPORT
    |--------------------------------------------------------------------------
    */

    public function teacherProfilePdf(Request $request, $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);

        $query = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id);

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendanceRecords = $query
            ->orderByDesc('attendance_date')
            ->get();

        $present = $attendanceRecords->where('status', 'Present')->count();
        $absent = $attendanceRecords->where('status', 'Absent')->count();
        $halfDays = $attendanceRecords->where('status', 'Half Day')->count();

        $workingDays = $present + $absent + $halfDays;
        $effectivePresent = $present + ($halfDays * 0.5);

        $attendanceSummary = [
            'present' => $present,
            'absent' => $absent,
            'working_days' => $workingDays,
            'percentage' => $workingDays > 0
                ? round(($effectivePresent / $workingDays) * 100, 2)
                : 0,
        ];

        $schoolSetting = SchoolSetting::query()->first();

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $pdf = Pdf::loadView(
            'allReports.facultyAttendanceReport.profile-pdf',
            compact(
                'teacher',
                'attendanceRecords',
                'attendanceSummary',
                'schoolSetting',
                'fromDate',
                'toDate'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'faculty-attendance-' . ($teacher->teacher_id ?? $teacher->id) . '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL FACULTY EXCEL EXPORT
    |--------------------------------------------------------------------------
    */

    public function teacherProfileExcel(Request $request, $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);

        $query = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id);

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendanceRecords = $query
            ->orderByDesc('attendance_date')
            ->get();

        $rows = [];

        foreach ($attendanceRecords as $record) {
            $rows[] = [
                $teacher->teacher_id ?? $teacher->id,
                trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')),
                $teacher->subject ?? '',
                $teacher->email ?? '',
                $teacher->phone ?? '',
                $record->attendance_date
                    ? Carbon::parse($record->attendance_date)->format('d-m-Y')
                    : '',
                $record->attendance_date
                    ? Carbon::parse($record->attendance_date)->format('l')
                    : '',
                $record->status ?? '',
                $record->overtime_hours ?? 0,
                $record->remarks ?? '',
            ];
        }

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
                    'Faculty ID',
                    'Faculty Name',
                    'Subject',
                    'Email',
                    'Phone',
                    'Attendance Date',
                    'Day',
                    'Status',
                    'Overtime Hours',
                    'Remarks',
                ];
            }
        };

        return Excel::download(
            $export,
            'faculty-attendance-' . ($teacher->teacher_id ?? $teacher->id) . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL FACULTY ATTENDANCE PROFILE
    |--------------------------------------------------------------------------
    */

    public function teacherProfile(Request $request, $teacher)
    {
        $teacher = Teacher::findOrFail($teacher);

        $query = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id);

        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendanceRecords = $query
            ->orderByDesc('attendance_date')
            ->get();

        $present = $attendanceRecords->where('status', 'Present')->count();
        $absent = $attendanceRecords->where('status', 'Absent')->count();
        $halfDays = $attendanceRecords->where('status', 'Half Day')->count();

        $workingDays = $present + $absent + $halfDays;
        $effectivePresent = $present + ($halfDays * 0.5);

        $attendanceSummary = [
            'present' => $present,
            'absent' => $absent,
            'working_days' => $workingDays,
            'percentage' => $workingDays > 0
                ? round(($effectivePresent / $workingDays) * 100, 2)
                : 0,
        ];

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $schoolSetting = SchoolSetting::query()->first();

        return view(
            'allReports.facultyAttendanceReport.profile',
            compact(
                'teacher',
                'attendanceRecords',
                'attendanceSummary',
                'schoolSetting',
                'fromDate',
                'toDate'
            )
        );
    }
}
