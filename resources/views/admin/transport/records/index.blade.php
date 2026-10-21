
@extends('layouts.app')

@section('title', 'Transport Records | Admin')
@section('page-title', 'Transport Records')

@section('content')

<div class="transport-records-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="transport-alert" role="status">
                <span class="success-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="transport-hero">
            <div class="transport-hero-content">
                <div class="transport-hero-icon">
                    <i class="fas fa-bus"></i>
                </div>

                <div class="transport-hero-text">
                    <div class="transport-breadcrumb">
                        <span>Transport Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Transport Records</span>
                    </div>

                    <h2>Transport Records</h2>

                    <p>
                        Manage student transportation assignments, routes,
                        vehicles, pickup points and drop points from one place.
                    </p>

                    <div class="transport-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Transport &amp; Student Safety Management</span>
                    </div>
                </div>
            </div>

            <div class="transport-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-route"></i>
                </div>

                <div class="hero-handwritten">Safe Journeys. Happy Students.</div>

                <p>
                    Every journey matters. Keep routes organised and
                    student transport assignments up to date.
                </p>

                <a href="{{ route('admin.transport.records.create') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Transport Record
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="transport-kpis">

            <article class="transport-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalRecords) }}</div>
                <div class="kpi-caption">All transport assignments</div>
            </article>

            <article class="transport-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($activeRecords) }}</div>
                <div class="kpi-caption">Currently active assignments</div>
            </article>

            <article class="transport-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Assigned Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($assignedStudents) }}</div>
                <div class="kpi-caption">Students linked to transport</div>
            </article>

            <article class="transport-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-pause-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($inactiveRecords) }}</div>
                <div class="kpi-caption">Inactive transport assignments</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="transport-paper-panel">

            <div class="transport-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3>Student Transport Assignments</h3>
                        <p>View and manage student transport details.</p>
                    </div>
                </div>

                <div class="transport-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($records->total()) }}</strong>
                    Records
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.transport.records.index') }}"
                  method="GET"
                  class="transport-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Find Transport Records</h4>
                        <p>Search assignments by student, ID, route, vehicle or status.</p>
                    </div>
                </div>

                <div class="transport-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="transport-search">Search Records</label>
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input id="transport-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Student, ID, route or vehicle..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="transport-status">Transport Status</label>
                        <select id="transport-status"
                                name="status"
                                class="diary-control">
                            <option value="">All Statuses</option>
                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-search"></i> Search
                        </button>

                        <a href="{{ route('admin.transport.records.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request('search') || request('status'))
                <div class="transport-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-filter"></i> Active filters
                        </span>

                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="fas fa-circle-half-stroke"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ number_format($records->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- RECORDS TABLE --}}
            @if($records->count() > 0)

                <div class="transport-table-wrapper">
                    <table class="transport-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Route</th>
                                <th>Vehicle</th>
                                <th>Pickup Point</th>
                                <th>Drop Point</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th class="actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>
                                        <span class="transport-row-number">
                                            {{ ($records->firstItem() ?? 0) + $loop->index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                @if($record->student)
                                                    {{ strtoupper(substr($record->student->first_name ?? 'S', 0, 1)) }}
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>

                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $record->student->full_name ?? 'Student Not Assigned' }}
                                                </div>
                                                <div class="student-id">
                                                    <i class="fas fa-id-badge"></i>
                                                    ID: {{ $record->student->student_id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="transport-info-cell">
                                            <span class="transport-info-icon route-icon">
                                                <i class="fas fa-route"></i>
                                            </span>
                                            <div class="transport-info-text">
                                                <strong>{{ $record->route ?? 'Not Assigned' }}</strong>
                                                <span>Transport Route</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="transport-info-cell">
                                            <span class="transport-info-icon vehicle-icon">
                                                <i class="fas fa-bus"></i>
                                            </span>
                                            <div class="transport-info-text">
                                                <strong>{{ $record->vehicle ?? 'Not Assigned' }}</strong>
                                                <span>School Vehicle</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($record->pickup_point)
                                            <div class="transport-location">
                                                <span class="location-icon pickup-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                                <span class="location-text">
                                                    {{ $record->pickup_point }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($record->drop_point)
                                            <div class="transport-location">
                                                <span class="location-icon drop-icon">
                                                    <i class="fas fa-location-dot"></i>
                                                </span>
                                                <span class="location-text">
                                                    {{ $record->drop_point }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($record->transport_status === 'active')
                                            <span class="transport-status active">
                                                <span class="status-dot"></span> Active
                                            </span>
                                        @else
                                            <span class="transport-status inactive">
                                                <span class="status-dot"></span> Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($record->start_date)
                                            <div class="transport-date">
                                                <div class="date-primary">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $record->start_date->format('d M Y') }}
                                                </div>
                                                <div class="date-caption">Start Date</div>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="transport-actions">
                                            <a href="{{ route('admin.transport.records.show', ['transportRecord' => $record->id]) }}"
                                               class="transport-action-btn view"
                                               title="View Record"
                                               aria-label="View transport record">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.transport.records.edit', ['transportRecord' => $record->id]) }}"
                                               class="transport-action-btn edit"
                                               title="Edit Record"
                                               aria-label="Edit transport record">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('admin.transport.records.destroy', ['transportRecord' => $record->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this transport record?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="transport-action-btn delete"
                                                        title="Delete Record"
                                                        aria-label="Delete transport record">
                                                    <i class="fas fa-trash-alt"></i>
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
                @if($records->hasPages())
                    <div class="transport-pagination">
                        <div class="pagination-info">
                            Showing <strong>{{ $records->firstItem() }}</strong>
                            to <strong>{{ $records->lastItem() }}</strong>
                            of <strong>{{ $records->total() }}</strong> records
                        </div>

                        <div class="pagination-links">
                            {{ $records->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="transport-card-footer">
                    <div>
                        Showing <strong>{{ $records->count() }}</strong>
                        record(s) on this page
                    </div>
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="transport-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-bus"></i>
                    </div>

                    @if(request('search') || request('status'))
                        <h4>No Matching Records Found</h4>
                        <p>
                            No transport records match your current search
                            or selected status. Try adjusting your filters.
                        </p>
                        <a href="{{ route('admin.transport.records.index') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h4>No Transport Records Yet</h4>
                        <p>
                            There are currently no student transport assignments.
                            Add your first record to start managing student transportation.
                        </p>
                        <a href="{{ route('admin.transport.records.create') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-plus"></i> Add First Transport Record
                        </a>
                    @endif
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* ==========================================================
   TRANSPORT RECORDS — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.transport-records-page {
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

.transport-records-page *,
.transport-records-page *::before,
.transport-records-page *::after {
    box-sizing: border-box;

}

.transport-records-page a,
.transport-records-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.transport-records-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.transport-hero,
.transport-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.transport-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.transport-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.transport-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.transport-hero-icon {
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

.transport-hero-text {
    min-width: 0;
}

.transport-breadcrumb {
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

.transport-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.transport-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.transport-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.transport-hero-note {
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

.transport-hero-note i {
    font-size: 13px;
}

.transport-hero-actions {
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

.transport-hero-actions::before,
.transport-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.transport-hero-actions::before {
    top: -115px;
    right: -60px;
}

.transport-hero-actions::after {
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

.transport-hero-actions p {
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

.transport-hero-actions .diary-primary-btn {
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

.transport-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* SUCCESS MESSAGE */

.transport-alert {
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

/* KPI CARDS */

.transport-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.transport-kpi {
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

.transport-kpi.blue { --kpi-color: #426b8a; }
.transport-kpi.green { --kpi-color: #43835d; }
.transport-kpi.orange { --kpi-color: #a87543; }
.transport-kpi.red { --kpi-color: #b95b50; }

.transport-kpi::before {
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

.transport-kpi.blue::before { content: "ASSIGNMENTS"; }
.transport-kpi.green::before { content: "ACTIVE"; }
.transport-kpi.orange::before { content: "STUDENTS"; }
.transport-kpi.red::before { content: "INACTIVE"; }

.transport-kpi:hover {
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

.transport-kpi .kpi-icon {
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

.transport-paper-panel {
    overflow: hidden;
}

.transport-panel-header {
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

.transport-record-count {
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

.transport-record-count i,
.transport-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.transport-filter-panel {
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

.transport-filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) minmax(160px, .9fr) auto;
    align-items: end;
    gap: 16px;
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

.transport-active-filters {
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

.transport-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.transport-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.transport-table thead th {
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

.transport-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.transport-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.transport-table tbody tr:hover {
    background: #f8f8f1;
}

.transport-table tbody tr:last-child td {
    border-bottom: none;
}

.transport-table .actions-heading {
    text-align: right;
}

.transport-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #e3e1d6;
    border-radius: 9px;
    color: #68776a;
    background: #f5f4ec;
    font-size: 10px;

    font-weight: 800;

}

/* STUDENT */

.student-info {
    display: flex;

    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.student-avatar {
    display: flex;
    align-items: center;

    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 14px;
    font-weight: 800;
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

/* ROUTE AND VEHICLE */

.transport-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 145px;
}

.transport-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 12px;
}

.route-icon {
    border-color: #ebdfcc;
    color: #a87543;
    background: #faf2e7;
}

.vehicle-icon {
    border-color: #dce6da;
    color: #527f70;
    background: #edf3e9;
}

.transport-info-text {
    min-width: 0;
}

.transport-info-text strong {
    display: block;
    max-width: 155px;
    overflow: hidden;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.transport-info-text span {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

/* PICKUP AND DROP LOCATIONS */

.transport-location {
    display: flex;
    align-items: center;
    gap: 8px;
    max-width: 180px;
    color: #637167;
    font-size: 10px;
}

.location-icon {
    display: inline-flex;

    align-items: center;

    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 10px;
}

.pickup-icon {
    border-color: #d9e7d5;
    color: #43835d;
    background: #edf6e9;
}

.drop-icon {
    border-color: #eed9d1;
    color: #b95b50;
    background: #fff1ed;
}

.location-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* STATUS */

.transport-status {
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

.transport-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.transport-status.inactive {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.transport-status.active .status-dot {
    background: #43835d;
}

.transport-status.inactive .status-dot {
    background: #b95b50;
}

/* DATE */

.transport-date {
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

/* ACTION BUTTONS */

.transport-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.transport-actions form {
    display: inline-flex;
    margin: 0;
}

.transport-action-btn {
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

.transport-action-btn:hover {
    transform: translateY(-2px);
}

.transport-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.transport-action-btn.edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.transport-action-btn.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* PAGINATION */

.transport-pagination {
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

.transport-card-footer {
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

.transport-card-footer strong {
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

.transport-empty-state {
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

.transport-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.transport-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .transport-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .transport-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }

}

@media (max-width: 900px) {
    .transport-hero {
        grid-template-columns: 1fr;

    }

    .transport-hero-actions {
        min-height: 220px;
    }

    .transport-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }

}

/* MOBILE */

@media (max-width: 650px) {
    .transport-records-page .container-fluid {
        padding: 18px 14px !important;
    }

    .transport-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .transport-hero::before {
        left: 18px;
    }

    .transport-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .transport-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .transport-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .transport-hero-text h2 {
        font-size: 24px;
    }

    .transport-hero-text p {
        font-size: 11px;
    }

    .transport-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .transport-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .transport-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .transport-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .transport-kpi .kpi-icon {
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

    .transport-panel-header {
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

    .transport-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .transport-filter-panel {
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

    .transport-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .transport-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .transport-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .transport-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .transport-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .transport-kpis {
        grid-template-columns: 1fr;
    }

    .transport-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .transport-record-count {
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

.transport-records-page a:focus-visible,
.transport-records-page button:focus-visible,
.transport-records-page input:focus-visible,
.transport-records-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .transport-records-page *,
    .transport-records-page *::before,
    .transport-records-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}


</style>

@endsection
