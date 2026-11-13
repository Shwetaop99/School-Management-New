<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Class\Subject;
use Illuminate\Http\Request;

class ClassReportController extends Controller
{
    public function index()
    {
        return view('allReports.classReport.index');
    }

    public function classes(Request $request)
    {
        $query = SchoolClass::query();

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");
            });
        }

        $classes = $query
            ->withCount('subjects')
            ->orderBy('class_name')
            ->orderBy('section')
            ->paginate(15)
            ->withQueryString();

        $academicYears = SchoolClass::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view(
            'allReports.classReport.class',
            compact('classes', 'academicYears')
        );
    }

    public function subjects(Request $request)
    {
        $classes = SchoolClass::query()
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        $query = Subject::with('schoolClass');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                    ->orWhere('subject_code', 'like', "%{$search}%");
            });
        }

        $subjects = $query
            ->orderBy('class_id')
            ->orderBy('subject_name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'allReports.classReport.subject',
            compact('subjects', 'classes')
        );
    }
}
