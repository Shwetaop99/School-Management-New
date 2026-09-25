@extends('layouts.app')

@section('title', 'Transport Records | Admin')

@section('content')

<style>

/* =========================================================
   TRANSPORT RECORDS — PREMIUM SCHOOL ERP UI
========================================================= */

.transport-records-page {
    width: 100%;
    min-height: calc(100vh - 60px);
    padding: 26px 28px 40px;
    background: #f4f7fb;
    color: #172033;
}

/* =========================================================
   HERO
========================================================= */

.transport-hero {
    position: relative;
    overflow: hidden;
    min-height: 165px;
    margin-bottom: 22px;
    padding: 28px 32px;
    border-radius: 18px;
    background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
    color: #fff;
    box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.transport-hero::before {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    right: -65px;
    top: -130px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.transport-hero::after {
    content: "";
    position: absolute;
    width: 145px;
    height: 145px;
    right: 120px;
    bottom: -100px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.hero-content {
    position: relative;
    z-index: 3;
}

.hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 9px;
    color: rgba(255,255,255,.78);
    font-size: 11px;
    font-weight: 600;
}

.hero-breadcrumb i {
    font-size: 9px;
}

.hero-content h1 {
    margin: 0 0 7px;
    font-size: 29px;
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -.3px;
}

.hero-content p {
    margin: 0;
    max-width: 650px;
    color: rgba(255,255,255,.91);
    font-size: 13px;
    line-height: 1.6;
}

.hero-icon-wrap {
    position: relative;
    z-index: 3;
    width: 105px;
    height: 105px;
    margin-right: 30px;
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 28px;
    background: rgba(255,255,255,.10);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-icon-wrap i {
    font-size: 53px;
    color: rgba(255,255,255,.88);
}

/* =========================================================
   SUMMARY CARDS
========================================================= */

.transport-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 22px;
}

.summary-card {
    position: relative;
    overflow: hidden;
    min-height: 125px;
    padding: 19px 20px;
    border-radius: 15px;
    color: #fff;
    box-shadow: 0 8px 22px rgba(15,23,42,.11);
    transition: .25s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 28px rgba(15,23,42,.16);
}

.summary-card::before {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: -38px;
    top: -50px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.summary-card.blue {
    background: linear-gradient(135deg,#1769d1,#237de0);
}

.summary-card.orange {
    background: linear-gradient(135deg,#ed9208,#f7aa25);
}

.summary-card.cyan {
    background: linear-gradient(135deg,#079dbd,#16b5d0);
}

.summary-card.green {
    background: linear-gradient(135deg,#16a34a,#22c55e);
}

.summary-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.summary-label {
    margin-bottom: 7px;
    color: rgba(255,255,255,.88);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.summary-number {
    margin: 0;
    font-size: 28px;
    line-height: 1;
    font-weight: 800;
}

.summary-icon {
    position: relative;
    z-index: 2;
    width: 39px;
    height: 39px;
    border-radius: 11px;
    background: rgba(255,255,255,.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.summary-footer {
    position: relative;
    z-index: 2;
    margin-top: 14px;
    color: rgba(255,255,255,.82);
    font-size: 11px;
}

/* =========================================================
   ALERT
========================================================= */

.transport-alert {
    margin-bottom: 18px;
    padding: 12px 15px;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    background: #f0fdf4;
    color: #15803d;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 650;
}

/* =========================================================
   MAIN SECTION
========================================================= */

.records-section {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15,23,42,.06);
}

.records-section-header {
    padding: 20px 22px 18px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-heading-icon {
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #dbeafe;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.section-heading h2 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.section-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.record-count {
    padding: 7px 12px;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

/* =========================================================
   FILTER BAR
========================================================= */

.records-filter-bar {
    padding: 16px 22px;
    background: #fbfcfe;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.filter-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.search-box {
    position: relative;
    width: 320px;
    min-width: 320px;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #1769d1;
    font-size: 14px;
    z-index: 2;
    pointer-events: none;
}

.search-box input {
    width: 100%;
    height: 42px;
    padding: 0 14px 0 40px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    outline: none;
    background: #f8fafc;
    color: #172033;
    font-size: 12.5px;
    font-weight: 500;
    transition: all .25s ease;
    box-sizing: border-box;
}

.search-box input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-box input:hover {
    background: #fff;
    border-color: #cbd5e1;
}

.search-box input:focus {
    background: #fff;
    border-color: #8bb8ef;
    box-shadow:
        0 0 0 3px rgba(23,105,209,.08),
        0 4px 12px rgba(15,23,42,.05);
}

.filter-select {
    height: 42px;
    min-width: 140px;
    padding: 0 12px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    outline: none;
    background: #fff;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s ease;
}

.filter-select:hover {
    border-color: #cbd5e1;
}

.filter-select:focus {
    border-color: #8bb8ef;
    box-shadow: 0 0 0 3px rgba(23,105,209,.08);
}

.filter-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 42px;
    padding: 0 13px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    transition: .2s ease;
    white-space: nowrap;
}

.filter-reset-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1769d1;
}

.search-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 42px;
    padding: 0 16px;
    border: 0;
    border-radius: 11px;
    background: #1769d1;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 5px 13px rgba(23,105,209,.20);
    transition: .2s ease;
    white-space: nowrap;
    cursor: pointer;
}

.search-btn:hover {
    background: #1268ca;
    transform: translateY(-1px);
}

.add-record-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 42px;
    padding: 0 17px;
    border: 0;
    border-radius: 11px;
    background: #1769d1;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 5px 13px rgba(23,105,209,.20);
    transition: .2s ease;
    white-space: nowrap;
}

.add-record-btn:hover {
    background: #1268ca;
    color: #fff;
    transform: translateY(-1px);
}

/* =========================================================
   TABLE
========================================================= */

.records-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.records-table {
    width: 100%;
    min-width: 1120px;
    border-collapse: collapse;
}

.records-table thead th {
    padding: 13px 17px;
    background: #f8fafc;
    border-bottom: 1px solid #e5ebf3;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.records-table tbody td {
    padding: 14px 17px;
    border-bottom: 1px solid #edf1f6;
    color: #475569;
    font-size: 12px;
    vertical-align: middle;
}

.records-table tbody tr {
    transition: .18s ease;
}

.records-table tbody tr:hover {
    background: #f8fbff;
}

.records-table tbody tr:last-child td {
    border-bottom: 0;
}

/* =========================================================
   STUDENT
========================================================= */

.student-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.student-avatar {
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    border-radius: 10px;
    background: linear-gradient(135deg,#dbeafe,#eff6ff);
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
}

.student-details strong {
    display: block;
    margin-bottom: 3px;
    color: #172033;
    font-size: 12px;
    font-weight: 750;
}

.student-details span {
    color: #94a3b8;
    font-size: 10px;
}

/* =========================================================
   ROUTE / VEHICLE
========================================================= */

.info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
}

.info-cell-icon {
    width: 31px;
    height: 31px;
    flex-shrink: 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.route-icon {
    background: #fff7ed;
    color: #ea580c;
}

.vehicle-icon {
    background: #ecfeff;
    color: #079dbd;
}

.info-cell-text strong {
    display: block;
    color: #172033;
    font-size: 11px;
    font-weight: 750;
}

.info-cell-text span {
    display: block;
    margin-top: 2px;
    color: #94a3b8;
    font-size: 9px;
}

/* =========================================================
   LOCATION
========================================================= */

.location-cell {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #475569;
    white-space: nowrap;
}

.location-cell i {
    color: #1769d1;
    font-size: 12px;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.status-badge.active {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #dc2626;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* =========================================================
   DATE
========================================================= */

.date-cell strong {
    display: block;
    color: #334155;
    font-size: 11px;
    font-weight: 700;
}

.date-cell span {
    display: block;
    margin-top: 2px;
    color: #94a3b8;
    font-size: 9px;
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
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 11px;
    transition: .2s ease;
    cursor: pointer;
}

.action-btn.view {
    color: #1769d1;
}

.action-btn.edit {
    color: #ea580c;
}

.action-btn.delete {
    color: #dc2626;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-btn.view:hover {
    background: #edf5ff;
    border-color: #bfdbfe;
}

.action-btn.edit:hover {
    background: #fff7ed;
    border-color: #fed7aa;
}

.action-btn.delete:hover {
    background: #fef2f2;
    border-color: #fecaca;
}

/* =========================================================
   PROFESSIONAL PAGINATION
========================================================= */

.pagination-wrapper {
    padding: 17px 22px;
    border-top: 1px solid #edf1f6;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.pagination-info {
    color: #64748b;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}

.pagination-info strong {
    color: #172033;
    font-weight: 800;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 5px;
}

.pagination-btn,
.pagination-number,
.pagination-dots {
    min-width: 35px;
    height: 35px;
    padding: 0 9px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    transition: all .2s ease;
}

.pagination-btn {
    gap: 5px;
}

.pagination-btn:hover,
.pagination-number:hover {
    background: #edf5ff;
    border-color: #bfdbfe;
    color: #1769d1;
    transform: translateY(-1px);
}

.pagination-number.active {
    border-color: #1769d1;
    background: #1769d1;
    color: #fff;
    box-shadow: 0 4px 11px rgba(23,105,209,.20);
}

.pagination-dots {
    border-color: transparent;
    background: transparent;
    cursor: default;
    color: #94a3b8;
    min-width: 25px;
    padding: 0 3px;
}

.pagination-disabled {
    background: #f8fafc;
    color: #cbd5e1;
    border-color: #edf1f6;
    cursor: not-allowed;
}

.pagination-disabled:hover {
    background: #f8fafc;
    color: #cbd5e1;
    border-color: #edf1f6;
    transform: none;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 75px 25px 80px;
    text-align: center;
}

.empty-icon-wrapper {
    position: relative;
    width: 82px;
    height: 82px;
    margin: 0 auto 19px;
}

.empty-icon-bg {
    position: absolute;
    inset: 0;
    border-radius: 23px;
    background: linear-gradient(135deg,#dbeafe,#e0f2fe);
    transform: rotate(6deg);
}

.empty-icon {
    position: relative;
    width: 82px;
    height: 82px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #dbeafe;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    box-shadow: 0 8px 20px rgba(23,105,209,.10);
}

.empty-state h3 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 18px;
    font-weight: 800;
}

.empty-state p {
    max-width: 470px;
    margin: 0 auto 21px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {
    .transport-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .hero-icon-wrap {
        margin-right: 10px;
    }
}

@media (max-width: 1000px) {
    .records-filter-bar {
        align-items: stretch;
        flex-direction: column;
    }

    .filter-left {
        width: 100%;
    }

    .search-box {
        flex: 1;
        width: auto;
        min-width: 0;
    }

    .search-btn,
    .add-record-btn {
        width: 100%;
    }
}

@media (max-width: 850px) {
    .transport-records-page {
        padding: 18px;
    }

    .transport-hero {
        padding: 24px;
    }

    .hero-icon-wrap {
        display: none;
    }

    .records-section-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .filter-left {
        flex-wrap: wrap;
    }

    .pagination-wrapper {
        align-items: center;
        flex-direction: column;
    }

    .pagination-info {
        text-align: center;
    }

    .pagination-controls {
        justify-content: center;
        flex-wrap: wrap;
    }
}

@media (max-width: 600px) {
    .transport-summary-grid {
        grid-template-columns: 1fr;
    }

    .transport-hero {
        min-height: 145px;
    }

    .hero-content h1 {
        font-size: 24px;
    }

    .filter-left {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        width: 100%;
        min-width: 100%;
    }

    .filter-select,
    .filter-reset-btn,
    .search-btn,
    .add-record-btn {
        width: 100%;
    }

    .pagination-controls {
        width: 100%;
        gap: 4px;
    }

    .pagination-btn {
        padding: 0 8px;
    }

    .pagination-btn span {
        display: none;
    }

    .pagination-number {
        min-width: 33px;
    }
}

</style>

<div class="transport-records-page">

{{-- =====================================================
     HERO
====================================================== --}}

<div class="transport-hero">
    <div class="hero-content">
        <div class="hero-breadcrumb">
            <span>Transport Management</span>
            <i class="bi bi-chevron-right"></i>
            <span>Transport Records</span>
        </div>

        <h1>Transport Records</h1>

        <p>
            Manage student transportation assignments, routes,
            vehicles, pickup points, drop points and transport status
            from one place.
        </p>
    </div>

    <div class="hero-icon-wrap">
        <i class="bi bi-person-vcard-fill"></i>
    </div>
</div>

{{-- =====================================================
     SUCCESS MESSAGE
====================================================== --}}

@if(session('success'))
    <div class="transport-alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>
            {{ session('success') }}
        </span>
    </div>
@endif

{{-- =====================================================
     SUMMARY
====================================================== --}}

<div class="transport-summary-grid">

    {{-- TOTAL --}}
    <div class="summary-card blue">
        <div class="summary-top">
            <div>
                <div class="summary-label">
                    Total Records
                </div>

                <div class="summary-number">
                    {{ number_format($totalRecords) }}
                </div>
            </div>

            <div class="summary-icon">
                <i class="bi bi-list-check"></i>
            </div>
        </div>

        <div class="summary-footer">
            All transport assignments
        </div>
    </div>

    {{-- ACTIVE --}}
    <div class="summary-card green">
        <div class="summary-top">
            <div>
                <div class="summary-label">
                    Active
                </div>

                <div class="summary-number">
                    {{ number_format($activeRecords) }}
                </div>
            </div>

            <div class="summary-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <div class="summary-footer">
            Currently using transport
        </div>
    </div>

    {{-- ASSIGNED --}}
    <div class="summary-card orange">
        <div class="summary-top">
            <div>
                <div class="summary-label">
                    Assigned Students
                </div>

                <div class="summary-number">
                    {{ number_format($assignedStudents) }}
                </div>
            </div>

            <div class="summary-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <div class="summary-footer">
            Students linked to transport
        </div>
    </div>

    {{-- INACTIVE --}}
    <div class="summary-card cyan">
        <div class="summary-top">
            <div>
                <div class="summary-label">
                    Inactive
                </div>

                <div class="summary-number">
                    {{ number_format($inactiveRecords) }}
                </div>
            </div>

            <div class="summary-icon">
                <i class="bi bi-pause-circle-fill"></i>
            </div>
        </div>

        <div class="summary-footer">
            Inactive transport assignments
        </div>
    </div>

</div>

{{-- =====================================================
     MAIN RECORDS SECTION
====================================================== --}}

<div class="records-section">

    {{-- =================================================
         SECTION HEADER
    ================================================== --}}

    <div class="records-section-header">
        <div class="section-heading">

            <div class="section-heading-icon">
                <i class="bi bi-person-vcard-fill"></i>
            </div>

            <div>
                <h2>
                    Student Transport Assignments
                </h2>

                <p>
                    View, manage and update student transport details.
                </p>
            </div>

        </div>

        <div class="record-count">
            {{ number_format($records->total()) }} Records
        </div>
    </div>

    {{-- =================================================
         FILTER BAR
    ================================================== --}}

    <form
        action="{{ route('admin.transport.records.index') }}"
        method="GET"
        class="records-filter-bar"
    >

        <div class="filter-left">

            {{-- SEARCH --}}
            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search student, ID, route or vehicle..."
                >

            </div>

            {{-- STATUS --}}
            <select
                class="filter-select"
                name="status"
                onchange="this.form.submit()"
            >

                <option value="">
                    All Status
                </option>

                <option
                    value="active"
                    {{ request('status') === 'active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="inactive"
                    {{ request('status') === 'inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

            {{-- RESET --}}
            @if(request('search') || request('status'))

                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="filter-reset-btn"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

            @endif

        </div>

        {{-- SEARCH BUTTON --}}
        <button
            type="submit"
            class="search-btn"
        >
            <i class="bi bi-search"></i>
            Search
        </button>

        {{-- ADD BUTTON --}}
        <a
            href="{{ route('admin.transport.records.create') }}"
            class="add-record-btn"
        >
            <i class="bi bi-plus-lg"></i>
            Add Transport Record
        </a>

    </form>

    {{-- =================================================
         TABLE
    ================================================== --}}

    @if($records->count() > 0)

        <div class="records-table-wrapper">

            <table class="records-table">

                <thead>
                    <tr>
                        <th>#</th>

                        <th>
                            Student
                        </th>

                        <th>
                            Route
                        </th>

                        <th>
                            Vehicle
                        </th>

                        <th>
                            Pickup Point
                        </th>

                        <th>
                            Drop Point
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Start Date
                        </th>

                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($records as $record)

                        <tr>

                            {{-- NUMBER --}}
                            <td>
                                <strong style="color:#94a3b8;">
                                    {{ ($records->firstItem() ?? 0) + $loop->index }}
                                </strong>
                            </td>

                            {{-- STUDENT --}}
                            <td>

                                <div class="student-info">

                                    <div class="student-avatar">

                                        @if($record->student)

                                            {{ strtoupper(
                                                substr(
                                                    $record->student->first_name ?? 'S',
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        @else

                                            ?

                                        @endif

                                    </div>

                                    <div class="student-details">

                                        <strong>
                                            {{ $record->student->full_name ?? 'Student Not Assigned' }}
                                        </strong>

                                        <span>
                                            Student ID:
                                            {{ $record->student->student_id ?? 'N/A' }}
                                        </span>

                                    </div>

                                </div>

                            </td>

                            {{-- ROUTE --}}
                            <td>

                                <div class="info-cell">

                                    <div class="info-cell-icon route-icon">
                                        <i class="bi bi-signpost-split-fill"></i>
                                    </div>

                                    <div class="info-cell-text">

                                        <strong style="white-space: nowrap;">
                                            {{ $record->route ?? 'Not Assigned' }}
                                        </strong>

                                        <span>
                                            Transport Route
                                        </span>

                                    </div>

                                </div>

                            </td>

                            {{-- VEHICLE --}}
                            <td>

                                <div class="info-cell">

                                    <div class="info-cell-icon vehicle-icon">
                                        <i class="bi bi-bus-front-fill"></i>
                                    </div>

                                    <div class="info-cell-text">

                                        {{-- FIX: VEHICLE NUMBER STAYS ON ONE LINE --}}
                                        <strong style="white-space: nowrap;">
                                            {{ $record->vehicle ?? 'Not Assigned' }}
                                        </strong>

                                        <span style="white-space: nowrap;">
                                            School Vehicle
                                        </span>

                                    </div>

                                </div>

                            </td>

                            {{-- PICKUP --}}
                            <td>

                                <div class="location-cell">

                                    <i class="bi bi-geo-alt-fill"></i>

                                    <span>
                                        {{ $record->pickup_point ?? '—' }}
                                    </span>

                                </div>

                            </td>

                            {{-- DROP --}}
                            <td>

                                <div class="location-cell">

                                    <i class="bi bi-geo-fill"></i>

                                    <span>
                                        {{ $record->drop_point ?? '—' }}
                                    </span>

                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($record->transport_status === 'active')

                                    <span class="status-badge active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="status-badge inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>

                            {{-- DATE --}}
                            <td>

                                <div class="date-cell">

                                    @if($record->start_date)

                                        <strong>
                                            {{ $record->start_date->format('d M Y') }}
                                        </strong>

                                        <span>
                                            Start Date
                                        </span>

                                    @else

                                        <strong style="color:#94a3b8;">
                                            —
                                        </strong>

                                    @endif

                                </div>

                            </td>

                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route(
                                            'admin.transport.records.show',
                                            ['transportRecord' => $record->id]
                                        ) }}"
                                        class="action-btn view"
                                        title="View Record"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route(
                                            'admin.transport.records.edit',
                                            ['transportRecord' => $record->id]
                                        ) }}"
                                        class="action-btn edit"
                                        title="Edit Record"
                                    >
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route(
                                            'admin.transport.records.destroy',
                                            ['transportRecord' => $record->id]
                                        ) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this transport record?'
                                        );"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn delete"
                                            title="Delete Record"
                                        >
                                            <i class="bi bi-trash-fill"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- =================================================
             PROFESSIONAL PAGINATION
        ================================================== --}}

        @if($records->total() > 0)

            <div class="pagination-wrapper">

                {{-- PAGINATION INFORMATION --}}
                <div class="pagination-info">

                    Showing

                    <strong>
                        {{ $records->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $records->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $records->total() }}
                    </strong>

                    transport records

                </div>

                {{-- PAGINATION CONTROLS --}}
                @if($records->hasPages())

                    <div class="pagination-controls">

                        {{-- PREVIOUS --}}
                        @if($records->onFirstPage())

                            <span class="pagination-btn pagination-disabled">

                                <i class="bi bi-chevron-left"></i>

                                <span>Previous</span>

                            </span>

                        @else

                            <a
                                href="{{ $records->previousPageUrl() }}"
                                class="pagination-btn"
                            >

                                <i class="bi bi-chevron-left"></i>

                                <span>Previous</span>

                            </a>

                        @endif

                        {{-- FIRST PAGE --}}
                        @if($records->currentPage() > 3)

                            <a
                                href="{{ $records->url(1) }}"
                                class="pagination-number"
                            >
                                1
                            </a>

                            @if($records->currentPage() > 4)

                                <span class="pagination-dots">
                                    ...
                                </span>

                            @endif

                        @endif

                        {{-- PAGE NUMBERS --}}
                        @foreach(
                            range(
                                max(1, $records->currentPage() - 2),
                                min(
                                    $records->lastPage(),
                                    $records->currentPage() + 2
                                )
                            ) as $page
                        )

                            @if($page == $records->currentPage())

                                <span class="pagination-number active">
                                    {{ $page }}
                                </span>

                            @else

                                <a
                                    href="{{ $records->url($page) }}"
                                    class="pagination-number"
                                >
                                    {{ $page }}
                                </a>

                            @endif

                        @endforeach

                        {{-- LAST PAGE --}}
                        @if($records->currentPage() < $records->lastPage() - 2)

                            @if($records->currentPage() < $records->lastPage() - 3)

                                <span class="pagination-dots">
                                    ...
                                </span>

                            @endif

                            <a
                                href="{{ $records->url($records->lastPage()) }}"
                                class="pagination-number"
                            >
                                {{ $records->lastPage() }}
                            </a>

                        @endif

                        {{-- NEXT --}}
                        @if($records->hasMorePages())

                            <a
                                href="{{ $records->nextPageUrl() }}"
                                class="pagination-btn"
                            >

                                <span>Next</span>

                                <i class="bi bi-chevron-right"></i>

                            </a>

                        @else

                            <span class="pagination-btn pagination-disabled">

                                <span>Next</span>

                                <i class="bi bi-chevron-right"></i>

                            </span>

                        @endif

                    </div>

                @endif

            </div>

        @endif

    @else

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}

        <div class="empty-state">

            <div class="empty-icon-wrapper">

                <div class="empty-icon-bg"></div>

                <div class="empty-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

            </div>

            @if(request('search') || request('status'))

                <h3>
                    No Matching Records
                </h3>

                <p>
                    We couldn't find any transport record matching
                    your search or selected status.
                </p>

                <a
                    href="{{ route('admin.transport.records.index') }}"
                    class="add-record-btn"
                >

                    <i class="bi bi-arrow-counterclockwise"></i>

                    Clear Filters

                </a>

            @else

                <h3>
                    No Transport Records Yet
                </h3>

                <p>
                    There are currently no student transport assignments.
                    Add your first transport record to start managing
                    student transportation.
                </p>

                <a
                    href="{{ route('admin.transport.records.create') }}"
                    class="add-record-btn"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add First Transport Record

                </a>

            @endif

        </div>

    @endif

</div>

</div>

@endsection