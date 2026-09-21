@extends('layouts.app')

@section('content')

<div class="bonafide-page py-4">


<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- PAGE HEADER --}}
    {{-- ===================================================== --}}

    <div class="page-header mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="header-icon">
                    <i class="bi bi-patch-check-fill"></i>
                </span>

                <span class="text-primary fw-semibold small text-uppercase">
                    Certificate Management
                </span>

            </div>

            <h2 class="fw-bold mb-1">
                Bonafide Certificates
            </h2>

            <p class="text-muted mb-0">
                Manage, issue and maintain student bonafide certificates.
            </p>

        </div>


        <a href="{{ route('admin.bonafide.create') }}"
           class="btn btn-primary create-btn">

            <i class="bi bi-plus-circle-fill me-2"></i>

            Create Certificate

        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- ALERTS --}}
    {{-- ===================================================== --}}

    @if(session('success'))

        <div class="alert alert-success custom-alert alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div>

                    <strong>Success</strong>

                    <div>
                        {{ session('success') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger custom-alert alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon danger-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div>

                    <strong>Error</strong>

                    <div>
                        {{ session('error') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- SUMMARY CARDS --}}
    {{-- ===================================================== --}}

    @php

        $totalCertificates = $certificates->total();

        $activeCertificates = $certificates->getCollection()
            ->where('status', 'active')
            ->count();

        $inactiveCertificates = $certificates->getCollection()
            ->where('status', 'inactive')
            ->count();

    @endphp


    <div class="row g-3 mb-4">


        {{-- Total --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon total-icon">
                    <i class="bi bi-files"></i>
                </div>

                <div class="summary-content">

                    <span>
                        Total Certificates
                    </span>

                    <strong>
                        {{ $totalCertificates }}
                    </strong>

                </div>

                <div class="summary-arrow">
                    <i class="bi bi-file-earmark-check"></i>
                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon active-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div class="summary-content">

                    <span>
                        Active Certificates
                    </span>

                    <strong>
                        {{ $activeCertificates }}
                    </strong>

                </div>

                <div class="summary-arrow">
                    <i class="bi bi-arrow-up-right"></i>
                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon inactive-icon">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>

                <div class="summary-content">

                    <span>
                        Inactive Certificates
                    </span>

                    <strong>
                        {{ $inactiveCertificates }}
                    </strong>

                </div>

                <div class="summary-arrow">
                    <i class="bi bi-dash-circle"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MAIN CARD --}}
    {{-- ===================================================== --}}

    <div class="certificate-card">


        {{-- ================================================= --}}
        {{-- SEARCH HEADER --}}
        {{-- ================================================= --}}

        <div class="search-header">

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <div class="search-title-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h5 class="fw-bold mb-0">
                        Search Certificates
                    </h5>

                </div>

                <small class="text-muted">
                    Search by certificate number, student name or student ID.
                </small>

            </div>


            @if(!empty($search))

                <span class="search-result-badge">

                    <i class="bi bi-funnel-fill me-1"></i>

                    Search active

                </span>

            @endif

        </div>


        <div class="search-body">

            <form method="GET"
                  action="{{ route('admin.bonafide.index') }}">

                <div class="row g-3 align-items-end">


                    {{-- Search --}}
                    <div class="col-lg-8">

                        <label for="search"
                               class="form-label fw-semibold">

                            Search

                        </label>

                        <div class="search-input-wrapper">

                            <i class="bi bi-search"></i>

                            <input type="text"
                                   name="search"
                                   id="search"
                                   value="{{ $search ?? '' }}"
                                   class="form-control ps-5"
                                   placeholder="Certificate number, student name or student ID...">

                            @if(!empty($search))

                                <a href="{{ route('admin.bonafide.index') }}"
                                   class="clear-search">

                                    <i class="bi bi-x-circle-fill"></i>

                                </a>

                            @endif

                        </div>

                    </div>


                    {{-- Search Button --}}
                    <div class="col-lg-2 col-md-6">

                        <button type="submit"
                                class="btn btn-primary w-100 search-btn">

                            <i class="bi bi-search me-1"></i>

                            Search

                        </button>

                    </div>


                    {{-- Clear --}}
                    <div class="col-lg-2 col-md-6">

                        @if(!empty($search))

                            <a href="{{ route('admin.bonafide.index') }}"
                               class="btn btn-light border w-100 clear-btn">

                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                Clear

                            </a>

                        @else

                            <button type="button"
                                    class="btn btn-light border w-100 clear-btn"
                                    disabled>

                                <i class="bi bi-file-earmark-text me-1"></i>

                                All Certificates

                            </button>

                        @endif

                    </div>

                </div>

            </form>

        </div>


        {{-- ================================================= --}}
        {{-- TABLE HEADER --}}
        {{-- ================================================= --}}

        <div class="table-topbar">

            <div>

                <h6 class="fw-bold mb-1">
                    Certificate Records
                </h6>

                <small class="text-muted">

                    @if($certificates->total() > 0)

                        {{ $certificates->total() }}
                        certificate{{ $certificates->total() != 1 ? 's' : '' }}
                        found

                    @else

                        No records available

                    @endif

                </small>

            </div>


            @if(!empty($search))

                <div class="filter-info">

                    <i class="bi bi-search me-1"></i>

                    Results for:
                    <strong>"{{ $search }}"</strong>

                </div>

            @endif

        </div>


        {{-- ================================================= --}}
        {{-- TABLE --}}
        {{-- ================================================= --}}

        <div class="table-container">

            @if($certificates->count())

                <div class="table-responsive">

                    <table class="table certificate-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Certificate
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
                                    Issue Date
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($certificates as $certificate)

                            @php

                                $student = $certificate->student;

                                $studentName = $student
                                    ? trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    )
                                    : 'Student not found';

                                $initials = '';

                                if ($student) {

                                    $initials =
                                        strtoupper(substr($student->first_name ?? '', 0, 1)) .
                                        strtoupper(substr($student->last_name ?? '', 0, 1));

                                }

                            @endphp


                            <tr>


                                {{-- Number --}}
                                <td class="ps-4">

                                    <span class="row-number">

                                        {{ $certificates->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- Certificate --}}
                                <td>

                                    <div class="certificate-info">

                                        <div class="certificate-mini-icon">

                                            <i class="bi bi-file-earmark-check-fill"></i>

                                        </div>

                                        <div>

                                            <div class="certificate-number">
                                                {{ $certificate->certificate_no }}
                                            </div>

                                            <small class="text-muted">
                                                Bonafide Certificate
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Student --}}
                                <td>

                                    <div class="student-info">

                                        <div class="student-avatar">

                                            {{ $initials ?: '?' }}

                                        </div>

                                        <div>

                                            <div class="student-name">

                                                {{ $studentName }}

                                            </div>

                                            @if($student && $student->gender)

                                                <small class="text-muted">

                                                    {{ $student->gender }}

                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Student ID --}}
                                <td>

                                    <span class="student-id">

                                        {{ $student->student_id ?? '—' }}

                                    </span>

                                </td>


                                {{-- Class --}}
                                <td>

                                    @if($student)

                                        <span class="class-badge">

                                            {{ $student->class ?? '—' }}

                                            @if(!empty($student->section))
                                                / {{ $student->section }}
                                            @endif

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Issue Date --}}
                                <td>

                                    @if($certificate->issue_date)

                                        <div class="date-info">

                                            <i class="bi bi-calendar3"></i>

                                            <span>

                                                {{ $certificate->issue_date->format('d M Y') }}

                                            </span>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Reason --}}
                                <td class="reason-column">

                                    @if($certificate->reason)

                                        <span class="reason-text"
                                              title="{{ $certificate->reason }}">

                                            {{ \Illuminate\Support\Str::limit($certificate->reason, 42) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($certificate->status === 'active')

                                        <span class="status-badge active-status">

                                            <i class="bi bi-circle-fill"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="status-badge inactive-status">

                                            <i class="bi bi-circle-fill"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="pe-4">

                                    <div class="action-buttons">


                                        {{-- View --}}
                                        <a href="{{ route('admin.bonafide.show', $certificate->id) }}"
                                           class="action-btn view-action"
                                           title="View Certificate">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        <a href="{{ route('admin.bonafide.edit', $certificate->id) }}"
                                           class="action-btn edit-action"
                                           title="Edit Certificate">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form method="POST"
                                              action="{{ route('admin.bonafide.destroy', $certificate->id) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this certificate?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete-action"
                                                    title="Delete Certificate">

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


            @else


                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-file-earmark-x"></i>

                    </div>

                    <h5 class="fw-bold mb-2">
                        No Bonafide Certificates Found
                    </h5>

                    <p class="text-muted mb-4">

                        @if(!empty($search))

                            No certificates matched your search criteria.

                        @else

                            No bonafide certificates have been created yet.

                        @endif

                    </p>


                    @if(!empty($search))

                        <a href="{{ route('admin.bonafide.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-counterclockwise me-1"></i>

                            Clear Search

                        </a>

                    @else

                        <a href="{{ route('admin.bonafide.create') }}"
                           class="btn btn-primary">

                            <i class="bi bi-plus-circle-fill me-1"></i>

                            Create First Certificate

                        </a>

                    @endif

                </div>

            @endif

        </div>


        {{-- ================================================= --}}
        {{-- PAGINATION --}}
        {{-- ================================================= --}}

        @if($certificates->hasPages())

            <div class="pagination-footer">

                <div class="pagination-info">

                    Showing

                    <strong>
                        {{ $certificates->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $certificates->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $certificates->total() }}
                    </strong>

                    certificates

                </div>


                <div class="pagination-wrapper">

                    {{ $certificates->links() }}

                </div>

            </div>

        @endif


    </div>

</div>


</div>

{{-- ============================================================= --}}
{{-- PAGE CSS --}}
{{-- ============================================================= --}}

<style>

    .bonafide-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }


    /* ================= HEADER ================= */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .header-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
        font-size: 18px;
    }

    .page-header h2 {
        letter-spacing: -.5px;
    }

    .create-btn {
        border-radius: 10px;
        padding: 11px 20px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(13, 110, 253, .16);
    }


    /* ================= ALERT ================= */

    .custom-alert {
        border: 0;
        border-radius: 12px;
        padding: 15px 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
    }

    .alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .success-icon {
        background: rgba(25, 135, 84, .12);
        color: #198754;
    }

    .danger-icon {
        background: rgba(220, 53, 69, .12);
        color: #dc3545;
    }


    /* ================= SUMMARY CARDS ================= */

    .summary-card {
        background: #fff;
        border: 1px solid #e8ebf0;
        border-radius: 14px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 92px;
        box-shadow: 0 6px 20px rgba(31, 41, 55, .04);
        transition: all .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(31, 41, 55, .07);
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .total-icon {
        background: rgba(13, 110, 253, .10);
        color: #0d6efd;
    }

    .active-icon {
        background: rgba(25, 135, 84, .10);
        color: #198754;
    }

    .inactive-icon {
        background: rgba(108, 117, 125, .10);
        color: #6c757d;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .summary-content span {
        font-size: 12px;
        color: #667085;
        margin-bottom: 3px;
    }

    .summary-content strong {
        font-size: 23px;
        line-height: 1;
        color: #1d2939;
    }

    .summary-arrow {
        color: #98a2b3;
        font-size: 16px;
    }


    /* ================= MAIN CARD ================= */

    .certificate-card {
        background: #fff;
        border: 1px solid #e8ebf0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(31, 41, 55, .06);
    }


    /* ================= SEARCH ================= */

    .search-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .search-title-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .09);
        color: #0d6efd;
    }

    .search-result-badge {
        background: #eef5ff;
        color: #0d6efd;
        border: 1px solid #dce9ff;
        border-radius: 50px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .search-body {
        padding: 20px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #edf0f4;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper > i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        z-index: 5;
    }

    .search-input-wrapper .form-control {
        min-height: 46px;
        border-radius: 9px;
        border-color: #dfe4ea;
    }

    .search-input-wrapper .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .08);
    }

    .clear-search {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        text-decoration: none;
        z-index: 5;
    }

    .clear-search:hover {
        color: #dc3545;
    }

    .search-btn,
    .clear-btn {
        min-height: 46px;
        border-radius: 9px;
        font-weight: 600;
    }


    /* ================= TABLE TOPBAR ================= */

    .table-topbar {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .filter-info {
        font-size: 12px;
        color: #667085;
        background: #f8f9fb;
        border: 1px solid #e8ebf0;
        padding: 7px 11px;
        border-radius: 8px;
    }


    /* ================= TABLE ================= */

    .table-container {
        width: 100%;
    }

    .certificate-table {
        min-width: 1150px;
    }

    .certificate-table thead th {
        background: #f8f9fb;
        color: #667085;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        padding: 14px 12px;
        border-bottom: 1px solid #e8ebf0;
        white-space: nowrap;
    }

    .certificate-table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f2f5;
        font-size: 13px;
        color: #344054;
    }

    .certificate-table tbody tr {
        transition: background-color .15s ease;
    }

    .certificate-table tbody tr:hover {
        background: #fbfcff;
    }

    .certificate-table tbody tr:last-child td {
        border-bottom: 0;
    }


    /* Row number */

    .row-number {
        color: #98a2b3;
        font-size: 12px;
        font-weight: 600;
    }


    /* Certificate */

    .certificate-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .certificate-mini-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(13, 110, 253, .09);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .certificate-number {
        color: #0d6efd;
        font-weight: 700;
        font-size: 13px;
    }


    /* Student */

    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 190px;
    }

    .student-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #eef3f8;
        color: #475467;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .student-name {
        color: #344054;
        font-weight: 600;
        white-space: nowrap;
    }


    /* Student ID */

    .student-id {
        font-family: monospace;
        background: #f8f9fb;
        border: 1px solid #edf0f4;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 12px;
    }


    /* Class */

    .class-badge {
        background: #f8f9fb;
        border: 1px solid #e8ebf0;
        color: #475467;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }


    /* Date */

    .date-info {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .date-info i {
        color: #98a2b3;
    }


    /* Reason */

    .reason-column {
        min-width: 210px;
        max-width: 250px;
    }

    .reason-text {
        display: block;
        color: #667085;
        line-height: 1.5;
        cursor: default;
    }


    /* Status */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge i {
        font-size: 6px;
    }

    .active-status {
        background: #ecfdf3;
        color: #027a48;
        border: 1px solid #d1fadf;
    }

    .inactive-status {
        background: #f2f4f7;
        color: #667085;
        border: 1px solid #e4e7ec;
    }


    /* ================= ACTIONS ================= */

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .action-buttons form {
        margin: 0;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid transparent;
        background: #fff;
        transition: all .15s ease;
        font-size: 14px;
    }

    .view-action {
        color: #0d6efd;
        border-color: #d9e7ff;
        background: #f5f9ff;
    }

    .view-action:hover {
        background: #0d6efd;
        color: #fff;
    }

    .edit-action {
        color: #b78103;
        border-color: #f4dfaa;
        background: #fffaf0;
    }

    .edit-action:hover {
        background: #f0ad00;
        color: #fff;
    }

    .delete-action {
        color: #dc3545;
        border-color: #f3c5ca;
        background: #fff6f7;
    }

    .delete-action:hover {
        background: #dc3545;
        color: #fff;
    }


    /* ================= EMPTY STATE ================= */

    .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        border-radius: 20px;
        background: #f5f7fa;
        color: #98a2b3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .empty-state p {
        max-width: 430px;
        margin-left: auto;
        margin-right: auto;
    }


    /* ================= PAGINATION ================= */

    .pagination-footer {
        padding: 18px 24px;
        border-top: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #667085;
        font-size: 12px;
    }

    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }

    .pagination-wrapper .page-link {
        border-radius: 7px;
        margin-left: 4px;
        border-color: #e4e7ec;
        color: #475467;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }


    /* ================= RESPONSIVE ================= */

    @media (max-width: 767.98px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .create-btn {
            width: 100%;
        }

        .summary-card {
            min-height: 82px;
        }

        .search-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px;
        }

        .search-body {
            padding: 18px;
        }

        .table-topbar {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px 18px;
        }

        .pagination-footer {
            padding: 18px;
        }

    }

</style>

@endsection
