@extends('layouts.app')

@section('title', 'Student Supply Kit Distribution History')

@section('content')

<style>
    .sh-page {
        color: #18241c;
        padding-bottom: 30px;
    }

    .sh-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .sh-page-header h3 {
        color: #19211d;
        font-weight: 750;
        margin-bottom: 5px;
    }

    .sh-page-header p {
        color: #647067;
        margin-bottom: 0;
    }

    .sh-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #19211d;
        color: #fff;
        border: 1px solid #19211d;
        border-radius: 9px;
        padding: 10px 17px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .sh-btn-primary:hover {
        background: #304438;
        border-color: #304438;
        color: #fff;
        transform: translateY(-1px);
    }

    .sh-card {
        background: #fff;
        border: 1px solid #e1eae3;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(27, 67, 41, .045);
        margin-bottom: 24px;
    }

    .sh-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 17px 20px;
        background: #fff;
        border-bottom: 1px solid #e7eee8;
    }

    .sh-card-header h5 {
        font-size: 1rem;
        font-weight: 750;
        margin: 0;
        color: #19211d;
    }

    .sh-card-body {
        padding: 20px;
    }

    .sh-filter-card .sh-card-body {
        padding: 20px;
    }

    .sh-page .form-label {
        font-size: .84rem;
        font-weight: 650;
        color: #19211d;
        margin-bottom: 7px;
    }

    .sh-page .form-control,
    .sh-page .form-select {
        min-height: 42px;
        border: 1px solid #dce6de;
        border-radius: 8px;
        font-size: .9rem;
    }

    .sh-page .form-control:focus,
    .sh-page .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .10);
    }

    .sh-btn-filter {
        min-height: 42px;
        width: 100%;
        border-radius: 8px;
        background: #19211d;
        color: #fff;
        border: 1px solid #19211d;
        font-weight: 600;
    }

    .sh-btn-filter:hover {
        background: #304438;
        color: #fff;
    }

    .sh-btn-clear {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #19211d;
        border: 1px solid #d3e3d6;
        border-radius: 7px;
        padding: 7px 11px;
        font-size: .82rem;
        text-decoration: none;
    }

    .sh-btn-clear:hover {
        background: #edf7ef;
        color: #19211d;
    }

    .sh-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 11px;
        border-radius: 30px;
        font-size: .78rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sh-count-green {
        color: #176b3b;
        background: #e5f6e9;
    }

    .sh-table {
        width: 100%;
        margin-bottom: 0;
    }

    .sh-table thead th {
        background: #f5f9f5;
        color: #19211d;
        border-bottom: 1px solid #e0e9e1;
        padding: 13px 15px;
        font-size: .75rem;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
    }

    .sh-table tbody td {
        padding: 14px 15px;
        border-color: #edf2ee;
        color: #19211d;
        font-size: .86rem;
        vertical-align: middle;
    }

    .sh-table tbody tr:hover {
        background: #f7fbf7;
    }

    .sh-student-name {
        color: #19211d;
        font-weight: 700;
    }

    .sh-student-id {
        display: block;
        color: #647067;
        font-size: .77rem;
        margin-top: 3px;
    }

    .sh-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 30px;
        font-size: .75rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sh-status-issued {
        color: #176b3b;
        background: #e5f6e9;
    }

    .sh-status-dot {
        width: 7px;
        height: 7px;
        background: currentColor;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .sh-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #dbe6dd;
        border-radius: 8px;
        color: #19211d;
        background: #fff;
        text-decoration: none;
        transition: .15s ease;
    }

    .sh-action:hover {
        color: #fff;
        background: #19211d;
        border-color: #19211d;
    }

    .sh-empty {
        padding: 35px 15px !important;
        text-align: center;
        color: #647067 !important;
    }

    /* Class-wise groups */

    .sh-class-card {
        background: #fff;
        border: 1px solid #d7e9da;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(26, 83, 45, .035);
    }

    .sh-class-card:last-child {
        margin-bottom: 0;
    }

    .sh-class-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 15px 18px;
        background: #e8f6eb;
        border-bottom: 1px solid #d5e9d9;
    }

    .sh-class-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sh-class-icon {
        width: 43px;
        height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #d0edd7;
        color: #19211d;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .sh-class-name {
        color: #19211d;
        font-size: .97rem;
        font-weight: 750;
        margin: 0 0 3px;
    }

    .sh-class-subtitle {
        color: #66816d;
        font-size: .79rem;
    }

    .sh-class-count {
        color: #176b3b;
        background: #fff;
        border: 1px solid #cce5d1;
        border-radius: 30px;
        padding: 7px 12px;
        font-size: .79rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .sh-student-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        color: #19211d;
        background: #e5f5e9;
        font-weight: 750;
        font-size: .8rem;
    }

    .sh-section-label {
        display: inline-block;
        min-width: 32px;
        text-align: center;
        padding: 5px 8px;
        border-radius: 6px;
        background: #eef7ef;
        border: 1px solid #d9eadc;
        color: #19211d;
        font-size: .8rem;
        font-weight: 700;
    }

    .sh-empty-state {
        text-align: center;
        padding: 45px 20px;
        background: #fff;
        border: 1px dashed #bfdec6;
        border-radius: 12px;
    }

    .sh-empty-icon {
        color: #198754;
        font-size: 2.7rem;
        margin-bottom: 12px;
    }

    .sh-empty-state h6 {
        color: #19211d;
        font-weight: 750;
    }

    .sh-empty-state p {
        color: #7b8a7f;
        font-size: .87rem;
        margin-bottom: 0;
    }

    .sh-pagination {
        padding: 14px 18px;
        border-top: 1px solid #e6eee7;
        background: #fff;
    }

    .sh-pagination nav p {
        margin-bottom: 0;
        color: #19211d;
        font-size: .82rem;
    }

    @media (max-width: 767px) {
        .sh-card-body,
        .sh-filter-card .sh-card-body {
            padding: 14px;
        }

        .sh-class-header {
            padding: 13px;
        }

        .sh-table thead th,
        .sh-table tbody td {
            padding: 11px 12px;
        }
    }
</style>

<div class="container-fluid py-4 sh-page">

    {{-- PAGE HEADER --}}
    <div class="sh-page-header">
        <div>
            <h3>Distribution History</h3>
            <p>
                View distributed supply kits grouped by class.
            </p>
        </div>

        <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
           class="sh-btn-primary">
            <i class="bi bi-box-seam"></i>
            Distribute Kits
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="sh-card sh-filter-card">
        <div class="sh-card-body">
            <form method="GET"
                  action="{{ route('admin.student-supply-kits.history') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-md-6">
                        <label for="search" class="form-label">
                            Search Student
                        </label>

                        <input type="text"
                               id="search"
                               name="search"
                               class="form-control"
                               value="{{ $search ?? '' }}"
                               placeholder="Student name or admission number">
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label for="academic_year" class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                id="academic_year"
                                class="form-select">
                            <option value="">All academic years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    @selected(($academicYear ?? '') === $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <label for="class" class="form-label">Class</label>

                        <select name="class"
                                id="class"
                                class="form-select">
                            <option value="">All classes</option>

                            @foreach($classes as $class)
                                <option value="{{ $class }}"
                                    @selected(($selectedClass ?? '') === $class)>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <label for="status" class="form-label">Status</label>

                        <select name="status"
                                id="status"
                                class="form-select">
                            <option value="">Issued kits only</option>
                            <option value="issued"
                                @selected(($status ?? '') === 'issued')>
                                Issued
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-1 col-md-6 d-grid">
                        <button type="submit" class="sh-btn-filter">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>
                    </div>

                    <div class="col-12">
                        <a href="{{ route('admin.student-supply-kits.history') }}"
                           class="sh-btn-clear">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Clear Filters
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- DISTRIBUTED KITS GROUPED BY CLASS --}}
    <div class="sh-card">

        <div class="sh-card-header">
            <h5>
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                Distributed Kits — Class Wise
            </h5>

            <span class="sh-count sh-count-green">
                <i class="bi bi-box-seam"></i>
                {{ $issuedKits->total() }} Distributed Records
            </span>
        </div>

        <div class="sh-card-body">

            @php
                /*
                 * Note: This groups the issued records on the current
                 * paginator page. The controller query must filter to
                 * issued records only.
                 */
                $issuedByClass = $issuedKits->getCollection()
                    ->groupBy(function ($kit) {
                        return $kit->student?->admission_class
                            ?: 'Class Not Assigned';
                    })
                    ->sortKeys();
            @endphp

            @forelse($issuedByClass as $className => $kits)

                <div class="sh-class-card">

                    {{-- CLASS HEADER --}}
                    <div class="sh-class-header">
                        <div class="sh-class-info">
                            <div class="sh-class-icon">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>

                            <div>
                                <h6 class="sh-class-name">
                                    {{ $className }}
                                </h6>

                                <div class="sh-class-subtitle">
                                    Students who received their supply kits
                                </div>
                            </div>
                        </div>

                        <span class="sh-class-count">
                            <i class="bi bi-people me-1"></i>
                            {{ $kits->count() }}
                            {{ $kits->count() === 1 ? 'Record' : 'Records' }}
                        </span>
                    </div>

                    {{-- STUDENTS IN THIS CLASS --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle sh-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Student Name</th>
                                    <th>Admission No.</th>
                                    <th>Section</th>
                                    <th>Supply Kit</th>
                                    <th>Academic Year</th>
                                    <th>Distribution Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($kits as $kit)
                                    <tr>
                                        <td>
                                            <span class="sh-student-number">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="sh-student-name">
                                                {{ $kit->student
                                                    ? trim(implode(' ', array_filter([
                                                        $kit->student->first_name,
                                                        $kit->student->middle_name,
                                                        $kit->student->last_name,
                                                    ])))
                                                    : 'Student unavailable' }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="sh-student-id">
                                                {{ $kit->student?->student_id ?: '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="sh-section-label">
                                                {{ $kit->student?->section ?: '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="sh-student-name">
                                                {{ $kit->kitTemplate?->kit_name ?? '—' }}
                                            </div>

                                            @if($kit->kitTemplate?->scheme)
                                                <small class="text-muted">
                                                    {{ $kit->kitTemplate->scheme->scheme_name }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $kit->academic_year ?: '—' }}
                                        </td>

                                        <td>
                                            {{ $kit->distribution_date
                                                ? \Illuminate\Support\Carbon::parse($kit->distribution_date)->format('d M Y')
                                                : '—' }}
                                        </td>

                                        <td>
                                            <span class="sh-status sh-status-issued">
                                                <span class="sh-status-dot"></span>
                                                Issued
                                            </span>
                                        </td>

                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.student-supply-kits.show', $kit->id) }}"
                                               class="sh-action"
                                               title="View kit"
                                               aria-label="View kit">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.student-supply-kits.print', $kit->id) }}"
                                               class="sh-action"
                                               title="Print kit"
                                               aria-label="Print kit">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            @empty
                <div class="sh-empty-state">
                    <div class="sh-empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <h6>No Distributed Kits Found</h6>

                    <p>
                        No issued supply kits match the selected filters.
                    </p>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        @if($issuedKits->hasPages())
            <div class="sh-pagination">
                {{ $issuedKits->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
