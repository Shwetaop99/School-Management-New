
@extends('layouts.app')

@section('title', 'Generate Exam Timetable')

@section('content')

<style>
/* =========================================================
   SCHOOL DIARY — GENERATE EXAM TIMETABLE
   Scoped styles: exam-timetable-page
========================================================= */

.exam-timetable-page {
    --et-page: #f7f6f0;
    --et-paper: #fffefa;
    --et-cream: #f5f3e9;
    --et-cream-dark: #f1ead8;
    --et-ink: #263b35;
    --et-ink-dark: #1d302b;
    --et-forest: #344f43;
    --et-green: #34775a;
    --et-green-soft: #e8f1e9;
    --et-sage: #527f70;
    --et-wood: #a87543;
    --et-blue: #426b8a;
    --et-blue-soft: #eaf0f5;
    --et-amber: #a87543;
    --et-amber-soft: #f7eee0;
    --et-red: #b95b50;
    --et-red-soft: #f8eae7;
    --et-muted: #7b857b;
    --et-text: #39483e;
    --et-line: #e5e1d5;
    --et-shadow: 0 8px 26px rgba(38, 59, 53, .055);
    --et-radius: 16px;

    color: var(--et-text);
    background: var(--et-page);
    padding: 26px;
    border-radius: 20px;
    font-family: Inter, "Public Sans", -apple-system, BlinkMacSystemFont,
        "Segoe UI", sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

.exam-timetable-page *,
.exam-timetable-page *::before,
.exam-timetable-page *::after {
    box-sizing: border-box;
}

.exam-timetable-page .et-page-header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 26px;
    padding: 8px 0 8px 20px;
    border-left: 4px solid var(--et-wood);
}

.exam-timetable-page .et-header-copy {
    min-width: 0;
}

.exam-timetable-page .et-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
    color: var(--et-wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.exam-timetable-page .et-page-title {
    margin: 0;
    color: var(--et-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.exam-timetable-page .et-page-subtitle {
    margin: 9px 0 0;
    color: var(--et-muted);
    font-size: 13px;
}

.exam-timetable-page .et-page-subtitle strong {
    color: var(--et-ink);
    font-weight: 700;
}

.exam-timetable-page .et-header-icon {
    display: inline-flex;
    width: 36px;
    height: 36px;
    align-items: center;
    justify-content: center;
    margin-right: 7px;
    border: 1px solid var(--et-line);
    border-radius: 11px;
    background: var(--et-cream);
    color: var(--et-forest);
    font-size: 18px;
    vertical-align: middle;
}

.exam-timetable-page .et-btn {
    display: inline-flex;
    min-height: 42px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 17px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 750;
    line-height: 1.3;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, color .2s ease,
        border-color .2s ease, transform .2s ease;
}

.exam-timetable-page .et-btn:hover {
    transform: translateY(-1px);
}

.exam-timetable-page .et-btn:focus-visible,
.exam-timetable-page .et-form-control:focus-visible,
.exam-timetable-page .et-checkbox:focus-visible {
    outline: 3px solid rgba(82, 127, 112, .28);
    outline-offset: 2px;
}

.exam-timetable-page .et-btn-primary {
    border-color: var(--et-forest);
    background: var(--et-forest);
    color: #fffefa;
}

.exam-timetable-page .et-btn-primary:hover {
    border-color: var(--et-ink-dark);
    background: var(--et-ink-dark);
    color: #fffefa;
}

.exam-timetable-page .et-btn-secondary {
    border-color: var(--et-line);
    background: var(--et-paper);
    color: var(--et-ink);
}

.exam-timetable-page .et-btn-secondary:hover {
    border-color: var(--et-sage);
    background: var(--et-cream);
    color: var(--et-ink-dark);
}

.exam-timetable-page .et-btn-cream {
    border-color: var(--et-cream-dark);
    background: var(--et-cream-dark);
    color: var(--et-ink);
}

.exam-timetable-page .et-btn-cream:hover {
    background: #fff5dc;
    color: var(--et-ink-dark);
}

.exam-timetable-page .et-btn-danger {
    border-color: #efd3ce;
    background: var(--et-red-soft);
    color: var(--et-red);
}

.exam-timetable-page .et-btn-danger:hover {
    border-color: var(--et-red);
    background: var(--et-red);
    color: #fff;
}

.exam-timetable-page .et-panel {
    margin-bottom: 22px;
    overflow: hidden;
    border: 1px solid var(--et-line);
    border-radius: var(--et-radius);
    background: var(--et-paper);
    box-shadow: var(--et-shadow);
}

.exam-timetable-page .et-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 22px;
    border-bottom: 1px solid var(--et-line);
    background: rgba(245, 243, 233, .56);
}

.exam-timetable-page .et-panel-heading {
    display: flex;
    min-width: 0;
    align-items: center;
    gap: 12px;
}

.exam-timetable-page .et-panel-icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: var(--et-paper);
    color: var(--et-forest);
    font-size: 17px;
}

.exam-timetable-page .et-panel-icon.et-icon-blue {
    background: var(--et-blue-soft);
    color: var(--et-blue);
}

.exam-timetable-page .et-panel-icon.et-icon-wood {
    background: var(--et-amber-soft);
    color: var(--et-wood);
}

.exam-timetable-page .et-panel-icon.et-icon-red {
    background: var(--et-red-soft);
    color: var(--et-red);
}

.exam-timetable-page .et-panel-icon.et-icon-green {
    background: var(--et-green-soft);
    color: var(--et-green);
}

.exam-timetable-page .et-panel-title {
    margin: 0;
    color: var(--et-ink);
    font-size: 15px;
    font-weight: 800;
    line-height: 1.4;
}

.exam-timetable-page .et-panel-description {
    margin: 3px 0 0;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-panel-body {
    padding: 22px;
}

.exam-timetable-page .et-panel-body.et-no-padding {
    padding: 0;
}

.exam-timetable-page .et-count {
    display: inline-flex;
    min-width: 34px;
    min-height: 29px;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 10px;
    border: 1px solid #d6e3d8;
    border-radius: 8px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-count.et-count-neutral {
    border-color: var(--et-line);
    background: var(--et-cream);
    color: var(--et-ink);
}

.exam-timetable-page .et-count.et-count-red {
    border-color: #efd3ce;
    background: var(--et-red-soft);
    color: var(--et-red);
}

.exam-timetable-page .et-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 22px;
    padding: 16px 18px;
    border: 1px solid var(--et-line);
    border-left: 4px solid var(--et-sage);
    border-radius: 12px;
    background: var(--et-paper);
    color: var(--et-text);
}

.exam-timetable-page .et-alert > i {
    margin-top: 2px;
    font-size: 18px;
}

.exam-timetable-page .et-alert-title {
    margin: 0 0 4px;
    color: var(--et-ink);
    font-size: 13px;
    font-weight: 800;
}

.exam-timetable-page .et-alert-copy {
    margin: 0;
    color: var(--et-muted);
    font-size: 12px;
}

.exam-timetable-page .et-alert-danger {
    border-left-color: var(--et-red);
    background: #fffaf9;
}

.exam-timetable-page .et-alert-danger > i,
.exam-timetable-page .et-alert-danger .et-alert-title {
    color: var(--et-red);
}

.exam-timetable-page .et-alert-info {
    border-color: #d9e3e9;
    border-left-color: var(--et-blue);
    background: #f6f9fb;
}

.exam-timetable-page .et-alert-info > i {
    color: var(--et-blue);
}

.exam-timetable-page .et-alert-warning {
    border-color: #ead8bd;
    border-left-color: var(--et-wood);
    background: #fcf7ee;
}

.exam-timetable-page .et-alert-warning > i,
.exam-timetable-page .et-alert-warning .et-alert-title {
    color: var(--et-wood);
}

.exam-timetable-page .et-error-list {
    margin: 8px 0 0;
    padding-left: 20px;
    color: var(--et-text);
    font-size: 12px;
}

.exam-timetable-page .et-error-list li + li {
    margin-top: 4px;
}

.exam-timetable-page .et-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.exam-timetable-page .et-info-item {
    min-width: 0;
    padding: 16px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: #fffefa;
}

.exam-timetable-page .et-info-label {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;
    color: var(--et-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.exam-timetable-page .et-info-label i {
    color: var(--et-wood);
    font-size: 13px;
}

.exam-timetable-page .et-info-value {
    overflow-wrap: anywhere;
    color: var(--et-ink);
    font-size: 14px;
    font-weight: 750;
}

.exam-timetable-page .et-info-value.et-value-missing {
    color: var(--et-red);
}

.exam-timetable-page .et-slot-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.exam-timetable-page .et-slot-card {
    height: 100%;
    padding: 18px;
    border: 1px solid var(--et-line);
    border-radius: 13px;
    background: var(--et-paper);
    transition: border-color .2s ease, transform .2s ease;
}

.exam-timetable-page .et-slot-card:hover {
    transform: translateY(-2px);
    border-color: #c4d2c7;
}

.exam-timetable-page .et-slot-title {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 15px;
    color: var(--et-ink);
    font-size: 13px;
    font-weight: 800;
}

.exam-timetable-page .et-slot-number {
    display: inline-flex;
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 13px;
}

.exam-timetable-page .et-slot-line {
    padding: 9px 0;
    border-top: 1px dashed var(--et-line);
}

.exam-timetable-page .et-slot-label {
    display: block;
    margin-bottom: 3px;
    color: var(--et-muted);
    font-size: 10px;
    font-weight: 750;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.exam-timetable-page .et-slot-time {
    color: var(--et-ink);
    font-size: 13px;
    font-weight: 750;
}

.exam-timetable-page .et-small-note {
    margin: 16px 0 0;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-class-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}

.exam-timetable-page .et-class-card {
    display: flex;
    min-width: 0;
    align-items: center;
    gap: 12px;
    height: 100%;
    padding: 15px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: var(--et-paper);
    transition: border-color .2s ease, background .2s ease;
}

.exam-timetable-page .et-class-card:hover {
    border-color: #c7d5c9;
    background: #fbfcf7;
}

.exam-timetable-page .et-class-icon {
    display: inline-flex;
    width: 39px;
    height: 39px;
    flex: 0 0 39px;
    align-items: center;
    justify-content: center;
    border: 1px solid #dce7dc;
    border-radius: 11px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 17px;
}

.exam-timetable-page .et-class-name {
    overflow-wrap: anywhere;
    color: var(--et-ink);
    font-size: 13px;
    font-weight: 800;
}

.exam-timetable-page .et-class-section {
    margin-top: 3px;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.exam-timetable-page .et-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--et-text);
    font-size: 12px;
}

.exam-timetable-page .et-table thead th {
    padding: 14px 16px;
    border-bottom: 1px solid var(--et-line);
    background: #f5f3e9;
    color: var(--et-ink);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .6px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.exam-timetable-page .et-table tbody td {
    padding: 15px 16px;
    border-bottom: 1px solid #eeebe2;
    vertical-align: middle;
}

.exam-timetable-page .et-table tbody tr:last-child td {
    border-bottom: 0;
}

.exam-timetable-page .et-table tbody tr {
    transition: background .15s ease;
}

.exam-timetable-page .et-table tbody tr:hover {
    background: #fbfaf5;
}

.exam-timetable-page .et-table tbody td:first-child,
.exam-timetable-page .et-table thead th:first-child {
    padding-left: 22px;
}

.exam-timetable-page .et-table-primary {
    color: var(--et-ink);
    font-weight: 750;
}

.exam-timetable-page .et-table-muted {
    margin-top: 3px;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-mark-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 9px;
    border: 1px solid var(--et-line);
    border-radius: 7px;
    background: var(--et-cream);
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.exam-timetable-page .et-duration {
    color: var(--et-ink);
    font-weight: 750;
    white-space: nowrap;
}

.exam-timetable-page .et-empty-state {
    padding: 25px 18px;
    border: 1px dashed var(--et-line);
    border-radius: 12px;
    background: #fcfbf6;
    color: var(--et-muted);
    font-size: 12px;
    text-align: center;
}

.exam-timetable-page .et-empty-icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    border-radius: 12px;
    background: var(--et-cream);
    color: var(--et-sage);
    font-size: 17px;
}

.exam-timetable-page .et-holiday-list {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.exam-timetable-page .et-holiday-chip {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    max-width: 100%;
    padding: 8px 12px;
    border: 1px solid #efd8d3;
    border-radius: 9px;
    background: #fff9f7;
    color: var(--et-text);
    font-size: 11px;
}

.exam-timetable-page .et-holiday-chip > i {
    color: var(--et-red);
}

.exam-timetable-page .et-holiday-name {
    color: var(--et-muted);
}

.exam-timetable-page .et-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.exam-timetable-page .et-field-label {
    display: block;
    margin-bottom: 9px;
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-timetable-page .et-required {
    color: var(--et-red);
}

.exam-timetable-page .et-form-control {
    display: block;
    width: 100%;
    min-height: 45px;
    padding: 10px 13px;
    border: 1px solid #dcd9cd;
    border-radius: 10px;
    outline: none;
    background-color: #fffefa;
    color: var(--et-ink);
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.exam-timetable-page .et-form-control:focus {
    border-color: var(--et-sage);
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .12);
}

.exam-timetable-page .et-form-control.is-invalid {
    border-color: var(--et-red);
}

.exam-timetable-page .et-form-control option {
    color: var(--et-ink);
}

.exam-timetable-page .et-invalid-feedback {
    margin-top: 6px;
    color: var(--et-red);
    font-size: 11px;
}

.exam-timetable-page .et-field-help {
    display: block;
    margin-top: 8px;
    color: var(--et-muted);
    font-size: 11px;
    line-height: 1.6;
}

.exam-timetable-page .et-date-summary {
    padding: 16px;
    border: 1px solid var(--et-line);
    border-radius: 11px;
    background: #f8f7f1;
}

.exam-timetable-page .et-date-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    font-size: 12px;
}

.exam-timetable-page .et-date-row + .et-date-row {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed var(--et-line);
}

.exam-timetable-page .et-date-label {
    color: var(--et-muted);
}

.exam-timetable-page .et-date-value {
    color: var(--et-ink);
    font-weight: 800;
    text-align: right;
}

.exam-timetable-page .et-replace-box {
    padding: 17px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: #fcfbf7;
}

.exam-timetable-page .et-check-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.exam-timetable-page .et-checkbox {
    width: 17px;
    height: 17px;
    flex: 0 0 17px;
    margin: 3px 0 0;
    accent-color: var(--et-forest);
    cursor: pointer;
}

.exam-timetable-page .et-check-label {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
}

.exam-timetable-page .et-check-description {
    margin: 7px 0 0 27px;
    color: var(--et-muted);
    font-size: 11px;
    line-height: 1.6;
}

.exam-timetable-page .et-action-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid var(--et-line);
}

.exam-timetable-page .et-steps-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
}

.exam-timetable-page .et-step {
    height: 100%;
    padding: 19px 15px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: #fffefa;
    text-align: center;
}

.exam-timetable-page .et-step-number {
    display: inline-flex;
    width: 43px;
    height: 43px;
    align-items: center;
    justify-content: center;
    margin-bottom: 13px;
    border: 1px solid #d5e3d7;
    border-radius: 13px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
}

.exam-timetable-page .et-step-title {
    margin: 0 0 8px;
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-timetable-page .et-step-copy {
    margin: 0;
    color: var(--et-muted);
    font-size: 11px;
    line-height: 1.7;
}

.exam-timetable-page .et-footer-note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 18px;
    padding: 13px 15px;
    border: 1px solid var(--et-line);
    border-radius: 10px;
    background: #f8f7f1;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-footer-note i {
    color: var(--et-sage);
}

.exam-timetable-page .et-btn:disabled {
    opacity: .75;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 1199.98px) {
    .exam-timetable-page .et-class-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .exam-timetable-page .et-steps-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 991.98px) {
    .exam-timetable-page {
        padding: 20px;
    }

    .exam-timetable-page .et-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .exam-timetable-page .et-class-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .exam-timetable-page .et-slot-grid {
        grid-template-columns: 1fr;
    }

    .exam-timetable-page .et-slot-card {
        padding: 16px;
    }
}

@media (max-width: 767.98px) {
    .exam-timetable-page {
        padding: 15px;
        border-radius: 14px;
    }

    .exam-timetable-page .et-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
        padding-left: 14px;
    }

    .exam-timetable-page .et-page-title {
        font-size: 26px;
    }

    .exam-timetable-page .et-header-actions,
    .exam-timetable-page .et-header-actions .et-btn {
        width: 100%;
    }

    .exam-timetable-page .et-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .exam-timetable-page .et-panel-body {
        padding: 16px;
    }

    .exam-timetable-page .et-panel-body.et-no-padding {
        padding: 0;
    }

    .exam-timetable-page .et-info-grid,
    .exam-timetable-page .et-form-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .exam-timetable-page .et-class-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .exam-timetable-page .et-class-card {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px;
    }

    .exam-timetable-page .et-steps-grid {
        gap: 10px;
    }

    .exam-timetable-page .et-step {
        padding: 15px 11px;
    }

    .exam-timetable-page .et-table {
        min-width: 650px;
    }

    .exam-timetable-page .et-action-row {
        flex-direction: column-reverse;
    }

    .exam-timetable-page .et-action-row .et-btn {
        width: 100%;
    }
}

@media (max-width: 420px) {
    .exam-timetable-page .et-class-grid,
    .exam-timetable-page .et-steps-grid {
        grid-template-columns: 1fr;
    }

    .exam-timetable-page .et-panel-heading {
        align-items: flex-start;
    }

    .exam-timetable-page .et-count {
        font-size: 10px;
    }

    .exam-timetable-page .et-date-row {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .exam-timetable-page *,
    .exam-timetable-page *::before,
    .exam-timetable-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

<div class="exam-timetable-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}
    <header class="et-page-header">

        <div class="et-header-copy">

            <div class="et-eyebrow">
                <i class="bi bi-journal-check"></i>
                School Diary / Examination Management
            </div>

            <h1 class="et-page-title">
                <span class="et-header-icon">
                    <i class="bi bi-calendar2-week"></i>
                </span>
                Generate Exam Timetable
            </h1>

            <p class="et-page-subtitle">
                Examination:
                <strong>{{ $exam->exam_name ?? 'Examination' }}</strong>

                @if(!empty($exam->academic_year))
                    <span class="mx-1">·</span>
                    Academic Year:
                    <strong>{{ $exam->academic_year }}</strong>
                @endif
            </p>

        </div>

        <div class="et-header-actions">

            <a
                href="{{ route('admin.exam-schedules.index', $exam) }}"
                class="et-btn et-btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Timetable
            </a>

        </div>

    </header>


    {{-- =====================================================
        VALIDATION / ERROR MESSAGES
    ====================================================== --}}
    @if($errors->any())

        <div class="et-alert et-alert-danger" role="alert">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>

                <h3 class="et-alert-title">
                    Timetable could not be generated
                </h3>

                <p class="et-alert-copy">
                    Please review the following information and try again.
                </p>

                <ul class="et-error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
        EXAMINATION INFORMATION
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-blue">
                    <i class="bi bi-info-circle"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">
                        Examination Information
                    </h2>

                    <p class="et-panel-description">
                        Review the examination details before scheduling.
                    </p>
                </div>

            </div>

        </div>

        <div class="et-panel-body">

            <div class="et-info-grid">

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-journal-bookmark"></i>
                        Examination
                    </div>

                    <div class="et-info-value">
                        {{ $exam->exam_name ?? '-' }}
                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-calendar3"></i>
                        Academic Year
                    </div>

                    <div class="et-info-value">
                        {{ $exam->academic_year ?? '-' }}
                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-calendar-event"></i>
                        Exam Start Date
                    </div>

                    <div class="et-info-value {{ $exam->start_date ? '' : 'et-value-missing' }}">

                        @if($exam->start_date)
                            {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}
                        @else
                            Not configured
                        @endif

                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-calendar-check"></i>
                        Exam End Date
                    </div>

                    <div class="et-info-value {{ $exam->end_date ? '' : 'et-value-missing' }}">

                        @if($exam->end_date)
                            {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}
                        @else
                            Not configured
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        AUTOMATIC SCHEDULING INFORMATION
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-wood">
                    <i class="bi bi-clock-history"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">
                        Automatic Exam Time Management
                    </h2>

                    <p class="et-panel-description">
                        No manual sessions are required.
                    </p>
                </div>

            </div>

        </div>

        <div class="et-panel-body">

            <div class="et-alert et-alert-info">

                <i class="bi bi-magic"></i>

                <div>

                    <h3 class="et-alert-title">
                        Time slots will be managed automatically
                    </h3>

                    <p class="et-alert-copy">
                        After you select the number of papers per day,
                        the system automatically assigns examination time slots.
                        You do not need to create or select Session 1, Session 2
                        or Session 3.
                    </p>

                </div>

            </div>

            <div class="et-slot-grid">

                {{-- 1 PAPER --}}
                <div class="et-slot-card">

                    <h3 class="et-slot-title">
                        <span class="et-slot-number">
                            <i class="bi bi-1-circle"></i>
                        </span>
                        1 Paper Per Day
                    </h3>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 1</span>
                        <div class="et-slot-time">10:00 AM – 11:00 AM*</div>
                    </div>

                </div>

                {{-- 2 PAPERS --}}
                <div class="et-slot-card">

                    <h3 class="et-slot-title">
                        <span class="et-slot-number">
                            <i class="bi bi-2-circle"></i>
                        </span>
                        2 Papers Per Day
                    </h3>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 1</span>
                        <div class="et-slot-time">10:00 AM – 11:00 AM*</div>
                    </div>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 2</span>
                        <div class="et-slot-time">01:00 PM – 02:00 PM*</div>
                    </div>

                </div>

                {{-- 3 PAPERS --}}
                <div class="et-slot-card">

                    <h3 class="et-slot-title">
                        <span class="et-slot-number">
                            <i class="bi bi-3-circle"></i>
                        </span>
                        3 Papers Per Day
                    </h3>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 1</span>
                        <div class="et-slot-time">10:00 AM – 11:00 AM*</div>
                    </div>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 2</span>
                        <div class="et-slot-time">01:00 PM – 02:00 PM*</div>
                    </div>

                    <div class="et-slot-line">
                        <span class="et-slot-label">Paper 3</span>
                        <div class="et-slot-time">03:00 PM – 04:00 PM*</div>
                    </div>

                </div>

            </div>

            <p class="et-small-note">
                <i class="bi bi-info-circle me-1"></i>
                The actual end time is calculated from the subject's configured
                duration. The system also checks that papers do not overlap.
            </p>

        </div>

    </section>


    {{-- =====================================================
        CLASSES INCLUDED
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-green">
                    <i class="bi bi-people"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">Classes Included</h2>

                    <p class="et-panel-description">
                        Classes that will receive examination schedules.
                    </p>
                </div>

            </div>

            <span class="et-count">
                {{ $examClasses->count() }}
                {{ $examClasses->count() === 1 ? 'Class' : 'Classes' }}
            </span>

        </div>

        <div class="et-panel-body">

            @if($examClasses->count())

                <div class="et-class-grid">

                    @foreach($examClasses as $examClass)

                        @php
                            $schoolClass = $examClass->schoolClass;
                        @endphp

                        <div class="et-class-card">

                            <span class="et-class-icon">
                                <i class="bi bi-mortarboard"></i>
                            </span>

                            <div>

                                <div class="et-class-name">
                                    {{ $schoolClass->class_name ?? $schoolClass->name ?? 'Class' }}
                                </div>

                                @if(!empty($schoolClass->section))
                                    <div class="et-class-section">
                                        Section {{ $schoolClass->section }}
                                    </div>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="et-empty-state">
                    <span class="et-empty-icon">
                        <i class="bi bi-people"></i>
                    </span>

                    <div class="et-alert-title">
                        No classes added
                    </div>

                    No classes have been added to this examination.

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
        EXAM SUBJECTS
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-blue">
                    <i class="bi bi-journal-text"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">Exam Subjects</h2>

                    <p class="et-panel-description">
                        Configured subjects, marks and examination duration.
                    </p>
                </div>

            </div>

            <span class="et-count et-count-neutral">
                {{ $examSubjects->count() }}
                {{ $examSubjects->count() === 1 ? 'Subject' : 'Subjects' }}
            </span>

        </div>

        <div class="et-panel-body et-no-padding">

            @if($examSubjects->count())

                <div class="et-table-wrap">

                    <table class="et-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Maximum Marks</th>
                                <th>Passing Marks</th>
                                <th>Duration</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($examSubjects as $index => $examSubject)

                                <tr>

                                    <td>
                                        <span class="et-mark-chip">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>

                                    <td>

                                        @php
                                            $schoolClass = $examSubject->schoolClass;
                                        @endphp

                                        <div class="et-table-primary">
                                            {{ $schoolClass->class_name ?? $schoolClass->name ?? '-' }}
                                        </div>

                                        @if(!empty($schoolClass->section))
                                            <div class="et-table-muted">
                                                Section {{ $schoolClass->section }}
                                            </div>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="et-table-primary">
                                            {{ $examSubject->subject->subject_name ?? 'Subject' }}
                                        </div>

                                        @if(!empty($examSubject->subject->subject_code))
                                            <div class="et-table-muted">
                                                Code: {{ $examSubject->subject->subject_code }}
                                            </div>
                                        @endif

                                    </td>

                                    <td>
                                        <span class="et-mark-chip">
                                            <i class="bi bi-award"></i>
                                            {{ $examSubject->maximum_marks ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $examSubject->passing_marks ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="et-duration">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $examSubject->duration_minutes ?? 60 }} min
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div style="padding: 22px;">

                    <div class="et-empty-state">
                        <span class="et-empty-icon">
                            <i class="bi bi-journal-x"></i>
                        </span>

                        <div class="et-alert-title">
                            No exam subjects configured
                        </div>

                        No exam subjects have been configured for this examination.

                    </div>

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
        HOLIDAYS
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-red">
                    <i class="bi bi-calendar-x"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">
                        Holidays Excluded From Scheduling
                    </h2>

                    <p class="et-panel-description">
                        Dates that will be skipped by automatic scheduling.
                    </p>
                </div>

            </div>

            <span class="et-count et-count-red">
                {{ $holidays->count() }}
            </span>

        </div>

        <div class="et-panel-body">

            <div class="et-footer-note" style="margin-top: 0; margin-bottom: 17px;">
                <i class="bi bi-info-circle"></i>

                <span>
                    Saturday and Sunday are automatically excluded.
                    The following configured examination holidays are also skipped.
                </span>
            </div>

            @if($holidays->count())

                <div class="et-holiday-list">

                    @foreach($holidays as $holiday)

                        <span class="et-holiday-chip">

                            <i class="bi bi-calendar-x"></i>

                            <strong>
                                {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') }}
                            </strong>

                            @if(!empty($holiday->holiday_name))
                                <span class="et-holiday-name">
                                    — {{ $holiday->holiday_name }}
                                </span>
                            @endif

                        </span>

                    @endforeach

                </div>

            @else

                <div class="et-empty-state">
                    <span class="et-empty-icon">
                        <i class="bi bi-calendar-check"></i>
                    </span>

                    <div class="et-alert-title">
                        No additional holidays
                    </div>

                    No additional examination holidays configured.

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
        GENERATION SETTINGS
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-wood">
                    <i class="bi bi-sliders"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">Generation Settings</h2>

                    <p class="et-panel-description">
                        Choose the scheduling preferences for this examination.
                    </p>
                </div>

            </div>

        </div>

        <div class="et-panel-body">

            <form
                method="POST"
                action="{{ route('admin.exam-schedules.generate', $exam) }}"
                id="generateTimetableForm"
            >

                @csrf

                <div class="et-form-grid">

                    {{-- Papers Per Day --}}
                    <div>

                        <label
                            for="papers_per_day"
                            class="et-field-label"
                        >
                            Papers Per Day
                            <span class="et-required">*</span>
                        </label>

                        <select
                            name="papers_per_day"
                            id="papers_per_day"
                            class="et-form-control {{ $errors->has('papers_per_day') ? 'is-invalid' : '' }}"
                            required
                        >

                            <option value="">
                                Select papers per day
                            </option>

                            <option
                                value="1"
                                {{ old('papers_per_day') == '1' ? 'selected' : '' }}
                            >
                                1 Paper Per Day
                            </option>

                            <option
                                value="2"
                                {{ old('papers_per_day') == '2' ? 'selected' : '' }}
                            >
                                2 Papers Per Day
                            </option>

                            <option
                                value="3"
                                {{ old('papers_per_day') == '3' ? 'selected' : '' }}
                            >
                                3 Papers Per Day
                            </option>

                        </select>

                        @error('papers_per_day')
                            <div class="et-invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <span class="et-field-help">
                            The selected number applies independently to every class.
                            A class stops receiving papers after all of its subjects
                            are completed.
                        </span>

                    </div>


                    {{-- Automatic Exam Period --}}
                    <div>

                        <label class="et-field-label">
                            Automatic Exam Period
                        </label>

                        <div class="et-date-summary">

                            <div class="et-date-row">

                                <span class="et-date-label">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Start Date
                                </span>

                                <strong class="et-date-value">

                                    @if($exam->start_date)
                                        {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}
                                    @else
                                        Not configured
                                    @endif

                                </strong>

                            </div>

                            <div class="et-date-row">

                                <span class="et-date-label">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    End Date
                                </span>

                                <strong class="et-date-value">

                                    @if($exam->end_date)
                                        {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}
                                    @else
                                        Not configured
                                    @endif

                                </strong>

                            </div>

                        </div>

                        <span class="et-field-help">
                            The system automatically uses available weekdays
                            between these dates.
                        </span>

                    </div>


                    {{-- Replace Existing Timetable --}}
                    <div style="grid-column: 1 / -1;">

                        <div class="et-replace-box">

                            <div class="et-check-row">

                                <input
                                    class="et-checkbox"
                                    type="checkbox"
                                    name="replace_existing"
                                    value="1"
                                    id="replace_existing"
                                    {{ old('replace_existing') ? 'checked' : '' }}
                                >

                                <label
                                    class="et-check-label"
                                    for="replace_existing"
                                >
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Replace Existing Timetable
                                </label>

                            </div>

                            <p class="et-check-description">
                                If enabled, the existing timetable for this examination
                                will be deleted and regenerated from the beginning.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- IMPORTANT GENERATION NOTICE --}}
                <div class="et-alert et-alert-warning" style="margin-top: 23px; margin-bottom: 0;">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <div>

                        <h3 class="et-alert-title">
                            Before generating
                        </h3>

                        <ul class="et-error-list">

                            <li>
                                Subjects are scheduled separately for each class.
                            </li>

                            <li>
                                Saturday, Sunday and configured holidays are skipped.
                            </li>

                            <li>
                                No teacher is assigned automatically. Teachers can be
                                assigned later from the timetable Edit page.
                            </li>

                            <li>
                                The system will stop without saving if every subject
                                cannot fit within the selected examination period.
                            </li>

                            <li>
                                Existing timetable data is changed only when
                                <strong>Replace Existing Timetable</strong> is selected.
                            </li>

                        </ul>

                    </div>

                </div>


                {{-- ACTION BUTTONS --}}
                <div class="et-action-row">

                    <a
                        href="{{ route('admin.exam-schedules.index', $exam) }}"
                        class="et-btn et-btn-secondary"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="et-btn et-btn-primary"
                        id="generateButton"
                    >
                        <i class="bi bi-magic"></i>
                        Generate Timetable
                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- =====================================================
        HOW AUTOMATIC GENERATION WORKS
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-green">
                    <i class="bi bi-diagram-3"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">
                        How Automatic Scheduling Works
                    </h2>

                    <p class="et-panel-description">
                        Four steps from exam dates to the completed timetable.
                    </p>
                </div>

            </div>

        </div>

        <div class="et-panel-body">

            <div class="et-steps-grid">

                {{-- Step 1 --}}
                <div class="et-step">

                    <span class="et-step-number">1</span>

                    <h3 class="et-step-title">Exam Dates</h3>

                    <p class="et-step-copy">
                        The system uses the configured start and end dates
                        automatically.
                    </p>

                </div>

                {{-- Step 2 --}}
                <div class="et-step">

                    <span class="et-step-number">2</span>

                    <h3 class="et-step-title">Automatic Time Slots</h3>

                    <p class="et-step-copy">
                        One, two or three paper slots are created automatically
                        according to your selection.
                    </p>

                </div>

                {{-- Step 3 --}}
                <div class="et-step">

                    <span class="et-step-number">3</span>

                    <h3 class="et-step-title">Class-Wise Subjects</h3>

                    <p class="et-step-copy">
                        Every class moves independently through its own subject list.
                    </p>

                </div>

                {{-- Step 4 --}}
                <div class="et-step">

                    <span class="et-step-number">4</span>

                    <h3 class="et-step-title">Complete Timetable</h3>

                    <p class="et-step-copy">
                        The timetable is saved only after all subjects are
                        successfully scheduled.
                    </p>

                </div>

            </div>

            <div class="et-footer-note">
                <i class="bi bi-shield-check"></i>

                <span>
                    Review your examination dates, configured subjects, holidays
                    and generation settings before submitting.
                </span>
            </div>

        </div>

    </section>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('generateTimetableForm');
    const button = document.getElementById('generateButton');
    const papersPerDay = document.getElementById('papers_per_day');
    const replaceExisting = document.getElementById('replace_existing');

    /*
    |--------------------------------------------------------------------------
    | Submit Protection
    |--------------------------------------------------------------------------
    */

    if (form && button) {

        form.addEventListener('submit', function (event) {

            /*
            |--------------------------------------------------------------------------
            | Validate Papers Per Day
            |--------------------------------------------------------------------------
            */

            if (!papersPerDay || !papersPerDay.value) {

                event.preventDefault();

                if (papersPerDay) {
                    papersPerDay.focus();
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Confirmation When Replacing Existing Timetable
            |--------------------------------------------------------------------------
            */

            if (replaceExisting && replaceExisting.checked) {

                const confirmed = confirm(
                    'Are you sure you want to replace the existing timetable? All existing timetable entries for this examination will be deleted and regenerated.'
                );

                if (!confirmed) {
                    event.preventDefault();
                    return;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submit
            |--------------------------------------------------------------------------
            */

            button.disabled = true;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
                'Generating Timetable...';

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Papers Per Day Change
    |--------------------------------------------------------------------------
    */

    if (papersPerDay) {

        papersPerDay.addEventListener('change', function () {

            const value = this.value;

            if (!value) {
                return;
            }

            /*
             * No AJAX is required.
             * Time slots are managed by the controller.
             */

        });

    }

});
</script>

@endpush

@endsection
