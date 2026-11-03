<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherSalary;
use App\Models\SchoolSetting;
use App\Exports\TeacherReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TeacherReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('teacher_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $query->whereIn('id', function ($attendanceQuery) use ($request) {
                $attendanceQuery->select('teacher_id')
                    ->from('teacher_attendances')
                    ->when($request->filled('from_date'), function ($q) use ($request) {
                        $q->whereDate('attendance_date', '>=', $request->from_date);
                    })
                    ->when($request->filled('to_date'), function ($q) use ($request) {
                        $q->whereDate('attendance_date', '<=', $request->to_date);
                    });
            });
        }

        $teachers = $query
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $subjects = Teacher::query()
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->distinct()
            ->orderBy('subject')
            ->pluck('subject');

        $genders = Teacher::query()
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->distinct()
            ->orderBy('gender')
            ->pluck('gender');

        $schoolSetting = SchoolSetting::query()->first();

        return view(
            'allReports.teacherReports.index',
            compact('teachers', 'subjects', 'genders', 'schoolSetting')
        );
    }

    public function show(Teacher $teacher)
    {
        $attendanceRecords = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('attendance_date')
            ->get();

        $salaryRecords = TeacherSalary::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->get();

        $timetableCount = $teacher->timetables()->count();

        $schoolSetting = SchoolSetting::query()->first();

        return view(
            'allReports.teacherReports.show',
            compact(
                'teacher',
                'attendanceRecords',
                'salaryRecords',
                'timetableCount',
                'schoolSetting'
            )
        );
    }

    public function pdf(Teacher $teacher)
    {
        $attendanceRecords = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('attendance_date')
            ->get();

        $salaryRecords = TeacherSalary::query()
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->get();

        $timetableCount = $teacher->timetables()->count();

        $schoolSetting = SchoolSetting::query()->first();

        $pdf = Pdf::loadView(
            'allReports.teacherReports.pdf',
            compact(
                'teacher',
                'attendanceRecords',
                'salaryRecords',
                'timetableCount',
                'schoolSetting'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'teacher-report-' . $teacher->teacher_id . '.pdf'
        );
    }

    public function excel(Teacher $teacher)
    {
        return Excel::download(
            new TeacherReportExport($teacher),
            'teacher-report-' . $teacher->teacher_id . '.xlsx'
        );
    }
}
