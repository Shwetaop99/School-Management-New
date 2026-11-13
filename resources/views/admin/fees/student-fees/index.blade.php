@extends('layouts.app')

@section('title', 'Student Fees')
@section('page-title', 'Student Fees')

@section('content')

@php
    $hasFilters = request()->filled('search')
        || request()->filled('academic_year')
        || request()->filled('status');
@endphp

<div class="student-fees-page">
    <div class="sf-container">

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="sf-alert sf-success" role="status">
                <span class="sf-alert-icon">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="sf-alert sf-error" role="status">
                <span class="sf-alert-icon">
                    <i class="bi bi-exclamation-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="sf-hero">
            <div class="sf-hero-content">
                <div class="sf-hero-icon">
                    <i class="bi bi-receipt-cutoff" aria-hidden="true"></i>
                </div>

                <div class="sf-hero-text">
                    <div class="sf-breadcrumb">
                        <span>Fee Management</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        <span>Student Fees</span>
                    </div>

                    <h2>Student Fees</h2>

                    <p>
                        Manage student fee assignments, payments, concessions,
                        late fines and outstanding balances from one place.
                    </p>

                    <div class="sf-hero-note">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        <span>School Fees &amp; Payment Management</span>
                    </div>
                </div>
            </div>

            <div class="sf-hero-actions">
                <div class="sf-hero-decoration">
                    <i class="bi bi-wallet2" aria-hidden="true"></i>
                </div>

                <div class="sf-handwritten">Clear Fees. Timely Payments.</div>

                <p>
                    Keep student fee assignments accurate and payment
                    balances up to date for every academic year.
                </p>

                <a href="{{ route('admin.fees.student-fees.create') }}"
                   class="sf-primary-btn">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    Assign Fee
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="sf-kpis">

            <article class="sf-kpi blue" data-tab="TOTAL">
                <div class="sf-kpi-top">
                    <span class="sf-kpi-label">Total Student Fees</span>
                    <span class="sf-kpi-icon">
                        <i class="bi bi-receipt" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="sf-kpi-value">{{ number_format($totalFees) }}</div>
                <div class="sf-kpi-caption">All assigned student fees</div>
            </article>

            <article class="sf-kpi amber" data-tab="PENDING">
                <div class="sf-kpi-top">
                    <span class="sf-kpi-label">Pending Fees</span>
                    <span class="sf-kpi-icon">
                        <i class="bi bi-clock-history" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="sf-kpi-value">{{ number_format($pendingFees) }}</div>
                <div class="sf-kpi-caption">Fees awaiting payment</div>
            </article>

            <article class="sf-kpi violet" data-tab="PARTIAL">
                <div class="sf-kpi-top">
                    <span class="sf-kpi-label">Partial Payments</span>
                    <span class="sf-kpi-icon">
                        <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="sf-kpi-value">{{ number_format($partialFees) }}</div>
                <div class="sf-kpi-caption">Fees with remaining balance</div>
            </article>

            <article class="sf-kpi green" data-tab="PAID">
                <div class="sf-kpi-top">
                    <span class="sf-kpi-label">Paid Fees</span>
                    <span class="sf-kpi-icon">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="sf-kpi-value">{{ number_format($paidFees) }}</div>
                <div class="sf-kpi-caption">Fees fully cleared</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="sf-paper-panel">

            <div class="sf-panel-header">
                <div class="sf-title-wrap">
                    <div class="sf-title-icon">
                        <i class="bi bi-journal-text" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3>Student Fee Records</h3>
                        <p>View and manage assigned student fees and balances.</p>
                    </div>
                </div>

                <div class="sf-record-count">
                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                    <strong>{{ number_format($studentFees->total()) }}</strong>
                    Records
                </div>
            </div>

            {{-- FILTERS --}}
            <form action="{{ route('admin.fees.student-fees.index') }}"
                  method="GET"
                  class="sf-filter-panel">

                <div class="sf-filter-heading">
                    <div class="sf-filter-heading-icon">
                        <i class="bi bi-sliders" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h4>Find Student Fees</h4>
                        <p>
                            Search fee records by student, academic year
                            or payment status.
                        </p>
                    </div>
                </div>

                <div class="sf-filter-grid">

                    <div class="sf-filter-group">
                        <label for="sf-search">Search Student</label>

                        <div class="sf-search-input">
                            <i class="bi bi-search" aria-hidden="true"></i>

                            <input id="sf-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Student ID or name..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="sf-filter-group">
                        <label for="sf-academic-year">Academic Year</label>

                        <select id="sf-academic-year"
                                name="academic_year"
                                class="sf-control">
                            <option value="">All Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sf-filter-group">
                        <label for="sf-status">Payment Status</label>

                        <select id="sf-status"
                                name="status"
                                class="sf-control">
                            <option value="">All Statuses</option>

                            <option value="Pending"
                                {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Partial"
                                {{ request('status') === 'Partial' ? 'selected' : '' }}>
                                Partial
                            </option>

                            <option value="Paid"
                                {{ request('status') === 'Paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="Overdue"
                                {{ request('status') === 'Overdue' ? 'selected' : '' }}>
                                Overdue
                            </option>
                        </select>
                    </div>

                    <div class="sf-filter-actions">
                        <button type="submit" class="sf-apply-btn">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.fees.student-fees.index') }}"
                           class="sf-reset-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="sf-active-filters">
                    <div class="sf-filter-items">
                        <span class="sf-filter-label">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                            Active filters
                        </span>

                        @if(request()->filled('search'))
                            <span class="sf-filter-chip">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request()->filled('academic_year'))
                            <span class="sf-filter-chip">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                {{ request('academic_year') }}
                            </span>
                        @endif

                        @if(request()->filled('status'))
                            <span class="sf-filter-chip">
                                <i class="bi bi-circle-half" aria-hidden="true"></i>
                                {{ request('status') }}
                            </span>
                        @endif
                    </div>

                    <span class="sf-result-count">
                        {{ number_format($studentFees->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- TABLE --}}
            @if($studentFees->count() > 0)

                <div class="sf-table-wrapper">
                    <table class="sf-table">
                        <thead>
                            <tr>
                                <th scope="col">Student</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Fee Structure</th>
                                <th scope="col">Total</th>
                                <th scope="col">Concession</th>
                                <th scope="col">Late Fine</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Remaining</th>
                                <th scope="col">Due Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="sf-actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($studentFees as $studentFee)
                                @php
                                    $statusClass = match($studentFee->status) {
                                        'Paid' => 'paid',
                                        'Partial' => 'partial',
                                        'Overdue' => 'overdue',
                                        default => 'pending',
                                    };
                                @endphp

                                <tr>
                                    <td>
                                        <div class="sf-student-info">
                                            <div class="sf-student-avatar">
                                                @if($studentFee->student && !empty($studentFee->student->profile_image))
                                                    <img
                                                        src="{{ asset('storage/' . $studentFee->student->profile_image) }}"
                                                        alt="{{ $studentFee->student->first_name ?? 'Student' }}"
                                                    >
                                                @else
                                                    {{ strtoupper(substr($studentFee->student->first_name ?? 'S', 0, 1)) }}
                                                @endif
                                            </div>

                                            <div class="sf-student-details">
                                                <div class="sf-student-name"
                                                     title="{{ trim(($studentFee->student->first_name ?? '') . ' ' . ($studentFee->student->middle_name ?? '') . ' ' . ($studentFee->student->last_name ?? '')) }}">
                                                    {{ $studentFee->student->first_name ?? '' }}
                                                    {{ $studentFee->student->middle_name ?? '' }}
                                                    {{ $studentFee->student->last_name ?? '' }}
                                                </div>

                                                <div class="sf-student-id">
                                                    <i class="bi bi-id-badge" aria-hidden="true"></i>
                                                    {{ $studentFee->student->student_id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="sf-info-cell">
                                            <span class="sf-info-icon blue">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                            </span>

                                            <div class="sf-info-text">
                                                <strong>{{ $studentFee->academic_year }}</strong>
                                                <span>Academic Year</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($studentFee->feeStructure)
                                            <div class="sf-structure-cell">
                                                <strong>{{ $studentFee->feeStructure->structure_name }}</strong>

                                                <span>
                                                    {{ $studentFee->feeStructure->schoolClass->class_name ?? 'N/A' }}

                                                    @if($studentFee->feeStructure->section)
                                                        · {{ $studentFee->feeStructure->section->section_name }}
                                                    @else
                                                        · All Sections
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="sf-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="sf-amount">
                                            ₹{{ number_format((float) $studentFee->total_amount, 2) }}
                                        </div>
                                        <div class="sf-cell-caption">Total Fee</div>
                                    </td>

                                    <td>
                                        <div class="sf-amount sf-concession">
                                            ₹{{ number_format((float) $studentFee->discount_amount, 2) }}
                                        </div>
                                        <div class="sf-cell-caption">Concession</div>
                                    </td>

                                    <td>
                                        <div class="sf-amount sf-fine">
                                            ₹{{ number_format((float) $studentFee->fine_amount, 2) }}
                                        </div>
                                        <div class="sf-cell-caption">Late Fine</div>
                                    </td>

                                    <td>
                                        <div class="sf-amount sf-paid">
                                            ₹{{ number_format((float) $studentFee->paid_amount, 2) }}
                                        </div>
                                        <div class="sf-cell-caption">Amount Paid</div>
                                    </td>

                                    <td>
                                        <div class="sf-amount sf-remaining">
                                            ₹{{ number_format((float) $studentFee->balance_amount, 2) }}
                                        </div>
                                        <div class="sf-cell-caption">Balance Due</div>
                                    </td>

                                    <td>
                                        @if($studentFee->due_date)
                                            <div class="sf-date">
                                                <div class="sf-date-primary">
                                                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                                    {{ $studentFee->due_date->format('d M Y') }}
                                                </div>
                                                <div class="sf-cell-caption">Due Date</div>
                                            </div>
                                        @else
                                            <span class="sf-muted">Not set</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="sf-status {{ $statusClass }}">
                                            <span class="sf-status-dot" aria-hidden="true"></span>
                                            {{ $studentFee->status }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="sf-actions">
                                            <a href="{{ route('admin.fees.student-fees.show', $studentFee) }}"
                                               class="sf-action-btn view"
                                               title="View Student Fee"
                                               aria-label="View student fee for {{ $studentFee->student->student_id ?? 'student' }}">
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>

                                            <form action="{{ route('admin.fees.student-fees.destroy', $studentFee) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this student fee?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="sf-action-btn delete"
                                                        title="Delete Student Fee"
                                                        aria-label="Delete student fee for {{ $studentFee->student->student_id ?? 'student' }}">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
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
                @if($studentFees->hasPages())
                    <div class="sf-pagination">
                        <div class="sf-pagination-info">
                            Showing <strong>{{ $studentFees->firstItem() }}</strong>
                            to <strong>{{ $studentFees->lastItem() }}</strong>
                            of <strong>{{ $studentFees->total() }}</strong> records
                        </div>

                        <div class="sf-pagination-links">
                            {{ $studentFees->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="sf-card-footer">
                    <div>
                        Showing <strong>{{ $studentFees->count() }}</strong>
                        record(s) on this page
                    </div>

                    <div class="sf-footer-status">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="sf-empty-state">
                    <div class="sf-empty-icon">
                        <i class="bi bi-receipt" aria-hidden="true"></i>
                    </div>

                    @if($hasFilters)
                        <h4>No Matching Fees Found</h4>
                        <p>
                            No student fee records match your current search
                            or selected filters. Try adjusting your filters.
                        </p>

                        <a href="{{ route('admin.fees.student-fees.index') }}"
                           class="sf-primary-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Clear Filters
                        </a>
                    @else
                        <h4>No Student Fees Yet</h4>
                        <p>
                            Assign your first student fee to start managing
                            payments, concessions and outstanding balances.
                        </p>

                        <a href="{{ route('admin.fees.student-fees.create') }}"
                           class="sf-primary-btn">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            Assign First Fee
                        </a>
                    @endif
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* STUDENT FEES — TRANSPORT PAGE DESIGN SYSTEM
   Scoped so it does not affect other pages. */

.student-fees-page {
    --sf-ink: #263b35;
    --sf-text: #39483e;
    --sf-muted: #7b857b;
    --sf-line: #e5e1d5;
    --sf-paper: #fffefa;
    --sf-chalk: #f5f3e9;
    --sf-blue: #426b8a;
    --sf-green: #43835d;
    --sf-amber: #a87543;
    --sf-red: #b95b50;
    --sf-violet: #7a4fb5;
    --sf-serif: var(--serif, Georgia, "Times New Roman", serif);
    --sf-hand: var(--hand, "Caveat", cursive);

    min-height: calc(100vh - 70px);
    color: var(--sf-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.student-fees-page *,
.student-fees-page *::before,
.student-fees-page *::after {
    box-sizing: border-box;
}

.student-fees-page a,
.student-fees-page button,
.student-fees-page .sf-kpi {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.student-fees-page .sf-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.student-fees-page .sf-hero,
.student-fees-page .sf-paper-panel {
    border: 1px solid var(--sf-line);
    border-radius: 15px;
    background: var(--sf-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.student-fees-page .sf-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.student-fees-page .sf-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.student-fees-page .sf-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.student-fees-page .sf-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--sf-ink);
    background: var(--sf-chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.student-fees-page .sf-hero-text {
    min-width: 0;
}

.student-fees-page .sf-breadcrumb {
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

.student-fees-page .sf-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.student-fees-page .sf-hero-text h2 {
    margin: 0 0 11px;
    color: var(--sf-ink);
    font-family: var(--sf-serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.student-fees-page .sf-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--sf-muted);
    font-size: 12px;
    line-height: 1.9;
}

.student-fees-page .sf-hero-note {
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

.student-fees-page .sf-hero-note i {
    font-size: 13px;
}

.student-fees-page .sf-hero-actions {
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

.student-fees-page .sf-hero-actions::before,
.student-fees-page .sf-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.student-fees-page .sf-hero-actions::before {
    top: -115px;
    right: -60px;
}

.student-fees-page .sf-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.student-fees-page .sf-hero-decoration {
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

.student-fees-page .sf-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--sf-hand);
    font-size: 23px;
    line-height: 1.4;
}

.student-fees-page .sf-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* BUTTONS */

.student-fees-page .sf-primary-btn,
.student-fees-page .sf-apply-btn,
.student-fees-page .sf-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 16px;
    border: 1px solid var(--sf-ink);
    border-radius: 9px;
    color: #fff;
    background: var(--sf-ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.student-fees-page .sf-primary-btn {
    position: relative;
    z-index: 1;
}

.student-fees-page .sf-primary-btn:hover,
.student-fees-page .sf-apply-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.student-fees-page .sf-hero-actions .sf-primary-btn {
    border-color: #e8e0c9;
    color: var(--sf-ink);
    background: #f1ead8;
}

.student-fees-page .sf-hero-actions .sf-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--sf-ink);
    background: #fff5dc;
}

.student-fees-page .sf-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: var(--sf-paper);
}

.student-fees-page .sf-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--sf-ink);
    background: #f0f3e9;
}

/* ALERTS */

.student-fees-page .sf-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

.student-fees-page .sf-success {
    border: 1px solid #d2e5d1;
    color: #347849;
    background: #f1f8ef;
}

.student-fees-page .sf-error {
    border: 1px solid #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.student-fees-page .sf-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: rgba(255, 255, 255, .7);
}

/* KPI CARDS */

.student-fees-page .sf-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.student-fees-page .sf-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--sf-line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--sf-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.student-fees-page .sf-kpi.blue { --kpi-color: #426b8a; }
.student-fees-page .sf-kpi.amber { --kpi-color: #a87543; }
.student-fees-page .sf-kpi.violet { --kpi-color: #7a4fb5; }
.student-fees-page .sf-kpi.green { --kpi-color: #43835d; }

.student-fees-page .sf-kpi::before {
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
    content: attr(data-tab);
}

.student-fees-page .sf-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.student-fees-page .sf-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.student-fees-page .sf-kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.student-fees-page .sf-kpi-icon {
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

.student-fees-page .sf-kpi-value {
    color: var(--sf-ink);
    font-family: var(--sf-serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.student-fees-page .sf-kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.student-fees-page .sf-paper-panel {
    overflow: hidden;
}

.student-fees-page .sf-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.student-fees-page .sf-title-wrap,
.student-fees-page .sf-filter-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.student-fees-page .sf-title-icon,
.student-fees-page .sf-filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--sf-ink);
    background: #f1f2e8;
    font-size: 16px;
}

.student-fees-page .sf-title-wrap h3,
.student-fees-page .sf-filter-heading h4 {
    margin: 0;
    color: var(--sf-ink);
    font-family: var(--sf-serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.student-fees-page .sf-title-wrap p,
.student-fees-page .sf-filter-heading p {
    margin: 5px 0 0;
    color: var(--sf-muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-fees-page .sf-record-count {
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

.student-fees-page .sf-record-count i,
.student-fees-page .sf-record-count strong {
    color: var(--sf-ink);
}

/* FILTERS */

.student-fees-page .sf-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--sf-line);
    background: #faf9f3;
}

.student-fees-page .sf-filter-heading {
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.student-fees-page .sf-filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.student-fees-page .sf-filter-heading h4 {
    font-size: 17px;
}

.student-fees-page .sf-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(220px, 2fr)
        minmax(150px, 1fr)
        minmax(140px, .9fr)
        auto;
    align-items: end;
    gap: 16px;
}

.student-fees-page .sf-filter-group {
    min-width: 0;
}

.student-fees-page .sf-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.student-fees-page .sf-control,
.student-fees-page .sf-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--sf-text);
    background: var(--sf-paper);
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.student-fees-page .sf-control {
    padding: 0 12px;
    cursor: pointer;
}

.student-fees-page .sf-control:hover,
.student-fees-page .sf-search-input:hover {
    border-color: #bdcabb;
}

.student-fees-page .sf-control:focus,
.student-fees-page .sf-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.student-fees-page .sf-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.student-fees-page .sf-search-input > i {
    color: #829083;
    font-size: 13px;
}

.student-fees-page .sf-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    color: var(--sf-text);
    background: transparent;
    font: inherit;
}

.student-fees-page .sf-search-input input::placeholder {
    color: #9a9f93;
}

.student-fees-page .sf-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

/* ACTIVE FILTERS */

.student-fees-page .sf-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
}

.student-fees-page .sf-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.student-fees-page .sf-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.student-fees-page .sf-filter-label i {
    color: var(--sf-green);
}

.student-fees-page .sf-filter-chip {
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

.student-fees-page .sf-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* TABLE */

.student-fees-page .sf-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.student-fees-page .sf-table {
    width: 100%;
    min-width: 1450px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.student-fees-page .sf-table thead th {
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

.student-fees-page .sf-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--sf-text);
    font-size: 11px;
    vertical-align: middle;
    white-space: nowrap;
}

.student-fees-page .sf-table tbody tr {
    background: var(--sf-paper);
    transition: background .18s ease;
}

.student-fees-page .sf-table tbody tr:hover {
    background: #f8f8f1;
}

.student-fees-page .sf-table tbody tr:last-child td {
    border-bottom: none;
}

.student-fees-page .sf-table .sf-actions-heading {
    text-align: right;
}

/* STUDENT CELL */

.student-fees-page .sf-student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.student-fees-page .sf-student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    overflow: hidden;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--sf-ink);
    background: #edf1e7;
    font-size: 14px;
    font-weight: 800;
}

.student-fees-page .sf-student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-fees-page .sf-student-details {
    min-width: 0;
}

.student-fees-page .sf-student-name {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-fees-page .sf-student-id {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.student-fees-page .sf-student-id i {
    color: var(--sf-amber);
}

/* INFO CELLS */

.student-fees-page .sf-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 115px;
}

.student-fees-page .sf-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 13px;
}

.student-fees-page .sf-info-icon.blue {
    border-color: #d9e4ec;
    color: var(--sf-blue);
    background: #edf4f8;
}

.student-fees-page .sf-info-text {
    min-width: 0;
}

.student-fees-page .sf-info-text strong {
    display: block;
    max-width: 155px;
    overflow: hidden;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-fees-page .sf-info-text span,
.student-fees-page .sf-cell-caption {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

.student-fees-page .sf-structure-cell strong {
    display: block;
    max-width: 220px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-fees-page .sf-structure-cell span {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
    white-space: normal;
}

/* AMOUNTS AND DATES */

.student-fees-page .sf-amount {
    color: var(--sf-ink);
    font-family: var(--sf-serif);
    font-size: 14px;
    font-weight: 700;
}

.student-fees-page .sf-concession { color: #43835d; }
.student-fees-page .sf-fine { color: #b95b50; }
.student-fees-page .sf-paid { color: #426b8a; }
.student-fees-page .sf-remaining { color: #a87543; }

.student-fees-page .sf-date {
    min-width: 105px;
}

.student-fees-page .sf-date-primary {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
}

.student-fees-page .sf-date-primary i {
    color: var(--sf-blue);
}

.student-fees-page .sf-muted {
    color: #929a8e;
    font-size: 12px;
}

/* STATUS */

.student-fees-page .sf-status {
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

.student-fees-page .sf-status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.student-fees-page .sf-status.pending {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.student-fees-page .sf-status.partial {
    border-color: #d9d0ea;
    color: #7a4fb5;
    background: #f4f0fa;
}

.student-fees-page .sf-status.paid {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.student-fees-page .sf-status.overdue {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

/* ACTIONS */

.student-fees-page .sf-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.student-fees-page .sf-actions form {
    display: inline-flex;
    margin: 0;
}

.student-fees-page .sf-action-btn {
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
    background: var(--sf-paper);
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.student-fees-page .sf-action-btn:hover {
    transform: translateY(-2px);
}

.student-fees-page .sf-action-btn.view:hover {
    border-color: #cbdce5;
    color: var(--sf-blue);
    background: #edf4f8;
}

.student-fees-page .sf-action-btn.delete:hover {
    border-color: #edcbc3;
    color: var(--sf-red);
    background: #fff0ec;
}

/* PAGINATION AND FOOTER */

.student-fees-page .sf-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
}

.student-fees-page .sf-pagination-info {
    color: var(--sf-muted);
    font-size: 10px;
}

.student-fees-page .sf-pagination-info strong,
.student-fees-page .sf-card-footer strong {
    color: var(--sf-ink);
}

.student-fees-page .sf-pagination-links {
    min-width: 0;
}

.student-fees-page .sf-pagination-links nav {
    margin: 0;
}

.student-fees-page .sf-pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.student-fees-page .sf-pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--sf-line);
    border-radius: 8px !important;
    color: var(--sf-ink);
    background: var(--sf-paper);
    font-size: 11px;
    box-shadow: none;
}

.student-fees-page .sf-pagination-links .page-link:hover {
    border-color: #b5c5b0;
    color: var(--sf-green);
    background: #f2f4e9;
}

.student-fees-page .sf-pagination-links .page-item.active .page-link {
    border-color: var(--sf-ink);
    color: #fff;
    background: var(--sf-ink);
}

.student-fees-page .sf-pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

.student-fees-page .sf-card-footer {
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

.student-fees-page .sf-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.student-fees-page .sf-footer-status i {
    color: var(--sf-green);
}

/* EMPTY STATE */

.student-fees-page .sf-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.student-fees-page .sf-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--sf-ink);
    background: var(--sf-chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.student-fees-page .sf-empty-state h4 {
    margin: 0 0 10px;
    color: var(--sf-ink);
    font-family: var(--sf-serif);
    font-size: 22px;
    font-weight: 700;
}

.student-fees-page .sf-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--sf-muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .student-fees-page .sf-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 42px;
    }

    .student-fees-page .sf-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .student-fees-page .sf-hero {
        grid-template-columns: 1fr;
    }

    .student-fees-page .sf-hero-actions {
        min-height: 220px;
    }

    .student-fees-page .sf-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .student-fees-page .sf-container {
        padding: 18px 14px;
    }

    .student-fees-page .sf-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .student-fees-page .sf-hero::before {
        left: 18px;
    }

    .student-fees-page .sf-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .student-fees-page .sf-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .student-fees-page .sf-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .student-fees-page .sf-hero-text h2 {
        font-size: 24px;
    }

    .student-fees-page .sf-hero-text p {
        font-size: 11px;
    }

    .student-fees-page .sf-hero-note {
        align-items: flex-start;
        line-height: 1.7;
    }

    .student-fees-page .sf-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .student-fees-page .sf-handwritten {
        font-size: 21px;
    }

    .student-fees-page .sf-kpis {
        column-gap: 15px;
        row-gap: 40px;
        margin-top: 31px;
    }

    .student-fees-page .sf-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .student-fees-page .sf-kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .student-fees-page .sf-kpi-value {
        font-size: 29px;
    }

    .student-fees-page .sf-kpi-caption {
        font-size: 9px;
    }

    .student-fees-page .sf-panel-header {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }

    .student-fees-page .sf-title-wrap {
        gap: 10px;
    }

    .student-fees-page .sf-title-icon,
    .student-fees-page .sf-filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .student-fees-page .sf-title-wrap h3,
    .student-fees-page .sf-filter-heading h4 {
        font-size: 16px;
    }

    .student-fees-page .sf-title-wrap p {
        font-size: 10px;
    }

    .student-fees-page .sf-filter-panel {
        padding: 17px 15px;
    }

    .student-fees-page .sf-filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .student-fees-page .sf-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .student-fees-page .sf-filter-actions > * {
        flex: 1;
    }

    .student-fees-page .sf-active-filters,
    .student-fees-page .sf-pagination,
    .student-fees-page .sf-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .student-fees-page .sf-result-count {
        white-space: normal;
    }

    .student-fees-page .sf-pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .student-fees-page .sf-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .student-fees-page .sf-kpis {
        grid-template-columns: 1fr;
    }

    .student-fees-page .sf-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .student-fees-page .sf-filter-chip {
        max-width: 100%;
    }
}

/* ACCESSIBILITY AND REDUCED MOTION */

.student-fees-page a:focus-visible,
.student-fees-page button:focus-visible,
.student-fees-page input:focus-visible,
.student-fees-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .student-fees-page *,
    .student-fees-page *::before,
    .student-fees-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection