
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll &amp; Salary Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 11mm 11mm 11mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #263b35;
            margin: 0;
            padding: 0;
            line-height: 1.4;
            background: #ffffff;
        }

        /* =========================================
           SCHOOL BRANDING & REPORT HEADER
        ========================================= */

        .report-header {
            width: 100%;
            margin-bottom: 13px;
            padding: 0 0 11px 0;
            border-bottom: 3px solid #344f43;
        }

        .school-header {
            width: 100%;
            margin-bottom: 10px;
        }

        .school-brand {
            width: 63%;
            float: left;
        }

        .school-logo-cell {
            width: 57px;
            vertical-align: middle;
        }

        .school-logo {
            width: 48px;
            height: 48px;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #20352c;
            margin: 0 0 4px 0;
        }

        .school-address {
            font-size: 7.5px;
            color: #65736b;
            margin: 2px 0;
        }

        .school-contact {
            font-size: 7px;
            color: #65736b;
            margin-top: 3px;
        }

        .report-badge {
            float: right;
            width: 35%;
            text-align: right;
            padding-top: 5px;
        }

        .report-kicker {
            font-size: 7px;
            font-weight: bold;
            color: #668071;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 17px;
            font-weight: bold;
            color: #20352c;
            margin: 0 0 4px 0;
        }

        .report-subtitle {
            font-size: 8px;
            color: #758178;
        }

        .clear {
            clear: both;
        }

        .header-divider {
            border-top: 1px solid #e5e1d5;
            margin: 8px 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            background: #f7f6f0;
        }

        .meta-table td {
            border: 1px solid #e5e1d5;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .meta-label {
            width: 12%;
            font-size: 7px;
            color: #758178;
            text-transform: uppercase;
            font-weight: bold;
        }

        .meta-value {
            width: 13%;
            font-size: 8px;
            color: #263b35;
            font-weight: bold;
        }

        /* =========================================
           SECTION HEADINGS
        ========================================= */

        .section-heading {
            width: 100%;
            margin: 13px 0 7px 0;
            padding: 0 0 5px 0;
            border-bottom: 1px solid #dcd8cb;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #263b35;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .section-note {
            float: right;
            font-size: 7px;
            color: #7b857b;
        }


        /* =========================================
           PAYROLL DETAILS TABLE
        ========================================= */

        .payroll-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .payroll-table thead {
            display: table-header-group;
        }

        .payroll-table tr {
            page-break-inside: avoid;
        }

        .payroll-table th {
            background: #344f43;
            color: #ffffff;
            border: 1px solid #344f43;
            padding: 7px 4px;
            font-size: 6.5px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            font-weight: bold;
        }

        .payroll-table td {
            border: 1px solid #e5e1d5;
            padding: 6px 4px;
            font-size: 7px;
            vertical-align: top;
            word-wrap: break-word;
            color: #39483e;
        }

        .payroll-table tbody tr:nth-child(even) td {
            background: #f7f6f0;
        }

        .payroll-table tbody tr:nth-child(odd) td {
            background: #fffefa;
        }

        .emp-name {
            font-weight: bold;
            font-size: 7.2px;
            color: #263b35;
        }

        .emp-id {
            font-size: 6px;
            color: #7b857b;
            margin-top: 3px;
        }

        .number {
            text-align: right;
            white-space: nowrap;
        }

        .net-amount {
            text-align: right;
            font-weight: bold;
            color: #287348;
            white-space: nowrap;
        }

        .status {
            font-weight: bold;
            font-size: 6.5px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .status-paid {
            color: #287348;
        }

        .status-unpaid {
            color: #b34b40;
        }

        .status-other {
            color: #8a6b28;
        }

        .empty {
            text-align: center;
            padding: 22px !important;
            color: #758178;
            font-style: italic;
            background: #f7f6f0 !important;
        }

        /* =========================================
           REPORT FOOTER
        ========================================= */

        .footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #dcd8cb;
            color: #7b857b;
            font-size: 7px;
        }

        .footer-left {
            float: left;
            width: 65%;
        }

        .footer-right {
            float: right;
            width: 35%;
            text-align: right;
        }

        .footer strong {
            color: #344f43;
        }
    </style>
</head>

<body>
    @php
        $selectedMonth = $validated['salary_month'] ?? '';
        $selectedStatus = $validated['payment_status'] ?? '';

        $money = function ($value) {
            return number_format((float) ($value ?? 0), 2);
        };

        $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
        $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

        $schoolAddress = collect([
            $schoolSetting->address ?? null,
            $schoolSetting->city ?? null,
            $schoolSetting->district ?? null,
            $schoolSetting->state ?? null,
            $schoolSetting->pincode ?? null,
        ])->filter(function ($value) {
            return trim((string) $value) !== '';
        })->implode(', ');

        $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
        $schoolEmail = trim((string) ($schoolSetting->email ?? ''));

        $logoUrl = $schoolSetting->logo_url ?? null;

        $recordCount = (int) ($summary->total_records ?? 0);
    @endphp

    {{-- =========================================
         SCHOOL IDENTITY & REPORT HEADER
    ========================================== --}}

    <div class="report-header">
        <div class="school-header">
            <div class="school-brand">
                <table style="border-collapse:collapse; width:100%;">
                    <tr>
                        @if (!empty($logoUrl))
                            <td class="school-logo-cell">
                                <img
                                    src="{{ $logoUrl }}"
                                    class="school-logo"
                                    alt="School Logo"
                                >
                            </td>
                        @endif

                        <td style="vertical-align:middle;">
                            <div class="school-name">
                                {{ $schoolName }}
                            </div>

                            @if ($schoolAddress !== '')
                                <div class="school-address">
                                    {{ $schoolAddress }}
                                </div>
                            @endif

                            @if ($schoolPhone !== '' || $schoolEmail !== '')
                                <div class="school-contact">
                                    @if ($schoolPhone !== '')
                                        Phone: {{ $schoolPhone }}
                                    @endif

                                    @if ($schoolPhone !== '' && $schoolEmail !== '')
                                        &nbsp; | &nbsp;
                                    @endif

                                    @if ($schoolEmail !== '')
                                        Email: {{ $schoolEmail }}
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="report-badge">
                <div class="report-kicker"></div>

                <div class="report-title">
                   
                </div>

                <div class="report-subtitle">
                 
                </div>
            </div>

            <div class="clear"></div>
        </div>

        <div class="header-divider"></div>
{{-- REPORT FILTERS AND METADATA --}}

<div style="text-align: right; font-size: 9px; color: #5c6b64; margin-bottom: 12px;">
    Generated: {{ now()->format('d M Y, h:i A') }}
</div>
    

    {{-- =========================================
         DETAILED PAYROLL RECORDS
    ========================================== --}}

    <div class="section-heading">
        <span class="section-title">Detailed Payroll Records</span>
        <span class="section-note">
            {{ number_format($recordCount) }} record(s)
        </span>
        <div class="clear"></div>
    </div>

    <table class="payroll-table">
        <thead>
            <tr>
                <th style="width:13%;">Employee</th>
                <th style="width:7%;">Month</th>
                <th style="width:8%;">Basic</th>
                <th style="width:7%;">Allowances</th>
                <th style="width:8%;">Gross</th>
                <th style="width:7%;">Deductions</th>
                <th style="width:8%;">Net Salary</th>
                <th style="width:7%;">Status</th>
                <th style="width:8%;">Payment Date</th>
                <th style="width:9%;">Method</th>
                <th style="width:10%;">Reference</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($salaries as $salary)
                @php
                    $status = strtolower(trim($salary->payment_status ?? ''));

                    $statusClass = $status === 'paid'
                        ? 'status-paid'
                        : (in_array($status, ['pending', 'unpaid', 'failed', 'overdue'], true)
                            ? 'status-unpaid'
                            : 'status-other');

                    $employeeName = $salary->teacher
                        ? trim(
                            ($salary->teacher->first_name ?? '') . ' ' .
                            ($salary->teacher->last_name ?? '')
                        )
                        : '';

                    if ($employeeName === '') {
                        $employeeName = 'Employee #' . ($salary->teacher_id ?? '—');
                    }
                @endphp

                <tr>
                    <td>
                        <div class="emp-name">
                            {{ $employeeName }}
                        </div>

                        <div class="emp-id">
                            Employee ID: {{ $salary->teacher_id ?? '—' }}
                        </div>
                    </td>

                    <td>
                        {{ $salary->salary_month
                            ? \Illuminate\Support\Carbon::parse($salary->salary_month)->format('M Y')
                            : '—' }}
                    </td>

                    <td class="number">
                        {{ $money($salary->basic_salary) }}
                    </td>

                    <td class="number">
                        {{ $money($salary->allowances) }}
                    </td>

                    <td class="number">
                        {{ $money($salary->gross_salary) }}
                    </td>

                    <td class="number">
                        {{ $money($salary->deductions) }}
                    </td>

                    <td class="net-amount">
                        {{ $money($salary->net_salary) }}
                    </td>

                    <td class="status {{ $statusClass }}">
                        {{ $salary->payment_status ?: 'Not set' }}
                    </td>

                    <td>
                        {{ $salary->payment_date
                            ? \Illuminate\Support\Carbon::parse($salary->payment_date)->format('d M Y')
                            : '—' }}
                    </td>

                    <td>
                        {{ $salary->payment_method ?: '—' }}
                    </td>

                    <td>
                        {{ $salary->transaction_reference
                            ?: ($salary->receipt_number ?: '—') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="empty">
                        No payroll records match the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- =========================================
         REPORT FOOTER
    ========================================== --}}

    <div class="footer">
        <div class="footer-left">
            <strong>{{ $schoolName }}</strong>
            &nbsp;|&nbsp;
            Payroll &amp; Salary Report
            &nbsp;|&nbsp;
            For official use only
        </div>

        <div class="footer-right">
            Generated {{ now()->format('d M Y, h:i A') }}
        </div>

        <div class="clear"></div>
    </div>
</body>
</html>
