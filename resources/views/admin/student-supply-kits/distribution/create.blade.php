
@extends('layouts.app')

@section('title', 'Distribute Supply Kits')

@push('styles')
<style>
    /* =========================================
       GREEN SCHOOL MANAGEMENT THEME
    ========================================= */

    .distribution-page {
        --kd-primary: #527f70;
        --kd-primary-dark: #3e6658;
        --kd-primary-light: #edf4ee;
        --kd-text: #263b35;
        --kd-muted: #7b857b;
        --kd-border: #e5e1d5;
        --kd-paper: #fffefa;
        --kd-background: #f5f3e9;
        color: var(--kd-text);
        padding-bottom: 35px;
    }

    .distribution-page *,
    .distribution-page *::before,
    .distribution-page *::after {
        box-sizing: border-box;
    }

    /* HERO */
    .kd-hero {
        position: relative;
        overflow: hidden;
        padding: 28px;
        margin-bottom: 24px;
        color: #fff;
        border-radius: 20px;
        background: linear-gradient(120deg, #344f43, #20352c);
        box-shadow: 0 12px 30px rgba(52, 79, 67, .14);
    }

    .kd-hero::after {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        right: -80px;
        top: -130px;
        border: 40px solid rgba(255,255,255,.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .kd-hero-content {
        position: relative;
        z-index: 1;
    }

    .kd-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 30px;
        background: rgba(255,255,255,.1);
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .7px;
        text-transform: uppercase;
    }

    .kd-hero h3 {
        margin: 14px 0 7px;
        font-size: clamp(23px, 3vw, 31px);
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .kd-hero p {
        margin: 0;
        color: rgba(255,255,255,.78);
        font-size: 14px;
        line-height: 1.7;
    }

    .kd-hero .btn {
        position: relative;
        z-index: 2;
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    /* FOUR STEPS */
    .kd-steps {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .kd-step {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        padding: 15px;
        background: var(--kd-paper);
        border: 1px solid var(--kd-border);
        border-radius: 14px;
        transition: .2s ease;
    }

    .kd-step.active {
        border-color: #a8c4b5;
        box-shadow: 0 4px 15px rgba(82,127,112,.10);
    }

    .kd-step-number {
        display: grid;
        place-items: center;
        flex: 0 0 37px;
        width: 37px;
        height: 37px;
        border-radius: 11px;
        background: var(--kd-primary-light);
        color: var(--kd-primary);
        font-weight: 800;
    }

    .kd-step-title {
        font-size: 12px;
        font-weight: 800;
        line-height: 1.5;
    }

    .kd-step-subtitle {
        margin-top: 3px;
        color: var(--kd-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    /* CARDS */
    .distribution-page .card {
        overflow: hidden;
        margin-bottom: 22px;
        border: 1px solid var(--kd-border);
        border-radius: 16px;
        background: var(--kd-paper);
        box-shadow: 0 3px 12px rgba(38,59,53,.035);
    }

    .distribution-page .card-header {
        padding: 18px 22px;
        background: var(--kd-paper);
        border-bottom: 1px solid #e5e9df;
    }

    .distribution-page .card-body {
        padding: 23px;
    }

    .distribution-page .card-title {
        margin: 0;
        color: var(--kd-text);
        font-size: 16px;
        font-weight: 800;
    }

    .kd-section-icon {
        display: inline-grid;
        place-items: center;
        width: 35px;
        height: 35px;
        margin-right: 9px;
        border-radius: 10px;
        background: var(--kd-primary-light);
        color: var(--kd-primary);
        vertical-align: middle;
    }

    .kd-section-description {
        margin: 8px 0 0 44px;
        color: var(--kd-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    /* FORMS */
    .distribution-page .form-label {
        display: block;
        margin-bottom: 8px;
        color: #39483e;
        font-size: 13px;
        font-weight: 700;
    }

    .distribution-page .form-control,
    .distribution-page .form-select {
        min-height: 45px;
        border: 1px solid #d9e2d9;
        border-radius: 10px;
        background-color: #fff;
        color: var(--kd-text);
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }

    .distribution-page .form-control:focus,
    .distribution-page .form-select:focus {
        border-color: #83a995;
        box-shadow: 0 0 0 4px rgba(82,127,112,.12);
    }

    .distribution-page .input-group-text {
        border-color: #d9e2d9;
        color: var(--kd-muted);
    }

    .distribution-page .input-group-text:first-child {
        border-radius: 10px 0 0 10px;
    }

    .distribution-page .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .distribution-page .btn {
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        transition: transform .15s, box-shadow .15s, background .15s;
    }

    .distribution-page .btn:active {
        transform: translateY(1px);
    }

    .distribution-page .btn-primary {
        background: var(--kd-primary);
        border-color: var(--kd-primary);
        color: #fff;
        box-shadow: 0 3px 8px rgba(82,127,112,.15);
    }

    .distribution-page .btn-primary:hover,
    .distribution-page .btn-primary:focus {
        background: var(--kd-primary-dark);
        border-color: var(--kd-primary-dark);
        color: #fff;
    }

    .distribution-page .btn-outline-primary {
        color: var(--kd-primary);
        border-color: var(--kd-primary);
    }

    .distribution-page .btn-outline-primary:hover {
        background: var(--kd-primary);
        border-color: var(--kd-primary);
        color: #fff;
    }

    .distribution-page .btn-outline-secondary {
        color: #59675e;
        border-color: #cbd5cb;
    }

    .distribution-page .btn-outline-secondary:hover {
        background: #edf1e9;
        border-color: #aebcaf;
        color: var(--kd-text);
    }

    .distribution-page .required::after {
        content: " *";
        color: #b95b50;
    }

    /* FILTER INFORMATION */
    .kd-filter-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        border: 1px solid #d6e5d9;
        border-radius: 11px;
        background: #f0f6f0;
        color: #365b48;
        font-size: 12px;
        line-height: 1.7;
    }

    .kd-filter-note i {
        margin-top: 1px;
        font-size: 17px;
    }

    /* STUDENT SUMMARY */
    .distribution-page .student-count {
        color: var(--kd-muted);
        font-size: 12px;
    }

    .distribution-page .student-count strong {
        color: var(--kd-primary);
        font-size: 15px;
    }

    .kd-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .kd-summary {
        padding: 16px;
        border: 1px solid var(--kd-border);
        border-radius: 12px;
        background: #f7f8f2;
    }

    .kd-summary-label {
        color: var(--kd-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .kd-summary-value {
        margin-top: 5px;
        color: var(--kd-text);
        font-size: 25px;
        font-weight: 800;
        line-height: 1.2;
    }

    .kd-summary-value.primary {
        color: var(--kd-primary);
    }

    /* TABLES */
    .distribution-page .table-responsive {
        border: 1px solid var(--kd-border);
        border-radius: 11px;
    }

    .distribution-page .table {
        margin-bottom: 0;
    }

    .distribution-page .table thead th {
        padding: 14px 13px;
        background: #f1f4ed;
        border-bottom: 1px solid var(--kd-border);
        color: #4b5c50;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .distribution-page .table tbody td {
        padding: 13px;
        color: #39483e;
        font-size: 13px;
        vertical-align: middle;
    }

    .distribution-page .table-hover tbody tr:hover > * {
        background-color: #f3f7f1;
    }

    .distribution-page .form-check-input {
        width: 17px;
        height: 17px;
        cursor: pointer;
        border-color: #bdcbbd;
    }

    .distribution-page .form-check-input:checked {
        background-color: var(--kd-primary);
        border-color: var(--kd-primary);
    }

    .distribution-page .student-name {
        color: var(--kd-text);
        font-size: 13px;
        font-weight: 750;
    }

    .distribution-page .student-id {
        margin-top: 3px;
        color: var(--kd-muted);
        font-size: 11px;
    }

    .distribution-page .already-issued > * {
        background: #fff8e8;
    }

    .distribution-page .badge-issued {
        display: inline-flex;
        align-items: center;
        padding: 6px 9px;
        border-radius: 20px;
        background: #fef3c7;
        color: #92400e;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .distribution-page .badge.bg-success-subtle {
        padding: 6px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 750;
    }

    /* EMPTY / LOADING */
    .distribution-page .empty-state {
        padding: 45px 20px;
        color: var(--kd-muted);
        text-align: center;
    }

    .distribution-page .empty-state > i {
        display: inline-grid;
        place-items: center;
        width: 65px;
        height: 65px;
        margin-bottom: 14px;
        border-radius: 20px;
        background: #edf2eb;
        color: #879b8a;
        font-size: 29px;
    }

    .distribution-page .empty-state h6 {
        color: var(--kd-text);
        font-weight: 750;
    }

    .distribution-page .empty-state p {
        font-size: 13px;
        line-height: 1.7;
    }

    .distribution-page .loading-box {
        padding: 38px 20px;
        color: var(--kd-muted);
        text-align: center;
        font-size: 13px;
    }

    .distribution-page .loading-box .spinner-border {
        width: 30px;
        height: 30px;
        color: var(--kd-primary) !important;
    }

    /* KIT DETAILS */
    .distribution-page .kit-details {
        padding: 20px;
        border: 1px solid #d9e5d9;
        border-radius: 13px;
        background: #f7faf5;
    }

    .distribution-page .kit-name {
        color: var(--kd-text);
        font-size: 20px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .distribution-page .kit-meta {
        color: var(--kd-muted);
        font-size: 12px;
        line-height: 1.9;
    }

    .distribution-page .kit-meta strong {
        color: #39483e;
    }

    .kd-kit-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        min-height: 44px;
        margin-top: 5px;
        padding: 8px;
        border-radius: 12px;
        background: #e3eee5;
        color: #365f4b;
        font-size: 20px;
        font-weight: 800;
    }

    /* STICKY ACTION */
    .kd-action-wrap {
        padding: 12px;
        border: 1px solid var(--kd-border);
        border-radius: 13px;
        background: #f7f8f2;
    }

    .distribution-page .action-bar {
        position: sticky;
        bottom: 15px;
        z-index: 20;
        padding: 17px 19px;
        border: 1px solid #d9e2d9;
        border-radius: 13px;
        background: rgba(255,254,250,.97);
        box-shadow: 0 8px 25px rgba(38,59,53,.09);
        backdrop-filter: blur(10px);
    }

    .kd-action-title {
        color: var(--kd-text);
        font-size: 14px;
        font-weight: 800;
    }

    .kd-action-title .text-primary {
        color: var(--kd-primary) !important;
    }

    .kd-action-subtitle {
        margin-top: 4px;
        color: var(--kd-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .distribution-page button:focus-visible,
    .distribution-page a:focus-visible {
        outline: 3px solid rgba(82,127,112,.35);
        outline-offset: 3px;
    }

    @media (max-width: 991px) {
        .kd-steps {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .distribution-page {
            padding-top: 16px !important;
        }

        .kd-hero {
            padding: 21px;
            border-radius: 15px;
        }

        .kd-hero h3 {
            font-size: 23px;
        }

        .kd-hero .btn {
            width: 100%;
        }

        .kd-steps {
            gap: 8px;
        }

        .kd-step {
            padding: 11px;
            gap: 8px;
        }

        .kd-step-number {
            width: 32px;
            height: 32px;
            flex-basis: 32px;
            border-radius: 9px;
        }

        .kd-step-title {
            font-size: 12px;
        }

        .kd-step-subtitle {
            font-size: 10px;
        }

        .distribution-page .card-header {
            padding: 15px;
        }

        .distribution-page .card-body {
            padding: 16px;
        }

        .distribution-page .table {
            min-width: 820px;
        }

        .kd-summary-grid {
            gap: 8px;
        }

        .kd-summary {
            padding: 13px;
        }

        .kd-summary-value {
            font-size: 22px;
        }

        .distribution-page .action-bar {
            bottom: 8px;
            padding: 14px;
        }

        #distributeBtn {
            width: 100%;
            margin-top: 10px;
        }

        #selectAllBtn,
        #unselectAllBtn {
            padding: 8px 10px;
        }
    }

    @media (max-width: 380px) {
        .kd-steps {
            grid-template-columns: 1fr;
        }
    }

    
/* =========================================
   GREEN DROPDOWN THEME
========================================= */

.distribution-page .form-select {
    border-color: #d9e2d9;
    color: #263b35;
    background-color: #fffefa;
    accent-color: #527f70;
    cursor: pointer;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.distribution-page .form-select:hover {
    border-color: #83a995;
}

.distribution-page .form-select:focus {
    border-color: #527f70 !important;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(82, 127, 112, .16) !important;
}

/* Dropdown options */
.distribution-page .form-select option {
    background-color: #fffefa;
    color: #263b35;
}

.distribution-page .form-select option:checked {
    background-color: #dcebe0;
    color: #264d3d;
    font-weight: 600;
}

/* Selected dropdown text */
.distribution-page .form-select:valid {
    color: #263b35;
}

/* Keyboard focus */
.distribution-page .form-select:focus-visible {
    outline: 2px solid #527f70;
    outline-offset: 2px;
}

/* Green checkbox and selection accents */
.distribution-page input[type="checkbox"] {
    accent-color: #527f70;
}

.distribution-page .form-check-input:focus {
    border-color: #83a995;
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .15);
}

</style>
@endpush

@section('content')
<div class="container-fluid py-4 distribution-page">

    {{-- PAGE HEADER --}}
    <div class="kd-hero">
        <div class="kd-hero-content">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                <div class="flex-grow-1">
                    <span class="kd-eyebrow">
                        <i class="bi bi-box-seam"></i>
                        Student Supply Management
                    </span>

                    <h3>
                        <i class="bi bi-box-seam me-2"></i>
                        Distribute Supply Kits
                    </h3>

                    <p>
                        Select eligible students, choose a kit and record the distribution.
                    </p>
                </div>

                
<div class="d-flex flex-wrap gap-2">
    {{-- BACK TO INDEX --}}
    <a href="{{ route('admin.student-supply-kits.index') }}"
       class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Index
    </a>

    {{-- DISTRIBUTION HISTORY --}}
    <a href="{{ route('admin.student-supply-kits.history') }}"
       class="btn btn-light">
        <i class="bi bi-clock-history me-1"></i>
        Distribution History
    </a>
</div>
            </div>
        </div>
    </div>

    {{-- FOUR-STEP GUIDE --}}
    <div class="kd-steps">
        <div class="kd-step active">
            <div class="kd-step-number">1</div>
            <div>
                <div class="kd-step-title">Filter Students</div>
                <div class="kd-step-subtitle">Year, class and section</div>
            </div>
        </div>

        <div class="kd-step">
            <div class="kd-step-number">2</div>
            <div>
                <div class="kd-step-title">Select Students</div>
                <div class="kd-step-subtitle">Choose eligible students</div>
            </div>
        </div>

        <div class="kd-step">
            <div class="kd-step-number">3</div>
            <div>
                <div class="kd-step-title">Choose Supply Kit</div>
                <div class="kd-step-subtitle">Review kit contents</div>
            </div>
        </div>

        <div class="kd-step">
            <div class="kd-step-number">4</div>
            <div>
                <div class="kd-step-title">Confirm Distribution</div>
                <div class="kd-step-subtitle">Save the distribution</div>
            </div>
        </div>
    </div>

    {{-- STEP 1: FILTER STUDENTS --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="kd-section-icon">
                    <i class="bi bi-funnel"></i>
                </span>
                1. Select Students
            </h5>
            <p class="kd-section-description">
                Choose the academic year and class before loading the student list.
            </p>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="academicYear" class="form-label required">
                        Academic Year
                    </label>
                    <select id="academicYear" class="form-select">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="classSelect" class="form-label required">Class</label>
                    <select id="classSelect" class="form-select">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="sectionSelect" class="form-label">Section</label>
                    <select id="sectionSelect" class="form-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="kd-filter-note mt-4">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>Before you continue</strong><br>
                    Academic year and class are required. Section is optional.
                    Students are loaded only when you click the button below.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="button" id="loadStudentsBtn" class="btn btn-primary px-4">
                    <i class="bi bi-search me-1"></i>
                    Load Students
                </button>

                <button type="button" id="clearFiltersBtn" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    {{-- STEP 2: STUDENT LIST --}}
    <div class="card d-none" id="studentsCard">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="card-title">
                        <span class="kd-section-icon">
                            <i class="bi bi-people"></i>
                        </span>
                        2. Select Students
                    </h5>
                    <div class="student-count mt-2">
                        Students found: <strong id="studentCount">0</strong>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-check2-square me-1"></i>
                        Select All
                    </button>
                    <button type="button" id="unselectAllBtn" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-square me-1"></i>
                        Unselect All
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 align-items-end mb-4">
                <div class="col-lg-7">
                    <label for="studentSearch" class="form-label">Search Students</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               id="studentSearch"
                               class="form-control"
                               placeholder="Search by name, student ID, class or section...">
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="kd-summary-grid mb-0">
                        <div class="kd-summary">
                            <div class="kd-summary-label">Students Found</div>
                            <div class="kd-summary-value" id="studentCountSummary">0</div>
                        </div>
                        <div class="kd-summary">
                            <div class="kd-summary-label">Selected Students</div>
                            <div class="kd-summary-value primary" id="selectedStudentCount">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="studentsLoading" class="loading-box d-none">
                <div class="spinner-border mb-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Loading students...</div>
            </div>

            <div id="studentsEmpty" class="empty-state d-none">
                <i class="bi bi-people"></i>
                <h6>No students found</h6>
                <p class="mb-0">No active students match the selected filters.</p>
            </div>

            <div class="table-responsive d-none" id="studentsTableWrapper">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="masterCheckbox"
                                   aria-label="Select all visible students">
                        </th>
                        <th width="60">#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Academic Year</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="studentsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- STEP 3: KIT AND DISTRIBUTION DETAILS --}}
    <div class="card d-none" id="distributionCard">
        <div class="card-header">
            <h5 class="card-title">
                <span class="kd-section-icon">
                    <i class="bi bi-box-seam"></i>
                </span>
                3. Select Kit & Distribution Details
            </h5>
            <p class="kd-section-description">
                Choose the kit and verify its contents before confirming distribution.
            </p>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="kitTemplateId" class="form-label required">Kit Template</label>
                    
<select id="kitTemplateId"
        name="kit_template_id"
        class="form-select"
        required>
    <option value="">Select Kit</option>

    @foreach($kitTemplates as $kit)
        <option value="{{ $kit->id }}"
                data-year="{{ $kit->academic_year }}"
                data-class="{{ $kit->class }}"
                data-scheme="{{ $kit->scheme?->scheme_name ?? '' }}"
                data-description="{{ $kit->description ?? '' }}">
            {{ $kit->kit_name }}
            @if($kit->class)
                - {{ $kit->class }}
            @endif
            @if($kit->academic_year)
                - {{ $kit->academic_year }}
            @endif
        </option>
    @endforeach
</select>

                    <small class="text-muted d-block mt-2">
                        Only kits matching the selected class and academic year are shown.
                    </small>
                </div>

                <div class="col-lg-3">
                    <label for="distributionDate" class="form-label required">
                        Distribution Date
                    </label>
                    <input type="date" id="distributionDate" class="form-control"
                           value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="col-lg-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <input type="text" id="remarks" class="form-control"
                           placeholder="Optional remarks">
                </div>
            </div>

            <div class="kit-details mt-4 d-none" id="kitDetailsCard">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1">
                        <div class="kit-name" id="selectedKitName">-</div>
                        <div class="kit-meta mt-2">
                            Scheme: <strong id="selectedKitScheme">-</strong>
                        </div>
                        <div class="kit-meta">
                            Academic Year: <strong id="selectedKitYear">-</strong>
                        </div>
                        <div class="kit-meta">
                            Class: <strong id="selectedKitClass">-</strong>
                        </div>
                    </div>

                    <div class="text-md-end">
                        <div class="kit-meta">Items in this kit</div>
                        <div class="kd-kit-count" id="kitItemCount">0</div>
                    </div>
                </div>

                <div class="mt-3" id="selectedKitDescriptionWrapper">
                    <div class="kit-meta mb-1">Description</div>
                    <div id="selectedKitDescription">-</div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-bordered align-middle">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th width="130">Code</th>
                            <th width="100">Unit</th>
                            <th width="100">Quantity</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody id="kitItemsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- STEP 4: DISTRIBUTION ACTION --}}
    <div class="card d-none" id="actionCard">
        <div class="card-body">
            <div class="action-bar">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="kd-action-title">
                            <i class="bi bi-clipboard-check me-2"></i>
                            Ready to distribute?
                        </div>
                        <div class="kd-action-subtitle">
                            Selected students:
                            <strong id="finalSelectedCount">0</strong>
                            <span class="mx-1">·</span>
                            Check the selected kit and date before submitting.
                        </div>
                    </div>

                    <button type="button" id="distributeBtn" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-box-seam me-1"></i>
                        Distribute Selected Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- HIDDEN DISTRIBUTION FORM --}}
    <form method="POST"
          action="{{ route('admin.student-supply-kits.distribution.store') }}"
          id="distributionForm"
          class="d-none">
        @csrf
        <input type="hidden" name="academic_year" id="formAcademicYear">
        <input type="hidden" name="class" id="formClass">
        <input type="hidden" name="section" id="formSection">
        <input type="hidden" name="kit_template_id" id="formKitTemplateId">
        <input type="hidden" name="distribution_date" id="formDistributionDate">
        <input type="hidden" name="remarks" id="formRemarks">
        <div id="selectedStudentInputs"></div>
    </form>
</div>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const $ = id => document.getElementById(id);

    const academicYear = $('academicYear');
    const classSelect = $('classSelect');
    const sectionSelect = $('sectionSelect');
    const loadStudentsBtn = $('loadStudentsBtn');
    const clearFiltersBtn = $('clearFiltersBtn');

    const studentsCard = $('studentsCard');
    const distributionCard = $('distributionCard');
    const actionCard = $('actionCard');
    const studentsLoading = $('studentsLoading');
    const studentsEmpty = $('studentsEmpty');
    const studentsTableWrapper = $('studentsTableWrapper');
    const studentsTableBody = $('studentsTableBody');

    const studentSearch = $('studentSearch');
    const studentCount = $('studentCount');
    const studentCountSummary = $('studentCountSummary');
    const selectedStudentCount = $('selectedStudentCount');
    const finalSelectedCount = $('finalSelectedCount');

    const selectAllBtn = $('selectAllBtn');
    const unselectAllBtn = $('unselectAllBtn');
    const masterCheckbox = $('masterCheckbox');

    const kitTemplateId = $('kitTemplateId');
    const distributionDate = $('distributionDate');
    const remarks = $('remarks');

    const kitDetailsCard = $('kitDetailsCard');
    const selectedKitName = $('selectedKitName');
    const selectedKitScheme = $('selectedKitScheme');
    const selectedKitYear = $('selectedKitYear');
    const selectedKitClass = $('selectedKitClass');
    const selectedKitDescription = $('selectedKitDescription');
    const kitItemCount = $('kitItemCount');
    const kitItemsBody = $('kitItemsBody');

    const distributeBtn = $('distributeBtn');
    const distributionForm = $('distributionForm');
    const selectedStudentInputs = $('selectedStudentInputs');

    let studentsData = [];
    let requestSequence = 0;

    const studentsUrl = @json(
        route('admin.student-supply-kits.distribution.students')
    );

    /* =====================================================
       SAFE HTML
    ===================================================== */

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* =====================================================
       SAFE JSON RESPONSE
    ===================================================== */

    async function parseJsonResponse(response) {
        const raw = await response.text();
        let data;

        try {
            // Remove UTF-8 BOM and leading whitespace before parsing.
            data = JSON.parse(raw.replace(/^[\s\uFEFF]+/, ''));
        } catch (error) {
            console.error('Invalid JSON response:', raw);

            throw new Error(
                'Invalid server response. Check the students endpoint in Laravel.'
            );
        }

        if (!response.ok) {
            throw new Error(
                data.message || 'Request failed with status ' + response.status
            );
        }

        return data;
    }

    /* =====================================================
       NOTIFICATIONS
    ===================================================== */

    function showError(message) {
        let box = $('distributionError');

        if (!box) {
            box = document.createElement('div');
            box.id = 'distributionError';
            box.className = 'alert alert-danger';
            box.setAttribute('role', 'alert');

            const firstCard = document.querySelector('.distribution-page .card');

            if (firstCard) {
                firstCard.parentNode.insertBefore(box, firstCard);
            } else {
                document.querySelector('.distribution-page')
                    .prepend(box);
            }
        }

        box.textContent = message;
        box.classList.remove('d-none');
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError() {
        const box = $('distributionError');

        if (box) {
            box.textContent = '';
            box.classList.add('d-none');
        }
    }

    /* =====================================================
       BUTTON LOADING STATE
    ===================================================== */

    function setLoading(loading) {
        loadStudentsBtn.disabled = loading;

        loadStudentsBtn.innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm me-2"></span>Loading Students...'
            : '<i class="bi bi-search me-1"></i>Load Students';
    }

    /* =====================================================
       FILTER KIT OPTIONS
    ===================================================== */

    function filterKitOptions() {
        const year = (academicYear.value || '').trim();
        const selectedClass = (classSelect.value || '').trim();

        Array.from(kitTemplateId.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            const optionYear = (option.dataset.year || '').trim();
            const optionClass = (option.dataset.class || '').trim();

            const yearMatches =
                !year || !optionYear ||
                optionYear.toLowerCase() === year.toLowerCase();

            const classMatches =
                !selectedClass || !optionClass ||
                optionClass.toLowerCase() === selectedClass.toLowerCase();

            option.hidden = !(yearMatches && classMatches);
        });

        const selectedOption =
            kitTemplateId.options[kitTemplateId.selectedIndex];

        if (selectedOption && selectedOption.hidden) {
            kitTemplateId.value = '';
            renderKitDetails(null);
        }
    }

    /* =====================================================
       STUDENT SELECTION COUNTS
    ===================================================== */

    function getCheckboxes() {
        return Array.from(
            studentsTableBody.querySelectorAll('.student-checkbox')
        );
    }

    function getSelectedIds() {
        return getCheckboxes()
            .filter(checkbox => checkbox.checked && !checkbox.disabled)
            .map(checkbox => String(checkbox.value));
    }

    function updateSelectedCount() {
        const count = getSelectedIds().length;

        selectedStudentCount.textContent = count;
        finalSelectedCount.textContent = count;

        updateMasterCheckbox();
    }

    function updateMasterCheckbox() {
        const visible = getCheckboxes().filter(function (checkbox) {
            return !checkbox.disabled &&
                checkbox.closest('tr').style.display !== 'none';
        });

        const checked = visible.filter(checkbox => checkbox.checked);

        masterCheckbox.checked =
            visible.length > 0 && checked.length === visible.length;

        masterCheckbox.indeterminate =
            checked.length > 0 && checked.length < visible.length;

        masterCheckbox.disabled = visible.length === 0;
    }

    /* =====================================================
       RESET RESULTS
    ===================================================== */

    function resetResults() {
        requestSequence++;

        studentsData = [];
        studentsTableBody.innerHTML = '';

        studentsCard.classList.add('d-none');
        distributionCard.classList.add('d-none');
        actionCard.classList.add('d-none');

        studentsLoading.classList.add('d-none');
        studentsEmpty.classList.add('d-none');
        studentsTableWrapper.classList.add('d-none');

        studentCount.textContent = '0';
        studentCountSummary.textContent = '0';

        studentSearch.value = '';
        masterCheckbox.checked = false;
        masterCheckbox.indeterminate = false;

        kitTemplateId.value = '';
        renderKitDetails(null);

        updateSelectedCount();
    }

    /* =====================================================
       RENDER STUDENTS
    ===================================================== */

    function renderStudents(students, previouslySelected = []) {
        studentsTableBody.innerHTML = '';

        studentCount.textContent = students.length;
        studentCountSummary.textContent = students.length;

        if (!students.length) {
            studentsTableWrapper.classList.add('d-none');
            studentsEmpty.classList.remove('d-none');

            studentsEmpty.innerHTML = `
                <i class="bi bi-people"></i>
                <h6>No students found</h6>
                <p class="mb-0">
                    No students match the selected academic year and class.
                </p>
            `;

            updateSelectedCount();
            return;
        }

        studentsEmpty.classList.add('d-none');
        studentsTableWrapper.classList.remove('d-none');

        students.forEach(function (student, index) {
            const id = student.id ?? student.student_id;

            if (id === undefined || id === null) {
                console.warn('Student without an ID:', student);
                return;
            }

            const alreadyIssued =
                student.already_issued === true ||
                student.already_issued === 1 ||
                student.already_issued === '1';

            const row = document.createElement('tr');

            if (alreadyIssued) {
                row.classList.add('already-issued');
            }

            const isSelected = previouslySelected.includes(String(id));

            row.innerHTML = `
                <td>
                    <input
                        type="checkbox"
                        class="form-check-input student-checkbox"
                        value="${escapeHtml(id)}"
                        aria-label="Select ${escapeHtml(student.name || 'student')}"
                        ${alreadyIssued ? 'disabled' : ''}
                        ${isSelected && !alreadyIssued ? 'checked' : ''}
                    >
                </td>

                <td>${index + 1}</td>

                <td>
                    <div class="student-name">
                        ${escapeHtml(student.name || student.full_name || '-')}
                    </div>
                    <div class="student-id">
                        ${escapeHtml(student.admission_number || student.student_code || '-')}
                    </div>
                </td>

                <td>
                    ${escapeHtml(student.admission_number || student.student_code || student.student_id || '-')}
                </td>

                <td>${escapeHtml(student.class || student.class_name || classSelect.value || '-')}</td>

                <td>${escapeHtml(student.section || student.section_name || '-')}</td>

                <td>${escapeHtml(student.academic_year || academicYear.value || '-')}</td>

                <td>
                    ${
                        alreadyIssued
                            ? `<span class="badge-issued">
                                   <i class="bi bi-check-circle me-1"></i>
                                   Already Issued
                               </span>`
                            : `<span class="badge bg-success-subtle text-success">
                                   Available
                               </span>`
                    }
                </td>
            `;

            studentsTableBody.appendChild(row);
        });

        applyStudentSearch();
        updateSelectedCount();
    }

    /* =====================================================
       SEARCH STUDENTS
    ===================================================== */

    function applyStudentSearch() {
        const term = studentSearch.value.trim().toLowerCase();

        getCheckboxes().forEach(function (checkbox) {
            const row = checkbox.closest('tr');

            row.style.display =
                !term || row.textContent.toLowerCase().includes(term)
                    ? ''
                    : 'none';
        });

        updateMasterCheckbox();
    }

    /* =====================================================
       LOAD STUDENTS BUTTON
    ===================================================== */

    async function loadStudents(preserveSelection = false) {
        clearError();

        const year = academicYear.value.trim();
        const selectedClass = classSelect.value.trim();
        const section = sectionSelect.value.trim();
        const kitId = kitTemplateId.value;

        if (!year) {
            showError('Please select an academic year.');
            academicYear.focus();
            return;
        }

        if (!selectedClass) {
            showError('Please select a class.');
            classSelect.focus();
            return;
        }

        const selectedBefore = preserveSelection ? getSelectedIds() : [];
        const currentRequest = ++requestSequence;

        studentsCard.classList.remove('d-none');
        studentsLoading.classList.remove('d-none');
        studentsEmpty.classList.add('d-none');
        studentsTableWrapper.classList.add('d-none');

        distributionCard.classList.add('d-none');
        actionCard.classList.add('d-none');

        studentsTableBody.innerHTML = '';
        setLoading(true);

        try {
            const params = new URLSearchParams({
                academic_year: year,
                class: selectedClass
            });

            if (section) {
                params.set('section', section);
            }

            if (kitId) {
                params.set('kit_template_id', kitId);
            }

            const response = await fetch(
                studentsUrl + '?' + params.toString(),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }
            );

            const data = await parseJsonResponse(response);

            // Ignore responses from an older request.
            if (currentRequest !== requestSequence) {
                return;
            }

            if (data.success === false) {
                throw new Error(data.message || 'Unable to load students.');
            }

            if (!Array.isArray(data.students)) {
                throw new Error(
                    'The students endpoint must return a students array.'
                );
            }

            studentsData = data.students;

            renderStudents(studentsData, selectedBefore);

            distributionCard.classList.remove('d-none');
            actionCard.classList.remove('d-none');

            if (kitId) {
                const kit = data.kit_template || null;

                if (kit) {
                    renderKitDetails(kit);
                } else {
                    renderKitDetailsFromOption();
                }
            }

        } catch (error) {
            if (currentRequest !== requestSequence) {
                return;
            }

            console.error('Load students failed:', error);

            studentsEmpty.classList.remove('d-none');
            studentsTableWrapper.classList.add('d-none');

            studentsEmpty.innerHTML = `
                <i class="bi bi-exclamation-circle text-danger"></i>
                <h6>Unable to load students</h6>
                <p class="mb-0">${escapeHtml(error.message)}</p>
            `;

            showError(error.message || 'Unable to load students.');
        } finally {
            if (currentRequest === requestSequence) {
                studentsLoading.classList.add('d-none');
                setLoading(false);
            }
        }
    }

    /* =====================================================
       RENDER KIT DETAILS
    ===================================================== */

    function renderKitDetails(kit) {
        kitItemsBody.innerHTML = '';

        if (!kit) {
            kitDetailsCard.classList.add('d-none');

            selectedKitName.textContent = '-';
            selectedKitScheme.textContent = '-';
            selectedKitYear.textContent = '-';
            selectedKitClass.textContent = '-';
            selectedKitDescription.textContent = '-';
            kitItemCount.textContent = '0';

            return;
        }

        kitDetailsCard.classList.remove('d-none');

        selectedKitName.textContent = kit.kit_name || kit.name || '-';
        selectedKitScheme.textContent =
            kit.scheme_name || kit.scheme?.scheme_name || '-';
        selectedKitYear.textContent =
            kit.academic_year || academicYear.value || '-';
        selectedKitClass.textContent =
            kit.class || kit.class_name || classSelect.value || '-';
        selectedKitDescription.textContent = kit.description || '-';

        const items = Array.isArray(kit.items) ? kit.items : [];

        kitItemCount.textContent = items.length;

        if (!items.length) {
            kitItemsBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        No kit items were returned by the server.
                    </td>
                </tr>
            `;
            return;
        }

        items.forEach(function (item) {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td><strong>${escapeHtml(item.item_name || item.name || '-')}</strong></td>
                <td>${escapeHtml(item.item_code || item.code || '-')}</td>
                <td>${escapeHtml(item.unit || '-')}</td>
                <td>${escapeHtml(item.quantity ?? item.pivot?.quantity ?? 0)}</td>
                <td>${escapeHtml(item.remarks || '-')}</td>
            `;

            kitItemsBody.appendChild(row);
        });
    }

    function renderKitDetailsFromOption() {
        const option = kitTemplateId.options[kitTemplateId.selectedIndex];

        if (!option || !option.value) {
            renderKitDetails(null);
            return;
        }

        renderKitDetails({
            kit_name: option.textContent.trim(),
            scheme_name: option.dataset.scheme || '-',
            academic_year: option.dataset.year || academicYear.value,
            class: option.dataset.class || classSelect.value,
            description: option.dataset.description || '',
            items: []
        });
    }

    /* =====================================================
       FILTER CHOICES
    ===================================================== */

    function filterKitOptions() {
        const year = academicYear.value.trim();
        const selectedClass = classSelect.value.trim();

        Array.from(kitTemplateId.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            const optionYear = (option.dataset.year || '').trim();
            const optionClass = (option.dataset.class || '').trim();

            const yearMatches =
                !year || !optionYear ||
                optionYear.toLowerCase() === year.toLowerCase();

            const classMatches =
                !selectedClass || !optionClass ||
                optionClass.toLowerCase() === selectedClass.toLowerCase();

            option.hidden = !(yearMatches && classMatches);
        });

        const selectedOption =
            kitTemplateId.options[kitTemplateId.selectedIndex];

        if (selectedOption && selectedOption.hidden) {
            kitTemplateId.value = '';
            renderKitDetails(null);
        }
    }

    /* =====================================================
       LOAD BUTTON EVENT
    ===================================================== */

    loadStudentsBtn.addEventListener('click', function (event) {
        event.preventDefault();
        loadStudents(false);
    });

    /* =====================================================
       CLEAR FILTERS
    ===================================================== */

    clearFiltersBtn.addEventListener('click', function () {
        academicYear.value = '';
        classSelect.value = '';
        sectionSelect.value = '';

        studentSearch.value = '';

        resetResults();
        filterKitOptions();
        clearError();
    });

    /* =====================================================
       FILTER CHANGES
    ===================================================== */

    academicYear.addEventListener('change', function () {
        clearError();
        resetResults();
        filterKitOptions();
    });

    classSelect.addEventListener('change', function () {
        clearError();
        resetResults();
        filterKitOptions();
    });

    sectionSelect.addEventListener('change', function () {
        clearError();
        resetResults();
    });

    /* =====================================================
       STUDENT SEARCH EVENT
    ===================================================== */

    studentSearch.addEventListener('input', applyStudentSearch);

    /* =====================================================
       SELECT ALL VISIBLE STUDENTS
    ===================================================== */

    selectAllBtn.addEventListener('click', function () {
        getCheckboxes().forEach(function (checkbox) {
            const row = checkbox.closest('tr');

            if (!checkbox.disabled && row.style.display !== 'none') {
                checkbox.checked = true;
            }
        });

        updateSelectedCount();
    });

    /* =====================================================
       UNSELECT ALL STUDENTS
    ===================================================== */

    unselectAllBtn.addEventListener('click', function () {
        getCheckboxes().forEach(function (checkbox) {
            checkbox.checked = false;
        });

        updateSelectedCount();
    });

    /* =====================================================
       MASTER CHECKBOX
    ===================================================== */

    masterCheckbox.addEventListener('change', function () {
        getCheckboxes().forEach(function (checkbox) {
            const row = checkbox.closest('tr');

            if (!checkbox.disabled && row.style.display !== 'none') {
                checkbox.checked = masterCheckbox.checked;
            }
        });

        updateSelectedCount();
    });

    /* =====================================================
       KIT CHANGE
    ===================================================== */

    kitTemplateId.addEventListener('change', function () {
        clearError();

        if (!kitTemplateId.value) {
            renderKitDetails(null);
            return;
        }

        // Refresh the student list for the chosen kit.
        // Existing selections are preserved where students remain eligible.
        if (!studentsCard.classList.contains('d-none')) {
            loadStudents(true);
        } else {
            renderKitDetailsFromOption();
        }
    });

    /* =====================================================
       DISTRIBUTE STUDENTS
    ===================================================== */

    distributeBtn.addEventListener('click', function () {
        clearError();

        const selectedIds = getSelectedIds();

        if (!selectedIds.length) {
            showError('Please select at least one student.');
            return;
        }

        if (!academicYear.value) {
            showError('Please select an academic year.');
            academicYear.focus();
            return;
        }

        if (!classSelect.value) {
            showError('Please select a class.');
            classSelect.focus();
            return;
        }

        if (!kitTemplateId.value) {
            showError('Please select a supply kit.');
            kitTemplateId.focus();
            return;
        }

        if (!distributionDate.value) {
            showError('Please select a distribution date.');
            distributionDate.focus();
            return;
        }

        const kitOption =
            kitTemplateId.options[kitTemplateId.selectedIndex];

        const kitName = kitOption ? kitOption.textContent.trim() : 'selected kit';

        const confirmation = confirm(
            'Confirm supply kit distribution?\n\n' +
            'Kit: ' + kitName + '\n' +
            'Students: ' + selectedIds.length + '\n' +
            'Date: ' + distributionDate.value + '\n\n' +
            'Please ensure the selection is correct before continuing.'
        );

        if (!confirmation) {
            return;
        }

        // Fill the hidden form fields.
        $('formAcademicYear').value = academicYear.value;
        $('formClass').value = classSelect.value;
        $('formSection').value = sectionSelect.value;
        $('formKitTemplateId').value = kitTemplateId.value;
        $('formDistributionDate').value = distributionDate.value;
        $('formRemarks').value = remarks.value.trim();

        // Rebuild student inputs to avoid submitting stale IDs.
        selectedStudentInputs.innerHTML = '';

        selectedIds.forEach(function (id) {
            const input = document.createElement('input');

            input.type = 'hidden';
            input.name = 'student_ids[]';
            input.value = id;

            selectedStudentInputs.appendChild(input);
        });

        distributeBtn.disabled = true;
        distributeBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

        distributionForm.submit();
    });

    /* =====================================================
       INITIALIZATION
    ===================================================== */

    filterKitOptions();
    updateSelectedCount();

});
</script>

@endpush
