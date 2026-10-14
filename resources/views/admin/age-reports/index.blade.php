@extends('layouts.app')

@section('title', 'Age Report')

@section('content')

<style>
.age-report-page {
    background: #f5f7fb;
    min-height: calc(100vh - 70px);
    padding: 24px;
    color: #172033;
    font-family: Arial, sans-serif;
}

.age-report-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 22px;
}

.age-report-title-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
}

.age-report-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e8f1ff;
    color: #1677f0;
    font-size: 23px;
}

.age-report-title h3 {
    margin: 0;
    font-size: 25px;
    font-weight: 700;
}

.age-report-title p {
    margin: 4px 0 0;
    color: #7b8497;
    font-size: 14px;
}

.age-report-actions {
    display: flex;
    gap: 8px;
}

.age-report-actions .btn,
.filter-buttons .btn {
    min-height: 42px;
    border-radius: 8px;
    font-weight: 600;
}

.filter-card,
.report-card,
.summary-card {
    background: #fff;
    border: 1px solid #e8ebf2;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(26, 39, 65, .05);
}

.filter-card {
    margin-bottom: 22px;
    overflow: hidden;
}

.filter-card-header,
.report-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-card-header h5,
.report-card-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.filter-card-body {
    padding: 20px;
}

.filter-label {
    display: block;
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 600;
    color: #505b70;
}

.filter-control {
    min-height: 42px;
    border: 1px solid #dfe4ec;
    border-radius: 8px;
    font-size: 14px;
    box-shadow: none !important;
}

.filter-control:focus {
    border-color: #1677f0;
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.summary-card {
    padding: 18px;
    height: 100%;
}

.summary-label {
    color: #737d90;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
}

.summary-value {
    font-size: 26px;
    line-height: 1.2;
    font-weight: 700;
}

.summary-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    color: #1769d1;
    border-radius: 10px;
    font-size: 20px;
}

.report-card {
    overflow: hidden;
}

.report-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.report-table {
    width: 100%;
    min-width: 850px;
    margin: 0;
    border-collapse: collapse;
}

.report-table thead th {
    background: #172033;
    color: #fff;
    border: 1px solid #30394c;
    padding: 11px 8px;
    font-size: 12px;
    text-align: center;
    white-space: nowrap;
}

.report-table tbody td {
    padding: 9px 8px;
    border: 1px solid #e2e6ed;
    font-size: 13px;
    text-align: center;
    vertical-align: middle;
}

.report-table .age-group-cell {
    text-align: left;
    font-weight: 700;
    padding-left: 14px;
}

.age-group-badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 6px;
    background: #eef5ff;
    color: #1769d1;
    font-weight: 700;
}

.gender-boys {
    color: #1769d1;
    font-weight: 600;
}

.gender-girls {
    color: #b13c78;
    font-weight: 600;
}

.gender-total,
.total-cell {
    font-weight: 700;
}

.total-cell {
    background: #f7f9fc;
}

.age-total-row td {
    background: #f8fafc;
    font-weight: 700;
}

.grand-total-row td {
    background: #172033;
    color: #fff;
    font-weight: 700;
}

.empty-report {
    padding: 45px 20px;
    text-align: center;
    color: #737d90;
}

.empty-report i {
    font-size: 34px;
    display: block;
    margin-bottom: 10px;
}

/* PRINT-ONLY REPORT HEADER */
.print-report-header {
    display: none;
}

@media (max-width: 767px) {
    .age-report-page {
        padding: 12px;
    }

    .age-report-title h3 {
        font-size: 21px;
    }

    .filter-card-body {
        padding: 14px;
    }
}

/*
|--------------------------------------------------------------------------
| PRINT ONLY THE REPORT
|--------------------------------------------------------------------------
| Hide the whole dashboard and show only #age-report-print-area.
*/
@media print {
    @page {
        size: A4 landscape;
        margin: 7mm;
    }

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    body * {
        visibility: hidden !important;
    }

    #age-report-print-area,
    #age-report-print-area * {
        visibility: visible !important;
    }

    #age-report-print-area {
        display: block !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        color: #000 !important;
        border: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .no-print {
        display: none !important;
    }

    .print-report-header {
        display: block !important;
        text-align: center;
        margin-bottom: 12px;
        color: #000 !important;
    }

    .print-report-header h2 {
        margin: 0 0 4px;
        font-size: 19px;
        font-weight: 700;
    }

    .print-report-header p {
        margin: 3px 0;
        font-size: 10px;
    }

    .age-report-header {
        display: none !important;
    }

    .summary-cards-print {
        display: flex !important;
        gap: 10px;
        margin: 10px 0;
    }

    .summary-cards-print > div {
        flex: 1;
    }

    .summary-card {
        padding: 7px !important;
        border: 1px solid #999 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    .summary-label {
        color: #333 !important;
        font-size: 9px !important;
    }

    .summary-value {
        color: #000 !important;
        font-size: 14px !important;
    }

    .summary-icon {
        display: none !important;
    }

    .report-card {
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .report-card-header {
        padding: 5px 0 !important;
        border-bottom: 1px solid #444 !important;
    }

    .report-card-header h5,
    .report-card-header span {
        color: #000 !important;
        font-size: 10px !important;
    }

    .report-table-wrapper {
        width: 100% !important;
        overflow: visible !important;
    }

    .report-table {
        width: 100% !important;
        min-width: 0 !important;
        table-layout: auto !important;
        border-collapse: collapse !important;
    }

    .report-table thead {
        display: table-header-group;
    }

    .report-table thead th {
        background: #e8e8e8 !important;
        color: #000 !important;
        border: 1px solid #555 !important;
        font-size: 8px !important;
        padding: 4px 3px !important;
        white-space: normal !important;
    }

    .report-table tbody td {
        color: #000 !important;
        border: 1px solid #777 !important;
        font-size: 8px !important;
        padding: 4px 3px !important;
    }

    .age-group-badge {
        background: transparent !important;
        color: #000 !important;
        padding: 0 !important;
    }

    .total-cell,
    .age-total-row td,
    .grand-total-row td {
        background: #eee !important;
        color: #000 !important;
    }

    .report-table tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

@php
    $selectedAcademicYear = $selectedAcademicYear ?? request('academic_year', '');
    $selectedClass = $selectedClass ?? request('class', '');
    $selectedSection = $selectedSection ?? request('section', '');
    $selectedGender = $selectedGender ?? request('gender', '');
    $ageAsOn = $ageAsOn ?? request('age_as_on', now()->format('Y-m-d'));

    $report = collect($report ?? []);
    $classes = collect($classes ?? []);
    $sections = collect($sections ?? []);
    $academicYears = collect($academicYears ?? []);
    $ageGroups = collect($ageGroups ?? []);

    $normalizeClass = function ($value) {
        return strtolower(trim(preg_replace('/^class\s*/i', '', (string) $value)));
    };

    $tableClasses = $selectedClass !== ''
        ? $classes->filter(function ($class) use ($normalizeClass, $selectedClass) {
            return $normalizeClass($class) === $normalizeClass($selectedClass);
        })->values()
        : $classes;

    $reportLookup = [];

    foreach ($report as $row) {
        $ageKey = trim((string) $row->age_group);
        $classKey = $normalizeClass($row->class);
        $reportLookup[$ageKey][$classKey] = $row;
    }

    $grandClassTotals = [];

    foreach ($tableClasses as $class) {
        $grandClassTotals[$class] = [
            'boys' => 0,
            'girls' => 0,
            'total' => 0,
        ];
    }

    $displayTotal = 0;
    $displayBoys = 0;
    $displayGirls = 0;

    foreach ($report as $row) {
        if ($selectedClass !== '' && $normalizeClass($row->class) !== $normalizeClass($selectedClass)) {
            continue;
        }

        $displayTotal += (int) ($row->total ?? 0);
        $displayBoys += (int) ($row->boys ?? 0);
        $displayGirls += (int) ($row->girls ?? 0);
    }

    $schoolSetting = $schoolSetting ?? null;

    $getSchoolValue = function (array $keys, $default = '') use ($schoolSetting) {
        foreach ($keys as $key) {
            $value = is_array($schoolSetting)
                ? ($schoolSetting[$key] ?? null)
                : data_get($schoolSetting, $key);

            if (is_string($value) || is_numeric($value)) {
                if (trim((string) $value) !== '') {
                    return trim((string) $value);
                }
            }
        }

        return $default;
    };

    $schoolName = $getSchoolValue(
        ['school_name', 'name', 'institution_name'],
        config('app.name', 'School Management System')
    );

    $schoolAddress = $getSchoolValue(['address', 'school_address']);
    $schoolPhone = $getSchoolValue(['phone', 'phone_number', 'mobile', 'contact_number']);
@endphp

<div class="age-report-page">

    <div class="age-report-header no-print">
        <div class="age-report-title-wrap">
            <div class="age-report-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="age-report-title">
                <h3>Age Report</h3>
                <p>Age-group-wise boys and girls summary</p>
            </div>
        </div>

        <div class="age-report-actions">
            <button type="button" class="btn btn-primary" onclick="printAgeReport()">
                <i class="bi bi-printer-fill me-1"></i>
                Print Report
            </button>
        </div>
    </div>

    <div class="filter-card no-print">
        <div class="filter-card-header">
            <h5>
                <i class="bi bi-funnel-fill text-primary me-2"></i>
                Report Filters
            </h5>

            @if($selectedAcademicYear || $selectedClass || $selectedSection || $selectedGender || request('age_as_on'))
                <span class="badge bg-primary">Filters Applied</span>
            @endif
        </div>

        <div class="filter-card-body">
            <form method="GET" action="{{ route('admin.age-report.index') }}">
                <div class="row g-3">

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Academic Year</label>
                        <select name="academic_year" class="form-select filter-control">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" @selected((string) $selectedAcademicYear === (string) $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Class</label>
                        <select name="class" class="form-select filter-control">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" @selected((string) $selectedClass === (string) $class)>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Section</label>
                        <select name="section" class="form-select filter-control">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" @selected((string) $selectedSection === (string) $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Gender</label>
                        <select name="gender" class="form-select filter-control">
                            <option value="">All Genders</option>
                            <option value="Male" @selected(strtolower((string) $selectedGender) === 'male')>Male</option>
                            <option value="Female" @selected(strtolower((string) $selectedGender) === 'female')>Female</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Age As On</label>
                        <input
                            type="date"
                            name="age_as_on"
                            class="form-control filter-control"
                            value="{{ $ageAsOn }}"
                            max="{{ now()->format('Y-m-d') }}"
                        >
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">&nbsp;</label>
                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Apply
                            </button>

                            <a href="{{ route('admin.age-report.index') }}"
                               class="btn btn-outline-secondary"
                               title="Reset Filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Everything inside this element is the only content printed. --}}
    <div id="age-report-print-area">

        <div class="print-report-header">
            <h2>{{ $schoolName }}</h2>

            @if($schoolAddress)
                <p>{{ $schoolAddress }}</p>
            @endif

            @if($schoolPhone)
                <p>Phone: {{ $schoolPhone }}</p>
            @endif

            <h3 style="font-size:14px; margin:10px 0 3px;">STUDENT AGE REPORT</h3>

            <p>
                Academic Year:
                {{ $selectedAcademicYear ?: 'All Academic Years' }}
                &nbsp; | &nbsp;
                Age As On: {{ \Carbon\Carbon::parse($ageAsOn)->format('d-m-Y') }}
            </p>

            @if($selectedClass)
                <p>Class: {{ $selectedClass }}</p>
            @endif

            @if($selectedSection)
                <p>Section: {{ $selectedSection }}</p>
            @endif

            @if($selectedGender)
                <p>Gender: {{ $selectedGender }}</p>
            @endif
        </div>

        <div class="summary-cards-print row g-3 mb-4">
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Total Students</div>
                    <div class="summary-value">{{ $displayTotal }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Boys</div>
                    <div class="summary-value">{{ $displayBoys }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Girls</div>
                    <div class="summary-value">{{ $displayGirls }}</div>
                </div>
            </div>
        </div>

        {{-- Summary cards are also visible on the normal screen. --}}
        <div class="row g-3 mb-4 no-print">
            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Total Students</div>
                            <div class="summary-value">{{ $displayTotal }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Boys</div>
                            <div class="summary-value">{{ $displayBoys }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-person-fill"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Girls</div>
                            <div class="summary-value">{{ $displayGirls }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-person-heart"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="report-card">
            <div class="report-card-header">
                <h5>
                    <i class="bi bi-table text-primary me-2"></i>
                    Age Group Summary
                </h5>

                <span class="text-secondary small">
                    Age As On:
                    <strong>{{ \Carbon\Carbon::parse($ageAsOn)->format('d-m-Y') }}</strong>
                </span>
            </div>

            @if($report->isNotEmpty() && $tableClasses->isNotEmpty())

                <div class="report-table-wrapper">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Age Group</th>
                                <th>Gender</th>

                                @foreach($tableClasses as $class)
                                    <th>{{ $class }}</th>
                                @endforeach

                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ageGroups as $ageGroup)

                                @php
                                    $ageBoys = 0;
                                    $ageGirls = 0;
                                    $ageTotal = 0;
                                    $classData = [];

                                    foreach ($tableClasses as $class) {
                                        $classKey = $normalizeClass($class);
                                        $matchedRow = $reportLookup[$ageGroup][$classKey] ?? null;

                                        $boys = (int) ($matchedRow->boys ?? 0);
                                        $girls = (int) ($matchedRow->girls ?? 0);
                                        $total = (int) ($matchedRow->total ?? ($boys + $girls));

                                        $classData[$class] = [
                                            'boys' => $boys,
                                            'girls' => $girls,
                                            'total' => $total,
                                        ];

                                        $ageBoys += $boys;
                                        $ageGirls += $girls;
                                        $ageTotal += $total;

                                        $grandClassTotals[$class]['boys'] += $boys;
                                        $grandClassTotals[$class]['girls'] += $girls;
                                        $grandClassTotals[$class]['total'] += $total;
                                    }
                                @endphp

                                <tr>
                                    <td class="age-group-cell" rowspan="3">
                                        <span class="age-group-badge">{{ $ageGroup }}</span>
                                    </td>
                                    <td class="gender-boys">Boys</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['boys'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageBoys }}</td>
                                </tr>

                                <tr>
                                    <td class="gender-girls">Girls</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['girls'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageGirls }}</td>
                                </tr>

                                <tr class="age-total-row">
                                    <td class="gender-total">Total</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['total'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageTotal }}</td>
                                </tr>

                            @endforeach

                            <tr class="grand-total-row">
                                <td colspan="2">Grand Total</td>

                                @foreach($tableClasses as $class)
                                    <td>{{ $grandClassTotals[$class]['total'] }}</td>
                                @endforeach

                                <td>{{ $displayTotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            @else
                <div class="empty-report">
                    <i class="bi bi-calendar-x"></i>
                    <h6>No Age Report Data Found</h6>
                    <p>No students match the selected filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function printAgeReport() {
    /*
     * Use the browser's print dialog. The print CSS hides the dashboard
     * and displays only the Age Report print area.
     */
    window.print();
}
</script>

@endsection
