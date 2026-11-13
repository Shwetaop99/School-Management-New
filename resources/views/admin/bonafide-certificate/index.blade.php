@extends('layouts.app')

@section('title', 'Bonafide Certificates | Admin')
@section('page-title', 'Bonafide Certificates')

@section('content')

@php
    $totalCertificates = $certificates->total();

    $activeCertificates = $certificates->getCollection()
        ->where('status', 'active')
        ->count();

    $inactiveCertificates = $certificates->getCollection()
        ->where('status', 'inactive')
        ->count();

    $hasFilters = !empty($search);
@endphp

<div class="bonafide-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS / ERROR ALERTS --}}
        @if(session('success'))
            <div class="health-alert" role="status">
                <span class="success-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="health-alert health-alert-error" role="alert">
                <span class="success-icon error-icon"><i class="fas fa-exclamation"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="health-hero">
            <div class="health-hero-content">
                <div class="health-hero-icon">
                    <i class="fas fa-certificate"></i>
                </div>

                <div class="health-hero-text">
                    <div class="health-breadcrumb">
                        <span>Certificate Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Bonafide Certificates</span>
                    </div>

                    <h2>Bonafide Certificates</h2>

                    <p>
                        Manage, issue and maintain student bonafide certificates
                        from one place.
                    </p>

                    <div class="health-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Official Student Documentation</span>
                    </div>
                </div>
            </div>

            <div class="health-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-file-signature"></i>
                </div>

                <div class="hero-handwritten">Verified. Trusted. Official.</div>

                <p>
                    Issue clean, consistent bonafide certificates
                    whenever students need them.
                </p>

                <a href="{{ route('admin.bonafide.create') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-plus"></i>
                    Create Certificate
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="health-kpis">

            <article class="health-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Certificates</span>
                    <span class="kpi-icon">
                        <i class="fas fa-folder-open"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalCertificates) }}</div>
                <div class="kpi-caption">All certificates matching your filters</div>
            </article>

            <article class="health-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Certificates</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($activeCertificates) }}</div>
                <div class="kpi-caption">Currently active on this page</div>
            </article>

            <article class="health-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Certificates</span>
                    <span class="kpi-icon">
                        <i class="fas fa-pause-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($inactiveCertificates) }}</div>
                <div class="kpi-caption">Inactive on this page</div>
            </article>

            <article class="health-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Page Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-list"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($certificates->count()) }}</div>
                <div class="kpi-caption">Shown on the current page</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="health-paper-panel">

            <div class="health-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3>Certificate Records</h3>
                        <p>Search, view and manage bonafide certificates.</p>
                    </div>
                </div>

                <div class="health-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($certificates->total()) }}</strong>
                    Certificates
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.bonafide.index') }}"
                  method="GET"
                  class="health-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Find Certificates</h4>
                        <p>Search by certificate number, student name or student ID.</p>
                    </div>
                </div>

                <div class="health-filter-grid bonafide-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="search">Search Certificate</label>
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input id="search"
                                   type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   placeholder="Certificate number, student name or student ID..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i> Search
                        </button>

                        <a href="{{ route('admin.bonafide.index') }}"
                           class="diary-reset-btn"
                           title="Reset">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="health-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-filter"></i> Active filters
                        </span>

                        <span class="filter-chip">
                            <i class="fas fa-search"></i>
                            {{ $search }}
                        </span>
                    </div>

                    <span class="filter-result-count">
                        {{ number_format($certificates->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- RECORDS TABLE --}}
            @if($certificates->count())

                <div class="health-table-wrapper">
                    <table class="health-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Certificate</th>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Class</th>
                                <th>Issue Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="actions-heading">Actions</th>
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

                                    $initial = $student
                                        ? strtoupper(substr($student->first_name ?? 'S', 0, 1))
                                        : '?';
                                @endphp

                                <tr>

                                    <td>
                                        <span class="health-row-number">
                                            {{ ($certificates->firstItem() ?? 0) + $loop->index }}
                                        </span>
                                    </td>

                                    {{-- Certificate --}}
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar cert-avatar">
                                                <i class="fas fa-file-signature"></i>
                                            </div>
                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $certificate->certificate_no }}
                                                </div>
                                                <div class="student-id">
                                                    <i class="fas fa-certificate"></i>
                                                    Bonafide Certificate
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Student --}}
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                {{ $initial }}
                                            </div>
                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $studentName }}
                                                </div>
                                                @if($student && $student->gender)
                                                    <div class="student-id">
                                                        {{ $student->gender }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Student ID --}}
                                    <td>
                                        @if($student && $student->student_id)
                                            <span class="health-chip section-chip">
                                                {{ $student->student_id }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Class --}}
                                    <td>
                                        @if($student && (!empty($student->class) || !empty($student->section)))
                                            <span class="health-chip class-chip">
                                                {{ $student->class ?? '—' }}
                                                @if(!empty($student->section))
                                                    / {{ $student->section }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Issue Date --}}
                                    <td>
                                        @if($certificate->issue_date)
                                            <div class="health-date">
                                                <div class="date-primary">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $certificate->issue_date->format('d M Y') }}
                                                </div>
                                                <div class="date-caption">Issue Date</div>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Reason --}}
                                    <td>
                                        @if($certificate->reason)
                                            <span class="health-measure reason-text"
                                                  title="{{ $certificate->reason }}">
                                                {{ \Illuminate\Support\Str::limit($certificate->reason, 42) }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if($certificate->status === 'active')
                                            <span class="health-status good">
                                                <i class="fas fa-circle-check"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="health-status unknown">
                                                <i class="fas fa-circle-minus"></i>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="health-actions">

                                            <a href="{{ route('admin.bonafide.show', $certificate->id) }}"
                                               class="health-action-btn view"
                                               title="View Certificate"
                                               aria-label="View certificate">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.bonafide.edit', $certificate->id) }}"
                                               class="health-action-btn edit"
                                               title="Edit Certificate"
                                               aria-label="Edit certificate">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.bonafide.destroy', $certificate->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this certificate?');"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="health-action-btn delete"
                                                        title="Delete Certificate"
                                                        aria-label="Delete certificate">
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
                @if($certificates->hasPages())
                    <div class="health-pagination">
                        <div class="pagination-info">
                            Showing <strong>{{ $certificates->firstItem() }}</strong>
                            to <strong>{{ $certificates->lastItem() }}</strong>
                            of <strong>{{ $certificates->total() }}</strong> certificates
                        </div>

                        <div class="pagination-links">
                            {{ $certificates->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="health-card-footer">
                    <div>
                        Showing <strong>{{ $certificates->count() }}</strong>
                        certificate(s) on this page
                    </div>
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="health-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>

                    <h4>No Bonafide Certificates Found</h4>
                    <p>
                        @if($hasFilters)
                            No certificates match your search.
                            Try adjusting the search or clear the filters.
                        @else
                            No bonafide certificates have been created yet.
                            Create the first one to get started.
                        @endif
                    </p>

                    <div class="empty-state-actions">
                        @if($hasFilters)
                            <a href="{{ route('admin.bonafide.index') }}"
                               class="diary-primary-btn">
                                <i class="fas fa-rotate-left"></i> Clear Search
                            </a>
                        @endif

                        <a href="{{ route('admin.bonafide.create') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-plus"></i> Create Certificate
                        </a>
                    </div>
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* ==========================================================
   BONAFIDE CERTIFICATES — SCHOOL DIARY DESIGN SYSTEM
   Same tokens, surfaces, components & responsive rules
   as Student Health Records
========================================================== */

.bonafide-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.bonafide-page *,
.bonafide-page *::before,
.bonafide-page *::after {
    box-sizing: border-box;
}

.bonafide-page a,
.bonafide-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.bonafide-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.health-hero,
.health-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.health-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.health-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.health-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.health-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.health-hero-text {
    min-width: 0;
}

.health-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.health-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.health-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.health-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.health-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.health-hero-note i {
    font-size: 13px;
}

.health-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.health-hero-actions::before,
.health-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.health-hero-actions::before {
    top: -115px;
    right: -60px;
}

.health-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.hero-decoration {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.hero-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.health-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.diary-primary-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.health-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.health-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* ALERTS */

.health-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    font-weight: 700;
}

.health-alert-error {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    color: #347849;
    background: #deeedb;
}

.error-icon {
    color: #b95b50;
    background: #f8e4df;
}

/* KPI CARDS */

.health-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.health-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.health-kpi.blue { --kpi-color: #426b8a; }
.health-kpi.green { --kpi-color: #43835d; }
.health-kpi.orange { --kpi-color: #a87543; }
.health-kpi.red { --kpi-color: #b95b50; }

.health-kpi::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.health-kpi.blue::before { content: "TOTAL"; }
.health-kpi.green::before { content: "ACTIVE"; }
.health-kpi.orange::before { content: "INACTIVE"; }
.health-kpi.red::before { content: "PAGE"; }

.health-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.health-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.health-paper-panel {
    overflow: hidden;
}

.health-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: #fffefa;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.panel-title-icon,
.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.health-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.health-record-count i,
.health-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.health-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.health-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(140px, .8fr) minmax(140px, .8fr) minmax(140px, .8fr)
        minmax(220px, 1.8fr) auto;
    align-items: end;
    gap: 16px;
}

/* Bonafide only has search → simpler grid */
.bonafide-filter-grid {
    grid-template-columns: minmax(280px, 1fr) auto;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.diary-control,
.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.diary-control {
    padding: 0 12px;
    cursor: pointer;
}

.diary-control:hover,
.diary-search-input:hover {
    border-color: #bdcabb;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #829083;
    font-size: 13px;
}

.diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font: inherit;
}

.diary-search-input input::placeholder {
    color: #9a9f93;
}

.diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.diary-apply-btn,
.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-apply-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.diary-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* ACTIVE FILTERS */

.health-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
    background: #fffefa;
}

.active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.active-filter-label i {
    color: var(--green);
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 260px;
    padding: 6px 10px;
    overflow-wrap: anywhere;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 700;
}

.filter-chip i {
    color: var(--amber);
}

.filter-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* RECORDS TABLE */

.health-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.health-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.health-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.health-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.health-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.health-table tbody tr:hover {
    background: #f8f8f1;
}

.health-table tbody tr:last-child td {
    border-bottom: none;
}

.health-table .actions-heading {
    text-align: right;
}

.health-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 6px;
    border: 1px solid #e3e1d6;
    border-radius: 9px;
    color: #68776a;
    background: #f5f4ec;
    font-size: 10px;
    font-weight: 800;
}

/* STUDENT / CERT INFO */

.student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 180px;
}

.student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    overflow: hidden;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 14px;
    font-weight: 800;
}

.student-avatar.cert-avatar {
    color: var(--blue);
    background: #edf4f8;
    border-color: #d9e2e9;
    font-size: 15px;
}

.student-details {
    min-width: 0;
}

.student-name {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-id {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.student-id i {
    color: var(--amber);
}

/* CHIPS */

.health-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 6px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
}

.health-chip.class-chip {
    border-color: #dce4d8;
    color: #477858;
    background: #edf6e9;
}

.health-chip.section-chip {
    border-color: #d9e2e9;
    color: #426b8a;
    background: #edf4f8;
}

/* DATES AND MEASUREMENTS */

.health-date {
    min-width: 105px;
}

.date-primary {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.date-primary i {
    color: var(--blue);
}

.date-caption {
    margin-top: 5px;
    color: #879083;
    font-size: 9px;
}

.health-measure {
    color: #34443a;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.reason-text {
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
    font-weight: 600;
    color: #5d6b60;
}

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* HEALTH STATUS */

.health-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.health-status.good {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.health-status.attention {
    border-color: #e7dfc9;
    color: #765e32;
    background: #faf5e8;
}

.health-status.referred {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.health-status.unknown {
    border-color: #e4e0d4;
    color: #7b857b;
    background: #f8f7f0;
}

/* ACTION BUTTONS */

.health-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
    white-space: nowrap;
}

.health-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e2e1d7;
    border-radius: 9px;
    color: #657369;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.health-action-btn:hover {
    transform: translateY(-2px);
}

.health-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.health-action-btn.edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.health-action-btn.delete {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.health-action-btn.delete:hover {
    border-color: #e0b8ae;
    color: #fff;
    background: #b95b50;
}

/* PAGINATION */

.health-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
    background: #fffefa;
}

.pagination-info {
    color: #7b857b;
    font-size: 10px;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.pagination-links {
    min-width: 0;
}

.pagination-links nav {
    margin: 0;
}

.pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--line);
    border-radius: 8px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
    box-shadow: none;
}

.pagination-links .page-link:hover {
    border-color: #b5c5b0;
    color: #43835d;
    background: #f2f4e9;
}

.pagination-links .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* FOOTER */

.health-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.health-card-footer strong {
    color: var(--ink);
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.footer-status i {
    color: #43835d;
}

/* EMPTY STATE */

.health-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.health-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.health-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

.empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

/* TABLET */

@media (max-width: 1200px) {
    .health-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .health-filter-grid,
    .bonafide-filter-grid {
        grid-template-columns: 1fr;
    }

    .diary-filter-actions {
        justify-content: flex-start;
    }
}

@media (max-width: 900px) {
    .health-hero {
        grid-template-columns: 1fr;
    }

    .health-hero-actions {
        min-height: 220px;
    }

    .health-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .bonafide-page .container-fluid {
        padding: 18px 14px !important;
    }

    .health-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .health-hero::before {
        left: 18px;
    }

    .health-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .health-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .health-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .health-hero-text h2 {
        font-size: 24px;
    }

    .health-hero-text p {
        font-size: 11px;
    }

    .health-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .health-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .health-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .health-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .health-kpi .kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .kpi-value {
        font-size: 29px;
    }

    .kpi-caption {
        font-size: 9px;
    }

    .health-panel-header {
        align-items: flex-start;
        padding: 16px;
    }

    .panel-title-wrap {
        gap: 10px;
    }

    .panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .panel-title-wrap p {
        font-size: 10px;
    }

    .health-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .health-filter-panel {
        padding: 17px 15px;
    }

    .filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .filter-heading h4 {
        font-size: 16px;
    }

    .health-filter-grid,
    .bonafide-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .health-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .health-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .health-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .health-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .health-kpis {
        grid-template-columns: 1fr;
    }

    .health-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .health-record-count {
        margin-left: 46px;
    }

    .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .diary-apply-btn,
    .diary-reset-btn {
        width: 100%;
    }

    .active-filter-items {
        align-items: flex-start;
    }

    .filter-chip {
        max-width: 100%;
    }
}

/* ACCESSIBILITY */

.bonafide-page a:focus-visible,
.bonafide-page button:focus-visible,
.bonafide-page input:focus-visible,
.bonafide-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .bonafide-page *,
    .bonafide-page *::before,
    .bonafide-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection