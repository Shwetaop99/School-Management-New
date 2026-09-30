<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Exports\TeacherReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TeacherReportController extends Controller
{
    public function index(Request $request)
{
    $query = Teacher::query();

    // Faculty Search
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

    // Gender
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    // Subject
    if ($request->filled('subject')) {
        $query->where('subject', $request->subject);
    }

    // Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $teachers = $query
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    // Subjects for dropdown
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

    return view(
    'allReports.teacherReports.index',
    compact(
        'teachers',
        'subjects',
        'genders'
    )
);
}

    public function show(Teacher $teacher)
    {
        return view(
            'allReports.teacherReports.show',
            compact('teacher')
        );
    }

    public function pdf(Teacher $teacher)
    {
        $pdf = Pdf::loadView(
            'admin.teacherReports.pdf',
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