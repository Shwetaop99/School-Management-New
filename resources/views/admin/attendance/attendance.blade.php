@extends('layouts.app')

@section('title', 'Student Attendance')
@section('page-title', 'Student Attendance')

@section('content')

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
>

<style>

/* =========================================================
   PAGE
========================================================= */

.attendance-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
}


/* =========================================================
   WELCOME HEADER
========================================================= */

.attendance-welcome {
    position: relative;
    overflow: hidden;
    min-height: 150px;
    padding: 28px 34px;
    margin-bottom: 24px;
    border-radius: 20px;

    background: linear-gradient(
        135deg,
        #1769d1 0%,
        #159cc7 55%,
        #6c63ff 100%
    );

    color: #fff;

    box-shadow:
        0 12px 30px rgba(23, 105, 209, .18);
}

.attendance-welcome::before {
    content: "";
    position: absolute;

    width: 230px;
    height: 230px;

    right: -70px;
    top: -135px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.attendance-welcome::after {
    content: "";
    position: absolute;

    width: 150px;
    height: 150px;

    right: 120px;
    bottom: -105px;

    border-radius: 50%;

    background: rgba(255,255,255,.09);
}

.attendance-welcome-content {
    position: relative;
    z-index: 2;

    min-height: 94px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.attendance-welcome-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

.attendance-main-icon {
    width: 64px;
    height: 64px;
    min-width: 64px;

    border-radius: 17px;

    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.20);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 30px;

    box-shadow:
        0 8px 20px rgba(0,0,0,.08);
}

.attendance-welcome h2 {
    margin: 0 0 7px;

    font-size: 30px;
    font-weight: 800;
    letter-spacing: -.4px;
}

.attendance-welcome p {
    margin: 0;

    color: rgba(255,255,255,.94);

    font-size: 14px;
}

.attendance-header-badge {
    padding: 11px 16px;

    border-radius: 11px;

    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.20);

    color: #fff;

    font-size: 12px;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   FILTER CARD
========================================================= */

.attendance-filter-card {
    overflow: hidden;

    margin-bottom: 22px;

    background: #fff;

    border: 1px solid #e5ebf3;
    border-radius: 17px;

    box-shadow:
        0 6px 22px rgba(15,23,42,.06);
}

.attendance-card-header {
    min-height: 70px;

    padding: 17px 23px;

    border-bottom: 1px solid #edf1f6;

    display: flex;
    align-items: center;
    justify-content: space-between;
}

.attendance-card-title {
    display: flex;
    align-items: center;
    gap: 9px;
}

.attendance-card-title i {
    color: #1769d1;
    font-size: 19px;
}

.attendance-card-title h3 {
    margin: 0;

    color: #172033;

    font-size: 17px;
    font-weight: 750;
}

.attendance-card-subtitle {
    margin: 5px 0 0 28px;

    color: #718096;

    font-size: 12px;
}

.attendance-card-body {
    padding: 23px;
}


/* =========================================================
   FILTER CONTROLS
========================================================= */

.filter-label {
    display: block;

    margin-bottom: 7px;

    color: #334155;

    font-size: 12px;
    font-weight: 700;
}

.filter-control {
    height: 45px;

    border: 1px solid #dfe7f0;
    border-radius: 10px;

    color: #334155;

    background: #fff;

    font-size: 13px;

    transition: all .2s ease;
}

.filter-control:hover {
    border-color: #cbd8e7;
}

.filter-control:focus {
    border-color: #1769d1;

    box-shadow:
        0 0 0 3px rgba(23,105,209,.10);
}

.filter-control:disabled {
    background: #f8fafc;

    cursor: not-allowed;
}

.view-attendance-btn {
    height: 45px;

    padding: 0 20px;

    border: none;
    border-radius: 10px;

    background: linear-gradient(
        135deg,
        #1769d1,
        #237de0
    );

    color: #fff;

    font-size: 13px;
    font-weight: 700;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    box-shadow:
        0 5px 14px rgba(23,105,209,.20);

    transition: all .2s ease;
}

.view-attendance-btn:hover {
    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 9px 20px rgba(23,105,209,.25);
}

.view-attendance-btn:disabled {
    opacity: .7;
    transform: none;
}


/* =========================================================
   SUNDAY HOLIDAY NOTICE
========================================================= */

.sunday-holiday-alert {
    display: none;

    margin-bottom: 22px;

    padding: 18px 20px;

    border: 1px solid #f3c4c9;
    border-radius: 15px;

    background: linear-gradient(
        135deg,
        #fff5f5,
        #fff0f1
    );

    color: #a92535;

    box-shadow:
        0 5px 16px rgba(201,45,61,.07);
}

.sunday-holiday-alert.show {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;
}

.sunday-alert-left {
    display: flex;
    align-items: center;

    gap: 13px;
}

.sunday-alert-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;

    border-radius: 12px;

    background: #ffe2e5;

    color: #c92d3d;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 21px;
}

.sunday-alert-title {
    margin: 0 0 3px;

    color: #a92535;

    font-size: 14px;
    font-weight: 800;
}

.sunday-alert-text {
    margin: 0;

    color: #b24b57;

    font-size: 12px;
}

.sunday-alert-badge {
    padding: 7px 12px;

    border-radius: 8px;

    background: #ffe2e5;

    color: #c92d3d;

    font-size: 11px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.attendance-stats {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 20px;

    margin-bottom: 22px;
}

.attendance-stat-card {
    position: relative;

    overflow: hidden;

    min-height: 145px;

    padding: 23px 25px;

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

.attendance-stat-card:hover {
    transform: translateY(-4px);

    box-shadow:
        0 14px 30px rgba(15,23,42,.16);
}

.attendance-stat-card::before {
    content: "";

    position: absolute;

    width: 160px;
    height: 160px;

    right: -55px;
    top: -70px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.attendance-stat-card::after {
    content: "";

    position: absolute;

    width: 85px;
    height: 85px;

    right: -22px;
    bottom: -40px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}

.attendance-stat-card.blue {
    background: linear-gradient(
        135deg,
        #1769d1,
        #237de0
    );
}

.attendance-stat-card.green {
    background: linear-gradient(
        135deg,
        #20a95c,
        #21b866
    );
}

.attendance-stat-card.red {
    background: linear-gradient(
        135deg,
        #e94d47,
        #f75d56
    );
}

.attendance-stat-top {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.attendance-stat-number {
    margin: 0 0 7px;

    font-size: 32px;
    line-height: 1;

    font-weight: 800;
}

.attendance-stat-title {
    color: rgba(255,255,255,.95);

    font-size: 14px;
    font-weight: 600;
}

.attendance-stat-icon {
    position: relative;
    z-index: 2;

    font-size: 38px;

    color: rgba(255,255,255,.90);
}


/* =========================================================
   REGISTER CARD
========================================================= */

.register-card {
    overflow: hidden;

    background: #fff;

    border: 1px solid #e5ebf3;
    border-radius: 17px;

    box-shadow:
        0 6px 22px rgba(15,23,42,.06);
}

.register-header {
    min-height: 74px;

    padding: 17px 23px;

    border-bottom: 1px solid #edf1f6;

    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;
}

.register-heading {
    display: flex;

    align-items: center;

    gap: 10px;
}

.register-heading i {
    color: #1769d1;

    font-size: 20px;
}

.register-heading h3 {
    margin: 0;

    color: #172033;

    font-size: 17px;
    font-weight: 750;
}

.register-subtitle {
    margin: 5px 0 0 30px;

    color: #718096;

    font-size: 12px;
}

.register-date {
    padding: 9px 14px;

    border-radius: 9px;

    background: #f8fafc;
    border: 1px solid #e3eaf2;

    color: #64748b;

    font-size: 12px;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   SUNDAY REGISTER DATE
========================================================= */

.register-date.sunday-date {
    background: #fff0f0;

    border-color: #f3c1c6;

    color: #c92d3d;
}


/* =========================================================
   TABLE
========================================================= */

.table-responsive {
    overflow-x: auto;
}

.attendance-table {
    width: 100%;

    min-width: 900px;

    margin: 0;
}

.attendance-table thead th {
    padding: 14px 17px;

    background: #f8fafc;

    border-bottom: 1px solid #edf1f6;

    color: #64748b;

    font-size: 11px;
    font-weight: 750;

    text-transform: uppercase;

    letter-spacing: .35px;

    white-space: nowrap;
}

.attendance-table tbody td {
    padding: 14px 17px;

    border-bottom: 1px solid #edf1f6;

    color: #334155;

    font-size: 13px;

    vertical-align: middle;
}

.attendance-table tbody tr {
    transition: background .2s ease;
}

.attendance-table tbody tr:hover {
    background: #f8fbff;
}

.attendance-table tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   STUDENT INFORMATION
========================================================= */

.student-info {
    display: flex;

    align-items: center;

    gap: 11px;
}

.student-avatar {
    width: 42px;
    height: 42px;

    min-width: 42px;

    border-radius: 11px;

    background: linear-gradient(
        135deg,
        #1769d1,
        #159cc7
    );

    color: #fff;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 13px;
    font-weight: 800;

    box-shadow:
        0 4px 10px rgba(23,105,209,.15);
}

.student-name {
    display: block;

    margin-bottom: 3px;

    color: #172033;

    font-size: 13px;
    font-weight: 700;
}

.student-small {
    color: #718096;

    font-size: 11px;
}

.class-badge {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 6px 10px;

    border-radius: 8px;

    background: #dbeafe;

    color: #1769d1;

    font-size: 11px;
    font-weight: 700;
}

.section-badge {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 6px 10px;

    border-radius: 8px;

    background: #ede9fe;

    color: #7c3aed;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   PRESENT / ABSENT
========================================================= */

.status-group {
    display: flex;

    align-items: center;

    gap: 8px;
}

.status-option {
    position: relative;
}

.status-option input {
    position: absolute;

    opacity: 0;

    pointer-events: none;
}

.status-label {
    min-width: 110px;

    padding: 9px 13px;

    border: 1px solid #e3eaf2;

    border-radius: 9px;

    background: #fff;

    color: #64748b;

    cursor: pointer;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 6px;

    font-size: 11px;
    font-weight: 750;

    transition: all .2s ease;
}

.status-label:hover {
    transform: translateY(-1px);

    background: #f8fafc;

    border-color: #cfd9e6;
}

.status-option.present input:checked + .status-label {
    background: #16a34a;

    border-color: #16a34a;

    color: #fff;

    box-shadow:
        0 4px 10px rgba(22,163,74,.18);
}

.status-option.absent input:checked + .status-label {
    background: #e94d47;

    border-color: #e94d47;

    color: #fff;

    box-shadow:
        0 4px 10px rgba(233,77,71,.18);
}


/* =========================================================
   SUNDAY DISABLED STATUS
========================================================= */

.sunday-row {
    background: #fffafa !important;
}

.sunday-row td {
    border-bottom-color: #f5d7da !important;
}

.sunday-status {
    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px 14px;

    border-radius: 10px;

    background: #fff0f0;

    border: 1px solid #f3c1c6;

    color: #c92d3d;

    font-size: 12px;

    font-weight: 750;
}

.sunday-status i {
    font-size: 17px;
}


/* =========================================================
   FOOTER
========================================================= */

.register-footer {
    padding: 17px 23px;

    background: #f8fafc;

    border-top: 1px solid #edf1f6;

    display: flex;

    align-items: center;
    justify-content: flex-end;
}

.save-attendance-btn {
    padding: 11px 21px;

    border: none;

    border-radius: 9px;

    background: linear-gradient(
        135deg,
        #1769d1,
        #237de0
    );

    color: #fff;

    font-size: 13px;
    font-weight: 700;

    display: inline-flex;

    align-items: center;

    gap: 8px;

    box-shadow:
        0 5px 12px rgba(23,105,209,.18);

    transition: all .2s ease;
}

.save-attendance-btn:hover {
    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 8px 18px rgba(23,105,209,.23);
}

.save-attendance-btn:disabled {
    opacity: .7;

    cursor: not-allowed;

    transform: none;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 55px 25px;

    text-align: center;

    color: #94a3b8;
}

.empty-icon {
    width: 65px;
    height: 65px;

    margin: 0 auto 15px;

    border-radius: 16px;

    background: #f1f5f9;

    color: #94a3b8;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 28px;
}

.empty-state h5 {
    margin: 0 0 5px;

    color: #334155;

    font-size: 15px;
    font-weight: 700;
}

.empty-state p {
    margin: 0;

    font-size: 12px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .attendance-stats {
        grid-template-columns:
            repeat(3, 1fr);
    }

}

@media (max-width: 850px) {

    .attendance-container {
        padding: 18px;
    }

    .attendance-welcome-content {
        align-items: flex-start;
    }

    .attendance-welcome h2 {
        font-size: 25px;
    }

    .attendance-header-badge {
        display: none;
    }

}

@media (max-width: 650px) {

    .attendance-container {
        padding: 15px;
    }

    .attendance-stats {
        grid-template-columns: 1fr;
    }

    .attendance-welcome {
        padding: 25px;
    }

    .attendance-welcome-left {
        align-items: flex-start;
    }

    .attendance-main-icon {
        width: 52px;
        height: 52px;

        min-width: 52px;

        font-size: 25px;
    }

    .attendance-welcome h2 {
        font-size: 23px;
    }

    .attendance-welcome p {
        font-size: 12px;
    }

    .attendance-card-body {
        padding: 17px;
    }

    .register-header {
        align-items: flex-start;

        flex-direction: column;
    }

    .register-date {
        width: 100%;

        text-align: center;
    }

    .status-group {
        flex-direction: column;

        align-items: stretch;
    }

    .status-label {
        width: 100%;
    }

    .register-footer {
        padding: 15px;
    }

    .save-attendance-btn {
        width: 100%;

        justify-content: center;
    }

    .sunday-holiday-alert.show {
        align-items: flex-start;

        flex-direction: column;
    }

}


/* =========================================================
   PRINT
========================================================= */

@media print {

    .sidebar,
    .navbar,
    .attendance-filter-card,
    .register-footer {
        display: none !important;
    }

    .attendance-container {
        padding: 0;

        background: #fff;
    }

    .attendance-welcome,
    .register-card,
    .attendance-stat-card {
        box-shadow: none;
    }

}

</style>


<div class="attendance-container">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="attendance-welcome">

        <div class="attendance-welcome-content">

            <div class="attendance-welcome-left">

                <div class="attendance-main-icon">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>

                <div>

                    <h2>
                        Student Attendance
                    </h2>

                    <p>
                        Record and manage daily student attendance
                        accurately and efficiently.
                    </p>

                </div>

            </div>

            <div class="attendance-header-badge">

                <i class="bi bi-calendar2-check-fill me-1"></i>

                Daily Attendance

            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTER CARD
    ====================================================== --}}

    <div class="attendance-filter-card">

        <div class="attendance-card-header">

            <div>

                <div class="attendance-card-title">

                    <i class="bi bi-funnel-fill"></i>

                    <h3>
                        Attendance Filters
                    </h3>

                </div>

                <div class="attendance-card-subtitle">

                    Select academic year, class, section and
                    attendance date.

                </div>

            </div>

        </div>


        <div class="attendance-card-body">

            <div class="row g-3 align-items-end">

                {{-- Academic Year --}}
                <div class="col-lg-3 col-md-6">

                    <label class="filter-label">
                        Academic Year
                    </label>

                    <select
                        id="academicYear"
                        class="form-select filter-control"
                    >

                        <option value="">
                            Select Academic Year
                        </option>

                        @foreach($academicYears ?? [] as $year)

                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Class --}}
                <div class="col-lg-3 col-md-6">

                    <label class="filter-label">
                        Class
                    </label>

                    <select
                        id="classSelect"
                        class="form-select filter-control"
                    >

                        <option value="">
                            Select Class
                        </option>

                        @foreach($classes ?? [] as $class)

                            <option value="{{ $class }}">
                                {{ $class }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Section --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Section
                    </label>

                    <select
                        id="sectionSelect"
                        class="form-select filter-control"
                        disabled
                    >

                        <option value="">
                            Select Class First
                        </option>

                    </select>

                </div>


                {{-- Date --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Attendance Date
                    </label>

                    <input
                        type="date"
                        id="attendanceDate"
                        class="form-control filter-control"
                        value="{{ now()->toDateString() }}"
                    >

                </div>


                {{-- View Button --}}
                <div class="col-lg-2 col-md-12">

                    <button
                        type="button"
                        id="loadStudentsBtn"
                        class="view-attendance-btn w-100"
                    >

                        <i class="bi bi-search"></i>

                        View Attendance

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SUNDAY HOLIDAY ALERT
    ====================================================== --}}

    <div
        class="sunday-holiday-alert"
        id="sundayHolidayAlert"
    >

        <div class="sunday-alert-left">

            <div class="sunday-alert-icon">

                <i class="bi bi-calendar-x-fill"></i>

            </div>

            <div>

                <div class="sunday-alert-title">
                    Sunday Holiday
                </div>

                <p
                    class="sunday-alert-text"
                    id="sundayAlertText"
                >
                    Attendance cannot be recorded on Sunday.
                </p>

            </div>

        </div>

        <div class="sunday-alert-badge">
            HOLIDAY
        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="attendance-stats">

        {{-- Total --}}
        <div class="attendance-stat-card blue">

            <div class="attendance-stat-top">

                <div>

                    <div
                        class="attendance-stat-number"
                        id="totalStudents"
                    >
                        0
                    </div>

                    <div class="attendance-stat-title">
                        Total Students
                    </div>

                </div>

                <div class="attendance-stat-icon">

                    <i class="bi bi-people-fill"></i>

                </div>

            </div>

        </div>


        {{-- Present --}}
        <div class="attendance-stat-card green">

            <div class="attendance-stat-top">

                <div>

                    <div
                        class="attendance-stat-number"
                        id="presentStudents"
                    >
                        0
                    </div>

                    <div class="attendance-stat-title">
                        Present Students
                    </div>

                </div>

                <div class="attendance-stat-icon">

                    <i class="bi bi-person-check-fill"></i>

                </div>

            </div>

        </div>


        {{-- Absent --}}
        <div class="attendance-stat-card red">

            <div class="attendance-stat-top">

                <div>

                    <div
                        class="attendance-stat-number"
                        id="absentStudents"
                    >
                        0
                    </div>

                    <div class="attendance-stat-title">
                        Absent Students
                    </div>

                </div>

                <div class="attendance-stat-icon">

                    <i class="bi bi-person-x-fill"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ATTENDANCE REGISTER
    ====================================================== --}}

    <div class="register-card">

        <div class="register-header">

            <div>

                <div class="register-heading">

                    <i class="bi bi-clipboard2-check-fill"></i>

                    <h3>
                        Student Attendance Register
                    </h3>

                </div>

                <div
                    class="register-subtitle"
                    id="attendanceSubtitle"
                >
                    Select the filters above to load students.
                </div>

            </div>


            <div
                class="register-date"
                id="displayDate"
            >
                {{ now()->format('d M Y') }}
            </div>

        </div>


        {{-- =================================================
             FORM
        ================================================== --}}

        <form
            method="POST"
            action="{{ route('admin.attendance.students.store') }}"
            id="attendanceForm"
        >

            @csrf


            <input
                type="hidden"
                name="attendance_date"
                id="formAttendanceDate"
                value="{{ now()->toDateString() }}"
            >

            <input
                type="hidden"
                name="academic_year"
                id="formAcademicYear"
            >

            <input
                type="hidden"
                name="class"
                id="formClass"
            >

            <input
                type="hidden"
                name="section"
                id="formSection"
            >


            <div class="table-responsive">

                <table class="table attendance-table">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Class
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Attendance Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="studentsTableBody">

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <i class="bi bi-person-lines-fill"></i>

                                    </div>

                                    <h5>
                                        No Students Loaded
                                    </h5>

                                    <p>
                                        Select academic year, class and
                                        section to view students.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div
                class="register-footer"
                id="attendanceFooter"
                style="display:none;"
            >

                <button
                    type="submit"
                    id="saveAttendanceBtn"
                    class="save-attendance-btn"
                >

                    <i class="bi bi-cloud-check-fill"></i>

                    Save Attendance

                </button>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const academicYear =
        document.getElementById('academicYear');

    const classSelect =
        document.getElementById('classSelect');

    const sectionSelect =
        document.getElementById('sectionSelect');

    const attendanceDate =
        document.getElementById('attendanceDate');

    const loadStudentsBtn =
        document.getElementById('loadStudentsBtn');

    const studentsTableBody =
        document.getElementById('studentsTableBody');

    const attendanceFooter =
        document.getElementById('attendanceFooter');

    const attendanceSubtitle =
        document.getElementById('attendanceSubtitle');

    const displayDate =
        document.getElementById('displayDate');

    const formAttendanceDate =
        document.getElementById('formAttendanceDate');

    const formAcademicYear =
        document.getElementById('formAcademicYear');

    const formClass =
        document.getElementById('formClass');

    const formSection =
        document.getElementById('formSection');

    const totalStudents =
        document.getElementById('totalStudents');

    const presentStudents =
        document.getElementById('presentStudents');

    const absentStudents =
        document.getElementById('absentStudents');

    const sundayHolidayAlert =
        document.getElementById('sundayHolidayAlert');

    const sundayAlertText =
        document.getElementById('sundayAlertText');


    /* =========================================================
       ROUTES
    ========================================================= */

    const sectionsUrl =
        @json(route('admin.attendance.sections'));

    const studentsUrl =
        @json(route('admin.attendance.students'));


    /* =========================================================
       SUNDAY CHECK
    ========================================================= */

    function isSunday(dateString) {

        if (!dateString) {
            return false;
        }

        const date =
            new Date(dateString + 'T00:00:00');

        return date.getDay() === 0;
    }


    /* =========================================================
       FORMAT DATE
    ========================================================= */

    function formatDate(dateString) {

        const date =
            new Date(dateString + 'T00:00:00');

        if (isNaN(date.getTime())) {
            return dateString;
        }

        return date.toLocaleDateString(
            'en-GB',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }
        );
    }


    /* =========================================================
       UPDATE SUNDAY UI
    ========================================================= */

    function updateSundayUI(dateString) {

        const sunday =
            isSunday(dateString);

        if (sunday) {

            sundayHolidayAlert.classList.add('show');

            sundayAlertText.textContent =
                formatDate(dateString) +
                ' is Sunday. Attendance is automatically marked as a holiday.';

            displayDate.classList.add(
                'sunday-date'
            );

            displayDate.innerHTML =
                '<i class="bi bi-calendar-x-fill me-1"></i>' +
                formatDate(dateString) +
                ' — Holiday';

        } else {

            sundayHolidayAlert.classList.remove(
                'show'
            );

            displayDate.classList.remove(
                'sunday-date'
            );

            displayDate.textContent =
                formatDate(dateString);
        }

    }


    /* =========================================================
       LOAD SECTIONS
    ========================================================= */

    classSelect.addEventListener(
        'change',
        loadSections
    );


    academicYear.addEventListener(
        'change',
        function () {

            sectionSelect.innerHTML =
                '<option value="">Select Class First</option>';

            sectionSelect.disabled = true;

            if (
                academicYear.value &&
                classSelect.value
            ) {

                loadSections();

            }

        }
    );


    function loadSections() {

        const year =
            academicYear.value;

        const selectedClass =
            classSelect.value;


        sectionSelect.disabled = true;

        sectionSelect.innerHTML =
            '<option value="">Loading sections...</option>';


        if (!year || !selectedClass) {

            sectionSelect.innerHTML =
                '<option value="">Select Class First</option>';

            return;
        }


        fetch(
            sectionsUrl +
            '?academic_year=' +
            encodeURIComponent(year) +
            '&class=' +
            encodeURIComponent(selectedClass),
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        )
        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Unable to load sections.'
                );

            }

            return response.json();

        })
        .then(data => {

            sectionSelect.innerHTML =
                '<option value="">Select Section</option>';


            if (
                data.success &&
                Array.isArray(data.sections) &&
                data.sections.length
            ) {

                data.sections.forEach(
                    function (section) {

                        const option =
                            document.createElement(
                                'option'
                            );

                        option.value =
                            section;

                        option.textContent =
                            section;

                        sectionSelect.appendChild(
                            option
                        );

                    }
                );

                sectionSelect.disabled =
                    false;

            } else {

                sectionSelect.innerHTML =
                    '<option value="">No Sections Available</option>';

            }

        })
        .catch(error => {

            console.error(error);

            sectionSelect.innerHTML =
                '<option value="">Unable to Load Sections</option>';

        });

    }


    /* =========================================================
       DATE CHANGE
    ========================================================= */

    attendanceDate.addEventListener(
        'change',
        function () {

            updateDate(this.value);

        }
    );


    function updateDate(date) {

        formAttendanceDate.value =
            date;

        updateSundayUI(date);

    }


    /* =========================================================
       LOAD STUDENTS
    ========================================================= */

    loadStudentsBtn.addEventListener(
        'click',
        loadStudents
    );


    function loadStudents() {

        const year =
            academicYear.value;

        const selectedClass =
            classSelect.value;

        const section =
            sectionSelect.value;

        const date =
            attendanceDate.value;


        if (!year) {

            alert(
                'Please select academic year.'
            );

            return;
        }


        if (!selectedClass) {

            alert(
                'Please select class.'
            );

            return;
        }


        if (!section) {

            alert(
                'Please select section.'
            );

            return;
        }


        if (!date) {

            alert(
                'Please select attendance date.'
            );

            return;
        }


        loadStudentsBtn.disabled =
            true;

        loadStudentsBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm"></span> Loading...';


        studentsTableBody.innerHTML = `

            <tr>

                <td colspan="6">

                    <div class="empty-state">

                        <div class="spinner-border text-primary mb-3"></div>

                        <h5>
                            Loading Students
                        </h5>

                        <p>
                            Please wait while students are being loaded.
                        </p>

                    </div>

                </td>

            </tr>

        `;


        fetch(
            studentsUrl +
            '?academic_year=' +
            encodeURIComponent(year) +
            '&class=' +
            encodeURIComponent(selectedClass) +
            '&section=' +
            encodeURIComponent(section) +
            '&attendance_date=' +
            encodeURIComponent(date),
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        )
        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Unable to load students.'
                );

            }

            return response.json();

        })
        .then(data => {

            const students =
                data.students || [];

            const sunday =
                isSunday(date);


            renderStudents(
                students,
                sunday
            );


            formAttendanceDate.value =
                date;

            formAcademicYear.value =
                year;

            formClass.value =
                selectedClass;

            formSection.value =
                section;


            updateDate(date);


            attendanceSubtitle.textContent =
                selectedClass +
                ' - Section ' +
                section +
                ' | ' +
                year +
                (sunday
                    ? ' | Sunday Holiday'
                    : '');

        })
        .catch(error => {

            console.error(error);

            studentsTableBody.innerHTML = `

                <tr>

                    <td colspan="6">

                        <div class="empty-state">

                            <div class="empty-icon">

                                <i class="bi bi-exclamation-triangle-fill"></i>

                            </div>

                            <h5>
                                Unable to Load Students
                            </h5>

                            <p>
                                Please check your filters and try again.
                            </p>

                        </div>

                    </td>

                </tr>

            `;

            attendanceFooter.style.display =
                'none';

            updateSummary([]);

        })
        .finally(() => {

            loadStudentsBtn.disabled =
                false;

            loadStudentsBtn.innerHTML =
                '<i class="bi bi-search"></i> View Attendance';

        });

    }


    /* =========================================================
       RENDER STUDENTS
    ========================================================= */

    function renderStudents(
        students,
        sunday = false
    ) {

        studentsTableBody.innerHTML =
            '';


        if (!students.length) {

            studentsTableBody.innerHTML = `

                <tr>

                    <td colspan="6">

                        <div class="empty-state">

                            <div class="empty-icon">

                                <i class="bi bi-people-fill"></i>

                            </div>

                            <h5>
                                No Students Found
                            </h5>

                            <p>
                                No active students are available
                                for this class and section.
                            </p>

                        </div>

                    </td>

                </tr>

            `;

            attendanceFooter.style.display =
                'none';

            updateSummary([]);

            return;
        }


        students.forEach(
            function (student, index) {

                const nameParts =
                    (student.name || 'Student')
                        .trim()
                        .split(' ');


                const initials =
                    (
                        (nameParts[0]?.charAt(0) || '') +
                        (
                            nameParts.length > 1
                                ? nameParts[
                                    nameParts.length - 1
                                ].charAt(0)
                                : ''
                        )
                    )
                    .toUpperCase() || 'ST';


                const currentStatus =
                    student.attendance_status
                        ? student.attendance_status.toLowerCase()
                        : '';


                const row =
                    document.createElement('tr');


                if (sunday) {

                    row.classList.add(
                        'sunday-row'
                    );

                }


                let attendanceHtml = '';


                if (sunday) {

                    /*
                     * IMPORTANT:
                     * No radio buttons are created on Sunday.
                     * Therefore nothing can be submitted.
                     */

                    attendanceHtml = `

                        <div class="sunday-status">

                            <i class="bi bi-calendar-x-fill"></i>

                            <span>
                                Sunday Holiday
                            </span>

                        </div>

                    `;

                } else {

                    attendanceHtml = `

                        <div class="status-group">

                            <div class="status-option present">

                                <input
                                    type="radio"
                                    id="present_${student.id}"
                                    name="attendance[${student.id}][status]"
                                    value="present"
                                    ${currentStatus === 'present' ? 'checked' : ''}
                                >

                                <label
                                    for="present_${student.id}"
                                    class="status-label"
                                >

                                    <i class="bi bi-check-circle-fill"></i>

                                    Present

                                </label>

                            </div>


                            <div class="status-option absent">

                                <input
                                    type="radio"
                                    id="absent_${student.id}"
                                    name="attendance[${student.id}][status]"
                                    value="absent"
                                    ${currentStatus === 'absent' ? 'checked' : ''}
                                >

                                <label
                                    for="absent_${student.id}"
                                    class="status-label"
                                >

                                    <i class="bi bi-x-circle-fill"></i>

                                    Absent

                                </label>

                            </div>

                        </div>

                    `;

                }


                row.innerHTML = `

                    <td>

                        <span class="student-small fw-bold">
                            ${index + 1}
                        </span>

                    </td>


                    <td>

                        <div class="student-info">

                            <div class="student-avatar">
                                ${initials}
                            </div>

                            <div>

                                <span class="student-name">
                                    ${escapeHtml(
                                        student.name ||
                                        'Student'
                                    )}
                                </span>

                            </div>

                        </div>

                    </td>


                    <td>

                        <span class="student-small">

                            ${escapeHtml(
                                student.student_id ||
                                '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <span class="class-badge">

                            <i class="bi bi-mortarboard-fill"></i>

                            ${escapeHtml(
                                student.class ||
                                classSelect.value ||
                                '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <span class="section-badge">

                            <i class="bi bi-grid-3x3-gap-fill"></i>

                            ${escapeHtml(
                                student.section ||
                                sectionSelect.value ||
                                '-'
                            )}

                        </span>

                    </td>


                    <td>

                        ${attendanceHtml}

                    </td>

                `;


                /*
                 * Student ID is submitted only on working days.
                 */

                if (!sunday) {

                    const hiddenStudentId =
                        document.createElement(
                            'input'
                        );

                    hiddenStudentId.type =
                        'hidden';

                    hiddenStudentId.name =
                        `attendance[${student.id}][student_id]`;

                    hiddenStudentId.value =
                        student.id;

                    row.appendChild(
                        hiddenStudentId
                    );

                }


                studentsTableBody.appendChild(
                    row
                );

            }
        );


        if (sunday) {

            /*
             * Sunday = Holiday.
             * Hide save button completely.
             */

            attendanceFooter.style.display =
                'none';

            updateSummary([]);

        } else {

            attendanceFooter.style.display =
                'flex';

            updateSummary(students);

            attachStatusListeners();

        }

    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    function updateSummary(students) {

        let present = 0;

        let absent = 0;


        students.forEach(
            function (student) {

                const status =
                    student.attendance_status
                        ? student.attendance_status.toLowerCase()
                        : '';


                if (status === 'present') {
                    present++;
                }


                if (status === 'absent') {
                    absent++;
                }

            }
        );


        totalStudents.textContent =
            students.length;

        presentStudents.textContent =
            present;

        absentStudents.textContent =
            absent;

    }


    /* =========================================================
       LIVE SUMMARY
    ========================================================= */

    function attachStatusListeners() {

        const radios =
            document.querySelectorAll(
                '#studentsTableBody input[type="radio"]'
            );


        radios.forEach(
            function (radio) {

                radio.addEventListener(
                    'change',
                    updateLiveSummary
                );

            }
        );

    }


    function updateLiveSummary() {

        let present = 0;

        let absent = 0;


        document
            .querySelectorAll(
                '#studentsTableBody input[type="radio"]:checked'
            )
            .forEach(
                function (checked) {

                    if (
                        checked.value === 'present'
                    ) {

                        present++;

                    }


                    if (
                        checked.value === 'absent'
                    ) {

                        absent++;

                    }

                }
            );


        const rows =
            document.querySelectorAll(
                '#studentsTableBody tr'
            );


        totalStudents.textContent =
            rows.length;

        presentStudents.textContent =
            present;

        absentStudents.textContent =
            absent;

    }


    /* =========================================================
       HTML ESCAPE
    ========================================================= */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;

    }


    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    document
        .getElementById('attendanceForm')
        .addEventListener(
            'submit',
            function (event) {

                const selectedDate =
                    formAttendanceDate.value;


                /*
                 * HARD PROTECTION:
                 * Never allow attendance to be submitted
                 * for Sunday.
                 */

                if (
                    isSunday(selectedDate)
                ) {

                    event.preventDefault();

                    alert(
                        'Sunday is a holiday. Attendance cannot be saved for Sunday.'
                    );

                    return;

                }


                const rows =
                    document.querySelectorAll(
                        '#studentsTableBody tr'
                    );


                const checked =
                    document.querySelectorAll(
                        '#studentsTableBody input[type="radio"]:checked'
                    );


                if (!rows.length) {

                    event.preventDefault();

                    alert(
                        'No students available to save.'
                    );

                    return;

                }


                if (
                    checked.length !==
                    rows.length
                ) {

                    event.preventDefault();

                    alert(
                        'Please select Present or Absent for every student.'
                    );

                    return;

                }


                const button =
                    document.getElementById(
                        'saveAttendanceBtn'
                    );


                if (button) {

                    button.disabled =
                        true;

                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm"></span> Saving...';

                }

            }
        );


    /* =========================================================
       INITIAL DATE CHECK
    ========================================================= */

    updateSundayUI(
        attendanceDate.value
    );

});

</script>

@endsection