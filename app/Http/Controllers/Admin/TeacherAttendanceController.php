<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
{
   public function index(Request $request)
{
    $date = $request->date ?? now()->toDateString();

    $teachers = Teacher::orderBy('first_name')->get();

    $attendance = TeacherAttendance::where('attendance_date', $date)
        ->get()
        ->keyBy('teacher_id');

    $summary = [
        'present' => $attendance->where('status', 'Present')->count(),
        'absent' => $attendance->where('status', 'Absent')->count(),
        'half_day' => $attendance->where('status', 'Half Day')->count(),
        'late' => $attendance->where('status', 'Late')->count(),
        'overtime' => $attendance->sum('overtime_hours'),
    ];

    return view('admin.teachers.attendance.index', compact(
        'teachers',
        'attendance',
        'date',
        'summary'
    ));
}
public function store(Request $request)
{
    $request->validate([
        'attendance_date' => 'required|date',
        'attendance' => 'required|array',
        'overtime' => 'nullable|array',
    ]);

    foreach ($request->attendance as $teacherId => $status) {

        TeacherAttendance::updateOrCreate(
            [
                'teacher_id' => $teacherId,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'status' => $status,
                'overtime_hours' => $request->overtime[$teacherId] ?? 0,
            ]
        );
    }

    return redirect()
        ->route('admin.teachers.attendance.index', [
            'date' => $request->attendance_date
        ])
        ->with('success', 'Teacher attendance saved successfully.');
}
}