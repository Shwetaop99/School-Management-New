@extends('layouts.app')

@section('title', 'Student Supply Kits')

@section('content')

<style>
    .supply-page {
        --primary: #1677f0;
        --primary-dark: #0d5fc7;
        --soft-blue: #eef6ff;
        --border: #e8edf3;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --success: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
        --secondary: #6c757d;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .page-subtitle {
        margin: 4px 0 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .header-actions .btn {
        border-radius: 8px;
        font-weight: 600;
    }

    .filter-card,
    .class-card,
    .student-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .filter-card {
        padding: 18px;
        margin-bottom: 22px;
    }

    .filter-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 14px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        min-height: 40px;
        border-radius: 8px;
        border-color: #dfe5ec;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.15rem rgba(22, 119, 240, 0.12);
    }

    /* Class Cards */

    .class-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .class-card {
        display: block;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition: all 0.2s ease;
        height: 100%;
    }

    .class-card:hover {
        transform: translateY(-3px);
        border-color: rgba(22, 119, 240, 0.35);
        box-shadow: 0 8px 24px rgba(22, 119, 240, 0.10);
        color: inherit;
    }

    .class-card-header {
        padding: 18px 18px 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .class-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--soft-blue);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .class-name {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .class-total {
        margin-top: 3px;
        color: var(--text-muted);
        font-size: 13px;
    }

    .class-arrow {
        color: #9ca3af;
        font-size: 18px;
    }

    .class-card-body {
        padding: 8px 18px 18px;
    }

    .class-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    .class-stat {
        background: #f8fafc;
        border-radius: 8px;
        padding: 9px 6px;
        text-align: center;
    }

    .class-stat-number {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.2;
    }

    .class-stat-label {
        margin-top: 3px;
        font-size: 10px;
        color: var(--text-muted);
        white-space: nowrap;
    }

    .stat-issued .class-stat-number {
        color: var(--success);
    }

    .stat-pending .class-stat-number {
        color: var(--warning);
    }

    .stat-cancelled .class-stat-number {
        color: var(--danger);
    }

    .stat-not-assigned .class-stat-number {
        color: var(--secondary);
    }

    .class-card-footer {
        border-top: 1px solid var(--border);
        padding: 11px 18px;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
    }

    /* Selected Class */

    .selected-class-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .selected-class-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .back-button {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: #fff;
        color: #374151;
        text-decoration: none;
    }

    .back-button:hover {
        background: #f8fafc;
        color: var(--primary);
    }

    .selected-class-title {
        margin: 0;
        font-size: 21px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .selected-class-subtitle {
        margin: 3px 0 0;
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Table */

    .student-card {
        overflow: hidden;
    }

    .table-header {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .table-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .table-count {
        font-size: 13px;
        color: var(--text-muted);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .student-table {
        width: 100%;
        margin: 0;
        min-width: 950px;
    }

    .student-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        color: #4b5563;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        padding: 13px 14px;
        white-space: nowrap;
    }

    .student-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        font-size: 13px;
        color: #374151;
    }

    .student-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .student-table tbody tr:hover {
        background: #fbfdff;
    }

    .student-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .student-id {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .kit-name {
        font-weight: 600;
        color: #374151;
    }

    .kit-scheme {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* Status */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-issued {
        color: #146c43;
        background: #d1e7dd;
    }

    .status-pending {
        color: #8a5a00;
        background: #fff3cd;
    }

    .status-cancelled {
        color: #b02a37;
        background: #f8d7da;
    }

    .status-not-assigned {
        color: #495057;
        background: #e9ecef;
    }

    /* Dropdown */

    .action-button {
        width: 34px;
        height: 34px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        color: #6b7280;
    }

    .action-button:hover {
        background: #f8fafc;
        color: var(--primary);
    }

    .dropdown-menu {
        border: 1px solid var(--border);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.10);
        border-radius: 9px;
        padding: 6px;
    }

    .dropdown-item {
        border-radius: 6px;
        font-size: 13px;
        padding: 8px 10px;
    }

    .dropdown-item:hover {
        background: #f1f6ff;
    }

    /* Empty State */

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        margin-bottom: 12px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 5px;
    }

    .empty-text {
        color: var(--text-muted);
        font-size: 13px;
        margin: 0;
    }

    /* Alerts */

    .alert {
        border-radius: 9px;
        font-size: 13px;
    }

    /* Responsive */

    @media (max-width: 1100px) {
        .class-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .class-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .class-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="supply-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>
            <h1 class="page-title">
                Student Supply Kits
            </h1>

            <p class="page-subtitle">
                Manage government supply kit distribution class-wise.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.kit-templates.create') }}"
                class="btn btn-outline-primary btn-sm"
            >
                <i class="bi bi-box-seam me-1"></i>
                Kit Creation
            </a>

            <a
                href="{{ route('admin.student-supply-kits.distribution.create') }}"
                class="btn btn-primary btn-sm"
            >
                <i class="bi bi-box-arrow-in-down me-1"></i>
                Kit Distribution
            </a>

            <a
    href="{{ route('admin.student-supply-kits.print', request()->query()) }}"
    target="_blank"
    class="btn btn-outline-secondary btn-sm"
>
    <i class="bi bi-printer me-1"></i>
    Print
</a>

        </div>
    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         CLASS CARD PAGE
    ========================================================== --}}

    @if($selectedClass === '')

        {{-- Academic Year Filter --}}

        <div class="filter-card">

            <div class="filter-title">
                <i class="bi bi-funnel me-1"></i>
                Select Academic Year
            </div>

            <form
                method="GET"
                action="{{ route('admin.student-supply-kits.index') }}"
            >

                <div class="row align-items-end">

                    <div class="col-md-4">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            value="{{ $academicYear }}"
                            class="form-control"
                            placeholder="2026-27"
                        >

                    </div>

                    <div class="col-md-auto mt-3 mt-md-0">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search me-1"></i>
                            Load Classes
                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- Class Cards --}}

        @if($classCards->count())

            <div class="class-grid">

                @foreach($classCards as $card)

                    <a
                        href="{{ route('admin.student-supply-kits.index', [
                            'class' => $card->class_name,
                            'academic_year' => $academicYear,
                        ]) }}"
                        class="class-card"
                    >

                        <div class="class-card-header">

                            <div class="d-flex align-items-center gap-3">

                                <div class="class-icon">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>

                                <div>

                                    <h2 class="class-name">
                                        {{ $card->class_name }}
                                    </h2>

                                    <div class="class-total">

                                        {{ $card->total_students }}

                                        {{ $card->total_students == 1
                                            ? 'Student'
                                            : 'Students' }}

                                    </div>

                                </div>

                            </div>

                            <div class="class-arrow">
                                <i class="bi bi-chevron-right"></i>
                            </div>

                        </div>


                        <div class="class-card-body">

                            <div class="class-stats">

                                <div class="class-stat stat-issued">

                                    <div class="class-stat-number">
                                        {{ $card->issued }}
                                    </div>

                                    <div class="class-stat-label">
                                        Issued
                                    </div>

                                </div>


                                <div class="class-stat stat-pending">

                                    <div class="class-stat-number">
                                        {{ $card->pending }}
                                    </div>

                                    <div class="class-stat-label">
                                        Pending
                                    </div>

                                </div>


                                <div class="class-stat stat-cancelled">

                                    <div class="class-stat-number">
                                        {{ $card->cancelled }}
                                    </div>

                                    <div class="class-stat-label">
                                        Cancelled
                                    </div>

                                </div>


                                <div class="class-stat stat-not-assigned">

                                    <div class="class-stat-number">
                                        {{ $card->not_assigned }}
                                    </div>

                                    <div class="class-stat-label">
                                        Not Assigned
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="class-card-footer">

                            View Students

                            <i class="bi bi-arrow-right ms-1"></i>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="student-card">

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div class="empty-title">
                        No Classes Found
                    </div>

                    <p class="empty-text">
                        No active students were found for the selected academic year.
                    </p>

                </div>

            </div>

        @endif


    {{-- =========================================================
         SELECTED CLASS / STUDENT PAGE
    ========================================================== --}}

    @else

        @php

            $selectedClassCard = $classCards->firstWhere(
                'class_name',
                $selectedClass
            );

        @endphp


        {{-- Selected Class Header --}}

        <div class="selected-class-header">

            <div class="selected-class-left">

                <a
                    href="{{ route('admin.student-supply-kits.index', [
                        'academic_year' => $academicYear
                    ]) }}"
                    class="back-button"
                    title="Back to Classes"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div>

                    <h2 class="selected-class-title">
                        {{ $selectedClass }}
                    </h2>

                    <p class="selected-class-subtitle">

                        @if($selectedClassCard)

                            {{ $selectedClassCard->total_students }}

                            {{ $selectedClassCard->total_students == 1
                                ? 'student'
                                : 'students' }}

                            · Academic Year {{ $academicYear }}

                        @else

                            Academic Year {{ $academicYear }}

                        @endif

                    </p>

                </div>

            </div>


            @if($selectedClassCard)

                <div class="d-flex gap-2 flex-wrap">

                    <span class="status-badge status-issued">

                        <i class="bi bi-check-circle"></i>

                        {{ $selectedClassCard->issued }}
                        Issued

                    </span>


                    <span class="status-badge status-pending">

                        <i class="bi bi-clock"></i>

                        {{ $selectedClassCard->pending }}
                        Pending

                    </span>


                    <span class="status-badge status-not-assigned">

                        <i class="bi bi-dash-circle"></i>

                        {{ $selectedClassCard->not_assigned }}
                        Not Assigned

                    </span>

                </div>

            @endif

        </div>


        {{-- =====================================================
             FILTERS
        ====================================================== --}}

        <div class="filter-card">

            <div class="filter-title">

                <i class="bi bi-funnel me-1"></i>

                Student Filters

            </div>


            <form
                method="GET"
                action="{{ route('admin.student-supply-kits.index') }}"
            >

                <input
                    type="hidden"
                    name="class"
                    value="{{ $selectedClass }}"
                >


                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Search Student
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Name or Student ID"
                        >

                    </div>


                    {{-- Academic Year --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            value="{{ $academicYear }}"
                            class="form-control"
                            placeholder="2026-27"
                        >

                    </div>


                    {{-- Section --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Section
                        </label>

                        <select
                            name="section"
                            class="form-select"
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


                    {{-- Status --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Kit Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="issued"
                                @selected(request('status') == 'issued')
                            >
                                Issued
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') == 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="cancelled"
                                @selected(request('status') == 'cancelled')
                            >
                                Cancelled
                            </option>

                            <option
                                value="not_assigned"
                                @selected(request('status') == 'not_assigned')
                            >
                                Not Assigned
                            </option>

                        </select>

                    </div>


                    {{-- Scheme --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Government Scheme
                        </label>

                        <select
                            name="scheme_id"
                            class="form-select"
                        >

                            <option value="">
                                All Schemes
                            </option>

                            @foreach($schemes as $scheme)

                                <option
                                    value="{{ $scheme->id }}"
                                    @selected(request('scheme_id') == $scheme->id)
                                >
                                    {{ $scheme->scheme_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Kit Template --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Kit Name
                        </label>

                        <select
                            name="kit_template_id"
                            class="form-select"
                        >

                            <option value="">
                                All Kits
                            </option>

                            @foreach($kitTemplates as $kitTemplate)

                                <option
                                    value="{{ $kitTemplate->id }}"
                                    @selected(request('kit_template_id') == $kitTemplate->id)
                                >

                                    {{ $kitTemplate->kit_name }}

                                    @if($kitTemplate->scheme)
                                        - {{ $kitTemplate->scheme->scheme_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-6 col-md-6 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>


                        <a
                            href="{{ route('admin.student-supply-kits.index', [
                                'class' => $selectedClass,
                                'academic_year' => $academicYear,
                            ]) }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>


                        <a
                            href="{{ route('admin.student-supply-kits.print', request()->query()) }}"
                            target="_blank"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-printer me-1"></i>
                            Print
                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
             STUDENT TABLE
        ====================================================== --}}

        <div class="student-card">

            <div class="table-header">

                <div>

                    <h3 class="table-title">
                        Students
                    </h3>

                    <div class="table-count">

                        {{ $students->count() }}

                        {{ $students->count() == 1
                            ? 'student'
                            : 'students' }}

                        found

                    </div>

                </div>


                <a
                    href="{{ route('admin.student-supply-kits.distribution.create') }}"
                    class="btn btn-sm btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Issue Kit
                </a>

            </div>


            @if($students->count())

                <div class="table-responsive">

                    <table class="table student-table">

                        <thead>

                            <tr>

                                <th style="width: 55px;">
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Student ID
                                </th>

                                <th>
                                    Section
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Kit
                                </th>

                                <th>
                                    Distribution Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th
                                    class="text-center"
                                    style="width: 70px;"
                                >
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($students as $student)

                                @php

                                    $studentName = trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Controller provides the latest kit as:
                                    | $student->supplyKitRecord
                                    |--------------------------------------------------------------------------
                                    */

                                    $kit = $student->supplyKitRecord ?? null;

                                    $status = $kit?->status ?? 'not_assigned';

                                @endphp


                                <tr>

                                    {{-- Number --}}

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Student --}}

                                    <td>

                                        <div class="student-name">
                                            {{ $studentName ?: '-' }}
                                        </div>

                                    </td>


                                    {{-- Student ID --}}

                                    <td>

                                        <span class="student-id">
                                            {{ $student->student_id ?: '-' }}
                                        </span>

                                    </td>


                                    {{-- Section --}}

                                    <td>
                                        {{ $student->section ?: '-' }}
                                    </td>


                                    {{-- Academic Year --}}

                                    <td>
                                        {{ $student->academic_year ?: $academicYear }}
                                    </td>


                                    {{-- Kit --}}

                                    <td>

                                        @if($kit)

                                            <div class="kit-name">

                                                {{ $kit->kitTemplate?->kit_name ?? '-' }}

                                            </div>


                                            @if($kit->kitTemplate?->scheme)

                                                <div class="kit-scheme">

                                                    {{ $kit->kitTemplate->scheme->scheme_name }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                Not Assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Distribution Date --}}

                                    <td>

                                        @if($kit?->distribution_date)

                                            {{ \Illuminate\Support\Carbon::parse(
                                                $kit->distribution_date
                                            )->format('d-m-Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @if($status === 'issued')

                                            <span class="status-badge status-issued">

                                                <i class="bi bi-check-circle"></i>

                                                Issued

                                            </span>

                                        @elseif($status === 'pending')

                                            <span class="status-badge status-pending">

                                                <i class="bi bi-clock"></i>

                                                Pending

                                            </span>

                                        @elseif($status === 'cancelled')

                                            <span class="status-badge status-cancelled">

                                                <i class="bi bi-x-circle"></i>

                                                Cancelled

                                            </span>

                                        @else

                                            <span class="status-badge status-not-assigned">

                                                <i class="bi bi-dash-circle"></i>

                                                Not Assigned

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-center">

                                        <div class="dropdown">

                                            <button
                                                type="button"
                                                class="action-button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                @if($kit)

                                                    {{-- View --}}

                                                    <li>

                                                        <a
                                                            href="{{ route(
                                                                'admin.student-supply-kits.show',
                                                                $kit->id
                                                            ) }}"
                                                            class="dropdown-item"
                                                        >

                                                            <i class="bi bi-eye me-2"></i>

                                                            View

                                                        </a>

                                                    </li>


                                                    {{-- Edit --}}

                                                    <li>

                                                        <a
                                                            href="{{ route(
                                                                'admin.student-supply-kits.edit',
                                                                $kit->id
                                                            ) }}"
                                                            class="dropdown-item"
                                                        >

                                                            <i class="bi bi-pencil me-2"></i>

                                                            Edit

                                                        </a>

                                                    </li>


                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>


                                                    {{-- Delete --}}

                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.student-supply-kits.destroy',
                                                                $kit->id
                                                            ) }}"
                                                            onsubmit="return confirm('Are you sure you want to delete this supply kit record?');"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >

                                                                <i class="bi bi-trash me-2"></i>

                                                                Delete

                                                            </button>

                                                        </form>

                                                    </li>

                                                @else

                                                    {{-- Issue Kit --}}

                                                    <li>

                                                        <a
                                                            href="{{ route(
                                                                'admin.student-supply-kits.distribution.create'
                                                            ) }}"
                                                            class="dropdown-item"
                                                        >

                                                            <i class="bi bi-box-arrow-in-down me-2"></i>

                                                            Issue Kit

                                                        </a>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div class="empty-title">
                        No Students Found
                    </div>

                    <p class="empty-text">
                        No students match the selected filters.
                    </p>

                </div>

            @endif

        </div>

    @endif

</div>

@endsection