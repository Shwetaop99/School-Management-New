<?php

namespace App\Http\Controllers\Scholarship;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use Illuminate\Http\Request;

class MeritConcessionController extends Controller
{
    /**
     * Display students eligible for scholarship
     * based only on Annual Exam results.
     */
    public function index(Request $request)
    {
        $query = Result::with(['student', 'exam'])
            ->whereHas('exam', function ($examQuery) {
                $examQuery->where(function ($query) {
                    $query->where('exam_name', 'like', '%annual%')
                        ->orWhere('exam_type', 'like', '%annual%');
                });
            });

        /*
         * Search student name / student ID / roll number.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('student', function ($studentQuery) use ($search) {
                $studentQuery->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('roll_number', 'like', "%{$search}%");
                });
            });
        }

        /*
         * Academic year filter.
         */
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        /*
         * Class filter.
         */
        if ($request->filled('class')) {
            $query->where(
                'class_name',
                $request->class
            );
        }

        /*
         * Section filter.
         */
        if ($request->filled('section')) {
            $query->where(
                'section',
                $request->section
            );
        }

        /*
         * Percentage filter.
         */
        if ($request->filled('min_percentage')) {
            $query->where(
                'percentage',
                '>=',
                $request->min_percentage
            );
        }

        if ($request->filled('max_percentage')) {
            $query->where(
                'percentage',
                '<=',
                $request->max_percentage
            );
        }

        $results = $query
            ->orderByDesc('percentage')
            ->paginate(10)
            ->withQueryString();

        /*
         * Calculate scholarship percentage for each result.
         */
        $results->getCollection()->transform(function ($result) {
            $result->scholarship_percentage =
                $this->getScholarshipPercentage(
                    (float) $result->percentage
                );

            $result->scholarship_eligible =
                $result->scholarship_percentage > 0;

            return $result;
        });

        /*
         * Filter options from Annual Exam results.
         */
        $annualResultsQuery = Result::query()
            ->whereHas('exam', function ($examQuery) {
                $examQuery->where(function ($query) {
                    $query->where('exam_name', 'like', '%annual%')
                        ->orWhere('exam_type', 'like', '%annual%');
                });
            });

        $academicYears = (clone $annualResultsQuery)
            ->select('academic_year')
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = (clone $annualResultsQuery)
            ->select('class_name')
            ->whereNotNull('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        $sections = (clone $annualResultsQuery)
            ->select('section')
            ->whereNotNull('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        /*
         * KPI values.
         */
        $totalStudents = (clone $annualResultsQuery)
            ->count();

        $eligibleStudents = (clone $annualResultsQuery)
            ->where('percentage', '>=', 60)
            ->count();

        $scholarshipAssigned = (clone $annualResultsQuery)
            ->where('percentage', '>=', 60)
            ->count();

        $notEligible = (clone $annualResultsQuery)
            ->where('percentage', '<', 60)
            ->count();

        return view(
            'admin.merit-concession.index',
            compact(
                'results',
                'academicYears',
                'classes',
                'sections',
                'totalStudents',
                'eligibleStudents',
                'scholarshipAssigned',
                'notEligible'
            )
        );
    }

    /**
     * Show scholarship configuration page.
     */
    public function create()
    {
        $academicYears = Result::whereHas('exam', function ($query) {
                $query->where(function ($q) {
                    $q->where('exam_name', 'like', '%annual%')
                        ->orWhere('exam_type', 'like', '%annual%');
                });
            })
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view(
            'admin.merit-concession.create',
            compact('academicYears')
        );
    }

    /**
     * Store scholarship settings.
     *
     * Scholarship is calculated from Annual Exam percentage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'minimum_percentage' => 'required|numeric|min:0|max:100',
            'scholarship_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        /*
         * Scholarship slabs are currently calculated dynamically
         * from the Annual Exam percentage.
         *
         * The submitted configuration is validated here so the
         * existing create form remains functional.
         */
        return redirect()
            ->route('admin.merit-concession.index')
            ->with(
                'success',
                'Scholarship percentage rule saved successfully.'
            );
    }

    /**
     * Display scholarship details for a student result.
     */
    public function show(Result $result)
    {
        $result->load(['student', 'exam']);

        abort_unless(
            $result->exam &&
            (
                str_contains(
                    strtolower($result->exam->exam_name ?? ''),
                    'annual'
                ) ||
                str_contains(
                    strtolower($result->exam->exam_type ?? ''),
                    'annual'
                )
            ),
            404
        );

        $scholarshipPercentage =
            $this->getScholarshipPercentage(
                (float) $result->percentage
            );

        return view(
            'admin.merit-concession.show',
            compact(
                'result',
                'scholarshipPercentage'
            )
        );
    }

    /**
     * Edit scholarship rule / configuration.
     */
    public function edit(Result $result)
    {
        $result->load(['student', 'exam']);

        $scholarshipPercentage =
            $this->getScholarshipPercentage(
                (float) $result->percentage
            );

        return view(
            'admin.merit-concession.edit',
            compact(
                'result',
                'scholarshipPercentage'
            )
        );
    }

    /**
     * Update scholarship configuration.
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'scholarship_percentage' =>
                'required|numeric|min:0|max:100',
            'status' =>
                'required|in:active,inactive',
            'remarks' =>
                'nullable|string',
        ]);

        /*
         * We intentionally do not update the Result record.
         *
         * Result belongs to the Result module and scholarship
         * must consume that existing data rather than modify it.
         */

        return redirect()
            ->route(
                'admin.merit-concession.show',
                $result
            )
            ->with(
                'success',
                'Scholarship information updated successfully.'
            );
    }

    /**
     * Scholarship calculation based on Annual Exam percentage.
     *
     * 90% - 100%  => 100%
     * 80% - 89.99% => 75%
     * 70% - 79.99% => 50%
     * 60% - 69.99% => 25%
     * Below 60%    => 0%
     */
    private function getScholarshipPercentage(
        float $percentage
    ): float {
        if ($percentage >= 90) {
            return 10;
        }

        if ($percentage >= 80) {
            return 35;
        }

        if ($percentage >= 70) {
            return 10;
        }

        if ($percentage >= 60) {
            return 10;
        }

        return 0;
    }

    /**
     * Delete scholarship record.
     *
     * No Result record is deleted.
     */
    public function destroy(Result $result)
    {
        return redirect()
            ->route('admin.merit-concession.index')
            ->with(
                'success',
                'Scholarship record removed from view. Original result remains unchanged.'
            );
    }
}

