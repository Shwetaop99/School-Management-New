@extends('layouts.app')

@section('content')

<div class="attendance-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">

            <a href="{{ route('admin.attendance.report') }}"
               class="back-btn">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div>

                <div class="d-flex align-items-center gap-2">

                    <span class="page-icon">
                        <i class="bi bi-calendar-check-fill"></i>
                    </span>

                    <h3 class="page-title mb-0">
                        Student Attendance
                    </h3>

                </div>

                <p class="page-subtitle mb-0">
                    Complete attendance history and performance overview
                </p>

            </div>

        </div>


        <button type="button"
                class="print-btn"
                onclick="window.print()">

            <i class="bi bi-printer-fill me-2"></i>
            Print Report

        </button>

    </div>


    {{-- =========================================================
        STUDENT PROFILE HERO
    ========================================================== --}}

    <div class="student-hero mb-4">

        <div class="hero-pattern"></div>

        <div class="student-hero-content">

            {{-- Profile Image --}}

            <div class="profile-wrapper">

                @if($student->profile_image)

                    <img src="{{ $student->profile_image }}"
                         alt="Student Photo"
                         class="student-photo">

                @else

                    <div class="student-photo-placeholder">

                        <i class="bi bi-person-fill"></i>

                    </div>

                @endif

            </div>


            {{-- Student Information --}}

            <div class="student-main-info">

                <div class="student-name">

                    {{ trim(
                        $student->first_name . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        $student->last_name
                    ) }}

                </div>


                <div class="student-id">

                    <i class="bi bi-person-badge-fill me-1"></i>

                    Student ID:
                    <strong>
                        {{ $student->student_id }}
                    </strong>

                </div>


                <div class="student-tags">

                    <span class="student-tag">
                        <i class="bi bi-book-fill"></i>
                        Class {{ $student->class ?? '-' }}
                    </span>

                    <span class="student-tag">
                        <i class="bi bi-people-fill"></i>
                        Section {{ $student->section ?? '-' }}
                    </span>

                    <span class="student-tag">
                        <i class="bi bi-calendar3"></i>
                        {{ $student->academic_year ?? '-' }}
                    </span>

                </div>

            </div>


            {{-- Attendance Percentage --}}

            @php

                $totalDays = $attendances->count();

                $present = $attendances
                    ->where('status', 'present')
                    ->count();

                $absent = $attendances
                    ->where('status', 'absent')
                    ->count();

                $leave = $attendances
                    ->where('status', 'leave')
                    ->count();

                $halfDay = $attendances
                    ->where('status', 'half_day')
                    ->count();

                $late = $attendances
                    ->where('status', 'late')
                    ->count();

                $attendanceValue =
                    $present +
                    $late +
                    ($halfDay * 0.5);

                $percentage = $totalDays > 0
                    ? round(
                        ($attendanceValue / $totalDays) * 100,
                        2
                    )
                    : 0;

            @endphp


            <div class="attendance-circle-wrapper">

                <div class="attendance-circle"
                     style="--percentage: {{ $percentage }}%;">

                    <div class="attendance-circle-inner">

                        <strong>
                            {{ $percentage }}%
                        </strong>

                        <span>
                            Attendance
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Student Details Bar --}}

        <div class="student-details-bar">

            <div class="student-detail-item">

                <span class="detail-icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </span>

                <div>

                    <small>
                        Roll Number
                    </small>

                    <strong>
                        {{ $student->roll_number ?? '-' }}
                    </strong>

                </div>

            </div>


            <div class="detail-divider"></div>


            <div class="student-detail-item">

                <span class="detail-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <div>

                    <small>
                        Class
                    </small>

                    <strong>
                        {{ $student->class ?? '-' }}
                    </strong>

                </div>

            </div>


            <div class="detail-divider"></div>


            <div class="student-detail-item">

                <span class="detail-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </span>

                <div>

                    <small>
                        Section
                    </small>

                    <strong>
                        {{ $student->section ?? '-' }}
                    </strong>

                </div>

            </div>


            <div class="detail-divider"></div>


            <div class="student-detail-item">

                <span class="detail-icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </span>

                <div>

                    <small>
                        Academic Year
                    </small>

                    <strong>
                        {{ $student->academic_year ?? '-' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY TITLE
    ========================================================== --}}

    <div class="section-heading mb-3">

        <div>

            <h5>
                Attendance Overview
            </h5>

            <p>
                Summary of the student's attendance records
            </p>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card total-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-bar-chart-fill"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Total Days
                </div>

                <div class="stat-value">
                    {{ $totalDays }}
                </div>

            </div>

        </div>


        {{-- Present --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card present-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-arrow-up"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Present
                </div>

                <div class="stat-value">
                    {{ $present }}
                </div>

            </div>

        </div>


        {{-- Absent --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card absent-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-x-lg"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-exclamation-lg"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Absent
                </div>

                <div class="stat-value">
                    {{ $absent }}
                </div>

            </div>

        </div>


        {{-- Leave --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card leave-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-calendar2-minus-fill"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-info-lg"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Leave
                </div>

                <div class="stat-value">
                    {{ $leave }}
                </div>

            </div>

        </div>


        {{-- Half Day --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card halfday-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-hourglass-split"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Half Day
                </div>

                <div class="stat-value">
                    {{ $halfDay }}
                </div>

            </div>

        </div>


        {{-- Attendance --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="stat-card percentage-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-percent"></i>
                    </div>

                    <span class="stat-arrow">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>

                </div>

                <div class="stat-label">
                    Attendance
                </div>

                <div class="stat-value">
                    {{ $percentage }}%
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ATTENDANCE HISTORY
    ========================================================== --}}

    <div class="attendance-card">

        <div class="attendance-card-header">

            <div class="history-title-wrapper">

                <div class="history-icon">

                    <i class="bi bi-calendar-week-fill"></i>

                </div>

                <div>

                    <h5>
                        Attendance History
                    </h5>

                    <p>
                        Daily attendance records
                    </p>

                </div>

            </div>


            <div class="record-count">

                <i class="bi bi-list-check me-1"></i>

                {{ $totalDays }} Records

            </div>

        </div>


        <div class="table-responsive">

            <table class="attendance-table">

                <thead>

                    <tr>

                        <th class="number-column">
                            #
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Academic Year
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Section
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Remarks
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($attendances as $index => $attendance)

                        <tr>

                            <td class="number-column">

                                <span class="row-number">
                                    {{ $index + 1 }}
                                </span>

                            </td>


                            <td>

                                <div class="date-cell">

                                    <div class="date-icon">

                                        <i class="bi bi-calendar-event"></i>

                                    </div>

                                    <div>

                                        <strong>
                                            {{ $attendance->attendance_date?->format('d M Y') }}
                                        </strong>

                                        @if($attendance->attendance_date)

                                            <small>
                                                {{ $attendance->attendance_date->format('l') }}
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="year-badge">

                                    {{ $attendance->academic_year }}

                                </span>

                            </td>


                            <td>

                                <span class="class-badge">

                                    {{ $attendance->class }}

                                </span>

                            </td>


                            <td>

                                <span class="section-badge">

                                    {{ $attendance->section }}

                                </span>

                            </td>


                            <td>

                                @php

                                    $statusClass = match(
                                        $attendance->status
                                    ) {

                                        'present' =>
                                            'status-present',

                                        'absent' =>
                                            'status-absent',

                                        'leave' =>
                                            'status-leave',

                                        'half_day' =>
                                            'status-half',

                                        'late' =>
                                            'status-late',

                                        default =>
                                            'status-default',

                                    };


                                    $statusIcon = match(
                                        $attendance->status
                                    ) {

                                        'present' =>
                                            'bi-check-circle-fill',

                                        'absent' =>
                                            'bi-x-circle-fill',

                                        'leave' =>
                                            'bi-calendar-minus-fill',

                                        'half_day' =>
                                            'bi-clock-fill',

                                        'late' =>
                                            'bi-alarm-fill',

                                        default =>
                                            'bi-question-circle-fill',

                                    };

                                @endphp


                                <span class="status-badge {{ $statusClass }}">

                                    <i class="bi {{ $statusIcon }}"></i>

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $attendance->status
                                        )
                                    ) }}

                                </span>

                            </td>


                            <td>

                                @if($attendance->remarks)

                                    <span class="remarks-text">

                                        {{ $attendance->remarks }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-calendar-x"></i>

                                </div>

                                <h6>
                                    No Attendance Records
                                </h6>

                                <p>
                                    No attendance records are available for this student.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
        FOOTER INFORMATION
    ========================================================== --}}

    <div class="report-footer mt-4">

        <div>

            <i class="bi bi-info-circle me-1"></i>

            Attendance percentage is calculated using Present,
            Late and Half Day records.

        </div>

        <div>

            Generated on
            <strong>
                {{ now()->format('d M Y, h:i A') }}
            </strong>

        </div>

    </div>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .attendance-page {
        min-height: calc(100vh - 70px);
        padding: 26px;
        background: #f5f7fb;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .page-header {
        margin-bottom: 25px;
    }

    .back-btn {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff;
        color: #344054;
        border: 1px solid #e4e7ec;
        text-decoration: none;
        transition: .2s ease;
    }

    .back-btn:hover {
        background: #1677f0;
        color: #fff;
        border-color: #1677f0;
        transform: translateX(-2px);
    }

    .page-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eaf2ff;
        color: #1677f0;
        font-size: 19px;
    }

    .page-title {
        font-size: 25px;
        color: #172b4d;
        letter-spacing: -.3px;
    }

    .page-subtitle {
        color: #667085;
        font-size: 14px;
        margin-top: 4px;
    }

    .print-btn {
        border: 1px solid #d0d5dd;
        background: #fff;
        color: #344054;
        border-radius: 11px;
        padding: 10px 17px;
        font-weight: 600;
        transition: .2s ease;
    }

    .print-btn:hover {
        background: #1677f0;
        border-color: #1677f0;
        color: #fff;
        box-shadow: 0 5px 15px rgba(22,119,240,.2);
    }


    /* =========================================================
       STUDENT HERO
    ========================================================== */

    .student-hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        background: linear-gradient(
            135deg,
            #1677f0 0%,
            #155eef 45%,
            #1047b8 100%
        );
        box-shadow: 0 12px 30px rgba(16,71,184,.18);
        color: #fff;
    }

    .hero-pattern {
        position: absolute;
        width: 420px;
        height: 420px;
        right: -160px;
        top: -210px;
        border-radius: 50%;
        border: 70px solid rgba(255,255,255,.05);
        pointer-events: none;
    }

    .student-hero-content {
        position: relative;
        z-index: 2;
        padding: 28px 30px;
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .profile-wrapper {
        flex-shrink: 0;
    }

    .student-photo,
    .student-photo-placeholder {
        width: 108px;
        height: 108px;
        border-radius: 22px;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,.9);
        box-shadow: 0 8px 20px rgba(0,0,0,.16);
    }

    .student-photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.16);
        color: #fff;
        font-size: 45px;
    }

    .student-main-info {
        flex: 1;
        min-width: 0;
    }

    .student-name {
        font-size: 27px;
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: -.4px;
    }

    .student-id {
        color: rgba(255,255,255,.85);
        font-size: 14px;
    }

    .student-id strong {
        color: #fff;
    }

    .student-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
    }

    .student-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 9px;
        background: rgba(255,255,255,.13);
        border: 1px solid rgba(255,255,255,.14);
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }


    /* =========================================================
       ATTENDANCE CIRCLE
    ========================================================== */

    .attendance-circle-wrapper {
        flex-shrink: 0;
    }

    .attendance-circle {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            conic-gradient(
                #fff var(--percentage),
                rgba(255,255,255,.17) var(--percentage)
            );
        position: relative;
    }

    .attendance-circle::before {
        content: "";
        position: absolute;
        width: 102px;
        height: 102px;
        background: #155eef;
        border-radius: 50%;
    }

    .attendance-circle-inner {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .attendance-circle-inner strong {
        font-size: 23px;
        line-height: 1;
    }

    .attendance-circle-inner span {
        margin-top: 6px;
        font-size: 10px;
        opacity: .8;
    }


    /* =========================================================
       STUDENT DETAILS BAR
    ========================================================== */

    .student-details-bar {
        position: relative;
        z-index: 3;
        background: rgba(7,42,110,.30);
        border-top: 1px solid rgba(255,255,255,.12);
        padding: 17px 30px;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .student-detail-item {
        display: flex;
        align-items: center;
        gap: 11px;
        flex: 1;
    }

    .detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.13);
        color: #fff;
    }

    .student-detail-item small {
        display: block;
        color: rgba(255,255,255,.65);
        font-size: 10px;
        margin-bottom: 2px;
    }

    .student-detail-item strong {
        display: block;
        color: #fff;
        font-size: 13px;
    }

    .detail-divider {
        width: 1px;
        height: 35px;
        background: rgba(255,255,255,.14);
    }


    /* =========================================================
       SECTION TITLE
    ========================================================== */

    .section-heading h5 {
        margin: 0;
        color: #172b4d;
        font-size: 17px;
        font-weight: 700;
    }

    .section-heading p {
        margin: 4px 0 0;
        color: #667085;
        font-size: 13px;
    }


    /* =========================================================
       STAT CARDS
    ========================================================== */

    .stat-card {
        background: #fff;
        border-radius: 17px;
        padding: 17px;
        min-height: 145px;
        border: 1px solid #edf0f5;
        box-shadow: 0 4px 14px rgba(16,24,40,.045);
        transition: .22s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 75px;
        height: 75px;
        border-radius: 50%;
        right: -30px;
        bottom: -35px;
        opacity: .06;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(16,24,40,.09);
    }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .stat-arrow {
        color: #98a2b3;
        font-size: 13px;
    }

    .stat-label {
        color: #667085;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .stat-value {
        color: #172b4d;
        font-size: 25px;
        font-weight: 750;
    }


    .total-card .stat-icon {
        background: #edf2f7;
        color: #475467;
    }

    .present-card .stat-icon {
        background: #e9f9ef;
        color: #12b76a;
    }

    .absent-card .stat-icon {
        background: #fff0f0;
        color: #f04438;
    }

    .leave-card .stat-icon {
        background: #fff8e7;
        color: #f79009;
    }

    .halfday-card .stat-icon {
        background: #eaf8ff;
        color: #039be5;
    }

    .percentage-card .stat-icon {
        background: #eaf2ff;
        color: #1677f0;
    }


    /* =========================================================
       ATTENDANCE TABLE CARD
    ========================================================== */

    .attendance-card {
        background: #fff;
        border-radius: 19px;
        border: 1px solid #edf0f5;
        box-shadow: 0 4px 16px rgba(16,24,40,.05);
        overflow: hidden;
    }

    .attendance-card-header {
        padding: 20px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eef1f5;
    }

    .history-title-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .history-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf4ff;
        color: #1677f0;
        font-size: 18px;
    }

    .history-title-wrapper h5 {
        margin: 0;
        color: #172b4d;
        font-size: 16px;
        font-weight: 700;
    }

    .history-title-wrapper p {
        margin: 3px 0 0;
        color: #98a2b3;
        font-size: 12px;
    }

    .record-count {
        padding: 7px 11px;
        border-radius: 9px;
        background: #f2f4f7;
        color: #667085;
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .attendance-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .attendance-table thead th {
        background: #f8fafc;
        color: #667085;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-size: 10px;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 1px solid #eaecf0;
        white-space: nowrap;
    }

    .attendance-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #f0f2f5;
        color: #344054;
        font-size: 13px;
        vertical-align: middle;
    }

    .attendance-table tbody tr {
        transition: .15s ease;
    }

    .attendance-table tbody tr:hover {
        background: #fafcff;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .number-column {
        width: 55px;
        text-align: center;
    }

    .row-number {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f2f4f7;
        color: #667085;
        font-size: 11px;
        font-weight: 700;
    }


    /* =========================================================
       DATE
    ========================================================== */

    .date-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f6ff;
        color: #1677f0;
    }

    .date-cell strong {
        display: block;
        color: #344054;
        font-size: 13px;
    }

    .date-cell small {
        display: block;
        color: #98a2b3;
        font-size: 10px;
        margin-top: 2px;
    }


    /* =========================================================
       BADGES
    ========================================================== */

    .year-badge {
        display: inline-block;
        padding: 6px 9px;
        border-radius: 7px;
        background: #f2f4f7;
        color: #475467;
        font-size: 11px;
        font-weight: 600;
    }

    .class-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 6px 9px;
        border-radius: 7px;
        background: #f3f0ff;
        color: #6941c6;
        font-weight: 700;
        font-size: 11px;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #fff4ed;
        color: #c4320a;
        font-weight: 700;
        font-size: 11px;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-present {
        background: #ecfdf3;
        color: #027a48;
    }

    .status-absent {
        background: #fef3f2;
        color: #b42318;
    }

    .status-leave {
        background: #fffaeb;
        color: #b54708;
    }

    .status-half {
        background: #eff8ff;
        color: #175cd3;
    }

    .status-late {
        background: #f4f3ff;
        color: #5925dc;
    }

    .status-default {
        background: #f2f4f7;
        color: #475467;
    }

    .remarks-text {
        color: #667085;
        font-size: 12px;
    }


    /* =========================================================
       EMPTY
    ========================================================== */

    .empty-state {
        padding: 65px 20px !important;
        text-align: center;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f2f4f7;
        color: #98a2b3;
        font-size: 27px;
    }

    .empty-state h6 {
        color: #344054;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .empty-state p {
        color: #98a2b3;
        font-size: 12px;
        margin: 0;
    }


    /* =========================================================
       FOOTER
    ========================================================== */

    .report-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        color: #98a2b3;
        font-size: 11px;
        padding: 0 4px;
    }

    .report-footer strong {
        color: #667085;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991px) {

        .student-hero-content {
            flex-wrap: wrap;
        }

        .attendance-circle-wrapper {
            margin-left: auto;
        }

        .student-details-bar {
            flex-wrap: wrap;
        }

        .detail-divider {
            display: none;
        }

        .student-detail-item {
            flex: 0 0 calc(50% - 12px);
        }

    }


    @media (max-width: 767px) {

        .attendance-page {
            padding: 15px;
        }

        .page-title {
            font-size: 20px;
        }

        .student-hero-content {
            padding: 22px;
            text-align: center;
            justify-content: center;
        }

        .student-main-info {
            flex: 0 0 100%;
        }

        .student-tags {
            justify-content: center;
        }

        .attendance-circle-wrapper {
            margin: 5px auto 0;
        }

        .student-details-bar {
            padding: 16px 20px;
        }

        .student-detail-item {
            flex: 0 0 100%;
        }

        .attendance-card-header {
            align-items: flex-start;
            gap: 12px;
        }

        .record-count {
            display: none;
        }

        .report-footer {
            flex-direction: column;
        }

    }


    /* =========================================================
       PRINT
    ========================================================== */

    @media print {

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            background: #fff !important;
        }

        body * {
            visibility: hidden;
        }

        .attendance-page,
        .attendance-page * {
            visibility: visible;
        }

        .attendance-page {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
            background: #fff;
        }

        .page-header {
            margin-bottom: 12px;
        }

        .back-btn,
        .print-btn {
            display: none !important;
        }

        .student-hero {
            box-shadow: none;
            border-radius: 10px;
            margin-bottom: 12px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .student-hero-content {
            padding: 15px;
        }

        .student-photo,
        .student-photo-placeholder {
            width: 75px;
            height: 75px;
        }

        .student-name {
            font-size: 19px;
        }

        .attendance-circle {
            width: 90px;
            height: 90px;
        }

        .attendance-circle::before {
            width: 70px;
            height: 70px;
        }

        .attendance-circle-inner strong {
            font-size: 16px;
        }

        .student-details-bar {
            padding: 10px 15px;
        }

        .stat-card {
            min-height: 85px;
            padding: 10px;
            box-shadow: none;
        }

        .stat-top {
            margin-bottom: 5px;
        }

        .stat-icon {
            width: 27px;
            height: 27px;
        }

        .stat-value {
            font-size: 18px;
        }

        .stat-label {
            font-size: 9px;
        }

        .attendance-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .attendance-table tbody td,
        .attendance-table thead th {
            padding: 7px 8px;
            font-size: 9px;
        }

        .status-badge,
        .year-badge,
        .class-badge,
        .section-badge {
            padding: 4px 6px;
            font-size: 8px;
        }

        .date-icon,
        .row-number {
            display: none;
        }

        .date-cell small {
            display: none;
        }

        .report-footer {
            margin-top: 10px !important;
        }

    }

</style>

@endsection