@extends('layouts.app')

@section('title', 'Notices | Admin')
@section('page-title', 'Notices')

@section('content')

@php
    $totalNotices     = $total ?? $notices->total();
    $publishedNotices = $published ?? 0;
    $draftNotices     = $draft ?? 0;
    $expiredNotices   = $expired ?? 0;
@endphp

<div class="notices-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="notices-success-alert" role="status">
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
            <div class="notices-error-alert" role="alert">
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
        <section class="notices-hero">
            <div class="notices-hero-content">
                <div class="notices-hero-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>

                <div class="notices-hero-text">
                    <div class="notices-breadcrumb">
                        ADMIN <span>/</span> NOTICES
                    </div>

                    <h2>Notice Management</h2>

                    <p>
                        Manage school announcements, notices and important
                        updates for students, staff and parents.
                    </p>

                    <div class="notices-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Announcements &amp; Communication Centre</span>
                    </div>
                </div>
            </div>

            <div class="notices-hero-actions">
                <div class="hero-handwritten">Inform. Engage. Update.</div>

                <p>
                    Keep the school community informed with
                    clear and timely notices.
                </p>

                <a href="{{ route('admin.notices.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Create Notice
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="notices-kpis">

            <article class="notices-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Notices</span>
                    <span class="kpi-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalNotices }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Registered notices
                </div>
            </article>

            <article class="notices-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Published</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $publishedNotices }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Live for the school community
                </div>
            </article>

            <article class="notices-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Drafts</span>
                    <span class="kpi-icon">
                        <i class="fas fa-pen-to-square"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $draftNotices }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-hourglass-half"></i>
                    Waiting to be published
                </div>
            </article>

            <article class="notices-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Expired</span>
                    <span class="kpi-icon">
                        <i class="fas fa-hourglass-end"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $expiredNotices }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-calendar-xmark"></i>
                    Past their end date
                </div>
            </article>

        </section>

        {{-- NOTICES TABLE --}}
        <section class="notices-table-card">

            <div class="notices-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">NOTICE BOARD</div>
                        <h3>All Notices</h3>
                        <p>Search, review, edit or remove school notices.</p>
                    </div>
                </div>

                <div class="notices-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $notices->total() }}</strong>
                    {{ $notices->total() == 1 ? 'Notice' : 'Notices' }}
                </div>
            </div>

            {{-- FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.notices.index') }}"
                  class="notices-filters">

                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search notices..."
                           aria-label="Search notices">
                </div>

                <select name="category" aria-label="Category">
                    <option value="">All categories</option>
                    @foreach (['Academic','Events','Examination','Holiday','General'] as $category)
                        <option value="{{ $category }}"
                            @selected(request('category') === $category)>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Status">
                    <option value="">All status</option>
                    @foreach (['Published','Draft','Expired'] as $status)
                        <option value="{{ $status }}"
                            @selected(request('status') === $status)>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>

                <select name="date_filter" aria-label="Date">
                    <option value="">All dates</option>
                    <option value="today" @selected(request('date_filter') === 'today')>Today</option>
                    <option value="week" @selected(request('date_filter') === 'week')>This week</option>
                    <option value="month" @selected(request('date_filter') === 'month')>This month</option>
                </select>

                <button type="submit" class="diary-primary-btn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>

                @if (request()->hasAny(['search','category','status','date_filter']))
                    <a href="{{ route('admin.notices.index') }}"
                       class="diary-secondary-btn">
                        Clear
                    </a>
                @endif
            </form>

            <div class="notices-table-wrapper">
                <table class="notices-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Notice</th>
                            <th scope="col">Category</th>
                            <th scope="col">Publish Date</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($notices as $notice)
                            @php
                                $statusKey   = strtolower($notice->status);
                                $priorityKey = strtolower($notice->priority);
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $notices->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="notice-info">
                                        <div class="notice-mini-icon">
                                            <i class="fas fa-thumbtack"></i>
                                        </div>
                                        <div class="notice-details">
                                            <div class="notice-name">
                                                {{ $notice->title }}
                                            </div>
                                            <div class="notice-description">
                                                Created by Admin
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $notice->category }}
                                    </span>
                                </td>

                                <td>
                                    <div class="date-value">
                                        {{ $notice->publish_date
                                            ? \Carbon\Carbon::parse($notice->publish_date)->format('d M Y')
                                            : '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="priority-badge {{ $priorityKey }}">
                                        <span class="priority-dot"></span>
                                        {{ $notice->priority }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusKey }}">
                                        <span class="status-dot"></span>
                                        {{ $notice->status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="notices-actions">

                                        <a href="{{ route('admin.notices.show', $notice) }}"
                                           class="game-action-btn view"
                                           title="View Notice"
                                           aria-label="View {{ $notice->title }}">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.notices.edit', $notice) }}"
                                           class="game-action-btn edit"
                                           title="Edit Notice"
                                           aria-label="Edit {{ $notice->title }}">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        @if ($notice->status === 'Published')
                                            <a href="{{ route('admin.notices.whatsapp', $notice) }}"
                                               target="_blank"
                                               rel="noopener"
                                               class="game-action-btn whatsapp"
                                               title="Share on WhatsApp"
                                               aria-label="Share {{ $notice->title }} on WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @endif

                                        <form method="POST"
                                              action="{{ route('admin.notices.destroy', $notice) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="game-action-btn delete"
                                                    title="Delete Notice"
                                                    aria-label="Delete {{ $notice->title }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="notices-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>

                                        <h4>No Notices Found</h4>

                                        <p>
                                            Try changing your search or filters,
                                            or create a new notice.
                                        </p>

                                        <div class="empty-state-actions">
                                            <a href="{{ route('admin.notices.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Create Notice
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="notices-card-footer">
                <div>
                    Showing
                    <strong>{{ $notices->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $notices->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ $notices->total() }}</strong>
                    notice(s)
                </div>

                @if ($notices->hasPages())
                    <div class="notices-pagination">
                        {{ $notices->links() }}
                    </div>
                @else
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Notice board loaded successfully
                    </div>
                @endif
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   NOTICES — INDEX PAGE
   Same tokens, cards, buttons and layout as Roles / Users / Meal Items.
   Every selector is scoped under .notices-page.
========================================================= */

.notices-page {
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

.notices-page *,
.notices-page *::before,
.notices-page *::after {
    box-sizing: border-box;
}

.notices-page a,
.notices-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.notices-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.notices-page .notices-hero,
.notices-page .notices-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.notices-page .notices-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.notices-page .notices-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.notices-page .notices-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.notices-page .notices-hero-icon {
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

.notices-page .notices-hero-text {
    min-width: 0;
}

.notices-page .notices-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.notices-page .notices-breadcrumb span {
    color: #b8b09e;
}

.notices-page .notices-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.notices-page .notices-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.notices-page .notices-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.notices-page .notices-hero-actions {
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

.notices-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.notices-page .notices-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.notices-page .diary-primary-btn {
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

.notices-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.notices-page .notices-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.notices-page .notices-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

.notices-page .diary-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--line);
    border-radius: 7px;
    color: var(--muted) !important;
    background: #fff;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.notices-page .diary-secondary-btn:hover {
    border-color: #b9b29c;
    color: var(--ink) !important;
    background: #faf9f4;
}

/* ALERTS */

.notices-page .notices-success-alert,
.notices-page .notices-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.notices-page .notices-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.notices-page .notices-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.notices-page .success-icon,
.notices-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.notices-page .success-icon { background: #dcefd9; }
.notices-page .error-icon { background: #fbe0d8; }

.notices-page .alert-close {
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

.notices-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.notices-page .notices-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.notices-page .notices-kpi {
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

.notices-page .notices-kpi::before {
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

.notices-page .notices-kpi.blue  { --kpi-color: #2b5d8a; }
.notices-page .notices-kpi.green { --kpi-color: #3a8060; }
.notices-page .notices-kpi.amber { --kpi-color: #a87543; }
.notices-page .notices-kpi.red   { --kpi-color: #bd5148; }

.notices-page .notices-kpi.blue::before  { content: "Total"; }
.notices-page .notices-kpi.green::before { content: "Published"; }
.notices-page .notices-kpi.amber::before { content: "Drafts"; }
.notices-page .notices-kpi.red::before   { content: "Expired"; }

.notices-page .notices-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.notices-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.notices-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.notices-page .notices-kpi .kpi-icon {
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

.notices-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.notices-page .kpi-caption {
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

.notices-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.notices-page .notices-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.notices-page .notices-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.notices-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.notices-page .panel-title-icon {
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

.notices-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.notices-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.notices-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.notices-page .notices-record-count {
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

.notices-page .notices-record-count i,
.notices-page .notices-record-count strong {
    color: var(--ink);
}

/* FILTERS */

.notices-page .notices-filters {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) 150px 140px 140px auto auto;
    align-items: center;
    gap: 10px;
    padding: 16px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: #faf9f4;
}

.notices-page .filter-search {
    position: relative;
}

.notices-page .filter-search i {
    position: absolute;
    top: 50%;
    left: 13px;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 12px;
    pointer-events: none;
}

.notices-page .notices-filters input,
.notices-page .notices-filters select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--line);
    border-radius: 7px;
    color: var(--text);
    background: var(--paper);
    font-family: inherit;
    font-size: 12px;
    outline: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.notices-page .notices-filters input {
    padding-left: 36px;
}

.notices-page .notices-filters input:focus,
.notices-page .notices-filters select:focus {
    border-color: var(--ink);
    box-shadow: 0 0 0 3px rgba(38, 59, 53, .1);
}

/* TABLE */

.notices-page .notices-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.notices-page .notices-table {
    width: 100%;
    min-width: 980px;
    margin: 0;
    border-collapse: collapse;
}

.notices-page .notices-table thead th {
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

.notices-page .notices-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.notices-page .notices-table tbody tr {
    transition: background .18s ease;
}

.notices-page .notices-table tbody tr:hover {
    background: #faf9f2;
}

.notices-page .notices-table tbody tr:last-child td {
    border-bottom: none;
}

.notices-page .notices-table .actions-heading {
    text-align: right;
}

.notices-page .record-number {
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

/* NOTICE CELL */

.notices-page .notice-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 240px;
}

.notices-page .notice-mini-icon {
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

.notices-page .notice-details {
    min-width: 0;
}

.notices-page .notice-name {
    max-width: 280px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.notices-page .notice-description {
    max-width: 280px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.notices-page .count-badge {
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
    white-space: nowrap;
}

.notices-page .date-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

/* PRIORITY + STATUS BADGES */

.notices-page .priority-badge,
.notices-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.notices-page .priority-dot,
.notices-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

/* status */
.notices-page .status-badge.published {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.notices-page .status-badge.published .status-dot {
    background: #3a8060;
}

.notices-page .status-badge.draft {
    border: 1px solid #e8d9c0;
    color: #a87543;
    background: #fcf7e9;
}

.notices-page .status-badge.draft .status-dot {
    background: #a87543;
}

.notices-page .status-badge.expired {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.notices-page .status-badge.expired .status-dot {
    background: #bd5148;
}

/* priority */
.notices-page .priority-badge.normal {
    border: 1px solid #e0e2d8;
    color: #667269;
    background: #f5f3e9;
}

.notices-page .priority-badge.normal .priority-dot {
    background: #8a948b;
}

.notices-page .priority-badge.important {
    border: 1px solid #e8d9c0;
    color: #a87543;
    background: #fcf7e9;
}

.notices-page .priority-badge.important .priority-dot {
    background: #a87543;
}

.notices-page .priority-badge.urgent {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.notices-page .priority-badge.urgent .priority-dot {
    background: #bd5148;
}

/* ACTION BUTTONS */

.notices-page .notices-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.notices-page .notices-actions form {
    display: inline-flex;
    margin: 0;
}

.notices-page .game-action-btn {
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

.notices-page .game-action-btn:hover {
    transform: translateY(-2px);
}

.notices-page .game-action-btn.view:hover {
    border-color: #c5d6ea;
    color: var(--blue);
    background: #f0f5f8;
}

.notices-page .game-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.notices-page .game-action-btn.whatsapp:hover {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.notices-page .game-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* EMPTY STATE */

.notices-page .notices-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.notices-page .empty-state-icon {
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

.notices-page .notices-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.notices-page .notices-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.notices-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.notices-page .notices-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.notices-page .notices-card-footer strong {
    color: var(--ink);
}

.notices-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.notices-page .footer-status i {
    color: var(--green);
}

/* PAGINATION (Laravel / Bootstrap) */

.notices-page .notices-pagination nav > div:first-child {
    display: none;
}

.notices-page .notices-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.notices-page .notices-pagination .page-link,
.notices-page .notices-pagination .pagination span,
.notices-page .notices-pagination .pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 9px;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none !important;
}

.notices-page .notices-pagination .page-item.active .page-link,
.notices-page .notices-pagination .pagination [aria-current="page"] span {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.notices-page .notices-pagination .page-item.disabled .page-link {
    opacity: .5;
}

.notices-page .notices-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1200px) {
    .notices-page .notices-filters {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .notices-page .filter-search {
        grid-column: 1 / -1;
    }

    .notices-page .notices-filters .diary-primary-btn,
    .notices-page .notices-filters .diary-secondary-btn {
        width: 100%;
    }
}

@media (max-width: 1100px) {
    .notices-page .notices-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .notices-page .notices-hero {
        grid-template-columns: 1fr;
    }

    .notices-page .notices-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .notices-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .notices-page .notices-hero::before {
        left: 22px;
    }

    .notices-page .notices-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .notices-page .notices-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .notices-page .notices-hero-text h2 {
        font-size: 23px;
    }

    .notices-page .notices-hero-text p {
        font-size: 12px;
    }

    .notices-page .notices-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .notices-page .notices-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .notices-page .notices-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .notices-page .notices-kpi {
        padding: 20px 16px 15px;
    }

    .notices-page .kpi-value {
        font-size: 30px;
    }

    .notices-page .notices-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .notices-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .notices-page .notices-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .notices-page .notices-filters {
        grid-template-columns: 1fr;
        padding: 14px 15px;
    }

    .notices-page .notices-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .notices-page .notices-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .notices-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .notices-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .notices-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.notices-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .notices-page *,
    .notices-page *::before,
    .notices-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection