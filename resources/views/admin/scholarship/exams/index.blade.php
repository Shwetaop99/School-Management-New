@extends('layouts.app')

@section('title', 'Scholarship Exam Records | Admin')

@section('page-title', 'Scholarship Exam Records')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP EXAM RECORDS — PREMIUM MANAGEMENT UI
========================================================= */

.scholarship-exams-page {
    min-height: calc(100vh - 70px);
    padding: 26px;
    background:
        radial-gradient(circle at 0% 0%, rgba(23,105,209,.075), transparent 28%),
        radial-gradient(circle at 100% 5%, rgba(21,156,199,.065), transparent 25%),
        linear-gradient(180deg, #f5f8fc 0%, #f8fafc 100%);
}

/* =========================================================
   PREMIUM HEADER
========================================================= */

.scholarship-header {
    position: relative;
    overflow: hidden;
    min-height: 150px;
    margin-bottom: 22px;
    padding: 27px 30px;
    border-radius: 21px;
    color: #fff;
    background:
        radial-gradient(circle at 88% 18%, rgba(255,255,255,.16), transparent 19%),
        radial-gradient(circle at 73% 100%, rgba(255,255,255,.08), transparent 25%),
        linear-gradient(135deg, #125bc2 0%, #1769d1 45%, #159cc7 100%);
    box-shadow: 0 15px 35px rgba(23,105,209,.19);
}

.scholarship-header::before {
    content: "";
    position: absolute;
    width: 250px;
    height: 250px;
    right: -72px;
    top: -125px;
    border-radius: 50%;
    border: 34px solid rgba(255,255,255,.07);
}

.scholarship-header::after {
    content: "";
    position: absolute;
    width: 155px;
    height: 155px;
    right: 145px;
    bottom: -108px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.scholarship-header-content {
    position: relative;
    z-index: 2;
    min-height: 96px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.scholarship-title-area {
    display: flex;
    align-items: center;
    gap: 17px;
}

.scholarship-icon {
    width: 64px;
    height: 64px;
    flex: 0 0 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,.27);
    border-radius: 18px;
    color: #fff;
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(9px);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.20),
        0 10px 25px rgba(0,0,0,.10);
    font-size: 26px;
}

.scholarship-title-area h1 {
    margin: 0;
    color: #fff;
    font-size: 27px;
    line-height: 1.15;
    font-weight: 850;
    letter-spacing: -.45px;
}

.scholarship-title-area p {
    max-width: 690px;
    margin: 7px 0 0;
    color: rgba(255,255,255,.82);
    font-size: 12px;
    line-height: 1.55;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 10px;
    color: rgba(255,255,255,.90);
    font-size: 10px;
    font-weight: 750;
}

.header-meta i {
    font-size: 10px;
}

/* =========================================================
   HEADER ACTION
========================================================= */

.scholarship-header-actions {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
}

.btn-scholarship-add {
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0 17px;
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 11px;
    color: #1769d1;
    background: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 850;
    box-shadow: 0 9px 21px rgba(0,0,0,.13);
    transition: all .22s ease;
}

.btn-scholarship-add:hover {
    color: #125bc2;
    transform: translateY(-2px);
    box-shadow: 0 13px 26px rgba(0,0,0,.17);
}

/* =========================================================
   KPI SECTION
========================================================= */

.scholarship-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.scholarship-kpi {
    position: relative;
    overflow: hidden;
    min-height: 138px;
    padding: 20px;
    border: 0;
    border-radius: 17px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(25,55,90,.10);
    transition: transform .22s ease, box-shadow .22s ease;
}

.scholarship-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 31px rgba(25,55,90,.15);
}

/* =========================================================
   KPI DECORATION
========================================================= */

.scholarship-kpi::before {
    content: "";
    position: absolute;
    width: 125px;
    height: 125px;
    top: -48px;
    right: -38px;
    border-radius: 50%;
    border: 22px solid rgba(255,255,255,.08);
}

.scholarship-kpi::after {
    content: "";
    position: absolute;
    width: 78px;
    height: 78px;
    right: 70px;
    bottom: -48px;
    border-radius: 50%;
    background: rgba(255,255,255,.075);
}

/* =========================================================
   EXACT MERIT CONCESSION COLOURS
========================================================= */

/* BLUE — TOTAL EXAMS */
.scholarship-kpi-blue {
    background: linear-gradient(135deg, #1769d1, #3489e9);
}

/* GREEN — TOTAL APPLIED */
.scholarship-kpi-green {
    background: linear-gradient(135deg, #078b70, #18b995);
}

/* ORANGE — TOTAL APPEARED */
.scholarship-kpi-orange {
    background: linear-gradient(135deg, #e88800, #f5ac32);
}

/* RED — TOTAL PASSED */
.scholarship-kpi-red {
    background: linear-gradient(135deg, #e94d47, #f36f67);
}

/* =========================================================
   KPI CONTENT
========================================================= */

.kpi-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.kpi-label {
    color: rgba(255,255,255,.89);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .65px;
}

.kpi-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 11px;
    color: #fff;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(6px);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.14);
    font-size: 15px;
}

.kpi-value {
    position: relative;
    z-index: 2;
    margin-top: 17px;
    color: #fff;
    font-size: 31px;
    line-height: 1;
    font-weight: 850;
    letter-spacing: -.6px;
}

.kpi-caption {
    position: relative;
    z-index: 2;
    margin-top: 7px;
    color: rgba(255,255,255,.72);
    font-size: 10px;
    font-weight: 600;
}

/* =========================================================
   FILTER PANEL
========================================================= */

.scholarship-filter-card {
    margin-bottom: 22px;
    padding: 19px;
    border: 1px solid #e3eaf3;
    border-radius: 17px;
    background: rgba(255,255,255,.96);
    box-shadow: 0 8px 24px rgba(25,55,90,.05);
}

.filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 16px;
}

.filter-heading-left {
    display: flex;
    align-items: center;
    gap: 11px;
}

.filter-heading-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 13px;
}

.filter-heading-title {
    color: #263449;
    font-size: 14px;
    font-weight: 850;
}

.filter-heading-subtitle {
    margin-top: 2px;
    color: #8a98aa;
    font-size: 10px;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #e1e8f1;
    border-radius: 20px;
    color: #68778b;
    background: #f8fafc;
    font-size: 9px;
    font-weight: 800;
}

.filter-grid {
    display: grid;
    grid-template-columns:
        minmax(240px, 1.8fr)
        minmax(175px, .85fr)
        minmax(160px, .75fr)
        auto;
    gap: 12px;
    align-items: end;
}

.filter-field label {
    display: block;
    margin-bottom: 6px;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.filter-control {
    width: 100%;
    height: 42px;
    padding: 0 12px;
    outline: none;
    border: 1px solid #dce5ef;
    border-radius: 10px;
    color: #263449;
    background: #fbfcfe;
    font-size: 12px;
    transition: all .2s ease;
}

.filter-control:hover {
    border-color: #c9d7e7;
    background: #fff;
}

.filter-control:focus {
    border-color: #4c91e8;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(76,145,232,.08);
}

.search-wrapper {
    position: relative;
}

.search-wrapper i {
    position: absolute;
    left: 13px;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    color: #91a0b3;
    font-size: 11px;
}

.search-wrapper .filter-control {
    padding-left: 35px;
}

/* =========================================================
   FILTER ACTIONS
========================================================= */

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-filter {
    height: 42px;
    padding: 0 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border: 0;
    border-radius: 10px;
    color: #fff;
    background: linear-gradient(135deg, #1769d1, #159cc7);
    font-size: 11px;
    font-weight: 850;
    cursor: pointer;
    box-shadow: 0 7px 15px rgba(23,105,209,.14);
    transition: all .2s ease;
}

.btn-filter:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(23,105,209,.20);
}

.btn-reset {
    height: 42px;
    padding: 0 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid #dce4ef;
    border-radius: 10px;
    color: #64748b;
    background: #fff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    transition: all .2s ease;
}

.btn-reset:hover {
    color: #1769d1;
    border-color: #c2d5ed;
    background: #f6faff;
}

/* =========================================================
   TABLE PANEL
========================================================= */

.scholarship-table-card {
    overflow: hidden;
    border: 1px solid #e2e9f2;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 9px 27px rgba(25,55,90,.055);
}

.table-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #e9eef5;
    background: linear-gradient(180deg, #fff, #fbfdff);
}

.table-heading {
    display: flex;
    align-items: center;
    gap: 11px;
}

.table-heading-icon {
    width: 39px;
    height: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 14px;
}

.table-heading h2 {
    margin: 0;
    color: #1c2738;
    font-size: 15px;
    font-weight: 850;
}

.table-heading span {
    display: block;
    margin-top: 3px;
    color: #8994a4;
    font-size: 10px;
}

.record-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 11px;
    border: 1px solid #e2e9f2;
    border-radius: 20px;
    color: #64748b;
    background: #f7f9fc;
    font-size: 10px;
    font-weight: 800;
}

/* =========================================================
   TABLE
========================================================= */

.table-responsive {
    overflow-x: auto;
}

.scholarship-table {
    width: 100%;
    min-width: 1080px;
    border-collapse: collapse;
}

.scholarship-table thead th {
    padding: 12px 15px;
    border-bottom: 1px solid #e8edf4;
    background: #f8fafc;
    color: #718096;
    font-size: 9px;
    font-weight: 850;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .55px;
    white-space: nowrap;
}

.scholarship-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #eef2f7;
    color: #344155;
    font-size: 12px;
    vertical-align: middle;
}

.scholarship-table tbody tr {
    transition: background .18s ease;
}

.scholarship-table tbody tr:hover {
    background: #f9fbff;
}

.scholarship-table tbody tr:last-child td {
    border-bottom: 0;
}

/* =========================================================
   EXAM INFORMATION
========================================================= */

.exam-name {
    color: #1b293c;
    font-size: 12px;
    font-weight: 850;
}

.exam-meta {
    margin-top: 4px;
    color: #8a95a5;
    font-size: 9px;
}

/* =========================================================
   YEAR
========================================================= */

.year-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid #dce9fa;
    border-radius: 8px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

/* =========================================================
   DATE
========================================================= */

.date-value {
    color: #344155;
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.date-icon {
    margin-right: 5px;
    color: #8a98aa;
}

/* =========================================================
   CLASS CHIPS
========================================================= */

.class-list {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    max-width: 190px;
}

.class-chip {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border: 1px solid #dbe9fb;
    border-radius: 6px;
    color: #1769d1;
    background: #f1f6ff;
    font-size: 9px;
    font-weight: 800;
}

.class-more {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border-radius: 6px;
    color: #778397;
    background: #f3f5f8;
    font-size: 9px;
    font-weight: 800;
}

/* =========================================================
   NUMBER CELLS
========================================================= */

.number-cell {
    color: #273448;
    font-size: 12px;
    font-weight: 850;
}

.number-icon {
    margin-right: 5px;
    color: #94a1b1;
    font-size: 9px;
}

.passed-number {
    color: #e04b45;
}

/* =========================================================
   PASS RATE
========================================================= */

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
    background: linear-gradient(90deg, #e88800, #f5ac32);
}

.pass-rate-text {
    color: #4f6074;
    font-size: 10px;
    font-weight: 850;
    white-space: nowrap;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

.status-active {
    border: 1px solid #c9efdf;
    color: #11815d;
    background: #eafaf3;
}

.status-inactive {
    border: 1px solid #f4d0ce;
    color: #d33f39;
    background: #fff1f0;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* =========================================================
   ACTIONS
========================================================= */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e7f0;
    border-radius: 9px;
    color: #68778b;
    background: #fff;
    text-decoration: none;
    font-size: 10px;
    cursor: pointer;
    transition: all .18s ease;
}

.action-btn:hover {
    color: #1769d1;
    border-color: #bcd3ef;
    background: #f3f8ff;
    transform: translateY(-1px);
}

.action-btn.edit:hover {
    color: #ed9208;
    border-color: rgba(237,146,8,.28);
    background: #fffaf1;
}

.action-btn.delete:hover {
    color: #e94d47;
    border-color: rgba(233,77,71,.28);
    background: #fff6f5;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.scholarship-empty {
    padding: 68px 25px;
    text-align: center;
}

.empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dce9fa;
    border-radius: 20px;
    color: #1769d1;
    background: linear-gradient(135deg, #edf5ff, #f5faff);
    box-shadow: 0 8px 20px rgba(23,105,209,.07);
    font-size: 26px;
}

.scholarship-empty h3 {
    margin: 0 0 7px;
    color: #263348;
    font-size: 17px;
    font-weight: 850;
}

.scholarship-empty p {
    max-width: 440px;
    margin: 0 auto 19px;
    color: #8792a2;
    font-size: 11px;
    line-height: 1.6;
}

/* =========================================================
   PAGINATION
========================================================= */

.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    padding: 15px 20px;
    border-top: 1px solid #edf1f6;
    background: #fcfdff;
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

@media (max-width: 800px) {
    .scholarship-exams-page {
        padding: 16px;
    }

    .scholarship-header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .scholarship-header-actions {
        width: 100%;
    }

    .btn-scholarship-add {
        width: 100%;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        grid-column: auto;
    }

    .filter-actions .btn-filter,
    .filter-actions .btn-reset {
        flex: 1;
    }

    .filter-heading {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (max-width: 600px) {
    .scholarship-kpis {
        grid-template-columns: 1fr;
    }

    .scholarship-header {
        padding: 23px;
    }

    .scholarship-title-area {
        align-items: flex-start;
    }

    .scholarship-title-area h1 {
        font-size: 23px;
    }

    .scholarship-icon {
        width: 56px;
        height: 56px;
        flex-basis: 56px;
        border-radius: 15px;
        font-size: 23px;
    }

    .table-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .record-count {
        align-self: flex-start;
    }
}
</style>

<div class="scholarship-exams-page">

    {{-- =====================================================
         PREMIUM HEADER
    ====================================================== --}}

    <div class="scholarship-header">

        <div class="scholarship-header-content">

            <div class="scholarship-title-area">

                <div class="scholarship-icon">
                    <i class="fas fa-award"></i>
                </div>

                <div>

                    <h1>
                        Scholarship Exam Records
                    </h1>

                    <p>
                        Maintain complete scholarship examination history,
                        application statistics, student participation and
                        year-wise examination outcomes.
                    </p>

                    <div class="header-meta">
                        <i class="fas fa-chart-line"></i>

                        <span>
                            Examination History
                            •
                            Student Participation
                            •
                            Results
                        </span>
                    </div>

                </div>

            </div>

            <div class="scholarship-header-actions">

                <a
                    href="{{ route('admin.scholarship.exams.create') }}"
                    class="btn-scholarship-add"
                >
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

        {{-- TOTAL EXAMS --}}
        <div class="scholarship-kpi scholarship-kpi-blue">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Exams
                </span>

                <div class="kpi-icon">
                    <i class="fas fa-file-lines"></i>
                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_exams'] ?? 0) }}
            </div>

            <div class="kpi-caption">
                Scholarship examinations recorded
            </div>

        </div>


        {{-- TOTAL APPLIED --}}
        <div class="scholarship-kpi scholarship-kpi-green">

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

            <div class="kpi-caption">
                Students registered for exams
            </div>

        </div>


        {{-- TOTAL APPEARED --}}
        <div class="scholarship-kpi scholarship-kpi-orange">

            <div class="kpi-top">

                <span class="kpi-label">
                    Total Appeared
                </span>

                <div class="kpi-icon">
                    <i class="fas fa-user-check"></i>
                </div>

            </div>

            <div class="kpi-value">
                {{ number_format($statistics['total_appeared'] ?? 0) }}
            </div>

            <div class="kpi-caption">
                Students appeared for exams
            </div>

        </div>


        {{-- TOTAL PASSED --}}
        <div class="scholarship-kpi scholarship-kpi-red">

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

            <div class="kpi-caption">
                Students successfully passed
            </div>

        </div>

    </div>


    {{-- =====================================================
         SEARCH & FILTER
    ====================================================== --}}

    <div class="scholarship-filter-card">

        <div class="filter-heading">

            <div class="filter-heading-left">

                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>

                <div>

                    <div class="filter-heading-title">
                        Search & Filter Exams
                    </div>

                    <div class="filter-heading-subtitle">
                        Find examination records by name, academic year or status
                    </div>

                </div>

            </div>

            <div class="filter-badge">
                <i class="fas fa-sliders"></i>
                Exam Filters
            </div>

        </div>


        <form
            method="GET"
            action="{{ route('admin.scholarship.exams.index') }}"
        >

            <div class="filter-grid">

                {{-- SEARCH --}}
                <div class="filter-field">

                    <label>
                        Search Examination
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
                        class="filter-control"
                    >

                        <option value="">
                            All Academic Years
                        </option>

                        @foreach($academicYears as $year)

                            <option
                                value="{{ $year }}"
                                {{ request('academic_year') == $year ? 'selected' : '' }}
                            >
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
                        class="filter-control"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- ACTIONS --}}
                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn-filter"
                    >
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.scholarship.exams.index') }}"
                        class="btn-reset"
                    >
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
         EXAMINATION HISTORY TABLE
    ====================================================== --}}

    <div class="scholarship-table-card">

        <div class="table-card-header">

            <div class="table-heading">

                <div class="table-heading-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>

                <div>

                    <h2>
                        Examination History
                    </h2>

                    <span>
                        Complete year-wise scholarship examination records
                    </span>

                </div>

            </div>


            <div class="record-count">

                <i class="fas fa-database"></i>

                {{ $exams->total() }}

                {{ $exams->total() == 1 ? 'Record' : 'Records' }}

            </div>

        </div>


        @if($exams->count())

            <div class="table-responsive">

                <table class="scholarship-table">

                    <thead>

                        <tr>

                            <th>
                                Examination
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

                                        <i class="fas fa-calendar"></i>

                                        {{ $exam->academic_year }}

                                    </span>

                                </td>


                                {{-- DATE --}}
                                <td>

                                    @if($exam->exam_date)

                                        <span class="date-value">

                                            <i class="fas fa-calendar-day date-icon"></i>

                                            {{ $exam->exam_date->format('d M Y') }}

                                        </span>

                                    @else

                                        <span class="date-value">
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

                                        <span class="date-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- APPLIED --}}
                                <td>

                                    <span class="number-cell">

                                        <i class="fas fa-user-plus number-icon"></i>

                                        {{ number_format($exam->total_applied ?? 0) }}

                                    </span>

                                </td>


                                {{-- APPEARED --}}
                                <td>

                                    <span class="number-cell">

                                        <i class="fas fa-user-check number-icon"></i>

                                        {{ number_format($exam->total_appeared ?? 0) }}

                                    </span>

                                </td>


                                {{-- PASSED --}}
                                <td>

                                    <span class="number-cell passed-number">

                                        <i class="fas fa-medal"></i>

                                        {{ number_format($exam->total_passed ?? 0) }}

                                    </span>

                                </td>


                                {{-- PASS RATE --}}
                                <td>

                                    @php

                                        $passPercentage = (float) (
                                            $exam->pass_percentage ?? 0
                                        );

                                        $passPercentage = min(
                                            100,
                                            max(0, $passPercentage)
                                        );

                                    @endphp


                                    <div class="pass-rate">

                                        <div class="pass-rate-bar">

                                            <div
                                                class="pass-rate-fill"
                                                style="width: {{ $passPercentage }}%;"
                                            ></div>

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

                                        {{-- VIEW --}}
                                        <a
                                            href="{{ route('admin.scholarship.exams.show', $exam) }}"
                                            class="action-btn"
                                            title="View Examination"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('admin.scholarship.exams.edit', $exam) }}"
                                            class="action-btn edit"
                                            title="Edit Examination"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('admin.scholarship.exams.destroy', $exam) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this scholarship examination record?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Delete Examination"
                                            >
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

                    {{ $exams
                        ->withQueryString()
                        ->links()
                    }}

                </div>

            @endif


        @else

            {{-- EMPTY STATE --}}
            <div class="scholarship-empty">

                <div class="empty-icon">
                    <i class="fas fa-award"></i>
                </div>

                <h3>
                    No Examination Records Found
                </h3>

                <p>
                    There are currently no scholarship examination records
                    matching your selected filters. Create your first
                    examination record to begin maintaining the history.
                </p>

                <a
                    href="{{ route('admin.scholarship.exams.create') }}"
                    class="btn-scholarship-add"
                >
                    <i class="fas fa-plus"></i>
                    Add First Exam Record
                </a>

            </div>

        @endif

    </div>

</div>

@endsection