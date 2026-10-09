```blade
@extends('layouts.app')

@section('title', 'Print Caste Report')

@section('content')

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

    $selectedClass = request('class', '');

    if ($selectedClass !== '') {
        $reportRows = $reportRows->filter(function ($row) use ($selectedClass) {
            return trim((string) data_get($row, 'class')) === trim($selectedClass);
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
@endphp

<style>
    .caste-report-page {
        position: relative;
        isolation: isolate;
        background: #fff;
        padding: 25px;
        color: #222;
        min-height: 500px;
    }

    .caste-report-page::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 320px;
        height: 320px;
        transform: translate(-50%, -50%);
        background-image: var(--school-watermark);
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        opacity: 0.07;
        pointer-events: none;
        z-index: -1;
    }

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

    .report-heading {
        text-align: center;
        margin: 15px 0;
    }

    .report-heading h3 {
        margin: 0 0 5px;
        font-size: 18px;
    }

    .report-heading p {
        margin: 0;
        font-size: 12px;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

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

    table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
    }

    th, td {
        border: 1px solid #777;
        padding: 8px;
        font-size: 12px;
    }

    th {
        background: #eaf1fb;
        text-align: left;
    }

    .number {
        text-align: center;
    }

    .total-row {
        font-weight: bold;
        background: #f1f1f1;
    }

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

    .signatures {
        display: flex;
        justify-content: space-between;
        gap: 20px;
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
    }

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

        .caste-report-page::before {
            position: fixed;
            opacity: 0.07;
            z-index: -1;
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

        .report-info {
            padding: 7px;
            gap: 8px 15px;
        }

        th, td {
            padding: 6px;
            font-size: 9px;
            overflow-wrap: anywhere;
        }

        .class-section {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .grand-total {
            break-inside: avoid;
            page-break-inside: avoid;
        }

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

<div
    class="caste-report-page"
    id="caste-report-print"
    style="--school-watermark: url('{{ $schoolLogo }}');"
>
    {{-- SCHOOL HEADER --}}
    <div class="school-header">
        <img
            src="{{ $schoolLogo }}"
            alt="School Logo"
            class="school-logo"
            onerror="this.style.display='none'"
        >

        <div class="school-details">
            <h2>{{ $school->school_name ?? config('app.name', 'School') }}</h2>

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

    {{-- REPORT TITLE --}}
    <div class="report-heading">
        <h3>CASTE / CATEGORY STUDENT REPORT</h3>
        <p>Academic Year: {{ $academicYearValue }}</p>
    </div>

    {{-- ACTIONS --}}
    <div class="report-actions no-print">
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Report
        </button>

        <a
            class="btn btn-danger"
            href="{{ route('admin.caste-report.pdf-download', request()->query()) }}"
        >
            <i class="bi bi-file-earmark-pdf"></i> Download PDF
        </a>

        <a
            class="btn btn-outline-secondary"
            href="{{ route('admin.caste-report.index', request()->query()) }}"
        >
            Back to Report
        </a>
    </div>

    {{-- REPORT SUMMARY --}}
    <div class="report-info">
        <span><strong>Class:</strong> {{ $selectedClass ?: 'All Classes' }}</span>
        <span><strong>Total Categories:</strong> {{ $totalCastes }}</span>
        <span><strong>Total Boys:</strong> {{ $totalBoys }}</span>
        <span><strong>Total Girls:</strong> {{ $totalGirls }}</span>
        <span><strong>Total Students:</strong> {{ $grandTotal }}</span>
    </div>

    {{-- CLASS-WISE TABLES --}}
    @forelse($classGroups as $className => $classRows)
        @php
            $casteGroups = $classRows->groupBy(function ($row) {
                return trim((string) data_get($row, 'caste')) ?: 'Not Specified';
            });

            $classBoys = (int) $classRows->sum('boys');
            $classGirls = (int) $classRows->sum('girls');
        @endphp

        <div class="class-section">
            <h4 class="class-title">Class: {{ $className }}</h4>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Sr. No.</th>
                        <th style="width: 40%;">Caste / Category</th>
                        <th class="number" style="width: 15%;">Boys</th>
                        <th class="number" style="width: 15%;">Girls</th>
                        <th class="number" style="width: 20%;">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($casteGroups as $casteName => $casteRows)
                        @php
                            $boys = (int) $casteRows->sum('boys');
                            $girls = (int) $casteRows->sum('girls');
                        @endphp

                        <tr>
                            <td class="number">{{ $loop->iteration }}</td>
                            <td>{{ $casteName }}</td>
                            <td class="number">{{ $boys }}</td>
                            <td class="number">{{ $girls }}</td>
                            <td class="number">{{ $boys + $girls }}</td>
                        </tr>
                    @endforeach

                    <tr class="total-row">
                        <td colspan="2">Class Total</td>
                        <td class="number">{{ $classBoys }}</td>
                        <td class="number">{{ $classGirls }}</td>
                        <td class="number">{{ $classBoys + $classGirls }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @empty
        <div class="empty-report">
            No caste report data found for the selected filters.
        </div>
    @endforelse

    {{-- GRAND TOTAL --}}
    @if($reportRows->isNotEmpty())
        <div class="grand-total">
            <span>GRAND TOTAL</span>
            <span>Boys: {{ $totalBoys }}</span>
            <span>Girls: {{ $totalGirls }}</span>
            <span>Students: {{ $grandTotal }}</span>
        </div>
    @endif

    {{-- SIGNATURES --}}
    <div class="signatures">
        <span>Class Teacher</span>
        <span>Principal / Headmaster</span>
    </div>
</div>

@endsection
```
