<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasteReportController extends Controller
{
    /**
     * Caste / Category Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter Values
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->where('status', 'active')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $classes = Student::query()
            ->where('status', 'active')
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->pluck('class')
            ->sortBy(function ($class) {
                return $this->classOrder()[$class] ?? 999;
            })
            ->values();

        $sections = Student::query()
            ->where('status', 'active')
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $castes = Student::query()
            ->where('status', 'active')
            ->whereNotNull('caste')
            ->where('caste', '!=', '')
            ->distinct()
            ->orderBy('caste')
            ->pluck('caste');


        /*
        |--------------------------------------------------------------------------
        | Main Report Query
        |--------------------------------------------------------------------------
        */

        $query = Student::query()
            ->select(
                'caste',
                'class',

                DB::raw("
                    SUM(
                        CASE
                            WHEN LOWER(TRIM(gender)) IN (
                                'male',
                                'm',
                                'boy',
                                'boys'
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) AS boys
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN LOWER(TRIM(gender)) IN (
                                'female',
                                'f',
                                'girl',
                                'girls'
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) AS girls
                ")
            )
            ->where('status', 'active')
            ->whereNotNull('caste')
            ->where('caste', '!=', '')
            ->whereNotNull('class')
            ->where('class', '!=', '');


        /*
        |--------------------------------------------------------------------------
        | Apply Academic Year Filter
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
        | Apply Class Filter
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
        | Apply Section Filter
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
        | Apply Caste Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('caste')) {
            $query->where(
                'caste',
                $request->caste
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Group Report
        |--------------------------------------------------------------------------
        */

        $report = $query
            ->groupBy('caste', 'class')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Row Totals
        |--------------------------------------------------------------------------
        */

        $report->each(function ($row) {

            $row->boys = (int) $row->boys;
            $row->girls = (int) $row->girls;

            $row->total = $row->boys + $row->girls;
        });


        /*
        |--------------------------------------------------------------------------
        | Class Order
        |--------------------------------------------------------------------------
        */

        $classOrder = $this->classOrder();


        /*
        |--------------------------------------------------------------------------
        | Sort Classes
        |--------------------------------------------------------------------------
        */

        $report = $report
            ->sortBy(function ($row) use ($classOrder) {

                $class = trim($row->class);

                return $classOrder[$class] ?? 999;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Grand Totals
        |--------------------------------------------------------------------------
        */

        $grandBoys = $report->sum('boys');

        $grandGirls = $report->sum('girls');

        $grandTotal = $report->sum('total');


        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        */

        $schoolSetting = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        $hasFilters =
            $request->filled('academic_year') ||
            $request->filled('class') ||
            $request->filled('section') ||
            $request->filled('caste');


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.caste-reports.index',
            compact(
                'report',
                'grandBoys',
                'grandGirls',
                'grandTotal',
                'schoolSetting',
                'academicYears',
                'classes',
                'sections',
                'castes',
                'hasFilters'
            )
        );
    }


    /**
     * Class-wise Caste / Category Print Report
     */
    public function classWisePrint(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Main Query
        |--------------------------------------------------------------------------
        */

        $query = Student::query()
            ->select(
                'caste',
                'class',

                DB::raw("
                    SUM(
                        CASE
                            WHEN LOWER(TRIM(gender)) IN (
                                'male',
                                'm',
                                'boy',
                                'boys'
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) AS boys
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN LOWER(TRIM(gender)) IN (
                                'female',
                                'f',
                                'girl',
                                'girls'
                            )
                            THEN 1
                            ELSE 0
                        END
                    ) AS girls
                ")
            )
            ->where('status', 'active')
            ->whereNotNull('caste')
            ->where('caste', '!=', '')
            ->whereNotNull('class')
            ->where('class', '!=', '');


        /*
        |--------------------------------------------------------------------------
        | Apply Same Filters To Print
        |--------------------------------------------------------------------------
        */

        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        if ($request->filled('class')) {
            $query->where(
                'class',
                $request->class
            );
        }

        if ($request->filled('section')) {
            $query->where(
                'section',
                $request->section
            );
        }

        if ($request->filled('caste')) {
            $query->where(
                'caste',
                $request->caste
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Report
        |--------------------------------------------------------------------------
        */

        $report = $query
            ->groupBy('caste', 'class')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Totals
        |--------------------------------------------------------------------------
        */

        $report->each(function ($row) {

            $row->boys = (int) $row->boys;

            $row->girls = (int) $row->girls;

            $row->total = $row->boys + $row->girls;
        });


        /*
        |--------------------------------------------------------------------------
        | Sort By Class
        |--------------------------------------------------------------------------
        */

        $classOrder = $this->classOrder();

        $report = $report
            ->sortBy(function ($row) use ($classOrder) {

                return $classOrder[
                    trim($row->class)
                ] ?? 999;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | School Settings
        |--------------------------------------------------------------------------
        */

        $schoolSetting = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Academic Year
        |--------------------------------------------------------------------------
        */

        $academicYear = $request->academic_year;

        if (!$academicYear) {

            $academicYear = Student::query()
                ->where('status', 'active')
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderBy('academic_year', 'desc')
                ->value('academic_year');
        }


        /*
        |--------------------------------------------------------------------------
        | Send Data To Print View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.caste-reports.class-wise-print',
            compact(
                'report',
                'schoolSetting',
                'academicYear'
            )
        );
    }


    /**
     * Class Ordering
     */
    private function classOrder(): array
    {
        return [
            'Nursery' => 1,
            'LKG'     => 2,
            'UKG'     => 3,
            '1'       => 4,
            '2'       => 5,
            '3'       => 6,
            '4'       => 7,
            '5'       => 8,
            '6'       => 9,
            '7'       => 10,
            '8'       => 11,
            '9'       => 12,
            '10'      => 13,
            '11'      => 14,
            '12'      => 15,
        ];
    }
}