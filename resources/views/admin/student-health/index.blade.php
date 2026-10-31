
@extends('layouts.app')

@section('title', 'Student Health Records | Admin')
@section('page-title', 'Student Health Records')

@section('content')

@php
    $classifyHealth = function ($text) {
        $text = strtolower(trim((string) $text));

        if ($text === '') {
            return 'unknown';
        }

        if (
            str_contains($text, 'good') ||
            str_contains($text, 'normal') ||
            str_contains($text, 'healthy')
        ) {
            return 'good';
        }

        if (
            str_contains($text, 'refer') ||
            str_contains($text, 'poor')
        ) {
            return 'referred';
        }

        return 'attention';
    };

    $totalStudents = $students->total();
    $checkedCount = 0;
    $goodCount = 0;
    $attentionCount = 0;

    foreach ($students->getCollection() as $summaryStudent) {
        $summaryRecord = $summaryStudent->healthRecords
            ->sortByDesc('checkup_date')
            ->first();

        if (!$summaryRecord) {
            continue;
        }

        $checkedCount++;

        $summaryClass = $classifyHealth(
            $summaryRecord->overall_health_status
        );

        if ($summaryClass === 'good') {
            $goodCount++;
        } elseif (in_array($summaryClass, ['attention', 'referred'], true)) {
            $attentionCount++;
        }
    }

    $hasFilters = request()->filled('academic_year')
        || request()->filled('class')
        || request()->filled('section')
        || request()->filled('search');
@endphp

<div class="health-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="health-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="health-hero">
            <div class="health-hero-content">
                <div class="health-hero-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>

                <div class="health-hero-text">
                    <div class="health-breadcrumb">
                        <span>Student Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Health Records</span>
                    </div>

                    <h2>Student Health Records</h2>

                    <p>
                        Manage annual student health checkups, measurements
                        and health status from one place.
                    </p>

                    <div class="health-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Student Wellbeing &amp; Health Management</span>
                    </div>
                </div>
            </div>

            <div class="health-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-notes-medical"></i>
                </div>

                <div class="hero-handwritten">
                    Healthy Students. Happy Learning.
                </div>

                <p>
                    Every checkup matters. Keep annual health records
                    complete and up to date.
                </p>

                <a href="{{ route('admin.students.index') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-user-plus"></i>
                    Select Student
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="health-kpis">

            <article class="health-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ number_format($totalStudents) }}
                </div>

                <div class="kpi-caption">
                    Students matching your filters
                </div>
            </article>

            <article class="health-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Checked Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ number_format($checkedCount) }}
                </div>

                <div class="kpi-caption">
                    With a checkup on this page
                </div>
            </article>

            <article class="health-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Good Health</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ number_format($goodCount) }}
                </div>

                <div class="kpi-caption">
                    Good status on this page
                </div>
            </article>

            <article class="health-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Needs Attention</span>
                    <span class="kpi-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ number_format($attentionCount) }}
                </div>

                <div class="kpi-caption">
                    Attention or referred on this page
                </div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="health-paper-panel">

            <div class="health-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-notes-medical"></i>
                    </div>

                    <div>
                        <h3>Students Health Checkup</h3>
                        <p>View the latest checkup and manage health records.</p>
                    </div>
                </div>

                <div class="health-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($students->total()) }}</strong>
                    Students
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.student-health.index') }}"
                  method="GET"
                  class="health-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div>
                        <h4>Find Students</h4>
                        <p>
                            Filter by academic year, class and section,
                            or search by student ID or name.
                        </p>
                    </div>
                </div>

                <div class="health-filter-grid">

                    <div class="diary-filter-group">
                        <label for="academic_year">Academic Year</label>

                        <select name="academic_year"
                                id="academic_year"
                                class="diary-control">
                            <option value="">All Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="class">Class</label>

                        <select name="class"
                                id="class"
                                class="diary-control">
                            <option value="">All Classes</option>

                            @foreach($classes as $class)
                                <option value="{{ $class }}"
                                    @selected(request('class') == $class)>
                                    Class {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="section">Section</label>

                        <select name="section"
                                id="section"
                                class="diary-control">
                            <option value="">All Sections</option>

                            @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $section)
                                <option value="{{ $section }}"
                                    @selected(request('section') == $section)>
                                    Section {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group search-group">
                        <label for="search">Search Student</label>

                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>

                            <input id="search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Student ID or student name..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>

                        <a href="{{ route('admin.student-health.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="health-active-filters">
                    <div class="active-filter-items">

                        <span class="active-filter-label">
                            <i class="fas fa-filter"></i>
                            Active filters
                        </span>

                        @if(request('academic_year'))
                            <span class="filter-chip">
                                <i class="far fa-calendar-alt"></i>
                                Year: {{ request('academic_year') }}
                            </span>
                        @endif

                        @if(request('class'))
                            <span class="filter-chip">
                                <i class="fas fa-user-graduate"></i>
                                Class: {{ request('class') }}
                            </span>
                        @endif

                        @if(request('section'))
                            <span class="filter-chip">
                                <i class="fas fa-th"></i>
                                Section: {{ request('section') }}
                            </span>
                        @endif

                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ number_format($students->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- STUDENT TABLE --}}
            @if($students->count())

                <div class="health-table-wrapper">
                    <table class="health-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Latest Checkup</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th>BMI</th>
                                <th>Health Status</th>
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

                                    $latestRecord = $student->healthRecords
                                        ->sortByDesc('checkup_date')
                                        ->first();

                                    $statusClass = $classifyHealth(
                                        $latestRecord->overall_health_status ?? ''
                                    );
                                @endphp

                                <tr>
                                    <td>
                                        <span class="health-row-number">
                                            {{ ($students->firstItem() ?? 0) + $loop->index }}
                                        </span>
                                    </td>

                                    {{-- STUDENT --}}
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                @if(!empty($student->profile_image))
                                                    <img src="{{ $student->profile_image }}"
                                                         alt="{{ $studentName }}">
                                                @else
                                                    {{ $initial }}
                                                @endif
                                            </div>

                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $studentName ?: 'N/A' }}
                                                </div>

                                                <div class="student-id">
                                                    <i class="fas fa-id-badge"></i>
                                                    ID: {{ $student->student_id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- CLASS --}}
                                    <td>
                                        @if(!empty($student->class))
                                            <span class="health-chip class-chip">
                                                {{ $student->class }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- SECTION --}}
                                    <td>
                                        @if(!empty($student->section))
                                            <span class="health-chip section-chip">
                                                {{ $student->section }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ACADEMIC YEAR --}}
                                    <td>
                                        @if(!empty($student->academic_year))
                                            <div class="health-date">
                                                <div class="date-primary">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $student->academic_year }}
                                                </div>
                                                <div class="date-caption">Current Year</div>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- LATEST CHECKUP --}}
                                    <td>
                                        @if($latestRecord && $latestRecord->checkup_date)
                                            <div class="health-date">
                                                <div class="date-primary">
                                                    <i class="fas fa-stethoscope"></i>
                                                    {{ $latestRecord->checkup_date->format('d M Y') }}
                                                </div>
                                                <div class="date-caption">
                                                    {{ $latestRecord->academic_year }}
                                                </div>
                                            </div>
                                        @elseif($latestRecord)
                                            <div class="health-date">
                                                <div class="date-primary">—</div>
                                                <div class="date-caption">
                                                    {{ $latestRecord->academic_year }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="diary-muted">Not Checked</span>
                                        @endif
                                    </td>

                                    {{-- HEIGHT --}}
                                    <td>
                                        @if($latestRecord && $latestRecord->height !== null)
                                            <span class="health-measure">
                                                {{ $latestRecord->height }}
                                                <small>cm</small>
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- WEIGHT --}}
                                    <td>
                                        @if($latestRecord && $latestRecord->weight !== null)
                                            <span class="health-measure">
                                                {{ $latestRecord->weight }}
                                                <small>kg</small>
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- BMI --}}
                                    <td>
                                        @if($latestRecord && $latestRecord->bmi !== null)
                                            <span class="health-measure">
                                                {{ $latestRecord->bmi }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- HEALTH STATUS --}}
                                    <td>
                                        @if($latestRecord && $latestRecord->overall_health_status)

                                            @if($statusClass === 'good')
                                                <span class="health-status good">
                                                    <i class="fas fa-circle-check"></i>
                                                    {{ $latestRecord->overall_health_status }}
                                                </span>
                                            @elseif($statusClass === 'referred')
                                                <span class="health-status referred">
                                                    <i class="fas fa-circle-exclamation"></i>
                                                    {{ $latestRecord->overall_health_status }}
                                                </span>
                                            @else
                                                <span class="health-status attention">
                                                    <i class="fas fa-triangle-exclamation"></i>
                                                    {{ $latestRecord->overall_health_status }}
                                                </span>
                                            @endif

                                        @else
                                            <span class="health-status unknown">
                                                <i class="fas fa-circle-minus"></i>
                                                Not Recorded
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="health-actions">

                                            <a href="{{ route('admin.student-health.create', $student) }}"
                                               class="health-action-btn add"
                                               title="Add Health Record"
                                               aria-label="Add health record">
                                                <i class="fas fa-plus"></i>
                                            </a>

                                            @if($latestRecord)

                                                <a href="{{ route('admin.student-health.show', $latestRecord) }}"
                                                   class="health-action-btn view"
                                                   title="View Latest Record"
                                                   aria-label="View latest record">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.student-health.edit', $latestRecord) }}"
                                                   class="health-action-btn edit"
                                                   title="Edit Latest Record"
                                                   aria-label="Edit latest record">
                                                    <i class="fas fa-pen"></i>
                                                </a>

                                                <a href="{{ route('admin.student-health.print', $latestRecord) }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="health-action-btn print"
                                                   title="Print Latest Record"
                                                   aria-label="Print latest record">
                                                    <i class="fas fa-print"></i>
                                                </a>

                                            @endif

                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- IMPROVED PAGINATION --}}
                @if($students->hasPages())
                    <div class="health-pagination">

                        <div class="pagination-info">
                            <span class="pagination-info-label">
                                Showing students
                            </span>

                            <strong>
                                {{ number_format($students->firstItem() ?? 0) }}
                            </strong>

                            <span>to</span>

                            <strong>
                                {{ number_format($students->lastItem() ?? 0) }}
                            </strong>

                            <span>of</span>

                            <strong>
                                {{ number_format($students->total()) }}
                            </strong>
                        </div>

                        <div class="pagination-links">
                            {{ $students->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="health-card-footer">
                    <div>
                        Showing <strong>{{ number_format($students->count()) }}</strong>
                        student(s) on this page
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
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <h4>No Students Found</h4>

                    <p>
                        No students match your selected filters.
                        Try adjusting your filters or go to the students list.
                    </p>

                    <div class="empty-state-actions">
                        @if($hasFilters)
                            <a href="{{ route('admin.student-health.index') }}"
                               class="diary-primary-btn">
                                <i class="fas fa-rotate-left"></i>
                                Clear Filters
                            </a>
                        @endif

                        <a href="{{ route('admin.students.index') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-users"></i>
                            Go to Students
                        </a>
                    </div>
                </div>

            @endif

        </section>
    </div>
</div>

<style>
/* ==========================================================
   STUDENT HEALTH RECORDS — RESPONSIVE DIARY DESIGN
========================================================== */

.health-page {
    --ink: #263b35;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
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

.health-page *,
.health-page *::before,
.health-page *::after {
    box-sizing: border-box;
}

.health-page a,
.health-page button {
    transition: all .2s ease;
}

.health-page .container-fluid {
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
    font-size: 9px;
}

.health-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
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
    background: linear-gradient(145deg, #344f43, #20352c);
    text-align: center;
}

.health-hero-actions::before,
.health-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255,255,255,.09);
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
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255,255,255,.07);
    font-size: 16px;
}

.hero-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: cursive;
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
}

.health-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* SUCCESS */

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

.success-icon {
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

.health-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.health-kpi {
    --kpi-color: #426b8a;

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

.health-kpi.blue { --kpi-color: #426b8a; }
.health-kpi.green { --kpi-color: #43835d; }
.health-kpi.orange { --kpi-color: #a87543; }
.health-kpi.red { --kpi-color: #b95b50; }

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
    font-family: Georgia, "Times New Roman", serif;
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
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
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

.health-record-count strong {
    color: var(--ink);
}

/* FILTERS */

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
    font-family: Georgia, serif;
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
        minmax(140px, .8fr)
        minmax(140px, .8fr)
        minmax(140px, .8fr)
        minmax(220px, 1.8fr)
        auto;
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
}

.diary-control {
    padding: 0 12px;
    cursor: pointer;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67,131,93,.1);
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
    border-color: #426652;
    color: #fff;
    background: #426652;
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
    text-transform: uppercase;
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

.filter-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* TABLE */

.health-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.health-table {
    width: 100%;
    min-width: 1250px;
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
}

.health-table tbody tr:hover {
    background: #f8f8f1;
}

.health-table tbody tr:last-child td {
    border-bottom: 0;
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

/* STUDENT DETAILS */

.student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 200px;
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

.student-avatar img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
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

/* CHIPS AND MEASUREMENTS */

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

.health-measure small {
    color: #879083;
    font-size: 9px;
    font-weight: 700;
}

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* STATUS BADGES */

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
}

.health-action-btn:hover {
    transform: translateY(-2px);
}

.health-action-btn.add {
    border-color: #d4e5d0;
    color: #43835d;
    background: #f4f9f1;
}

.health-action-btn.add:hover {
    background: #edf6e9;
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

.health-action-btn.print:hover {
    border-color: #d3dfd2;
    color: #527f70;
    background: #edf3e9;
}

/* IMPROVED PAGINATION */

.health-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 18px 22px;
    border-top: 1px solid #e5e1d5;
    background: #fffefa;
}

.pagination-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    color: #7b857b;
    font-size: 11px;
    line-height: 1.7;
}

.pagination-info strong {
    color: #263b35;
    font-weight: 800;
}

.pagination-info-label {
    margin-right: 5px;
    color: #526358;
    font-weight: 700;
}

.pagination-links {
    min-width: 0;
    max-width: 100%;
}

.pagination-links nav {
    margin: 0;
}

.pagination-links nav > div:first-child {
    display: none;
}

.pagination-links nav > div:last-child {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}

.pagination-links .pagination {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
    padding: 0;
}

.pagination-links .page-item {
    margin: 0;
}

.pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 35px;
    min-height: 35px;
    padding: 7px 11px;
    border: 1px solid #e5e1d5;
    border-radius: 8px !important;
    color: #40574a;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.4;
    text-decoration: none;
    box-shadow: none;
}

.pagination-links .page-link:hover {
    z-index: 2;
    border-color: #b8c9b8;
    color: #263b35;
    background: #f1f4e9;
}

.pagination-links .page-item.active .page-link {
    z-index: 3;
    border-color: #263b35;
    color: #fff;
    background: #263b35;
}

.pagination-links .page-item.disabled .page-link {
    border-color: #ece9e0;
    color: #b2b6ac;
    background: #f7f6f0;
    cursor: not-allowed;
}

.pagination-links .page-link:focus {
    position: relative;
    z-index: 4;
    outline: 2px solid #739780;
    outline-offset: 2px;
    box-shadow: none;
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
    font-size: 27px;
}

.health-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: Georgia, serif;
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

    .health-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .health-filter-grid .search-group,
    .diary-filter-actions {
        grid-column: span 2;
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
    .health-page .container-fluid {
        padding: 18px 14px !important;
    }

    .health-hero {
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
        margin-top: 34px;
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

    .health-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .health-filter-grid .search-group,
    .diary-filter-actions {
        grid-column: auto;
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
        gap: 13px;
        padding: 16px;
    }

    .pagination-info {
        font-size: 10px;
    }

    .pagination-links {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        padding: 3px 2px 6px;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .pagination-links nav > div:last-child {
        justify-content: flex-start;
    }

    .pagination-links .pagination {
        flex-wrap: nowrap;
        width: max-content;
    }

    .pagination-links .page-link {
        min-width: 34px;
        min-height: 34px;
        padding: 6px 9px;
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

.health-page a:focus-visible,
.health-page button:focus-visible,
.health-page input:focus-visible,
.health-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .health-page *,
    .health-page *::before,
    .health-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection
