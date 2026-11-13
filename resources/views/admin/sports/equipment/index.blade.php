@extends('layouts.app')

@section('title', 'Sports Equipment | Admin')
@section('page-title', 'Sports Equipment')

@section('content')

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   SPORTS EQUIPMENT — INDEX PAGE
   Preserve existing Laravel functionality and routes.
========================================================= */

.sports-equipment-page {
    --ink: #263b35;
    --ink-dark: #20352c;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --cream: #f1ead8;
    --wood: #a87543;
    --blue: #2b5d8a;
    --green: #3a8b58;
    --amber: #d58a0b;
    --red: #bd5148;
    --cyan: #527f79;

    width: 100%;
    min-height: calc(100vh - 70px);
    padding: 26px;
    color: var(--text);
    background:
        radial-gradient(circle at 0% 0%, rgba(168,117,67,.045), transparent 28%),
        radial-gradient(circle at 100% 0%, rgba(82,127,121,.045), transparent 26%),
        linear-gradient(180deg, #f7f6f0 0%, #f5f3e9 100%);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    font-size: 13px;
}

.sports-equipment-page *,
.sports-equipment-page *::before,
.sports-equipment-page *::after {
    box-sizing: border-box;
}

.sports-equipment-page a,
.sports-equipment-page button {
    transition:
        color .18s ease,
        background .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.sports-equipment-page a:focus-visible,
.sports-equipment-page button:focus-visible,
.sports-equipment-page input:focus-visible,
.sports-equipment-page select:focus-visible {
    outline: 3px solid rgba(82,127,121,.35);
    outline-offset: 2px;
}

/* ================= PAGE HERO ================= */

.equipment-page-header {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(250px, .7fr);
    overflow: hidden;
    margin-bottom: 28px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 7px 22px rgba(39,55,45,.055);
}

.equipment-page-header::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 29px;
    width: 2px;
    background: rgba(189,81,72,.32);
}

.equipment-heading-wrapper {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 51px;
}

.equipment-page-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e6dfce;
    border-radius: 12px;
    font-size: 23px;
}

.equipment-heading-text {
    min-width: 0;
}

.equipment-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 9px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.equipment-page-title {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 30px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.55px;
}

.equipment-page-subtitle {
    max-width: 590px;
    margin: 9px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.75;
}

.equipment-header-mini {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
    color: var(--ink-hover);
    font-size: 11px;
    font-weight: 750;
}

.equipment-header-mini i {
    color: var(--green);
}

/* ================= HERO QUOTE SECTION ================= */

.equipment-header-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 19px;
    min-width: 0;
    padding: 28px 25px;
    color: #f6f2e5;
    background:
        radial-gradient(ellipse at 18% 0%, rgba(255,255,255,.09), transparent 58%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
}

.equipment-header-note {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    max-width: 350px;
    text-align: center;
}

.equipment-header-note > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    color: #f1d8a9;
    background: rgba(255,255,255,.075);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 11px;
    font-size: 18px;
}

.equipment-header-note strong {
    display: block;
    margin: 0;
    color: #fffefa;
    font-family:  var(--hand, cursive);
    font-size: 25px;
    font-weight: 600;
    line-height: 1.3;
    letter-spacing: -.25px;
}

.equipment-header-note p {
    max-width: 320px;
    margin: 0;
    color: #e0e6dc;
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    font-size: 12px;
    font-weight: 400;
    line-height: 1.8;
}

/* ================= HERO CTA ================= */

.equipment-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 0 17px;
    color: var(--ink);
    background: var(--cream);
    border: 1px solid #e2d8c1;
    border-radius: 8px;
    box-shadow: 0 5px 14px rgba(0,0,0,.12);
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.equipment-add-btn:hover {
    color: var(--ink-dark);
    background: #e8dfc9;
    border-color: #d8cbb0;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,0,0,.16);
}

/* ================= SUCCESS MESSAGE ================= */

.equipment-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
    padding: 13px 16px;
    color: #26794b;
    background: #f0faf2;
    border: 1px solid #c7e8d4;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
}

.equipment-alert > i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    background: #d9f1e0;
    border-radius: 50%;
}

/* ================= STATISTIC CARDS ================= */

.equipment-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.equipment-stat-card {
    --stat-color: var(--blue);

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 13px;
    min-width: 0;
    margin-top: 0;
    padding: 23px 20px 18px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-top: 4px solid var(--stat-color);
    border-radius: 10px;
    box-shadow: 0 5px 17px rgba(39,55,45,.045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.equipment-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(39,55,45,.09);
}

.equipment-stat-card::before {
    content: "";
    position: absolute;
    top: -26px;
    left: 15px;
    padding: 5px 12px 7px;
    color: #fff;
    background: var(--stat-color);
    border-radius: 6px 6px 0 0;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .2px;
}

.equipment-stat-blue { --stat-color: var(--blue); }
.equipment-stat-green { --stat-color: var(--green); }
.equipment-stat-orange { --stat-color: var(--amber); }
.equipment-stat-red { --stat-color: var(--red); }

.equipment-stat-blue::before { content: "Inventory"; }
.equipment-stat-green::before { content: "Operational"; }
.equipment-stat-orange::before { content: "Available"; }
.equipment-stat-red::before { content: "Attention"; }

.equipment-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.equipment-stat-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 750;
    letter-spacing: .15px;
}

.equipment-stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    color: var(--stat-color);
    background: #f0f2e9;
    border: 1px solid #ebe9dd;
    border-radius: 10px;
    font-size: 16px;
}

.equipment-stat-blue .equipment-stat-icon {
    background: #edf3f8;
    border-color: #e0e9ef;
}

.equipment-stat-green .equipment-stat-icon {
    background: #edf7ed;
    border-color: #d8ead8;
}

.equipment-stat-orange .equipment-stat-icon {
    background: #fff5df;
    border-color: #f1e4c7;
}

.equipment-stat-red .equipment-stat-icon {
    background: #fff0e9;
    border-color: #f3dcd4;
}

.equipment-stat-value {
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 33px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.equipment-stat-caption {
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
    line-height: 1.55;
}

/* ================= PANEL CARDS ================= */

.equipment-filter-card,
.equipment-table-card {
    overflow: hidden;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(39,55,45,.045);
}

.equipment-filter-card {
    margin-bottom: 23px;
}

.equipment-filter-header,
.equipment-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 19px;
    background: #fffefa;
    border-bottom: 1px dashed #d8d5c8;
}

.equipment-filter-heading-wrap,
.equipment-table-heading-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.equipment-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    flex: 0 0 39px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e9e2d1;
    border-radius: 10px;
    font-size: 14px;
}

.equipment-panel-heading {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
}

.equipment-panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.equipment-filter-hint {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    color: var(--ink);
    background: #f7f6f0;
    border: 1px solid var(--line);
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.equipment-filter-body {
    padding: 20px;
}

/* ================= FILTER CONTROLS ================= */

.equipment-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(190px, 1.6fr)
        minmax(140px, 1fr)
        minmax(140px, 1fr)
        minmax(130px, .9fr)
        auto;
    gap: 13px;
    align-items: end;
}

.equipment-filter-field {
    min-width: 0;
}

.equipment-filter-label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 10px;
    font-weight: 800;
}

.equipment-filter-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 42px;
    padding: 0 11px;
    color: var(--text);
    background: #fffefa;
    border: 1px solid #dcded3;
    border-radius: 8px;
    outline: none;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.equipment-filter-control::placeholder {
    color: #9aa196;
}

.equipment-filter-control:hover {
    border-color: #c8cfbf;
}

.equipment-filter-control:focus {
    border-color: #6e9a86;
    box-shadow: 0 0 0 3px rgba(58,139,88,.11);
}

.equipment-filter-control option {
    color: var(--text);
    background: var(--paper);
}

.equipment-search-wrapper {
    position: relative;
}

.equipment-search-icon {
    position: absolute;
    top: 15px;
    left: 12px;
    z-index: 1;
    color: #929b8f;
    font-size: 11px;
    pointer-events: none;
}

.equipment-search-input {
    padding-left: 33px;
}

.equipment-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.equipment-filter-btn,
.equipment-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 0 14px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.equipment-filter-btn {
    color: #fffefa;
    background: var(--ink);
    border: 1px solid var(--ink);
    box-shadow: 0 4px 11px rgba(38,59,53,.12);
}

.equipment-filter-btn:hover {
    color: #fffefa;
    background: var(--ink-hover);
    border-color: var(--ink-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(38,59,53,.16);
}

.equipment-reset-btn {
    color: var(--ink);
    background: #fffefa;
    border: 1px solid #d7d6ca;
}

.equipment-reset-btn:hover {
    color: var(--ink-dark);
    background: var(--cream);
    border-color: #d8cbb0;
}

/* ================= TABLE HEADER ================= */

.equipment-table-title {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
}

.equipment-table-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 27px;
    min-height: 23px;
    padding: 0 9px;
    color: var(--ink);
    background: var(--cream);
    border: 1px solid #e5dcc6;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 850;
    white-space: nowrap;
}

/* ================= INVENTORY TABLE ================= */

.equipment-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.equipment-table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

.equipment-table thead th {
    padding: 13px;
    color: #68766d;
    background: #f5f3e9;
    border-bottom: 1px solid #e5e1d5;
    font-size: 9px;
    font-weight: 850;
    letter-spacing: .6px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.equipment-table tbody td {
    padding: 14px 13px;
    color: var(--text);
    border-bottom: 1px solid #eeece3;
    font-size: 12px;
    vertical-align: middle;
}

.equipment-table tbody tr {
    transition: background .18s ease;
}

.equipment-table tbody tr:hover {
    background: #faf9f4;
}

.equipment-table tbody tr:last-child td {
    border-bottom: 0;
}

.equipment-name {
    display: block;
    max-width: 220px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.equipment-brand {
    display: block;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.5;
}

.equipment-category {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 9px;
    color: var(--blue);
    background: #f0f5f8;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.equipment-location {
    display: inline-block;
    min-width: 100px;
    color: #5d6a60;
    font-size: 11px;
    line-height: 1.5;
}

/* ================= STOCK INDICATOR ================= */

.equipment-stock {
    min-width: 110px;
}

.equipment-stock-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 7px;
}

.equipment-stock-number {
    color: var(--ink);
    font-size: 11px;
    font-weight: 850;
    white-space: nowrap;
}

.equipment-stock-total {
    color: #858e82;
    font-size: 10px;
    white-space: nowrap;
}

.equipment-stock-bar {
    width: 100%;
    height: 5px;
    overflow: hidden;
    background: #e7e9df;
    border-radius: 10px;
}

.equipment-stock-progress {
    height: 100%;
    background: linear-gradient(90deg, #2b5d8a, #527f79);
    border-radius: inherit;
}

/* ================= CONDITION AND STATUS BADGES ================= */

.equipment-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

.equipment-badge-good {
    color: #347849;
    background: #edf7ed;
    border-color: #cfe5d3;
}

.equipment-badge-fair {
    color: #8b641d;
    background: #faf5e7;
    border-color: #e6dfc9;
}

.equipment-badge-damaged {
    color: #a63e30;
    background: #fff0e9;
    border-color: #f1d4cb;
}

.equipment-badge-active {
    color: var(--blue);
    background: #f0f5f8;
    border-color: #d7e2e9;
}

.equipment-badge-inactive {
    color: #77766e;
    background: #f0efe9;
    border-color: #e1dfd5;
}

.equipment-badge-dot {
    font-size: 5px;
}

/* ================= ROW ACTION BUTTONS ================= */

.equipment-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.equipment-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    flex: 0 0 33px;
    padding: 0;
    background: #fffefa;
    border: 1px solid #dedfd4;
    border-radius: 8px;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.equipment-action-view {
    color: var(--blue);
}

.equipment-action-view:hover {
    color: var(--blue);
    background: #f1f6f8;
    border-color: #bdcfd9;
    transform: translateY(-1px);
}

.equipment-action-edit {
    color: var(--wood);
}

.equipment-action-edit:hover {
    color: #80532f;
    background: #fcf7e9;
    border-color: #e6d0a8;
    transform: translateY(-1px);
}

.equipment-action-delete {
    color: var(--red);
}

.equipment-action-delete:hover {
    color: #fff;
    background: var(--red);
    border-color: var(--red);
    transform: translateY(-1px);
}

.equipment-delete-form {
    display: inline-flex;
    margin: 0;
}

/* ================= EMPTY STATE ================= */

.equipment-empty-state {
    padding: 62px 20px;
    text-align: center;
}

.equipment-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 16px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    font-size: 25px;
}

.equipment-empty-title {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
}

.equipment-empty-text {
    max-width: 450px;
    margin: 0 auto 20px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.75;
}

.equipment-empty-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 0 16px;
    color: #fffefa;
    background: var(--ink);
    border: 1px solid var(--ink);
    border-radius: 8px;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
}

.equipment-empty-add-btn:hover {
    color: #fffefa;
    background: var(--ink-hover);
    border-color: var(--ink-hover);
    transform: translateY(-1px);
}

/* ================= PAGINATION ================= */

.equipment-pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 15px 18px;
    background: #fffefa;
    border-top: 1px dashed #d8d5c8;
}

.equipment-pagination nav {
    max-width: 100%;
}

.equipment-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.equipment-pagination .page-link {
    color: var(--ink);
    background: #fffefa;
    border-color: var(--line);
    border-radius: 6px;
    font-size: 11px;
}

.equipment-pagination .page-item.active .page-link {
    color: #fffefa;
    background: var(--ink);
    border-color: var(--ink);
}

.equipment-pagination .page-link:hover {
    color: var(--ink);
    background: var(--cream);
    border-color: #d8cbb0;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 1400px) {
    .equipment-filter-grid {
        grid-template-columns:
            minmax(180px, 1.5fr)
            minmax(130px, 1fr)
            minmax(130px, 1fr)
            minmax(120px, .9fr);
    }

    .equipment-filter-actions {
        grid-column: 1 / -1;
        justify-content: flex-end;
    }
}

@media (max-width: 1100px) {
    .equipment-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 34px;
    }

    .equipment-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .equipment-filter-actions {
        grid-column: span 2;
        justify-content: flex-start;
    }
}

@media (max-width: 900px) {
    .sports-equipment-page {
        padding: 18px;
    }

    .equipment-page-header {
        grid-template-columns: 1fr;
    }

    .equipment-header-actions {
        min-height: 0;
        padding: 28px 22px;
    }

    .equipment-header-note {
        flex-direction: column;
        gap: 10px;
    }

    .equipment-header-note strong {
        font-size: 23px;
    }

    .equipment-header-note p {
        max-width: 420px;
    }
}

@media (max-width: 650px) {
    .sports-equipment-page {
        padding: 12px;
    }

    .equipment-page-header {
        margin-bottom: 25px;
    }

    .equipment-page-header::before {
        left: 19px;
    }

    .equipment-heading-wrapper {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 35px;
    }

    .equipment-page-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        border-radius: 10px;
        font-size: 18px;
    }

    .equipment-page-title {
        font-size: 23px;
    }

    .equipment-page-subtitle {
        font-size: 11px;
    }

    .equipment-breadcrumb {
        gap: 6px;
        font-size: 9px;
        letter-spacing: .7px;
    }

    .equipment-header-mini {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.6;
    }

    .equipment-header-actions {
        padding: 25px 16px;
    }

    .equipment-header-note strong {
        font-size: 22px;
    }

    .equipment-header-note p {
        font-size: 11px;
    }

    .equipment-add-btn {
        width: 100%;
    }

    .equipment-stats-grid {
        grid-template-columns: 1fr;
        gap: 31px;
        margin-top: 33px;
    }

    .equipment-stat-card {
        padding: 22px 18px 17px;
    }

    .equipment-filter-header,
    .equipment-table-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .equipment-filter-hint {
        display: none;
    }

    .equipment-filter-body {
        padding: 14px;
    }

    .equipment-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .equipment-filter-actions {
        grid-column: auto;
        flex-direction: column;
        align-items: stretch;
    }

    .equipment-filter-btn,
    .equipment-reset-btn {
        width: 100%;
    }

    .equipment-table-header {
        gap: 10px;
        padding: 15px;
    }

    .equipment-pagination {
        justify-content: flex-start;
        overflow-x: auto;
    }

    .equipment-empty-state {
        padding: 45px 16px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .sports-equipment-page *,
    .sports-equipment-page *::before,
    .sports-equipment-page *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="sports-equipment-page">

    {{-- ================= PAGE HERO ================= --}}

    <div class="equipment-page-header">

        <div class="equipment-heading-wrapper">

            <div class="equipment-page-icon">
                <i class="fas fa-dumbbell" aria-hidden="true"></i>
            </div>

            <div class="equipment-heading-text">

                <div class="equipment-breadcrumb">
                    <i class="fas fa-futbol" aria-hidden="true"></i>
                    SPORTS MANAGEMENT / EQUIPMENT
                </div>

                <h2 class="equipment-page-title">Sports Equipment</h2>

                <p class="equipment-page-subtitle">
                    Manage sports equipment, stock, condition and availability
                    through one organized inventory register.
                </p>

                <div class="equipment-header-mini">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <span>Inventory · Stock · Equipment Condition</span>
                </div>

            </div>

        </div>

        {{-- ACHIEVEMENTS-STYLE HERO QUOTE --}}

        <div class="equipment-header-actions">

            <div class="equipment-header-note">

                <i class="fas fa-boxes" aria-hidden="true"></i>

                <strong>Equip. Empower. Excel.</strong>

                <p>
                    Every great performance starts with the right equipment.
                    Manage every resource that keeps our champions moving.
                </p>

            </div>

            <a href="{{ route('admin.sports.equipment.create') }}"
               class="equipment-add-btn">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add Equipment
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>

        </div>

    </div>

    {{-- ================= SUCCESS MESSAGE ================= --}}

    @if(session('success'))
        <div class="equipment-alert" role="status">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ================= STATISTIC CARDS ================= --}}

    <div class="equipment-stats-grid">

        <div class="equipment-stat-card equipment-stat-blue">
            <div class="equipment-stat-top">
                <span class="equipment-stat-label">Total Equipment</span>
                <span class="equipment-stat-icon">
                    <i class="fas fa-boxes" aria-hidden="true"></i>
                </span>
            </div>

            <div class="equipment-stat-value">
                {{ $equipment->total() }}
            </div>

            <div class="equipment-stat-caption">
                Total equipment records maintained
            </div>
        </div>

        <div class="equipment-stat-card equipment-stat-green">
            <div class="equipment-stat-top">
                <span class="equipment-stat-label">Active Equipment</span>
                <span class="equipment-stat-icon">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                </span>
            </div>

            <div class="equipment-stat-value">
                {{ \App\Models\Sports\Equipment\Equipment::where('status', 'active')->count() }}
            </div>

            <div class="equipment-stat-caption">
                Equipment currently marked active
            </div>
        </div>

        <div class="equipment-stat-card equipment-stat-orange">
            <div class="equipment-stat-top">
                <span class="equipment-stat-label">Available Units</span>
                <span class="equipment-stat-icon">
                    <i class="fas fa-cubes" aria-hidden="true"></i>
                </span>
            </div>

            <div class="equipment-stat-value">
                {{ \App\Models\Sports\Equipment\Equipment::sum('available_quantity') }}
            </div>

            <div class="equipment-stat-caption">
                Units currently recorded as available
            </div>
        </div>

        <div class="equipment-stat-card equipment-stat-red">
            <div class="equipment-stat-top">
                <span class="equipment-stat-label">Damaged Equipment</span>
                <span class="equipment-stat-icon">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                </span>
            </div>

            <div class="equipment-stat-value">
                {{ \App\Models\Sports\Equipment\Equipment::where('condition', 'damaged')->count() }}
            </div>

            <div class="equipment-stat-caption">
                Equipment requiring attention
            </div>
        </div>

    </div>

    {{-- ================= SEARCH AND FILTERS ================= --}}

    <div class="equipment-filter-card">

        <div class="equipment-filter-header">

            <div class="equipment-filter-heading-wrap">

                <div class="equipment-panel-icon">
                    <i class="fas fa-filter" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="equipment-panel-heading">
                        Search &amp; Filter Equipment
                    </div>

                    <div class="equipment-panel-subtitle">
                        Find inventory by name, category, condition or status.
                    </div>
                </div>

            </div>

            <div class="equipment-filter-hint">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Inventory Filters
            </div>

        </div>

        <div class="equipment-filter-body">

            <form method="GET"
                  action="{{ route('admin.sports.equipment.index') }}">

                <div class="equipment-filter-grid">

                    {{-- SEARCH --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label" for="equipment-search">
                            Search Equipment
                        </label>

                        <div class="equipment-search-wrapper">

                            <i class="fas fa-search equipment-search-icon"
                               aria-hidden="true"></i>

                            <input
                                id="equipment-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="equipment-filter-control equipment-search-input"
                                placeholder="Search equipment, brand or model..."
                            >

                        </div>

                    </div>

                    {{-- CATEGORY --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label" for="equipment-category">
                            Category
                        </label>

                        <select
                            id="equipment-category"
                            name="category"
                            class="equipment-filter-control"
                        >
                            <option value="">All Categories</option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category }}"
                                    @selected(request('category') === $category)
                                >
                                    {{ $category }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- CONDITION --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label" for="equipment-condition">
                            Condition
                        </label>

                        <select
                            id="equipment-condition"
                            name="condition"
                            class="equipment-filter-control"
                        >
                            <option value="">All Conditions</option>

                            <option value="good"
                                @selected(request('condition') === 'good')>
                                Good
                            </option>

                            <option value="fair"
                                @selected(request('condition') === 'fair')>
                                Fair
                            </option>

                            <option value="damaged"
                                @selected(request('condition') === 'damaged')>
                                Damaged
                            </option>

                        </select>

                    </div>

                    {{-- STATUS --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label" for="equipment-status">
                            Status
                        </label>

                        <select
                            id="equipment-status"
                            name="status"
                            class="equipment-filter-control"
                        >
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

                    {{-- FILTER ACTIONS --}}

                    <div class="equipment-filter-actions">

                        <button type="submit" class="equipment-filter-btn">
                            <i class="fas fa-filter" aria-hidden="true"></i>
                            Apply
                        </button>

                        <a href="{{ route('admin.sports.equipment.index') }}"
                           class="equipment-reset-btn">
                            <i class="fas fa-rotate-left" aria-hidden="true"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ================= EQUIPMENT INVENTORY TABLE ================= --}}

    <div class="equipment-table-card">

        <div class="equipment-table-header">

            <div class="equipment-table-heading-wrap">

                <div class="equipment-panel-icon">
                    <i class="fas fa-dumbbell" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="equipment-panel-heading">
                        Equipment Inventory

                        <span class="equipment-table-count">
                            {{ $equipment->total() }}
                        </span>
                    </div>

                    <div class="equipment-panel-subtitle">
                        Equipment details, stock levels, condition and availability.
                    </div>
                </div>

            </div>

            <div class="equipment-panel-subtitle">
                Showing {{ $equipment->firstItem() ?? 0 }}
                – {{ $equipment->lastItem() ?? 0 }}
                of {{ $equipment->total() }} records
            </div>

        </div>

        @if($equipment->count())

            <div class="equipment-table-wrapper">

                <table class="equipment-table">

                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Condition</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($equipment as $item)

                            @php
                                $totalQuantity = (int) ($item->quantity ?? 0);
                                $availableQuantity = (int) ($item->available_quantity ?? 0);

                                $stockPercentage = $totalQuantity > 0
                                    ? ($availableQuantity / $totalQuantity) * 100
                                    : 0;

                                $stockPercentage = min(100, max(0, $stockPercentage));
                            @endphp

                            <tr>

                                {{-- EQUIPMENT DETAILS --}}

                                <td>
                                    <span class="equipment-name">
                                        {{ $item->equipment_name }}
                                    </span>

                                    @if($item->brand || $item->model)
                                        <span class="equipment-brand">
                                            @if($item->brand)
                                                {{ $item->brand }}
                                            @endif

                                            @if($item->brand && $item->model)
                                                ·
                                            @endif

                                            @if($item->model)
                                                {{ $item->model }}
                                            @endif
                                        </span>
                                    @endif
                                </td>

                                {{-- CATEGORY --}}

                                <td>
                                    <span class="equipment-category">
                                        {{ $item->category ?: '—' }}
                                    </span>
                                </td>

                                {{-- STOCK --}}

                                <td>
                                    <div class="equipment-stock">

                                        <div class="equipment-stock-top">
                                            <span class="equipment-stock-number">
                                                {{ $availableQuantity }}
                                                @if($item->unit)
                                                    {{ $item->unit }}
                                                @endif
                                            </span>

                                            <span class="equipment-stock-total">
                                                / {{ $totalQuantity }}
                                            </span>
                                        </div>

                                        <div
                                            class="equipment-stock-bar"
                                            role="progressbar"
                                            aria-valuenow="{{ (int) round($stockPercentage) }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            aria-label="Available stock percentage"
                                        >
                                            <div
                                                class="equipment-stock-progress"
                                                style="width: {{ $stockPercentage }}%;"
                                            ></div>
                                        </div>

                                    </div>
                                </td>

                                {{-- CONDITION --}}

                                <td>
                                    @if($item->condition === 'good')

                                        <span class="equipment-badge equipment-badge-good">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                            Good
                                        </span>

                                    @elseif($item->condition === 'fair')

                                        <span class="equipment-badge equipment-badge-fair">
                                            <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                            Fair
                                        </span>

                                    @elseif($item->condition === 'damaged')

                                        <span class="equipment-badge equipment-badge-damaged">
                                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                            Damaged
                                        </span>

                                    @else

                                        <span class="equipment-badge equipment-badge-inactive">
                                            —
                                        </span>

                                    @endif
                                </td>

                                {{-- LOCATION --}}

                                <td>
                                    <span class="equipment-location">
                                        {{ $item->location ?: '—' }}
                                    </span>
                                </td>

                                {{-- STATUS --}}

                                <td>
                                    @if($item->status === 'active')

                                        <span class="equipment-badge equipment-badge-active">
                                            <i class="fas fa-circle equipment-badge-dot"
                                               aria-hidden="true"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="equipment-badge equipment-badge-inactive">
                                            <i class="fas fa-circle equipment-badge-dot"
                                               aria-hidden="true"></i>
                                            Inactive
                                        </span>

                                    @endif
                                </td>

                                {{-- ACTIONS --}}

                                <td>
                                    <div class="equipment-actions">

                                        <a
                                            href="{{ route('admin.sports.equipment.show', $item->id) }}"
                                            class="equipment-action-btn equipment-action-view"
                                            title="View equipment"
                                            aria-label="View equipment"
                                        >
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.sports.equipment.edit', $item->id) }}"
                                            class="equipment-action-btn equipment-action-edit"
                                            title="Edit equipment"
                                            aria-label="Edit equipment"
                                        >
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.sports.equipment.destroy', $item->id) }}"
                                            method="POST"
                                            class="equipment-delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete this equipment?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="equipment-action-btn equipment-action-delete"
                                                title="Delete equipment"
                                                aria-label="Delete equipment"
                                            >
                                                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- ================= PAGINATION ================= --}}

            @if($equipment->hasPages())
                <div class="equipment-pagination">
                    {{ $equipment->withQueryString()->links() }}
                </div>
            @endif

        @else

            {{-- ================= EMPTY STATE ================= --}}

            <div class="equipment-empty-state">

                <div class="equipment-empty-icon">
                    <i class="fas fa-dumbbell" aria-hidden="true"></i>
                </div>

                <h3 class="equipment-empty-title">
                    No Equipment Found
                </h3>

                <p class="equipment-empty-text">
                    No equipment matches your current filters. Try changing
                    your search criteria or add a new equipment record.
                </p>

                @if(request()->filled('search') ||
                    request()->filled('category') ||
                    request()->filled('condition') ||
                    request()->filled('status'))

                    <a href="{{ route('admin.sports.equipment.index') }}"
                       class="equipment-empty-add-btn">
                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                        Reset Filters
                    </a>

                @else

                    <a href="{{ route('admin.sports.equipment.create') }}"
                       class="equipment-empty-add-btn">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Add First Equipment
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection
