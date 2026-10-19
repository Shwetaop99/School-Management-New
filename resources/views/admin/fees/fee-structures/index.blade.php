@extends('layouts.app')

@section('title', 'Fee Structures')
@section('page-title', 'Fee Structures')

@section('content')

@php
    $fsTotal = (int) $totalStructures;
    $fsActive = (int) $activeStructures;
    $fsPct = $fsTotal > 0
        ? round(($fsActive / $fsTotal) * 100)
        : 0;

    $hasFilters = request()->filled('search')
        || request()->filled('academic_year')
        || request()->filled('status');
@endphp

<div class="fee-structures-page">
    <div class="fs-container">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="fs-alert" role="status">
                <span class="fs-success-icon">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="fs-hero">
            <div class="fs-hero-content">
                <div class="fs-hero-icon">
                    <i class="bi bi-receipt" aria-hidden="true"></i>
                </div>

                <div class="fs-hero-text">
                    <div class="fs-breadcrumb">
                        <span>Fee Management</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        <span>Fee Structures</span>
                    </div>

                    <h2>Fee Structures</h2>

                    <p>
                        Create and manage class-wise and section-wise fee
                        structures, academic years and fee items from one place.
                    </p>

                    <div class="fs-hero-note">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        <span>School Fees &amp; Academic Planning Management</span>
                    </div>
                </div>
            </div>

            <div class="fs-hero-actions">
                <div class="fs-hero-decoration">
                    <i class="bi bi-journal-check" aria-hidden="true"></i>
                </div>

                <div class="fs-handwritten">Clear Plans. Confident Parents.</div>

                <p>
                    A clear fee plan today saves confusion tomorrow.
                    Keep your school fee structures organised and up to date.
                </p>

                <a href="{{ route('admin.fees.fee-structures.create') }}"
                   class="fs-primary-btn">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    Add Fee Structure
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="fs-kpis">

            <article class="fs-kpi blue" data-tab="STRUCTURES">
                <div class="fs-kpi-top">
                    <span class="fs-kpi-label">Total Structures</span>
                    <span class="fs-kpi-icon">
                        <i class="bi bi-diagram-3" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="fs-kpi-value">{{ number_format($fsTotal) }}</div>
                <div class="fs-kpi-caption">All configured fee structures</div>
            </article>

            <article class="fs-kpi green" data-tab="ACTIVE">
                <div class="fs-kpi-top">
                    <span class="fs-kpi-label">Active Structures</span>
                    <span class="fs-kpi-icon">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="fs-kpi-value">{{ number_format($fsActive) }}</div>
                <div class="fs-kpi-caption">Currently active fee structures</div>
            </article>

            <article class="fs-kpi orange" data-tab="OVERVIEW">
                <div class="fs-kpi-top">
                    <span class="fs-kpi-label">Active Percentage</span>
                    <span class="fs-kpi-icon">
                        <i class="bi bi-pie-chart" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="fs-kpi-value">{{ $fsPct }}%</div>
                <div class="fs-kpi-caption">Active share of all fee structures</div>
            </article>

            <article class="fs-kpi red" data-tab="INACTIVE">
                <div class="fs-kpi-top">
                    <span class="fs-kpi-label">Inactive Structures</span>
                    <span class="fs-kpi-icon">
                        <i class="bi bi-pause-circle" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="fs-kpi-value">
                    {{ number_format((int) $inactiveStructures) }}
                </div>
                <div class="fs-kpi-caption">Currently inactive fee structures</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="fs-paper-panel">

            <div class="fs-panel-header">
                <div class="fs-title-wrap">
                    <div class="fs-title-icon">
                        <i class="bi bi-journal-text" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3>All Fee Structures</h3>
                        <p>View and manage class-wise and section-wise fees.</p>
                    </div>
                </div>

                <div class="fs-record-count">
                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                    <strong>{{ number_format($feeStructures->total()) }}</strong>
                    Structures
                </div>
            </div>

            {{-- FILTERS --}}
            <form action="{{ route('admin.fees.fee-structures.index') }}"
                  method="GET"
                  class="fs-filter-panel">

                <div class="fs-filter-heading">
                    <div class="fs-filter-heading-icon">
                        <i class="bi bi-sliders" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h4>Find Fee Structures</h4>
                        <p>
                            Search structures by name, academic year,
                            class, section or status.
                        </p>
                    </div>
                </div>

                <div class="fs-filter-grid">

                    <div class="fs-filter-group">
                        <label for="fs-search">Search Structures</label>

                        <div class="fs-search-input">
                            <i class="bi bi-search" aria-hidden="true"></i>

                            <input id="fs-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Structure, year, class or section..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="fs-filter-group">
                        <label for="fs-academic-year">Academic Year</label>

                        <select id="fs-academic-year"
                                name="academic_year"
                                class="fs-control">
                            <option value="">All Academic Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fs-filter-group">
                        <label for="fs-status">Structure Status</label>

                        <select id="fs-status"
                                name="status"
                                class="fs-control">
                            <option value="">All Statuses</option>

                            <option value="Active"
                                {{ request('status') === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ request('status') === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="fs-filter-actions">
                        <button type="submit" class="fs-apply-btn">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            Search
                        </button>

                        <a href="{{ route('admin.fees.fee-structures.index') }}"
                           class="fs-reset-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="fs-active-filters">
                    <div class="fs-filter-items">
                        <span class="fs-filter-label">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                            Active filters
                        </span>

                        @if(request()->filled('search'))
                            <span class="fs-filter-chip">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request()->filled('academic_year'))
                            <span class="fs-filter-chip">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                {{ request('academic_year') }}
                            </span>
                        @endif

                        @if(request()->filled('status'))
                            <span class="fs-filter-chip">
                                <i class="bi bi-circle-half" aria-hidden="true"></i>
                                {{ request('status') }}
                            </span>
                        @endif
                    </div>

                    <span class="fs-result-count">
                        {{ number_format($feeStructures->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- TABLE --}}
            @if($feeStructures->count() > 0)

                <div class="fs-table-wrapper">
                    <table class="fs-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fee Structure</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Class</th>
                                <th scope="col">Section</th>
                                <th scope="col">Fee Items</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="fs-actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($feeStructures as $feeStructure)
                                @php
                                    $totalAmount = $feeStructure->items->sum('amount');
                                    $feeCount = $feeStructure->items->count();
                                    $isActive = $feeStructure->status === 'Active';
                                @endphp

                                <tr>
                                    <td>
                                        <span class="fs-row-number">
                                            {{ $feeStructures->firstItem() + $loop->index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fs-structure-info">
                                            <div class="fs-structure-avatar">
                                                <i class="bi bi-receipt" aria-hidden="true"></i>
                                            </div>

                                            <div class="fs-structure-details">
                                                <div class="fs-structure-name"
                                                     title="{{ $feeStructure->structure_name }}">
                                                    {{ $feeStructure->structure_name }}
                                                </div>

                                                <div class="fs-structure-id">
                                                    <i class="bi bi-hash" aria-hidden="true"></i>
                                                    Structure ID: {{ $feeStructure->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fs-info-cell">
                                            <span class="fs-info-icon blue">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                            </span>

                                            <div class="fs-info-text">
                                                <strong>{{ $feeStructure->academic_year }}</strong>
                                                <span>Academic Year</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fs-info-cell">
                                            <span class="fs-info-icon orange">
                                                <i class="bi bi-mortarboard" aria-hidden="true"></i>
                                            </span>

                                            <div class="fs-info-text">
                                                <strong>
                                                    {{ $feeStructure->schoolClass->class_name ?? 'N/A' }}
                                                </strong>
                                                <span>School Class</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fs-info-cell">
                                            <span class="fs-info-icon green">
                                                <i class="bi bi-people" aria-hidden="true"></i>
                                            </span>

                                            <div class="fs-info-text">
                                                <strong>
                                                    {{ $feeStructure->section->section_name ?? 'All Sections' }}
                                                </strong>
                                                <span>Applicable Section</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="fs-fee-count">
                                            <i class="bi bi-list-check" aria-hidden="true"></i>
                                            {{ $feeCount }}
                                            {{ $feeCount == 1 ? 'Fee' : 'Fees' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fs-amount">
                                            ₹{{ number_format((float) $totalAmount, 2) }}
                                        </div>
                                        <div class="fs-cell-caption">Total Fees</div>
                                    </td>

                                    <td>
                                        <span class="fs-status {{ $isActive ? 'active' : 'inactive' }}">
                                            <span class="fs-status-dot" aria-hidden="true"></span>
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fs-actions">
                                            <a href="{{ route('admin.fees.fee-structures.show', $feeStructure) }}"
                                               class="fs-action-btn view"
                                               title="View Structure"
                                               aria-label="View fee structure {{ $feeStructure->structure_name }}">
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>

                                            <a href="{{ route('admin.fees.fee-structures.edit', $feeStructure) }}"
                                               class="fs-action-btn edit"
                                               title="Edit Structure"
                                               aria-label="Edit fee structure {{ $feeStructure->structure_name }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>

                                            <form action="{{ route('admin.fees.fee-structures.destroy', $feeStructure) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this fee structure?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="fs-action-btn delete"
                                                        title="Delete Structure"
                                                        aria-label="Delete fee structure {{ $feeStructure->structure_name }}">
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
                @if($feeStructures->hasPages())
                    <div class="fs-pagination">
                        <div class="fs-pagination-info">
                            Showing <strong>{{ $feeStructures->firstItem() }}</strong>
                            to <strong>{{ $feeStructures->lastItem() }}</strong>
                            of <strong>{{ $feeStructures->total() }}</strong> structures
                        </div>

                        <div class="fs-pagination-links">
                            {{ $feeStructures->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="fs-card-footer">
                    <div>
                        Showing <strong>{{ $feeStructures->count() }}</strong>
                        structure(s) on this page
                    </div>

                    <div class="fs-footer-status">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="fs-empty-state">
                    <div class="fs-empty-icon">
                        <i class="bi bi-receipt" aria-hidden="true"></i>
                    </div>

                    @if($hasFilters)
                        <h4>No Matching Structures Found</h4>
                        <p>
                            No fee structures match your current search or
                            selected filters. Try adjusting your filters.
                        </p>

                        <a href="{{ route('admin.fees.fee-structures.index') }}"
                           class="fs-primary-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Clear Filters
                        </a>
                    @else
                        <h4>No Fee Structures Yet</h4>
                        <p>
                            Create your first fee structure to start organising
                            class-wise and section-wise school fees.
                        </p>

                        <a href="{{ route('admin.fees.fee-structures.create') }}"
                           class="fs-primary-btn">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            Add First Fee Structure
                        </a>
                    @endif
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* FEE STRUCTURES — TRANSPORT PAGE DESIGN
   All selectors are scoped to this page. */

.fee-structures-page {
    --fs-ink: #263b35;
    --fs-text: #39483e;
    --fs-muted: #7b857b;
    --fs-line: #e5e1d5;
    --fs-paper: #fffefa;
    --fs-chalk: #f5f3e9;
    --fs-blue: #426b8a;
    --fs-green: #43835d;
    --fs-amber: #a87543;
    --fs-red: #b95b50;
    --fs-serif: var(--serif, Georgia, "Times New Roman", serif);
    --fs-hand: var(--hand, "Caveat", cursive);

    min-height: calc(100vh - 70px);
    color: var(--fs-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.fee-structures-page *,
.fee-structures-page *::before,
.fee-structures-page *::after {
    box-sizing: border-box;
}

.fee-structures-page a,
.fee-structures-page button,
.fee-structures-page .fs-kpi {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.fee-structures-page .fs-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.fee-structures-page .fs-hero,
.fee-structures-page .fs-paper-panel {
    border: 1px solid var(--fs-line);
    border-radius: 15px;
    background: var(--fs-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.fee-structures-page .fs-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.fee-structures-page .fs-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.fee-structures-page .fs-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.fee-structures-page .fs-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--fs-ink);
    background: var(--fs-chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.fee-structures-page .fs-hero-text {
    min-width: 0;
}

.fee-structures-page .fs-breadcrumb {
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

.fee-structures-page .fs-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.fee-structures-page .fs-hero-text h2 {
    margin: 0 0 11px;
    color: var(--fs-ink);
    font-family: var(--fs-serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.fee-structures-page .fs-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--fs-muted);
    font-size: 12px;
    line-height: 1.9;
}

.fee-structures-page .fs-hero-note {
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

.fee-structures-page .fs-hero-note i {
    font-size: 13px;
}

.fee-structures-page .fs-hero-actions {
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

.fee-structures-page .fs-hero-actions::before,
.fee-structures-page .fs-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.fee-structures-page .fs-hero-actions::before {
    top: -115px;
    right: -60px;
}

.fee-structures-page .fs-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.fee-structures-page .fs-hero-decoration {
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

.fee-structures-page .fs-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--fs-hand);
    font-size: 23px;
    line-height: 1.4;
}

.fee-structures-page .fs-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* BUTTONS */

.fee-structures-page .fs-primary-btn,
.fee-structures-page .fs-apply-btn,
.fee-structures-page .fs-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 16px;
    border: 1px solid var(--fs-ink);
    border-radius: 9px;
    color: #fff;
    background: var(--fs-ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.fee-structures-page .fs-primary-btn {
    position: relative;
    z-index: 1;
}

.fee-structures-page .fs-primary-btn:hover,
.fee-structures-page .fs-apply-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.fee-structures-page .fs-hero-actions .fs-primary-btn {
    border-color: #e8e0c9;
    color: var(--fs-ink);
    background: #f1ead8;
}

.fee-structures-page .fs-hero-actions .fs-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--fs-ink);
    background: #fff5dc;
}

.fee-structures-page .fs-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: var(--fs-paper);
}

.fee-structures-page .fs-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--fs-ink);
    background: #f0f3e9;
}

/* SUCCESS MESSAGE */

.fee-structures-page .fs-alert {
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

.fee-structures-page .fs-success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: #deeedb;
}

/* KPI CARDS */

.fee-structures-page .fs-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.fee-structures-page .fs-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--fs-line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--fs-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.fee-structures-page .fs-kpi.blue { --kpi-color: #426b8a; }
.fee-structures-page .fs-kpi.green { --kpi-color: #43835d; }
.fee-structures-page .fs-kpi.orange { --kpi-color: #a87543; }
.fee-structures-page .fs-kpi.red { --kpi-color: #b95b50; }

.fee-structures-page .fs-kpi::before {
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

.fee-structures-page .fs-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.fee-structures-page .fs-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.fee-structures-page .fs-kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.fee-structures-page .fs-kpi-icon {
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

.fee-structures-page .fs-kpi-value {
    color: var(--fs-ink);
    font-family: var(--fs-serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.fee-structures-page .fs-kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.fee-structures-page .fs-paper-panel {
    overflow: hidden;
}

.fee-structures-page .fs-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.fee-structures-page .fs-title-wrap,
.fee-structures-page .fs-filter-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.fee-structures-page .fs-title-icon,
.fee-structures-page .fs-filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--fs-ink);
    background: #f1f2e8;
    font-size: 16px;
}

.fee-structures-page .fs-title-wrap h3,
.fee-structures-page .fs-filter-heading h4 {
    margin: 0;
    color: var(--fs-ink);
    font-family: var(--fs-serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.fee-structures-page .fs-title-wrap p,
.fee-structures-page .fs-filter-heading p {
    margin: 5px 0 0;
    color: var(--fs-muted);
    font-size: 11px;
    line-height: 1.7;
}

.fee-structures-page .fs-record-count {
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

.fee-structures-page .fs-record-count i,
.fee-structures-page .fs-record-count strong {
    color: var(--fs-ink);
}

/* FILTERS */

.fee-structures-page .fs-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--fs-line);
    background: #faf9f3;
}

.fee-structures-page .fs-filter-heading {
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.fee-structures-page .fs-filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.fee-structures-page .fs-filter-heading h4 {
    font-size: 17px;
}

.fee-structures-page .fs-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(220px, 2fr)
        minmax(150px, 1fr)
        minmax(130px, .8fr)
        auto;
    align-items: end;
    gap: 16px;
}

.fee-structures-page .fs-filter-group {
    min-width: 0;
}

.fee-structures-page .fs-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.fee-structures-page .fs-control,
.fee-structures-page .fs-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--fs-text);
    background: var(--fs-paper);
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.fee-structures-page .fs-control {
    padding: 0 12px;
    cursor: pointer;
}

.fee-structures-page .fs-control:hover,
.fee-structures-page .fs-search-input:hover {
    border-color: #bdcabb;
}

.fee-structures-page .fs-control:focus,
.fee-structures-page .fs-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.fee-structures-page .fs-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.fee-structures-page .fs-search-input > i {
    color: #829083;
    font-size: 13px;
}

.fee-structures-page .fs-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    color: var(--fs-text);
    background: transparent;
    font: inherit;
}

.fee-structures-page .fs-search-input input::placeholder {
    color: #9a9f93;
}

.fee-structures-page .fs-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.fee-structures-page .fs-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
}

.fee-structures-page .fs-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.fee-structures-page .fs-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.fee-structures-page .fs-filter-label i {
    color: var(--fs-green);
}

.fee-structures-page .fs-filter-chip {
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

.fee-structures-page .fs-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* TABLE */

.fee-structures-page .fs-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.fee-structures-page .fs-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.fee-structures-page .fs-table thead th {
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

.fee-structures-page .fs-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--fs-text);
    font-size: 11px;
    vertical-align: middle;
}

.fee-structures-page .fs-table tbody tr {
    background: var(--fs-paper);
    transition: background .18s ease;
}

.fee-structures-page .fs-table tbody tr:hover {
    background: #f8f8f1;
}

.fee-structures-page .fs-table tbody tr:last-child td {
    border-bottom: none;
}

.fee-structures-page .fs-table .fs-actions-heading {
    text-align: right;
}

.fee-structures-page .fs-row-number {
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

.fee-structures-page .fs-structure-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 210px;
}

.fee-structures-page .fs-structure-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--fs-ink);
    background: #edf1e7;
    font-size: 17px;
}

.fee-structures-page .fs-structure-details,
.fee-structures-page .fs-info-text {
    min-width: 0;
}

.fee-structures-page .fs-structure-name {
    max-width: 230px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.fee-structures-page .fs-structure-id {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.fee-structures-page .fs-structure-id i {
    color: var(--fs-amber);
}

.fee-structures-page .fs-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 115px;
}

.fee-structures-page .fs-info-icon {
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

.fee-structures-page .fs-info-icon.blue {
    border-color: #d9e4ec;
    color: var(--fs-blue);
    background: #edf4f8;
}

.fee-structures-page .fs-info-icon.orange {
    border-color: #ebdfcc;
    color: var(--fs-amber);
    background: #faf2e7;
}

.fee-structures-page .fs-info-icon.green {
    border-color: #dce6da;
    color: #527f70;
    background: #edf3e9;
}

.fee-structures-page .fs-info-text strong {
    display: block;
    max-width: 155px;
    overflow: hidden;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.fee-structures-page .fs-info-text span,
.fee-structures-page .fs-cell-caption {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

.fee-structures-page .fs-fee-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #527f70;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.fee-structures-page .fs-amount {
    color: var(--fs-ink);
    font-family: var(--fs-serif);
    font-size: 15px;
    font-weight: 700;
    white-space: nowrap;
}

/* STATUS */

.fee-structures-page .fs-status {
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

.fee-structures-page .fs-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.fee-structures-page .fs-status.inactive {
    border-color: #eed6cf;
    color: var(--fs-red);
    background: #fff1ed;
}

.fee-structures-page .fs-status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

/* ACTIONS */

.fee-structures-page .fs-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.fee-structures-page .fs-actions form {
    display: inline-flex;
    margin: 0;
}

.fee-structures-page .fs-action-btn {
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
    background: var(--fs-paper);
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.fee-structures-page .fs-action-btn:hover {
    transform: translateY(-2px);
}

.fee-structures-page .fs-action-btn.view:hover {
    border-color: #cbdce5;
    color: var(--fs-blue);
    background: #edf4f8;
}

.fee-structures-page .fs-action-btn.edit:hover {
    border-color: #ead7b9;
    color: var(--fs-amber);
    background: #faf3e6;
}

.fee-structures-page .fs-action-btn.delete:hover {
    border-color: #edcbc3;
    color: var(--fs-red);
    background: #fff0ec;
}

/* PAGINATION AND FOOTER */

.fee-structures-page .fs-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
}

.fee-structures-page .fs-pagination-info {
    color: var(--fs-muted);
    font-size: 10px;
}

.fee-structures-page .fs-pagination-info strong,
.fee-structures-page .fs-card-footer strong {
    color: var(--fs-ink);
}

.fee-structures-page .fs-pagination-links {
    min-width: 0;
}

.fee-structures-page .fs-pagination-links nav {
    margin: 0;
}

.fee-structures-page .fs-pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.fee-structures-page .fs-pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--fs-line);
    border-radius: 8px !important;
    color: var(--fs-ink);
    background: var(--fs-paper);
    font-size: 11px;
    box-shadow: none;
}

.fee-structures-page .fs-pagination-links .page-link:hover {
    border-color: #b5c5b0;
    color: var(--fs-green);
    background: #f2f4e9;
}

.fee-structures-page .fs-pagination-links .page-item.active .page-link {
    border-color: var(--fs-ink);
    color: #fff;
    background: var(--fs-ink);
}

.fee-structures-page .fs-pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

.fee-structures-page .fs-card-footer {
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

.fee-structures-page .fs-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.fee-structures-page .fs-footer-status i {
    color: var(--fs-green);
}

/* EMPTY STATE */

.fee-structures-page .fs-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.fee-structures-page .fs-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--fs-ink);
    background: var(--fs-chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.fee-structures-page .fs-empty-state h4 {
    margin: 0 0 10px;
    color: var(--fs-ink);
    font-family: var(--fs-serif);
    font-size: 22px;
    font-weight: 700;
}

.fee-structures-page .fs-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--fs-muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .fee-structures-page .fs-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 42px;
    }

    .fee-structures-page .fs-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .fee-structures-page .fs-hero {
        grid-template-columns: 1fr;
    }

    .fee-structures-page .fs-hero-actions {
        min-height: 220px;
    }

    .fee-structures-page .fs-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .fee-structures-page .fs-container {
        padding: 18px 14px;
    }

    .fee-structures-page .fs-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .fee-structures-page .fs-hero::before {
        left: 18px;
    }

    .fee-structures-page .fs-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .fee-structures-page .fs-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .fee-structures-page .fs-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .fee-structures-page .fs-hero-text h2 {
        font-size: 24px;
    }

    .fee-structures-page .fs-hero-text p {
        font-size: 11px;
    }

    .fee-structures-page .fs-hero-note {
        align-items: flex-start;
        line-height: 1.7;
    }

    .fee-structures-page .fs-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .fee-structures-page .fs-handwritten {
        font-size: 21px;
    }

    .fee-structures-page .fs-kpis {
        column-gap: 15px;
        row-gap: 40px;
        margin-top: 31px;
    }

    .fee-structures-page .fs-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .fee-structures-page .fs-kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .fee-structures-page .fs-kpi-value {
        font-size: 29px;
    }

    .fee-structures-page .fs-kpi-caption {
        font-size: 9px;
    }

    .fee-structures-page .fs-panel-header {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }

    .fee-structures-page .fs-title-wrap {
        gap: 10px;
    }

    .fee-structures-page .fs-title-icon,
    .fee-structures-page .fs-filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .fee-structures-page .fs-title-wrap h3,
    .fee-structures-page .fs-filter-heading h4 {
        font-size: 16px;
    }

    .fee-structures-page .fs-title-wrap p {
        font-size: 10px;
    }

    .fee-structures-page .fs-filter-panel {
        padding: 17px 15px;
    }

    .fee-structures-page .fs-filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .fee-structures-page .fs-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .fee-structures-page .fs-filter-actions > * {
        flex: 1;
    }

    .fee-structures-page .fs-active-filters,
    .fee-structures-page .fs-pagination,
    .fee-structures-page .fs-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .fee-structures-page .fs-result-count {
        white-space: normal;
    }

    .fee-structures-page .fs-pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .fee-structures-page .fs-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .fee-structures-page .fs-kpis {
        grid-template-columns: 1fr;
    }

    .fee-structures-page .fs-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .fee-structures-page .fs-filter-chip {
        max-width: 100%;
    }
}

/* KEYBOARD FOCUS AND REDUCED MOTION */

.fee-structures-page a:focus-visible,
.fee-structures-page button:focus-visible,
.fee-structures-page input:focus-visible,
.fee-structures-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .fee-structures-page *,
    .fee-structures-page *::before,
    .fee-structures-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection