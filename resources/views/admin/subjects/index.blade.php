@extends('layouts.app')

@section('title', 'Subjects | Admin')

@section('page-title', 'Subjects')

@section('content')

<style>

/* =========================================================
   SUBJECT PAGE — TRANSPORT RECORDS STYLE
========================================================= */

.subject-page {
    width: 100%;
    min-height: calc(100vh - 60px);
    padding: 26px 28px 40px;
    background: #f4f7fb;
    color: #172033;
}

/* =========================================================
   HERO
========================================================= */

.subject-hero {
    position: relative;
    overflow: hidden;
    min-height: 165px;
    margin-bottom: 22px;
    padding: 28px 32px;
    border-radius: 18px;
    background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
    color: #fff;
    box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.subject-hero::before {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    right: -65px;
    top: -130px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .10);
}

.subject-hero::after {
    content: "";
    position: absolute;
    width: 145px;
    height: 145px;
    right: 120px;
    bottom: -100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .10);
}

.subject-hero-content {
    position: relative;
    z-index: 3;
}

.subject-hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 9px;
    color: rgba(255, 255, 255, .78);
    font-size: 11px;
    font-weight: 600;
}

.subject-hero-breadcrumb i {
    font-size: 9px;
}

.subject-hero-content h1 {
    margin: 0 0 7px;
    font-size: 29px;
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -.3px;
}

.subject-hero-content p {
    margin: 0;
    max-width: 650px;
    color: rgba(255, 255, 255, .91);
    font-size: 13px;
    line-height: 1.6;
}

.subject-hero-actions {
    position: relative;
    z-index: 4;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-right: 18px;
}

.hero-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 17px;
    border: 1px solid rgba(255, 255, 255, .22);
    border-radius: 11px;
    background: rgba(255, 255, 255, .14);
    backdrop-filter: blur(6px);
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(0, 0, 0, .08);
    transition: .2s ease;
    white-space: nowrap;
}

.hero-add-btn:hover {
    background: rgba(255, 255, 255, .22);
    color: #fff;
    transform: translateY(-1px);
}

.subject-hero-icon-wrap {
    position: relative;
    z-index: 3;
    width: 105px;
    height: 105px;
    margin-right: 30px;
    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 28px;
    background: rgba(255, 255, 255, .10);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.subject-hero-icon-wrap i {
    font-size: 53px;
    color: rgba(255, 255, 255, .88);
}

/* =========================================================
   SUCCESS ALERT
========================================================= */

.subject-alert {
    margin-bottom: 18px;
    padding: 12px 15px;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    background: #f0fdf4;
    color: #15803d;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 650;
}

.subject-alert i {
    font-size: 14px;
}

/* =========================================================
   SUMMARY CARDS
========================================================= */

.subject-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 22px;
}

.summary-card {
    position: relative;
    overflow: hidden;
    min-height: 125px;
    padding: 19px 20px;
    border-radius: 15px;
    color: #fff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .11);
    transition: .25s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 28px rgba(15, 23, 42, .16);
}

.summary-card::before {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: -38px;
    top: -50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .10);
}

.summary-card.blue {
    background: linear-gradient(135deg, #1769d1, #237de0);
}

.summary-card.green {
    background: linear-gradient(135deg, #16a34a, #22c55e);
}

.summary-card.orange {
    background: linear-gradient(135deg, #ed9208, #f7aa25);
}

.summary-card.cyan {
    background: linear-gradient(135deg, #079dbd, #16b5d0);
}

.summary-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.summary-label {
    margin-bottom: 7px;
    color: rgba(255, 255, 255, .88);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.summary-number {
    margin: 0;
    font-size: 28px;
    line-height: 1;
    font-weight: 800;
}

.summary-icon {
    position: relative;
    z-index: 2;
    width: 39px;
    height: 39px;
    border-radius: 11px;
    background: rgba(255, 255, 255, .15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.summary-footer {
    position: relative;
    z-index: 2;
    margin-top: 14px;
    color: rgba(255, 255, 255, .82);
    font-size: 11px;
}

/* =========================================================
   FILTER / MAIN CONTROL SECTION
========================================================= */

.subject-filter-section {
    margin-bottom: 22px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
}

.subject-filter-header {
    padding: 20px 22px 18px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-heading-icon {
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #dbeafe;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.filter-heading h2 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.filter-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.filter-content {
    padding: 16px 22px;
    background: #fbfcfe;
}

.subject-filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-group {
    position: relative;
    flex: 1;
    min-width: 0;
}

.filter-label {
    position: absolute;
    left: 14px;
    top: -7px;
    z-index: 2;
    padding: 0 5px;
    background: #fbfcfe;
    color: #64748b;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .35px;
}

.class-select {
    width: 100%;
    height: 42px;
    padding: 0 13px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    outline: none;
    background: #fff;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s ease;
}

.class-select:hover {
    border-color: #cbd5e1;
}

.class-select:focus {
    border-color: #8bb8ef;
    box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 42px;
    padding: 0 16px;
    border: 0;
    border-radius: 11px;
    background: #1769d1;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 5px 13px rgba(23, 105, 209, .20);
    transition: .2s ease;
    white-space: nowrap;
    cursor: pointer;
}

.filter-btn:hover {
    background: #1268ca;
    transform: translateY(-1px);
}

.clear-filter-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 42px;
    padding: 0 13px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    transition: .2s ease;
    white-space: nowrap;
}

.clear-filter-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1769d1;
}

/* =========================================================
   CLASS DIRECTORY
========================================================= */

.directory-section {
    margin-bottom: 22px;
}

.directory-header {
    padding: 20px 22px 18px;
    margin-bottom: 0;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-bottom: 0;
    border-radius: 16px 16px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.directory-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.directory-title-icon {
    width: 43px;
    height: 43px;
    border-radius: 12px;
    background: #dbeafe;
    color: #1769d1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.directory-title h3 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.directory-title p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.directory-count {
    padding: 7px 12px;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.class-groups {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    padding: 18px;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 0 0 16px 16px;
}

/* =========================================================
   CLASS CARDS
========================================================= */

.class-group-card {
    position: relative;
    background: #fff;
    border: 1px solid #e3eaf3;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(25, 55, 95, .045);
    transition: all .22s ease;
}

.class-group-card:hover {
    transform: translateY(-3px);
    border-color: #c9ddf5;
    box-shadow: 0 12px 27px rgba(25, 55, 95, .10);
}

.class-card-accent {
    height: 4px;
    width: 100%;
    background: linear-gradient(
        90deg,
        #147cf5 0%,
        #2188f7 45%,
        #2bcfe8 100%
    );
}

.class-group-body {
    padding: 18px 18px 16px;
}

.class-group-main {
    display: flex;
    align-items: center;
    gap: 14px;
}

.class-number-box {
    position: relative;
    width: 62px;
    height: 62px;
    flex-shrink: 0;
    border-radius: 16px;
    background: linear-gradient(
        145deg,
        #147cf5 0%,
        #1268ca 65%,
        #0e58b0 100%
    );
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 9px 20px rgba(20, 124, 245, .23),
        inset 0 1px 0 rgba(255, 255, 255, .20);
    overflow: hidden;
    transition: all .25s ease;
}

.class-group-card:hover .class-number-box {
    transform: scale(1.04);
    box-shadow:
        0 11px 24px rgba(20, 124, 245, .28),
        inset 0 1px 0 rgba(255, 255, 255, .25);
}

.class-number-box::before {
    content: "";
    position: absolute;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    top: -42px;
    right: -25px;
    background: rgba(255, 255, 255, .13);
}

.class-number-box::after {
    content: "";
    position: absolute;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    bottom: -28px;
    left: -18px;
    background: rgba(255, 255, 255, .08);
}

.class-icon {
    position: relative;
    z-index: 3;
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: rgba(255, 255, 255, .16);
    border: 1px solid rgba(255, 255, 255, .20);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    box-shadow: 0 3px 9px rgba(0, 0, 0, .08);
}

.class-group-info {
    min-width: 0;
    flex: 1;
}

.class-group-info h3 {
    margin: 0 0 8px;
    color: #172033;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.3;
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
    padding: 0 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
}

.class-section-tag {
    background: #f3efff;
    color: #6941b5;
    border: 1px solid #e8ddff;
}

.class-year-tag {
    background: #f4f7fb;
    color: #64748b;
    border: 1px solid #e8edf4;
}

.class-subject-summary {
    margin-top: 18px;
    padding: 12px 13px;
    background: linear-gradient(135deg, #f8fbff, #f6f9fc);
    border: 1px solid #e8eef5;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.class-subject-summary-label {
    color: #718096;
    font-size: 11px;
    font-weight: 600;
}

.class-subject-summary-count {
    color: #147cf5;
    font-size: 13px;
    font-weight: 700;
}

.class-group-footer {
    border-top: 1px solid #edf1f6;
    padding: 11px 18px;
    background: #fafcff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.class-subject-label {
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
}

.class-subject-label strong {
    color: #172033;
}

.class-view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 32px;
    padding: 0 12px;
    border-radius: 7px;
    background: #edf5ff;
    border: 1px solid #dbeaff;
    color: #1769d1;
    text-decoration: none;
    font-size: 10px;
    font-weight: 700;
    transition: all .18s ease;
}

.class-view-btn:hover {
    background: #1769d1;
    border-color: #1769d1;
    color: #fff;
    transform: translateX(1px);
}

.class-view-arrow {
    font-size: 13px;
    line-height: 1;
    transition: transform .18s ease;
}

.class-view-btn:hover .class-view-arrow {
    transform: translateX(3px);
}

/* =========================================================
   SUBJECT TABLE SECTION
========================================================= */

.subject-table-section {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
}

.subject-table-header {
    padding: 20px 22px 18px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.subject-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.subject-section-heading-icon {
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #dbeafe;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.subject-section-heading h2 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.subject-section-heading p {
    margin: 0;
    color: #94a3b8;
    font-size: 11px;
}

.subject-count {
    padding: 7px 12px;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

/* =========================================================
   BACK TO CLASSES
========================================================= */

.subject-back-row {
    margin-bottom: 14px;
}

.back-to-classes {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 34px;
    padding: 0 12px;
    border-radius: 8px;
    color: #64748b;
    background: #fff;
    border: 1px solid #e3eaf3;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 2px 7px rgba(25, 55, 95, .035);
    transition: all .18s ease;
}

.back-to-classes:hover {
    color: #147cf5;
    background: #f7fbff;
    border-color: #cfe1f8;
    transform: translateX(-2px);
}

.back-arrow {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: #eef5ff;
    color: #147cf5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    line-height: 1;
}

/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.subject-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.subject-table thead th {
    padding: 13px 17px;
    background: #f8fafc;
    border-bottom: 1px solid #e5ebf3;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
    text-align: left;
}

.subject-table tbody td {
    padding: 14px 17px;
    border-bottom: 1px solid #edf1f6;
    color: #475569;
    font-size: 12px;
    vertical-align: middle;
}

.subject-table tbody tr {
    transition: background .18s ease;
}

.subject-table tbody tr:hover {
    background: #f8fbff;
}

.subject-table tbody tr:last-child td {
    border-bottom: 0;
}

/* =========================================================
   TABLE NUMBER
========================================================= */

.row-number {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f4f7fb;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
}

/* =========================================================
   SUBJECT NAME
========================================================= */

.subject-name-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 180px;
}

.subject-icon {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #eef5ff;
    color: #1769d1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}

.subject-name {
    color: #172033;
    font-size: 12px;
    font-weight: 750;
}

/* =========================================================
   SUBJECT CODE
========================================================= */

.subject-code {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 6px;
    background: #f5f7fa;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
    border: 1px solid #edf1f5;
}

/* =========================================================
   CLASS
========================================================= */

.class-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 20px;
    background: #edf5ff;
    color: #1769d1;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

/* =========================================================
   SECTION
========================================================= */

.section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 29px;
    height: 27px;
    padding: 0 8px;
    border-radius: 7px;
    background: #f4efff;
    color: #6f42c1;
    font-size: 10px;
    font-weight: 800;
}

/* =========================================================
   ACADEMIC YEAR
========================================================= */

.academic-year {
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

.status-active {
    background: #dcfce7;
    color: #16a34a;
}

.status-active .status-dot {
    background: #16a34a;
}

.status-inactive {
    background: #fee2e2;
    color: #dc2626;
}

.status-inactive .status-dot {
    background: #dc2626;
}

/* =========================================================
   ACTION BUTTONS
========================================================= */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 5px;
}

.action-buttons form {
    margin: 0;
    padding: 0;
}

.action-btn {
    width: 31px;
    height: 31px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 11px;
    transition: .2s ease;
    cursor: pointer;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-view {
    color: #1769d1;
}

.action-view:hover {
    background: #edf5ff;
    border-color: #bfdbfe;
    color: #1769d1;
}

.action-edit {
    color: #ea580c;
}

.action-edit:hover {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #ea580c;
}

.action-delete {
    color: #dc2626;
}

.action-delete:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 75px 25px 80px;
    text-align: center;
}

.empty-icon-wrapper {
    position: relative;
    width: 82px;
    height: 82px;
    margin: 0 auto 19px;
}

.empty-icon-bg {
    position: absolute;
    inset: 0;
    border-radius: 23px;
    background: linear-gradient(135deg, #dbeafe, #e0f2fe);
    transform: rotate(6deg);
}

.empty-state-icon {
    position: relative;
    width: 82px;
    height: 82px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #dbeafe;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    box-shadow: 0 8px 20px rgba(23, 105, 209, .10);
}

.empty-state h4 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 18px;
    font-weight: 800;
}

.empty-state p {
    max-width: 470px;
    margin: 0 auto 21px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
}

.empty-add-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 0 17px;
    border: 0;
    border-radius: 11px;
    background: #1769d1;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 5px 13px rgba(23, 105, 209, .20);
    transition: .2s ease;
}

.empty-add-btn:hover {
    background: #1268ca;
    color: #fff;
    transform: translateY(-1px);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .subject-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .class-groups {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .subject-hero-icon-wrap {
        margin-right: 10px;
    }
}

@media (max-width: 1000px) {

    .subject-filter-form {
        align-items: stretch;
        flex-direction: column;
    }

    .filter-group {
        width: 100%;
    }

    .filter-btn,
    .clear-filter-btn {
        width: 100%;
    }
}

@media (max-width: 850px) {

    .subject-page {
        padding: 18px;
    }

    .subject-hero {
        padding: 24px;
    }

    .subject-hero-actions {
        margin-right: 0;
    }

    .subject-hero-icon-wrap {
        display: none;
    }

    .subject-table-header,
    .directory-header,
    .subject-filter-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .directory-header {
        border-bottom: 1px solid #e5ebf3;
        border-radius: 16px;
    }

    .class-groups {
        border-top: 0;
        border-radius: 0 0 16px 16px;
    }
}

@media (max-width: 650px) {

    .subject-summary-grid {
        grid-template-columns: 1fr;
    }

    .class-groups {
        grid-template-columns: 1fr;
    }

    .subject-hero {
        min-height: 145px;
    }

    .subject-hero-content h1 {
        font-size: 24px;
    }

    .subject-hero-actions {
        position: absolute;
        right: 24px;
        bottom: 24px;
    }

    .hero-add-btn {
        padding: 0 13px;
    }

    .filter-content {
        padding: 16px;
    }

    .subject-table-header {
        padding: 18px;
    }

    .directory-header {
        padding: 18px;
    }

    .class-groups {
        padding: 14px;
    }

    .class-group-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .class-view-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {

    .subject-hero-actions {
        position: static;
        margin-top: 18px;
    }

    .subject-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .hero-add-btn {
        width: 100%;
    }

    .class-number-box {
        width: 56px;
        height: 56px;
    }

    .class-icon {
        width: 31px;
        height: 31px;
        font-size: 17px;
    }

    .subject-name-wrapper {
        min-width: 160px;
    }
}

</style>


<div class="subject-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="subject-hero">

        <div class="subject-hero-content">

            <div class="subject-hero-breadcrumb">
                <span>Academic Management</span>

                <i class="bi bi-chevron-right"></i>

                <span>Subjects</span>
            </div>

            <h1>
                Subjects
            </h1>

            <p>
                Manage academic subjects class-wise and keep your
                curriculum organized from one place.
            </p>

        </div>

        <div class="subject-hero-actions">

            <a
                href="{{ route('admin.subjects.create') }}"
                class="hero-add-btn"
            >
                <i class="bi bi-plus-lg"></i>
                Add Subject
            </a>

        </div>

        <div class="subject-hero-icon-wrap">

            <i class="fa-solid fa-book-open"></i>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="subject-alert">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         SUMMARY CARDS
    ====================================================== --}}

    <div class="subject-summary-grid">

        {{-- TOTAL SUBJECTS --}}

        <div class="summary-card blue">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Total Subjects
                    </div>

                    <div class="summary-number">
                        {{ $subjects->count() }}
                    </div>

                </div>

                <div class="summary-icon">

                    <i class="fa-solid fa-book-open"></i>

                </div>

            </div>

            <div class="summary-footer">
                All academic subjects
            </div>

        </div>


        {{-- ACTIVE SUBJECTS --}}

        <div class="summary-card green">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Active
                    </div>

                    <div class="summary-number">
                        {{ $subjects->where('status', true)->count() }}
                    </div>

                </div>

                <div class="summary-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

            </div>

            <div class="summary-footer">
                Currently active subjects
            </div>

        </div>


        {{-- INACTIVE SUBJECTS --}}

        <div class="summary-card orange">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Inactive
                    </div>

                    <div class="summary-number">
                        {{ $subjects->where('status', false)->count() }}
                    </div>

                </div>

                <div class="summary-icon">

                    <i class="bi bi-pause-circle-fill"></i>

                </div>

            </div>

            <div class="summary-footer">
                Inactive academic subjects
            </div>

        </div>


        {{-- ASSIGNED CLASSES --}}

        <div class="summary-card cyan">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Assigned Classes
                    </div>

                    <div class="summary-number">
                        {{ $subjects->pluck('class_id')->unique()->count() }}
                    </div>

                </div>

                <div class="summary-icon">

                    <i class="fa-solid fa-building-columns"></i>

                </div>

            </div>

            <div class="summary-footer">
                Classes with subjects assigned
            </div>

        </div>

    </div>


    {{-- =====================================================
         CLASS FILTER
    ====================================================== --}}

    <div class="subject-filter-section">

        <div class="subject-filter-header">

            <div class="filter-heading">

                <div class="filter-heading-icon">

                    <i class="fa-solid fa-filter"></i>

                </div>

                <div>

                    <h2>
                        Subject Filter
                    </h2>

                    <p>
                        Select a class to view its assigned subjects.
                    </p>

                </div>

            </div>

        </div>


        <div class="filter-content">

            <form
                action="{{ route('admin.subjects.index') }}"
                method="GET"
                class="subject-filter-form"
            >

                <div class="filter-group">

                    <label
                        for="class_id"
                        class="filter-label"
                    >
                        Select Class
                    </label>

                    <select
                        name="class_id"
                        id="class_id"
                        class="class-select"
                    >

                        <option value="">
                            All Classes
                        </option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}
                            >
                                Class {{ $class->class_name }}
                                - Section {{ $class->section }}
                                ({{ $class->academic_year }})
                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="filter-btn"
                >

                    <i class="bi bi-search"></i>

                    Show Subjects

                </button>


                @if(request()->filled('class_id'))

                    <a
                        href="{{ route('admin.subjects.index') }}"
                        class="clear-filter-btn"
                    >

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Clear Filter

                    </a>

                @endif

            </form>

        </div>

    </div>


    {{-- =====================================================
         NO CLASS SELECTED
         SHOW CLASS DIRECTORY
    ====================================================== --}}

    @if(!request()->filled('class_id'))

        @if($subjects->count())

            @php

                $groupedSubjects = $subjects->groupBy('class_id');

            @endphp


            <div class="directory-section">

                {{-- DIRECTORY HEADER --}}

                <div class="directory-header">

                    <div class="directory-title">

                        <div class="directory-title-icon">

                            <i class="fa-solid fa-building-columns"></i>

                        </div>

                        <div>

                            <h3>
                                Class Directory
                            </h3>

                            <p>
                                Select a class to view its assigned subjects.
                            </p>

                        </div>

                    </div>

                    <span class="directory-count">

                        {{ $groupedSubjects->count() }}

                        {{ $groupedSubjects->count() == 1 ? 'Class' : 'Classes' }}

                    </span>

                </div>


                {{-- CLASS CARDS --}}

                <div class="class-groups">

                    @foreach($groupedSubjects as $classId => $classSubjects)

                        @php

                            $schoolClass = $classes->firstWhere(
                                'id',
                                $classId
                            );

                        @endphp

                        @if($schoolClass)

                            <div class="class-group-card">

                                <div class="class-card-accent"></div>

                                <div class="class-group-body">

                                    <div class="class-group-main">

                                        {{-- CLASS ICON --}}

                                        <div class="class-number-box">

                                            <div class="class-icon">

                                                <i class="fa-solid fa-building-columns"></i>

                                            </div>

                                        </div>


                                        {{-- CLASS INFORMATION --}}

                                        <div class="class-group-info">

                                            <h3>

                                                Class {{ $schoolClass->class_name }}

                                                <span
                                                    style="color:#a0aabc; font-weight:500;"
                                                >
                                                    •
                                                </span>

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


                                    {{-- SUBJECT COUNT --}}

                                    <div class="class-subject-summary">

                                        <span class="class-subject-summary-label">
                                            Subjects assigned
                                        </span>

                                        <span class="class-subject-summary-count">

                                            {{ $classSubjects->count() }}

                                            {{ $classSubjects->count() == 1 ? 'Subject' : 'Subjects' }}

                                        </span>

                                    </div>

                                </div>


                                {{-- CARD FOOTER --}}

                                <div class="class-group-footer">

                                    <span class="class-subject-label">

                                        <strong>
                                            {{ $classSubjects->count() }}
                                        </strong>

                                        {{ $classSubjects->count() == 1
                                            ? 'subject assigned'
                                            : 'subjects assigned'
                                        }}

                                    </span>


                                    <a
                                        href="{{ route(
                                            'admin.subjects.index',
                                            ['class_id' => $schoolClass->id]
                                        ) }}"
                                        class="class-view-btn"
                                    >

                                        View Subjects

                                        <span class="class-view-arrow">
                                            →
                                        </span>

                                    </a>

                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>

            </div>

        @else

            {{-- =================================================
                 NO SUBJECTS
            ================================================== --}}

            <div class="subject-table-section">

                <div class="empty-state">

                    <div class="empty-icon-wrapper">

                        <div class="empty-icon-bg"></div>

                        <div class="empty-state-icon">

                            <i class="fa-solid fa-book-open"></i>

                        </div>

                    </div>


                    <h4>
                        No Subjects Found
                    </h4>

                    <p>
                        Start by adding your first subject to a school class.
                    </p>


                    <a
                        href="{{ route('admin.subjects.create') }}"
                        class="empty-add-btn"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add Subject

                    </a>

                </div>

            </div>

        @endif


    {{-- =====================================================
         CLASS SELECTED
         SHOW SUBJECT TABLE
    ====================================================== --}}

    @else

        @php

            $selectedClass = $classes->firstWhere(
                'id',
                request('class_id')
            );

        @endphp


        {{-- BACK TO CLASS DIRECTORY --}}

        <div class="subject-back-row">

            <a
                href="{{ route('admin.subjects.index') }}"
                class="back-to-classes"
            >

                <span class="back-arrow">

                    <i class="bi bi-arrow-left"></i>

                </span>

                <span>
                    Back to All Classes
                </span>

            </a>

        </div>


        {{-- SUBJECT TABLE SECTION --}}

        <div class="subject-table-section">

            {{-- TABLE HEADER --}}

            <div class="subject-table-header">

                <div class="subject-section-heading">

                    <div class="subject-section-heading-icon">

                        <i class="fa-solid fa-book-open"></i>

                    </div>

                    <div>

                        <h2>

                            @if($selectedClass)

                                Class {{ $selectedClass->class_name }}
                                • Section {{ $selectedClass->section }}

                            @else

                                Subjects

                            @endif

                        </h2>

                        <p>
                            View, manage and update subjects assigned to this class.
                        </p>

                    </div>

                </div>


                <div class="subject-count">

                    {{ $subjects->count() }}

                    {{ $subjects->count() == 1 ? 'Subject' : 'Subjects' }}

                </div>

            </div>


            @if($subjects->count())

                <div class="table-wrapper">

                    <table class="subject-table">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Subject Code
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Section
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($subjects as $subject)

                                <tr>

                                    {{-- NUMBER --}}

                                    <td>

                                        <span class="row-number">

                                            {{ $loop->iteration }}

                                        </span>

                                    </td>


                                    {{-- SUBJECT --}}

                                    <td>

                                        <div class="subject-name-wrapper">

                                            <span class="subject-icon">

                                                <i class="fa-solid fa-book-open"></i>

                                            </span>

                                            <span class="subject-name">

                                                {{ $subject->subject_name }}

                                            </span>

                                        </div>

                                    </td>


                                    {{-- SUBJECT CODE --}}

                                    <td>

                                        @if($subject->subject_code)

                                            <span class="subject-code">

                                                {{ $subject->subject_code }}

                                            </span>

                                        @else

                                            <span class="subject-code">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- CLASS --}}

                                    <td>

                                        @if($subject->schoolClass)

                                            <span class="class-badge">

                                                Class
                                                {{ $subject->schoolClass->class_name }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- SECTION --}}

                                    <td>

                                        @if($subject->schoolClass)

                                            <span class="section-badge">

                                                {{ $subject->schoolClass->section }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- ACADEMIC YEAR --}}

                                    <td>

                                        @if($subject->schoolClass)

                                            <span class="academic-year">

                                                {{ $subject->schoolClass->academic_year }}

                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- STATUS --}}

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


                                    {{-- ACTIONS --}}

                                    <td>

                                        <div class="action-buttons">

                                            {{-- VIEW --}}

                                            <a
                                                href="{{ route(
                                                    'admin.subjects.show',
                                                    $subject->id
                                                ) }}"
                                                class="action-btn action-view"
                                                title="View Subject"
                                                aria-label="View Subject"
                                            >

                                                <i class="bi bi-eye-fill"></i>

                                            </a>


                                            {{-- EDIT --}}

                                            <a
                                                href="{{ route(
                                                    'admin.subjects.edit',
                                                    $subject->id
                                                ) }}"
                                                class="action-btn action-edit"
                                                title="Edit Subject"
                                                aria-label="Edit Subject"
                                            >

                                                <i class="bi bi-pencil-fill"></i>

                                            </a>


                                            {{-- DELETE --}}

                                            <form
                                                action="{{ route(
                                                    'admin.subjects.destroy',
                                                    $subject->id
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this subject?'
                                                );"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="action-btn action-delete"
                                                    title="Delete Subject"
                                                    aria-label="Delete Subject"
                                                >

                                                    <i class="bi bi-trash-fill"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =================================================
                     NO SUBJECTS FOR SELECTED CLASS
                ================================================== --}}

                <div class="empty-state">

                    <div class="empty-icon-wrapper">

                        <div class="empty-icon-bg"></div>

                        <div class="empty-state-icon">

                            <i class="fa-solid fa-book-open"></i>

                        </div>

                    </div>


                    <h4>
                        No Subjects Found
                    </h4>

                    <p>
                        No subjects have been assigned to this class yet.
                    </p>


                    <a
                        href="{{ route('admin.subjects.create') }}"
                        class="empty-add-btn"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add Subject

                    </a>

                </div>

            @endif

        </div>

    @endif

</div>

@endsection