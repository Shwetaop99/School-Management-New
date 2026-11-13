@extends('layouts.app')

@section('title', 'Exam Management | Admin')
@section('page-title', 'Exam Management')

@section('content')

@php
    $totalExams = $exams->count();
    $draftExams = $exams->where('status', 'draft')->count();
    $scheduledExams = $exams->where('status', 'scheduled')->count();
    $completedExams = $exams->where('status', 'completed')->count();
@endphp

<div class="exam-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="exam-success-alert" role="status">
                <span class="exam-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="exam-error-alert" role="alert">
                <span class="exam-alert-icon danger">
                    <i class="bi bi-exclamation-lg"></i>
                </span>

                <div>
                    <strong>Please correct the following</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="exam-hero">

            <div class="exam-hero-content">
                <div class="exam-hero-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                <div class="exam-hero-text">
                    <span class="exam-eyebrow">
                        <i class="bi bi-book-half"></i>
                        SCHOOL ACADEMIC DIRECTORY
                    </span>

                    <h2>Examination Management</h2>

                    <p>
                        Organise examinations, academic years, exam types
                        and schedules from one structured academic workspace.
                    </p>

                    <div class="exam-hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Academic Assessment &amp; Examination Records</span>
                    </div>
                </div>
            </div>

            <div class="exam-hero-actions">
                <div class="exam-hero-decoration decoration-one"></div>
                <div class="exam-hero-decoration decoration-two"></div>

                <div class="exam-handwritten">Prepare. Perform. Progress.</div>

                <p>
                    Plan examinations, coordinate academic sessions
                    and maintain organised assessment records.
                </p>

                <a href="{{ route('admin.exams.create') }}"
                   class="exam-primary-btn">
                    <i class="bi bi-plus-lg"></i>
                    Create Exam
                    <i class="bi bi-arrow-right button-arrow"></i>
                </a>
            </div>

        </section>

        {{-- DYNAMIC STATISTICS --}}
        <section class="exam-kpis">

            {{-- TOTAL --}}
            <article class="exam-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Exams</span>
                    <span class="kpi-icon">
                        <i class="bi bi-journal-text"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalExams }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="bi bi-folder2-open"></i>
                    </span>
                    All registered examinations
                </div>
            </article>

            {{-- DRAFT --}}
            <article class="exam-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Draft Exams</span>
                    <span class="kpi-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $draftExams }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    Examinations being configured
                </div>
            </article>

            {{-- SCHEDULED --}}
            <article class="exam-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Scheduled Exams</span>
                    <span class="kpi-icon">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $scheduledExams }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    Examinations scheduled
                </div>
            </article>

            {{-- COMPLETED --}}
            <article class="exam-kpi teal">
                <div class="kpi-top">
                    <span class="kpi-label">Completed Exams</span>
                    <span class="kpi-icon">
                        <i class="bi bi-check2-circle"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $completedExams }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="bi bi-patch-check"></i>
                    </span>
                    Completed examinations
                </div>
            </article>

        </section>

        {{-- MAIN PAPER PANEL --}}
        <section class="exam-paper-panel">

            {{-- PANEL HEADER --}}
            <div class="exam-panel-header">

                <div class="exam-panel-title-wrap">
                    <div class="exam-panel-title-icon">
                        <i class="bi bi-list-check"></i>
                    </div>

                    <div class="exam-panel-title-content">
                        <span class="exam-panel-eyebrow">
                            ACADEMIC RECORDS
                        </span>

                        <h3>Examination List</h3>

                        <p>
                            View and manage examinations, types and schedules.
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
                                <th class="number-column">#</th>
                                <th>Examination</th>
                                <th>Academic Year</th>
                                <th>Type</th>
                                <th>Exam Period</th>
                                <th>Status</th>
                                <th class="actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($exams as $exam)

                                @php
                                    $statusClass = match($exam->status) {
                                        'draft' => 'status-draft',
                                        'scheduled' => 'status-scheduled',
                                        'completed' => 'status-completed',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-draft',
                                    };

                                    $statusIcon = match($exam->status) {
                                        'draft' => 'bi-file-earmark',
                                        'scheduled' => 'bi-calendar-check',
                                        'completed' => 'bi-check-circle',
                                        'cancelled' => 'bi-x-circle',
                                        default => 'bi-circle',
                                    };
                                @endphp

                                <tr>
                                    {{-- NUMBER --}}
                                    <td>
                                        <span class="exam-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- EXAMINATION DETAILS --}}
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
                                                    Exam ID: {{ $exam->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ACADEMIC YEAR --}}
                                    <td>
                                        @if($exam->academic_year)
                                            <span class="exam-year-badge">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $exam->academic_year }}
                                            </span>
                                        @else
                                            <span class="exam-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- TYPE --}}
                                    <td>
                                        @if($exam->exam_type)
                                            <span class="exam-type-badge">
                                                <i class="bi bi-bookmark-star"></i>
                                                {{ $exam->exam_type }}
                                            </span>
                                        @else
                                            <span class="exam-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- EXAM PERIOD --}}
                                    <td>
                                        <div class="exam-period">
                                            <div class="exam-period-start">
                                                <i class="bi bi-calendar-event"></i>
                                                {{ $exam->start_date?->format('d M Y') ?? 'Not set' }}
                                            </div>

                                            <div class="exam-period-end">
                                                <i class="bi bi-arrow-right"></i>
                                                {{ $exam->end_date?->format('d M Y') ?? 'End date not set' }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        <span class="exam-status-badge {{ $statusClass }}">
                                            <span class="status-dot"></span>
                                            <i class="bi {{ $statusIcon }}"></i>
                                            {{ ucfirst($exam->status ?? 'draft') }}
                                        </span>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="exam-actions">

                                            <a href="{{ route('admin.exams.show', $exam) }}"
                                               class="exam-action-btn view"
                                               title="View Exam"
                                               aria-label="View Exam">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.exams.edit', $exam) }}"
                                               class="exam-action-btn edit"
                                               title="Edit Exam"
                                               aria-label="Edit Exam">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.exams.destroy', $exam) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this exam? All related exam setup and timetable data will also be removed.');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="exam-action-btn delete"
                                                        title="Delete Exam"
                                                        aria-label="Delete Exam">
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

                {{-- TABLE FOOTER --}}
                <div class="exam-card-footer">
                    <div class="exam-footer-records">
                        <i class="bi bi-file-earmark-text"></i>
                        Showing
                        <strong>{{ $totalExams }}</strong>
                        {{ $totalExams === 1 ? 'exam' : 'exams' }}
                    </div>

                    <div class="exam-footer-status">
                        <i class="bi bi-check-circle"></i>
                        Examination records loaded successfully
                    </div>
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

                    <h4>No Exams Found</h4>

                    <p>
                        No examinations have been registered yet.
                        Create your first exam to organise academic sessions,
                        subjects and examination timetables.
                    </p>

                    <a href="{{ route('admin.exams.create') }}"
                       class="exam-primary-btn">
                        <i class="bi bi-plus-lg"></i>
                        Create First Exam
                        <i class="bi bi-arrow-right button-arrow"></i>
                    </a>

                </div>

            @endif

        </section>

        {{-- PAGE FOOTNOTE --}}
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
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   EXAM MANAGEMENT
   Warm paper • Forest green • Cream • Earthy accents
========================================================= */

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
    --cream: #f1ead8;
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

.exam-page a,
.exam-page button {
    transition: all .2s ease;
}

.exam-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* =========================================================
   HERO
========================================================= */

.exam-page .exam-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(290px, .85fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 38px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.exam-page .exam-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 32px;
    width: 2px;
    background: rgba(184, 91, 62, .30);
    content: "";
}

.exam-page .exam-hero-content {
    display: flex;
    align-items: center;
    gap: 19px;
    min-width: 0;
    padding: 32px 28px 32px 55px;
}

.exam-page .exam-hero-icon {
    display: flex;
    flex: 0 0 56px;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.exam-page .exam-hero-text {
    min-width: 0;
}

.exam-page .exam-eyebrow {
    display: block;
    margin-bottom: 9px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.exam-page .exam-hero-text h2 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 30px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.5px;
}

.exam-page .exam-hero-text p {
    max-width: 510px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.exam-page .exam-hero-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

/* Hero action panel */

.exam-page .exam-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 13px;
    min-height: 215px;
    overflow: hidden;
    padding: 28px;
    color: #e9efe2;
    background: linear-gradient(135deg, #344f43 0%, #263b35 55%, #20352c 100%);
    text-align: center;
}

.exam-page .exam-hero-decoration {
    position: absolute;
    width: 180px;
    height: 180px;
    border: 1px solid rgba(241, 234, 216, .13);
    border-radius: 50%;
    pointer-events: none;
}

.exam-page .decoration-one {
    top: -110px;
    right: -55px;
}

.exam-page .decoration-two {
    right: -65px;
    bottom: -130px;
    width: 230px;
    height: 230px;
}

.exam-page .exam-handwritten {
    position: relative;
    z-index: 1;
    color: var(--cream);
    font-family: var(--hand, cursive);
    font-size: 25px;
    font-weight: 600;
}

.exam-page .exam-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 290px;
    margin: 0;
    color: #d0d9ce;
    font-size: 12px;
    line-height: 1.8;
}

/* =========================================================
   PRIMARY BUTTON
========================================================= */

.exam-page .exam-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 41px;
    padding: 10px 16px;
    border: 1px solid var(--ink);
    border-radius: 8px;
    color: var(--paper);
    background: var(--ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.exam-page .exam-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: var(--paper);
    background: var(--ink-hover);
    box-shadow: 0 6px 15px rgba(29, 48, 43, .14);
}

.exam-page .exam-hero-actions .exam-primary-btn {
    position: relative;
    z-index: 1;
    margin-top: 2px;
    border-color: var(--cream);
    color: var(--ink);
    background: var(--cream);
}

.exam-page .exam-hero-actions .exam-primary-btn:hover {
    border-color: var(--paper);
    color: var(--ink-dark);
    background: var(--paper);
}

.exam-page .button-arrow {
    margin-left: 3px;
    font-size: 10px;
}

/* =========================================================
   ALERTS
========================================================= */

.exam-page .exam-success-alert,
.exam-page .exam-error-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
}

.exam-page .exam-success-alert {
    border-color: #cfe4d2;
    color: #347849;
    background: #f2f8ef;
}

.exam-page .exam-error-alert {
    align-items: flex-start;
    border-color: #ebd2ca;
    color: #a34038;
    background: #fff4f0;
}

.exam-page .exam-alert-icon {
    display: flex;
    flex: 0 0 28px;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    color: #347849;
    background: #dceedd;
}

.exam-page .exam-alert-icon.danger {
    color: var(--red);
    background: #f9e2dc;
}

.exam-page .exam-error-alert ul {
    font-size: 11px;
    font-weight: 400;
    line-height: 1.8;
}

/* =========================================================
   KPI CARDS — MATCHING LOCKED CLASSES DESIGN
========================================================= */

.exam-page .exam-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin: 40px 0 27px;
}

.exam-page .exam-kpi {
    --kpi-color: var(--blue);
    --kpi-tint: #edf3f8;
    --kpi-border: #d7e2e9;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 17px;
    min-width: 0;
    min-height: 190px;
    overflow: visible;
    padding: 23px 21px 17px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    transition: transform .22s ease, box-shadow .22s ease;
}

.exam-page .exam-kpi::before {
    position: absolute;
    top: -24px;
    left: 16px;
    display: flex;
    align-items: center;
    min-height: 24px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fffefa;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    content: "ACADEMIC";
}

.exam-page .exam-kpi.blue {
    --kpi-color: #2b5d8a;
    --kpi-tint: #edf3f8;
    --kpi-border: #d7e2e9;
}

.exam-page .exam-kpi.blue::before {
    content: "DIRECTORY";
}

.exam-page .exam-kpi.orange {
    --kpi-color: #a87543;
    --kpi-tint: #faf3e6;
    --kpi-border: #e8dcc5;
}

.exam-page .exam-kpi.orange::before {
    content: "IN PREPARATION";
}

.exam-page .exam-kpi.green {
    --kpi-color: #3a8961;
    --kpi-tint: #edf6ed;
    --kpi-border: #d0e5d2;
}

.exam-page .exam-kpi.green::before {
    content: "SCHEDULED";
}

.exam-page .exam-kpi.teal {
    --kpi-color: #527f79;
    --kpi-tint: #edf5f2;
    --kpi-border: #d0e3dc;
}

.exam-page .exam-kpi.teal::before {
    content: "COMPLETED";
}

.exam-page .exam-kpi:hover {
    transform: translateY(-4px);
    border-color: var(--kpi-border);
    box-shadow: 0 12px 25px rgba(38, 59, 53, .09);
}

.exam-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.exam-page .kpi-label {
    color: #68756b;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.5;
}

.exam-page .kpi-icon {
    display: flex;
    flex: 0 0 46px;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border: 1px solid var(--kpi-border);
    border-radius: 11px;
    color: var(--kpi-color);
    background: var(--kpi-tint);
    font-size: 18px;
}

.exam-page .kpi-value {
    margin-top: 1px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 39px;
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -.6px;
    overflow-wrap: anywhere;
}

.exam-page .kpi-caption {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    min-height: 32px;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px dashed #ded8c9;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.7;
}

.exam-page .kpi-caption-icon {
    flex: 0 0 auto;
    padding-top: 2px;
    color: var(--kpi-color);
    font-size: 11px;
}

/* =========================================================
   MAIN PAPER PANEL
========================================================= */

.exam-page .exam-paper-panel {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.exam-page .exam-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 19px 22px;
    border-bottom: 1px dashed #dcd6c7;
    background: var(--paper);
}

.exam-page .exam-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.exam-page .exam-panel-title-icon {
    display: flex;
    flex: 0 0 42px;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border: 1px solid #d9e3dd;
    border-radius: 10px;
    color: var(--ink);
    background: #f0f2e9;
    font-size: 15px;
}

.exam-page .exam-panel-title-content h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 19px;
    font-weight: 700;
}

.exam-page .exam-panel-title-content p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.exam-page .exam-panel-eyebrow {
    display: block;
    margin-bottom: 4px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.exam-page .exam-record-count {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 32px;
    padding: 7px 12px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.exam-page .exam-record-count i,
.exam-page .exam-record-count strong {
    color: var(--ink);
}

/* =========================================================
   EXAM TABLE
========================================================= */

.exam-page .exam-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.exam-page .exam-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.exam-page .exam-table thead th {
    padding: 14px 15px;
    border-bottom: 1px solid #e5e1d5;
    color: #707a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.exam-page .exam-table thead th:first-child,
.exam-page .exam-table tbody td:first-child {
    padding-left: 20px;
}

.exam-page .exam-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.exam-page .exam-table tbody tr {
    background: var(--paper);
    transition: background-color .18s ease;
}

.exam-page .exam-table tbody tr:hover {
    background: #faf9f2;
}

.exam-page .exam-table tbody tr:last-child td {
    border-bottom: none;
}

.exam-page .exam-table .actions-heading {
    padding-right: 20px;
    text-align: right;
}

/* Row number */

.exam-page .exam-row-number {
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

/* Examination details */

.exam-page .exam-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 200px;
}

.exam-page .exam-avatar {
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
    font-size: 14px;
}

.exam-page .exam-details {
    min-width: 0;
}

.exam-page .exam-name {
    max-width: 240px;
    overflow-wrap: anywhere;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-page .exam-subtitle {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #858b7f;
    font-size: 10px;
}

.exam-page .exam-subtitle i {
    color: var(--wood);
}

/* Academic year and type */

.exam-page .exam-year-badge,
.exam-page .exam-type-badge {
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

.exam-page .exam-year-badge {
    border-color: #e7dec8;
    color: #876326;
    background: #faf5e8;
}

.exam-page .exam-type-badge {
    border-color: #d5e0e7;
    color: var(--blue);
    background: #f0f5f8;
}

.exam-page .exam-muted {
    color: #91988d;
    font-size: 12px;
}

/* Exam period */

.exam-page .exam-period {
    min-width: 145px;
    white-space: nowrap;
}

.exam-page .exam-period-start {
    margin-bottom: 5px;
    color: var(--ink);
    font-size: 11px;
    font-weight: 700;
}

.exam-page .exam-period-start i {
    margin-right: 4px;
    color: var(--wood);
}

.exam-page .exam-period-end {
    color: var(--muted);
    font-size: 10px;
}

.exam-page .exam-period-end i {
    margin-right: 4px;
    color: var(--teal);
}

/* Status badges */

.exam-page .exam-status-badge {
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

.exam-page .status-dot {
    flex: 0 0 6px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.exam-page .status-draft {
    border-color: #e1ded3;
    color: #68746a;
    background: #f5f3e9;
}

.exam-page .status-draft .status-dot {
    background: #899187;
}

.exam-page .status-scheduled {
    border-color: #d5e0e7;
    color: var(--blue);
    background: #f0f5f8;
}

.exam-page .status-scheduled .status-dot {
    background: var(--blue);
}

.exam-page .status-completed {
    border-color: #cfe3d2;
    color: #347849;
    background: #eff7ed;
}

.exam-page .status-completed .status-dot {
    background: var(--green);
}

.exam-page .status-cancelled {
    border-color: #ebd2ca;
    color: var(--red);
    background: #fff2ee;
}

.exam-page .status-cancelled .status-dot {
    background: var(--red);
}

/* =========================================================
   ACTION BUTTONS
========================================================= */

.exam-page .exam-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.exam-page .exam-actions form {
    display: inline-flex;
    margin: 0;
}

.exam-page .exam-action-btn {
    display: inline-flex;
    flex: 0 0 33px;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    padding: 0;
    border: 1px solid #e1ded3;
    border-radius: 8px;
    color: #68746a;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    line-height: 1;
    text-decoration: none;
    cursor: pointer;
}

.exam-page .exam-action-btn:hover {
    transform: translateY(-2px);
}

.exam-page .exam-action-btn.view:hover {
    border-color: #c4d6df;
    color: var(--blue);
    background: #f0f5f8;
}

.exam-page .exam-action-btn.edit:hover {
    border-color: #e7d4ae;
    color: #a87528;
    background: #faf4e6;
}

.exam-page .exam-action-btn.delete:hover {
    border-color: #edcbc4;
    color: var(--red);
    background: #fff1ed;
}

.exam-page .exam-primary-btn:focus-visible,
.exam-page .exam-action-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .35);
    outline-offset: 3px;
}

/* =========================================================
   TABLE FOOTER
========================================================= */

.exam-page .exam-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    min-height: 52px;
    padding: 13px 20px;
    border-top: 1px dashed #dcd6c7;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.exam-page .exam-card-footer strong {
    color: var(--ink);
}

.exam-page .exam-footer-records {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.exam-page .exam-footer-records > i {
    color: var(--wood);
}

.exam-page .exam-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68766d;
    font-size: 10px;
}

.exam-page .exam-footer-status i {
    color: var(--green);
}

/* =========================================================
   EMPTY STATE
========================================================= */

.exam-page .exam-empty-state {
    position: relative;
    overflow: hidden;
    padding: 62px 22px 66px;
    text-align: center;
}

.exam-page .exam-empty-icon {
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

.exam-page .exam-empty-eyebrow {
    display: block;
    margin-bottom: 8px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.exam-page .exam-empty-state h4 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 23px;
    font-weight: 700;
}

.exam-page .exam-empty-state p {
    max-width: 440px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* =========================================================
   PAGE FOOTNOTE
========================================================= */

.exam-page .exam-page-footnote {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 19px 10px 5px;
    color: #8a9185;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .3px;
}

.exam-page .exam-page-footnote > span:first-child {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--ink);
    font-weight: 800;
    letter-spacing: 1px;
}

.exam-page .exam-page-footnote > span:first-child i {
    color: var(--wood);
}

.exam-page .exam-footnote-divider {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #b4ad9b;
}

/* =========================================================
   RESPONSIVE — TABLET
========================================================= */

@media (max-width: 1200px) {
    .exam-page .exam-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px 18px;
    }

    .exam-page .exam-hero {
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, .8fr);
    }
}

@media (max-width: 900px) {
    .exam-page .exam-hero {
        grid-template-columns: 1fr;
    }

    .exam-page .exam-hero-actions {
        min-height: 175px;
    }
}

/* =========================================================
   RESPONSIVE — MOBILE
========================================================= */

@media (max-width: 650px) {
    .exam-page .container-fluid {
        padding: 18px 13px !important;
    }

    .exam-page .exam-hero {
        margin-bottom: 32px;
    }

    .exam-page .exam-hero::before {
        left: 20px;
    }

    .exam-page .exam-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 14px 23px 37px;
    }

    .exam-page .exam-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .exam-page .exam-hero-text h2 {
        font-size: 24px;
    }

    .exam-page .exam-hero-text p {
        font-size: 12px;
    }

    .exam-page .exam-eyebrow {
        font-size: 9px;
        letter-spacing: .6px;
    }

    .exam-page .exam-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .exam-page .exam-hero-actions {
        min-height: 165px;
        padding: 22px 17px;
    }

    .exam-page .exam-handwritten {
        font-size: 23px;
    }

    .exam-page .exam-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 27px 12px;
        margin-top: 34px;
        margin-bottom: 23px;
    }

    .exam-page .exam-kpi {
        gap: 14px;
        min-height: 175px;
        padding: 19px 14px 14px;
    }

    .exam-page .exam-kpi::before {
        left: 11px;
        font-size: 8px;
    }

    .exam-page .kpi-label {
        font-size: 11px;
    }

    .exam-page .kpi-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
        border-radius: 9px;
        font-size: 14px;
    }

    .exam-page .kpi-value {
        font-size: 32px;
    }

    .exam-page .kpi-caption {
        font-size: 9px;
        line-height: 1.6;
    }

    .exam-page .exam-panel-header {
        align-items: flex-start;
        gap: 10px;
        padding: 15px 13px;
    }

    .exam-page .exam-panel-title-wrap {
        gap: 9px;
    }

    .exam-page .exam-panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
        font-size: 13px;
    }

    .exam-page .exam-panel-title-content h3 {
        font-size: 17px;
    }

    .exam-page .exam-panel-title-content p {
        font-size: 10px;
    }

    .exam-page .exam-record-count {
        gap: 5px;
        padding: 6px 8px;
        font-size: 10px;
    }

    .exam-page .exam-table {
        min-width: 1000px;
    }

    .exam-page .exam-card-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
        padding: 13px 15px;
    }

    .exam-page .exam-empty-state {
        padding: 48px 17px 52px;
    }
}

@media (max-width: 380px) {
    .exam-page .exam-kpis {
        grid-template-columns: 1fr;
        gap: 27px;
    }

    .exam-page .exam-kpi {
        min-height: 165px;
        padding: 20px 18px 16px;
    }

    .exam-page .kpi-value {
        font-size: 35px;
    }

    .exam-page .exam-panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .exam-page .exam-record-count {
        align-self: flex-start;
    }

    .exam-page .exam-hero-text h2 {
        font-size: 22px;
    }
}

/* =========================================================
   ACCESSIBILITY
========================================================= */

@media (prefers-reduced-motion: reduce) {
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
