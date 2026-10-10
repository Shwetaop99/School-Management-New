@extends('layouts.app')

@section('content')

<div class="attendance-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="page-header-left">

            <div class="page-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>
                <h3>
                    Student Attendance
                </h3>

                <p>
                    Manage class-wise daily student attendance
                </p>
            </div>

        </div>


        <div class="page-actions">

            <a href="{{ route('admin.attendance.monthly') }}"
               class="header-action monthly-action">

                <i class="bi bi-calendar3"></i>
                <span>Monthly Register</span>

            </a>


            <a href="{{ route('admin.attendance.report') }}"
               class="header-action report-action">

                <i class="bi bi-bar-chart-fill"></i>
                <span>Attendance Report</span>

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert-box alert-success-box">

            <div class="alert-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="flex-grow-1">

                <strong>
                    Attendance Saved
                </strong>

                <div>
                    {{ session('success') }}
                </div>

            </div>

            <button type="button"
                    class="alert-close"
                    data-bs-dismiss="alert">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert-box alert-danger-box">

            <div class="alert-icon">
                <i class="bi bi-exclamation-lg"></i>
            </div>

            <div class="flex-grow-1">

                <strong>
                    Please correct the following errors
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

            <button type="button"
                    class="alert-close"
                    data-bs-dismiss="alert">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

    @endif


    {{-- =========================================================
        FILTER / SELECTION CARD
    ========================================================== --}}

    <div class="selection-card">

        <div class="selection-header">

            <div class="selection-title">

                <div class="selection-icon">

                    <i class="bi bi-sliders2"></i>

                </div>

                <div>

                    <h5>
                        Attendance Selection
                    </h5>

                    <p>
                        Select academic year, class, section and date
                    </p>

                </div>

            </div>

            <div class="step-badge">
                <i class="bi bi-1-circle-fill"></i>
                Selection
            </div>

        </div>


        <div class="selection-body">

            <div class="row g-4">

                {{-- =================================================
                    ACADEMIC YEAR
                ================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <label for="academic_year"
                           class="modern-label">

                        <span>
                            Academic Year
                        </span>

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="bi bi-calendar-range"></i>

                        <select id="academic_year"
                                class="modern-select">

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    {{ $year == date('Y') . '-' . (date('Y') + 1) ? 'selected' : '' }}>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- =================================================
                    CLASS
                ================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <label for="class"
                           class="modern-label">

                        <span>
                            Class
                        </span>

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="bi bi-mortarboard-fill"></i>

                        <select id="class"
                                class="modern-select">

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

                </div>


                {{-- =================================================
                    SECTION
                ================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <label for="section"
                           class="modern-label">

                        <span>
                            Section
                        </span>

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="bi bi-grid-3x3-gap-fill"></i>

                        <select id="section"
                                class="modern-select">

                            <option value="">
                                Select Section
                            </option>

                            <option value="A">
                                Section A
                            </option>

                            <option value="B">
                                Section B
                            </option>

                            <option value="C">
                                Section C
                            </option>

                            <option value="D">
                                Section D
                            </option>

                            <option value="E">
                                Section E
                            </option>

                            <option value="F">
                                Section F
                            </option>

                        </select>

                    </div>

                </div>


                {{-- =================================================
                    DATE
                ================================================== --}}

                <div class="col-xl-3 col-md-6">

                    <label for="attendance_date"
                           class="modern-label">

                        <span>
                            Attendance Date
                        </span>

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="bi bi-calendar-event-fill"></i>

                        <input type="date"
                               id="attendance_date"
                               class="modern-input"
                               value="{{ date('Y-m-d') }}">

                    </div>

                </div>


                {{-- =================================================
                    LOAD BUTTON
                ================================================== --}}

                <div class="col-12">

                    <div class="load-area">

                        <div class="selection-hint">

                            <i class="bi bi-info-circle-fill"></i>

                            <span>
                                Select all required fields before loading students.
                            </span>

                        </div>


                        <button type="button"
                                id="loadStudentsBtn"
                                class="load-btn">

                            <span class="load-btn-icon">

                                <i class="bi bi-people-fill"></i>

                            </span>

                            <span>
                                Load Students
                            </span>

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LOADING
    ========================================================== --}}

    <div id="loadingBox"
         class="loading-card d-none">

        <div class="loading-animation">

            <div class="spinner-border text-primary"
                 role="status">

            </div>

        </div>

        <h6>
            Loading Students
        </h6>

        <p>
            Fetching students and existing attendance records...
        </p>

    </div>


    {{-- =========================================================
        ERROR
    ========================================================== --}}

    <div id="errorBox"
         class="alert-box alert-danger-box d-none">

        <div class="alert-icon">

            <i class="bi bi-exclamation-triangle-fill"></i>

        </div>

        <div id="errorMessage"
             class="flex-grow-1">
        </div>

        <button type="button"
                class="alert-close"
                onclick="document.getElementById('errorBox').classList.add('d-none')">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    {{-- =========================================================
        ATTENDANCE SECTION
    ========================================================== --}}

    <div id="attendanceSection"
         class="d-none">


        {{-- =====================================================
            CLASS SUMMARY
        ====================================================== --}}

        <div class="class-summary-card">

            <div class="summary-main">

                <div class="class-summary-icon">

                    <i class="bi bi-mortarboard-fill"></i>

                </div>


                <div>

                    <div class="summary-eyebrow">

                        <span class="live-dot"></span>

                        Attendance Session

                    </div>

                    <h4 id="classTitle">
                        Class Attendance
                    </h4>

                    <div class="summary-meta">

                        <span>

                            <i class="bi bi-calendar3"></i>

                            <span id="selectedYearText"></span>

                        </span>

                        <span class="meta-divider">
                            •
                        </span>

                        <span>

                            <i class="bi bi-grid-3x3-gap-fill"></i>

                            Section
                            <strong id="selectedSectionText"></strong>

                        </span>

                        <span class="meta-divider">
                            •
                        </span>

                        <span>

                            <i class="bi bi-calendar-event"></i>

                            <span id="selectedDateText"></span>

                        </span>

                    </div>

                </div>

            </div>


            {{-- Statistics --}}

            <div class="session-stats">

                <div class="session-stat total-stat">

                    <div class="session-stat-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>

                    <div>

                        <small>
                            Total
                        </small>

                        <strong id="totalCount">
                            0
                        </strong>

                    </div>

                </div>


                <div class="session-stat present-stat">

                    <div class="session-stat-icon">

                        <i class="bi bi-check-lg"></i>

                    </div>

                    <div>

                        <small>
                            Present
                        </small>

                        <strong id="presentCount">
                            0
                        </strong>

                    </div>

                </div>


                <div class="session-stat absent-stat">

                    <div class="session-stat-icon">

                        <i class="bi bi-x-lg"></i>

                    </div>

                    <div>

                        <small>
                            Absent
                        </small>

                        <strong id="absentCount">
                            0
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            STUDENT LIST HEADER
        ====================================================== --}}

        <div class="student-list-header">

            <div>

                <div class="list-title-row">

                    <div class="list-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>

                    <div>

                        <h5>
                            Student List
                        </h5>

                        <p>
                            Mark attendance for each student
                        </p>

                    </div>

                </div>

            </div>


            <div class="quick-actions">

                <button type="button"
                        id="markAllPresent"
                        class="quick-btn quick-present">

                    <i class="bi bi-check-circle-fill"></i>

                    <span>
                        Mark All Present
                    </span>

                </button>


                <button type="button"
                        id="markAllAbsent"
                        class="quick-btn quick-absent">

                    <i class="bi bi-x-circle-fill"></i>

                    <span>
                        Mark All Absent
                    </span>

                </button>

            </div>

        </div>


        {{-- =====================================================
            ATTENDANCE FORM
        ====================================================== --}}

        <form method="POST"
              action="{{ route('admin.attendance.store') }}"
              id="attendanceForm">

            @csrf


            <input type="hidden"
                   name="academic_year"
                   id="formAcademicYear">

            <input type="hidden"
                   name="class"
                   id="formClass">

            <input type="hidden"
                   name="section"
                   id="formSection">

            <input type="hidden"
                   name="attendance_date"
                   id="formAttendanceDate">


            {{-- =================================================
                STUDENT TABLE
            ================================================== --}}

            <div class="student-table-card">

                <div class="table-responsive">

                    <table class="student-table">

                        <thead>

                            <tr>

                                <th class="serial-column">
                                    #
                                </th>

                                <th class="roll-column">
                                    Roll No.
                                </th>

                                <th class="id-column">
                                    Student ID
                                </th>

                                <th>
                                    Student
                                </th>

                                <th class="attendance-column">
                                    Attendance Status
                                </th>

                            </tr>

                        </thead>


                        <tbody id="studentTableBody">
                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    EMPTY
                ================================================== --}}

                <div id="emptyStudents"
                     class="empty-students d-none">

                    <div class="empty-student-icon">

                        <i class="bi bi-people"></i>

                    </div>

                    <h6>
                        No Students Found
                    </h6>

                    <p>
                        No active students were found for the selected class and section.
                    </p>

                </div>


                {{-- =================================================
                    SAVE FOOTER
                ================================================== --}}

                <div class="save-footer">

                    <div class="save-information">

                        <div class="save-info-icon">

                            <i class="bi bi-shield-check"></i>

                        </div>

                        <div>

                            <strong>
                                Ready to Save
                            </strong>

                            <small>
                                Make sure every student has the correct attendance status.
                            </small>

                        </div>

                    </div>


                    <button type="submit"
                            id="saveAttendanceBtn"
                            class="save-btn">

                        <i class="bi bi-check2-circle"></i>

                        Save Attendance

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
     STYLES
============================================================== --}}

<style>

/* =========================================================
   PAGE
========================================================= */

.attendance-page {
    min-height: calc(100vh - 70px);
    background: #f5f7fb;
    padding: 28px;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.page-icon {
    width: 52px;
    height: 52px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #1677f0;
    font-size: 22px;
    box-shadow: 0 5px 15px rgba(22,119,240,.08);
}

.page-header h3 {
    margin: 0;
    color: #172b4d;
    font-size: 25px;
    font-weight: 750;
}

.page-header p {
    margin: 4px 0 0;
    color: #667085;
    font-size: 13px;
}

.page-actions {
    display: flex;
    gap: 10px;
}

.header-action {
    min-height: 42px;
    padding: 0 15px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 650;
    transition: .2s ease;
}

.monthly-action {
    color: #087443;
    background: #ecfdf3;
    border: 1px solid #abefc6;
}

.monthly-action:hover {
    background: #087443;
    color: #fff;
    transform: translateY(-1px);
}

.report-action {
    color: #175cd3;
    background: #eff8ff;
    border: 1px solid #b2ddff;
}

.report-action:hover {
    background: #175cd3;
    color: #fff;
    transform: translateY(-1px);
}


/* =========================================================
   ALERTS
========================================================= */

.alert-box {
    border-radius: 13px;
    padding: 13px 15px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    border: 1px solid;
}

.alert-success-box {
    background: #ecfdf3;
    border-color: #abefc6;
    color: #067647;
}

.alert-danger-box {
    background: #fef3f2;
    border-color: #fecdca;
    color: #b42318;
}

.alert-icon {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.7);
}

.alert-box ul {
    padding-left: 18px;
}

.alert-close {
    border: 0;
    background: transparent;
    color: currentColor;
    opacity: .7;
    cursor: pointer;
    padding: 3px;
}


/* =========================================================
   SELECTION CARD
========================================================= */

.selection-card {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 19px;
    box-shadow: 0 5px 18px rgba(16,24,40,.045);
    overflow: hidden;
    margin-bottom: 24px;
}

.selection-header {
    padding: 19px 22px;
    border-bottom: 1px solid #eef1f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.selection-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.selection-icon {
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

.selection-title h5 {
    color: #172b4d;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
}

.selection-title p {
    color: #98a2b3;
    font-size: 11px;
    margin: 3px 0 0;
}

.step-badge {
    padding: 7px 11px;
    border-radius: 8px;
    background: #f2f4f7;
    color: #667085;
    font-size: 11px;
    font-weight: 650;
}

.selection-body {
    padding: 22px;
}


/* =========================================================
   FORM FIELDS
========================================================= */

.modern-label {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-bottom: 8px;
    color: #344054;
    font-size: 12px;
    font-weight: 650;
}

.required {
    color: #f04438;
}

.input-wrapper {
    position: relative;
}

.input-wrapper > i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #98a2b3;
    z-index: 2;
    font-size: 15px;
    pointer-events: none;
}

.modern-select,
.modern-input {
    width: 100%;
    min-height: 46px;
    border: 1px solid #d0d5dd;
    border-radius: 11px;
    background: #fff;
    color: #344054;
    padding-left: 40px;
    padding-right: 12px;
    font-size: 13px;
    transition: .2s ease;
}

.modern-select {
    cursor: pointer;
}

.modern-select:focus,
.modern-input:focus {
    outline: none;
    border-color: #1677f0;
    box-shadow: 0 0 0 4px rgba(22,119,240,.09);
}

.modern-select:hover,
.modern-input:hover {
    border-color: #98a2b3;
}


/* =========================================================
   LOAD AREA
========================================================= */

.load-area {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-top: 5px;
}

.selection-hint {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #98a2b3;
    font-size: 11px;
}

.selection-hint i {
    color: #1677f0;
}

.load-btn {
    min-height: 46px;
    border: 0;
    border-radius: 11px;
    background: #1677f0;
    color: #fff;
    padding: 0 17px;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(22,119,240,.2);
    transition: .2s ease;
}

.load-btn:hover {
    background: #0f62cf;
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(22,119,240,.25);
}

.load-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
}

.load-btn-icon {
    width: 25px;
    height: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.14);
    border-radius: 7px;
}


/* =========================================================
   LOADING
========================================================= */

.loading-card {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 18px;
    padding: 45px 20px;
    text-align: center;
    box-shadow: 0 5px 18px rgba(16,24,40,.04);
    margin-bottom: 24px;
}

.loading-animation {
    margin-bottom: 14px;
}

.loading-card h6 {
    color: #344054;
    font-weight: 700;
    margin-bottom: 5px;
}

.loading-card p {
    color: #98a2b3;
    font-size: 12px;
    margin: 0;
}


/* =========================================================
   CLASS SUMMARY
========================================================= */

.class-summary-card {
    background: linear-gradient(
        135deg,
        #1677f0 0%,
        #155eef 55%,
        #1047b8 100%
    );
    border-radius: 20px;
    color: #fff;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    box-shadow: 0 12px 28px rgba(16,71,184,.16);
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.class-summary-card::after {
    content: "";
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    border: 60px solid rgba(255,255,255,.04);
    right: -150px;
    top: -170px;
}

.summary-main {
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    z-index: 2;
}

.class-summary-icon {
    width: 57px;
    height: 57px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.13);
    border: 1px solid rgba(255,255,255,.12);
    font-size: 24px;
}

.summary-eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;
    color: rgba(255,255,255,.7);
    text-transform: uppercase;
    letter-spacing: .7px;
    font-size: 9px;
    font-weight: 700;
    margin-bottom: 3px;
}

.live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #6ce9a6;
}

.class-summary-card h4 {
    margin: 0 0 7px;
    font-size: 21px;
    font-weight: 750;
}

.summary-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    color: rgba(255,255,255,.75);
    font-size: 11px;
}

.summary-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.summary-meta strong {
    color: #fff;
}

.meta-divider {
    opacity: .5;
}


/* =========================================================
   SESSION STATS
========================================================= */

.session-stats {
    display: flex;
    gap: 9px;
    position: relative;
    z-index: 2;
}

.session-stat {
    min-width: 105px;
    padding: 10px 12px;
    border-radius: 12px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.11);
    display: flex;
    align-items: center;
    gap: 9px;
}

.session-stat-icon {
    width: 31px;
    height: 31px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.12);
    font-size: 13px;
}

.session-stat small {
    display: block;
    color: rgba(255,255,255,.65);
    font-size: 9px;
}

.session-stat strong {
    display: block;
    color: #fff;
    font-size: 19px;
    line-height: 1.1;
}


/* =========================================================
   STUDENT LIST HEADER
========================================================= */

.student-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 14px;
}

.list-title-row {
    display: flex;
    align-items: center;
    gap: 11px;
}

.list-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf4ff;
    color: #1677f0;
    font-size: 17px;
}

.list-title-row h5 {
    margin: 0;
    color: #172b4d;
    font-size: 16px;
    font-weight: 700;
}

.list-title-row p {
    margin: 3px 0 0;
    color: #98a2b3;
    font-size: 11px;
}


/* =========================================================
   QUICK ACTIONS
========================================================= */

.quick-actions {
    display: flex;
    gap: 8px;
}

.quick-btn {
    border-radius: 9px;
    padding: 8px 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s ease;
}

.quick-present {
    background: #ecfdf3;
    color: #087443;
    border: 1px solid #abefc6;
}

.quick-present:hover {
    background: #087443;
    color: #fff;
}

.quick-absent {
    background: #fef3f2;
    color: #b42318;
    border: 1px solid #fecdca;
}

.quick-absent:hover {
    background: #b42318;
    color: #fff;
}


/* =========================================================
   TABLE CARD
========================================================= */

.student-table-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e9edf3;
    box-shadow: 0 5px 18px rgba(16,24,40,.045);
    overflow: hidden;
}

.student-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.student-table thead th {
    background: #f8fafc;
    color: #667085;
    text-transform: uppercase;
    letter-spacing: .4px;
    font-size: 10px;
    font-weight: 750;
    padding: 14px 15px;
    border-bottom: 1px solid #eaecf0;
    white-space: nowrap;
}

.student-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #f0f2f5;
    vertical-align: middle;
}

.student-table tbody tr {
    transition: .15s ease;
}

.student-table tbody tr:hover {
    background: #fafcff;
}

.student-table tbody tr:last-child td {
    border-bottom: 0;
}

.serial-column {
    width: 55px;
    text-align: center;
}

.roll-column {
    width: 90px;
}

.id-column {
    width: 120px;
}

.attendance-column {
    width: 420px;
    text-align: center;
}


/* =========================================================
   STUDENT
========================================================= */

.student-avatar {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    object-fit: cover;
    border: 2px solid #eef2f6;
    flex-shrink: 0;
}

.student-avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: #eef2f6;
    color: #98a2b3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.student-name {
    color: #172b4d;
    font-size: 13px;
    font-weight: 700;
}

.student-id {
    color: #98a2b3;
    font-size: 10px;
    margin-top: 3px;
}


/* =========================================================
   ATTENDANCE BUTTONS
========================================================= */

.attendance-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    flex-wrap: wrap;
}

.attendance-btn {
    min-width: 76px;
    min-height: 34px;
    border: 1px solid #d0d5dd;
    background: #fff;
    color: #667085;
    border-radius: 8px;
    padding: 6px 9px;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.attendance-btn:hover {
    transform: translateY(-1px);
    border-color: #98a2b3;
    box-shadow: 0 3px 8px rgba(16,24,40,.07);
}

.attendance-btn.active-present {
    background: #12b76a;
    border-color: #12b76a;
    color: #fff;
    box-shadow: 0 4px 10px rgba(18,183,106,.18);
}

.attendance-btn.active-absent {
    background: #f04438;
    border-color: #f04438;
    color: #fff;
    box-shadow: 0 4px 10px rgba(240,68,56,.18);
}

.attendance-btn.active-leave {
    background: #f79009;
    border-color: #f79009;
    color: #fff;
    box-shadow: 0 4px 10px rgba(247,144,9,.18);
}

.attendance-btn.active-half-day {
    background: #039be5;
    border-color: #039be5;
    color: #fff;
    box-shadow: 0 4px 10px rgba(3,155,229,.18);
}

.attendance-btn.active-late {
    background: #6941c6;
    border-color: #6941c6;
    color: #fff;
    box-shadow: 0 4px 10px rgba(105,65,198,.18);
}


/* =========================================================
   EMPTY
========================================================= */

.empty-students {
    padding: 65px 20px;
    text-align: center;
}

.empty-student-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f2f4f7;
    color: #98a2b3;
    font-size: 30px;
}

.empty-students h6 {
    color: #344054;
    font-weight: 700;
    margin-bottom: 5px;
}

.empty-students p {
    color: #98a2b3;
    font-size: 12px;
    margin: 0;
}


/* =========================================================
   SAVE FOOTER
========================================================= */

.save-footer {
    padding: 15px 18px;
    border-top: 1px solid #eef1f5;
    background: #fbfcfe;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.save-information {
    display: flex;
    align-items: center;
    gap: 10px;
}

.save-info-icon {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ecfdf3;
    color: #12b76a;
}

.save-information strong {
    display: block;
    color: #344054;
    font-size: 11px;
}

.save-information small {
    display: block;
    color: #98a2b3;
    font-size: 10px;
    margin-top: 2px;
}

.save-btn {
    min-height: 43px;
    border: 0;
    border-radius: 10px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #1677f0;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 5px 13px rgba(22,119,240,.18);
    transition: .2s ease;
}

.save-btn:hover {
    background: #0f62cf;
    transform: translateY(-1px);
}

.save-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .attendance-page {
        padding: 20px;
    }

    .class-summary-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .session-stats {
        width: 100%;
    }

    .session-stat {
        flex: 1;
    }

}


@media (max-width: 767px) {

    .attendance-page {
        padding: 14px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .page-actions {
        width: 100%;
    }

    .header-action {
        flex: 1;
        justify-content: center;
    }

    .selection-header {
        align-items: flex-start;
        gap: 10px;
        flex-direction: column;
    }

    .selection-body {
        padding: 17px;
    }

    .load-area {
        align-items: stretch;
        flex-direction: column;
    }

    .load-btn {
        justify-content: center;
    }

    .class-summary-card {
        padding: 18px;
    }

    .summary-main {
        align-items: flex-start;
    }

    .summary-meta {
        gap: 5px;
    }

    .session-stats {
        flex-direction: column;
    }

    .session-stat {
        width: 100%;
    }

    .student-list-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .quick-actions {
        width: 100%;
    }

    .quick-btn {
        flex: 1;
        justify-content: center;
    }

    .save-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .save-btn {
        justify-content: center;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .page-icon {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }

    .page-header h3 {
        font-size: 20px;
    }

    .header-action span {
        display: none;
    }

    .header-action {
        flex: 0 0 auto;
        width: 43px;
        justify-content: center;
    }

    .selection-title p {
        display: none;
    }

    .class-summary-icon {
        width: 48px;
        height: 48px;
    }

    .class-summary-card h4 {
        font-size: 18px;
    }

    .student-table {
        min-width: 850px;
    }

}


/* =========================================================
   PRINT
========================================================= */

@media print {

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body {
        background: #fff !important;
    }

    .attendance-page {
        padding: 0 !important;
        background: #fff !important;
    }

    .page-actions,
    .selection-card,
    .quick-actions,
    .save-footer,
    #loadingBox,
    #errorBox {
        display: none !important;
    }

    .page-header {
        margin-bottom: 12px;
    }

    .page-header h3 {
        font-size: 20px;
    }

    .class-summary-card {
        box-shadow: none;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .student-list-header {
        margin-bottom: 8px;
    }

    .student-table-card {
        box-shadow: none;
        border: 1px solid #ddd;
        border-radius: 7px;
    }

    .student-table thead th {
        padding: 8px;
        font-size: 9px;
    }

    .student-table tbody td {
        padding: 7px;
        font-size: 9px;
    }

    .attendance-btn {
        min-width: auto;
        padding: 4px 7px;
        font-size: 8px;
        box-shadow: none !important;
    }

}

</style>


{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const academicYear =
        document.getElementById('academic_year');

    const classSelect =
        document.getElementById('class');

    const sectionSelect =
        document.getElementById('section');

    const attendanceDate =
        document.getElementById('attendance_date');

    const loadStudentsBtn =
        document.getElementById('loadStudentsBtn');

    const loadingBox =
        document.getElementById('loadingBox');

    const errorBox =
        document.getElementById('errorBox');

    const errorMessage =
        document.getElementById('errorMessage');

    const attendanceSection =
        document.getElementById('attendanceSection');

    const studentTableBody =
        document.getElementById('studentTableBody');

    const emptyStudents =
        document.getElementById('emptyStudents');

    const totalCount =
        document.getElementById('totalCount');

    const presentCount =
        document.getElementById('presentCount');

    const absentCount =
        document.getElementById('absentCount');

    const selectedYearText =
        document.getElementById('selectedYearText');

    const selectedSectionText =
        document.getElementById('selectedSectionText');

    const selectedDateText =
        document.getElementById('selectedDateText');

    const classTitle =
        document.getElementById('classTitle');

    const formAcademicYear =
        document.getElementById('formAcademicYear');

    const formClass =
        document.getElementById('formClass');

    const formSection =
        document.getElementById('formSection');

    const formAttendanceDate =
        document.getElementById('formAttendanceDate');

    const markAllPresent =
        document.getElementById('markAllPresent');

    const markAllAbsent =
        document.getElementById('markAllAbsent');


    /* =========================================================
       LOAD STUDENTS
    ========================================================== */

    loadStudentsBtn.addEventListener('click', function () {

        const year =
            academicYear.value;

        const selectedClass =
            classSelect.value;

        const section =
            sectionSelect.value;

        const date =
            attendanceDate.value;


        /* =====================================================
           VALIDATION
        ====================================================== */

        if (!year) {

            showError(
                'Please select an academic year.'
            );

            academicYear.focus();

            return;
        }


        if (!selectedClass) {

            showError(
                'Please select a class.'
            );

            classSelect.focus();

            return;
        }


        if (!section) {

            showError(
                'Please select a section.'
            );

            sectionSelect.focus();

            return;
        }


        if (!date) {

            showError(
                'Please select attendance date.'
            );

            attendanceDate.focus();

            return;
        }


        /* =====================================================
           FORM VALUES
        ====================================================== */

        formAcademicYear.value =
            year;

        formClass.value =
            selectedClass;

        formSection.value =
            section;

        formAttendanceDate.value =
            date;


        /* =====================================================
           UI
        ====================================================== */

        errorBox.classList.add(
            'd-none'
        );

        attendanceSection.classList.add(
            'd-none'
        );

        loadingBox.classList.remove(
            'd-none'
        );

        loadStudentsBtn.disabled =
            true;


        /* =====================================================
           API REQUEST
        ====================================================== */

        const url =
            "{{ route('admin.attendance.students') }}" +
            '?academic_year=' +
            encodeURIComponent(year) +
            '&class=' +
            encodeURIComponent(selectedClass) +
            '&section=' +
            encodeURIComponent(section) +
            '&attendance_date=' +
            encodeURIComponent(date);


        fetch(url, {

            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        })

        .then(async response => {

            const result =
                await response.json();

            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Unable to load students.'
                );

            }

            return result;

        })

        .then(result => {

            loadingBox.classList.add(
                'd-none'
            );

            loadStudentsBtn.disabled =
                false;


            if (!result.success) {

                showError(
                    result.message ||
                    'Unable to load students.'
                );

                return;
            }


            /* ===============================================
               UPDATE HEADER
            ================================================ */

            classTitle.textContent =
                'Class ' +
                selectedClass +
                ' Attendance';

            selectedYearText.textContent =
                year;

            selectedSectionText.textContent =
                section;

            selectedDateText.textContent =
                formatDate(date);


            /* ===============================================
               RENDER STUDENTS
            ================================================ */

            renderStudents(
                result.students || []
            );

        })

        .catch(error => {

            loadingBox.classList.add(
                'd-none'
            );

            loadStudentsBtn.disabled =
                false;

            console.error(
                'Attendance students error:',
                error
            );

            showError(
                error.message ||
                'Something went wrong while loading students.'
            );

        });

    });


    /* =========================================================
       RENDER STUDENTS
    ========================================================== */

    function renderStudents(students) {

        studentTableBody.innerHTML =
            '';

        attendanceSection.classList.remove(
            'd-none'
        );


        if (!students.length) {

            emptyStudents.classList.remove(
                'd-none'
            );

            document.querySelector(
                '#attendanceSection .table-responsive'
            ).classList.add(
                'd-none'
            );

            document.getElementById(
                'saveAttendanceBtn'
            ).disabled = true;

            totalCount.textContent =
                0;

            presentCount.textContent =
                0;

            absentCount.textContent =
                0;

            return;
        }


        emptyStudents.classList.add(
            'd-none'
        );

        document.querySelector(
            '#attendanceSection .table-responsive'
        ).classList.remove(
            'd-none'
        );

        document.getElementById(
            'saveAttendanceBtn'
        ).disabled = false;


        students.forEach(
            function (student, index) {

                const row =
                    document.createElement('tr');

                const status =
                    student.attendance_status ||
                    'present';


                /* ==========================================
                   PROFILE IMAGE
                =========================================== */

                let avatar = '';


                if (student.profile_image) {

                    avatar = `

                        <img
                            src="${escapeHtml(student.profile_image)}"
                            alt="Student"
                            class="student-avatar"
                        >

                    `;

                } else {

                    avatar = `

                        <div class="student-avatar-placeholder">

                            <i class="bi bi-person-fill"></i>

                        </div>

                    `;

                }


                /* ==========================================
                   ROW
                =========================================== */

                row.innerHTML = `

                    <td class="text-center">

                        <span class="fw-semibold text-muted">

                            ${index + 1}

                        </span>

                    </td>


                    <td>

                        <span class="fw-bold">

                            ${escapeHtml(
                                student.roll_number || '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <span class="student-id">

                            ${escapeHtml(
                                student.student_id || '-'
                            )}

                        </span>

                    </td>


                    <td>

                        <div class="d-flex align-items-center gap-3">

                            ${avatar}

                            <div>

                                <div class="student-name">

                                    ${escapeHtml(
                                        student.name || '-'
                                    )}

                                </div>

                                <div class="student-id">

                                    Class
                                    ${escapeHtml(
                                        student.class || ''
                                    )}

                                    -

                                    Section
                                    ${escapeHtml(
                                        student.section || ''
                                    )}

                                </div>

                            </div>

                        </div>

                    </td>


                    <td>

                        <input
                            type="hidden"
                            name="attendance[${index}][student_id]"
                            value="${student.id}"
                        >


                        <input
                            type="hidden"
                            name="attendance[${index}][status]"
                            class="status-input"
                            value="${status}"
                        >


                        <input
                            type="hidden"
                            name="attendance[${index}][remarks]"
                            value="${escapeHtml(
                                student.remarks || ''
                            )}"
                        >


                        <div class="attendance-buttons">


                            <button
                                type="button"
                                class="attendance-btn status-btn"
                                data-status="present"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Present

                            </button>


                            <button
                                type="button"
                                class="attendance-btn status-btn"
                                data-status="absent"
                            >

                                <i class="bi bi-x-circle me-1"></i>

                                Absent

                            </button>


                            <button
                                type="button"
                                class="attendance-btn status-btn"
                                data-status="leave"
                            >

                                <i class="bi bi-calendar-minus me-1"></i>

                                Leave

                            </button>


                            <button
                                type="button"
                                class="attendance-btn status-btn"
                                data-status="half_day"
                            >

                                <i class="bi bi-clock me-1"></i>

                                Half Day

                            </button>


                            <button
                                type="button"
                                class="attendance-btn status-btn"
                                data-status="late"
                            >

                                <i class="bi bi-alarm me-1"></i>

                                Late

                            </button>


                        </div>

                    </td>

                `;


                studentTableBody.appendChild(
                    row
                );


                /* ==========================================
                   BUTTON EVENTS
                =========================================== */

                const buttons =
                    row.querySelectorAll(
                        '.status-btn'
                    );

                const statusInput =
                    row.querySelector(
                        '.status-input'
                    );


                buttons.forEach(
                    function (button) {

                        button.addEventListener(
                            'click',
                            function () {

                                const selectedStatus =
                                    this.dataset.status;


                                statusInput.value =
                                    selectedStatus;


                                updateButtonState(
                                    buttons,
                                    selectedStatus
                                );


                                updateSummary();

                            }
                        );

                    }
                );


                /* ==========================================
                   INITIAL STATUS
                =========================================== */

                updateButtonState(
                    buttons,
                    status
                );

            }
        );


        updateSummary();

    }


    /* =========================================================
       UPDATE BUTTON STATE
    ========================================================== */

    function updateButtonState(
        buttons,
        status
    ) {

        buttons.forEach(
            function (button) {

                button.classList.remove(
                    'active-present',
                    'active-absent',
                    'active-leave',
                    'active-half-day',
                    'active-late'
                );


                if (
                    button.dataset.status ===
                    status
                ) {

                    button.classList.add(
                        'active-' +
                        status.replace(
                            '_',
                            '-'
                        )
                    );

                }

            }
        );

    }


    /* =========================================================
       MARK ALL PRESENT
    ========================================================== */

    markAllPresent.addEventListener(
        'click',
        function () {

            setAllAttendance(
                'present'
            );

        }
    );


    /* =========================================================
       MARK ALL ABSENT
    ========================================================== */

    markAllAbsent.addEventListener(
        'click',
        function () {

            setAllAttendance(
                'absent'
            );

        }
    );


    /* =========================================================
       SET ALL ATTENDANCE
    ========================================================== */

    function setAllAttendance(status) {

        const rows =
            studentTableBody.querySelectorAll(
                'tr'
            );


        rows.forEach(
            function (row) {

                const input =
                    row.querySelector(
                        '.status-input'
                    );

                const buttons =
                    row.querySelectorAll(
                        '.status-btn'
                    );


                if (!input) {
                    return;
                }


                input.value =
                    status;


                updateButtonState(
                    buttons,
                    status
                );

            }
        );


        updateSummary();

    }


    /* =========================================================
       UPDATE SUMMARY
    ========================================================== */

    function updateSummary() {

        const inputs =
            studentTableBody.querySelectorAll(
                '.status-input'
            );


        let present = 0;

        let absent = 0;


        inputs.forEach(
            function (input) {

                if (
                    input.value ===
                    'present'
                ) {

                    present++;

                }


                if (
                    input.value ===
                    'absent'
                ) {

                    absent++;

                }

            }
        );


        totalCount.textContent =
            inputs.length;

        presentCount.textContent =
            present;

        absentCount.textContent =
            absent;

    }


    /* =========================================================
       FORMAT DATE
    ========================================================== */

    function formatDate(dateString) {

        const date =
            new Date(
                dateString +
                'T00:00:00'
            );


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
       SHOW ERROR
    ========================================================== */

    function showError(message) {

        errorMessage.textContent =
            message;

        errorBox.classList.remove(
            'd-none'
        );

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================== */

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


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