<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Class\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AgeReportController extends Controller
{
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

    public function index(Request $request)
    {
        $data = $this->buildReportData($request);
        $data['schoolSetting'] = $this->getSchoolSetting();

        return view('admin.age-reports.index', $data);
    }

    public function print(Request $request)
{
    $data = $this->buildReportData($request);

    $data['schoolSetting'] = $this->getSchoolSetting();

    return view('admin.age-reports.print', $data);
}

    private function buildReportData(Request $request): array
    {
        /*
        |--------------------------------------------------------------------------
        | Selected filters
        |--------------------------------------------------------------------------
        */

        $selectedAcademicYear = trim((string) $request->input('academic_year', ''));
        $selectedClass = trim((string) $request->input('class', ''));
        $selectedSection = trim((string) $request->input('section', ''));
        $selectedGender = trim((string) $request->input('gender', ''));

        try {
            $asOnDate = $request->filled('age_as_on')
                ? Carbon::parse($request->input('age_as_on'))->startOfDay()
                : Carbon::today();

            $ageAsOn = $asOnDate->format('Y-m-d');
        } catch (\Throwable $e) {
            $asOnDate = Carbon::today();
            $ageAsOn = $asOnDate->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Academic year options
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year')
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Class options
        |--------------------------------------------------------------------------
        */

        $classes = collect();

        if (Schema::hasTable('school_classes')) {
            try {
                $classes = SchoolClass::query()
                    ->where(function ($query) {
                        $query->where('status', 1)
                            ->orWhere('status', 'active')
                            ->orWhereNull('status');
                    })
                    ->orderBy('class_name')
                    ->pluck('class_name')
                    ->map(fn ($value) => trim((string) $value))
                    ->filter()
                    ->unique()
                    ->values();
            } catch (\Throwable $e) {
                $classes = collect();
            }
        }

        if ($classes->isEmpty()) {
            $classes = Student::query()
                ->whereNotNull('class')
                ->where('class', '!=', '')
                ->distinct()
                ->pluck('class')
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->values();
        }

        $classes = $this->sortClasses($classes);

        /*
        |--------------------------------------------------------------------------
        | Section options
        |--------------------------------------------------------------------------
        */

        $sections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section')
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->sort(fn ($a, $b) => strcasecmp($a, $b))
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Filter students
        |--------------------------------------------------------------------------
        */

        $studentsQuery = Student::query()
            ->whereNotNull('date_of_birth')
            ->where('date_of_birth', '!=', '');

        if ($selectedAcademicYear !== '') {
            $studentsQuery->where(
                'academic_year',
                $selectedAcademicYear
            );
        }

        if ($selectedSection !== '') {
            $studentsQuery->where(
                'section',
                $selectedSection
            );
        }

        if ($selectedGender !== '') {
            $gender = strtolower($selectedGender);

            if (in_array($gender, ['male', 'm', 'boy', 'boys'], true)) {
                $studentsQuery->whereRaw(
                    "LOWER(TRIM(gender)) IN ('male','m','boy','boys')"
                );
            } elseif (in_array($gender, ['female', 'f', 'girl', 'girls'], true)) {
                $studentsQuery->whereRaw(
                    "LOWER(TRIM(gender)) IN ('female','f','girl','girls')"
                );
            } else {
                $studentsQuery->whereRaw(
                    'LOWER(TRIM(gender)) = ?',
                    [$gender]
                );
            }
        }

        /*
         * Apply the class filter to the retrieved collection.
         * This supports "1" and "Class 1" even when the database
         * uses different formats for class values.
         */

        $students = $studentsQuery->get();

        if ($selectedClass !== '') {
            $students = $students->filter(function ($student) use ($selectedClass) {
                return $this->classesMatch(
                    $student->class,
                    $selectedClass
                );
            })->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate ages and remove future/invalid DOB values
        |--------------------------------------------------------------------------
        */

        $students = $students->map(function ($student) use ($asOnDate) {
            try {
                $dob = Carbon::parse($student->date_of_birth)->startOfDay();

                if ($dob->greaterThan($asOnDate)) {
                    $student->calculated_age = null;
                    $student->age_group = null;

                    return $student;
                }

                $age = (int) $dob->diffInYears($asOnDate);

                $student->calculated_age = $age;
                $student->age_group = $this->getAgeGroup($age);
            } catch (\Throwable $e) {
                $student->calculated_age = null;
                $student->age_group = null;
            }

            return $student;
        })->filter(function ($student) {
            return $student->age_group !== null;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | Build report matrix
        |--------------------------------------------------------------------------
        */

        $report = collect();

        foreach ($this->ageGroups() as $ageGroup) {
            foreach ($classes as $class) {
                $matchingStudents = $students->filter(function ($student) use (
                    $ageGroup,
                    $class
                ) {
                    return $student->age_group === $ageGroup
                        && $this->classesMatch($student->class, $class);
                });

                $boys = $matchingStudents->filter(
                    fn ($student) => $this->isMale($student->gender)
                )->count();

                $girls = $matchingStudents->filter(
                    fn ($student) => $this->isFemale($student->gender)
                )->count();

                $report->push((object) [
                    'age_group' => $ageGroup,
                    'class' => $class,
                    'boys' => $boys,
                    'girls' => $girls,
                    'total' => $matchingStudents->count(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Summary totals from the same filtered student collection
        |--------------------------------------------------------------------------
        */

        $totalStudents = $students->count();

        $maleStudents = $students->filter(
            fn ($student) => $this->isMale($student->gender)
        )->count();

        $femaleStudents = $students->filter(
            fn ($student) => $this->isFemale($student->gender)
        )->count();

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

    private function getAgeGroup(int $age): string
    {
        if ($age < 5) return 'Below 5';
        if ($age <= 6) return '5–6';
        if ($age <= 8) return '7–8';
        if ($age <= 10) return '9–10';
        if ($age <= 12) return '11–12';
        if ($age <= 14) return '13–14';
        if ($age <= 16) return '15–16';

        return '17+';
    }

    private function classesMatch($studentClass, $reportClass): bool
    {
        $normalize = function ($value) {
            $value = trim((string) $value);
            $value = preg_replace('/^class\s*/i', '', $value);

            return strtolower(trim((string) $value));
        };

        return $normalize($studentClass) === $normalize($reportClass);
    }

    private function isMale($gender): bool
    {
        return in_array(
            strtolower(trim((string) $gender)),
            ['male', 'm', 'boy', 'boys'],
            true
        );
    }

    private function isFemale($gender): bool
    {
        return in_array(
            strtolower(trim((string) $gender)),
            ['female', 'f', 'girl', 'girls'],
            true
        );
    }

    private function sortClasses(Collection $classes): Collection
    {
        return $classes->sort(function ($a, $b) {
            $normalize = fn ($value) => trim(
                (string) preg_replace('/^class\s*/i', '', (string) $value)
            );

            $aValue = $normalize($a);
            $bValue = $normalize($b);

            $aNumeric = is_numeric($aValue);
            $bNumeric = is_numeric($bValue);

            if ($aNumeric && $bNumeric) {
                return (float) $aValue <=> (float) $bValue;
            }

            if ($aNumeric !== $bNumeric) {
                return $aNumeric ? -1 : 1;
            }

            return strcasecmp((string) $a, (string) $b);
        })->values();
    }

    private function getSchoolSetting(): ?object
    {
        if (!Schema::hasTable('school_settings')) {
            return null;
        }

        return DB::table('school_settings')->first();
    }
}
