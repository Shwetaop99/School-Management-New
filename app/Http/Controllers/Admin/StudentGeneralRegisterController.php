<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentGeneralRegisterController extends Controller
{
    /**
     * Display Student General Register.
     */
    public function index(Request $request)
    {
        $query = Student::query()
            ->where('status', 'active');

        // Academic Year
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        // Current Class
        if ($request->filled('class')) {
            $query->where(
                'class',
                $request->class
            );
        }

        // Section
        if ($request->filled('section')) {
            $query->where(
                'section',
                $request->section
            );
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('register_no', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('aadhar_card_no', 'like', "%{$search}%");
            });
        }

        // Pagination: 10 students per page
        $students = $query
            ->orderByRaw('CAST(register_no AS UNSIGNED) ASC')
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        // Academic Years
        $academicYears = Student::query()
            ->where('status', 'active')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        // Classes
        $classes = Student::query()
            ->where('status', 'active')
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        // Sections
        $sections = Student::query()
            ->where('status', 'active')
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return view(
            'admin.student-general-register.index',
            compact(
                'students',
                'academicYears',
                'classes',
                'sections'
            )
        );
    }

    /**
     * Display one student.
     */
    public function show(Student $student)
    {
        return view(
            'admin.student-general-register.show',
            compact('student')
        );
    }

    /**
     * Print General Register.
     */
    public function print(Request $request)
    {
        $query = Student::query()
            ->where('status', 'active');

        // Academic Year
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        // Current Class
        if ($request->filled('class')) {
            $query->where(
                'class',
                $request->class
            );
        }

        // Section
        if ($request->filled('section')) {
            $query->where(
                'section',
                $request->section
            );
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('register_no', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('aadhar_card_no', 'like', "%{$search}%");
            });
        }

        // Print all students matching the filters
        $students = $query
            ->orderByRaw('CAST(register_no AS UNSIGNED) ASC')
            ->orderBy('first_name')
            ->get();

        return view(
            'admin.student-general-register.print',
            compact('students')
        );
    }
}
