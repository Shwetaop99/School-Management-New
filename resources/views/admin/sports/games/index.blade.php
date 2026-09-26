@extends('layouts.app')

@section('title', 'Games & Events | Admin')

@section('content')

<div class="sports-games-page">

    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="games-page-header">

            <div class="page-heading">

                <div class="page-heading-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div>
                    <h2>Games & Events</h2>

                    <p>
                        Manage school sports games, competitions and events.
                    </p>
                </div>

            </div>

            <div class="page-actions">

                <a
                    href="{{ route('admin.sports.games.create') }}"
                    class="dashboard-primary-btn"
                >
                    <i class="fas fa-plus"></i>
                    Add Game / Event
                </a>

            </div>

        </div>


        {{-- STATISTICS --}}
        <div class="games-stats-row">

            {{-- TOTAL GAMES --}}
            <div class="games-stat-card blue-card">

                <div class="stat-content">

                    <span>Total Games</span>

                    <h3>
                        {{ \App\Models\Sports\Games\Game::count() }}
                    </h3>

                    <small>
                        <i class="fas fa-futbol"></i>
                        All sports activities
                    </small>

                </div>

                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>

            </div>


            {{-- UPCOMING --}}
            <div class="games-stat-card orange-card">

                <div class="stat-content">

                    <span>Upcoming</span>

                    <h3>
                        {{ \App\Models\Sports\Games\Game::where('status', 'upcoming')->count() }}
                    </h3>

                    <small>
                        <i class="fas fa-calendar-days"></i>
                        Scheduled events
                    </small>

                </div>

                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>

            </div>


            {{-- ONGOING --}}
            <div class="games-stat-card cyan-card">

                <div class="stat-content">

                    <span>Ongoing</span>

                    <h3>
                        {{ \App\Models\Sports\Games\Game::where('status', 'ongoing')->count() }}
                    </h3>

                    <small>
                        <i class="fas fa-person-running"></i>
                        Currently active
                    </small>

                </div>

                <div class="stat-icon">
                    <i class="fas fa-running"></i>
                </div>

            </div>


            {{-- COMPLETED --}}
            <div class="games-stat-card red-card">

                <div class="stat-content">

                    <span>Completed</span>

                    <h3>
                        {{ \App\Models\Sports\Games\Game::where('status', 'completed')->count() }}
                    </h3>

                    <small>
                        <i class="fas fa-check-circle"></i>
                        Finished events
                    </small>

                </div>

                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

            </div>

        </div>


        {{-- MAIN CONTENT CARD --}}
        <div class="games-content-card">

            {{-- CARD HEADER --}}
            <div class="content-card-header">

                <div class="content-title">

                    <div class="content-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>

                        <h5>
                            Games & Events
                        </h5>

                        <p>
                            View and manage all sports activities.
                        </p>

                    </div>

                </div>

                <div class="records-badge">

                    <span>
                        {{ $games->total() }}
                    </span>

                    Records

                </div>

            </div>


            {{-- FILTER BAR --}}
            <form
                method="GET"
                action="{{ route('admin.sports.games.index') }}"
                class="games-filter-bar"
            >

                {{-- SEARCH --}}
                <div class="search-box">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search games, sports, venue..."
                        autocomplete="off"
                    >

                </div>


                {{-- STATUS --}}
                <div class="filter-item">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="upcoming"
                            {{ request('status') === 'upcoming' ? 'selected' : '' }}
                        >
                            Upcoming
                        </option>

                        <option
                            value="ongoing"
                            {{ request('status') === 'ongoing' ? 'selected' : '' }}
                        >
                            Ongoing
                        </option>

                        <option
                            value="completed"
                            {{ request('status') === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="cancelled"
                            {{ request('status') === 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                {{-- SPORT --}}
                <div class="filter-item">

                    <select
                        name="sport_name"
                        class="form-select"
                    >

                        <option value="">
                            All Sports
                        </option>

                        @foreach($sports as $sport)

                            <option
                                value="{{ $sport }}"
                                {{ request('sport_name') === $sport ? 'selected' : '' }}
                            >
                                {{ $sport }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- FILTER BUTTON --}}
                <button
                    type="submit"
                    class="filter-submit-btn"
                    title="Apply Filters"
                >
                    <i class="fas fa-filter"></i>
                </button>


                {{-- CLEAR --}}
                @if(request()->hasAny(['search', 'status', 'sport_name']))

                    <a
                        href="{{ route('admin.sports.games.index') }}"
                        class="clear-filter-btn"
                        title="Clear Filters"
                    >
                        <i class="fas fa-rotate-left"></i>
                    </a>

                @else

                    <button
                        type="button"
                        class="clear-filter-btn"
                        disabled
                        title="Clear Filters"
                    >
                        <i class="fas fa-rotate-left"></i>
                    </button>

                @endif

            </form>


            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))

                <div class="success-alert">

                    <div class="success-alert-icon">
                        <i class="fas fa-check"></i>
                    </div>

                    <div>
                        {{ session('success') }}
                    </div>

                </div>

            @endif


            {{-- TABLE --}}
            <div class="games-table-wrapper">

                <table class="games-table">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Game / Event
                            </th>

                            <th>
                                Sport
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Class / Section
                            </th>

                            <th>
                                Date & Time
                            </th>

                            <th>
                                Venue
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($games as $game)

                            @php
                                $status = strtolower($game->status ?? 'upcoming');
                            @endphp

                            <tr>

                                {{-- NUMBER --}}
                                <td>

                                    <span class="row-number">
                                        {{ $games->firstItem() + $loop->index }}
                                    </span>

                                </td>


                                {{-- GAME --}}
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


                                {{-- SPORT --}}
                                <td>

                                    @if($game->sport_name)

                                        <span class="sport-text">
                                            {{ $game->sport_name }}
                                        </span>

                                    @else

                                        <span class="muted-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- YEAR --}}
                                <td>

                                    @if($game->academic_year)

                                        <span class="year-badge">
                                            {{ $game->academic_year }}
                                        </span>

                                    @else

                                        <span class="muted-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- CLASS --}}
                                <td>

                                    @if($game->class || $game->section)

                                        <span class="class-pill">

                                            <i class="fas fa-users"></i>

                                            {{ $game->class ?: '—' }}

                                            @if($game->section)

                                                <span class="class-divider">
                                                    /
                                                </span>

                                                {{ $game->section }}

                                            @endif

                                        </span>

                                    @else

                                        <span class="muted-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- DATE --}}
                                <td>

                                    @if($game->event_date)

                                        <div class="event-date">

                                            <i class="far fa-calendar-alt"></i>

                                            {{ $game->event_date->format('d M Y') }}

                                        </div>


                                        @if($game->start_time)

                                            <div class="event-time">

                                                <i class="far fa-clock"></i>

                                                {{ \Carbon\Carbon::parse($game->start_time)->format('h:i A') }}

                                                @if($game->end_time)

                                                    <span class="time-separator">
                                                        –
                                                    </span>

                                                    {{ \Carbon\Carbon::parse($game->end_time)->format('h:i A') }}

                                                @endif

                                            </div>

                                        @endif

                                    @else

                                        <span class="muted-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- VENUE --}}
                                <td>

                                    @if($game->venue)

                                        <div class="venue">

                                            <span class="venue-icon">
                                                <i class="fas fa-location-dot"></i>
                                            </span>

                                            <span>
                                                {{ $game->venue }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="muted-text">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($status === 'upcoming')

                                        <span class="status-pill upcoming">

                                            <span class="status-dot"></span>

                                            Upcoming

                                        </span>

                                    @elseif($status === 'ongoing')

                                        <span class="status-pill ongoing">

                                            <span class="status-dot"></span>

                                            Ongoing

                                        </span>

                                    @elseif($status === 'completed')

                                        <span class="status-pill completed">

                                            <span class="status-dot"></span>

                                            Completed

                                        </span>

                                    @elseif($status === 'cancelled')

                                        <span class="status-pill cancelled">

                                            <span class="status-dot"></span>

                                            Cancelled

                                        </span>

                                    @else

                                        <span class="status-pill upcoming">

                                            <span class="status-dot"></span>

                                            {{ ucfirst($status) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="table-actions">

                                        {{-- VIEW --}}
                                        <a
                                            href="{{ route('admin.sports.games.show', $game->id) }}"
                                            class="table-action view-action"
                                            title="View Event Notice"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('admin.sports.games.edit', $game->id) }}"
                                            class="table-action edit-action"
                                            title="Edit"
                                        >
                                            <i class="fas fa-pen"></i>
                                        </a>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('admin.sports.games.destroy', $game->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this game/event?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="table-action delete-action"
                                                title="Delete"
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

                                    <div class="empty-state">

                                        <div class="empty-state-icon">
                                            <i class="fas fa-futbol"></i>
                                        </div>

                                        <h5>
                                            No Games or Events Found
                                        </h5>

                                        <p>
                                            No sports games or events match your current filters.
                                        </p>

                                        <a
                                            href="{{ route('admin.sports.games.create') }}"
                                            class="dashboard-primary-btn empty-add-btn"
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

                        <strong>
                            {{ $games->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $games->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $games->total() }}
                        </strong>

                        records

                    </div>

                    <div>
                        {{ $games->links() }}
                    </div>

                </div>

            @endif


            {{-- FOOTER --}}
            @if($games->total() > 0)

                <div class="games-card-footer">

                    <div class="footer-info">

                        Showing

                        <strong>
                            {{ $games->count() }}
                        </strong>

                        record(s) on this page

                    </div>

                    <div class="footer-status">

                        <i class="fas fa-circle-check"></i>

                        Data loaded successfully

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


<style>

/* =========================================================
   SPORTS GAMES PAGE
========================================================= */

.sports-games-page {
    min-height: calc(100vh - 70px);
    background: #f6f8fb;
    color: #172033;
    font-family: 'Inter', sans-serif;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.games-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.page-heading {
    display: flex;
    align-items: center;
    gap: 13px;
}

.page-heading-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 19px;
    background: linear-gradient(135deg, #0d6efd, #1987e8);
    box-shadow: 0 6px 16px rgba(13, 110, 253, .16);
}

.page-heading h2 {
    margin: 0;
    color: #172033;
    font-size: 23px;
    line-height: 1.2;
    font-weight: 750;
    letter-spacing: -.35px;
}

.page-heading p {
    margin: 4px 0 0;
    color: #7a8799;
    font-size: 12px;
    line-height: 1.5;
}


/* =========================================================
   HEADER BUTTONS
========================================================= */

.page-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dashboard-primary-btn,
.dashboard-outline-btn {
    height: 38px;
    padding: 0 14px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 11.5px;
    font-weight: 650;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
}

.dashboard-primary-btn {
    border: 1px solid #147cf5;
    background: #147cf5;
    color: #fff;
    box-shadow: 0 5px 12px rgba(20, 124, 245, .14);
}

.dashboard-primary-btn:hover {
    background: #1268ca;
    border-color: #1268ca;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 17px rgba(20, 124, 245, .20);
}

.dashboard-outline-btn {
    border: 1px solid #e4e9f0;
    background: #fff;
    color: #64748b;
}

.dashboard-outline-btn:hover {
    background: #f8fbff;
    border-color: #cfe0f5;
    color: #147cf5;
}


/* =========================================================
   STATISTICS
========================================================= */

.games-stats-row {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.games-stat-card {
    position: relative;
    overflow: hidden;
    min-height: 165px;
    padding: 24px 25px;
    border-radius: 17px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    color: #fff;
    transition: transform .22s ease, box-shadow .22s ease;
}

.games-stat-card::before {
    content: "";
    position: absolute;
    width: 65px;
    height: 65px;
    top: -30px;
    right: 18px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .06);
}

.games-stat-card::after {
    content: "";
    position: absolute;
    width: 105px;
    height: 105px;
    right: -32px;
    bottom: -48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .08);
}

.games-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(15, 23, 42, .14);
}

.blue-card {
    background: linear-gradient(135deg, #147cf5 0%, #1268ca 100%);
}

.orange-card {
    background: linear-gradient(135deg, #ffb238 0%, #ff9d1c 100%);
}

.cyan-card {
    background: linear-gradient(135deg, #2bcfe8 0%, #18b5d5 100%);
}

.red-card {
    background: linear-gradient(135deg, #ff6d61 0%, #f65343 100%);
}


/* =========================================================
   STAT CONTENT
========================================================= */

.stat-content {
    position: relative;
    z-index: 2;
}

.stat-content span {
    display: block;
    margin-bottom: 5px;
    color: rgba(255, 255, 255, .90);
    font-size: 11px;
    font-weight: 600;
}

.stat-content h3 {
    margin: 0;
    color: #fff;
    font-size: 27px;
    line-height: 1;
    font-weight: 800;
}

.stat-content small {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 7px;
    color: rgba(255, 255, 255, .76);
    font-size: 9.5px;
}


/* =========================================================
   STAT ICON
========================================================= */

.stat-icon {
    position: relative;
    z-index: 2;
    width: 49px;
    height: 49px;
    min-width: 49px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, .17);
    border: 1px solid rgba(255, 255, 255, .18);
    color: #fff;
    font-size: 18px;
}


/* =========================================================
   MAIN CARD
========================================================= */

.games-content-card {
    overflow: hidden;
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, .045);
}


/* =========================================================
   CARD HEADER
========================================================= */

.content-card-header {
    min-height: 70px;
    padding: 15px 19px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.content-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.content-title-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 10px;
    background: #edf5ff;
    color: #147cf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.content-title h5 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 15px;
    font-weight: 700;
}

.content-title p {
    margin: 0;
    color: #8d99aa;
    font-size: 10.5px;
}

.records-badge {
    height: 31px;
    padding: 0 10px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f5f8fc;
    color: #94a3b8;
    font-size: 10px;
    font-weight: 600;
}

.records-badge span {
    color: #147cf5;
    font-size: 11px;
    font-weight: 750;
}


/* =========================================================
   FILTER BAR
========================================================= */

.games-filter-bar {
    padding: 13px 19px;
    background: #fafbfd;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-box {
    position: relative;
    width: 100%;
    max-width: 390px;
    height: 38px;
}

.search-box i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa6b6;
    font-size: 11px;
    pointer-events: none;
}

.search-box input {
    width: 100%;
    height: 100%;
    padding: 0 12px 0 34px;
    border: 1px solid #e1e7ef;
    border-radius: 8px;
    outline: none;
    background: #fff;
    color: #334155;
    font-size: 11px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.search-box input:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, .07);
}

.filter-item {
    width: 165px;
}

.filter-item .form-select {
    height: 38px;
    border: 1px solid #e1e7ef;
    border-radius: 8px;
    background-color: #fff;
    color: #64748b;
    font-size: 11px;
    box-shadow: none;
    cursor: pointer;
}

.filter-item .form-select:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, .07);
}

.filter-submit-btn,
.clear-filter-btn {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s ease;
}

.filter-submit-btn {
    border: 1px solid #147cf5;
    background: #147cf5;
    color: #fff;
}

.filter-submit-btn:hover {
    background: #1268ca;
    border-color: #1268ca;
}

.clear-filter-btn {
    border: 1px solid #e1e7ef;
    background: #fff;
    color: #7a8799;
    text-decoration: none;
}

.clear-filter-btn:hover {
    background: #edf5ff;
    border-color: #cfe0f5;
    color: #147cf5;
    transform: rotate(-10deg);
}

.clear-filter-btn:disabled {
    opacity: .45;
    cursor: not-allowed;
    transform: none;
}


/* =========================================================
   SUCCESS ALERT
========================================================= */

.success-alert {
    margin: 14px 19px 0;
    padding: 10px 13px;
    border: 1px solid #ccebd8;
    border-radius: 9px;
    background: #f0fbf4;
    color: #23844b;
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 11px;
    font-weight: 600;
}

.success-alert-icon {
    width: 24px;
    height: 24px;
    border-radius: 7px;
    background: #d9f5e2;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
}


/* =========================================================
   TABLE
========================================================= */

.games-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.games-table {
    width: 100%;
    min-width: 1180px;
    margin: 0;
    border-collapse: collapse;
}

.games-table thead {
    background: #fafbfd;
}

.games-table thead th {
    height: 47px;
    padding: 0 14px;
    border-bottom: 1px solid #e7edf4;
    color: #7a8799;
    text-align: left;
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.games-table tbody td {
    height: 67px;
    padding: 12px 14px;
    border-bottom: 1px solid #f0f2f6;
    color: #475569;
    font-size: 11px;
    vertical-align: middle;
}

.games-table tbody tr:last-child td {
    border-bottom: 0;
}

.games-table tbody tr:hover {
    background: #f9fbfe;
}

.row-number {
    color: #a0acba;
    font-size: 10.5px;
    font-weight: 650;
}


/* =========================================================
   GAME INFO
========================================================= */

.game-info {
    min-width: 210px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.game-avatar {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf5ff;
    color: #147cf5;
    font-size: 14px;
}

.game-details {
    min-width: 0;
}

.game-title {
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #172033;
    font-size: 12px;
    font-weight: 700;
}

.game-type {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 3px;
    color: #9aa6b6;
    font-size: 9.5px;
}


/* =========================================================
   DATA STYLES
========================================================= */

.sport-text {
    color: #334155;
    font-weight: 650;
    white-space: nowrap;
}

.year-badge,
.class-pill {
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.year-badge {
    padding: 5px 8px;
    border-radius: 6px;
    background: #f4f7fb;
    color: #64748b;
    font-size: 9.5px;
    font-weight: 650;
}

.class-pill {
    gap: 5px;
    padding: 5px 8px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #475569;
    font-size: 9.5px;
    font-weight: 650;
}

.class-pill i {
    color: #94a3b8;
    font-size: 8px;
}

.class-divider {
    color: #b3bdc9;
    padding: 0 1px;
}

.muted-text {
    color: #b4beca;
}


/* =========================================================
   DATE / TIME
========================================================= */

.event-date {
    display: flex;
    align-items: center;
    color: #334155;
    font-size: 10.5px;
    font-weight: 650;
    white-space: nowrap;
}

.event-date i {
    margin-right: 5px;
    color: #147cf5;
}

.event-time {
    margin-top: 4px;
    color: #98a4b3;
    font-size: 9px;
    white-space: nowrap;
}

.event-time i {
    margin-right: 3px;
}

.time-separator {
    padding: 0 2px;
}


/* =========================================================
   VENUE
========================================================= */

.venue {
    display: flex;
    align-items: center;
    gap: 6px;
    max-width: 150px;
    color: #64748b;
    font-size: 10.5px;
}

.venue-icon {
    width: 22px;
    height: 22px;
    min-width: 22px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f7fb;
    color: #94a3b8;
    font-size: 8px;
}

.venue > span:last-child {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   STATUS
========================================================= */

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-pill.upcoming {
    background: #fff6df;
    color: #ad7500;
}

.status-pill.upcoming .status-dot {
    background: #f2a900;
}

.status-pill.ongoing {
    background: #e7faff;
    color: #00839d;
}

.status-pill.ongoing .status-dot {
    background: #14b8d4;
}

.status-pill.completed {
    background: #eaf8ef;
    color: #23844b;
}

.status-pill.completed .status-dot {
    background: #28a65d;
}

.status-pill.cancelled {
    background: #fff0ef;
    color: #d84740;
}

.status-pill.cancelled .status-dot {
    background: #ed554d;
}


/* =========================================================
   ACTIONS
========================================================= */

.table-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
}

.table-actions form {
    margin: 0;
}

.table-action {
    width: 29px;
    height: 29px;
    border-radius: 7px;
    border: 1px solid #e5eaf0;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 9.5px;
    cursor: pointer;
    text-decoration: none;
    transition: all .17s ease;
}

.table-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(15, 23, 42, .08);
}

.view-action {
    color: #147cf5;
}

.view-action:hover {
    background: #edf5ff;
    border-color: #cfe2ff;
    color: #147cf5;
}

.edit-action {
    color: #d99411;
}

.edit-action:hover {
    background: #fff8e8;
    border-color: #f5dda9;
    color: #d99411;
}

.delete-action {
    color: #df5149;
}

.delete-action:hover {
    background: #fff1f0;
    border-color: #f2ceca;
    color: #df5149;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-state-icon {
    width: 62px;
    height: 62px;
    margin: 0 auto 14px;
    border-radius: 16px;
    background: #edf5ff;
    color: #147cf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.empty-state h5 {
    margin: 0 0 5px;
    color: #172033;
    font-size: 15px;
    font-weight: 700;
}

.empty-state p {
    margin: 0 0 17px;
    color: #94a3b8;
    font-size: 11px;
}

.empty-add-btn {
    height: 36px;
}


/* =========================================================
   PAGINATION
========================================================= */

.games-pagination {
    padding: 14px 19px;
    border-top: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.pagination-info {
    color: #94a3b8;
    font-size: 10px;
}

.pagination-info strong {
    color: #64748b;
    font-weight: 700;
}

.games-pagination nav {
    margin: 0;
}

.games-pagination .pagination {
    margin: 0;
    gap: 4px;
}

.games-pagination .page-link {
    min-width: 30px;
    height: 30px;
    padding: 0 8px;
    border: 1px solid #e5eaf0;
    border-radius: 7px !important;
    background: #fff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    box-shadow: none;
}

.games-pagination .page-link:hover {
    background: #edf5ff;
    border-color: #cfe0f5;
    color: #147cf5;
}

.games-pagination .page-item.active .page-link {
    background: #147cf5;
    border-color: #147cf5;
    color: #fff;
}

.games-pagination .page-item.disabled .page-link {
    color: #b7c0cb;
    background: #f8fafc;
}


/* =========================================================
   FOOTER
========================================================= */

.games-card-footer {
    min-height: 55px;
    padding: 10px 19px;
    border-top: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.footer-info {
    color: #94a3b8;
    font-size: 10px;
}

.footer-info strong {
    color: #64748b;
    font-weight: 700;
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #8c99aa;
    font-size: 9.5px;
}

.footer-status i {
    color: #28a65d;
    font-size: 9px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991px) {

    .games-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .page-actions {
        width: 100%;
    }

    .page-actions a,
    .page-actions button {
        flex: 1;
    }

    .games-stats-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .games-filter-bar {
        flex-wrap: wrap;
    }

    .search-box {
        max-width: none;
        flex: 1 1 100%;
    }

    .filter-item {
        flex: 1;
        width: auto;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767px) {

    .sports-games-page .container-fluid {
        padding-top: 18px !important;
    }

    .games-page-header {
        margin-bottom: 18px;
    }

    .page-heading {
        align-items: flex-start;
    }

    .page-heading h2 {
        font-size: 21px;
    }

    .page-heading p {
        font-size: 11px;
    }

    .games-filter-bar {
        padding: 13px;
    }

    .content-card-header {
        padding: 14px 15px;
    }

    .games-pagination {
        align-items: flex-start;
        flex-direction: column;
    }

    .games-card-footer {
        align-items: flex-start;
        flex-direction: column;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575px) {

    .sports-games-page .container-fluid {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }

    .games-stats-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .games-stat-card {
        min-height: 120px;
        padding: 17px 18px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        font-size: 17px;
    }

    .stat-content h3 {
        font-size: 24px;
    }

    .page-actions {
        flex-direction: column;
        width: 100%;
    }

    .page-actions a,
    .page-actions button {
        width: 100%;
        flex: none;
    }

    .filter-item {
        width: 100%;
        flex: none;
    }

    .filter-submit-btn,
    .clear-filter-btn {
        width: 100%;
    }

    .records-badge {
        display: none;
    }

    .content-title {
        align-items: flex-start;
    }

    .content-title h5 {
        font-size: 14px;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 400px) {

    .page-heading {
        gap: 10px;
    }

    .page-heading-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        font-size: 17px;
    }

    .page-heading h2 {
        font-size: 19px;
    }

    .page-heading p {
        font-size: 10.5px;
    }

}

</style>

@endsection