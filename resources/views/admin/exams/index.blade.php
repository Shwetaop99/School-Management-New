```@extends('layouts.app')

@section('title', 'Exam Management | Admin')
@section('page-title', 'Exam Management')

@section('content')

@php
    $totalExams = $exams->count();
    $draftExams = $exams->where('status', 'draft')->count();
    $scheduledExams = $exams->where('status', 'scheduled')->count();
    $completedExams = $exams->where('status', 'completed')->count();
    $cancelledExams = $exams->where('status', 'cancelled')->count();
@endphp

<div class="exam-page">
    <div class="container-fluid py-4">

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="exam-alert exam-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="exam-alert exam-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- HERO --}}
        <section class="exam-hero">
            <div class="exam-hero-content">

                <div class="exam-hero-icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>

                <div class="exam-hero-text">
                    <span class="exam-eyebrow">
                        <i class="bi bi-book-half"></i>
                        SCHOOL ACADEMIC DIRECTORY
                    </span>

                    <h2>Examination Management</h2>

                    <p>
                        Manage examinations, academic years, exam types,
                        subjects and examination schedules from one place.
                    </p>

                    <div class="exam-hero-note">
                        <i class="bi bi-shield-check"></i>
                        Academic Assessment &amp; Examination Records
                    </div>
                </div>
            </div>

            <div class="exam-hero-actions">
                <div class="exam-decoration decoration-one"></div>
                <div class="exam-decoration decoration-two"></div>

                <div class="exam-handwritten">
                    Prepare. Perform. Progress.
                </div>

                <p>
                    Organise your academic calendar and keep
                    every examination on track.
                </p>

                <a href="{{ route('admin.exams.create') }}"
                   class="exam-primary-btn">
                    <i class="bi bi-plus-lg"></i>
                    Create New Exam
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>

        {{-- STATISTICS --}}
        <section class="exam-kpis">

            <article class="exam-kpi blue">
                <div class="kpi-top">
                    <span>Total Examinations</span>
                    <div class="kpi-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $totalExams }}</div>
                <div class="kpi-caption">
                    <i class="bi bi-folder2-open"></i>
                    All registered examinations
                </div>
            </article>

            <article class="exam-kpi orange">
                <div class="kpi-top">
                    <span>Draft Examinations</span>
                    <div class="kpi-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $draftExams }}</div>
                <div class="kpi-caption">
                    <i class="bi bi-pencil-square"></i>
                    Examinations in preparation
                </div>
            </article>

            <article class="exam-kpi green">
                <div class="kpi-top">
                    <span>Scheduled Exams</span>
                    <div class="kpi-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $scheduledExams }}</div>
                <div class="kpi-caption">
                    <i class="bi bi-clock-history"></i>
                    Upcoming examinations
                </div>
            </article>

            <article class="exam-kpi teal">
                <div class="kpi-top">
                    <span>Completed Exams</span>
                    <div class="kpi-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="kpi-value">{{ $completedExams }}</div>
                <div class="kpi-caption">
                    <i class="bi bi-patch-check"></i>
                    Successfully completed
                </div>
            </article>

        </section>

        {{-- EXAMINATION RECORDS --}}
        <section class="exam-paper-panel">

            <div class="exam-panel-header">

                <div class="exam-panel-title-wrap">
                    <div class="exam-panel-title-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div>
                        <span class="exam-panel-eyebrow">
                            ACADEMIC RECORDS
                        </span>

                        <h3>Examination List</h3>

                        <p>
                            View and manage all registered examinations.
                        </p>
                    </div>
                </div>

                <div class="exam-record-count">
                    <i class="bi bi-folder2-open"></i>
                    <strong>{{ $totalExams }}</strong>
                    {{ $totalExams === 1 ? 'Exam' : 'Exams' }}
                </div>

            </div>

            {{-- EXAM TABLE --}}
            @if($exams->count())

                <div class="exam-table-wrapper">
                    <table class="exam-table">

                        <thead>
                            <tr>
                                <th width="65">#</th>
                                <th>Examination</th>
                                <th>Academic Year</th>
                                <th>Exam Type</th>
                                <th>Exam Period</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($exams as $exam)

                                @php
                                    $status = $exam->status ?? 'draft';

                                    $statusClass = match($status) {
                                        'draft' => 'status-draft',
                                        'scheduled' => 'status-scheduled',
                                        'completed' => 'status-completed',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-draft',
                                    };

                                    $statusIcon = match($status) {
                                        'draft' => 'bi-file-earmark',
                                        'scheduled' => 'bi-calendar-check',
                                        'completed' => 'bi-check-circle',
                                        'cancelled' => 'bi-x-circle',
                                        default => 'bi-circle',
                                    };
                                @endphp

                                <tr>
                                    <td>
                                        <span class="exam-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="exam-info">
                                            <div class="exam-avatar">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>

                                            <div class="exam-details">
                                                <div class="exam-name">
                                                    {{ $exam->exam_name }}
                                                </div>

                                                <div class="exam-subtitle">
                                                    <i class="bi bi-hash"></i>
                                                    ID: {{ $exam->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($exam->academic_year)
                                            <span class="exam-year-badge">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $exam->academic_year }}
                                            </span>
                                        @else
                                            <span class="exam-muted">Not assigned</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($exam->exam_type)
                                            <span class="exam-type-badge">
                                                <i class="bi bi-bookmark-star"></i>
                                                {{ $exam->exam_type }}
                                            </span>
                                        @else
                                            <span class="exam-muted">Not assigned</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="exam-period">
                                            <div class="exam-period-start">
                                                <i class="bi bi-calendar-event"></i>
                                                {{ $exam->start_date?->format('d M Y') ?? 'Not set' }}
                                            </div>

                                            <div class="exam-period-end">
                                                <i class="bi bi-arrow-right"></i>
                                                {{ $exam->end_date?->format('d M Y') ?? 'Not set' }}
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="exam-status-badge {{ $statusClass }}">
                                            <span class="status-dot"></span>
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="exam-actions">

                                            {{-- VIEW TIMETABLE --}}
                                            <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                                               class="exam-action-btn view"
                                               title="View Exam Timetable"
                                               aria-label="View Exam Timetable">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('admin.exams.edit', $exam) }}"
                                               class="exam-action-btn edit"
                                               title="Edit Examination"
                                               aria-label="Edit Examination">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            {{-- DELETE --}}
                                            <form method="POST"
                                                  action="{{ route('admin.exams.destroy', $exam) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this examination? Related examination setup and timetable data may also be removed.');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="exam-action-btn delete"
                                                        title="Delete Examination"
                                                        aria-label="Delete Examination">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- FOOTER --}}
                <div class="exam-card-footer">
                    <span>
                        <i class="bi bi-file-earmark-text"></i>
                        Showing <strong>{{ $totalExams }}</strong>
                        {{ $totalExams === 1 ? 'examination' : 'examinations' }}
                    </span>

                    <span class="exam-footer-status">
                        <i class="bi bi-check-circle-fill"></i>
                        Examination records loaded
                    </span>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="exam-empty-state">

                    <div class="exam-empty-icon">
                        <i class="bi bi-journal-x"></i>
                    </div>

                    <span class="exam-empty-eyebrow">
                        ACADEMIC DIRECTORY
                    </span>

                    <h4>No Examinations Found</h4>

                    <p>
                        You haven't registered any examinations yet.
                        Start by creating your first examination and
                        organising its academic schedule.
                    </p>

                    <a href="{{ route('admin.exams.create') }}"
                       class="exam-primary-btn">
                        <i class="bi bi-plus-lg"></i>
                        Create First Exam
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            @endif

        </section>

        {{-- FOOTNOTE --}}
        <div class="exam-page-footnote">
            <span>
                <i class="bi bi-book-half"></i>
                SCHOOL DIARY
            </span>

            <span class="exam-footnote-divider"></span>

            <span>Academic Assessment Management</span>
        </div>

    </div>
</div>

<style>
/* =====================================================
   SCHOOL DIARY - EXAM MANAGEMENT
===================================================== */

.exam-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --wood: #a87543;
    --blue: #2b5d8a;
    --teal: #527f79;
    --green: #3a8961;
    --amber: #b78232;
    --red: #bd5148;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: 'Public Sans', sans-serif;
}

.exam-page *,
.exam-page *::before,
.exam-page *::after {
    box-sizing: border-box;
}

.exam-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: auto;
    padding-right: 24px;
    padding-left: 24px;
}

.exam-page a,
.exam-page button {
    transition: all .2s ease;
}

/* ALERTS */

.exam-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 14px 17px;
    border: 1px solid;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
}

.exam-alert > i {
    font-size: 17px;
}

.exam-alert-success {
    border-color: #cfe4d2;
    color: #347849;
    background: #f2f8ef;
}

.exam-alert-danger {
    border-color: #ebd2ca;
    color: #a34038;
    background: #fff4f0;
}

.exam-alert ul {
    padding-left: 17px;
    font-weight: 400;
    line-height: 1.8;
}

/* HERO */

.exam-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(290px, .85fr);
    overflow: hidden;
    position: relative;
    margin-bottom: 38px;
    border: 1px solid var(--line);
    border-radius: 11px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38,59,53,.045);
}

.exam-hero-content {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 35px 30px 35px 42px;
}

.exam-hero-icon {
    display: flex;
    flex: 0 0 58px;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    border: 1px solid #e6dfce;
    border-radius: 13px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 23px;
}

.exam-hero-text {
    min-width: 0;
}

.exam-eyebrow,
.exam-panel-eyebrow {
    display: block;
    margin-bottom: 9px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
}

.exam-hero-text h2 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: Georgia,serif;
    font-size: clamp(23px, 2.2vw, 31px);
    font-weight: 700;
}

.exam-hero-text p {
    max-width: 530px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.exam-hero-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.exam-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 215px;
    overflow: hidden;
    padding: 28px;
    color: #e9efe2;
    background: linear-gradient(135deg,#344f43,#263b35 60%,#20352c);
    text-align: center;
}

.exam-decoration {
    position: absolute;
    width: 180px;
    height: 180px;
    border: 1px solid rgba(241,234,216,.13);
    border-radius: 50%;
    pointer-events: none;
}

.decoration-one {
    top: -110px;
    right: -55px;
}

.decoration-two {
    right: -65px;
    bottom: -130px;
    width: 230px;
    height: 230px;
}

.exam-handwritten {
    position: relative;
    z-index: 1;
    color: var(--chalk);
    font-family: cursive;
    font-size: 25px;
    font-weight: 600;
}

.exam-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 290px;
    margin: 0;
    color: #d0d9ce;
    font-size: 12px;
    line-height: 1.8;
}

/* BUTTON */

.exam-primary-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 10px 17px;
    border: 1px solid var(--ink);
    border-radius: 8px;
    color: white;
    background: var(--ink);
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.exam-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: white;
    background: var(--ink-hover);
    box-shadow: 0 6px 15px rgba(29,48,43,.14);
}

.exam-hero-actions .exam-primary-btn {
    margin-top: 3px;
    border-color: var(--cream);
    color: var(--ink);
    background: var(--cream);
}

.exam-hero-actions .exam-primary-btn:hover {
    border-color: white;
    color: var(--ink);
    background: white;
}

/* KPI CARDS */

.exam-kpis {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 20px;
    margin: 40px 0 28px;
}

.exam-kpi {
    --kpi-color: var(--blue);
    --kpi-tint: #edf3f8;
    --kpi-border: #d7e2e9;

    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 0;
    min-height: 175px;
    padding: 22px 20px 17px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38,59,53,.045);
    transition: transform .2s ease,box-shadow .2s ease;
}

.exam-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(38,59,53,.09);
}

.exam-kpi.blue {
    --kpi-color: #2b5d8a;
    --kpi-tint: #edf3f8;
    --kpi-border: #d7e2e9;
}

.exam-kpi.orange {
    --kpi-color: #a87543;
    --kpi-tint: #faf3e6;
    --kpi-border: #e8dcc5;
}

.exam-kpi.green {
    --kpi-color: #3a8961;
    --kpi-tint: #edf6ed;
    --kpi-border: #d0e5d2;
}

.exam-kpi.teal {
    --kpi-color: #527f79;
    --kpi-tint: #edf5f2;
    --kpi-border: #d0e3dc;
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: #68756b;
    font-size: 12px;
    font-weight: 700;
}

.kpi-icon {
    display: flex;
    flex: 0 0 43px;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 1px solid var(--kpi-border);
    border-radius: 10px;
    color: var(--kpi-color);
    background: var(--kpi-tint);
    font-size: 18px;
}

.kpi-value {
    color: var(--ink);
    font-family: Georgia,serif;
    font-size: 39px;
    font-weight: 700;
    line-height: 1;
}

.kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px dashed #ded8c9;
    color: var(--muted);
    font-size: 10px;
}

.kpi-caption i {
    color: var(--kpi-color);
}

/* MAIN PANEL */

.exam-paper-panel {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38,59,53,.045);
}

.exam-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 20px 22px;
    border-bottom: 1px dashed #dcd6c7;
}

.exam-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
}

.exam-panel-title-icon {
    display: flex;
    flex: 0 0 43px;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 1px solid #d9e3dd;
    border-radius: 10px;
    color: var(--ink);
    background: #f0f2e9;
    font-size: 16px;
}

.exam-panel-title-content h3,
.exam-panel-header h3 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia,serif;
    font-size: 20px;
    font-weight: 700;
}

.exam-panel-header p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.exam-record-count {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 12px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
    white-space: nowrap;
}

.exam-record-count i,
.exam-record-count strong {
    color: var(--ink);
}

/* TABLE */

.exam-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.exam-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.exam-table thead th {
    padding: 14px 15px;
    border-bottom: 1px solid #e5e1d5;
    color: #707a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.exam-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.exam-table tbody tr {
    background: var(--paper);
    transition: background .2s ease;
}

.exam-table tbody tr:hover {
    background: #faf9f2;
}

.exam-table tbody tr:last-child td {
    border-bottom: 0;
}

.exam-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: 1px solid #e5e1d5;
    border-radius: 8px;
    color: #647166;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

.exam-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.exam-avatar {
    display: flex;
    flex: 0 0 39px;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    border: 1px solid #d9e3dd;
    border-radius: 10px;
    color: var(--ink);
    background: #f0f2e9;
    font-size: 15px;
}

.exam-name {
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-subtitle {
    margin-top: 5px;
    color: #858b7f;
    font-size: 10px;
}

.exam-year-badge,
.exam-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.exam-year-badge {
    border-color: #e7dec8;
    color: #876326;
    background: #faf5e8;
}

.exam-type-badge {
    border-color: #d5e0e7;
    color: var(--blue);
    background: #f0f5f8;
}

.exam-muted {
    color: #91988d;
    font-size: 11px;
}

.exam-period {
    min-width: 140px;
    white-space: nowrap;
}

.exam-period-start {
    margin-bottom: 5px;
    color: var(--ink);
    font-size: 11px;
    font-weight: 700;
}

.exam-period-start i {
    margin-right: 4px;
    color: var(--wood);
}

.exam-period-end {
    color: var(--muted);
    font-size: 10px;
}

.exam-period-end i {
    margin-right: 4px;
    color: var(--teal);
}

/* STATUS */

.exam-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-draft {
    border-color: #e1ded3;
    color: #68746a;
    background: #f5f3e9;
}

.status-draft .status-dot {
    background: #899187;
}

.status-scheduled {
    border-color: #d5e0e7;
    color: var(--blue);
    background: #f0f5f8;
}

.status-scheduled .status-dot {
    background: var(--blue);
}

.status-completed {
    border-color: #cfe3d2;
    color: #347849;
    background: #eff7ed;
}

.status-completed .status-dot {
    background: var(--green);
}

.status-cancelled {
    border-color: #ebd2ca;
    color: var(--red);
    background: #fff2ee;
}

.status-cancelled .status-dot {
    background: var(--red);
}

/* ACTIONS */

.exam-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.exam-actions form {
    display: inline-flex;
    margin: 0;
}

.exam-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e1ded3;
    border-radius: 8px;
    color: #68746a;
    background: #fffefa;
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.exam-action-btn:hover {
    transform: translateY(-2px);
}

.exam-action-btn.view:hover {
    border-color: #c4d6df;
    color: var(--blue);
    background: #f0f5f8;
}

.exam-action-btn.edit:hover {
    border-color: #e7d4ae;
    color: #a87528;
    background: #faf4e6;
}

.exam-action-btn.delete:hover {
    border-color: #edcbc4;
    color: var(--red);
    background: #fff1ed;
}

.exam-action-btn:focus-visible,
.exam-primary-btn:focus-visible {
    outline: 3px solid rgba(82,127,121,.35);
    outline-offset: 3px;
}

/* FOOTER */

.exam-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 20px;
    border-top: 1px dashed #dcd6c7;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.exam-card-footer strong {
    color: var(--ink);
}

.exam-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68766d;
    font-size: 10px;
}

.exam-footer-status i {
    color: var(--green);
}

/* EMPTY STATE */

.exam-empty-state {
    padding: 60px 22px;
    text-align: center;
}

.exam-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 17px;
    border: 1px solid #e5dfcc;
    border-radius: 15px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 26px;
}

.exam-empty-eyebrow {
    display: block;
    margin-bottom: 8px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.exam-empty-state h4 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: Georgia,serif;
    font-size: 23px;
    font-weight: 700;
}

.exam-empty-state p {
    max-width: 440px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* PAGE FOOTNOTE */

.exam-page-footnote {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 20px 10px 5px;
    color: #8a9185;
    font-size: 9px;
    font-weight: 600;
}

.exam-page-footnote span:first-child {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--ink);
    font-weight: 800;
    letter-spacing: 1px;
}

.exam-page-footnote span:first-child i {
    color: var(--wood);
}

.exam-footnote-divider {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #b4ad9b;
}

/* RESPONSIVE */

@media(max-width:1200px) {
    .exam-kpis {
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 28px 18px;
    }
}

@media(max-width:900px) {
    .exam-hero {
        grid-template-columns: 1fr;
    }

    .exam-hero-actions {
        min-height: 175px;
    }
}

@media(max-width:650px) {
    .exam-page .container-fluid {
        padding: 18px 13px !important;
    }

    .exam-hero {
        margin-bottom: 32px;
    }

    .exam-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 16px;
    }

    .exam-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .exam-hero-text h2 {
        font-size: 23px;
    }

    .exam-hero-text p {
        font-size: 12px;
    }

    .exam-eyebrow {
        font-size: 9px;
    }

    .exam-hero-actions {
        min-height: 165px;
        padding: 22px 17px;
    }

    .exam-handwritten {
        font-size: 23px;
    }

    .exam-kpis {
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 27px 12px;
        margin-top: 34px;
        margin-bottom: 23px;
    }

    .exam-kpi {
        gap: 13px;
        min-height: 165px;
        padding: 17px 13px 14px;
    }

    .kpi-top {
        align-items: flex-start;
        font-size: 10px;
    }

    .kpi-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .kpi-value {
        font-size: 32px;
    }

    .kpi-caption {
        font-size: 9px;
        line-height: 1.6;
    }

    .exam-panel-header {
        align-items: flex-start;
        gap: 10px;
        padding: 15px 13px;
    }

    .exam-panel-title-wrap {
        gap: 9px;
    }

    .exam-panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .exam-panel-header h3 {
        font-size: 17px;
    }

    .exam-panel-header p {
        font-size: 10px;
        line-height: 1.6;
    }

    .exam-record-count {
        gap: 5px;
        padding: 6px 8px;
        font-size: 10px;
    }

    .exam-table {
        min-width: 1000px;
    }

    .exam-card-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
        padding: 13px 15px;
    }

    .exam-empty-state {
        padding: 48px 17px;
    }
}

@media(max-width:380px) {
    .exam-kpis {
        grid-template-columns: 1fr;
    }

    .exam-kpi {
        min-height: 155px;
        padding: 19px 17px;
    }

    .exam-panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .exam-record-count {
        align-self: flex-start;
    }

    .exam-hero-text h2 {
        font-size: 21px;
    }
}

@media(prefers-reduced-motion:reduce) {
    .exam-page *,
    .exam-page *::before,
    .exam-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection
