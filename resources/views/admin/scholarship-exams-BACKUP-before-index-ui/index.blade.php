@extends('layouts.app')

@section('content')

<style>

/* =========================================================
   SCHOLARSHIP EXAM RECORDS
========================================================= */

.scholarship-exams-page {

    min-height: calc(100vh - 70px);

    padding: 26px;

    background:

        radial-gradient(circle at 5% 8%, rgba(23,105,209,.055), transparent 25%),

        radial-gradient(circle at 95% 15%, rgba(21,156,199,.05), transparent 25%),

        #f4f7fb;

}

/* =========================================================
   HEADER
========================================================= */

.scholarship-header {

    position: relative;

    overflow: hidden;

    border-radius: 22px;

    padding: 28px 30px;

    margin-bottom: 22px;

    background: linear-gradient(135deg, #125bc2 0%, #1769d1 45%, #159cc7 100%);

    color: #fff;

    box-shadow: 0 14px 35px rgba(23,105,209,.20);

}

.scholarship-header::before {

    content: "";

    position: absolute;

    width: 240px;

    height: 240px;

    right: -70px;

    top: -100px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);

}

.scholarship-header::after {

    content: "";

    position: absolute;

    width: 170px;

    height: 170px;

    right: 120px;

    bottom: -115px;

    border-radius: 50%;

    background: rgba(255,255,255,.07);

}

.scholarship-header-content {

    position: relative;

    z-index: 2;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

}

.scholarship-title-area {

    display: flex;

    align-items: center;

    gap: 17px;

}

.scholarship-icon {

    width: 62px;

    height: 62px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 18px;

    background: rgba(255,255,255,.17);

    border: 1px solid rgba(255,255,255,.25);

    box-shadow: inset 0 1px 0 rgba(255,255,255,.20);

    font-size: 27px;

}

.scholarship-title-area h1 {

    margin: 0;

    font-size: 25px;

    font-weight: 800;

    letter-spacing: -.3px;

}

.scholarship-title-area p {

    margin: 5px 0 0;

    font-size: 13px;

    color: rgba(255,255,255,.82);

}

.scholarship-header-actions {

    position: relative;

    z-index: 3;

    display: flex;

    align-items: center;

    gap: 10px;

}

.btn-scholarship-add {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 17px;

    border-radius: 11px;

    border: 0;

    background: #fff;

    color: #1769d1;

    font-size: 13px;

    font-weight: 800;

    text-decoration: none;

    box-shadow: 0 8px 20px rgba(0,0,0,.12);

    transition: .2s ease;

}

.btn-scholarship-add:hover {

    color: #1769d1;

    transform: translateY(-2px);

    box-shadow: 0 11px 25px rgba(0,0,0,.17);

}

/* =========================================================
   KPI CARDS
========================================================= */

.scholarship-kpis {

    display: grid;

    grid-template-columns: repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-bottom: 22px;

}

.scholarship-kpi {

    position: relative;

    overflow: hidden;

    min-height: 118px;

    padding: 19px;

    border-radius: 17px;

    background: #fff;

    border: 1px solid #e5ebf3;

    box-shadow: 0 7px 22px rgba(25,55,90,.055);

}

.scholarship-kpi::after {

    content: "";

    position: absolute;

    width: 72px;

    height: 72px;

    right: -20px;

    bottom: -28px;

    border-radius: 50%;

    background: rgba(23,105,209,.045);

}

.kpi-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

}

.kpi-label {

    font-size: 11px;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .55px;

    color: #7b8797;

}

.kpi-icon {

    width: 37px;

    height: 37px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    font-size: 16px;

}

.kpi-blue .kpi-icon {

    background: rgba(23,105,209,.10);

    color: #1769d1;

}

.kpi-orange .kpi-icon {

    background: rgba(237,146,8,.12);

    color: #ed9208;

}

.kpi-cyan .kpi-icon {

    background: rgba(7,157,189,.11);

    color: #079dbd;

}

.kpi-green .kpi-icon {

    background: rgba(32,166,103,.11);

    color: #20a667;

}

.kpi-red .kpi-icon {

    background: rgba(233,77,71,.11);

    color: #e94d47;

}

.kpi-value {

    margin-top: 12px;

    font-size: 25px;

    line-height: 1;

    font-weight: 850;

    color: #172236;

}

/* =========================================================
   FILTER PANEL
========================================================= */

.scholarship-filter-card {

    background: #fff;

    border: 1px solid #e5ebf3;

    border-radius: 18px;

    padding: 18px;

    margin-bottom: 20px;

    box-shadow: 0 7px 22px rgba(25,55,90,.045);

}

.filter-grid {

    display: grid;

    grid-template-columns: minmax(220px, 1.7fr) minmax(170px, .8fr) minmax(150px, .7fr) auto;

    gap: 12px;

    align-items: end;

}

.filter-field label {

    display: block;

    margin-bottom: 7px;

    font-size: 11px;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .45px;

    color: #697689;

}

.filter-control {

    width: 100%;

    min-height: 43px;

    padding: 9px 12px;

    border: 1px solid #dfe6ef;

    border-radius: 10px;

    outline: none;

    background: #fbfcfe;

    color: #273449;

    font-size: 13px;

    transition: .2s ease;

}

.filter-control:focus {

    border-color: #1769d1;

    background: #fff;

    box-shadow: 0 0 0 3px rgba(23,105,209,.08);

}

.search-wrapper {

    position: relative;

}

.search-wrapper i {

    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: #8995a5;

    font-size: 13px;

}

.search-wrapper .filter-control {

    padding-left: 35px;

}

.filter-actions {

    display: flex;

    gap: 8px;

}

.btn-filter {

    min-height: 43px;

    padding: 9px 15px;

    border: 0;

    border-radius: 10px;

    background: #1769d1;

    color: #fff;

    font-size: 12px;

    font-weight: 800;

    text-decoration: none;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

}

.btn-filter:hover {

    background: #1259b3;

    color: #fff;

}

.btn-reset {

    min-height: 43px;

    padding: 9px 13px;

    border: 1px solid #dfe6ef;

    border-radius: 10px;

    background: #fff;

    color: #667386;

    font-size: 12px;

    font-weight: 800;

    text-decoration: none;

    display: inline-flex;

    align-items: center;

    justify-content: center;

}

.btn-reset:hover {

    background: #f7f9fc;

    color: #1769d1;

}

/* =========================================================
   TABLE CARD
========================================================= */

.scholarship-table-card {

    background: #fff;

    border: 1px solid #e5ebf3;

    border-radius: 18px;

    overflow: hidden;

    box-shadow: 0 7px 22px rgba(25,55,90,.045);

}

.table-card-header {

    padding: 18px 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    border-bottom: 1px solid #edf1f6;

}

.table-heading {

    display: flex;

    align-items: center;

    gap: 11px;

}

.table-heading-icon {

    width: 38px;

    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background: rgba(23,105,209,.09);

    color: #1769d1;

}

.table-heading h2 {

    margin: 0;

    font-size: 15px;

    font-weight: 850;

    color: #1c2738;

}

.table-heading span {

    display: block;

    margin-top: 2px;

    font-size: 11px;

    color: #8994a4;

}

.record-count {

    padding: 7px 11px;

    border-radius: 8px;

    background: #f4f7fb;

    color: #657286;

    font-size: 11px;

    font-weight: 800;

}

.table-responsive {

    overflow-x: auto;

}

.scholarship-table {

    width: 100%;

    min-width: 980px;

    border-collapse: collapse;

}

.scholarship-table thead th {

    padding: 12px 16px;

    background: #f8fafc;

    border-bottom: 1px solid #e8edf3;

    color: #758195;

    font-size: 10px;

    font-weight: 850;

    text-transform: uppercase;

    letter-spacing: .5px;

    white-space: nowrap;

}

.scholarship-table tbody td {

    padding: 15px 16px;

    border-bottom: 1px solid #eef2f6;

    color: #344155;

    font-size: 12px;

    vertical-align: middle;

}

.scholarship-table tbody tr:last-child td {

    border-bottom: 0;

}

.scholarship-table tbody tr {

    transition: .15s ease;

}

.scholarship-table tbody tr:hover {

    background: #fbfcfe;

}

/* =========================================================
   EXAM INFO
========================================================= */

.exam-name {

    font-weight: 800;

    color: #1b293c;

    font-size: 13px;

}

.exam-meta {

    margin-top: 4px;

    color: #8a95a5;

    font-size: 10px;

}

.year-badge {

    display: inline-flex;

    align-items: center;

    padding: 5px 8px;

    border-radius: 7px;

    background: rgba(23,105,209,.08);

    color: #1769d1;

    font-size: 10px;

    font-weight: 850;

}

.type-badge {

    display: inline-flex;

    align-items: center;

    padding: 5px 8px;

    border-radius: 7px;

    background: #f5f7fa;

    color: #5e6c80;

    font-size: 10px;

    font-weight: 750;

}

.class-list {

    display: flex;

    flex-wrap: wrap;

    gap: 5px;

    max-width: 190px;

}

.class-chip {

    display: inline-flex;

    padding: 4px 7px;

    border-radius: 6px;

    background: #f1f6ff;

    color: #1769d1;

    font-size: 9px;

    font-weight: 800;

}

.class-more {

    display: inline-flex;

    padding: 4px 7px;

    border-radius: 6px;

    background: #f4f5f7;

    color: #778397;

    font-size: 9px;

    font-weight: 800;

}

.number-cell {

    font-weight: 800;

    color: #273448;

}

.passed-number {

    color: #15936a;

}

.pass-rate {

    display: flex;

    align-items: center;

    gap: 8px;

}

.pass-rate-bar {

    width: 55px;

    height: 5px;

    overflow: hidden;

    border-radius: 20px;

    background: #e8edf3;

}

.pass-rate-fill {

    height: 100%;

    border-radius: inherit;

    background: #20a667;

}

.pass-rate-text {

    font-size: 10px;

    font-weight: 850;

    color: #4f6074;

    white-space: nowrap;

}

/* =========================================================
   STATUS
========================================================= */

.status-badge {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 10px;

    font-weight: 850;

}

.status-active {

    background: rgba(32,166,103,.10);

    color: #15935f;

}

.status-inactive {

    background: rgba(233,77,71,.10);

    color: #d94741;

}

.status-dot {

    width: 5px;

    height: 5px;

    border-radius: 50%;

    background: currentColor;

}

/* =========================================================
   ACTIONS
========================================================= */

.action-buttons {

    display: flex;

    align-items: center;

    gap: 5px;

}

.action-btn {

    width: 31px;

    height: 31px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    border: 1px solid #e2e8f0;

    background: #fff;

    color: #6f7c8f;

    text-decoration: none;

    font-size: 11px;

    transition: .18s ease;

}

.action-btn:hover {

    color: #1769d1;

    border-color: rgba(23,105,209,.25);

    background: #f4f8ff;

    transform: translateY(-1px);

}

.action-btn.edit:hover {

    color: #ed9208;

    border-color: rgba(237,146,8,.25);

    background: #fffaf1;

}

.action-btn.delete:hover {

    color: #e94d47;

    border-color: rgba(233,77,71,.25);

    background: #fff6f5;

}

/* =========================================================
   EMPTY STATE
========================================================= */

.scholarship-empty {

    padding: 65px 25px;

    text-align: center;

}

.empty-icon {

    width: 70px;

    height: 70px;

    margin: 0 auto 16px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 20px;

    background: #f0f5fd;

    color: #1769d1;

    font-size: 27px;

}

.scholarship-empty h3 {

    margin: 0;

    color: #263348;

    font-size: 17px;

    font-weight: 850;

}

.scholarship-empty p {

    max-width: 420px;

    margin: 7px auto 18px;

    color: #8792a2;

    font-size: 12px;

}

/* =========================================================
   PAGINATION
========================================================= */

.pagination-wrapper {

    padding: 15px 20px;

    border-top: 1px solid #edf1f6;

    display: flex;

    justify-content: flex-end;

}

.pagination-wrapper nav {

    margin: 0;

}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .scholarship-kpis {

        grid-template-columns: repeat(2, minmax(0, 1fr));

    }

    .filter-grid {

        grid-template-columns: 1fr 1fr;

    }

    .filter-actions {

        grid-column: 1 / -1;

    }

}

@media (max-width: 768px) {

    .scholarship-exams-page {

        padding: 15px;

    }

    .scholarship-header-content {

        align-items: flex-start;

        flex-direction: column;

    }

    .scholarship-header-actions {

        width: 100%;

    }

    .btn-scholarship-add {

        width: 100%;

        justify-content: center;

    }

    .scholarship-kpis {

        grid-template-columns: repeat(2, minmax(0, 1fr));

    }

    .filter-grid {

        grid-template-columns: 1fr;

    }

    .filter-actions {

        grid-column: auto;

    }

    .filter-actions a,

    .filter-actions button {

        flex: 1;

    }

}

@media (max-width: 480px) {

    .scholarship-kpis {

        grid-template-columns: 1fr;

    }

    .scholarship-title-area {

        align-items: flex-start;

    }

    .scholarship-title-area h1 {

        font-size: 21px;

    }

}

</style>


<div class="scholarship-exams-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="scholarship-header">

        <div class="scholarship-header-content">

            <div class="scholarship-title-area">

                <div class="scholarship-icon">

                    <i class="fas fa-award"></i>

                </div>

                <div>

                    <h1>Scholarship Exam Records</h1>

                    <p>
                        Manage scholarship examination history,
                        student results and scholarship records.
                    </p>

                </div>

            </div>

            <div class="scholarship-header-actions">

                <a href="{{ route('admin.scholarship.exams.create') }}"
                   class="btn-scholarship-add">

                    <i class="fas fa-plus"></i>

                    Add Exam Record

                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
         KPI STATISTICS
    ====================================================== --}}

    <div class="scholarship-kpis">

        {{-- TOTAL EXAMS - BLUE --}}
        <div class="scholarship-kpi kpi-blue">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Exams
                </span>

                <div class="kpi-icon">

                    <i class="fas fa-file-alt"></i>

                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_exams'] ?? 0) }}
            </div>

        </div>


        {{-- TOTAL APPLIED - ORANGE --}}
        <div class="scholarship-kpi kpi-orange">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Applied
                </span>

                <div class="kpi-icon">

                    <i class="fas fa-user-plus"></i>

                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_applied'] ?? 0) }}
            </div>

        </div>


        {{-- TOTAL PASSED - GREEN --}}
        <div class="scholarship-kpi kpi-green">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Passed
                </span>

                <div class="kpi-icon">

                    <i class="fas fa-medal"></i>

                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_passed'] ?? 0) }}
            </div>

        </div>


        {{-- SCHOLARSHIPS - RED --}}
        <div class="scholarship-kpi kpi-red">

            <div class="kpi-top">

                <span class="kpi-label">
                    Scholarships
                </span>

                <div class="kpi-icon">

                    <i class="fas fa-hand-holding-usd"></i>

                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_scholarships'] ?? 0) }}
            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <div class="scholarship-filter-card">

        <form method="GET"
              action="{{ route('admin.scholarship.exams.index') }}">

            <div class="filter-grid">

                {{-- SEARCH --}}

                <div class="filter-field">

                    <label>
                        Search
                    </label>

                    <div class="search-wrapper">

                        <i class="fas fa-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="filter-control"
                            placeholder="Exam name, type, conducted by..."
                        >

                    </div>

                </div>


                {{-- ACADEMIC YEAR --}}

                <div class="filter-field">

                    <label>
                        Academic Year
                    </label>

                    <select
                        name="academic_year"
                        class="filter-control">

                        <option value="">
                            All Academic Years
                        </option>

                        @foreach($academicYears as $year)

                            <option
                                value="{{ $year }}"
                                {{ request('academic_year') == $year ? 'selected' : '' }}>

                                {{ $year }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}

                <div class="filter-field">

                    <label>
                        Status
                    </label>

                    <select
                        name="status"
                        class="filter-control">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>

                            Active

                        </option>

                        <option
                            value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>


                {{-- ACTIONS --}}

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn-filter">

                        <i class="fas fa-filter"></i>

                        Filter

                    </button>

                    <a
                        href="{{ route('admin.scholarship.exams.index') }}"
                        class="btn-reset">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
         EXAM RECORDS TABLE
    ====================================================== --}}

    <div class="scholarship-table-card">

        <div class="table-card-header">

            <div class="table-heading">

                <div class="table-heading-icon">

                    <i class="fas fa-history"></i>

                </div>

                <div>

                    <h2>
                        Scholarship Examination History
                    </h2>

                    <span>
                        Complete year-wise examination records
                    </span>

                </div>

            </div>

            <div class="record-count">

                {{ $exams->total() }} Records

            </div>

        </div>


        @if($exams->count())

            <div class="table-responsive">

                <table class="scholarship-table">

                    <thead>

                        <tr>

                            <th>
                                Exam
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Classes
                            </th>

                            <th>
                                Applied
                            </th>

                            <th>
                                Appeared
                            </th>

                            <th>
                                Passed
                            </th>

                            <th>
                                Pass Rate
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($exams as $exam)

                            <tr>

                                {{-- EXAM --}}

                                <td>

                                    <div class="exam-name">

                                        {{ $exam->exam_name }}

                                    </div>

                                    <div class="exam-meta">

                                        {{ $exam->exam_type ?: 'Scholarship Exam' }}

                                        @if($exam->conducted_by)

                                            · {{ $exam->conducted_by }}

                                        @endif

                                    </div>

                                </td>


                                {{-- YEAR --}}

                                <td>

                                    <span class="year-badge">

                                        {{ $exam->academic_year }}

                                    </span>

                                </td>


                                {{-- DATE --}}

                                <td>

                                    @if($exam->exam_date)

                                        <div style="font-weight:750;color:#344155;">

                                            {{ $exam->exam_date->format('d M Y') }}

                                        </div>

                                    @else

                                        <span style="color:#9aa5b5;">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- CLASSES --}}

                                <td>

                                    @if($exam->classes->count())

                                        <div class="class-list">

                                            @foreach($exam->classes->take(3) as $class)

                                                <span class="class-chip">

                                                    {{ $class->class_name }}

                                                    @if($class->section)

                                                        - {{ $class->section }}

                                                    @endif

                                                </span>

                                            @endforeach

                                            @if($exam->classes->count() > 3)

                                                <span class="class-more">

                                                    +{{ $exam->classes->count() - 3 }}

                                                </span>

                                            @endif

                                        </div>

                                    @else

                                        <span style="color:#9aa5b5;">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- APPLIED --}}

                                <td>

                                    <span class="number-cell">

                                        {{ number_format($exam->total_applied ?? 0) }}

                                    </span>

                                </td>


                                {{-- APPEARED --}}

                                <td>

                                    <span class="number-cell">

                                        {{ number_format($exam->total_appeared ?? 0) }}

                                    </span>

                                </td>


                                {{-- PASSED --}}

                                <td>

                                    <span class="number-cell passed-number">

                                        {{ number_format($exam->total_passed ?? 0) }}

                                    </span>

                                </td>


                                {{-- PASS RATE --}}

                                <td>

                                    @php

                                        $passPercentage = (float) ($exam->pass_percentage ?? 0);

                                        $passPercentage = min(100, max(0, $passPercentage));

                                    @endphp

                                    <div class="pass-rate">

                                        <div class="pass-rate-bar">

                                            <div
                                                class="pass-rate-fill"
                                                style="width: {{ $passPercentage }}%;">

                                            </div>

                                        </div>

                                        <span class="pass-rate-text">

                                            {{ number_format($passPercentage, 2) }}%

                                        </span>

                                    </div>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($exam->status)

                                        <span class="status-badge status-active">

                                            <span class="status-dot"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">

                                            <span class="status-dot"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.scholarship.exams.show', $exam) }}"
                                            class="action-btn"
                                            title="View">

                                            <i class="fas fa-eye"></i>

                                        </a>

                                        <a
                                            href="{{ route('admin.scholarship.exams.edit', $exam) }}"
                                            class="action-btn edit"
                                            title="Edit">

                                            <i class="fas fa-pen"></i>

                                        </a>

                                        <form
                                            action="{{ route('admin.scholarship.exams.destroy', $exam) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this scholarship exam record?');">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Delete">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if($exams->hasPages())

                <div class="pagination-wrapper">

                    {{ $exams->withQueryString()->links() }}

                </div>

            @endif

        @else

            {{-- EMPTY STATE --}}

            <div class="scholarship-empty">

                <div class="empty-icon">

                    <i class="fas fa-award"></i>

                </div>

                <h3>

                    No Scholarship Exam Records Found

                </h3>

                <p>

                    Start maintaining your scholarship examination
                    history by creating your first exam record.

                </p>

                <a
                    href="{{ route('admin.scholarship.exams.create') }}"
                    class="btn-scholarship-add">

                    <i class="fas fa-plus"></i>

                    Add First Exam Record

                </a>

            </div>

        @endif

    </div>

</div>

@endsection