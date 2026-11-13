```blade
@extends('layouts.app')

@section('title', 'Government Kit Templates')

@section('content')

<div class="container-fluid py-4 kit-page">

    {{-- PAGE HEADER --}}
    <div class="kit-page-header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kit-header-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <div class="kit-eyebrow">SUPPLY MANAGEMENT</div>
                    <h3 class="kit-page-title mb-1">Government Kit Templates</h3>
                    <p class="mb-0">
                        Manage standard government-provided kits and their items.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 kit-header-actions">
                <a href="{{ route('admin.student-supply-kits.index') }}"
                   class="btn btn-kit-outline">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Supply Kits
                </a>

                <a href="{{ route('admin.kit-templates.create') }}"
                   class="btn btn-kit-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Government Kit
                </a>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show kit-alert" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show kit-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon kit-stat-blue">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="kit-stat-label">Total Government Kits</div>
                    <div class="kit-stat-value">{{ $stats['total'] ?? 0 }}</div>
                    <div class="kit-stat-caption">All registered templates</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon kit-stat-green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="kit-stat-label">Active Kits</div>
                    <div class="kit-stat-value">{{ $stats['active'] ?? 0 }}</div>
                    <div class="kit-stat-caption">Available for distribution</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon kit-stat-orange">
                    <i class="bi bi-pause-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="kit-stat-label">Inactive Kits</div>
                    <div class="kit-stat-value">{{ $stats['inactive'] ?? 0 }}</div>
                    <div class="kit-stat-caption">Currently disabled</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kit-stat-card">
                <div class="kit-stat-icon kit-stat-purple">
                    <i class="bi bi-boxes"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="kit-stat-label">Total Kit Items</div>
                    <div class="kit-stat-value">{{ $stats['items'] ?? 0 }}</div>
                    <div class="kit-stat-caption">Items across all templates</div>
                </div>
            </div>
        </div>

    </div>

    {{-- SEARCH AND FILTERS --}}
    <div class="kit-panel mb-4">

        <div class="kit-panel-heading">
            <div class="d-flex align-items-center gap-3">
                <div class="kit-heading-icon">
                    <i class="bi bi-sliders"></i>
                </div>

                <div>
                    <h5 class="mb-1">Search & Filters</h5>
                    <small class="text-muted">
                        Find government kits by name, class, academic year or status.
                    </small>
                </div>
            </div>
        </div>

        <div class="p-3 p-lg-4">
            <form method="GET" action="{{ route('admin.kit-templates.index') }}">
                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-12 col-lg-4">
                        <label for="kit-search" class="kit-label">
                            Search Government Kits
                        </label>

                        <div class="kit-input-wrap">
                            <i class="bi bi-search"></i>

                            <input
                                id="kit-search"
                                type="text"
                                name="search"
                                class="form-control kit-input"
                                placeholder="Enter kit name or description..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- CLASS --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="kit-class" class="kit-label">
                            Applicable Class
                        </label>

                        <select id="kit-class"
                                name="class_id"
                                class="form-select kit-input">
                            <option value="">All Classes</option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected(request('class_id') == $class->id)>
                                    {{ $class->class_name }}@if($class->section) - {{ $class->section }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="kit-year" class="kit-label">
                            Academic Year
                        </label>

                        <select id="kit-year"
                                name="academic_year"
                                class="form-select kit-input">
                            <option value="">All Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="kit-status" class="kit-label">
                            Kit Status
                        </label>

                        <select id="kit-status"
                                name="status"
                                class="form-select kit-input">
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

                    {{-- FILTER BUTTONS --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="btn btn-kit-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i>
                                Apply
                            </button>

                            <a href="{{ route('admin.kit-templates.index') }}"
                               class="btn btn-kit-reset"
                               title="Clear filters"
                               aria-label="Clear filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- GOVERNMENT KIT TABLE --}}
    <div class="kit-panel">

        <div class="kit-panel-heading kit-table-heading">
            <div class="d-flex align-items-center gap-3">
                <div class="kit-heading-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>
                    <h5 class="mb-1">Government Student Supply Kits</h5>
                    <small class="text-muted">
                        Manage standard kits and their items for student distribution.
                    </small>
                </div>
            </div>

            <span class="kit-total-badge">
                <i class="bi bi-collection me-1"></i>
                {{ $kitTemplates->total() }}
                {{ $kitTemplates->total() === 1 ? 'Kit' : 'Kits' }}
            </span>
        </div>

        @if($kitTemplates->count())

            <div class="table-responsive">
                <table class="table kit-table align-middle mb-0">

                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Government Kit</th>
                            <th>Class</th>
                            <th>Academic Year</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($kitTemplates as $kitTemplate)
                            <tr>

                                {{-- SERIAL NUMBER --}}
                                <td class="ps-4 text-muted">
                                    {{ $kitTemplates->firstItem() + $loop->index }}
                                </td>

                                {{-- KIT DETAILS --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3 kit-name-cell">

                                        <div class="kit-row-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="kit-name">
                                                {{ $kitTemplate->kit_name }}
                                            </div>

                                            @if($kitTemplate->description)
                                                <div class="kit-description">
                                                    {{ \Illuminate\Support\Str::limit($kitTemplate->description, 65) }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- CLASS --}}
                                <td>
                                    @if($kitTemplate->schoolClass)
                                        <span class="kit-class-badge">
                                            {{ $kitTemplate->schoolClass->class_name }}

                                            @if($kitTemplate->schoolClass->section)
                                                - {{ $kitTemplate->schoolClass->section }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- ACADEMIC YEAR --}}
                                <td>
                                    <span class="kit-year-text">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $kitTemplate->academic_year ?: '—' }}
                                    </span>
                                </td>

                                {{-- ITEM COUNT --}}
                                <td>
                                    <span class="kit-item-badge">
                                        <i class="bi bi-boxes me-1"></i>
                                        {{ $kitTemplate->items_count ?? $kitTemplate->items->count() }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($kitTemplate->status === 'active')
                                        <span class="kit-status kit-status-active">
                                            <span class="kit-status-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="kit-status kit-status-inactive">
                                            <span class="kit-status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="text-end pe-4">
                                    <div class="dropdown">

                                        <button
                                            class="btn kit-action-btn"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-label="Actions for {{ $kitTemplate->kit_name }}">
                                            <i class="bi bi-three-dots"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end kit-dropdown">

                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.kit-templates.show', $kitTemplate) }}">
                                                    <i class="bi bi-eye"></i>
                                                    View Kit
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.kit-templates.edit', $kitTemplate) }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                    Edit Kit
                                                </a>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.kit-templates.destroy', $kitTemplate) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this government kit template?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="dropdown-item kit-delete-item">
                                                        <i class="bi bi-trash3"></i>
                                                        Delete Kit
                                                    </button>
                                                </form>
                                            </li>

                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            @if($kitTemplates->hasPages())
                <div class="kit-pagination">

                    <div class="small text-muted">
                        Showing
                        {{ $kitTemplates->firstItem() }}–{{ $kitTemplates->lastItem() }}
                        of {{ $kitTemplates->total() }} kits
                    </div>

                    <div class="kit-pagination-links">
                        {{ $kitTemplates->withQueryString()->links() }}
                    </div>

                </div>
            @endif

        @else

            {{-- EMPTY STATE --}}
            <div class="kit-empty-state">

                <div class="kit-empty-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h5>No Government Kit Templates Found</h5>

                <p>
                    No templates match your current filters, or no government kits
                    have been created yet. Create a template to define the standard
                    items distributed to eligible students.
                </p>

                @if(request()->hasAny(['search', 'class_id', 'academic_year', 'status']))
                    <a href="{{ route('admin.kit-templates.index') }}"
                       class="btn btn-kit-outline me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Clear Filters
                    </a>
                @endif

                <a href="{{ route('admin.kit-templates.create') }}"
                   class="btn btn-kit-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Government Kit
                </a>

            </div>

        @endif
    </div>

</div>

<style>
/* =========================================================
   GOVERNMENT KIT TEMPLATES
   Styled to match the Create Government Kit page.
========================================================= */

.kit-page {
    --kit-primary: #176238;
    --kit-primary-dark: #0c3f25;
    --kit-primary-light: #e6f3e9;
    --kit-border: #d7e6da;
    --kit-muted: #6f8174;

    padding: 28px !important;
    color: #173b27;
    background-color: #faf8f1;
    background-image: repeating-linear-gradient(
        to bottom,
        transparent 0,
        transparent 59px,
        rgba(22, 83, 48, .035) 60px
    );
    border-radius: 0;
}

/* PAGE HEADER */
.kit-page-header {
    position: relative;
    overflow: hidden;
    padding: 25px 26px !important;
    margin-bottom: 22px !important;
    color: #fff;
    background: linear-gradient(
        110deg,
        #0b3b23 0%,
        #145a34 60%,
        #1d6840 100%
    );
    border: 1px solid #1c6038;
    border-radius: 17px;
    box-shadow: 0 8px 22px rgba(15, 65, 36, .13);
}

.kit-page-header .kit-eyebrow {
    color: #9cf2c0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.4px;
    margin-bottom: 5px;
}

.kit-page-header .kit-page-title {
    color: #fff;
    font-size: clamp(1.3rem, 2vw, 1.8rem);
    font-weight: 800;
    letter-spacing: -.4px;
}

.kit-page-header p,
.kit-page-header .text-muted {
    color: #d7f0df !important;
    font-size: 13px;
}

.kit-header-icon {
    width: 56px;
    height: 56px;
    flex: 0 0 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .24);
    border-radius: 15px;
    font-size: 26px;
    box-shadow: none;
}

/* BUTTONS */
.btn-kit-primary,
.btn-kit-outline,
.btn-kit-reset {
    min-height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    transition: background .2s ease, border-color .2s ease, transform .2s ease;
}

.btn-kit-primary {
    padding: 10px 16px;
    color: #fff;
    background: #176238;
    border: 1px solid #176238;
    box-shadow: 0 3px 8px rgba(21, 91, 48, .10);
}

.btn-kit-primary:hover,
.btn-kit-primary:focus {
    color: #fff;
    background: #0e4829;
    border-color: #0e4829;
    transform: translateY(-1px);
}

.btn-kit-outline {
    padding: 10px 15px;
    color: #176238;
    background: #fff;
    border: 1px solid #d0e1d4;
}

.btn-kit-outline:hover {
    color: #104426;
    background: #eaf5ec;
    border-color: #b7d7bf;
}

.btn-kit-reset {
    width: 43px;
    flex: 0 0 43px;
    padding: 8px;
    color: #176238;
    background: #fff;
    border: 1px solid #d0e1d4;
    font-size: 17px;
}

.btn-kit-reset:hover {
    color: #104426;
    background: #eaf5ec;
    border-color: #b7d7bf;
}

/* ALERTS */
.kit-alert {
    border-radius: 11px;
    border: 1px solid #d5e8d9;
    box-shadow: 0 3px 12px rgba(20, 60, 35, .05);
    font-size: 13px;
}

/* STATISTICS */
.kit-stat-card {
    height: 100%;
    min-height: 112px;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #fff;
    border: 1px solid #dce8dd;
    border-left: 3px solid #161d18;
    border-radius: 14px;
    box-shadow: 0 3px 12px rgba(24, 62, 37, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.kit-stat-card:hover {
    border-color: #a9d3b4;
    box-shadow: 0 7px 20px rgba(24, 62, 37, .09);
    transform: translateY(-2px);
}

.kit-stat-icon {
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 22px;
}

.kit-stat-blue {
    color: #161d18;
    background: #e6f3e9;
}

.kit-stat-green {
    color: #161d18;
    background: #d8f0df;
}

.kit-stat-orange {
    color: #161d18;
    background: #e0f5e6;
}

.kit-stat-purple {
    color: #161d18;
    background: #d1fae0;
}

.kit-stat-label {
    color: #161d18;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.5;
}

.kit-stat-value {
    margin-top: 3px;
    color: #161d18;
    font-size: 26px;
    line-height: 1.2;
    font-weight: 800;
}

.kit-stat-caption {
    margin-top: 5px;
    color: #161d18;
    font-size: 11px;
}

/* PANELS */
.kit-panel {
    overflow: visible;
    background: #fff;
    border: 1px solid #dce8dd;
    border-radius: 15px;
    box-shadow: 0 3px 13px rgba(24, 62, 37, .045);
}

.kit-panel-heading {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 17px 22px;
    background: #fbfdfb;
    border-bottom: 1px solid #e2ece4;
    border-radius: 15px 15px 0 0;
}

.kit-panel-heading h5 {
    color: #161d18;
    font-size: 14px;
    font-weight: 800;
}

.kit-panel-heading small {
    color: #161d18;
    font-size: 12px;
}

.kit-heading-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #161d18;
    background: #e7f4e9;
    border: 1px solid #dcecdf;
    border-radius: 11px;
    font-size: 18px;
}

/* FILTER FIELDS */
.kit-label {
    display: block;
    margin-bottom: 7px;
    color: #161d18;
    font-size: 12px;
    font-weight: 700;
}

.kit-input-wrap {
    position: relative;
}

.kit-input-wrap > i {
    position: absolute;
    top: 50%;
    left: 13px;
    z-index: 2;
    color: #161d18;
    transform: translateY(-50%);
    pointer-events: none;
}

.kit-input {
    width: 100%;
    min-height: 44px;
    color: #161d18;
    background-color: #fff;
    border: 1px solid #d5e4d8;
    border-radius: 9px;
    font-size: 13px;
    box-shadow: none;
}

.kit-input-wrap .kit-input {
    padding-left: 37px;
}

.kit-input:focus {
    border-color: #161d18;
    box-shadow: 0 0 0 3px rgba(35, 126, 68, .10);
}

/* TABLE COUNT */
.kit-total-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    color: #161d18;
    background: #e8f5eb;
    border: 1px solid #d1e8d7;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

/* TABLE */
.kit-table {
    --bs-table-bg: #fff;
    --bs-table-color: #161d18;
    --bs-table-hover-bg: #f5faf5;
    --bs-table-hover-color: #161d18;
    margin-bottom: 0;
}

.kit-table thead th {
    padding: 14px 12px;
    color: #161d18;
    background: #f0f7f1;
    border-bottom: 1px solid #dce8dd;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
    white-space: nowrap;
}

.kit-table tbody td {
    padding: 15px 12px;
    color: #161d18;
    border-bottom: 1px solid #edf2ed;
    font-size: 13px;
    vertical-align: middle;
}

.kit-table tbody tr:last-child td {
    border-bottom: 0;
}

.kit-table tbody tr {
    transition: background .15s ease;
}

.kit-table tbody tr:hover > * {
    background-color: #f5faf5;
}

.kit-row-icon {
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #161d18;
    background: #e9f5eb;
    border: 1px solid #d6e9da;
    border-radius: 11px;
    font-size: 19px;
}

.kit-name {
    color: #161d18;
    font-size: 13px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.kit-description {
    max-width: 300px;
    margin-top: 4px;
    color: #161d18;
    font-size: 11px;
    line-height: 1.5;
}

.kit-class-badge {
    display: inline-block;
    padding: 6px 9px;
    color: #161d18;
    background: #f1f8f2;
    border: 1px solid #d7e8da;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.kit-year-text {
    color: #161d18;
    font-size: 12px;
    white-space: nowrap;
}

.kit-year-text i {
    color: #4b9864;
}

.kit-item-badge {
    display: inline-block;
    padding: 7px 10px;
    color: #161d18;
    background: #e8f5eb;
    border: 1px solid #d1e8d7;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

/* STATUS */
.kit-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.kit-status-dot {
    width: 7px;
    height: 7px;
    flex: 0 0 7px;
    border-radius: 50%;
}

.kit-status-active {
    color: #161d18;
    background: #e4f4e8;
}

.kit-status-active .kit-status-dot {
    background: #161d18;
}

.kit-status-inactive {
    color: #161d18;
    background: #eef1ee;
}

.kit-status-inactive .kit-status-dot {
    background: #89958d;
}

/* ACTION DROPDOWN */
.kit-action-btn {
    width: 35px;
    height: 35px;
    padding: 0;
    color: #161d18;
    background: #fff;
    border: 1px solid #d8e6db;
    border-radius: 9px;
    font-size: 17px;
}

.kit-action-btn:hover,
.kit-action-btn:focus {
    color: #161d18;
    background: #eaf5ec;
    border-color: #b7d7bf;
}

.kit-dropdown {
    min-width: 175px;
    padding: 7px;
    border: 1px solid #dce8dd;
    border-radius: 11px;
    box-shadow: 0 8px 24px rgba(20, 60, 35, .12);
    z-index: 1050;
}

.kit-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    color: #161d18;
    border-radius: 7px;
    font-size: 12px;
}

.kit-dropdown .dropdown-item i {
    width: 16px;
    font-size: 14px;
}

.kit-dropdown .dropdown-item:hover {
    color: #161d18;
    background: #edf7ef;
}

.kit-dropdown .kit-delete-item,
.kit-dropdown .kit-delete-item:hover {
    color: #dc3545;
}

/* PAGINATION */
.kit-pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 17px 22px;
    border-top: 1px solid #e2ece4;
}

.kit-pagination nav p {
    margin-bottom: 0;
}

.kit-pagination nav > div:first-child {
    display: none;
}

.kit-pagination nav > div:last-child {
    margin-bottom: 0;
}

.kit-pagination nav a {
    color: #161d18;
}

.kit-pagination nav .active > span,
.kit-pagination nav .active > a {
    color: #fff;
    background: #161d18;
    border-color: #161d18;
}

/* EMPTY STATE */
.kit-empty-state {
    padding: 50px 20px;
    text-align: center;
}

.kit-empty-icon {
    width: 78px;
    height: 78px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    color: #161d18;
    background: #e8f5eb;
    border: 1px solid #d6e9da;
    border-radius: 20px;
    font-size: 34px;
}

.kit-empty-state h5 {
    margin-bottom: 9px;
    color: #161d18;
    font-size: 17px;
    font-weight: 800;
}

.kit-empty-state p {
    max-width: 490px;
    margin: 0 auto 22px;
    color: #718778;
    font-size: 13px;
    line-height: 1.7;
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .kit-page {
        padding: 20px !important;
    }
}

@media (max-width: 767.98px) {
    .kit-page {
        padding: 14px !important;
    }

    .kit-page-header {
        padding: 20px 16px !important;
        border-radius: 14px;
    }

    .kit-header-icon {
        width: 46px;
        height: 46px;
        flex-basis: 46px;
        font-size: 22px;
    }

    .kit-page-header > .d-flex {
        align-items: flex-start !important;
    }

    .kit-header-actions {
        width: 100%;
    }

    .kit-header-actions .btn {
        flex: 1 1 auto;
    }

    .kit-panel-heading {
        padding: 15px;
    }

    .kit-stat-card {
        min-height: 100px;
        padding: 16px;
    }

    .kit-pagination {
        padding: 15px;
    }

    .kit-pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .kit-table thead th,
    .kit-table tbody td {
        padding: 12px 10px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .kit-stat-card,
    .kit-table tbody tr,
    .btn-kit-primary,
    .btn-kit-outline {
        transition: none;
    }
}
</style>

@endsection
```