<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e2a25;
            font-size: 9px;
        }

        .header {
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 14px;
            text-align: center;
        }

        .logo {
            max-width: 75px;
            max-height: 65px;
            margin-bottom: 6px;
        }

        .school-name {
            color: #1e2a25;
            font-family: DejaVu Serif, serif;
            font-size: 22px;
            font-weight: bold;
        }

        .school-contact {
            color: #5c6b64;
            font-size: 9px;
            margin-top: 3px;
        }

        .report-title {
            color: #2c3e36;
            font-size: 15px;
            font-weight: bold;
            margin-top: 10px;
        }

        .subtitle {
            color: #5c6b64;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary {
            margin-bottom: 12px;
        }

        .summary td {
            padding: 5px;
            border: 1px solid #d4cfc2;
        }

        .summary-label {
            color: #5c6b64;
            font-weight: bold;
        }

        .fee-table {
            table-layout: fixed;
        }

        .fee-table th,
        .fee-table td {
            border: 1px solid #d4cfc2;
            padding: 6px 4px;
            text-align: left;
            word-wrap: break-word;
        }

        .fee-table th {
            background-color: #eae7de;
            color: #2c3e36;
            font-weight: bold;
        }

        .fee-table tbody tr {
            page-break-inside: avoid;
        }

        .number {
            text-align: right !important;
        }

        .paid {
            color: #1a6b3c;
            font-weight: bold;
        }

        .balance {
            color: #a33a2e;
            font-weight: bold;
        }

        .status {
            font-weight: bold;
        }

        .empty {
            text-align: center !important;
            padding: 18px !important;
        }

        .footer {
            border-top: 1px solid #d4cfc2;
            margin-top: 16px;
            padding-top: 7px;
            font-size: 8px;
            color: #5c6b64;
        }

        .footer td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $money = function ($amount) {
            return 'Rs. ' . number_format((float) ($amount ?? 0), 2);
        };

        $schoolAddress = collect([
            $schoolSetting->address ?? null,
            $schoolSetting->city ?? null,
            $schoolSetting->district ?? null,
            $schoolSetting->state ?? null,
            $schoolSetting->pincode ?? null,
        ])->filter(fn ($value) => trim((string) $value) !== '')
          ->implode(', ');

        $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
        $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
    @endphp

    <div class="header">
        @if (!empty($schoolLogo))
            <div>
                <img class="logo" src="{{ $schoolLogo }}" alt="School Logo">
            </div>
        @endif

        <div class="school-name">{{ $schoolName }}</div>

        @if ($schoolAddress !== '')
            <div class="school-contact">{{ $schoolAddress }}</div>
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

        <div class="report-title">FEE REPORT</div>
        <div class="subtitle">Official Fee Collection Statement</div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="summary-label">Search:</span>
                {{ $selectedSearch ?: 'All Students' }}
            </td>
            <td>
                <span class="summary-label">Academic Year:</span>
                {{ $academicYear ?: 'All Years' }}
            </td>
            <td>
                <span class="summary-label">Status:</span>
                {{ $selectedStatus ? ucfirst($selectedStatus) : 'All Statuses' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="summary-label">Due From:</span>
                {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'All Dates' }}
            </td>
            <td>
                <span class="summary-label">Due To:</span>
                {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'All Dates' }}
            </td>
            <td>
                <span class="summary-label">Total Records:</span>
                {{ number_format($fees->count()) }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="summary-label">Total Assigned:</span>
                {{ $money($totalAssigned) }}
            </td>
            <td>
                <span class="summary-label">Total Collected:</span>
                {{ $money($totalPaid) }}
            </td>
            <td>
                <span class="summary-label">Outstanding Balance:</span>
                {{ $money($totalBalance) }}
            </td>
        </tr>
    </table>

    <table class="fee-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 17%;">Student</th>
                <th style="width: 10%;">Academic Year</th>
                <th style="width: 15%;">Fee Structure</th>
                <th style="width: 11%;">Total Fee</th>
                <th style="width: 11%;">Paid</th>
                <th style="width: 11%;">Balance</th>
                <th style="width: 11%;">Due Date</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fees as $fee)
                @php
                    $status = strtolower(trim($fee->status ?? 'pending'));
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>
                            {{ $fee->student->first_name ?? 'N/A' }}
                            {{ $fee->student->last_name ?? '' }}
                        </strong>
                        <br>
                        ID: {{ $fee->student->student_id ?? 'N/A' }}
                    </td>
                    <td>{{ $fee->academic_year ?? '—' }}</td>
                    <td>{{ $fee->feeStructure->structure_name ?? '—' }}</td>
                    <td class="number">{{ $money($fee->total_amount) }}</td>
                    <td class="number paid">{{ $money($fee->paid_amount) }}</td>
                    <td class="number balance">{{ $money($fee->balance_amount) }}</td>
                    <td>
                        {{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('d M Y') : '—' }}
                    </td>
                    <td class="status">{{ ucfirst($status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="empty">
                        No fee records found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>Official Fee Collection Statement</td>
                <td>Generated: {{ $generatedAt->format('d M Y, h:i A') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
