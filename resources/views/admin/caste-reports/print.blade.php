
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caste / Category Student Report</title>

    @php
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

        $reportRows = collect($report ?? []);

        $selectedClass = trim((string) request(
            'class',
            data_get($selectedFilters ?? [], 'class', '')
        ));

        if ($selectedClass !== '') {
            $reportRows = $reportRows->filter(function ($row) use ($selectedClass) {
                return strcasecmp(
                    trim((string) data_get($row, 'class')),
                    $selectedClass
                ) === 0;
            });
        }

        $selectedCaste = trim((string) request(
            'caste',
            data_get($selectedFilters ?? [], 'caste', '')
        ));

        if ($selectedCaste !== '') {
            $reportRows = $reportRows->filter(function ($row) use ($selectedCaste) {
                return strcasecmp(
                    trim((string) data_get($row, 'caste')),
                    $selectedCaste
                ) === 0;
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

        $classGroups = $reportRows->groupBy(function ($row) {
            return trim((string) data_get($row, 'class')) ?: 'Not Specified';
        });

        $totalBoys = (int) $reportRows->sum('boys');
        $totalGirls = (int) $reportRows->sum('girls');
        $grandTotal = $totalBoys + $totalGirls;

        $totalCastes = $reportRows
            ->map(fn ($row) => strtolower(trim((string) data_get($row, 'caste'))))
            ->unique()
            ->count();

        $classSummary = $classGroups->map(function ($classRows, $className) {
            $boys = (int) $classRows->sum('boys');
            $girls = (int) $classRows->sum('girls');

            return [
                'class' => $className,
                'total_students' => $boys + $girls,
            ];
        })->values();

        $summaryGrandTotal = (int) $classSummary->sum('total_students');
    @endphp

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ededed;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        /* =========================
           TOOLBAR
        ========================= */

        .toolbar {
            width: min(190mm, calc(100% - 24px));
            margin: 16px auto 12px;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .toolbar button,
        .toolbar a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 34px;
            padding: 7px 12px;
            border: 1px solid #333;
            border-radius: 4px;
            background: #fff;
            color: #111;
            text-decoration: none;
            font-size: 11px;
            cursor: pointer;
        }

        .toolbar button:hover,
        .toolbar a:hover {
            background: #222;
            color: #fff;
        }

        /* =========================
           REPORT PAGE
        ========================= */

        .report-sheet {
            width: 190mm;
            min-height: 277mm;
            margin: 0 auto 20px;
            padding: 10mm;
            position: relative;
            background: #fff;
            border: 1.5px solid #111;
            outline: 1px solid #555;
            outline-offset: -6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .12);
        }

        /* =========================
           SCHOOL HEADER
        ========================= */

        .school-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 0 0 10px;
            border-bottom: 1.5px solid #111;
            text-align: center;
        }

        .school-logo {
            display: block;
            width: 68px;
            height: 68px;
            flex: 0 0 68px;
            object-fit: contain;
        }

        .school-details {
            min-width: 0;
        }

        .school-details h1 {
            margin: 0 0 4px;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 800;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .school-details p {
            margin: 2px 0;
            font-size: 9px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        /* =========================
           REPORT HEADING
        ========================= */

        .report-heading {
            margin: 12px 0 13px;
            text-align: center;
        }

        .report-heading h2 {
            margin: 0 0 4px;
            font-size: 15px;
            line-height: 1.25;
            font-weight: 800;
            letter-spacing: .3px;
        }

        .report-heading p {
            margin: 0;
            font-size: 10px;
        }

        /* =========================
           REPORT INFORMATION
        ========================= */

        .report-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 6px 10px;
            margin: 0 0 12px;
            padding: 7px 8px;
            border: 1px solid #555;
            background: #f4f4f4;
            font-size: 9px;
        }

        /* =========================
           CLASS SECTIONS
        ========================= */

        .class-section {
            margin: 0 0 13px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .class-title {
            margin: 0;
            padding: 6px 8px;
            border: 1px solid #333;
            border-bottom: 0;
            background: #e7e7e7;
            font-size: 11px;
            font-weight: 700;
            break-after: avoid;
            page-break-after: avoid;
        }

        /* =========================
           TABLES
        ========================= */

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            background: transparent !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 6px 5px;
            font-size: 9px;
            line-height: 1.25;
            overflow-wrap: anywhere;
            vertical-align: middle;
        }

        th {
            background: rgba(222, 222, 222, .88) !important;
            text-align: center;
            font-weight: 700;
        }

        tbody,
        tbody tr,
        tbody td {
            background-color: transparent !important;
        }

        .caste-table th:nth-child(1),
        .caste-table td:nth-child(1) {
            width: 10%;
            text-align: center;
        }

        .caste-table th:nth-child(2),
        .caste-table td:nth-child(2) {
            width: 40%;
            text-align: left;
        }

        .caste-table th:nth-child(3),
        .caste-table td:nth-child(3),
        .caste-table th:nth-child(4),
        .caste-table td:nth-child(4),
        .caste-table th:nth-child(5),
        .caste-table td:nth-child(5) {
            width: 16.666%;
            text-align: center;
        }

        .summary-table th:nth-child(1),
        .summary-table td:nth-child(1) {
            width: 18%;
            text-align: center;
        }

        .summary-table th:nth-child(2),
        .summary-table td:nth-child(2) {
            width: 42%;
            text-align: left;
        }

        .summary-table th:nth-child(3),
        .summary-table td:nth-child(3) {
            width: 40%;
            text-align: center;
        }

        .number {
            text-align: center;
        }

        .total-row,
        .summary-grand-total {
            background: rgba(237, 237, 237, .88) !important;
            font-weight: 800;
        }

        /* =========================
           GRAND TOTAL
        ========================= */

        .grand-total {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 6px 12px;
            margin-top: 13px;
            padding: 8px;
            border: 1px solid #333;
            background: rgba(237, 237, 237, .88);
            font-size: 10px;
            font-weight: 800;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* =========================
           SIGNATURES
        ========================= */

        .signatures {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin: 42px 8px 8px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .signatures span {
            width: 42%;
            padding-top: 7px;
            border-top: 1px solid #222;
            text-align: center;
            font-size: 9px;
        }

        /* =========================
           EMPTY REPORT
        ========================= */

        .empty-report {
            padding: 22px 10px;
            border: 1px solid #555;
            text-align: center;
        }

        #summary-report {
            display: none;
        }

        /* =========================
           MOBILE RESPONSIVE
        ========================= */

        @media (max-width: 760px) {
            .report-sheet {
                width: calc(100% - 16px);
                min-height: auto;
                margin-bottom: 12px;
                padding: 22px 14px;
            }

            .toolbar {
                width: calc(100% - 16px);
            }

            .school-header {
                flex-direction: column;
                gap: 7px;
            }

            .school-details h1 {
                font-size: 17px;
            }

            .report-info {
                flex-direction: column;
            }

            th,
            td {
                padding: 5px 3px;
                font-size: 8px;
            }

            .signatures {
                gap: 16px;
                margin-top: 32px;
            }
        }

        /* =========================
           PRINT SETTINGS
        ========================= */

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 10mm 27mm 10mm;
            }

            html,
            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #111 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Border on every printed page */
            body::before {
                content: "";
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                border: 1.2px solid #111;
                outline: 1px solid #555;
                outline-offset: -4px;
                pointer-events: none;
                z-index: 999999;
            }

            /* WATERMARK ON EVERY PRINTED PAGE */
            body::after {
                content: "";
                position: fixed;
                top: 50%;
                left: 50%;
                width: 125mm;
                height: 125mm;
                transform: translate(-50%, -50%);
                background-image: url('{{ $schoolLogo }}');
                background-repeat: no-repeat;
                background-position: center center;
                background-size: contain;
                opacity: .09;
                pointer-events: none;
                z-index: 999998;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .toolbar {
                display: none !important;
            }

            .report-sheet {
                width: 100% !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 5mm 6mm 0 !important;
                border: 0 !important;
                outline: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
                position: relative !important;
                z-index: auto !important;
            }

            /* School header */
            .school-header {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 10px !important;
                padding-bottom: 7px !important;
            }

            .school-logo {
                width: 58px !important;
                height: 58px !important;
                flex: 0 0 58px !important;
            }

            .school-details h1 {
                font-size: 15px !important;
            }

            .school-details p {
                font-size: 8px !important;
            }

            /* Report heading */
            .report-heading {
                margin: 8px 0 !important;
            }

            .report-heading h2 {
                font-size: 12px !important;
            }

            .report-heading p {
                font-size: 8px !important;
            }

            /* Report information */
            .report-info {
                display: flex !important;
                flex-wrap: wrap !important;
                padding: 5px 6px !important;
                margin-bottom: 8px !important;
                font-size: 8px !important;
                background: rgba(244, 244, 244, .88) !important;
            }

            /* Class sections */
            .class-section {
                display: block !important;
                margin: 0 0 8px !important;
                break-inside: auto !important;
                page-break-inside: auto !important;
            }

            .class-title {
                padding: 5px 6px !important;
                font-size: 9px !important;
                background: rgba(231, 231, 231, .88) !important;
                break-after: avoid !important;
                page-break-after: avoid !important;
            }

            /* Transparent table backgrounds allow watermark visibility */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                background: transparent !important;
            }

            tbody,
            tbody tr,
            tbody td {
                background: transparent !important;
            }

            thead {
                display: table-header-group !important;
            }

            tfoot {
                display: table-footer-group !important;
            }

            th,
            td {
                padding: 4px 3px !important;
                font-size: 8px !important;
                line-height: 1.2 !important;
                overflow-wrap: anywhere !important;
            }

            th {
                background: rgba(222, 222, 222, .88) !important;
            }

            tr {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .total-row,
            .summary-grand-total {
                background: rgba(237, 237, 237, .88) !important;
                font-weight: 800 !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            /* Grand total */
            .grand-total {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                gap: 5px 10px !important;
                margin-top: 8px !important;
                padding: 6px !important;
                font-size: 8px !important;
                background: rgba(237, 237, 237, .88) !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            /* Signatures at bottom of each printed page */
            .signatures {
                position: fixed !important;
                left: 15mm !important;
                right: 15mm !important;
                bottom: 9mm !important;
                display: flex !important;
                justify-content: space-between !important;
                gap: 30px !important;
                margin: 0 !important;
                padding: 0 !important;
                z-index: 1000000 !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .signatures span {
                display: block !important;
                width: 40% !important;
                padding-top: 7px !important;
                border-top: 1px solid #222 !important;
                text-align: center !important;
                font-size: 8px !important;
            }

            /* Summary and full report print modes */
            body[data-print-mode="summary"] #full-report,
            body[data-print-mode="summary"] #full-report-info {
                display: none !important;
            }

            body[data-print-mode="summary"] #summary-report {
                display: block !important;
            }

            body[data-print-mode="full"] #summary-report {
                display: none !important;
            }

            body[data-print-mode="full"] #full-report,
            body[data-print-mode="full"] #full-report-info {
                display: block !important;
            }

            .empty-report {
                padding: 15px !important;
                font-size: 9px !important;
            }

            img {
                break-inside: avoid !important;
            }
        }
    </style>
</head>

<body>

    <!-- TOOLBAR -->
    <div class="toolbar no-print">
        <button type="button" onclick="printReport('summary')">
            Print Class Summary
        </button>

        <button type="button" onclick="printReport('full')">
            Print Full Caste Report
        </button>

    </div>

    <!-- REPORT -->
    <main id="caste-report-print" class="report-sheet">

        <!-- SCHOOL HEADER -->
        <header class="school-header">

            <img
                src="{{ $schoolLogo }}"
                alt="School Logo"
                class="school-logo"
                onerror="this.style.display='none'"
            >

            <div class="school-details">

                <h1>
                    {{ $school->school_name ?? config('app.name', 'School') }}
                </h1>

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
        </header>

        <!-- REPORT TITLE -->
        <section class="report-heading">

            <h2 id="report-title">
                CASTE / CATEGORY STUDENT REPORT
            </h2>

            <p>
                Academic Year: {{ $academicYearValue }}
            </p>

        </section>

        <!-- CLASS SUMMARY -->
        <section id="summary-report">

            <table class="summary-table">

                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Class</th>
                        <th>Total Students</th>
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
                            <td colspan="3" style="text-align:center">
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

        </section>

        <!-- REPORT INFORMATION -->
        <section id="full-report-info" class="report-info">

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

        </section>

        <!-- FULL CASTE REPORT -->
        <section id="full-report">

            @forelse($classGroups as $className => $classRows)

                @php
                    $casteGroups = $classRows->groupBy(function ($row) {
                        return trim((string) data_get($row, 'caste')) ?: 'Not Specified';
                    });

                    $classBoys = (int) $classRows->sum('boys');
                    $classGirls = (int) $classRows->sum('girls');
                @endphp

                <section class="class-section">

                    <h3 class="class-title">
                        Class: {{ $className }}
                    </h3>

                    <table class="caste-table">

                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Caste / Category</th>
                                <th>Boys</th>
                                <th>Girls</th>
                                <th>Total</th>
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

                                <td colspan="2">
                                    Class Total
                                </td>

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

                </section>

            @empty

                <div class="empty-report">
                    No caste report data found for the selected filters.
                </div>

            @endforelse

            <!-- GRAND TOTAL -->
            @if($reportRows->isNotEmpty())

                <div class="grand-total">

                    <span>GRAND TOTAL</span>

                    <span>
                        Boys: {{ $totalBoys }}
                    </span>

                    <span>
                        Girls: {{ $totalGirls }}
                    </span>

                    <span>
                        Students: {{ $grandTotal }}
                    </span>

                </div>

            @endif

        </section>

        <!-- SIGNATURES -->
        <footer class="signatures">

            <span>
                Class Teacher
            </span>

            <span>
                Principal / Headmaster
            </span>

        </footer>

    </main>

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

</body>
</html>
