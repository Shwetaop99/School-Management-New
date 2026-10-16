<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Exports\TeacherReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherReportController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('first_name')->get();

        return view(
            'admin.teachers.reports.index',
            compact('teachers')
        );
    }

    public function show(Teacher $teacher)
    {
        return view(
            'admin.reports.show',
            compact('teacher')
        );
    }

    public function pdf(Teacher $teacher)
    {
        $pdf = Pdf::loadView(
            'admin.reports.pdf',
            compact('teacher')
        );

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