@extends('layouts.app')

@section('title', 'Student Attendance | Admin')
@section('page-title', 'Student Attendance')

@section('content')

@php
    $totalClasses   = $classCards->count();
    $monthLabel     = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
@endphp

<div class="attendance-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="attendance-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="attendance-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>{{ session('error') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="attendance-hero">
            <div class="attendance-hero-content">
                <div class="attendance-hero-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <div class="attendance-hero-text">
                    <div class="attendance-breadcrumb">
                        ADMIN <span>/</span> ATTENDANCE
                    </div>

                    <h2>Student Attendance</h2>

                    <p>
                        Manage daily student attendance by class and section.
                        View present and absent students and mark attendance
                        efficiently.
                    </p>

                    <div class="attendance-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Daily Attendance Register</span>
                    </div>
                </div>
            </div>

            <div class="attendance-hero-actions">
                <div class="hero-handwritten">Mark. Track. Improve.</div>

                <p>
                    Choose a class and section below to open
                    its attendance sheet.
                </p>

                <div class="month-pill">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $monthLabel }}
                </div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="attendance-kpis">

            <article class="attendance-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Classes &amp; Sections</span>
                    <span class="kpi-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalClasses }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Total classes &amp; sections
                </div>
            </article>

            <article class="attendance-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Total Students</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalStudents }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-user-graduate"></i>
                    All active students
                </div>
            </article>

            <article class="attendance-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Students Present</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalPresent }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Total students present
                </div>
            </article>

            <article class="attendance-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Students Absent</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-xmark"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalAbsent }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-user-slash"></i>
                    Total students absent
                </div>
            </article>

        </section>

        {{-- CLASSES TABLE --}}
        <section class="attendance-table-card">

            <div class="attendance-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">CLASS REGISTER</div>
                        <h3>Classes &amp; Sections</h3>
                        <p>Select a class and section to manage student attendance.</p>
                    </div>
                </div>

                <div class="attendance-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalClasses }}</strong>
                    {{ $totalClasses == 1 ? 'Class' : 'Classes' }}
                </div>
            </div>

            <div class="attendance-table-wrapper">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Class</th>
                            <th scope="col">Academic Year</th>
                            <th scope="col">Section</th>
                            <th scope="col">Students</th>
                            <th scope="col">Present</th>
                            <th scope="col">Absent</th>
                            <th scope="col">Class Teacher</th>
                            <th scope="col" class="actions-heading">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($classCards as $card)
                            @php
                                $teacherName = trim($card->class_teacher ?? '');
                                $hasTeacher  = $teacherName !== '';

                                $initials = '';
                                if ($hasTeacher) {
                                    foreach (array_slice(preg_split('/\s+/', $teacherName), 0, 2) as $word) {
                                        $initials .= strtoupper(substr($word, 0, 1));
                                    }
                                }
                                $initials = $initials ?: 'NA';
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <div class="class-info">
                                        <div class="class-mini-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="class-details">
                                            <div class="class-name">
                                                {{ $card->class ?? '-' }}
                                            </div>
                                            <div class="class-description">
                                                Section {{ $card->section ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="year-badge">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $card->academic_year ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $card->section ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="student-value">
                                        <i class="fas fa-users"></i>
                                        <strong>{{ $card->student_count ?? 0 }}</strong>
                                    </div>
                                </td>

                                <td>
                                    <span class="attendance-badge present">
                                        <i class="fas fa-circle-check"></i>
                                        {{ $card->present_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="attendance-badge absent">
                                        <i class="fas fa-circle-xmark"></i>
                                        {{ $card->absent_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <div class="teacher-info">
                                        <div class="teacher-avatar {{ $hasTeacher ? '' : 'empty' }}">
                                            {{ $initials }}
                                        </div>
                                        <span class="teacher-name {{ $hasTeacher ? '' : 'not-assigned' }}">
                                            {{ $hasTeacher ? $teacherName : 'Not Assigned' }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="attendance-actions">
                                        <a href="{{ route('admin.attendance.student.sheet', [
                                                'academic_year' => $card->academic_year,
                                                'class'         => $card->class,
                                                'section'       => $card->section,
                                                'month'         => $month,
                                            ]) }}"
                                           class="diary-primary-btn compact-btn"
                                           aria-label="Mark attendance for {{ $card->class }} {{ $card->section }}">
                                            <i class="fas fa-pen-to-square"></i>
                                            Mark Attendance
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="attendance-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>

                                        <h4>No Classes Found</h4>

                                        <p>
                                            No active students or class sections are
                                            currently available for attendance.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            @if($totalClasses > 0)
                <div class="attendance-card-footer">
                    <div>
                        Showing
                        <strong>{{ $totalClasses }}</strong>
                        class(es) for
                        <strong>{{ $monthLabel }}</strong>
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Class register loaded successfully
                    </div>
                </div>
            @endif

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   STUDENT ATTENDANCE — INDEX PAGE
   Same tokens, cards, buttons and layout as Roles / Notices / Library.
   Every selector is scoped under .attendance-page.
========================================================= */

.attendance-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.attendance-page *,
.attendance-page *::before,
.attendance-page *::after {
    box-sizing: border-box;
}

.attendance-page a,
.attendance-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.attendance-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.attendance-page .attendance-hero,
.attendance-page .attendance-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.attendance-page .attendance-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.attendance-page .attendance-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.attendance-page .attendance-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.attendance-page .attendance-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.attendance-page .attendance-hero-text {
    min-width: 0;
}

.attendance-page .attendance-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.attendance-page .attendance-breadcrumb span {
    color: #b8b09e;
}

.attendance-page .attendance-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.attendance-page .attendance-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.attendance-page .attendance-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.attendance-page .attendance-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 25px;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.attendance-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.attendance-page .attendance-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

.attendance-page .month-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid #f1ead8;
    border-radius: 7px;
    color: var(--ink);
    background: #f1ead8;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    white-space: nowrap;
}

/* BUTTONS */

.attendance-page .diary-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--ink);
    border-radius: 7px;
    color: #fff !important;
    background: var(--ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.attendance-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.attendance-page .diary-primary-btn.compact-btn {
    min-height: 34px;
    padding: 7px 12px;
    font-size: 11px;
}

/* ALERTS */

.attendance-page .attendance-success-alert,
.attendance-page .attendance-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.attendance-page .attendance-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.attendance-page .attendance-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.attendance-page .success-icon,
.attendance-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.attendance-page .success-icon { background: #dcefd9; }
.attendance-page .error-icon { background: #fbe0d8; }

.attendance-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.attendance-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.attendance-page .attendance-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.attendance-page .attendance-kpi {
    --kpi-color: var(--blue);

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    padding: 22px 20px 16px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.attendance-page .attendance-kpi::before {
    position: absolute;
    top: -25px;
    left: 15px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .25px;
}

.attendance-page .attendance-kpi.blue  { --kpi-color: #2b5d8a; }
.attendance-page .attendance-kpi.amber { --kpi-color: #a87543; }
.attendance-page .attendance-kpi.green { --kpi-color: #3a8060; }
.attendance-page .attendance-kpi.red   { --kpi-color: #bd5148; }

.attendance-page .attendance-kpi.blue::before  { content: "Classes"; }
.attendance-page .attendance-kpi.amber::before { content: "Students"; }
.attendance-page .attendance-kpi.green::before { content: "Present"; }
.attendance-page .attendance-kpi.red::before   { content: "Absent"; }

.attendance-page .attendance-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.attendance-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.attendance-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.attendance-page .attendance-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white);
    border-radius: 10px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    font-size: 17px;
}

.attendance-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.attendance-page .kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 35px;
    padding-top: 11px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.attendance-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.attendance-page .attendance-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.attendance-page .attendance-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.attendance-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.attendance-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.attendance-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.attendance-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.attendance-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.attendance-page .attendance-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.attendance-page .attendance-record-count i,
.attendance-page .attendance-record-count strong {
    color: var(--ink);
}

/* TABLE */

.attendance-page .attendance-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.attendance-page .attendance-table {
    width: 100%;
    min-width: 1180px;
    margin: 0;
    border-collapse: collapse;
}

.attendance-page .attendance-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.attendance-page .attendance-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.attendance-page .attendance-table tbody tr {
    transition: background .18s ease;
}

.attendance-page .attendance-table tbody tr:hover {
    background: #faf9f2;
}

.attendance-page .attendance-table tbody tr:last-child td {
    border-bottom: none;
}

.attendance-page .attendance-table .actions-heading {
    text-align: right;
}

.attendance-page .record-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e7e4d8;
    border-radius: 7px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

/* CLASS CELL */

.attendance-page .class-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 180px;
}

.attendance-page .class-mini-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e6dfce;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.attendance-page .class-details {
    min-width: 0;
}

.attendance-page .class-name {
    max-width: 220px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.attendance-page .class-description {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.attendance-page .year-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 28px;
    padding: 0 10px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--amber);
    background: #fcf7e9;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.attendance-page .count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 28px;
    padding: 0 9px;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    color: var(--blue);
    background: #f0f5f8;
    font-size: 12px;
    font-weight: 800;
}

.attendance-page .student-value {
    display: inline-flex;
    align-items: baseline;
    gap: 7px;
}

.attendance-page .student-value i {
    color: var(--blue);
    font-size: 12px;
}

.attendance-page .student-value strong {
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

/* ATTENDANCE BADGES */

.attendance-page .attendance-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.attendance-page .attendance-badge.present {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.attendance-page .attendance-badge.absent {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

/* TEACHER */

.attendance-page .teacher-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.attendance-page .teacher-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e6dfce;
    border-radius: 50%;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
    font-weight: 800;
}

.attendance-page .teacher-avatar.empty {
    color: #858e82;
    background: #f5f3e9;
}

.attendance-page .teacher-name {
    max-width: 180px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.attendance-page .teacher-name.not-assigned {
    color: var(--muted);
    font-style: italic;
    font-weight: 600;
}

/* ACTION */

.attendance-page .attendance-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

/* EMPTY STATE */

.attendance-page .attendance-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.attendance-page .empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border: 1px solid #e5dfcc;
    border-radius: 14px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 27px;
}

.attendance-page .attendance-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.attendance-page .attendance-empty-state p {
    max-width: 450px;
    margin: 0 auto;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* FOOTER */

.attendance-page .attendance-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.attendance-page .attendance-card-footer strong {
    color: var(--ink);
}

.attendance-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.attendance-page .footer-status i {
    color: var(--green);
}

/* TABLET */

@media (max-width: 1100px) {
    .attendance-page .attendance-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .attendance-page .attendance-hero {
        grid-template-columns: 1fr;
    }

    .attendance-page .attendance-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .attendance-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .attendance-page .attendance-hero::before {
        left: 22px;
    }

    .attendance-page .attendance-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .attendance-page .attendance-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .attendance-page .attendance-hero-text h2 {
        font-size: 23px;
    }

    .attendance-page .attendance-hero-text p {
        font-size: 12px;
    }

    .attendance-page .attendance-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .attendance-page .attendance-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .attendance-page .attendance-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .attendance-page .attendance-kpi {
        padding: 20px 16px 15px;
    }

    .attendance-page .kpi-value {
        font-size: 30px;
    }

    .attendance-page .attendance-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .attendance-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .attendance-page .attendance-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .attendance-page .attendance-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .attendance-page .attendance-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .attendance-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */

.attendance-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .attendance-page *,
    .attendance-page *::before,
    .attendance-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection