@extends('layouts.app')

@section('title', 'Student General Register | Admin')
@section('page-title', 'Student General Register')

@section('content')

<div class="register-page">
    <div class="container-fluid py-4">

        {{-- HERO --}}
        <section class="register-hero">
            <div class="register-hero-content">
                <div class="register-hero-icon">
                    <i class="fas fa-book"></i>
                </div>

                <div class="register-hero-text">
                    <div class="register-breadcrumb">
                        <span>Student Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>General Register</span>
                    </div>

                    <h2>Student General Register</h2>

                    <p>
                        Complete general register of all students with
                        admission, birth, caste, class and previous school
                        details in one master record.
                    </p>

                    <div class="register-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Official School Records &amp; Student Master Register</span>
                    </div>
                </div>
            </div>

            <div class="register-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-print"></i>
                </div>

                <div class="hero-handwritten">Every Record. In Order.</div>

                <p>
                    Every entry matters. Filter the register and print
                    it exactly as you see it.
                </p>

                
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="register-kpis">

            <article class="register-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($students->total()) }}</div>
                <div class="kpi-caption">Students in the register</div>
            </article>

            <article class="register-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Academic Years</span>
                    <span class="kpi-icon">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format(count($academicYears ?? [])) }}</div>
                <div class="kpi-caption">Years available to filter</div>
            </article>

            <article class="register-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format(count($classes ?? [])) }}</div>
                <div class="kpi-caption">Classes available to filter</div>
            </article>

            <article class="register-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Showing Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-list-ul"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($students->count()) }}</div>
                <div class="kpi-caption">Records on this page</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="register-paper-panel">

            <div class="register-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3>General Register</h3>
                        <p>Student master register.</p>
                    </div>
                </div>

                <div class="register-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($students->total()) }}</strong>
                    Students
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.student-general-register.index') }}"
                  method="GET"
                  id="generalRegisterFilterForm"
                  class="register-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Filter General Register</h4>
                        <p>Filter by academic year, class, section, or search by name, ID, register number or Aadhaar.</p>
                    </div>
                </div>

                <div class="register-filter-grid">

                    <div class="diary-filter-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year"
                                id="academic_year"
                                class="diary-control">
                            <option value="">All Years</option>

                            @foreach(($academicYears ?? []) as $year)
                                <option value="{{ $year }}"
                                    @selected((string) request('academic_year') === (string) $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="class">Current Class</label>
                        <select name="class"
                                id="class"
                                class="diary-control">
                            <option value="">All Classes</option>

                            @foreach(($classes ?? []) as $class)
                                <option value="{{ $class }}"
                                    @selected((string) request('class') === (string) $class)>
                                    {{ $class }}
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

                            @foreach(($sections ?? []) as $section)
                                <option value="{{ $section }}"
                                    @selected((string) request('section') === (string) $section)>
                                    {{ $section }}
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
                                   placeholder="Name, Student ID, Register No. or Aadhaar..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-search"></i> Search
                        </button>

                        <a href="{{ route('admin.student-general-register.index') }}"
                           class="diary-reset-btn"
                           title="Clear Filters">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request('academic_year') || request('class') || request('section') || request('search'))
                <div class="register-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-filter"></i> Active filters
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

            {{-- REGISTER TABLE --}}
            @if($students->count() > 0)

                <div class="register-table-wrapper">
                    <table class="register-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr.<br>No.</th>
                                <th rowspan="2">Photo</th>
                                <th rowspan="2">Register<br>Number</th>
                                <th rowspan="2">Book<br>Number</th>
                                <th rowspan="2">Student's Name</th>
                                <th rowspan="2">Student ID</th>
                                <th rowspan="2">Aadhar Number</th>
                                <th rowspan="2">Mother's Name</th>
                                <th rowspan="2">Nationality</th>
                                <th rowspan="2">Mother Tongue</th>
                                <th rowspan="2">Religion</th>
                                <th rowspan="2">Caste</th>
                                <th rowspan="2">Sub Caste</th>
                                <th rowspan="2">Admission Date</th>
                                <th rowspan="2">Gender</th>
                                <th rowspan="2">Birth Place</th>
                                <th colspan="2" class="group-heading">Birth Information</th>
                                <th rowspan="2">Previous School</th>
                                <th rowspan="2">Admission Class</th>
                                <th rowspan="2">Current Class</th>
                                <th rowspan="2">Section</th>
                                <th rowspan="2">State</th>
                                <th rowspan="2">Country</th>
                                <th rowspan="2" class="actions-heading">Action</th>
                            </tr>

                            <tr>
                                <th class="sub-heading">Date of Birth</th>
                                <th class="sub-heading">In Words</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $student)

                                @php
                                    $serialNumber = ($students->firstItem() ?? 0) + $loop->index;

                                    $studentName = $student->full_name
                                        ?: trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        );
                                @endphp

                                <tr>

                                    <td>
                                        <span class="register-row-number">{{ $serialNumber }}</span>
                                    </td>

                                    <td>
                                        @if($student->profile_image)
                                            <img
                                                src="{{ \Illuminate\Support\Str::startsWith($student->profile_image, ['http://', 'https://'])
                                                    ? $student->profile_image
                                                    : asset('storage/' . ltrim($student->profile_image, '/')) }}"
                                                alt="{{ $studentName ?: 'Student' }}"
                                                class="register-student-photo"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >

                                            <div class="register-student-photo-placeholder" style="display:none;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @else
                                            <div class="register-student-photo-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>{{ $student->register_no ?: '—' }}</td>

                                    <td>{{ $student->book_no ?: '—' }}</td>

                                    <td class="student-name-cell">
                                        <div class="student-name">
                                            {{ $studentName ?: '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($student->student_id)
                                            <span class="register-id-chip">
                                                <i class="fas fa-id-badge"></i>
                                                {{ $student->student_id }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td class="nowrap">{{ $student->aadhar_card_no ?: '—' }}</td>

                                    <td>{{ $student->mother_name ?: '—' }}</td>

                                    <td>{{ $student->nationality ?: '—' }}</td>

                                    <td>{{ $student->mother_tongue ?: '—' }}</td>

                                    <td>{{ $student->religion ?: '—' }}</td>

                                    <td>{{ $student->caste ?: '—' }}</td>

                                    <td>{{ $student->sub_caste ?: '—' }}</td>

                                    <td class="nowrap">
                                        @if($student->admission_date)
                                            <span class="register-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Illuminate\Support\Carbon::parse($student->admission_date)->format('d-m-Y') }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $student->gender ?: '—' }}</td>

                                    <td>{{ $student->birth_place ?: '—' }}</td>

                                    <td class="nowrap">
                                        @if($student->date_of_birth)
                                            <span class="register-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('d-m-Y') }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td class="dob-words">
                                        @if($student->date_of_birth)
                                            {{ \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('d F Y') }}
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $student->previous_school_name ?: '—' }}</td>

                                    <td>
                                        @if($student->admission_class)
                                            <span class="register-chip">{{ $student->admission_class }}</span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($student->class)
                                            <span class="register-chip">{{ $student->class }}</span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($student->section)
                                            <span class="register-chip">{{ $student->section }}</span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $student->state ?: '—' }}</td>

                                    <td>{{ $student->country ?: '—' }}</td>

                                    <td>
                                        <div class="register-actions">
                                            <a href="{{ route('admin.student-general-register.show', $student->id) }}"
                                               class="register-action-btn view"
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
                    <div class="register-pagination">
                        <div class="pagination-info">
                            Showing <strong>{{ $students->firstItem() ?? 0 }}</strong>
                            to <strong>{{ $students->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $students->total() }}</strong> students
                        </div>

                        <div class="pagination-links">
                            {{ $students->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="register-card-footer">
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
                <div class="register-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-book"></i>
                    </div>

                    @if(request('academic_year') || request('class') || request('section') || request('search'))
                        <h4>No Students Found</h4>
                        <p>
                            No students match the selected filters.
                            Try adjusting your search or filters.
                        </p>
                        <a href="{{ route('admin.student-general-register.index') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h4>No Students In The Register</h4>
                        <p>
                            There are currently no students in the general
                            register. Student records will appear here once
                            they are admitted.
                        </p>
                    @endif
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* ==========================================================
   STUDENT GENERAL REGISTER — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.register-page {
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

.register-page *,
.register-page *::before,
.register-page *::after {
    box-sizing: border-box;
}

.register-page a,
.register-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.register-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.register-hero,
.register-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.register-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.register-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.register-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.register-hero-icon {
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

.register-hero-text {
    min-width: 0;
}

.register-breadcrumb {
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

.register-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.register-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.register-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.register-hero-note {
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

.register-hero-note i {
    font-size: 13px;
}

.register-hero-actions {
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

.register-hero-actions::before,
.register-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.register-hero-actions::before {
    top: -115px;
    right: -60px;
}

.register-hero-actions::after {
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

.register-hero-actions p {
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

.register-hero-actions .diary-primary-btn {
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

.register-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* KPI CARDS */

.register-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.register-kpi {
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

.register-kpi.blue { --kpi-color: #426b8a; }
.register-kpi.green { --kpi-color: #43835d; }
.register-kpi.orange { --kpi-color: #a87543; }
.register-kpi.red { --kpi-color: #b95b50; }

.register-kpi::before {
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

.register-kpi.blue::before { content: "STUDENTS"; }
.register-kpi.green::before { content: "YEARS"; }
.register-kpi.orange::before { content: "CLASSES"; }
.register-kpi.red::before { content: "THIS PAGE"; }

.register-kpi:hover {
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

.register-kpi .kpi-icon {
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

.register-paper-panel {
    overflow: hidden;
}

.register-panel-header {
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

.register-record-count {
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

.register-record-count i,
.register-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.register-filter-panel {
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

.register-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(140px, .8fr) minmax(140px, .8fr) minmax(140px, .8fr)
        minmax(220px, 1.8fr) auto;
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

.register-active-filters {
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

/* REGISTER TABLE */

.register-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.register-table {
    width: 100%;
    min-width: 2800px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.register-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    vertical-align: middle;
    white-space: nowrap;
}

.register-table thead th.group-heading {
    border-bottom: 1px dashed #d4d2c3;
    text-align: center;
}

.register-table thead th.sub-heading {
    padding: 11px 14px;
    color: #758176;
    background: #f5f5ed;
}

.register-table thead th.actions-heading {
    text-align: right;
}

.register-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
    white-space: nowrap;
}

.register-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.register-table tbody tr:hover {
    background: #f8f8f1;
}

.register-table tbody tr:last-child td {
    border-bottom: none;
}

.register-table .nowrap {
    white-space: nowrap;
}

.register-row-number {
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

/* PHOTO */

.register-student-photo {
    display: block;
    width: 44px;
    height: 44px;
    object-fit: cover;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    background: #edf1e7;
}

.register-student-photo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 15px;
}

/* NAME */

.student-name-cell {
    min-width: 190px;
}

.student-name {
    max-width: 230px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.register-id-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #828c7f;
    font-size: 10px;
}

.register-id-chip i {
    color: var(--amber);
}

/* DATES AND CHIPS */

.register-date {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.register-date i {
    color: var(--blue);
}

.dob-words {
    min-width: 150px;
    color: #637167;
}

.register-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 6px 10px;
    border: 1px solid #dce4d8;
    border-radius: 7px;
    color: #477858;
    background: #edf6e9;
    font-size: 10px;
    font-weight: 800;
}

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* ACTION BUTTONS */

.register-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.register-action-btn {
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

.register-action-btn:hover {
    transform: translateY(-2px);
}

.register-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

/* PAGINATION */

.register-pagination {
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

.register-card-footer {
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

.register-card-footer strong {
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

.register-empty-state {
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

.register-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.register-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .register-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .register-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .register-filter-grid .search-group,
    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .register-hero {
        grid-template-columns: 1fr;
    }

    .register-hero-actions {
        min-height: 220px;
    }

    .register-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .register-page .container-fluid {
        padding: 18px 14px !important;
    }

    .register-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .register-hero::before {
        left: 18px;
    }

    .register-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .register-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .register-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .register-hero-text h2 {
        font-size: 24px;
    }

    .register-hero-text p {
        font-size: 11px;
    }

    .register-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .register-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .register-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .register-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .register-kpi .kpi-icon {
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

    .register-panel-header {
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

    .register-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .register-filter-panel {
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

    .register-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .register-filter-grid .search-group,
    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .register-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .register-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .register-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .register-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .register-kpis {
        grid-template-columns: 1fr;
    }

    .register-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .register-record-count {
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

.register-page a:focus-visible,
.register-page button:focus-visible,
.register-page input:focus-visible,
.register-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .register-page *,
    .register-page *::before,
    .register-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

{{-- PRINT BUTTON FILTER HANDLING --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const printButton = document.getElementById('printRegisterBtn');

    if (!printButton) {
        return;
    }

    function updatePrintUrl() {

        const params = new URLSearchParams();

        const academicYear =
            document.getElementById('academic_year')?.value || '';

        const selectedClass =
            document.getElementById('class')?.value || '';

        const section =
            document.getElementById('section')?.value || '';

        const search =
            document.getElementById('search')?.value.trim() || '';

        if (academicYear) {
            params.set('academic_year', academicYear);
        }

        if (selectedClass) {
            params.set('class', selectedClass);
        }

        if (section) {
            params.set('section', section);
        }

        if (search) {
            params.set('search', search);
        }

        const baseUrl =
            "{{ route('admin.student-general-register.print') }}";

        const queryString = params.toString();

        printButton.href = queryString
            ? baseUrl + '?' + queryString
            : baseUrl;
    }

    document.getElementById('academic_year')
        ?.addEventListener('change', updatePrintUrl);

    document.getElementById('class')
        ?.addEventListener('change', updatePrintUrl);

    document.getElementById('section')
        ?.addEventListener('change', updatePrintUrl);

    document.getElementById('search')
        ?.addEventListener('input', updatePrintUrl);

    updatePrintUrl();
});
</script>

@endsection