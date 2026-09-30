<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\BookIssue;
use Barryvdh\DomPDF\Facade\Pdf;


class StudentReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Student Query
        |--------------------------------------------------------------------------
        */

        $query = Student::query();


        /*
        |--------------------------------------------------------------------------
        | Student Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('aadhar_card_no', 'like', "%{$search}%");

            });
        }

        


        /*
        |--------------------------------------------------------------------------
        | Academic Year Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('academic_year')) {

            $query->where(
                'academic_year',
                $request->academic_year
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Class Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('class')) {

            $query->where(
                'class',
                $request->class
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Section Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('section')) {

            $query->where(
                'section',
                $request->section
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Gender Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('gender')) {

            $query->where(
                'gender',
                $request->gender
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Student Records
        |--------------------------------------------------------------------------
        */

        $students = $query
    ->orderBy('class')
    ->orderBy('section')
    ->orderBy('roll_number')
    ->paginate(15)
    ->withQueryString();

foreach ($students as $student) {

    $student->attendanceRecords = Attendance::where(
        'student_id',
        $student->id
    )
    ->orderBy('attendance_date')
    ->get();

    $student->libraryRecords = BookIssue::with('book')
        ->where('student_id', $student->id)
        ->orderBy('issue_date')
        ->get();

}




        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->pluck('class');


        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');


        /*
        |--------------------------------------------------------------------------
        | Report Statistics
        |--------------------------------------------------------------------------
        */

        $totalStudents = (clone $query)->count();

        $activeStudents = (clone $query)
            ->where('status', 'active')
            ->count();

        $maleStudents = (clone $query)
            ->where('gender', 'Male')
            ->count();

        $femaleStudents = (clone $query)
            ->where('gender', 'Female')
            ->count();


        return view('allReports.studentReport.index', compact(
            'students',
            'academicYears',
            'classes',
            'sections',
            'totalStudents',
            'activeStudents',
            'maleStudents',
            'femaleStudents'
        ));
    }

    public function pdf(Request $request)
{
    $query = Student::query();

    // Student Search
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('aadhar_card_no', 'like', "%{$search}%");

        });
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

    // Gender
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    // Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $students = $query
        ->orderBy('class')
        ->orderBy('section')
        ->orderBy('roll_number')
        ->get();

    // Attendance + Library records
    foreach ($students as $student) {

        $student->attendanceRecords = Attendance::where(
            'student_id',
            $student->id
        )
        ->orderBy('attendance_date')
        ->get();

        $student->libraryRecords = BookIssue::with('book')
            ->where('student_id', $student->id)
            ->orderBy('issue_date')
            ->get();
    }

    return Pdf::loadView(
        'allReports.studentReport.pdf',
        compact('students')
    )->download('student-report.pdf');
}
}