@extends('layouts.app')

@section('title', 'Subjects | Admin')
@section('page-title', 'Subjects')

@section('content')

<style>
/* ==========================================================
   SUBJECTS — LOCKED SCHOOL DIARY DESIGN SYSTEM
   Reference: Transport Records
   Warm paper · Forest green · Cream cards · Earthy accents
========================================================== */

.subject-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --line-dark: #d8d2c3;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    padding: 26px 28px 40px;
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.subject-page *,
.subject-page *::before,
.subject-page *::after {
    box-sizing: border-box;
}

.subject-page a,
.subject-page button,
.subject-page select {
    -webkit-tap-highlight-color: transparent;
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.subject-page a {
    text-decoration: none;
}

.subject-page button,
.subject-page input,
.subject-page select {
    font: inherit;
}

.subject-page button {
    cursor: pointer;
}

.subject-page .diary-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.subject-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

/* ==========================================================
   HERO — MATCH TRANSPORT RECORDS
========================================================== */

.subject-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

.subject-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.subject-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.subject-hero-icon-wrap {
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

.subject-hero-copy {
    min-width: 0;
}

.subject-hero-breadcrumb {
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

.subject-hero-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.subject-hero-content h1 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.subject-hero-content p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.subject-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.subject-hero-note i {
    font-size: 13px;
}

.subject-hero-actions {
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

.subject-hero-actions::before,
.subject-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.subject-hero-actions::before {
    top: -115px;
    right: -60px;
}

.subject-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.subject-hero-decoration {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.subject-hero-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.subject-hero-description {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.subject-page .hero-add-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid #e8e0c9;
    border-radius: 9px;
    color: var(--ink);
    background: #f1ead8;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.subject-page .hero-add-btn:hover {
    transform: translateY(-2px);
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
    box-shadow: 0 6px 15px rgba(0, 0, 0, .12);
}

/* ==========================================================
   SUCCESS MESSAGE
========================================================== */

.subject-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.7;
}

.subject-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    color: #347849;
    background: #deeedb;
}

/* ==========================================================
   SUMMARY CARDS — TRANSPORT KPI STYLE
========================================================== */

.subject-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.summary-card {
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
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
    transition: transform .2s ease, box-shadow .2s ease;
}

.summary-card.blue { --kpi-color: #426b8a; }
.summary-card.green { --kpi-color: #43835d; }
.summary-card.orange { --kpi-color: #a87543; }
.summary-card.cyan { --kpi-color: #527f70; }

.summary-card::before {
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

.summary-card.blue::before { content: "CURRICULUM"; }
.summary-card.green::before { content: "ACTIVE"; }
.summary-card.orange::before { content: "INACTIVE"; }
.summary-card.cyan::before { content: "CLASS COVERAGE"; }

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.summary-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.6;
}

.summary-number {
    margin-top: 12px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -.5px;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.summary-icon {
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

.summary-footer {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* ==========================================================
   SHARED SECTION HEADINGS AND COUNTS
========================================================== */

.subject-section-heading,
.directory-title,
.filter-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.subject-section-heading-icon,
.directory-title-icon,
.filter-heading-icon {
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

.directory-title-icon {
    color: var(--amber);
}

.subject-section-heading h2,
.directory-title h3,
.filter-heading h2 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.5;
    letter-spacing: -.25px;
}

.subject-section-heading p,
.directory-title p,
.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.subject-count,
.directory-count {
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
    white-space: nowrap;
}

.subject-count i,
.directory-count i {
    color: var(--ink);
}

/* ==========================================================
   FILTER PANEL
========================================================== */

.subject-filter-section {
    overflow: hidden;
    margin-bottom: 29px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.subject-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--paper);
}

.subject-filter-header .filter-heading-icon {
    color: var(--sage);
    background: #edf2e9;
}

.filter-content {
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.subject-filter-form {
    display: grid;
    grid-template-columns: minmax(220px, 1fr) auto auto;
    align-items: end;
    gap: 12px;
}

.filter-group {
    min-width: 0;
}

.filter-label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.class-select {
    width: 100%;
    min-width: 0;
    height: 44px;
    padding: 0 12px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    cursor: pointer;
}

.class-select:hover {
    border-color: #bdcabb;
}

.class-select:focus {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.filter-btn,
.clear-filter-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.filter-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.filter-btn:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.clear-filter-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.clear-filter-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* ==========================================================
   CLASS DIRECTORY
========================================================== */

.directory-section {
    overflow: hidden;
    margin-bottom: 29px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.directory-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--paper);
}

.class-groups {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
    padding: 22px;
    background: #faf9f3;
}

/* ==========================================================
   CLASS CARDS — PAPER CARD STYLE
========================================================== */

.class-group-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
    transition: transform .2s ease, border-color .2s ease,
        box-shadow .2s ease;
}

.class-group-card:hover {
    transform: translateY(-3px);
    border-color: #c9d0c3;
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.class-card-accent {
    height: 4px;
    background: linear-gradient(90deg, #344f43, #527f70);
}

.class-group-body {
    flex: 1;
    padding: 19px;
}

.class-group-main {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.class-number-box {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 53px;
    width: 53px;
    height: 53px;
    border: 1px solid #e3e1d6;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
}

.class-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1px solid #e2ddcf;
    border-radius: 9px;
    color: var(--ink);
    background: var(--paper);
    font-size: 15px;
}

.class-group-info {
    flex: 1;
    min-width: 0;
}

.class-group-info h3 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 16px;
    font-weight: 700;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.class-group-info h3 .class-divider {
    color: var(--amber);
    font-weight: 500;
}

.class-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
}

.class-section-tag,
.class-year-tag {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 4px 8px;
    border: 1px solid var(--line);
    border-radius: 7px;
    font-size: 9px;
    font-weight: 750;
}

.class-section-tag {
    border-color: #d5e5d1;
    color: #347849;
    background: #edf6e9;
}

.class-year-tag {
    color: #765e32;
    border-color: #e7dfc9;
    background: #faf5e8;
}

.class-subject-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 19px;
    padding: 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    background: #f8f7f0;
}

.class-subject-summary-label {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.class-subject-summary-count {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
    text-align: right;
}

.class-group-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 13px 17px;
    border-top: 1px solid #eae6da;
    background: #f8f7f0;
}

.class-subject-label {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.7;
}

.class-subject-label strong {
    color: var(--ink);
}

.class-view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 35px;
    padding: 0 12px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-size: 10px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.class-view-btn:hover {
    transform: translateY(-1px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .1);
}

.class-view-arrow {
    font-size: 14px;
    transition: transform .18s ease;
}

.class-view-btn:hover .class-view-arrow {
    transform: translateX(3px);
}

/* ==========================================================
   BACK TO CLASSES
========================================================== */

.subject-back-row {
    margin-bottom: 15px;
}

.back-to-classes {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    min-height: 38px;
    padding: 0 12px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: #fffefa;
    font-size: 10px;
    font-weight: 800;
}

.back-to-classes:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

.back-arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    border: 1px solid var(--line);
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 12px;
}

/* ==========================================================
   SUBJECT TABLE PANEL
========================================================== */

.subject-table-section {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

.subject-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--paper);
}

.subject-table-header .subject-section-heading-icon {
    color: var(--ink);
    background: #f1f2e8;
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.subject-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.subject-table thead th {
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

.subject-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.subject-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.subject-table tbody tr:hover {
    background: #f8f8f1;
}

.subject-table tbody tr:last-child td {
    border-bottom: none;
}

/* SUBJECT IDENTITY */

.row-number {
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

.subject-name-wrapper {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.subject-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #dce3d8;
    border-radius: 11px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 14px;
}

.subject-name {
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.subject-code {
    display: inline-flex;
    align-items: center;
    padding: 7px 9px;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.class-badge {
    display: inline-flex;
    align-items: center;
    padding: 7px 10px;
    border: 1px solid #dce6da;
    border-radius: 7px;
    color: #405e4b;
    background: #edf3e9;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 29px;
    padding: 0 8px;
    border: 1px solid #ebdfcc;
    border-radius: 8px;
    color: #a87543;
    background: #faf2e7;
    font-size: 10px;
    font-weight: 800;
}

.academic-year {
    color: #68776a;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

/* STATUS */

.status-badge {
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

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.status-active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.status-active .status-dot {
    background: #43835d;
}

.status-inactive {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.status-inactive .status-dot {
    background: #b95b50;
}

/* ACTION BUTTONS */

.action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.action-buttons form {
    display: inline-flex;
    margin: 0;
    padding: 0;
}

.action-btn {
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
    background: #fffefa;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.action-edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.action-delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* ==========================================================
   EMPTY STATE
========================================================== */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon-wrapper {
    position: relative;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
}

.empty-icon-bg {
    position: absolute;
    inset: 0;
    border: 1px solid #e3decc;
    border-radius: 19px;
    background: var(--chalk);
    transform: rotate(4deg);
}

.empty-state-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    border: 1px solid #e3decc;
    border-radius: 16px;
    color: var(--ink);
    background: var(--paper);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 22px;
    font-weight: 700;
}

.empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* ==========================================================
   ACCESSIBILITY
========================================================== */

.subject-page a:focus-visible,
.subject-page button:focus-visible,
.subject-page select:focus-visible,
.subject-page input:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .subject-page *,
    .subject-page *::before,
    .subject-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

/* ==========================================================
   TABLET
========================================================== */

@media (max-width: 1200px) {
    .subject-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .class-groups {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .subject-filter-form {
        grid-template-columns: minmax(0, 1fr) auto;
    }

    .filter-group {
        grid-column: 1 / -1;
    }
}

@media (max-width: 900px) {
    .subject-page {
        padding: 20px;
    }

    .subject-hero {
        grid-template-columns: 1fr;
    }

    .subject-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }

    .subject-hero-actions {
        min-height: 220px;
    }

    .subject-filter-form {
        grid-template-columns: minmax(0, 1fr) auto;
    }
}

/* ==========================================================
   MOBILE
========================================================== */

@media (max-width: 650px) {
    .subject-page {
        padding: 18px 14px 28px;
    }

    .subject-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .subject-hero::before {
        left: 18px;
    }

    .subject-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .subject-hero-icon-wrap {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .subject-hero-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .subject-hero-content h1 {
        font-size: 24px;
    }

    .subject-hero-content p {
        font-size: 11px;
    }

    .subject-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .subject-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .subject-hero-handwritten {
        font-size: 21px;
    }

    .subject-page .hero-add-btn {
        width: 100%;
    }

    .subject-summary-grid {
        gap: 15px;
        margin-top: 31px;
    }

    .summary-card {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .summary-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .summary-number {
        font-size: 29px;
    }

    .summary-footer {
        font-size: 9px;
    }

    .subject-filter-header,
    .directory-header,
    .subject-table-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 13px;
        padding: 16px;
    }

    .filter-content {
        padding: 17px 15px;
    }

    .subject-filter-form {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .filter-group {
        grid-column: auto;
    }

    .filter-btn,
    .clear-filter-btn {
        width: 100%;
    }

    .class-groups {
        grid-template-columns: 1fr;
        gap: 14px;
        padding: 15px;
    }

    .class-group-body {
        padding: 16px;
    }

    .class-group-footer {
        align-items: stretch;
        flex-direction: column;
        padding: 13px 15px;
    }

    .class-view-btn {
        width: 100%;
        min-height: 38px;
    }

    .subject-count,
    .directory-count {
        align-self: flex-start;
    }

    .subject-section-heading,
    .directory-title,
    .filter-heading {
        align-items: flex-start;
    }

    .subject-section-heading-icon,
    .directory-title-icon,
    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .subject-section-heading h2,
    .directory-title h3,
    .filter-heading h2 {
        font-size: 16px;
    }

    .empty-state {
        padding: 48px 17px;
    }

    .subject-table {
        min-width: 1000px;
    }
}

@media (max-width: 400px) {
    .subject-page {
        padding: 18px 12px 25px;
    }

    .subject-summary-grid {
        grid-template-columns: 1fr;
    }

    .subject-table-header {
        gap: 12px;
    }

    .subject-count {
        margin-left: 46px;
    }

    .class-group-body {
        padding: 13px;
    }

    .class-number-box {
        flex-basis: 46px;
        width: 46px;
        height: 46px;
    }

    .class-icon {
        width: 30px;
        height: 30px;
        font-size: 13px;
    }

    .class-group-info h3 {
        font-size: 14px;
    }

    .subject-alert {
        align-items: flex-start;
    }
}
</style>

<div class="subject-page">

    {{-- HERO — MATCH TRANSPORT RECORDS --}}
    <section class="subject-hero">
        <div class="subject-hero-content">

            <div class="subject-hero-icon-wrap">
                <i class="fas fa-book-open"></i>
            </div>

            <div class="subject-hero-copy">
                <div class="subject-hero-breadcrumb">
                    <span>Academic Management</span>
                    <i class="fas fa-chevron-right"></i>
                    <span>Subjects</span>
                </div>

                <h1>Subjects</h1>

                <p>
                    Manage academic subjects class-wise and keep your
                    curriculum organised from one place.
                </p>

                <div class="subject-hero-note">
                    <i class="fas fa-shield-alt"></i>
                    <span>Academic Planning &amp; Curriculum Management</span>
                </div>
            </div>
        </div>

        <div class="subject-hero-actions">
            <div class="subject-hero-decoration">
                <i class="fas fa-book-reader"></i>
            </div>

            <div class="subject-hero-handwritten">
                Learning Starts Here.
            </div>

            <p class="subject-hero-description">
                Keep every class organised with clear subject assignments
                and a well-managed academic curriculum.
            </p>

            <a href="{{ route('admin.subjects.create') }}"
               class="hero-add-btn">
                <i class="fas fa-plus"></i>
                Add Subject
            </a>
        </div>
    </section>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="subject-alert" role="status">
            <span class="subject-alert-icon">
                <i class="fas fa-check"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <section class="subject-summary-grid">

        <article class="summary-card blue">
            <div class="summary-top">
                <div>
                    <div class="summary-label">Total Subjects</div>
                    <div class="summary-number">
                        {{ number_format($subjects->count()) }}
                    </div>
                </div>
                <div class="summary-icon">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
            <div class="summary-footer">All academic subjects</div>
        </article>

        <article class="summary-card green">
            <div class="summary-top">
                <div>
                    <div class="summary-label">Active Subjects</div>
                    <div class="summary-number">
                        {{ number_format($subjects->where('status', true)->count()) }}
                    </div>
                </div>
                <div class="summary-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="summary-footer">Currently active subjects</div>
        </article>

        <article class="summary-card orange">
            <div class="summary-top">
                <div>
                    <div class="summary-label">Inactive Subjects</div>
                    <div class="summary-number">
                        {{ number_format($subjects->where('status', false)->count()) }}
                    </div>
                </div>
                <div class="summary-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
            </div>
            <div class="summary-footer">Inactive academic subjects</div>
        </article>

        <article class="summary-card cyan">
            <div class="summary-top">
                <div>
                    <div class="summary-label">Assigned Classes</div>
                    <div class="summary-number">
                        {{ number_format($subjects->pluck('class_id')->unique()->count()) }}
                    </div>
                </div>
                <div class="summary-icon">
                    <i class="fas fa-school"></i>
                </div>
            </div>
            <div class="summary-footer">Classes with subjects assigned</div>
        </article>

    </section>

    {{-- CLASS FILTER --}}
    <section class="subject-filter-section">

        <div class="subject-filter-header">
            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h2>Find Subjects</h2>
                    <p>Select a class to view its assigned subjects.</p>
                </div>
            </div>
        </div>

        <div class="filter-content">
            <form action="{{ route('admin.subjects.index') }}"
                  method="GET"
                  class="subject-filter-form">

                <div class="filter-group">
                    <label for="class_id" class="filter-label">
                        Select Class
                    </label>

                    <select name="class_id"
                            id="class_id"
                            class="class-select">

                        <option value="">All Classes</option>

                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                @selected(request('class_id') == $class->id)>
                                Class {{ $class->class_name }}
                                - Section {{ $class->section }}
                                ({{ $class->academic_year }})
                            </option>
                        @endforeach

                    </select>
                </div>

                <button type="submit" class="filter-btn">
                    <i class="fas fa-search"></i>
                    Show Subjects
                </button>

                @if(request()->filled('class_id'))
                    <a href="{{ route('admin.subjects.index') }}"
                       class="clear-filter-btn">
                        <i class="fas fa-rotate-left"></i>
                        Clear Filter
                    </a>
                @endif

            </form>
        </div>
    </section>

    {{-- ALL CLASSES: CLASS DIRECTORY --}}
    @if(!request()->filled('class_id'))

        @if($subjects->count())

            @php
                $groupedSubjects = $subjects->groupBy('class_id');
            @endphp

            <section class="directory-section">

                <div class="directory-header">
                    <div class="directory-title">
                        <div class="directory-title-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <h3>Class Directory</h3>
                            <p>
                                Select a class to view its assigned subjects.
                            </p>
                        </div>
                    </div>

                    <span class="directory-count">
                        <i class="fas fa-folder-open"></i>
                        <strong>{{ number_format($groupedSubjects->count()) }}</strong>
                        {{ $groupedSubjects->count() == 1 ? 'Class' : 'Classes' }}
                    </span>
                </div>

                <div class="class-groups">

                    @foreach($groupedSubjects as $classId => $classSubjects)

                        @php
                            $schoolClass = $classes->firstWhere('id', $classId);
                        @endphp

                        @if($schoolClass)

                            <article class="class-group-card">

                                <div class="class-card-accent"></div>

                                <div class="class-group-body">

                                    <div class="class-group-main">

                                        <div class="class-number-box">
                                            <div class="class-icon">
                                                <i class="fas fa-school"></i>
                                            </div>
                                        </div>

                                        <div class="class-group-info">
                                            <h3>
                                                Class {{ $schoolClass->class_name }}
                                                <span class="class-divider">·</span>
                                                Section {{ $schoolClass->section }}
                                            </h3>

                                            <div class="class-meta">
                                                <span class="class-section-tag">
                                                    Section {{ $schoolClass->section }}
                                                </span>

                                                <span class="class-year-tag">
                                                    {{ $schoolClass->academic_year }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="class-subject-summary">
                                        <span class="class-subject-summary-label">
                                            <i class="fas fa-book-open"></i>
                                            Subjects assigned
                                        </span>

                                        <span class="class-subject-summary-count">
                                            {{ number_format($classSubjects->count()) }}
                                            {{ $classSubjects->count() == 1 ? 'Subject' : 'Subjects' }}
                                        </span>
                                    </div>

                                </div>

                                <div class="class-group-footer">
                                    <span class="class-subject-label">
                                        <strong>{{ $classSubjects->count() }}</strong>
                                        {{ $classSubjects->count() == 1 ? 'subject assigned' : 'subjects assigned' }}
                                    </span>

                                    <a href="{{ route('admin.subjects.index', ['class_id' => $schoolClass->id]) }}"
                                       class="class-view-btn">
                                        View Subjects
                                        <span class="class-view-arrow">→</span>
                                    </a>
                                </div>

                            </article>

                        @endif

                    @endforeach

                </div>
            </section>

        @else

            {{-- NO SUBJECTS --}}
            <section class="subject-table-section">
                <div class="empty-state">

                    <div class="empty-icon-wrapper">
                        <div class="empty-icon-bg"></div>
                        <div class="empty-state-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>

                    <h4>No Subjects Found</h4>

                    <p>
                        There are currently no academic subjects.
                        Add your first subject to start organising the curriculum.
                    </p>

                    <a href="{{ route('admin.subjects.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-plus"></i>
                        Add Subject
                    </a>

                </div>
            </section>

        @endif

    {{-- SELECTED CLASS: SUBJECT TABLE --}}
    @else

        @php
            $selectedClass = $classes->firstWhere('id', request('class_id'));
        @endphp

        <div class="subject-back-row">
            <a href="{{ route('admin.subjects.index') }}"
               class="back-to-classes">
                <span class="back-arrow">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Back to All Classes</span>
            </a>
        </div>

        <section class="subject-table-section">

            <div class="subject-table-header">

                <div class="subject-section-heading">
                    <div class="subject-section-heading-icon">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div>
                        <h2>
                            @if($selectedClass)
                                Class {{ $selectedClass->class_name }}
                                · Section {{ $selectedClass->section }}
                            @else
                                Subjects
                            @endif
                        </h2>

                        <p>
                            View and manage subjects assigned to this class.
                        </p>
                    </div>
                </div>

                <div class="subject-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($subjects->count()) }}</strong>
                    {{ $subjects->count() == 1 ? 'Subject' : 'Subjects' }}
                </div>

            </div>

            @if($subjects->count())

                <div class="table-wrapper">
                    <table class="subject-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Subject Code</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($subjects as $subject)

                                <tr>
                                    <td>
                                        <span class="row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="subject-name-wrapper">
                                            <span class="subject-icon">
                                                <i class="fas fa-book-open"></i>
                                            </span>

                                            <span class="subject-name">
                                                {{ $subject->subject_name }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="subject-code">
                                            {{ $subject->subject_code ?: '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($subject->schoolClass)
                                            <span class="class-badge">
                                                Class {{ $subject->schoolClass->class_name }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($subject->schoolClass)
                                            <span class="section-badge">
                                                {{ $subject->schoolClass->section }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($subject->schoolClass)
                                            <span class="academic-year">
                                                {{ $subject->schoolClass->academic_year }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($subject->status)
                                            <span class="status-badge status-active">
                                                <span class="status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <span class="status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="action-buttons">

                                            <a href="{{ route('admin.subjects.show', $subject->id) }}"
                                               class="action-btn action-view"
                                               title="View Subject"
                                               aria-label="View Subject">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                               class="action-btn action-edit"
                                               title="Edit Subject"
                                               aria-label="Edit Subject">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('admin.subjects.destroy', $subject->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this subject?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="action-btn action-delete"
                                                        title="Delete Subject"
                                                        aria-label="Delete Subject">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="transport-card-footer"
                     style="display:flex;align-items:center;justify-content:space-between;gap:15px;min-height:55px;padding:13px 22px;border-top:1px dashed #ddd7c9;color:#788277;background:#f8f7f0;font-size:10px;">

                    <div>
                        Showing <strong style="color:#263b35;">{{ $subjects->count() }}</strong>
                        subject(s) for this class
                    </div>

                    <div style="display:inline-flex;align-items:center;gap:7px;color:#68776a;font-weight:700;">
                        <i class="fas fa-circle-check" style="color:#43835d;"></i>
                        Data loaded successfully
                    </div>

                </div>

            @else

                {{-- NO SUBJECTS FOR SELECTED CLASS --}}
                <div class="empty-state">

                    <div class="empty-icon-wrapper">
                        <div class="empty-icon-bg"></div>
                        <div class="empty-state-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>

                    <h4>No Subjects Found</h4>

                    <p>
                        No subjects have been assigned to this class yet.
                        Add a subject to build this class's curriculum.
                    </p>

                    <a href="{{ route('admin.subjects.create') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-plus"></i>
                        Add Subject
                    </a>

                </div>

            @endif

        </section>

    @endif

</div>

@endsection
