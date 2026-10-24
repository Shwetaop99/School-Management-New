@extends('layouts.app')

@section('title', 'Student ID Cards | Admin')
@section('page-title', 'Student ID Cards')

@section('content')

@php
    $totalCards = $idCards->total();

    $activeCards = $idCards->getCollection()
        ->where('status', 'active')
        ->count();

    $inactiveCards = $idCards->getCollection()
        ->where('status', 'inactive')
        ->count();

    $currentYear = now()->year . '-' . (now()->year + 1);

    $currentYearCards = $idCards->getCollection()
        ->where('academic_year', $currentYear)
        ->count();
@endphp

<div class="id-card-index">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="idcard-alert" role="status">
                <span class="success-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="idcard-hero">
            <div class="idcard-hero-content">
                <div class="idcard-hero-icon">
                    <i class="fas fa-id-card"></i>
                </div>

                <div class="idcard-hero-text">
                    <div class="idcard-breadcrumb">
                        <span>Student Documents</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>ID Cards</span>
                    </div>

                    <h2>Student ID Cards</h2>

                    <p>
                        Manage, view, print and generate student identity
                        cards from one place.
                    </p>

                    <div class="idcard-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Student Identity &amp; School Safety Management</span>
                    </div>
                </div>
            </div>

            <div class="idcard-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-address-card"></i>
                </div>

                <div class="hero-handwritten">Every Student. Identified.</div>

                <p>
                    Every card matters. Generate identity cards and keep
                    them linked to up-to-date student records.
                </p>

                <div class="hero-button-group">
                    <a href="{{ route('admin.id-card.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-plus"></i>
                        Generate ID Card
                    </a>

                    <a href="{{ route('admin.id-card.templates.index') }}"
                       class="diary-ghost-btn">
                        <i class="fas fa-th-large"></i>
                        Templates
                    </a>
                </div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="idcard-kpis">

            <article class="idcard-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total ID Cards</span>
                    <span class="kpi-icon">
                        <i class="fas fa-id-card"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalCards) }}</div>
                <div class="kpi-caption">All generated ID cards</div>
            </article>

            <article class="idcard-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Cards</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($activeCards) }}</div>
                <div class="kpi-caption">Active cards on this page</div>
            </article>

            <article class="idcard-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Cards</span>
                    <span class="kpi-icon">
                        <i class="fas fa-pause-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($inactiveCards) }}</div>
                <div class="kpi-caption">Inactive cards on this page</div>
            </article>

            <article class="idcard-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">{{ $currentYear }}</span>
                    <span class="kpi-icon">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($currentYearCards) }}</div>
                <div class="kpi-caption">Cards for the current year</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="idcard-paper-panel">

            <div class="idcard-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <div>
                        <h3>Generated ID Cards</h3>
                        <p>View and manage all student ID cards.</p>
                    </div>
                </div>

                <div class="idcard-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($totalCards) }}</strong>
                    Cards
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <div class="idcard-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Find ID Cards</h4>
                        <p>Search cards on this page by student, ID or card number, or filter by status.</p>
                    </div>
                </div>

                <div class="idcard-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="cardSearch">Search Cards</label>
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input id="cardSearch"
                                   type="text"
                                   placeholder="Student, ID or card number..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="statusFilter">Card Status</label>
                        <select id="statusFilter" class="diary-control">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="button"
                                id="resetCardFilters"
                                class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i> Reset
                        </button>
                    </div>

                </div>
            </div>

            {{-- LIVE FILTER SUMMARY --}}
            <div class="idcard-active-filters" id="activeFilters" style="display:none;">
                <div class="active-filter-items">
                    <span class="active-filter-label">
                        <i class="fas fa-filter"></i> Active filters
                    </span>

                    <span class="filter-chip" id="searchChip" style="display:none;">
                        <i class="fas fa-search"></i>
                        <span id="searchChipText"></span>
                    </span>

                    <span class="filter-chip" id="statusChip" style="display:none;">
                        <i class="fas fa-circle-half-stroke"></i>
                        <span id="statusChipText"></span>
                    </span>
                </div>

                <span class="filter-result-count">
                    <span id="visibleCount">0</span> result(s)
                </span>
            </div>

            {{-- RECORDS TABLE --}}
            @if($idCards->count() > 0)

                <div class="idcard-table-wrapper">
                    <table class="idcard-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Card Number</th>
                                <th>Class</th>
                                <th>Template</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th class="actions-heading">Actions</th>
                            </tr>
                        </thead>

                        <tbody id="idCardTableBody">
                            @foreach($idCards as $index => $idCard)

                                @php
                                    $student = $idCard->student;

                                    $searchText = strtolower(trim(
                                        ($student->full_name ?? '') . ' ' .
                                        ($student->student_id ?? '') . ' ' .
                                        ($idCard->card_number ?? '')
                                    ));
                                @endphp

                                <tr class="card-row"
                                    data-status="{{ $idCard->status }}"
                                    data-search="{{ $searchText }}">

                                    <td>
                                        <span class="idcard-row-number">
                                            {{ $idCards->firstItem() + $index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                @if(!empty($student?->profile_image))
                                                    <img src="{{ $student->profile_image }}"
                                                         alt="{{ $student->full_name }}">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>

                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $student->full_name ?? 'Student' }}
                                                </div>
                                                <div class="student-id">
                                                    <i class="fas fa-phone"></i>
                                                    {{ $student->phone ?? 'No phone number' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if(!empty($student->student_id))
                                            <span class="idcard-id-chip">
                                                <i class="fas fa-id-badge"></i>
                                                {{ $student->student_id }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="idcard-number">
                                            {{ $idCard->card_number }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="idcard-class-info">
                                            <strong>{{ $student->class ?? '—' }}</strong>

                                            @if(!empty($student->section))
                                                <span>Section {{ $student->section }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <span class="idcard-template template-{{ strtolower($idCard->template) }}">
                                            <i class="fas fa-columns"></i>
                                            {{ ucfirst(str_replace('_', ' ', $idCard->template)) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($idCard->academic_year)
                                            <div class="idcard-date">
                                                <div class="date-primary">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $idCard->academic_year }}
                                                </div>
                                                <div class="date-caption">Academic Year</div>
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($idCard->status === 'active')
                                            <span class="idcard-status active">
                                                <span class="status-dot"></span> Active
                                            </span>
                                        @else
                                            <span class="idcard-status inactive">
                                                <span class="status-dot"></span> Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="idcard-actions">
                                            <a href="{{ route('admin.id-card.show', $idCard->id) }}"
                                               class="idcard-action-btn view"
                                               title="View ID Card"
                                               aria-label="View ID card">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.id-card.print', $idCard->id) }}"
                                               target="_blank"
                                               class="idcard-action-btn print"
                                               title="Print ID Card"
                                               aria-label="Print ID card">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            <form action="{{ route('admin.id-card.destroy', $idCard->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this ID card?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="idcard-action-btn delete"
                                                        title="Delete ID Card"
                                                        aria-label="Delete ID card">
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

                {{-- LIVE FILTER EMPTY STATE --}}
                <div class="idcard-empty-state" id="filterNoResults" style="display:none;">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>

                    <h4>No Matching ID Cards</h4>
                    <p>
                        No ID cards match your current search or selected
                        status. Try a different student name, Student ID or
                        card number.
                    </p>
                    <button type="button"
                            id="clearNoResults"
                            class="diary-primary-btn">
                        <i class="fas fa-rotate-left"></i> Clear Filters
                    </button>
                </div>

                {{-- PAGINATION --}}
                @if($idCards->hasPages())
                    <div class="idcard-pagination">
                        <div class="pagination-info">
                            Showing <strong>{{ $idCards->firstItem() }}</strong>
                            to <strong>{{ $idCards->lastItem() }}</strong>
                            of <strong>{{ $idCards->total() }}</strong> cards
                        </div>

                        <div class="pagination-links">
                            {{ $idCards->links() }}
                        </div>
                    </div>
                @endif

                {{-- INFORMATION BAR --}}
                <div class="idcard-info-bar">
                    <span class="idcard-info-icon">
                        <i class="fas fa-lightbulb"></i>
                    </span>

                    <div>
                        <h6>ID Card Management</h6>
                        <p>
                            Student information is linked directly to the student
                            record. Changes made to the student profile will
                            automatically be reflected when the card is viewed again.
                        </p>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="idcard-card-footer">
                    <div>
                        Showing <strong>{{ $idCards->count() }}</strong>
                        card(s) on this page
                    </div>
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="idcard-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-id-card"></i>
                    </div>

                    <h4>No ID Cards Generated</h4>
                    <p>
                        Start by searching for a student and generating
                        their ID card.
                    </p>
                    <a href="{{ route('admin.id-card.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-plus"></i> Generate First ID Card
                    </a>
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* ==========================================================
   STUDENT ID CARDS — SCHOOL DIARY DESIGN SYSTEM
   Premium editorial dashboard | Responsive | Accessible
========================================================== */

.id-card-index {
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

.id-card-index *,
.id-card-index *::before,
.id-card-index *::after {
    box-sizing: border-box;
}

.id-card-index a,
.id-card-index button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.id-card-index .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.idcard-hero,
.idcard-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.idcard-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.idcard-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.idcard-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.idcard-hero-icon {
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

.idcard-hero-text {
    min-width: 0;
}

.idcard-breadcrumb {
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

.idcard-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.idcard-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.idcard-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.idcard-hero-note {
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

.idcard-hero-note i {
    font-size: 13px;
}

.idcard-hero-actions {
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

.idcard-hero-actions::before,
.idcard-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.idcard-hero-actions::before {
    top: -115px;
    right: -60px;
}

.idcard-hero-actions::after {
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

.idcard-hero-actions p {
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
    cursor: pointer;
}

.idcard-hero-actions .diary-primary-btn {
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

.idcard-hero-actions .diary-primary-btn:hover {
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

/* SUCCESS MESSAGE */

.idcard-alert {
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

.idcard-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.idcard-kpi {
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

.idcard-kpi.blue { --kpi-color: #426b8a; }
.idcard-kpi.green { --kpi-color: #43835d; }
.idcard-kpi.orange { --kpi-color: #a87543; }
.idcard-kpi.red { --kpi-color: #b95b50; }

.idcard-kpi::before {
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

.idcard-kpi.blue::before { content: "CARDS"; }
.idcard-kpi.green::before { content: "ACTIVE"; }
.idcard-kpi.orange::before { content: "INACTIVE"; }
.idcard-kpi.red::before { content: "THIS YEAR"; }

.idcard-kpi:hover {
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

.idcard-kpi .kpi-icon {
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

.idcard-paper-panel {
    overflow: hidden;
}

.idcard-panel-header {
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

.idcard-record-count {
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

.idcard-record-count i,
.idcard-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.idcard-filter-panel {
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

.idcard-filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) minmax(160px, .9fr) auto;
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

.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* ACTIVE FILTERS */

.idcard-active-filters {
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

/* RECORDS TABLE */

.idcard-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.idcard-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.idcard-table thead th {
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

.idcard-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.idcard-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.idcard-table tbody tr:hover {
    background: #f8f8f1;
}

.idcard-table tbody tr:last-child td {
    border-bottom: none;
}

.idcard-table .actions-heading {
    text-align: right;
}

.idcard-row-number {
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

.idcard-id-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #dce4d8;
    border-radius: 7px;
    color: #477858;
    background: #edf6e9;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-id-chip i {
    color: var(--green);
}

.idcard-number {
    color: #34443a;
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-class-info {
    min-width: 90px;
}

.idcard-class-info strong {
    display: block;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
}

.idcard-class-info span {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

/* TEMPLATE */

.idcard-template {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid #e4e0d4;
    border-radius: 7px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-template.template-modern {
    border-color: #d9e2e9;
    color: #426b8a;
    background: #edf4f8;
}

.idcard-template.template-compact {
    border-color: #dce6da;
    color: #527f70;
    background: #edf3e9;
}

.idcard-template.template-front_back {
    border-color: #ebdfcc;
    color: #a87543;
    background: #faf2e7;
}

/* DATE */

.idcard-date {
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

.idcard-status {
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

.idcard-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.idcard-status.inactive {
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

.idcard-status.active .status-dot {
    background: #43835d;
}

.idcard-status.inactive .status-dot {
    background: #b95b50;
}

/* ACTION BUTTONS */

.idcard-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.idcard-actions form {
    display: inline-flex;
    margin: 0;
}

.idcard-action-btn {
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

.idcard-action-btn:hover {
    transform: translateY(-2px);
}

.idcard-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.idcard-action-btn.print:hover {
    border-color: #d4e5d0;
    color: #43835d;
    background: #edf6e9;
}

.idcard-action-btn.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* PAGINATION */

.idcard-pagination {
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

/* INFORMATION BAR */

.idcard-info-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px 24px;
    border-top: 1px solid var(--line);
    background: #fffefa;
}

.idcard-info-icon {
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
    font-size: 16px;
}

.idcard-info-bar h6 {
    margin: 0 0 4px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 15px;
    font-weight: 700;
}

.idcard-info-bar p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* FOOTER */

.idcard-card-footer {
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

.idcard-card-footer strong {
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

.idcard-empty-state {
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

.idcard-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.idcard-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .idcard-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .idcard-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .idcard-hero {
        grid-template-columns: 1fr;
    }

    .idcard-hero-actions {
        min-height: 220px;
    }

    .idcard-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .id-card-index .container-fluid {
        padding: 18px 14px !important;
    }

    .idcard-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .idcard-hero::before {
        left: 18px;
    }

    .idcard-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .idcard-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .idcard-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .idcard-hero-text h2 {
        font-size: 24px;
    }

    .idcard-hero-text p {
        font-size: 11px;
    }

    .idcard-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .idcard-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .idcard-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .idcard-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .idcard-kpi .kpi-icon {
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

    .idcard-panel-header {
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

    .idcard-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .idcard-filter-panel {
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

    .idcard-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .idcard-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .idcard-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .idcard-info-bar {
        align-items: flex-start;
        padding: 16px 15px;
    }

    .idcard-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .idcard-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .idcard-kpis {
        grid-template-columns: 1fr;
    }

    .idcard-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .idcard-record-count {
        margin-left: 46px;
    }

    .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

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

.id-card-index a:focus-visible,
.id-card-index button:focus-visible,
.id-card-index input:focus-visible,
.id-card-index select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .id-card-index *,
    .id-card-index *::before,
    .id-card-index *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

{{-- SEARCH / FILTER SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('cardSearch');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetCardFilters');
    const clearNoResults = document.getElementById('clearNoResults');
    const rows = document.querySelectorAll('.card-row');

    const activeFilters = document.getElementById('activeFilters');
    const searchChip = document.getElementById('searchChip');
    const searchChipText = document.getElementById('searchChipText');
    const statusChip = document.getElementById('statusChip');
    const statusChipText = document.getElementById('statusChipText');
    const visibleCountEl = document.getElementById('visibleCount');

    const tableWrapper = document.querySelector('.idcard-table-wrapper');
    const noResults = document.getElementById('filterNoResults');

    if (!searchInput || !statusFilter) {
        return;
    }

    function filterCards() {

        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value.toLowerCase().trim();

        let visibleCount = 0;

        rows.forEach(function (row) {

            const rowSearch = (row.dataset.search || '').toLowerCase();
            const rowStatus = (row.dataset.status || '').toLowerCase();

            const matchesSearch = !search || rowSearch.includes(search);
            const matchesStatus = !status || rowStatus === status;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        /* Active filter chips */
        const hasFilters = Boolean(search || status);

        if (activeFilters) {
            activeFilters.style.display = hasFilters ? 'flex' : 'none';
        }

        if (searchChip && searchChipText) {
            searchChip.style.display = search ? 'inline-flex' : 'none';
            searchChipText.textContent = searchInput.value.trim();
        }

        if (statusChip && statusChipText) {
            statusChip.style.display = status ? 'inline-flex' : 'none';
            statusChipText.textContent =
                status.charAt(0).toUpperCase() + status.slice(1);
        }

        if (visibleCountEl) {
            visibleCountEl.textContent = visibleCount;
        }

        /* No results state */
        const showNoResults = visibleCount === 0 && rows.length > 0;

        if (noResults) {
            noResults.style.display = showNoResults ? 'block' : 'none';
        }

        if (tableWrapper) {
            tableWrapper.style.display = showNoResults ? 'none' : '';
        }
    }

    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = '';
        filterCards();
        searchInput.focus();
    }

    searchInput.addEventListener('input', filterCards);
    statusFilter.addEventListener('change', filterCards);

    if (resetButton) {
        resetButton.addEventListener('click', resetFilters);
    }

    if (clearNoResults) {
        clearNoResults.addEventListener('click', resetFilters);
    }
});
</script>

@endsection