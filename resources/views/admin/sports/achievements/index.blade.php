@extends('layouts.app')

@section('title', 'Sports Achievements | Admin')
@section('page-title', 'Sports Achievements')

@section('content')

<div class="sports-achievements-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="sports-success-alert" role="status">
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

        {{-- SCHOOL DIARY HERO --}}
        <section class="achievement-hero">
            <div class="achievement-hero-content">
                <div class="achievement-hero-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div class="achievement-hero-text">
                    <div class="achievement-breadcrumb">
                        SPORTS <span>/</span> ACHIEVEMENTS
                    </div>

                    <h2>Sports Achievements</h2>

                    <p>
                        Recognise student excellence, competitions and sporting
                        milestones from one place.
                    </p>

                    <div class="achievement-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Sports &amp; Activities Management</span>
                    </div>
                </div>
            </div>

            <div class="achievement-hero-actions">
                <div class="hero-handwritten">Play. Achieve. Inspire.</div>

                <p>
                    Celebrate every milestone and recognise the dedication
                    behind every sporting achievement.
                </p>

                <a href="{{ route('admin.sports.achievements.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Achievement
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="achievement-kpis">

            <article class="achievement-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Achievements</span>
                    <span class="kpi-icon">
                        <i class="fas fa-trophy"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ $achievements->total() }}
                </div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    All recorded achievements
                </div>
            </article>

            <article class="achievement-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Achievements</span>
                    <span class="kpi-icon">
                        <i class="fas fa-award"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ \App\Models\Sports\Achievements\Achievement::where('status', 'active')->count() }}
                </div>

                <div class="kpi-caption">
                    <i class="fas fa-circle-check"></i>
                    Currently active records
                </div>
            </article>

            <article class="achievement-kpi cyan">
                <div class="kpi-top">
                    <span class="kpi-label">Sports Represented</span>
                    <span class="kpi-icon">
                        <i class="fas fa-running"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ $sports->count() }}
                </div>

                <div class="kpi-caption">
                    <i class="fas fa-futbol"></i>
                    Different sports recorded
                </div>
            </article>

            <article class="achievement-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Achievements</span>
                    <span class="kpi-icon">
                        <i class="fas fa-ban"></i>
                    </span>
                </div>

                <div class="kpi-value">
                    {{ \App\Models\Sports\Achievements\Achievement::where('status', 'inactive')->count() }}
                </div>

                <div class="kpi-caption">
                    <i class="fas fa-box-archive"></i>
                    Archived or inactive records
                </div>
            </article>

        </section>

        {{-- SEARCH AND FILTERS --}}
        <section class="achievement-filter-card">

            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>

                <div class="filter-heading-copy">
                    <div class="section-eyebrow">RECORD MANAGEMENT</div>
                    <h3>Search &amp; Filter Achievements</h3>
                    <p>
                        Find records by student, sport, academic year or status.
                    </p>
                </div>

                @if(request()->hasAny(['search', 'status', 'sport_name', 'academic_year']))
                    <a href="{{ route('admin.sports.achievements.index') }}"
                       class="diary-reset-btn reset-heading-btn">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif
            </div>

            <form method="GET"
                  action="{{ route('admin.sports.achievements.index') }}">

                <div class="achievement-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="achievement-search">Search Records</label>

                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>

                            <input
                                type="text"
                                name="search"
                                id="achievement-search"
                                value="{{ request('search') }}"
                                placeholder="Student, achievement, competition..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="sport_name">Sport</label>

                        <select name="sport_name"
                                id="sport_name"
                                class="diary-control">
                            <option value="">All Sports</option>

                            @foreach($sports as $sport)
                                <option value="{{ $sport }}"
                                    @selected(request('sport_name') == $sport)>
                                    {{ $sport }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                        <label for="status">Record Status</label>

                        <select name="status"
                                id="status"
                                class="diary-control">
                            <option value="">All Statuses</option>

                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.sports.achievements.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny(['search', 'status', 'sport_name', 'academic_year']))
                <div class="achievement-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-sliders-h"></i>
                            Active filters
                        </span>

                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request('sport_name'))
                            <span class="filter-chip">
                                <i class="fas fa-running"></i>
                                {{ request('sport_name') }}
                            </span>
                        @endif

                        @if(request('academic_year'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar"></i>
                                {{ request('academic_year') }}
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
                        {{ $achievements->total() }} result(s)
                    </span>
                </div>
            @endif

        </section>

        {{-- ACHIEVEMENT RECORDS --}}
        <section class="achievement-table-card">

            <div class="achievement-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">SCHOOL SPORTS REGISTER</div>
                        <h3>Achievement Records</h3>
                        <p>View and manage all school sports achievements.</p>
                    </div>
                </div>

                <div class="achievement-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $achievements->total() }}</strong>
                    Records
                </div>
            </div>

            <div class="achievement-table-wrapper">
                <table class="achievement-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Achievement</th>
                            <th>Student</th>
                            <th>Sport</th>
                            <th>Competition</th>
                            <th>Position</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($achievements as $achievement)
                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $achievements->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="achievement-info">
                                        <div class="achievement-mini-icon">
                                            <i class="fas fa-trophy"></i>
                                        </div>

                                        <div class="achievement-details">
                                            <div class="achievement-name">
                                                {{ $achievement->title }}
                                            </div>

                                            @if($achievement->achievement_type)
                                                <div class="achievement-type">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $achievement->achievement_type }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($achievement->student_name)
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                {{ strtoupper(substr($achievement->student_name, 0, 1)) }}
                                            </div>

                                            <span>{{ $achievement->student_name }}</span>
                                        </div>
                                    @else
                                        <span class="diary-muted">Not specified</span>
                                    @endif
                                </td>

                                <td>
                                    @if($achievement->sport_name)
                                        <span class="sport-badge">
                                            <i class="fas fa-running"></i>
                                            {{ $achievement->sport_name }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($achievement->competition_name)
                                        <div class="competition-info">
                                            <span class="venue-icon">
                                                <i class="fas fa-flag"></i>
                                            </span>

                                            <span class="competition-name">
                                                {{ $achievement->competition_name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($achievement->position)
                                        <span class="position-badge">
                                            <i class="fas fa-award"></i>
                                            {{ $achievement->position }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($achievement->achievement_date)
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $achievement->achievement_date->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($achievement->status === 'active')
                                        <span class="status-badge active">
                                            <span class="status-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="achievement-actions">
                                        <a
                                            href="{{ route('admin.sports.achievements.show', $achievement) }}"
                                            class="game-action-btn view"
                                            title="View Achievement"
                                            aria-label="View achievement">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.sports.achievements.edit', $achievement) }}"
                                            class="game-action-btn edit"
                                            title="Edit Achievement"
                                            aria-label="Edit achievement">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.sports.achievements.destroy', $achievement) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this achievement?');">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="game-action-btn delete"
                                                title="Delete Achievement"
                                                aria-label="Delete achievement">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="achievement-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-trophy"></i>
                                        </div>

                                        <h4>No Achievements Found</h4>

                                        <p>
                                            No achievement records match your
                                            current search or filters.
                                        </p>

                                        <a
                                            href="{{ route('admin.sports.achievements.create') }}"
                                            class="diary-primary-btn">
                                            <i class="fas fa-plus"></i>
                                            Add Achievement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($achievements->hasPages())
                <div class="achievement-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $achievements->firstItem() }}</strong>
                        to
                        <strong>{{ $achievements->lastItem() }}</strong>
                        of
                        <strong>{{ $achievements->total() }}</strong>
                        records
                    </div>

                    <div>
                        {{ $achievements->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif

            {{-- FOOTER --}}
            @if($achievements->total() > 0)
                <div class="achievement-card-footer">
                    <div>
                        Showing
                        <strong>{{ $achievements->count() }}</strong>
                        record(s) on this page
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>
            @endif

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   SPORTS ACHIEVEMENTS — INDEX PAGE
========================================================= */

.sports-achievements-page {
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

.sports-achievements-page *,
.sports-achievements-page *::before,
.sports-achievements-page *::after {
    box-sizing: border-box;
}

.sports-achievements-page a,
.sports-achievements-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.sports-achievements-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.sports-achievements-page .achievement-hero,
.sports-achievements-page .achievement-filter-card,
.sports-achievements-page .achievement-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.achievement-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.achievement-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.achievement-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.achievement-hero-icon {
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

.achievement-hero-text {
    min-width: 0;
}

.achievement-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.achievement-breadcrumb span {
    color: #b8b09e;
}

.achievement-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.achievement-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.achievement-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.achievement-hero-actions {
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

.hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.achievement-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* LOCKED BUTTON STYLES */

.diary-primary-btn {
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

.diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.achievement-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.achievement-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* SUCCESS ALERT */

.sports-success-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #cfe3d1;
    border-radius: 9px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    font-weight: 700;
}

.success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #dcefd9;
}

.alert-close {
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

.alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.achievement-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.achievement-kpi {
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

/* Folder tabs from the locked design */

.achievement-kpi::before {
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

.achievement-kpi.blue { --kpi-color: #2b5d8a; }
.achievement-kpi.green { --kpi-color: #3a8060; }
.achievement-kpi.cyan { --kpi-color: #527f79; }
.achievement-kpi.red { --kpi-color: #bd5148; }

.achievement-kpi.blue::before { content: "Records"; }
.achievement-kpi.green::before { content: "Active"; }
.achievement-kpi.cyan::before { content: "Sports"; }
.achievement-kpi.red::before { content: "Inactive"; }

.achievement-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.achievement-kpi .kpi-icon {
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

.kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.kpi-caption {
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

.kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* FILTER PANEL */

.achievement-filter-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 19px 20px 16px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fffefa;
}

.filter-heading-icon,
.panel-title-icon {
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

.section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.filter-heading h3,
.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.filter-heading p,
.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.reset-heading-btn {
    margin-left: auto;
}

.achievement-filter-card form {
    padding: 19px 20px 20px;
}

.achievement-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(200px, 2fr)
        minmax(130px, 1fr)
        minmax(135px, 1fr)
        minmax(130px, 1fr)
        auto;
    align-items: end;
    gap: 12px;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.diary-control,
.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 41px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.diary-control {
    padding: 0 10px;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 11px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #929b8f;
    font-size: 12px;
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
    color: #929b8f;
}

.diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.diary-apply-btn,
.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 41px;
    padding: 0 13px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.diary-apply-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}

.diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
}

.diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: #fffefa;
}

.diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}

/* ACTIVE FILTERS */

.achievement-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 11px 20px;
    border-top: 1px solid #eeece3;
    background: #faf9f4;
}

.active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}

.active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.active-filter-label i {
    color: var(--green);
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 220px;
    padding: 5px 9px;
    overflow: hidden;
    border: 1px solid #e6dfc9;
    border-radius: 6px;
    color: #765e32;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.filter-chip i {
    color: var(--amber);
}

.filter-result-count {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* RECORDS PANEL */

.achievement-table-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.achievement-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.achievement-record-count {
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

.achievement-record-count i,
.achievement-record-count strong {
    color: var(--ink);
}

/* TABLE */

.achievement-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.achievement-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: collapse;
}

.achievement-table thead th {
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

.achievement-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.achievement-table tbody tr {
    transition: background .18s ease;
}

.achievement-table tbody tr:hover {
    background: #faf9f2;
}

.achievement-table tbody tr:last-child td {
    border-bottom: none;
}

.achievement-table .actions-heading {
    text-align: right;
}

.record-number {
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

/* ACHIEVEMENT */

.achievement-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.achievement-mini-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #e6dfce;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.achievement-details {
    min-width: 0;
}

.achievement-name {
    max-width: 210px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.achievement-type {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}

.achievement-type i {
    color: var(--amber);
}

/* STUDENT */

.student-info {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 130px;
}

.student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid #d8e4d6;
    border-radius: 50%;
    color: #416c50;
    background: #eaf1e6;
    font-size: 11px;
    font-weight: 800;
}

.student-info > span {
    color: var(--ink);
    font-size: 11px;
    font-weight: 600;
}

/* SPORT AND POSITION */

.sport-badge,
.position-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.sport-badge {
    border: 1px solid #d7e2e9;
    color: var(--blue);
    background: #f0f5f8;
}

.position-badge {
    border: 1px solid #e6dfc9;
    color: #8b641d;
    background: #faf5e7;
}

/* COMPETITION */

.competition-info {
    display: flex;
    align-items: center;
    gap: 7px;
    max-width: 180px;
    color: #647168;
    font-size: 11px;
}

.venue-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 25px;
    width: 25px;
    height: 25px;
    border-radius: 6px;
    color: var(--red);
    background: #fff1ed;
    font-size: 10px;
}

.competition-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* DATE */

.event-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.event-date i {
    color: var(--blue);
}

/* STATUS */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.status-badge.active {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.status-badge.active .status-dot {
    background: #3a8060;
}

.status-badge.inactive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.status-badge.inactive .status-dot {
    background: #bd5148;
}

.diary-muted {
    color: #929a8e;
    font-size: 11px;
}

/* ACTION BUTTONS */

.achievement-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.achievement-actions form {
    display: inline-flex;
    margin: 0;
}

.game-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none !important;
    cursor: pointer;
}

.game-action-btn:hover {
    transform: translateY(-2px);
}

.game-action-btn.view:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}

.game-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.game-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* EMPTY STATE */

.achievement-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-state-icon {
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

.achievement-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.achievement-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* PAGINATION */

.achievement-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}

.pagination-info {
    color: var(--muted);
    font-size: 11px;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.achievement-pagination nav {
    margin: 0;
}

.achievement-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.achievement-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    min-height: 32px;
    padding: 5px 9px;
    border: 1px solid var(--line);
    border-radius: 7px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 12px;
    box-shadow: none;
}

.achievement-pagination .page-link:hover {
    border-color: #bfcbbd;
    color: var(--ink-hover);
    background: #f5f4eb;
}

.achievement-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.achievement-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* FOOTER */

.achievement-card-footer {
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

.achievement-card-footer strong {
    color: var(--ink);
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.footer-status i {
    color: var(--green);
}

/* TABLET */

@media (max-width: 1250px) {
    .achievement-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .achievement-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .achievement-hero {
        grid-template-columns: 1fr;
    }

    .achievement-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .sports-achievements-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .achievement-hero::before {
        left: 22px;
    }

    .achievement-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .achievement-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .achievement-hero-text h2 {
        font-size: 23px;
    }

    .achievement-hero-text p {
        font-size: 12px;
    }

    .achievement-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .achievement-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .achievement-kpis {
        gap: 15px;
        margin-top: 30px;
    }

    .achievement-kpi {
        padding: 20px 16px 15px;
    }

    .kpi-value {
        font-size: 30px;
    }

    .filter-heading {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }

    .filter-heading h3 {
        font-size: 16px;
    }

    .filter-heading p {
        font-size: 11px;
    }

    .reset-heading-btn {
        margin-left: 0;
    }

    .achievement-filter-card form {
        padding: 15px;
    }

    .achievement-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .achievement-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 11px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .achievement-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .panel-title-wrap p {
        font-size: 11px;
    }

    .achievement-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .achievement-table {
        min-width: 1150px;
    }

    .achievement-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .achievement-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .achievement-kpis {
        grid-template-columns: 1fr;
    }

    .achievement-panel-header {
        gap: 8px;
    }

    .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
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

.sports-achievements-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .sports-achievements-page *,
    .sports-achievements-page *::before,
    .sports-achievements-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection
