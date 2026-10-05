
@extends('layouts.app')

@section('title', 'Student Fee Details')

@section('content')

<style>
    .fee-page {
        background: #f4f7fb;
        min-height: calc(100vh - 80px);
        padding: 24px;
    }

    .fee-header {
        background: linear-gradient(135deg, #1769d1, #6c63ff);
        color: #fff;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(23, 105, 209, 0.15);
    }

    .fee-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
    }

    .fee-header p {
        margin: 6px 0 0;
        opacity: .9;
        font-size: 14px;
    }

    .top-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .btn-custom {
        border: none;
        border-radius: 10px;
        padding: 10px 17px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: .2s ease;
    }

    .btn-custom:hover {
        transform: translateY(-1px);
    }

    .btn-payment {
        background: #fff;
        color: #1769d1;
    }

    .btn-back {
        background: rgba(255,255,255,.16);
        color: #fff;
        border: 1px solid rgba(255,255,255,.3);
    }

    .info-card,
    .summary-card,
    .items-card,
    .payment-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(31, 45, 61, .07);
        border: 1px solid #e8edf5;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-title {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .card-title i {
        color: #1769d1;
    }

    .card-body {
        padding: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .info-item {
        background: #f8faff;
        border: 1px solid #edf1f7;
        border-radius: 12px;
        padding: 14px;
    }

    .info-label {
        color: #7b8494;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .info-value {
        color: #1f2937;
        font-size: 14px;
        font-weight: 700;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }

    .summary-box {
        padding: 18px;
        border-radius: 13px;
        background: #f8faff;
        border: 1px solid #edf1f7;
    }

    .summary-box .label {
        font-size: 12px;
        color: #7b8494;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .summary-box .amount {
        font-size: 21px;
        font-weight: 800;
        color: #1f2937;
    }

    .summary-box.balance {
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .summary-box.balance .amount {
        color: #ea580c;
    }

    .summary-box.paid {
        background: #ecfdf5;
        border-color: #bbf7d0;
    }

    .summary-box.paid .amount {
        color: #15803d;
    }

    .summary-box.concession {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .summary-box.concession .amount {
        color: #2563eb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-partial {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-paid {
        background: #ecfdf5;
        color: #15803d;
    }

    .status-overdue {
        background: #fef2f2;
        color: #b91c1c;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        background: #f8faff;
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        padding: 13px 15px;
        border-bottom: 1px solid #e8edf5;
        text-align: left;
        white-space: nowrap;
    }

    .custom-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #edf0f5;
        font-size: 13px;
        color: #344054;
        vertical-align: middle;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .amount-cell {
        font-weight: 700;
        color: #1769d1;
    }

    .empty-state {
        padding: 35px 20px;
        text-align: center;
        color: #7b8494;
    }

    .empty-state i {
        font-size: 35px;
        display: block;
        margin-bottom: 10px;
        color: #aab4c3;
    }

    .remarks {
        white-space: pre-wrap;
        color: #475467;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 700px) {
        .fee-page {
            padding: 14px;
        }

        .fee-header {
            padding: 20px;
        }

        .info-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .fee-header h2 {
            font-size: 21px;
        }
    }
</style>

@php
    $student = $studentFee->student;
    $structure = $studentFee->feeStructure;

    $studentName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );

    $className = optional(optional($structure)->schoolClass)->class_name ?? '-';
    $sectionName = optional(optional($structure)->section)->section_name ?? 'All Sections';

    $statusClass = match ($studentFee->status) {
        'Paid' => 'status-paid',
        'Partial' => 'status-partial',
        'Overdue' => 'status-overdue',
        default => 'status-pending',
    };
@endphp

<div class="fee-page">

    {{-- HEADER --}}
    <div class="fee-header">

        <h2>
            <i class="bi bi-person-vcard me-2"></i>
            Student Fee Details
        </h2>

        <p>
            View fee assignment, fee breakdown and payment information.
        </p>

        <div class="top-actions">

            {{-- Collect Payment --}}
            @if((float) $studentFee->balance_amount > 0)
            
<a href="{{ route('admin.fees.student-fees.payment.create', $studentFee) }}"
   class="btn-custom btn-payment">
    <i class="bi bi-credit-card"></i>
    Collect Payment
</a>

            @endif

            {{-- Back --}}
            <a href="{{ route('admin.fees.student-fees.index') }}"
               class="btn-custom btn-back">

                <i class="bi bi-arrow-left"></i>
                Back to Student Fees

            </a>

        </div>

    </div>


    {{-- STUDENT INFORMATION --}}
    <div class="info-card">

        <div class="card-title">
            <i class="bi bi-person-circle"></i>
            Student Information
        </div>

        <div class="card-body">

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Student</div>
                    <div class="info-value">
                        {{ $studentName ?: '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Student ID</div>
                    <div class="info-value">
                        {{ $student->student_id ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Class</div>
                    <div class="info-value">
                        {{ $className }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Section</div>
                    <div class="info-value">
                        {{ $sectionName }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Academic Year</div>
                    <div class="info-value">
                        {{ $studentFee->academic_year ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Due Date</div>
                    <div class="info-value">
                        {{ $studentFee->due_date ? $studentFee->due_date->format('d M Y') : '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Fee Structure</div>
                    <div class="info-value">
                        {{ $structure->structure_name ?? '-' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">

                        <span class="status-badge {{ $statusClass }}">
                            {{ $studentFee->status }}
                        </span>

                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- FEE SUMMARY --}}
    <div class="summary-card">

        <div class="card-title">
            <i class="bi bi-bar-chart-line"></i>
            Fee Summary
        </div>

        <div class="card-body">

            <div class="summary-grid">

                <div class="summary-box">
                    <div class="label">Total Fee</div>
                    <div class="amount">
                        &#8377;{{ number_format((float) $studentFee->total_amount, 2) }}
                    </div>
                </div>

                <div class="summary-box concession">
                    <div class="label">Concession</div>
                    <div class="amount">
                        &#8377;{{ number_format((float) $studentFee->discount_amount, 2) }}
                    </div>
                </div>

                <div class="summary-box">
                    <div class="label">Late Fine</div>
                    <div class="amount">
                        &#8377;{{ number_format((float) $studentFee->fine_amount, 2) }}
                    </div>
                </div>

                <div class="summary-box paid">
                    <div class="label">Paid Amount</div>
                    <div class="amount">
                        &#8377;{{ number_format((float) $studentFee->paid_amount, 2) }}
                    </div>
                </div>

                <div class="summary-box balance">
                    <div class="label">Balance</div>
                    <div class="amount">
                        &#8377;{{ number_format((float) $studentFee->balance_amount, 2) }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- FEE ITEMS --}}
    <div class="items-card">

        <div class="card-title">
            <i class="bi bi-list-check"></i>
            Fee Items
        </div>

        <div class="table-wrapper">

            @if($structure && $structure->items->count())

                <table class="custom-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fee Type</th>
                            <th>Category</th>
                            <th>Frequency</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($structure->items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ optional($item->feeType)->name ?? '-' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ optional($item->feeType)->category ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($item->feeType)->frequency ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->due_date ? $item->due_date->format('d M Y') : '-' }}
                                </td>

                                <td class="amount-cell">
                                    &#8377;{{ number_format((float) $item->amount, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <i class="bi bi-receipt"></i>

                    No fee items found for this fee structure.

                </div>

            @endif

        </div>

    </div>


    {{-- PAYMENT HISTORY --}}
    <div class="payment-card">

        <div class="card-title">
            <i class="bi bi-clock-history"></i>
            Payment History
        </div>

        <div class="table-wrapper">

            @if($studentFee->payments && $studentFee->payments->count())

                <table class="custom-table">

                    <thead>

                        <tr>
                            <th>Receipt Number</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Transaction Reference</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($studentFee->payments->sortByDesc('payment_date') as $payment)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $payment->receipt_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $payment->payment_date
                                        ? $payment->payment_date->format('d M Y')
                                        : '-' }}
                                </td>

                                <td class="amount-cell">
                                    &#8377;{{ number_format((float) $payment->amount, 2) }}
                                </td>

                                <td>
                                    {{ $payment->payment_method }}
                                </td>

                                <td>
                                    {{ $payment->transaction_reference ?: '-' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <i class="bi bi-wallet2"></i>

                    No payments have been recorded for this student fee yet.

                </div>

            @endif

        </div>

    </div>


    {{-- REMARKS --}}
    @if($studentFee->remarks)

        <div class="info-card">

            <div class="card-title">
                <i class="bi bi-chat-left-text"></i>
                Remarks
            </div>

            <div class="card-body">

                <div class="remarks">
                    {{ $studentFee->remarks }}
                </div>

            </div>

        </div>

    @endif

</div>

@endsection
