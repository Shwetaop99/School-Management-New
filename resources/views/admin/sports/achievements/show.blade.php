@extends('layouts.app')

@section('title', 'Achievement Details | Admin')

@section('content')

<div class="sports-achievement-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
        <div class="achievement-page-header">

            <div class="achievement-heading-wrapper">

                <div class="achievement-page-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div>
                    <div class="page-breadcrumb">
                        Sports Management
                        <span>/</span>
                        Achievements
                    </div>

                    <h2>Achievement Details</h2>

                    <p>
                        View complete achievement information and student performance details.
                    </p>
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


        {{-- =========================================================
             MAIN PROFILE CARD
        ========================================================== --}}
        <div class="achievement-profile-card">


            {{-- =====================================================
                 ACHIEVEMENT HERO
            ====================================================== --}}
            <div class="achievement-hero">

                <div class="hero-left">

                    <div class="hero-medal">

                        <div class="medal-glow"></div>

                        <i class="fas fa-medal"></i>

                    </div>

                    <div class="hero-content">

                        <div class="hero-label">
                            SPORTS ACHIEVEMENT
                        </div>

                        <h1>
                            {{ $achievement->title }}
                        </h1>

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


            {{-- =====================================================
                 QUICK HIGHLIGHTS
            ====================================================== --}}
            <div class="achievement-highlights">

                <div class="highlight-card">

                    <div class="highlight-icon blue">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <div>
                        <span>Student</span>

                        <strong>
                            {{ $achievement->student_name ?: 'Not specified' }}
                        </strong>
                    </div>

                </div>


                <div class="highlight-card">

                    <div class="highlight-icon orange">
                        <i class="fas fa-trophy"></i>
                    </div>

                    <div>
                        <span>Position / Award</span>

                        <strong>
                            {{ $achievement->position ?: 'Not specified' }}
                        </strong>
                    </div>

                </div>


                <div class="highlight-card">

                    <div class="highlight-icon cyan">
                        <i class="fas fa-running"></i>
                    </div>

                    <div>
                        <span>Sport</span>

                        <strong>
                            {{ $achievement->sport_name ?: 'Not specified' }}
                        </strong>

                    </div>

                </div>


                <div class="highlight-card">

                    <div class="highlight-icon red">
                        <i class="fas fa-award"></i>
                    </div>

                    <div>
                        <span>Achievement Type</span>

                        <strong>
                            {{ $achievement->achievement_type ?: 'Not specified' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 INFORMATION SECTION
            ====================================================== --}}
            <div class="details-content">


                {{-- STUDENT INFORMATION --}}
                <div class="details-panel">

                    <div class="panel-header">

                        <div class="panel-icon blue">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div>
                            <h3>Student Information</h3>
                            <p>Student and academic details</p>
                        </div>

                    </div>


                    <div class="details-grid">

                        <div class="detail-item">

                            <span class="detail-label">
                                Student Name
                            </span>

                            <div class="detail-value">

                                <div class="value-icon blue">
                                    <i class="fas fa-user"></i>
                                </div>

                                <span>
                                    {{ $achievement->student_name ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Class
                            </span>

                            <div class="detail-value">

                                <div class="value-icon cyan">
                                    <i class="fas fa-school"></i>
                                </div>

                                <span>
                                    {{ $achievement->class ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Section
                            </span>

                            <div class="detail-value">

                                <div class="value-icon orange">
                                    <i class="fas fa-users"></i>
                                </div>

                                <span>
                                    {{ $achievement->section ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Academic Year
                            </span>

                            <div class="detail-value">

                                <div class="value-icon red">
                                    <i class="fas fa-calendar"></i>
                                </div>

                                <span>
                                    {{ $achievement->academic_year ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- COMPETITION INFORMATION --}}
                <div class="details-panel">

                    <div class="panel-header">

                        <div class="panel-icon orange">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <div>
                            <h3>Competition Information</h3>
                            <p>Competition, venue and achievement details</p>
                        </div>

                    </div>


                    <div class="details-grid">

                        <div class="detail-item">

                            <span class="detail-label">
                                Competition Name
                            </span>

                            <div class="detail-value">

                                <div class="value-icon orange">
                                    <i class="fas fa-trophy"></i>
                                </div>

                                <span>
                                    {{ $achievement->competition_name ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Achievement Type
                            </span>

                            <div class="detail-value">

                                <div class="value-icon blue">
                                    <i class="fas fa-award"></i>
                                </div>

                                <span>
                                    {{ $achievement->achievement_type ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Position / Award
                            </span>

                            <div class="detail-value">

                                <div class="value-icon red">
                                    <i class="fas fa-medal"></i>
                                </div>

                                <span>
                                    {{ $achievement->position ?: 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Achievement Date
                            </span>

                            <div class="detail-value">

                                <div class="value-icon cyan">
                                    <i class="far fa-calendar-check"></i>
                                </div>

                                <span>
                                    {{ $achievement->achievement_date
                                        ? $achievement->achievement_date->format('d M Y')
                                        : 'Not specified' }}
                                </span>

                            </div>

                        </div>


                        <div class="detail-item full-width">

                            <span class="detail-label">
                                Venue
                            </span>

                            <div class="detail-value">

                                <div class="value-icon blue">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>

                                <span>
                                    {{ $achievement->venue ?: 'Not specified' }}
                                </span>

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

                            <p>
                                {{ $achievement->description }}
                            </p>

                        @else

                            <div class="description-empty">

                                <i class="far fa-file-alt"></i>

                                <span>
                                    No description available for this achievement.
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


            </div>


            {{-- =====================================================
                 FOOTER ACTIONS
            ====================================================== --}}
            <div class="achievement-footer">

                <div class="footer-left">

                    <div class="footer-status-icon">
                        <i class="fas fa-shield-check"></i>
                    </div>

                    <div>
                        <strong>Achievement Record</strong>

                        <span>
                            Information is stored in the sports management system.
                        </span>
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

                        <button type="submit"
                                class="footer-delete-btn">

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
   SPORTS ACHIEVEMENT DETAILS
========================================================= */

.sports-achievement-page {
    min-height: calc(100vh - 60px);
    background: #f6f8fb;
    color: #172033;
    font-family: 'Inter', sans-serif;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.achievement-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 24px;
}

.achievement-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.achievement-page-icon {
    width: 50px;
    height: 50px;
    flex: 0 0 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: linear-gradient(
        135deg,
        #147cf5 0%,
        #1268ca 100%
    );

    color: #fff;
    font-size: 20px;

    box-shadow:
        0 7px 18px rgba(20,124,245,.20);
}

.page-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;

    margin-bottom: 3px;

    color: #94a3b8;
    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .55px;
}

.page-breadcrumb span {
    color: #cbd5e1;
}

.achievement-heading-wrapper h2 {
    margin: 0;

    color: #172033;

    font-size: 23px;
    line-height: 1.25;
    font-weight: 750;
    letter-spacing: -.25px;
}

.achievement-heading-wrapper p {
    margin: 5px 0 0;

    color: #718096;
    font-size: 12px;
}


/* =========================================================
   HEADER ACTIONS
========================================================= */

.achievement-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-shrink: 0;
}

.achievement-back-btn,
.achievement-edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    min-height: 40px;

    border-radius: 9px;

    padding: 9px 15px;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    transition:
        background .2s ease,
        border-color .2s ease,
        color .2s ease,
        transform .2s ease,
        box-shadow .2s ease;
}

.achievement-back-btn {
    background: #fff;

    color: #64748b;

    border: 1px solid #e3e8ef;

    box-shadow: 0 2px 5px rgba(15,23,42,.03);
}

.achievement-back-btn:hover {
    background: #f8fafc;
    color: #172033;
    border-color: #d7dee8;
    transform: translateY(-1px);
}

.achievement-edit-btn {
    color: #fff;

    border: 1px solid transparent;

    background: linear-gradient(
        135deg,
        #147cf5 0%,
        #1268ca 100%
    );

    box-shadow:
        0 5px 14px rgba(20,124,245,.18);
}

.achievement-edit-btn:hover {
    color: #fff;
    transform: translateY(-1px);

    box-shadow:
        0 8px 18px rgba(20,124,245,.24);
}


/* =========================================================
   MAIN PROFILE CARD
========================================================= */

.achievement-profile-card {
    background: #fff;

    border: 1px solid #e9edf3;

    border-radius: 16px;

    overflow: hidden;

    box-shadow:
        0 5px 20px rgba(15,23,42,.055);
}


/* =========================================================
   ACHIEVEMENT HERO
========================================================= */

.achievement-hero {
    position: relative;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 25px;

    padding: 27px 28px;

    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #fbfdff 65%,
            #f6faff 100%
        );

    border-bottom: 1px solid #edf0f5;
}

.achievement-hero::after {
    content: '';

    position: absolute;

    left: 0;
    bottom: 0;

    width: 100%;
    height: 3px;

    background:
        linear-gradient(
            90deg,
            #147cf5,
            #21b8d6
        );
}

.hero-left {
    display: flex;
    align-items: center;
    gap: 17px;

    min-width: 0;
}

.hero-medal {
    position: relative;

    width: 64px;
    height: 64px;

    flex: 0 0 64px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 17px;

    background:
        linear-gradient(
            135deg,
            #ffb238 0%,
            #ff9d1c 100%
        );

    color: #fff;

    font-size: 25px;

    box-shadow:
        0 8px 20px rgba(255,178,56,.22);

    overflow: hidden;
}

.medal-glow {
    position: absolute;

    width: 45px;
    height: 45px;

    top: -20px;
    right: -15px;

    border-radius: 50%;

    background: rgba(255,255,255,.22);

    filter: blur(2px);
}

.hero-medal i {
    position: relative;
    z-index: 2;
}

.hero-content {
    min-width: 0;
}

.hero-label {
    margin-bottom: 4px;

    color: #147cf5;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: 1px;
}

.hero-content h1 {
    margin: 0 0 8px;

    color: #172033;

    font-size: 21px;
    line-height: 1.3;
    font-weight: 750;

    word-break: break-word;
}

.hero-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;

    color: #718096;

    font-size: 11px;
    font-weight: 600;
}

.hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.hero-meta i {
    color: #147cf5;
    font-size: 11px;
}


/* =========================================================
   STATUS
========================================================= */

.hero-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;

    min-width: 105px;

    padding: 9px 13px;

    border-radius: 11px;

    border: 1px solid;
}

.status-badge > span:last-child {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.status-badge strong {
    font-size: 12px;
    line-height: 1.2;
}

.status-badge small {
    font-size: 9px;
    font-weight: 600;
}

.status-indicator {
    width: 8px;
    height: 8px;

    flex: 0 0 8px;

    border-radius: 50%;
}

.status-badge.active {
    color: #15803d;

    background: #f0fdf4;

    border-color: #bbf7d0;
}

.status-badge.active .status-indicator {
    background: #22c55e;

    box-shadow:
        0 0 0 4px rgba(34,197,94,.10);
}

.status-badge.inactive {
    color: #dc2626;

    background: #fff5f5;

    border-color: #fecaca;
}

.status-badge.inactive .status-indicator {
    background: #ef4444;

    box-shadow:
        0 0 0 4px rgba(239,68,68,.10);
}


/* =========================================================
   HIGHLIGHTS
========================================================= */

.achievement-highlights {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 12px;

    padding: 18px 24px;

    background: #fafbfd;

    border-bottom: 1px solid #edf0f5;
}

.highlight-card {
    display: flex;
    align-items: center;

    gap: 11px;

    min-width: 0;

    padding: 13px 14px;

    background: #fff;

    border: 1px solid #edf0f5;

    border-radius: 11px;

    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}

.highlight-card:hover {
    border-color: #dce5ef;

    transform: translateY(-1px);

    box-shadow:
        0 5px 14px rgba(15,23,42,.05);
}

.highlight-icon {
    width: 36px;
    height: 36px;

    flex: 0 0 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 13px;
}

.highlight-icon.blue {
    color: #147cf5;
    background: #edf5ff;
}

.highlight-icon.cyan {
    color: #18b5d5;
    background: #e9fbfe;
}

.highlight-icon.orange {
    color: #f59e0b;
    background: #fff6e8;
}

.highlight-icon.red {
    color: #f65343;
    background: #fff0ee;
}

.highlight-card > div:last-child {
    min-width: 0;
}

.highlight-card span {
    display: block;

    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.highlight-card strong {
    display: block;

    overflow: hidden;

    color: #172033;

    font-size: 12px;
    font-weight: 700;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   DETAILS CONTENT
========================================================= */

.details-content {
    padding: 24px;
}

.details-panel {
    margin-bottom: 18px;

    border: 1px solid #edf0f5;

    border-radius: 13px;

    overflow: hidden;

    background: #fff;
}

.details-panel:last-child {
    margin-bottom: 0;
}

.panel-header {
    display: flex;
    align-items: center;

    gap: 11px;

    padding: 15px 17px;

    background: #fafbfd;

    border-bottom: 1px solid #edf0f5;
}

.panel-icon {
    width: 37px;
    height: 37px;

    flex: 0 0 37px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 13px;
}

.panel-icon.blue {
    background: #edf5ff;
    color: #147cf5;
}

.panel-icon.cyan {
    background: #e9fbfe;
    color: #18b5d5;
}

.panel-icon.orange {
    background: #fff6e8;
    color: #f59e0b;
}

.panel-icon.red {
    background: #fff0ee;
    color: #f65343;
}

.panel-header h3 {
    margin: 0;

    color: #172033;

    font-size: 13px;
    font-weight: 750;
}

.panel-header p {
    margin: 3px 0 0;

    color: #94a3b8;

    font-size: 10px;
}


/* =========================================================
   DETAIL GRID
========================================================= */

.details-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));
}

.detail-item {
    min-width: 0;

    padding: 17px;

    border-bottom: 1px solid #f0f2f6;
}

.detail-item:nth-child(odd) {
    border-right: 1px solid #f0f2f6;
}

.detail-item:nth-last-child(-n+2) {
    border-bottom: none;
}

.detail-item.full-width {
    grid-column: 1 / -1;

    border-right: none;
    border-bottom: none;
}

.detail-label {
    display: block;

    margin-bottom: 8px;

    color: #94a3b8;

    font-size: 9px;
    font-weight: 750;

    text-transform: uppercase;
    letter-spacing: .55px;
}

.detail-value {
    display: flex;
    align-items: center;

    gap: 10px;

    min-width: 0;

    color: #172033;

    font-size: 13px;
    font-weight: 650;
}

.detail-value span {
    min-width: 0;

    overflow-wrap: anywhere;
}

.value-icon {
    width: 32px;
    height: 32px;

    flex: 0 0 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    font-size: 11px;
}

.value-icon.blue {
    background: #edf5ff;
    color: #147cf5;
}

.value-icon.cyan {
    background: #e9fbfe;
    color: #18b5d5;
}

.value-icon.orange {
    background: #fff6e8;
    color: #f59e0b;
}

.value-icon.red {
    background: #fff0ee;
    color: #f65343;
}


/* =========================================================
   DESCRIPTION
========================================================= */

.description-content {
    min-height: 110px;

    padding: 20px;

    background: #fff;
}

.description-content p {
    margin: 0;

    color: #596579;

    font-size: 13px;

    line-height: 1.75;

    white-space: pre-line;
}

.description-icon {
    width: 31px;
    height: 31px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 10px;

    border-radius: 8px;

    background: #fff0ee;

    color: #f65343;

    font-size: 11px;
}

.description-empty {
    min-height: 70px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-direction: column;

    gap: 7px;

    color: #a0aec0;

    font-size: 11px;
}

.description-empty i {
    font-size: 20px;

    color: #cbd5e1;
}


/* =========================================================
   FOOTER
========================================================= */

.achievement-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 17px 24px;

    background: #fafbfd;

    border-top: 1px solid #edf0f5;
}

.footer-left {
    display: flex;
    align-items: center;

    gap: 10px;

    min-width: 0;
}

.footer-status-icon {
    width: 34px;
    height: 34px;

    flex: 0 0 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #edf5ff;

    color: #147cf5;

    font-size: 12px;
}

.footer-left strong {
    display: block;

    color: #344054;

    font-size: 11px;
    font-weight: 700;
}

.footer-left span {
    display: block;

    margin-top: 2px;

    color: #94a3b8;

    font-size: 9px;
}

.footer-actions {
    display: flex;
    align-items: center;

    gap: 8px;

    flex-shrink: 0;
}

.footer-actions form {
    margin: 0;
}

.footer-back-btn,
.footer-edit-btn,
.footer-delete-btn {
    min-height: 38px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 8px 13px;

    border-radius: 8px;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;

    transition:
        background .2s ease,
        border-color .2s ease,
        color .2s ease,
        transform .2s ease,
        box-shadow .2s ease;
}

.footer-back-btn {
    background: #fff;

    color: #64748b;

    border: 1px solid #dfe4ea;
}

.footer-back-btn:hover {
    background: #f8fafc;

    color: #172033;

    border-color: #d3dae4;
}

.footer-edit-btn {
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );

    color: #fff;

    border: 1px solid transparent;

    box-shadow:
        0 4px 11px rgba(20,124,245,.15);
}

.footer-edit-btn:hover {
    color: #fff;

    transform: translateY(-1px);

    box-shadow:
        0 7px 16px rgba(20,124,245,.22);
}

.footer-delete-btn {
    background: #fff;

    color: #e04435;

    border: 1px solid #ffd6d1;
}

.footer-delete-btn:hover {
    background: #fff5f4;

    color: #c93629;

    border-color: #ffc3bc;

    transform: translateY(-1px);
}


/* =========================================================
   LARGE TABLET
========================================================= */

@media (max-width: 1100px) {

    .achievement-highlights {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 768px) {

    .achievement-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .achievement-header-actions {
        width: 100%;
    }

    .achievement-header-actions a {
        flex: 1;
    }

    .achievement-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .hero-status {
        width: 100%;
    }

    .status-badge {
        width: 100%;
        justify-content: flex-start;
    }

    .details-content {
        padding: 16px;
    }

    .achievement-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .footer-left {
        width: 100%;
    }

    .footer-actions {
        width: 100%;
    }

    .footer-actions > *,
    .footer-actions form,
    .footer-actions a,
    .footer-actions button {
        flex: 1;
    }

    .footer-actions button {
        width: 100%;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 576px) {

    .achievement-page-header {
        margin-bottom: 18px;
    }

    .achievement-heading-wrapper {
        align-items: flex-start;
    }

    .achievement-page-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;

        border-radius: 12px;

        font-size: 17px;
    }

    .achievement-heading-wrapper h2 {
        font-size: 19px;
    }

    .achievement-heading-wrapper p {
        font-size: 11px;
    }

    .page-breadcrumb {
        display: none;
    }

    .achievement-header-actions {
        flex-direction: column;
    }

    .achievement-header-actions a {
        width: 100%;
        flex: none;
    }

    .achievement-hero {
        padding: 20px 17px;
    }

    .hero-left {
        align-items: flex-start;
    }

    .hero-medal {
        width: 52px;
        height: 52px;
        flex-basis: 52px;

        border-radius: 13px;

        font-size: 20px;
    }

    .hero-content h1 {
        font-size: 17px;
    }

    .hero-meta {
        gap: 8px 12px;
        font-size: 10px;
    }

    .achievement-highlights {
        grid-template-columns: 1fr;

        padding: 14px;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .detail-item,
    .detail-item:nth-child(odd),
    .detail-item:nth-last-child(-n+2) {
        border-right: none;
        border-bottom: 1px solid #f0f2f6;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-item.full-width {
        grid-column: auto;
    }

    .panel-header {
        padding: 13px 14px;
    }

    .detail-item {
        padding: 14px;
    }

    .description-content {
        padding: 16px;
    }

    .footer-actions {
        display: grid;

        grid-template-columns: 1fr 1fr;
    }

    .footer-actions form {
        grid-column: 1 / -1;
    }

    .footer-actions > a,
    .footer-actions form,
    .footer-actions button {
        width: 100%;
    }

}


/* =========================================================
   VERY SMALL DEVICES
========================================================= */

@media (max-width: 380px) {

    .achievement-heading-wrapper {
        gap: 10px;
    }

    .achievement-page-icon {
        width: 40px;
        height: 40px;
        flex-basis: 40px;
    }

    .achievement-heading-wrapper h2 {
        font-size: 17px;
    }

    .achievement-hero {
        padding: 17px 14px;
    }

    .hero-left {
        gap: 11px;
    }

    .hero-content h1 {
        font-size: 15px;
    }

    .details-content {
        padding: 12px;
    }

    .footer-actions {
        grid-template-columns: 1fr;
    }

    .footer-actions form {
        grid-column: auto;
    }

}

</style>

@endsection