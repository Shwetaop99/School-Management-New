@extends('layouts.app')

@section('title', 'Student Supply Kits | Admin')
@section('page-title', 'Student Supply Kits')

@section('content')

@php
    // Change this one line if your print route has a different name.
    $printRoute = 'admin.student-supply-kits.print-report';

    $isClassView = $selectedClass === '';

    $selectedClassCard = $isClassView
        ? null
        : $classCards->firstWhere('class_name', $selectedClass);

    if ($isClassView) {
        $kpiTotal       = (int) $classCards->sum('total_students');
        $kpiIssued      = (int) $classCards->sum('issued');
        $kpiPending     = (int) $classCards->sum('pending');
        $kpiNotAssigned = (int) $classCards->sum('not_assigned');
        $kpiScope       = 'across all classes';
    } else {
        $kpiTotal       = (int) ($selectedClassCard->total_students ?? 0);
        $kpiIssued      = (int) ($selectedClassCard->issued ?? 0);
        $kpiPending     = (int) ($selectedClassCard->pending ?? 0);
        $kpiNotAssigned = (int) ($selectedClassCard->not_assigned ?? 0);
        $kpiScope       = 'in ' . $selectedClass;
    }

    $hasStudentFilters = request('search')
        || request('section')
        || request('status')
        || request('scheme_id')
        || request('kit_template_id');

    $statusLabels = [
        'issued'       => 'Issued',
        'pending'      => 'Pending',
        'cancelled'    => 'Cancelled',
        'not_assigned' => 'Not Assigned',
    ];
@endphp

<div class="supply-page">
    <div class="supply-container">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="supply-alert success" role="status">
                <span class="alert-icon"><i class="bi bi-check-lg"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="supply-alert error" role="alert">
                <span class="alert-icon"><i class="bi bi-exclamation-lg"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="supply-hero">
            <div class="supply-hero-content">
                <div class="supply-hero-icon"><i class="bi bi-box-seam"></i></div>

                <div class="supply-hero-text">
                    <div class="supply-breadcrumb">
                        <span>Student Management</span>
                        <i class="bi bi-chevron-right"></i>
                        <span>Supply Kits</span>

                        @unless($isClassView)
                            <i class="bi bi-chevron-right"></i>
                            <span>{{ $selectedClass }}</span>
                        @endunless
                    </div>

                    <h2>Student Supply Kits</h2>

                    <p>
                        Manage government supply kit distribution class-wise,
                        track issued and pending kits and keep every
                        student's record up to date.
                    </p>

                    <div class="supply-hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Government Schemes &amp; Student Supply Management</span>
                    </div>
                </div>
            </div>

            <div class="supply-hero-actions">
                <div class="hero-decoration"><i class="bi bi-truck"></i></div>
                <div class="hero-handwritten">Every Kit. Delivered.</div>

                <p>
                    Every student matters. Create kits, distribute them
                    and print distribution lists.
                </p>

                <div class="hero-button-group">

    <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
       class="diary-primary-btn">
        <i class="bi bi-box-arrow-in-down"></i>
        Kit Distribution
    </a>

    <a href="{{ route('admin.kit-templates.index') }}"
       class="diary-ghost-btn">
        <i class="bi bi-plus-lg"></i>
        Kit Creation
    </a>

    {{-- STOCK MANAGEMENT --}}
    <a href="{{ route('admin.supply-stocks.index', [
            'academic_year' => $academicYear
        ]) }}"
       class="diary-ghost-btn">
        <i class="bi bi-boxes"></i>
        Stock Management
    </a>

    <a href="{{ route($printRoute, request()->query()) }}"
       target="_blank"
       class="diary-ghost-btn">
        <i class="bi bi-printer"></i>
        Print
    </a>

</div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="supply-kpis">
            <article class="supply-kpi blue" data-tab="STUDENTS">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($kpiTotal) }}</div>
                <div class="kpi-caption">Active students {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi green" data-tab="ISSUED">
                <div class="kpi-top">
                    <span class="kpi-label">Kits Issued</span>
                    <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($kpiIssued) }}</div>
                <div class="kpi-caption">Issued {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi orange" data-tab="PENDING">
                <div class="kpi-top">
                    <span class="kpi-label">Kits Pending</span>
                    <span class="kpi-icon"><i class="bi bi-clock-history"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($kpiPending) }}</div>
                <div class="kpi-caption">Pending {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi red" data-tab="NOT ASSIGNED">
                <div class="kpi-top">
                    <span class="kpi-label">Not Assigned</span>
                    <span class="kpi-icon"><i class="bi bi-dash-circle"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($kpiNotAssigned) }}</div>
                <div class="kpi-caption">No kit assigned {{ $kpiScope }}</div>
            </article>
        </section>

        {{-- =====================================================
             CLASS CARD VIEW
        ====================================================== --}}
        @if($isClassView)

            <section class="supply-paper-panel">

                <div class="supply-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon"><i class="bi bi-grid"></i></div>
                        <div>
                            <h3>Classes</h3>
                            <p>Select a class to view and manage its supply kits.</p>
                        </div>
                    </div>

                    <div class="supply-record-count">
                        <i class="bi bi-folder2-open"></i>
                        <strong>{{ number_format($classCards->count()) }}</strong>
                        {{ $classCards->count() == 1 ? 'Class' : 'Classes' }}
                    </div>
                </div>

                {{-- ACADEMIC YEAR FILTER --}}
                <form action="{{ route('admin.student-supply-kits.index') }}"
                      method="GET" class="supply-filter-panel">

                    <div class="filter-heading">
                        <div class="filter-heading-icon"><i class="bi bi-sliders"></i></div>
                        <div>
                            <h4>Select Academic Year</h4>
                            <p>Load the classes and kit status for an academic year.</p>
                        </div>
                    </div>

                    <div class="supply-filter-grid year-only">
                        <div class="diary-filter-group">
                            <label for="academic_year">Academic Year</label>
                            <div class="diary-search-input">
                                <i class="bi bi-calendar3"></i>
                                <input id="academic_year" type="text" name="academic_year"
                                       value="{{ $academicYear }}" placeholder="2026-27"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-actions">
                            <button type="submit" class="diary-apply-btn">
                                <i class="bi bi-search"></i> Load Classes
                            </button>
                        </div>
                    </div>
                </form>

                @if($classCards->count())

                    <div class="supply-grid">
                        @foreach($classCards as $card)
                            <a href="{{ route('admin.student-supply-kits.index', [
                                    'class' => $card->class_name,
                                    'academic_year' => $academicYear,
                                ]) }}"
                               class="supply-class-card">

                                <div class="supply-class-top">
                                    <span class="supply-class-icon"><i class="bi bi-mortarboard"></i></span>

                                    <div class="supply-class-text">
                                        <h5>{{ $card->class_name }}</h5>
                                        <div class="supply-class-total">
                                            {{ $card->total_students }}
                                            {{ $card->total_students == 1 ? 'Student' : 'Students' }}
                                        </div>
                                    </div>

                                    <span class="supply-class-arrow"><i class="bi bi-chevron-right"></i></span>
                                </div>

                                <div class="supply-class-stats">
                                    <div class="supply-class-stat issued">
                                        <div class="stat-number">{{ $card->issued }}</div>
                                        <div class="stat-label">Issued</div>
                                    </div>
                                    <div class="supply-class-stat pending">
                                        <div class="stat-number">{{ $card->pending }}</div>
                                        <div class="stat-label">Pending</div>
                                    </div>
                                    <div class="supply-class-stat cancelled">
                                        <div class="stat-number">{{ $card->cancelled }}</div>
                                        <div class="stat-label">Cancelled</div>
                                    </div>
                                    <div class="supply-class-stat not-assigned">
                                        <div class="stat-number">{{ $card->not_assigned }}</div>
                                        <div class="stat-label">Not Assigned</div>
                                    </div>
                                </div>

                                <div class="supply-class-footer">
                                    <span>View Students</span>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="supply-card-footer">
                        <div>
                            Showing <strong>{{ $classCards->count() }}</strong>
                            class(es) for <strong>{{ $academicYear }}</strong>
                        </div>
                        <div class="footer-status">
                            <i class="bi bi-check-circle-fill"></i> Data loaded successfully
                        </div>
                    </div>

                @else

                    <div class="supply-empty-state">
                        <div class="empty-state-icon"><i class="bi bi-mortarboard"></i></div>
                        <h4>No Classes Found</h4>
                        <p>
                            No active students were found for the selected
                            academic year. Try a different academic year.
                        </p>
                    </div>

                @endif
            </section>

        {{-- =====================================================
             SELECTED CLASS / STUDENT VIEW
        ====================================================== --}}
        @else

            <section class="supply-paper-panel">

                <div class="supply-panel-header">
                    <div class="panel-title-wrap">
                        <a href="{{ route('admin.student-supply-kits.index', ['academic_year' => $academicYear]) }}"
                           class="supply-back-btn"
                           title="Back to Classes"
                           aria-label="Back to classes">
                            <i class="bi bi-arrow-left"></i>
                        </a>

                        <div>
                            <h3>{{ $selectedClass }}</h3>
                            <p>
                                @if($selectedClassCard)
                                    {{ $selectedClassCard->total_students }}
                                    {{ $selectedClassCard->total_students == 1 ? 'student' : 'students' }}
                                    &middot; Academic Year {{ $academicYear }}
                                @else
                                    Academic Year {{ $academicYear }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="supply-record-count">
                        <i class="bi bi-folder2-open"></i>
                        <strong>{{ number_format($students->count()) }}</strong>
                        {{ $students->count() == 1 ? 'Student' : 'Students' }}
                    </div>
                </div>

                {{-- SEARCH AND FILTERS --}}
                <form action="{{ route('admin.student-supply-kits.index') }}"
                      method="GET" class="supply-filter-panel">

                    <input type="hidden" name="class" value="{{ $selectedClass }}">

                    <div class="filter-heading">
                        <div class="filter-heading-icon"><i class="bi bi-sliders"></i></div>
                        <div>
                            <h4>Student Filters</h4>
                            <p>Search by student, section, kit status, government scheme or kit name.</p>
                        </div>
                    </div>

                    <div class="supply-filter-grid">

                        <div class="diary-filter-group">
                            <label for="search">Search Student</label>
                            <div class="diary-search-input">
                                <i class="bi bi-search"></i>
                                <input id="search" type="text" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Name or Student ID..." autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-group">
                            <label for="academic_year">Academic Year</label>
                            <div class="diary-search-input">
                                <i class="bi bi-calendar3"></i>
                                <input id="academic_year" type="text" name="academic_year"
                                       value="{{ $academicYear }}" placeholder="2026-27"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-group">
                            <label for="section">Section</label>
                            <select name="section" id="section" class="diary-control">
                                <option value="">All Sections</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section }}" @selected(request('section') == $section)>
                                        {{ $section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="status">Kit Status</label>
                            <select name="status" id="status" class="diary-control">
                                <option value="">All Status</option>
                                @foreach($statusLabels as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}" @selected(request('status') == $statusValue)>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="scheme_id">Government Scheme</label>
                            <select name="scheme_id" id="scheme_id" class="diary-control">
                                <option value="">All Schemes</option>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}" @selected(request('scheme_id') == $scheme->id)>
                                        {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="kit_template_id">Kit Name</label>
                            <select name="kit_template_id" id="kit_template_id" class="diary-control">
                                <option value="">All Kits</option>
                                @foreach($kitTemplates as $kitTemplate)
                                    <option value="{{ $kitTemplate->id }}"
                                        @selected(request('kit_template_id') == $kitTemplate->id)>
                                        {{ $kitTemplate->kit_name }}@if($kitTemplate->scheme) - {{ $kitTemplate->scheme->scheme_name }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-actions">
                            <button type="submit" class="diary-apply-btn">
                                <i class="bi bi-search"></i> Search
                            </button>

                            <a href="{{ route('admin.student-supply-kits.index', [
                                    'class' => $selectedClass,
                                    'academic_year' => $academicYear,
                                ]) }}"
                               class="diary-reset-btn">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </a>

                            <a href="{{ route($printRoute, request()->query()) }}"
                               target="_blank" class="diary-reset-btn">
                                <i class="bi bi-printer"></i> Print
                            </a>
                        </div>

                    </div>
                </form>

                {{-- ACTIVE FILTERS --}}
                @if($hasStudentFilters)
                    <div class="supply-active-filters">
                        <div class="active-filter-items">
                            <span class="active-filter-label">
                                <i class="bi bi-funnel"></i> Active filters
                            </span>

                            @if(request('search'))
                                <span class="filter-chip">
                                    <i class="bi bi-search"></i> {{ request('search') }}
                                </span>
                            @endif

                            @if(request('section'))
                                <span class="filter-chip">
                                    <i class="bi bi-grid-3x3-gap"></i> Section: {{ request('section') }}
                                </span>
                            @endif

                            @if(request('status'))
                                <span class="filter-chip">
                                    <i class="bi bi-circle-half"></i>
                                    {{ $statusLabels[request('status')] ?? ucfirst(request('status')) }}
                                </span>
                            @endif

                            @if(request('scheme_id'))
                                <span class="filter-chip">
                                    <i class="bi bi-bank"></i>
                                    {{ optional($schemes->firstWhere('id', request('scheme_id')))->scheme_name ?? 'Scheme' }}
                                </span>
                            @endif

                            @if(request('kit_template_id'))
                                <span class="filter-chip">
                                    <i class="bi bi-box-seam"></i>
                                    {{ optional($kitTemplates->firstWhere('id', request('kit_template_id')))->kit_name ?? 'Kit' }}
                                </span>
                            @endif
                        </div>

                        <span class="filter-result-count">
                            {{ number_format($students->count()) }} result(s)
                        </span>
                    </div>
                @endif

                {{-- STUDENT TABLE --}}
                @if($students->count())

                    <div class="supply-table-wrapper">
                        <table class="supply-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Student ID</th>
                                    <th>Section</th>
                                    <th>Academic Year</th>
                                    <th>Kit</th>
                                    <th>Distribution Date</th>
                                    <th>Status</th>
                                    <th class="actions-heading">Actions</th>
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

                                        $initial = strtoupper(substr($student->first_name ?? 'S', 0, 1));

                                        // Controller provides the latest kit as $student->supplyKitRecord
                                        $kit    = $student->supplyKitRecord ?? null;
                                        $status = $kit?->status ?? 'not_assigned';
                                    @endphp

                                    <tr>
                                        <td><span class="supply-row-number">{{ $loop->iteration }}</span></td>

                                        {{-- Student --}}
                                        <td>
                                            <div class="student-info">
                                                <div class="student-avatar">{{ $initial }}</div>
                                                <div class="student-details">
                                                    <div class="student-name">{{ $studentName ?: '-' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Student ID --}}
                                        <td>
                                            @if($student->student_id)
                                                <span class="supply-id-chip">
                                                    <i class="bi bi-person-vcard"></i> {{ $student->student_id }}
                                                </span>
                                            @else
                                                <span class="diary-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Section --}}
                                        <td>
                                            @if($student->section)
                                                <span class="supply-chip">{{ $student->section }}</span>
                                            @else
                                                <span class="diary-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Academic Year --}}
                                        <td>
                                            <div class="supply-date">
                                                <div class="date-primary">
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ $student->academic_year ?: $academicYear }}
                                                </div>
                                                <div class="date-caption">Academic Year</div>
                                            </div>
                                        </td>

                                        {{-- Kit --}}
                                        <td>
                                            @if($kit)
                                                <div class="supply-info-cell">
                                                    <span class="supply-info-icon"><i class="bi bi-box-seam"></i></span>
                                                    <div class="supply-info-text">
                                                        <strong>{{ $kit->kitTemplate?->kit_name ?? '-' }}</strong>
                                                        <span>{{ $kit->kitTemplate?->scheme?->scheme_name ?? 'Supply Kit' }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="diary-muted">Not Assigned</span>
                                            @endif
                                        </td>

                                        {{-- Distribution Date --}}
                                        <td>
                                            @if($kit?->distribution_date)
                                                <div class="supply-date">
                                                    <div class="date-primary">
                                                        <i class="bi bi-calendar3"></i>
                                                        {{ \Illuminate\Support\Carbon::parse($kit->distribution_date)->format('d-m-Y') }}
                                                    </div>
                                                    <div class="date-caption">Distributed</div>
                                                </div>
                                            @else
                                                <span class="diary-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @if($status === 'issued')
                                                <span class="supply-status issued"><span class="status-dot"></span> Issued</span>
                                            @elseif($status === 'pending')
                                                <span class="supply-status pending"><span class="status-dot"></span> Pending</span>
                                            @elseif($status === 'cancelled')
                                                <span class="supply-status cancelled"><span class="status-dot"></span> Cancelled</span>
                                            @else
                                                <span class="supply-status not-assigned"><span class="status-dot"></span> Not Assigned</span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="supply-actions">
                                                @if($kit)
                                                    <a href="{{ route('admin.student-supply-kits.show', $kit->id) }}"
                                                       class="supply-action-btn view"
                                                       title="View Record" aria-label="View supply kit record">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.student-supply-kits.edit', $kit->id) }}"
                                                       class="supply-action-btn edit"
                                                       title="Edit Record" aria-label="Edit supply kit record">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <form action="{{ route('admin.student-supply-kits.destroy', $kit->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this supply kit record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="supply-action-btn delete"
                                                                title="Delete Record"
                                                                aria-label="Delete supply kit record">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
                                                       class="supply-action-btn issue"
                                                       title="Issue Kit" aria-label="Issue kit">
                                                        <i class="bi bi-box-seam"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="supply-card-footer">
                        <div>
                            Showing <strong>{{ $students->count() }}</strong>
                            {{ $students->count() == 1 ? 'student' : 'students' }}
                            in <strong>{{ $selectedClass }}</strong>
                        </div>
                        <div class="footer-status">
                            <i class="bi bi-check-circle-fill"></i> Data loaded successfully
                        </div>
                    </div>

                @else

                    <div class="supply-empty-state">
                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                        <h4>No Students Found</h4>
                        <p>No students match the selected filters. Try adjusting your filters.</p>

                        <a href="{{ route('admin.student-supply-kits.index', [
                                'class' => $selectedClass,
                                'academic_year' => $academicYear,
                            ]) }}"
                           class="diary-primary-btn">
                            <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
                        </a>
                    </div>

                @endif
            </section>

        @endif

    </div>
</div>

<style>
/* ==========================================================
   STUDENT SUPPLY KITS
========================================================== */

.supply-page {
    --ink: #263b35;
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
    --serif: Georgia, "Times New Roman", serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.supply-page *,
.supply-page *::before,
.supply-page *::after { box-sizing: border-box; }

.supply-page a,
.supply-page button {
    transition: color .2s ease, background .2s ease, border-color .2s ease,
                box-shadow .2s ease, transform .2s ease;
}

.supply-page .supply-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.supply-page .supply-hero,
.supply-page .supply-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */
.supply-page .supply-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.supply-page .supply-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.supply-page .supply-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.supply-page .supply-hero-icon {
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
    font-size: 25px;
}

.supply-page .supply-hero-text { min-width: 0; }

.supply-page .supply-breadcrumb {
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

.supply-page .supply-breadcrumb i { color: #b1a895; font-size: 9px; }

.supply-page .supply-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
}

.supply-page .supply-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.supply-page .supply-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
}

.supply-page .supply-hero-actions {
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

.supply-page .hero-decoration {
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

.supply-page .hero-handwritten {
    color: #f0e5c9;
    font-family: "Caveat", cursive;
    font-size: 23px;
    line-height: 1.4;
}

.supply-page .supply-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.supply-page .hero-button-group {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* BUTTONS */
.supply-page .diary-primary-btn,
.supply-page .diary-ghost-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.supply-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}

.supply-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
}

.supply-page .supply-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.supply-page .supply-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

.supply-page .diary-ghost-btn {
    border: 1px solid rgba(255, 255, 255, .25);
    color: #f0e5c9;
    background: rgba(255, 255, 255, .07);
}

.supply-page .diary-ghost-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, .45);
    color: #fff;
    background: rgba(255, 255, 255, .14);
}

/* ALERTS */
.supply-page .supply-alert {
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

.supply-page .supply-alert.error {
    border-color: #eed6cf;
    color: var(--red);
    background: #fff1ed;
}

.supply-page .supply-alert .alert-icon {
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

.supply-page .supply-alert.error .alert-icon {
    color: var(--red);
    background: #fbdcd4;
}

/* KPI CARDS */
.supply-page .supply-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.supply-page .supply-kpi {
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
}

.supply-page .supply-kpi.blue   { --kpi-color: #426b8a; }
.supply-page .supply-kpi.green  { --kpi-color: #43835d; }
.supply-page .supply-kpi.orange { --kpi-color: #a87543; }
.supply-page .supply-kpi.red    { --kpi-color: #b95b50; }

.supply-page .supply-kpi::before {
    content: attr(data-tab);
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

.supply-page .supply-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.supply-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.supply-page .kpi-label { color: #758176; font-size: 11px; font-weight: 700; }

.supply-page .kpi-icon {
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

.supply-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: clamp(24px, 2vw, 35px);
    font-weight: 700;
    line-height: 1.1;
}

.supply-page .kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* PANEL */
.supply-page .supply-paper-panel { overflow: hidden; margin-bottom: 28px; }

.supply-page .supply-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.supply-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.supply-page .panel-title-icon,
.supply-page .filter-heading-icon,
.supply-page .supply-back-btn {
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
    text-decoration: none;
}

.supply-page .supply-back-btn:hover {
    transform: translateX(-2px);
    border-color: #b5c4b0;
    background: #f0f3e9;
}

.supply-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 19px;
    font-weight: 700;
}

.supply-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.supply-page .supply-record-count {
    display: inline-flex;
    align-items: center;
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

.supply-page .supply-record-count i,
.supply-page .supply-record-count strong { color: var(--ink); }

/* FILTERS */
.supply-page .supply-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.supply-page .filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.supply-page .filter-heading-icon { color: var(--sage); background: #edf2e9; }

.supply-page .filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 17px;
    font-weight: 700;
}

.supply-page .filter-heading p { margin: 5px 0 0; color: var(--muted); font-size: 11px; }

.supply-page .supply-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    align-items: end;
    gap: 16px;
}

.supply-page .supply-filter-grid.year-only { grid-template-columns: minmax(220px, 1fr) auto; }

.supply-page .supply-filter-grid .diary-filter-actions { grid-column: span 2; }
.supply-page .supply-filter-grid.year-only .diary-filter-actions { grid-column: auto; }

.supply-page .diary-filter-group { min-width: 0; }

.supply-page .diary-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.supply-page .diary-control,
.supply-page .diary-search-input {
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
}

.supply-page .diary-control { padding: 0 12px; cursor: pointer; }

.supply-page .diary-control:focus,
.supply-page .diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.supply-page .diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.supply-page .diary-search-input > i { color: #829083; font-size: 13px; }

.supply-page .diary-search-input input {
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

.supply-page .diary-filter-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

.supply-page .diary-apply-btn,
.supply-page .diary-reset-btn {
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

.supply-page .diary-apply-btn { border-color: var(--ink); color: #fff; background: var(--ink); }
.supply-page .diary-apply-btn:hover { border-color: #426652; color: #fff; background: #426652; }

.supply-page .diary-reset-btn { border-color: #deded2; color: #5d6b60; background: #fffefa; }
.supply-page .diary-reset-btn:hover { border-color: #b5c4b0; color: var(--ink); background: #f0f3e9; }

/* ACTIVE FILTERS */
.supply-page .supply-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
}

.supply-page .active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.supply-page .active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.supply-page .active-filter-label i { color: var(--green); }

.supply-page .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 260px;
    padding: 6px 10px;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 700;
}

.supply-page .filter-chip i { color: var(--amber); }

.supply-page .filter-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* CLASS CARDS */
.supply-page .supply-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    padding: 28px 24px;
    background: #faf9f3;
}

.supply-page .supply-class-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    overflow: hidden;
    border: 1px solid var(--line);
    border-top: 3px solid var(--sage);
    border-radius: 12px;
    color: inherit;
    background: var(--paper);
    text-decoration: none;
}

.supply-page .supply-class-card:hover {
    transform: translateY(-3px);
    border-color: #cbd7c9;
    color: inherit;
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.supply-page .supply-class-top {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 20px 20px 16px;
}

.supply-page .supply-class-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #dce4d8;
    border-radius: 12px;
    color: var(--sage);
    background: #edf2e9;
    font-size: 17px;
}

.supply-page .supply-class-text { flex: 1 1 auto; min-width: 0; }

.supply-page .supply-class-text h5 {
    margin: 0;
    overflow: hidden;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 19px;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supply-page .supply-class-total { margin-top: 4px; color: var(--muted); font-size: 11px; }
.supply-page .supply-class-arrow { color: #b1a895; font-size: 12px; }

.supply-page .supply-class-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
    margin: 0 20px 18px;
    padding-top: 16px;
    border-top: 1px dashed #e1dccf;
}

.supply-page .supply-class-stat {
    min-width: 0;
    padding: 10px 4px;
    border: 1px solid transparent;
    border-radius: 9px;
    text-align: center;
}

.supply-page .supply-class-stat .stat-number {
    font-family: var(--serif);
    font-size: 19px;
    font-weight: 700;
    line-height: 1.2;
}

.supply-page .supply-class-stat .stat-label {
    margin-top: 4px;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.supply-page .supply-class-stat.issued       { border-color: #d4e5d0; color: #347849; background: #edf6e9; }
.supply-page .supply-class-stat.pending      { border-color: #e7dfc9; color: #765e32; background: #faf5e8; }
.supply-page .supply-class-stat.cancelled    { border-color: #eed6cf; color: var(--red); background: #fff1ed; }
.supply-page .supply-class-stat.not-assigned { border-color: #e4e0d4; color: #6d786d; background: #f8f7f0; }

.supply-page .supply-class-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: auto;
    padding: 13px 20px;
    border-top: 1px dashed #ddd7c9;
    color: var(--ink);
    background: #f8f7f0;
    font-size: 11px;
    font-weight: 800;
}

.supply-page .supply-class-footer i { color: var(--green); font-size: 11px; }
.supply-page .supply-class-card:hover .supply-class-footer i { transform: translateX(3px); }

/* STUDENT TABLE */
.supply-page .supply-table-wrapper { width: 100%; overflow-x: auto; }

.supply-page .supply-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.supply-page .supply-table thead th {
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

.supply-page .supply-table thead th.actions-heading { text-align: right; }

.supply-page .supply-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    font-size: 11px;
    vertical-align: middle;
}

.supply-page .supply-table tbody tr:hover { background: #f8f8f1; }
.supply-page .supply-table tbody tr:last-child td { border-bottom: none; }

.supply-page .supply-row-number {
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

.supply-page .student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.supply-page .student-avatar {
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

.supply-page .student-details { min-width: 0; }

.supply-page .student-name {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supply-page .supply-id-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #828c7f;
    font-size: 10px;
    white-space: nowrap;
}

.supply-page .supply-id-chip i { color: var(--amber); }

.supply-page .supply-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 6px 10px;
    border: 1px solid #d9e2e9;
    border-radius: 7px;
    color: var(--blue);
    background: #edf4f8;
    font-size: 10px;
    font-weight: 800;
}

.supply-page .supply-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 170px;
}

.supply-page .supply-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border: 1px solid #ebdfcc;
    border-radius: 10px;
    color: var(--amber);
    background: #faf2e7;
    font-size: 12px;
}

.supply-page .supply-info-text { min-width: 0; }

.supply-page .supply-info-text strong,
.supply-page .supply-info-text span {
    display: block;
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supply-page .supply-info-text strong { color: #34443a; font-size: 10px; font-weight: 800; }
.supply-page .supply-info-text span   { margin-top: 4px; color: #879083; font-size: 9px; }

.supply-page .supply-date { min-width: 105px; }

.supply-page .date-primary {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.supply-page .date-primary i { color: var(--blue); }
.supply-page .date-caption { margin-top: 5px; color: #879083; font-size: 9px; }
.supply-page .diary-muted { color: #929a8e; font-size: 12px; }

/* STATUS */
.supply-page .supply-status {
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

.supply-page .supply-status.issued       { border-color: #d4e5d0; color: #347849; background: #edf6e9; }
.supply-page .supply-status.pending      { border-color: #e7dfc9; color: #765e32; background: #faf5e8; }
.supply-page .supply-status.cancelled    { border-color: #eed6cf; color: var(--red); background: #fff1ed; }
.supply-page .supply-status.not-assigned { border-color: #e4e0d4; color: #6d786d; background: #f8f7f0; }

.supply-page .status-dot { flex: 0 0 7px; width: 7px; height: 7px; border-radius: 50%; }

.supply-page .supply-status.issued .status-dot       { background: #43835d; }
.supply-page .supply-status.pending .status-dot      { background: #a87543; }
.supply-page .supply-status.cancelled .status-dot    { background: #b95b50; }
.supply-page .supply-status.not-assigned .status-dot { background: #929a8e; }

/* ACTION BUTTONS */
.supply-page .supply-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.supply-page .supply-actions form { display: inline-flex; margin: 0; }

.supply-page .supply-action-btn {
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
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.supply-page .supply-action-btn:hover { transform: translateY(-2px); }

.supply-page .supply-action-btn.view:hover   { border-color: #cbdce5; color: var(--blue); background: #edf4f8; }
.supply-page .supply-action-btn.edit:hover   { border-color: #ead7b9; color: var(--amber); background: #faf3e6; }
.supply-page .supply-action-btn.delete:hover { border-color: #edcbc3; color: var(--red); background: #fff0ec; }

.supply-page .supply-action-btn.issue { border-color: #d4e5d0; color: var(--green); background: #f4f9f1; }
.supply-page .supply-action-btn.issue:hover { border-color: #b9d6b4; background: #edf6e9; }

/* FOOTER */
.supply-page .supply-card-footer {
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

.supply-page .supply-card-footer strong { color: var(--ink); }

.supply-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-weight: 700;
}

.supply-page .footer-status i { color: var(--green); }

/* EMPTY STATE */
.supply-page .supply-empty-state { padding: 65px 20px; text-align: center; }

.supply-page .empty-state-icon {
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
    font-size: 27px;
}

.supply-page .supply-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 700;
}

.supply-page .supply-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
    .supply-page .supply-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 35px; }
    .supply-page .supply-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .supply-page .supply-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .supply-page .supply-filter-grid .diary-filter-actions { grid-column: span 2; }
}

@media (max-width: 900px) {
    .supply-page .supply-hero { grid-template-columns: 1fr; }
    .supply-page .supply-hero-actions { min-height: 220px; }
}

@media (max-width: 650px) {
    .supply-page .supply-container { padding: 18px 14px; }

    .supply-page .supply-hero::before { left: 18px; }

    .supply-page .supply-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .supply-page .supply-hero-icon { flex-basis: 43px; width: 43px; height: 43px; font-size: 18px; }
    .supply-page .supply-hero-text h2 { font-size: 24px; }

    .supply-page .supply-kpis { gap: 15px; row-gap: 35px; }
    .supply-page .supply-kpi { padding: 19px 15px 15px; }
    .supply-page .kpi-icon { flex-basis: 38px; width: 38px; height: 38px; }

    .supply-page .supply-panel-header { align-items: flex-start; padding: 16px; }

    .supply-page .supply-filter-panel { padding: 17px 15px; }

    .supply-page .supply-filter-grid,
    .supply-page .supply-filter-grid.year-only { grid-template-columns: 1fr; gap: 14px; }

    .supply-page .supply-filter-grid .diary-filter-actions { grid-column: auto; }
    .supply-page .diary-filter-actions > * { flex: 1; }

    .supply-page .supply-active-filters { align-items: flex-start; flex-direction: column; padding: 13px 15px; }

    .supply-page .supply-grid { grid-template-columns: 1fr; padding: 20px 15px; }

    .supply-page .supply-card-footer { align-items: flex-start; flex-direction: column; padding: 14px 15px; }
}

@media (max-width: 400px) {
    .supply-page .supply-kpis { grid-template-columns: 1fr; }
    .supply-page .supply-panel-header { flex-wrap: wrap; gap: 12px; }
    .supply-page .supply-class-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .supply-page .hero-button-group { flex-direction: column; width: 100%; }
    .supply-page .hero-button-group > * { width: 100%; }
}

.supply-page a:focus-visible,
.supply-page button:focus-visible,
.supply-page input:focus-visible,
.supply-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .supply-page *,
    .supply-page *::before,
    .supply-page *::after {
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection