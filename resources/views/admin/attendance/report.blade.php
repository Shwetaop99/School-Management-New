@extends('layouts.app')

@section('content')

<div class="attendance-report-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}

        <div class="report-page-header mb-4">

            <div class="header-left">

                <div class="header-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>

                <div>
                    <div class="header-eyebrow">
                        ATTENDANCE MANAGEMENT
                    </div>

                    <h3 class="header-title">
                        Attendance Report
                    </h3>

                    <p class="header-subtitle">
                        View class-wise student attendance summary and performance.
                    </p>
                </div>

            </div>


            <div class="header-actions">

                <a
                    href="{{ route('admin.attendance.index') }}"
                    class="header-btn header-btn-outline"
                >
                    <i class="bi bi-calendar-check"></i>
                    <span>Mark Attendance</span>
                </a>


                <a
                    href="{{ route('admin.attendance.monthly') }}"
                    class="header-btn header-btn-success"
                >
                    <i class="bi bi-calendar3"></i>
                    <span>Monthly Register</span>
                </a>

            </div>

        </div>


        {{-- =========================================================
            FILTER CARD
        ========================================================== --}}

        <div class="filter-card mb-4">

            <div class="filter-card-header">

                <div class="filter-title-wrapper">

                    <div class="filter-icon">
                        <i class="bi bi-funnel-fill"></i>
                    </div>

                    <div>

                        <h5 class="filter-title">
                            Report Filters
                        </h5>

                        <p class="filter-subtitle">
                            Select the class and date range to generate attendance report.
                        </p>

                    </div>

                </div>

            </div>


            <div class="filter-card-body">

                <div class="row g-3">

                    {{-- Academic Year --}}

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <label
                            for="academicYear"
                            class="custom-label"
                        >
                            <i class="bi bi-calendar-event"></i>
                            Academic Year
                        </label>

                        <select
                            id="academicYear"
                            class="form-select custom-control"
                        >

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Class --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label
                            for="class"
                            class="custom-label"
                        >
                            <i class="bi bi-mortarboard-fill"></i>
                            Class
                        </label>

                        <select
                            id="class"
                            class="form-select custom-control"
                        >

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class }}">
                                    {{ $class }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Section --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label
                            for="section"
                            class="custom-label"
                        >
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                            Section
                        </label>

                        <select
                            id="section"
                            class="form-select custom-control"
                        >

                            <option value="">
                                Select Section
                            </option>

                            @foreach(['A','B','C','D','E','F'] as $section)

                                <option value="{{ $section }}">
                                    Section {{ $section }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label
                            for="fromDate"
                            class="custom-label"
                        >
                            <i class="bi bi-calendar-minus"></i>
                            From Date
                        </label>

                        <input
                            type="date"
                            id="fromDate"
                            class="form-control custom-control"
                            value="{{ now()->format('Y-m-01') }}"
                        >

                    </div>


                    {{-- To Date --}}

                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label
                            for="toDate"
                            class="custom-label"
                        >
                            <i class="bi bi-calendar-plus"></i>
                            To Date
                        </label>

                        <input
                            type="date"
                            id="toDate"
                            class="form-control custom-control"
                            value="{{ now()->format('Y-m-d') }}"
                        >

                    </div>


                    {{-- Generate --}}

                    <div class="col-xl-1 col-lg-4 col-md-6 d-flex align-items-end">

                        <button
                            type="button"
                            id="generateReport"
                            class="generate-btn w-100"
                            title="Generate Report"
                        >

                            <i class="bi bi-search"></i>

                            <span class="generate-text">
                                Generate
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            LOADING
        ========================================================== --}}

        <div
            id="loadingBox"
            class="loading-card d-none"
        >

            <div class="loading-spinner-wrapper">

                <div class="loading-spinner"></div>

            </div>

            <div>

                <div class="loading-title">
                    Generating Attendance Report
                </div>

                <div class="loading-text">
                    Please wait while we prepare the attendance data...
                </div>

            </div>

        </div>


        {{-- =========================================================
            ERROR
        ========================================================== --}}

        <div
            id="errorBox"
            class="custom-error d-none"
        >

            <div class="error-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>
                <div class="error-title">
                    Unable to Generate Report
                </div>

                <div
                    id="errorMessage"
                    class="error-message"
                ></div>
            </div>

        </div>


        {{-- =========================================================
            REPORT SECTION
        ========================================================== --}}

        <div id="reportSection" class="d-none">


            {{-- =====================================================
                SUMMARY CARDS
            ====================================================== --}}

            <div class="summary-grid mb-4">


                {{-- Total Students --}}

                <div class="summary-card students-card">

                    <div class="summary-content">

                        <div>

                            <div class="summary-label">
                                Total Students
                            </div>

                            <div
                                id="totalStudents"
                                class="summary-number"
                            >
                                0
                            </div>

                            <div class="summary-description">
                                Students in selected class
                            </div>

                        </div>


                        <div class="summary-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                    </div>

                </div>


                {{-- Present --}}

                <div class="summary-card present-card">

                    <div class="summary-content">

                        <div>

                            <div class="summary-label">
                                Total Present
                            </div>

                            <div
                                id="totalPresent"
                                class="summary-number"
                            >
                                0
                            </div>

                            <div class="summary-description">
                                Present attendance records
                            </div>

                        </div>


                        <div class="summary-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                    </div>

                </div>


                {{-- Absent --}}

                <div class="summary-card absent-card">

                    <div class="summary-content">

                        <div>

                            <div class="summary-label">
                                Total Absent
                            </div>

                            <div
                                id="totalAbsent"
                                class="summary-number"
                            >
                                0
                            </div>

                            <div class="summary-description">
                                Absent attendance records
                            </div>

                        </div>


                        <div class="summary-icon">
                            <i class="bi bi-person-x-fill"></i>
                        </div>

                    </div>

                </div>


                {{-- Average --}}

                <div class="summary-card average-card">

                    <div class="summary-content">

                        <div>

                            <div class="summary-label">
                                Average Attendance
                            </div>

                            <div
                                id="averageAttendance"
                                class="summary-number"
                            >
                                0%
                            </div>

                            <div class="summary-description">
                                Overall attendance percentage
                            </div>

                        </div>


                        <div class="summary-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                REPORT TABLE CARD
            ====================================================== --}}

            <div class="report-card">


                {{-- TABLE HEADER --}}

                <div class="report-card-header">

                    <div class="report-heading-wrapper">

                        <div class="table-title-icon">
                            <i class="bi bi-table"></i>
                        </div>

                        <div>

                            <h5 class="report-title">
                                Student Attendance
                            </h5>

                            <div
                                id="reportDescription"
                                class="report-description"
                            >
                            </div>

                        </div>

                    </div>


                    <button
                        type="button"
                        id="printReport"
                        class="print-btn"
                    >

                        <i class="bi bi-printer-fill"></i>

                        <span>
                            Print Report
                        </span>

                    </button>

                </div>


                {{-- TABLE --}}

                <div class="table-container">

                    <div class="table-responsive">

                        <table class="table attendance-table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Roll No.
                                    </th>

                                    <th>
                                        Student ID
                                    </th>

                                    <th>
                                        Student Name
                                    </th>

                                    <th class="text-center">
                                        Working Days
                                    </th>

                                    <th class="text-center">
                                        Present
                                    </th>

                                    <th class="text-center">
                                        Absent
                                    </th>

                                    <th class="text-center">
                                        Leave
                                    </th>

                                    <th class="text-center">
                                        Half Day
                                    </th>

                                    <th class="text-center">
                                        Late
                                    </th>

                                    <th class="text-center">
                                        Attendance %
                                    </th>

                                    <th class="text-center action-column">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="reportBody">

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            EMPTY STATE
        ========================================================== --}}

        <div
            id="emptyBox"
            class="empty-state"
        >

            <div class="empty-icon">

                <i class="bi bi-bar-chart-line"></i>

            </div>

            <h5 class="empty-title">
                Generate Attendance Report
            </h5>

            <p class="empty-text">
                Select academic year, class, section and date range
                to view student attendance.
            </p>

        </div>

    </div>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}

<style>

    /* =========================================================
       PAGE
    ========================================================== */

    .attendance-report-page {
        min-height: calc(100vh - 70px);

        background:
            linear-gradient(
                135deg,
                #f5f8ff 0%,
                #f8fafc 45%,
                #f4f7fb 100%
            );
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .report-page-header {
        display: flex;

        justify-content: space-between;
        align-items: center;

        gap: 20px;
        flex-wrap: wrap;
    }

    .header-left {
        display: flex;

        align-items: center;

        gap: 15px;
    }

    .header-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

        color: #ffffff;

        font-size: 23px;

        box-shadow:
            0 8px 20px rgba(37, 99, 235, .20);
    }

    .header-eyebrow {
        color: #2563eb;

        font-size: 10px;

        font-weight: 800;

        letter-spacing: 1.2px;

        margin-bottom: 3px;
    }

    .header-title {
        margin: 0;

        color: #111827;

        font-size: 25px;

        font-weight: 800;
    }

    .header-subtitle {
        margin: 3px 0 0;

        color: #6b7280;

        font-size: 13px;
    }

    .header-actions {
        display: flex;

        gap: 9px;

        flex-wrap: wrap;
    }

    .header-btn {
        min-height: 42px;

        padding: 9px 15px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        border-radius: 10px;

        text-decoration: none;

        font-size: 13px;

        font-weight: 700;

        transition: all .2s ease;
    }

    .header-btn-outline {
        background: #ffffff;

        color: #2563eb;

        border: 1px solid #bfdbfe;
    }

    .header-btn-outline:hover {
        background: #eff6ff;

        color: #1d4ed8;

        transform: translateY(-1px);
    }

    .header-btn-success {
        background: #ffffff;

        color: #15803d;

        border: 1px solid #bbf7d0;
    }

    .header-btn-success:hover {
        background: #f0fdf4;

        color: #166534;

        transform: translateY(-1px);
    }


    /* =========================================================
       FILTER CARD
    ========================================================== */

    .filter-card {
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #e5e7eb;

        border-radius: 18px;

        box-shadow:
            0 8px 28px rgba(15, 23, 42, .06);
    }

    .filter-card-header {
        padding: 17px 20px;

        border-bottom: 1px solid #eef2f7;

        background:
            linear-gradient(
                90deg,
                #ffffff,
                #f8fbff
            );
    }

    .filter-title-wrapper {
        display: flex;

        align-items: center;

        gap: 12px;
    }

    .filter-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 16px;
    }

    .filter-title {
        margin: 0;

        font-size: 15px;

        font-weight: 800;

        color: #1f2937;
    }

    .filter-subtitle {
        margin: 2px 0 0;

        font-size: 11px;

        color: #6b7280;
    }

    .filter-card-body {
        padding: 20px;
    }

    .custom-label {
        display: flex;

        align-items: center;

        gap: 6px;

        margin-bottom: 7px;

        font-size: 12px;

        font-weight: 700;

        color: #374151;
    }

    .custom-label i {
        color: #2563eb;

        font-size: 13px;
    }

    .custom-control {
        min-height: 44px;

        border-radius: 10px;

        border: 1px solid #dbe1e8;

        background-color: #ffffff;

        color: #1f2937;

        font-size: 13px;

        font-weight: 500;

        box-shadow: none;

        transition: all .2s ease;
    }

    .custom-control:hover {
        border-color: #bfdbfe;
    }

    .custom-control:focus {
        border-color: #60a5fa;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, .10);
    }

    .generate-btn {
        min-height: 44px;

        border: 0;

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

        color: #ffffff;

        font-size: 13px;

        font-weight: 700;

        box-shadow:
            0 5px 14px rgba(37, 99, 235, .22);

        transition: all .2s ease;
    }

    .generate-btn:hover {
        transform: translateY(-1px);

        box-shadow:
            0 8px 18px rgba(37, 99, 235, .28);
    }

    .generate-btn:active {
        transform: translateY(0);
    }

    .generate-btn:disabled {
        opacity: .65;

        cursor: not-allowed;

        transform: none;
    }


    /* =========================================================
       LOADING
    ========================================================== */

    .loading-card {
        display: flex;

        align-items: center;

        justify-content: center;

        gap: 15px;

        padding: 25px;

        margin-bottom: 20px;

        background: #ffffff;

        border: 1px solid #dbeafe;

        border-radius: 15px;

        box-shadow:
            0 6px 20px rgba(37, 99, 235, .06);
    }

    .loading-spinner-wrapper {
        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eff6ff;
    }

    .loading-spinner {
        width: 23px;
        height: 23px;

        border: 3px solid #dbeafe;

        border-top-color: #2563eb;

        border-radius: 50%;

        animation: reportSpin .8s linear infinite;
    }

    @keyframes reportSpin {

        to {
            transform: rotate(360deg);
        }

    }

    .loading-title {
        font-weight: 800;

        color: #1f2937;

        font-size: 14px;
    }

    .loading-text {
        margin-top: 2px;

        color: #6b7280;

        font-size: 12px;
    }


    /* =========================================================
       ERROR
    ========================================================== */

    .custom-error {
        display: flex;

        align-items: center;

        gap: 12px;

        padding: 14px 16px;

        margin-bottom: 20px;

        border-radius: 12px;

        background: #fff7f7;

        border: 1px solid #fecaca;

        color: #991b1b;
    }

    .error-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 9px;

        background: #fee2e2;

        color: #dc2626;
    }

    .error-title {
        font-size: 13px;

        font-weight: 800;
    }

    .error-message {
        margin-top: 2px;

        font-size: 12px;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;
    }

    .summary-card {
        position: relative;

        overflow: hidden;

        min-height: 130px;

        padding: 20px;

        border-radius: 16px;

        background: #ffffff;

        border: 1px solid #e5e7eb;

        box-shadow:
            0 7px 24px rgba(15, 23, 42, .055);

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 12px 30px rgba(15, 23, 42, .09);
    }

    .summary-card::before {
        content: "";

        position: absolute;

        left: 0;
        top: 0;
        bottom: 0;

        width: 4px;
    }

    .students-card::before {
        background: #2563eb;
    }

    .present-card::before {
        background: #16a34a;
    }

    .absent-card::before {
        background: #dc2626;
    }

    .average-card::before {
        background: #7c3aed;
    }

    .summary-content {
        height: 100%;

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 15px;
    }

    .summary-label {
        margin-bottom: 5px;

        color: #6b7280;

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .5px;
    }

    .summary-number {
        color: #111827;

        font-size: 30px;

        line-height: 1.1;

        font-weight: 800;
    }

    .present-card .summary-number {
        color: #16a34a;
    }

    .absent-card .summary-number {
        color: #dc2626;
    }

    .average-card .summary-number {
        color: #7c3aed;
    }

    .summary-description {
        margin-top: 5px;

        color: #9ca3af;

        font-size: 10px;
    }

    .summary-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 13px;

        font-size: 19px;
    }

    .students-card .summary-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .present-card .summary-icon {
        background: #f0fdf4;
        color: #16a34a;
    }

    .absent-card .summary-icon {
        background: #fef2f2;
        color: #dc2626;
    }

    .average-card .summary-icon {
        background: #f5f3ff;
        color: #7c3aed;
    }


    /* =========================================================
       REPORT CARD
    ========================================================== */

    .report-card {
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #e5e7eb;

        border-radius: 18px;

        box-shadow:
            0 8px 28px rgba(15, 23, 42, .06);
    }

    .report-card-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 19px 20px;

        border-bottom: 1px solid #edf0f4;

        background:
            linear-gradient(
                90deg,
                #ffffff,
                #fbfdff
            );
    }

    .report-heading-wrapper {
        display: flex;

        align-items: center;

        gap: 12px;
    }

    .table-title-icon {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #f1f5f9;

        color: #475569;

        font-size: 17px;
    }

    .report-title {
        margin: 0;

        color: #1f2937;

        font-size: 16px;

        font-weight: 800;
    }

    .report-description {
        margin-top: 3px;

        color: #6b7280;

        font-size: 11px;
    }

    .print-btn {
        min-height: 40px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        padding: 8px 15px;

        border: 0;

        border-radius: 9px;

        background: #111827;

        color: #ffffff;

        font-size: 12px;

        font-weight: 700;

        transition: all .2s ease;
    }

    .print-btn:hover {
        background: #1f2937;

        transform: translateY(-1px);

        box-shadow:
            0 5px 14px rgba(17, 24, 39, .18);
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .table-container {
        width: 100%;
    }

    .attendance-table {
        min-width: 1100px;

        margin: 0 !important;
    }

    .attendance-table thead th {
        padding: 13px 10px;

        background: #f8fafc;

        color: #475569;

        border-bottom: 1px solid #e2e8f0;

        font-size: 10px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: .4px;

        white-space: nowrap;
    }

    .attendance-table tbody td {
        padding: 13px 10px;

        border-bottom: 1px solid #f1f5f9;

        color: #374151;

        font-size: 12px;
    }

    .attendance-table tbody tr {
        transition: background .15s ease;
    }

    .attendance-table tbody tr:hover {
        background: #f8fbff;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .attendance-table td:first-child {
        color: #94a3b8;

        font-weight: 700;
    }

    .attendance-table td:nth-child(4) {
        color: #111827;

        font-weight: 700;
    }

    .attendance-table td:nth-child(5),
    .attendance-table td:nth-child(6),
    .attendance-table td:nth-child(7),
    .attendance-table td:nth-child(8),
    .attendance-table td:nth-child(9),
    .attendance-table td:nth-child(10),
    .attendance-table td:nth-child(11) {
        font-weight: 600;
    }

    .attendance-percent {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 65px;

        padding: 5px 9px;

        border-radius: 50px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 11px;

        font-weight: 800;
    }

    .attendance-table .btn {
        border-radius: 8px;

        width: 34px;
        height: 32px;

        padding: 0;

        display: inline-flex;

        align-items: center;
        justify-content: center;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 65px 20px;

        text-align: center;

        background: #ffffff;

        border: 1px dashed #d7dee8;

        border-radius: 18px;

        box-shadow:
            0 6px 22px rgba(15, 23, 42, .035);
    }

    .empty-icon {
        width: 72px;
        height: 72px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0 auto 16px;

        border-radius: 20px;

        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #f5f3ff
            );

        color: #94a3b8;

        font-size: 30px;
    }

    .empty-title {
        margin-bottom: 6px;

        color: #374151;

        font-size: 16px;

        font-weight: 800;
    }

    .empty-text {
        max-width: 450px;

        margin: 0 auto;

        color: #9ca3af;

        font-size: 12px;

        line-height: 1.6;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1200px) {

        .summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .generate-text {
            display: none;
        }

    }


    @media (max-width: 768px) {

        .attendance-report-page .container-fluid {
            padding-top: 20px !important;
        }

        .report-page-header {
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
        }

        .filter-card-body {
            padding: 15px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .report-card-header {
            align-items: flex-start;

            flex-direction: column;
        }

        .print-btn {
            width: 100%;
        }

    }


    @media (max-width: 480px) {

        .header-left {
            align-items: flex-start;
        }

        .header-icon {
            width: 45px;
            height: 45px;

            border-radius: 12px;

            font-size: 18px;
        }

        .header-title {
            font-size: 21px;
        }

        .header-subtitle {
            font-size: 11px;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-btn {
            width: 100%;
        }

        .summary-card {
            min-height: 115px;

            padding: 16px;
        }

        .summary-number {
            font-size: 26px;
        }

    }


    /* =========================================================
       PRINT
    ========================================================== */

    @media print {

        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            background: #ffffff !important;
        }

        body * {
            visibility: hidden;
        }

        #reportSection,
        #reportSection * {
            visibility: visible;
        }

        #reportSection {
            position: absolute;

            left: 0;
            top: 0;

            width: 100%;

            margin: 0;
        }

        .report-card {
            border: 0 !important;

            box-shadow: none !important;

            border-radius: 0 !important;
        }

        .report-card-header {
            padding: 0 0 10px 0 !important;

            background: #ffffff !important;

            border-bottom: 1px solid #000 !important;
        }

        .table-title-icon {
            display: none;
        }

        .print-btn {
            display: none !important;
        }

        .action-column,
        #reportBody td:last-child {
            display: none !important;
        }

        .table-container,
        .table-responsive {
            overflow: visible !important;
        }

        .attendance-table {
            width: 100% !important;

            min-width: 0 !important;

            border-collapse: collapse !important;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #777 !important;

            padding: 5px !important;

            font-size: 9px !important;
        }

        .attendance-table thead {
            display: table-header-group;
        }

        .attendance-table tr {
            page-break-inside: avoid;
        }

        .attendance-percent {
            padding: 0 !important;

            background: transparent !important;

            color: #000000 !important;

            min-width: auto !important;
        }

    }

</style>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       ELEMENTS
    ========================================================== */

    const generateButton =
        document.getElementById('generateReport');


    const loadingBox =
        document.getElementById('loadingBox');


    const errorBox =
        document.getElementById('errorBox');


    const errorMessage =
        document.getElementById('errorMessage');


    const reportSection =
        document.getElementById('reportSection');


    const emptyBox =
        document.getElementById('emptyBox');


    const reportBody =
        document.getElementById('reportBody');


    const printButton =
        document.getElementById('printReport');


    /* =========================================================
       EVENTS
    ========================================================== */

    generateButton.addEventListener(
        'click',
        generateReport
    );


    printButton.addEventListener(
        'click',
        printAttendanceReport
    );


    /* =========================================================
       GENERATE REPORT
    ========================================================== */

    async function generateReport() {


        const academicYear =
            document.getElementById(
                'academicYear'
            ).value;


        const classValue =
            document.getElementById(
                'class'
            ).value;


        const section =
            document.getElementById(
                'section'
            ).value;


        const fromDate =
            document.getElementById(
                'fromDate'
            ).value;


        const toDate =
            document.getElementById(
                'toDate'
            ).value;


        hideError();


        /* VALIDATION */

        if (
            !academicYear ||
            !classValue ||
            !section ||
            !fromDate ||
            !toDate
        ) {

            showError(
                'Please select Academic Year, Class, Section, From Date and To Date.'
            );

            return;

        }


        if (fromDate > toDate) {

            showError(
                'From Date cannot be greater than To Date.'
            );

            return;

        }


        /* LOADING */

        loadingBox.classList.remove(
            'd-none'
        );


        reportSection.classList.add(
            'd-none'
        );


        emptyBox.classList.add(
            'd-none'
        );


        generateButton.disabled = true;


        generateButton.innerHTML = `
            <span
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
            ></span>

            <span class="generate-text">
                Loading
            </span>
        `;


        try {


            const url =
                `{{ route('admin.attendance.report') }}` +
                `?academic_year=${encodeURIComponent(academicYear)}` +
                `&class=${encodeURIComponent(classValue)}` +
                `&section=${encodeURIComponent(section)}` +
                `&from_date=${encodeURIComponent(fromDate)}` +
                `&to_date=${encodeURIComponent(toDate)}`;


            const response =
                await fetch(
                    url,
                    {

                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        }

                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to generate attendance report.'
                );

            }


            const result =
                await response.json();


            if (!result.success) {

                throw new Error(
                    result.message ||
                    'Unable to generate report.'
                );

            }


            renderReport(result);


        } catch (error) {


            console.error(error);


            showError(
                error.message ||
                'Unable to generate report.'
            );


            emptyBox.classList.remove(
                'd-none'
            );


        } finally {


            loadingBox.classList.add(
                'd-none'
            );


            generateButton.disabled =
                false;


            generateButton.innerHTML = `
                <i class="bi bi-search"></i>
                <span class="generate-text">
                    Generate
                </span>
            `;

        }

    }


    /* =========================================================
       RENDER REPORT
    ========================================================== */

    function renderReport(result) {


        const students =
            result.students || [];


        const summary =
            result.summary || {};


        /* SUMMARY */

        document.getElementById(
            'totalStudents'
        ).textContent =
            summary.total_students ??
            students.length;


        document.getElementById(
            'totalPresent'
        ).textContent =
            summary.total_present ??
            0;


        document.getElementById(
            'totalAbsent'
        ).textContent =
            summary.total_absent ??
            0;


        document.getElementById(
            'averageAttendance'
        ).textContent =
            (
                Number(
                    summary.average_attendance ?? 0
                ).toFixed(2)
            ) + '%';


        /* DESCRIPTION */

        document.getElementById(
            'reportDescription'
        ).textContent =

            `${result.academic_year ?? ''}` +
            ` • Class ${result.class ?? ''}` +
            ` • Section ${result.section ?? ''}` +
            ` • ${formatDate(result.from_date)}` +
            ` to ${formatDate(result.to_date)}`;


        /* CLEAR TABLE */

        reportBody.innerHTML = '';


        /* NO DATA */

        if (students.length === 0) {


            reportBody.innerHTML = `

                <tr>

                    <td
                        colspan="12"
                        class="text-center py-5 text-muted"
                    >

                        <div
                            style="
                                font-size:32px;
                                margin-bottom:8px;
                                color:#cbd5e1;
                            "
                        >
                            <i class="bi bi-inbox"></i>
                        </div>

                        <div
                            style="
                                font-weight:700;
                                color:#64748b;
                            "
                        >
                            No students found
                        </div>

                        <div
                            style="
                                font-size:11px;
                                margin-top:3px;
                            "
                        >
                            No attendance records are available for the selected filters.
                        </div>

                    </td>

                </tr>

            `;


        } else {


            /* STUDENTS */

            students.forEach(
                function (student, index) {


                    const percentage =
                        Number(
                            student.attendance_percentage ?? 0
                        );


                    let percentageClass =
                        '';


                    if (percentage >= 75) {

                        percentageClass =
                            'style="background:#f0fdf4;color:#15803d;"';

                    } else if (percentage >= 60) {

                        percentageClass =
                            'style="background:#fffbeb;color:#b45309;"';

                    } else {

                        percentageClass =
                            'style="background:#fef2f2;color:#dc2626;"';

                    }


                    const row =
                        document.createElement('tr');


                    row.innerHTML = `

                        <td class="ps-4">

                            ${index + 1}

                        </td>


                        <td>

                            ${escapeHtml(
                                student.roll_number ?? '-'
                            )}

                        </td>


                        <td>

                            <span
                                style="
                                    font-weight:600;
                                    color:#475569;
                                "
                            >

                                ${escapeHtml(
                                    student.student_id ?? '-'
                                )}

                            </span>

                        </td>


                        <td>

                            <span
                                style="
                                    font-weight:700;
                                    color:#1f2937;
                                "
                            >

                                ${escapeHtml(
                                    student.name ?? '-'
                                )}

                            </span>

                        </td>


                        <td class="text-center">

                            ${student.working_days ?? 0}

                        </td>


                        <td
                            class="text-center"
                            style="color:#16a34a;font-weight:700;"
                        >

                            ${student.present ?? 0}

                        </td>


                        <td
                            class="text-center"
                            style="color:#dc2626;font-weight:700;"
                        >

                            ${student.absent ?? 0}

                        </td>


                        <td class="text-center">

                            ${student.leave ?? 0}

                        </td>


                        <td class="text-center">

                            ${student.half_day ?? 0}

                        </td>


                        <td class="text-center">

                            ${student.late ?? 0}

                        </td>


                        <td class="text-center">

                            <span
                                class="attendance-percent"
                                ${percentageClass}
                            >

                                ${percentage.toFixed(2)}%

                            </span>

                        </td>


                        <td
                            class="text-center action-column"
                        >

                            <a
                                href="{{ url('/admin/attendance/student') }}/${encodeURIComponent(student.id)}"
                                class="btn btn-sm btn-outline-primary"
                                title="View Attendance"
                            >

                                <i class="bi bi-eye-fill"></i>

                            </a>

                        </td>

                    `;


                    reportBody.appendChild(
                        row
                    );

                }
            );

        }


        /* SHOW REPORT */

        reportSection.classList.remove(
            'd-none'
        );


        emptyBox.classList.add(
            'd-none'
        );

    }


    /* =========================================================
       PRINT REPORT
    ========================================================== */

    function printAttendanceReport() {


        const academicYear =
            document.getElementById(
                'academicYear'
            ).value;


        const classValue =
            document.getElementById(
                'class'
            ).value;


        const section =
            document.getElementById(
                'section'
            ).value;


        const fromDate =
            document.getElementById(
                'fromDate'
            ).value;


        const toDate =
            document.getElementById(
                'toDate'
            ).value;


        /* VALIDATION */

        if (
            !academicYear ||
            !classValue ||
            !section ||
            !fromDate ||
            !toDate
        ) {

            showError(
                'Please generate the attendance report before printing.'
            );

            return;

        }


        if (fromDate > toDate) {

            showError(
                'From Date cannot be greater than To Date.'
            );

            return;

        }


        /* PRINT URL */

        const printUrl =
            `{{ route('admin.attendance.report.print') }}` +
            `?academic_year=${encodeURIComponent(academicYear)}` +
            `&class=${encodeURIComponent(classValue)}` +
            `&section=${encodeURIComponent(section)}` +
            `&from_date=${encodeURIComponent(fromDate)}` +
            `&to_date=${encodeURIComponent(toDate)}`;


        window.open(
            printUrl,
            '_blank'
        );

    }


    /* =========================================================
       ERROR
    ========================================================== */

    function showError(message) {

        if (errorMessage) {

            errorMessage.textContent =
                message;

        } else {

            errorBox.textContent =
                message;

        }


        errorBox.classList.remove(
            'd-none'
        );

    }


    function hideError() {

        errorBox.classList.add(
            'd-none'
        );

    }


    /* =========================================================
       DATE FORMAT
    ========================================================== */

    function formatDate(dateString) {


        if (!dateString) {

            return '-';

        }


        const date =
            new Date(
                dateString + 'T00:00:00'
            );


        if (isNaN(date.getTime())) {

            return dateString;

        }


        return date.toLocaleDateString(
            'en-IN',
            {

                day: '2-digit',

                month: 'short',

                year: 'numeric'

            }
        );

    }


    /* =========================================================
       HTML ESCAPE
    ========================================================== */

    function escapeHtml(value) {


        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }


});

</script>

@endsection