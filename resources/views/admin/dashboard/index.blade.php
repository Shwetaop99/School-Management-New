@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    /* =========================================================
       DASHBOARD
    ========================================================= */

    .dashboard-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
    }

    /* =========================================================
       WELCOME BANNER
    ========================================================= */

    .welcome-card {
        position: relative;
        overflow: hidden;
        min-height: 155px;
        padding: 30px 34px;
        margin-bottom: 24px;

        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 100%
        );

        color: #fff;

        box-shadow:
            0 10px 28px rgba(23, 105, 209, 0.18);

        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .welcome-card::before {
        content: "";
        position: absolute;

        width: 190px;
        height: 190px;

        right: -45px;
        top: -105px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .welcome-card::after {
        content: "";
        position: absolute;

        width: 120px;
        height: 120px;

        right: 100px;
        bottom: -82px;

        border-radius: 50%;

        background: rgba(255,255,255,.12);
    }

    .welcome-card h2 {
        position: relative;
        z-index: 2;

        margin: 0 0 7px;

        font-size: 30px;
        font-weight: 800;
    }

    .welcome-card p {
        position: relative;
        z-index: 2;

        margin: 0;

        font-size: 15px;
        color: rgba(255,255,255,.94);
    }

    /* =========================================================
       MAIN STAT CARDS
    ========================================================= */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;

        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;

        min-height: 165px;

        padding: 24px 25px;

        border-radius: 17px;

        color: #fff;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        box-shadow:
            0 8px 22px rgba(15,23,42,.12);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);

        box-shadow:
            0 15px 32px rgba(15,23,42,.18);
    }

    .stat-card::before {
        content: "";
        position: absolute;

        width: 150px;
        height: 150px;

        right: -50px;
        top: -65px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .stat-card::after {
        content: "";
        position: absolute;

        width: 80px;
        height: 80px;

        right: -20px;
        bottom: -38px;

        border-radius: 50%;

        background: rgba(255,255,255,.08);
    }

    .stat-card.blue {
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
    }

    .stat-card.orange {
        background: linear-gradient(
            135deg,
            #ed9208,
            #f7aa25
        );
    }

    .stat-card.cyan {
        background: linear-gradient(
            135deg,
            #079dbd,
            #16b5d0
        );
    }

    .stat-card.red {
        background: linear-gradient(
            135deg,
            #e94d47,
            #f75d56
        );
    }

    .stat-top {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .stat-number {
        margin: 0 0 7px;

        font-size: 32px;
        line-height: 1;

        font-weight: 800;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;

        color: rgba(255,255,255,.95);
    }

    .stat-icon {
        position: relative;
        z-index: 2;

        font-size: 38px;

        color: rgba(255,255,255,.90);
    }

    .stat-link {
        position: relative;
        z-index: 2;

        width: fit-content;

        margin-top: 15px;

        color: #fff;
        text-decoration: none;

        font-size: 12px;
        font-weight: 600;

        transition: .2s ease;
    }

    .stat-link:hover {
        color: #fff;
        transform: translateX(4px);
    }

    /* =========================================================
       TWO COLUMN SECTION
    ========================================================= */

    .dashboard-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);

        gap: 22px;

        margin-bottom: 22px;
    }

    .dashboard-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #e5ebf3;

        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15,23,42,.06);
    }

    .card-header {
        min-height: 70px;

        padding: 17px 22px;

        border-bottom: 1px solid #edf1f6;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .card-title i {
        color: #1769d1;
        font-size: 19px;
    }

    .card-title h3 {
        margin: 0;

        color: #172033;

        font-size: 17px;
        font-weight: 700;
    }

    .card-subtitle {
        margin: 4px 0 0 28px;

        color: #718096;

        font-size: 12px;
    }

    .card-body {
        padding: 22px;
    }

    /* =========================================================
       STUDENT STATISTICS
    ========================================================= */

    .student-stat-content {
        min-height: 230px;

        display: flex;
        align-items: center;
        justify-content: space-around;

        gap: 30px;
    }

    .student-chart {
        width: 175px;
        height: 175px;

        flex-shrink: 0;

        position: relative;

        border-radius: 50%;

        background:
            conic-gradient(
                #1769d1 0deg 216deg,
                #ec4899 216deg 360deg
            );

        display: flex;
        align-items: center;
        justify-content: center;

        box-shadow:
            0 8px 20px rgba(23,105,209,.12);
    }

    .student-chart::after {
        content: "";

        position: absolute;

        width: 112px;
        height: 112px;

        background: #fff;

        border-radius: 50%;
    }

    .chart-total {
        position: relative;
        z-index: 2;

        text-align: center;
    }

    .chart-total strong {
        display: block;

        color: #172033;

        font-size: 27px;
        font-weight: 800;
    }

    .chart-total span {
        color: #64748b;
        font-size: 12px;
    }

    .student-legend {
        display: flex;
        flex-direction: column;

        gap: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;

        gap: 10px;
    }

    .legend-dot {
        width: 12px;
        height: 12px;

        border-radius: 50%;
    }

    .male-dot {
        background: #1769d1;
    }

    .female-dot {
        background: #ec4899;
    }

    .legend-text strong {
        display: block;

        color: #172033;

        font-size: 17px;
    }

    .legend-text span {
        color: #64748b;

        font-size: 12px;
    }

    /* =========================================================
       CALENDAR
    ========================================================= */

    .calendar-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 17px;
    }

    .calendar-button {
        width: 34px;
        height: 34px;

        border: 1px solid #e3eaf2;

        background: #f8fafc;

        border-radius: 9px;

        color: #64748b;

        cursor: pointer;

        transition: .2s ease;
    }

    .calendar-button:hover {
        background: #1769d1;
        color: #fff;
        border-color: #1769d1;
    }

    .calendar-title {
        color: #172033;

        font-size: 18px;
        font-weight: 700;
    }

    .calendar-week,
    .calendar-days {
        display: grid;

        grid-template-columns:
            repeat(7, 1fr);

        gap: 6px;
    }

    .calendar-week {
        margin-bottom: 7px;
    }

    .calendar-week div {
        padding: 5px 0;

        text-align: center;

        color: #64748b;

        font-size: 11px;
        font-weight: 700;
    }

    .calendar-day {
        min-height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        color: #334155;

        font-size: 12px;

        transition: .2s ease;
    }

    .calendar-day:hover {
        background: #edf5ff;
        color: #1769d1;
    }

    .calendar-day.empty {
        visibility: hidden;
    }

    .calendar-day.today {
        background: #1769d1;

        color: #fff;

        font-weight: 700;

        box-shadow:
            0 4px 10px rgba(23,105,209,.25);
    }

    .calendar-day.weekend {
        color: #ef4444;
    }

    .calendar-day.today.weekend {
        color: #fff;
    }

    /* =========================================================
       SCHOOL OVERVIEW
    ========================================================= */

    .overview-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0,1fr));

        gap: 15px;
    }

    .overview-item {
        padding: 18px;

        background: #f8fafc;

        border: 1px solid #e6edf5;

        border-radius: 13px;

        transition: .2s ease;
    }

    .overview-item:hover {
        transform: translateY(-3px);

        background: #f2f7ff;
    }

    .overview-icon {
        width: 42px;
        height: 42px;

        margin-bottom: 12px;

        border-radius: 11px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 19px;
    }

    .overview-item strong {
        display: block;

        margin-bottom: 3px;

        color: #172033;

        font-size: 22px;
        font-weight: 800;
    }

    .overview-item span {
        color: #64748b;

        font-size: 12px;
    }

    .blue-bg {
        background: #dbeafe;
        color: #1769d1;
    }

    .purple-bg {
        background: #ede9fe;
        color: #7c3aed;
    }

    .green-bg {
        background: #dcfce7;
        color: #16a34a;
    }

    .orange-bg {
        background: #ffedd5;
        color: #ea580c;
    }

    /* =========================================================
       NOTICES
    ========================================================= */

    .notice-list {
        display: flex;
        flex-direction: column;
    }

    .notice-item {
        padding: 14px 0;

        display: flex;
        align-items: center;

        gap: 14px;

        border-bottom: 1px solid #edf1f6;
    }

    .notice-item:last-child {
        border-bottom: none;
    }

    .notice-icon {
        width: 42px;
        height: 42px;

        min-width: 42px;

        border-radius: 11px;

        background: #e7f0ff;

        color: #1769d1;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notice-content strong {
        display: block;

        margin-bottom: 4px;

        color: #172033;

        font-size: 13px;
    }

    .notice-content span {
        color: #64748b;

        font-size: 11px;
    }

    .empty-state {
        padding: 30px;

        text-align: center;

        color: #94a3b8;

        font-size: 13px;
    }

    /* =========================================================
       QUICK ACTIONS
    ========================================================= */

    .quick-actions {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0,1fr));

        gap: 13px;
    }

    .quick-action {
        min-height: 82px;

        padding: 15px;

        border: 1px solid #e6edf5;

        border-radius: 12px;

        background: #f8fafc;

        text-decoration: none;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        gap: 8px;

        transition: .2s ease;
    }

    .quick-action:hover {
        transform: translateY(-3px);

        background: #edf5ff;

        border-color: #c9dcfa;
    }

    .quick-action i {
        color: #1769d1;

        font-size: 21px;
    }

    .quick-action span {
        color: #334155;

        font-size: 12px;
        font-weight: 600;

        text-align: center;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .stats-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .overview-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .quick-actions {
            grid-template-columns:
                repeat(3, 1fr);
        }
    }

    @media (max-width: 850px) {

        .dashboard-container {
            padding: 18px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .student-stat-content {
            flex-direction: column;
        }
    }

    @media (max-width: 650px) {

        .stats-grid,
        .overview-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .welcome-card {
            padding: 25px;
        }

        .welcome-card h2 {
            font-size: 24px;
        }

        .stat-card {
            min-height: 150px;
        }
    }
</style>


@php
    /*
    |--------------------------------------------------------------------------
    | Dynamic values
    |--------------------------------------------------------------------------
    */

    $studentCount = (int) ($students ?? 0);
    $teacherCount = (int) ($teachers ?? 0);
    $classCount = (int) ($classes ?? 0);
    $noticeCount = (int) ($notices ?? 0);

    /*
    |--------------------------------------------------------------------------
    | Calendar
    |--------------------------------------------------------------------------
    */

    $currentDate = now();

    $calendarMonth = $currentDate->format('F');
    $calendarYear = $currentDate->year;

    $daysInMonth = $currentDate->daysInMonth;

    $firstDay = $currentDate->copy()
        ->startOfMonth()
        ->dayOfWeek;

    /*
    |--------------------------------------------------------------------------
    | Student statistics
    |--------------------------------------------------------------------------
    */

    $maleStudents = (int) ($maleStudents ?? 0);
    $femaleStudents = (int) ($femaleStudents ?? 0);

    /*
    | If gender data isn't available yet, use total students
    | as the chart total.
    */

    $genderTotal = $maleStudents + $femaleStudents;

    if ($genderTotal === 0 && $studentCount > 0) {
        $maleStudents = $studentCount;
        $femaleStudents = 0;
        $genderTotal = $studentCount;
    }

    $malePercentage = $genderTotal > 0
        ? round(($maleStudents / $genderTotal) * 100)
        : 0;

    $femalePercentage = $genderTotal > 0
        ? 100 - $malePercentage
        : 0;

    $maleDegrees = $malePercentage * 3.6;
    $femaleDegrees = $femalePercentage * 3.6;

    /*
    |--------------------------------------------------------------------------
    | Recent notices
    |--------------------------------------------------------------------------
    */

    $recentNotices = $recentNotices ?? collect();
@endphp


<div class="dashboard-container">

    {{-- =====================================================
         WELCOME
    ====================================================== --}}

    <div class="welcome-card">

        <h2>
            Welcome back, Admin! 👋
        </h2>

        <p>
            Here's what's happening across your school today.
        </p>

    </div>


    {{-- =====================================================
         MAIN STATISTICS
    ====================================================== --}}

    <div class="stats-grid">

        {{-- STUDENTS --}}
        <div class="stat-card blue">

            <div class="stat-top">

                <div>
                    <div class="stat-number">
                        {{ number_format($studentCount) }}
                    </div>

                    <div class="stat-title">
                        Total Students
                    </div>
                </div>

                <i class="bi bi-people-fill stat-icon"></i>

            </div>

            <a href="#" class="stat-link">
                View students →
            </a>

        </div>


        {{-- FACULTY --}}
        <div class="stat-card orange">

            <div class="stat-top">

                <div>
                    <div class="stat-number">
                        {{ number_format($teacherCount) }}
                    </div>

                    <div class="stat-title">
                        Total Faculty
                    </div>
                </div>

                <i class="bi bi-person-workspace stat-icon"></i>

            </div>

            <a href="#" class="stat-link">
                View faculty →
            </a>

        </div>


        {{-- CLASSES --}}
        <div class="stat-card cyan">

            <div class="stat-top">

                <div>
                    <div class="stat-number">
                        {{ number_format($classCount) }}
                    </div>

                    <div class="stat-title">
                        Total Classes
                    </div>
                </div>

                <i class="bi bi-grid-3x3-gap-fill stat-icon"></i>

            </div>

            <a href="#" class="stat-link">
                View classes →
            </a>

        </div>


        {{-- NOTICES --}}
        <div class="stat-card red">

            <div class="stat-top">

                <div>
                    <div class="stat-number">
                        {{ number_format($noticeCount) }}
                    </div>

                    <div class="stat-title">
                        Total Notices
                    </div>
                </div>

                <i class="bi bi-megaphone-fill stat-icon"></i>

            </div>

            <a href="#" class="stat-link">
                View notices →
            </a>

        </div>

    </div>


    {{-- =====================================================
         STUDENT STATISTICS + CALENDAR
    ====================================================== --}}

    <div class="dashboard-grid">


        {{-- STUDENT STATISTICS --}}
        <div class="dashboard-card">

            <div class="card-header">

                <div>

                    <div class="card-title">

                        <i class="bi bi-pie-chart-fill"></i>

                        <h3>
                            Student Statistics
                        </h3>

                    </div>

                    <div class="card-subtitle">
                        Gender-wise student distribution
                    </div>

                </div>

                <div class="blue-bg"
                     style="
                        padding:8px 12px;
                        border-radius:9px;
                        font-size:12px;
                        font-weight:700;
                     ">

                    Total {{ number_format($studentCount) }}

                </div>

            </div>


            <div class="card-body">

                <div class="student-stat-content">

                    <div
                        class="student-chart"
                        style="
                            background:
                            conic-gradient(
                                #1769d1 0deg {{ $maleDegrees }}deg,
                                #ec4899 {{ $maleDegrees }}deg 360deg
                            );
                        "
                    >

                        <div class="chart-total">

                            <strong>
                                {{ number_format($studentCount) }}
                            </strong>

                            <span>
                                Students
                            </span>

                        </div>

                    </div>


                    <div class="student-legend">

                        <div class="legend-item">

                            <div class="legend-dot male-dot"></div>

                            <div class="legend-text">

                                <strong>
                                    {{ number_format($maleStudents) }}
                                </strong>

                                <span>
                                    Male · {{ $malePercentage }}%
                                </span>

                            </div>

                        </div>


                        <div class="legend-item">

                            <div class="legend-dot female-dot"></div>

                            <div class="legend-text">

                                <strong>
                                    {{ number_format($femaleStudents) }}
                                </strong>

                                <span>
                                    Female · {{ $femalePercentage }}%
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SCHOOL CALENDAR --}}
        <div class="dashboard-card">

            <div class="card-header">

                <div>

                    <div class="card-title">

                        <i class="bi bi-calendar3"></i>

                        <h3>
                            School Calendar
                        </h3>

                    </div>

                    <div class="card-subtitle">
                        Academic events and important dates
                    </div>

                </div>

                <button
                    type="button"
                    class="calendar-button"
                    title="View Calendar"
                >
                    <i class="bi bi-calendar-event"></i>
                </button>

            </div>


            <div class="card-body">

                <div class="calendar-controls">

                    <button
                        type="button"
                        class="calendar-button"
                    >
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <div class="calendar-title">
                        {{ $calendarMonth }} {{ $calendarYear }}
                    </div>

                    <button
                        type="button"
                        class="calendar-button"
                    >
                        <i class="bi bi-chevron-right"></i>
                    </button>

                </div>


                <div class="calendar-week">

                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>

                </div>


                <div class="calendar-days">

                    {{-- Empty days before month starts --}}
                    @for ($i = 0; $i < $firstDay; $i++)

                        <div class="calendar-day empty"></div>

                    @endfor


                    {{-- Actual days --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)

                        @php

                            $date = $currentDate->copy()
                                ->startOfMonth()
                                ->addDays($day - 1);

                            $isToday = $date->isToday();

                            $isWeekend = $date->isWeekend();

                        @endphp


                        <div
                            class="
                                calendar-day
                                {{ $isToday ? 'today' : '' }}
                                {{ $isWeekend ? 'weekend' : '' }}
                            "
                        >
                            {{ $day }}
                        </div>

                    @endfor

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SCHOOL OVERVIEW
    ====================================================== --}}

    <div class="dashboard-card" style="margin-bottom:22px;">

        <div class="card-header">

            <div>

                <div class="card-title">

                    <i class="bi bi-bar-chart-fill"></i>

                    <h3>
                        School Overview
                    </h3>

                </div>

                <div class="card-subtitle">
                    Current school management statistics
                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="overview-grid">


                <div class="overview-item">

                    <div class="overview-icon blue-bg">
                        <i class="bi bi-book-fill"></i>
                    </div>

                    <strong>
                        0
                    </strong>

                    <span>
                        Library Books
                    </span>

                </div>


                <div class="overview-item">

                    <div class="overview-icon green-bg">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>

                    <strong>
                        0
                    </strong>

                    <span>
                        Transport Records
                    </span>

                </div>


                <div class="overview-item">

                    <div class="overview-icon orange-bg">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>

                    <strong>
                        0
                    </strong>

                    <span>
                        School Events
                    </span>

                </div>


                <div class="overview-item">

                    <div class="overview-icon purple-bg">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <strong>
                        {{ number_format($classCount) }}
                    </strong>

                    <span>
                        Active Classes
                    </span>

                </div>


            </div>

        </div>

    </div>


    {{-- =====================================================
         NOTICES + QUICK ACTIONS
    ====================================================== --}}

    <div class="dashboard-grid">


        {{-- RECENT NOTICES --}}
        <div class="dashboard-card">

            <div class="card-header">

                <div>

                    <div class="card-title">

                        <i class="bi bi-megaphone-fill"></i>

                        <h3>
                            Recent Notices
                        </h3>

                    </div>

                    <div class="card-subtitle">
                        Latest announcements from the school
                    </div>

                </div>

            </div>


            <div class="card-body">

                @if (
                    $recentNotices instanceof \Illuminate\Support\Collection
                    && $recentNotices->count() > 0
                )

                    <div class="notice-list">

                        @foreach ($recentNotices as $notice)

                            <div class="notice-item">

                                <div class="notice-icon">
                                    <i class="bi bi-bell-fill"></i>
                                </div>

                                <div class="notice-content">

                                    <strong>
                                        {{ $notice->title ?? 'School Notice' }}
                                    </strong>

                                    <span>
                                        {{ $notice->description ?? 'New school announcement available.' }}
                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-state">

                        <i
                            class="bi bi-inbox"
                            style="
                                font-size:30px;
                                display:block;
                                margin-bottom:10px;
                            "
                        ></i>

                        No recent notices available.

                    </div>

                @endif

            </div>

        </div>


        {{-- QUICK ACTIONS --}}
        <div class="dashboard-card">

            <div class="card-header">

                <div>

                    <div class="card-title">

                        <i class="bi bi-lightning-charge-fill"></i>

                        <h3>
                            Quick Actions
                        </h3>

                    </div>

                    <div class="card-subtitle">
                        Frequently used school management actions
                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="quick-actions">


                    <a href="#" class="quick-action">

                        <i class="bi bi-person-plus-fill"></i>

                        <span>
                            Add Student
                        </span>

                    </a>


                    <a href="#" class="quick-action">

                        <i class="bi bi-person-workspace"></i>

                        <span>
                            Add Faculty
                        </span>

                    </a>


                    <a href="#" class="quick-action">

                        <i class="bi bi-calendar-check-fill"></i>

                        <span>
                            Mark Attendance
                        </span>

                    </a>


                    <a href="#" class="quick-action">

                        <i class="bi bi-cash-stack"></i>

                        <span>
                            Collect Fees
                        </span>

                    </a>


                    <a href="#" class="quick-action">

                        <i class="bi bi-megaphone-fill"></i>

                        <span>
                            Create Notice
                        </span>

                    </a>


                    <a href="#" class="quick-action">

                        <i class="bi bi-book-fill"></i>

                        <span>
                            Add Book
                        </span>

                    </a>


                </div>

            </div>

        </div>

    </div>

</div>

@endsection