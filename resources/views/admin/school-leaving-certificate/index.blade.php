
@extends('layouts.app')

@section('title', 'School Leaving Certificates | Admin')

@section('content')

@php
    $certificateCollection = $certificates->getCollection();

    $totalCertificates = $certificates->total();
    $issuedCertificates = $certificateCollection->where('status', 'issued')->count();
    $draftCertificates = $certificateCollection->where('status', 'draft')->count();
    $cancelledCertificates = $certificateCollection->where('status', 'cancelled')->count();

    $searchTerm = trim($search ?? request('search', ''));
    $hasFilters = $searchTerm !== '';
@endphp

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — SCHOOL LEAVING CERTIFICATES
    ========================================================= */

    .slc-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 35px;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .slc-page *,
    .slc-page *::before,
    .slc-page *::after {
        box-sizing: border-box;
    }

    /* HERO */

    .slc-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
        flex-wrap: wrap;
        padding: 23px 25px;
        margin-bottom: 20px;
        overflow: hidden;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 13px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .slc-hero::after {
        content: "";
        position: absolute;
        right: -45px;
        top: -85px;
        width: 230px;
        height: 230px;
        border: 30px solid rgba(52, 119, 90, .035);
        border-radius: 50%;
        pointer-events: none;
    }

    .slc-hero-content {
        position: relative;
        z-index: 1;
        min-width: 240px;
    }

    .slc-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 13px;
        color: #849087;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .slc-breadcrumb a {
        color: #34775a;
        text-decoration: none;
    }

    .slc-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .slc-breadcrumb i {
        color: #b18a55;
        font-size: 9px;
    }

    .slc-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .slc-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 49px;
        width: 49px;
        height: 49px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 13px;
        font-size: 22px;
    }

    .slc-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -.45px;
    }

    .slc-subtitle {
        margin: 5px 0 0;
        color: #7a857d;
        font-size: 11px;
        line-height: 1.5;
    }

    .slc-hero-note {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 11px;
        color: #34775a;
        font-size: 10px;
        font-weight: 650;
    }

    .slc-hero-actions {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
    }

    /* BUTTONS */

    .slc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all .18s ease;
    }

    .slc-btn-primary {
        color: #fff;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .17);
    }

    .slc-btn-primary:hover {
        color: #fff;
        background: #245a43;
        border-color: #245a43;
        transform: translateY(-1px);
    }

    .slc-btn-light {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .slc-btn-light:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .slc-btn-sm {
        min-height: 31px;
        min-width: 32px;
        padding: 0 8px;
        font-size: 12px;
    }

    .slc-btn-view {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .slc-btn-edit {
        color: #87582d;
        background: #f8eee0;
        border-color: #d9b88f;
    }

    .slc-btn-print {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .slc-btn-delete {
        color: #8a3a34;
        background: #fff1ed;
        border-color: #e8b8b0;
    }

    .slc-btn-view:hover,
    .slc-btn-print:hover {
        background: #dce9d9;
        color: #245a43;
    }

    .slc-btn-edit:hover {
        background: #f0e0cc;
        color: #87582d;
    }

    .slc-btn-delete:hover {
        background: #fde4de;
        color: #8a3a34;
    }

    /* SUMMARY CARDS */

    .slc-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .slc-stat-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 17px 16px;
        overflow: hidden;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .04);
    }

    .slc-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 19px;
    }

    .slc-stat-total .slc-stat-icon {
        color: #34775a;
        background: #eaf3e9;
    }

    .slc-stat-issued .slc-stat-icon {
        color: #27734c;
        background: #e4f2e8;
    }

    .slc-stat-draft .slc-stat-icon {
        color: #95652e;
        background: #f8eee0;
    }

    .slc-stat-cancelled .slc-stat-icon {
        color: #a34740;
        background: #fff1ed;
    }

    .slc-stat-info {
        min-width: 0;
    }

    .slc-stat-label {
        display: block;
        margin-bottom: 4px;
        color: #78847b;
        font-size: 10px;
        font-weight: 650;
    }

    .slc-stat-value {
        display: block;
        color: #263b35;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
    }

    .slc-stat-caption {
        display: block;
        margin-top: 4px;
        color: #9a9e94;
        font-size: 9px;
    }

    /* ALERTS */

    .slc-alert {
        margin-bottom: 16px;
        padding: 13px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .slc-alert-success {
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
    }

    .slc-alert-danger {
        color: #8a3a34;
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        border-left: 4px solid #bd5148;
    }

    /* MAIN PANEL */

    .slc-panel {
        margin-bottom: 18px;
        overflow: hidden;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 13px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .slc-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(180deg, #fffefa, #faf8f0);
        border-bottom: 1px solid #e9e5d9;
    }

    .slc-panel-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 800;
    }

    .slc-panel-heading i {
        color: #34775a;
        font-size: 16px;
    }

    .slc-record-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    /* SEARCH */

    .slc-search-area {
        padding: 17px 20px;
        border-bottom: 1px solid #efecdf;
    }

    .slc-search-label {
        display: block;
        margin-bottom: 7px;
        color: #405149;
        font-size: 10px;
        font-weight: 750;
    }

    .slc-search-wrap {
        position: relative;
    }

    .slc-search-wrap > i {
        position: absolute;
        top: 50%;
        left: 13px;
        z-index: 1;
        color: #829083;
        font-size: 14px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .slc-search-input {
        width: 100%;
        height: 40px;
        padding: 0 13px 0 39px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        outline: none;
        font-family: inherit;
        font-size: 11px;
        transition: .18s ease;
    }

    .slc-search-input:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .slc-search-input::placeholder {
        color: #a0a397;
    }

    .slc-search-actions {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    .slc-search-actions .slc-btn-primary {
        flex: 1;
    }

    /* FILTER CHIP */

    .slc-filter-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 0 20px 15px;
    }

    .slc-filter-label {
        color: #818b81;
        font-size: 10px;
        font-weight: 700;
    }

    .slc-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 9px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }

    .slc-filter-chip a {
        color: #245a43;
        text-decoration: none;
    }

    /* TABLE */

    .slc-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .slc-table {
        width: 100%;
        min-width: 1050px;
        margin: 0;
        border-collapse: collapse;
    }

    .slc-table thead th {
        padding: 12px 13px;
        color: #637168;
        background: #f8f6ed;
        border-bottom: 1px solid #e5e1d3;
        font-size: 9px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .45px;
        white-space: nowrap;
    }

    .slc-table tbody td {
        padding: 12px 13px;
        color: #35463e;
        background: #fffefa;
        border-bottom: 1px solid #efecdf;
        font-size: 11px;
        vertical-align: middle;
    }

    .slc-table tbody tr:last-child td {
        border-bottom: none;
    }

    .slc-table tbody tr:hover td {
        background: #faf9f3;
    }

    .slc-number {
        color: #879087;
        font-weight: 650;
    }

    .slc-certificate-no {
        color: #245a43;
        font-weight: 800;
        white-space: nowrap;
    }

    .slc-student-name {
        color: #263b35;
        font-weight: 750;
    }

    .slc-student-id {
        display: inline-flex;
        padding: 4px 8px;
        color: #405149;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .slc-muted {
        color: #9a9e94;
    }

    .slc-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 800;
        white-space: nowrap;
    }

    .slc-status-issued {
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
    }

    .slc-status-draft {
        color: #87582d;
        background: #f8eee0;
        border: 1px solid #d9b88f;
    }

    .slc-status-cancelled {
        color: #8a3a34;
        background: #fff1ed;
        border: 1px solid #e8b8b0;
    }

    .slc-actions {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
    }

    /* FOOTER / PAGINATION */

    .slc-panel-footer {
        padding: 13px 20px;
        background: #faf8f0;
        border-top: 1px solid #e9e5d9;
    }

    .slc-panel-footer nav p {
        margin-bottom: 0;
    }

    .slc-panel-footer .pagination {
        margin-bottom: 0;
    }

    /* EMPTY STATE */

    .slc-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .slc-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        margin-bottom: 15px;
        color: #77877a;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 16px;
        font-size: 27px;
    }

    .slc-empty-title {
        margin: 0 0 7px;
        color: #263b35;
        font-size: 15px;
        font-weight: 800;
    }

    .slc-empty-text {
        margin: 0 0 17px;
        color: #7a857d;
        font-size: 11px;
        line-height: 1.6;
    }

    /* RESPONSIVE */

    @media (max-width: 991px) {
        .slc-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .slc-hero {
            align-items: stretch;
            flex-direction: column;
            padding: 18px 16px;
        }

        .slc-hero-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
        }

        .slc-hero-actions .slc-btn {
            width: 100%;
        }

        .slc-title {
            font-size: 19px;
        }

        .slc-title-icon {
            flex-basis: 43px;
            width: 43px;
            height: 43px;
            font-size: 19px;
        }

        .slc-search-area {
            padding: 15px;
        }

        .slc-search-actions {
            margin-top: 10px;
        }

        .slc-panel-header {
            padding: 14px 15px;
        }

        .slc-filter-row {
            padding: 0 15px 14px;
        }
    }

    @media (max-width: 480px) {
        .slc-stats-grid {
            gap: 9px;
        }

        .slc-stat-card {
            gap: 9px;
            padding: 12px 10px;
        }

        .slc-stat-icon {
            flex-basis: 36px;
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .slc-stat-value {
            font-size: 19px;
        }

        .slc-stat-label {
            font-size: 9px;
        }

        .slc-title {
            font-size: 17px;
        }

        .slc-subtitle {
            font-size: 10px;
        }

        .slc-hero-actions {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .slc-page *,
        .slc-page *::before,
        .slc-page *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

<div class="slc-page">

    {{-- =====================================================
         HERO HEADER
    ====================================================== --}}
    <div class="slc-hero">

        <div class="slc-hero-content">

            <div class="slc-breadcrumb">
                <a href="{{ route('admin.students.index') }}">Students</a>
                <i class="bi bi-chevron-right"></i>
                <span>Certificate Management</span>
                <i class="bi bi-chevron-right"></i>
                <span>School Leaving Certificates</span>
            </div>

            <div class="slc-title-row">
                <div class="slc-title-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>

                <div>
                    <h1 class="slc-title">School Leaving Certificates</h1>
                    <p class="slc-subtitle">
                        Manage, review and issue official school leaving certificates.
                    </p>
                </div>
            </div>

            <div class="slc-hero-note">
                <i class="bi bi-shield-check"></i>
                Official student records and certificate management
            </div>

        </div>

        <div class="slc-hero-actions">

            <a href="{{ url()->previous() }}"
               class="slc-btn slc-btn-light">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.school-leaving-certificate.create') }}"
               class="slc-btn slc-btn-primary">
                <i class="bi bi-plus-circle"></i>
                Create Certificate
            </a>

        </div>
    </div>

    {{-- =====================================================
         FLASH MESSAGES
    ====================================================== --}}
    @if(session('success'))
        <div class="slc-alert slc-alert-success">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="slc-alert slc-alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- =====================================================
         SUMMARY CARDS
         Issued / Draft / Cancelled counts are for current page.
    ====================================================== --}}
    <div class="slc-stats-grid">

        <div class="slc-stat-card slc-stat-total">
            <div class="slc-stat-icon">
                <i class="bi bi-files"></i>
            </div>
            <div class="slc-stat-info">
                <span class="slc-stat-label">Total Certificates</span>
                <strong class="slc-stat-value">{{ $totalCertificates }}</strong>
                <span class="slc-stat-caption">Matching all pages</span>
            </div>
        </div>

        <div class="slc-stat-card slc-stat-issued">
            <div class="slc-stat-icon">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="slc-stat-info">
                <span class="slc-stat-label">Issued</span>
                <strong class="slc-stat-value">{{ $issuedCertificates }}</strong>
                <span class="slc-stat-caption">On this page</span>
            </div>
        </div>

        <div class="slc-stat-card slc-stat-draft">
            <div class="slc-stat-icon">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div class="slc-stat-info">
                <span class="slc-stat-label">Draft</span>
                <strong class="slc-stat-value">{{ $draftCertificates }}</strong>
                <span class="slc-stat-caption">On this page</span>
            </div>
        </div>

        <div class="slc-stat-card slc-stat-cancelled">
            <div class="slc-stat-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="slc-stat-info">
                <span class="slc-stat-label">Cancelled</span>
                <strong class="slc-stat-value">{{ $cancelledCertificates }}</strong>
                <span class="slc-stat-caption">On this page</span>
            </div>
        </div>

    </div>

    {{-- =====================================================
         CERTIFICATE RECORDS PANEL
    ====================================================== --}}
    <div class="slc-panel">

        <div class="slc-panel-header">
            <h5 class="slc-panel-heading">
                <i class="bi bi-journal-text"></i>
                Certificate Records
            </h5>

            <span class="slc-record-count">
                <i class="bi bi-collection"></i>
                {{ $totalCertificates }} Records
            </span>
        </div>

        {{-- SEARCH --}}
        <div class="slc-search-area">
            <form method="GET"
                  action="{{ route('admin.school-leaving-certificate.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-9">
                        <label class="slc-search-label" for="slc-search">
                            Search Certificates
                        </label>

                        <div class="slc-search-wrap">
                            <i class="bi bi-search"></i>

                            <input type="text"
                                   id="slc-search"
                                   name="search"
                                   value="{{ $searchTerm }}"
                                   class="slc-search-input"
                                   placeholder="Search by certificate number, student name or student ID...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="slc-search-actions">

                            <button type="submit"
                                    class="slc-btn slc-btn-primary">
                                <i class="bi bi-search"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.school-leaving-certificate.index') }}"
                               class="slc-btn slc-btn-light"
                               title="Reset Search"
                               aria-label="Reset Search">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- ACTIVE FILTER --}}
        @if($hasFilters)
            <div class="slc-filter-row">
                <span class="slc-filter-label">Active filter:</span>

                <span class="slc-filter-chip">
                    <i class="bi bi-search"></i>
                    {{ $searchTerm }}

                    <a href="{{ route('admin.school-leaving-certificate.index') }}"
                       title="Remove search filter"
                       aria-label="Remove search filter">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                </span>
            </div>
        @endif

        {{-- =================================================
             CERTIFICATE TABLE
        ================================================== --}}
        @if($certificates->count() > 0)

            <div class="slc-table-wrap">
                <table class="slc-table">

                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Certificate No.</th>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Class</th>
                            <th>Issue Date</th>
                            <th>Leaving Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($certificates as $certificate)

                            @php
                                $student = $certificate->student;

                                $studentName = $student
                                    ? trim(implode(' ', array_filter([
                                        $student->first_name ?? '',
                                        $student->middle_name ?? '',
                                        $student->last_name ?? '',
                                    ])))
                                    : 'Student Deleted';

                                $status = strtolower($certificate->status ?? 'draft');
                            @endphp

                            <tr>

                                <td class="ps-4">
                                    <span class="slc-number">
                                        {{ $certificates->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <span class="slc-certificate-no">
                                        {{ $certificate->certificate_no ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    @if($student)
                                        <span class="slc-student-name">
                                            {{ $studentName ?: 'Unnamed Student' }}
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            Student Deleted
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($student && $student->student_id)
                                        <span class="slc-student-id">
                                            {{ $student->student_id }}
                                        </span>
                                    @else
                                        <span class="slc-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($student)
                                        {{ $student->class ?? '—' }}

                                        @if(!empty($student->section))
                                            <span class="slc-muted">
                                                - {{ $student->section }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="slc-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($certificate->issue_date)
                                        {{ \Illuminate\Support\Carbon::parse($certificate->issue_date)->format('d-m-Y') }}
                                    @else
                                        <span class="slc-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($certificate->leaving_date)
                                        <strong>
                                            {{ \Illuminate\Support\Carbon::parse($certificate->leaving_date)->format('d-m-Y') }}
                                        </strong>
                                    @else
                                        <span class="slc-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @switch($status)

                                        @case('issued')
                                            <span class="slc-status slc-status-issued">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Issued
                                            </span>
                                            @break

                                        @case('cancelled')
                                            <span class="slc-status slc-status-cancelled">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Cancelled
                                            </span>
                                            @break

                                        @default
                                            <span class="slc-status slc-status-draft">
                                                <i class="bi bi-clock-fill"></i>
                                                Draft
                                            </span>

                                    @endswitch
                                </td>

                                <td class="text-center">
                                    <div class="slc-actions">

                                        <a href="{{ route('admin.school-leaving-certificate.show', $certificate) }}"
                                           class="slc-btn slc-btn-sm slc-btn-view"
                                           title="View Certificate"
                                           aria-label="View Certificate">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.school-leaving-certificate.edit', $certificate) }}"
                                           class="slc-btn slc-btn-sm slc-btn-edit"
                                           title="Edit Certificate"
                                           aria-label="Edit Certificate">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="{{ route('admin.school-leaving-certificate.print', $certificate) }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="slc-btn slc-btn-sm slc-btn-print"
                                           title="Print Certificate"
                                           aria-label="Print Certificate">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        <button type="button"
                                                class="slc-btn slc-btn-sm slc-btn-delete"
                                                title="Delete Certificate"
                                                aria-label="Delete Certificate"
                                                onclick="confirmSlcDelete({{ $certificate->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>

                                    <form id="slc-delete-form-{{ $certificate->id }}"
                                          action="{{ route('admin.school-leaving-certificate.destroy', $certificate) }}"
                                          method="POST"
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            @if($certificates->hasPages())
                <div class="slc-panel-footer">
                    {{ $certificates->appends(request()->query())->links() }}
                </div>
            @endif

        @else

            {{-- EMPTY STATE --}}
            <div class="slc-empty">

                <div class="slc-empty-icon">
                    <i class="bi bi-file-earmark-x"></i>
                </div>

                <h5 class="slc-empty-title">
                    No School Leaving Certificates Found
                </h5>

                @if($hasFilters)

                    <p class="slc-empty-text">
                        No certificate matched your search for
                        <strong>"{{ $searchTerm }}"</strong>.
                        <br>
                        Try another certificate number, student name or student ID.
                    </p>

                    <a href="{{ route('admin.school-leaving-certificate.index') }}"
                       class="slc-btn slc-btn-light">
                        <i class="bi bi-x-circle"></i>
                        Clear Search
                    </a>

                @else

                    <p class="slc-empty-text">
                        No school leaving certificates have been created yet.
                        <br>
                        Create a certificate to get started.
                    </p>

                    <a href="{{ route('admin.school-leaving-certificate.create') }}"
                       class="slc-btn slc-btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Create First Certificate
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@push('scripts')
<script>
    function confirmSlcDelete(id) {
        const confirmed = confirm(
            'Are you sure you want to delete this School Leaving Certificate?'
        );

        if (!confirmed) {
            return;
        }

        const form = document.getElementById('slc-delete-form-' + id);

        if (form) {
            form.submit();
        }
    }
</script>
@endpush

@endsection
