@extends('layouts.app')

@section('title', 'Games & Events | Admin')
@section('page-title', 'Games & Events')

@section('content')

<div class="sports-games-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="sports-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="games-hero">
            <div class="games-hero-content">
                <div class="games-hero-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div class="games-hero-text">
                    <div class="games-breadcrumb">
                        <span>SPORTS</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>ACTIVITIES</span>
                    </div>

                    <h2>Games &amp; Events</h2>

                    <p>
                        Manage school sports games, competitions and events
                        from one place.
                    </p>

                    <div class="games-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Sports &amp; Activities Management</span>
                    </div>
                </div>
            </div>

            <div class="games-hero-actions">
                <div class="hero-quote-mark">“</div>

                <div class="hero-handwritten">Play. Compete. Achieve.</div>

                <p>
                    Every champion begins with a chance to play.
                    Build teamwork, celebrate effort and make every
                    school sporting event count.
                </p>

                <a href="{{ route('admin.sports.games.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Game / Event
                    <i class="fas fa-arrow-right hero-btn-arrow"></i>
                </a>
            </div>
        </section>

        {{-- DYNAMIC STATISTICS --}}
        @php
            $allGames = \App\Models\Sports\Games\Game::all();

            $totalGames = $allGames->count();
            $upcomingCount = 0;
            $ongoingCount = 0;
            $completedCount = 0;
            $cancelledCount = 0;
            $now = now();

            foreach ($allGames as $statGame) {
                $storedStatus = strtolower(
                    (string) ($statGame->status ?? 'upcoming')
                );

                if ($storedStatus === 'cancelled') {
                    $cancelledCount++;
                    continue;
                }

                if (!$statGame->event_date) {
                    if ($storedStatus === 'completed') {
                        $completedCount++;
                    } elseif ($storedStatus === 'ongoing') {
                        $ongoingCount++;
                    } else {
                        $upcomingCount++;
                    }

                    continue;
                }

                try {
                    $statDate = \Carbon\Carbon::parse(
                        $statGame->event_date
                    )->startOfDay();

                    $statStart = !empty($statGame->start_time)
                        ? $statDate->copy()->setTimeFromTimeString(
                            (string) $statGame->start_time
                        )
                        : $statDate->copy()->startOfDay();

                    $statEnd = !empty($statGame->end_time)
                        ? $statDate->copy()->setTimeFromTimeString(
                            (string) $statGame->end_time
                        )
                        : $statDate->copy()->endOfDay();

                    if ($now->lt($statStart)) {
                        $upcomingCount++;
                    } elseif ($now->lte($statEnd)) {
                        $ongoingCount++;
                    } else {
                        $completedCount++;
                    }
                } catch (\Throwable $e) {
                    if ($storedStatus === 'completed') {
                        $completedCount++;
                    } elseif ($storedStatus === 'ongoing') {
                        $ongoingCount++;
                    } else {
                        $upcomingCount++;
                    }
                }
            }
        @endphp

        {{-- KPI CARDS --}}
        <section class="games-kpis">

            <article class="games-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Games</span>
                    <span class="kpi-icon">
                        <i class="fas fa-trophy"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ $totalGames }}</div>
                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    All registered sports activities
                </div>
            </article>

            <article class="games-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Upcoming Events</span>
                    <span class="kpi-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ $upcomingCount }}</div>
                <div class="kpi-caption">
                    <i class="fas fa-calendar-check"></i>
                    Events scheduled for the future
                </div>
            </article>

            <article class="games-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Ongoing Events</span>
                    <span class="kpi-icon">
                        <i class="fas fa-running"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ $ongoingCount }}</div>
                <div class="kpi-caption">
                    <i class="fas fa-stopwatch"></i>
                    Events currently in progress
                </div>
            </article>

            <article class="games-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Completed Events</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ $completedCount }}</div>
                <div class="kpi-caption">
                    <i class="fas fa-flag-checkered"></i>
                    Finished sporting activities
                </div>
            </article>

        </section>

        {{-- MAIN PAPER PANEL --}}
        <section class="games-paper-panel">

            {{-- PANEL HEADER --}}
            <div class="games-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">SPORTS DIRECTORY</div>
                        <h3>Games &amp; Events</h3>
                        <p>View and manage all school sports activities.</p>
                    </div>
                </div>

                <div class="games-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $games->total() }}</strong>
                    Records
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.sports.games.index') }}"
                  class="games-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">FIND A RECORD</div>
                        <h4>Search &amp; Filter Games</h4>
                        <p>Find events by name, status or sport.</p>
                    </div>
                </div>

                <div class="games-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="games-search">Search Games</label>

                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
                            <input
                                id="games-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Game, sport or venue..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="games-status">Event Status</label>

                        <select id="games-status"
                                name="status"
                                class="diary-control">
                            <option value="">All Statuses</option>
                            <option value="upcoming"
                                @selected(request('status') === 'upcoming')>
                                Upcoming
                            </option>
                            <option value="ongoing"
                                @selected(request('status') === 'ongoing')>
                                Ongoing
                            </option>
                            <option value="completed"
                                @selected(request('status') === 'completed')>
                                Completed
                            </option>
                            <option value="cancelled"
                                @selected(request('status') === 'cancelled')>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="games-sport">Sport</label>

                        <select id="games-sport"
                                name="sport_name"
                                class="diary-control">
                            <option value="">All Sports</option>

                            @foreach($sports as $sport)
                                <option value="{{ $sport }}"
                                    @selected(request('sport_name') === $sport)>
                                    {{ $sport }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply
                        </button>

                        <a href="{{ route('admin.sports.games.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny(['search', 'status', 'sport_name']))
                <div class="games-active-filters">
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

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="fas fa-circle-half-stroke"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif

                        @if(request('sport_name'))
                            <span class="filter-chip">
                                <i class="fas fa-futbol"></i>
                                {{ request('sport_name') }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ $games->total() }} result(s)
                    </span>
                </div>
            @endif

            {{-- RESULTS TABLE --}}
            <div class="games-table-wrapper">
                <table class="games-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Game / Event</th>
                            <th>Sport</th>
                            <th>Academic Year</th>
                            <th>Class / Section</th>
                            <th>Date &amp; Time</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($games as $game)

                            @php
                                $storedStatus = strtolower(
                                    (string) ($game->status ?? 'upcoming')
                                );

                                $status = $storedStatus;

                                if (
                                    $storedStatus !== 'cancelled' &&
                                    $game->event_date
                                ) {
                                    try {
                                        $eventDate = \Carbon\Carbon::parse(
                                            $game->event_date
                                        )->startOfDay();

                                        $eventStart = !empty($game->start_time)
                                            ? $eventDate->copy()
                                                ->setTimeFromTimeString(
                                                    (string) $game->start_time
                                                )
                                            : $eventDate->copy()->startOfDay();

                                        $eventEnd = !empty($game->end_time)
                                            ? $eventDate->copy()
                                                ->setTimeFromTimeString(
                                                    (string) $game->end_time
                                                )
                                            : $eventDate->copy()->endOfDay();

                                        $currentTime = now();

                                        if ($currentTime->lt($eventStart)) {
                                            $status = 'upcoming';
                                        } elseif ($currentTime->lte($eventEnd)) {
                                            $status = 'ongoing';
                                        } else {
                                            $status = 'completed';
                                        }
                                    } catch (\Throwable $e) {
                                        $status = $storedStatus;
                                    }
                                }
                            @endphp

                            <tr>
                                <td>
                                    <span class="game-row-number">
                                        {{ $games->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="game-info">
                                        <div class="game-avatar">
                                            <i class="fas fa-futbol"></i>
                                        </div>

                                        <div class="game-details">
                                            <div class="game-title">
                                                {{ $game->title }}
                                            </div>

                                            @if($game->event_type)
                                                <div class="game-type">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $game->event_type }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($game->sport_name)
                                        <span class="sport-badge">
                                            {{ $game->sport_name }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($game->academic_year)
                                        <span class="year-badge">
                                            <i class="fas fa-calendar"></i>
                                            {{ $game->academic_year }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($game->class || $game->section)
                                        <span class="class-badge">
                                            <i class="fas fa-users"></i>
                                            {{ $game->class ?: '—' }}

                                            @if($game->section)
                                                <span class="class-divider">/</span>
                                                {{ $game->section }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($game->event_date)
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($game->event_date)->format('d M Y') }}
                                        </div>

                                        @if($game->start_time)
                                            <div class="event-time">
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse((string) $game->start_time)->format('h:i A') }}

                                                @if($game->end_time)
                                                    <span class="time-separator">–</span>
                                                    {{ \Carbon\Carbon::parse((string) $game->end_time)->format('h:i A') }}
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($game->venue)
                                        <div class="game-venue">
                                            <span class="venue-icon">
                                                <i class="fas fa-location-dot"></i>
                                            </span>
                                            <span class="venue-name">
                                                {{ $game->venue }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="status-badge {{ in_array($status, ['upcoming', 'ongoing', 'completed', 'cancelled'], true) ? $status : 'upcoming' }}">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="game-actions">
                                        <a
                                            href="{{ route('admin.sports.games.show', $game->id) }}"
                                            class="game-action-btn view"
                                            title="View Event Notice"
                                            aria-label="View event"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.sports.games.edit', $game->id) }}"
                                            class="game-action-btn edit"
                                            title="Edit"
                                            aria-label="Edit event"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.sports.games.destroy', $game->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this game/event?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="game-action-btn delete"
                                                title="Delete"
                                                aria-label="Delete event"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="games-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-futbol"></i>
                                        </div>

                                        <div class="section-eyebrow">NO RECORDS YET</div>
                                        <h4>No Games or Events Found</h4>

                                        <p>
                                            No sports games or events match
                                            your current filters. Try adjusting
                                            your search or add a new event.
                                        </p>

                                        <a
                                            href="{{ route('admin.sports.games.create') }}"
                                            class="diary-primary-btn"
                                        >
                                            <i class="fas fa-plus"></i>
                                            Add Game / Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($games->hasPages())
                <div class="games-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $games->firstItem() }}</strong>
                        to
                        <strong>{{ $games->lastItem() }}</strong>
                        of
                        <strong>{{ $games->total() }}</strong>
                        records
                    </div>

                    <div>
                        {{ $games->withQueryString()->links() }}
                    </div>
                </div>
            @endif

            {{-- FOOTER --}}
            @if($games->total() > 0)
                <div class="games-card-footer">
                    <div>
                        Showing
                        <strong>{{ $games->count() }}</strong>
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
   GAMES & EVENTS — INDEX PAGE
========================================================= */

/* Google Fonts must be imported before other CSS rules */
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&family=Patrick+Hand&display=swap');

.sports-games-page {
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
    --serif: 'DM Serif Display', Georgia, 'Times New Roman', serif;
    --hand: 'Patrick Hand', 'Segoe Print', cursive;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: 'Inter', Arial, sans-serif;
    font-size: 13px;
}

.sports-games-page *,
.sports-games-page *::before,
.sports-games-page *::after {
    box-sizing: border-box;
}

.sports-games-page a,
.sports-games-page button {
    transition:
        color .18s ease,
        background .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.sports-games-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.sports-games-page .games-hero,
.sports-games-page .games-paper-panel,
.sports-games-page .games-filter-panel {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.games-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(290px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.games-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.games-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.games-hero-icon {
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

.games-hero-text {
    min-width: 0;
}

.games-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.games-breadcrumb i {
    color: #b8b09e;
    font-size: 8px;
}

.games-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 34px;
    font-weight: 400;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.games-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.games-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 10px;
    font-weight: 700;
}

.games-hero-note i {
    font-size: 12px;
}

/* HERO QUOTE PANEL */

.games-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 29px 25px;
    overflow: hidden;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.games-hero-actions::before,
.games-hero-actions::after {
    content: "";
    position: absolute;
    width: 115px;
    height: 115px;
    border: 1px solid rgba(244, 240, 223, .10);
    border-radius: 50%;
    pointer-events: none;
}

.games-hero-actions::before {
    top: -65px;
    right: -30px;
}

.games-hero-actions::after {
    bottom: -85px;
    left: -30px;
}

.hero-quote-mark {
    height: 24px;
    color: rgba(244, 240, 223, .55);
    font-family: var(--serif);
    font-size: 57px;
    line-height: 1;
}

.hero-handwritten {
    position: relative;
    z-index: 1;
    color: #fffefa;
    font-family:  var(--hand, cursive);
    font-size: 25px;
    font-weight: 600;
    letter-spacing: .2px;
    line-height: 1.35;
}

.games-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 315px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* LOCKED PRIMARY BUTTON */

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

.games-hero-actions .hero-primary-btn {
    position: relative;
    z-index: 1;
    margin-top: 3px;
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.games-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

.hero-btn-arrow {
    margin-left: 3px;
    font-size: 10px;
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

/* KPI CARDS — FOLDER TAB STYLE */

.games-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.games-kpi {
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

.games-kpi::before {
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

.games-kpi.blue { --kpi-color: #2b5d8a; }
.games-kpi.green { --kpi-color: #3a8060; }
.games-kpi.orange { --kpi-color: #a87543; }
.games-kpi.red { --kpi-color: #bd5148; }

.games-kpi.blue::before { content: "Records"; }
.games-kpi.green::before { content: "In Progress"; }
.games-kpi.orange::before { content: "Scheduled"; }
.games-kpi.red::before { content: "Completed"; }

.games-kpi:hover {
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

.sports-games-page .kpi-icon {
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
    font-weight: 400;
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

/* MAIN PAPER PANEL */

.games-paper-panel {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.games-panel-header {
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

.panel-title-icon,
.filter-heading-icon {
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

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 400;
}

.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.games-record-count {
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

.games-record-count i,
.games-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.games-filter-panel {
    overflow: hidden;
    margin: 20px;
    border-radius: 9px;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 19px 20px 16px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 400;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.games-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(200px, 2fr)
        minmax(130px, 1fr)
        minmax(135px, 1fr)
        auto;
    align-items: end;
    gap: 12px;
    padding: 19px 20px 20px;
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
    background: var(--paper);
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
    background: var(--paper);
}

.diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}

/* ACTIVE FILTERS */

.games-active-filters {
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

/* RESULTS TABLE */

.games-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.games-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: collapse;
}

.games-table thead th {
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

.games-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.games-table tbody tr {
    transition: background .18s ease;
}

.games-table tbody tr:hover {
    background: #faf9f2;
}

.games-table tbody tr:last-child td {
    border-bottom: none;
}

.games-table .actions-heading {
    text-align: right;
}

.game-row-number {
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

/* GAME INFORMATION */

.game-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.game-avatar {
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

.game-details {
    min-width: 0;
}

.game-title {
    max-width: 210px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.game-type {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}

.game-type i {
    color: var(--amber);
}

/* DATA BADGES */

.sport-badge,
.year-badge,
.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.sport-badge {
    border: 1px solid #d7e2e9;
    color: var(--blue);
    background: #f0f5f8;
}

.year-badge {
    border: 1px solid #e6dfc9;
    color: #8b641d;
    background: #faf5e7;
}

.class-badge {
    border: 1px solid #d7e2e9;
    color: var(--blue);
    background: #f0f5f8;
}

.class-badge i {
    font-size: 10px;
}

.class-divider {
    color: #9aa59a;
}

.diary-muted {
    color: #929a8e;
    font-size: 11px;
}

/* DATE AND TIME */

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

.event-time {
    margin-top: 5px;
    color: #858e82;
    font-size: 10px;
    white-space: nowrap;
}

.event-time i {
    margin-right: 3px;
}

.time-separator {
    padding: 0 2px;
}

/* VENUE */

.game-venue {
    display: flex;
    align-items: center;
    gap: 7px;
    max-width: 175px;
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
    border-radius: 7px;
    color: var(--red);
    background: #fff1ed;
    font-size: 10px;
}

.venue-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* STATUS BADGES */

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

.status-badge.upcoming {
    border: 1px solid #f0dfb5;
    color: #9b6a09;
    background: #fff7e5;
}

.status-badge.upcoming .status-dot {
    background: #d58a0b;
}

.status-badge.ongoing {
    border: 1px solid #bfe5e9;
    color: #087d8c;
    background: #eaf9fa;
}

.status-badge.ongoing .status-dot {
    background: #159bb0;
}

.status-badge.completed {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.status-badge.completed .status-dot {
    background: #3a8060;
}

.status-badge.cancelled {
    border: 1px solid #f0d5ce;
    color: #bd5140;
    background: #fff1ed;
}

.status-badge.cancelled .status-dot {
    background: #bd5148;
}

/* ACTION BUTTONS */

.game-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.game-actions form {
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
    border: 1px solid #deded2;
    border-radius: 7px;
    color: #68746a;
    background: var(--paper);
    font-size: 11px;
    text-decoration: none;
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

.games-empty-state {
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
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 27px;
}

.games-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 400;
}

.games-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* PAGINATION */

.games-pagination {
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

.games-pagination nav {
    margin: 0;
}

.games-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.games-pagination .page-link {
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

.games-pagination .page-link:hover {
    border-color: #bfcbbd;
    color: var(--green);
    background: #f5f5eb;
}

.games-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.games-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* FOOTER */

.games-card-footer {
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

.games-card-footer strong {
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

/* ACCESSIBILITY */

.sports-games-page a:focus-visible,
.sports-games-page button:focus-visible,
.sports-games-page input:focus-visible,
.sports-games-page select:focus-visible {
    outline: 3px solid rgba(58, 128, 96, .35);
    outline-offset: 2px;
}

/* TABLET */

@media (max-width: 1200px) {
    .games-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .games-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .games-hero {
        grid-template-columns: 1fr;
    }

    .games-hero-actions {
        min-height: 220px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .sports-games-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .games-hero::before {
        left: 22px;
    }

    .games-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .games-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 10px;
        font-size: 18px;
    }

    .games-hero-text h2 {
        font-size: 24px;
    }

    .games-hero-text p {
        font-size: 12px;
    }

    .games-hero-note {
        align-items: flex-start;
        font-size: 11px;
    }

    .games-hero-actions {
        min-height: 220px;
        padding: 23px 18px;
    }

    .hero-handwritten {
        font-size: 24px;
    }

    .games-kpis {
        gap: 18px;
        margin-top: 31px;
    }

    .games-kpi {
        padding: 20px 18px 15px;
    }

    .kpi-value {
        font-size: 30px;
    }

    .games-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .panel-title-wrap {
        gap: 9px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .panel-title-wrap p {
        font-size: 11px;
    }

    .games-record-count {
        gap: 5px;
        padding: 6px 8px;
        font-size: 10px;
    }

    .games-filter-panel {
        margin: 14px;
    }

    .filter-heading {
        padding: 15px;
    }

    .filter-heading h4 {
        font-size: 16px;
    }

    .games-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 15px;
    }

    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .games-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 11px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .games-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .games-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .games-kpis {
        grid-template-columns: 1fr;
    }

    .games-panel-header {
        gap: 8px;
    }

    .panel-title-icon,
    .filter-heading-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .games-record-count {
        gap: 4px;
        padding: 5px 7px;
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

    .games-hero-text h2 {
        font-size: 21px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .sports-games-page *,
    .sports-games-page *::before,
    .sports-games-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection