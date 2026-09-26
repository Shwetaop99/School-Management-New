<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('student');

        // Search student
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });

            if ($request->filled('student_id')) {
    $query->whereHas('student', function ($q) use ($request) {
        $q->where('student_id', $request->student_id);
    });
}
        }

        // Academic Year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // Section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        // Attendance Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // From Date
        if ($request->filled('from_date')) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        // To Date
        if ($request->filled('to_date')) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        $attendance = $query
            ->orderByDesc('attendance_date')
            ->paginate(20)
            ->withQueryString();

        // Filter dropdown values
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

    return Pdf::loadView(
        'allReports.attendanceReport.pdf',
        compact('attendance')
    )
    ->setPaper('a4', 'landscape')
    ->download('attendance-report.pdf');
}


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
}