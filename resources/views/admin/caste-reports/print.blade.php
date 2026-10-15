@extends('layouts.app')

@section('title', 'Print Caste Report')

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

    $reportRows = $reportRows->sortBy([
        ['class', 'asc'],
        ['caste', 'asc'],
    ])->values();

    $academicYearValue = $academicYear
        ?? data_get($selectedFilters ?? [], 'academic_year')
        ?? request('academic_year')
        ?? 'All Academic Years';

    /*
    |--------------------------------------------------------------------------
    | Class-Wise Grouping
    |--------------------------------------------------------------------------
    */

    $classGroups = $reportRows->groupBy(function ($row) {
        return trim((string) data_get($row, 'class'))
            ?: 'Not Specified';
    });

    /*
    |--------------------------------------------------------------------------
    | Overall Totals
    |--------------------------------------------------------------------------
    */

    $totalBoys = (int) $reportRows->sum('boys');

    $totalGirls = (int) $reportRows->sum('girls');

    $grandTotal = $totalBoys + $totalGirls;

    $totalCastes = $reportRows
        ->map(fn ($row) => strtolower(trim((string) data_get($row, 'caste'))))
        ->unique()
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Class Summary: Class + Total Students Only
    |--------------------------------------------------------------------------
    */

    $classSummary = $classGroups->map(function ($classRows, $className) {
        $boys = (int) $classRows->sum('boys');
        $girls = (int) $classRows->sum('girls');

        return [
            'class' => $className,
            'total_students' => $boys + $girls,
        ];
    })->sortBy('class')->values();

    $summaryGrandTotal = (int) $classSummary->sum('total_students');
@endphp

<style>
    /*
    |--------------------------------------------------------------------------
    | Main Report
    |--------------------------------------------------------------------------
    */

    .caste-report-page {
        position: relative;
        background: #fff;
        padding: 25px;
        color: #222;
        min-height: 500px;
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
        margin: 15px 0 20px;
    }

    .report-heading h3 {
        margin: 0 0 6px;
        font-size: 18px;
    }

    .report-heading p {
        margin: 0;
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

    .class-section {
        margin-bottom: 25px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .class-title {
        padding: 9px 10px;
        margin: 0;
        background: #edf2f8;
        border: 1px solid #777;
        border-bottom: none;
        font-size: 14px;
    }

    .caste-table,
    .summary-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    .caste-table th,
    .caste-table td,
    .summary-table th,
    .summary-table td {
        border: 1px solid #777;
        padding: 9px;
        font-size: 12px;
    }

    .caste-table th,
    .summary-table th {
        background: #eaf1fb;
        text-align: center;
    }

    .number {
        text-align: center;
    }

    .total-row,
    .summary-grand-total {
        font-weight: bold;
        background: #f1f1f1;
    }

    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    .grand-total {
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

    .signatures {
        display: flex;
        justify-content: space-between;
        gap: 25px;
        margin: 65px 10px 10px;
    }

    .signatures span {
        min-width: 150px;
        padding-top: 8px;
        border-top: 1px solid #333;
        text-align: center;
        font-size: 11px;
    }

    .empty-report {
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
        .caste-report-page {
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

        .caste-table th,
        .caste-table td,
        .summary-table th,
        .summary-table td {
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

        #caste-report-print,
        #caste-report-print * {
            visibility: visible !important;
        }

        #caste-report-print {
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
         * Class Summary Mode:
         * Hide caste-wise details and the Boys/Girls summary.
         */

        body[data-print-mode="summary"] #full-report,
        body[data-print-mode="summary"] #full-report-info {
            display: none !important;
        }

        body[data-print-mode="summary"] #summary-report {
            display: block !important;
        }

        /*
         * Full Caste Report Mode:
         * Hide the class summary table.
         */

        body[data-print-mode="full"] #summary-report {
            display: none !important;
        }

        body[data-print-mode="full"] #full-report,
        body[data-print-mode="full"] #full-report-info {
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

        .caste-table th,
        .caste-table td,
        .summary-table th,
        .summary-table td {
            padding: 7px;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .class-section,
        .grand-total,
        .signatures {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div id="caste-report-print" class="caste-report-page">

    {{-- SCHOOL HEADER: SHOWN IN BOTH PRINT FORMATS --}}

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
        <h3 id="report-title">
            CASTE / CATEGORY STUDENT REPORT
        </h3>

        <p>
            Academic Year: {{ $academicYearValue }}
        </p>
    </div>

    {{-- PRINT BUTTONS --}}

    <div class="report-actions no-print">
        <button
            type="button"
            class="btn btn-primary"
            onclick="printReport('summary')"
        >
            <i class="bi bi-printer"></i>
            Print Class Summary
        </button>

        <button
            type="button"
            class="btn btn-success"
            onclick="printReport('full')"
        >
            <i class="bi bi-printer"></i>
            Print Full Caste Report
        </button>

        <a
            class="btn btn-danger"
            href="{{ route('admin.caste-report.pdf-download', request()->query()) }}"
        >
            <i class="bi bi-file-earmark-pdf"></i>
            Download PDF
        </a>

        <a
            class="btn btn-outline-secondary"
            href="{{ route('admin.caste-report.index', request()->query()) }}"
        >
            Back to Report
        </a>
    </div>

    {{-- =========================================================
         FORMAT 1: CLASS SUMMARY
         Only Class and Total Students
    ========================================================== --}}

    <div id="summary-report" class="print-section">

        <table class="summary-table">
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
                    <tr class="summary-grand-total">
                        <td colspan="2">GRAND TOTAL</td>
                        <td>{{ $summaryGrandTotal }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>

    {{-- =========================================================
         FORMAT 2: FULL CASTE REPORT
         Caste, Boys, Girls and Total
    ========================================================== --}}

    <div id="full-report-info" class="report-info">

        <span>
            <strong>Class:</strong>
            {{ $selectedClass ?: 'All Classes' }}
        </span>

        <span>
            <strong>Total Categories:</strong>
            {{ $totalCastes }}
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

    <div id="full-report" class="print-section">

        @forelse($classGroups as $className => $classRows)

            @php
                $casteGroups = $classRows->groupBy(function ($row) {
                    return trim((string) data_get($row, 'caste'))
                        ?: 'Not Specified';
                });

                $classBoys = (int) $classRows->sum('boys');
                $classGirls = (int) $classRows->sum('girls');
            @endphp

            <div class="class-section">

                <h4 class="class-title">
                    Class: {{ $className }}
                </h4>

                <table class="caste-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Sr. No.</th>
                            <th style="width: 40%;">Caste / Category</th>
                            <th style="width: 15%;">Boys</th>
                            <th style="width: 15%;">Girls</th>
                            <th style="width: 20%;">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($casteGroups as $casteName => $casteRows)

                            @php
                                $boys = (int) $casteRows->sum('boys');
                                $girls = (int) $casteRows->sum('girls');
                            @endphp

                            <tr>
                                <td class="number">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $casteName }}
                                </td>

                                <td class="number">
                                    {{ $boys }}
                                </td>

                                <td class="number">
                                    {{ $girls }}
                                </td>

                                <td class="number">
                                    {{ $boys + $girls }}
                                </td>
                            </tr>

                        @endforeach

                        <tr class="total-row">
                            <td colspan="2">Class Total</td>

                            <td class="number">
                                {{ $classBoys }}
                            </td>

                            <td class="number">
                                {{ $classGirls }}
                            </td>

                            <td class="number">
                                {{ $classBoys + $classGirls }}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        @empty

            <div class="empty-report">
                No caste report data found for the selected filters.
            </div>

        @endforelse

        {{-- FULL REPORT GRAND TOTAL --}}

        @if($reportRows->isNotEmpty())
            <div class="grand-total">
                <span>GRAND TOTAL</span>
                <span>Boys: {{ $totalBoys }}</span>
                <span>Girls: {{ $totalGirls }}</span>
                <span>Students: {{ $grandTotal }}</span>
            </div>
        @endif

    </div>

    {{-- SIGNATURES: SHOWN IN BOTH PRINT FORMATS --}}

    <div class="signatures">
        <span>Class Teacher</span>
        <span>Principal / Headmaster</span>
    </div>

</div>

{{-- PRINT MODE SCRIPT --}}

<script>
    function printReport(mode) {
        document.body.dataset.printMode = mode;

        const title = document.getElementById('report-title');

        if (title) {
            title.textContent = mode === 'summary'
                ? 'CLASS-WISE STUDENT COUNT REPORT'
                : 'CASTE / CATEGORY STUDENT REPORT';
        }

        window.print();
    }

    window.addEventListener('afterprint', function () {
        delete document.body.dataset.printMode;

        const title = document.getElementById('report-title');

        if (title) {
            title.textContent = 'CASTE / CATEGORY STUDENT REPORT';
        }
    });
</script>

@endsection
