

@extends('layouts.app')



@section('title', 'Generate Exam Timetable')



@section('content')



<style>

/* =========================================================

   SCHOOL DIARY DESIGN SYSTEM

   Generate Exam Timetable

========================================================= */



.exam-generate-page {

    --eg-page: #f7f6f0;

    --eg-paper: #fffefa;

    --eg-cream: #f5f3e9;

    --eg-cream-deep: #f1ead8;

    --eg-ink: #263b35;

    --eg-dark: #1d302b;

    --eg-forest: #344f43;

    --eg-green: #43835d;

    --eg-sage: #527f70;

    --eg-blue: #426b8a;

    --eg-amber: #a87543;

    --eg-red: #b95b50;

    --eg-text: #39483e;

    --eg-muted: #7b857b;

    --eg-line: #e5e1d5;

    --eg-shadow: 0 8px 26px rgba(38, 59, 53, .055);

    --eg-radius: 16px;



    padding: 24px;

    color: var(--eg-text);

    font-family: "Public Sans", "Inter", sans-serif;

    background: var(--eg-page);

    border-radius: 20px;

}



.exam-generate-page *,

.exam-generate-page *::before,

.exam-generate-page *::after {

    box-sizing: border-box;

}



.exam-generate-page .eg-page-header {

    position: relative;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 22px;

    padding: 28px 30px;

    margin-bottom: 24px;

    overflow: hidden;

    background: var(--eg-paper);

    border: 1px solid var(--eg-line);

    border-left: 5px solid var(--eg-forest);

    border-radius: var(--eg-radius);

    box-shadow: var(--eg-shadow);

}



.exam-generate-page .eg-page-header::after {

    content: "";

    position: absolute;

    width: 180px;

    height: 180px;

    right: 18%;

    top: -115px;

    border: 1px solid rgba(82, 127, 112, .13);

    border-radius: 50%;

    pointer-events: none;

}



.exam-generate-page .eg-header-content {

    position: relative;

    z-index: 1;

    min-width: 0;

}



.exam-generate-page .eg-eyebrow {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    margin-bottom: 9px;

    color: var(--eg-amber);

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 1.6px;

    text-transform: uppercase;

}



.exam-generate-page .eg-page-title {

    margin: 0;

    color: var(--eg-ink);

    font-family: Georgia, "Times New Roman", serif;

    font-size: clamp(25px, 3vw, 34px);

    font-weight: 700;

    line-height: 1.2;

    letter-spacing: -.6px;

}



.exam-generate-page .eg-page-subtitle {

    margin: 10px 0 0;

    color: var(--eg-muted);

    font-size: 13px;

    line-height: 1.7;

}



.exam-generate-page .eg-page-subtitle strong {

    color: var(--eg-text);

    font-weight: 700;

}



.exam-generate-page .eg-header-actions {

    position: relative;

    z-index: 1;

    display: flex;

    flex-wrap: wrap;

    align-items: center;

    gap: 10px;

    flex-shrink: 0;

}



.exam-generate-page .eg-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    min-height: 43px;

    padding: 11px 17px;

    border: 1px solid transparent;

    border-radius: 10px;

    font-family: inherit;

    font-size: 12px;

    font-weight: 750;

    text-decoration: none;

    cursor: pointer;

    transition: *background* .2s ease, color .2s ease,

                border-color .2s ease, transform .2s ease;

}



.exam-generate-page .eg-btn:hover {

    transform: translateY(-1px);

}



.exam-generate-page .eg-btn-outline {

    color: var(--eg-ink);

    background: var(--eg-paper);

    border-color: var(--eg-line);

}



.exam-generate-page .eg-btn-outline:hover {

    color: var(--eg-dark);

    background: var(--eg-cream);

    border-color: #c9c4b5;

}



.exam-generate-page .eg-btn-primary {

    min-height: 49px;

    padding: 13px 22px;

    color: var(--eg-ink);

    background: var(--eg-cream-deep);

    border-color: #e3dac3;

    box-shadow: 0 4px 10px rgba(38, 59, 53, .07);

}



.exam-generate-page .eg-btn-primary:hover {

    color: var(--eg-dark);

    background: #fff5dc;

    border-color: #d9cba8;

}



.exam-generate-page .eg-btn-primary:disabled {

    opacity: .72;

    cursor: wait;

    transform: none;

}



.exam-generate-page .eg-btn:focus-visible,

.exam-generate-page input:focus-visible,

.exam-generate-page select:focus-visible {

    outline: 3px solid rgba(82, 127, 112, .25);

    outline-offset: 2px;

}



/* Breadcrumb-style page context */



.exam-generate-page .eg-context-line {

    display: flex;

    flex-wrap: wrap;

    align-items: center;

    gap: 8px;

    margin: -9px 0 22px 4px;

    color: var(--eg-muted);

    font-size: 11px;

}



.exam-generate-page .eg-context-line i {

    color: var(--eg-sage);

}



.exam-generate-page .eg-context-separator {

    color: #c4c1b4;

}



/* Validation */



.exam-generate-page .eg-validation {

    margin-bottom: 22px;

    padding: 18px 20px;

    color: #8f4139;

    background: #fff6f3;

    border: 1px solid #efd1cb;

    border-left: 4px solid var(--eg-red);

    border-radius: 12px;

}



.exam-generate-page .eg-validation-title {

    display: flex;

    align-items: center;

    gap: 9px;

    margin-bottom: 9px;

    font-size: 13px;

    font-weight: 800;

}



.exam-generate-page .eg-validation ul {

    margin: 0;

    padding-left: 24px;

    font-size: 12px;

    line-height: 1.9;

}



/* Main panels */



.exam-generate-page .eg-panel {

    margin-bottom: 23px;

    overflow: hidden;

    background: var(--eg-paper);

    border: 1px solid var(--eg-line);

    border-radius: var(--eg-radius);

    box-shadow: var(--eg-shadow);

}



.exam-generate-page .eg-panel:last-child {

    margin-bottom: 0;

}



.exam-generate-page .eg-panel-header {

    display: flex;

    align-items: center;

    gap: 13px;

    padding: 21px 25px;

    background: #fffdf7;

    border-bottom: 1px solid var(--eg-line);

}



.exam-generate-page .eg-panel-icon {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    width: 41px;

    height: 41px;

    flex: 0 0 41px;

    color: var(--eg-sage);

    background: #edf2eb;

    border: 1px solid #dce7db;

    border-radius: 12px;

    font-size: 18px;

}



.exam-generate-page .eg-panel-heading {

    min-width: 0;

}



.exam-generate-page .eg-panel-title {

    margin: 0;

    color: var(--eg-ink);

    font-family: Georgia, "Times New Roman", serif;

    font-size: 19px;

    font-weight: 700;

    line-height: 1.4;

}



.exam-generate-page .eg-panel-description {

    margin: 4px 0 0;

    color: var(--eg-muted);

    font-size: 11px;

    line-height: 1.6;

}



.exam-generate-page .eg-panel-body {

    padding: 25px;

}



/* Shared section headings */



.exam-generate-page .eg-section {

    margin-top: 29px;

}



.exam-generate-page .eg-section:first-child {

    margin-top: 0;

}



.exam-generate-page .eg-section-heading {

    display: flex;

    justify-content: space-between;

    align-items: center;

    flex-wrap: wrap;

    gap: 12px;

    margin-bottom: 13px;

}



.exam-generate-page .eg-section-title {

    display: flex;

    align-items: center;

    gap: 9px;

    margin: 0;

    color: var(--eg-ink);

    font-size: 13px;

    font-weight: 800;

}



.exam-generate-page .eg-section-title > i {

    color: var(--eg-sage);

    font-size: 16px;

}



.exam-generate-page .eg-section-note {

    margin: 5px 0 0;

    color: var(--eg-muted);

    font-size: 11px;

    line-height: 1.65;

}



/* Examination information */



.exam-generate-page .eg-info-grid {

    display: grid;

    grid-template-columns: repeat(4, minmax(0, 1fr));

    gap: 13px;

}



.exam-generate-page .eg-info-item {

    position: relative;

    min-width: 0;

    padding: 17px;

    background: #fffefa;

    border: 1px solid var(--eg-line);

    border-radius: 12px;

}



.exam-generate-page .eg-info-item::before {

    content: "";

    position: absolute;

    left: 0;

    top: 15px;

    bottom: 15px;

    width: 3px;

    background: var(--eg-sage);

    border-radius: 0 4px 4px 0;

}



.exam-generate-page .eg-info-label {

    margin-bottom: 8px;

    color: var(--eg-muted);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: .7px;

    text-transform: uppercase;

}



.exam-generate-page .eg-info-value {

    overflow-wrap: anywhere;

    color: var(--eg-ink);

    font-size: 13px;

    font-weight: 750;

    line-height: 1.65;

}



.exam-generate-page .eg-info-value .eg-date-connector {

    padding: 0 3px;

    color: var(--eg-amber);

    font-size: 11px;

    font-weight: 600;

}



/* Information / warning notices */



.exam-generate-page .eg-notice {

    display: flex;

    gap: 13px;

    padding: 18px 19px;

    border: 1px solid;

    border-radius: 12px;

}



.exam-generate-page .eg-notice-icon {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    width: 35px;

    height: 35px;

    flex: 0 0 35px;

    border-radius: 10px;

    font-size: 16px;

}



.exam-generate-page .eg-notice-content {

    min-width: 0;

}



.exam-generate-page .eg-notice-title {

    margin: 2px 0 9px;

    font-size: 13px;

    font-weight: 800;

}



.exam-generate-page .eg-notice ul {

    margin: 0;

    padding-left: 19px;

    font-size: 12px;

    line-height: 1.9;

}



.exam-generate-page .eg-notice-info {

    color: #365b50;

    background: #f0f5ef;

    border-color: #dbe7d8;

}



.exam-generate-page .eg-notice-info .eg-notice-icon {

    color: var(--eg-sage);

    background: #e0eadf;

}



.exam-generate-page .eg-notice-success {

    color: #365d45;

    background: #f0f5ec;

    border-color: #d7e6d1;

}



.exam-generate-page .eg-notice-success .eg-notice-icon {

    color: var(--eg-green);

    background: #e0ebd9;

}



.exam-generate-page .eg-notice-warning {

    color: #79552e;

    background: #fff8e9;

    border-color: #efdfbd;

}



.exam-generate-page .eg-notice-warning .eg-notice-icon {

    color: var(--eg-amber);

    background: #f7ebd2;

}



/* Form fields */



.exam-generate-page .eg-form-label {

    display: block;

    margin-bottom: 9px;

    color: var(--eg-ink);

    font-size: 12px;

    font-weight: 800;

}



.exam-generate-page .eg-required {

    color: var(--eg-red);

}



.exam-generate-page .eg-form-select {

    display: block;

    width: 100%;

    min-height: 49px;

    padding: 12px 42px 12px 14px;

    color: var(--eg-ink);

    background-color: #fffefa;

    border: 1px solid #dcd8cb;

    border-radius: 10px;

    font-family: inherit;

    font-size: 13px;

    font-weight: 600;

    box-shadow: none;

}



.exam-generate-page .eg-form-select:hover {

    border-color: #b9c9bb;

}



.exam-generate-page .eg-form-help {

    margin-top: 9px;

    color: var(--eg-muted);

    font-size: 11px;

    line-height: 1.7;

}



.exam-generate-page .eg-form-help i {

    margin-right: 4px;

    color: var(--eg-sage);

}



/* Paper times */



.exam-generate-page .eg-time-container {

    height: 100%;

    padding: 15px;

    background: var(--eg-cream);

    border: 1px solid var(--eg-line);

    border-radius: 13px;

}



.exam-generate-page .eg-time-grid {

    display: grid;

    grid-template-columns: repeat(3, minmax(0, 1fr));

    gap: 10px;

}



.exam-generate-page .eg-time-card {

    min-width: 0;

    padding: 15px 8px;

    text-align: center;

    background: var(--eg-paper);

    border: 1px solid #e7e2d5;

    border-radius: 11px;

}



.exam-generate-page .eg-time-number {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    margin-bottom: 9px;

    color: var(--eg-muted);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: .7px;

    text-transform: uppercase;

}



.exam-generate-page .eg-time-number i {

    color: var(--eg-sage);

}



.exam-generate-page .eg-time-value {

    color: var(--eg-ink);

    font-family: Georgia, "Times New Roman", serif;

    font-size: clamp(16px, 1.5vw, 21px);

    font-weight: 700;

    white-space: nowrap;

}



.exam-generate-page .eg-time-footnote {

    display: flex;

    align-items: flex-start;

    gap: 8px;

    margin-top: 13px;

    color: var(--eg-muted);

    font-size: 10px;

    line-height: 1.8;

}



.exam-generate-page .eg-time-footnote i {

    margin-top: 2px;

    color: var(--eg-sage);

}



/* Count badges */



.exam-generate-page .eg-count {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    min-height: 28px;

    padding: 6px 11px;

    color: var(--eg-ink);

    background: var(--eg-cream);

    border: 1px solid var(--eg-line);

    border-radius: 8px;

    font-size: 10px;

    font-weight: 800;

    white-space: nowrap;

}



.exam-generate-page .eg-count-green {

    color: #356a49;

    background: #edf5e9;

    border-color: #dce9d5;

}



.exam-generate-page .eg-count-amber {

    color: #845b2e;

    background: #fff5df;

    border-color: #f0e0bb;

}



.exam-generate-page .eg-count-blue {

    color: #3c607c;

    background: #edf3f7;

    border-color: #d9e4ec;

}



/* Class chips */



.exam-generate-page .eg-class-list {

    padding: 15px;

    background: #fcfbf6;

    border: 1px solid var(--eg-line);

    border-radius: 12px;

}



.exam-generate-page .eg-class-chip {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    height: 100%;

    padding: 12px;

    color: var(--eg-text);

    background: var(--eg-paper);

    border: 1px solid var(--eg-line);

    border-radius: 10px;

    font-size: 12px;

    font-weight: 700;

    line-height: 1.6;

    overflow-wrap: anywhere;

}



.exam-generate-page .eg-class-chip > i {

    margin-top: 2px;

    color: var(--eg-sage);

    font-size: 15px;

}



.exam-generate-page .eg-class-name {

    min-width: 0;

}



.exam-generate-page .eg-class-section {

    display: block;

    margin-top: 2px;

    color: var(--eg-muted);

    font-size: 10px;

    font-weight: 600;

}



/* Table */



.exam-generate-page .eg-table-wrap {

    overflow-x: auto;

    border: 1px solid var(--eg-line);

    border-radius: 12px;

}



.exam-generate-page .eg-table {

    width: 100%;

    min-width: 690px;

    margin: 0;

    color: var(--eg-text);

    vertical-align: middle;

    border-collapse: separate;

    border-spacing: 0;

}



.exam-generate-page .eg-table thead th {

    padding: 14px 15px;

    color: #526258;

    background: #f3f1e7;

    border: 0;

    border-bottom: 1px solid var(--eg-line);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: .55px;

    text-transform: uppercase;

    white-space: nowrap;

}



.exam-generate-page .eg-table tbody td {

    padding: 14px 15px;

    background: var(--eg-paper);

    border: 0;

    border-bottom: 1px solid #eeeae0;

    font-size: 12px;

}



.exam-generate-page .eg-table tbody tr:last-child td {

    border-bottom: 0;

}



.exam-generate-page .eg-table tbody tr {

    transition: *background* .15s ease;

}



.exam-generate-page .eg-table tbody tr:hover td {

    background: #faf8f0;

}



.exam-generate-page .eg-row-number {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    width: 29px;

    height: 29px;

    color: var(--eg-forest);

    background: #edf1e8;

    border: 1px solid #dfe6d8;

    border-radius: 9px;

    font-size: 11px;

    font-weight: 800;

}



.exam-generate-page .eg-table-main {

    color: var(--eg-ink);

    font-weight: 750;

}



.exam-generate-page .eg-table-sub {

    display: block;

    margin-top: 4px;

    color: var(--eg-muted);

    font-size: 10px;

    font-weight: 500;

}



.exam-generate-page .eg-marks {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 38px;

    padding: 6px 9px;

    color: #3c607c;

    background: #edf3f7;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 800;

}



.exam-generate-page .eg-duration {

    color: var(--eg-ink);

    font-weight: 750;

    white-space: nowrap;

}



/* Holidays */



.exam-generate-page .eg-holiday-list {

    display: flex;

    flex-wrap: wrap;

    gap: 9px;

}



.exam-generate-page .eg-holiday-chip {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 10px 12px;

    color: var(--eg-text);

    background: #fffefa;

    border: 1px solid var(--eg-line);

    border-radius: 9px;

    font-size: 11px;

    font-weight: 700;

    line-height: 1.6;

}



.exam-generate-page .eg-holiday-chip > i {

    color: var(--eg-red);

    font-size: 14px;

}



.exam-generate-page .eg-holiday-name {

    color: var(--eg-muted);

    font-weight: 500;

}



/* Empty / missing data */



.exam-generate-page .eg-empty-state {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 17px;

    color: #78582f;

    background: #fff8e9;

    border: 1px solid #efdfbd;

    border-radius: 11px;

    font-size: 12px;

    line-height: 1.7;

}



.exam-generate-page .eg-empty-state > i {

    margin-top: 2px;

    color: var(--eg-amber);

    font-size: 17px;

}



/* Replace existing timetable */



.exam-generate-page .eg-replace-box {

    padding: 19px;

    background: #fffefa;

    border: 1px solid var(--eg-line);

    border-radius: 12px;

    transition: border-color .2s ease, *background* .2s ease;

}



.exam-generate-page .eg-replace-box:has(.eg-replace-checkbox:checked) {

    background: #fff9ef;

    border-color: #e4cda4;

}



.exam-generate-page .eg-replace-label {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    cursor: pointer;

}



.exam-generate-page .eg-replace-checkbox {

    width: 18px;

    height: 18px;

    flex: 0 0 18px;

    margin-top: 2px;

    accent-color: var(--eg-forest);

    cursor: pointer;

}



.exam-generate-page .eg-replace-title {

    color: var(--eg-ink);

    font-size: 12px;

    font-weight: 800;

    line-height: 1.6;

}



.exam-generate-page .eg-replace-description {

    margin: 6px 0 0 30px;

    color: var(--eg-muted);

    font-size: 11px;

    line-height: 1.8;

}



.exam-generate-page .eg-replace-warning {

    display: none;

    margin: 13px 0 0 30px;

    padding: 10px 12px;

    color: #8b4c38;

    background: #fff0e7;

    border: 1px solid #f1d2c0;

    border-radius: 8px;

    font-size: 11px;

    line-height: 1.7;

}



.exam-generate-page .eg-replace-box:has(.eg-replace-checkbox:checked) .eg-replace-warning {

    display: block;

}



/* Bottom actions */



.exam-generate-page .eg-form-footer {

    display: flex;

    justify-content: flex-end;

    flex-wrap: wrap;

    align-items: center;

    gap: 10px;

    margin-top: 25px;

    padding-top: 22px;

    border-top: 1px solid var(--eg-line);

}



.exam-generate-page .eg-footer-note {

    margin-right: auto;

    color: var(--eg-muted);

    font-size: 10px;

    line-height: 1.7;

}



.exam-generate-page .eg-footer-note i {

    color: var(--eg-sage);

}



/* Responsive */



@media (max-width: 1199px) {

    .exam-generate-page .eg-info-grid {

        grid-template-columns: repeat(2, minmax(0, 1fr));

    }

}



@media (max-width: 767px) {

    .exam-generate-page {

        padding: 13px;

        border-radius: 13px;

    }



    .exam-generate-page .eg-page-header {

        align-items: flex-start;

        flex-direction: column;

        padding: 22px 19px;

        gap: 18px;

    }



    .exam-generate-page .eg-header-actions {

        width: 100%;

    }



    .exam-generate-page .eg-header-actions .eg-btn {

        width: 100%;

    }



    .exam-generate-page .eg-panel-header {

        padding: 17px;

    }



    .exam-generate-page .eg-panel-body {

        padding: 17px;

    }



    .exam-generate-page .eg-info-grid {

        grid-template-columns: 1fr;

        gap: 9px;

    }



    .exam-generate-page .eg-info-item {

        padding: 14px 16px;

    }



    .exam-generate-page .eg-time-grid {

        gap: 6px;

    }



    .exam-generate-page .eg-time-card {

        padding: 12px 5px;

    }



    .exam-generate-page .eg-time-value {

        font-size: 15px;

    }



    .exam-generate-page .eg-notice {

        padding: 14px;

        gap: 10px;

    }



    .exam-generate-page .eg-notice-icon {

        width: 30px;

        height: 30px;

        flex-basis: 30px;

    }



    .exam-generate-page .eg-form-footer {

        align-items: stretch;

        flex-direction: column-reverse;

    }



    .exam-generate-page .eg-form-footer .eg-btn {

        width: 100%;

    }



    .exam-generate-page .eg-footer-note {

        margin: 5px 0 0;

        text-align: center;

    }



    .exam-generate-page .eg-section-heading {

        align-items: flex-start;

    }

}



@media (max-width: 420px) {

    .exam-generate-page .eg-time-grid {

        grid-template-columns: 1fr;

    }



    .exam-generate-page .eg-time-card {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 10px;

        padding: 12px;

        text-align: left;

    }



    .exam-generate-page .eg-time-number {

        margin: 0;

    }



    .exam-generate-page .eg-time-value {

        font-size: 17px;

    }



    .exam-generate-page .eg-count {

        white-space: normal;

    }

}



@media (prefers-reduced-motion: reduce) {

    .exam-generate-page *,

    .exam-generate-page *::before,

    .exam-generate-page *::after {

        scroll-behavior: auto !important;

        transition: none !important;

        animation: none !important;

    }

}

/* Professional print-style configured subjects table */
.eg-print-subjects-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #d9dee7;
    border-radius: 6px;
    background: #fff;
}

.eg-print-subjects-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
    table-layout: fixed;
    color: #252b36;
}

.eg-print-subjects-table thead th {
    padding: 11px 12px;
    background: #f0f2f5;
    color: #252b36;
    border: 1px solid #d9dee7;
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    white-space: nowrap;
}

.eg-print-subjects-table tbody td {
    padding: 9px 12px;
    border: 1px solid #d9dee7;
    font-size: 12px;
    line-height: 1.4;
    vertical-align: middle;
}

.eg-print-subjects-table .eg-print-number {
    text-align: center;
    color: #555e6d;
}

.eg-print-subjects-table .eg-print-class-cell {
    background: #fafbfc;
    font-weight: 600;
}

.eg-print-subjects-table .eg-print-section {
    display: block;
    margin-top: 3px;
    color: #6b7280;
    font-size: 10px;
    font-weight: 400;
}

.eg-print-subjects-table .eg-print-subject-cell {
    font-weight: 500;
}

.eg-print-subjects-table tbody tr {
    break-inside: avoid;
}

@media print {
    .eg-print-subjects-wrap {
        overflow: visible !important;
        border-radius: 0;
    }

    .eg-print-subjects-table {
        table-layout: fixed;
        width: 100%;
    }

    .eg-print-subjects-table th,
    .eg-print-subjects-table td {
        padding: 6px 8px !important;
        font-size: 10px !important;
        overflow-wrap: break-word;
    }

    .eg-print-subjects-table thead {
        display: table-header-group;
    }
}

</style>



<div class="exam-generate-page">



    {{-- =========================================================

        PAGE HEADER

    ========================================================== --}}

    <div class="eg-page-header">



        <div class="eg-header-content">



            <div class="eg-eyebrow">

                <i class="bi bi-journal-bookmark-fill"></i>

                Examination Management

            </div>



            <h1 class="eg-page-title">

                Generate Exam Timetable

            </h1>



            <p class="eg-page-subtitle">

                Configure automatic paper scheduling for

                <strong>{{ $exam->exam_name ?? 'Examination' }}</strong>



                @if($exam->academic_year)

                    <span class="mx-1">·</span>

                    {{ $exam->academic_year }}

                @endif

            </p>



        </div>



        <div class="eg-header-actions">



            <a href="{{ route('admin.exam-schedules.index', $exam) }}"

               class="eg-btn eg-btn-outline">



                <i class="bi bi-arrow-left"></i>

                Back to Timetable



            </a>



        </div>



    </div>



    <div class="eg-context-line">

        <i class="bi bi-house-door"></i>

        <span>Examinations</span>

        <span class="eg-context-separator">/</span>

        <span>{{ $exam->exam_name ?? 'Examination' }}</span>

        <span class="eg-context-separator">/</span>

        <span>Generate Timetable</span>

    </div>





    {{-- =========================================================

        VALIDATION ERRORS

    ========================================================== --}}

    @if($errors->any())



        <div class="eg-validation" role="alert">



            <div class="eg-validation-title">

                <i class="bi bi-exclamation-triangle-fill"></i>

                Please fix the following errors

            </div>



            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>



        </div>



    @endif





    {{-- =========================================================

        EXAMINATION INFORMATION

    ========================================================== --}}

    <section class="eg-panel">



        <div class="eg-panel-header">



            <div class="eg-panel-icon">

                <i class="bi bi-info-circle"></i>

            </div>



            <div class="eg-panel-heading">



                <h2 class="eg-panel-title">

                    Examination Information

                </h2>



                <p class="eg-panel-description">

                    Review the examination details before generating the timetable.

                </p>



            </div>



        </div>



        <div class="eg-panel-body">



            <div class="eg-info-grid">



                <div class="eg-info-item">



                    <div class="eg-info-label">

                        Examination

                    </div>



                    <div class="eg-info-value">

                        {{ $exam->exam_name ?? '-' }}

                    </div>



                </div>



                <div class="eg-info-item">



                    <div class="eg-info-label">

                        Academic Year

                    </div>



                    <div class="eg-info-value">

                        {{ $exam->academic_year ?? '-' }}

                    </div>



                </div>



                <div class="eg-info-item">



                    <div class="eg-info-label">

                        Exam Type

                    </div>



                    <div class="eg-info-value">

                        {{ $exam->exam_type ?? '-' }}

                    </div>



                </div>



                <div class="eg-info-item">



                    <div class="eg-info-label">

                        Examination Period

                    </div>



                    <div class="eg-info-value">



                        {{ $exam->start_date

                            ? \Carbon\Carbon::parse($exam->start_date)->format('d M Y')

                            : '-'

                        }}



                        <span class="eg-date-connector">to</span>



                        {{ $exam->end_date

                            ? \Carbon\Carbon::parse($exam->end_date)->format('d M Y')

                            : '-'

                        }}



                    </div>



                </div>



            </div>



        </div>



    </section>





    {{-- =========================================================

        AUTOMATIC GENERATION FORM

    ========================================================== --}}

    <section class="eg-panel">



        <div class="eg-panel-header">



            <div class="eg-panel-icon">

                <i class="bi bi-magic"></i>

            </div>



            <div class="eg-panel-heading">



                <h2 class="eg-panel-title">

                    Automatic Timetable Generation

                </h2>



                <p class="eg-panel-description">

                    Review the automatic scheduling rules. Paper counts are calculated for each class.

                </p>



            </div>



        </div>



        <div class="eg-panel-body">



            {{-- AUTOMATIC GENERATION EXPLANATION --}}

            <div class="eg-notice eg-notice-info mb-4">



                <div class="eg-notice-icon">

                    <i class="bi bi-lightbulb"></i>

                </div>



                <div class="eg-notice-content">


            <form method="POST"

                  action="{{ route('admin.exam-schedules.generate', $exam) }}"

                  id="generateTimetableForm">



                @csrf





                {{-- =====================================================
                    AUTOMATIC SCHEDULING PLAN AND PAPER TIMES
                ====================================================== --}}
                @php
                    $examTypeText = strtolower(str_replace(
                        ['-', '_'], ' ',
                        trim(($exam->exam_type ?? '') . ' ' . ($exam->exam_name ?? ''))
                    ));

                    if (str_contains($examTypeText, 'unit test') || preg_match('/\but\b/', $examTypeText)) {
                        $maximumExamDays = 3;
                        $examPlanName = 'Unit Test';
                    } elseif (
                        str_contains($examTypeText, 'mid sem') ||
                        str_contains($examTypeText, 'midsemester') ||
                        str_contains($examTypeText, 'mid term') ||
                        str_contains($examTypeText, 'midterm')
                    ) {
                        $maximumExamDays = 7;
                        $examPlanName = 'Mid-Semester';
                    } elseif (
                        str_contains($examTypeText, 'end sem') ||
                        str_contains($examTypeText, 'endsemester') ||
                        str_contains($examTypeText, 'end term') ||
                        str_contains($examTypeText, 'final exam')
                    ) {
                        $maximumExamDays = 10;
                        $examPlanName = 'End-Semester';
                    } else {
                        $maximumExamDays = 10;
                        $examPlanName = 'Preliminary / Other';
                    }
                @endphp

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label class="eg-form-label">Automatic Scheduling Plan</label>
                        <div class="eg-time-container">
                            <div class="eg-notice-title">{{ $examPlanName }} scheduling</div>
                            <p class="eg-section-note">
                                Maximum {{ $maximumExamDays }} usable examination days.
                                Saturdays, Sundays and configured holidays are skipped.
                            </p>
                            <p class="eg-section-note">
                                Paper counts are calculated independently for each class using its
                                subjects and the available examination dates.
                            </p>
                            <span class="eg-count eg-count-green">
                                <i class="bi bi-calendar-check"></i> Automatic scheduling
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label class="eg-form-label">Automatic Paper Times</label>
                        <div class="eg-time-container">
                            <div class="eg-time-grid">
                                <div class="eg-time-card">
                                    <div class="eg-time-number"><i class="bi bi-1-circle"></i> Paper 1</div>
                                    <div class="eg-time-value">10:00 AM</div>
                                </div>
                                <div class="eg-time-card">
                                    <div class="eg-time-number"><i class="bi bi-2-circle"></i> Paper 2</div>
                                    <div class="eg-time-value">01:00 PM</div>
                                </div>
                                <div class="eg-time-card">
                                    <div class="eg-time-number"><i class="bi bi-3-circle"></i> Paper 3</div>
                                    <div class="eg-time-value">03:00 PM</div>
                                </div>
                            </div>
                            <div class="eg-time-footnote">
                                <i class="bi bi-info-circle"></i>
                                <span>
                                    Additional daily slots are used only when needed. End times are
                                    calculated from each subject's configured duration.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =====================================================

                    CLASSES INCLUDED

                ====================================================== --}}

                <div class="eg-section">



                    <div class="eg-section-heading">



                        <div>

                            <h3 class="eg-section-title">

                                <i class="bi bi-mortarboard"></i>

                                Classes Included

                            </h3>



                            <p class="eg-section-note">

                                Classes configured for this examination.

                            </p>

                        </div>



                        <span class="eg-count eg-count-green">

                            <i class="bi bi-collection"></i>

                            {{ $examClasses->count() }} Classes

                        </span>



                    </div>





                    @if($examClasses->isEmpty())



                        <div class="eg-empty-state">



                            <i class="bi bi-exclamation-circle"></i>



                            <div>

                                <strong>No classes configured.</strong>

                                Add classes to this examination before generating the timetable.

                            </div>



                        </div>



                    @else



                        <div class="eg-class-list">



                            <div class="row g-2">



                                @foreach($examClasses as $examClass)



                                    <div class="col-xl-3 col-md-4 col-sm-6">



                                        <div class="eg-class-chip">



                                            <i class="bi bi-mortarboard"></i>



                                            <div class="eg-class-name">



                                                {{ $examClass->schoolClass->class_name

                                                    ?? $examClass->schoolClass->name

                                                    ?? 'Unknown Class'

                                                }}



                                                @if($examClass->schoolClass->section ?? null)



                                                    <span class="eg-class-section">

                                                        Section: {{ $examClass->schoolClass->section }}

                                                    </span>



                                                @endif



                                            </div>



                                        </div>



                                    </div>



                                @endforeach



                            </div>



                        </div>



                    @endif



                </div>


@php
    $subjectsByClass = $examSubjects->groupBy(function ($item) {
        return $item->school_class_id
            ?? $item->class_id
            ?? $item->schoolClass?->id
            ?? 'unknown';
    });
@endphp

<div class="eg-table-wrap eg-print-subjects-wrap">
    <table class="eg-table eg-print-subjects-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Class</th>
                <th>Subject</th>
                <th class="text-center" style="width: 100px;">Max Marks</th>
                <th class="text-center" style="width: 110px;">Duration</th>
            </tr>
        </thead>

        <tbody>
            @php $rowNumber = 1; @endphp

            @forelse($subjectsByClass as $classSubjects)
                @php
                    $first = $classSubjects->first();
                    $schoolClass = $first->schoolClass;

                    $className = $schoolClass->class_name
                        ?? $schoolClass->name
                        ?? 'Unknown Class';

                    $section = $schoolClass->section ?? null;
                @endphp

                @foreach($classSubjects as $examSubject)
                    <tr>
                        <td class="eg-print-number">
                            {{ $rowNumber++ }}
                        </td>

                        @if($loop->first)
                            <td rowspan="{{ $classSubjects->count() }}"
                                class="eg-print-class-cell">
                                <strong>{{ $className }}</strong>

                                @if($section)
                                    <span class="eg-print-section">
                                        Section {{ $section }}
                                    </span>
                                @endif
                            </td>
                        @endif

                        <td class="eg-print-subject-cell">
                            {{ $examSubject->subject->subject_name
                                ?? 'Unknown Subject' }}
                        </td>

                        <td class="text-center">
                            {{ $examSubject->maximum_marks ?? 0 }}
                        </td>

                        <td class="text-center">
                            {{ $examSubject->duration_minutes ?? 60 }} min
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No examination subjects configured.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>




                {{-- =====================================================

                    CONFIGURED HOLIDAYS

                ====================================================== --}}

                <div class="eg-section">



                    <div class="eg-section-heading">



                        <div>

                            <h3 class="eg-section-title">

                                <i class="bi bi-calendar2-x"></i>

                                Configured Holidays

                            </h3>



                            <p class="eg-section-note">

                                Additional examination holidays excluded from scheduling.

                            </p>

                        </div>



                        <span class="eg-count eg-count-amber">

                            <i class="bi bi-calendar-x"></i>

                            {{ $holidays->count() }} Holidays

                        </span>



                    </div>





                    @if($holidays->isEmpty())



                        <div class="eg-empty-state">



                            <i class="bi bi-calendar-check"></i>



                            <div>

                                No additional examination holidays are configured.

                            </div>



                        </div>



                    @else



                        <div class="eg-holiday-list">



                            @foreach($holidays as $holiday)



                                <div class="eg-holiday-chip">



                                    <i class="bi bi-calendar-x"></i>



                                    <span>

                                        {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}

                                    </span>



                                    @if($holiday->holiday_name ?? null)



                                        <span class="eg-holiday-name">

                                            — {{ $holiday->holiday_name }}

                                        </span>



                                    @endif



                                </div>



                            @endforeach



                        </div>



                    @endif



                </div>





                {{-- =====================================================

                    REPLACE EXISTING TIMETABLE

                ====================================================== --}}

                <div class="eg-section">



                    <div class="eg-section-heading">



                        <div>

                            <h3 class="eg-section-title">

                                <i class="bi bi-arrow-repeat"></i>

                                Existing Timetable

                            </h3>



                            <p class="eg-section-note">

                                Choose whether to retain or replace the existing schedule.

                            </p>

                        </div>



                    </div>



                    <div class="eg-replace-box">



                        <label for="replace_existing" class="eg-replace-label">



                            <input type="checkbox"

                                   class="eg-replace-checkbox"

                                   name="replace_existing"

                                   value="1"

                                   id="replace_existing"

                                   {{ old('replace_existing') ? 'checked' : '' }}>



                            <span class="eg-replace-title">

                                Replace existing timetable

                            </span>



                        </label>



                        <p class="eg-replace-description">

                            If enabled, the existing timetable for this examination

                            will be deleted and a completely new timetable will be generated.

                        </p>



                        <div class="eg-replace-warning">

                            <i class="bi bi-exclamation-triangle-fill me-1"></i>

                            <strong>Important:</strong>

                            Existing timetable entries will be deleted and replaced.

                            Confirm carefully before generating.

                        </div>
                    </div>
                </div>

                {{-- =====================================================

                    FORM ACTIONS

                ====================================================== --}}

                <div class="eg-form-footer">



                    <div class="eg-footer-note">

                        <i class="bi bi-shield-check me-1"></i>

                        Review your settings before generating the timetable.

                    </div>



                    <a href="{{ route('admin.exam-schedules.index', $exam) }}"

                       class="eg-btn eg-btn-outline">



                        <i class="bi bi-x-circle"></i>

                        Cancel



                    </a>



                    <button type="submit"

                            class="eg-btn eg-btn-primary"

                            id="generateButton">



                        <i class="bi bi-magic"></i>

                        Generate Timetable



                    </button>



                </div>



            </form>



        </div>



    </section>



</div>





{{-- =========================================================

    CONFIRMATION SCRIPT

========================================================= --}}

@push('scripts')



<script>

document.addEventListener('DOMContentLoaded', function () {



    const form = document.getElementById('generateTimetableForm');

    const button = document.getElementById('generateButton');



    const replaceCheckbox = document.getElementById('replace_existing');



    if (!form) {

        return;

    }



    let submissionConfirmed = false;



    form.addEventListener('submit', function (event) {



        /*

         * Prevent duplicate submissions after a successful confirmation.

         */

        if (submissionConfirmed) {

            event.preventDefault();

            return;

        }



        let message =
            'Generate the examination timetable automatically?\n\n'
            + 'The system will calculate paper counts independently for each class, '
            + 'use configured subject durations, and skip weekends and examination holidays.';



        if (replaceCheckbox && replaceCheckbox.checked) {



            message +=

                '\n\nWARNING: Existing timetable entries for this examination '

                + 'will be deleted and replaced.';

        }



        if (!confirm(message)) {



            event.preventDefault();



            return;

        }



        submissionConfirmed = true;



        if (button) {



            button.disabled = true;



            button.innerHTML =

                '<span class="spinner-border spinner-border-sm" '

                + 'role="status" aria-hidden="true"></span> '

                + 'Generating Timetable...';



        }



    });



});

</script>



@endpush



@endsection
