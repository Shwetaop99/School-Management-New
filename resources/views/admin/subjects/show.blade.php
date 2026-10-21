
@extends('layouts.app')

@section('title', 'View Subject | Admin')
@section('page-title', 'View Subject')

@section('content')

<div class="subject-view-page">
    <div class="subject-view-container">

        {{-- PAGE HEADER --}}
        <div class="subject-page-header">
            <div class="subject-heading-area">
                <div class="subject-heading-icon">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="subject-heading-text">
                    <div class="subject-breadcrumb">
                        ACADEMIC MANAGEMENT
                        <i class="fas fa-chevron-right"></i>
                        SUBJECTS
                        <i class="fas fa-chevron-right"></i>
                        VIEW SUBJECT
                    </div>

                    <h2>View Subject</h2>
                    <p>View complete subject information and class assignment.</p>
                </div>
            </div>

            <div class="subject-header-actions">
                <a
                    href="{{ route('admin.subjects.index') }}"
                    class="subject-btn subject-btn-secondary"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Subjects</span>
                </a>

                <a
                    href="{{ route('admin.subjects.edit', $subject->id) }}"
                    class="subject-btn subject-btn-primary"
                >
                    <i class="fas fa-edit"></i>
                    Edit Subject
                </a>
            </div>
        </div>

        {{-- SUBJECT OVERVIEW --}}
        <div class="subject-overview-card">

            <div class="subject-overview-icon">
                <i class="fas fa-book"></i>
            </div>

            <div class="subject-overview-info">
                <div class="subject-overview-label">SUBJECT DETAILS</div>

                <h3>{{ $subject->subject_name }}</h3>

                <div class="subject-overview-meta">
                    <span class="subject-meta-item">
                        <i class="fas fa-hashtag"></i>
                        {{ $subject->subject_code ?: 'No subject code' }}
                    </span>

                    <span class="subject-meta-divider"></span>

                    <span class="subject-meta-item">
                        <i class="fas fa-school"></i>

                        @if($subject->schoolClass)
                            {{ $subject->schoolClass->class_name }}
                            · Section {{ $subject->schoolClass->section }}
                        @else
                            Class not assigned
                        @endif
                    </span>
                </div>
            </div>

            <div class="subject-overview-status">
                @if($subject->status)
                    <span class="subject-status-badge subject-status-active">
                        <span class="subject-status-dot"></span>
                        Active
                    </span>
                @else
                    <span class="subject-status-badge subject-status-inactive">
                        <span class="subject-status-dot"></span>
                        Inactive
                    </span>
                @endif
            </div>

        </div>

        {{-- SUBJECT INFORMATION CARD --}}
        <div class="subject-details-card">

            <div class="subject-card-header">
                <div class="subject-card-heading">
                    <div class="subject-section-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>

                    <div>
                        <h3>Subject Information</h3>
                        <p>Complete details for this subject record.</p>
                    </div>
                </div>

                <span class="subject-record-label">
                    <i class="fas fa-file-alt"></i>
                    Subject Record
                </span>
            </div>

            <div class="subject-details-content">

                {{-- SECTION 01 --}}
                <div class="subject-details-section">

                    <div class="subject-section-title">
                        <span class="subject-section-number">01</span>

                        <div>
                            <h4>Basic Details</h4>
                            <p>Subject identification and current status.</p>
                        </div>
                    </div>

                    <div class="subject-details-grid">

                        {{-- SUBJECT NAME --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-book"></i>
                                Subject Name
                            </div>

                            <div class="subject-detail-value">
                                {{ $subject->subject_name }}
                            </div>
                        </div>

                        {{-- SUBJECT CODE --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-hashtag"></i>
                                Subject Code
                            </div>

                            <div class="subject-detail-value subject-code-value">
                                {{ $subject->subject_code ?: '—' }}
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-toggle-on"></i>
                                Subject Status
                            </div>

                            <div class="subject-detail-value">
                                @if($subject->status)
                                    <span class="subject-status-badge subject-status-active">
                                        <span class="subject-status-dot"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="subject-status-badge subject-status-inactive">
                                        <span class="subject-status-dot"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="subject-details-divider"></div>

                {{-- SECTION 02 --}}
                <div class="subject-details-section">

                    <div class="subject-section-title">
                        <span class="subject-section-number">02</span>

                        <div>
                            <h4>Class Assignment</h4>
                            <p>Academic class, section and academic year.</p>
                        </div>
                    </div>

                    <div class="subject-details-grid">

                        {{-- CLASS --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-school"></i>
                                Class
                            </div>

                            <div class="subject-detail-value">
                                @if($subject->schoolClass)
                                    <span class="subject-class-badge">
                                        <i class="fas fa-graduation-cap"></i>
                                        {{ $subject->schoolClass->class_name }}
                                    </span>
                                @else
                                    <span class="subject-empty-value">—</span>
                                @endif
                            </div>
                        </div>

                        {{-- SECTION --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-layer-group"></i>
                                Section
                            </div>

                            <div class="subject-detail-value">
                                @if($subject->schoolClass && $subject->schoolClass->section)
                                    <span class="subject-section-badge">
                                        {{ $subject->schoolClass->section }}
                                    </span>
                                @else
                                    <span class="subject-empty-value">—</span>
                                @endif
                            </div>
                        </div>

                        {{-- ACADEMIC YEAR --}}
                        <div class="subject-detail-item">
                            <div class="subject-detail-label">
                                <i class="fas fa-calendar-alt"></i>
                                Academic Year
                            </div>

                            <div class="subject-detail-value">
                                @if($subject->schoolClass && $subject->schoolClass->academic_year)
                                    <span class="subject-year-value">
                                        <i class="fas fa-calendar-check"></i>
                                        {{ $subject->schoolClass->academic_year }}
                                    </span>
                                @else
                                    <span class="subject-empty-value">—</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- CARD FOOTER --}}
            <div class="subject-details-footer">
                <div class="subject-footer-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Review the subject details before making changes.</span>
                </div>

                <div class="subject-footer-actions">
                    <a
                        href="{{ route('admin.subjects.index') }}"
                        class="subject-btn subject-btn-secondary"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Back to Subjects
                    </a>

                    <a
                        href="{{ route('admin.subjects.edit', $subject->id) }}"
                        class="subject-btn subject-btn-primary"
                    >
                        <i class="fas fa-edit"></i>
                        Edit Subject
                    </a>
                </div>
            </div>

        </div>

        {{-- PAGE FOOTER --}}
        <div class="subject-page-footer">
            <i class="fas fa-book-reader"></i>
            <span>School Management System</span>
            <span class="subject-footer-dot">•</span>
            <span>Subject Management</span>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    /* =========================================
       SCHOOL DIARY DESIGN SYSTEM
       VIEW SUBJECT
    ========================================= */

    .subject-view-page {
        --sd-bg: #f5f3e9;
        --sd-paper: #fffefa;
        --sd-ink: #263b35;
        --sd-green: #34775a;
        --sd-green-dark: #245a43;
        --sd-green-light: #eaf3e9;
        --sd-wood: #a87543;
        --sd-cyan: #527f79;
        --sd-red: #bd5148;
        --sd-border: #e5e1d3;

        width: 100%;
        min-height: 100%;
        padding: 5px 0 20px;
        color: var(--sd-ink);
        font-size: 12px;
        box-sizing: border-box;
    }

    .subject-view-page *,
    .subject-view-page *::before,
    .subject-view-page *::after {
        box-sizing: border-box;
    }

    .subject-view-container {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
    }

    /* PAGE HEADER */

    .subject-view-page .subject-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        padding: 15px 17px;
        border: 1px solid var(--sd-border);
        border-left: 4px solid var(--sd-green);
        border-radius: 9px;
        background: var(--sd-paper);
        box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
    }

    .subject-view-page .subject-heading-area {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .subject-view-page .subject-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        border: 1px solid #d6e6d8;
        border-radius: 9px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 19px;
    }

    .subject-view-page .subject-heading-text {
        min-width: 0;
    }

    .subject-view-page .subject-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 5px;
        color: #8b927f;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .75px;
    }

    .subject-view-page .subject-breadcrumb i {
        color: #b8b7a7;
        font-size: 8px;
    }

    .subject-view-page .subject-heading-text h2 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 22px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: -.45px;
    }

    .subject-view-page .subject-heading-text p {
        margin: 4px 0 0;
        color: #7e887d;
        font-size: 11px;
        line-height: 1.5;
    }

    .subject-view-page .subject-header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
    }

    /* BUTTONS */

    .subject-view-page .subject-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 9px 13px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.3;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, transform .2s ease;
    }

    .subject-view-page .subject-btn-primary {
        border-color: var(--sd-green);
        background: var(--sd-green);
        color: #fffefa;
    }

    .subject-view-page .subject-btn-primary:hover {
        border-color: var(--sd-green-dark);
        background: var(--sd-green-dark);
        color: #fff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .subject-view-page .subject-btn-secondary {
        border-color: var(--sd-border);
        background: #fffefa;
        color: #687367;
    }

    .subject-view-page .subject-btn-secondary:hover {
        border-color: #d2d6c5;
        background: #f1eee3;
        color: var(--sd-ink);
        text-decoration: none;
    }

    .subject-view-page .subject-btn:focus-visible {
        outline: 2px solid var(--sd-green);
        outline-offset: 3px;
    }

    /* SUBJECT OVERVIEW */

    .subject-view-page .subject-overview-card {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 18px;
        padding: 19px 21px;
        border: 1px solid #d9e3d4;
        border-radius: 10px;
        background: linear-gradient(115deg, #f1f6e9 0%, #fffefa 75%);
        box-shadow: 0 3px 14px rgba(38, 59, 53, .035);
    }

    .subject-view-page .subject-overview-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 55px;
        width: 55px;
        height: 55px;
        border: 1px solid #d2e3d1;
        border-radius: 11px;
        background: #e5f0df;
        color: var(--sd-green);
        font-size: 23px;
    }

    .subject-view-page .subject-overview-info {
        flex: 1;
        min-width: 0;
    }

    .subject-view-page .subject-overview-label {
        margin-bottom: 5px;
        color: #788c72;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .8px;
    }

    .subject-view-page .subject-overview-info h3 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 19px;
        line-height: 1.4;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .subject-view-page .subject-overview-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 7px;
    }

    .subject-view-page .subject-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #7d8878;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-view-page .subject-meta-item i {
        color: var(--sd-cyan);
        font-size: 10px;
    }

    .subject-view-page .subject-meta-divider {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #c0c6b7;
    }

    .subject-view-page .subject-overview-status {
        align-self: flex-start;
        flex-shrink: 0;
    }

    /* STATUS BADGES */

    .subject-view-page .subject-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.3;
        white-space: nowrap;
    }

    .subject-view-page .subject-status-active {
        border-color: #d2e6d1;
        background: #eaf3e9;
        color: #34775a;
    }

    .subject-view-page .subject-status-inactive {
        border-color: #edcec7;
        background: #fff0eb;
        color: #bd5148;
    }

    .subject-view-page .subject-status-dot {
        width: 6px;
        height: 6px;
        flex-shrink: 0;
        border-radius: 50%;
        background: currentColor;
    }

    /* DETAILS CARD */

    .subject-view-page .subject-details-card {
        overflow: hidden;
        border: 1px solid var(--sd-border);
        border-radius: 10px;
        background: var(--sd-paper);
        box-shadow: 0 4px 18px rgba(38, 59, 53, .045);
    }

    .subject-view-page .subject-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--sd-border);
        background: #fffdf7;
    }

    .subject-view-page .subject-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .subject-view-page .subject-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 35px;
        width: 35px;
        height: 35px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green);
        font-size: 14px;
    }

    .subject-view-page .subject-card-heading h3 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 14px;
        font-weight: 800;
    }

    .subject-view-page .subject-card-heading p {
        margin: 3px 0 0;
        color: #858b7d;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-view-page .subject-record-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 9px;
        border: 1px solid #e6e0ce;
        border-radius: 6px;
        background: #f8f4e8;
        color: #8d744e;
        font-size: 9px;
        font-weight: 800;
        white-space: nowrap;
    }

    .subject-view-page .subject-details-content {
        padding: 22px;
    }

    /* NUMBERED SECTIONS */

    .subject-view-page .subject-details-section {
        width: 100%;
    }

    .subject-view-page .subject-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .subject-view-page .subject-section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border: 1px solid #d7e5d7;
        border-radius: 8px;
        background: var(--sd-green-light);
        color: var(--sd-green-dark);
        font-size: 11px;
        font-weight: 800;
    }

    .subject-view-page .subject-section-title h4 {
        margin: 0;
        color: var(--sd-ink);
        font-size: 13px;
        font-weight: 800;
    }

    .subject-view-page .subject-section-title p {
        margin: 3px 0 0;
        color: #898e80;
        font-size: 10px;
        line-height: 1.5;
    }

    /* DETAIL GRID */

    .subject-view-page .subject-details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        border-top: 1px solid #ece9dd;
        border-left: 1px solid #ece9dd;
        border-radius: 7px;
        overflow: hidden;
    }

    .subject-view-page .subject-detail-item {
        min-width: 0;
        min-height: 90px;
        padding: 16px 17px;
        border-right: 1px solid #ece9dd;
        border-bottom: 1px solid #ece9dd;
        background: #fffefa;
        transition: background .2s ease;
    }

    .subject-view-page .subject-detail-item:hover {
        background: #fcfbf4;
    }

    .subject-view-page .subject-detail-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 10px;
        color: #858b7d;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .45px;
    }

    .subject-view-page .subject-detail-label i {
        width: 14px;
        color: #8ba08a;
        font-size: 11px;
        text-align: center;
    }

    .subject-view-page .subject-detail-value {
        color: var(--sd-ink);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .subject-view-page .subject-code-value {
        color: #687d73;
        font-weight: 600;
        letter-spacing: .2px;
    }

    .subject-view-page .subject-class-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid #d6e4d2;
        border-radius: 6px;
        background: #edf4e8;
        color: var(--sd-green-dark);
        font-size: 11px;
        font-weight: 800;
    }

    .subject-view-page .subject-class-badge i {
        font-size: 11px;
    }

    .subject-view-page .subject-section-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 31px;
        padding: 5px 9px;
        border: 1px solid #e6d8c5;
        border-radius: 6px;
        background: #f8f0e4;
        color: #966b3f;
        font-size: 11px;
        font-weight: 800;
    }

    .subject-view-page .subject-year-value {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #527f79;
        font-size: 12px;
        font-weight: 800;
    }

    .subject-view-page .subject-year-value i {
        font-size: 12px;
    }

    .subject-view-page .subject-empty-value {
        color: #a6a696;
        font-weight: 500;
    }

    .subject-view-page .subject-details-divider {
        height: 1px;
        margin: 25px 0;
        background: var(--sd-border);
    }

    /* CARD FOOTER */

    .subject-view-page .subject-details-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 20px;
        border-top: 1px solid var(--sd-border);
        background: #fbfaf3;
    }

    .subject-view-page .subject-footer-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #898d7e;
        font-size: 10px;
        line-height: 1.5;
    }

    .subject-view-page .subject-footer-note i {
        color: var(--sd-cyan);
        font-size: 12px;
    }

    .subject-view-page .subject-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* PAGE FOOTER */

    .subject-view-page .subject-page-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        padding: 15px 8px 0;
        color: #969787;
        font-size: 10px;
    }

    .subject-view-page .subject-page-footer i {
        color: var(--sd-green);
    }

    .subject-view-page .subject-footer-dot {
        color: var(--sd-wood);
    }

    /* RESPONSIVE: TABLET */

    @media (max-width: 991.98px) {
        .subject-view-page .subject-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .subject-view-page .subject-header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .subject-view-page .subject-details-content {
            padding: 19px;
        }

        .subject-view-page .subject-details-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .subject-view-page .subject-footer-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    /* RESPONSIVE: MOBILE */

    @media (max-width: 767.98px) {
        .subject-view-page .subject-page-header {
            gap: 12px;
            padding: 13px;
        }

        .subject-view-page .subject-heading-icon {
            flex-basis: 40px;
            width: 40px;
            height: 40px;
            font-size: 17px;
        }

        .subject-view-page .subject-heading-text h2 {
            font-size: 19px;
        }

        .subject-view-page .subject-header-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .subject-view-page .subject-header-actions .subject-btn {
            width: 100%;
        }

        .subject-view-page .subject-overview-card {
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px;
        }

        .subject-view-page .subject-overview-icon {
            flex-basis: 45px;
            width: 45px;
            height: 45px;
            font-size: 19px;
        }

        .subject-view-page .subject-overview-info {
            flex-basis: calc(100% - 65px);
        }

        .subject-view-page .subject-overview-info h3 {
            font-size: 17px;
        }

        .subject-view-page .subject-overview-status {
            margin-left: 57px;
        }

        .subject-view-page .subject-card-header {
            padding: 13px 15px;
        }

        .subject-view-page .subject-details-content {
            padding: 17px;
        }

        .subject-view-page .subject-details-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .subject-view-page .subject-detail-item {
            min-height: auto;
            padding: 14px;
        }

        .subject-view-page .subject-details-divider {
            margin: 21px 0;
        }

        .subject-view-page .subject-details-footer {
            padding: 15px;
        }
    }

    /* RESPONSIVE: SMALL MOBILE */

    @media (max-width: 480px) {
        .subject-view-page {
            padding-top: 2px;
        }

        .subject-view-page .subject-page-header {
            gap: 10px;
            margin-bottom: 14px;
            padding: 11px;
        }

        .subject-view-page .subject-heading-area {
            gap: 9px;
        }

        .subject-view-page .subject-heading-icon {
            flex-basis: 35px;
            width: 35px;
            height: 35px;
            font-size: 15px;
        }

        .subject-view-page .subject-breadcrumb {
            gap: 4px;
            font-size: 8px;
            letter-spacing: .35px;
        }

        .subject-view-page .subject-heading-text h2 {
            font-size: 17px;
        }

        .subject-view-page .subject-heading-text p {
            font-size: 10px;
        }

        .subject-view-page .subject-header-actions {
            gap: 7px;
        }

        .subject-view-page .subject-btn {
            gap: 5px;
            min-height: 36px;
            padding: 8px 9px;
            font-size: 10px;
        }

        .subject-view-page .subject-overview-card {
            gap: 10px;
            padding: 13px;
        }

        .subject-view-page .subject-overview-icon {
            flex-basis: 39px;
            width: 39px;
            height: 39px;
            font-size: 16px;
        }

        .subject-view-page .subject-overview-info {
            flex-basis: calc(100% - 52px);
        }

        .subject-view-page .subject-overview-info h3 {
            font-size: 15px;
        }

        .subject-view-page .subject-overview-meta {
            gap: 7px;
        }

        .subject-view-page .subject-meta-item {
            font-size: 9px;
        }

        .subject-view-page .subject-overview-status {
            margin-left: 49px;
        }

        .subject-view-page .subject-card-header {
            align-items: flex-start;
            gap: 7px;
            padding: 12px;
        }

        .subject-view-page .subject-card-heading {
            gap: 8px;
        }

        .subject-view-page .subject-section-icon {
            flex-basis: 31px;
            width: 31px;
            height: 31px;
            font-size: 12px;
        }

        .subject-view-page .subject-card-heading h3 {
            font-size: 12px;
        }

        .subject-view-page .subject-card-heading p {
            font-size: 9px;
        }

        .subject-view-page .subject-record-label {
            gap: 4px;
            padding: 6px;
            font-size: 8px;
        }

        .subject-view-page .subject-details-content {
            padding: 14px 12px;
        }

        .subject-view-page .subject-section-title {
            gap: 8px;
            margin-bottom: 14px;
        }

        .subject-view-page .subject-section-number {
            flex-basis: 30px;
            width: 30px;
            height: 30px;
        }

        .subject-view-page .subject-section-title h4 {
            font-size: 12px;
        }

        .subject-view-page .subject-section-title p {
            font-size: 9px;
        }

        .subject-view-page .subject-detail-item {
            padding: 12px;
        }

        .subject-view-page .subject-detail-label {
            margin-bottom: 7px;
            font-size: 9px;
        }

        .subject-view-page .subject-detail-value {
            font-size: 12px;
        }

        .subject-view-page .subject-details-footer {
            gap: 12px;
            padding: 13px 12px;
        }

        .subject-view-page .subject-footer-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
        }

        .subject-view-page .subject-footer-actions .subject-btn {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .subject-view-page *,
        .subject-view-page *::before,
        .subject-view-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>
@endpush
