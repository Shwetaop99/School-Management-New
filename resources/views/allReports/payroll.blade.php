
@extends('layouts.app')

@section('title', 'Payroll Report')
@section('page-title', 'Payroll Report')

@section('content')
<div class="payroll-report-page">

    <style>
        .payroll-report-page {
            --payroll-bg: #f7f6f0;
            --payroll-paper: #fffefa;
            --payroll-cream: #f1ead8;
            --payroll-forest: #263b35;
            --payroll-dark: #20352c;
            --payroll-muted: #7b857b;
            --payroll-line: #e5e1d5;
            --payroll-green: #43835d;
            --payroll-amber: #a87543;
            padding: 24px;
            min-height: calc(100vh - 70px);
            background: var(--payroll-bg);
            color: var(--payroll-forest);
            font-family: Inter, "Public Sans", Arial, sans-serif;
        }

        .payroll-report-page *,
        .payroll-report-page *::before,
        .payroll-report-page *::after {
            box-sizing: border-box;
        }

        .payroll-report-page button,
        .payroll-report-page input,
        .payroll-report-page select {
            font: inherit;
        }

        /* PAGE INTRODUCTION */

        .payroll-hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            overflow: hidden;
            padding: 30px;
            margin-bottom: 22px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 18px;
            background: linear-gradient(125deg, #344f43 0%, #20352c 100%);
            color: #fffefa;
            box-shadow: 0 8px 24px rgba(32, 53, 44, .09);
        }

        .payroll-hero::after {
            content: "";
            position: absolute;
            width: 210px;
            height: 210px;
            right: 16%;
            top: -135px;
            border: 1px solid rgba(255, 254, 250, .11);
            border-radius: 50%;
            pointer-events: none;
        }

        .payroll-hero-content {
            position: relative;
            z-index: 1;
        }

        .payroll-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 11px;
            color: #d8e3d9;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .payroll-eyebrow::before {
            content: "";
            width: 21px;
            height: 2px;
            border-radius: 2px;
            background: #d7c49d;
        }

        .payroll-hero h2 {
            margin: 0 0 9px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(25px, 3vw, 33px);
            font-weight: 600;
            letter-spacing: -.5px;
            line-height: 1.2;
        }

        .payroll-hero p {
            max-width: 540px;
            margin: 0;
            color: #dbe4dc;
            font-size: 13px;
            line-height: 1.7;
        }

        .payroll-hero-actions {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 9px;
        }

        .payroll-btn,
        .payroll-back {
            display: inline-flex;
            min-height: 41px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease,
                        transform .18s ease;
        }

        .payroll-btn:hover,
        .payroll-back:hover {
            transform: translateY(-1px);
        }

        .payroll-btn-light,
        .payroll-back {
            border-color: #e5dfcf;
            background: #f1ead8;
            color: #263b35;
        }

        .payroll-btn-light:hover,
        .payroll-back:hover {
            background: #fff5df;
            color: #20352c;
        }

        .payroll-btn-white {
            border-color: #fffefa;
            background: #fffefa;
            color: #263b35;
        }

        .payroll-btn-white:hover {
            background: #f5f3e9;
        }

        .payroll-btn-primary {
            background: #344f43;
            color: #fffefa;
        }

        .payroll-btn-primary:hover {
            background: #20352c;
        }

        /* SHARED PANELS */

        .payroll-panel {
            margin-bottom: 21px;
            padding: 22px;
            border: 1px solid var(--payroll-line);
            border-radius: 14px;
            background: var(--payroll-paper);
            box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
        }

        .payroll-panel-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 19px;
        }

        .payroll-panel-heading h3 {
            margin: 0 0 5px;
            color: var(--payroll-forest);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.2px;
        }

        .payroll-panel-heading p {
            margin: 0;
            color: var(--payroll-muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .payroll-section-label {
            display: inline-block;
            margin-bottom: 6px;
            color: var(--payroll-amber);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        /* FILTERS */

        .payroll-filter-grid {
            display: grid;
            grid-template-columns: minmax(170px, 1fr)
                                   minmax(170px, 1fr) auto;
            align-items: end;
            gap: 15px;
        }

        .payroll-field label {
            display: block;
            margin-bottom: 7px;
            color: #536258;
            font-size: 11px;
            font-weight: 750;
        }

        .payroll-field input,
        .payroll-field select {
            width: 100%;
            min-height: 43px;
            padding: 10px 12px;
            border: 1px solid #dcded3;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: var(--payroll-forest);
            font-size: 12px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .payroll-field input:focus,
        .payroll-field select:focus {
            border-color: #527f70;
            box-shadow: 0 0 0 3px rgba(82, 127, 112, .12);
        }

        .payroll-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* SUMMARY CARDS — SCREEN ONLY */

        .payroll-summary {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 21px;
        }

        .payroll-stat {
            position: relative;
            min-width: 0;
            overflow: hidden;
            padding: 18px 17px 19px;
            border: 1px solid var(--payroll-line);
            border-radius: 12px;
            background: var(--payroll-paper);
            box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
        }

        .payroll-stat::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #527f70;
        }

        .payroll-stat:nth-child(2)::before {
            background: #a87543;
        }

        .payroll-stat:nth-child(3)::before {
            background: #426b8a;
        }

        .payroll-stat:nth-child(4)::before {
            background: #b95b50;
        }

        .payroll-stat:nth-child(5)::before {
            background: #43835d;
        }

        .payroll-stat-label {
            display: block;
            min-height: 30px;
            margin-bottom: 9px;
            color: var(--payroll-muted);
            font-size: 10px;
            font-weight: 700;
            line-height: 1.5;
        }

        .payroll-stat strong {
            display: block;
            overflow-wrap: anywhere;
            color: var(--payroll-forest);
            font-size: clamp(16px, 1.5vw, 21px);
            font-weight: 800;
            letter-spacing: -.5px;
            line-height: 1.35;
            font-variant-numeric: tabular-nums;
        }

        /* REPORT TABLE */

        .payroll-table-card {
            overflow: hidden;
            padding: 0;
        }

        .payroll-table-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 22px 22px 18px;
            border-bottom: 1px solid #ece9df;
        }

        .payroll-table-heading h3 {
            margin: 0 0 6px;
            color: var(--payroll-forest);
            font-size: 15px;
            font-weight: 800;
        }

        .payroll-table-heading p {
            margin: 0;
            color: var(--payroll-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .payroll-table-count {
            flex-shrink: 0;
            padding: 7px 10px;
            border: 1px solid #e5e1d5;
            border-radius: 7px;
            background: #f5f3e9;
            color: #40574a;
            font-size: 10px;
            font-weight: 800;
        }

        .payroll-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .payroll-table {
            width: 100%;
            min-width: 1120px;
            border-collapse: separate;
            border-spacing: 0;
            background: #fffefa;
        }

        .payroll-table th {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e1d5;
            background: #f5f3e9;
            color: #536258;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .55px;
            line-height: 1.5;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .payroll-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #eeeae1;
            color: #39483e;
            font-size: 11px;
            line-height: 1.55;
            vertical-align: middle;
        }

        .payroll-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .payroll-table tbody tr:hover td {
            background: #faf9f3;
        }

        .payroll-employee-name {
            display: block;
            min-width: 110px;
            color: #263b35;
            font-size: 11px;
            font-weight: 800;
        }

        .payroll-employee-id {
            display: block;
            margin-top: 3px;
            color: #879087;
            font-size: 9px;
        }

        .payroll-money {
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .payroll-net-amount {
            color: #28633c;
            font-weight: 800;
        }

        .payroll-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border: 1px solid #e4dac3;
            border-radius: 5px;
            background: #f8f0df;
            color: #76552e;
            font-size: 9px;
            font-weight: 800;
            line-height: 1.3;
            white-space: nowrap;
        }

        .payroll-status-paid {
            border-color: #cde2d0;
            background: #eaf4eb;
            color: #28633c;
        }

        .payroll-status-unpaid {
            border-color: #eed1cb;
            background: #fbefec;
            color: #99483d;
        }

        .payroll-empty {
            padding: 45px 18px !important;
            color: var(--payroll-muted) !important;
            text-align: center;
        }

        .payroll-empty-title {
            display: block;
            margin-bottom: 5px;
            color: var(--payroll-forest);
            font-size: 13px;
            font-weight: 800;
        }

        .payroll-empty-description {
            font-size: 11px;
        }

        /* PAGINATION */

        .payroll-pagination {
            padding: 17px 22px;
            border-top: 1px solid #ece9df;
            background: #fffefa;
        }

        .payroll-pagination nav {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* PRINT-ONLY CONTENT */

        .payroll-print-header,
        .payroll-report-footer {
            display: none;
        }

        /* RESPONSIVE */

        @media (max-width: 1250px) {
            .payroll-report-page {
                padding: 18px;
            }

            .payroll-hero {
                align-items: flex-start;
            }

            .payroll-summary {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .payroll-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .payroll-filter-actions {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 700px) {
            .payroll-report-page {
                padding: 12px;
            }

            .payroll-hero {
                padding: 21px;
                border-radius: 13px;
            }

            .payroll-hero-actions {
                width: 100%;
            }

            .payroll-hero-actions .payroll-btn,
            .payroll-hero-actions .payroll-back {
                flex: 1 1 auto;
            }

            .payroll-panel {
                padding: 16px;
            }

            .payroll-filter-grid,
            .payroll-summary {
                grid-template-columns: 1fr;
            }

            .payroll-filter-actions {
                grid-column: auto;
            }

            .payroll-stat {
                padding: 15px;
            }

            .payroll-stat-label {
                min-height: 0;
            }

            .payroll-table-heading {
                padding: 17px;
            }

            .payroll-table-heading p {
                max-width: 240px;
            }
        }

        /* PROFESSIONAL PRINT AND SAVE-AS-PDF LAYOUT */

        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm 9mm 13mm;
            }

            html,
            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #263b35 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Hide the application layout, navigation and all
               unrelated page elements. */
            body * {
                visibility: hidden !important;
            }

            .payroll-report-page,
            .payroll-report-page * {
                visibility: visible !important;
            }

            .payroll-report-page {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                max-width: none !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #263b35 !important;
                font-family: Arial, Helvetica, sans-serif !important;
                font-size: 9px !important;
            }

            /* No cards, screen controls, filters or pagination in print. */
            .payroll-hero,
            .payroll-filters,
            .payroll-summary,
            .payroll-pagination,
            .payroll-no-print {
                display: none !important;
            }

            /* Printed report identity and metadata. */
            .payroll-print-header {
                display: block !important;
                visibility: visible !important;
                margin: 0 0 15px !important;
                padding: 0 0 11px !important;
                border-bottom: 2px solid #263b35 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .payroll-print-header * {
                visibility: visible !important;
            }

            .payroll-print-topline {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 12px !important;
                margin-bottom: 8px !important;
            }

            .payroll-print-brand {
                color: #40574a !important;
                font-size: 9px !important;
                font-weight: 800 !important;
                letter-spacing: 1.8px !important;
                text-transform: uppercase !important;
            }

            .payroll-print-document-label {
                color: #68776b !important;
                font-size: 8px !important;
                font-weight: 700 !important;
                letter-spacing: 1px !important;
                text-transform: uppercase !important;
            }

            .payroll-print-title {
                margin: 0 0 5px !important;
                color: #20352c !important;
                font-family: Georgia, "Times New Roman", serif !important;
                font-size: 23px !important;
                font-weight: 700 !important;
                line-height: 1.2 !important;
            }

            .payroll-print-subtitle {
                margin: 0 0 12px !important;
                color: #68776b !important;
                font-size: 9px !important;
                line-height: 1.5 !important;
            }

            .payroll-print-meta {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                gap: 8px 18px !important;
                padding-top: 9px !important;
                border-top: 1px solid #d9ded8 !important;
            }

            .payroll-print-meta-item {
                color: #536258 !important;
                font-size: 8.5px !important;
                line-height: 1.5 !important;
            }

            .payroll-print-meta-item strong {
                margin-right: 3px !important;
                color: #20352c !important;
            }

            /* Flat table; never print the screen card styling. */
            .payroll-table-card {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
                border: 0 !important;
                border-radius: 0 !important;
                background: #fff !important;
                box-shadow: none !important;
            }

            .payroll-table-heading {
                display: block !important;
                margin: 0 0 8px !important;
                padding: 0 0 7px !important;
                border: 0 !important;
                background: #fff !important;
            }

            .payroll-table-heading h3 {
                margin: 0 0 3px !important;
                color: #20352c !important;
                font-size: 11px !important;
                font-weight: 800 !important;
            }

            .payroll-table-heading p {
                margin: 0 !important;
                color: #68776b !important;
                font-size: 8px !important;
            }

            .payroll-table-count {
                display: none !important;
            }

            .payroll-table-wrap {
                display: block !important;
                width: 100% !important;
                overflow: visible !important;
            }

            .payroll-table {
                display: table !important;
                width: 100% !important;
                min-width: 0 !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
                border-spacing: 0 !important;
                background: #fff !important;
            }

            .payroll-table thead {
                display: table-header-group !important;
            }

            .payroll-table tbody {
                display: table-row-group !important;
            }

            .payroll-table tr {
                display: table-row !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .payroll-table th {
                padding: 7px 5px !important;
                border: 1px solid #cbd4cc !important;
                background: #e9eee8 !important;
                color: #20352c !important;
                font-size: 7px !important;
                font-weight: 800 !important;
                line-height: 1.35 !important;
                letter-spacing: .1px !important;
                text-align: left !important;
                text-transform: uppercase !important;
                white-space: normal !important;
                overflow-wrap: anywhere !important;
            }

            .payroll-table td {
                padding: 6px 5px !important;
                border: 1px solid #d9ded8 !important;
                background: #fff !important;
                color: #263b35 !important;
                font-size: 7.2px !important;
                line-height: 1.35 !important;
                vertical-align: top !important;
                white-space: normal !important;
                overflow-wrap: anywhere !important;
                word-break: normal !important;
            }

            .payroll-table tbody tr:nth-child(even) td {
                background: #f8faf8 !important;
            }

            .payroll-table tbody tr:hover td {
                background: inherit !important;
            }

            .payroll-employee-name {
                min-width: 0 !important;
                color: #20352c !important;
                font-size: 7.2px !important;
                font-weight: 800 !important;
            }

            .payroll-employee-id {
                color: #68776b !important;
                font-size: 6.5px !important;
            }

            .payroll-money {
                white-space: normal !important;
                font-variant-numeric: tabular-nums !important;
            }

            .payroll-net-amount {
                color: #245333 !important;
                font-weight: 800 !important;
            }

            .payroll-status {
                display: inline-block !important;
                padding: 3px 4px !important;
                border: 1px solid #cbd4cc !important;
                border-radius: 2px !important;
                background: #f1f4f0 !important;
                color: #263b35 !important;
                font-size: 6.5px !important;
                font-weight: 800 !important;
                white-space: normal !important;
            }

            .payroll-status-paid {
                border-color: #c5d9c9 !important;
                background: #edf5ee !important;
                color: #245333 !important;
            }

            .payroll-status-unpaid {
                border-color: #e5c8c2 !important;
                background: #fbefec !important;
                color: #99483d !important;
            }

            .payroll-empty {
                padding: 16px 8px !important;
                color: #536258 !important;
                text-align: center !important;
            }

            .payroll-empty-title,
            .payroll-empty-description {
                color: #536258 !important;
                font-size: 8px !important;
            }

            /* Confidential footer. */
            .payroll-report-footer {
                display: flex !important;
                visibility: visible !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 12px !important;
                margin-top: 12px !important;
                padding-top: 7px !important;
                border-top: 1px solid #d9ded8 !important;
                color: #68776b !important;
                font-size: 7px !important;
                line-height: 1.4 !important;
            }

            .payroll-report-footer * {
                visibility: visible !important;
            }

            a {
                color: inherit !important;
                text-decoration: none !important;
            }
        }
    </style>

    @php
        $money = function ($amount) {
            return '₹' . number_format((float) ($amount ?? 0), 2);
        };

        $selectedMonth = $validated['salary_month'] ?? '';
        $selectedStatus = $validated['payment_status'] ?? '';
    @endphp

    {{-- SCREEN HEADER --}}
    <section class="payroll-hero">
        <div class="payroll-hero-content">
            <div class="payroll-eyebrow">Finance &amp; Administration</div>
            <h2>Payroll &amp; Salary Report</h2>
            <p>
                A consolidated view of employee salaries, deductions,
                payment status and transaction records.
            </p>
        </div>

        <div class="payroll-hero-actions payroll-no-print">
            <button type="button"
                    class="payroll-btn payroll-btn-white"
                    onclick="downloadPayrollPdf()">
                <span aria-hidden="true">↓</span> Save as PDF
            </button>

            <button type="button"
                    class="payroll-btn payroll-btn-light"
                    onclick="printPayrollReport()">
                <span aria-hidden="true">⎙</span> Print Report
            </button>

            <a href="{{ route('admin.reports.index') }}"
               class="payroll-back">
                <span aria-hidden="true">←</span> Back to Reports
            </a>
        </div>
    </section>

    {{-- PRINT-ONLY REPORT HEADER --}}
    <header class="payroll-print-header">
        <div class="payroll-print-topline">
            <div class="payroll-print-brand">School Management System</div>
            <div class="payroll-print-document-label">Official Report</div>
        </div>

        <h1 class="payroll-print-title">Payroll &amp; Salary Report</h1>

        <p class="payroll-print-subtitle">
            Employee salary, deductions and payment transaction statement
        </p>

        <div class="payroll-print-meta">
            <div class="payroll-print-meta-item">
                <strong>Salary Month:</strong>
                <span>
                    @if ($selectedMonth)
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}
                    @else
                        All months
                    @endif
                </span>
            </div>

            <div class="payroll-print-meta-item">
                <strong>Payment Status:</strong>
                <span>{{ $selectedStatus ?: 'All statuses' }}</span>
            </div>

            <div class="payroll-print-meta-item">
                <strong>Total Matching Records:</strong>
                <span>{{ number_format((int) ($summary->total_records ?? 0)) }}</span>
            </div>

            <div class="payroll-print-meta-item">
                <strong>Generated:</strong>
                <span id="payroll-print-date">—</span>
            </div>
        </div>
    </header>

    {{-- FILTERS: SCREEN ONLY --}}
    <section class="payroll-panel payroll-filters payroll-no-print">
        <div class="payroll-panel-heading">
            <div>
                <span class="payroll-section-label">Refine results</span>
                <h3>Filter Payroll Records</h3>
                <p>Select a salary month or payment status to narrow the report.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports.payroll') }}">
            <div class="payroll-filter-grid">
                <div class="payroll-field">
                    <label for="salary_month">Salary Month</label>
                    <input type="month"
                           id="salary_month"
                           name="salary_month"
                           value="{{ $selectedMonth }}">
                </div>

                <div class="payroll-field">
                    <label for="payment_status">Payment Status</label>
                    <select id="payment_status" name="payment_status">
                        <option value="">All statuses</option>
                        <option value="Paid" @selected($selectedStatus === 'Paid')>
                            Paid
                        </option>
                        <option value="Pending" @selected($selectedStatus === 'Pending')>
                            Pending
                        </option>
                    </select>
                </div>

                <div class="payroll-filter-actions">
                    <button type="submit" class="payroll-btn payroll-btn-primary">
                        Apply Filters
                    </button>

                    <a href="{{ route('admin.reports.payroll') }}"
                       class="payroll-btn payroll-btn-light">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </section>

    {{-- SUMMARY CARDS: SCREEN ONLY --}}
    <section class="payroll-summary">
        <div class="payroll-stat">
            <span class="payroll-stat-label">Payroll Records</span>
            <strong>{{ number_format((int) ($summary->total_records ?? 0)) }}</strong>
        </div>

        <div class="payroll-stat">
            <span class="payroll-stat-label">Basic Salary Total</span>
            <strong>{{ $money($summary->basic_total ?? 0) }}</strong>
        </div>

        <div class="payroll-stat">
            <span class="payroll-stat-label">Gross Salary Total</span>
            <strong>{{ $money($summary->gross_total ?? 0) }}</strong>
        </div>

        <div class="payroll-stat">
            <span class="payroll-stat-label">Total Deductions</span>
            <strong>{{ $money($summary->deductions_total ?? 0) }}</strong>
        </div>

        <div class="payroll-stat">
            <span class="payroll-stat-label">Net Salary Total</span>
            <strong>{{ $money($summary->net_total ?? 0) }}</strong>
        </div>
    </section>

    {{-- PAYROLL TRANSACTION TABLE --}}
    <section class="payroll-panel payroll-table-card">
        <div class="payroll-table-heading">
            <div>
                <span class="payroll-section-label">Detailed statement</span>
                <h3>Salary Transaction Details</h3>
                <p>
                    Employee-wise salary and payment information for the selected filters.
                </p>
            </div>

            <div class="payroll-table-count payroll-no-print">
                {{ number_format($salaries->total()) }}
                {{ $salaries->total() === 1 ? 'record' : 'records' }}
            </div>
        </div>

        <div class="payroll-table-wrap">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Salary Month</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Gross Salary</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Payment Status</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($salaries as $salary)
                        <tr>
                            <td>
                                <span class="payroll-employee-name">
                                    @if ($salary->teacher)
                                        {{ trim(($salary->teacher->first_name ?? '') . ' ' . ($salary->teacher->last_name ?? '')) ?: 'Employee #' . $salary->teacher_id }}
                                    @else
                                        Employee #{{ $salary->teacher_id }}
                                    @endif
                                </span>
                                <span class="payroll-employee-id">
                                    ID: {{ $salary->teacher_id ?? '—' }}
                                </span>
                            </td>

                            <td>
                                {{ $salary->salary_month ? \Illuminate\Support\Carbon::parse($salary->salary_month)->format('M Y') : '—' }}
                            </td>

                            <td class="payroll-money">
                                {{ $money($salary->basic_salary) }}
                            </td>

                            <td class="payroll-money">
                                {{ $money($salary->allowances) }}
                            </td>

                            <td class="payroll-money">
                                {{ $money($salary->gross_salary) }}
                            </td>

                            <td class="payroll-money">
                                {{ $money($salary->deductions) }}
                            </td>

                            <td class="payroll-money payroll-net-amount">
                                {{ $money($salary->net_salary) }}
                            </td>

                            <td>
                                @php
                                    $status = strtolower(trim($salary->payment_status ?? ''));
                                    $statusClass = $status === 'paid'
                                        ? 'payroll-status-paid'
                                        : (in_array($status, ['pending', 'unpaid', 'failed', 'overdue'], true)
                                            ? 'payroll-status-unpaid'
                                            : '');
                                @endphp

                                <span class="payroll-status {{ $statusClass }}">
                                    {{ $salary->payment_status ?: 'Not set' }}
                                </span>
                            </td>

                            <td>
                                {{ $salary->payment_date ? \Illuminate\Support\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}
                            </td>

                            <td>
                                {{ $salary->payment_method ?: '—' }}
                            </td>

                            <td>
                                {{ $salary->transaction_reference ?: $salary->receipt_number ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="payroll-empty">
                                <span class="payroll-empty-title">
                                    No payroll records found
                                </span>
                                <span class="payroll-empty-description">
                                    Try changing the selected month or payment status.
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($salaries->hasPages())
            <div class="payroll-pagination payroll-no-print">
                {{ $salaries->links() }}
            </div>
        @endif
    </section>

    {{-- PRINT-ONLY FOOTER --}}
    <footer class="payroll-report-footer">
        <span>Payroll &amp; Salary Report</span>
        <span>Confidential — For authorized school use only</span>
    </footer>

    <script>
        (function () {
            function setPayrollPrintDate() {
                const dateElement = document.getElementById('payroll-print-date');

                if (dateElement) {
                    dateElement.textContent = new Date().toLocaleString();
                }
            }

            window.printPayrollReport = function () {
                setPayrollPrintDate();
                window.print();
            };

            window.downloadPayrollPdf = function () {
                setPayrollPrintDate();
                window.print();
            };
        })();
    </script>

</div>
@endsection
