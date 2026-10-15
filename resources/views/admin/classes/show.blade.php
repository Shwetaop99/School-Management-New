
@extends('layouts.app')

@section('title', 'View Class | Admin')

@section('page-title', 'View Class')

@section('content')

<div class="class-view-page">
    <div class="class-view-container">

        {{-- PAGE HEADER --}}
        <div class="class-page-header">
            <div class="class-heading-area">
                <div class="class-heading-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <div class="class-heading-copy">
                    <div class="class-breadcrumb">
                        ACADEMIC MANAGEMENT <span>/</span> CLASSES <span>/</span> VIEW CLASS
                    </div>

                    <h2>View Class</h2>
                    <p>View class information, academic year and current status.</p>
                </div>
            </div>

            <a href="{{ route('admin.classes.index') }}" class="class-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Classes</span>
            </a>
        </div>

        {{-- CLASS DETAILS CARD --}}
        <div class="class-view-card">

            {{-- CARD HEADER --}}
            <div class="class-view-card-header">
                <div class="class-section-heading">
                    <div class="class-section-number">01</div>

                    <div class="class-section-icon">
                        <i class="fas fa-school"></i>
                    </div>

                    <div>
                        <h3>Class Information</h3>
                        <p>Details saved for this school class.</p>
                    </div>
                </div>

                <div class="class-record-badge">
                    <i class="fas fa-check-circle"></i>
                    Class Record
                </div>
            </div>

            {{-- CARD BODY --}}
            <div class="class-view-card-body">

                {{-- CLASS OVERVIEW --}}
                <div class="class-overview">
                    <div class="class-overview-icon">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div class="class-overview-content">
                        <span class="class-overview-label">CLASS NAME</span>

                        <h3>{{ $schoolClass->class_name }}</h3>

                        <p>
                            Section {{ $schoolClass->section }}
                            <span class="class-overview-divider">•</span>
                            Academic Year {{ $schoolClass->academic_year }}
                        </p>
                    </div>

                    <div class="class-overview-status">
                        @if($schoolClass->status)
                            <span class="class-status-badge class-status-active">
                                <span class="class-status-dot"></span>
                                Active
                            </span>
                        @else
                            <span class="class-status-badge class-status-inactive">
                                <span class="class-status-dot"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                {{-- CLASS DETAILS SECTION --}}
                <div class="class-details-section">
                    <div class="class-section-label">
                        <span class="class-section-label-icon">
                            <i class="fas fa-list-alt"></i>
                        </span>
                        General Details
                    </div>

                    <div class="class-info-grid">

                        {{-- CLASS NAME --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-green">
                                <i class="fas fa-book"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Class Name</span>
                                <span class="class-info-value">
                                    {{ $schoolClass->class_name }}
                                </span>
                            </div>
                        </div>

                        {{-- SECTION --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-wood">
                                <i class="fas fa-users"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Section</span>
                                <span class="class-info-value">
                                    {{ $schoolClass->section }}
                                </span>
                            </div>
                        </div>

                        {{-- ACADEMIC YEAR --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-cyan">
                                <i class="fas fa-calendar-alt"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Academic Year</span>
                                <span class="class-info-value">
                                    {{ $schoolClass->academic_year }}
                                </span>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-green">
                                <i class="fas fa-toggle-on"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Current Status</span>

                                @if($schoolClass->status)
                                    <span class="class-status-badge class-status-active">
                                        <span class="class-status-dot"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="class-status-badge class-status-inactive">
                                        <span class="class-status-dot"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RECORD TIMESTAMPS --}}
                <div class="class-details-section class-timestamps-section">
                    <div class="class-section-label">
                        <span class="class-section-label-icon class-timestamp-icon">
                            <i class="fas fa-history"></i>
                        </span>
                        Record Information
                    </div>

                    <div class="class-info-grid">

                        {{-- CREATED AT --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-wood">
                                <i class="fas fa-calendar-plus"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Created At</span>
                                <span class="class-info-value class-date-value">
                                    {{ $schoolClass->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- UPDATED AT --}}
                        <div class="class-info-item">
                            <div class="class-info-icon class-icon-cyan">
                                <i class="fas fa-clock"></i>
                            </div>

                            <div class="class-info-content">
                                <span class="class-info-label">Last Updated</span>
                                <span class="class-info-value class-date-value">
                                    {{ $schoolClass->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ACTION BAR --}}
                <div class="class-view-actions">
                    <div class="class-action-note">
                        <i class="fas fa-info-circle"></i>
                        Review the class details or edit the record if needed.
                    </div>

                    <div class="class-action-buttons">
                        <a
                            href="{{ route('admin.classes.index') }}"
                            class="class-btn class-btn-light"
                        >
                            <i class="fas fa-arrow-left"></i>
                            Back to Classes
                        </a>

                        <a
                            href="{{ route('admin.classes.edit', $schoolClass->id) }}"
                            class="class-btn class-btn-primary"
                        >
                            <i class="fas fa-edit"></i>
                            Edit Class
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- FOOTER NOTE --}}
        <div class="class-page-footer">
            <i class="fas fa-shield-alt"></i>
            This page displays the details of the selected class record.
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — VIEW CLASS
   Scoped styles: .class-view-page
   ========================================================= */

.class-view-page {
    --class-bg: #f5f3e9;
    --class-paper: #fffefa;
    --class-ink: #263b35;
    --class-green: #34775a;
    --class-green-dark: #245a43;
    --class-green-light: #eaf3e9;
    --class-wood: #a87543;
    --class-cyan: #527f79;
    --class-red: #bd5148;
    --class-border: #e5e1d3;
    --class-muted: #7a8279;

    width: 100%;
    min-width: 0;
    padding: 4px 0 18px;
    color: var(--class-ink);
    font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
    font-size: 12px;
}

.class-view-page *,
.class-view-page *::before,
.class-view-page *::after {
    box-sizing: border-box;
}

.class-view-container {
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
}

/* PAGE HEADER */

.class-view-page .class-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
    padding: 5px 0 5px 15px;
    border-left: 4px solid var(--class-green);
}

.class-view-page .class-heading-area {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.class-view-page .class-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex: 0 0 45px;
    border: 1px solid #d5e5d6;
    border-radius: 10px;
    background: var(--class-green-light);
    color: var(--class-green);
    font-size: 19px;
}

.class-view-page .class-heading-copy {
    min-width: 0;
}

.class-view-page .class-breadcrumb {
    margin-bottom: 5px;
    color: var(--class-muted);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.05px;
    line-height: 1.5;
}

.class-view-page .class-breadcrumb span {
    margin: 0 5px;
    color: var(--class-wood);
}

.class-view-page .class-page-header h2 {
    margin: 0;
    color: var(--class-ink);
    font-size: 22px;
    font-weight: 750;
    line-height: 1.25;
    letter-spacing: -.45px;
}

.class-view-page .class-page-header p {
    margin: 5px 0 0;
    color: var(--class-muted);
    font-size: 11px;
    line-height: 1.5;
}

.class-view-page .class-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 0 0 auto;
    min-height: 36px;
    padding: 8px 12px;
    border: 1px solid var(--class-border);
    border-radius: 7px;
    background: var(--class-paper);
    color: var(--class-green-dark);
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: background .18s ease, border-color .18s ease;
}

.class-view-page .class-back-btn:hover {
    border-color: #b9d1bd;
    background: var(--class-green-light);
    color: var(--class-green-dark);
    text-decoration: none;
}

/* MAIN CARD */

.class-view-page .class-view-card {
    width: 100%;
    max-width: 950px;
    overflow: hidden;
    border: 1px solid var(--class-border);
    border-radius: 10px;
    background: var(--class-paper);
    box-shadow: 0 3px 12px rgba(53, 65, 49, .045);
}

.class-view-page .class-view-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 19px;
    border-bottom: 1px solid var(--class-border);
    background: #fffdf7;
}

.class-view-page .class-section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.class-view-page .class-section-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    border: 1px solid #d7e5d6;
    border-radius: 7px;
    background: var(--class-green-light);
    color: var(--class-green-dark);
    font-size: 10px;
    font-weight: 800;
}

.class-view-page .class-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid #e7ddca;
    border-radius: 8px;
    background: #f7efe1;
    color: var(--class-wood);
    font-size: 14px;
}

.class-view-page .class-section-heading h3 {
    margin: 0;
    color: var(--class-ink);
    font-size: 13px;
    font-weight: 750;
    line-height: 1.4;
}

.class-view-page .class-section-heading p {
    margin: 3px 0 0;
    color: var(--class-muted);
    font-size: 10px;
    line-height: 1.4;
}

.class-view-page .class-record-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid #d7e5d6;
    border-radius: 20px;
    background: var(--class-green-light);
    color: var(--class-green-dark);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.class-view-page .class-record-badge i {
    font-size: 11px;
}

/* CARD BODY */

.class-view-page .class-view-card-body {
    padding: 20px;
}

/* CLASS OVERVIEW */

.class-view-page .class-overview {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 23px;
    padding: 17px;
    border: 1px solid #dce7d9;
    border-radius: 9px;
    background: linear-gradient(115deg, #f0f6eb 0%, #fffefa 100%);
}

.class-view-page .class-overview-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    border: 1px solid #d6e5d3;
    border-radius: 10px;
    background: #e4efdf;
    color: var(--class-green);
    font-size: 19px;
}

.class-view-page .class-overview-content {
    min-width: 0;
    flex: 1;
}

.class-view-page .class-overview-label {
    display: block;
    margin-bottom: 4px;
    color: var(--class-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .9px;
}

.class-view-page .class-overview-content h3 {
    margin: 0;
    color: var(--class-ink);
    font-size: 17px;
    font-weight: 750;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.class-view-page .class-overview-content p {
    margin: 4px 0 0;
    color: #6e7a6d;
    font-size: 10px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.class-view-page .class-overview-divider {
    padding: 0 4px;
    color: var(--class-wood);
}

.class-view-page .class-overview-status {
    flex: 0 0 auto;
}

/* SECTION LABELS */

.class-view-page .class-details-section {
    margin-bottom: 22px;
}

.class-view-page .class-timestamps-section {
    margin-bottom: 0;
}

.class-view-page .class-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: var(--class-ink);
    font-size: 11px;
    font-weight: 750;
}

.class-view-page .class-section-label-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    border: 1px solid #dce8dc;
    border-radius: 6px;
    background: #f0f5eb;
    color: var(--class-green);
    font-size: 11px;
}

.class-view-page .class-timestamp-icon {
    border-color: #e7ddca;
    background: #f7efe1;
    color: var(--class-wood);
}

/* DETAILS GRID */

.class-view-page .class-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    overflow: hidden;
    border: 1px solid var(--class-border);
    border-radius: 8px;
    background: var(--class-paper);
}

.class-view-page .class-info-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    min-width: 0;
    min-height: 88px;
    padding: 15px;
    border-bottom: 1px solid var(--class-border);
}

.class-view-page .class-info-item:nth-child(odd) {
    border-right: 1px solid var(--class-border);
}

.class-view-page .class-info-item:nth-last-child(-n+2) {
    border-bottom: none;
}

.class-view-page .class-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 13px;
}

.class-view-page .class-icon-green {
    border-color: #dce8d9;
    background: #eef5e9;
    color: var(--class-green);
}

.class-view-page .class-icon-wood {
    border-color: #ebdfcd;
    background: #f8f0e4;
    color: var(--class-wood);
}

.class-view-page .class-icon-cyan {
    border-color: #d6e6e1;
    background: #edf5f1;
    color: var(--class-cyan);
}

.class-view-page .class-info-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    min-width: 0;
    padding-top: 1px;
}

.class-view-page .class-info-label {
    display: block;
    color: var(--class-muted);
    font-size: 9px;
    font-weight: 750;
    letter-spacing: .65px;
    line-height: 1.5;
    text-transform: uppercase;
}

.class-view-page .class-info-value {
    display: block;
    color: var(--class-ink);
    font-size: 12px;
    font-weight: 700;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.class-view-page .class-date-value {
    font-size: 11px;
    font-weight: 600;
}

/* STATUS BADGES */

.class-view-page .class-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
    line-height: 1.2;
    white-space: nowrap;
}

.class-view-page .class-status-active {
    border-color: #d1e5d0;
    background: #eaf3e9;
    color: var(--class-green-dark);
}

.class-view-page .class-status-inactive {
    border-color: #edcfca;
    background: #fff0ed;
    color: var(--class-red);
}

.class-view-page .class-status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
    background: currentColor;
}

/* ACTION BAR */

.class-view-page .class-view-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 22px;
    padding-top: 17px;
    border-top: 1px solid var(--class-border);
}

.class-view-page .class-action-note {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    max-width: 280px;
    color: var(--class-muted);
    font-size: 10px;
    line-height: 1.6;
}

.class-view-page .class-action-note i {
    margin-top: 2px;
    color: var(--class-green);
}

.class-view-page .class-action-buttons {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

.class-view-page .class-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 36px;
    padding: 8px 12px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease,
        color .18s ease, box-shadow .18s ease;
}

.class-view-page .class-btn:focus-visible,
.class-view-page .class-back-btn:focus-visible {
    outline: 2px solid var(--class-cyan);
    outline-offset: 3px;
}

.class-view-page .class-btn-primary {
    border-color: var(--class-green);
    background: var(--class-green);
    color: #fffefa;
    box-shadow: 0 2px 4px rgba(36, 90, 67, .12);
}

.class-view-page .class-btn-primary:hover {
    border-color: var(--class-green-dark);
    background: var(--class-green-dark);
    color: #fffefa;
    box-shadow: 0 3px 7px rgba(36, 90, 67, .15);
}

.class-view-page .class-btn-light {
    border-color: var(--class-border);
    background: var(--class-paper);
    color: #687168;
}

.class-view-page .class-btn-light:hover {
    border-color: #d3cbb9;
    background: var(--class-bg);
    color: var(--class-ink);
}

/* FOOTER */

.class-view-page .class-page-footer {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 13px;
    color: #81877b;
    font-size: 10px;
    line-height: 1.5;
}

.class-view-page .class-page-footer i {
    color: var(--class-wood);
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .class-view-page .class-view-card {
        max-width: 100%;
    }

    .class-view-page .class-view-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .class-view-page .class-action-note {
        max-width: 100%;
    }

    .class-view-page .class-action-buttons {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 767.98px) {
    .class-view-page {
        padding-top: 2px;
    }

    .class-view-page .class-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
        padding-left: 11px;
    }

    .class-view-page .class-page-header h2 {
        font-size: 20px;
    }

    .class-view-page .class-view-card-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px;
    }

    .class-view-page .class-view-card-body {
        padding: 15px 14px;
    }

    .class-view-page .class-overview {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 13px;
    }

    .class-view-page .class-overview-content {
        flex-basis: calc(100% - 65px);
    }

    .class-view-page .class-overview-status {
        margin-left: 61px;
    }

    .class-view-page .class-info-item {
        min-height: 82px;
        padding: 12px;
    }

    .class-view-page .class-view-actions {
        gap: 13px;
    }

    .class-view-page .class-action-buttons {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .class-view-page .class-btn {
        width: 100%;
        white-space: normal;
    }
}

@media (max-width: 480px) {
    .class-view-page .class-heading-area {
        align-items: flex-start;
        gap: 9px;
    }

    .class-view-page .class-heading-icon {
        width: 39px;
        height: 39px;
        flex-basis: 39px;
        font-size: 16px;
    }

    .class-view-page .class-breadcrumb {
        font-size: 8px;
        letter-spacing: .65px;
    }

    .class-view-page .class-page-header h2 {
        font-size: 19px;
    }

    .class-view-page .class-page-header p {
        font-size: 10px;
    }

    .class-view-page .class-view-card-body {
        padding: 12px 10px;
    }

    .class-view-page .class-overview {
        gap: 10px;
    }

    .class-view-page .class-overview-icon {
        width: 40px;
        height: 40px;
        flex-basis: 40px;
        font-size: 16px;
    }

    .class-view-page .class-overview-content {
        flex-basis: calc(100% - 52px);
    }

    .class-view-page .class-overview-content h3 {
        font-size: 15px;
    }

    .class-view-page .class-overview-status {
        margin-left: 50px;
    }

    .class-view-page .class-info-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .class-view-page .class-info-item,
    .class-view-page .class-info-item:nth-child(odd) {
        min-height: 72px;
        border-right: none;
        border-bottom: 1px solid var(--class-border);
    }

    .class-view-page .class-info-item:last-child {
        border-bottom: none;
    }

    .class-view-page .class-info-item:nth-last-child(2):not(:last-child) {
        border-bottom: 1px solid var(--class-border);
    }

    .class-view-page .class-action-buttons {
        grid-template-columns: minmax(0, 1fr);
    }

    .class-view-page .class-page-footer {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .class-view-page *,
    .class-view-page *::before,
    .class-view-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>
@endpush
