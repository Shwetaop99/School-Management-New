<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Class\SchoolClass;

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

        $classes = \App\Models\Class\SchoolClass::query()
    ->whereNotNull('class_name')
    ->where('class_name', '!=', '')
    ->select('class_name')
    ->distinct()
    ->pluck('class_name')
    ->map(fn ($name) => trim($name))
    ->filter()
    ->unique()
    ->sort(function ($a, $b) {
        $a = trim($a);
        $b = trim($b);

        if (is_numeric($a) && is_numeric($b)) {
            return (int) $a <=> (int) $b;
        }

        return strcasecmp($a, $b);
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

        return view('admin.caste-reports.index', compact(
    'report',
    'classes',
    'academicYears',
    'sections',
    'castes',
    'schoolSetting',
    'grandBoys',
    'grandGirls',
    'grandTotal',
    'hasFilters'
));
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
 * Dedicated A4 print page.
 */
public function print(Request $request)
{
    $data = $this->getCasteReportData($request);

    return view('admin.caste-reports.print', $data);
}


/**
 * Download the actual PDF.
 */
public function pdfDownload(Request $request)
{
    $data = $this->getCasteReportData($request);

    return \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'admin.caste-reports.pdf-document',
        $data
    )->setPaper('a4', 'landscape')
     ->download('caste-category-report.pdf');
}

/**
 * Build filtered report data for print and PDF.
 */
private function getCasteReportData(Request $request): array
{
    $query = Student::query()
        ->select(
            'caste',
            'class',
            DB::raw("
                SUM(CASE
                    WHEN LOWER(TRIM(gender)) IN ('male', 'm', 'boy', 'boys')
                    THEN 1 ELSE 0
                END) AS boys
            "),
            DB::raw("
                SUM(CASE
                    WHEN LOWER(TRIM(gender)) IN ('female', 'f', 'girl', 'girls')
                    THEN 1 ELSE 0
                END) AS girls
            ")
        )
        ->where('status', 'active')
        ->whereNotNull('caste')
        ->where('caste', '!=', '')
        ->whereNotNull('class')
        ->where('class', '!=', '');

    foreach (['academic_year', 'class', 'section', 'caste'] as $filter) {
        if ($request->filled($filter)) {
            $query->where($filter, $request->input($filter));
        }
    }

    $report = $query->groupBy('caste', 'class')->get();

    $report->each(function ($row) {
        $row->boys = (int) $row->boys;
        $row->girls = (int) $row->girls;
        $row->total = $row->boys + $row->girls;
    });

    $classOrder = $this->classOrder();

    $report = $report->sortBy(function ($row) use ($classOrder) {
        return $classOrder[trim($row->class)] ?? 999;
    })->values();

    return [
        'report' => $report,
        'schoolSetting' => SchoolSetting::first(),
        'academicYear' => $request->academic_year
            ?: Student::where('status', 'active')
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year'),
        'selectedFilters' => $request->only([
            'academic_year', 'class', 'section', 'caste',
        ]),
        'grandBoys' => $report->sum('boys'),
        'grandGirls' => $report->sum('girls'),
        'grandTotal' => $report->sum('total'),
    ];
}

    
   /**
 * Fetch class order dynamically from Class Management.
 */
private function classOrder(): array
{
    return SchoolClass::query()
        ->whereNotNull('class_name')
        ->where('class_name', '!=', '')
        ->select('class_name')
        ->distinct()
        ->get()
        ->pluck('class_name')
        ->mapWithKeys(function ($className, $index) {
            return [trim($className) => $index + 1];
        })
        ->all();
}
}