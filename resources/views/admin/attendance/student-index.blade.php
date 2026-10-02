@extends('layouts.app')

@section('title', 'Student Attendance')
@section('page-title', 'Student Attendance')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    /* =========================================================
       GLOBAL
    ========================================================= */

    * {
        box-sizing: border-box;
    }

    .attendance-page {
        min-height: calc(100vh - 80px);
        padding: 28px;
        background: #f4f7fb;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .attendance-header {
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
        padding: 30px 32px;
        border-radius: 20px;
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 52%,
            #6c63ff 100%
        );
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(23, 105, 209, 0.18);
    }

    .attendance-header::before {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -80px;
        top: -120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .attendance-header::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        right: 180px;
        bottom: -110px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .attendance-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .attendance-header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .attendance-header-icon {
        width: 62px;
        height: 62px;
        min-width: 62px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 17px;
        background: rgba(255, 255, 255, 0.17);
        border: 1px solid rgba(255, 255, 255, 0.20);
        font-size: 29px;
        backdrop-filter: blur(8px);
    }

    .attendance-header-text h2 {
        margin: 0 0 7px;
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .attendance-header-text p {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.88);
        font-size: 13px;
        line-height: 1.6;
    }

    .attendance-month {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 16px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        backdrop-filter: blur(8px);
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .attendance-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 26px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        min-height: 145px;
        padding: 21px;
        border: none;
        border-radius: 17px;
        color: #ffffff;
        box-shadow: 0 8px 22px rgba(20, 50, 90, 0.10);
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(20, 50, 90, 0.15);
    }

    .stat-card::before {
        content: "";
        position: absolute;
        width: 65px;
        height: 65px;
        right: 42px;
        top: -32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 105px;
        height: 105px;
        right: -30px;
        bottom: -45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.10);
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

    .stat-content {
        position: relative;
        z-index: 2;
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.17);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-size: 21px;
        backdrop-filter: blur(5px);
    }

    .stat-small {
        padding: 5px 9px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.94);
        font-size: 10px;
        font-weight: 700;
    }

    .stat-number {
        margin-top: 15px;
        color: #ffffff;
        font-size: 29px;
        font-weight: 800;
        line-height: 1;
    }

    .stat-label {
        display: block;
        margin-top: 8px;
        color: rgba(255, 255, 255, 0.91);
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .classes-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4e9f1;
        border-radius: 18px;
        box-shadow: 0 7px 24px rgba(20, 50, 90, 0.07);
    }

    .classes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 25px;
        border-bottom: 1px solid #edf1f6;
    }

    .classes-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .classes-heading-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #eaf3ff;
        color: #1769d1;
        font-size: 20px;
    }

    .classes-heading-text h3 {
        margin: 0 0 4px;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .classes-heading-text p {
        margin: 0;
        color: #8994a5;
        font-size: 12px;
    }

    .class-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 10px;
        background: #f0f7ff;
        color: #1769d1;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .classes-table {
        width: 100%;
        min-width: 1180px;
        margin: 0;
        border-collapse: collapse;
    }

    .classes-table thead th {
        padding: 15px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e5ebf3;
        color: #6c7789;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.55px;
        white-space: nowrap;
    }

    .classes-table tbody td {
        padding: 17px 18px;
        border-bottom: 1px solid #edf1f6;
        color: #273449;
        font-size: 13px;
        vertical-align: middle;
    }

    .classes-table tbody tr {
        transition: background 0.2s ease;
    }

    .classes-table tbody tr:hover {
        background: #f9fbff;
    }

    .classes-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       CLASS
    ========================================================= */

    .class-info {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .class-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: linear-gradient(
            135deg,
            #e9f3ff,
            #eef8ff
        );
        color: #1769d1;
        font-size: 18px;
    }

    .class-name {
        display: block;
        color: #182337;
        font-size: 14px;
        font-weight: 800;
    }

    /* =========================================================
       ACADEMIC YEAR
    ========================================================= */

    .academic-year-cell {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        border-radius: 9px;
        background: #f5f7ff;
        color: #5965c9;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .academic-year-cell i {
        font-size: 13px;
    }

    /* =========================================================
       SECTION
    ========================================================= */

    .section-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        padding: 7px 11px;
        border-radius: 8px;
        background: #f0efff;
        color: #6258e8;
        font-size: 12px;
        font-weight: 800;
    }

    /* =========================================================
       STUDENTS
    ========================================================= */

    .student-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #344054;
        font-size: 13px;
        font-weight: 800;
    }

    .student-count i {
        color: #1769d1;
        font-size: 16px;
    }

    /* =========================================================
       ATTENDANCE
    ========================================================= */

    .attendance-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 800;
    }

    .attendance-count i {
        font-size: 15px;
    }

    .present-count {
        color: #16a34a;
    }

    .absent-count {
        color: #e94d47;
    }

    .attendance-label {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 600;
    }

    /* =========================================================
       TEACHER
    ========================================================= */

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .teacher-avatar {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(
            135deg,
            #1769d1,
            #6c63ff
        );
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
    }

    .teacher-name {
        max-width: 180px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .not-assigned {
        color: #94a3b8;
        font-style: italic;
    }

    /* =========================================================
       ACTION BUTTON
    ========================================================= */

    .mark-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 14px;
        border: none;
        border-radius: 9px;
        background: linear-gradient(
            135deg,
            #1769d1,
            #159cc7
        );
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 5px 13px rgba(23, 105, 209, 0.17);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .mark-btn:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(23, 105, 209, 0.25);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #eef5ff;
        color: #1769d1;
        font-size: 31px;
    }

    .empty-state h4 {
        margin: 0 0 7px;
        color: #263247;
        font-size: 17px;
        font-weight: 800;
    }

    .empty-state p {
        margin: 0;
        color: #8b95a5;
        font-size: 13px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {
        .attendance-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .attendance-page {
            padding: 20px;
        }

        .attendance-header-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .attendance-month {
            align-self: flex-start;
        }
    }

    @media (max-width: 576px) {
        .attendance-page {
            padding: 14px;
        }

        .attendance-header {
            padding: 22px 19px;
            border-radius: 16px;
        }

        .attendance-header-left {
            align-items: flex-start;
            gap: 13px;
        }

        .attendance-header-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 13px;
            font-size: 23px;
        }

        .attendance-header-text h2 {
            font-size: 21px;
        }

        .attendance-header-text p {
            font-size: 12px;
        }

        .attendance-stats {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .stat-card {
            min-height: 130px;
        }

        .classes-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px;
        }

        .classes-heading-text h3 {
            font-size: 16px;
        }

        .classes-table {
            min-width: 1180px;
        }
    }
</style>


{{-- =========================================================
     PAGE
========================================================= --}}

<div class="attendance-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="attendance-header">

        <div class="attendance-header-content">

            <div class="attendance-header-left">

                <div class="attendance-header-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>

                <div class="attendance-header-text">

                    <h2>
                        Student Attendance
                    </h2>

                    <p>
                        Manage daily student attendance by class and section.
                        View total present and absent students and mark attendance efficiently.
                    </p>

                </div>

            </div>

            <div class="attendance-month">

                <i class="bi bi-calendar3"></i>

                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}

            </div>

        </div>

    </section>


    {{-- =====================================================
         DASHBOARD STATISTICS
    ====================================================== --}}

    <section class="attendance-stats">

        {{-- TOTAL CLASSES --}}

        <div class="stat-card blue">

            <div class="stat-content">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <span class="stat-small">
                        Academic
                    </span>

                </div>

                <div class="stat-number">
                    {{ $classCards->count() }}
                </div>

                <span class="stat-label">
                    Total Classes & Sections
                </span>

            </div>

        </div>


        {{-- TOTAL STUDENTS --}}

        <div class="stat-card orange">

            <div class="stat-content">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <span class="stat-small">
                        All Students
                    </span>

                </div>

                <div class="stat-number">
                    {{ $totalStudents }}
                </div>

                <span class="stat-label">
                    Total Students
                </span>

            </div>

        </div>


        {{-- ALL PRESENT STUDENTS --}}

        <div class="stat-card cyan">

            <div class="stat-content">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>

                    <span class="stat-small">
                        Attendance
                    </span>

                </div>

                <div class="stat-number">
                    {{ $totalPresent }}
                </div>

                <span class="stat-label">
                    Total Students Present
                </span>

            </div>

        </div>


        {{-- ALL ABSENT STUDENTS --}}

        <div class="stat-card red">

            <div class="stat-content">

                <div class="stat-top">

                    <div class="stat-icon">
                        <i class="bi bi-person-x-fill"></i>
                    </div>

                    <span class="stat-small">
                        Attendance
                    </span>

                </div>

                <div class="stat-number">
                    {{ $totalAbsent }}
                </div>

                <span class="stat-label">
                    Total Students Absent
                </span>

            </div>

        </div>

    </section>


    {{-- =====================================================
         CLASSES & SECTIONS
    ====================================================== --}}

    <section class="classes-card">

        {{-- CARD HEADER --}}

        <div class="classes-header">

            <div class="classes-heading">

                <div class="classes-heading-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>

                <div class="classes-heading-text">

                    <h3>
                        Classes & Sections
                    </h3>

                    <p>
                        Select a class and section to manage student attendance
                    </p>

                </div>

            </div>

            <div class="class-count">

                <i class="bi bi-layers-fill"></i>

                {{ $classCards->count() }} Classes

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        @if($classCards->count())

            <div class="table-container">

                <table class="classes-table">

                    <thead>

                        <tr>

                            <th>
                                Class
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Students
                            </th>

                            <th>
                                Present
                            </th>

                            <th>
                                Absent
                            </th>

                            <th>
                                Class Teacher
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($classCards as $card)

                            @php

                                $teacherName = trim(
                                    $card->class_teacher ?? 'Not Assigned'
                                );

                                $teacherWords = preg_split(
                                    '/\s+/',
                                    $teacherName
                                );

                                $initials = '';

                                foreach (
                                    array_slice($teacherWords, 0, 2)
                                    as $word
                                ) {

                                    if (!empty($word)) {

                                        $initials .= strtoupper(
                                            substr($word, 0, 1)
                                        );

                                    }

                                }

                                if (!$initials) {
                                    $initials = 'NA';
                                }

                            @endphp

                            <tr>

                                {{-- CLASS --}}

                                <td>

                                    <div class="class-info">

                                        <div class="class-icon">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>

                                        <span class="class-name">
                                            {{ $card->class ?? '-' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- ACADEMIC YEAR --}}

                                <td>

                                    <span class="academic-year-cell">

                                        <i class="bi bi-calendar3"></i>

                                        {{ $card->academic_year ?? '-' }}

                                    </span>

                                </td>


                                {{-- SECTION --}}

                                <td>

                                    <span class="section-badge">
                                        {{ $card->section ?? '-' }}
                                    </span>

                                </td>


                                {{-- STUDENTS --}}

                                <td>

                                    <span class="student-count">

                                        <i class="bi bi-people-fill"></i>

                                        {{ $card->student_count ?? 0 }}

                                    </span>

                                </td>


                                {{-- PRESENT --}}

                                <td>

                                    <div class="attendance-count present-count">

                                        <i class="bi bi-check-circle-fill"></i>

                                        {{ $card->present_count ?? 0 }}

                                    </div>

                                    <span class="attendance-label">
                                        Present
                                    </span>

                                </td>


                                {{-- ABSENT --}}

                                <td>

                                    <div class="attendance-count absent-count">

                                        <i class="bi bi-x-circle-fill"></i>

                                        {{ $card->absent_count ?? 0 }}

                                    </div>

                                    <span class="attendance-label">
                                        Absent
                                    </span>

                                </td>


                                {{-- CLASS TEACHER --}}

                                <td>

                                    @if(
                                        !empty($card->class_teacher) &&
                                        trim($card->class_teacher) !== ''
                                    )

                                        <div class="teacher-info">

                                            <div class="teacher-avatar">
                                                {{ $initials }}
                                            </div>

                                            <span class="teacher-name">
                                                {{ $teacherName }}
                                            </span>

                                        </div>

                                    @else

                                        <div class="teacher-info">

                                            <div class="teacher-avatar">
                                                NA
                                            </div>

                                            <span class="teacher-name not-assigned">
                                                Not Assigned
                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- ACTION --}}

                                <td>

                                    <a
                                        href="{{ route('admin.attendance.student.sheet', [
                                            'academic_year' => $card->academic_year,
                                            'class' => $card->class,
                                            'section' => $card->section,
                                            'month' => $month
                                        ]) }}"
                                        class="mark-btn"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        <span>
                                            Mark Attendance
                                        </span>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <h4>
                    No Classes Found
                </h4>

                <p>
                    No active students or class sections are currently
                    available for attendance.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection