@extends('layouts.app')

@section('title', 'Scholarship | Admin')

@section('page-title', 'Scholarship')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP MANAGEMENT — PREMIUM UI
========================================================= */

.scholarship-page {
    min-height: calc(100vh - 70px);
    padding: 26px;
    background:
        radial-gradient(circle at 0% 0%, rgba(23,105,209,.08), transparent 28%),
        radial-gradient(circle at 100% 5%, rgba(21,156,199,.07), transparent 25%),
        linear-gradient(180deg, #f5f8fc 0%, #f8fafc 100%);
}

/* =========================================================
   PREMIUM HEADER
========================================================= */

.scholarship-hero {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    min-height: 155px;
    margin-bottom: 22px;
    padding: 28px 30px;
    border-radius: 20px;
    color: #fff;
    background:
        radial-gradient(circle at 90% 15%, rgba(255,255,255,.18), transparent 20%),
        radial-gradient(circle at 75% 100%, rgba(255,255,255,.10), transparent 25%),
        linear-gradient(135deg, #125bc2 0%, #1769d1 45%, #159cc7 100%);
    box-shadow: 0 15px 35px rgba(23,105,209,.18);
}

.scholarship-hero::before {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    right: -70px;
    top: -105px;
    border-radius: 50%;
    border: 35px solid rgba(255,255,255,.07);
}

.scholarship-hero::after {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    right: 125px;
    bottom: -95px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.scholarship-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 18px;
}

.scholarship-hero-icon {
    width: 66px;
    height: 66px;
    flex: 0 0 66px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 18px;
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(8px);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.18),
        0 10px 25px rgba(0,0,0,.10);
    font-size: 27px;
}

.scholarship-hero-text h2 {
    margin: 0;
    font-size: 27px;
    line-height: 1.15;
    font-weight: 850;
    letter-spacing: -.4px;
}

.scholarship-hero-text p {
    margin: 8px 0 0;
    max-width: 650px;
    color: rgba(255,255,255,.82);
    font-size: 13px;
    line-height: 1.55;
}

.hero-mini-info {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 10px;
    color: rgba(255,255,255,.9);
    font-size: 11px;
    font-weight: 700;
}

.hero-mini-info i {
    font-size: 10px;
}

.hero-actions {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 10px;
}

.scholarship-rules-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 43px;
    padding: 0 17px;
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 11px;
    color: #1769d1;
    background: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    box-shadow: 0 9px 20px rgba(0,0,0,.12);
    transition: all .22s ease;
}

.scholarship-rules-btn:hover {
    color: #125bc2;
    transform: translateY(-2px);
    box-shadow: 0 13px 25px rgba(0,0,0,.16);
}

.scholarship-rules-btn i {
    font-size: 12px;
}

/* =========================================================
   SUCCESS ALERT
========================================================= */

.success-alert {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #bdebd9;
    border-radius: 12px;
    background: linear-gradient(135deg, #f0fcf7, #e9faf3);
    color: #087b59;
    font-size: 12px;
    font-weight: 750;
    box-shadow: 0 5px 15px rgba(8,123,89,.05);
}

.success-alert i {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #d8f7eb;
}

/* =========================================================
   KPI CARDS
========================================================= */

.scholarship-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}

.scholarship-kpi {
    position: relative;
    overflow: hidden;
    min-height: 128px;
    padding: 19px;
    border-radius: 16px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(25,50,90,.09);
    transition: transform .22s ease, box-shadow .22s ease;
}

.scholarship-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(25,50,90,.14);
}

.scholarship-kpi::before {
    content: "";
    position: absolute;
    width: 115px;
    height: 115px;
    right: -40px;
    top: -45px;
    border-radius: 50%;
    border: 22px solid rgba(255,255,255,.08);
}

.scholarship-kpi::after {
    content: "";
    position: absolute;
    width: 85px;
    height: 85px;
    right: -25px;
    bottom: -35px;
    border-radius: 50%;
    background: rgba(255,255,255,.09);
}

.scholarship-kpi.blue {
    background: linear-gradient(135deg, #1769d1, #3489e9);
}

.scholarship-kpi.green {
    background: linear-gradient(135deg, #078b70, #18b995);
}

.scholarship-kpi.orange {
    background: linear-gradient(135deg, #e88800, #f5ac32);
}

.scholarship-kpi.red {
    background: linear-gradient(135deg, #db403b, #ef6c66);
}

.kpi-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.kpi-label {
    font-size: 11px;
    font-weight: 750;
    letter-spacing: .2px;
    opacity: .9;
}

.kpi-icon {
    width: 37px;
    height: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 11px;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(5px);
    font-size: 14px;
}

.kpi-value {
    position: relative;
    z-index: 2;
    font-size: 31px;
    line-height: 1;
    font-weight: 850;
    letter-spacing: -.5px;
}

.kpi-caption {
    position: relative;
    z-index: 2;
    margin-top: 8px;
    color: rgba(255,255,255,.72);
    font-size: 10px;
    font-weight: 600;
}

/* =========================================================
   FILTER PANEL
========================================================= */

.filter-panel {
    margin-bottom: 22px;
    padding: 19px;
    border: 1px solid #e3eaf3;
    border-radius: 16px;
    background: rgba(255,255,255,.94);
    box-shadow: 0 8px 24px rgba(25,50,90,.055);
}

.filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.filter-title-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
}

.filter-title-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 13px;
}

.filter-title {
    margin: 0;
    color: #263449;
    font-size: 14px;
    font-weight: 850;
}

.filter-subtitle {
    margin: 2px 0 0;
    color: #8a98aa;
    font-size: 10px;
}

.filter-badge {
    padding: 6px 10px;
    border: 1px solid #e1e9f3;
    border-radius: 20px;
    color: #68778b;
    background: #f8fafc;
    font-size: 10px;
    font-weight: 750;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
}

.filter-control {
    width: 100%;
    height: 42px;
    padding: 0 12px;
    border: 1px solid #dce5ef;
    border-radius: 10px;
    outline: none;
    color: #263449;
    background: #fbfcfe;
    font-size: 12px;
    transition: .2s ease;
}

.filter-control:hover {
    border-color: #c8d6e8;
    background: #fff;
}

.filter-control:focus {
    border-color: #4c91e8;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(76,145,232,.09);
}

.filter-search {
    position: relative;
}

.filter-search i {
    position: absolute;
    left: 13px;
    top: 14px;
    z-index: 2;
    color: #91a0b3;
    font-size: 11px;
}

.filter-search input {
    padding-left: 35px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.apply-btn {
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
    font-weight: 800;
    box-shadow: 0 7px 15px rgba(23,105,209,.16);
    cursor: pointer;
    transition: .2s ease;
}

.apply-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(23,105,209,.22);
}

.reset-btn {
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
    font-size: 11px;
    font-weight: 750;
    text-decoration: none;
    transition: .2s ease;
}

.reset-btn:hover {
    color: #1769d1;
    border-color: #bdd3ed;
    background: #f6faff;
}

/* =========================================================
   TABLE PANEL
========================================================= */

.table-panel {
    overflow: hidden;
    border: 1px solid #e2e9f2;
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 9px 27px rgba(25,50,90,.06);
}

.table-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 19px;
    border-bottom: 1px solid #e9eef5;
    background: linear-gradient(180deg, #ffffff, #fbfdff);
}

.table-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
}

.table-panel-icon {
    width: 37px;
    height: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 14px;
}

.table-panel-title {
    color: #263449;
    font-size: 14px;
    font-weight: 850;
}

.table-panel-subtitle {
    margin-top: 2px;
    color: #8a98aa;
    font-size: 10px;
}

.table-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 22px;
    margin-left: 7px;
    padding: 0 8px;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 10px;
    font-weight: 850;
}

.table-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-responsive {
    overflow-x: auto;
}

.scholarship-table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
}

.scholarship-table th {
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

.scholarship-table td {
    padding: 14px 15px;
    border-bottom: 1px solid #eef2f7;
    color: #334155;
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

.serial-number {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    color: #65748a;
    background: #f4f7fb;
    font-size: 10px;
    font-weight: 800;
}

.student-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.student-avatar {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    color: #1769d1;
    background: linear-gradient(135deg, #eaf3ff, #dceeff);
    font-size: 12px;
    font-weight: 850;
}

.student-name {
    color: #1d2b3e;
    font-size: 12px;
    font-weight: 800;
}

.student-id {
    margin-top: 3px;
    color: #8a98aa;
    font-size: 9px;
}

.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 9px;
    border: 1px solid #dbe9fb;
    border-radius: 8px;
    color: #1769d1;
    background: #f1f6ff;
    font-size: 10px;
    font-weight: 800;
}

.percentage-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}

.percentage-bar {
    width: 48px;
    height: 5px;
    overflow: hidden;
    border-radius: 10px;
    background: #e8eef5;
}

.percentage-fill {
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, #1769d1, #159cc7);
}

.percentage-value {
    color: #1d2b3e;
    font-size: 13px;
    font-weight: 850;
}

.scholarship-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

.scholarship-badge.eligible {
    border: 1px solid #c9efdf;
    background: #eafaf3;
    color: #11815d;
}

.scholarship-badge.not-eligible {
    border: 1px solid #f4d0ce;
    background: #fff1f0;
    color: #d33f39;
}

.annual-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid #dce9fa;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

.year-text {
    color: #556579;
    font-weight: 750;
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    width: 33px;
    height: 33px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e7f0;
    border-radius: 9px;
    color: #65748a;
    background: #fff;
    text-decoration: none;
    font-size: 11px;
    transition: .2s ease;
}

.action-btn:hover {
    color: #1769d1;
    border-color: #bcd3ef;
    background: #f3f8ff;
    transform: translateY(-1px);
}

.action-btn.edit:hover {
    color: #e48700;
    border-color: #f0d19d;
    background: #fff9ee;
}

.muted-text {
    color: #9aa7b7;
    font-size: 11px;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dce9fa;
    border-radius: 19px;
    color: #1769d1;
    background: linear-gradient(135deg, #edf5ff, #f5faff);
    font-size: 25px;
    box-shadow: 0 8px 20px rgba(23,105,209,.07);
}

.empty-state h4 {
    margin: 0 0 7px;
    color: #263449;
    font-size: 16px;
    font-weight: 850;
}

.empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: #8a98aa;
    font-size: 11px;
    line-height: 1.6;
}

/* =========================================================
   PAGINATION
========================================================= */

.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    padding: 15px 18px;
    border-top: 1px solid #eef2f7;
    background: #fcfdff;
}

.pagination-wrapper nav {
    display: inline-flex;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {
    .scholarship-kpis {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .filter-actions {
        grid-column: span 3;
    }
}

@media (max-width: 800px) {
    .scholarship-page {
        padding: 16px;
    }

    .scholarship-hero {
        align-items: flex-start;
        flex-direction: column;
        padding: 23px;
    }

    .hero-actions {
        width: 100%;
    }

    .scholarship-rules-btn {
        width: 100%;
    }

    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 600px) {
    .scholarship-kpis {
        grid-template-columns: 1fr;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        grid-column: auto;
    }

    .filter-actions .apply-btn,
    .filter-actions .reset-btn {
        flex: 1;
    }

    .scholarship-hero-text h2 {
        font-size: 23px;
    }

    .scholarship-hero-icon {
        width: 55px;
        height: 55px;
        flex-basis: 55px;
    }

    .table-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .table-header-actions {
        width: 100%;
    }

    .table-header-actions .reset-btn {
        width: 100%;
    }
}
</style>

<div class="scholarship-page">

{{-- =====================================================
     SUCCESS MESSAGE
====================================================== --}}

@if(session('success'))
    <div class="success-alert">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif


{{-- =====================================================
     PREMIUM PAGE HEADER
====================================================== --}}

<div class="scholarship-hero">

    <div class="scholarship-hero-content">

        <div class="scholarship-hero-icon">
            <i class="fas fa-award"></i>
        </div>

        <div class="scholarship-hero-text">

            <h2>Scholarship Management</h2>

            <p>
                Manage student scholarships, merit eligibility and
                annual examination-based concessions from one place.
            </p>

            <div class="hero-mini-info">
                <i class="fas fa-shield-check"></i>
                <span>Annual Exam & Merit Based Scholarship</span>
            </div>

        </div>

    </div>

    <div class="hero-actions">

        <a
            href="{{ route('admin.scholarship.merit.concession.create') }}"
            class="scholarship-rules-btn"
        >
            <i class="fas fa-sliders"></i>
            Scholarship Rules
        </a>

    </div>

</div>


{{-- =====================================================
     KPI CARDS
====================================================== --}}

<div class="scholarship-kpis">

    <div class="scholarship-kpi blue">

        <div class="kpi-top">
            <span class="kpi-label">Annual Results</span>

            <span class="kpi-icon">
                <i class="fas fa-file-lines"></i>
            </span>
        </div>

        <div class="kpi-value">
            {{ $totalStudents }}
        </div>

        <div class="kpi-caption">
            Total annual examination records
        </div>

    </div>


    <div class="scholarship-kpi green">

        <div class="kpi-top">
            <span class="kpi-label">Eligible Students</span>

            <span class="kpi-icon">
                <i class="fas fa-circle-check"></i>
            </span>
        </div>

        <div class="kpi-value">
            {{ $eligibleStudents }}
        </div>

        <div class="kpi-caption">
            Students meeting scholarship criteria
        </div>

    </div>


    <div class="scholarship-kpi orange">

        <div class="kpi-top">
            <span class="kpi-label">Scholarship Assigned</span>

            <span class="kpi-icon">
                <i class="fas fa-award"></i>
            </span>
        </div>

        <div class="kpi-value">
            {{ $scholarshipAssigned }}
        </div>

        <div class="kpi-caption">
            Students with scholarship percentage
        </div>

    </div>


    <div class="scholarship-kpi red">

        <div class="kpi-top">
            <span class="kpi-label">Not Eligible</span>

            <span class="kpi-icon">
                <i class="fas fa-ban"></i>
            </span>
        </div>

        <div class="kpi-value">
            {{ $notEligible }}
        </div>

        <div class="kpi-caption">
            Students currently not eligible
        </div>

    </div>

</div>


{{-- =====================================================
     SEARCH & FILTER
====================================================== --}}

<div class="filter-panel">

    <div class="filter-heading">

        <div class="filter-title-wrap">

            <div class="filter-title-icon">
                <i class="fas fa-filter"></i>
            </div>

            <div>
                <div class="filter-title">
                    Search & Filter Annual Results
                </div>

                <div class="filter-subtitle">
                    Refine scholarship records by student, year, class or percentage
                </div>
            </div>

        </div>

        <div class="filter-badge">
            <i class="fas fa-sliders"></i>
            Advanced Filters
        </div>

    </div>


    <form
        method="GET"
        action="{{ route('admin.scholarship.merit.concession.index') }}"
    >

        <div class="filter-grid">

            <div class="filter-group">

                <label>Search Student</label>

                <div class="filter-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        class="filter-control"
                        placeholder="Name, Student ID or Roll No..."
                        value="{{ request('search') }}"
                    >

                </div>

            </div>


            <div class="filter-group">

                <label>Academic Year</label>

                <select
                    name="academic_year"
                    class="filter-control"
                >

                    <option value="">
                        All Years
                    </option>

                    @foreach($academicYears as $year)

                        <option
                            value="{{ $year }}"
                            @selected(request('academic_year') == $year)
                        >
                            {{ $year }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="filter-group">

                <label>Class</label>

                <select
                    name="class"
                    class="filter-control"
                >

                    <option value="">
                        All Classes
                    </option>

                    @foreach($classes as $class)

                        <option
                            value="{{ $class }}"
                            @selected(request('class') == $class)
                        >
                            {{ $class }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="filter-group">

                <label>Section</label>

                <select
                    name="section"
                    class="filter-control"
                >

                    <option value="">
                        All Sections
                    </option>

                    @foreach($sections as $section)

                        <option
                            value="{{ $section }}"
                            @selected(request('section') == $section)
                        >
                            {{ $section }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="filter-group">

                <label>Minimum Percentage</label>

                <input
                    type="number"
                    name="min_percentage"
                    class="filter-control"
                    min="0"
                    max="100"
                    step="0.01"
                    placeholder="e.g. 60"
                    value="{{ request('min_percentage') }}"
                >

            </div>


            <div class="filter-actions">

                <button
                    type="submit"
                    class="apply-btn"
                >
                    <i class="fas fa-filter"></i>
                    Apply
                </button>

            </div>

        </div>

    </form>

</div>


{{-- =====================================================
     SCHOLARSHIP TABLE
====================================================== --}}

<div class="table-panel">

    <div class="table-panel-header">

        <div class="table-panel-title-wrap">

            <div class="table-panel-icon">
                <i class="fas fa-award"></i>
            </div>

            <div>

                <div class="table-panel-title">

                    Annual Exam Scholarship

                    <span class="table-count">
                        {{ $results->total() }}
                    </span>

                </div>

                <div class="table-panel-subtitle">
                    Student-wise scholarship eligibility and allocation
                </div>

            </div>

        </div>


        <div class="table-header-actions">

            <a
                href="{{ route('admin.scholarship.merit.concession.index') }}"
                class="reset-btn"
            >
                <i class="fas fa-rotate-left"></i>
                Reset Filters
            </a>

        </div>

    </div>


    @if($results->count())

        <div class="table-responsive">

            <table class="scholarship-table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Academic Year</th>
                        <th>Annual Exam</th>
                        <th>Percentage</th>
                        <th>Scholarship</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($results as $result)

                        <tr>

                            <td>

                                <span class="serial-number">
                                    {{ $results->firstItem() + $loop->index }}
                                </span>

                            </td>


                            <td>

                                @if($result->student)

                                    <div class="student-cell">

                                        <div class="student-avatar">
                                            {{ strtoupper(substr($result->student->full_name, 0, 1)) }}
                                        </div>

                                        <div>

                                            <div class="student-name">
                                                {{ $result->student->full_name }}
                                            </div>

                                            <div class="student-id">

                                                ID:
                                                {{ $result->student->student_id }}

                                                @if($result->student->roll_number)

                                                    · Roll:
                                                    {{ $result->student->roll_number }}

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <span class="muted-text">
                                        Student Not Found
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="class-badge">

                                    <i class="fas fa-users"></i>

                                    {{ $result->class_name }}

                                    @if($result->section)
                                        - {{ $result->section }}
                                    @endif

                                </span>

                            </td>


                            <td>

                                <span class="year-text">
                                    {{ $result->academic_year }}
                                </span>

                            </td>


                            <td>

                                <span class="annual-badge">

                                    <i class="fas fa-graduation-cap"></i>

                                    {{ $result->exam->exam_name ?? 'Annual Exam' }}

                                </span>

                            </td>


                            <td>

                                <div class="percentage-wrapper">

                                    <span class="percentage-value">
                                        {{ number_format((float) $result->percentage, 2) }}%
                                    </span>

                                    <div class="percentage-bar">

                                        <div
                                            class="percentage-fill"
                                            style="width: {{ min((float) $result->percentage, 100) }}%;"
                                        ></div>

                                    </div>

                                </div>

                            </td>


                            <td>

                                @if($result->scholarship_percentage > 0)

                                    <span class="scholarship-badge eligible">

                                        <i class="fas fa-award"></i>

                                        {{ number_format($result->scholarship_percentage, 0) }}%

                                    </span>

                                @else

                                    <span class="scholarship-badge not-eligible">

                                        <i class="fas fa-ban"></i>

                                        Not Eligible

                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($result->scholarship_eligible)

                                    <span class="scholarship-badge eligible">
                                        <i class="fas fa-circle-check"></i>
                                        Eligible
                                    </span>

                                @else

                                    <span class="scholarship-badge not-eligible">
                                        <i class="fas fa-circle-xmark"></i>
                                        Not Eligible
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('admin.scholarship.merit.concession.show', $result) }}"
                                        class="action-btn"
                                        title="View Scholarship"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('admin.scholarship.merit.concession.edit', $result) }}"
                                        class="action-btn edit"
                                        title="Edit Scholarship"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        @if($results->hasPages())

            <div class="pagination-wrapper">
                {{ $results->links() }}
            </div>

        @endif


    @else

        <div class="empty-state">

            <div class="empty-icon">
                <i class="fas fa-award"></i>
            </div>

            <h4>No Annual Exam Results Found</h4>

            <p>
                Scholarship students will appear here when
                Annual Exam results are available.
            </p>

        </div>

    @endif

</div>

</div>

@endsection
