```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caste-wise Student Report</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            background: #fff;
        }

        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 15px;
            position: relative;
        }

        .school-logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
            display: block;
            margin: 0 auto 7px;
        }

        .school-header h1 {
            font-size: 19px;
            margin: 0 0 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .school-header p {
            margin: 3px 0;
            line-height: 1.5;
        }

        /*
         * Faint school logo watermark behind the report.
         * Fixed positioning supports DomPDF.
         */
        .watermark {
            position: fixed;
            top: 30%;
            left: 25%;
            width: 50%;
            height: 38%;
            opacity: 0.07;
            z-index: -1;
        }

        .watermark img {
            width: 100%;
            height: 100%;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            margin: 14px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-info {
            width: 100%;
            margin-bottom: 12px;
            line-height: 1.8;
        }

        .report-info td {
            border: none;
            padding: 2px 4px;
            vertical-align: top;
        }

        .report-info .label {
            font-weight: bold;
            width: 18%;
        }

        .report-info .value {
            width: 32%;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #555;
            padding: 7px 5px;
            text-align: left;
        }

        .report-table th {
            background-color: #eaf1fb;
            font-weight: bold;
            text-align: center;
        }

        .report-table td.number {
            text-align: center;
        }

        .report-table .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .report-table thead {
            display: table-header-group;
        }

        .report-table tr {
            page-break-inside: avoid;
        }

        .signatures {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
        }

        .signatures td {
            border: none;
            text-align: center;
            padding: 20px 5px 0;
            width: 33.33%;
        }

        .footer {
            margin-top: 18px;
            text-align: right;
            font-size: 9px;
        }

        @media screen {
            body {
                max-width: 210mm;
                margin: 20px auto;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    @php
        $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

        $schoolName = $school->school_name
            ?? config('app.name', 'School');

        $addressParts = array_filter([
            $school->address ?? null,
            $school->city ?? null,
            $school->district ?? null,
            $school->state ?? null,
            $school->pincode ?? null,
        ]);

        $schoolAddress = implode(', ', $addressParts);

        /*
        |--------------------------------------------------------------------------
        | Resolve school logo for DomPDF
        |--------------------------------------------------------------------------
        | Supports local storage files, public files, and remote URLs.
        */

        $schoolLogo = $school->logo ?? null;
        $logoForPdf = null;

        if (!empty($schoolLogo)) {
            if (preg_match('~^https?://~i', $schoolLogo)) {
                // Remote URL: DomPDF remote access must be enabled.
                $logoForPdf = $schoolLogo;
            } else {
                $relativePath = ltrim($schoolLogo, '/');

                if (str_starts_with($relativePath, 'storage/')) {
                    $relativePath = substr(
                        $relativePath,
                        strlen('storage/')
                    );
                }

                $candidatePaths = [
                    storage_path('app/public/' . $relativePath),
                    public_path('storage/' . $relativePath),
                    public_path(ltrim($schoolLogo, '/')),
                ];

                foreach ($candidatePaths as $candidatePath) {
                    if (is_file($candidatePath)) {
                        $mimeType = mime_content_type($candidatePath)
                            ?: 'image/png';

                        $logoForPdf = 'data:' . $mimeType . ';base64,'
                            . base64_encode(
                                file_get_contents($candidatePath)
                            );

                        break;
                    }
                }
            }
        }

        // Use the default logo if the configured logo cannot be found.
        if (
            !$logoForPdf
            && is_file(public_path('images/gurukullogo.png'))
        ) {
            $defaultLogoPath = public_path('images/gurukullogo.png');

            $logoForPdf = 'data:image/png;base64,'
                . base64_encode(file_get_contents($defaultLogoPath));
        }

        /*
        |--------------------------------------------------------------------------
        | Report data
        |--------------------------------------------------------------------------
        */

        $rows = collect($report ?? []);

        $selectedClass = request('class');

        if ($selectedClass !== null && $selectedClass !== '') {
            $rows = $rows->filter(function ($row) use ($selectedClass) {
                return (string) data_get($row, 'class')
                    === (string) $selectedClass;
            });
        }

        $rows = $rows->sortBy([
            ['class', 'asc'],
            ['caste', 'asc'],
        ])->values();

        $academicYear = $academicYear
            ?? data_get($selectedFilters ?? [], 'academic_year')
            ?? request('academic_year')
            ?? 'All Academic Years';

        $totalBoys = $rows->sum(
            fn ($row) => (int) data_get($row, 'boys', 0)
        );

        $totalGirls = $rows->sum(
            fn ($row) => (int) data_get($row, 'girls', 0)
        );

        $grandTotal = $totalBoys + $totalGirls;

        $classGroups = $rows->groupBy('class');
    @endphp

    {{-- Faint watermark behind the report content --}}
    @if($logoForPdf)
        <div class="watermark">
            <img src="{{ $logoForPdf }}" alt="">
        </div>
    @endif

    {{-- School logo and details --}}
    <div class="school-header">
        {{-- School logo at the top --}}
        @if($logoForPdf)
            <img
                src="{{ $logoForPdf }}"
                alt="School Logo"
                class="school-logo"
            >
        @endif

        <h1>{{ $schoolName }}</h1>

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

    <h2 class="report-title">Caste-wise Student Report</h2>

    {{-- Report summary --}}
    <table class="report-info">
        <tr>
            <td class="label">Academic Year:</td>
            <td class="value">{{ $academicYear }}</td>

            <td class="label">Class:</td>
            <td class="value">
                {{ $selectedClass ?: 'All Classes' }}
            </td>
        </tr>

        <tr>
            <td class="label">Total Boys:</td>
            <td class="value">{{ $totalBoys }}</td>

            <td class="label">Total Girls:</td>
            <td class="value">{{ $totalGirls }}</td>
        </tr>

        <tr>
            <td class="label">Total Students:</td>
            <td class="value">{{ $grandTotal }}</td>

            <td class="label">Generated:</td>
            <td class="value">{{ now()->format('d-m-Y') }}</td>
        </tr>
    </table>

    {{-- Caste report table --}}
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 8%;">Sr. No.</th>
                <th style="width: 25%;">Class</th>
                <th style="width: 35%;">Caste</th>
                <th style="width: 10%;">Boys</th>
                <th style="width: 10%;">Girls</th>
                <th style="width: 12%;">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($classGroups as $className => $classRows)
                @foreach($classRows as $row)
                    <tr>
                        <td class="number">
                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                        </td>

                        <td>{{ data_get($row, 'class', '-') }}</td>

                        <td>{{ data_get($row, 'caste', '-') }}</td>

                        <td class="number">
                            {{ (int) data_get($row, 'boys', 0) }}
                        </td>

                        <td class="number">
                            {{ (int) data_get($row, 'girls', 0) }}
                        </td>

                        <td class="number">
                            {{ (int) data_get($row, 'boys', 0)
                                + (int) data_get($row, 'girls', 0) }}
                        </td>
                    </tr>
                @endforeach

                {{-- Class-wise totals --}}
                <tr class="total-row">
                    <td colspan="3">
                        Class Total: {{ $className }}
                    </td>

                    <td class="number">
                        {{ $classRows->sum(
                            fn ($row) => (int) data_get($row, 'boys', 0)
                        ) }}
                    </td>

                    <td class="number">
                        {{ $classRows->sum(
                            fn ($row) => (int) data_get($row, 'girls', 0)
                        ) }}
                    </td>

                    <td class="number">
                        {{ $classRows->sum(
                            fn ($row) =>
                                (int) data_get($row, 'boys', 0)
                                + (int) data_get($row, 'girls', 0)
                        ) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="number">
                        No caste report data found.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="3">Grand Total</td>
                <td class="number">{{ $totalBoys }}</td>
                <td class="number">{{ $totalGirls }}</td>
                <td class="number">{{ $grandTotal }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Signatures --}}
    <table class="signatures">
        <tr>
            <td>Prepared By: __________________</td>
            <td>Class Teacher: __________________</td>
            <td>Principal's Signature: __________________</td>
        </tr>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('d-m-Y h:i A') }}
    </div>
</body>
</html>
