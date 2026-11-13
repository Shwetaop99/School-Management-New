<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSetting;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CasteReportController extends Controller
{
    /**
     * Caste / Category Report Index
     */
    public function index(Request $request)
    {
        $academicYears = Student::query()
            ->where('status', 'active')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = SchoolClass::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->select('class_name')
            ->distinct()
            ->pluck('class_name')
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->sort(function ($a, $b) {
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

        $query = $this->buildReportQuery($request);

        $report = $query
            ->groupBy('caste', 'class')
            ->get();

        $report = $this->prepareReport($report);

        $grandBoys = (int) $report->sum('boys');
        $grandGirls = (int) $report->sum('girls');
        $grandTotal = (int) $report->sum('total');

        $schoolSetting = SchoolSetting::first();

        $hasFilters =
            $request->filled('academic_year') ||
            $request->filled('class') ||
            $request->filled('section') ||
            $request->filled('caste');

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
     * Existing class-wise print report.
     */
    public function classWisePrint(Request $request)
    {
        $query = $this->buildReportQuery($request);

        $report = $query
            ->groupBy('caste', 'class')
            ->get();

        $report = $this->prepareReport($report);

        $academicYear = $request->academic_year;

        if (!$academicYear) {
            $academicYear = Student::query()
                ->where('status', 'active')
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year');
        }

        $schoolSetting = SchoolSetting::first();

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
     * Open the standalone print page.
     */
    public function print(Request $request)
    {
        $data = $this->getCasteReportData($request);

        return view('admin.caste-reports.print', $data);
    }

    /**
     * Download PDF using the SAME Blade template as print.
     */
    public function pdfDownload(Request $request)
    {
        $data = $this->getCasteReportData($request);

        $pdf = Pdf::loadView(
            'admin.caste-reports.print',
            $data
        )
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download('caste-category-report.pdf');
    }

    /**
     * Build all report data for both Print and PDF.
     */
    private function getCasteReportData(Request $request): array
    {
        $query = $this->buildReportQuery($request);

        $report = $query
            ->groupBy('caste', 'class')
            ->get();

        $report = $this->prepareReport($report);

        /*
         * Get all available classes.
         */
        $classes = SchoolClass::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->select('class_name')
            ->distinct()
            ->pluck('class_name')
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->sort(function ($a, $b) {
                if (is_numeric($a) && is_numeric($b)) {
                    return (int) $a <=> (int) $b;
                }

                return strcasecmp($a, $b);
            })
            ->values();

        /*
         * If a class is selected, show only that class.
         */
        $selectedClass = trim((string) $request->input('class', ''));

        $classNames = $selectedClass !== ''
            ? $classes->filter(function ($name) use ($selectedClass) {
                return strcasecmp($name, $selectedClass) === 0;
            })->values()
            : $classes;

        /*
         * Calculate totals.
         */
        $grandBoys = (int) $report->sum('boys');
        $grandGirls = (int) $report->sum('girls');
        $grandTotal = (int) $report->sum('total');

        /*
         * Academic year.
         */
        $academicYear = $request->academic_year;

        if (!$academicYear) {
            $academicYear = Student::query()
                ->where('status', 'active')
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->orderByDesc('academic_year')
                ->value('academic_year') ?? 'All Academic Years';
        }

        /*
         * Selected filters.
         */
        $selectedFilters = $request->only([
            'academic_year',
            'class',
            'section',
            'caste',
        ]);

        $hasFilters =
            $request->filled('academic_year') ||
            $request->filled('class') ||
            $request->filled('section') ||
            $request->filled('caste');

        return [
            'report' => $report,
            'schoolSetting' => SchoolSetting::first(),
            'academicYear' => $academicYear,
            'selectedFilters' => $selectedFilters,
            'classNames' => $classNames,
            'classes' => $classes,
            'selectedClass' => $selectedClass,
            'grandBoys' => $grandBoys,
            'grandGirls' => $grandGirls,
            'grandTotal' => $grandTotal,
            'hasFilters' => $hasFilters,
        ];
    }

    /**
     * Common report query.
     */
    private function buildReportQuery(Request $request)
    {
        $query = Student::query()
            ->select(
                'caste',
                'class',
                DB::raw("
                    SUM(
                        CASE
                            WHEN LOWER(TRIM(gender)) IN (
                                'male', 'm', 'boy', 'boys'
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
                                'female', 'f', 'girl', 'girls'
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

        foreach ([
            'academic_year',
            'class',
            'section',
            'caste',
        ] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        return $query;
    }

    /**
     * Prepare report totals and class sorting.
     */
    private function prepareReport($report)
    {
        $report->each(function ($row) {
            $row->boys = (int) $row->boys;
            $row->girls = (int) $row->girls;
            $row->total = $row->boys + $row->girls;
        });

        $classOrder = $this->classOrder();

        return $report
            ->sortBy(function ($row) use ($classOrder) {
                return $classOrder[trim($row->class)] ?? 999;
            })
            ->values();
    }

    /**
     * Fetch class order dynamically.
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
