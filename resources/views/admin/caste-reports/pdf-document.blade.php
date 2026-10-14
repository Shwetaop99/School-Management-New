````blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Caste Report</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Caste Report</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* School logo at the top */
        .school-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        /* Faint school logo watermark in the background */
        .watermark {
            position: fixed;
            top: 250px;
            left: 170px;
            width: 250px;
            height: 250px;
            opacity: 0.07;
            z-index: -1;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .school-header h1 {
            font-size: 19px;
            margin: 0 0 5px;
        }

        .school-header p {
            margin: 3px 0;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            margin: 15px 0;
        }

        .report-info {
            margin-bottom: 12px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #555;
            padding: 7px 5px;
            text-align: left;
        }

        th {
            background-color: #eaf1fb;
            font-weight: bold;
        }

        .number {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .signatures {
            margin-top: 55px;
            width: 100%;
        }

        .signatures td {
            border: none;
            text-align: center;
            padding-top: 20px;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>

<body>
    @php
        $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

        $schoolName = $school->school_name ?? config('app.name', 'School');

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
        | School Logo
        |--------------------------------------------------------------------------
        | Supports:
        | 1. Logo saved in storage/app/public
        | 2. Logo path beginning with storage/
        | 3. A public URL
        | 4. Default public/images/gurukullogo.png
        */

        $schoolLogo = $school->logo ?? null;
        $logoForPdf = null;

        if (!empty($schoolLogo)) {
            if (preg_match('~^https?://~i', $schoolLogo)) {
                // Use the URL directly if DomPDF remote access is enabled.
                $logoForPdf = $schoolLogo;
            } else {
                $relativePath = ltrim($schoolLogo, '/');

                if (str_starts_with($relativePath, 'storage/')) {
                    $relativePath = substr(
                        $relativePath,
                        strlen('storage/')
                    );
                }

                $logoPath = storage_path(
                    'app/public/' . $relativePath
                );

                if (is_file($logoPath)) {
                    $mimeType = mime_content_type($logoPath)
                        ?: 'image/png';

                    $logoForPdf = 'data:' . $mimeType . ';base64,'
                        . base64_encode(file_get_contents($logoPath));
                } elseif (is_file(public_path($relativePath))) {
                    $logoPath = public_path($relativePath);

                    $mimeType = mime_content_type($logoPath)
                        ?: 'image/png';

                    $logoForPdf = 'data:' . $mimeType . ';base64,'
                        . base64_encode(file_get_contents($logoPath));
                }
            }
        }

        // Default logo when the school profile has no usable logo.
        if (
            !$logoForPdf
            && is_file(public_path('images/gurukullogo.png'))
        ) {
            $logoPath = public_path('images/gurukullogo.png');

            $logoForPdf = 'data:image/png;base64,'
                . base64_encode(file_get_contents($logoPath));
        }

        $rows = collect($report ?? []);

        $selectedClass = request('class');

        if (!empty($selectedClass)) {
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

    {{-- Faint background school logo --}}
    @if($logoForPdf)
        <div class="watermark">
            <img src="{{ $logoForPdf }}" alt="">
        </div>
    @endif

    <div class="school-header">

        {{-- School logo at the top --}}
        @if($logoForPdf)
            <div>
                <img
                    src="{{ $logoForPdf }}"
                    alt="School Logo"
                    class="school-logo"
                >
            </div>
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

    <h2>Caste-wise Student Report</h2>

    <div class="report-info">
        <strong>Academic Year:</strong> {{ $academicYear }}<br>
        <strong>Class:</strong> {{ $selectedClass ?: 'All Classes' }}<br>
        <strong>Total Boys:</strong> {{ $totalBoys }}<br>
        <strong>Total Girls:</strong> {{ $totalGirls }}<br>
        <strong>Total Students:</strong> {{ $grandTotal }}
    </div>

    <table>
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

                <tr class="total-row">
                    <td colspan="3">Class Total: {{ $className }}</td>

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
```


        .school-header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* School logo at the top */
        .school-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        /* Faint school logo watermark in the background */
        .watermark {
            position: fixed;
            top: 250px;
            left: 170px;
            width: 250px;
            height: 250px;
            opacity: 0.07;
            z-index: -1;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .school-header h1 {
            font-size: 19px;
            margin: 0 0 5px;
        }

        .school-header p {
            margin: 3px 0;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            margin: 15px 0;
        }

        .report-info {
            margin-bottom: 12px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #555;
            padding: 7px 5px;
            text-align: left;
        }

        th {
            background-color: #eaf1fb;
            font-weight: bold;
        }

        .number {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .signatures {
            margin-top: 55px;
            width: 100%;
        }

        .signatures td {
            border: none;
            text-align: center;
            padding-top: 20px;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>

<body>
    @php
        $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

        $schoolName = $school->school_name ?? config('app.name', 'School');

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
        | School Logo
        |--------------------------------------------------------------------------
        | Supports:
        | 1. Logo saved in storage/app/public
        | 2. Logo path beginning with storage/
        | 3. A public URL
        | 4. Default public/images/gurukullogo.png
        */

        $schoolLogo = $school->logo ?? null;
        $logoForPdf = null;

        if (!empty($schoolLogo)) {
            if (preg_match('~^https?://~i', $schoolLogo)) {
                // Use the URL directly if DomPDF remote access is enabled.
                $logoForPdf = $schoolLogo;
            } else {
                $relativePath = ltrim($schoolLogo, '/');

                if (str_starts_with($relativePath, 'storage/')) {
                    $relativePath = substr(
                        $relativePath,
                        strlen('storage/')
                    );
                }

                $logoPath = storage_path(
                    'app/public/' . $relativePath
                );

                if (is_file($logoPath)) {
                    $mimeType = mime_content_type($logoPath)
                        ?: 'image/png';

                    $logoForPdf = 'data:' . $mimeType . ';base64,'
                        . base64_encode(file_get_contents($logoPath));
                } elseif (is_file(public_path($relativePath))) {
                    $logoPath = public_path($relativePath);

                    $mimeType = mime_content_type($logoPath)
                        ?: 'image/png';

                    $logoForPdf = 'data:' . $mimeType . ';base64,'
                        . base64_encode(file_get_contents($logoPath));
                }
            }
        }

        // Default logo when the school profile has no usable logo.
        if (
            !$logoForPdf
            && is_file(public_path('images/gurukullogo.png'))
        ) {
            $logoPath = public_path('images/gurukullogo.png');

            $logoForPdf = 'data:image/png;base64,'
                . base64_encode(file_get_contents($logoPath));
        }

        $rows = collect($report ?? []);

        $selectedClass = request('class');

        if (!empty($selectedClass)) {
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

    {{-- Faint background school logo --}}
    @if($logoForPdf)
        <div class="watermark">
            <img src="{{ $logoForPdf }}" alt="">
        </div>
    @endif

    <div class="school-header">

        {{-- School logo at the top --}}
        @if($logoForPdf)
            <div>
                <img
                    src="{{ $logoForPdf }}"
                    alt="School Logo"
                    class="school-logo"
                >
            </div>
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

    <h2>Caste-wise Student Report</h2>

    <div class="report-info">
        <strong>Academic Year:</strong> {{ $academicYear }}<br>
        <strong>Class:</strong> {{ $selectedClass ?: 'All Classes' }}<br>
        <strong>Total Boys:</strong> {{ $totalBoys }}<br>
        <strong>Total Girls:</strong> {{ $totalGirls }}<br>
        <strong>Total Students:</strong> {{ $grandTotal }}
    </div>

    <table>
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

                <tr class="total-row">
                    <td colspan="3">Class Total: {{ $className }}</td>

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
````
