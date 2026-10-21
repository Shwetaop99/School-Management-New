<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgeReportController extends Controller
{
    /**
     * Classes used in Age Report.
     */
    private function getClasses(): array
    {
        return [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];
    }

    /**
     * Age groups used in Format 1.
     */
    private function getAgeGroups(): array
    {
        return [
            'Below 5',
            '5–6',
            '7–8',
            '9–10',
            '11–12',
            '13–14',
            '15–16',
            '17+',
        ];
    }

    /**
     * Get age group from completed age.
     */
    private function getAgeGroup(int $age): string
    {
        if ($age < 5) {
            return 'Below 5';
        }

        if ($age <= 6) {
            return '5–6';
        }

        if ($age <= 8) {
            return '7–8';
        }

        if ($age <= 10) {
            return '9–10';
        }

        if ($age <= 12) {
            return '11–12';
        }

        if ($age <= 14) {
            return '13–14';
        }

        if ($age <= 16) {
            return '15–16';
        }

        return '17+';
    }

    /**
     * Normalize gender.
     */
    private function getGenderType(?string $gender): ?string
    {
        $gender = strtolower(trim($gender ?? ''));

        if (in_array($gender, [
            'male',
            'm',
            'boy',
            'boys',
        ], true)) {
            return 'boys';
        }

        if (in_array($gender, [
            'female',
            'f',
            'girl',
            'girls',
        ], true)) {
            return 'girls';
        }

        return null;
    }

    /**
     * Build the Format 1 report.
     */
    private function buildReport(
        Request $request,
        Carbon $ageAsOn
    ): array {
        $classes = $this->getClasses();
        $ageGroups = $this->getAgeGroups();

        /*
        |--------------------------------------------------------------------------
        | Base Student Query
        |--------------------------------------------------------------------------
        */

        $query = Student::query()
            ->where('status', 'active')
            ->whereNotNull('date_of_birth')
            ->whereDate(
                'date_of_birth',
                '<=',
                $ageAsOn->format('Y-m-d')
            );

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

            if ($request->gender === 'Male') {

                $query->whereRaw("
                    LOWER(TRIM(gender)) IN (
                        'male',
                        'm',
                        'boy',
                        'boys'
                    )
                ");

            } elseif ($request->gender === 'Female') {

                $query->whereRaw("
                    LOWER(TRIM(gender)) IN (
                        'female',
                        'f',
                        'girl',
                        'girls'
                    )
                ");
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get Students
        |--------------------------------------------------------------------------
        */

        $students = $query
            ->orderBy('class')
            ->orderBy('section')
            ->orderBy('first_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Initialize Report
        |--------------------------------------------------------------------------
        |
        | Structure:
        |
        | Age Group
        |     Class
        |         Boys
        |         Girls
        |         Total
        |
        |--------------------------------------------------------------------------
        */

        $reportData = [];

        foreach ($ageGroups as $ageGroup) {

            foreach ($classes as $class) {

                $reportData[$ageGroup][$class] = [
                    'boys'  => 0,
                    'girls' => 0,
                    'total' => 0,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Age And Populate Report
        |--------------------------------------------------------------------------
        */

        foreach ($students as $student) {

            if (!$student->date_of_birth) {
                continue;
            }

            try {

                $dob = Carbon::parse($student->date_of_birth);

                /*
                |--------------------------------------------------------------------------
                | Exact completed age as on selected date
                |--------------------------------------------------------------------------
                */

                $age = $dob->diffInYears($ageAsOn);

            } catch (\Throwable $e) {

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Age Group
            |--------------------------------------------------------------------------
            */

            $ageGroup = $this->getAgeGroup($age);

            /*
            |--------------------------------------------------------------------------
            | Student Class
            |--------------------------------------------------------------------------
            */

            $class = trim((string) $student->class);

            /*
            |--------------------------------------------------------------------------
            | Ignore classes outside the report classes.
            |--------------------------------------------------------------------------
            */

            if (!in_array($class, $classes, true)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Gender
            |--------------------------------------------------------------------------
            */

            $genderType = $this->getGenderType(
                $student->gender
            );

            if ($genderType === 'boys') {

                $reportData[$ageGroup][$class]['boys']++;

            } elseif ($genderType === 'girls') {

                $reportData[$ageGroup][$class]['girls']++;
            }

            /*
            |--------------------------------------------------------------------------
            | Total
            |--------------------------------------------------------------------------
            */

            $reportData[$ageGroup][$class]['total'] =
                $reportData[$ageGroup][$class]['boys']
                +
                $reportData[$ageGroup][$class]['girls'];
        }

        /*
        |--------------------------------------------------------------------------
        | Convert Array Into Collection
        |--------------------------------------------------------------------------
        |
        | Blade expects:
        |
        | $row->age_group
        | $row->class
        | $row->boys
        | $row->girls
        | $row->total
        |
        |--------------------------------------------------------------------------
        */

        $report = collect();

        foreach ($ageGroups as $ageGroup) {

            foreach ($classes as $class) {

                $report->push(
                    (object) [
                        'age_group' => $ageGroup,
                        'class'     => $class,
                        'boys'      => $reportData[$ageGroup][$class]['boys'],
                        'girls'     => $reportData[$ageGroup][$class]['girls'],
                        'total'     => $reportData[$ageGroup][$class]['total'],
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalStudents = $students->count();

        $maleStudents = $students
            ->filter(function ($student) {

                return $this->getGenderType(
                    $student->gender
                ) === 'boys';

            })
            ->count();

        $femaleStudents = $students
            ->filter(function ($student) {

                return $this->getGenderType(
                    $student->gender
                ) === 'girls';

            })
            ->count();

        return [
            'report'         => $report,
            'students'       => $students,
            'totalStudents'  => $totalStudents,
            'maleStudents'   => $maleStudents,
            'femaleStudents' => $femaleStudents,
            'classes'        => $classes,
            'ageGroups'      => $ageGroups,
        ];
    }

    /**
     * Display Age Report.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Age As On Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('age_as_on')) {

            try {

                $ageAsOn = Carbon::parse(
                    $request->age_as_on
                )->startOfDay();

            } catch (\Throwable $e) {

                $ageAsOn = now()->startOfDay();
            }

        } else {

            $ageAsOn = now()->startOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Build Report
        |--------------------------------------------------------------------------
        */

        $data = $this->buildReport(
            $request,
            $ageAsOn
        );

        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->where('status', 'active')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];

        /*
        |--------------------------------------------------------------------------
        | School Settings
        |--------------------------------------------------------------------------
        */

        $schoolSetting = SchoolSetting::first();

        /*
        |--------------------------------------------------------------------------
        | Return Age Report View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.age-reports.index',
            [
                'report'         => $data['report'],
                'students'       => $data['students'],
                'ageAsOn'        => $ageAsOn,
                'academicYears'  => $academicYears,
                'classes'        => $data['classes'],
                'sections'       => $sections,
                'ageGroups'      => $data['ageGroups'],
                'totalStudents'  => $data['totalStudents'],
                'maleStudents'   => $data['maleStudents'],
                'femaleStudents' => $data['femaleStudents'],
                'schoolSetting'  => $schoolSetting,
            ]
        );
    }

    /**
     * Print Age Report.
     */
    public function print(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Age As On Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('age_as_on')) {

            try {

                $ageAsOn = Carbon::parse(
                    $request->age_as_on
                )->startOfDay();

            } catch (\Throwable $e) {

                $ageAsOn = now()->startOfDay();
            }

        } else {

            $ageAsOn = now()->startOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Build Same Report With Same Filters
        |--------------------------------------------------------------------------
        */

        $data = $this->buildReport(
            $request,
            $ageAsOn
        );

        /*
        |--------------------------------------------------------------------------
        | School Settings
        |--------------------------------------------------------------------------
        */

        $schoolSetting = SchoolSetting::first();

        /*
        |--------------------------------------------------------------------------
        | Report Date
        |--------------------------------------------------------------------------
        */

        $reportDate = $ageAsOn->format('d-m-Y');

        /*
        |--------------------------------------------------------------------------
        | Return Print View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.age-reports.print',
            [
                'report'         => $data['report'],
                'students'       => $data['students'],
                'ageAsOn'        => $ageAsOn,
                'classes'        => $data['classes'],
                'ageGroups'      => $data['ageGroups'],
                'totalStudents'  => $data['totalStudents'],
                'maleStudents'   => $data['maleStudents'],
                'femaleStudents' => $data['femaleStudents'],
                'schoolSetting'  => $schoolSetting,
                'reportDate'     => $reportDate,
            ]
        );
    }
}