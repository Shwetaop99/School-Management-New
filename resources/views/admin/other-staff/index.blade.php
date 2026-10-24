@extends('layouts.app')

@section('title', 'Other Staff | Admin')
@section('page-title', 'Other Staff')

@section('content')

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Other Staff Dashboard
   Warm paper • Forest ink • Cream CTA • Earthy accents
   Reference: Scholarship / Merit Concession Dashboard
========================================================= */

.other-staff-page {
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

.other-staff-page *,
.other-staff-page *::before,
.other-staff-page *::after {
    box-sizing: border-box;
}

.other-staff-page a,
.other-staff-page button {
    -webkit-tap-highlight-color: transparent;
    transition:
        color .18s ease,
        background .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.other-staff-page .paper {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
}

/* ---------- School Diary hero ---------- */

.other-staff-page .staff-hero {
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

.other-staff-page .staff-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 32px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.other-staff-page .staff-hero-content {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
    padding: 30px 30px 30px 54px;
}

.other-staff-page .staff-hero-icon {
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

.other-staff-page .staff-hero-text {
    min-width: 0;
}

.other-staff-page .hero-eyebrow {
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

.other-staff-page .staff-hero-text h2 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.other-staff-page .staff-hero-text p {
    max-width: 620px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.other-staff-page .hero-mini-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 800;
}

.other-staff-page .hero-mini-info i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 23px;
    height: 23px;
    border-radius: 7px;
    color: var(--green);
    background: #eaf2e8;
}

/* ---------- Hero information panel (no add button) ---------- */

.other-staff-page .hero-actions {
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

.other-staff-page .hero-actions::after {
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

.other-staff-page .hero-actions-label {
    color: var(--cream);
    font-family: var(--hand, cursive);
    font-size: 25px;
    line-height: 1.2;
}

.other-staff-page .hero-actions-subtitle {
    max-width: 265px;
    color: #c5d0bd;
    font-size: 11px;
    line-height: 1.7;
}

/* ---------- Success / error alerts ---------- */

.other-staff-page .success-alert,
.other-staff-page .error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
}

.other-staff-page .success-alert {
    border: 1px solid #c7e2ce;
    color: #347849;
    background: #f0f7ed;
}

.other-staff-page .error-alert {
    border: 1px solid #edd2cb;
    color: var(--red);
    background: #fff1ed;
}

.other-staff-page .alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex: 0 0 28px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .65);
}

/* ---------- KPI cards / folder tabs ---------- */

.other-staff-page .staff-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
    padding-top: 10px;
}

.other-staff-page .staff-kpi {
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

.other-staff-page .staff-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(29, 58, 51, .10);
}

.other-staff-page .staff-kpi::before {
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

.other-staff-page .staff-kpi.blue { --c: #2b5d8a; }
.other-staff-page .staff-kpi.green { --c: #3a7958; }
.other-staff-page .staff-kpi.orange { --c: #b98024; }
.other-staff-page .staff-kpi.red { --c: #bd5148; }

.other-staff-page .staff-kpi.blue::before {
    content: "TEAM";
}

.other-staff-page .staff-kpi.green::before {
    content: "ACTIVE";
}

.other-staff-page .staff-kpi.orange::before {
    content: "INACTIVE";
}

.other-staff-page .staff-kpi.red::before {
    content: "LIBRARY";
}

.other-staff-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.other-staff-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.other-staff-page .kpi-icon {
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

.other-staff-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.other-staff-page .kpi-caption {
    padding-top: 12px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

/* ---------- Staff panel ---------- */

.other-staff-page .staff-panel {
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 4px 15px rgba(29, 58, 51, .04);
}

.other-staff-page .staff-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.other-staff-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.other-staff-page .panel-icon {
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

.other-staff-page .panel-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.other-staff-page .panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

.other-staff-page .table-count {
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

/* ---------- Search and filters ---------- */

.other-staff-page .filter-panel {
    padding: 20px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fffefa;
}

.other-staff-page .filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 17px;
    padding-bottom: 14px;
    border-bottom: 1px dashed #d8d1bf;
}

.other-staff-page .filter-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.other-staff-page .filter-title-icon {
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

.other-staff-page .filter-title {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.other-staff-page .filter-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.65;
}

.other-staff-page .filter-badge {
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

.other-staff-page .staff-filters {
    display: grid;
    grid-template-columns:
        minmax(190px, 1.7fr)
        repeat(2, minmax(130px, 1fr))
        auto;
    gap: 12px;
    align-items: end;
}

.other-staff-page .filter-group {
    min-width: 0;
}

.other-staff-page .filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 10px;
    font-weight: 800;
}

.other-staff-page .filter-search {
    position: relative;
}

.other-staff-page .filter-search > i {
    position: absolute;
    top: 15px;
    left: 12px;
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.other-staff-page .filter-control {
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

.other-staff-page .filter-search .filter-control {
    padding-left: 34px;
}

.other-staff-page .filter-control:hover {
    border-color: #bfcbbd;
}

.other-staff-page .filter-control:focus {
    border-color: #6e9a86;
    box-shadow: 0 0 0 3px rgba(58, 121, 88, .12);
}

.other-staff-page .filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ---------- Locked button styles ---------- */

.other-staff-page .apply-btn,
.other-staff-page .reset-btn {
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

.other-staff-page .apply-btn {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
    box-shadow: 0 3px 8px rgba(38, 59, 53, .10);
}

.other-staff-page .apply-btn:hover {
    border-color: var(--forest-light);
    color: #fffefa;
    background: var(--forest-light);
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(38, 59, 53, .13);
}

.other-staff-page .reset-btn {
    border-color: #d7d6ca;
    color: var(--ink);
    background: var(--paper);
}

.other-staff-page .reset-btn:hover {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
    transform: translateY(-1px);
}

.other-staff-page .apply-btn:focus-visible,
.other-staff-page .reset-btn:focus-visible,
.other-staff-page .action-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 121, .35);
    outline-offset: 3px;
}

/* ---------- Staff table ---------- */

.other-staff-page .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.other-staff-page .staff-table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

.other-staff-page .staff-table th {
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

.other-staff-page .staff-table td {
    padding: 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.other-staff-page .staff-table tbody tr {
    transition: background .18s ease;
}

.other-staff-page .staff-table tbody tr:hover {
    background: #faf9f3;
}

.other-staff-page .staff-table tbody tr:last-child td {
    border-bottom: none;
}

/* ---------- Staff identity / avatar ---------- */

.other-staff-page .staff-identity {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.other-staff-page .staff-avatar,
.other-staff-page .staff-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    overflow: hidden;
    border: 1px solid #d7e2d6;
    border-radius: 10px;
    color: var(--ink);
    background: #eef2e8;
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 15px;
    font-weight: 800;
}

.other-staff-page .staff-avatar {
    object-fit: cover;
}

.other-staff-page .staff-name {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}

.other-staff-page .staff-id {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.5;
}

/* ---------- Designation / department badges ---------- */

.other-staff-page .designation-badge,
.other-staff-page .department-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
}

.other-staff-page .designation-badge {
    border: 1px solid #d8e2d6;
    color: var(--ink);
    background: #f0f3e9;
}

.other-staff-page .department-badge {
    border: 1px solid #e6dfc9;
    color: #8b641d;
    background: #faf5e7;
}

.other-staff-page .phone-text,
.other-staff-page .date-text {
    color: #5d6a60;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* ---------- Status badges ---------- */

.other-staff-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.other-staff-page .status-badge.active {
    border: 1px solid #cfe2cd;
    color: #347849;
    background: #edf5e9;
}

.other-staff-page .status-badge.inactive {
    border: 1px solid #edd2cb;
    color: var(--red);
    background: #fff1ed;
}

/* ---------- Action buttons ---------- */

.other-staff-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.other-staff-page .action-buttons form {
    display: inline-flex;
    margin: 0;
}

.other-staff-page .action-btn {
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
    cursor: pointer;
}

.other-staff-page .action-btn:hover {
    border-color: #c8d4c5;
    color: #fffefa;
    background: var(--ink);
    transform: translateY(-2px);
    box-shadow: 0 4px 9px rgba(38, 59, 53, .10);
}

.other-staff-page .action-btn.edit:hover {
    border-color: #e6d0a8;
    color: #fff;
    background: var(--wood);
}

.other-staff-page .action-btn.delete:hover {
    border-color: #edd2cb;
    color: #fff;
    background: var(--red);
}

.other-staff-page .muted-text {
    color: #929a8e;
    font-size: 11px;
}

/* ---------- Empty state ---------- */

.other-staff-page .empty-state {
    padding: 58px 20px;
    text-align: center;
}

.other-staff-page .empty-icon {
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

.other-staff-page .empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 21px;
    font-weight: 700;
}

.other-staff-page .empty-state p {
    max-width: 450px;
    margin: 0 auto;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

/* ---------- Pagination ---------- */

.other-staff-page .pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}

.other-staff-page .pagination-wrapper nav {
    display: inline-flex;
    max-width: 100%;
}

.other-staff-page .pagination-wrapper .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.other-staff-page .pagination-wrapper .page-link {
    border-color: var(--line);
    border-radius: 7px;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
}

.other-staff-page .pagination-wrapper .page-link:hover {
    border-color: var(--ink);
    color: var(--ink);
    background: var(--chalk);
}

.other-staff-page .pagination-wrapper .active > .page-link {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
}

/* ---------- Responsive ---------- */

@media (max-width: 1350px) {
    .other-staff-page .staff-filters {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .other-staff-page .filter-actions {
        grid-column: span 3;
    }
}

@media (max-width: 1200px) {
    .other-staff-page .staff-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .other-staff-page .staff-hero {
        grid-template-columns: 1fr;
    }

    .other-staff-page .hero-actions {
        min-height: 175px;
    }

    .other-staff-page .staff-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .other-staff-page .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 650px) {
    .other-staff-page .staff-hero::before {
        left: 20px;
    }

    .other-staff-page .staff-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 16px 23px 39px;
    }

    .other-staff-page .staff-hero-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 18px;
    }

    .other-staff-page .staff-hero-text h2 {
        font-size: 23px;
    }

    .other-staff-page .staff-hero-text p {
        font-size: 12px;
    }

    .other-staff-page .hero-actions {
        min-height: 160px;
        padding: 21px;
    }

    .other-staff-page .hero-actions-label {
        font-size: 22px;
    }

    .other-staff-page .staff-kpis {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .other-staff-page .filter-panel {
        padding: 15px;
    }

    .other-staff-page .filter-heading {
        align-items: flex-start;
    }

    .other-staff-page .filter-title {
        font-size: 16px;
    }

    .other-staff-page .filter-badge {
        display: none;
    }

    .other-staff-page .staff-filters {
        grid-template-columns: 1fr;
    }

    .other-staff-page .filter-actions {
        grid-column: auto;
    }

    .other-staff-page .filter-actions .apply-btn,
    .other-staff-page .filter-actions .reset-btn {
        flex: 1;
    }

    .other-staff-page .staff-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .other-staff-page .panel-title {
        font-size: 16px;
    }

    .other-staff-page .pagination-wrapper {
        justify-content: center;
        padding: 14px 8px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .other-staff-page *,
    .other-staff-page *::before,
    .other-staff-page *::after {
        scroll-behavior: auto !important;
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="other-staff-page">

    {{-- Success message --}}
    @if (session('success'))
        <div class="success-alert" role="status">
            <span class="alert-icon">
                <i class="fas fa-circle-check" aria-hidden="true"></i>
            </span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error message --}}
    @if (session('error'))
        <div class="error-alert" role="alert">
            <span class="alert-icon">
                <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            </span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Locked School Diary hero --}}
    <section class="staff-hero">

        <div class="staff-hero-content">

            <div class="staff-hero-icon">
                <i class="fas fa-users-gear" aria-hidden="true"></i>
            </div>

            <div class="staff-hero-text">

                <div class="hero-eyebrow">
                    <i class="fas fa-book-open" aria-hidden="true"></i>
                    SCHOOL PEOPLE &amp; OPERATIONS
                </div>

                <h2>Other Staff</h2>

                <p>
                    Manage your school's non-teaching workforce in one place.
                    Organise staff details, designations, departments and
                    employment status while keeping every staff record accessible.
                </p>

                <div class="hero-mini-info">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Staff Records &amp; Workforce Management</span>
                </div>

            </div>

        </div>

        {{-- Decorative information panel; add button removed --}}
        <div class="hero-actions">

            <div class="hero-actions-label">
                Every Role Matters
            </div>

            <div class="hero-actions-subtitle">
                Keep your school team organised, connected and up to date.
            </div>

        </div>

    </section>

    {{-- Staff KPI cards --}}
    <section class="staff-kpis">

        {{-- Total staff --}}
        <div class="staff-kpi blue">

            <div class="kpi-top">
                <span class="kpi-label">Total Staff</span>

                <span class="kpi-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ number_format($totalStaff) }}
            </div>

            <div class="kpi-caption">
                Total non-teaching staff records
            </div>

        </div>

        {{-- Active staff --}}
        <div class="staff-kpi green">

            <div class="kpi-top">
                <span class="kpi-label">Active Staff</span>

                <span class="kpi-icon">
                    <i class="fas fa-user-check" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ number_format($activeStaff) }}
            </div>

            <div class="kpi-caption">
                Staff members currently active
            </div>

        </div>

        {{-- Inactive staff --}}
        <div class="staff-kpi orange">

            <div class="kpi-top">
                <span class="kpi-label">Inactive Staff</span>

                <span class="kpi-icon">
                    <i class="fas fa-user-clock" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ number_format($inactiveStaff) }}
            </div>

            <div class="kpi-caption">
                Staff members currently inactive
            </div>

        </div>

        {{-- Librarians --}}
        <div class="staff-kpi red">

            <div class="kpi-top">
                <span class="kpi-label">Active Librarians</span>

                <span class="kpi-icon">
                    <i class="fas fa-book-open" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ number_format($librarians) }}
            </div>

            <div class="kpi-caption">
                Active staff assigned to the library
            </div>

        </div>

    </section>

    {{-- Staff records panel --}}
    <section class="staff-panel">

        <div class="staff-panel-header">

            <div class="panel-title-wrap">

                <div class="panel-icon">
                    <i class="fas fa-address-card" aria-hidden="true"></i>
                </div>

                <div>
                    <h3 class="panel-title">
                        Staff Members

                        <span class="table-count">
                            {{ method_exists($staff, 'total') ? $staff->total() : $staff->count() }}
                        </span>
                    </h3>

                    <div class="panel-subtitle">
                        All non-teaching staff registered in the school
                    </div>
                </div>

            </div>

        </div>

        {{-- Search and filters --}}
        <div class="filter-panel">

            <div class="filter-heading">

                <div class="filter-title-wrap">

                    <div class="filter-title-icon">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3 class="filter-title">
                            Search &amp; Filter Staff
                        </h3>

                        <div class="filter-subtitle">
                            Find staff by name, ID, designation or employment status.
                        </div>
                    </div>

                </div>

                <div class="filter-badge">
                    <i class="fas fa-sliders-h" aria-hidden="true"></i>
                    Staff Filters
                </div>

            </div>

            <form
                method="GET"
                action="{{ route('admin.other-staff.index') }}"
                class="staff-filters"
            >

                {{-- Search --}}
                <div class="filter-group">

                    <label for="staff-search">
                        Search Staff
                    </label>

                    <div class="filter-search">

                        <i class="fas fa-search" aria-hidden="true"></i>

                        <input
                            id="staff-search"
                            type="text"
                            name="search"
                            class="filter-control"
                            value="{{ request('search') }}"
                            placeholder="Name, Staff ID or phone..."
                        >

                    </div>

                </div>

                {{-- Designation --}}
                <div class="filter-group">

                    <label for="staff-designation">
                        Designation
                    </label>

                    <select
                        id="staff-designation"
                        name="designation"
                        class="filter-control"
                    >

                        <option value="">All Designations</option>

                        @foreach ($designations as $designation)
                            <option
                                value="{{ $designation }}"
                                @selected(request('designation') === $designation)
                            >
                                {{ $designation }}
                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- Status --}}
                <div class="filter-group">

                    <label for="staff-status">
                        Employment Status
                    </label>

                    <select
                        id="staff-status"
                        name="status"
                        class="filter-control"
                    >

                        <option value="">All Statuses</option>

                        <option
                            value="Active"
                            @selected(request('status') === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(request('status') === 'Inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>

                {{-- Filter actions --}}
                <div class="filter-actions">

                    <button type="submit" class="apply-btn">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        Search
                    </button>

                    @if (request()->hasAny(['search', 'designation', 'status']))
                        <a
                            href="{{ route('admin.other-staff.index') }}"
                            class="reset-btn"
                        >
                            <i class="fas fa-rotate-left" aria-hidden="true"></i>
                            Reset
                        </a>
                    @endif

                </div>

            </form>

        </div>

        {{-- Staff results table --}}
        @if ($staff->count())

            <div class="table-responsive">

                <table class="staff-table">

                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($staff as $member)

                            <tr>

                                {{-- Staff identity --}}
                                <td>

                                    <div class="staff-identity">

                                        @if ($member->profile_photo)

                                            <img
                                                src="{{ asset('storage/' . $member->profile_photo) }}"
                                                alt="{{ $member->name }}"
                                                class="staff-avatar"
                                                loading="lazy"
                                            >

                                        @else

                                            <div class="staff-avatar-placeholder">
                                                {{ strtoupper(mb_substr($member->name ?? 'S', 0, 1)) }}
                                            </div>

                                        @endif

                                        <div>

                                            <div class="staff-name">
                                                {{ $member->name }}
                                            </div>

                                            <div class="staff-id">
                                                ID: {{ $member->staff_id ?: '—' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- Designation --}}
                                <td>

                                    <span class="designation-badge">
                                        <i class="fas fa-briefcase" aria-hidden="true"></i>
                                        {{ $member->designation ?: '—' }}
                                    </span>

                                </td>

                                {{-- Department --}}
                                <td>

                                    <span class="department-badge">
                                        <i class="fas fa-building" aria-hidden="true"></i>
                                        {{ $member->department ?: '—' }}
                                    </span>

                                </td>

                                {{-- Phone --}}
                                <td>

                                    @if ($member->phone)

                                        <span class="phone-text">
                                            <i class="fas fa-phone" aria-hidden="true"></i>
                                            {{ $member->phone }}
                                        </span>

                                    @else

                                        <span class="muted-text">—</span>

                                    @endif

                                </td>

                                {{-- Joining date --}}
                                <td>

                                    @if ($member->joining_date)

                                        <span class="date-text">
                                            <i class="fas fa-calendar-days" aria-hidden="true"></i>
                                            {{ \Carbon\Carbon::parse($member->joining_date)->format('d M Y') }}
                                        </span>

                                    @else

                                        <span class="muted-text">—</span>

                                    @endif

                                </td>

                                {{-- Status --}}
                                <td>

                                    @if ($member->status === 'Active')

                                        <span class="status-badge active">
                                            <i class="fas fa-circle-check" aria-hidden="true"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="status-badge inactive">
                                            <i class="fas fa-circle-xmark" aria-hidden="true"></i>
                                            {{ $member->status ?: 'Inactive' }}
                                        </span>

                                    @endif

                                </td>

                                {{-- Actions --}}
                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.other-staff.show', $member) }}"
                                            class="action-btn"
                                            title="View Staff"
                                            aria-label="View {{ $member->name }}"
                                        >
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.other-staff.edit', $member) }}"
                                            class="action-btn edit"
                                            title="Edit Staff"
                                            aria-label="Edit {{ $member->name }}"
                                        >
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.other-staff.destroy', $member) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this staff member?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Delete Staff"
                                                aria-label="Delete {{ $member->name }}"
                                            >
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($staff->hasPages())

                <div class="pagination-wrapper">
                    {{ $staff->links() }}
                </div>

            @endif

        @else

            {{-- Empty state: Add Staff button removed --}}
            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>

                <h4>No Staff Members Found</h4>

                <p>
                    No staff records match your current search or filters.
                    Try changing the filters to find existing staff records.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection