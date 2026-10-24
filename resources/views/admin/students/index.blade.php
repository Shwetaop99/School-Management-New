
@extends('layouts.app')

@section('title', 'Students | Admin')
@section('page-title', 'Students')

@section('content')

<style>
/* ==========================================================
   STUDENTS — LOCKED SCHOOL DIARY DESIGN SYSTEM
   Reference: Transport Records
   Forest green | Cream paper | Sage tables | Responsive
========================================================== */

.student-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.student-page *,
.student-page *::before,
.student-page *::after {
    box-sizing: border-box;
}

.student-page a,
.student-page button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.student-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* HERO */

.student-hero,
.student-paper-panel,
.student-filter-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

.student-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.student-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.student-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.student-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.student-hero-text {
    min-width: 0;
}

.student-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.student-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.student-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.student-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.student-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
}

.student-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.student-hero-actions::before,
.student-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.student-hero-actions::before {
    top: -115px;
    right: -60px;
}

.student-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.student-hero-decoration,
.student-hero-actions .diary-primary-btn,
.student-handwritten,
.student-hero-actions p {
    position: relative;
    z-index: 1;
}

.student-hero-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255,255,255,.07);
    font-size: 16px;
}

.student-handwritten {
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.student-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* BUTTON SYSTEM */

.student-page .diary-primary-btn,
.student-page .diary-secondary-btn,
.student-page .diary-apply-btn,
.student-page .diary-reset-btn,
.student-page .diary-outline-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    line-height: 1.4;
    text-decoration: none;
    cursor: pointer;
}

.student-page .diary-primary-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.student-page .student-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.student-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29,48,43,.13);
}

.student-page .student-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

.student-page .diary-secondary-btn {
    border-color: #d4e5d0;
    color: #347849;
    background: #f1f8ef;
}

.student-page .diary-secondary-btn:hover {
    border-color: #a8c9a3;
    color: #245a43;
    background: #e6f1e2;
}

.student-page .diary-outline-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: var(--paper);
}

.student-page .diary-outline-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

.student-page .diary-apply-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.student-page .diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
}

.student-page .diary-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.student-page .diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* PAGE HEADER AND IMPORT/EXPORT */

.student-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 29px;
}

.student-page-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.student-page-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 46px;
    width: 46px;
    height: 46px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 19px;
}

.student-page-heading h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 23px;
    font-weight: 700;
}

.student-page-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 9px;
}

.student-import-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.student-import-input {
    width: 220px;
    max-width: 100%;
    min-height: 42px;
    padding: 7px 10px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--text);
    background: var(--paper);
    font-family: inherit;
    font-size: 11px;
}

.student-import-input:focus {
    border-color: #739780;
    outline: 0;
    box-shadow: 0 0 0 3px rgba(67,131,93,.1);
}

/* ALERTS */

.student-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 18px;
    padding: 14px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    line-height: 1.8;
}

.student-alert.error {
    border-color: #eed6cf;
    color: #a84238;
    background: #fff1ed;
}

.student-alert.warning {
    border-color: #eadfbe;
    color: #765e32;
    background: #faf5e8;
}

.student-alert .alert-symbol {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    border-radius: 9px;
    color: inherit;
    background: rgba(255,255,255,.65);
}

.student-alert-content {
    flex: 1;
    min-width: 0;
}

.student-alert-content ul {
    margin: 8px 0 0;
    padding-left: 19px;
}

.student-alert .btn-close {
    flex: 0 0 auto;
    margin-left: auto;
}

/* KPI CARDS */

.student-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.student-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.025);
}

.student-kpi.blue { --kpi-color: #426b8a; }
.student-kpi.green { --kpi-color: #43835d; }
.student-kpi.orange { --kpi-color: #a87543; }
.student-kpi.red { --kpi-color: #b95b50; }

.student-kpi::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.student-kpi.blue::before { content: "ENROLMENT"; }
.student-kpi.green::before { content: "BOYS"; }
.student-kpi.orange::before { content: "GIRLS"; }
.student-kpi.red::before { content: "ACADEMICS"; }

.student-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38,59,53,.07);
}

.student-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.student-kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.student-kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.student-kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.student-kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* PANEL HEADERS */

.student-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--paper);
}

.student-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.student-panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.student-panel-title-wrap h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
}

.student-panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
}

.student-record-count i,
.student-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.student-filter-panel {
    margin-bottom: 29px;
    overflow: hidden;
}

.student-filter-body {
    padding: 23px 24px;
    background: #faf9f3;
}

.student-filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.student-filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: #527f70;
    background: #edf2e9;
    font-size: 16px;
}

.student-filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.student-filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-page .filter-label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.student-page .form-control,
.student-page .form-select {
    width: 100%;
    min-width: 0;
    min-height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background-color: var(--paper);
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
}

.student-page .form-control,
.student-page .form-select {
    padding: 10px 12px;
}

.student-page .form-control:hover,
.student-page .form-select:hover,
.student-import-input:hover {
    border-color: #bdcabb;
}

.student-page .form-control:focus,
.student-page .form-select:focus {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67,131,93,.1);
}

.student-page .input-group {
    display: flex;
    align-items: stretch;
    width: 100%;
}

.student-page .input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 13px;
    border: 1px solid #dedfd3;
    border-right: 0;
    border-radius: 9px 0 0 9px;
    color: #829083;
    background: var(--paper);
}

.student-page .input-group .form-control {
    min-width: 0;
    border-radius: 0 9px 9px 0;
}

.student-filter-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 17px;
}

/* ACTIVE FILTER BADGE */

.student-active-filter {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e7dfc9;
    border-radius: 9px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 800;
}

/* TABLE PANEL */

.student-table-panel {
    overflow: hidden;
}

.student-table-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 20px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--paper);
}

.student-list-title {
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
}

.student-list-subtitle {
    margin-top: 5px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.student-page .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.student-page .student-table {
    width: 100%;
    min-width: 1150px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--text);
    background: var(--paper);
    font-size: 11px;
}

.student-page .student-table > :not(caption) > * > * {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    vertical-align: middle;
}

.student-page .student-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.student-page .student-table tbody tr {
    background: var(--paper);
    transition: background .18s ease;
}

.student-page .student-table tbody tr:hover {
    background: #f8f8f1;
}

.student-page .student-table tbody tr:last-child td {
    border-bottom: 0;
}

.student-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #e3e1d6;
    border-radius: 9px;
    color: #68776a;
    background: #f5f4ec;
    font-size: 10px;
    font-weight: 800;
}

/* STUDENT IDENTITY */

.student-identity {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.student-page .student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    object-fit: cover;
    border: 1px solid #dce3d8;
    border-radius: 12px !important;
    color: var(--ink);
    background: #edf1e7;
    font-size: 13px;
    font-weight: 800;
}

.student-identity-details {
    min-width: 0;
}

.student-page .student-name {
    max-width: 210px;
    overflow-wrap: anywhere;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    line-height: 1.6;
}

.student-page .student-marathi-name {
    margin-top: 4px;
    color: #828c7f;
    font-size: 10px;
    line-height: 1.6;
}

.student-id-badge {
    display: inline-block;
    padding: 7px 10px;
    border: 1px solid #dce6da;
    border-radius: 7px;
    color: #426b8a;
    background: #edf3e9;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.student-academic-year {
    color: #637167;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.student-academic-year i {
    margin-right: 5px;
    color: var(--blue);
}

.student-class-badge,
.student-section-badge {
    display: inline-block;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.student-class-badge {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.student-section-badge {
    border-color: #d6e2e8;
    color: #426b8a;
    background: #edf4f8;
}

.student-roll-number {
    color: #526358;
    font-size: 11px;
    font-weight: 700;
}

.student-gender {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.student-gender.male { color: #426b8a; }
.student-gender.female { color: #b95b76; }

.student-phone {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #526358;
    font-size: 10px;
    text-decoration: none;
    white-space: nowrap;
}

.student-phone i {
    color: var(--blue);
}

.student-phone:hover {
    color: var(--green);
}

.student-muted {
    color: #929a8e;
    font-size: 11px;
}

/* STATUS */

.student-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.student-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.student-status.inactive {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.student-status .status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.student-status.active .status-dot { background: #43835d; }
.student-status.inactive .status-dot { background: #b95b50; }

/* ACTIONS */

.student-action-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.student-action-group form {
    display: inline-flex;
    margin: 0;
}

.student-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e2e1d7;
    border-radius: 9px;
    color: #657369;
    background: var(--paper);
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.student-action-btn:hover {
    transform: translateY(-2px);
}

.student-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.student-action-btn.edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.student-action-btn.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* EMPTY STATE */

.student-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.student-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 5px 13px rgba(38,59,53,.04);
    font-size: 27px;
}

.student-empty-state h5 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.student-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

.student-empty-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* PAGINATION */

.student-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
    background: var(--paper);
}

.student-pagination .pagination-info {
    color: #7b857b;
    font-size: 10px;
    line-height: 1.8;
}

.student-pagination .pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.student-pagination .pagination-links {
    min-width: 0;
    max-width: 100%;
}

.student-pagination .pagination-links nav {
    margin: 0;
}

.student-pagination .pagination-links nav > div:first-child {
    margin-bottom: 8px;
}

.student-pagination .pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.student-pagination .page-item {
    margin: 0;
}

.student-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--line);
    border-radius: 8px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
    box-shadow: none;
    text-decoration: none;
}

.student-pagination .page-link:hover {
    border-color: #b5c5b0;
    color: #43835d;
    background: #f2f4e9;
}

.student-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.student-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

.student-pagination .page-link:focus {
    box-shadow: 0 0 0 3px rgba(67,131,93,.12);
}

/* FOOTER */

.student-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.student-table-footer strong {
    color: var(--ink);
}

.student-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.student-footer-status i {
    color: #43835d;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .student-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .student-hero {
        grid-template-columns: 1fr;
    }

    .student-hero-actions {
        min-height: 220px;
    }

    .student-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }

    .student-page-header {
        align-items: flex-start;
    }

    .student-header-actions {
        justify-content: flex-start;
    }
}

@media (max-width: 650px) {
    .student-page .container-fluid {
        padding: 18px 14px !important;
    }

    .student-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .student-hero::before {
        left: 18px;
    }

    .student-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .student-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .student-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .student-hero-text h2 {
        font-size: 24px;
    }

    .student-hero-text p {
        font-size: 11px;
    }

    .student-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .student-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .student-handwritten {
        font-size: 21px;
    }

    .student-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .student-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .student-kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .student-kpi-value {
        font-size: 29px;
    }

    .student-kpi-caption {
        font-size: 9px;
    }

    .student-page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .student-header-actions {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .student-header-actions > a,
    .student-import-form,
    .student-import-form > * {
        width: 100%;
    }

    .student-import-form {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .student-import-input {
        width: 100%;
    }

    .student-panel-header,
    .student-table-summary {
        align-items: flex-start;
        padding: 16px;
    }

    .student-panel-title-wrap {
        gap: 10px;
    }

    .student-panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .student-panel-title-wrap h4,
    .student-list-title {
        font-size: 16px;
    }

    .student-panel-title-wrap p,
    .student-list-subtitle {
        font-size: 10px;
    }

    .student-record-count {
        font-size: 9px;
    }

    .student-filter-body {
        padding: 17px 15px;
    }

    .student-filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .student-filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .student-filter-heading h4 {
        font-size: 16px;
    }

    .student-filter-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .student-filter-actions > * {
        width: 100%;
    }

    .student-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .student-pagination .pagination-links {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .student-pagination .pagination {
        justify-content: flex-start;
    }

    .student-table-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .student-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .student-kpis {
        grid-template-columns: 1fr;
    }

    .student-filter-actions {
        grid-template-columns: 1fr;
    }

    .student-page .diary-primary-btn,
    .student-page .diary-secondary-btn,
    .student-page .diary-apply-btn,
    .student-page .diary-reset-btn,
    .student-page .diary-outline-btn {
        width: 100%;
    }

    .student-pagination .page-link {
        min-width: 32px;
        min-height: 32px;
        padding: 5px 8px;
        font-size: 10px;
    }
}

/* ACCESSIBILITY */

.student-page a:focus-visible,
.student-page button:focus-visible,
.student-page input:focus-visible,
.student-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .student-page *,
    .student-page *::before,
    .student-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<div class="student-page">
<div class="container-fluid py-4">

    {{-- HERO --}}
    <section class="student-hero">
        <div class="student-hero-content">
            <div class="student-hero-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div class="student-hero-text">
                <div class="student-breadcrumb">
                    <span>School Management</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Students</span>
                </div>

                <h2>Student Management</h2>

                <p>
                    Manage student registrations, academic information,
                    profiles and records from one place.
                </p>

                <div class="student-hero-note">
                    <i class="bi bi-shield-check"></i>
                    <span>Every Student. Every Achievement. Every Opportunity.</span>
                </div>
            </div>
        </div>

        <div class="student-hero-actions">
            <div class="student-hero-decoration">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>

            <div class="student-handwritten">Growing Minds. Bright Futures.</div>

            <p>
                Keep student information organised and
                academic records ready when you need them.
            </p>

            <a href="{{ route('admin.students.bulk-create') }}"
               class="diary-primary-btn">
                <i class="bi bi-person-plus-fill"></i>
                Add Student
            </a>
        </div>
    </section>

    {{-- PAGE HEADER --}}
    <section class="student-page-header">
        <div class="student-page-heading">
            <div class="student-page-heading-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h3>Students</h3>
                <p>View, import, export and manage registered students.</p>
            </div>
        </div>

        {{-- DOWNLOAD EXCEL TEMPLATE --}}
        <a href="{{ route('admin.students.bulk-template') }}"
   class="btn btn-outline-success px-3 py-2 rounded-3 fw-semibold">
            <i class="bi bi-file-earmark-excel me-2"></i>
            Download Template
        </a>

        {{-- IMPORT STUDENTS --}}
       

                <input type="file"
                       name="file"
                       class="student-import-input"
                       accept=".xlsx,.xls,.csv"
                       aria-label="Choose student import file"
                       required>

                <button type="submit" class="diary-primary-btn">
                    <i class="bi bi-upload"></i>
                    Import Students
                </button>
            </form>
        </div>
    </section>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="student-alert" role="status">
            <span class="alert-symbol">
                <i class="bi bi-check-circle-fill"></i>
            </span>
            <div class="student-alert-content">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="student-alert error" role="alert">
            <span class="alert-symbol">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </span>
            <div class="student-alert-content">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="student-alert warning" role="alert">
            <span class="alert-symbol">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </span>
            <div class="student-alert-content">
                <strong>Import validation errors</strong>
                <ul>
                    @foreach(session('import_errors') as $importError)
                        @if(is_array($importError))
                            @foreach($importError as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @else
                            <li>{{ $importError }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- STATISTICS --}}
    <section class="student-kpis">

        <article class="student-kpi blue">
            <div class="student-kpi-top">
                <span class="student-kpi-label">Total Students</span>
                <span class="student-kpi-icon">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>
            <div class="student-kpi-value">
                {{ number_format($totalStudents) }}
            </div>
            <div class="student-kpi-caption">
                Total registered student records
            </div>
        </article>

        <article class="student-kpi green">
            <div class="student-kpi-top">
                <span class="student-kpi-label">Boys</span>
                <span class="student-kpi-icon">
                    <i class="bi bi-gender-male"></i>
                </span>
            </div>
            <div class="student-kpi-value">
                {{ number_format($totalMaleStudents) }}
            </div>
            <div class="student-kpi-caption">
                Registered male students
            </div>
        </article>

        <article class="student-kpi orange">
            <div class="student-kpi-top">
                <span class="student-kpi-label">Girls</span>
                <span class="student-kpi-icon">
                    <i class="bi bi-gender-female"></i>
                </span>
            </div>
            <div class="student-kpi-value">
                {{ number_format($totalFemaleStudents) }}
            </div>
            <div class="student-kpi-caption">
                Registered female students
            </div>
        </article>

        <article class="student-kpi red">
            <div class="student-kpi-top">
                <span class="student-kpi-label">Classes</span>
                <span class="student-kpi-icon">
                    <i class="bi bi-building"></i>
                </span>
            </div>
            <div class="student-kpi-value">
                {{ count($classes) }}
            </div>
            <div class="student-kpi-caption">
                Classes available in the current list
            </div>
        </article>

    </section>

    {{-- FILTER PANEL --}}
    <section class="student-filter-panel">

        <div class="student-panel-header">
            <div class="student-panel-title-wrap">
                <div class="student-panel-title-icon">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <div>
                    <h4>Search &amp; Filter Students</h4>
                    <p>Find student records using the available filters.</p>
                </div>
            </div>

            @if(request()->hasAny([
                'search',
                'academic_year',
                'class',
                'section',
                'status'
            ]))
                <span class="student-active-filter">
                    <i class="bi bi-funnel-fill"></i>
                    Filters Applied
                </span>
            @endif
        </div>

        <div class="student-filter-body">
            <div class="student-filter-heading">
                <div class="student-filter-heading-icon">
                    <i class="bi bi-sliders"></i>
                </div>
                <div>
                    <h4>Find Student Records</h4>
                    <p>
                        Search by student information, academic year,
                        class, section or status.
                    </p>
                </div>
            </div>

            <form id="studentFilterForm"
                  method="GET"
                  action="{{ route('admin.students.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-6">
                        <label for="student-search" class="filter-label">
                            Search Student
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input id="student-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Student ID, name, phone or email">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label for="student-academic-year" class="filter-label">
                            Academic Year
                        </label>
                        <select id="student-academic-year"
                                name="academic_year"
                                class="form-select">
                            <option value="">All Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label for="student-class" class="filter-label">
                            Class
                        </label>
                        <select id="student-class"
                                name="class"
                                class="form-select">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}"
                                    @selected(request('class') == $class)>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label for="student-section" class="filter-label">
                            Section
                        </label>
                        <select id="student-section"
                                name="section"
                                class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}"
                                    @selected(request('section') == $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label for="student-status" class="filter-label">
                            Status
                        </label>
                        <select id="student-status"
                                name="status"
                                class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <div class="student-filter-actions">
                    <a href="{{ route('admin.students.index') }}"
                       class="diary-reset-btn">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset Filters
                    </a>

                    <button type="submit" class="diary-apply-btn">
                        <i class="bi bi-search"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- STUDENT TABLE --}}
    <section class="student-paper-panel student-table-panel">

        <div class="student-table-summary">
            <div class="student-panel-title-wrap">
                <div class="student-panel-title-icon">
                    <i class="bi bi-card-list"></i>
                </div>
                <div>
                    <div class="student-list-title">Student List</div>
                    <div class="student-list-subtitle">
                        @if($students->total() > 0)
                            Showing {{ $students->firstItem() }}
                            to {{ $students->lastItem() }}
                            of {{ number_format($students->total()) }} students
                        @else
                            No students found
                        @endif
                    </div>
                </div>
            </div>

            <div class="student-record-count">
                <i class="bi bi-folder2-open"></i>
                <strong>{{ number_format($students->total()) }}</strong>
                Records
            </div>
        </div>

        <div class="table-responsive">
            <table class="table student-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll No.</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)

                        @php
                            $fullName = trim(
                                ($student->first_name ?? '') . ' ' .
                                ($student->middle_name ?? '') . ' ' .
                                ($student->last_name ?? '')
                            );

                            $initial = strtoupper(
                                substr($student->first_name ?? 'S', 0, 1)
                            );

                            $avatarUrl = $student->profile_image;
                            $studentStatus = strtolower($student->status ?? '');
                        @endphp

                        <tr>
                            <td>
                                <span class="student-row-number">
                                    {{ ($students->firstItem() ?? 1) + $loop->index }}
                                </span>
                            </td>

                            <td>
                                <div class="student-identity">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}"
                                             alt="{{ $fullName ?: 'Student' }}"
                                             class="student-avatar"
                                             loading="lazy"
                                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Student&background=edf1e7&color=263b35&size=100';">
                                    @else
                                        <div class="student-avatar">
                                            {{ $initial ?: 'S' }}
                                        </div>
                                    @endif

                                    <div class="student-identity-details">
                                        <div class="student-name">
                                            {{ $fullName ?: 'Unnamed Student' }}
                                        </div>

                                        @if($student->marathi_name)
                                            <div class="student-marathi-name">
                                                {{ $student->marathi_name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="student-id-badge">
                                    {{ $student->student_id ?? '-' }}
                                </span>
                            </td>

                            <td>
                                @if($student->academic_year)
                                    <span class="student-academic-year">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $student->academic_year }}
                                    </span>
                                @else
                                    <span class="student-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($student->class)
                                    <span class="student-class-badge">
                                        {{ $student->class }}
                                    </span>
                                @else
                                    <span class="student-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($student->section)
                                    <span class="student-section-badge">
                                        {{ $student->section }}
                                    </span>
                                @else
                                    <span class="student-muted">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="student-roll-number">
                                    {{ $student->roll_number ?? '—' }}
                                </span>
                            </td>

                            <td>
                                @if(strtolower($student->gender ?? '') === 'male')
                                    <span class="student-gender male">
                                        <i class="bi bi-gender-male"></i>
                                        Male
                                    </span>
                                @elseif(strtolower($student->gender ?? '') === 'female')
                                    <span class="student-gender female">
                                        <i class="bi bi-gender-female"></i>
                                        Female
                                    </span>
                                @else
                                    <span class="student-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($student->phone)
                                    <a href="tel:{{ $student->phone }}"
                                       class="student-phone">
                                        <i class="bi bi-telephone"></i>
                                        {{ $student->phone }}
                                    </a>
                                @else
                                    <span class="student-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($studentStatus === 'active')
                                    <span class="student-status active">
                                        <span class="status-dot"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="student-status inactive">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($student->status ?? 'Inactive') }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="student-action-group">
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="student-action-btn view"
                                       title="View Student"
                                       aria-label="View student">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="student-action-btn edit"
                                       title="Edit Student"
                                       aria-label="Edit student">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.students.destroy', $student) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="student-action-btn delete"
                                                title="Delete Student"
                                                aria-label="Delete student">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11">
                                <div class="student-empty-state">
                                    <div class="student-empty-icon">
                                        <i class="bi bi-person-x"></i>
                                    </div>

                                    <h5>No Students Found</h5>

                                    <p>
                                        No student records match your current filters.
                                        Try adjusting your search or clearing the filters.
                                    </p>

                                    <div class="student-empty-actions">
                                        <a href="{{ route('admin.students.index') }}"
                                           class="diary-outline-btn">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Reset Filters
                                        </a>

                                        <a href="{{ route('admin.students.create') }}"
                                           class="diary-primary-btn">
                                            <i class="bi bi-person-plus-fill"></i>
                                            Add Student
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($students->hasPages())
            <div class="student-pagination">
                <div class="pagination-info">
                    Showing
                    <strong>{{ $students->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $students->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ number_format($students->total()) }}</strong>
                    students
                </div>

                <div class="pagination-links">
                    {{ $students
                        ->onEachSide(1)
                        ->withQueryString()
                        ->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

        {{-- FOOTER --}}
        <div class="student-table-footer">
            <div>
                Showing <strong>{{ $students->count() }}</strong>
                record(s) on this page
            </div>

            <div class="student-footer-status">
                <i class="bi bi-check-circle-fill"></i>
                Student records loaded
            </div>
        </div>

    </section>

</div>
</div>

@endsection

