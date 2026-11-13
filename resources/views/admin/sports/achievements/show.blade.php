@extends('layouts.app')

@section('title', 'Achievement Details | Admin')
@section('page-title', 'Achievement Details')

@section('content')

<div class="sports-achievement-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="achievement-page-header">
            <div class="achievement-heading-wrapper">
                <div class="achievement-page-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div class="achievement-heading-content">
                    <div class="page-breadcrumb">
                        Sports Management <span>/</span> Achievements
                    </div>

                    <h2>Achievement Details</h2>
                    <p>View complete achievement information and student performance details.</p>
                </div>
            </div>

            <div class="achievement-header-actions">
                <a href="{{ route('admin.sports.achievements.index') }}"
                   class="achievement-back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>

                <a href="{{ route('admin.sports.achievements.edit', $achievement) }}"
                   class="achievement-edit-btn">
                    <i class="fas fa-pen"></i>
                    <span>Edit Achievement</span>
                </a>
            </div>
        </div>

        {{-- MAIN PROFILE CARD --}}
        <div class="achievement-profile-card">

            {{-- ACHIEVEMENT HERO --}}
            <div class="achievement-hero">
                <div class="hero-left">
                    <div class="hero-medal">
                        <div class="medal-glow"></div>
                        <i class="fas fa-medal"></i>
                    </div>

                    <div class="hero-content">
                        <div class="hero-label">SPORTS ACHIEVEMENT</div>

                        <h1>{{ $achievement->title }}</h1>

                        <div class="hero-meta">
                            @if($achievement->sport_name)
                                <span>
                                    <i class="fas fa-futbol"></i>
                                    {{ $achievement->sport_name }}
                                </span>
                            @endif

                            @if($achievement->achievement_date)
                                <span>
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $achievement->achievement_date->format('d M Y') }}
                                </span>
                            @endif

                            @if($achievement->academic_year)
                                <span>
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $achievement->academic_year }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="hero-status">
                    @if($achievement->status === 'active')
                        <span class="status-badge active">
                            <span class="status-indicator"></span>
                            <span>
                                <strong>Active</strong>
                                <small>Currently active</small>
                            </span>
                        </span>
                    @else
                        <span class="status-badge inactive">
                            <span class="status-indicator"></span>
                            <span>
                                <strong>Inactive</strong>
                                <small>Currently inactive</small>
                            </span>
                        </span>
                    @endif
                </div>
            </div>

            {{-- QUICK HIGHLIGHTS --}}
            <div class="achievement-highlights">
                <div class="highlight-card">
                    <div class="highlight-icon green">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="highlight-info">
                        <span>Student</span>
                        <strong>{{ $achievement->student_name ?: 'Not specified' }}</strong>
                    </div>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon brown">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="highlight-info">
                        <span>Position / Award</span>
                        <strong>{{ $achievement->position ?: 'Not specified' }}</strong>
                    </div>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon cyan">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="highlight-info">
                        <span>Sport</span>
                        <strong>{{ $achievement->sport_name ?: 'Not specified' }}</strong>
                    </div>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon red">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="highlight-info">
                        <span>Achievement Type</span>
                        <strong>{{ $achievement->achievement_type ?: 'Not specified' }}</strong>
                    </div>
                </div>
            </div>

            {{-- DETAILS CONTENT --}}
            <div class="details-content">

                {{-- STUDENT INFORMATION --}}
                <div class="details-panel">
                    <div class="panel-header">
                        <div class="panel-icon green">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h3>Student Information</h3>
                            <p>Student and academic details</p>
                        </div>
                    </div>

                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Student Name</span>
                            <div class="detail-value">
                                <div class="value-icon green"><i class="fas fa-user"></i></div>
                                <span>{{ $achievement->student_name ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Class</span>
                            <div class="detail-value">
                                <div class="value-icon cyan"><i class="fas fa-school"></i></div>
                                <span>{{ $achievement->class ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Section</span>
                            <div class="detail-value">
                                <div class="value-icon brown"><i class="fas fa-users"></i></div>
                                <span>{{ $achievement->section ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Academic Year</span>
                            <div class="detail-value">
                                <div class="value-icon red"><i class="fas fa-calendar"></i></div>
                                <span>{{ $achievement->academic_year ?: 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COMPETITION INFORMATION --}}
                <div class="details-panel">
                    <div class="panel-header">
                        <div class="panel-icon brown">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <h3>Competition Information</h3>
                            <p>Competition, venue and achievement details</p>
                        </div>
                    </div>

                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Competition Name</span>
                            <div class="detail-value">
                                <div class="value-icon brown"><i class="fas fa-trophy"></i></div>
                                <span>{{ $achievement->competition_name ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Achievement Type</span>
                            <div class="detail-value">
                                <div class="value-icon green"><i class="fas fa-award"></i></div>
                                <span>{{ $achievement->achievement_type ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Position / Award</span>
                            <div class="detail-value">
                                <div class="value-icon red"><i class="fas fa-medal"></i></div>
                                <span>{{ $achievement->position ?: 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Achievement Date</span>
                            <div class="detail-value">
                                <div class="value-icon cyan"><i class="far fa-calendar-check"></i></div>
                                <span>
                                    {{ $achievement->achievement_date
                                        ? $achievement->achievement_date->format('d M Y')
                                        : 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-item full-width">
                            <span class="detail-label">Venue</span>
                            <div class="detail-value">
                                <div class="value-icon green"><i class="fas fa-map-marker-alt"></i></div>
                                <span>{{ $achievement->venue ?: 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="details-panel description-panel">
                    <div class="panel-header">
                        <div class="panel-icon red">
                            <i class="fas fa-align-left"></i>
                        </div>
                        <div>
                            <h3>Description</h3>
                            <p>Additional information about this achievement</p>
                        </div>
                    </div>

                    <div class="description-content">
                        @if($achievement->description)
                            <div class="description-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p>{{ $achievement->description }}</p>
                        @else
                            <div class="description-empty">
                                <i class="far fa-file-alt"></i>
                                <span>No description available for this achievement.</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- FOOTER ACTIONS --}}
            <div class="achievement-footer">
                <div class="footer-left">
                    <div class="footer-status-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <strong>Achievement Record</strong>
                        <span>Information is stored in the sports management system.</span>
                    </div>
                </div>

                <div class="footer-actions">
                    <a href="{{ route('admin.sports.achievements.index') }}"
                       class="footer-back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Achievements
                    </a>

                    <a href="{{ route('admin.sports.achievements.edit', $achievement) }}"
                       class="footer-edit-btn">
                        <i class="fas fa-pen"></i>
                        Edit Achievement
                    </a>

                    <form action="{{ route('admin.sports.achievements.destroy', $achievement) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this achievement?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="footer-delete-btn">
                            <i class="fas fa-trash-alt"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* =========================================================
   SCHOOL DIARY THEME — SPORTS ACHIEVEMENT DETAILS
   All styles are scoped to this page.
========================================================= */

.sports-achievement-page {
    --achievement-ink: #263b35;
    --achievement-ink-dark: #1d302b;
    --achievement-green: #34775a;
    --achievement-green-dark: #245a43;
    --achievement-green-soft: #eaf3e9;
    --achievement-paper: #fffefa;
    --achievement-cream: #f5f3e9;
    --achievement-muted: #849087;
    --achievement-border: #e5e1d3;
    --achievement-brown: #a87543;
    --achievement-brown-soft: #f7eee3;
    --achievement-red: #bd5148;
    --achievement-red-soft: #fff0ec;
    --achievement-cyan: #527f79;

    min-height: calc(100vh - 60px);
    background: var(--achievement-cream);
    color: var(--achievement-ink);
    font-family: 'Inter', sans-serif;
}

.sports-achievement-page .container-fluid {
    max-width: 1600px;
}

/* PAGE HEADER */
.sports-achievement-page .achievement-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    margin-bottom: 23px;
}

.sports-achievement-page .achievement-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.sports-achievement-page .achievement-page-icon {
    width: 51px;
    height: 51px;
    flex: 0 0 51px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cbdcc9;
    border-radius: 13px;
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
    font-size: 20px;
}

.sports-achievement-page .achievement-heading-content {
    min-width: 0;
}

.sports-achievement-page .page-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 4px;
    color: var(--achievement-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.sports-achievement-page .page-breadcrumb span {
    color: #b8b9a9;
}

.sports-achievement-page .achievement-heading-wrapper h2 {
    margin: 0;
    color: var(--achievement-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 27px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.4px;
}

.sports-achievement-page .achievement-heading-wrapper p {
    margin: 6px 0 0;
    color: #778078;
    font-size: 12px;
    line-height: 1.6;
}

.sports-achievement-page .achievement-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-shrink: 0;
}

/* BUTTONS */
.sports-achievement-page .achievement-back-btn,
.sports-achievement-page .achievement-edit-btn,
.sports-achievement-page .footer-back-btn,
.sports-achievement-page .footer-edit-btn,
.sports-achievement-page .footer-delete-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 9px 14px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 750;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
}

.sports-achievement-page .achievement-back-btn,
.sports-achievement-page .footer-back-btn {
    background: var(--achievement-paper);
    color: var(--achievement-ink);
    border-color: var(--achievement-border);
}

.sports-achievement-page .achievement-back-btn:hover,
.sports-achievement-page .footer-back-btn:hover {
    background: #f0eee3;
    color: var(--achievement-ink-dark);
    border-color: #cfcbbb;
}

.sports-achievement-page .achievement-edit-btn,
.sports-achievement-page .footer-edit-btn {
    background: var(--achievement-green);
    color: #fff;
    border-color: var(--achievement-green);
}

.sports-achievement-page .achievement-edit-btn:hover,
.sports-achievement-page .footer-edit-btn:hover {
    background: var(--achievement-green-dark);
    border-color: var(--achievement-green-dark);
    color: #fff;
    transform: translateY(-1px);
}

.sports-achievement-page .footer-delete-btn {
    background: var(--achievement-paper);
    color: var(--achievement-red);
    border-color: #e8c8bf;
}

.sports-achievement-page .footer-delete-btn:hover {
    background: var(--achievement-red-soft);
    border-color: #dca69b;
    color: #963d35;
}

/* MAIN CARD */
.sports-achievement-page .achievement-profile-card {
    overflow: hidden;
    border: 1px solid var(--achievement-border);
    border-radius: 12px;
    background: var(--achievement-paper);
    box-shadow: 0 4px 16px rgba(38, 59, 53, .045);
}

/* ACHIEVEMENT HERO */
.sports-achievement-page .achievement-hero {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 27px 28px;
    border-bottom: 1px solid var(--achievement-border);
    background: var(--achievement-paper);
}

.sports-achievement-page .achievement-hero::after {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--achievement-green);
    content: '';
}

.sports-achievement-page .hero-left {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
}

.sports-achievement-page .hero-medal {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    flex: 0 0 64px;
    overflow: hidden;
    border: 1px solid #e6d3b8;
    border-radius: 12px;
    background: var(--achievement-brown-soft);
    color: var(--achievement-brown);
    font-size: 26px;
}

.sports-achievement-page .medal-glow {
    position: absolute;
    top: -20px;
    right: -15px;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(168, 117, 67, .12);
}

.sports-achievement-page .hero-medal i {
    position: relative;
    z-index: 1;
}

.sports-achievement-page .hero-content {
    min-width: 0;
}

.sports-achievement-page .hero-label {
    margin-bottom: 5px;
    color: var(--achievement-green);
    font-size: 9px;
    font-weight: 850;
    letter-spacing: 1.35px;
}

.sports-achievement-page .hero-content h1 {
    margin: 0 0 9px;
    color: var(--achievement-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 25px;
    font-weight: 700;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.sports-achievement-page .hero-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px 16px;
    color: #778078;
    font-size: 11px;
    font-weight: 600;
}

.sports-achievement-page .hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.sports-achievement-page .hero-meta i {
    color: var(--achievement-green);
}

/* STATUS */
.sports-achievement-page .hero-status {
    flex-shrink: 0;
}

.sports-achievement-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    min-width: 120px;
    padding: 9px 12px;
    border: 1px solid;
    border-radius: 8px;
}

.sports-achievement-page .status-badge > span:last-child {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.sports-achievement-page .status-badge strong {
    font-size: 11px;
    line-height: 1.3;
}

.sports-achievement-page .status-badge small {
    font-size: 9px;
    font-weight: 600;
}

.sports-achievement-page .status-indicator {
    width: 8px;
    height: 8px;
    flex: 0 0 8px;
    border-radius: 50%;
}

.sports-achievement-page .status-badge.active {
    color: var(--achievement-green-dark);
    background: var(--achievement-green-soft);
    border-color: #cbdcc9;
}

.sports-achievement-page .status-badge.active .status-indicator {
    background: var(--achievement-green);
}

.sports-achievement-page .status-badge.inactive {
    color: var(--achievement-red);
    background: var(--achievement-red-soft);
    border-color: #e8c8bf;
}

.sports-achievement-page .status-badge.inactive .status-indicator {
    background: var(--achievement-red);
}

/* QUICK HIGHLIGHTS */
.sports-achievement-page .achievement-highlights {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    padding: 19px 23px;
    border-bottom: 1px solid var(--achievement-border);
    background: #f9f8f0;
}

.sports-achievement-page .highlight-card {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
    padding: 13px;
    border: 1px solid var(--achievement-border);
    border-radius: 8px;
    background: var(--achievement-paper);
    transition: border-color .2s ease, transform .2s ease;
}

.sports-achievement-page .highlight-card:hover {
    border-color: #c8d5c3;
    transform: translateY(-1px);
}

.sports-achievement-page .highlight-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border-radius: 8px;
    font-size: 13px;
}

.sports-achievement-page .highlight-icon.green,
.sports-achievement-page .panel-icon.green,
.sports-achievement-page .value-icon.green {
    color: var(--achievement-green);
    background: var(--achievement-green-soft);
}

.sports-achievement-page .highlight-icon.brown,
.sports-achievement-page .panel-icon.brown,
.sports-achievement-page .value-icon.brown {
    color: var(--achievement-brown);
    background: var(--achievement-brown-soft);
}

.sports-achievement-page .highlight-icon.cyan,
.sports-achievement-page .panel-icon.cyan,
.sports-achievement-page .value-icon.cyan {
    color: var(--achievement-cyan);
    background: #eaf1ed;
}

.sports-achievement-page .highlight-icon.red,
.sports-achievement-page .panel-icon.red,
.sports-achievement-page .value-icon.red {
    color: var(--achievement-red);
    background: var(--achievement-red-soft);
}

.sports-achievement-page .highlight-info {
    min-width: 0;
}

.sports-achievement-page .highlight-info span {
    display: block;
    margin-bottom: 4px;
    color: var(--achievement-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .55px;
    text-transform: uppercase;
}

.sports-achievement-page .highlight-info strong {
    display: block;
    overflow: hidden;
    color: var(--achievement-ink);
    font-size: 12px;
    font-weight: 750;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* DETAILS */
.sports-achievement-page .details-content {
    padding: 24px;
}

.sports-achievement-page .details-panel {
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--achievement-border);
    border-radius: 9px;
    background: var(--achievement-paper);
}

.sports-achievement-page .details-panel:last-child {
    margin-bottom: 0;
}

.sports-achievement-page .panel-header {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 15px 17px;
    border-bottom: 1px solid var(--achievement-border);
    background: #f8f7ef;
}

.sports-achievement-page .panel-header::after {
    align-self: stretch;
    order: -1;
    width: 3px;
    min-height: 34px;
    margin: -3px 1px -3px -17px;
    background: var(--achievement-green);
    content: '';
}

.sports-achievement-page .panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    border: 1px solid var(--achievement-border);
    border-radius: 8px;
    font-size: 13px;
}

.sports-achievement-page .panel-header h3 {
    margin: 0;
    color: var(--achievement-ink-dark);
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 16px;
    font-weight: 700;
}

.sports-achievement-page .panel-header p {
    margin: 4px 0 0;
    color: var(--achievement-muted);
    font-size: 10px;
    line-height: 1.5;
}

/* DETAIL GRID */
.sports-achievement-page .details-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.sports-achievement-page .detail-item {
    min-width: 0;
    padding: 17px;
    border-bottom: 1px solid #eeece2;
}

.sports-achievement-page .detail-item:nth-child(odd) {
    border-right: 1px solid #eeece2;
}

.sports-achievement-page .detail-item:nth-last-child(-n+2) {
    border-bottom: none;
}

.sports-achievement-page .detail-item.full-width {
    grid-column: 1 / -1;
    border-right: none;
    border-bottom: none;
}

.sports-achievement-page .detail-label {
    display: block;
    margin-bottom: 9px;
    color: var(--achievement-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.sports-achievement-page .detail-value {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    color: var(--achievement-ink);
    font-size: 12px;
    font-weight: 700;
    line-height: 1.6;
}

.sports-achievement-page .detail-value span {
    min-width: 0;
    overflow-wrap: anywhere;
}

.sports-achievement-page .value-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    border: 1px solid rgba(132, 144, 135, .14);
    border-radius: 7px;
    font-size: 11px;
}

/* DESCRIPTION */
.sports-achievement-page .description-content {
    min-height: 105px;
    padding: 20px;
    background: var(--achievement-paper);
}

.sports-achievement-page .description-content p {
    margin: 0;
    color: #59675e;
    font-size: 12px;
    line-height: 1.85;
    overflow-wrap: anywhere;
    white-space: pre-line;
}

.sports-achievement-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    margin-bottom: 10px;
    border: 1px solid #e8c8bf;
    border-radius: 7px;
    background: var(--achievement-red-soft);
    color: var(--achievement-red);
    font-size: 11px;
}

.sports-achievement-page .description-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    min-height: 75px;
    color: var(--achievement-muted);
    font-size: 11px;
    text-align: center;
}

.sports-achievement-page .description-empty i {
    color: #b5b7a9;
    font-size: 21px;
}

/* FOOTER */
.sports-achievement-page .achievement-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 17px 23px;
    border-top: 1px solid var(--achievement-border);
    background: #f8f7ef;
}

.sports-achievement-page .footer-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.sports-achievement-page .footer-status-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #cbdcc9;
    border-radius: 8px;
    background: var(--achievement-green-soft);
    color: var(--achievement-green);
    font-size: 12px;
}

.sports-achievement-page .footer-left strong {
    display: block;
    color: var(--achievement-ink);
    font-size: 11px;
    font-weight: 800;
}

.sports-achievement-page .footer-left span {
    display: block;
    margin-top: 3px;
    color: var(--achievement-muted);
    font-size: 9px;
    line-height: 1.5;
}

.sports-achievement-page .footer-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    flex-shrink: 0;
}

.sports-achievement-page .footer-actions form {
    margin: 0;
}

/* TABLET */
@media (max-width: 1100px) {
    .sports-achievement-page .achievement-highlights {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sports-achievement-page .achievement-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-achievement-page .footer-actions {
        width: 100%;
    }
}

/* SMALL TABLET */
@media (max-width: 768px) {
    .sports-achievement-page .achievement-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-achievement-page .achievement-header-actions {
        width: 100%;
    }

    .sports-achievement-page .achievement-header-actions a {
        flex: 1;
    }

    .sports-achievement-page .achievement-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-achievement-page .hero-status,
    .sports-achievement-page .status-badge {
        width: 100%;
    }

    .sports-achievement-page .details-content {
        padding: 16px;
    }

    .sports-achievement-page .footer-actions > a,
    .sports-achievement-page .footer-actions form {
        flex: 1;
    }

    .sports-achievement-page .footer-actions form button {
        width: 100%;
    }
}

/* MOBILE */
@media (max-width: 576px) {
    .sports-achievement-page .achievement-page-header {
        margin-bottom: 17px;
    }

    .sports-achievement-page .achievement-heading-wrapper {
        align-items: flex-start;
        gap: 11px;
    }

    .sports-achievement-page .achievement-page-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 17px;
    }

    .sports-achievement-page .achievement-heading-wrapper h2 {
        font-size: 22px;
    }

    .sports-achievement-page .achievement-heading-wrapper p {
        font-size: 11px;
    }

    .sports-achievement-page .page-breadcrumb {
        font-size: 8px;
    }

    .sports-achievement-page .achievement-header-actions {
        flex-direction: column;
    }

    .sports-achievement-page .achievement-header-actions a {
        width: 100%;
        flex: none;
    }

    .sports-achievement-page .achievement-hero {
        padding: 20px 17px 20px 21px;
    }

    .sports-achievement-page .hero-left {
        align-items: flex-start;
        gap: 12px;
    }

    .sports-achievement-page .hero-medal {
        width: 49px;
        height: 49px;
        flex-basis: 49px;
        font-size: 20px;
    }

    .sports-achievement-page .hero-content h1 {
        font-size: 20px;
    }

    .sports-achievement-page .hero-meta {
        gap: 8px 12px;
        font-size: 10px;
    }

    .sports-achievement-page .achievement-highlights {
        grid-template-columns: 1fr;
        padding: 13px;
    }

    .sports-achievement-page .details-grid {
        grid-template-columns: 1fr;
    }

    .sports-achievement-page .detail-item,
    .sports-achievement-page .detail-item:nth-child(odd),
    .sports-achievement-page .detail-item:nth-last-child(-n+2) {
        border-right: none;
        border-bottom: 1px solid #eeece2;
    }

    .sports-achievement-page .detail-item:last-child {
        border-bottom: none;
    }

    .sports-achievement-page .detail-item.full-width {
        grid-column: auto;
    }

    .sports-achievement-page .panel-header {
        padding: 13px 14px;
    }

    .sports-achievement-page .panel-header h3 {
        font-size: 15px;
    }

    .sports-achievement-page .detail-item {
        padding: 14px;
    }

    .sports-achievement-page .description-content {
        padding: 16px;
    }

    .sports-achievement-page .achievement-footer {
        padding: 15px;
    }

    .sports-achievement-page .footer-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .sports-achievement-page .footer-actions > a {
        width: 100%;
        white-space: normal;
        text-align: center;
    }

    .sports-achievement-page .footer-actions form {
        grid-column: 1 / -1;
    }

    .sports-achievement-page .footer-actions form,
    .sports-achievement-page .footer-actions button {
        width: 100%;
    }
}

/* VERY SMALL DEVICES */
@media (max-width: 380px) {
    .sports-achievement-page .achievement-heading-wrapper h2 {
        font-size: 19px;
    }

    .sports-achievement-page .achievement-hero {
        padding: 17px 13px 17px 17px;
    }

    .sports-achievement-page .hero-content h1 {
        font-size: 17px;
    }

    .sports-achievement-page .details-content {
        padding: 11px;
    }

    .sports-achievement-page .footer-actions {
        grid-template-columns: 1fr;
    }

    .sports-achievement-page .footer-actions form {
        grid-column: auto;
    }
}
</style>

@endsection
