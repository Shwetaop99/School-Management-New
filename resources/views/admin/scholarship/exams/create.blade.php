@extends('layouts.app')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP EXAM CREATE — PROFESSIONAL SCHOOL UI
========================================================= */

.scholarship-create-page {
    min-height: calc(100vh - 70px);
    padding: 22px;
    background: #f5f7fb;
    color: #273449;
}

/* =========================================================
   HEADER
========================================================= */

.scholarship-create-header {
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    padding: 22px 24px;
    border-radius: 16px;
    background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
    box-shadow: 0 8px 24px rgba(23,105,209,.14);
    color: #fff;
}

.scholarship-create-header::after {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    right: -70px;
    top: -100px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.scholarship-create-header-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.scholarship-create-title {
    display: flex;
    align-items: center;
    gap: 14px;
}

.scholarship-create-icon {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.22);
    font-size: 21px;
}

.scholarship-create-title h1 {
    margin: 0;
    font-size: 21px;
    font-weight: 800;
    letter-spacing: -.2px;
}

.scholarship-create-title p {
    margin: 4px 0 0;
    color: rgba(255,255,255,.80);
    font-size: 12px;
}

.btn-back-exams {
    position: relative;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.24);
    background: rgba(255,255,255,.12);
    color: #fff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 750;
    transition: .2s ease;
}

.btn-back-exams:hover {
    color: #fff;
    background: rgba(255,255,255,.20);
}

/* =========================================================
   ALERT
========================================================= */

.scholarship-alert {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    margin-bottom: 18px;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 11px;
}

.scholarship-alert-danger {
    background: #fff5f4;
    border: 1px solid #f3d3d0;
    color: #c33e38;
}

.scholarship-alert i {
    margin-top: 2px;
}

/* =========================================================
   MAIN GRID
========================================================= */

.scholarship-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 18px;
    align-items: start;
}

/* =========================================================
   CARD
========================================================= */

.scholarship-form-card {
    overflow: hidden;
    margin-bottom: 18px;
    border: 1px solid #e4e9f1;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(31,55,85,.045);
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 17px;
    border-bottom: 1px solid #edf0f5;
}

.form-card-icon {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #edf4ff;
    color: #1769d1;
    font-size: 14px;
}

.form-card-icon.orange {
    background: #fff5e6;
    color: #e58a08;
}

.form-card-icon.cyan {
    background: #eafafd;
    color: #078da9;
}

.form-card-icon.green {
    background: #eaf9f1;
    color: #17955c;
}

.form-card-header h2 {
    margin: 0;
    color: #253247;
    font-size: 13px;
    font-weight: 800;
}

.form-card-header p {
    margin: 2px 0 0;
    color: #8a95a5;
    font-size: 10px;
}

.form-card-body {
    padding: 18px;
}

/* =========================================================
   FORM
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    color: #566477;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.required {
    color: #e04d47;
}

.form-control-scholarship {
    width: 100%;
    min-height: 42px;
    box-sizing: border-box;
    padding: 9px 11px;
    border: 1px solid #dce3ec;
    border-radius: 8px;
    outline: none;
    background: #fbfcfe;
    color: #29364a;
    font-size: 12px;
    transition: .18s ease;
}

.form-control-scholarship:hover {
    border-color: #c9d5e4;
}

.form-control-scholarship:focus {
    border-color: #1769d1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(23,105,209,.07);
}

textarea.form-control-scholarship {
    min-height: 95px;
    resize: vertical;
}

.form-help {
    margin-top: 5px;
    color: #929cab;
    font-size: 9px;
}

.invalid-feedback-custom {
    margin-top: 5px;
    color: #d94b44;
    font-size: 10px;
    font-weight: 650;
}

.input-error {
    border-color: #e04d47 !important;
    background: #fffafa !important;
}

/* =========================================================
   CLASS SELECTION
========================================================= */

.class-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 11px;
}

.class-toolbar-title {
    color: #667386;
    font-size: 10px;
    font-weight: 700;
}

.class-toolbar-actions {
    display: flex;
    gap: 7px;
}

.btn-select-all {
    padding: 6px 10px;
    border: 1px solid #dce4ee;
    border-radius: 7px;
    background: #fff;
    color: #1769d1;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    transition: .18s ease;
}

.btn-select-all:hover {
    background: #f3f7fd;
}

.class-selection {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
}

.class-option {
    position: relative;
}

.class-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.class-option label {
    min-height: 50px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 7px 9px;
    border: 1px solid #e0e6ee;
    border-radius: 9px;
    background: #fbfcfe;
    cursor: pointer;
    transition: .18s ease;
}

.class-option label:hover {
    border-color: #b8cfee;
    background: #f7faff;
}

.class-option input:checked + label {
    border-color: #1769d1;
    background: #f2f6fd;
}

.class-check {
    width: 19px;
    height: 19px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d3dce7;
    border-radius: 5px;
    background: #fff;
    color: transparent;
    font-size: 9px;
}

.class-option input:checked + label .class-check {
    border-color: #1769d1;
    background: #1769d1;
    color: #fff;
}

.class-info {
    min-width: 0;
}

.class-name {
    display: block;
    color: #2d394c;
    font-size: 10px;
    font-weight: 800;
}

.class-section {
    display: block;
    margin-top: 2px;
    color: #8994a4;
    font-size: 9px;
}

/* =========================================================
   STUDENTS
========================================================= */

.students-card {
    display: none;
}

.students-card.visible {
    display: block;
}

.student-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

.student-search {
    position: relative;
    flex: 1;
    min-width: 220px;
}

.student-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa5b5;
    font-size: 11px;
}

.student-search input {
    width: 100%;
    height: 38px;
    box-sizing: border-box;
    padding: 8px 11px 8px 33px;
    border: 1px solid #dce3ec;
    border-radius: 8px;
    outline: none;
    background: #fbfcfe;
    font-size: 11px;
}

.student-search input:focus {
    border-color: #1769d1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(23,105,209,.06);
}

.student-count {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #758196;
    font-size: 10px;
    font-weight: 700;
}

.student-count strong {
    color: #1769d1;
    font-size: 13px;
}

.student-loading {
    display: none;
    padding: 30px 15px;
    text-align: center;
}

.student-loading.active {
    display: block;
}

.loading-spinner {
    width: 28px;
    height: 28px;
    margin: 0 auto 9px;
    border: 3px solid #e3ebf5;
    border-top-color: #1769d1;
    border-radius: 50%;
    animation: scholarshipSpin .75s linear infinite;
}

@keyframes scholarshipSpin {
    to {
        transform: rotate(360deg);
    }
}

.loading-text {
    color: #7e8999;
    font-size: 10px;
    font-weight: 700;
}

.student-empty {
    display: none;
    padding: 30px 15px;
    text-align: center;
    border: 1px dashed #dce4ed;
    border-radius: 10px;
    background: #fafbfd;
}

.student-empty.active {
    display: block;
}

.student-empty-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #eef4fc;
    color: #1769d1;
    font-size: 18px;
}

.student-empty strong {
    display: block;
    color: #3d4a5d;
    font-size: 11px;
}

.student-empty span {
    display: block;
    margin-top: 4px;
    color: #8c97a7;
    font-size: 9px;
}

.student-table-wrap {
    display: none;
    overflow-x: auto;
    border: 1px solid #e3e8ef;
    border-radius: 10px;
}

.student-table-wrap.active {
    display: block;
}

.student-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
}

.student-table th {
    padding: 10px 11px;
    text-align: left;
    background: #f7f9fc;
    border-bottom: 1px solid #e4e9f0;
    color: #687589;
    font-size: 8px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.student-table td {
    padding: 10px 11px;
    border-bottom: 1px solid #edf0f4;
    color: #435066;
    font-size: 10px;
    vertical-align: middle;
}

.student-table tr:last-child td {
    border-bottom: 0;
}

.student-table tr.student-hidden {
    display: none;
}

.student-roll {
    color: #1769d1;
    font-weight: 850;
}

.student-name {
    color: #29364a;
    font-weight: 800;
}

.student-id {
    display: block;
    margin-top: 2px;
    color: #9aa4b3;
    font-size: 8px;
}

.student-class {
    color: #647186;
    font-weight: 700;
}

.student-status {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.status-option {
    position: relative;
}

.status-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.status-option label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 70px;
    min-height: 27px;
    padding: 4px 8px;
    border: 1px solid #dce3ec;
    border-radius: 6px;
    background: #fff;
    color: #758196;
    cursor: pointer;
    font-size: 8px;
    font-weight: 850;
    transition: .15s ease;
}

.status-option label:hover {
    border-color: #b9cbe5;
}

.status-option input[value="applied"]:checked + label {
    background: #eef5ff;
    border-color: #8eb5ed;
    color: #1769d1;
}

.status-option input[value="appeared"]:checked + label {
    background: #edfbfe;
    border-color: #82d1df;
    color: #0787a4;
}

.status-option input[value="passed"]:checked + label {
    background: #f1fbf6;
    border-color: #91d7b0;
    color: #168452;
}

.status-option input[value="not_applied"]:checked + label {
    background: #f7f8fa;
    border-color: #cbd3dd;
    color: #697587;
}

.student-status-help {
    margin-top: 11px;
    padding: 9px 11px;
    border: 1px solid #e1ebf7;
    border-radius: 8px;
    background: #f8fbff;
    color: #718096;
    font-size: 9px;
    line-height: 1.5;
}

.student-status-help strong {
    color: #40536c;
}

/* =========================================================
   STATISTICS
========================================================= */

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 9px;
}

.stat-box {
    position: relative;
    overflow: hidden;
    padding: 13px;
    border: 1px solid #e3e9f1;
    border-radius: 10px;
    background: #fbfcfe;
}

.stat-box.eligible {
    border-left: 3px solid #1769d1;
}

.stat-box.applied {
    border-left: 3px solid #ed9208;
}

.stat-box.appeared {
    border-left: 3px solid #079dbd;
}

.stat-box.passed {
    border-left: 3px solid #20a667;
}

.stat-label {
    display: block;
    color: #7e8999;
    font-size: 8px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.stat-value {
    display: block;
    margin-top: 5px;
    color: #273449;
    font-size: 22px;
    line-height: 1;
    font-weight: 900;
}

.stat-description {
    display: block;
    margin-top: 5px;
    color: #9aa4b3;
    font-size: 8px;
}

/* =========================================================
   SIDE INFORMATION
========================================================= */

.side-info-card {
    background: #fff;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 9px;
}

.info-item-icon {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #eef4fc;
    color: #1769d1;
    font-size: 10px;
}

.info-item strong {
    display: block;
    color: #354257;
    font-size: 10px;
    font-weight: 800;
}

.info-item span {
    display: block;
    margin-top: 2px;
    color: #8994a4;
    font-size: 9px;
    line-height: 1.45;
}

/* =========================================================
   STATUS
========================================================= */

.status-box {
    padding: 13px;
    border: 1px solid #e4e9f1;
    border-radius: 10px;
    background: #fafbfd;
}

.status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.status-text strong {
    display: block;
    color: #334055;
    font-size: 11px;
    font-weight: 850;
}

.status-text span {
    display: block;
    margin-top: 3px;
    color: #8c97a7;
    font-size: 9px;
}

/* =========================================================
   SWITCH
========================================================= */

.switch {
    position: relative;
    width: 40px;
    height: 22px;
    flex-shrink: 0;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    inset: 0;
    cursor: pointer;
    border-radius: 30px;
    background: #cbd4df;
    transition: .2s ease;
}

.slider::before {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    left: 3px;
    top: 3px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,.15);
    transition: .2s ease;
}

.switch input:checked + .slider {
    background: #20a667;
}

.switch input:checked + .slider::before {
    transform: translateX(18px);
}

/* =========================================================
   ACTIONS
========================================================= */

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding: 14px 17px;
    border-top: 1px solid #edf0f4;
    background: #fcfdff;
}

.btn-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 8px 14px;
    border: 1px solid #dce3ec;
    border-radius: 8px;
    background: #fff;
    color: #667386;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
}

.btn-cancel:hover {
    background: #f7f9fc;
    color: #4e5d72;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 39px;
    padding: 8px 15px;
    border: 0;
    border-radius: 8px;
    background: #1769d1;
    color: #fff;
    font-size: 11px;
    font-weight: 850;
    cursor: pointer;
    box-shadow: 0 5px 13px rgba(23,105,209,.16);
    transition: .2s ease;
}

.btn-save:hover {
    background: #125bb9;
    transform: translateY(-1px);
}

.btn-save:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
}

/* =========================================================
   NO CLASSES
========================================================= */

.no-classes {
    padding: 23px;
    text-align: center;
    border: 1px dashed #dce4ed;
    border-radius: 10px;
    background: #fafbfd;
}

.no-classes-icon {
    margin-bottom: 7px;
    color: #1769d1;
    font-size: 22px;
}

.no-classes strong {
    display: block;
    color: #39465a;
    font-size: 11px;
}

.no-classes span {
    display: block;
    margin-top: 4px;
    color: #8c97a7;
    font-size: 9px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .scholarship-form-grid {
        grid-template-columns: 1fr;
    }

    .class-selection {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (max-width: 850px) {

    .statistics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {

    .scholarship-create-page {
        padding: 14px;
    }

    .scholarship-create-header-content {
        align-items: flex-start;
        flex-direction: column;
    }

    .btn-back-exams {
        width: 100%;
        justify-content: center;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .class-selection {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions a,
    .form-actions button {
        width: 100%;
    }
}

@media (max-width: 480px) {

    .class-selection {
        grid-template-columns: 1fr;
    }

    .statistics-grid {
        grid-template-columns: 1fr 1fr;
    }

    .scholarship-create-title h1 {
        font-size: 18px;
    }

    .scholarship-create-title p {
        font-size: 10px;
    }
}
</style>

<div class="scholarship-create-page">


{{-- =====================================================
     HEADER
====================================================== --}}

<div class="scholarship-create-header">

    <div class="scholarship-create-header-content">

        <div class="scholarship-create-title">

            <div class="scholarship-create-icon">
                <i class="fas fa-award"></i>
            </div>

            <div>
                <h1>Add Scholarship Exam Record</h1>

                <p>
                    Record examination details and student participation
                    for the academic year.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.scholarship.exams.index') }}"
            class="btn-back-exams">

            <i class="fas fa-arrow-left"></i>
            Back to Exam Records

        </a>

    </div>

</div>


{{-- =====================================================
     VALIDATION ERRORS
====================================================== --}}

@if($errors->any())

    <div class="scholarship-alert scholarship-alert-danger">

        <i class="fas fa-exclamation-circle"></i>

        <div>

            <strong>
                Please correct the following errors:
            </strong>

            <ul style="margin:6px 0 0 17px;padding:0;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =====================================================
     FORM
====================================================== --}}

<form
    method="POST"
    action="{{ route('admin.scholarship.exams.store') }}"
    id="scholarshipExamForm">

    @csrf

    <div class="scholarship-form-grid">


        {{-- =================================================
             LEFT SIDE
        ================================================== --}}

        <div>


            {{-- =================================================
                 EXAM INFORMATION
            ================================================== --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>

                    <div>
                        <h2>Examination Information</h2>

                        <p>
                            Basic details of the scholarship examination
                        </p>
                    </div>

                </div>


                <div class="form-card-body">

                    <div class="form-grid">


                        {{-- EXAM NAME --}}

                        <div class="form-group full">

                            <label class="form-label">
                                Exam Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="exam_name"
                                value="{{ old('exam_name') }}"
                                class="form-control-scholarship {{ $errors->has('exam_name') ? 'input-error' : '' }}"
                                placeholder="e.g. Maharashtra Scholarship Examination">

                            @if($errors->has('exam_name'))

                                <div class="invalid-feedback-custom">
                                    {{ $errors->first('exam_name') }}
                                </div>

                            @endif

                        </div>


                        {{-- ACADEMIC YEAR --}}

                        <div class="form-group">

                            <label class="form-label">
                                Academic Year
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="academic_year"
                                id="academicYear"
                                value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                                class="form-control-scholarship {{ $errors->has('academic_year') ? 'input-error' : '' }}"
                                placeholder="e.g. 2026-2027">

                            <div class="form-help">
                                Match the Academic Year used in Student Management.
                            </div>

                            @if($errors->has('academic_year'))

                                <div class="invalid-feedback-custom">
                                    {{ $errors->first('academic_year') }}
                                </div>

                            @endif

                        </div>


                        {{-- EXAM DATE --}}

                        <div class="form-group">

                            <label class="form-label">
                                Exam Date
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="exam_date"
                                value="{{ old('exam_date') }}"
                                class="form-control-scholarship {{ $errors->has('exam_date') ? 'input-error' : '' }}">

                            @if($errors->has('exam_date'))

                                <div class="invalid-feedback-custom">
                                    {{ $errors->first('exam_date') }}
                                </div>

                            @endif

                        </div>


                        {{-- EXAM TYPE --}}

                        <div class="form-group">

                            <label class="form-label">
                                Exam Type
                                <span class="required">*</span>
                            </label>

                            <select
                                name="exam_type"
                                class="form-control-scholarship {{ $errors->has('exam_type') ? 'input-error' : '' }}">

                                <option value="">
                                    Select Exam Type
                                </option>

                                <option
                                    value="Government Scholarship"
                                    {{ old('exam_type') == 'Government Scholarship' ? 'selected' : '' }}>
                                    Government Scholarship
                                </option>

                                <option
                                    value="School Scholarship"
                                    {{ old('exam_type') == 'School Scholarship' ? 'selected' : '' }}>
                                    School Scholarship
                                </option>

                                <option
                                    value="State Scholarship"
                                    {{ old('exam_type') == 'State Scholarship' ? 'selected' : '' }}>
                                    State Scholarship
                                </option>

                                <option
                                    value="National Scholarship"
                                    {{ old('exam_type') == 'National Scholarship' ? 'selected' : '' }}>
                                    National Scholarship
                                </option>

                                <option
                                    value="Other"
                                    {{ old('exam_type') == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            @if($errors->has('exam_type'))

                                <div class="invalid-feedback-custom">
                                    {{ $errors->first('exam_type') }}
                                </div>

                            @endif

                        </div>


                        {{-- CONDUCTED BY --}}

                        <div class="form-group">

                            <label class="form-label">
                                Conducted By
                            </label>

                            <input
                                type="text"
                                name="conducted_by"
                                value="{{ old('conducted_by') }}"
                                class="form-control-scholarship"
                                placeholder="e.g. School / Education Department">

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PARTICIPATING CLASSES
            ================================================== --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon orange">
                        <i class="fas fa-school"></i>
                    </div>

                    <div>

                        <h2>Participating Classes</h2>

                        <p>
                            Select classes and sections from Class Management
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="class-toolbar">

                        <span class="class-toolbar-title">
                            Select one or more classes / sections
                        </span>

                        <div class="class-toolbar-actions">

                            <button
                                type="button"
                                class="btn-select-all"
                                id="selectAllClasses">

                                Select All

                            </button>

                        </div>

                    </div>


                    @if($classes->count())

                        <div class="class-selection">

                            @foreach($classes as $class)

                                <div class="class-option">

                                    <input
                                        type="checkbox"
                                        name="class_ids[]"
                                        value="{{ $class->id }}"
                                        id="class_{{ $class->id }}"
                                        {{ in_array($class->id, old('class_ids', [])) ? 'checked' : '' }}>

                                    <label for="class_{{ $class->id }}">

                                        <span class="class-check">
                                            <i class="fas fa-check"></i>
                                        </span>

                                        <span class="class-info">

                                            <span class="class-name">
                                                {{ $class->class_name }}
                                            </span>

                                            <span class="class-section">
                                                Section {{ $class->section ?: '—' }}
                                            </span>

                                        </span>

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="no-classes">

                            <div class="no-classes-icon">
                                <i class="fas fa-school"></i>
                            </div>

                            <strong>
                                No Active Classes Found
                            </strong>

                            <span>
                                Please add active classes in Class Management first.
                            </span>

                        </div>

                    @endif


                    @if($errors->has('class_ids'))

                        <div class="invalid-feedback-custom">
                            {{ $errors->first('class_ids') }}
                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 STUDENT PARTICIPATION
            ================================================== --}}

            <div
                class="scholarship-form-card students-card"
                id="studentsCard">

                <div class="form-card-header">

                    <div class="form-card-icon green">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <div>

                        <h2>Student Participation</h2>

                        <p>
                            Record the participation status of each student
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="student-toolbar">

                        <div class="student-search">

                            <i class="fas fa-search"></i>

                            <input
                                type="text"
                                id="studentSearch"
                                placeholder="Search student name, roll number or class...">

                        </div>

                        <div class="student-count">

                            <span>
                                Students Loaded
                            </span>

                            <strong id="studentCount">
                                0
                            </strong>

                        </div>

                    </div>


                    <div
                        class="student-loading"
                        id="studentLoading">

                        <div class="loading-spinner"></div>

                        <div class="loading-text">
                            Loading students from Student Management...
                        </div>

                    </div>


                    <div
                        class="student-empty"
                        id="studentEmpty">

                        <div class="student-empty-icon">
                            <i class="fas fa-user-slash"></i>
                        </div>

                        <strong>
                            No Students Found
                        </strong>

                        <span id="studentEmptyMessage">
                            No active students were found for the selected classes and academic year.
                        </span>

                    </div>


                    <div
                        class="student-table-wrap"
                        id="studentTableWrap">

                        <table class="student-table">

                            <thead>

                                <tr>

                                    <th style="width:65px;">
                                        Roll No.
                                    </th>

                                    <th>
                                        Student
                                    </th>

                                    <th style="width:120px;">
                                        Class
                                    </th>

                                    <th>
                                        Participation Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="studentTableBody">
                            </tbody>

                        </table>

                    </div>


                    <div class="student-status-help">

                        <strong>
                            Status:
                        </strong>

                        Applied = registered for the exam.
                        Appeared = attended the exam.
                        Passed = successfully passed the exam.
                        Not Applied students are not stored as applications.

                    </div>

                </div>

            </div>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon cyan">
                        <i class="fas fa-chart-column"></i>
                    </div>

                    <div>

                        <h2>Examination Statistics</h2>

                        <p>
                            Automatically calculated from student participation
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="statistics-grid">

                        <div class="stat-box eligible">

                            <span class="stat-label">
                                Eligible
                            </span>

                            <span
                                class="stat-value"
                                id="eligibleCount">
                                0
                            </span>

                            <span class="stat-description">
                                Selected classes
                            </span>

                        </div>


                        <div class="stat-box applied">

                            <span class="stat-label">
                                Applied
                            </span>

                            <span
                                class="stat-value"
                                id="appliedCount">
                                0
                            </span>

                            <span class="stat-description">
                                Registered
                            </span>

                        </div>


                        <div class="stat-box appeared">

                            <span class="stat-label">
                                Appeared
                            </span>

                            <span
                                class="stat-value"
                                id="appearedCount">
                                0
                            </span>

                            <span class="stat-description">
                                Attended exam
                            </span>

                        </div>


                        <div class="stat-box passed">

                            <span class="stat-label">
                                Passed
                            </span>

                            <span
                                class="stat-value"
                                id="passedCount">
                                0
                            </span>

                            <span class="stat-description">
                                Successful
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 REMARKS
            ================================================== --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon">
                        <i class="fas fa-note-sticky"></i>
                    </div>

                    <div>

                        <h2>Additional Information</h2>

                        <p>
                            Optional notes for future reference
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="form-group">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control-scholarship"
                            placeholder="Enter additional information about this scholarship examination...">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="scholarship-form-card">

                <div class="form-actions">

                    <a
                        href="{{ route('admin.scholarship.exams.index') }}"
                        class="btn-cancel">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn-save"
                        id="saveExamButton">

                        <i class="fas fa-save"></i>

                        Save Exam Record

                    </button>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT SIDE
        ================================================== --}}

        <div>


            {{-- RECORD INFORMATION --}}

            <div class="scholarship-form-card side-info-card">

                <div class="form-card-header">

                    <div class="form-card-icon">
                        <i class="fas fa-circle-info"></i>
                    </div>

                    <div>

                        <h2>Record Information</h2>

                        <p>
                            Information stored in this module
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="info-list">


                        <div class="info-item">

                            <div class="info-item-icon">
                                <i class="fas fa-calendar"></i>
                            </div>

                            <div>

                                <strong>
                                    Year-wise History
                                </strong>

                                <span>
                                    Maintain examination records
                                    for every academic year.
                                </span>

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-item-icon">
                                <i class="fas fa-school"></i>
                            </div>

                            <div>

                                <strong>
                                    Existing Classes
                                </strong>

                                <span>
                                    Classes and sections are taken
                                    directly from Class Management.
                                </span>

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-item-icon">
                                <i class="fas fa-users"></i>
                            </div>

                            <div>

                                <strong>
                                    Student Records
                                </strong>

                                <span>
                                    Students are loaded from
                                    Student Management.
                                </span>

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-item-icon">
                                <i class="fas fa-chart-simple"></i>
                            </div>

                            <div>

                                <strong>
                                    Automatic Statistics
                                </strong>

                                <span>
                                    Eligible, Applied, Appeared and
                                    Passed totals update automatically.
                                </span>

                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-item-icon">
                                <i class="fas fa-medal"></i>
                            </div>

                            <div>

                                <strong>
                                    Result Tracking
                                </strong>

                                <span>
                                    Passed students remain available
                                    for future result reference.
                                </span>

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            {{-- STATUS --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>

                    <div>

                        <h2>Record Status</h2>

                        <p>
                            Control historical record visibility
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div class="status-box">

                        <div class="status-row">

                            <div class="status-text">

                                <strong>
                                    Active Record
                                </strong>

                                <span>
                                    Keep this examination
                                    available in records.
                                </span>

                            </div>

                            <label class="switch">

                                <input
                                    type="checkbox"
                                    name="status"
                                    value="1"
                                    {{ old('status', true) ? 'checked' : '' }}>

                                <span class="slider"></span>

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DATA RULES --}}

            <div class="scholarship-form-card">

                <div class="form-card-header">

                    <div class="form-card-icon orange">
                        <i class="fas fa-shield-halved"></i>
                    </div>

                    <div>

                        <h2>Data Rules</h2>

                        <p>
                            Participation validation
                        </p>

                    </div>

                </div>


                <div class="form-card-body">

                    <div style="
                        padding:11px;
                        border-radius:9px;
                        background:#fffaf1;
                        border:1px solid #f5e2bc;
                    ">

                        <div style="
                            display:flex;
                            gap:8px;
                            align-items:flex-start;
                        ">

                            <i
                                class="fas fa-check-circle"
                                style="color:#ed9208;margin-top:2px;">
                            </i>

                            <span style="
                                color:#77623c;
                                font-size:9px;
                                line-height:1.6;
                            ">

                                Appeared automatically counts as
                                Applied. Passed automatically counts
                                as both Appeared and Applied.
                                Not Applied students are not stored.

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENTS
    ====================================================== */

    const form =
        document.getElementById('scholarshipExamForm');

    const academicYear =
        document.getElementById('academicYear');

    const selectAllButton =
        document.getElementById('selectAllClasses');

    const classCheckboxes =
        document.querySelectorAll(
            'input[name="class_ids[]"]'
        );

    const studentsCard =
        document.getElementById('studentsCard');

    const studentLoading =
        document.getElementById('studentLoading');

    const studentEmpty =
        document.getElementById('studentEmpty');

    const studentEmptyMessage =
        document.getElementById('studentEmptyMessage');

    const studentTableWrap =
        document.getElementById('studentTableWrap');

    const studentTableBody =
        document.getElementById('studentTableBody');

    const studentSearch =
        document.getElementById('studentSearch');

    const studentCount =
        document.getElementById('studentCount');

    const eligibleCount =
        document.getElementById('eligibleCount');

    const appliedCount =
        document.getElementById('appliedCount');

    const appearedCount =
        document.getElementById('appearedCount');

    const passedCount =
        document.getElementById('passedCount');

    const saveExamButton =
        document.getElementById('saveExamButton');


    /* =====================================================
       STUDENT DATA
    ====================================================== */

    let allStudents = [];

    let loadedStudentIds = new Set();


    /* =====================================================
       SELECT ALL
    ====================================================== */

    if (selectAllButton) {

        selectAllButton.addEventListener(
            'click',
            function () {

                const allChecked =
                    Array.from(classCheckboxes)
                        .every(function (checkbox) {
                            return checkbox.checked;
                        });

                classCheckboxes.forEach(function (checkbox) {

                    checkbox.checked = !allChecked;

                });

                this.textContent =
                    allChecked
                        ? 'Select All'
                        : 'Clear All';

                loadSelectedClasses();

            }
        );

    }


    /* =====================================================
       CLASS CHANGE
    ====================================================== */

    classCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            function () {

                updateSelectAllButton();

                loadSelectedClasses();

            }
        );

    });


    function updateSelectAllButton() {

        if (!selectAllButton) {
            return;
        }

        const checkedCount =
            Array.from(classCheckboxes)
                .filter(function (checkbox) {
                    return checkbox.checked;
                })
                .length;

        if (
            checkedCount === classCheckboxes.length &&
            classCheckboxes.length > 0
        ) {

            selectAllButton.textContent =
                'Clear All';

        } else {

            selectAllButton.textContent =
                'Select All';

        }

    }


    /* =====================================================
       ACADEMIC YEAR CHANGE
    ====================================================== */

    if (academicYear) {

        academicYear.addEventListener(
            'change',
            function () {

                if (
                    document.querySelectorAll(
                        'input[name="class_ids[]"]:checked'
                    ).length
                ) {

                    loadSelectedClasses();

                }

            }
        );

    }


    /* =====================================================
       LOAD SELECTED CLASSES
    ====================================================== */

    async function loadSelectedClasses() {

        const selectedClasses =
            Array.from(
                document.querySelectorAll(
                    'input[name="class_ids[]"]:checked'
                )
            );

        const year =
            academicYear
                ? academicYear.value.trim()
                : '';


        if (
            selectedClasses.length === 0 ||
            year === ''
        ) {

            allStudents = [];

            loadedStudentIds =
                new Set();

            studentTableBody.innerHTML = '';

            studentsCard.classList.remove(
                'visible'
            );

            updateStatistics();

            return;

        }


        studentsCard.classList.add('visible');

        studentLoading.classList.add('active');

        studentEmpty.classList.remove('active');

        studentTableWrap.classList.remove(
            'active'
        );

        studentTableBody.innerHTML = '';

        allStudents = [];

        loadedStudentIds =
            new Set();


        try {

            const requests =
                selectedClasses.map(
                    function (checkbox) {

                        const url =
                            "{{ route('admin.scholarship.exams.students') }}"
                            + "?academic_year="
                            + encodeURIComponent(year)
                            + "&class_id="
                            + encodeURIComponent(
                                checkbox.value
                            );

                        return fetch(url, {

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }

                        })
                        .then(function (response) {

                            if (!response.ok) {

                                throw new Error(
                                    'Unable to load students.'
                                );

                            }

                            return response.json();

                        });

                    }
                );


            const responses =
                await Promise.all(requests);


            responses.forEach(function (response) {

                if (
                    response.success &&
                    Array.isArray(response.students)
                ) {

                    response.students.forEach(
                        function (student) {

                            const id =
                                Number(student.id);

                            if (
                                !loadedStudentIds.has(id)
                            ) {

                                loadedStudentIds.add(id);

                                allStudents.push(
                                    student
                                );

                            }

                        }
                    );

                }

            });


            allStudents.sort(
                function (a, b) {

                    const rollA =
                        parseInt(
                            a.roll_number || 0
                        );

                    const rollB =
                        parseInt(
                            b.roll_number || 0
                        );

                    if (rollA !== rollB) {

                        return rollA - rollB;

                    }

                    return String(
                        a.first_name || ''
                    ).localeCompare(
                        String(
                            b.first_name || ''
                        )
                    );

                }
            );


            renderStudents();


        } catch (error) {

            console.error(error);

            studentLoading.classList.remove(
                'active'
            );

            studentTableWrap.classList.remove(
                'active'
            );

            studentEmpty.classList.add(
                'active'
            );

            studentEmptyMessage.textContent =
                'Unable to load students. Please check the Academic Year, Class records and Student records.';

            allStudents = [];

            updateStatistics();

        }

    }


    /* =====================================================
       RENDER STUDENTS
    ====================================================== */

    function renderStudents() {

        studentLoading.classList.remove(
            'active'
        );

        studentTableBody.innerHTML = '';

        studentCount.textContent =
            allStudents.length;


        if (allStudents.length === 0) {

            studentTableWrap.classList.remove(
                'active'
            );

            studentEmpty.classList.add(
                'active'
            );

            studentEmptyMessage.textContent =
                'No active students were found for the selected classes and academic year.';

            updateStatistics();

            return;

        }


        studentEmpty.classList.remove(
            'active'
        );

        studentTableWrap.classList.add(
            'active'
        );


        allStudents.forEach(function (student) {

            const studentId =
                Number(student.id);

            const row =
                document.createElement('tr');

            row.dataset.studentId =
                studentId;

            const fullName =
                [
                    student.first_name,
                    student.middle_name,
                    student.last_name
                ]
                    .filter(Boolean)
                    .join(' ');


            row.innerHTML = `

                <td>
                    <span class="student-roll">
                        ${escapeHtml(student.roll_number || '—')}
                    </span>
                </td>

                <td>
                    <span class="student-name">
                        ${escapeHtml(fullName || 'Unnamed Student')}
                    </span>

                    ${
                        student.student_id
                            ? `
                                <span class="student-id">
                                    ID: ${escapeHtml(student.student_id)}
                                </span>
                              `
                            : ''
                    }
                </td>

                <td>
                    <span class="student-class">
                        ${escapeHtml(student.class || '—')}
                        ${
                            student.section
                                ? ' / ' + escapeHtml(student.section)
                                : ''
                        }
                    </span>
                </td>

                <td>

                    <div class="student-status">

                        <div class="status-option">

                            <input
                                type="radio"
                                name="student_records[${studentId}]"
                                value="not_applied"
                                id="student_${studentId}_not_applied"
                                checked>

                            <label
                                for="student_${studentId}_not_applied">

                                Not Applied

                            </label>

                        </div>


                        <div class="status-option">

                            <input
                                type="radio"
                                name="student_records[${studentId}]"
                                value="applied"
                                id="student_${studentId}_applied">

                            <label
                                for="student_${studentId}_applied">

                                Applied

                            </label>

                        </div>


                        <div class="status-option">

                            <input
                                type="radio"
                                name="student_records[${studentId}]"
                                value="appeared"
                                id="student_${studentId}_appeared">

                            <label
                                for="student_${studentId}_appeared">

                                Appeared

                            </label>

                        </div>


                        <div class="status-option">

                            <input
                                type="radio"
                                name="student_records[${studentId}]"
                                value="passed"
                                id="student_${studentId}_passed">

                            <label
                                for="student_${studentId}_passed">

                                Passed

                            </label>

                        </div>

                    </div>

                </td>

            `;


            studentTableBody.appendChild(row);

        });


        attachStatusListeners();

        updateStatistics();

    }


    /* =====================================================
       STATUS LISTENERS
    ====================================================== */

    function attachStatusListeners() {

        const radios =
            studentTableBody.querySelectorAll(
                'input[type="radio"]'
            );

        radios.forEach(function (radio) {

            radio.addEventListener(
                'change',
                function () {

                    updateStatistics();

                }
            );

        });

    }


    /* =====================================================
       LIVE STATISTICS
    ====================================================== */

    function updateStatistics() {

        const eligible =
            allStudents.length;

        let applied = 0;

        let appeared = 0;

        let passed = 0;


        allStudents.forEach(
            function (student) {

                const studentId =
                    Number(student.id);

                const selected =
                    document.querySelector(
                        `input[name="student_records[${studentId}]"]:checked`
                    );

                if (!selected) {
                    return;
                }

                const status =
                    selected.value;


                if (
                    status === 'applied' ||
                    status === 'appeared' ||
                    status === 'passed'
                ) {

                    applied++;

                }


                if (
                    status === 'appeared' ||
                    status === 'passed'
                ) {

                    appeared++;

                }


                if (status === 'passed') {

                    passed++;

                }

            }
        );


        eligibleCount.textContent =
            eligible;

        appliedCount.textContent =
            applied;

        appearedCount.textContent =
            appeared;

        passedCount.textContent =
            passed;

    }


    /* =====================================================
       SEARCH
    ====================================================== */

    if (studentSearch) {

        studentSearch.addEventListener(
            'input',
            function () {

                const search =
                    this.value
                        .trim()
                        .toLowerCase();

                const rows =
                    studentTableBody.querySelectorAll(
                        'tr'
                    );


                rows.forEach(function (row) {

                    const text =
                        row.textContent
                            .toLowerCase();

                    if (
                        search === '' ||
                        text.includes(search)
                    ) {

                        row.classList.remove(
                            'student-hidden'
                        );

                    } else {

                        row.classList.add(
                            'student-hidden'
                        );

                    }

                });

            }
        );

    }


    /* =====================================================
       FORM SUBMIT
    ====================================================== */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                const selectedClasses =
                    document.querySelectorAll(
                        'input[name="class_ids[]"]:checked'
                    );


                if (
                    selectedClasses.length === 0
                ) {

                    event.preventDefault();

                    alert(
                        'Please select at least one participating class.'
                    );

                    return;

                }


                if (
                    !academicYear ||
                    academicYear.value.trim() === ''
                ) {

                    event.preventDefault();

                    alert(
                        'Please enter the Academic Year.'
                    );

                    academicYear?.focus();

                    return;

                }


                updateStatistics();


                if (saveExamButton) {

                    saveExamButton.disabled =
                        true;

                    saveExamButton.innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i>
                        Saving Exam Record...
                    `;

                }

            }
        );

    }


    /* =====================================================
       HTML ESCAPE
    ====================================================== */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* =====================================================
       INITIAL STATE
    ====================================================== */

    updateSelectAllButton();

});
</script>

@endsection
