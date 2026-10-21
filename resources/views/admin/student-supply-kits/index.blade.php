@extends('layouts.app')

@section('title', 'Student Supply Kits | Admin')
@section('page-title', 'Student Supply Kits')

@section('content')

@php
    $isClassView = $selectedClass === '';

    $selectedClassCard = $isClassView
        ? null
        : $classCards->firstWhere('class_name', $selectedClass);

    if ($isClassView) {
        $kpiTotal = (int) $classCards->sum('total_students');
        $kpiIssued = (int) $classCards->sum('issued');
        $kpiPending = (int) $classCards->sum('pending');
        $kpiNotAssigned = (int) $classCards->sum('not_assigned');
        $kpiScope = 'across all classes';
    } else {
        $kpiTotal = (int) ($selectedClassCard->total_students ?? 0);
        $kpiIssued = (int) ($selectedClassCard->issued ?? 0);
        $kpiPending = (int) ($selectedClassCard->pending ?? 0);
        $kpiNotAssigned = (int) ($selectedClassCard->not_assigned ?? 0);
        $kpiScope = 'in ' . $selectedClass;
    }

    $hasStudentFilters = request('search')
        || request('section')
        || request('status')
        || request('scheme_id')
        || request('kit_template_id');

    $statusLabels = [
        'issued' => 'Issued',
        'pending' => 'Pending',
        'cancelled' => 'Cancelled',
        'not_assigned' => 'Not Assigned',
    ];
@endphp

<div class="supply-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="supply-alert success" role="status">
                <span class="alert-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="supply-alert error" role="alert">
                <span class="alert-icon"><i class="fas fa-exclamation"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="supply-hero">
            <div class="supply-hero-content">
                <div class="supply-hero-icon">
                    <i class="fas fa-box-open"></i>
                </div>

                <div class="supply-hero-text">
                    <div class="supply-breadcrumb">
                        <span>Student Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Supply Kits</span>

                        @unless($isClassView)
                            <i class="fas fa-chevron-right"></i>
                            <span>{{ $selectedClass }}</span>
                        @endunless
                    </div>

                    <h2>Student Supply Kits</h2>

                    <p>
                        Manage government supply kit distribution
                        class-wise, track issued and pending kits and
                        keep every student's record up to date.
                    </p>

                    <div class="supply-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Government Schemes &amp; Student Supply Management</span>
                    </div>
                </div>
            </div>

            <div class="supply-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-truck-loading"></i>
                </div>

                <div class="hero-handwritten">Every Kit. Delivered.</div>

                <p>
                    Every student matters. Create kits, distribute them
                    and print distribution lists.
                </p>

                <div class="hero-button-group">
                    <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-box"></i>
                        Kit Distribution
                    </a>

                    <a href="{{ route('admin.kit-templates.create') }}"
                       class="diary-ghost-btn">
                        <i class="fas fa-plus"></i>
                        Kit Creation
                    </a>

                    <a href="{{ route('admin.student-supply-kits.print', request()->query()) }}"
                       target="_blank"
                       class="diary-ghost-btn">
                        <i class="fas fa-print"></i>
                        Print
                    </a>
                </div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="supply-kpis">

            <article class="supply-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($kpiTotal) }}</div>
                <div class="kpi-caption">Active students {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Kits Issued</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($kpiIssued) }}</div>
                <div class="kpi-caption">Issued {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Kits Pending</span>
                    <span class="kpi-icon">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($kpiPending) }}</div>
                <div class="kpi-caption">Pending {{ $kpiScope }}</div>
            </article>

            <article class="supply-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Not Assigned</span>
                    <span class="kpi-icon">
                        <i class="fas fa-minus-circle"></i>
                    </span>
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
                        <div class="panel-title-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <div>
                            <h3>Classes</h3>
                            <p>Select a class to view and manage its supply kits.</p>
                        </div>
                    </div>

                    <div class="supply-record-count">
                        <i class="fas fa-folder-open"></i>
                        <strong>{{ number_format($classCards->count()) }}</strong>
                        {{ $classCards->count() == 1 ? 'Class' : 'Classes' }}
                    </div>
                </div>

                {{-- ACADEMIC YEAR FILTER --}}
                <form action="{{ route('admin.student-supply-kits.index') }}"
                      method="GET"
                      class="supply-filter-panel">

                    <div class="filter-heading">
                        <div class="filter-heading-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div>
                            <h4>Select Academic Year</h4>
                            <p>Load the classes and kit status for an academic year.</p>
                        </div>
                    </div>

                    <div class="supply-filter-grid year-only">

                        <div class="diary-filter-group">
                            <label for="academic_year">Academic Year</label>
                            <div class="diary-search-input">
                                <i class="far fa-calendar-alt"></i>
                                <input id="academic_year"
                                       type="text"
                                       name="academic_year"
                                       value="{{ $academicYear }}"
                                       placeholder="2026-27"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-actions">
                            <button type="submit" class="diary-apply-btn">
                                <i class="fas fa-search"></i> Load Classes
                            </button>
                        </div>

                    </div>
                </form>

                @if($classCards->count())

                    {{-- CLASS CARDS --}}
                    <div class="supply-grid">

                        @foreach($classCards as $card)

                            <a href="{{ route('admin.student-supply-kits.index', [
                                    'class' => $card->class_name,
                                    'academic_year' => $academicYear,
                                ]) }}"
                               class="supply-class-card">

                                <div class="supply-class-top">
                                    <span class="supply-class-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </span>

                                    <div class="supply-class-text">
                                        <h5>{{ $card->class_name }}</h5>
                                        <div class="supply-class-total">
                                            {{ $card->total_students }}
                                            {{ $card->total_students == 1 ? 'Student' : 'Students' }}
                                        </div>
                                    </div>

                                    <span class="supply-class-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
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
                                    <i class="fas fa-arrow-right"></i>
                                </div>

                            </a>

                        @endforeach

                    </div>

                    {{-- FOOTER --}}
                    <div class="supply-card-footer">
                        <div>
                            Showing <strong>{{ $classCards->count() }}</strong>
                            class(es) for <strong>{{ $academicYear }}</strong>
                        </div>
                        <div class="footer-status">
                            <i class="fas fa-circle-check"></i>
                            Data loaded successfully
                        </div>
                    </div>

                @else

                    {{-- EMPTY STATE --}}
                    <div class="supply-empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

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
                        <a href="{{ route('admin.student-supply-kits.index', [
                                'academic_year' => $academicYear,
                            ]) }}"
                           class="supply-back-btn"
                           title="Back to Classes"
                           aria-label="Back to classes">
                            <i class="fas fa-arrow-left"></i>
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
                        <i class="fas fa-folder-open"></i>
                        <strong>{{ number_format($students->count()) }}</strong>
                        {{ $students->count() == 1 ? 'Student' : 'Students' }}
                    </div>
                </div>

                {{-- SEARCH AND FILTERS --}}
                <form action="{{ route('admin.student-supply-kits.index') }}"
                      method="GET"
                      class="supply-filter-panel">

                    <input type="hidden" name="class" value="{{ $selectedClass }}">

                    <div class="filter-heading">
                        <div class="filter-heading-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div>
                            <h4>Student Filters</h4>
                            <p>Search by student, section, kit status, government scheme or kit name.</p>
                        </div>
                    </div>

                    <div class="supply-filter-grid">

                        <div class="diary-filter-group search-group">
                            <label for="search">Search Student</label>
                            <div class="diary-search-input">
                                <i class="fas fa-search"></i>
                                <input id="search"
                                       type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Name or Student ID..."
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-group">
                            <label for="academic_year">Academic Year</label>
                            <div class="diary-search-input">
                                <i class="far fa-calendar-alt"></i>
                                <input id="academic_year"
                                       type="text"
                                       name="academic_year"
                                       value="{{ $academicYear }}"
                                       placeholder="2026-27"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="diary-filter-group">
                            <label for="section">Section</label>
                            <select name="section"
                                    id="section"
                                    class="diary-control">
                                <option value="">All Sections</option>

                                @foreach($sections as $section)
                                    <option value="{{ $section }}"
                                        @selected(request('section') == $section)>
                                        {{ $section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="status">Kit Status</label>
                            <select name="status"
                                    id="status"
                                    class="diary-control">
                                <option value="">All Status</option>

                                @foreach($statusLabels as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}"
                                        @selected(request('status') == $statusValue)>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="scheme_id">Government Scheme</label>
                            <select name="scheme_id"
                                    id="scheme_id"
                                    class="diary-control">
                                <option value="">All Schemes</option>

                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}"
                                        @selected(request('scheme_id') == $scheme->id)>
                                        {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="diary-filter-group">
                            <label for="kit_template_id">Kit Name</label>
                            <select name="kit_template_id"
                                    id="kit_template_id"
                                    class="diary-control">
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
                                <i class="fas fa-search"></i> Search
                            </button>

                            <a href="{{ route('admin.student-supply-kits.index', [
                                    'class' => $selectedClass,
                                    'academic_year' => $academicYear,
                                ]) }}"
                               class="diary-reset-btn">
                                <i class="fas fa-rotate-left"></i> Reset
                            </a>

                            <a href="{{ route('admin.student-supply-kits.print', request()->query()) }}"
                               target="_blank"
                               class="diary-reset-btn">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>

                    </div>
                </form>

                {{-- ACTIVE FILTERS --}}
                @if($hasStudentFilters)
                    <div class="supply-active-filters">
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

                            @if(request('section'))
                                <span class="filter-chip">
                                    <i class="fas fa-th"></i>
                                    Section: {{ request('section') }}
                                </span>
                            @endif

                            @if(request('status'))
                                <span class="filter-chip">
                                    <i class="fas fa-circle-half-stroke"></i>
                                    {{ $statusLabels[request('status')] ?? ucfirst(request('status')) }}
                                </span>
                            @endif

                            @if(request('scheme_id'))
                                <span class="filter-chip">
                                    <i class="fas fa-landmark"></i>
                                    {{ optional($schemes->firstWhere('id', request('scheme_id')))->scheme_name ?? 'Scheme' }}
                                </span>
                            @endif

                            @if(request('kit_template_id'))
                                <span class="filter-chip">
                                    <i class="fas fa-box"></i>
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

                                        $initial = strtoupper(
                                            substr($student->first_name ?? 'S', 0, 1)
                                        );

                                        /*
                                         * Controller provides the latest kit as:
                                         * $student->supplyKitRecord
                                         */
                                        $kit = $student->supplyKitRecord ?? null;

                                        $status = $kit?->status ?? 'not_assigned';
                                    @endphp

                                    <tr>

                                        <td>
                                            <span class="supply-row-number">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>

                                        {{-- Student --}}
                                        <td>
                                            <div class="student-info">
                                                <div class="student-avatar">
                                                    {{ $initial }}
                                                </div>

                                                <div class="student-details">
                                                    <div class="student-name">
                                                        {{ $studentName ?: '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Student ID --}}
                                        <td>
                                            @if($student->student_id)
                                                <span class="supply-id-chip">
                                                    <i class="fas fa-id-badge"></i>
                                                    {{ $student->student_id }}
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
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $student->academic_year ?: $academicYear }}
                                                </div>
                                                <div class="date-caption">Academic Year</div>
                                            </div>
                                        </td>

                                        {{-- Kit --}}
                                        <td>
                                            @if($kit)
                                                <div class="supply-info-cell">
                                                    <span class="supply-info-icon kit-icon">
                                                        <i class="fas fa-box"></i>
                                                    </span>
                                                    <div class="supply-info-text">
                                                        <strong>{{ $kit->kitTemplate?->kit_name ?? '-' }}</strong>
                                                        <span>
                                                            {{ $kit->kitTemplate?->scheme?->scheme_name ?? 'Supply Kit' }}
                                                        </span>
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
                                                        <i class="far fa-calendar-alt"></i>
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
                                                <span class="supply-status issued">
                                                    <span class="status-dot"></span> Issued
                                                </span>
                                            @elseif($status === 'pending')
                                                <span class="supply-status pending">
                                                    <span class="status-dot"></span> Pending
                                                </span>
                                            @elseif($status === 'cancelled')
                                                <span class="supply-status cancelled">
                                                    <span class="status-dot"></span> Cancelled
                                                </span>
                                            @else
                                                <span class="supply-status not-assigned">
                                                    <span class="status-dot"></span> Not Assigned
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="supply-actions">

                                                @if($kit)

                                                    <a href="{{ route('admin.student-supply-kits.show', $kit->id) }}"
                                                       class="supply-action-btn view"
                                                       title="View Record"
                                                       aria-label="View supply kit record">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.student-supply-kits.edit', $kit->id) }}"
                                                       class="supply-action-btn edit"
                                                       title="Edit Record"
                                                       aria-label="Edit supply kit record">
                                                        <i class="fas fa-pen"></i>
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
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>

                                                @else

                                                    <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
                                                       class="supply-action-btn issue"
                                                       title="Issue Kit"
                                                       aria-label="Issue kit">
                                                        <i class="fas fa-box"></i>
                                                    </a>

                                                @endif

                                            </div>
                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- FOOTER --}}
                    <div class="supply-card-footer">
                        <div>
                            Showing <strong>{{ $students->count() }}</strong>
                            {{ $students->count() == 1 ? 'student' : 'students' }}
                            in <strong>{{ $selectedClass }}</strong>
                        </div>
                        <div class="footer-status">
                            <i class="fas fa-circle-check"></i>
                            Data loaded successfully
                        </div>
                    </div>

                @else

                    {{-- EMPTY STATE --}}
                    <div class="supply-empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <h4>No Students Found</h4>
                        <p>
                            No students match the selected filters.
                            Try adjusting your filters.
                        </p>

                        <a href="{{ route('admin.student-supply-kits.index', [
                                'class' => $selectedClass,
                                'academic_year' => $academicYear,
                            ]) }}"
                           class="diary-primary-btn">
                            <i class="fas fa-rotate-left"></i> Clear Filters
                        </a>
                    </div>

                @endif
            </section>

        @endif

    </div>
</div>

<style>
/* ==========================================================
   STUDENT SUPPLY KITS — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.supply-page {
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

.supply-page *,
.supply-page *::before,
.supply-page *::after {
    box-sizing: border-box;
}

.supply-page a,
.supply-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.supply-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.supply-hero,
.supply-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.supply-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.supply-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.supply-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.supply-hero-icon {
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

.supply-hero-text {
    min-width: 0;
}

.supply-breadcrumb {
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

.supply-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.supply-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.supply-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.supply-hero-note {
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

.supply-hero-note i {
    font-size: 13px;
}

.supply-hero-actions {
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

.supply-hero-actions::before,
.supply-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.supply-hero-actions::before {
    top: -115px;
    right: -60px;
}

.supply-hero-actions::after {
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

.supply-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.hero-button-group {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
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

.supply-hero-actions .diary-primary-btn {
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

.supply-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

.diary-ghost-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid rgba(255, 255, 255, .25);
    border-radius: 9px;
    color: #f0e5c9;
    background: rgba(255, 255, 255, .07);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.diary-ghost-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, .45);
    color: #fff;
    background: rgba(255, 255, 255, .14);
}

/* ALERTS */

.supply-alert {
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

.supply-alert.error {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.supply-alert .alert-icon {
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

.supply-alert.error .alert-icon {
    color: #b95b50;
    background: #fbdcd4;
}

/* KPI CARDS */

.supply-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.supply-kpi {
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

.supply-kpi.blue { --kpi-color: #426b8a; }
.supply-kpi.green { --kpi-color: #43835d; }
.supply-kpi.orange { --kpi-color: #a87543; }
.supply-kpi.red { --kpi-color: #b95b50; }

.supply-kpi::before {
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

.supply-kpi.blue::before { content: "STUDENTS"; }
.supply-kpi.green::before { content: "ISSUED"; }
.supply-kpi.orange::before { content: "PENDING"; }
.supply-kpi.red::before { content: "NOT ASSIGNED"; }

.supply-kpi:hover {
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

.supply-kpi .kpi-icon {
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

.supply-paper-panel {
    overflow: hidden;
}

.supply-panel-header {
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
.filter-heading-icon,
.supply-back-btn {
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

.supply-back-btn:hover {
    transform: translateX(-2px);
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
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

.supply-record-count {
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

.supply-record-count i,
.supply-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.supply-filter-panel {
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

.supply-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    align-items: end;
    gap: 16px;
}

.supply-filter-grid.year-only {
    grid-template-columns: minmax(220px, 1fr) auto;
}

.supply-filter-grid .diary-filter-actions {
    grid-column: span 2;
}

.supply-filter-grid.year-only .diary-filter-actions {
    grid-column: auto;
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
    flex-wrap: wrap;
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

.supply-active-filters {
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

/* CLASS CARDS */

.supply-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    padding: 28px 24px;
    background: #faf9f3;
}

.supply-class-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    overflow: hidden;
    border: 1px solid var(--line);
    border-top: 3px solid var(--sage);
    border-radius: 12px;
    color: inherit;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
    text-decoration: none;
}

.supply-class-card:hover {
    transform: translateY(-3px);
    border-color: #cbd7c9;
    color: inherit;
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.supply-class-top {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 20px 20px 16px;
}

.supply-class-icon {
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

.supply-class-text {
    flex: 1 1 auto;
    min-width: 0;
}

.supply-class-text h5 {
    margin: 0;
    overflow: hidden;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supply-class-total {
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
}

.supply-class-arrow {
    color: #b1a895;
    font-size: 12px;
}

.supply-class-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
    margin: 0 20px 18px;
    padding-top: 16px;
    border-top: 1px dashed #e1dccf;
}

.supply-class-stat {
    min-width: 0;
    padding: 10px 4px;
    border: 1px solid transparent;
    border-radius: 9px;
    text-align: center;
}

.supply-class-stat .stat-number {
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    line-height: 1.2;
}

.supply-class-stat .stat-label {
    margin-top: 4px;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.supply-class-stat.issued {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.supply-class-stat.pending {
    border-color: #e7dfc9;
    color: #765e32;
    background: #faf5e8;
}

.supply-class-stat.cancelled {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.supply-class-stat.not-assigned {
    border-color: #e4e0d4;
    color: #6d786d;
    background: #f8f7f0;
}

.supply-class-footer {
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

.supply-class-footer i {
    color: var(--green);
    font-size: 11px;
}

.supply-class-card:hover .supply-class-footer i {
    transform: translateX(3px);
}

/* STUDENT TABLE */

.supply-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.supply-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.supply-table thead th {
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

.supply-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.supply-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.supply-table tbody tr:hover {
    background: #f8f8f1;
}

.supply-table tbody tr:last-child td {
    border-bottom: none;
}

.supply-table .actions-heading {
    text-align: right;
}

.supply-row-number {
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

.supply-id-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #828c7f;
    font-size: 10px;
    white-space: nowrap;
}

.supply-id-chip i {
    color: var(--amber);
}

.supply-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 6px 10px;
    border: 1px solid #d9e2e9;
    border-radius: 7px;
    color: #426b8a;
    background: #edf4f8;
    font-size: 10px;
    font-weight: 800;
}

/* KIT */

.supply-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 170px;
}

.supply-info-icon {
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

.kit-icon {
    border-color: #ebdfcc;
    color: #a87543;
    background: #faf2e7;
}

.supply-info-text {
    min-width: 0;
}

.supply-info-text strong {
    display: block;
    max-width: 190px;
    overflow: hidden;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supply-info-text span {
    display: block;
    max-width: 190px;
    margin-top: 4px;
    overflow: hidden;
    color: #879083;
    font-size: 9px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* DATE */

.supply-date {
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

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* STATUS */

.supply-status {
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

.supply-status.issued {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.supply-status.pending {
    border-color: #e7dfc9;
    color: #765e32;
    background: #faf5e8;
}

.supply-status.cancelled {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.supply-status.not-assigned {
    border-color: #e4e0d4;
    color: #6d786d;
    background: #f8f7f0;
}

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.supply-status.issued .status-dot { background: #43835d; }
.supply-status.pending .status-dot { background: #a87543; }
.supply-status.cancelled .status-dot { background: #b95b50; }
.supply-status.not-assigned .status-dot { background: #929a8e; }

/* ACTION BUTTONS */

.supply-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.supply-actions form {
    display: inline-flex;
    margin: 0;
}

.supply-action-btn {
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

.supply-action-btn:hover {
    transform: translateY(-2px);
}

.supply-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.supply-action-btn.edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.supply-action-btn.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

.supply-action-btn.issue {
    border-color: #d4e5d0;
    color: #43835d;
    background: #f4f9f1;
}

.supply-action-btn.issue:hover {
    border-color: #b9d6b4;
    background: #edf6e9;
}

/* FOOTER */

.supply-card-footer {
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

.supply-card-footer strong {
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

.supply-empty-state {
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

.supply-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.supply-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .supply-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .supply-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .supply-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .supply-filter-grid .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .supply-hero {
        grid-template-columns: 1fr;
    }

    .supply-hero-actions {
        min-height: 220px;
    }

    .supply-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .supply-page .container-fluid {
        padding: 18px 14px !important;
    }

    .supply-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .supply-hero::before {
        left: 18px;
    }

    .supply-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .supply-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .supply-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .supply-hero-text h2 {
        font-size: 24px;
    }

    .supply-hero-text p {
        font-size: 11px;
    }

    .supply-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .supply-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .supply-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .supply-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .supply-kpi .kpi-icon {
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

    .supply-panel-header {
        align-items: flex-start;
        padding: 16px;
    }

    .panel-title-wrap {
        gap: 10px;
    }

    .panel-title-icon,
    .supply-back-btn {
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

    .supply-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .supply-filter-panel {
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

    .supply-filter-grid,
    .supply-filter-grid.year-only {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .supply-filter-grid .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .supply-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .supply-grid {
        grid-template-columns: 1fr;
        padding: 20px 15px;
    }

    .supply-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .supply-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .supply-kpis {
        grid-template-columns: 1fr;
    }

    .supply-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .supply-record-count {
        margin-left: 46px;
    }

    .supply-class-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
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

    .hero-button-group {
        flex-direction: column;
        width: 100%;
    }

    .hero-button-group > * {
        width: 100%;
    }
}

/* ACCESSIBILITY */

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
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection