@extends('layouts.app')

@section('title', 'Event Notice | Admin')

@section('content')

<div class="sports-event-notice-page">

    <div class="container-fluid py-4">

        {{-- ========================= PAGE HEADER ========================= --}}
        <div class="event-page-header mb-4">

            <div class="event-heading-wrapper">
                <div class="event-page-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>

                <div>
                    <h1>Sports Event Notice</h1>
                    <p>View details of the selected sports event.</p>
                </div>
            </div>

            <div class="event-header-actions">
                <a href="{{ route('admin.sports.games.index') }}"
                   class="dashboard-secondary-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Games
                </a>

                <button type="button"
                        class="dashboard-primary-btn"
                        onclick="window.print()">
                    <i class="fas fa-print"></i>
                    Print Notice
                </button>
            </div>

        </div>


        {{-- ========================= EVENT NOTICE ========================= --}}
        <div class="notice-card">

            {{-- Notice Top --}}
            <div class="notice-top">

                <div class="notice-label">
                    <i class="fas fa-trophy"></i>
                    SPORTS EVENT
                </div>

                @php
                    $statusClass = match($game->status) {
                        'upcoming' => 'status-upcoming',
                        'ongoing' => 'status-ongoing',
                        'completed' => 'status-completed',
                        'cancelled' => 'status-cancelled',
                        default => 'status-upcoming',
                    };
                @endphp

                <span class="event-status {{ $statusClass }}">
                    {{ ucfirst($game->status) }}
                </span>

            </div>


            {{-- Event Title --}}
            <div class="notice-title-section">

                <h2>{{ $game->title }}</h2>

                <div class="notice-subtitle">
                    <span>
                        <i class="fas fa-running"></i>
                        {{ $game->sport_name }}
                    </span>

                    @if($game->event_type)
                        <span>
                            <i class="fas fa-calendar-check"></i>
                            {{ $game->event_type }}
                        </span>
                    @endif

                </div>

            </div>


            {{-- Event Information --}}
            <div class="event-info-grid">

                <div class="event-info-item">
                    <div class="info-icon blue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>

                    <div>
                        <span>Date</span>
                        <strong>
                            {{ $game->event_date ? $game->event_date->format('d M Y') : 'Not specified' }}
                        </strong>
                    </div>
                </div>


                <div class="event-info-item">
                    <div class="info-icon orange">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>
                        <span>Time</span>
                        <strong>
                            @if($game->start_time)
                                {{ \Carbon\Carbon::parse($game->start_time)->format('h:i A') }}

                                @if($game->end_time)
                                    - {{ \Carbon\Carbon::parse($game->end_time)->format('h:i A') }}
                                @endif
                            @else
                                Not specified
                            @endif
                        </strong>
                    </div>
                </div>


                <div class="event-info-item">
                    <div class="info-icon cyan">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>

                    <div>
                        <span>Venue</span>
                        <strong>
                            {{ $game->venue ?: 'Not specified' }}
                        </strong>
                    </div>
                </div>


                <div class="event-info-item">
                    <div class="info-icon red">
                        <i class="fas fa-users"></i>
                    </div>

                    <div>
                        <span>Participants</span>
                        <strong>
                            @if($game->class || $game->section)
                                {{ $game->class ? 'Class ' . $game->class : '' }}
                                {{ $game->section ? ' - Section ' . $game->section : '' }}
                            @else
                                All Students
                            @endif
                        </strong>
                    </div>
                </div>

            </div>


            {{-- Academic Year / Organizer --}}
            <div class="notice-meta-grid">

                <div class="notice-meta-item">
                    <span>Academic Year</span>
                    <strong>
                        {{ $game->academic_year ?: 'Not specified' }}
                    </strong>
                </div>

                <div class="notice-meta-item">
                    <span>Organizer</span>
                    <strong>
                        {{ $game->organizer ?: 'School Administration' }}
                    </strong>
                </div>

            </div>


            {{-- Description --}}
            @if($game->description)

                <div class="notice-description">

                    <div class="description-heading">
                        <i class="fas fa-info-circle"></i>
                        Event Details
                    </div>

                    <p>{{ $game->description }}</p>

                </div>

            @endif


            {{-- Footer --}}
            <div class="notice-footer">

                <div>
                    <i class="fas fa-school"></i>
                    School Sports Department
                </div>

                <div>
                    Notice Date:
                    {{ $game->created_at->format('d M Y') }}
                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   SPORTS EVENT NOTICE
   ========================================================= */

.sports-event-notice-page {
    background: #f6f8fb;
    min-height: calc(100vh - 70px);
    font-family: 'Inter', sans-serif;
    color: #172033;
}


/* ========================= HEADER ========================= */

.event-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.event-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
}

.event-page-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    box-shadow: 0 5px 14px rgba(20,124,245,.18);
}

.event-heading-wrapper h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #172033;
}

.event-heading-wrapper p {
    margin: 4px 0 0;
    color: #718096;
    font-size: 13px;
}

.event-header-actions {
    display: flex;
    gap: 10px;
}


/* ========================= BUTTONS ========================= */

.dashboard-primary-btn,
.dashboard-secondary-btn {
    min-height: 40px;
    padding: 0 16px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .18s ease;
}

.dashboard-primary-btn {
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    box-shadow: 0 4px 12px rgba(20,124,245,.16);
}

.dashboard-primary-btn:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(20,124,245,.22);
}

.dashboard-secondary-btn {
    background: #fff;
    color: #475569;
    border: 1px solid #e5e9f0;
}

.dashboard-secondary-btn:hover {
    background: #f8fafc;
    color: #147cf5;
}


/* ========================= NOTICE CARD ========================= */

.notice-card {
    max-width: 1050px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15,23,42,.06);
    overflow: hidden;
}


/* ========================= NOTICE TOP ========================= */

.notice-top {
    padding: 22px 26px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.notice-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .8px;
    color: #147cf5;
}

.event-status {
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.status-upcoming {
    background: #eaf3ff;
    color: #147cf5;
}

.status-ongoing {
    background: #e9fbff;
    color: #0ba8c1;
}

.status-completed {
    background: #ecfdf3;
    color: #159447;
}

.status-cancelled {
    background: #fff0ee;
    color: #e74b3c;
}


/* ========================= TITLE ========================= */

.notice-title-section {
    padding: 12px 26px 24px;
    border-bottom: 1px solid #edf0f5;
}

.notice-title-section h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 750;
    color: #172033;
}

.notice-subtitle {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    color: #64748b;
    font-size: 13px;
}

.notice-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}


/* ========================= INFO GRID ========================= */

.event-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    border-bottom: 1px solid #edf0f5;
}

.event-info-item {
    padding: 21px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-right: 1px solid #edf0f5;
}

.event-info-item:last-child {
    border-right: none;
}

.info-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon.blue {
    background: #eaf3ff;
    color: #147cf5;
}

.info-icon.orange {
    background: #fff6e5;
    color: #f59e0b;
}

.info-icon.cyan {
    background: #e9fbff;
    color: #12aeca;
}

.info-icon.red {
    background: #fff0ee;
    color: #ef5b4b;
}

.event-info-item span,
.notice-meta-item span {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
}

.event-info-item strong {
    display: block;
    color: #172033;
    font-size: 13px;
    font-weight: 650;
}


/* ========================= META ========================= */

.notice-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-bottom: 1px solid #edf0f5;
}

.notice-meta-item {
    padding: 18px 26px;
}

.notice-meta-item + .notice-meta-item {
    border-left: 1px solid #edf0f5;
}

.notice-meta-item strong {
    font-size: 13px;
    color: #172033;
}


/* ========================= DESCRIPTION ========================= */

.notice-description {
    padding: 22px 26px;
}

.description-heading {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #172033;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 10px;
}

.description-heading i {
    color: #147cf5;
}

.notice-description p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.7;
}


/* ========================= FOOTER ========================= */

.notice-footer {
    padding: 15px 26px;
    background: #fafbfd;
    border-top: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #94a3b8;
    font-size: 11px;
}

.notice-footer div:first-child {
    color: #64748b;
    font-weight: 600;
}

.notice-footer i {
    margin-right: 6px;
    color: #147cf5;
}


/* ========================= RESPONSIVE ========================= */

@media (max-width: 992px) {

    .event-info-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .event-info-item:nth-child(2) {
        border-right: none;
    }

    .event-info-item:nth-child(-n+2) {
        border-bottom: 1px solid #edf0f5;
    }

}

@media (max-width: 768px) {

    .event-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .event-header-actions {
        width: 100%;
    }

    .event-header-actions a,
    .event-header-actions button {
        flex: 1;
    }

    .notice-card {
        border-radius: 12px;
    }

    .notice-title-section h2 {
        font-size: 23px;
    }

    .event-info-grid {
        grid-template-columns: 1fr;
    }

    .event-info-item {
        border-right: none !important;
        border-bottom: 1px solid #edf0f5;
    }

    .event-info-item:last-child {
        border-bottom: none;
    }

    .notice-meta-grid {
        grid-template-columns: 1fr;
    }

    .notice-meta-item + .notice-meta-item {
        border-left: none;
        border-top: 1px solid #edf0f5;
    }

    .notice-footer {
        align-items: flex-start;
        flex-direction: column;
        gap: 6px;
    }

}

@media print {

    body {
        background: #fff !important;
    }

    .event-page-header {
        display: none !important;
    }

    .sports-event-notice-page {
        background: #fff !important;
    }

    .notice-card {
        max-width: none;
        border: 1px solid #ddd;
        box-shadow: none;
    }

}

</style>

@endsection