@extends('layouts.app')

@section('title', 'Faculty Attendance Reports | Admin')
@section('page-title', 'Faculty Attendance Reports')

@section('content')

<div class="payroll-simple faculty-attendance-page">
    <div class="container-fluid py-4">

        <style>
            .faculty-attendance-page {
                background: #f5f4f0;
                color: #1e2a25;
                min-height: 100vh;
                padding: 10px;
            }

            .faculty-attendance-page .back-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #fff;
                color: #2c3e36;
                border: 1px solid #d4cfc2;
                border-radius: 9px;
                padding: 10px 17px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                margin-bottom: 18px;
                transition: .2s ease;
            }

            .faculty-attendance-page .back-btn:hover {
                background: #f3ead8;
                color: #2c3e36;
            }

            .faculty-attendance-page .report-hero {
                display: grid;
                grid-template-columns: 1.4fr 1fr;
                background: #fdfbf7;
                border: 1px solid #d4cfc2;
                border-radius: 18px;
                overflow: hidden;
                margin-bottom: 22px;
            }

            .faculty-attendance-page .hero-main {
                padding: 28px;
            }

            .faculty-attendance-page .hero-side {
                background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
                padding: 28px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .faculty-attendance-page .eyebrow {
                color: #5c6b64;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
            }

            .faculty-attendance-page .hero-title {
                font-size: 28px;
                font-weight: 800;
                color: #1e2a25;
                margin: 10px 0;
            }

            .faculty-attendance-page .hero-description {
                color: #5c6b64;
                font-size: 13px;
                line-height: 1.8;
                max-width: 650px;
            }

            .faculty-attendance-page .hero-number {
                font-size: 38px;
                font-weight: 800;
                color: #2c3e36;
                margin: 8px 0;
            }

            .faculty-attendance-page .filter-panel,
            .faculty-attendance-page .report-card {
                background: #fff;
                border: 1px solid #d4cfc2;
                border-radius: 15px;
                padding: 20px;
                margin-bottom: 22px;
                box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
            }

            .faculty-attendance-page .section-heading {
                font-size: 17px;
                font-weight: 800;
                color: #1e2a25;
                margin-bottom: 17px;
            }

            .faculty-attendance-page .form-label {
                color: #5c6b64;
                font-size: 12px;
                font-weight: 700;
            }

            .faculty-attendance-page .form-control,
            .faculty-attendance-page .form-select {
                border: 1px solid #d4cfc2;
                border-radius: 9px;
                min-height: 42px;
                font-size: 13px;
            }

            .faculty-attendance-page .btn-primary-custom {
                background: #2c3e36;
                color: #fff;
                border: 0;
                border-radius: 9px;
                padding: 10px 17px;
                font-size: 13px;
                font-weight: 700;
            }

            .faculty-attendance-page .btn-primary-custom:hover {
                background: #3a4f45;
                color: #fff;
            }

            .faculty-attendance-page .btn-light-custom {
                background: #f3ead8;
                color: #2c3e36;
                border: 1px solid #d4cfc2;
                border-radius: 9px;
                padding: 10px 17px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
            }

            .faculty-attendance-page .stat-card {
                background: #fff;
                border: 1px solid #d4cfc2;
                border-radius: 14px;
                padding: 18px;
                height: 100%;
            }

            .faculty-attendance-page .stat-label {
                color: #5c6b64;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .6px;
            }

            .faculty-attendance-page .stat-value {
                color: #2c3e36;
                font-size: 27px;
                font-weight: 800;
                margin-top: 8px;
            }

            .faculty-attendance-page .stat-green {
                color: #1a6b3c;
            }

            .faculty-attendance-page .table-wrap {
                overflow-x: auto;
            }

            .faculty-attendance-page .table {
                min-width: 900px;
                margin-bottom: 0;
                vertical-align: middle;
            }

            .faculty-attendance-page .table thead th {
                background: #f5f4f0;
                color: #5c6b64;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .5px;
                padding: 14px;
                border-bottom: 1px solid #d4cfc2;
                white-space: nowrap;
            }

            .faculty-attendance-page .table tbody td {
                padding: 14px;
                color: #39483e;
                font-size: 13px;
                border-color: #eeeae1;
            }

            .faculty-attendance-page .faculty-name {
                font-weight: 800;
                color: #2c3e36;
            }

            .faculty-attendance-page .muted {
                color: #7b857b;
                font-size: 11px;
                margin-top: 3px;
            }

            .faculty-attendance-page .status-badge {
                display: inline-block;
                padding: 5px 9px;
                border-radius: 30px;
                font-size: 11px;
                font-weight: 800;
            }

            .faculty-attendance-page .status-present {
                background: #e4f2e8;
                color: #1a6b3c;
            }

            .faculty-attendance-page .status-absent {
                background: #f9e7e5;
                color: #b3443b;
            }

            .faculty-attendance-page .status-half {
                background: #fff0d9;
                color: #9b671f;
            }

            .faculty-attendance-page .status-leave {
                background: #f3ead8;
                color: #85612e;
            }

            .faculty-attendance-page .progress {
                height: 7px;
                background: #eeeae1;
                border-radius: 20px;
                margin-top: 7px;
                min-width: 75px;
            }

            .faculty-attendance-page .progress-bar {
                background: #1a6b3c;
                border-radius: 20px;
            }

            .faculty-attendance-page .empty-state {
                text-align: center;
                padding: 35px !important;
                color: #7b857b !important;
            }

            .faculty-attendance-page .pagination {
                margin-bottom: 0;
            }

            .faculty-attendance-page .pagination .page-link {
                color: #2c3e36;
                border-color: #e5e0d5;
                font-size: 12px;
            }

            .faculty-attendance-page .pagination .active .page-link {
                background: #2c3e36;
                border-color: #2c3e36;
                color: #fff;
            }

            @media(max-width: 768px) {
                .faculty-attendance-page .report-hero {
                    grid-template-columns: 1fr;
                }

                .faculty-attendance-page .hero-title {
                    font-size: 23px;
                }

                .faculty-attendance-page .filter-panel,
                .faculty-attendance-page .report-card {
                    padding: 15px;
                }
            }
        </style>

        {{-- BACK BUTTON --}}
        <a href="{{ route('admin.reports.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Reports
        </a>

        {{-- REPORT HERO --}}
        <div class="report-hero">
            <div class="hero-main">
                <div class="eyebrow">School Management / Reports</div>

                <div class="hero-title">
                    Faculty Attendance Report
                </div>

                <div class="hero-description">
                    Monitor faculty attendance, working days and leave records.
                    Search faculty members and review their individual
                    attendance and salary profiles.
                </div>

                <div class="mt-3">
                    <span class="eyebrow">Report Period</span>
                    <div class="fw-bold mt-1">
                        {{ $startDate->format('d M Y') }}
                        —
                        {{ $endDate->format('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="hero-side">
                <div class="eyebrow">Total Faculty Members</div>

                <div class="hero-number">
                    {{ $teachers->total() }}
                </div>

                <div style="font-size:13px;color:#5c6b64;">
                    Faculty members matching the selected search
                </div>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="filter-panel">
            <div class="section-heading">Filter Faculty Attendance</div>

            <form method="GET"
                  action="{{ route('admin.reports.teacher-attendance') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Search Faculty</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Name, ID or subject"
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Month</label>
                        <input type="month"
                               name="month"
                               class="form-control"
                               value="{{ $month }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">From Date</label>
                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">To Date</label>
                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Attendance Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(['Present', 'Half Day', 'Absent', 'Leave'] as $status)
                                <option value="{{ $status }}"
                                    {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-1 col-md-6">
                        <button type="submit"
                                class="btn btn-primary-custom w-100">
                            Apply
                        </button>
                    </div>

                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('admin.reports.teacher-attendance') }}"
                       class="btn btn-light-custom">
                        Reset Filters
                    </a>
                </div>
            </form>
        </div>

        {{-- FACULTY TABLE --}}
        <div class="report-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-heading mb-1">
                        Faculty Attendance Details
                    </div>

                    <div class="muted">
                        Individual attendance summaries for the selected period
                    </div>
                </div>

                <div class="eyebrow">
                    {{ $teachers->total() }} Faculty Members
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Details</th>
                            <th>Subject</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Half Days</th>
                            <th>Leave</th>
                            <th>Attendance %</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($teachers as $teacher)
                            @php
                                $attendance = $teacher->attendance_summary;
                                $percentage = $attendance['percentage'] ?? 0;
                            @endphp

                            <tr>
                                <td>
                                    {{ $teachers->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="faculty-name">
                                        {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) }}
                                    </div>

                                    <div class="muted">
                                        ID: {{ $teacher->teacher_id ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $teacher->subject ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="status-badge status-present">
                                        {{ $attendance['present'] ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-absent">
                                        {{ $attendance['absent'] ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-half">
                                        {{ $attendance['half_days'] ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-leave">
                                        {{ $attendance['leave'] ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ number_format($percentage, 1) }}%
                                    </div>

                                    <div class="progress">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: {{ min(100, max(0, $percentage)) }}%;">
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{ route('admin.reports.teacher-attendance.profile', [
                                        'teacher' => $teacher->id,
                                        'from_date' => request('from_date'),
                                        'to_date' => request('to_date'),
                                    ]) }}"
                                       class="btn btn-primary-custom btn-sm">
                                        View Profile
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">
                                    No faculty members found.
                                    Try changing your search or filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($teachers->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

                    <div class="muted">
                        Showing {{ $teachers->firstItem() }}
                        to {{ $teachers->lastItem() }}
                        of {{ $teachers->total() }} faculty members
                    </div>

                    {{ $teachers->withQueryString()->links() }}

                </div>
            @elseif($teachers->total() > 0)
                <div class="muted mt-4">
                    Showing {{ $teachers->firstItem() }}
                    to {{ $teachers->lastItem() }}
                    of {{ $teachers->total() }} faculty members
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
