@extends('layouts.app')

@section('title', 'Student Reports | Admin')
@section('page-title', 'Student Reports')

@section('content')

<div class="transport-records-page student-reports-page">
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
                    <i class="fas fa-user-graduate"></i>
                </div>

                <div class="transport-hero-text">
                    <div class="transport-breadcrumb">
                        <span>Reports</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Student Reports</span>
                    </div>

                    <h2>Student Reports</h2>

                    <p>
                        Generate and view detailed student information,
                        academic records, attendance and library history
                        from one place.
                    </p>

                    <div class="transport-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Records &amp; Student Information Management</span>
                    </div>
                </div>
            </div>

            <div class="transport-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-file-alt"></i>
                </div>

                <div class="hero-handwritten">Clear Records. Better Insights.</div>

                <p>
                    Filter by class, section, academic year or status
                    and export complete student reports instantly.
                </p>

                <a href="{{ route('admin.reports.students') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Reports
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="transport-kpis">

            <article class="transport-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($students->total()) }}</div>
                <div class="kpi-caption">Students matching current filters</div>
            </article>

            <article class="transport-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">
                    {{ number_format($students->where('status', 'active')->count() ?: $students->filter(fn($s) => strtolower($s->status ?? '') === 'active')->count()) }}
                </div>
                <div class="kpi-caption">Currently active students</div>
            </article>

            <article class="transport-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Academic Years</span>
                    <span class="kpi-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format(count($academicYears ?? [])) }}</div>
                <div class="kpi-caption">Available academic years</div>
            </article>

            <article class="transport-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-chalkboard"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format(count($classes ?? [])) }}</div>
                <div class="kpi-caption">Classes available in system</div>
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
                        <h3>Student Report</h3>
                        <p>View and filter student information and academic records.</p>
                    </div>
                </div>

                <div class="transport-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($students->total()) }}</strong>
                    Records
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.reports.students') }}"
                  method="GET"
                  class="transport-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Find Student Records</h4>
                        <p>Search by name, ID, class, section, academic year or status.</p>
                    </div>
                </div>

                <div class="transport-filter-grid student-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="student-search">Search Students</label>
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input id="student-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Name, ID, phone or email..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="from-date">From Date</label>
                        <input id="from-date"
                               type="date"
                               name="from_date"
                               class="diary-control"
                               value="{{ request('from_date') }}">
                    </div>

                    <div class="diary-filter-group">
                        <label for="to-date">To Date</label>
                        <input id="to-date"
                               type="date"
                               name="to_date"
                               class="diary-control"
                               value="{{ request('to_date') }}">
                    </div>

                    <div class="diary-filter-group">
                        <label for="academic-year">Academic Year</label>
                        <select id="academic-year"
                                name="academic_year"
                                class="diary-control">
                            <option value="">All Years</option>
                            @foreach($academicYears ?? [] as $year)
                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="class">Class</label>
                        <select id="class"
                                name="class"
                                class="diary-control">
                            <option value="">All Classes</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class }}"
                                    @selected(request('class') == $class)>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="section">Section</label>
                        <select id="section"
                                name="section"
                                class="diary-control">
                            <option value="">All Sections</option>
                            @foreach($sections ?? [] as $section)
                                <option value="{{ $section }}"
                                    @selected(request('section') == $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="gender">Gender</label>
                        <select id="gender"
                                name="gender"
                                class="diary-control">
                            <option value="">All Genders</option>
                            <option value="Male" @selected(request('gender') === 'Male')>Male</option>
                            <option value="Female" @selected(request('gender') === 'Female')>Female</option>
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="status">Status</label>
                        <select id="status"
                                name="status"
                                class="diary-control">
                            <option value="">All Status</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-search"></i> Search
                        </button>

                        <a href="{{ route('admin.reports.students') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny(['search', 'from_date', 'to_date', 'academic_year', 'class', 'section', 'gender', 'status']))
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

                        @if(request('from_date'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar"></i>
                                From: {{ request('from_date') }}
                            </span>
                        @endif

                        @if(request('to_date'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar"></i>
                                To: {{ request('to_date') }}
                            </span>
                        @endif

                        @if(request('academic_year'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar-alt"></i>
                                {{ request('academic_year') }}
                            </span>
                        @endif

                        @if(request('class'))
                            <span class="filter-chip">
                                <i class="fas fa-chalkboard"></i>
                                Class {{ request('class') }}
                            </span>
                        @endif

                        @if(request('section'))
                            <span class="filter-chip">
                                <i class="fas fa-layer-group"></i>
                                Sec {{ request('section') }}
                            </span>
                        @endif

                        @if(request('gender'))
                            <span class="filter-chip">
                                <i class="fas fa-venus-mars"></i>
                                {{ request('gender') }}
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
                        {{ number_format($students->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- ACTION BAR --}}
            <div class="report-export-bar">
                <div class="export-label">
                    <i class="fas fa-download"></i>
                    Export Options
                </div>
                <div class="report-actions">
                    <a href="{{ route('admin.reports.students.excel', request()->query()) }}"
                       class="diary-export-btn excel">
                        <i class="fas fa-file-excel"></i>
                        Excel
                    </a>
                    <a href="{{ route('admin.reports.students.pdf', request()->query()) }}"
                       class="diary-export-btn pdf">
                        <i class="fas fa-file-pdf"></i>
                        PDF
                    </a>
                    <button type="button"
                            onclick="window.print()"
                            class="diary-export-btn print">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>

            {{-- RECORDS TABLE --}}
            @if($students->count() > 0)

                <div class="transport-table-wrapper">
                    <table class="transport-table student-report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No.</th>
                                <th>Admission Date</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="actions-heading print-hide">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <span class="transport-row-number">
                                            {{ ($students->firstItem() ?? 0) + $loop->index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $student->full_name ?? '—' }}
                                                </div>
                                                <div class="student-id">
                                                    <i class="fas fa-id-badge"></i>
                                                    ID: {{ $student->student_id ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <strong>{{ $student->student_id ?? '—' }}</strong>
                                    </td>

                                    <td>{{ $student->academic_year ?? '—' }}</td>
                                    <td>{{ $student->class ?? '—' }}</td>
                                    <td>{{ $student->section ?? '—' }}</td>
                                    <td>{{ $student->roll_number ?? '—' }}</td>

                                    <td>
                                        @if($student->admission_date)
                                            <div class="transport-date">
                                                <div class="date-primary">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $student->admission_date->format('d M Y') }}
                                                </div>
                                                <div class="date-caption">Admission</div>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($student->gender)
                                            <span class="gender-badge">{{ $student->gender }}</span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $student->phone ?? '—' }}</td>

                                    <td>
                                        @if(strtolower($student->status ?? '') === 'active')
                                            <span class="transport-status active">
                                                <span class="status-dot"></span> Active
                                            </span>
                                        @else
                                            <span class="transport-status inactive">
                                                <span class="status-dot"></span>
                                                {{ ucfirst($student->status ?? 'Inactive') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="print-hide">
                                        <div class="transport-actions">
                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                               class="transport-action-btn view"
                                               title="View Student"
                                               aria-label="View student">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($students->hasPages())
                    <div class="transport-pagination print-hide">
                        <div class="pagination-info">
                            Showing <strong>{{ $students->firstItem() }}</strong>
                            to <strong>{{ $students->lastItem() }}</strong>
                            of <strong>{{ $students->total() }}</strong> records
                        </div>

                        <div class="pagination-links">
                            {{ $students->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="transport-card-footer">
                    <div>
                        Showing <strong>{{ $students->count() }}</strong>
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
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    @if(request()->hasAny(['search', 'from_date', 'to_date', 'academic_year', 'class', 'section', 'gender', 'status']))
                        <h4>No Matching Records Found</h4>
                        <p>
                            No student records match your current search
                            or selected filters. Try adjusting your criteria.
                        </p>
                        <a href="{{ route('admin.reports.students') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h4>No Student Records Yet</h4>
                        <p>
                            There are currently no student records available.
                            Add students to start generating reports.
                        </p>
                    @endif
                </div>

            @endif
        </section>

        {{-- ================= DETAILED STUDENT SECTIONS ================= --}}
        @foreach($students as $student)

            <div class="student-detail-report">

                {{-- ATTENDANCE --}}
                <section class="transport-paper-panel detail-section">
                    <div class="transport-panel-header">
                        <div class="panel-title-wrap">
                            <div class="panel-title-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h3>Attendance — {{ $student->full_name }}</h3>
                                <p>Attendance history for this student.</p>
                            </div>
                        </div>
                    </div>

                    @if($student->attendanceRecords->count())
                        <div class="transport-table-wrapper">
                            <table class="transport-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Attendance Date</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->attendanceRecords as $index => $attendance)
                                        <tr>
                                            <td>
                                                <span class="transport-row-number">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                @if($attendance->attendance_date)
                                                    <div class="transport-date">
                                                        <div class="date-primary">
                                                            <i class="far fa-calendar-alt"></i>
                                                            {{ $attendance->attendance_date->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="diary-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="transport-status {{ strtolower($attendance->status ?? '') === 'present' ? 'active' : 'inactive' }}">
                                                    <span class="status-dot"></span>
                                                    {{ $attendance->status ?? '—' }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->remarks ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="transport-empty-state compact">
                            <p>No attendance records available for this student.</p>
                        </div>
                    @endif
                </section>

                {{-- LIBRARY --}}
                <section class="transport-paper-panel detail-section">
                    <div class="transport-panel-header">
                        <div class="panel-title-wrap">
                            <div class="panel-title-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h3>Library — {{ $student->full_name }}</h3>
                                <p>Library issue and return history.</p>
                            </div>
                        </div>
                    </div>

                    @if($student->libraryRecords->count())
                        <div class="transport-table-wrapper">
                            <table class="transport-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Fine</th>
                                        <th>Fine Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->libraryRecords as $index => $library)
                                        <tr>
                                            <td>
                                                <span class="transport-row-number">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                {{ $library->issue_date ? $library->issue_date->format('d M Y') : '—' }}
                                            </td>
                                            <td>
                                                {{ $library->due_date ? $library->due_date->format('d M Y') : '—' }}
                                            </td>
                                            <td>
                                                {{ $library->return_date ? $library->return_date->format('d M Y') : '—' }}
                                            </td>
                                            <td>
                                                <span class="transport-status {{ strtolower($library->status ?? '') === 'returned' ? 'active' : 'inactive' }}">
                                                    <span class="status-dot"></span>
                                                    {{ $library->status ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $library->fine !== null ? '₹' . number_format($library->fine, 2) : '—' }}
                                            </td>
                                            <td>{{ $library->fine_status ?? '—' }}</td>
                                            <td>{{ $library->remarks ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="transport-empty-state compact">
                            <p>No library records available for this student.</p>
                        </div>
                    @endif
                </section>

            </div>

        @endforeach

    </div>
</div>

<style>
/* ==========================================================
   STUDENT REPORTS — SCHOOL DIARY DESIGN SYSTEM
   Exact match to Transport Records page
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

.transport-kpi.blue::before { content: "STUDENTS"; }
.transport-kpi.green::before { content: "ACTIVE"; }
.transport-kpi.orange::before { content: "YEARS"; }
.transport-kpi.red::before { content: "CLASSES"; }

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
    margin-bottom: 28px;
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

/* Multi-filter layout for Student Reports */
.student-filter-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.student-filter-grid .search-group {
    grid-column: span 2;
}

.student-filter-grid .diary-filter-actions {
    grid-column: span 2;
    justify-content: flex-start;
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

/* EXPORT BAR */

.report-export-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid #eeece3;
    background: #faf9f3;
}

.export-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6d786d;
    font-size: 11px;
    font-weight: 700;
}

.export-label i {
    color: var(--sage);
}

.report-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.diary-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 36px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: 0.2s;
}

.diary-export-btn.excel {
    background: #edf6e9;
    color: #347849;
    border-color: #d2e5d1;
}

.diary-export-btn.excel:hover {
    background: #deeedb;
    color: #2a6339;
}

.diary-export-btn.pdf {
    background: #fff1ed;
    color: #b95b50;
    border-color: #eed6cf;
}

.diary-export-btn.pdf:hover {
    background: #ffe4dc;
    color: #a04a40;
}

.diary-export-btn.print {
    background: #f5f3e9;
    color: #526358;
    border-color: #e2ddcf;
}

.diary-export-btn.print:hover {
    background: #ebe8db;
    color: var(--ink);
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

.gender-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 7px;
    background: #f1f2e8;
    color: #526358;
    font-size: 10px;
    font-weight: 700;
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

/* ACTION BUTTONS */

.transport-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
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

.transport-empty-state.compact {
    padding: 30px 20px;
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

/* DETAIL SECTIONS */

.detail-section {
    margin-top: 24px;
}

/* TABLET */

@media (max-width: 1200px) {
    .transport-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-filter-grid .search-group,
    .student-filter-grid .diary-filter-actions {
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

    .student-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .student-filter-grid .search-group,
    .student-filter-grid .diary-filter-actions {
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

    .report-export-bar {
        flex-direction: column;
        align-items: flex-start;
        padding: 14px 15px;
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

/* PRINT STYLES */
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    html, body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        overflow: visible !important;
    }

    .main-sidebar,
    .main-header,
    .navbar,
    .sidebar,
    .transport-filter-panel,
    .report-export-bar,
    .print-hide,
    .transport-hero-actions,
    .transport-kpis,
    button,
    .btn {
        display: none !important;
    }

    .content-wrapper,
    .main-content,
    .container,
    .container-fluid,
    .transport-records-page {
        width: 100% !important;
        max-width: 100% !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        background: #ffffff !important;
    }

    .transport-hero {
        background: #ffffff !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin-bottom: 12px !important;
    }

    .transport-hero::before {
        display: none !important;
    }

    .transport-paper-panel {
        border: 1px solid #d1d5db !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }

    .transport-table {
        min-width: 0 !important;
        table-layout: fixed !important;
    }

    .transport-table thead th,
    .transport-table tbody td {
        font-size: 8px !important;
        padding: 6px 5px !important;
        white-space: normal !important;
        word-break: break-word !important;
        border: 1px solid #d1d5db !important;
    }

    .student-avatar {
        width: 24px !important;
        height: 24px !important;
        font-size: 10px !important;
    }

    tr {
        page-break-inside: avoid !important;
    }
}
</style>

@endsection