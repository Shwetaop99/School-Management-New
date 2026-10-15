@extends('layouts.app')

@section('title', 'Classes | Admin')
@section('page-title', 'Classes')

@section('content')

<div class="school-classes-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="classes-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="classes-hero">
            <div class="classes-hero-content">
                <div class="classes-hero-icon">
                    <i class="fas fa-school"></i>
                </div>

                <div class="classes-hero-text">
                    <span class="classes-eyebrow">
                        <i class="fas fa-book-open"></i>
                        SCHOOL ACADEMIC DIRECTORY
                    </span>

                    <h2>Classes &amp; Sections</h2>

                    <p>
                        Manage school classes, sections and academic years
                        from one organised academic workspace.
                    </p>

                    <div class="classes-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Academic Structure Management</span>
                    </div>
                </div>
            </div>

            <div class="classes-hero-actions">
                <div class="classes-hero-decoration decoration-one"></div>
                <div class="classes-hero-decoration decoration-two"></div>

                <div class="classes-handwritten">Learn. Grow. Achieve.</div>

                <p>
                    Organise classrooms, maintain academic sections
                    and build a structured learning environment.
                </p>

                <a href="{{ route('admin.classes.create') }}"
                   class="diary-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Class
                    <i class="fas fa-arrow-right button-arrow"></i>
                </a>
            </div>
        </section>

        {{-- DYNAMIC STATISTICS --}}
        @php
            $totalClasses = $classes->count();

            $activeClasses = $classes->filter(function ($class) {
                return (bool) $class->status;
            })->count();

            $inactiveClasses = $totalClasses - $activeClasses;

            $totalSections = $classes
                ->pluck('section')
                ->filter(function ($section) {
                    return $section !== null && trim((string) $section) !== '';
                })
                ->unique()
                ->count();
        @endphp

        {{-- KPI CARDS --}}
        <section class="classes-kpis">

            <article class="classes-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-school"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalClasses }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="fas fa-folder-open"></i>
                    </span>
                    All registered school classes
                </div>
            </article>

            <article class="classes-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $activeClasses }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    Classes currently marked active
                </div>
            </article>

            <article class="classes-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Classes</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-xmark"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $inactiveClasses }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="fas fa-pause-circle"></i>
                    </span>
                    Classes currently marked inactive
                </div>
            </article>

            <article class="classes-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Total Sections</span>
                    <span class="kpi-icon">
                        <i class="fas fa-layer-group"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalSections }}</div>

                <div class="kpi-caption">
                    <span class="kpi-caption-icon">
                        <i class="fas fa-list"></i>
                    </span>
                    Distinct registered sections
                </div>
            </article>

        </section>

        {{-- MAIN PAPER PANEL --}}
        <section class="classes-paper-panel">

            {{-- PANEL HEADER --}}
            <div class="classes-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div class="panel-title-content">
                        <span class="panel-eyebrow">ACADEMIC RECORDS</span>
                        <h3>All Classes</h3>
                        <p>
                            View and manage school classes and academic sections.
                        </p>
                    </div>
                </div>

                <div class="classes-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalClasses }}</strong>
                    {{ $totalClasses === 1 ? 'Class' : 'Classes' }}
                </div>
            </div>

            {{-- CLASSES TABLE --}}
            @if($classes->count())

                <div class="classes-table-wrapper">
                    <table class="classes-table">
                        <thead>
                            <tr>
                                <th class="number-column">#</th>
                                <th>Class Details</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th class="actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($classes as $class)
                                <tr>
                                    {{-- NUMBER --}}
                                    <td>
                                        <span class="class-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- CLASS NAME --}}
                                    <td>
                                        <div class="class-info">
                                            <div class="class-avatar">
                                                <i class="fas fa-school"></i>
                                            </div>

                                            <div class="class-details">
                                                <div class="class-title">
                                                    {{ $class->class_name }}
                                                </div>

                                                <div class="class-subtitle">
                                                    <i class="fas fa-book-open"></i>
                                                    School Class
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- SECTION --}}
                                    <td>
                                        @if($class->section)
                                            <span class="section-badge">
                                                <i class="fas fa-layer-group"></i>
                                                {{ $class->section }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- ACADEMIC YEAR --}}
                                    <td>
                                        @if($class->academic_year)
                                            <span class="year-badge">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $class->academic_year }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($class->status)
                                            <span class="class-status-badge active">
                                                <span class="status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="class-status-badge inactive">
                                                <span class="status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="class-actions">
                                            <a
                                                href="{{ route('admin.classes.show', $class->id) }}"
                                                class="class-action-btn view"
                                                title="View Class"
                                                aria-label="View Class"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a
                                                href="{{ route('admin.classes.edit', $class->id) }}"
                                                class="class-action-btn edit"
                                                title="Edit Class"
                                                aria-label="Edit Class"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form
                                                action="{{ route('admin.classes.destroy', $class->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this class?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="class-action-btn delete"
                                                    title="Delete Class"
                                                    aria-label="Delete Class"
                                                >
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

                {{-- TABLE FOOTER --}}
                <div class="classes-card-footer">
                    <div class="footer-records">
                        <i class="fas fa-file-alt"></i>
                        Showing
                        <strong>{{ $totalClasses }}</strong>
                        {{ $totalClasses === 1 ? 'class' : 'classes' }}
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Class records loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="classes-empty-state">
                    <div class="empty-state-decoration"></div>

                    <div class="empty-state-icon">
                        <i class="fas fa-school"></i>
                    </div>

                    <span class="empty-state-eyebrow">ACADEMIC DIRECTORY</span>

                    <h4>No Classes Found</h4>

                    <p>
                        No school classes have been registered yet.
                        Start by adding your first class to organise
                        your school's academic structure.
                    </p>

                    <a href="{{ route('admin.classes.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-plus"></i>
                        Add First Class
                        <i class="fas fa-arrow-right button-arrow"></i>
                    </a>
                </div>

            @endif
        </section>

        {{-- PAGE FOOTNOTE --}}
        <div class="classes-page-footnote">
            <span>
                <i class="fas fa-book-open"></i>
                SCHOOL DIARY
            </span>
            <span class="footnote-divider"></span>
            <span>Academic Structure Management</span>
        </div>

    </div>
</div>

<style>

/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   CLASSES & SECTIONS — COMPLETE CSS
   Warm paper • Forest green • Cream • Earthy accents
========================================================= */

.school-classes-page {
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

.school-classes-page *,
.school-classes-page *::before,
.school-classes-page *::after {
    box-sizing: border-box;
}

.school-classes-page a,
.school-classes-page button {
    transition: all .2s ease;
}

.school-classes-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* =========================================================
   HERO HEADER
========================================================= */

.school-classes-page .classes-hero {
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

.school-classes-page .classes-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 32px;
    width: 2px;
    background: rgba(184, 91, 62, .30);
    content: "";
}

.school-classes-page .classes-hero-content {
    display: flex;
    align-items: center;
    gap: 19px;
    min-width: 0;
    padding: 32px 28px 32px 55px;
}

.school-classes-page .classes-hero-icon {
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

.school-classes-page .classes-hero-text {
    min-width: 0;
}

.school-classes-page .classes-eyebrow {
    display: block;
    margin-bottom: 9px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.school-classes-page .classes-hero-text h2 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 30px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.5px;
}

.school-classes-page .classes-hero-text p {
    max-width: 510px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.school-classes-page .classes-hero-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

/* Hero right side */

.school-classes-page .classes-hero-actions {
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

.school-classes-page .classes-hero-actions::before,
.school-classes-page .classes-hero-actions::after {
    position: absolute;
    width: 180px;
    height: 180px;
    border: 1px solid rgba(241, 234, 216, .13);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.school-classes-page .classes-hero-actions::before {
    top: -110px;
    right: -55px;
}

.school-classes-page .classes-hero-actions::after {
    right: -65px;
    bottom: -130px;
    width: 230px;
    height: 230px;
}

.school-classes-page .classes-handwritten {
    position: relative;
    z-index: 1;
    color: var(--cream);
    font-family: var(--hand, cursive);
    font-size: 25px;
    font-weight: 600;
}

.school-classes-page .classes-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 290px;
    margin: 0;
    color: #d0d9ce;
    font-size: 12px;
    line-height: 1.8;
}

/* =========================================================
   BUTTON DESIGN
========================================================= */

.school-classes-page .diary-primary-btn {
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

.school-classes-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: var(--paper);
    background: var(--ink-hover);
    box-shadow: 0 6px 15px rgba(29, 48, 43, .14);
}

.school-classes-page .classes-hero-actions .diary-primary-btn {
    position: relative;
    z-index: 1;
    margin-top: 2px;
    border-color: var(--cream);
    color: var(--ink);
    background: var(--cream);
}

.school-classes-page .classes-hero-actions .diary-primary-btn:hover {
    border-color: var(--paper);
    color: var(--ink-dark);
    background: var(--paper);
}

.school-classes-page .button-arrow {
    margin-left: 3px;
    font-size: 10px;
}

/* =========================================================
   SUCCESS ALERT
========================================================= */

.school-classes-page .classes-success-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #cfe4d2;
    border-radius: 9px;
    color: #347849;
    background: #f2f8ef;
    font-size: 12px;
    font-weight: 700;
}

.school-classes-page .success-icon {
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

/* =========================================================
   KPI CARDS — COMPLETE REDESIGN
   Paper cards with coloured top tabs, icon tiles,
   large serif figures and bottom ruled captions.
========================================================= */

.school-classes-page .classes-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin: 40px 0 27px;
}

.school-classes-page .classes-kpi {
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

/* Small coloured folder tab */

.school-classes-page .classes-kpi::before {
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

/* Different colour for every card */

.school-classes-page .classes-kpi.blue {
    --kpi-color: #2b5d8a;
    --kpi-tint: #edf3f8;
    --kpi-border: #d7e2e9;
}

.school-classes-page .classes-kpi.blue::before {
    content: "DIRECTORY";
}

.school-classes-page .classes-kpi.green {
    --kpi-color: #3a8961;
    --kpi-tint: #edf6ed;
    --kpi-border: #d0e5d2;
}

.school-classes-page .classes-kpi.green::before {
    content: "ACTIVE";
}

.school-classes-page .classes-kpi.red {
    --kpi-color: #bd5148;
    --kpi-tint: #fff0ec;
    --kpi-border: #efd3cb;
}

.school-classes-page .classes-kpi.red::before {
    content: "INACTIVE";
}

.school-classes-page .classes-kpi.orange {
    --kpi-color: #a87543;
    --kpi-tint: #faf3e6;
    --kpi-border: #e8dcc5;
}

.school-classes-page .classes-kpi.orange::before {
    content: "SECTIONS";
}

/* Hover treatment */

.school-classes-page .classes-kpi:hover {
    transform: translateY(-4px);
    border-color: var(--kpi-border);
    box-shadow: 0 12px 25px rgba(38, 59, 53, .09);
}

/* Label and icon row */

.school-classes-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.school-classes-page .kpi-label {
    color: #68756b;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.5;
}

.school-classes-page .kpi-icon {
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

/* Large statistic */

.school-classes-page .kpi-value {
    margin-top: 1px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 39px;
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -.6px;
    overflow-wrap: anywhere;
}

/* Caption with ruled separator */

.school-classes-page .kpi-caption {
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

.school-classes-page .kpi-caption-icon {
    flex: 0 0 auto;
    padding-top: 2px;
    color: var(--kpi-color);
    font-size: 11px;
}

/* =========================================================
   MAIN PAPER PANEL
========================================================= */

.school-classes-page .classes-paper-panel {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.school-classes-page .classes-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 19px 22px;
    border-bottom: 1px dashed #dcd6c7;
    background: var(--paper);
}

.school-classes-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.school-classes-page .panel-title-icon {
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

.school-classes-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 19px;
    font-weight: 700;
}

.school-classes-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.school-classes-page .panel-eyebrow {
    display: block;
    margin-bottom: 4px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.school-classes-page .classes-record-count {
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

.school-classes-page .classes-record-count i,
.school-classes-page .classes-record-count strong {
    color: var(--ink);
}

/* =========================================================
   TABLE DESIGN
========================================================= */

.school-classes-page .classes-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.school-classes-page .classes-table {
    width: 100%;
    min-width: 850px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.school-classes-page .classes-table thead th {
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

.school-classes-page .classes-table thead th:first-child,
.school-classes-page .classes-table tbody td:first-child {
    padding-left: 20px;
}

.school-classes-page .classes-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.school-classes-page .classes-table tbody tr {
    background: var(--paper);
    transition: background-color .18s ease;
}

.school-classes-page .classes-table tbody tr:hover {
    background: #faf9f2;
}

.school-classes-page .classes-table tbody tr:last-child td {
    border-bottom: none;
}

.school-classes-page .classes-table .actions-heading {
    padding-right: 20px;
    text-align: right;
}

/* Row number */

.school-classes-page .class-row-number {
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

/* Class information */

.school-classes-page .class-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 175px;
}

.school-classes-page .class-avatar {
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

.school-classes-page .class-details {
    min-width: 0;
}

.school-classes-page .class-title {
    max-width: 230px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.school-classes-page .class-subtitle {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #858b7f;
    font-size: 10px;
}

.school-classes-page .class-subtitle i {
    color: var(--wood);
}

/* Section and year badges */

.school-classes-page .section-badge,
.school-classes-page .year-badge {
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

.school-classes-page .section-badge {
    border-color: #d5e0e7;
    color: var(--blue);
    background: #f0f5f8;
}

.school-classes-page .year-badge {
    border-color: #e7dec8;
    color: #876326;
    background: #faf5e8;
}

.school-classes-page .diary-muted {
    color: #91988d;
    font-size: 12px;
}

/* Status badges */

.school-classes-page .class-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 10px;
    border: 1px solid;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.school-classes-page .status-dot {
    flex: 0 0 6px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.school-classes-page .class-status-badge.active {
    border-color: #cfe3d2;
    color: #347849;
    background: #eff7ed;
}

.school-classes-page .class-status-badge.active .status-dot {
    background: #3a8961;
}

.school-classes-page .class-status-badge.inactive {
    border-color: #ebd2ca;
    color: #bd5148;
    background: #fff2ee;
}

.school-classes-page .class-status-badge.inactive .status-dot {
    background: #bd5148;
}

/* =========================================================
   ACTION BUTTONS
========================================================= */

.school-classes-page .class-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.school-classes-page .class-actions form {
    display: inline-flex;
    margin: 0;
}

.school-classes-page .class-action-btn {
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

.school-classes-page .class-action-btn:hover {
    transform: translateY(-2px);
}

.school-classes-page .class-action-btn.view:hover {
    border-color: #c4d6df;
    color: var(--blue);
    background: #f0f5f8;
}

.school-classes-page .class-action-btn.edit:hover {
    border-color: #e7d4ae;
    color: #a87528;
    background: #faf4e6;
}

.school-classes-page .class-action-btn.delete:hover {
    border-color: #edcbc4;
    color: var(--red);
    background: #fff1ed;
}

.school-classes-page .diary-primary-btn:focus-visible,
.school-classes-page .class-action-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .35);
    outline-offset: 3px;
}

/* =========================================================
   TABLE FOOTER
========================================================= */

.school-classes-page .classes-card-footer {
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

.school-classes-page .classes-card-footer strong {
    color: var(--ink);
}

.school-classes-page .footer-records {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.school-classes-page .footer-records > i {
    color: var(--wood);
}

.school-classes-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68766d;
    font-size: 10px;
}

.school-classes-page .footer-status i {
    color: var(--green);
}

/* =========================================================
   EMPTY STATE
========================================================= */

.school-classes-page .classes-empty-state {
    position: relative;
    overflow: hidden;
    padding: 62px 22px 66px;
    text-align: center;
}

.school-classes-page .empty-state-icon {
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

.school-classes-page .empty-state-eyebrow {
    display: block;
    margin-bottom: 8px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.school-classes-page .classes-empty-state h4 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 23px;
    font-weight: 700;
}

.school-classes-page .classes-empty-state p {
    max-width: 440px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* =========================================================
   PAGE FOOTNOTE
========================================================= */

.school-classes-page .classes-page-footnote {
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

.school-classes-page .classes-page-footnote > span:first-child {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--ink);
    font-weight: 800;
    letter-spacing: 1px;
}

.school-classes-page .classes-page-footnote > span:first-child i {
    color: var(--wood);
}

.school-classes-page .footnote-divider {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #b4ad9b;
}

/* =========================================================
   RESPONSIVE — TABLET
========================================================= */

@media (max-width: 1200px) {
    .school-classes-page .classes-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px 18px;
    }

    .school-classes-page .classes-hero {
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, .8fr);
    }
}

@media (max-width: 900px) {
    .school-classes-page .classes-hero {
        grid-template-columns: 1fr;
    }

    .school-classes-page .classes-hero-actions {
        min-height: 175px;
    }
}

/* =========================================================
   RESPONSIVE — MOBILE
========================================================= */

@media (max-width: 650px) {
    .school-classes-page .container-fluid {
        padding: 18px 13px !important;
    }

    .school-classes-page .classes-hero {
        margin-bottom: 32px;
    }

    .school-classes-page .classes-hero::before {
        left: 20px;
    }

    .school-classes-page .classes-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 14px 23px 37px;
    }

    .school-classes-page .classes-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .school-classes-page .classes-hero-text h2 {
        font-size: 24px;
    }

    .school-classes-page .classes-hero-text p {
        font-size: 12px;
    }

    .school-classes-page .classes-eyebrow {
        font-size: 9px;
        letter-spacing: .6px;
    }

    .school-classes-page .classes-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .school-classes-page .classes-hero-actions {
        min-height: 165px;
        padding: 22px 17px;
    }

    .school-classes-page .classes-handwritten {
        font-size: 23px;
    }

    .school-classes-page .classes-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 27px 12px;
        margin-top: 34px;
        margin-bottom: 23px;
    }

    .school-classes-page .classes-kpi {
        gap: 14px;
        min-height: 175px;
        padding: 19px 14px 14px;
    }

    .school-classes-page .classes-kpi::before {
        left: 11px;
        font-size: 8px;
    }

    .school-classes-page .kpi-label {
        font-size: 11px;
    }

    .school-classes-page .kpi-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
        border-radius: 9px;
        font-size: 14px;
    }

    .school-classes-page .kpi-value {
        font-size: 32px;
    }

    .school-classes-page .kpi-caption {
        font-size: 9px;
        line-height: 1.6;
    }

    .school-classes-page .classes-panel-header {
        align-items: flex-start;
        gap: 10px;
        padding: 15px 13px;
    }

    .school-classes-page .panel-title-wrap {
        gap: 9px;
    }

    .school-classes-page .panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
        font-size: 13px;
    }

    .school-classes-page .panel-title-wrap h3 {
        font-size: 17px;
    }

    .school-classes-page .panel-title-wrap p {
        font-size: 10px;
    }

    .school-classes-page .classes-record-count {
        gap: 5px;
        padding: 6px 8px;
        font-size: 10px;
    }

    .school-classes-page .classes-table {
        min-width: 800px;
    }

    .school-classes-page .classes-card-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
        padding: 13px 15px;
    }

    .school-classes-page .classes-empty-state {
        padding: 48px 17px 52px;
    }
}

@media (max-width: 380px) {
    .school-classes-page .classes-kpis {
        grid-template-columns: 1fr;
        gap: 27px;
    }

    .school-classes-page .classes-kpi {
        min-height: 165px;
        padding: 20px 18px 16px;
    }

    .school-classes-page .kpi-value {
        font-size: 35px;
    }

    .school-classes-page .classes-panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .school-classes-page .classes-record-count {
        align-self: flex-start;
    }

    .school-classes-page .classes-hero-text h2 {
        font-size: 22px;
    }
}

/* =========================================================
   ACCESSIBILITY
========================================================= */

@media (prefers-reduced-motion: reduce) {
    .school-classes-page *,
    .school-classes-page *::before,
    .school-classes-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

</style>

@endsection
