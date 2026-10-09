<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Class\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AgeReportController extends Controller
{
    /**
     * Age groups used in the report.
     */
    private function ageGroups(): array
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
     * Display Age Report.
     */
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('admin.age-reports.index', $data);
    }

    /**
     * Print Age Report.
     */
    public function print(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('admin.age-reports.print', $data);
    }

    /**
     * Build all report data.
     */
    private function buildReportData(Request $request): array
    {
        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year')
            ->map(function ($year) {
                return trim((string) $year);
            })
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Dynamic Classes
        |--------------------------------------------------------------------------
        |
        | Classes are primarily taken from Class Management.
        | If Class Management has no classes, student class values are used.
        |
        */

        $schoolClasses = SchoolClass::query()
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 'active')
                    ->orWhereNull('status');
            })
            ->orderBy('class_name')
            ->get();

        $classes = $schoolClasses
            ->pluck('class_name')
            ->map(function ($class) {
                return trim((string) $class);
            })
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Fallback Classes From Students
        |--------------------------------------------------------------------------
        */

        if ($classes->isEmpty()) {
            $classes = Student::query()
                ->whereNotNull('class')
                ->where('class', '!=', '')
                ->distinct()
                ->pluck('class')
                ->map(function ($class) {
                    return trim((string) $class);
                })
                ->filter()
                ->unique()
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Sort Classes
        |--------------------------------------------------------------------------
        |
        | Numeric classes first:
        |
        | 1, 2, 3 ... 12
        |
        | Then alphabetic:
        |
        | Nursery, LKG, UKG
        |
        */

        $classes = $this->sortClasses($classes);

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section')
            ->map(function ($section) {
                return trim((string) $section);
            })
            ->filter()
            ->unique()
            ->sort(function ($a, $b) {
                return strcasecmp($a, $b);
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Selected Filters
        |--------------------------------------------------------------------------
        */

        $selectedAcademicYear = $request->input('academic_year');
        $selectedClass = $request->input('class');
        $selectedSection = $request->input('section');
        $selectedGender = $request->input('gender');

        $ageAsOn = $request->input('age_as_on');

        if (!$ageAsOn) {
            $ageAsOn = now()->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Age As On Date
        |--------------------------------------------------------------------------
        */

        try {
            $asOnDate = Carbon::parse($ageAsOn);
            $ageAsOn = $asOnDate->format('Y-m-d');
        } catch (\Throwable $e) {
            $asOnDate = now();
            $ageAsOn = $asOnDate->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Students Query
        |--------------------------------------------------------------------------
        */

        $studentsQuery = Student::query()
            ->whereNotNull('date_of_birth')
            ->where('date_of_birth', '!=', '');

        /*
        |--------------------------------------------------------------------------
        | Academic Year Filter
        |--------------------------------------------------------------------------
        */

        if (
            $selectedAcademicYear !== null &&
            trim((string) $selectedAcademicYear) !== ''
        ) {
            $studentsQuery->where(
                'academic_year',
                trim((string) $selectedAcademicYear)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Class Filter
        |--------------------------------------------------------------------------
        |
        | Supports:
        |
        | 1
        | Class 1
        |
        | 10
        | Class 10
        |
        | Nursery
        | Class Nursery
        |
        */

        if (
            $selectedClass !== null &&
            trim((string) $selectedClass) !== ''
        ) {
            $classValue = trim((string) $selectedClass);

            /*
             * Remove "Class" prefix if it already exists.
             */
            $classNumber = preg_replace(
                '/^class\s*/i',
                '',
                $classValue
            );

            $classNumber = trim((string) $classNumber);

            $studentsQuery->where(function ($query) use (
                $classValue,
                $classNumber
            ) {
                $query->where('class', $classValue)
                    ->orWhere('class', 'Class ' . $classNumber);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Section Filter
        |--------------------------------------------------------------------------
        */

        if (
            $selectedSection !== null &&
            trim((string) $selectedSection) !== ''
        ) {
            $studentsQuery->where(
                'section',
                trim((string) $selectedSection)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Gender Filter
        |--------------------------------------------------------------------------
        */

        if (
            $selectedGender !== null &&
            trim((string) $selectedGender) !== ''
        ) {
            $gender = strtolower(
                trim((string) $selectedGender)
            );

            $studentsQuery->where(function ($query) use ($gender) {
                if (
                    in_array(
                        $gender,
                        ['male', 'm', 'boy', 'boys'],
                        true
                    )
                ) {
                    $query->whereRaw(
                        "LOWER(TRIM(gender)) IN ('male', 'm', 'boy', 'boys')"
                    );
                } elseif (
                    in_array(
                        $gender,
                        ['female', 'f', 'girl', 'girls'],
                        true
                    )
                ) {
                    $query->whereRaw(
                        "LOWER(TRIM(gender)) IN ('female', 'f', 'girl', 'girls')"
                    );
                } else {
                    $query->whereRaw(
                        'LOWER(TRIM(gender)) = ?',
                        [$gender]
                    );
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Get Students
        |--------------------------------------------------------------------------
        */

        $students = $studentsQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate Age
        |--------------------------------------------------------------------------
        */

        $students = $students
            ->map(function ($student) use ($asOnDate) {
                try {
                    $dob = Carbon::parse($student->date_of_birth);

                    /*
                     * Ignore future DOB.
                     */
                    if ($dob->greaterThan($asOnDate)) {
                        $student->calculated_age = null;
                        $student->age_group = null;

                        return $student;
                    }

                    /*
                     * Calculate age as of selected date.
                     */
                    $age = $dob->diffInYears($asOnDate);

                    $student->calculated_age = $age;
                    $student->age_group = $this->getAgeGroup($age);
                } catch (\Throwable $e) {
                    $student->calculated_age = null;
                    $student->age_group = null;
                }

                return $student;
            })
            ->filter(function ($student) {
                return $student->age_group !== null;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Build Report
        |--------------------------------------------------------------------------
        */

        $report = collect();

        foreach ($this->ageGroups() as $ageGroup) {
            foreach ($classes as $class) {
                $matchingStudents = $students->filter(
                    function ($student) use ($ageGroup, $class) {
                        return $student->age_group === $ageGroup
                            && $this->classesMatch(
                                $student->class,
                                $class
                            );
                    }
                );

                $boys = $matchingStudents
                    ->filter(function ($student) {
                        return $this->isMale($student->gender);
                    })
                    ->count();

                $girls = $matchingStudents
                    ->filter(function ($student) {
                        return $this->isFemale($student->gender);
                    })
                    ->count();

                $total = $matchingStudents->count();

                $report->push(
                    (object) [
                        'age_group' => $ageGroup,
                        'class' => $class,
                        'boys' => $boys,
                        'girls' => $girls,
                        'total' => $total,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Overall Totals
        |--------------------------------------------------------------------------
        */

        $totalStudents = $students->count();

        $maleStudents = $students
            ->filter(function ($student) {
                return $this->isMale($student->gender);
            })
            ->count();

        $femaleStudents = $students
            ->filter(function ($student) {
                return $this->isFemale($student->gender);
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Return Data To Blade
        |--------------------------------------------------------------------------
        */

        return [
            'academicYears' => $academicYears,

            'classes' => $classes,

            'sections' => $sections,

            'ageAsOn' => $ageAsOn,

            'report' => $report,

            'totalStudents' => $totalStudents,

            'maleStudents' => $maleStudents,

            'femaleStudents' => $femaleStudents,

            'ageGroups' => $this->ageGroups(),

            'selectedAcademicYear' => $selectedAcademicYear,

            'selectedClass' => $selectedClass,

            'selectedSection' => $selectedSection,

            'selectedGender' => $selectedGender,
        ];
    }

    /**
     * Convert age into report age group.
     */
    private function getAgeGroup(int $age): ?string
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
     * Check whether two class values represent the same class.
     *
     * Examples:
     *
     * 1       = Class 1
     * 10      = Class 10
     * Nursery = Nursery
     */
    private function classesMatch(
        $studentClass,
        $reportClass
    ): bool {
        $studentClass = trim((string) $studentClass);
        $reportClass = trim((string) $reportClass);

        /*
         * Exact match.
         */
        if ($studentClass === $reportClass) {
            return true;
        }

        /*
         * Remove "Class" prefix.
         */
        $studentNormalized = preg_replace(
            '/^class\s*/i',
            '',
            $studentClass
        );

        $reportNormalized = preg_replace(
            '/^class\s*/i',
            '',
            $reportClass
        );

        return strtolower(
            trim((string) $studentNormalized)
        ) === strtolower(
            trim((string) $reportNormalized)
        );
    }

    /**
     * Check male gender.
     */
    private function isMale($gender): bool
    {
        return in_array(
            strtolower(trim((string) $gender)),
            [
                'male',
                'm',
                'boy',
                'boys',
            ],
            true
        );
    }

    /**
     * Check female gender.
     */
    private function isFemale($gender): bool
    {
        return in_array(
            strtolower(trim((string) $gender)),
            [
                'female',
                'f',
                'girl',
                'girls',
            ],
            true
        );
    }

    /**
     * Sort class names naturally.
     *
     * Numeric classes:
     * 1, 2, 3 ... 12
     *
     * Then alphabetic classes:
     * Nursery, LKG, UKG
     */
    private function sortClasses(
        Collection $classes
    ): Collection {
        return $classes
            ->sort(function ($a, $b) {
                $aValue = trim((string) $a);
                $bValue = trim((string) $b);

                /*
                 * Remove "Class" prefix before sorting.
                 */
                $aNumber = preg_replace(
                    '/^class\s*/i',
                    '',
                    $aValue
                );

                $bNumber = preg_replace(
                    '/^class\s*/i',
                    '',
                    $bValue
                );

                $aNumber = trim((string) $aNumber);
                $bNumber = trim((string) $bNumber);

                $aIsNumeric = is_numeric($aNumber);
                $bIsNumeric = is_numeric($bNumber);

                /*
                 * Both numeric.
                 */
                if ($aIsNumeric && $bIsNumeric) {
                    return (int) $aNumber <=> (int) $bNumber;
                }

                /*
                 * Numeric comes before alphabetic.
                 */
                if ($aIsNumeric && !$bIsNumeric) {
                    return -1;
                }

                if (!$aIsNumeric && $bIsNumeric) {
                    return 1;
                }

                /*
                 * Alphabetic sorting.
                 */
                return strcasecmp(
                    $aValue,
                    $bValue
                );
            })
            ->values();
    }
}
