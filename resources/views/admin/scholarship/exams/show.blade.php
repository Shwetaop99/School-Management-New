@extends('layouts.app')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP EXAM DETAILS
   PREMIUM PROFESSIONAL UI
========================================================= */

.scholarship-show-page {
    min-height: calc(100vh - 70px);
    padding: 26px;
    background:
        radial-gradient(circle at 5% 8%, rgba(23,105,209,.055), transparent 25%),
        radial-gradient(circle at 95% 20%, rgba(21,156,199,.05), transparent 25%),
        #f4f7fb;
}

/* =========================================================
   HEADER
========================================================= */

.scholarship-show-header {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 25px 28px;
    margin-bottom: 22px;
    border-radius: 20px;
    color: #fff;
    background: linear-gradient(135deg,#1769d1 0%,#159cc7 100%);
    box-shadow: 0 15px 38px rgba(23,105,209,.18);
}

.scholarship-show-header::before {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    right: -80px;
    top: -140px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.scholarship-show-header::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: 130px;
    bottom: -125px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.scholarship-show-header-left {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 17px;
}

.scholarship-show-icon {
    width: 60px;
    height: 60px;
    flex: 0 0 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 17px;
    color: #fff;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.22);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
    font-size: 24px;
}

.scholarship-show-title {
    margin: 0;
    font-size: 25px;
    font-weight: 850;
    letter-spacing: -.45px;
}

.scholarship-show-subtitle {
    margin: 5px 0 0;
    color: rgba(255,255,255,.83);
    font-size: 13px;
}

.scholarship-header-actions {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-header {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 11px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 750;
    transition: .2s ease;
}

.btn-header:hover {
    transform: translateY(-1px);
}

.btn-header-back {
    color: #fff;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.22);
}

.btn-header-back:hover {
    color: #fff;
    background: rgba(255,255,255,.2);
}

.btn-header-edit {
    color: #1769d1;
    background: #fff;
    border: 1px solid #fff;
}

.btn-header-edit:hover {
    color: #1769d1;
    background: #f5f9ff;
}

/* =========================================================
   MAIN GRID
========================================================= */

.scholarship-show-grid {
    display: grid;
    grid-template-columns: minmax(0,1.7fr) minmax(310px,.8fr);
    gap: 20px;
    align-items: start;
}

/* =========================================================
   CARDS
========================================================= */

.scholarship-show-card,
.summary-card {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 7px 25px rgba(31,55,88,.055);
    margin-bottom: 20px;
}

.show-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 17px 20px;
    border-bottom: 1px solid #edf1f6;
}

.show-card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.show-card-icon {
    width: 39px;
    height: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    border-radius: 11px;
    color: #fff;
    background: linear-gradient(135deg,#1769d1,#3489e9);
    font-size: 15px;
}

.show-card-icon.green {
    background: linear-gradient(135deg,#078b70,#18b995);
}

.show-card-icon.orange {
    background: linear-gradient(135deg,#ed9208,#f5ad35);
}

.show-card-icon.cyan {
    background: linear-gradient(135deg,#079dbd,#21bfdc);
}

.show-card-icon.red {
    background: linear-gradient(135deg,#e94d47,#f36f67);
}

.show-card-title {
    margin: 0;
    color: #172033;
    font-size: 15px;
    font-weight: 800;
}

.show-card-subtitle {
    margin: 3px 0 0;
    color: #8a95a7;
    font-size: 10px;
}

.show-card-body {
    padding: 20px;
}

/* =========================================================
   EXAM OVERVIEW
========================================================= */

.exam-name-box {
    position: relative;
    overflow: hidden;
    padding: 21px;
    margin-bottom: 18px;
    border-radius: 15px;
    background:
        linear-gradient(135deg,rgba(23,105,209,.065),rgba(21,156,199,.045));
    border: 1px solid rgba(23,105,209,.1);
}

.exam-name-box::after {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: -35px;
    top: -55px;
    border-radius: 50%;
    background: rgba(23,105,209,.05);
}

.exam-name-label {
    margin-bottom: 6px;
    color: #718096;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .75px;
}

.exam-name {
    position: relative;
    z-index: 1;
    margin: 0;
    color: #172033;
    font-size: 23px;
    font-weight: 850;
}

.exam-type-badge {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    padding: 6px 10px;
    border-radius: 8px;
    color: #1769d1;
    background: #edf5ff;
    border: 1px solid #dceaff;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================================
   DETAIL GRID
========================================================= */

.detail-grid {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 13px;
}

.detail-item {
    padding: 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #edf1f6;
}

.detail-label {
    display: block;
    margin-bottom: 6px;
    color: #7a8799;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.detail-value {
    color: #202b3c;
    font-size: 13px;
    font-weight: 750;
}

.detail-value.muted {
    color: #9aa5b5;
}

/* =========================================================
   PERFORMANCE
========================================================= */

.performance-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 12px;
}

.performance-card {
    position: relative;
    overflow: hidden;
    padding: 17px;
    border-radius: 14px;
    border: 1px solid #e8edf4;
}

.performance-card::after {
    content: "";
    position: absolute;
    width: 65px;
    height: 65px;
    right: -20px;
    bottom: -25px;
    border-radius: 50%;
    background: rgba(255,255,255,.7);
}

.performance-label {
    position: relative;
    z-index: 1;
    color: #7a8799;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.performance-number {
    position: relative;
    z-index: 1;
    margin-top: 7px;
    color: #172033;
    font-size: 24px;
    line-height: 1;
    font-weight: 900;
}

.performance-card.blue {
    background: linear-gradient(135deg,#f2f7ff,#fbfdff);
    border-color: #dceaff;
}

.performance-card.blue .performance-number {
    color: #1769d1;
}

.performance-card.green {
    background: linear-gradient(135deg,#f0fbf8,#fbfffe);
    border-color: #d5f0e9;
}

.performance-card.green .performance-number {
    color: #078b70;
}

.performance-card.orange {
    background: linear-gradient(135deg,#fff8eb,#fffdf9);
    border-color: #f8e6c2;
}

.performance-card.orange .performance-number {
    color: #dc8300;
}

.performance-card.red {
    background: linear-gradient(135deg,#fff3f2,#fffafa);
    border-color: #f5d8d6;
}

.performance-card.red .performance-number {
    color: #df443f;
}

/* =========================================================
   PASS RATE
========================================================= */

.pass-rate-box {
    margin-top: 16px;
    padding: 17px;
    border-radius: 13px;
    background: #f8fafc;
    border: 1px solid #edf1f6;
}

.pass-rate-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.pass-rate-title {
    color: #566276;
    font-size: 12px;
    font-weight: 700;
}

.pass-rate-value {
    color: #078b70;
    font-size: 17px;
    font-weight: 850;
}

.progress-track {
    width: 100%;
    height: 9px;
    overflow: hidden;
    border-radius: 20px;
    background: #e9eef5;
}

.progress-fill {
    height: 100%;
    border-radius: 20px;
    background: linear-gradient(90deg,#078b70,#18b995);
}

/* =========================================================
   CLASSES
========================================================= */

.class-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.class-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 11px;
    border-radius: 8px;
    color: #1769d1;
    background: #edf5ff;
    border: 1px solid #dbeaff;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================================
   STUDENT TABS
========================================================= */

.student-tabs {
    display: flex;
    gap: 7px;
    padding: 5px;
    margin-bottom: 18px;
    border-radius: 12px;
    background: #f3f6fa;
    border: 1px solid #e7edf4;
}

.student-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 39px;
    padding: 0 13px;
    border: 0;
    border-radius: 9px;
    background: transparent;
    color: #7a8799;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.student-tab:hover {
    color: #1769d1;
    background: #fff;
}

.student-tab.active {
    color: #1769d1;
    background: #fff;
    box-shadow: 0 3px 12px rgba(31,55,88,.08);
}

.student-tab.appeared.active {
    color: #dc8300;
}

.student-tab.passed.active {
    color: #078b70;
}

.tab-count {
    min-width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    border-radius: 7px;
    color: inherit;
    background: rgba(23,105,209,.08);
    font-size: 10px;
}

/* =========================================================
   STUDENT TABLE
========================================================= */

.student-tab-content {
    display: none;
}

.student-tab-content.active {
    display: block;
}

.student-table-wrap {
    overflow-x: auto;
    border: 1px solid #e8edf4;
    border-radius: 13px;
}

.student-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
}

.student-table th {
    padding: 11px 13px;
    color: #728095;
    background: #f8fafc;
    border-bottom: 1px solid #e8edf4;
    font-size: 9px;
    font-weight: 850;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .55px;
    white-space: nowrap;
}

.student-table td {
    padding: 12px 13px;
    color: #344054;
    border-bottom: 1px solid #edf1f5;
    font-size: 12px;
    font-weight: 600;
    vertical-align: middle;
}

.student-table tbody tr:last-child td {
    border-bottom: 0;
}

.student-table tbody tr:hover {
    background: #fbfdff;
}

.student-index {
    color: #9aa5b5;
    font-weight: 700;
}

.student-name {
    display: block;
    color: #172033;
    font-weight: 800;
}

.student-id {
    display: block;
    margin-top: 3px;
    color: #9aa5b5;
    font-size: 9px;
}

.student-roll {
    color: #1769d1;
    font-weight: 850;
}

.class-text {
    color: #4d596b;
    font-weight: 700;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border-radius: 7px;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

.status-applied {
    color: #1769d1;
    background: #edf5ff;
    border: 1px solid #dbeaff;
}

.status-appeared {
    color: #b96d00;
    background: #fff6e6;
    border: 1px solid #f5dfb5;
}

.status-passed {
    color: #08765f;
    background: #e9faf5;
    border: 1px solid #cdeee5;
}

.result-value {
    color: #172033;
    font-weight: 800;
}

.scholarship-yes {
    color: #08765f;
}

.scholarship-no {
    color: #8b96a8;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.no-students {
    padding: 38px 15px;
    text-align: center;
    color: #8b96a8;
    font-size: 12px;
}

.no-students-icon {
    width: 52px;
    height: 52px;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    color: #8b96a8;
    background: #f3f5f8;
    font-size: 20px;
}

/* =========================================================
   REMARKS
========================================================= */

.remarks-box {
    padding: 15px;
    border-radius: 12px;
    color: #596579;
    background: #f8fafc;
    border: 1px solid #edf1f6;
    font-size: 13px;
    line-height: 1.7;
}

/* =========================================================
   SIDE SUMMARY
========================================================= */

.side-summary {
    position: sticky;
    top: 20px;
}

.summary-top {
    padding: 23px;
    color: #fff;
    background: linear-gradient(135deg,#1769d1,#159cc7);
}

.summary-top-label {
    margin-bottom: 5px;
    color: rgba(255,255,255,.76);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.summary-top-number {
    font-size: 34px;
    line-height: 1;
    font-weight: 900;
}

.summary-top-text {
    margin-top: 6px;
    color: rgba(255,255,255,.84);
    font-size: 11px;
}

.summary-body {
    padding: 17px;
}

.summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #edf1f6;
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
    color: #6f7b8d;
    font-size: 11px;
    font-weight: 700;
}

.summary-row-label i {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    color: #1769d1;
    background: #edf5ff;
    font-size: 10px;
}

.summary-row-value {
    color: #202b3c;
    font-size: 12px;
    font-weight: 850;
    text-align: right;
}

/* =========================================================
   STATUS
========================================================= */

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 800;
}

.status-active {
    color: #08765f;
    background: #e9faf5;
    border: 1px solid #cdeee5;
}

.status-inactive {
    color: #687386;
    background: #f1f3f6;
    border: 1px solid #e1e5eb;
}

/* =========================================================
   META
========================================================= */

.meta-card {
    padding: 17px;
}

.meta-title {
    margin-bottom: 13px;
    color: #172033;
    font-size: 13px;
    font-weight: 800;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.meta-item:last-child {
    margin-bottom: 0;
}

.meta-icon {
    width: 31px;
    height: 31px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    border-radius: 8px;
    color: #079dbd;
    background: #eafaff;
    font-size: 11px;
}

.meta-text small {
    display: block;
    margin-bottom: 2px;
    color: #8994a5;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
}

.meta-text strong {
    color: #3b4658;
    font-size: 11px;
    font-weight: 750;
}

/* =========================================================
   SCHOLARSHIP TOTAL
========================================================= */

.scholarship-total {
    padding: 16px;
    margin-top: 15px;
    border-radius: 12px;
    background: linear-gradient(135deg,#f0fbf8,#fbfffe);
    border: 1px solid #d5f0e9;
}

.scholarship-total-label {
    color: #6d7c89;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.scholarship-total-value {
    margin-top: 5px;
    color: #078b70;
    font-size: 22px;
    font-weight: 900;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:1100px) {

    .scholarship-show-grid {
        grid-template-columns: 1fr;
    }

    .side-summary {
        position: static;
    }

}

@media(max-width:850px) {

    .performance-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .detail-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

}

@media(max-width:700px) {

    .scholarship-show-page {
        padding: 15px;
    }

    .scholarship-show-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 20px;
    }

    .scholarship-header-actions {
        width: 100%;
    }

    .btn-header {
        flex: 1;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .student-tabs {
        overflow-x: auto;
    }

    .student-tab {
        flex: 0 0 auto;
        min-width: 125px;
    }

}

@media(max-width:480px) {

    .performance-grid {
        grid-template-columns: 1fr 1fr;
    }

    .scholarship-show-title {
        font-size: 21px;
    }

    .scholarship-show-icon {
        width: 50px;
        height: 50px;
        flex-basis: 50px;
    }

    .show-card-body {
        padding: 15px;
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

    $passedStudentMap = $passedStudents->keyBy('student_id');

    /*
    |--------------------------------------------------------------------------
    | COUNTS
    |--------------------------------------------------------------------------
    */

    $eligibleCount = (int) ($exam->total_eligible ?? 0);

    $appliedCount = $appliedStudents->count();

    $appearedCount = $appearedStudents->count();

    $passedCount = $passedApplications->count();

    /*
    |--------------------------------------------------------------------------
    | FALLBACK TO STORED TOTALS
    |--------------------------------------------------------------------------
    |
    | This keeps old records visible even before student-level records
    | have been entered.
    |
    */

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

    /*
    |--------------------------------------------------------------------------
    | SCHOLARSHIP TOTAL
    |--------------------------------------------------------------------------
    */

    $scholarshipRecipients = $passedStudents->filter(function ($record) {
        return (bool) $record->scholarship_received;
    });

    $scholarshipAmount = $scholarshipRecipients->sum(function ($record) {
        return (float) ($record->scholarship_amount ?? 0);
    });
@endphp


<div class="scholarship-show-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

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


    {{-- =====================================================
         MAIN GRID
    ====================================================== --}}

    <div class="scholarship-show-grid">


        {{-- =================================================
             LEFT COLUMN
        ================================================== --}}

        <div>


            {{-- =================================================
                 EXAMINATION OVERVIEW
            ================================================== --}}

            <div class="scholarship-show-card">

                <div class="show-card-header">

                    <div class="show-card-heading">

                        <div class="show-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>

                        <div>

                            <h2 class="show-card-title">
                                Examination Overview
                            </h2>

                            <p class="show-card-subtitle">
                                Basic information about this scholarship examination
                            </p>

                        </div>

                    </div>

                </div>


                <div class="show-card-body">

                    <div class="exam-name-box">

                        <div class="exam-name-label">
                            Scholarship Examination
                        </div>

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

                            <span class="detail-label">
                                Academic Year
                            </span>

                            <div class="detail-value">
                                {{ $exam->academic_year ?: '—' }}
                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Examination Date
                            </span>

                            <div class="detail-value">

                                {{ $exam->exam_date
                                    ? \Carbon\Carbon::parse($exam->exam_date)->format('d M Y')
                                    : '—'
                                }}

                            </div>

                        </div>


                        <div class="detail-item">

                            <span class="detail-label">
                                Conducted By
                            </span>

                            <div class="detail-value {{ empty($exam->conducted_by) ? 'muted' : '' }}">

                                {{ $exam->conducted_by ?: 'Not specified' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PARTICIPATION STATISTICS
            ================================================== --}}

            <div class="scholarship-show-card">

                <div class="show-card-header">

                    <div class="show-card-heading">

                        <div class="show-card-icon green">
                            <i class="fas fa-chart-pie"></i>
                        </div>

                        <div>

                            <h2 class="show-card-title">
                                Participation & Result
                            </h2>

                            <p class="show-card-subtitle">
                                Student participation and examination outcome
                            </p>

                        </div>

                    </div>

                </div>


                <div class="show-card-body">

                    <div class="performance-grid">


                        <div class="performance-card blue">

                            <div class="performance-label">
                                Eligible
                            </div>

                            <div class="performance-number">
                                {{ number_format($eligibleCount) }}
                            </div>

                        </div>


                        <div class="performance-card green">

                            <div class="performance-label">
                                Applied
                            </div>

                            <div class="performance-number">
                                {{ number_format($appliedCount) }}
                            </div>

                        </div>


                        <div class="performance-card orange">

                            <div class="performance-label">
                                Appeared
                            </div>

                            <div class="performance-number">
                                {{ number_format($appearedCount) }}
                            </div>

                        </div>


                        <div class="performance-card red">

                            <div class="performance-label">
                                Passed
                            </div>

                            <div class="performance-number">
                                {{ number_format($passedCount) }}
                            </div>

                        </div>

                    </div>


                    <div class="pass-rate-box">

                        <div class="pass-rate-top">

                            <span class="pass-rate-title">
                                Overall Pass Rate
                            </span>

                            <span class="pass-rate-value">
                                {{ number_format($passRate,1) }}%
                            </span>

                        </div>


                        <div class="progress-track">

                            <div class="progress-fill"
                                 style="width: {{ min(100,max(0,$passRate)) }}%;">
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PARTICIPATING CLASSES
            ================================================== --}}

            <div class="scholarship-show-card">

                <div class="show-card-header">

                    <div class="show-card-heading">

                        <div class="show-card-icon cyan">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>

                            <h2 class="show-card-title">
                                Participating Classes
                            </h2>

                            <p class="show-card-subtitle">
                                Classes included in this examination
                            </p>

                        </div>

                    </div>

                </div>


                <div class="show-card-body">

                    @if($exam->classes->count())

                        <div class="class-list">

                            @foreach($exam->classes as $class)

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


            {{-- =================================================
                 STUDENT PARTICIPATION
            ================================================== --}}

            <div class="scholarship-show-card">

                <div class="show-card-header">

                    <div class="show-card-heading">

                        <div class="show-card-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>

                        <div>

                            <h2 class="show-card-title">
                                Student Participation
                            </h2>

                            <p class="show-card-subtitle">
                                Detailed student-wise examination records
                            </p>

                        </div>

                    </div>

                </div>


                <div class="show-card-body">


                    {{-- TABS --}}

                    <div class="student-tabs">

                        <button type="button"
                                class="student-tab active"
                                data-tab="applied">

                            <i class="fas fa-file-signature"></i>

                            Applied

                            <span class="tab-count">
                                {{ $appliedStudents->count() }}
                            </span>

                        </button>


                        <button type="button"
                                class="student-tab appeared"
                                data-tab="appeared">

                            <i class="fas fa-pen"></i>

                            Appeared

                            <span class="tab-count">
                                {{ $appearedStudents->count() }}
                            </span>

                        </button>


                        <button type="button"
                                class="student-tab passed"
                                data-tab="passed">

                            <i class="fas fa-award"></i>

                            Passed

                            <span class="tab-count">
                                {{ $passedApplications->count() }}
                            </span>

                        </button>

                    </div>


                    {{-- =================================================
                         APPLIED STUDENTS
                    ================================================== --}}

                    <div class="student-tab-content active"
                         id="tab-applied">

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


                                                    <td>

                                                        {{ $student->academic_year ?: '—' }}

                                                    </td>


                                                    <td>

                                                        @if($application->status === 'passed')

                                                            <span class="status-badge status-passed">

                                                                <i class="fas fa-award"></i>

                                                                Passed

                                                            </span>

                                                        @elseif($application->status === 'appeared')

                                                            <span class="status-badge status-appeared">

                                                                <i class="fas fa-pen"></i>

                                                                Appeared

                                                            </span>

                                                        @else

                                                            <span class="status-badge status-applied">

                                                                <i class="fas fa-file-signature"></i>

                                                                Applied

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


                    {{-- =================================================
                         APPEARED STUDENTS
                    ================================================== --}}

                    <div class="student-tab-content"
                         id="tab-appeared">

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


                                                    <td>
                                                        {{ $student->academic_year ?: '—' }}
                                                    </td>


                                                    <td>

                                                        @if($application->status === 'passed')

                                                            <span class="status-badge status-passed">

                                                                <i class="fas fa-award"></i>

                                                                Passed

                                                            </span>

                                                        @else

                                                            <span class="status-badge status-appeared">

                                                                <i class="fas fa-pen"></i>

                                                                Appeared

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


                    {{-- =================================================
                         PASSED STUDENTS
                    ================================================== --}}

                    <div class="student-tab-content"
                         id="tab-passed">

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
                                                                ? number_format((float)$passedStudent->marks,2)
                                                                : '—'
                                                            }}

                                                        </span>

                                                    </td>


                                                    <td>

                                                        <span class="result-value">

                                                            {{ $passedStudent->percentage !== null
                                                                ? number_format((float)$passedStudent->percentage,2) . '%'
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

                                                            <span class="scholarship-no">
                                                                Not Received
                                                            </span>

                                                        @endif

                                                    </td>


                                                    <td>

                                                        <span class="result-value">

                                                            @if($passedStudent->scholarship_amount !== null)

                                                                ₹{{ number_format((float)$passedStudent->scholarship_amount,2) }}

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


                            {{-- SCHOLARSHIP SUMMARY --}}

                            @if($scholarshipRecipients->count())

                                <div class="scholarship-total">

                                    <div class="scholarship-total-label">
                                        Total Scholarship Distributed
                                    </div>

                                    <div class="scholarship-total-value">
                                        ₹{{ number_format($scholarshipAmount,2) }}
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


            {{-- =================================================
                 REMARKS
            ================================================== --}}

            @if(!empty($exam->remarks))

                <div class="scholarship-show-card">

                    <div class="show-card-header">

                        <div class="show-card-heading">

                            <div class="show-card-icon orange">
                                <i class="fas fa-comment-alt"></i>
                            </div>

                            <div>

                                <h2 class="show-card-title">
                                    Examination Remarks
                                </h2>

                                <p class="show-card-subtitle">
                                    Additional information recorded for this examination
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="show-card-body">

                        <div class="remarks-box">
                            {{ $exam->remarks }}
                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}

        <div class="side-summary">


            {{-- =================================================
                 PERFORMANCE SUMMARY
            ================================================== --}}

            <div class="summary-card">

                <div class="summary-top">

                    <div class="summary-top-label">
                        Examination Performance
                    </div>

                    <div class="summary-top-number">
                        {{ number_format($passRate,1) }}%
                    </div>

                    <div class="summary-top-text">
                        Overall pass rate
                    </div>

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

                                    <i class="fas fa-circle"></i>

                                    Active

                                </span>

                            @else

                                <span class="status-pill status-inactive">

                                    <i class="fas fa-circle"></i>

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="summary-row">

                        <div class="summary-row-label">

                            <i class="fas fa-users"></i>

                            Eligible

                        </div>

                        <div class="summary-row-value">
                            {{ number_format($eligibleCount) }}
                        </div>

                    </div>


                    <div class="summary-row">

                        <div class="summary-row-label">

                            <i class="fas fa-file-signature"></i>

                            Applied

                        </div>

                        <div class="summary-row-value">
                            {{ number_format($appliedCount) }}
                        </div>

                    </div>


                    <div class="summary-row">

                        <div class="summary-row-label">

                            <i class="fas fa-pen"></i>

                            Appeared

                        </div>

                        <div class="summary-row-value">
                            {{ number_format($appearedCount) }}
                        </div>

                    </div>


                    <div class="summary-row">

                        <div class="summary-row-label">

                            <i class="fas fa-award"></i>

                            Passed

                        </div>

                        <div class="summary-row-value">
                            {{ number_format($passedCount) }}
                        </div>

                    </div>


                    <div class="summary-row">

                        <div class="summary-row-label">

                            <i class="fas fa-money-bill-wave"></i>

                            Scholarships

                        </div>

                        <div class="summary-row-value">
                            {{ $scholarshipRecipients->count() }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RECORD INFORMATION
            ================================================== --}}

            <div class="summary-card">

                <div class="show-card-header">

                    <div class="show-card-heading">

                        <div class="show-card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>

                            <h2 class="show-card-title">
                                Record Information
                            </h2>

                        </div>

                    </div>

                </div>


                <div class="meta-card">

                    <div class="meta-title">
                        System Record
                    </div>


                    <div class="meta-item">

                        <div class="meta-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>

                        <div class="meta-text">

                            <small>
                                Created
                            </small>

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

                            <small>
                                Last Updated
                            </small>

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

                            <small>
                                Participating Classes
                            </small>

                            <strong>

                                {{ $exam->classes->count() }}

                                {{ $exam->classes->count() === 1
                                    ? 'Class'
                                    : 'Classes'
                                }}

                            </strong>

                        </div>

                    </div>


                    <div class="meta-item">

                        <div class="meta-icon">
                            <i class="fas fa-user-check"></i>
                        </div>

                        <div class="meta-text">

                            <small>
                                Student Records
                            </small>

                            <strong>
                                {{ $applications->count() }}
                            </strong>

                        </div>

                    </div>


                    <div class="meta-item">

                        <div class="meta-icon">
                            <i class="fas fa-award"></i>
                        </div>

                        <div class="meta-text">

                            <small>
                                Passed Records
                            </small>

                            <strong>
                                {{ $passedStudents->count() }}
                            </strong>

                        </div>

                    </div>


                    @if($scholarshipAmount > 0)

                        <div class="scholarship-total">

                            <div class="scholarship-total-label">
                                Scholarship Amount
                            </div>

                            <div class="scholarship-total-value">
                                ₹{{ number_format($scholarshipAmount,2) }}
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabs = document.querySelectorAll('.student-tab');
    const contents = document.querySelectorAll('.student-tab-content');

    tabs.forEach(function (tab) {

        tab.addEventListener('click', function () {

            const target = this.getAttribute('data-tab');

            tabs.forEach(function (item) {
                item.classList.remove('active');
            });

            contents.forEach(function (content) {
                content.classList.remove('active');
            });

            this.classList.add('active');

            const targetContent = document.getElementById('tab-' + target);

            if (targetContent) {
                targetContent.classList.add('active');
            }

        });

    });

});
</script>

@endsection

