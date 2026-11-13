<?php

namespace App\Exports;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherSalary;
use App\Models\SchoolSetting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TeacherReportExport implements FromView
{
    public function __construct(public Teacher $teacher)
    {
    }

    public function view(): View
    {
        $attendanceRecords = TeacherAttendance::query()
            ->where('teacher_id', $this->teacher->id)
            ->orderByDesc('attendance_date')
            ->get();

        $salaryRecords = TeacherSalary::query()
            ->where('teacher_id', $this->teacher->id)
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->get();

        $schoolSetting = SchoolSetting::query()->first();

        $timetableCount = $this->teacher->timetables()->count();

        return view('allReports.teacherReports.excel', [
            'teacher' => $this->teacher,
            'attendanceRecords' => $attendanceRecords,
            'salaryRecords' => $salaryRecords,
            'schoolSetting' => $schoolSetting,
            'timetableCount' => $timetableCount,
        ]);
    }
}
