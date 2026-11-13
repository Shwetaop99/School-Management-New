
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Age Report</title>

    @php
        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        */

        $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

        $schoolLogo = $school?->logo ?? null;

        if ($schoolLogo && !preg_match('~^(https?://|/)~i', $schoolLogo)) {
            $schoolLogo = asset('storage/' . ltrim($schoolLogo, '/'));
        }

        if (!$schoolLogo) {
            $schoolLogo = asset('images/gurukullogo.png');
        }

        $schoolAddress = collect([
            $school?->address,
            $school?->city,
            $school?->district,
            $school?->state,
            $school?->pincode,
        ])->filter()->implode(', ');

        /*
        |--------------------------------------------------------------------------
        | Selected Filters
        |--------------------------------------------------------------------------
        */

        $selectedClassValue = $selectedClass ?? request('class', '');

        $academicYearValue = !empty($selectedAcademicYear)
            ? $selectedAcademicYear
            : 'All Academic Years';

        $ageAsOnValue = !empty($ageAsOn)
            ? \Carbon\Carbon::parse($ageAsOn)->format('d-m-Y')
            : now()->format('d-m-Y');

        /*
        |--------------------------------------------------------------------------
        | Report Data
        |--------------------------------------------------------------------------
        */

        $reportRows = collect($report ?? []);

        // Keep only age groups containing students.
        $reportRows = $reportRows->filter(function ($row) {
            $total = data_get($row, 'total');

            if ($total === null) {
                $total = (int) data_get($row, 'boys', 0)
                    + (int) data_get($row, 'girls', 0);
            }

            return (int) $total > 0;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | Student Count Helper
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

        /*
        |--------------------------------------------------------------------------
        | Grand Totals
        |--------------------------------------------------------------------------
        */

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
        | Class-Wise Grouping
        |--------------------------------------------------------------------------
        */

        $classGroups = $reportRows
            ->groupBy(function ($row) {
                return trim((string) data_get($row, 'class'))
                    ?: 'Not Specified';
            })
            ->sortKeys();

    @endphp

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            background: #f1f2f5;
        }

        /* =====================================================
           MAIN PAGE
        ===================================================== */

        .age-report-page {
            position: relative;
            isolation: isolate;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 12mm 12mm 25mm;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, .12);
        }

        .report-content {
            position: relative;
            z-index: 2;
        }

        /* =====================================================
           PAGE BORDER
        ===================================================== */

        .page-border {
            position: absolute;
            z-index: 4;
            top: 6mm;
            right: 6mm;
            bottom: 6mm;
            left: 6mm;
            border: 1px solid #777;
            pointer-events: none;
        }

        /* =====================================================
           SCHOOL WATERMARK
        ===================================================== */

        .watermark {
            position: absolute;
            z-index: 1;
            top: 50%;
            left: 50%;
            width: 125mm;
            height: 125mm;
            object-fit: contain;
            opacity: .10;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        /* =====================================================
           SCHOOL HEADER
        ===================================================== */

        .school-header {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
        }

        .school-header td {
            border: none;
            vertical-align: middle;
            padding: 0 0 12px;
        }

        .school-logo-cell {
            width: 75px;
            text-align: left;
        }

        .school-logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .school-details-cell {
            text-align: center;
        }

        .school-spacer-cell {
            width: 75px;
        }

        .school-details h1 {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.3;
        }

        .school-details p {
            margin: 2px 0;
            font-size: 10px;
            line-height: 1.4;
        }

        /* =====================================================
           REPORT HEADING
        ===================================================== */

        .report-heading {
            text-align: center;
            margin: 16px 0 18px;
        }

        .report-heading h2 {
            margin: 0 0 7px;
            font-size: 17px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .report-heading p {
            margin: 4px 0;
            font-size: 11px;
        }

        /* =====================================================
           REPORT INFORMATION
        ===================================================== */

        .report-info {
            width: 100%;
            margin-bottom: 16px;
            padding: 9px 10px;
            border: 1px solid #777;
            background: rgba(255, 255, 255, .85);
            font-size: 10px;
            line-height: 1.7;
        }

        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-info td {
            border: none;
            padding: 2px 5px;
            text-align: left;
        }

        /* =====================================================
           CLASS SECTIONS
        ===================================================== */

        .age-class-section {
            margin-bottom: 18px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .age-class-title {
            margin: 0;
            padding: 8px 10px;
            border: 1px solid #555;
            border-bottom: none;
            background: #edf1f6;
            font-size: 12px;
            font-weight: 700;
        }

        /* =====================================================
           REPORT TABLE
        ===================================================== */

        .age-detail-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            background: rgba(255, 255, 255, .88);
        }

        .age-detail-table th,
        .age-detail-table td {
            border: 1px solid #555;
            padding: 8px 7px;
            font-size: 10px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .age-detail-table th {
            background: #eaf0f8;
            font-weight: 700;
            text-align: center;
        }

        .age-detail-table td {
            text-align: center;
        }

        .age-detail-table td:nth-child(2) {
            text-align: left;
        }

        .age-total-row {
            background: #f0f0f0;
            font-weight: 700;
        }

        /* =====================================================
           GRAND TOTAL
        ===================================================== */

        .age-grand-total {
            display: table;
            width: 100%;
            margin-top: 16px;
            padding: 10px;
            border: 1px solid #555;
            background: #f0f0f0;
            font-size: 11px;
            font-weight: 700;
        }

        .age-grand-total span {
            display: table-cell;
            text-align: center;
        }

        .age-grand-total span:first-child {
            text-align: left;
        }

        /* =====================================================
           EMPTY REPORT
        ===================================================== */

        .age-empty-report {
            padding: 25px;
            border: 1px solid #aaa;
            background: rgba(255, 255, 255, .9);
            text-align: center;
            font-size: 12px;
        }

        /* =====================================================
           SIGNATURES
        ===================================================== */

        .age-signatures {
            display: table;
            width: 100%;
            margin-top: 55px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-cell {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }

        .signature-cell:last-child {
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 155px;
            padding-top: 7px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 10px;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .report-footer {
            position: absolute;
            right: 12mm;
            bottom: 10mm;
            left: 12mm;
            z-index: 5;
            padding-top: 5px;
            border-top: 1px solid #aaa;
            background: rgba(255, 255, 255, .9);
            text-align: center;
            font-size: 8px;
            color: #555;
        }

        /* =====================================================
           PRINT BUTTON
        ===================================================== */

        .report-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 9px;
            margin: 0 0 20px;
        }

        .report-actions button {
            display: inline-block;
            padding: 10px 22px;
            border: none;
            border-radius: 5px;
            background: #21864b;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .report-actions button:hover {
            background: #176c3a;
        }

        /* =====================================================
           SCREEN RESPONSIVE
        ===================================================== */

        @media screen and (max-width: 850px) {
            .age-report-page {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 18px;
            }

            .school-logo-cell,
            .school-spacer-cell {
                width: 55px;
            }

            .school-logo {
                width: 48px;
                height: 48px;
            }

            .school-details h1 {
                font-size: 15px;
            }

            .school-details p {
                font-size: 9px;
            }

            .watermark {
                width: 85%;
                height: auto;
            }

            .age-detail-table th,
            .age-detail-table td {
                padding: 5px 3px;
                font-size: 9px;
            }

            .report-footer {
                position: static;
                margin-top: 30px;
            }
        }

        /* =====================================================
           PRINT STYLES
        ===================================================== */

        @media print {

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            html,
            body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
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
                min-height: 277mm;
                margin: 0 !important;
                padding: 8mm 8mm 22mm !important;
                box-shadow: none !important;
                overflow: visible !important;
                isolation: isolate;
            }

            .report-actions,
            .no-print {
                display: none !important;
            }

            .page-border {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 4;
            }

            .watermark {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                width: 125mm !important;
                height: 125mm !important;
                object-fit: contain !important;
                opacity: .10 !important;
                transform: translate(-50%, -50%) !important;
                display: block !important;
                visibility: visible !important;
                z-index: 1 !important;
            }

            .report-content {
                position: relative;
                z-index: 2;
            }

            .school-header {
                margin-bottom: 10px;
            }

            .school-header td {
                padding-bottom: 9px;
            }

            .school-logo {
                width: 62px;
                height: 62px;
            }

            .school-details h1 {
                font-size: 17px;
            }

            .school-details p {
                font-size: 9px;
            }

            .report-heading {
                margin: 12px 0 14px;
            }

            .report-heading h2 {
                font-size: 14px;
            }

            .report-heading p {
                font-size: 9px;
            }

            .report-info {
                margin-bottom: 12px;
                padding: 7px;
            }

            .age-class-title {
                padding: 7px;
                font-size: 11px;
            }

            .age-detail-table th,
            .age-detail-table td {
                padding: 6px 5px;
                font-size: 9px;
            }

            .age-class-section,
            .age-grand-total,
            .age-signatures {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .report-footer {
                position: fixed;
                right: 8mm;
                bottom: 4mm;
                left: 8mm;
                background: #fff;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

<div id="age-report-print" class="age-report-page">

    {{-- PAGE BORDER --}}
    <div class="page-border"></div>

    {{-- SCHOOL WATERMARK --}}
    @if($schoolLogo)
        <img
            src="{{ $schoolLogo }}"
            class="watermark"
            alt=""
            onerror="this.style.display='none'"
        >
    @endif

    <div class="report-content">

        {{-- =================================================
             SCHOOL HEADER
        ================================================== --}}

        <table class="school-header">
            <tr>
                <td class="school-logo-cell">
                    <img
                        src="{{ $schoolLogo }}"
                        alt="School Logo"
                        class="school-logo"
                        onerror="this.style.display='none'"
                    >
                </td>

                <td class="school-details-cell">
                    <div class="school-details">

                        <h1>
                            {{ $school?->school_name ?? config('app.name', 'School') }}
                        </h1>

                        @if($schoolAddress)
                            <p>{{ $schoolAddress }}</p>
                        @endif

                        @if(!empty($school?->phone))
                            <p>Phone: {{ $school->phone }}</p>
                        @endif

                        @if(!empty($school?->email))
                            <p>Email: {{ $school->email }}</p>
                        @endif

                        @if(!empty($school?->udise_code))
                            <p>UDISE Code: {{ $school->udise_code }}</p>
                        @endif

                    </div>
                </td>

                <td class="school-spacer-cell"></td>
            </tr>
        </table>

        {{-- =================================================
             REPORT HEADING
        ================================================== --}}

        <div class="report-heading">

            <h2>STUDENT AGE REPORT</h2>

            <p>
                <strong>Academic Year:</strong>
                {{ $academicYearValue }}
            </p>

            <p>
                <strong>Age As On:</strong>
                {{ $ageAsOnValue }}
            </p>

        </div>

        {{-- =================================================
             PRINT BUTTON
        ================================================== --}}

        <div class="report-actions no-print">
            <button type="button" onclick="printAgeReport()">
                Print Age Report
            </button>
        </div>

        {{-- =================================================
             REPORT INFORMATION
        ================================================== --}}

        <div class="report-info">

            <table>
                <tr>
                    <td>
                        <strong>Class:</strong>
                        {{ $selectedClassValue ?: 'All Classes' }}
                    </td>

                    <td>
                        <strong>Total Boys:</strong>
                        {{ $totalBoys }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Total Girls:</strong>
                        {{ $totalGirls }}
                    </td>

                    <td>
                        <strong>Total Students:</strong>
                        {{ $grandTotal }}
                    </td>
                </tr>
            </table>

        </div>

        {{-- =================================================
             DETAILED AGE REPORT
        ================================================== --}}

        <div id="full-age-report">

            @forelse($classGroups as $className => $classRows)

                @php
                    $ageGroupRows = $classRows
                        ->groupBy(function ($row) {
                            return trim((string) data_get($row, 'age_group'))
                                ?: 'Not Specified';
                        });

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

                    <h3 class="age-class-title">
                        Class: {{ $className }}
                    </h3>

                    <table class="age-detail-table">

                        <thead>
                            <tr>
                                <th style="width:12%;">Sr. No.</th>
                                <th style="width:40%;">Age Group</th>
                                <th style="width:16%;">Boys</th>
                                <th style="width:16%;">Girls</th>
                                <th style="width:16%;">Total</th>
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

            {{-- GRAND TOTAL --}}

            @if($reportRows->isNotEmpty())

                <div class="age-grand-total">
                    <span>GRAND TOTAL</span>
                    <span>Boys: {{ $totalBoys }}</span>
                    <span>Girls: {{ $totalGirls }}</span>
                    <span>Students: {{ $grandTotal }}</span>
                </div>

            @endif

        </div>

        {{-- =================================================
             SIGNATURES
        ================================================== --}}

        <div class="age-signatures">

            <div class="signature-cell">
                <span class="signature-line">
                    Class Teacher
                </span>
            </div>

            <div class="signature-cell">
                <span class="signature-line">
                    Principal / Headmaster
                </span>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}

    <div class="report-footer">
        {{ $school?->school_name ?? config('app.name', 'School') }}
        | Student Age Report
    </div>

</div>

<script>
    function printAgeReport() {
        window.print();
    }
</script>

</body>
</html>
