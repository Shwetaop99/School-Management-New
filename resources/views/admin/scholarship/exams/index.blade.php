@extends('layouts.app')

@section('title', 'Scholarship Exam Records | Admin')
@section('page-title', 'Scholarship Exam Records')

@section('content')
<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Scholarship Examination Records
========================================================= */

.scholarship-page {
    --ink: #263b35;
    --ink-2: #20352c;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f4f0df;
    --wood: #a87543;
    --red: #bd5148;
    --blue: #2b5d8a;
    --green: #34775a;
    --teal: #527f79;

    min-height: calc(100vh - 70px);
    padding: 26px;
    color: var(--text);
    background:
        radial-gradient(circle at 0% 0%, rgba(168,117,67,.045), transparent 28%),
        radial-gradient(circle at 100% 0%, rgba(82,127,121,.055), transparent 26%),
        linear-gradient(180deg, #f7f6f0 0%, #f5f3e9 100%);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    font-size: 13px;
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

.scholarship-page button {
    font-family: inherit;
}

.scholarship-page a:focus-visible,
.scholarship-page button:focus-visible,
.scholarship-page input:focus-visible,
.scholarship-page select:focus-visible {
    outline: 3px solid rgba(82,127,121,.35);
    outline-offset: 3px;
}

/* ================= HERO HEADER ================= */

.scholarship-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, .72fr);
    overflow: hidden;
    margin-bottom: 25px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 13px;
    box-shadow: 0 7px 22px rgba(39,55,45,.055);
}

.scholarship-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 29px;
    width: 2px;
    background: rgba(189,81,72,.30);
    content: "";
}

.scholarship-hero-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
    padding: 30px 30px 30px 52px;
}

.scholarship-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 62px;
    height: 62px;
    flex: 0 0 62px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e6dfce;
    border-radius: 13px;
    font-size: 24px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.75);
}

.scholarship-hero-text {
    min-width: 0;
}

.scholarship-header-eyebrow {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 10px;
    color: var(--wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.25px;
    line-height: 1.5;
    text-transform: uppercase;
}

.scholarship-header-eyebrow i {
    font-size: 12px;
}

.scholarship-hero-text h2 {
    margin: 0;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 2.2vw, 32px);
    font-weight: 700;
    line-height: 1.16;
    letter-spacing: -.65px;
}

.scholarship-hero-text p {
    max-width: 610px;
    margin: 10px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.hero-mini-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 750;
}

.hero-mini-info i {
    font-size: 12px;
}

/* ================= RIGHT-SIDE HERO ================= */

.hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 17px;
    min-width: 0;
    padding: 28px 24px;
    color: #f6f2e5;
    background:
        radial-gradient(ellipse at 15% 0%, rgba(255,255,255,.09), transparent 58%),
        linear-gradient(145deg, #344f43 0%, #263b35 52%, #20352c 100%);
}

.hero-actions::before {
    position: absolute;
    top: -54px;
    left: -48px;
    width: 135px;
    height: 135px;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.hero-actions::after {
    position: absolute;
    right: -42px;
    bottom: -68px;
    width: 160px;
    height: 160px;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.scholarship-header-note {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 9px;
    width: 100%;
    max-width: 340px;
    text-align: center;
}

.scholarship-header-note > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    color: #f1d8a9;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.19);
    border-radius: 12px;
    font-size: 18px;
}

/* PLAYFAIR DISPLAY: INSPIRATIONAL QUOTE ONLY */
.scholarship-header-note strong {
    color: #fffefa;
    font-family:  var(--hand, cursive);
    font-size: 25px;
    font-weight: 600;
    letter-spacing: .2px;
    line-height: 1.35;
}

.scholarship-header-note p {
    max-width: 310px;
    margin: 0;
    color: #e0e6dc;
    font-size: 11px;
    line-height: 1.8;
}

.scholarship-rules-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 43px;
    max-width: 100%;
    padding: 0 17px;
    color: var(--ink);
    background: #f1ead8;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 8px;
    box-shadow: 0 5px 14px rgba(0,0,0,.12);
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.scholarship-rules-btn:hover {
    color: var(--ink);
    background: #fffefa;
    border-color: #fffefa;
    box-shadow: 0 8px 18px rgba(0,0,0,.16);
    transform: translateY(-2px);
}

.scholarship-rules-btn i:last-child {
    margin-left: 2px;
    transition: transform .18s ease;
}

.scholarship-rules-btn:hover i:last-child {
    transform: translateX(3px);
}

/* ================= SUCCESS ALERT ================= */

.success-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 22px;
    padding: 13px 16px;
    color: #2d7048;
    background: #f0f8ef;
    border: 1px solid #cfe3ca;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(39,55,45,.025);
}

.success-alert > i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    background: #dcefd7;
    border-radius: 50%;
}

/* ================= KPI CARDS ================= */

.scholarship-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 25px;
}

.scholarship-kpi {
    --kpi-color: var(--blue);

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    margin-top: 10px;
    padding: 23px 20px 18px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 11px;
    box-shadow: 0 5px 17px rgba(39,55,45,.045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.scholarship-kpi:hover {
    box-shadow: 0 12px 25px rgba(39,55,45,.095);
    transform: translateY(-4px);
}

.scholarship-kpi::before {
    position: absolute;
    top: -24px;
    left: 15px;
    padding: 4px 11px;
    color: #fff;
    background: var(--kpi-color);
    border-radius: 6px 6px 0 0;
    content: "";
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .25px;
}

.scholarship-kpi.blue { --kpi-color: var(--blue); }
.scholarship-kpi.green { --kpi-color: var(--green); }
.scholarship-kpi.orange { --kpi-color: var(--wood); }
.scholarship-kpi.red { --kpi-color: var(--red); }

.scholarship-kpi.blue::before { content: "Records"; }
.scholarship-kpi.green::before { content: "Applied"; }
.scholarship-kpi.orange::before { content: "Passed"; }
.scholarship-kpi.red::before { content: "Appeared"; }

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 750;
    letter-spacing: .1px;
    line-height: 1.5;
}

.kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    height: 41px;
    flex: 0 0 41px;
    color: var(--kpi-color);
    background: #f0f3f0;
    border: 1px solid rgba(38,59,53,.05);
    border-radius: 11px;
    font-size: 16px;
}

.scholarship-kpi.blue .kpi-icon { background: #edf3f8; }
.scholarship-kpi.green .kpi-icon { background: #edf6ee; }
.scholarship-kpi.orange .kpi-icon { background: #f8f0e3; }
.scholarship-kpi.red .kpi-icon { background: #fbefeb; }

.kpi-value {
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 34px;
    font-weight: 700;
    line-height: 1.05;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.kpi-caption {
    padding-top: 10px;
    color: var(--muted);
    border-top: 1px dashed #e4e1d6;
    font-size: 10px;
    font-weight: 600;
    line-height: 1.6;
}

/* ================= FILTER PANEL ================= */

.filter-panel {
    margin-bottom: 24px;
    padding: 20px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(39,55,45,.04);
}

.filter-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #dfdccf;
}

.filter-title-wrap {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.filter-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e6dfce;
    border-radius: 10px;
    font-size: 14px;
}

.filter-title {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
    line-height: 1.4;
}

.filter-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    color: #667269;
    background: #faf9f4;
    border: 1px solid var(--line);
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.filter-grid {
    display: grid;
    grid-template-columns: minmax(180px, 1.5fr) minmax(150px, 1fr) minmax(150px, 1fr) auto;
    align-items: end;
    gap: 13px;
}

.filter-group {
    min-width: 0;
}

.filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 10px;
    font-weight: 800;
}

.filter-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 43px;
    padding: 0 12px;
    color: var(--text);
    background: #fffefa;
    border: 1px solid #dcded3;
    border-radius: 8px;
    outline: none;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.filter-control:hover {
    border-color: #c8cfbf;
}

.filter-control:focus {
    border-color: #6e9a86;
    box-shadow: 0 0 0 3px rgba(58,119,90,.11);
}

.filter-control::placeholder {
    color: #9aa095;
}

.filter-search {
    position: relative;
}

.filter-search > i {
    position: absolute;
    top: 16px;
    left: 12px;
    z-index: 1;
    color: #929b8f;
    font-size: 11px;
    pointer-events: none;
}

.filter-search input {
    padding-left: 34px;
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.apply-btn,
.reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 43px;
    padding: 0 15px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease,
        color .18s ease, transform .18s ease, box-shadow .18s ease;
}

.apply-btn {
    color: #fff;
    background: var(--ink);
    border: 1px solid var(--ink);
    box-shadow: 0 4px 10px rgba(38,59,53,.11);
}

.apply-btn:hover {
    color: #fff;
    background: var(--ink-hover);
    border-color: var(--ink-hover);
    box-shadow: 0 6px 13px rgba(38,59,53,.15);
    transform: translateY(-1px);
}

.reset-btn {
    color: var(--ink);
    background: #fffefa;
    border: 1px solid #d7d6ca;
}

.reset-btn:hover {
    color: var(--ink);
    background: var(--chalk);
    border-color: #c7c5b7;
}

/* ================= TABLE PANEL ================= */

.table-panel {
    overflow: hidden;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(39,55,45,.045);
}

.table-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 20px;
    background: #fffefa;
    border-bottom: 1px dashed #d8d5c8;
}

.table-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.table-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e6dfce;
    border-radius: 10px;
    font-size: 15px;
}

.table-panel-title {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
    line-height: 1.6;
}

.table-panel-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.table-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 27px;
    height: 23px;
    margin-left: 5px;
    padding: 0 8px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e6dfce;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 850;
    vertical-align: 1px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.scholarship-table {
    width: 100%;
    min-width: 1150px;
    border-collapse: separate;
    border-spacing: 0;
}

.scholarship-table th {
    padding: 13px;
    color: #68736a;
    background: #f5f3e9;
    border-bottom: 1px solid #e5e1d5;
    font-size: 9px;
    font-weight: 850;
    letter-spacing: .55px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.scholarship-table td {
    padding: 14px 13px;
    color: var(--text);
    border-bottom: 1px solid #eeece3;
    font-size: 12px;
    vertical-align: middle;
}

.scholarship-table tbody tr {
    transition: background .18s ease;
}

.scholarship-table tbody tr:hover {
    background: #faf9f3;
}

.scholarship-table tbody tr:last-child td {
    border-bottom: 0;
}

.serial-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    padding: 0 6px;
    color: #657168;
    background: #f4f2e9;
    border: 1px solid #e8e4d8;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

.exam-name {
    display: block;
    max-width: 220px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    line-height: 1.55;
    overflow-wrap: anywhere;
}

.exam-type {
    display: inline-block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 10px;
}

.exam-organizer {
    display: block;
    margin-top: 5px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.5;
}

.exam-organizer i {
    margin-right: 3px;
}

.year-text {
    color: #5d6a60;
    font-weight: 750;
    white-space: nowrap;
}

.class-list {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    max-width: 165px;
}

.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    color: var(--blue);
    background: #f0f5f8;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.class-more {
    display: inline-flex;
    align-items: center;
    padding: 5px 7px;
    color: #667269;
    background: #f0f0e8;
    border: 1px solid #e3e2d7;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
}

.date-text {
    color: #5d6a60;
    font-size: 11px;
    white-space: nowrap;
}

.number-cell {
    color: var(--ink);
    font-size: 13px;
    font-weight: 850;
    font-variant-numeric: tabular-nums;
}

.pass-cell {
    min-width: 95px;
}

.percentage-wrapper {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.percentage-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 850;
    white-space: nowrap;
}

.percentage-bar {
    width: 65px;
    height: 5px;
    overflow: hidden;
    background: #e7e9df;
    border-radius: 10px;
}

.percentage-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--teal), #86a99b);
    border-radius: inherit;
}

.scholarship-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 850;
    white-space: nowrap;
}

.scholarship-badge > i {
    font-size: 6px;
}

.scholarship-badge.completed {
    color: #347849;
    background: #edf7ed;
    border-color: #cfe5d3;
}

.scholarship-badge.active {
    color: #8b641d;
    background: #faf5e7;
    border-color: #e6dfc9;
}

.scholarship-badge.upcoming {
    color: var(--blue);
    background: #f0f5f8;
    border-color: #d7e2e9;
}

.scholarship-badge.inactive {
    color: #77766e;
    background: #f0efe9;
    border-color: #e1dfd5;
}

.scholarship-badge.default {
    color: #63523f;
    background: #f5eee1;
    border-color: #e9ddc8;
}

/* ================= ACTION BUTTONS ================= */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    padding: 0;
    color: #68746a;
    background: #fffefa;
    border: 1px solid #dedfd4;
    border-radius: 8px;
    font-size: 11px;
    line-height: 1;
    text-decoration: none;
    cursor: pointer;
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease;
}

.action-btn:hover {
    color: var(--ink);
    background: #f2f1e8;
    border-color: #c9cdbf;
    transform: translateY(-1px);
}

.action-btn.edit:hover {
    color: #986a23;
    background: #fcf7e9;
    border-color: #e6d0a8;
}

.action-btn.delete {
    color: var(--red);
}

.action-btn.delete:hover {
    color: #fff;
    background: var(--red);
    border-color: var(--red);
}

.delete-form {
    display: inline-flex;
    margin: 0;
}

.muted-text {
    color: #929a8e;
    font-size: 11px;
}

/* ================= EMPTY STATE ================= */

.empty-state {
    padding: 62px 20px;
    text-align: center;
}

.empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 17px;
    color: var(--ink);
    background: var(--chalk);
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    font-size: 26px;
}

.empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
}

.empty-state p {
    max-width: 450px;
    margin: 0 auto 20px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.8;
}

.empty-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 41px;
    padding: 0 16px;
    color: #fff;
    background: var(--ink);
    border: 1px solid var(--ink);
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(38,59,53,.1);
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    transition: background .18s ease, transform .18s ease;
}

.empty-add-btn:hover {
    color: #fff;
    background: var(--ink-hover);
    border-color: var(--ink-hover);
    transform: translateY(-1px);
}

/* ================= PAGINATION ================= */

.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 15px 19px;
    background: #fffefa;
    border-top: 1px dashed #d8d5c8;
}

.pagination-info {
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.scholarship-page .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.scholarship-page .page-link {
    color: var(--ink);
    background: #fffefa;
    border-color: var(--line);
    border-radius: 6px;
    font-size: 11px;
    box-shadow: none;
}

.scholarship-page .page-link:hover {
    color: var(--ink);
    background: var(--chalk);
    border-color: #d7d1bd;
}

.scholarship-page .page-item.active .page-link {
    color: #fff;
    background: var(--ink);
    border-color: var(--ink);
}

.scholarship-page .page-item.disabled .page-link {
    color: #a3a69d;
    background: #f5f3ec;
    border-color: #e5e1d5;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 1250px) {
    .scholarship-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-actions {
        grid-column: span 2;
    }

    .filter-actions .apply-btn,
    .filter-actions .reset-btn {
        min-width: 100px;
    }
}

@media (max-width: 900px) {
    .scholarship-page {
        padding: 18px;
    }

    .scholarship-hero {
        grid-template-columns: 1fr;
    }

    .hero-actions {
        min-height: 235px;
        padding: 25px 22px;
    }

    .scholarship-header-note {
        gap: 9px;
    }

    .scholarship-header-note strong {
        font-size: 21px;
    }

    .scholarship-header-note p {
        max-width: 420px;
    }

    .scholarship-rules-btn {
        min-height: 42px;
    }
}

@media (max-width: 650px) {
    .scholarship-page {
        padding: 12px;
    }

    .scholarship-hero {
        margin-bottom: 20px;
        border-radius: 11px;
    }

    .scholarship-hero::before {
        left: 19px;
    }

    .scholarship-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 34px;
    }

    .scholarship-hero-icon {
        width: 44px;
        height: 44px;
        flex-basis: 44px;
        border-radius: 10px;
        font-size: 18px;
    }

    .scholarship-header-eyebrow {
        gap: 5px;
        margin-bottom: 8px;
        font-size: 9px;
        letter-spacing: .65px;
    }

    .scholarship-hero-text h2 {
        font-size: 23px;
        line-height: 1.22;
    }

    .scholarship-hero-text p {
        margin-top: 8px;
        font-size: 11px;
        line-height: 1.75;
    }

    .hero-mini-info {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.6;
    }

    .hero-actions {
        min-height: auto;
        gap: 15px;
        padding: 23px 16px;
    }

    .scholarship-header-note {
        gap: 9px;
    }

    .scholarship-header-note > i {
        width: 40px;
        height: 40px;
    }

    .scholarship-header-note strong {
        font-size: 20px;
    }

    .scholarship-header-note p {
        font-size: 11px;
    }

    .scholarship-rules-btn {
        width: 100%;
        white-space: normal;
    }

    .scholarship-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .scholarship-kpi {
        gap: 11px;
        padding: 22px 17px 16px;
    }

    .kpi-value {
        font-size: 31px;
    }

    .filter-panel {
        margin-bottom: 19px;
        padding: 14px;
        border-radius: 10px;
    }

    .filter-heading {
        align-items: flex-start;
        margin-bottom: 15px;
        padding-bottom: 13px;
    }

    .filter-title-wrap {
        gap: 9px;
    }

    .filter-title-icon {
        width: 33px;
        height: 33px;
        flex-basis: 33px;
    }

    .filter-title {
        font-size: 12px;
    }

    .filter-subtitle {
        font-size: 10px;
    }

    .filter-badge {
        display: none;
    }

    .filter-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 13px;
    }

    .filter-actions {
        grid-column: auto;
        width: 100%;
    }

    .filter-actions .apply-btn,
    .filter-actions .reset-btn {
        flex: 1;
        min-width: 0;
    }

    .table-panel {
        border-radius: 10px;
    }

    .table-panel-header {
        align-items: flex-start;
        padding: 14px;
    }

    .table-panel-title-wrap {
        gap: 9px;
    }

    .table-panel-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
        font-size: 13px;
    }

    .table-panel-title {
        font-size: 12px;
    }

    .table-panel-subtitle {
        font-size: 10px;
    }

    .table-count {
        height: 21px;
        padding: 0 6px;
    }

    .scholarship-table {
        min-width: 1100px;
    }

    .scholarship-table th {
        padding: 11px;
    }

    .scholarship-table td {
        padding: 12px 11px;
    }

    .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
        padding: 14px;
    }

    .pagination-wrapper > div:last-child {
        max-width: 100%;
        overflow-x: auto;
    }

    .empty-state {
        padding: 43px 16px;
    }

    .empty-icon {
        width: 62px;
        height: 62px;
        font-size: 23px;
    }

    .empty-state h4 {
        font-size: 18px;
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

{{-- Load Playfair Display for the inspirational hero quote only --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap"
      rel="stylesheet">

<div class="scholarship-page">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="success-alert" role="status">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ================= HERO HEADER ================= --}}
    <section class="scholarship-hero">

        <div class="scholarship-hero-content">
            <div class="scholarship-hero-icon">
                <i class="fas fa-award" aria-hidden="true"></i>
            </div>

            <div class="scholarship-hero-text">
                <div class="scholarship-header-eyebrow">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                    STUDENT SERVICES / SCHOLARSHIP
                </div>

                <h2>Scholarship Exam Records</h2>

                <p>
                    Maintain scholarship examination history, monitor student
                    participation, and review results across academic years.
                </p>

                <div class="hero-mini-info">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <span>Examinations &bull; Participation &bull; Results</span>
                </div>
            </div>
        </div>

        {{-- Right-side hero content --}}
        <div class="hero-actions">
            <div class="scholarship-header-note">
                <i class="fas fa-book-open" aria-hidden="true"></i>

                <strong>Learn. Achieve. Excel.</strong>

                <p>
                    Track every scholarship examination and celebrate the
                    dedication behind each student's academic achievement.
                </p>
            </div>

            <a href="{{ route('admin.scholarship.exams.create') }}"
               class="scholarship-rules-btn">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add Exam Record
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

    </section>

    {{-- ================= KPI CARDS ================= --}}
    <section class="scholarship-kpis" aria-label="Examination statistics">

        <article class="scholarship-kpi blue">
            <div class="kpi-top">
                <span class="kpi-label">Total Examinations</span>
                <span class="kpi-icon">
                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ $statistics['total_exams'] ?? 0 }}
            </div>

            <div class="kpi-caption">
                Total examination records maintained
            </div>
        </article>

        <article class="scholarship-kpi green">
            <div class="kpi-top">
                <span class="kpi-label">Students Applied</span>
                <span class="kpi-icon">
                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ $statistics['total_applied'] ?? 0 }}
            </div>

            <div class="kpi-caption">
                Total student applications recorded
            </div>
        </article>

        <article class="scholarship-kpi orange">
            <div class="kpi-top">
                <span class="kpi-label">Students Passed</span>
                <span class="kpi-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ $statistics['total_passed'] ?? 0 }}
            </div>

            <div class="kpi-caption">
                Successful examination candidates
            </div>
        </article>

        <article class="scholarship-kpi red">
            <div class="kpi-top">
                <span class="kpi-label">Students Appeared</span>
                <span class="kpi-icon">
                    <i class="fas fa-user-check" aria-hidden="true"></i>
                </span>
            </div>

            <div class="kpi-value">
                {{ $statistics['total_appeared'] ?? 0 }}
            </div>

            <div class="kpi-caption">
                Total examination participation
            </div>
        </article>

    </section>

    {{-- ================= SEARCH AND FILTERS ================= --}}
    <section class="filter-panel">

        <div class="filter-heading">
            <div class="filter-title-wrap">
                <div class="filter-title-icon">
                    <i class="fas fa-filter" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="filter-title">
                        Search &amp; Filter Examination Records
                    </div>

                    <div class="filter-subtitle">
                        Find examinations by name, academic year, or status.
                    </div>
                </div>
            </div>

            <div class="filter-badge">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Examination Filters
            </div>
        </div>

        <form method="GET"
              action="{{ route('admin.scholarship.exams.index') }}">

            <div class="filter-grid">

                <div class="filter-group">
                    <label for="exam-search">Search Examination</label>

                    <div class="filter-search">
                        <i class="fas fa-search" aria-hidden="true"></i>

                        <input
                            id="exam-search"
                            type="text"
                            name="search"
                            class="filter-control"
                            placeholder="Enter examination name..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="filter-group">
                    <label for="academic-year">Academic Year</label>

                    <select
                        id="academic-year"
                        name="academic_year"
                        class="filter-control"
                    >
                        <option value="">All Academic Years</option>

                        @foreach($academicYears as $year)
                            <option
                                value="{{ $year }}"
                                @selected(request('academic_year') == $year)
                            >
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="exam-status">Examination Status</label>

                    <select
                        id="exam-status"
                        name="status"
                        class="filter-control"
                    >
                        <option value="">All Statuses</option>

                        <option value="upcoming"
                            @selected(request('status') == 'upcoming')>
                            Upcoming
                        </option>

                        <option value="active"
                            @selected(request('status') == 'active')>
                            Active
                        </option>

                        <option value="completed"
                            @selected(request('status') == 'completed')>
                            Completed
                        </option>

                        <option value="inactive"
                            @selected(request('status') == 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="apply-btn">
                        <i class="fas fa-filter" aria-hidden="true"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.scholarship.exams.index') }}"
                       class="reset-btn">
                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                        Reset
                    </a>
                </div>

            </div>
        </form>

    </section>

    {{-- ================= EXAMINATION TABLE ================= --}}
    <section class="table-panel">

        <div class="table-panel-header">
            <div class="table-panel-title-wrap">
                <div class="table-panel-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </div>

                <div>
                    <div class="table-panel-title">
                        Scholarship Examination Register

                        <span class="table-count">
                            {{ $exams->total() }}
                        </span>
                    </div>

                    <div class="table-panel-subtitle">
                        Examination details, student participation, and results.
                    </div>
                </div>
            </div>
        </div>

        @if($exams->count())

            <div class="table-responsive">
                <table class="scholarship-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Examination</th>
                            <th>Academic Year</th>
                            <th>Exam Date</th>
                            <th>Classes</th>
                            <th>Applied</th>
                            <th>Appeared</th>
                            <th>Passed</th>
                            <th>Pass Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($exams as $exam)

                            @php
                                $examStatus = strtolower(
                                    (string) ($exam->status ?? '')
                                );

                                $statusClass = match ($examStatus) {
                                    'completed' => 'completed',
                                    'active' => 'active',
                                    'upcoming' => 'upcoming',
                                    'inactive' => 'inactive',
                                    default => 'default',
                                };

                                $passPercentage = max(
                                    0,
                                    min(
                                        100,
                                        (float) ($exam->pass_percentage ?? 0)
                                    )
                                );
                            @endphp

                            <tr>
                                <td>
                                    <span class="serial-number">
                                        {{ $exams->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <span class="exam-name">
                                        {{ $exam->exam_name }}
                                    </span>

                                    @if($exam->exam_type)
                                        <span class="exam-type">
                                            {{ $exam->exam_type }}
                                        </span>
                                    @endif

                                    @if($exam->conducted_by)
                                        <span class="exam-organizer">
                                            <i class="fas fa-building"
                                               aria-hidden="true"></i>
                                            {{ $exam->conducted_by }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="year-text">
                                        {{ $exam->academic_year ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="date-text">
                                        @if($exam->exam_date)
                                            {{ \Illuminate\Support\Carbon::parse($exam->exam_date)->format('d M Y') }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <div class="class-list">
                                        @forelse($exam->classes->take(3) as $class)
                                            <span class="class-badge">
                                                <i class="fas fa-users"
                                                   aria-hidden="true"></i>

                                                {{ $class->class_name }}{{ $class->section ? '-'.$class->section : '' }}
                                            </span>
                                        @empty
                                            <span class="muted-text">
                                                Not assigned
                                            </span>
                                        @endforelse

                                        @if($exam->classes->count() > 3)
                                            <span class="class-more">
                                                +{{ $exam->classes->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <span class="number-cell">
                                        {{ $exam->total_applied ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="number-cell">
                                        {{ $exam->total_appeared ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="number-cell">
                                        {{ $exam->total_passed ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <div class="pass-cell">
                                        <div class="percentage-wrapper">
                                            <span class="percentage-value">
                                                {{ number_format($passPercentage, 1) }}%
                                            </span>

                                            <div class="percentage-bar">
                                                <div
                                                    class="percentage-fill"
                                                    style="width: {{ $passPercentage }}%;"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="scholarship-badge {{ $statusClass }}">
                                        <i class="fas fa-circle"
                                           aria-hidden="true"></i>

                                        {{ ucfirst($examStatus ?: 'Unknown') }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.scholarship.exams.show', $exam->id) }}"
                                            class="action-btn"
                                            title="View Examination"
                                            aria-label="View Examination"
                                        >
                                            <i class="fas fa-eye"
                                               aria-hidden="true"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.scholarship.exams.edit', $exam->id) }}"
                                            class="action-btn edit"
                                            title="Edit Examination"
                                            aria-label="Edit Examination"
                                        >
                                            <i class="fas fa-pen"
                                               aria-hidden="true"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.scholarship.exams.destroy', $exam->id) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete this examination record?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Delete Examination"
                                                aria-label="Delete Examination"
                                            >
                                                <i class="fas fa-trash-alt"
                                                   aria-hidden="true"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            @if($exams->hasPages())
                <div class="pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $exams->firstItem() }}</strong>
                        to
                        <strong>{{ $exams->lastItem() }}</strong>
                        of
                        <strong>{{ $exams->total() }}</strong>
                        records
                    </div>

                    <div>
                        {{ $exams->withQueryString()->links() }}
                    </div>

                </div>
            @endif

        @else

            {{-- EMPTY STATE --}}
            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fas fa-award" aria-hidden="true"></i>
                </div>

                <h4>No Examination Records Found</h4>

                <p>
                    No scholarship examination records match your current
                    filters. Change your search criteria or add a new
                    examination record to get started.
                </p>

                @if(request()->filled('search') ||
                    request()->filled('academic_year') ||
                    request()->filled('status'))

                    <a
                        href="{{ route('admin.scholarship.exams.index') }}"
                        class="empty-add-btn"
                    >
                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                        Reset Filters
                    </a>

                @else

                    <a
                        href="{{ route('admin.scholarship.exams.create') }}"
                        class="empty-add-btn"
                    >
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        Add First Exam Record
                    </a>

                @endif

            </div>

        @endif

    </section>

</div>
@endsection
