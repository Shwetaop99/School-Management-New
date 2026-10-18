@extends('layouts.app')



@section('title', 'Exam Reports')

@section('page-title', 'Exam Reports')



@section('content')

<div class="container-fluid exam-report-page py-4">



    {{-- Screen Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h2 class="fw-bold mb-1">Exam Reports</h2>
            <p class="text-muted mb-0">Examination summary and records</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-danger" onclick="window.print()">
                <i class="fas fa-file-pdf me-1"></i>
                Print / Save as PDF
            </button>

            <button type="button" class="btn btn-success" onclick="exportExamTableToCSV()">
                <i class="fas fa-file-excel me-1"></i>
                Export Excel
            </button>
        </div>
    </div>

    {{-- Screen Summary Cards --}}

    <div class="row g-3 mb-4 no-print">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">Total Exams</div>

                    <h3 class="fw-bold mb-0">{{ $totalExams }}</h3>

                </div>

            </div>

        </div>



        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">Completed Exams</div>

                    <h3 class="fw-bold text-success mb-0">{{ $completedExams }}</h3>

                </div>

            </div>

        </div>



        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">Upcoming Exams</div>

                    <h3 class="fw-bold text-primary mb-0">{{ $upcomingExams }}</h3>

                </div>

            </div>

        </div>

    </div>



    {{-- Filters: Screen Only --}}

    <div class="card border-0 shadow-sm mb-4 no-print">

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports.exams') }}">

                <div class="row g-3 align-items-end">



                    <div class="col-md-3">

                        <label class="form-label" for="search">Search Exam</label>

                        <input

                            id="search"

                            type="text"

                            name="search"

                            class="form-control"

                            placeholder="Search exam name..."

                            value="{{ request('search') }}"

                        >

                    </div>



                    <div class="col-md-3">

                        <label class="form-label" for="academic_year">Academic Year</label>

                        <select id="academic_year" name="academic_year" class="form-select">

                            <option value="">All Academic Years</option>

                            @foreach ($academicYears as $year)

                                <option value="{{ $year }}" @selected(request('academic_year') == $year)>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    <div class="col-md-2">

                        <label class="form-label" for="exam_type">Exam Type</label>

                        <select id="exam_type" name="exam_type" class="form-select">

                            <option value="">All Types</option>

                            @foreach ($examTypes as $type)

                                <option value="{{ $type }}" @selected(request('exam_type') == $type)>

                                    {{ ucfirst($type) }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    <div class="col-md-2">

                        <label class="form-label" for="status">Status</label>

                        <select id="status" name="status" class="form-select">

                            <option value="">All Statuses</option>

                            @foreach (['upcoming', 'ongoing', 'completed', 'cancelled'] as $statusOption)

                                <option value="{{ $statusOption }}" @selected(request('status') == $statusOption)>

                                    {{ ucfirst($statusOption) }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    <div class="col-md-2 d-flex gap-2">

                        <button type="submit" class="btn btn-primary">Filter</button>

                        <a href="{{ route('admin.reports.exams') }}" class="btn btn-outline-secondary">Reset</a>

                    </div>



                </div>

            </form>

        </div>

    </div>



    {{-- Printable Report --}}

    <div class="exam-print-area">



        {{-- Report Header --}}

        <header class="report-header">

            <div class="school-name">SCHOOL MANAGEMENT</div>

            <div class="school-subtitle">Examination Department</div>

            <div class="report-heading">EXAMINATION REPORT</div>

            <div class="report-meta">

                <span>Academic Year: {{ request('academic_year') ?: 'All Academic Years' }}</span>

                <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>

            </div>

            <div class="header-line"></div>

        </header>



        {{-- Report Summary --}}

        <div class="print-summary">

            <div><strong>Total Exams:</strong> {{ $totalExams }}</div>

            <div><strong>Completed:</strong> {{ $completedExams }}</div>

            <div><strong>Upcoming:</strong> {{ $upcomingExams }}</div>

        </div>



        {{-- Exam Table --}}

        <div class="table-responsive print-table-wrap">

            <table class="table exam-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Exam Name</th>

                        <th>Academic Year</th>

                        <th>Type</th>

                        <th>Start Date</th>

                        <th>End Date</th>

                        <th>Subjects</th>

                        <th>Max Marks</th>

                        <th>Status</th>

                    </tr>

                </thead>



                <tbody>

                    @forelse ($exams as $exam)

                        @php

                            $examStatus = strtolower($exam->status ?? 'unknown');



                            $statusClass = match ($examStatus) {

                                'completed' => 'status-completed',

                                'upcoming'  => 'status-upcoming',

                                'ongoing'   => 'status-ongoing',

                                'cancelled' => 'status-cancelled',

                                default     => 'status-default',

                            };

                        @endphp



                        <tr>

                            <td>{{ $exams->firstItem() + $loop->index }}</td>

                            <td class="exam-name">{{ $exam->exam_name }}</td>

                            <td>{{ $exam->academic_year ?? '—' }}</td>

                            <td>{{ $exam->exam_type ? ucfirst($exam->exam_type) : '—' }}</td>

                            <td>

                                {{ $exam->start_date

                                    ? \Carbon\Carbon::parse($exam->start_date)->format('d/m/Y')

                                    : '—' }}

                            </td>

                            <td>

                                {{ $exam->end_date

                                    ? \Carbon\Carbon::parse($exam->end_date)->format('d/m/Y')

                                    : '—' }}

                            </td>

                            <td>{{ $exam->exam_subjects_count ?? 0 }}</td>

                            <td>{{ $exam->total_maximum_marks ?? 0 }}</td>

                            <td>

                                <span class="status-label {{ $statusClass }}">

                                    {{ ucfirst($examStatus) }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center empty-row">

                                No examination records found for the selected filters.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- Screen Pagination (keeps the active filters) --}}

        @if ($exams->hasPages())

            <div class="mt-3 no-print">

                {{ $exams->withQueryString()->links('pagination::bootstrap-5') }}

            </div>

        @endif



        {{-- Report Footer --}}

        <footer class="report-footer">

            <div>Official Examination Report</div>

            <div>Generated by School Management System</div>

        </footer>



    </div>

</div>

@endsection



@push('styles')

<style>

    /* ---------- Screen ---------- */

    .exam-report-page {

        color: #263238;

    }



    .exam-report-page .card {

        border-radius: 10px;

    }



    .exam-report-page .btn {

        border-radius: 6px;

    }



    .exam-print-area {

        background: #fff;

        padding: 24px;

        border-radius: 10px;

    }



    .report-header {

        text-align: center;

        margin-bottom: 18px;

    }



    .school-name {

        font-family: Georgia, serif;

        font-size: 25px;

        font-weight: 700;

        letter-spacing: 1.5px;

        color: #173b35;

    }



    .school-subtitle {

        font-size: 12px;

        color: #65736e;

        margin-top: 2px;

    }



    .report-heading {

        font-size: 17px;

        font-weight: 700;

        letter-spacing: 1px;

        margin-top: 15px;

    }



    .report-meta {

        display: flex;

        justify-content: space-between;

        gap: 12px;

        font-size: 11px;

        margin-top: 12px;

    }



    .header-line {

        border-bottom: 2px solid #244e43;

        margin-top: 10px;

    }



    .print-summary {

        display: flex;

        justify-content: space-between;

        gap: 12px;

        padding: 10px 12px;

        margin-bottom: 16px;

        background: #f3f7f5;

        border: 1px solid #dce7e1;

        border-radius: 5px;

        font-size: 12px;

    }



    .exam-table {

        width: 100%;

        border-collapse: collapse;

        font-size: 11px;

        margin-bottom: 0;

    }



    .exam-table th,

    .exam-table td {

        padding: 10px 8px;

        border: 1px solid #dce2df;

        vertical-align: middle;

    }



    .exam-table thead th {

        background: #eaf1ed;

        color: #203d33;

        font-weight: 700;

        white-space: nowrap;

    }



    .exam-table tbody tr:nth-child(even) {

        background: #fafcfb;

    }



    .exam-name {

        font-weight: 600;

    }



    .status-label {

        display: inline-block;

        padding: 4px 7px;

        border-radius: 4px;

        font-size: 10px;

        font-weight: 700;

        white-space: nowrap;

    }



    .status-completed { color: #17663a; background: #e2f3e8; }

    .status-upcoming  { color: #1553a4; background: #e6efff; }

    .status-ongoing   { color: #795500; background: #fff2c9; }

    .status-cancelled { color: #a12626; background: #fde7e7; }

    .status-default   { color: #4d5961; background: #edf0f2; }



    .empty-row {

        padding: 25px !important;

        color: #68756f;

    }



    .report-footer {

        display: flex;

        justify-content: space-between;

        gap: 12px;

        border-top: 1px solid #cbd6d0;

        padding-top: 9px;

        margin-top: 22px;

        font-size: 10px;

        color: #5e6e66;

    }



    /* ---------- Print ---------- */

    @page {

        size: A4 landscape;

        margin: 8mm;

    }



    @media print {

        html,

        body {

            background: #fff !important;

            -webkit-print-color-adjust: exact !important;

            print-color-adjust: exact !important;

        }



        /* Hide the dashboard chrome (sidebar, header, footer, loaders) */

        .no-print,

        #topbar,

        #pageLoader,

        .sidebar,

        .sidebar-overlay,

        .top-header,

        .app-footer {

            display: none !important;

        }



        /* Collapse the dashboard layout so the report fills the page */

        .app-wrapper,

        .main-area {

            display: block !important;

            width: 100% !important;

            min-height: 0 !important;

            margin: 0 !important;

        }



        .main-content {

            padding: 0 !important;

            background: #fff !important;

        }



        .exam-report-page,

        .exam-report-page.py-4 {

            padding: 0 !important;

            max-width: 100% !important;

        }



        .exam-print-area {

            padding: 0 !important;

            border: none !important;

            border-radius: 0 !important;

            box-shadow: none !important;

            background: #fff !important;

        }



        .report-header { margin: 0 0 8px !important; }

        .school-name { font-size: 19px !important; }

        .school-subtitle { font-size: 9px !important; }

        .report-heading { font-size: 13px !important; margin-top: 6px !important; }

        .report-meta { font-size: 8px !important; margin-top: 6px !important; }

        .header-line { margin-top: 6px !important; }



        .print-summary {

            margin: 0 0 8px !important;

            padding: 6px 8px !important;

            font-size: 9px !important;

            border-radius: 0 !important;

        }



        .print-table-wrap,

        .table-responsive {

            overflow: visible !important;

        }



        .exam-table {

            table-layout: fixed !important;

            font-size: 8px !important;

        }



        .exam-table th,

        .exam-table td {

            padding: 4px 3px !important;

            overflow-wrap: anywhere !important;

            white-space: normal !important;

            border: 1px solid #bfc9c3 !important;

        }



        /* Column widths (total 100%) */

        .exam-table th:nth-child(1) { width: 5%; }

        .exam-table th:nth-child(2) { width: 26%; }

        .exam-table th:nth-child(3) { width: 11%; }

        .exam-table th:nth-child(4) { width: 9%; }

        .exam-table th:nth-child(5) { width: 10%; }

        .exam-table th:nth-child(6) { width: 10%; }

        .exam-table th:nth-child(7) { width: 8%; }

        .exam-table th:nth-child(8) { width: 9%; }

        .exam-table th:nth-child(9) { width: 12%; }



        .exam-table thead { display: table-header-group; }   /* repeat header on every page */

        .exam-table tr { break-inside: avoid !important; page-break-inside: avoid !important; }



        .status-label { font-size: 7px !important; padding: 2px 4px !important; }



        .report-footer { margin-top: 10px !important; font-size: 8px !important; }



        * {

            animation: none !important;

            transition: none !important;

        }

    }

</style>

@endpush


@push('scripts')
<script>
    function exportExamTableToCSV() {
        const table = document.querySelector('.exam-table');
        if (!table) {
            alert('The exam table could not be found.');
            return;
        }

        const rows = Array.from(table.querySelectorAll('tr'));
        const csv = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells.map(cell => {
                const value = cell.innerText.replace(/\s+/g, ' ').trim();
                return '"' + value.replace(/"/g, '""') + '"';
            }).join(',');
        }).join('\r\n');

        const blob = new Blob(['\ufeff' + csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'exam-report.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }
</script>
@endpush
