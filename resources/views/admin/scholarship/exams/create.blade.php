@extends('layouts.app')

@section('title', 'Add Scholarship Exam Record | Admin')
@section('page-title', 'Add Scholarship Exam Record')

@section('content')

<style>
/* =========================================================
   SCHOLARSHIP EXAM CREATE — SIMPLE SCHOOL DIARY DESIGN
========================================================= */
.scholarship-create-page {
    --paper: #fffefa;
    --ink: #263b35;
    --muted: #849087;
    --wood: #a87543;
    --green: #34775a;
    --green-dark: #245a43;
    --green-light: #eaf3e9;
    --red: #bd5148;
    --red-light: #fff0ec;
    --border: #e5e1d3;

    min-height: calc(100vh - 70px);
    padding: 24px;
    background: #f5f3e9;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
}

.scholarship-create-page *,
.scholarship-create-page *::before,
.scholarship-create-page *::after {
    box-sizing: border-box;
}

/* SIMPLE HEADER */
.scholarship-create-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
    padding: 22px 24px;
    border: 1px solid var(--border);
    border-left: 4px solid var(--green);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.04);
}

.scholarship-create-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 18px;
}

.scholarship-create-title {
    min-width: 0;
}

.scholarship-eyebrow {
    display: inline-block;
    margin-bottom: 6px;
    color: var(--green);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.scholarship-create-title h1 {
    margin: 0;
    color: var(--ink);
    font-size: 22px;
    line-height: 1.4;
    font-weight: 800;
}

.scholarship-create-title p {
    margin: 5px 0 0;
    color: #7b857a;
    font-size: 11px;
    line-height: 1.7;
}

.btn-back-exams {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    padding: 10px 14px;
    border: 1px solid #dcd9ca;
    border-radius: 8px;
    background: #fffefa;
    color: #52665c;
    text-decoration: none;
    font-size: 11px;
    font-weight: 750;
    transition: .2s ease;
}

.btn-back-exams:hover {
    border-color: #a8c0a5;
    background: #eef5e9;
    color: var(--green-dark);
}

/* ALERTS */
.scholarship-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 19px;
    padding: 15px 17px;
    border: 1px solid;
    border-radius: 11px;
    font-size: 11px;
    line-height: 1.7;
}

.scholarship-alert-danger {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

.scholarship-alert > i {
    margin-top: 2px;
    font-size: 15px;
}

/* MAIN GRID */
.scholarship-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 21px;
    align-items: start;
}

.scholarship-form-main,
.scholarship-form-sidebar {
    min-width: 0;
}

/* CARDS */
.scholarship-form-card {
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54,64,43,.035);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.scholarship-form-card:hover {
    border-color: #d7d3c2;
    box-shadow: 0 5px 17px rgba(54,64,43,.05);
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 18px;
    border-bottom: 1px solid #ece8dc;
    background: #faf8f0;
}

.form-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 14px;
}

.form-card-icon.orange {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.form-card-icon.cyan {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: #527f79;
}

.form-card-icon.green {
    border-color: #d6e6d5;
    background: #eaf3e9;
    color: #34775a;
}

.form-card-header h2 {
    margin: 0;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.form-card-header p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.form-card-body {
    padding: 20px;
}

/* FORM FIELDS */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 16px;
}

.form-group {
    min-width: 0;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 7px;
    color: #56665a;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .45px;
    text-transform: uppercase;
}

.required {
    margin-left: 2px;
    color: var(--red);
}

.form-control-scholarship {
    display: block;
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    border: 1px solid #dedbcc;
    border-radius: 8px;
    outline: none;
    background: #fffefb;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.form-control-scholarship::placeholder {
    color: #a5a79b;
}

.form-control-scholarship:hover {
    border-color: #c9c5b3;
}

.form-control-scholarship:focus {
    border-color: var(--green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(52,119,90,.10);
}

.form-control-scholarship:disabled {
    cursor: not-allowed;
    opacity: .65;
    background: #f0eee6;
}

textarea.form-control-scholarship {
    min-height: 105px;
    resize: vertical;
    line-height: 1.7;
}

.form-help {
    margin-top: 6px;
    color: #92988b;
    font-size: 9px;
    line-height: 1.6;
}

.invalid-feedback-custom {
    margin-top: 6px;
    color: var(--red);
    font-size: 10px;
    font-weight: 700;
}

.input-error {
    border-color: var(--red) !important;
    background: #fff8f5 !important;
}

.input-error:focus {
    box-shadow: 0 0 0 3px rgba(189,81,72,.09) !important;
}

/* CLASS SELECTION */
.class-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 13px;
}

.class-toolbar-title {
    color: #7d8679;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.6;
}

.class-toolbar-actions {
    display: flex;
    flex-shrink: 0;
    gap: 7px;
}

.btn-select-all {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 11px;
    border: 1px solid #d8e3d2;
    border-radius: 7px;
    background: #f1f6ed;
    color: var(--green-dark);
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    transition: .18s ease;
}

.btn-select-all:hover {
    border-color: #a7c2a0;
    background: #e6efdf;
}

.class-selection {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 9px;
}

.class-option {
    position: relative;
    min-width: 0;
}

.class-option input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.class-option label {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 58px;
    padding: 10px;
    border: 1px solid #e3e0d5;
    border-radius: 9px;
    background: #fffefa;
    cursor: pointer;
    transition: .18s ease;
}

.class-option label:hover {
    border-color: #b6c9ad;
    background: #f8f9f0;
}

.class-option input:checked + label {
    border-color: #6d9b76;
    background: #eef5e9;
    box-shadow: 0 0 0 2px rgba(52,119,90,.045);
}

.class-option input:focus-visible + label {
    outline: 2px solid var(--green);
    outline-offset: 2px;
}

.class-check {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    flex: 0 0 20px;
    border: 1px solid #d5d7c9;
    border-radius: 5px;
    background: #fffefa;
    color: transparent;
    font-size: 10px;
    transition: .18s ease;
}

.class-option input:checked + label .class-check {
    border-color: var(--green);
    background: var(--green);
    color: #fff;
}

.class-info {
    min-width: 0;
}

.class-name {
    display: block;
    overflow: hidden;
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.class-section {
    display: block;
    margin-top: 4px;
    color: #8d9486;
    font-size: 9px;
}

.no-classes {
    padding: 28px 18px;
    border: 1px dashed #d4d4c4;
    border-radius: 11px;
    background: #faf9f2;
    text-align: center;
}

.no-classes-icon,
.student-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 47px;
    height: 47px;
    margin: 0 auto 11px;
    border-radius: 13px;
    background: #edf3e6;
    color: var(--green);
    font-size: 19px;
}

.no-classes strong,
.student-empty strong {
    display: block;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.no-classes span,
.student-empty span {
    display: block;
    margin-top: 6px;
    color: #8b9184;
    font-size: 10px;
    line-height: 1.7;
}

/* STUDENT PARTICIPATION */
.students-card {
    display: none;
}

.students-card.visible {
    display: block;
    animation: scholarshipReveal .25s ease;
}

@keyframes scholarshipReveal {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

.student-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 15px;
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
    color: #959b8c;
    font-size: 11px;
}

.student-search input {
    width: 100%;
    height: 40px;
    padding: 9px 12px 9px 34px;
    border: 1px solid #dedbcc;
    border-radius: 8px;
    outline: none;
    background: #fffefa;
    color: var(--ink);
    font-family: inherit;
    font-size: 11px;
    transition: .18s ease;
}

.student-search input:focus {
    border-color: var(--green);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(52,119,90,.08);
}

.student-count {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 11px;
    border: 1px solid #dce5d5;
    border-radius: 8px;
    background: #f2f6ed;
    color: #74806e;
    font-size: 10px;
    font-weight: 750;
}

.student-count strong {
    color: var(--green-dark);
    font-size: 14px;
    font-weight: 900;
}

.student-loading,
.student-empty {
    display: none;
    padding: 32px 15px;
    border: 1px solid #e7e4d9;
    border-radius: 10px;
    background: #fbfaf5;
    text-align: center;
}

.student-loading.active,
.student-empty.active {
    display: block;
}

.loading-spinner {
    width: 31px;
    height: 31px;
    margin: 0 auto 12px;
    border: 3px solid #e5e5d9;
    border-top-color: var(--green);
    border-radius: 50%;
    animation: scholarshipSpin .75s linear infinite;
}

@keyframes scholarshipSpin {
    to { transform: rotate(360deg); }
}

.loading-text {
    color: #7e897a;
    font-size: 10px;
    font-weight: 750;
}

.student-table-wrap {
    display: none;
    overflow-x: auto;
    border: 1px solid #e3e0d5;
    border-radius: 10px;
}

.student-table-wrap.active {
    display: block;
}

.student-table {
    width: 100%;
    min-width: 760px;
    border-collapse: separate;
    border-spacing: 0;
}

.student-table th {
    padding: 12px 13px;
    border-bottom: 1px solid #e4e0d3;
    background: #f3f0e5;
    color: #697365;
    font-size: 9px;
    font-weight: 850;
    letter-spacing: .45px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.student-table td {
    padding: 12px 13px;
    border-bottom: 1px solid #eeece2;
    color: #4f5d50;
    font-size: 10px;
    vertical-align: middle;
}

.student-table tbody tr {
    transition: background .15s ease;
}

.student-table tbody tr:hover {
    background: #f9f8f0;
}

.student-table tbody tr:last-child td {
    border-bottom: 0;
}

.student-table tr.student-hidden {
    display: none;
}

.student-roll {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    padding: 5px 7px;
    border: 1px solid #dce5d5;
    border-radius: 6px;
    background: #f0f5e9;
    color: var(--green-dark);
    font-weight: 850;
}

.student-name {
    display: block;
    color: var(--ink);
    font-size: 10px;
    font-weight: 800;
}

.student-id {
    display: block;
    margin-top: 4px;
    color: #969b8e;
    font-size: 9px;
}

.student-class {
    color: #657264;
    font-weight: 750;
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
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.status-option label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 70px;
    min-height: 29px;
    padding: 5px 8px;
    border: 1px solid #e0ded3;
    border-radius: 6px;
    background: #fffefa;
    color: #7c8477;
    cursor: pointer;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
    transition: .15s ease;
}

.status-option label:hover {
    border-color: #bdcbb4;
}

.status-option input:focus-visible + label {
    outline: 2px solid var(--green);
    outline-offset: 2px;
}

.status-option input[value="applied"]:checked + label {
    border-color: #b4c9a8;
    background: #edf4e7;
    color: #476c3d;
}

.status-option input[value="appeared"]:checked + label {
    border-color: #b4d4cf;
    background: #edf6f2;
    color: #39786d;
}

.status-option input[value="passed"]:checked + label {
    border-color: #9fc6a4;
    background: #e8f4e7;
    color: #287344;
}

.status-option input[value="not_applied"]:checked + label {
    border-color: #d4d3c8;
    background: #f3f1e9;
    color: #687064;
}

.student-status-help {
    margin-top: 13px;
    padding: 12px 13px;
    border: 1px solid #e3e3d2;
    border-radius: 8px;
    background: #f7f7ed;
    color: #737d6d;
    font-size: 10px;
    line-height: 1.9;
}

.student-status-help strong {
    color: var(--ink);
}

/* LIVE STATISTICS */
.statistics-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}

.stat-box {
    padding: 15px 13px;
    border: 1px solid #e5e2d7;
    border-radius: 9px;
    background: #fffef9;
}

.stat-box.eligible { border-left: 3px solid #547c96; }
.stat-box.applied { border-left: 3px solid var(--wood); }
.stat-box.appeared { border-left: 3px solid #5a958b; }
.stat-box.passed { border-left: 3px solid #43815b; }

.stat-label {
    display: block;
    color: #7c8678;
    font-size: 9px;
    font-weight: 850;
    letter-spacing: .35px;
    text-transform: uppercase;
}

.stat-value {
    display: block;
    margin-top: 8px;
    color: var(--ink);
    font-size: 25px;
    line-height: 1;
    font-weight: 850;
    font-variant-numeric: tabular-nums;
}

.stat-description {
    display: block;
    margin-top: 7px;
    color: #979b8e;
    font-size: 9px;
    line-height: 1.5;
}

/* SIDEBAR */
.side-info-card .form-card-body {
    padding: 19px;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 19px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.info-item-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    border: 1px solid #dce7d6;
    border-radius: 8px;
    background: #eff4e9;
    color: var(--green);
    font-size: 11px;
}

.info-item:nth-child(2) .info-item-icon {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.info-item:nth-child(3) .info-item-icon {
    border-color: #d6e6d5;
    background: #eaf3e9;
    color: #34775a;
}

.info-item:nth-child(4) .info-item-icon {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: #527f79;
}

.info-item strong {
    display: block;
    color: #35483b;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.6;
}

.info-item span {
    display: block;
    margin-top: 4px;
    color: #879084;
    font-size: 9px;
    line-height: 1.8;
}

/* RECORD STATUS */
.status-box {
    padding: 15px;
    border: 1px solid #e1e2d3;
    border-radius: 9px;
    background: #f9f8f0;
}

.status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 13px;
}

.status-text strong {
    display: block;
    color: #35483b;
    font-size: 11px;
    font-weight: 800;
}

.status-text span {
    display: block;
    margin-top: 5px;
    color: #899184;
    font-size: 9px;
    line-height: 1.7;
}

/* TOGGLE */
.switch {
    position: relative;
    display: inline-block;
    width: 42px;
    height: 23px;
    flex: 0 0 42px;
}

.switch input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.switch .slider {
    position: absolute;
    inset: 0;
    border-radius: 30px;
    background: #c8cbbd;
    cursor: pointer;
    transition: background .2s ease;
}

.switch .slider::before {
    content: "";
    position: absolute;
    width: 17px;
    height: 17px;
    top: 3px;
    left: 3px;
    border-radius: 50%;
    background: #fffefa;
    box-shadow: 0 2px 5px rgba(0,0,0,.12);
    transition: transform .2s ease;
}

.switch input:checked + .slider {
    background: #43815b;
}

.switch input:checked + .slider::before {
    transform: translateX(19px);
}

.switch input:focus-visible + .slider {
    outline: 2px solid var(--green);
    outline-offset: 3px;
}

/* DATA RULES */
.data-rule-box {
    padding: 13px;
    border: 1px solid #eadcc1;
    border-radius: 9px;
    background: #fbf5e8;
}

.data-rule-content {
    display: flex;
    align-items: flex-start;
    gap: 9px;
}

.data-rule-content > i {
    margin-top: 3px;
    color: var(--wood);
    font-size: 13px;
}

.data-rule-content span {
    color: #776849;
    font-size: 10px;
    line-height: 1.9;
}

/* FORM ACTIONS */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 19px;
    border-top: 1px solid #ece8dc;
    background: #faf8f0;
}

.btn-cancel,
.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 17px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.btn-cancel {
    border: 1px solid #dcd9ca;
    background: #fffefa;
    color: #687466;
}

.btn-cancel:hover {
    border-color: #c6c4b4;
    background: #f3f1e8;
    color: var(--ink);
}

.btn-save {
    border: 1px solid #315f45;
    background: linear-gradient(135deg, #315f45, #4b8059);
    color: #fff;
    box-shadow: 0 4px 10px rgba(49,95,69,.12);
}

.btn-save:hover {
    border-color: #244a35;
    background: linear-gradient(135deg, #244a35, #386b49);
    color: #fff;
    transform: translateY(-1px);
}

.btn-save:disabled {
    opacity: .7;
    cursor: not-allowed;
    transform: none;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
    .scholarship-form-grid {
        grid-template-columns: minmax(0, 1fr) 275px;
        gap: 15px;
    }

    .class-selection {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .statistics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 980px) {
    .scholarship-form-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .scholarship-form-sidebar {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: start;
        gap: 0 15px;
    }

    .scholarship-form-sidebar .side-info-card {
        grid-row: span 2;
    }
}

@media (max-width: 768px) {
    .scholarship-create-page {
        padding: 14px;
    }

    .scholarship-create-header {
        padding: 17px;
    }

    .scholarship-create-header-content {
        align-items: flex-start;
        flex-direction: column;
        gap: 14px;
    }

    .scholarship-create-title h1 {
        font-size: 19px;
    }

    .btn-back-exams {
        width: 100%;
    }

    .form-card-header {
        padding: 14px 15px;
    }

    .form-card-body {
        padding: 16px;
    }

    .scholarship-form-sidebar {
        display: block;
    }

    .form-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 15px;
    }

    .form-group.full {
        grid-column: auto;
    }

    .class-selection {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px;
    }

    .form-actions a,
    .form-actions button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .scholarship-create-page {
        padding: 10px;
    }

    .scholarship-create-header {
        padding: 15px;
    }

    .scholarship-create-title h1 {
        font-size: 17px;
    }

    .scholarship-create-title p {
        font-size: 10px;
    }

    .class-toolbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .class-selection {
        grid-template-columns: minmax(0, 1fr);
    }

    .statistics-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .student-search {
        min-width: 0;
        width: 100%;
    }

    .student-count {
        align-self: flex-start;
    }

    .scholarship-form-card {
        border-radius: 11px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-create-page *,
    .scholarship-create-page *::before,
    .scholarship-create-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<div class="scholarship-create-page">

    {{-- SIMPLE HEADER --}}
    <div class="scholarship-create-header">
        <div class="scholarship-create-header-content">
            <div class="scholarship-create-title">
                <div class="scholarship-eyebrow">
                    <i class="fas fa-graduation-cap"></i>
                    Scholarship Management
                </div>

                <h1>Add Scholarship Exam Record</h1>

                <p>
                    Add examination details and record student participation.
                </p>
            </div>

            <a href="{{ route('admin.scholarship.exams.index') }}"
               class="btn-back-exams">
                <i class="fas fa-arrow-left"></i>
                Back to Exam Records
            </a>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="scholarship-alert scholarship-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please correct the following errors:</strong>
                <ul style="margin:6px 0 0 17px;padding:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST"
          action="{{ route('admin.scholarship.exams.store') }}"
          id="scholarshipExamForm">
        @csrf

        <div class="scholarship-form-grid">

            {{-- LEFT: MAIN FORM --}}
            <div class="scholarship-form-main">

                {{-- EXAM INFORMATION --}}
                <div class="scholarship-form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <h2>Examination Information</h2>
                            <p>Basic details of the scholarship examination</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="form-grid">

                            <div class="form-group full">
                                <label class="form-label">
                                    Exam Name <span class="required">*</span>
                                </label>

                                <input type="text"
                                       name="exam_name"
                                       value="{{ old('exam_name') }}"
                                       class="form-control-scholarship {{ $errors->has('exam_name') ? 'input-error' : '' }}"
                                       placeholder="e.g. Maharashtra Scholarship Examination"
                                       required>

                                @if($errors->has('exam_name'))
                                    <div class="invalid-feedback-custom">
                                        {{ $errors->first('exam_name') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Academic Year <span class="required">*</span>
                                </label>

                                <input type="text"
                                       name="academic_year"
                                       id="academicYear"
                                       value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                                       class="form-control-scholarship {{ $errors->has('academic_year') ? 'input-error' : '' }}"
                                       placeholder="e.g. 2026-2027"
                                       required>

                                <div class="form-help">
                                    Match the Academic Year used in Student Management.
                                </div>

                                @if($errors->has('academic_year'))
                                    <div class="invalid-feedback-custom">
                                        {{ $errors->first('academic_year') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Exam Date <span class="required">*</span>
                                </label>

                                <input type="date"
                                       name="exam_date"
                                       value="{{ old('exam_date') }}"
                                       class="form-control-scholarship {{ $errors->has('exam_date') ? 'input-error' : '' }}"
                                       required>

                                @if($errors->has('exam_date'))
                                    <div class="invalid-feedback-custom">
                                        {{ $errors->first('exam_date') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Exam Type <span class="required">*</span>
                                </label>

                                <select name="exam_type"
                                        class="form-control-scholarship {{ $errors->has('exam_type') ? 'input-error' : '' }}"
                                        required>
                                    <option value="">Select Exam Type</option>

                                    @foreach([
                                        'Government Scholarship',
                                        'School Scholarship',
                                        'State Scholarship',
                                        'National Scholarship',
                                        'Other'
                                    ] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('exam_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>

                                @if($errors->has('exam_type'))
                                    <div class="invalid-feedback-custom">
                                        {{ $errors->first('exam_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">Conducted By</label>

                                <input type="text"
                                       name="conducted_by"
                                       value="{{ old('conducted_by') }}"
                                       class="form-control-scholarship"
                                       placeholder="e.g. School / Education Department">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- PARTICIPATING CLASSES --}}
                <div class="scholarship-form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon orange">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <h2>Participating Classes</h2>
                            <p>Select classes and sections from Class Management</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="class-toolbar">
                            <span class="class-toolbar-title">
                                Select one or more classes / sections
                            </span>

                            <div class="class-toolbar-actions">
                                <button type="button"
                                        class="btn-select-all"
                                        id="selectAllClasses">
                                    <i class="fas fa-check-double"></i>
                                    Select All
                                </button>
                            </div>
                        </div>

                        @if($classes->count())
                            <div class="class-selection">
                                @foreach($classes as $class)
                                    <div class="class-option">
                                        <input type="checkbox"
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

                                <strong>No Active Classes Found</strong>

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

                {{-- STUDENT PARTICIPATION --}}
                <div class="scholarship-form-card students-card"
                     id="studentsCard">
                    <div class="form-card-header">
                        <div class="form-card-icon green">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h2>Student Participation</h2>
                            <p>Record the participation status of each student</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="student-toolbar">
                            <div class="student-search">
                                <i class="fas fa-search"></i>
                                <input type="text"
                                       id="studentSearch"
                                       placeholder="Search student name, roll number or class...">
                            </div>

                            <div class="student-count">
                                <span>Students Loaded</span>
                                <strong id="studentCount">0</strong>
                            </div>
                        </div>

                        <div class="student-loading" id="studentLoading">
                            <div class="loading-spinner"></div>
                            <div class="loading-text">
                                Loading students from Student Management...
                            </div>
                        </div>

                        <div class="student-empty" id="studentEmpty">
                            <div class="student-empty-icon">
                                <i class="fas fa-user-slash"></i>
                            </div>

                            <strong>No Students Found</strong>

                            <span id="studentEmptyMessage">
                                No active students were found for the selected classes and academic year.
                            </span>
                        </div>

                        <div class="student-table-wrap" id="studentTableWrap">
                            <table class="student-table">
                                <thead>
                                    <tr>
                                        <th style="width:65px;">Roll No.</th>
                                        <th>Student</th>
                                        <th style="width:120px;">Class</th>
                                        <th>Participation Status</th>
                                    </tr>
                                </thead>

                                <tbody id="studentTableBody"></tbody>
                            </table>
                        </div>

                        <div class="student-status-help">
                            <strong>Status:</strong>
                            Applied = registered for the exam.
                            Appeared = attended the exam.
                            Passed = successfully passed the exam.
                            Not Applied students are not stored as applications.
                        </div>
                    </div>
                </div>

                {{-- STATISTICS --}}
                <div class="scholarship-form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon cyan">
                            <i class="fas fa-chart-column"></i>
                        </div>
                        <div>
                            <h2>Examination Statistics</h2>
                            <p>Automatically calculated from student participation</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="statistics-grid">
                            <div class="stat-box eligible">
                                <span class="stat-label">Eligible</span>
                                <span class="stat-value" id="eligibleCount">0</span>
                                <span class="stat-description">Selected classes</span>
                            </div>

                            <div class="stat-box applied">
                                <span class="stat-label">Applied</span>
                                <span class="stat-value" id="appliedCount">0</span>
                                <span class="stat-description">Registered</span>
                            </div>

                            <div class="stat-box appeared">
                                <span class="stat-label">Appeared</span>
                                <span class="stat-value" id="appearedCount">0</span>
                                <span class="stat-description">Attended exam</span>
                            </div>

                            <div class="stat-box passed">
                                <span class="stat-label">Passed</span>
                                <span class="stat-value" id="passedCount">0</span>
                                <span class="stat-description">Successful</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REMARKS --}}
                <div class="scholarship-form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon">
                            <i class="fas fa-note-sticky"></i>
                        </div>
                        <div>
                            <h2>Additional Information</h2>
                            <p>Optional notes for future reference</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="form-group">
                            <label class="form-label">Remarks</label>

                            <textarea name="remarks"
                                      class="form-control-scholarship"
                                      placeholder="Enter additional information about this scholarship examination...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="scholarship-form-card">
                    <div class="form-actions">
                        <a href="{{ route('admin.scholarship.exams.index') }}"
                           class="btn-cancel">
                            <i class="fas fa-arrow-left"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn-save"
                                id="saveExamButton">
                            <i class="fas fa-save"></i>
                            Save Exam Record
                        </button>
                    </div>
                </div>

            </div>

            {{-- RIGHT: SIDEBAR --}}
            <div class="scholarship-form-sidebar">

                {{-- RECORD INFORMATION --}}
                <div class="scholarship-form-card side-info-card">
                    <div class="form-card-header">
                        <div class="form-card-icon">
                            <i class="fas fa-circle-info"></i>
                        </div>
                        <div>
                            <h2>Record Information</h2>
                            <p>Information stored in this module</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="info-list">

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <strong>Year-wise History</strong>
                                    <span>
                                        Maintain examination records for every academic year.
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div>
                                    <strong>Existing Classes</strong>
                                    <span>
                                        Classes and sections are taken directly from Class Management.
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <strong>Student Records</strong>
                                    <span>
                                        Students are loaded from Student Management.
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-chart-simple"></i>
                                </div>
                                <div>
                                    <strong>Automatic Statistics</strong>
                                    <span>
                                        Eligible, Applied, Appeared and Passed totals update automatically.
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-item-icon">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div>
                                    <strong>Result Tracking</strong>
                                    <span>
                                        Passed students remain available for future result reference.
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RECORD STATUS --}}
                <div class="scholarship-form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon green">
                            <i class="fas fa-toggle-on"></i>
                        </div>
                        <div>
                            <h2>Record Status</h2>
                            <p>Control historical record visibility</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="status-box">
                            <div class="status-row">
                                <div class="status-text">
                                    <strong>Active Record</strong>
                                    <span>
                                        Keep this examination available in records.
                                    </span>
                                </div>

                                <label class="switch">
                                    <input type="checkbox"
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
                            <p>Participation validation</p>
                        </div>
                    </div>

                    <div class="form-card-body">
                        <div class="data-rule-box">
                            <div class="data-rule-content">
                                <i class="fas fa-check-circle"></i>
                                <span>
                                    Appeared automatically counts as Applied.
                                    Passed automatically counts as both Appeared
                                    and Applied. Not Applied students are not stored.
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

    const form = document.getElementById('scholarshipExamForm');
    const academicYear = document.getElementById('academicYear');
    const selectAllButton = document.getElementById('selectAllClasses');

    const classCheckboxes = document.querySelectorAll(
        'input[name="class_ids[]"]'
    );

    const studentsCard = document.getElementById('studentsCard');
    const studentLoading = document.getElementById('studentLoading');
    const studentEmpty = document.getElementById('studentEmpty');
    const studentEmptyMessage = document.getElementById('studentEmptyMessage');
    const studentTableWrap = document.getElementById('studentTableWrap');
    const studentTableBody = document.getElementById('studentTableBody');
    const studentSearch = document.getElementById('studentSearch');
    const studentCount = document.getElementById('studentCount');

    const eligibleCount = document.getElementById('eligibleCount');
    const appliedCount = document.getElementById('appliedCount');
    const appearedCount = document.getElementById('appearedCount');
    const passedCount = document.getElementById('passedCount');

    const saveExamButton = document.getElementById('saveExamButton');

    let allStudents = [];
    let loadedStudentIds = new Set();
    let requestSequence = 0;

    /* SELECT ALL CLASSES */
    if (selectAllButton) {
        selectAllButton.addEventListener('click', function () {
            const allChecked = Array.from(classCheckboxes).every(
                function (checkbox) {
                    return checkbox.checked;
                }
            );

            classCheckboxes.forEach(function (checkbox) {
                checkbox.checked = !allChecked;
            });

            updateSelectAllButton();
            loadSelectedClasses();
        });
    }

    /* CLASS CHANGE */
    classCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateSelectAllButton();
            loadSelectedClasses();
        });
    });

    function updateSelectAllButton() {
        if (!selectAllButton) return;

        const checkedCount = Array.from(classCheckboxes).filter(
            function (checkbox) {
                return checkbox.checked;
            }
        ).length;

        selectAllButton.innerHTML =
            checkedCount === classCheckboxes.length &&
            classCheckboxes.length > 0
                ? '<i class="fas fa-times"></i> Clear All'
                : '<i class="fas fa-check-double"></i> Select All';
    }

    /* ACADEMIC YEAR CHANGE */
    if (academicYear) {
        academicYear.addEventListener('change', function () {
            if (document.querySelectorAll(
                'input[name="class_ids[]"]:checked'
            ).length) {
                loadSelectedClasses();
            }
        });
    }

    /* LOAD SELECTED CLASSES */
    async function loadSelectedClasses() {
        const currentRequest = ++requestSequence;

        const selectedClasses = Array.from(
            document.querySelectorAll(
                'input[name="class_ids[]"]:checked'
            )
        );

        const year = academicYear ? academicYear.value.trim() : '';

        if (selectedClasses.length === 0 || year === '') {
            allStudents = [];
            loadedStudentIds = new Set();

            studentTableBody.innerHTML = '';
            studentCount.textContent = '0';

            studentLoading.classList.remove('active');
            studentEmpty.classList.remove('active');
            studentTableWrap.classList.remove('active');
            studentsCard.classList.remove('visible');

            updateStatistics();
            return;
        }

        studentsCard.classList.add('visible');
        studentLoading.classList.add('active');
        studentEmpty.classList.remove('active');
        studentTableWrap.classList.remove('active');

        studentTableBody.innerHTML = '';
        studentCount.textContent = '0';

        allStudents = [];
        loadedStudentIds = new Set();

        updateStatistics();

        try {
            const requests = selectedClasses.map(function (checkbox) {
                const url =
                    "{{ route('admin.scholarship.exams.students') }}"
                    + "?academic_year=" + encodeURIComponent(year)
                    + "&class_id=" + encodeURIComponent(checkbox.value);

                return fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function (response) {
                    if (!response.ok) {
                        throw new Error('Unable to load students.');
                    }

                    return response.json();
                });
            });

            const responses = await Promise.all(requests);

            if (currentRequest !== requestSequence) return;

            responses.forEach(function (response) {
                if (response.success && Array.isArray(response.students)) {
                    response.students.forEach(function (student) {
                        const id = Number(student.id);

                        if (!loadedStudentIds.has(id)) {
                            loadedStudentIds.add(id);
                            allStudents.push(student);
                        }
                    });
                }
            });

            allStudents.sort(function (a, b) {
                const rollA = parseInt(a.roll_number || 0, 10);
                const rollB = parseInt(b.roll_number || 0, 10);

                if (rollA !== rollB) return rollA - rollB;

                return String(a.first_name || '').localeCompare(
                    String(b.first_name || '')
                );
            });

            renderStudents();

        } catch (error) {
            if (currentRequest !== requestSequence) return;

            console.error(error);

            studentLoading.classList.remove('active');
            studentTableWrap.classList.remove('active');
            studentEmpty.classList.add('active');

            studentEmptyMessage.textContent =
                'Unable to load students. Please check the Academic Year, Class records and Student records.';

            allStudents = [];
            studentCount.textContent = '0';

            updateStatistics();
        }
    }

    /* RENDER STUDENTS */
    function renderStudents() {
        studentLoading.classList.remove('active');
        studentTableBody.innerHTML = '';

        studentCount.textContent = allStudents.length;

        if (allStudents.length === 0) {
            studentTableWrap.classList.remove('active');
            studentEmpty.classList.add('active');

            studentEmptyMessage.textContent =
                'No active students were found for the selected classes and academic year.';

            updateStatistics();
            return;
        }

        studentEmpty.classList.remove('active');
        studentTableWrap.classList.add('active');

        allStudents.forEach(function (student) {
            const studentId = Number(student.id);
            const row = document.createElement('tr');

            row.dataset.studentId = studentId;

            const fullName = [
                student.first_name,
                student.middle_name,
                student.last_name
            ].filter(Boolean).join(' ');

            const studentName = escapeHtml(fullName || 'Unnamed Student');
            const rollNumber = escapeHtml(student.roll_number || '—');
            const studentClass = escapeHtml(student.class || '—');

            const studentSection = student.section
                ? ' / ' + escapeHtml(student.section)
                : '';

            const studentIdentifier = student.student_id
                ? '<span class="student-id">ID: '
                    + escapeHtml(student.student_id)
                    + '</span>'
                : '';

            row.innerHTML = `
                <td>
                    <span class="student-roll">${rollNumber}</span>
                </td>
                <td>
                    <span class="student-name">${studentName}</span>
                    ${studentIdentifier}
                </td>
                <td>
                    <span class="student-class">
                        ${studentClass}${studentSection}
                    </span>
                </td>
                <td>
                    <div class="student-status">
                        <div class="status-option">
                            <input type="radio"
                                   name="student_records[${studentId}]"
                                   value="not_applied"
                                   id="student_${studentId}_not_applied"
                                   checked>
                            <label for="student_${studentId}_not_applied">
                                Not Applied
                            </label>
                        </div>

                        <div class="status-option">
                            <input type="radio"
                                   name="student_records[${studentId}]"
                                   value="applied"
                                   id="student_${studentId}_applied">
                            <label for="student_${studentId}_applied">
                                Applied
                            </label>
                        </div>

                        <div class="status-option">
                            <input type="radio"
                                   name="student_records[${studentId}]"
                                   value="appeared"
                                   id="student_${studentId}_appeared">
                            <label for="student_${studentId}_appeared">
                                Appeared
                            </label>
                        </div>

                        <div class="status-option">
                            <input type="radio"
                                   name="student_records[${studentId}]"
                                   value="passed"
                                   id="student_${studentId}_passed">
                            <label for="student_${studentId}_passed">
                                Passed
                            </label>
                        </div>
                    </div>
                </td>
            `;

            studentTableBody.appendChild(row);
        });

        attachStatusListeners();
        applyStudentSearch();
        updateStatistics();
    }

    /* STATUS LISTENERS */
    function attachStatusListeners() {
        const radios = studentTableBody.querySelectorAll(
            'input[type="radio"]'
        );

        radios.forEach(function (radio) {
            radio.addEventListener('change', updateStatistics);
        });
    }

    /* LIVE STATISTICS */
    function updateStatistics() {
        const eligible = allStudents.length;

        let applied = 0;
        let appeared = 0;
        let passed = 0;

        allStudents.forEach(function (student) {
            const studentId = Number(student.id);

            const selected = document.querySelector(
                `input[name="student_records[${studentId}]"]:checked`
            );

            if (!selected) return;

            const status = selected.value;

            if (['applied', 'appeared', 'passed'].includes(status)) {
                applied++;
            }

            if (['appeared', 'passed'].includes(status)) {
                appeared++;
            }

            if (status === 'passed') {
                passed++;
            }
        });

        eligibleCount.textContent = eligible;
        appliedCount.textContent = applied;
        appearedCount.textContent = appeared;
        passedCount.textContent = passed;
    }

    /* STUDENT SEARCH */
    if (studentSearch) {
        studentSearch.addEventListener('input', applyStudentSearch);
    }

    function applyStudentSearch() {
        const search = studentSearch
            ? studentSearch.value.trim().toLowerCase()
            : '';

        studentTableBody.querySelectorAll('tr').forEach(function (row) {
            const text = row.textContent.toLowerCase();

            row.classList.toggle(
                'student-hidden',
                search !== '' && !text.includes(search)
            );
        });
    }

    /* FORM SUBMISSION */
    if (form) {
        form.addEventListener('submit', function (event) {
            const selectedClasses = document.querySelectorAll(
                'input[name="class_ids[]"]:checked'
            );

            if (selectedClasses.length === 0) {
                event.preventDefault();
                alert('Please select at least one participating class.');
                return;
            }

            if (!academicYear || academicYear.value.trim() === '') {
                event.preventDefault();
                alert('Please enter the Academic Year.');
                academicYear?.focus();
                return;
            }

            updateStatistics();

            if (saveExamButton) {
                saveExamButton.disabled = true;
                saveExamButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Saving Exam Record...';
            }
        });
    }

    /* HTML ESCAPE */
    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* INITIAL STATE */
    updateSelectAllButton();

    if (document.querySelectorAll(
        'input[name="class_ids[]"]:checked'
    ).length > 0) {
        loadSelectedClasses();
    }
});
</script>

@endsection
