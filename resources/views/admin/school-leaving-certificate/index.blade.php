@extends('layouts.app')

@section('title', 'School Leaving Certificates | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — SCHOOL LEAVING CERTIFICATES
       LOCKED VERSION
    ========================================================= */

    .slc-index-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .slc-index-page *,
    .slc-index-page *::before,
    .slc-index-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .slc-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .slc-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .slc-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .slc-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .slc-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .slc-breadcrumb i {
        color: #a87543;
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
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .slc-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .slc-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-create,
    .btn-scheme-search,
    .btn-scheme-reset,
    .btn-scheme-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-create,
    .btn-scheme-search {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-create:hover,
    .btn-scheme-search:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-reset {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-reset:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-scheme-action {
        min-height: 32px;
        min-width: 32px;
        padding: 0 8px;
        font-size: 12px;
    }

    .btn-action-view {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .btn-action-view:hover {
        background: #dce9d9;
        border-color: #83aa8a;
    }

    .btn-action-edit {
        color: #87582d;
        background: #f8eee0;
        border-color: #d9b88f;
    }

    .btn-action-edit:hover {
        background: #f0e0cc;
        border-color: #c9a87a;
    }

    .btn-action-print {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .btn-action-print:hover {
        background: #dce9d9;
        border-color: #83aa8a;
    }

    .btn-action-delete {
        color: #8a3a34;
        background: #fff1ed;
        border-color: #e8b8b0;
    }

    .btn-action-delete:hover {
        background: #fde4de;
        border-color: #dba39a;
    }

    /* ---------- ALERTS ---------- */
    .scheme-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .scheme-alert-success {
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
        color: #245a43;
    }

    .scheme-alert-danger {
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        border-left: 4px solid #bd5148;
        color: #8a3a34;
    }

    /* ---------- SEARCH CARD ---------- */
    .search-card,
    .list-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .search-card-body {
        padding: 18px 20px;
    }

    .search-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #7c8b7e;
        font-size: 14px;
        z-index: 2;
        pointer-events: none;
    }

    .search-input {
        display: block;
        width: 100%;
        height: 40px;
        padding: 0 12px 0 38px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .search-input::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .search-input:hover {
        border-color: #c5d1c2;
    }

    .search-input:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .search-actions {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    /* ---------- LIST CARD ---------- */
    .list-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e9e5d9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
    }

    .list-card-header h5 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .list-card-header h5 i {
        color: #34775a;
    }

    .record-count {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    /* ---------- TABLE ---------- */
    .slc-table-wrapper {
        overflow-x: auto;
    }

    .slc-table {
        width: 100%;
        min-width: 900px;
        margin: 0;
        border-collapse: collapse;
    }

    .slc-table thead th {
        background: #f8f6ed;
        color: #5c6b62;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 12px 14px;
        border-bottom: 1px solid #e5e1d3;
        white-space: nowrap;
        text-align: left;
    }

    .slc-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #efecdf;
        color: #263b35;
        font-size: 12px;
        vertical-align: middle;
        background: #fffefa;
    }

    .slc-table tbody tr:last-child td {
        border-bottom: none;
    }

    .slc-table tbody tr:hover td {
        background: #faf9f3;
    }

    .cert-no {
        font-weight: 700;
        color: #245a43;
    }

    .student-name {
        font-weight: 650;
        color: #263b35;
    }

    .student-id-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 9px;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-issued {
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
    }

    .status-cancelled {
        background: #fff1ed;
        color: #8a3a34;
        border: 1px solid #e8b8b0;
    }

    .status-draft {
        background: #f8eee0;
        color: #87582d;
        border: 1px solid #d9b88f;
    }

    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ---------- PAGINATION ---------- */
    .list-footer {
        padding: 14px 20px;
        border-top: 1px solid #e9e5d9;
        background: #faf8f0;
    }

    /* ---------- EMPTY STATE ---------- */
    .empty-state {
        padding: 56px 24px;
        text-align: center;
    }

    .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        color: #7a857d;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 14px;
        font-size: 28px;
    }

    .empty-title {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 15px;
        font-weight: 750;
    }

    .empty-text {
        margin: 0 0 18px;
        color: #7a857d;
        font-size: 12px;
        line-height: 1.5;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .slc-header {
            padding: 18px 16px;
        }

        .slc-title {
            font-size: 19px;
        }

        .slc-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .slc-header-content {
            flex-direction: column;
        }

        .btn-scheme-create {
            width: 100%;
        }

        .search-card-body {
            padding: 16px;
        }

        .search-actions {
            margin-top: 10px;
        }

        .search-actions .btn-scheme-search,
        .search-actions .btn-scheme-reset {
            flex: 1;
        }
    }

    @media (max-width: 480px) {
        .slc-title {
            font-size: 17px;
        }

        .slc-subtitle {
            font-size: 10px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .slc-index-page *,
        .slc-index-page *::before,
        .slc-index-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="slc-index-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="slc-header">
        <div class="slc-header-content">
            <div>
                <div class="slc-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>School Leaving Certificates</span>
                </div>

                <div class="slc-title-row">
                    <div class="slc-title-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h1 class="slc-title">School Leaving Certificates</h1>
                        <p class="slc-subtitle">Manage all school leaving certificates.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.school-leaving-certificate.create') }}"
               class="btn-scheme-create">
                <i class="bi bi-plus-circle"></i>
                Create Certificate
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="scheme-alert scheme-alert-success">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="scheme-alert scheme-alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ===================== SEARCH ===================== --}}
    <div class="search-card">
        <div class="search-card-body">
            <form method="GET" action="{{ route('admin.school-leaving-certificate.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label class="search-label">Search Certificate</label>
                        <div class="search-input-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? request('search') }}"
                                   class="search-input"
                                   placeholder="Search by certificate number, student ID or student name...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="search-actions">
                            <button type="submit" class="btn-scheme-search flex-grow-1">
                                <i class="bi bi-search"></i>
                                Search
                            </button>
                            <a href="{{ route('admin.school-leaving-certificate.index') }}"
                               class="btn-scheme-reset"
                               title="Clear Search">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== CERTIFICATE TABLE ===================== --}}
    <div class="list-card">
        <div class="list-card-header">
            <h5>
                <i class="bi bi-list-ul"></i>
                Certificate Records
            </h5>
            <span class="record-count">
                {{ $certificates->total() }} Records
            </span>
        </div>

        @if($certificates->count() > 0)

            <div class="slc-table-wrapper">
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
                                    ? trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    )
                                    : 'Student Deleted';
                            @endphp

                            <tr>
                                <td class="ps-4">
                                    {{ $certificates->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="cert-no">{{ $certificate->certificate_no }}</span>
                                </td>

                                <td>
                                    @if($student)
                                        <div class="student-name">{{ $studentName }}</div>
                                    @else
                                        <span class="text-danger">Student Deleted</span>
                                    @endif
                                </td>

                                <td>
                                    @if($student)
                                        <span class="student-id-badge">{{ $student->student_id }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($student)
                                        {{ $student->class ?? '—' }}
                                        @if(!empty($student->section))
                                            <span class="text-muted">- {{ $student->section }}</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    {{ $certificate->issue_date ? $certificate->issue_date->format('d-m-Y') : '—' }}
                                </td>

                                <td>
                                    @if($certificate->leaving_date)
                                        <strong>{{ $certificate->leaving_date->format('d-m-Y') }}</strong>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @switch($certificate->status)
                                        @case('issued')
                                            <span class="status-badge status-issued">Issued</span>
                                            @break
                                        @case('cancelled')
                                            <span class="status-badge status-cancelled">Cancelled</span>
                                            @break
                                        @default
                                            <span class="status-badge status-draft">Draft</span>
                                    @endswitch
                                </td>

                                <td class="text-center">
                                    <div class="action-group">
                                        <a href="{{ route('admin.school-leaving-certificate.show', $certificate) }}"
                                           class="btn-scheme-action btn-action-view"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.school-leaving-certificate.edit', $certificate) }}"
                                           class="btn-scheme-action btn-action-edit"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="{{ route('admin.school-leaving-certificate.print', $certificate) }}"
                                           target="_blank"
                                           class="btn-scheme-action btn-action-print"
                                           title="Print">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        <button type="button"
                                                class="btn-scheme-action btn-action-delete"
                                                title="Delete"
                                                onclick="confirmDelete({{ $certificate->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $certificate->id }}"
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

            @if($certificates->hasPages())
                <div class="list-footer">
                    {{ $certificates->links() }}
                </div>
            @endif

        @else

            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-file-earmark-x"></i>
                </div>

                <h5 class="empty-title">No Certificates Found</h5>

                @if($search ?? request('search'))
                    <p class="empty-text">
                        No certificate matched <strong>"{{ $search ?? request('search') }}"</strong>.
                    </p>
                    <a href="{{ route('admin.school-leaving-certificate.index') }}"
                       class="btn-scheme-reset">
                        Clear Search
                    </a>
                @else
                    <p class="empty-text">
                        No school leaving certificates have been created yet.
                    </p>
                    <a href="{{ route('admin.school-leaving-certificate.create') }}"
                       class="btn-scheme-create">
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
function confirmDelete(id) {
    const confirmed = confirm(
        'Are you sure you want to delete this School Leaving Certificate?'
    );

    if (confirmed) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush

@endsection