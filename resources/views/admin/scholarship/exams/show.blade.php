
@extends('layouts.app')

@section('title', 'Scholarship Examination Details | Admin')
@section('page-title', 'Scholarship Examination Details')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP EXAM DETAILS
   SCHOOL DIARY DESIGN SYSTEM
   Scoped styles — does not modify other modules
========================================================= */

.scholarship-show-page {
    --ink: #263b35;
    --ink-2: #1d302b;
    --muted: #778078;
    --text: #37443d;
    --line: #e5dfd0;
    --paper: #ffffff;
    --cream: #f8f6ef;
    --chalk: #d8e6c9;
    --accent: #e4572e;
    --red: #c94a36;
    --wood: #8d603c;
    --green: #3a9d6b;
    --amber: #d58a0b;
    --blue: #2b5d8a;
    --violet: #7a4fb5;
    --cyan: #268da0;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;

    min-height: calc(100vh - 70px);
    padding: 26px;
    color: var(--text);
    background:
        radial-gradient(circle at 8% 4%, rgba(58,157,107,.045), transparent 25%),
        radial-gradient(circle at 95% 16%, rgba(212,138,11,.045), transparent 25%),
        var(--cream);
    font-family: "Public Sans", "Segoe UI", sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

.scholarship-show-page *,
.scholarship-show-page *::before,
.scholarship-show-page *::after {
    box-sizing: border-box;
}

.scholarship-show-page a {
    text-decoration: none;
}

.scholarship-show-page button {
    font-family: inherit;
}

.scholarship-show-page .muted {
    color: #9aa097;
}

/* HEADER */

.scholarship-show-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    overflow: hidden;
    padding: 25px 28px 25px 42px;
    margin-bottom: 24px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background:
        radial-gradient(circle at 95% 15%, rgba(58,157,107,.07), transparent 35%),
        #fff;
    box-shadow: 0 4px 16px rgba(38,59,53,.035);
}

.scholarship-show-header::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 22px;
    width: 2px;
    background: rgba(228,87,46,.35);
}

.scholarship-show-header::after {
    content: "";
    position: absolute;
    right: 25px;
    bottom: -65px;
    width: 155px;
    height: 155px;
    border: 1px dashed rgba(58,157,107,.22);
    border-radius: 50%;
    pointer-events: none;
}

.scholarship-show-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.scholarship-show-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    flex: 0 0 56px;
    border: 1px solid #dce7d5;
    border-radius: 12px;
    color: var(--green);
    background: #edf5e9;
    font-size: 23px;
}

.scholarship-show-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 27px;
    font-weight: 700;
    letter-spacing: -.4px;
    line-height: 1.3;
}

.scholarship-show-subtitle {
    margin: 7px 0 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
}

.scholarship-header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.scholarship-show-page .btn-header {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 650;
    transition: background .2s ease, transform .2s ease;
}

.scholarship-show-page .btn-header:hover {
    transform: translateY(-2px);
}

.scholarship-show-page .btn-header-back {
    color: var(--ink);
    background: #fff;
    border-color: #d8d2c4;
}

.scholarship-show-page .btn-header-back:hover {
    color: var(--ink-2);
    background: #f8f6ef;
    border-color: var(--ink);
}

.scholarship-show-page .btn-header-edit {
    color: #fff;
    background: var(--ink);
    border-color: var(--ink);
}

.scholarship-show-page .btn-header-edit:hover {
    color: #fff;
    background: var(--ink-2);
}

/* MAIN GRID */

.scholarship-show-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.7fr) minmax(280px, .8fr);
    gap: 20px;
    align-items: start;
}

.scholarship-show-grid > div {
    min-width: 0;
}

/* CARDS */

.scholarship-show-card,
.scholarship-show-page .summary-card {
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.035);
}

.show-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fff;
}

.show-card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.show-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border: 1px solid #dce7d5;
    border-radius: 10px;
    color: var(--green);
    background: #edf5e9;
    font-size: 16px;
}

.show-card-icon.green {
    color: var(--green);
    background: #edf5e9;
    border-color: #dce7d5;
}

.show-card-icon.orange {
    color: var(--amber);
    background: #fff5df;
    border-color: #f0e1c2;
}

.show-card-icon.cyan {
    color: var(--cyan);
    background: #eaf7f8;
    border-color: #d1eaeb;
}

.show-card-icon.red {
    color: var(--accent);
    background: #fff0e9;
    border-color: #f1d8cb;
}

.show-card-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.45;
}

.show-card-subtitle {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 11.5px;
    line-height: 1.6;
}

.show-card-body {
    padding: 21px;
}

/* EXAM OVERVIEW */

.exam-name-box {
    position: relative;
    overflow: hidden;
    padding: 22px;
    margin-bottom: 18px;
    border: 1px solid #dce7d5;
    border-radius: 9px;
    background:
        linear-gradient(120deg, rgba(58,157,107,.065), rgba(255,255,255,.5)),
        #fafcf7;
}

.exam-name-box::after {
    content: "";
    position: absolute;
    top: -55px;
    right: -30px;
    width: 125px;
    height: 125px;
    border: 1px dashed rgba(58,157,107,.2);
    border-radius: 50%;
}

.exam-name-label {
    margin-bottom: 6px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .8px;
}

.exam-name {
    position: relative;
    z-index: 1;
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 25px;
    font-weight: 700;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.exam-type-badge {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    margin-top: 11px;
    border: 1px solid #dce7d5;
    border-radius: 6px;
    color: var(--green);
    background: #edf5e9;
    font-size: 11px;
    font-weight: 750;
}

/* DETAIL GRID */

.detail-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.detail-item {
    min-width: 0;
    padding: 15px;
    border: 1px solid #e8e4d9;
    border-radius: 8px;
    background: #fcfbf7;
}

.detail-label {
    display: block;
    margin-bottom: 6px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.detail-value {
    color: var(--ink);
    font-size: 13px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

/* PERFORMANCE STATISTICS */

.performance-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.performance-card {
    position: relative;
    overflow: hidden;
    min-width: 0;
    padding: 17px 15px;
    border: 1px solid #e8e4d9;
    border-top: 3px solid var(--metric-color, var(--green));
    border-radius: 8px;
    background: #fff;
    transition: transform .2s ease, box-shadow .2s ease;
}

.performance-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(38,59,53,.08);
}

.performance-label {
    color: var(--muted);
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.performance-number {
    margin-top: 9px;
    color: var(--metric-color, var(--ink));
    font-family: var(--serif);
    font-size: 29px;
    line-height: 1.2;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.performance-card.blue {
    --metric-color: var(--blue);
    background: #f7fafd;
}

.performance-card.green {
    --metric-color: var(--green);
    background: #f7fbf6;
}

.performance-card.orange {
    --metric-color: var(--amber);
    background: #fffcf5;
}

.performance-card.red {
    --metric-color: var(--accent);
    background: #fff9f6;
}

/* PASS RATE */

.pass-rate-box {
    padding: 17px;
    margin-top: 17px;
    border: 1px solid #e8e4d9;
    border-radius: 8px;
    background: #fcfbf7;
}

.pass-rate-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 11px;
}

.pass-rate-title {
    color: var(--text);
    font-size: 12px;
    font-weight: 700;
}

.pass-rate-value {
    color: var(--green);
    font-family: var(--serif);
    font-size: 19px;
    font-weight: 700;
}

.progress-track {
    width: 100%;
    height: 9px;
    overflow: hidden;
    border-radius: 6px;
    background: #e8e6dd;
}

.progress-fill {
    height: 100%;
    border-radius: 6px;
    background: repeating-linear-gradient(
        45deg,
        #3a9d6b 0 8px,
        #6fbd8e 8px 16px
    );
    transition: width .5s ease;
}

/* CLASSES */

.class-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.class-tag {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 11px;
    border: 1px solid #dce7d5;
    border-radius: 7px;
    color: var(--ink);
    background: #f5f9f1;
    font-size: 11px;
    font-weight: 700;
}

.class-tag i {
    color: var(--green);
}

/* STUDENT TABS */

.student-tabs {
    display: flex;
    gap: 7px;
    padding: 5px;
    margin-bottom: 18px;
    border: 1px solid #e5dfd0;
    border-radius: 8px;
    background: #f8f6ef;
}

.student-tab {
    display: flex;
    flex: 1;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 40px;
    padding: 8px 12px;
    border: 1px solid transparent;
    border-radius: 6px;
    color: var(--muted);
    background: transparent;
    font-size: 11px;
    font-weight: 750;
    cursor: pointer;
    white-space: nowrap;
    transition: color .2s ease, background .2s ease;
}

.student-tab:hover {
    color: var(--ink);
    background: #fff;
}

.student-tab.active {
    color: var(--ink);
    border-color: #e5dfd0;
    background: #fff;
    box-shadow: 0 2px 6px rgba(38,59,53,.04);
}

.student-tab.appeared.active {
    color: var(--amber);
}

.student-tab.passed.active {
    color: var(--green);
}

.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 23px;
    height: 22px;
    padding: 0 6px;
    border-radius: 5px;
    color: inherit;
    background: #f1efe7;
    font-size: 10px;
    font-weight: 800;
}

/* STUDENT TABLE */

.student-tab-content {
    display: none;
}

.student-tab-content.active {
    display: block;
    animation: scholarshipFadeIn .2s ease;
}

@keyframes scholarshipFadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.student-table-wrap {
    overflow-x: auto;
    width: 100%;
    border: 1px solid #e5dfd0;
    border-radius: 8px;
}

.student-table {
    width: 100%;
    min-width: 760px;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
}

.student-table th {
    padding: 12px 13px;
    border-bottom: 1px dashed #d8d1bf;
    color: #6e786f;
    background: #f8f6ef;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}

.student-table td {
    padding: 13px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 12px;
    font-weight: 550;
    vertical-align: middle;
}

.student-table tbody tr {
    transition: background .15s ease;
}

.student-table tbody tr:last-child td {
    border-bottom: 0;
}

.student-table tbody tr:hover {
    background: #fbfaf5;
}

.student-index {
    color: #9aa097 !important;
    font-weight: 700;
}

.student-name {
    display: block;
    color: var(--ink);
    font-weight: 750;
}

.student-id {
    display: block;
    margin-top: 3px;
    color: #8c958c;
    font-size: 10px;
}

.student-roll {
    color: var(--blue);
    font-weight: 750;
}

.class-text {
    color: var(--text);
    font-weight: 650;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.status-applied {
    border-color: #dbe3ed;
    color: var(--blue);
    background: #eff4fa;
}

.status-appeared {
    border-color: #f0dfbd;
    color: #a96b0a;
    background: #fff7e7;
}

.status-passed {
    border-color: #d4e6ce;
    color: #327f52;
    background: #edf6e9;
}

.result-value {
    color: var(--ink);
    font-weight: 750;
}

.scholarship-yes {
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.scholarship-no {
    color: #8b938a;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* EMPTY STATE */

.no-students {
    padding: 38px 15px;
    border: 1px dashed #d8d1bf;
    border-radius: 8px;
    color: var(--muted);
    background: #fcfbf7;
    font-size: 12px;
    line-height: 1.8;
    text-align: center;
}

.no-students-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    margin: 0 auto 12px;
    border: 1px solid #e5dfd0;
    border-radius: 10px;
    color: var(--green);
    background: #f3f6ed;
    font-size: 19px;
}

/* REMARKS */

.remarks-box {
    padding: 16px;
    border: 1px solid #e8e4d9;
    border-radius: 8px;
    color: var(--text);
    background: #fcfbf7;
    font-size: 13px;
    line-height: 1.9;
    overflow-wrap: anywhere;
    white-space: pre-line;
}

/* RIGHT SUMMARY */

.side-summary {
    position: sticky;
    top: 20px;
    min-width: 0;
}

.summary-top {
    padding: 24px;
    color: #fff;
    background:
        radial-gradient(circle at 95% 5%, rgba(255,255,255,.10), transparent 40%),
        var(--ink);
}

.summary-top-label {
    margin-bottom: 7px;
    color: #c5d0bd;
    font-family: var(--hand);
    font-size: 17px;
    font-weight: 600;
}

.summary-top-number {
    color: #fff;
    font-family: var(--serif);
    font-size: 38px;
    line-height: 1.2;
    font-weight: 700;
}

.summary-top-text {
    margin-top: 6px;
    color: #c5d0bd;
    font-size: 12px;
}

.summary-body {
    padding: 18px;
}

.summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px dashed #e5dfd0;
}

.summary-row:first-child {
    padding-top: 0;
}

.summary-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}

.summary-row-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 650;
}

.summary-row-label > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    border: 1px solid #e5dfd0;
    border-radius: 7px;
    color: var(--green);
    background: #f7f6ef;
    font-size: 11px;
}

.summary-row-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-align: right;
}

/* ACTIVE STATUS */

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.status-active {
    border-color: #d4e6ce;
    color: #327f52;
    background: #edf6e9;
}

.status-inactive {
    border-color: #e5dfd0;
    color: #747b72;
    background: #f5f3ec;
}

/* RECORD INFORMATION */

.meta-card {
    padding: 19px;
}

.meta-title {
    margin-bottom: 16px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 17px;
    font-weight: 700;
}

.meta-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
    min-width: 0;
}

.meta-item:last-child {
    margin-bottom: 0;
}

.meta-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    border: 1px solid #dce7d5;
    border-radius: 8px;
    color: var(--green);
    background: #f2f7ed;
    font-size: 11px;
}

.meta-text {
    min-width: 0;
}

.meta-text small {
    display: block;
    margin-bottom: 3px;
    color: #858d82;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.meta-text strong {
    display: block;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.7;
    overflow-wrap: anywhere;
}

/* SCHOLARSHIP TOTAL */

.scholarship-total {
    padding: 16px;
    margin-top: 15px;
    border: 1px solid #dce7d5;
    border-radius: 8px;
    background: #f5f9f1;
}

.scholarship-total-label {
    color: #778078;
    font-size: 10px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.scholarship-total-value {
    margin-top: 6px;
    color: var(--green);
    font-family: var(--serif);
    font-size: 24px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

/* ACCESSIBILITY */

.scholarship-show-page a:focus-visible,
.scholarship-show-page button:focus-visible {
    outline: 3px solid rgba(58,157,107,.4);
    outline-offset: 3px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .scholarship-show-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .side-summary {
        position: static;
    }
}

@media (max-width: 850px) {
    .performance-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .scholarship-show-page {
        padding: 15px;
    }

    .scholarship-show-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 21px 20px 21px 34px;
    }

    .scholarship-show-header::before {
        left: 18px;
    }

    .scholarship-show-header-left {
        align-items: flex-start;
    }

    .scholarship-header-actions {
        width: 100%;
    }

    .scholarship-show-page .btn-header {
        flex: 1;
        padding: 0 10px;
    }

    .detail-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .student-tabs {
        overflow-x: auto;
    }

    .student-tab {
        flex: 0 0 auto;
        min-width: 115px;
    }

    .show-card-header {
        padding: 15px;
    }

    .show-card-body {
        padding: 15px;
    }

    .scholarship-show-title {
        font-size: 23px;
    }
}

@media (max-width: 480px) {
    .performance-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .performance-card {
        padding: 13px;
    }

    .performance-number {
        font-size: 25px;
    }

    .scholarship-show-title {
        font-size: 21px;
    }

    .scholarship-show-icon {
        width: 48px;
        height: 48px;
        flex-basis: 48px;
        font-size: 20px;
    }

    .exam-name {
        font-size: 21px;
    }

    .summary-top {
        padding: 20px;
    }

    .summary-body,
    .meta-card {
        padding: 15px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-show-page *,
    .scholarship-show-page *::before,
    .scholarship-show-page *::after {
        animation: none !important;
        transition: none !important;
        scroll-behavior: auto !important;
    }
}
</style>

@php
    /*
    |--------------------------------------------------------------------------
    | STUDENT RECORDS
    |--------------------------------------------------------------------------
    */

    $applications = $exam->applications ?? collect();

    $appliedStudents = $applications->filter(function ($application) {
        return in_array($application->status, [
            'applied',
            'appeared',
            'passed'
        ]);
    });

    $appearedStudents = $applications->filter(function ($application) {
        return in_array($application->status, [
            'appeared',
            'passed'
        ]);
    });

    $passedApplications = $applications->filter(function ($application) {
        return $application->status === 'passed';
    });

    $passedStudents = $exam->passedStudents ?? collect();

    $eligibleCount = (int) ($exam->total_eligible ?? 0);

    $appliedCount = $appliedStudents->count();
    $appearedCount = $appearedStudents->count();
    $passedCount = $passedApplications->count();

    if ($appliedCount === 0) {
        $appliedCount = (int) ($exam->total_applied ?? 0);
    }

    if ($appearedCount === 0) {
        $appearedCount = (int) ($exam->total_appeared ?? 0);
    }

    if ($passedCount === 0) {
        $passedCount = (int) ($exam->total_passed ?? 0);
    }

    $passRate = $appearedCount > 0
        ? round(($passedCount / $appearedCount) * 100, 1)
        : 0;

    $scholarshipRecipients = $passedStudents->filter(function ($record) {
        return (bool) $record->scholarship_received;
    });

    $scholarshipAmount = $scholarshipRecipients->sum(function ($record) {
        return (float) ($record->scholarship_amount ?? 0);
    });

    $examClasses = $exam->classes ?? collect();
@endphp

<div class="scholarship-show-page">

    {{-- HEADER --}}

    <div class="scholarship-show-header">
        <div class="scholarship-show-header-left">
            <div class="scholarship-show-icon">
                <i class="fas fa-award"></i>
            </div>

            <div>
                <h1 class="scholarship-show-title">
                    Scholarship Examination Details
                </h1>

                <p class="scholarship-show-subtitle">
                    Complete examination history, participation and student performance
                </p>
            </div>
        </div>

        <div class="scholarship-header-actions">
            <a href="{{ route('admin.scholarship.exams.index') }}"
               class="btn-header btn-header-back">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.scholarship.exams.edit', $exam) }}"
               class="btn-header btn-header-edit">
                <i class="fas fa-edit"></i>
                Edit Exam
            </a>
        </div>
    </div>

    <div class="scholarship-show-grid">

        {{-- LEFT COLUMN --}}

        <div>

            {{-- EXAMINATION OVERVIEW --}}

            <div class="scholarship-show-card">
                <div class="show-card-header">
                    <div class="show-card-heading">
                        <div class="show-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>

                        <div>
                            <h2 class="show-card-title">Examination Overview</h2>
                            <p class="show-card-subtitle">
                                Basic information about this scholarship examination
                            </p>
                        </div>
                    </div>
                </div>

                <div class="show-card-body">
                    <div class="exam-name-box">
                        <div class="exam-name-label">Scholarship Examination</div>

                        <h2 class="exam-name">
                            {{ $exam->exam_name }}
                        </h2>

                        @if($exam->exam_type)
                            <span class="exam-type-badge">
                                <i class="fas fa-certificate"></i>
                                {{ $exam->exam_type }}
                            </span>
                        @endif
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Academic Year</span>
                            <div class="detail-value">
                                {{ $exam->academic_year ?: '—' }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Examination Date</span>
                            <div class="detail-value">
                                {{ $exam->exam_date
                                    ? \Carbon\Carbon::parse($exam->exam_date)->format('d M Y')
                                    : '—'
                                }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Conducted By</span>
                            <div class="detail-value {{ empty($exam->conducted_by) ? 'muted' : '' }}">
                                {{ $exam->conducted_by ?: 'Not specified' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARTICIPATION STATISTICS --}}

            <div class="scholarship-show-card">
                <div class="show-card-header">
                    <div class="show-card-heading">
                        <div class="show-card-icon green">
                            <i class="fas fa-chart-pie"></i>
                        </div>

                        <div>
                            <h2 class="show-card-title">Participation &amp; Result</h2>
                            <p class="show-card-subtitle">
                                Student participation and examination outcome
                            </p>
                        </div>
                    </div>
                </div>

                <div class="show-card-body">
                    <div class="performance-grid">
                        <div class="performance-card blue">
                            <div class="performance-label">Eligible</div>
                            <div class="performance-number">
                                {{ number_format($eligibleCount) }}
                            </div>
                        </div>

                        <div class="performance-card green">
                            <div class="performance-label">Applied</div>
                            <div class="performance-number">
                                {{ number_format($appliedCount) }}
                            </div>
                        </div>

                        <div class="performance-card orange">
                            <div class="performance-label">Appeared</div>
                            <div class="performance-number">
                                {{ number_format($appearedCount) }}
                            </div>
                        </div>

                        <div class="performance-card red">
                            <div class="performance-label">Passed</div>
                            <div class="performance-number">
                                {{ number_format($passedCount) }}
                            </div>
                        </div>
                    </div>

                    <div class="pass-rate-box">
                        <div class="pass-rate-top">
                            <span class="pass-rate-title">Overall Pass Rate</span>
                            <span class="pass-rate-value">
                                {{ number_format($passRate, 1) }}%
                            </span>
                        </div>

                        <div class="progress-track">
                            <div class="progress-fill"
                                 style="width: {{ min(100, max(0, $passRate)) }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARTICIPATING CLASSES --}}

            <div class="scholarship-show-card">
                <div class="show-card-header">
                    <div class="show-card-heading">
                        <div class="show-card-icon cyan">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>
                            <h2 class="show-card-title">Participating Classes</h2>
                            <p class="show-card-subtitle">
                                Classes included in this examination
                            </p>
                        </div>
                    </div>
                </div>

                <div class="show-card-body">
                    @if($examClasses->count())
                        <div class="class-list">
                            @foreach($examClasses as $class)
                                <span class="class-tag">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $class->class_name }}

                                    @if(!empty($class->section))
                                        - {{ $class->section }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="no-students">
                            <div class="no-students-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            No classes were linked with this examination.
                        </div>
                    @endif
                </div>
            </div>

            {{-- STUDENT PARTICIPATION --}}

            <div class="scholarship-show-card">
                <div class="show-card-header">
                    <div class="show-card-heading">
                        <div class="show-card-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div>
                            <h2 class="show-card-title">Student Participation</h2>
                            <p class="show-card-subtitle">
                                Detailed student-wise examination records
                            </p>
                        </div>
                    </div>
                </div>

                <div class="show-card-body">

                    {{-- STUDENT TABS --}}

                    <div class="student-tabs" role="tablist" aria-label="Student participation">
                        <button type="button"
                                class="student-tab active"
                                id="button-applied"
                                data-tab="applied"
                                role="tab"
                                aria-controls="tab-applied"
                                aria-selected="true">
                            <i class="fas fa-file-signature"></i>
                            Applied
                            <span class="tab-count">{{ $appliedStudents->count() }}</span>
                        </button>

                        <button type="button"
                                class="student-tab appeared"
                                id="button-appeared"
                                data-tab="appeared"
                                role="tab"
                                aria-controls="tab-appeared"
                                aria-selected="false">
                            <i class="fas fa-pen"></i>
                            Appeared
                            <span class="tab-count">{{ $appearedStudents->count() }}</span>
                        </button>

                        <button type="button"
                                class="student-tab passed"
                                id="button-passed"
                                data-tab="passed"
                                role="tab"
                                aria-controls="tab-passed"
                                aria-selected="false">
                            <i class="fas fa-award"></i>
                            Passed
                            <span class="tab-count">{{ $passedStudents->count() }}</span>
                        </button>
                    </div>

                    {{-- APPLIED STUDENTS --}}

                    <div class="student-tab-content active"
                         id="tab-applied"
                         role="tabpanel"
                         aria-labelledby="button-applied">

                        @if($appliedStudents->count())
                            <div class="student-table-wrap">
                                <table class="student-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Roll No.</th>
                                            <th>Class</th>
                                            <th>Academic Year</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($appliedStudents as $index => $application)
                                            @php
                                                $student = $application->student;
                                            @endphp

                                            @if($student)
                                                <tr>
                                                    <td class="student-index">
                                                        {{ $index + 1 }}
                                                    </td>

                                                    <td>
                                                        <span class="student-name">
                                                            {{ $student->full_name }}
                                                        </span>

                                                        @if($student->student_id)
                                                            <span class="student-id">
                                                                ID: {{ $student->student_id }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="student-roll">
                                                            {{ $student->roll_number ?: '—' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="class-text">
                                                            {{ $student->class ?: '—' }}
                                                            @if($student->section)
                                                                - {{ $student->section }}
                                                            @endif
                                                        </span>
                                                    </td>

                                                    <td>{{ $student->academic_year ?: '—' }}</td>

                                                    <td>
                                                        @if($application->status === 'passed')
                                                            <span class="status-badge status-passed">
                                                                <i class="fas fa-award"></i> Passed
                                                            </span>
                                                        @elseif($application->status === 'appeared')
                                                            <span class="status-badge status-appeared">
                                                                <i class="fas fa-pen"></i> Appeared
                                                            </span>
                                                        @else
                                                            <span class="status-badge status-applied">
                                                                <i class="fas fa-file-signature"></i> Applied
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="no-students">
                                <div class="no-students-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                No applied student records have been added for this examination.
                            </div>
                        @endif
                    </div>

                    {{-- APPEARED STUDENTS --}}

                    <div class="student-tab-content"
                         id="tab-appeared"
                         role="tabpanel"
                         aria-labelledby="button-appeared"
                         hidden>

                        @if($appearedStudents->count())
                            <div class="student-table-wrap">
                                <table class="student-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Roll No.</th>
                                            <th>Class</th>
                                            <th>Academic Year</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($appearedStudents as $index => $application)
                                            @php
                                                $student = $application->student;
                                            @endphp

                                            @if($student)
                                                <tr>
                                                    <td class="student-index">
                                                        {{ $index + 1 }}
                                                    </td>

                                                    <td>
                                                        <span class="student-name">
                                                            {{ $student->full_name }}
                                                        </span>

                                                        @if($student->student_id)
                                                            <span class="student-id">
                                                                ID: {{ $student->student_id }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="student-roll">
                                                            {{ $student->roll_number ?: '—' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="class-text">
                                                            {{ $student->class ?: '—' }}
                                                            @if($student->section)
                                                                - {{ $student->section }}
                                                            @endif
                                                        </span>
                                                    </td>

                                                    <td>{{ $student->academic_year ?: '—' }}</td>

                                                    <td>
                                                        @if($application->status === 'passed')
                                                            <span class="status-badge status-passed">
                                                                <i class="fas fa-award"></i> Passed
                                                            </span>
                                                        @else
                                                            <span class="status-badge status-appeared">
                                                                <i class="fas fa-pen"></i> Appeared
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="no-students">
                                <div class="no-students-icon">
                                    <i class="fas fa-pen"></i>
                                </div>
                                No appeared student records have been added for this examination.
                            </div>
                        @endif
                    </div>

                    {{-- PASSED STUDENTS --}}

                    <div class="student-tab-content"
                         id="tab-passed"
                         role="tabpanel"
                         aria-labelledby="button-passed"
                         hidden>

                        @if($passedStudents->count())
                            <div class="student-table-wrap">
                                <table class="student-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Roll No.</th>
                                            <th>Class</th>
                                            <th>Marks</th>
                                            <th>Percentage</th>
                                            <th>Scholarship</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($passedStudents as $index => $passedStudent)
                                            @php
                                                $student = $passedStudent->student;
                                            @endphp

                                            @if($student)
                                                <tr>
                                                    <td class="student-index">
                                                        {{ $index + 1 }}
                                                    </td>

                                                    <td>
                                                        <span class="student-name">
                                                            {{ $student->full_name }}
                                                        </span>

                                                        @if($student->student_id)
                                                            <span class="student-id">
                                                                ID: {{ $student->student_id }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="student-roll">
                                                            {{ $student->roll_number ?: '—' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="class-text">
                                                            {{ $student->class ?: '—' }}
                                                            @if($student->section)
                                                                - {{ $student->section }}
                                                            @endif
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="result-value">
                                                            {{ $passedStudent->marks !== null
                                                                ? number_format((float) $passedStudent->marks, 2)
                                                                : '—'
                                                            }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="result-value">
                                                            {{ $passedStudent->percentage !== null
                                                                ? number_format((float) $passedStudent->percentage, 2) . '%'
                                                                : '—'
                                                            }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        @if($passedStudent->scholarship_received)
                                                            <span class="scholarship-yes">
                                                                <i class="fas fa-check-circle"></i>
                                                                Received
                                                            </span>
                                                        @else
                                                            <span class="scholarship-no">Not Received</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="result-value">
                                                            @if($passedStudent->scholarship_amount !== null)
                                                                ₹{{ number_format((float) $passedStudent->scholarship_amount, 2) }}
                                                            @else
                                                                —
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($scholarshipRecipients->count())
                                <div class="scholarship-total">
                                    <div class="scholarship-total-label">
                                        Total Scholarship Distributed
                                    </div>
                                    <div class="scholarship-total-value">
                                        ₹{{ number_format($scholarshipAmount, 2) }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="no-students">
                                <div class="no-students-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                No passed-student records have been added for this examination.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- EXAMINATION REMARKS --}}

            @if(!empty($exam->remarks))
                <div class="scholarship-show-card">
                    <div class="show-card-header">
                        <div class="show-card-heading">
                            <div class="show-card-icon orange">
                                <i class="fas fa-comment-alt"></i>
                            </div>

                            <div>
                                <h2 class="show-card-title">Examination Remarks</h2>
                                <p class="show-card-subtitle">
                                    Additional information recorded for this examination
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="show-card-body">
                        <div class="remarks-box">{{ $exam->remarks }}</div>
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT COLUMN --}}

        <div class="side-summary">

            {{-- PERFORMANCE SUMMARY --}}

            <div class="summary-card">
                <div class="summary-top">
                    <div class="summary-top-label">Examination Performance</div>

                    <div class="summary-top-number">
                        {{ number_format($passRate, 1) }}%
                    </div>

                    <div class="summary-top-text">Overall pass rate</div>
                </div>

                <div class="summary-body">
                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-circle-check"></i>
                            Status
                        </div>

                        <div class="summary-row-value">
                            @if($exam->status)
                                <span class="status-pill status-active">
                                    <i class="fas fa-circle"></i> Active
                                </span>
                            @else
                                <span class="status-pill status-inactive">
                                    <i class="fas fa-circle"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-users"></i> Eligible
                        </div>
                        <div class="summary-row-value">{{ number_format($eligibleCount) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-file-signature"></i> Applied
                        </div>
                        <div class="summary-row-value">{{ number_format($appliedCount) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-pen"></i> Appeared
                        </div>
                        <div class="summary-row-value">{{ number_format($appearedCount) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-award"></i> Passed
                        </div>
                        <div class="summary-row-value">{{ number_format($passedCount) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-row-label">
                            <i class="fas fa-money-bill-wave"></i> Scholarships
                        </div>
                        <div class="summary-row-value">
                            {{ $scholarshipRecipients->count() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- RECORD INFORMATION --}}

            <div class="summary-card">
                <div class="show-card-header">
                    <div class="show-card-heading">
                        <div class="show-card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>
                            <h2 class="show-card-title">Record Information</h2>
                            <p class="show-card-subtitle">Examination record metadata</p>
                        </div>
                    </div>
                </div>

                <div class="meta-card">
                    <div class="meta-title">System Record</div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meta-text">
                            <small>Created</small>
                            <strong>
                                {{ $exam->created_at
                                    ? $exam->created_at->format('d M Y, h:i A')
                                    : '—'
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="meta-text">
                            <small>Last Updated</small>
                            <strong>
                                {{ $exam->updated_at
                                    ? $exam->updated_at->format('d M Y, h:i A')
                                    : '—'
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="meta-text">
                            <small>Participating Classes</small>
                            <strong>
                                {{ $examClasses->count() }}
                                {{ $examClasses->count() === 1 ? 'Class' : 'Classes' }}
                            </strong>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="meta-text">
                            <small>Student Records</small>
                            <strong>{{ $applications->count() }}</strong>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="meta-text">
                            <small>Passed Records</small>
                            <strong>{{ $passedStudents->count() }}</strong>
                        </div>
                    </div>

                    @if($scholarshipAmount > 0)
                        <div class="scholarship-total">
                            <div class="scholarship-total-label">Scholarship Amount</div>
                            <div class="scholarship-total-value">
                                ₹{{ number_format($scholarshipAmount, 2) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>

<script>
(function () {
    function initScholarshipTabs() {
        document.querySelectorAll('.scholarship-show-page').forEach(function (page) {
            if (page.dataset.tabsInitialized === 'true') {
                return;
            }

            page.dataset.tabsInitialized = 'true';

            const tabs = page.querySelectorAll('.student-tab');
            const contents = page.querySelectorAll('.student-tab-content');

            function activateTab(tab) {
                const target = tab.getAttribute('data-tab');

                tabs.forEach(function (item) {
                    const active = item === tab;
                    item.classList.toggle('active', active);
                    item.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                contents.forEach(function (content) {
                    const active = content.id === 'tab-' + target;
                    content.classList.toggle('active', active);
                    content.hidden = !active;
                });
            }

            tabs.forEach(function (tab, index) {
                tab.addEventListener('click', function () {
                    activateTab(tab);
                });

                tab.addEventListener('keydown', function (event) {
                    if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') {
                        return;
                    }

                    event.preventDefault();

                    const direction = event.key === 'ArrowRight' ? 1 : -1;
                    const nextIndex = (index + direction + tabs.length) % tabs.length;

                    tabs[nextIndex].focus();
                    activateTab(tabs[nextIndex]);
                });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScholarshipTabs);
    } else {
        initScholarshipTabs();
    }
})();
</script>

@endsection
