@extends('layouts.app')

@section('title', 'Scholarship | Admin')
@section('page-title', 'Scholarship')

@section('content')

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Scholarship / Merit Concession Dashboard
   Warm paper • Forest ink • Cream CTA • Earthy accents
========================================================= */

.scholarship-page {
    --ink: #263b35;
    --ink-2: #1d302b;
    --forest: #344f43;
    --forest-light: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --cream: #f1ead8;
    --wood: #a87543;
    --red: #bd5148;
    --blue: #2b5d8a;
    --amber: #b98024;
    --green: #3a7958;
    --teal: #527f79;

    max-width: 1500px;
    margin: 0 auto;
    padding: 0 0 24px;
    color: var(--text);
    font-family: 'Public Sans', 'Inter', sans-serif;
}

.scholarship-page *,
.scholarship-page *::before,
.scholarship-page *::after {
    box-sizing: border-box;
}

.scholarship-page a,
.scholarship-page button {
    -webkit-tap-highlight-color: transparent;
}

.scholarship-page a {
    transition:
        color .18s ease,
        background .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.scholarship-page .paper {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
}

/* ---------- School Diary hero ---------- */

.scholarship-page .scholarship-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(280px, .8fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.scholarship-page .scholarship-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 32px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.scholarship-page .scholarship-hero-content {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
    padding: 30px 30px 30px 54px;
}

.scholarship-page .scholarship-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    flex: 0 0 56px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--cream);
    font-size: 23px;
}

.scholarship-page .scholarship-hero-text {
    min-width: 0;
}

.scholarship-page .hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.3px;
    text-transform: uppercase;
}

.scholarship-page .scholarship-hero-text h2 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.scholarship-page .scholarship-hero-text p {
    max-width: 620px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.scholarship-page .hero-mini-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 800;
}

.scholarship-page .hero-mini-info i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 23px;
    height: 23px;
    border-radius: 7px;
    color: var(--green);
    background: #eaf2e8;
}

/* Hero right panel */

.scholarship-page .hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 190px;
    padding: 25px;
    color: #e9efe2;
    text-align: center;
    background:
        radial-gradient(
            ellipse at 15% 0,
            rgba(255, 255, 255, .10),
            transparent 58%
        ),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
}

.scholarship-page .hero-actions::after {
    content: "";
    position: absolute;
    right: 18px;
    bottom: 18px;
    width: 54px;
    height: 54px;
    border-right: 1px solid rgba(241, 234, 216, .18);
    border-bottom: 1px solid rgba(241, 234, 216, .18);
    pointer-events: none;
}

.scholarship-page .hero-actions-label {
    color: var(--cream);
    font-family: var(--hand, cursive);
    font-size: 25px;
    line-height: 1.2;
}

.scholarship-page .hero-actions-subtitle {
    max-width: 265px;
    color: #c5d0bd;
    font-size: 11px;
    line-height: 1.7;
}

.scholarship-page .scholarship-rules-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 10px 17px;
    border: 1px solid rgba(241, 234, 216, .7);
    border-radius: 8px;
    color: var(--ink);
    background: var(--cream);
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .10);
}

.scholarship-page .scholarship-rules-btn:hover {
    color: var(--ink);
    border-color: #fffefa;
    background: #fffefa;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, .16);
}

/* ---------- Success message ---------- */

.scholarship-page .success-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #c7e2ce;
    border-radius: 10px;
    color: #347849;
    background: #f0f7ed;
    font-size: 12px;
    font-weight: 700;
}

.scholarship-page .success-alert i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex: 0 0 28px;
    border-radius: 50%;
    color: #347849;
    background: #deedd9;
}

/* ---------- KPI cards / folder tabs ---------- */

.scholarship-page .scholarship-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
    padding-top: 10px;
}

.scholarship-page .scholarship-kpi {
    --c: var(--blue);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 13px;
    min-width: 0;
    padding: 21px 21px 16px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--c);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 4px 14px rgba(29, 58, 51, .045);
    transition: transform .22s ease, box-shadow .22s ease;
}

.scholarship-page .scholarship-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(29, 58, 51, .10);
}

.scholarship-page .scholarship-kpi::before {
    position: absolute;
    top: -11px;
    left: 15px;
    padding: 3px 11px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--c);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
}

.scholarship-page .scholarship-kpi.blue { --c: #2b5d8a; }
.scholarship-page .scholarship-kpi.green { --c: #3a7958; }
.scholarship-page .scholarship-kpi.orange { --c: #b98024; }
.scholarship-page .scholarship-kpi.red { --c: #bd5148; }

.scholarship-page .scholarship-kpi.blue::before { content: "RESULTS"; }
.scholarship-page .scholarship-kpi.green::before { content: "MERIT"; }
.scholarship-page .scholarship-kpi.orange::before { content: "AWARDED"; }
.scholarship-page .scholarship-kpi.red::before { content: "REVIEW"; }

.scholarship-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.scholarship-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.scholarship-page .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    flex: 0 0 44px;
    border: 1px solid color-mix(in srgb, var(--c) 16%, white);
    border-radius: 11px;
    color: var(--c);
    background: color-mix(in srgb, var(--c) 10%, white);
    font-size: 18px;
}

.scholarship-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.scholarship-page .kpi-caption {
    padding-top: 12px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

/* ---------- Shared section headings ---------- */

.scholarship-page .filter-panel,
.scholarship-page .table-panel {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 4px 15px rgba(29, 58, 51, .04);
}

.scholarship-page .filter-panel {
    margin-bottom: 23px;
    padding: 21px;
}

.scholarship-page .filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 19px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #d8d1bf;
}

.scholarship-page .filter-title-wrap,
.scholarship-page .table-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.scholarship-page .filter-title-icon,
.scholarship-page .table-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    height: 41px;
    flex: 0 0 41px;
    border: 1px solid #e0e4d8;
    border-radius: 10px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.scholarship-page .filter-title,
.scholarship-page .table-panel-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.scholarship-page .filter-subtitle,
.scholarship-page .table-panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

.scholarship-page .filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: #68766d;
    background: #faf9f4;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

/* ---------- Search / filters ---------- */

.scholarship-page .filter-grid {
    display: grid;
    grid-template-columns: minmax(190px, 1.7fr) repeat(4, minmax(110px, 1fr)) auto;
    gap: 12px;
    align-items: end;
}

.scholarship-page .filter-group {
    min-width: 0;
}

.scholarship-page .filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 10px;
    font-weight: 800;
}

.scholarship-page .filter-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 42px;
    padding: 0 11px;
    border: 1px solid #dcded3;
    border-radius: 8px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.scholarship-page .filter-control:hover {
    border-color: #bfcbbd;
}

.scholarship-page .filter-control:focus {
    border-color: #6e9a86;
    box-shadow: 0 0 0 3px rgba(58, 121, 88, .12);
}

.scholarship-page .filter-search {
    position: relative;
}

.scholarship-page .filter-search > i {
    position: absolute;
    top: 15px;
    left: 12px;
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.scholarship-page .filter-search input {
    padding-left: 34px;
}

.scholarship-page .filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ---------- Locked button styles ---------- */

.scholarship-page .apply-btn,
.scholarship-page .reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 0 14px;
    border: 1px solid transparent;
    border-radius: 8px;
    text-decoration: none;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
    cursor: pointer;
    transition:
        color .18s ease,
        background .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.scholarship-page .apply-btn {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
    box-shadow: 0 3px 8px rgba(38, 59, 53, .10);
}

.scholarship-page .apply-btn:hover {
    border-color: var(--forest-light);
    color: #fffefa;
    background: var(--forest-light);
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(38, 59, 53, .13);
}

.scholarship-page .reset-btn {
    border-color: #d7d6ca;
    color: var(--ink);
    background: var(--paper);
}

.scholarship-page .reset-btn:hover {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
    transform: translateY(-1px);
}

.scholarship-page .apply-btn:focus-visible,
.scholarship-page .reset-btn:focus-visible,
.scholarship-page .scholarship-rules-btn:focus-visible,
.scholarship-page .action-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .35);
    outline-offset: 3px;
}

/* ---------- Results table panel ---------- */

.scholarship-page .table-panel {
    overflow: hidden;
}

.scholarship-page .table-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.scholarship-page .table-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 24px;
    margin-left: 7px;
    padding: 0 8px;
    border: 1px solid #e1dfd2;
    border-radius: 20px;
    color: var(--ink);
    background: var(--chalk);
    font-family: 'Public Sans', 'Inter', sans-serif;
    font-size: 10px;
    font-weight: 800;
    vertical-align: middle;
}

.scholarship-page .table-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.scholarship-page .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.scholarship-page .scholarship-table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
}

.scholarship-page .scholarship-table th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    background: #f5f3e9;
    color: #727a70;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.scholarship-page .scholarship-table td {
    padding: 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.scholarship-page .scholarship-table tbody tr {
    transition: background .18s ease;
}

.scholarship-page .scholarship-table tbody tr:hover {
    background: #faf9f3;
}

.scholarship-page .scholarship-table tbody tr:last-child td {
    border-bottom: none;
}

.scholarship-page .serial-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e5e1d5;
    border-radius: 8px;
    color: #667269;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
}

.scholarship-page .student-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 180px;
}

.scholarship-page .student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #d7e2d6;
    border-radius: 10px;
    color: var(--ink);
    background: #eef2e8;
    font-size: 13px;
    font-weight: 800;
}

.scholarship-page .student-name {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}

.scholarship-page .student-id {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.5;
}

.scholarship-page .class-badge,
.scholarship-page .annual-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.scholarship-page .class-badge {
    border: 1px solid #d8e2d6;
    color: var(--ink);
    background: #f0f3e9;
}

.scholarship-page .annual-badge {
    border: 1px solid #e6dfc9;
    color: #8b641d;
    background: #faf5e7;
}

.scholarship-page .year-text {
    color: #5d6a60;
    font-weight: 700;
    white-space: nowrap;
}

.scholarship-page .percentage-wrapper {
    display: flex;
    align-items: center;
    gap: 9px;
}

.scholarship-page .percentage-bar {
    width: 48px;
    height: 6px;
    flex: 0 0 48px;
    overflow: hidden;
    border-radius: 10px;
    background: #e7e9df;
}

.scholarship-page .percentage-fill {
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--teal), #86a88c);
}

.scholarship-page .percentage-value {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.scholarship-page .scholarship-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.scholarship-page .scholarship-badge.eligible {
    border: 1px solid #cfe2cd;
    color: #347849;
    background: #edf5e9;
}

.scholarship-page .scholarship-badge.not-eligible {
    border: 1px solid #edd2cb;
    color: var(--red);
    background: #fff1ed;
}

.scholarship-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.scholarship-page .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border: 1px solid #dedfd4;
    border-radius: 8px;
    color: var(--ink);
    background: var(--paper);
    text-decoration: none;
    font-size: 12px;
}

.scholarship-page .action-btn:hover {
    border-color: #c8d4c5;
    color: #fffefa;
    background: var(--ink);
    transform: translateY(-2px);
    box-shadow: 0 4px 9px rgba(38, 59, 53, .10);
}

.scholarship-page .action-btn.edit:hover {
    border-color: #e6d0a8;
    color: #fff;
    background: var(--wood);
}

.scholarship-page .muted-text {
    color: #929a8e;
    font-size: 11px;
}

/* ---------- Empty state ---------- */

.scholarship-page .empty-state {
    padding: 58px 20px;
    text-align: center;
}

.scholarship-page .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    color: var(--ink);
    background: var(--cream);
    font-size: 27px;
}

.scholarship-page .empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 21px;
    font-weight: 700;
}

.scholarship-page .empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* ---------- Pagination ---------- */

.scholarship-page .pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}

.scholarship-page .pagination-wrapper nav {
    display: inline-flex;
    max-width: 100%;
}

.scholarship-page .pagination-wrapper .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.scholarship-page .pagination-wrapper .page-link {
    border-color: var(--line);
    border-radius: 7px;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
}

.scholarship-page .pagination-wrapper .page-link:hover {
    border-color: var(--ink);
    color: var(--ink);
    background: var(--chalk);
}

.scholarship-page .pagination-wrapper .active > .page-link {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
}

/* ---------- Responsive ---------- */

@media (max-width: 1350px) {
    .scholarship-page .filter-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .scholarship-page .filter-actions {
        grid-column: span 3;
    }
}

@media (max-width: 1200px) {
    .scholarship-page .scholarship-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .scholarship-page .scholarship-hero {
        grid-template-columns: 1fr;
    }

    .scholarship-page .hero-actions {
        min-height: 175px;
    }

    .scholarship-page .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .scholarship-page .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 650px) {
    .scholarship-page .scholarship-hero::before {
        left: 20px;
    }

    .scholarship-page .scholarship-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 16px 23px 39px;
    }

    .scholarship-page .scholarship-hero-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 18px;
    }

    .scholarship-page .scholarship-hero-text h2 {
        font-size: 23px;
    }

    .scholarship-page .scholarship-hero-text p {
        font-size: 12px;
    }

    .scholarship-page .hero-actions {
        min-height: 160px;
        padding: 21px;
    }

    .scholarship-page .hero-actions-label {
        font-size: 22px;
    }

    .scholarship-page .scholarship-kpis {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .scholarship-page .filter-panel {
        padding: 15px;
    }

    .scholarship-page .filter-heading {
        align-items: flex-start;
    }

    .scholarship-page .filter-title {
        font-size: 16px;
    }

    .scholarship-page .filter-badge {
        display: none;
    }

    .scholarship-page .filter-grid {
        grid-template-columns: 1fr;
    }

    .scholarship-page .filter-actions {
        grid-column: auto;
    }

    .scholarship-page .filter-actions .apply-btn,
    .scholarship-page .filter-actions .reset-btn {
        flex: 1;
    }

    .scholarship-page .table-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .scholarship-page .table-header-actions {
        width: 100%;
    }

    .scholarship-page .table-header-actions .reset-btn {
        width: 100%;
    }

    .scholarship-page .table-panel-title {
        font-size: 16px;
    }

    .scholarship-page .pagination-wrapper {
        justify-content: center;
        padding: 14px 8px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .scholarship-page *,
    .scholarship-page *::before,
    .scholarship-page *::after {
        scroll-behavior: auto !important;
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="scholarship-page">

    {{-- Success message --}}
    @if(session('success'))
        <div class="success-alert" role="status">
            <i class="fas fa-circle-check" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Locked School Diary hero --}}
    <section class="scholarship-hero">
        <div class="scholarship-hero-content">
            <div class="scholarship-hero-icon">
                <i class="fas fa-award" aria-hidden="true"></i>
            </div>

            <div class="scholarship-hero-text">
                <div class="hero-eyebrow">
                    <i class="fas fa-book-open" aria-hidden="true"></i>
                    STUDENT ACHIEVEMENT
                </div>

                <h2>Merit Concession</h2>

                <p>
                    Manage student scholarships, merit eligibility and
                    annual examination-based concessions from one place.
                    Recognise academic performance and track scholarship allocation.
                </p>

                <div class="hero-mini-info">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Annual Exam &amp; Merit Based Scholarship</span>
                </div>
            </div>
        </div>

        <div class="hero-actions">
            <div class="hero-actions-label">Merit &amp; Opportunity</div>

            <div class="hero-actions-subtitle">
                Recognise academic achievement and manage student concessions.
            </div>

            <a href="{{ route('admin.scholarship.merit.concession.create') }}"
               class="scholarship-rules-btn">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Scholarship Rules
            </a>
        </div>
    </section>

    {{-- Scholarship KPI cards --}}
    <section class="scholarship-kpis">

        <div class="scholarship-kpi blue">
            <div class="kpi-top">
                <span class="kpi-label">Annual Results</span>
                <span class="kpi-icon">
                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">{{ $totalStudents }}</div>
            <div class="kpi-caption">Total annual examination records</div>
        </div>

        <div class="scholarship-kpi green">
            <div class="kpi-top">
                <span class="kpi-label">Eligible Students</span>
                <span class="kpi-icon">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">{{ $eligibleStudents }}</div>
            <div class="kpi-caption">Students meeting scholarship criteria</div>
        </div>

        <div class="scholarship-kpi orange">
            <div class="kpi-top">
                <span class="kpi-label">Scholarship Assigned</span>
                <span class="kpi-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">{{ $scholarshipAssigned }}</div>
            <div class="kpi-caption">Students with scholarship percentage</div>
        </div>

        <div class="scholarship-kpi red">
            <div class="kpi-top">
                <span class="kpi-label">Not Eligible</span>
                <span class="kpi-icon">
                    <i class="fas fa-ban" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">{{ $notEligible }}</div>
            <div class="kpi-caption">Students currently not eligible</div>
        </div>

    </section>

    {{-- Search and filters --}}
    <section class="filter-panel">

        <div class="filter-heading">
            <div class="filter-title-wrap">
                <div class="filter-title-icon">
                    <i class="fas fa-filter" aria-hidden="true"></i>
                </div>

                <div>
                    <h3 class="filter-title">Search &amp; Filter Annual Results</h3>
                    <div class="filter-subtitle">
                        Refine scholarship records by student, year, class or percentage.
                    </div>
                </div>
            </div>

            <div class="filter-badge">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Advanced Filters
            </div>
        </div>

        <form method="GET"
              action="{{ route('admin.scholarship.merit.concession.index') }}">

            <div class="filter-grid">

                <div class="filter-group">
                    <label for="scholarship-search">Search Student</label>

                    <div class="filter-search">
                        <i class="fas fa-search" aria-hidden="true"></i>

                        <input
                            id="scholarship-search"
                            type="text"
                            name="search"
                            class="filter-control"
                            placeholder="Name, Student ID or Roll No..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="filter-group">
                    <label for="scholarship-year">Academic Year</label>

                    <select id="scholarship-year"
                            name="academic_year"
                            class="filter-control">
                        <option value="">All Years</option>

                        @foreach($academicYears as $year)
                            <option value="{{ $year }}"
                                @selected(request('academic_year') == $year)>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="scholarship-class">Class</label>

                    <select id="scholarship-class"
                            name="class"
                            class="filter-control">
                        <option value="">All Classes</option>

                        @foreach($classes as $class)
                            <option value="{{ $class }}"
                                @selected(request('class') == $class)>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="scholarship-section">Section</label>

                    <select id="scholarship-section"
                            name="section"
                            class="filter-control">
                        <option value="">All Sections</option>

                        @foreach($sections as $section)
                            <option value="{{ $section }}"
                                @selected(request('section') == $section)>
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="scholarship-min-percentage">
                        Minimum Percentage
                    </label>

                    <input
                        id="scholarship-min-percentage"
                        type="number"
                        name="min_percentage"
                        class="filter-control"
                        min="0"
                        max="100"
                        step="0.01"
                        placeholder="e.g. 60"
                        value="{{ request('min_percentage') }}"
                    >
                </div>

                <div class="filter-actions">
                    <button type="submit" class="apply-btn">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.scholarship.merit.concession.index') }}"
                       class="reset-btn">
                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </section>

    {{-- Annual exam scholarship results --}}
    <section class="table-panel">

        <div class="table-panel-header">
            <div class="table-panel-title-wrap">
                <div class="table-panel-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="table-panel-title">
                        Annual Exam Scholarship

                        <span class="table-count">
                            {{ $results->total() }}
                        </span>
                    </div>

                    <div class="table-panel-subtitle">
                        Student-wise scholarship eligibility and allocation
                    </div>
                </div>
            </div>

            <div class="table-header-actions">
                <a href="{{ route('admin.scholarship.merit.concession.index') }}"
                   class="reset-btn">
                    <i class="fas fa-rotate-left" aria-hidden="true"></i>
                    Reset Filters
                </a>
            </div>
        </div>

        @if($results->count())

            <div class="table-responsive">
                <table class="scholarship-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Academic Year</th>
                            <th>Annual Exam</th>
                            <th>Percentage</th>
                            <th>Scholarship</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($results as $result)
                            <tr>

                                <td>
                                    <span class="serial-number">
                                        {{ $results->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    @if($result->student)
                                        <div class="student-cell">

                                            <div class="student-avatar">
                                                {{ strtoupper(substr($result->student->full_name ?? 'S', 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="student-name">
                                                    {{ $result->student->full_name }}
                                                </div>

                                                <div class="student-id">
                                                    ID: {{ $result->student->student_id }}

                                                    @if($result->student->roll_number)
                                                        · Roll: {{ $result->student->roll_number }}
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    @else
                                        <span class="muted-text">
                                            Student Not Found
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="class-badge">
                                        <i class="fas fa-users" aria-hidden="true"></i>

                                        {{ $result->class_name }}

                                        @if($result->section)
                                            - {{ $result->section }}
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <span class="year-text">
                                        {{ $result->academic_year }}
                                    </span>
                                </td>

                                <td>
                                    <span class="annual-badge">
                                        <i class="fas fa-graduation-cap"
                                           aria-hidden="true"></i>

                                        {{ $result->exam->exam_name ?? 'Annual Exam' }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $percentage = max(
                                            0,
                                            min((float) $result->percentage, 100)
                                        );
                                    @endphp

                                    <div class="percentage-wrapper">
                                        <span class="percentage-value">
                                            {{ number_format((float) $result->percentage, 2) }}%
                                        </span>

                                        <div class="percentage-bar">
                                            <div
                                                class="percentage-fill"
                                                style="width: {{ $percentage }}%;"
                                            ></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if((float) $result->scholarship_percentage > 0)
                                        <span class="scholarship-badge eligible">
                                            <i class="fas fa-award"
                                               aria-hidden="true"></i>

                                            {{ number_format((float) $result->scholarship_percentage, 0) }}%
                                        </span>
                                    @else
                                        <span class="scholarship-badge not-eligible">
                                            <i class="fas fa-ban"
                                               aria-hidden="true"></i>
                                            Not Eligible
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($result->scholarship_eligible)
                                        <span class="scholarship-badge eligible">
                                            <i class="fas fa-check-circle"
                                               aria-hidden="true"></i>
                                            Eligible
                                        </span>
                                    @else
                                        <span class="scholarship-badge not-eligible">
                                            <i class="fas fa-times-circle"
                                               aria-hidden="true"></i>
                                            Not Eligible
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.scholarship.merit.concession.show', $result) }}"
                                            class="action-btn"
                                            title="View Scholarship"
                                            aria-label="View scholarship"
                                        >
                                            <i class="fas fa-eye"
                                               aria-hidden="true"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.scholarship.merit.concession.edit', $result) }}"
                                            class="action-btn edit"
                                            title="Edit Scholarship"
                                            aria-label="Edit scholarship"
                                        >
                                            <i class="fas fa-pen"
                                               aria-hidden="true"></i>
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            @if($results->hasPages())
                <div class="pagination-wrapper">
                    {{ $results->links() }}
                </div>
            @endif

        @else

            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </div>

                <h4>No Annual Exam Results Found</h4>

                <p>
                    Scholarship students will appear here when Annual Exam
                    results are available. Try changing your filters if
                    you expected to see records.
                </p>
            </div>

        @endif

    </section>

</div>

@endsection
