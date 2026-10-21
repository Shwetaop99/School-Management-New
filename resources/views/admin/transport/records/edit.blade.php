
@extends('layouts.app')

@section('title', 'Edit Transport Record | Admin')
@section('page-title', 'Edit Transport Record')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — EDIT TRANSPORT RECORD
    ========================================================= */

    .transport-create-page {
        --diary-bg: #f5f3e9;
        --diary-paper: #fffefa;
        --diary-ink: #263b35;
        --diary-green: #34775a;
        --diary-dark-green: #245a43;
        --diary-green-light: #eaf3e9;
        --diary-wood: #a87543;
        --diary-cyan: #527f79;
        --diary-red: #bd5148;
        --diary-border: #e5e1d3;
        --diary-muted: #7a8177;
        --diary-soft: #f8f7f0;

        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 30px;
        background: var(--diary-bg);
        color: var(--diary-ink);
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .transport-create-page *,
    .transport-create-page *::before,
    .transport-create-page *::after {
        box-sizing: border-box;
    }

    .transport-create-page .container-fluid {
        padding-left: 20px;
        padding-right: 20px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .transport-create-page .transport-create-header {
        position: relative;
        overflow: hidden;
        padding: 19px 22px;
        margin-bottom: 17px;
        background: var(--diary-paper);
        color: var(--diary-ink);
        border: 1px solid var(--diary-border);
        border-left: 4px solid var(--diary-green);
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .transport-create-page .transport-create-header::before,
    .transport-create-page .transport-create-header::after {
        content: none;
    }

    .transport-create-page .create-header-content {
        position: relative;
        z-index: 1;
    }

    .transport-create-page .create-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 11px;
        color: var(--diary-muted);
        font-size: 9px;
        font-weight: 650;
        letter-spacing: .65px;
        text-transform: uppercase;
    }

    .transport-create-page .create-breadcrumb a {
        color: var(--diary-green);
        text-decoration: none;
        transition: color .18s ease;
    }

    .transport-create-page .create-breadcrumb a:hover {
        color: var(--diary-dark-green);
        text-decoration: underline;
    }

    .transport-create-page .create-breadcrumb i {
        color: var(--diary-wood);
        font-size: 9px;
    }

    .transport-create-page .create-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .transport-create-page .create-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 43px;
        height: 43px;
        border: 1px solid #d6e4d4;
        border-radius: 9px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 19px;
    }

    .transport-create-page .create-title {
        margin: 0;
        color: var(--diary-ink);
        font-size: 22px;
        font-weight: 750;
        line-height: 1.3;
        letter-spacing: -.45px;
    }

    .transport-create-page .create-subtitle {
        margin: 5px 0 0;
        color: var(--diary-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .transport-create-page .transport-form-card {
        overflow: visible;
        background: var(--diary-paper);
        border: 1px solid var(--diary-border);
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .transport-create-page .form-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--diary-border);
        background: #fcfbf5;
        border-radius: 10px 10px 0 0;
    }

    .transport-create-page .form-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .transport-create-page .form-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 35px;
        height: 35px;
        border: 1px solid #d6e4d4;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 15px;
    }

    .transport-create-page .form-card-heading h5 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 13px;
        font-weight: 750;
    }

    .transport-create-page .form-card-heading p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.45;
    }

    .transport-create-page .required-note {
        color: var(--diary-muted);
        font-size: 10px;
    }

    .transport-create-page .required-note span,
    .transport-create-page label .required {
        color: var(--diary-red);
    }

    /* =========================================================
       FORM BODY
    ========================================================= */

    .transport-create-page .transport-form-body {
        padding: 21px 21px 18px;
    }

    .transport-create-page .form-section {
        margin-bottom: 0;
    }

    .transport-create-page .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .transport-create-page .section-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border: 1px solid #d9e5d6;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 14px;
    }

    .transport-create-page .section-heading h6 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4;
    }

    .transport-create-page .section-heading span {
        display: block;
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM GROUPS
    ========================================================= */

    .transport-create-page .transport-form-group {
        min-width: 0;
        margin-bottom: 17px;
    }

    .transport-create-page .transport-form-group > label {
        display: block;
        margin-bottom: 6px;
        color: var(--diary-ink);
        font-size: 10.5px;
        font-weight: 700;
    }

    .transport-create-page .transport-form-group label .required {
        margin-left: 2px;
    }

    .transport-create-page .transport-input-wrapper {
        position: relative;
        min-width: 0;
    }

    .transport-create-page .transport-input-icon {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 2;
        transform: translateY(-50%);
        color: var(--diary-cyan);
        font-size: 12px;
        pointer-events: none;
    }

    .transport-create-page .transport-form-control,
    .transport-create-page .transport-form-select,
    .transport-create-page .transport-form-textarea {
        display: block;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        margin: 0;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: #fffefa;
        color: var(--diary-ink);
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        outline: none;
        box-shadow: none;
        transition: border-color .18s ease, background .18s ease,
                    box-shadow .18s ease;
    }

    .transport-create-page .transport-form-control,
    .transport-create-page .transport-form-select {
        height: 38px;
        padding: 0 11px;
    }

    .transport-create-page .transport-form-control.with-icon,
    .transport-create-page .transport-form-select.with-icon {
        padding-left: 34px;
    }

    .transport-create-page .transport-form-control::placeholder {
        color: #a1a398;
        font-size: 10.5px;
        font-weight: 400;
    }

    .transport-create-page .transport-form-control:hover,
    .transport-create-page .transport-form-select:hover {
        border-color: #c8d4c5;
        background: #fffefa;
    }

    .transport-create-page .transport-form-control:focus,
    .transport-create-page .transport-form-select:focus {
        border-color: var(--diary-green);
        background: #fffefa;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    /* =========================================================
       SEARCHABLE STUDENT SELECT
    ========================================================= */

    .transport-create-page .student-search-wrapper {
        position: relative;
        min-width: 0;
    }

    .transport-create-page .student-search-input {
        display: block;
        width: 100%;
        height: 38px;
        padding: 0 37px 0 34px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: #fffefa;
        color: var(--diary-ink);
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        outline: none;
        box-shadow: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .transport-create-page .student-search-input::placeholder {
        color: #a1a398;
        font-size: 10.5px;
    }

    .transport-create-page .student-search-input:hover {
        border-color: #c8d4c5;
    }

    .transport-create-page .student-search-input:focus {
        border-color: var(--diary-green);
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .transport-create-page .student-search-icon,
    .transport-create-page .student-search-arrow {
        position: absolute;
        top: 50%;
        z-index: 2;
        transform: translateY(-50%);
        color: var(--diary-cyan);
        font-size: 12px;
        pointer-events: none;
    }

    .transport-create-page .student-search-icon {
        left: 12px;
    }

    .transport-create-page .student-search-arrow {
        right: 12px;
        color: var(--diary-muted);
        font-size: 10px;
    }

    .transport-create-page .student-dropdown {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        left: 0;
        z-index: 1000;
        display: none;
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid var(--diary-border);
        border-radius: 8px;
        background: var(--diary-paper);
        box-shadow: 0 8px 22px rgba(38, 59, 53, .13);
    }

    .transport-create-page .student-dropdown.show {
        display: block;
    }

    .transport-create-page .student-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 11px;
        border-bottom: 1px solid #efede3;
        cursor: pointer;
        transition: background .15s ease;
    }

    .transport-create-page .student-option:last-child {
        border-bottom: none;
    }

    .transport-create-page .student-option:hover {
        background: var(--diary-green-light);
    }

    .transport-create-page .student-option-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 31px;
        height: 31px;
        border: 1px solid #d6e4d4;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 12px;
        font-weight: 750;
    }

    .transport-create-page .student-option-info {
        flex: 1;
        min-width: 0;
    }

    .transport-create-page .student-option-name {
        overflow: hidden;
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .transport-create-page .student-option-meta {
        margin-top: 3px;
        color: var(--diary-muted);
        font-size: 9px;
        line-height: 1.5;
    }

    .transport-create-page .student-no-result {
        padding: 16px 12px;
        color: var(--diary-muted);
        font-size: 10.5px;
        text-align: center;
    }

    /* =========================================================
       SELECTED STUDENT DETAILS
    ========================================================= */

    .transport-create-page .selected-student-card {
        display: none;
        margin-top: 13px;
        padding: 15px;
        border: 1px solid #d8e4d4;
        border-radius: 9px;
        background: #f8faf3;
    }

    .transport-create-page .selected-student-card.show {
        display: block;
    }

    .transport-create-page .selected-student-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
        padding-bottom: 11px;
        border-bottom: 1px solid #e3e8d9;
    }

    .transport-create-page .selected-student-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border: 1px solid #d6e4d4;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 14px;
        font-weight: 750;
    }

    .transport-create-page .selected-student-header h6 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 11.5px;
        font-weight: 750;
    }

    .transport-create-page .selected-student-header p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 9.5px;
    }

    .transport-create-page .student-detail-box {
        min-height: 57px;
        padding: 9px 10px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
    }

    .transport-create-page .student-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 5px;
        color: var(--diary-muted);
        font-size: 9px;
        font-weight: 650;
    }

    .transport-create-page .student-detail-label i {
        color: var(--diary-green);
        font-size: 10px;
    }

    .transport-create-page .student-detail-value {
        overflow-wrap: anywhere;
        color: var(--diary-ink);
        font-size: 10.5px;
        font-weight: 700;
        line-height: 1.5;
    }

    /* =========================================================
       STATUS AND PAYMENT OPTIONS
    ========================================================= */

    .transport-create-page .status-options,
    .transport-create-page .payment-options {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .transport-create-page .status-option,
    .transport-create-page .payment-option {
        position: relative;
        flex: 1 1 100px;
        min-width: 0;
    }

    .transport-create-page .status-option input,
    .transport-create-page .payment-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
    }

    .transport-create-page .status-option label,
    .transport-create-page .payment-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        min-height: 38px;
        margin: 0;
        padding: 8px 10px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: #fffefa;
        color: var(--diary-ink);
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease,
                    box-shadow .18s ease;
    }

    .transport-create-page .status-option label i,
    .transport-create-page .payment-option label i {
        font-size: 12px;
    }

    .transport-create-page .active-option label,
    .transport-create-page .paid-option label {
        color: var(--diary-green);
    }

    .transport-create-page .active-option input:checked + label,
    .transport-create-page .paid-option input:checked + label {
        border-color: #a8c9a4;
        background: var(--diary-green-light);
        color: var(--diary-dark-green);
        box-shadow: 0 0 0 2px rgba(52, 119, 90, .07);
    }

    .transport-create-page .inactive-option label {
        color: var(--diary-red);
    }

    .transport-create-page .inactive-option input:checked + label {
        border-color: #dfb2ac;
        background: #fbefec;
        color: var(--diary-red);
        box-shadow: 0 0 0 2px rgba(189, 81, 72, .06);
    }

    .transport-create-page .pending-option label {
        color: var(--diary-wood);
    }

    .transport-create-page .pending-option input:checked + label {
        border-color: #ddc4a4;
        background: #f8f0e5;
        color: #865b30;
        box-shadow: 0 0 0 2px rgba(168, 117, 67, .06);
    }

    .transport-create-page .partial-option label {
        color: var(--diary-cyan);
    }

    .transport-create-page .partial-option input:checked + label {
        border-color: #b7d2cc;
        background: #eaf3f0;
        color: #35665e;
        box-shadow: 0 0 0 2px rgba(82, 127, 121, .06);
    }

    .transport-create-page .status-option input:focus-visible + label,
    .transport-create-page .payment-option input:focus-visible + label {
        outline: 2px solid var(--diary-green);
        outline-offset: 2px;
    }

    /* =========================================================
       VALIDATION AND HINTS
    ========================================================= */

    .transport-create-page .field-hint {
        margin-top: 5px;
        color: var(--diary-muted);
        font-size: 9.5px;
        line-height: 1.55;
    }

    .transport-create-page .transport-form-control.is-invalid,
    .transport-create-page .transport-form-select.is-invalid,
    .transport-create-page .student-search-input.is-invalid {
        border-color: #d99a92;
        background: #fff9f6;
    }

    .transport-create-page .transport-form-control.is-invalid:focus,
    .transport-create-page .transport-form-select.is-invalid:focus,
    .transport-create-page .student-search-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
    }

    .transport-create-page .field-error {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-top: 5px;
        color: var(--diary-red);
        font-size: 10px;
        line-height: 1.5;
    }

    .transport-create-page .field-error i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    .transport-create-page .form-divider {
        height: 1px;
        margin: 21px 0;
        background: var(--diary-border);
    }

    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .transport-create-page .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        padding: 14px 20px;
        border-top: 1px solid var(--diary-border);
        border-radius: 0 0 10px 10px;
        background: #fcfbf5;
    }

    .transport-create-page .btn-transport-cancel,
    .transport-create-page .btn-transport-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 8px 14px;
        border-radius: 7px;
        font-family: inherit;
        font-size: 10.5px;
        font-weight: 750;
        line-height: 1.4;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease,
                    color .18s ease, box-shadow .18s ease;
    }

    .transport-create-page .btn-transport-cancel {
        border: 1px solid var(--diary-border);
        background: var(--diary-paper);
        color: var(--diary-ink);
    }

    .transport-create-page .btn-transport-cancel:hover {
        border-color: #c9c4b4;
        background: #f4f1e6;
        color: var(--diary-dark-green);
    }

    .transport-create-page .btn-transport-save {
        border: 1px solid var(--diary-green);
        background: var(--diary-green);
        color: #fffefa;
        box-shadow: 0 2px 5px rgba(36, 90, 67, .12);
    }

    .transport-create-page .btn-transport-save:hover {
        border-color: var(--diary-dark-green);
        background: var(--diary-dark-green);
        color: #fffefa;
        box-shadow: 0 4px 9px rgba(36, 90, 67, .16);
    }

    .transport-create-page .btn-transport-cancel:focus-visible,
    .transport-create-page .btn-transport-save:focus-visible {
        outline: 2px solid var(--diary-wood);
        outline-offset: 3px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {
        .transport-create-page .transport-create-header {
            padding: 17px 18px;
        }

        .transport-create-page .transport-form-body {
            padding: 18px;
        }

        .transport-create-page .form-card-header {
            padding: 14px 17px;
        }

        .transport-create-page .form-actions {
            padding: 13px 17px;
        }
    }

    @media (max-width: 767.98px) {
        .transport-create-page .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }

        .transport-create-page .transport-create-header {
            margin-bottom: 12px;
        }

        .transport-create-page .transport-form-body {
            padding: 16px 14px;
        }

        .transport-create-page .form-card-header {
            align-items: flex-start;
            padding: 13px 14px;
        }

        .transport-create-page .status-option,
        .transport-create-page .payment-option {
            flex: 1 1 calc(50% - 7px);
        }

        .transport-create-page .form-divider {
            margin: 17px 0;
        }
    }

    @media (max-width: 480px) {
        .transport-create-page .transport-create-header {
            padding: 15px 13px;
        }

        .transport-create-page .create-title-row {
            align-items: flex-start;
            gap: 9px;
        }

        .transport-create-page .create-title-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .transport-create-page .create-title {
            font-size: 18px;
        }

        .transport-create-page .create-subtitle {
            font-size: 10px;
        }

        .transport-create-page .create-breadcrumb {
            font-size: 8px;
        }

        .transport-create-page .transport-form-body {
            padding: 14px 11px;
        }

        .transport-create-page .form-card-header {
            gap: 9px;
        }

        .transport-create-page .required-note {
            width: 100%;
        }

        .transport-create-page .status-option,
        .transport-create-page .payment-option {
            flex: 1 1 100%;
        }

        .transport-create-page .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
            padding: 12px;
        }

        .transport-create-page .btn-transport-cancel,
        .transport-create-page .btn-transport-save {
            width: 100%;
        }

        .transport-create-page .selected-student-card {
            padding: 11px;
        }

        .transport-create-page .student-detail-box {
            min-height: 52px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .transport-create-page *,
        .transport-create-page *::before,
        .transport-create-page *::after {
            scroll-behavior: auto !important;
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<div class="transport-create-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="transport-create-header">
            <div class="create-header-content">

                <div class="create-breadcrumb">
                    <a href="{{ route('admin.transport.records.index') }}">
                        Transport Records
                    </a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Edit Record</span>
                </div>

                <div class="create-title-row">
                    <div class="create-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>
                        <h1 class="create-title">Edit Transport Record</h1>
                        <p class="create-subtitle">
                            Update the student's transport service and fee details.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="transport-form-card">

            <div class="form-card-header">
                <div class="form-card-heading">
                    <div class="form-heading-icon">
                        <i class="bi bi-bus-front"></i>
                    </div>

                    <div>
                        <h5>Edit Transport Record</h5>
                        <p>Update the student's transport assignment details.</p>
                    </div>
                </div>

                <div class="required-note">
                    <span>*</span> Required fields
                </div>
            </div>

            <form
                action="{{ route('admin.transport.records.update', ['transportRecord' => $transportRecord->id]) }}"
                method="POST"
                id="transportRecordForm"
            >
                @csrf
                @method('PUT')

                <div class="transport-form-body">

                    {{-- 1. STUDENT INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <div>
                                <h6>01. Student Information</h6>
                                <span>Select a student to automatically load their details.</span>
                            </div>
                        </div>

                        <div class="transport-form-group">
                            <label for="student_search">
                                Select Student <span class="required">*</span>
                            </label>

                            <div class="student-search-wrapper">
                                <i class="bi bi-search student-search-icon"></i>

                                <input
                                    type="text"
                                    id="student_search"
                                    class="student-search-input @error('student_id') is-invalid @enderror"
                                    placeholder="Search student by name or student ID..."
                                    autocomplete="off"
                                    value=""
                                >

                                <i class="bi bi-chevron-down student-search-arrow"></i>

                                <input
                                    type="hidden"
                                    name="student_id"
                                    id="student_id"
                                    value="{{ old('student_id', $transportRecord->student_id) }}"
                                >

                                <div class="student-dropdown" id="studentDropdown">
                                    @forelse($students as $student)
                                        <div
                                            class="student-option"
                                            data-id="{{ $student->id }}"
                                            data-name="{{ $student->full_name }}"
                                            data-student-id="{{ $student->student_id }}"
                                            data-roll-number="{{ $student->roll_number }}"
                                            data-class="{{ $student->class }}"
                                            data-section="{{ $student->section }}"
                                            data-parent-name="{{ $student->father_name }}"
                                            data-parent-phone="{{ $student->father_phone }}"
                                            data-address="{{ $student->address }}"
                                        >
                                            <div class="student-option-avatar">
                                                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                                            </div>

                                            <div class="student-option-info">
                                                <div class="student-option-name">
                                                    {{ $student->full_name }}
                                                </div>

                                                <div class="student-option-meta">
                                                    @if($student->student_id)
                                                        ID: {{ $student->student_id }}
                                                    @endif

                                                    @if($student->roll_number)
                                                        • Roll No: {{ $student->roll_number }}
                                                    @endif

                                                    @if($student->class)
                                                        • Class: {{ $student->class }}
                                                    @endif

                                                    @if($student->section)
                                                        - {{ $student->section }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="student-no-result">
                                            <i class="bi bi-person-x me-1"></i>
                                            No students found.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @error('student_id')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="field-hint">
                                Search and select the student who will use this transport service.
                            </div>
                        </div>

                        {{-- SELECTED STUDENT DETAILS --}}
                        <div class="selected-student-card" id="selectedStudentCard">

                            <div class="selected-student-header">
                                <div class="selected-student-avatar" id="selectedStudentAvatar">
                                    S
                                </div>

                                <div>
                                    <h6 id="selectedStudentTitle">Student Details</h6>
                                    <p>Existing student information</p>
                                </div>
                            </div>

                            <div class="row g-3">

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-person"></i>
                                            Student Name
                                        </div>
                                        <div class="student-detail-value" id="studentName">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-card-text"></i>
                                            Roll No / Student ID
                                        </div>
                                        <div class="student-detail-value" id="studentRollId">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-mortarboard"></i>
                                            Class
                                        </div>
                                        <div class="student-detail-value" id="studentClass">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-grid"></i>
                                            Division
                                        </div>
                                        <div class="student-detail-value" id="studentDivision">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-person-heart"></i>
                                            Parent Name
                                        </div>
                                        <div class="student-detail-value" id="studentParentName">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-telephone"></i>
                                            Parent Phone No.
                                        </div>
                                        <div class="student-detail-value" id="studentParentPhone">—</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-detail-box">
                                        <div class="student-detail-label">
                                            <i class="bi bi-geo-alt"></i>
                                            Address
                                        </div>
                                        <div class="student-detail-value" id="studentAddress">—</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- 2. TRANSPORT INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-bus-front"></i>
                            </div>
                            <div>
                                <h6>02. Transport Information</h6>
                                <span>Enter the student's assigned transport details.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- ROUTE --}}
                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label for="route">Route</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-signpost-2 transport-input-icon"></i>
                                        <input
                                            type="text"
                                            name="route"
                                            id="route"
                                            value="{{ old('route', $transportRecord->route) }}"
                                            class="transport-form-control with-icon @error('route') is-invalid @enderror"
                                            placeholder="e.g. Route 01 - Chandgad"
                                        >
                                    </div>

                                    @error('route')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- VEHICLE --}}
                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label for="vehicle">Vehicle</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-bus-front transport-input-icon"></i>
                                        <input
                                            type="text"
                                            name="vehicle"
                                            id="vehicle"
                                            value="{{ old('vehicle', $transportRecord->vehicle) }}"
                                            class="transport-form-control with-icon @error('vehicle') is-invalid @enderror"
                                            placeholder="e.g. MH-09-AB-1234"
                                        >
                                    </div>

                                    @error('vehicle')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- PICKUP POINT --}}
                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label for="pickup_point">Pickup Point</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-geo-alt transport-input-icon"></i>
                                        <input
                                            type="text"
                                            name="pickup_point"
                                            id="pickup_point"
                                            value="{{ old('pickup_point', $transportRecord->pickup_point) }}"
                                            class="transport-form-control with-icon @error('pickup_point') is-invalid @enderror"
                                            placeholder="e.g. Main Bus Stop"
                                        >
                                    </div>

                                    @error('pickup_point')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- DROP POINT --}}
                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label for="drop_point">Drop Point</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-geo-alt-fill transport-input-icon"></i>
                                        <input
                                            type="text"
                                            name="drop_point"
                                            id="drop_point"
                                            value="{{ old('drop_point', $transportRecord->drop_point) }}"
                                            class="transport-form-control with-icon @error('drop_point') is-invalid @enderror"
                                            placeholder="e.g. School Main Gate"
                                        >
                                    </div>

                                    @error('drop_point')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="col-lg-6">
                                <div class="transport-form-group">
                                    <label>
                                        Transport Status <span class="required">*</span>
                                    </label>

                                    <div class="status-options">
                                        <div class="status-option active-option">
                                            <input
                                                type="radio"
                                                name="transport_status"
                                                id="status_active"
                                                value="active"
                                                {{ old('transport_status', $transportRecord->transport_status) === 'active' ? 'checked' : '' }}
                                            >
                                            <label for="status_active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Active
                                            </label>
                                        </div>

                                        <div class="status-option inactive-option">
                                            <input
                                                type="radio"
                                                name="transport_status"
                                                id="status_inactive"
                                                value="inactive"
                                                {{ old('transport_status', $transportRecord->transport_status) === 'inactive' ? 'checked' : '' }}
                                            >
                                            <label for="status_inactive">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Inactive
                                            </label>
                                        </div>
                                    </div>

                                    @error('transport_status')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- START DATE --}}
                            <div class="col-lg-3 col-md-6">
                                <div class="transport-form-group">
                                    <label for="start_date">Start Date</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-calendar-event transport-input-icon"></i>
                                        <input
                                            type="date"
                                            name="start_date"
                                            id="start_date"
                                            value="{{ old('start_date', optional($transportRecord->start_date)->format('Y-m-d')) }}"
                                            class="transport-form-control with-icon @error('start_date') is-invalid @enderror"
                                        >
                                    </div>

                                    @error('start_date')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- END DATE --}}
                            <div class="col-lg-3 col-md-6">
                                <div class="transport-form-group">
                                    <label for="end_date">End Date</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-calendar-check transport-input-icon"></i>
                                        <input
                                            type="date"
                                            name="end_date"
                                            id="end_date"
                                            value="{{ old('end_date', optional($transportRecord->end_date)->format('Y-m-d')) }}"
                                            class="transport-form-control with-icon @error('end_date') is-invalid @enderror"
                                        >
                                    </div>

                                    @error('end_date')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="field-hint">
                                        Must be equal to or later than start date.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- 3. TRAVEL DETAILS --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h6>03. Travel Details</h6>
                                <span>Set the transport type and daily travel timings.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- TRANSPORT TYPE --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="transport_type">Transport Type</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-bus-front transport-input-icon"></i>
                                        <select
                                            name="transport_type"
                                            id="transport_type"
                                            class="transport-form-select with-icon @error('transport_type') is-invalid @enderror"
                                        >
                                            <option value="">Select transport type</option>
                                            <option value="school_bus" {{ old('transport_type', $transportRecord->transport_type) === 'school_bus' ? 'selected' : '' }}>
                                                School Bus
                                            </option>
                                            <option value="van" {{ old('transport_type', $transportRecord->transport_type) === 'van' ? 'selected' : '' }}>
                                                Van
                                            </option>
                                            <option value="private" {{ old('transport_type', $transportRecord->transport_type) === 'private' ? 'selected' : '' }}>
                                                Private
                                            </option>
                                            <option value="other" {{ old('transport_type', $transportRecord->transport_type) === 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                    </div>

                                    @error('transport_type')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- PICKUP TIME --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="pickup_time">Pickup Time</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-alarm transport-input-icon"></i>
                                        <input
                                            type="time"
                                            name="pickup_time"
                                            id="pickup_time"
                                            value="{{ old('pickup_time', $transportRecord->pickup_time ? \Carbon\Carbon::parse($transportRecord->pickup_time)->format('H:i') : '') }}"
                                            class="transport-form-control with-icon @error('pickup_time') is-invalid @enderror"
                                        >
                                    </div>

                                    @error('pickup_time')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- DROP TIME --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="drop_time">Drop Time</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-clock transport-input-icon"></i>
                                        <input
                                            type="time"
                                            name="drop_time"
                                            id="drop_time"
                                            value="{{ old('drop_time', $transportRecord->drop_time ? \Carbon\Carbon::parse($transportRecord->drop_time)->format('H:i') : '') }}"
                                            class="transport-form-control with-icon @error('drop_time') is-invalid @enderror"
                                        >
                                    </div>

                                    @error('drop_time')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- 4. FEE INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="bi bi-currency-rupee"></i>
                            </div>
                            <div>
                                <h6>04. Fee Information</h6>
                                <span>Enter transport fee and payment information.</span>
                            </div>
                        </div>

                        <div class="row">

                            {{-- TRANSPORT FEE --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="transport_fee">Transport Fee</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-currency-rupee transport-input-icon"></i>
                                        <input
                                            type="number"
                                            name="transport_fee"
                                            id="transport_fee"
                                            value="{{ old('transport_fee', $transportRecord->transport_fee) }}"
                                            class="transport-form-control with-icon @error('transport_fee') is-invalid @enderror"
                                            placeholder="e.g. 1500"
                                            min="0"
                                            step="0.01"
                                        >
                                    </div>

                                    @error('transport_fee')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- FEE FREQUENCY --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label for="fee_frequency">Fee Frequency</label>

                                    <div class="transport-input-wrapper">
                                        <i class="bi bi-calendar3 transport-input-icon"></i>
                                        <select
                                            name="fee_frequency"
                                            id="fee_frequency"
                                            class="transport-form-select with-icon @error('fee_frequency') is-invalid @enderror"
                                        >
                                            <option value="">Select frequency</option>
                                            <option value="monthly" {{ old('fee_frequency', $transportRecord->fee_frequency) === 'monthly' ? 'selected' : '' }}>
                                                Monthly
                                            </option>
                                            <option value="quarterly" {{ old('fee_frequency', $transportRecord->fee_frequency) === 'quarterly' ? 'selected' : '' }}>
                                                Quarterly
                                            </option>
                                            <option value="yearly" {{ old('fee_frequency', $transportRecord->fee_frequency) === 'yearly' ? 'selected' : '' }}>
                                                Yearly
                                            </option>
                                        </select>
                                    </div>

                                    @error('fee_frequency')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- PAYMENT STATUS --}}
                            <div class="col-lg-4">
                                <div class="transport-form-group">
                                    <label>
                                        Payment Status <span class="required">*</span>
                                    </label>

                                    <div class="payment-options">

                                        <div class="payment-option paid-option">
                                            <input
                                                type="radio"
                                                name="payment_status"
                                                id="payment_paid"
                                                value="paid"
                                                {{ old('payment_status', $transportRecord->payment_status) === 'paid' ? 'checked' : '' }}
                                            >
                                            <label for="payment_paid">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Paid
                                            </label>
                                        </div>

                                        <div class="payment-option pending-option">
                                            <input
                                                type="radio"
                                                name="payment_status"
                                                id="payment_pending"
                                                value="pending"
                                                {{ old('payment_status', $transportRecord->payment_status) === 'pending' ? 'checked' : '' }}
                                            >
                                            <label for="payment_pending">
                                                <i class="bi bi-clock-fill"></i>
                                                Pending
                                            </label>
                                        </div>

                                        <div class="payment-option partial-option">
                                            <input
                                                type="radio"
                                                name="payment_status"
                                                id="payment_partial"
                                                value="partially_paid"
                                                {{ old('payment_status', $transportRecord->payment_status) === 'partially_paid' ? 'checked' : '' }}
                                            >
                                            <label for="payment_partial">
                                                <i class="bi bi-circle-half"></i>
                                                Partial
                                            </label>
                                        </div>

                                    </div>

                                    @error('payment_status')
                                        <div class="field-error">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.transport.records.show', ['transportRecord' => $transportRecord->id]) }}"
                        class="btn-transport-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Cancel
                    </a>

                    <button type="submit" class="btn-transport-save">
                        <i class="bi bi-check2-circle"></i>
                        Update Transport Record
                    </button>

                </div>

            </form>
        </div>

    </div>
</div>

{{-- STUDENT SEARCH + AUTO DETAILS SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('student_search');
    const hiddenStudentId = document.getElementById('student_id');
    const dropdown = document.getElementById('studentDropdown');

    const selectedCard = document.getElementById('selectedStudentCard');
    const selectedAvatar = document.getElementById('selectedStudentAvatar');
    const selectedTitle = document.getElementById('selectedStudentTitle');

    const studentName = document.getElementById('studentName');
    const studentRollId = document.getElementById('studentRollId');
    const studentClass = document.getElementById('studentClass');
    const studentDivision = document.getElementById('studentDivision');
    const studentParentName = document.getElementById('studentParentName');
    const studentParentPhone = document.getElementById('studentParentPhone');
    const studentAddress = document.getElementById('studentAddress');

    const studentOptions = Array.from(
        document.querySelectorAll('.student-option')
    );

    function displayValue(value) {
        if (!value || value.trim() === '') {
            return 'Not available';
        }

        return value;
    }

    function selectStudent(option) {
        const id = option.dataset.id || '';
        const name = option.dataset.name || '';
        const studentId = option.dataset.studentId || '';
        const rollNumber = option.dataset.rollNumber || '';
        const studentClassValue = option.dataset.class || '';
        const section = option.dataset.section || '';
        const parentName = option.dataset.parentName || '';
        const parentPhone = option.dataset.parentPhone || '';
        const address = option.dataset.address || '';

        hiddenStudentId.value = id;
        searchInput.value = name;

        studentName.textContent = displayValue(name);

        studentRollId.textContent =
            studentId && rollNumber
                ? rollNumber + ' / ' + studentId
                : (rollNumber || studentId || 'Not available');

        studentClass.textContent = displayValue(studentClassValue);
        studentDivision.textContent = displayValue(section);
        studentParentName.textContent = displayValue(parentName);
        studentParentPhone.textContent = displayValue(parentPhone);
        studentAddress.textContent = displayValue(address);

        selectedAvatar.textContent = name
            ? name.charAt(0).toUpperCase()
            : 'S';

        selectedTitle.textContent = name || 'Student Details';

        selectedCard.classList.add('show');
        dropdown.classList.remove('show');
        searchInput.classList.remove('is-invalid');
    }

    function filterStudents() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        studentOptions.forEach(function (option) {
            const name = (option.dataset.name || '').toLowerCase();
            const studentId = (option.dataset.studentId || '').toLowerCase();
            const rollNumber = (option.dataset.rollNumber || '').toLowerCase();
            const studentClassValue = (option.dataset.class || '').toLowerCase();

            const matches =
                name.includes(searchTerm) ||
                studentId.includes(searchTerm) ||
                rollNumber.includes(searchTerm) ||
                studentClassValue.includes(searchTerm);

            if (matches) {
                option.style.display = 'flex';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });

        let noResult = dropdown.querySelector('.student-no-result-search');

        if (visibleCount === 0) {
            if (!noResult) {
                noResult = document.createElement('div');
                noResult.className =
                    'student-no-result student-no-result-search';

                noResult.innerHTML =
                    '<i class="bi bi-search me-1"></i> No matching student found.';

                dropdown.appendChild(noResult);
            }

            noResult.style.display = 'block';
        } else if (noResult) {
            noResult.style.display = 'none';
        }
    }

    searchInput.addEventListener('focus', function () {
        filterStudents();
        dropdown.classList.add('show');
    });

    searchInput.addEventListener('click', function () {
        filterStudents();
        dropdown.classList.add('show');
    });

    searchInput.addEventListener('input', function () {
        filterStudents();
        dropdown.classList.add('show');
    });

    studentOptions.forEach(function (option) {
        option.addEventListener('click', function () {
            selectStudent(option);
        });
    });

    document.addEventListener('click', function (event) {
        const wrapper = document.querySelector('.student-search-wrapper');

        if (wrapper && !wrapper.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });

    /* LOAD EXISTING STUDENT */
    const currentStudentId = hiddenStudentId.value;

    if (currentStudentId) {
        const currentOption = studentOptions.find(function (option) {
            return option.dataset.id === currentStudentId;
        });

        if (currentOption) {
            selectStudent(currentOption);
        }
    }

});
</script>

@endsection
