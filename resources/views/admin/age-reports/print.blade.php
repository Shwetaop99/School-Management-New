@extends('layouts.app')

@section('title', 'Print Student Age Report')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | School Information
    |--------------------------------------------------------------------------
    */

    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $schoolLogo = $school->logo ?? null;

    if ($schoolLogo && !preg_match('~^(https?://|/)~i', $schoolLogo)) {
        $schoolLogo = asset('storage/' . ltrim($schoolLogo, '/'));
    }

    if (!$schoolLogo) {
        $schoolLogo = asset('images/gurukullogo.png');
    }

    $schoolAddress = collect([
        $school->address ?? null,
        $school->city ?? null,
        $school->district ?? null,
        $school->state ?? null,
        $school->pincode ?? null,
    ])->filter()->implode(', ');

    /*
    |--------------------------------------------------------------------------
    | Report Data
    |--------------------------------------------------------------------------
    */

    $reportRows = collect($report ?? []);

    $selectedClass = request('class', '');

    if ($selectedClass !== '') {
        $reportRows = $reportRows->filter(function ($row) use ($selectedClass) {
            return trim((string) data_get($row, 'class'))
                === trim($selectedClass);
        });
    }

    $reportRows = $reportRows->values();

    $academicYearValue = $academicYear
        ?? data_get($selectedFilters ?? [], 'academic_year')
        ?? request('academic_year')
        ?? 'All Academic Years';

    $ageAsOnValue = $ageAsOn
        ?? request('age_as_on')
        ?? 'Not Specified';

    /*
    |--------------------------------------------------------------------------
    | Calculate Student Totals
    |--------------------------------------------------------------------------
    */

    $getStudentCount = function ($row) {
        $total = data_get($row, 'total');

        if ($total !== null) {
            return (int) $total;
        }

        return (int) data_get($row, 'boys', 0)
            + (int) data_get($row, 'girls', 0);
    };

    $totalBoys = (int) $reportRows->sum(function ($row) {
        return (int) data_get($row, 'boys', 0);
    });

    $totalGirls = (int) $reportRows->sum(function ($row) {
        return (int) data_get($row, 'girls', 0);
    });

    $grandTotal = (int) $reportRows->sum(function ($row) use ($getStudentCount) {
        return $getStudentCount($row);
    });

    /*
    |--------------------------------------------------------------------------
    | Class-Wise Groups
    |--------------------------------------------------------------------------
    */

    $classGroups = $reportRows
        ->groupBy(function ($row) {
            return trim((string) data_get($row, 'class'))
                ?: 'Not Specified';
        })
        ->sortKeys();

    /*
    |--------------------------------------------------------------------------
    | Summary Report: Class + Total Students Only
    |--------------------------------------------------------------------------
    */

    $classSummary = $classGroups->map(function ($classRows, $className) use ($getStudentCount) {
        return [
            'class' => $className,
            'total_students' => (int) $classRows->sum(function ($row) use ($getStudentCount) {
                return $getStudentCount($row);
            }),
        ];
    })->values();

    $summaryGrandTotal = (int) $classSummary->sum('total_students');

@endphp

<style>
    /*
    |--------------------------------------------------------------------------
    | Main Report
    |--------------------------------------------------------------------------
    */

    .age-report-page {
        position: relative;
        isolation: isolate;
        background: #fff;
        color: #222;
        padding: 25px;
        min-height: 450px;
    }

    /*
    |--------------------------------------------------------------------------
    | School Header
    |--------------------------------------------------------------------------
    */

    .school-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 18px;
        text-align: center;
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 2px solid #333;
    }

    .school-logo {
        width: 85px;
        height: 85px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .school-details h2 {
        margin: 0 0 6px;
        font-size: 22px;
        font-weight: bold;
    }

    .school-details p {
        margin: 3px 0;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Report Heading
    |--------------------------------------------------------------------------
    */

    .report-heading {
        text-align: center;
        margin: 18px 0 22px;
    }

    .report-heading h3 {
        margin: 0 0 7px;
        font-size: 18px;
    }

    .report-heading p {
        margin: 4px 0;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Buttons
    |--------------------------------------------------------------------------
    */

    .report-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    /*
    |--------------------------------------------------------------------------
    | Report Information
    |--------------------------------------------------------------------------
    */

    .report-info {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 25px;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        background: #fff;
        font-size: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    */

    .age-class-section {
        margin-bottom: 25px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .age-class-title {
        padding: 9px 10px;
        margin: 0;
        background: #edf2f8;
        border: 1px solid #777;
        border-bottom: none;
        font-size: 14px;
    }

    .age-summary-table,
    .age-detail-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    .age-summary-table th,
    .age-summary-table td,
    .age-detail-table th,
    .age-detail-table td {
        border: 1px solid #777;
        padding: 9px;
        font-size: 12px;
    }

    .age-summary-table th,
    .age-detail-table th {
        background: #eaf1fb;
        text-align: center;
    }

    .age-summary-table td,
    .age-detail-table td {
        text-align: center;
    }

    .age-total-row {
        font-weight: bold;
        background: #f1f1f1;
    }

    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    .age-grand-total {
        margin-top: 20px;
        padding: 13px;
        border: 1px solid #555;
        font-weight: bold;
        background: #f1f1f1;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 12px;
    }

    /*
    |--------------------------------------------------------------------------
    | Signatures
    |--------------------------------------------------------------------------
    */

    .age-signatures {
        display: flex;
        justify-content: space-between;
        gap: 25px;
        margin: 65px 10px 10px;
    }

    .age-signatures span {
        min-width: 150px;
        padding-top: 8px;
        border-top: 1px solid #333;
        text-align: center;
        font-size: 11px;
    }

    .age-empty-report {
        padding: 30px;
        border: 1px solid #ddd;
        text-align: center;
    }

    /*
    |--------------------------------------------------------------------------
    | Screen Layout
    |--------------------------------------------------------------------------
    */

    @media (max-width: 768px) {
        .age-report-page {
            padding: 12px;
        }

        .school-header {
            flex-direction: column;
        }

        .school-details h2 {
            font-size: 18px;
        }

        .report-info {
            flex-direction: column;
            gap: 8px;
        }

        .age-summary-table th,
        .age-summary-table td,
        .age-detail-table th,
        .age-detail-table td {
            padding: 6px;
            font-size: 10px;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Print Layout
    |--------------------------------------------------------------------------
    */

    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body * {
            visibility: hidden !important;
        }

        #age-report-print,
        #age-report-print * {
            visibility: visible !important;
        }

        #age-report-print {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            overflow: visible !important;
        }

        .report-actions,
        .no-print,
        .sidebar,
        #sidebar,
        .navbar,
        .topbar,
        nav,
        footer,
        button {
            display: none !important;
        }

        /*
         * Summary Mode
         */

        body[data-print-mode="summary"] #full-age-report,
        body[data-print-mode="summary"] #full-age-report-info {
            display: none !important;
        }

        body[data-print-mode="summary"] #summary-age-report {
            display: block !important;
        }

        /*
         * Full Report Mode
         */

        body[data-print-mode="full"] #summary-age-report {
            display: none !important;
        }

        body[data-print-mode="full"] #full-age-report,
        body[data-print-mode="full"] #full-age-report-info {
            display: block !important;
        }

        .school-header {
            gap: 12px;
        }

        .school-logo {
            width: 70px;
            height: 70px;
        }

        .school-details h2 {
            font-size: 17px;
        }

        .school-details p {
            font-size: 9px;
        }

        .report-heading h3 {
            font-size: 14px;
        }

        .report-heading p {
            font-size: 10px;
        }

        .report-info {
            padding: 7px;
            gap: 8px 15px;
        }

        .age-summary-table th,
        .age-summary-table td,
        .age-detail-table th,
        .age-detail-table td {
            padding: 7px;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .age-class-section,
        .age-grand-total,
        .age-signatures {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div id="age-report-print" class="age-report-page">

    {{-- =====================================================
         SCHOOL HEADER: SHOWN IN BOTH FORMATS
    ====================================================== --}}

    <div class="school-header">
        <img
            src="{{ $schoolLogo }}"
            alt="School Logo"
            class="school-logo"
            onerror="this.style.display='none'"
        >

        <div class="school-details">
            <h2>
                {{ $school->school_name ?? config('app.name', 'School') }}
            </h2>

            @if($schoolAddress)
                <p>{{ $schoolAddress }}</p>
            @endif

            @if(!empty($school->phone))
                <p>Phone: {{ $school->phone }}</p>
            @endif

            @if(!empty($school->email))
                <p>Email: {{ $school->email }}</p>
            @endif

            @if(!empty($school->udise_code))
                <p>UDISE Code: {{ $school->udise_code }}</p>
            @endif
        </div>
    </div>

    {{-- REPORT HEADING --}}

    <div class="report-heading">
        <h3 id="age-report-title">STUDENT AGE REPORT</h3>

        <p>
            Academic Year: {{ $academicYearValue }}
        </p>

        <p>
            Age As On: {{ $ageAsOnValue }}
        </p>
    </div>

    {{-- PRINT ACTIONS --}}

    <div class="report-actions no-print">
        <button
            type="button"
            class="btn btn-primary"
            onclick="printAgeReport('summary')"
        >
            <i class="bi bi-printer"></i>
            Print Class Summary
        </button>

        <button
            type="button"
            class="btn btn-success"
            onclick="printAgeReport('full')"
        >
            <i class="bi bi-printer"></i>
            Print Full Age Report
        </button>

        <a
            class="btn btn-outline-secondary"
            href="{{ route('admin.reports.age', request()->query()) }}"
        >
            Back to Age Report
        </a>
    </div>

    {{-- =====================================================
         FORMAT 1: CLASS SUMMARY
         Only Class and Total Students
    ====================================================== --}}

    <div id="summary-age-report">

        <table class="age-summary-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Sr. No.</th>
                    <th style="width: 40%;">Class</th>
                    <th style="width: 40%;">Total Students</th>
                </tr>
            </thead>

            <tbody>
                @forelse($classSummary as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row['class'] }}</td>
                        <td>{{ $row['total_students'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            No student data found for the selected filters.
                        </td>
                    </tr>
                @endforelse

                @if($classSummary->isNotEmpty())
                    <tr class="age-total-row">
                        <td colspan="2">GRAND TOTAL</td>
                        <td>{{ $summaryGrandTotal }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>

    {{-- =====================================================
         FORMAT 2: FULL AGE REPORT
    ====================================================== --}}

    <div id="full-age-report-info" class="report-info">

        <span>
            <strong>Class:</strong>
            {{ $selectedClass ?: 'All Classes' }}
        </span>

        <span>
            <strong>Total Boys:</strong>
            {{ $totalBoys }}
        </span>

        <span>
            <strong>Total Girls:</strong>
            {{ $totalGirls }}
        </span>

        <span>
            <strong>Total Students:</strong>
            {{ $grandTotal }}
        </span>

    </div>

    <div id="full-age-report">

        @forelse($classGroups as $className => $classRows)

            @php
                $ageGroupRows = $classRows
                    ->groupBy(function ($row) {
                        return trim((string) data_get($row, 'age_group'))
                            ?: 'Not Specified';
                    })
                    ->sortKeys();

                $classBoys = (int) $classRows->sum(function ($row) {
                    return (int) data_get($row, 'boys', 0);
                });

                $classGirls = (int) $classRows->sum(function ($row) {
                    return (int) data_get($row, 'girls', 0);
                });

                $classTotal = (int) $classRows->sum(function ($row) use ($getStudentCount) {
                    return $getStudentCount($row);
                });
            @endphp

            <div class="age-class-section">

                <h4 class="age-class-title">
                    Class: {{ $className }}
                </h4>

                <table class="age-detail-table">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Sr. No.</th>
                            <th style="width: 40%;">Age Group</th>
                            <th style="width: 16%;">Boys</th>
                            <th style="width: 16%;">Girls</th>
                            <th style="width: 16%;">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ageGroupRows as $ageGroupName => $ageRows)

                            @php
                                $boys = (int) $ageRows->sum(function ($row) {
                                    return (int) data_get($row, 'boys', 0);
                                });

                                $girls = (int) $ageRows->sum(function ($row) {
                                    return (int) data_get($row, 'girls', 0);
                                });

                                $ageTotal = (int) $ageRows->sum(function ($row) use ($getStudentCount) {
                                    return $getStudentCount($row);
                                });
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ageGroupName }}</td>
                                <td>{{ $boys }}</td>
                                <td>{{ $girls }}</td>
                                <td>{{ $ageTotal }}</td>
                            </tr>

                        @endforeach

                        <tr class="age-total-row">
                            <td colspan="2">CLASS TOTAL</td>
                            <td>{{ $classBoys }}</td>
                            <td>{{ $classGirls }}</td>
                            <td>{{ $classTotal }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        @empty

            <div class="age-empty-report">
                No age report data found for the selected filters.
            </div>

        @endforelse

        {{-- FULL REPORT GRAND TOTAL --}}

        @if($reportRows->isNotEmpty())
            <div class="age-grand-total">
                <span>GRAND TOTAL</span>
                <span>Boys: {{ $totalBoys }}</span>
                <span>Girls: {{ $totalGirls }}</span>
                <span>Students: {{ $grandTotal }}</span>
            </div>
        @endif

    </div>

    {{-- SIGNATURES: SHOWN IN BOTH FORMATS --}}

    <div class="age-signatures">
        <span>Class Teacher</span>
        <span>Principal / Headmaster</span>
    </div>

</div>

{{-- PRINT MODE SCRIPT --}}

<script>
    function printAgeReport(mode) {
        document.body.dataset.printMode = mode;

        const title = document.getElementById('age-report-title');

        if (title) {
            title.textContent = mode === 'summary'
                ? 'CLASS-WISE STUDENT COUNT REPORT'
                : 'STUDENT AGE REPORT';
        }

        window.print();
    }

    window.addEventListener('afterprint', function () {
        delete document.body.dataset.printMode;

        const title = document.getElementById('age-report-title');

        if (title) {
            title.textContent = 'STUDENT AGE REPORT';
        }
    });
</script>

@endsection