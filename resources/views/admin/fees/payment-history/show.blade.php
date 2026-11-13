@extends('layouts.app')

@section('title', 'Payment Transaction')

@section('content')

@php
    $studentFee = $feePayment->studentFee;
    $student = $studentFee->student ?? null;
    $feeStructure = $studentFee->feeStructure ?? null;

    $studentName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );

    $studentName = $studentName ?: 'Student';

    $initials =
        strtoupper(substr($student->first_name ?? 'S', 0, 1)) .
        strtoupper(substr($student->last_name ?? 'T', 0, 1));

    $feeItems = $feeStructure?->items ?? collect();

    $totalAmount = (float) ($studentFee->total_amount ?? 0);
    $discountAmount = (float) ($studentFee->discount_amount ?? 0);
    $fineAmount = (float) ($studentFee->fine_amount ?? 0);
    $paidAmount = (float) ($studentFee->paid_amount ?? 0);
    $balanceAmount = (float) ($studentFee->balance_amount ?? 0);
    $currentPayment = (float) ($feePayment->amount ?? 0);

    /*
     * paid_amount already contains this payment.
     * Therefore previous payment = total paid - current payment.
     */
    $previousPaid = max(0, $paidAmount - $currentPayment);

    $className = $feeStructure?->schoolClass?->class_name ?? 'N/A';
    $sectionName = $feeStructure?->section?->section_name ?? 'All Sections';
    $academicYear = $studentFee->academic_year ?? 'N/A';
    $structureName = $feeStructure->structure_name ?? 'Fee Structure';

    $schoolName = $school->name ?? 'Gurukul Vidyalaya';
    $schoolAddress = $school->address ?? '';
    $schoolPhone = $school->phone ?? '';
    $schoolEmail = $school->email ?? '';
    $schoolLogo = $school->logo ?? null;

    $studentProfile = $student->profile_image ?? null;

    $paymentStatus = $studentFee->status ?? 'Pending';

    $statusLabel = match ($paymentStatus) {
        'Paid' => 'PAID',
        'Partial' => 'PARTIAL',
        'Overdue' => 'OVERDUE',
        default => 'PENDING',
    };
@endphp


{{-- ============================================================
     SCREEN VERSION
     ============================================================ --}}

<div class="payment-screen">

    <div class="payment-wrapper">

        {{-- TOP TRANSACTION CARD --}}
        <div class="transaction-card">

            <div class="transaction-header">

                <div class="header-left">

                    <div class="header-icon">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>

                    <div>
                        <div class="header-title">
                            Fee Payment Receipt
                        </div>

                        <div class="header-subtitle">
                            Transaction #{{ $feePayment->receipt_number }}
                        </div>
                    </div>

                </div>

                <div class="header-actions">

                    <a href="{{ route('admin.fees.payment-history.index') }}"
                       class="btn-light-action">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="btn-print">
                        <i class="bi bi-printer"></i>
                        Print Receipt
                    </button>

                </div>

            </div>


            {{-- PAYMENT SUMMARY --}}
            <div class="summary-grid">

                <div class="summary-box">

                    <div class="summary-icon amount-icon">
                        <i class="bi bi-currency-rupee"></i>
                    </div>

                    <div>
                        <div class="summary-label">
                            Amount Paid
                        </div>

                        <div class="summary-value">
                            &#8377;{{ number_format($currentPayment, 2) }}
                        </div>
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-icon date-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <div class="summary-label">
                            Payment Date
                        </div>

                        <div class="summary-value small-value">
                            {{ $feePayment->payment_date?->format('d M Y') ?? 'N/A' }}
                        </div>
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-icon method-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div>
                        <div class="summary-label">
                            Payment Method
                        </div>

                        <div class="summary-value small-value">
                            {{ $feePayment->payment_method }}
                        </div>
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-icon status-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div>
                        <div class="summary-label">
                            Fee Status
                        </div>

                        <div class="summary-value small-value">
                            {{ $statusLabel }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- MAIN CONTENT --}}
        <div class="content-grid">

            {{-- STUDENT CARD --}}
            <div class="info-card">

                <div class="card-heading">

                    <div class="heading-icon blue">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h3>Student Information</h3>
                        <p>Student details associated with this payment</p>
                    </div>

                </div>


                <div class="student-profile">

                    @if($studentProfile)
                        <img src="{{ asset('storage/' . $studentProfile) }}"
                             alt="Student"
                             class="student-photo">
                    @else
                        <div class="student-avatar">
                            {{ $initials }}
                        </div>
                    @endif

                    <div class="student-main">

                        <div class="student-name">
                            {{ $studentName }}
                        </div>

                        <div class="student-id">
                            Student ID:
                            <strong>{{ $student->student_id ?? 'N/A' }}</strong>
                        </div>

                    </div>

                </div>


                <div class="detail-list">

                    <div class="detail-row">
                        <span>Roll Number</span>
                        <strong>{{ $student->roll_number ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-row">
                        <span>Class</span>
                        <strong>{{ $className }}</strong>
                    </div>

                    <div class="detail-row">
                        <span>Section</span>
                        <strong>{{ $sectionName }}</strong>
                    </div>

                    <div class="detail-row">
                        <span>Academic Year</span>
                        <strong>{{ $academicYear }}</strong>
                    </div>

                </div>

            </div>


            {{-- TRANSACTION CARD --}}
            <div class="info-card">

                <div class="card-heading">

                    <div class="heading-icon purple">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>

                    <div>
                        <h3>Transaction Information</h3>
                        <p>Payment and receipt information</p>
                    </div>

                </div>


                <div class="detail-list">

                    <div class="detail-row">
                        <span>Receipt Number</span>
                        <strong class="receipt-number">
                            {{ $feePayment->receipt_number }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Payment Date</span>
                        <strong>
                            {{ $feePayment->payment_date?->format('d M Y') ?? 'N/A' }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Payment Method</span>
                        <strong>{{ $feePayment->payment_method }}</strong>
                    </div>

                    <div class="detail-row">
                        <span>Transaction Reference</span>
                        <strong>
                            {{ $feePayment->transaction_reference ?: 'Not Provided' }}
                        </strong>
                    </div>

                    <div class="detail-row">
                        <span>Recorded On</span>
                        <strong>
                            {{ $feePayment->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- FEE STRUCTURE --}}
        <div class="info-card full-card">

            <div class="card-heading">

                <div class="heading-icon green">
                    <i class="bi bi-receipt"></i>
                </div>

                <div>
                    <h3>Fee Structure</h3>
                    <p>{{ $structureName }}</p>
                </div>

            </div>


            <div class="table-responsive">

                <table class="fee-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fee Component</th>
                            <th>Due Date</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($feeItems as $index => $item)

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="fee-name">
                                        {{ $item->feeType->name ?? 'Fee' }}
                                    </div>

                                    @if($item->feeType?->category)
                                        <div class="fee-category">
                                            {{ $item->feeType->category }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->due_date?->format('d M Y') ?? 'No Due Date' }}
                                </td>

                                <td class="text-right fee-amount">
                                    &#8377;{{ number_format((float) $item->amount, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty-row">
                                    No fee components found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="3">
                                Structure Total
                            </td>

                            <td class="text-right">
                                &#8377;{{ number_format($totalAmount, 2) }}
                            </td>
                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>


        {{-- FINANCIAL SUMMARY --}}
        <div class="info-card full-card">

            <div class="card-heading">

                <div class="heading-icon orange">
                    <i class="bi bi-calculator"></i>
                </div>

                <div>
                    <h3>Financial Summary</h3>
                    <p>Current fee payment position</p>
                </div>

            </div>


            <div class="financial-grid">

                <div class="financial-item">
                    <span>Total Fee</span>
                    <strong>
                        &#8377;{{ number_format($totalAmount, 2) }}
                    </strong>
                </div>

                <div class="financial-item">
                    <span>Concession / Discount</span>
                    <strong class="discount-value">
                        - &#8377;{{ number_format($discountAmount, 2) }}
                    </strong>
                </div>

                <div class="financial-item">
                    <span>Late Fine</span>
                    <strong class="fine-value">
                        + &#8377;{{ number_format($fineAmount, 2) }}
                    </strong>
                </div>

                <div class="financial-item">
                    <span>Paid Before This Payment</span>
                    <strong>
                        &#8377;{{ number_format($previousPaid, 2) }}
                    </strong>
                </div>

                <div class="financial-item highlight">
                    <span>This Payment</span>
                    <strong>
                        &#8377;{{ number_format($currentPayment, 2) }}
                    </strong>
                </div>

                <div class="financial-item balance">
                    <span>Remaining Balance</span>
                    <strong>
                        &#8377;{{ number_format($balanceAmount, 2) }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- ADDITIONAL INFORMATION --}}
        @if($feePayment->transaction_reference || $feePayment->remarks)

            <div class="info-card full-card">

                <div class="card-heading">

                    <div class="heading-icon gray">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h3>Additional Information</h3>
                        <p>Additional details recorded with this payment</p>
                    </div>

                </div>


                @if($feePayment->transaction_reference)

                    <div class="additional-row">

                        <span>
                            <i class="bi bi-upc-scan"></i>
                            Transaction Reference
                        </span>

                        <strong>
                            {{ $feePayment->transaction_reference }}
                        </strong>

                    </div>

                @endif


                @if($feePayment->remarks)

                    <div class="remarks-box">

                        <div class="remarks-title">
                            <i class="bi bi-chat-left-text"></i>
                            Remarks
                        </div>

                        <div class="remarks-text">
                            {{ $feePayment->remarks }}
                        </div>

                    </div>

                @endif

            </div>

        @endif


        <div class="screen-footer">
            <i class="bi bi-shield-check"></i>
            This payment transaction has been recorded in the school fee management system.
        </div>

    </div>

</div>



{{-- ============================================================
     PROFESSIONAL PRINT RECEIPT
     This section is hidden on screen and ONLY appears during print.
     ============================================================ --}}

<div class="print-receipt">

    <div class="receipt-paper">

        {{-- SCHOOL HEADER --}}
        <div class="receipt-school-header">

            <div class="school-brand">

                @if($schoolLogo)
                    <img src="{{ asset('storage/' . $schoolLogo) }}"
                         class="school-logo"
                         alt="School Logo">
                @endif

                <div class="school-details">

                    <div class="school-name">
                        {{ $schoolName }}
                    </div>

                    @if($schoolAddress)
                        <div class="school-contact">
                            {{ $schoolAddress }}
                        </div>
                    @endif

                    <div class="school-contact-line">

                        @if($schoolPhone)
                            <span>
                                Phone: {{ $schoolPhone }}
                            </span>
                        @endif

                        @if($schoolEmail)
                            <span>
                                Email: {{ $schoolEmail }}
                            </span>
                        @endif

                    </div>

                </div>

            </div>

            <div class="receipt-title-box">

                <div class="receipt-title">
                    FEE PAYMENT RECEIPT
                </div>

                <div class="receipt-status">
                    {{ $statusLabel }}
                </div>

            </div>

        </div>


        {{-- RECEIPT META --}}
        <div class="receipt-meta">

            <div>
                <span>Receipt No.</span>
                <strong>{{ $feePayment->receipt_number }}</strong>
            </div>

            <div>
                <span>Payment Date</span>
                <strong>
                    {{ $feePayment->payment_date?->format('d M Y') ?? 'N/A' }}
                </strong>
            </div>

            <div>
                <span>Academic Year</span>
                <strong>{{ $academicYear }}</strong>
            </div>

        </div>


        {{-- STUDENT DETAILS --}}
        <div class="print-section-title">
            STUDENT INFORMATION
        </div>

        <table class="receipt-info-table">

            <tr>

                <td>
                    <span>Student Name</span>
                    <strong>{{ $studentName }}</strong>
                </td>

                <td>
                    <span>Student ID</span>
                    <strong>{{ $student->student_id ?? 'N/A' }}</strong>
                </td>

                <td>
                    <span>Roll Number</span>
                    <strong>{{ $student->roll_number ?? 'N/A' }}</strong>
                </td>

            </tr>

            <tr>

                <td>
                    <span>Class</span>
                    <strong>{{ $className }}</strong>
                </td>

                <td>
                    <span>Section</span>
                    <strong>{{ $sectionName }}</strong>
                </td>

                <td>
                    <span>Fee Structure</span>
                    <strong>{{ $structureName }}</strong>
                </td>

            </tr>

        </table>


        {{-- FEE COMPONENTS --}}
        <div class="print-section-title">
            FEE DETAILS
        </div>

        <table class="receipt-fee-table">

            <thead>

                <tr>
                    <th width="7%">#</th>
                    <th>Fee Component</th>
                    <th width="22%">Due Date</th>
                    <th width="22%" class="print-right">Amount</th>
                </tr>

            </thead>

            <tbody>

                @forelse($feeItems as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $item->feeType->name ?? 'Fee' }}
                        </td>

                        <td>
                            {{ $item->due_date?->format('d M Y') ?? 'No Due Date' }}
                        </td>

                        <td class="print-right">
                            &#8377;{{ number_format((float) $item->amount, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="print-empty">
                            No fee components available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

            <tfoot>

                <tr>
                    <td colspan="3">
                        Structure Total
                    </td>

                    <td class="print-right">
                        &#8377;{{ number_format($totalAmount, 2) }}
                    </td>
                </tr>

            </tfoot>

        </table>


        {{-- PAYMENT CALCULATION --}}
        <div class="print-payment-layout">

            <div class="print-payment-left">

                <div class="print-section-title">
                    PAYMENT INFORMATION
                </div>

                <table class="payment-info-print">

                    <tr>
                        <td>Payment Method</td>
                        <td>{{ $feePayment->payment_method }}</td>
                    </tr>

                    <tr>
                        <td>Transaction Reference</td>
                        <td>
                            {{ $feePayment->transaction_reference ?: 'Not Provided' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Payment Status</td>
                        <td>{{ $statusLabel }}</td>
                    </tr>

                    @if($feePayment->remarks)
                        <tr>
                            <td>Remarks</td>
                            <td>{{ $feePayment->remarks }}</td>
                        </tr>
                    @endif

                </table>

            </div>


            <div class="print-payment-right">

                <div class="print-section-title">
                    PAYMENT SUMMARY
                </div>

                <table class="summary-print">

                    <tr>
                        <td>Total Fee</td>
                        <td>
                            &#8377;{{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td>Concession / Discount</td>
                        <td>
                            - &#8377;{{ number_format($discountAmount, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td>Late Fine</td>
                        <td>
                            + &#8377;{{ number_format($fineAmount, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td>Paid Before</td>
                        <td>
                            &#8377;{{ number_format($previousPaid, 2) }}
                        </td>
                    </tr>

                    <tr class="current-payment-row">
                        <td>THIS PAYMENT</td>
                        <td>
                            &#8377;{{ number_format($currentPayment, 2) }}
                        </td>
                    </tr>

                    <tr class="balance-row">
                        <td>REMAINING BALANCE</td>
                        <td>
                            &#8377;{{ number_format($balanceAmount, 2) }}
                        </td>
                    </tr>

                </table>

            </div>

        </div>


        {{-- AMOUNT PAID HIGHLIGHT --}}
        <div class="amount-paid-banner">

            <div>
                <span>Amount Received</span>
                <strong>
                    &#8377;{{ number_format($currentPayment, 2) }}
                </strong>
            </div>

            <div class="amount-status">
                {{ $statusLabel }}
            </div>

        </div>


        {{-- SIGNATURES --}}
        <div class="signature-area">

            <div class="signature-box">

                <div class="signature-line"></div>

                <div class="signature-label">
                    Parent / Guardian Signature
                </div>

            </div>


            <div class="signature-box">

                <div class="signature-line"></div>

                <div class="signature-label">
                    Authorized School Representative
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="receipt-footer">

            <div>
                Thank you for your payment.
            </div>

            <div>
                Generated on
                {{ now()->format('d M Y, h:i A') }}
            </div>

        </div>

        <div class="receipt-note">
            This is a computer-generated fee receipt and does not require a physical stamp.
        </div>

    </div>

</div>



<style>

/* ============================================================
   SCREEN STYLES
   ============================================================ */

.payment-screen {
    min-height: calc(100vh - 64px);
    background: #f4f7fb;
    padding: 30px;
}

.payment-wrapper {
    max-width: 1250px;
    margin: 0 auto;
}

.transaction-card {
    background: #ffffff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(20, 50, 90, 0.08);
    margin-bottom: 22px;
}

.transaction-header {
    background: linear-gradient(135deg, #1769d1, #6c63ff);
    color: #ffffff;
    padding: 24px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(255,255,255,.16);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 25px;
}

.header-title {
    font-size: 23px;
    font-weight: 700;
}

.header-subtitle {
    font-size: 13px;
    opacity: .86;
    margin-top: 4px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-light-action,
.btn-print {
    border: 0;
    border-radius: 10px;
    padding: 10px 16px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.btn-light-action {
    background: rgba(255,255,255,.15);
    color: #ffffff;
}

.btn-light-action:hover {
    background: rgba(255,255,255,.25);
    color: #ffffff;
}

.btn-print {
    background: #ffffff;
    color: #1769d1;
}

.btn-print:hover {
    background: #f1f5ff;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid #eef2f7;
}

.summary-box {
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 13px;
    border-right: 1px solid #eef2f7;
}

.summary-box:last-child {
    border-right: 0;
}

.summary-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.amount-icon {
    background: #e9f2ff;
    color: #1769d1;
}

.date-icon {
    background: #eafaf2;
    color: #15945b;
}

.method-icon {
    background: #f1edff;
    color: #6c63ff;
}

.status-icon {
    background: #e8f8ef;
    color: #1b9b5c;
}

.summary-label {
    font-size: 11px;
    color: #8792a2;
    margin-bottom: 3px;
}

.summary-value {
    font-size: 17px;
    font-weight: 700;
    color: #182334;
}

.small-value {
    font-size: 14px;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 22px;
}

.info-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 23px;
    box-shadow: 0 6px 25px rgba(20, 50, 90, 0.06);
}

.full-card {
    margin-bottom: 22px;
}

.card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.heading-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.heading-icon.blue {
    background: #eaf2ff;
    color: #1769d1;
}

.heading-icon.purple {
    background: #f0edff;
    color: #6c63ff;
}

.heading-icon.green {
    background: #e9f9f1;
    color: #15945b;
}

.heading-icon.orange {
    background: #fff4e6;
    color: #ed8a18;
}

.heading-icon.gray {
    background: #eef1f5;
    color: #657080;
}

.card-heading h3 {
    margin: 0;
    font-size: 17px;
    color: #182334;
}

.card-heading p {
    margin: 3px 0 0;
    color: #8a94a4;
    font-size: 12px;
}

.student-profile {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-bottom: 18px;
    border-bottom: 1px solid #edf0f5;
}

.student-photo,
.student-avatar {
    width: 58px;
    height: 58px;
    border-radius: 15px;
    object-fit: cover;
}

.student-avatar {
    background: linear-gradient(135deg, #1769d1, #6c63ff);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 19px;
}

.student-name {
    font-size: 17px;
    font-weight: 700;
    color: #182334;
}

.student-id {
    color: #8b95a5;
    font-size: 12px;
    margin-top: 4px;
}

.student-id strong {
    color: #526070;
}

.detail-list {
    margin-top: 4px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px dashed #e5e9ef;
    font-size: 13px;
}

.detail-row:last-child {
    border-bottom: 0;
}

.detail-row span {
    color: #7d8796;
}

.detail-row strong {
    color: #263244;
    text-align: right;
}

.receipt-number {
    color: #1769d1 !important;
}

.table-responsive {
    overflow-x: auto;
}

.fee-table {
    width: 100%;
    border-collapse: collapse;
}

.fee-table th {
    background: #f6f8fb;
    color: #647084;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 12px 14px;
    text-align: left;
}

.fee-table td {
    padding: 14px;
    border-bottom: 1px solid #edf0f4;
    color: #4d596a;
    font-size: 13px;
}

.fee-table tfoot td {
    background: #f8faff;
    font-weight: 700;
    color: #182334;
}

.text-right {
    text-align: right !important;
}

.fee-name {
    font-weight: 600;
    color: #273346;
}

.fee-category {
    font-size: 11px;
    color: #98a1ae;
    margin-top: 3px;
}

.fee-amount {
    font-weight: 700;
    color: #1769d1 !important;
}

.empty-row {
    text-align: center;
    color: #9aa3b0 !important;
    padding: 25px !important;
}

.financial-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.financial-item {
    padding: 16px;
    background: #f7f9fc;
    border-radius: 12px;
    border: 1px solid #edf0f4;
}

.financial-item span {
    display: block;
    font-size: 11px;
    color: #7d8796;
    margin-bottom: 6px;
}

.financial-item strong {
    font-size: 16px;
    color: #273346;
}

.financial-item.highlight {
    background: #eef5ff;
    border-color: #d7e7ff;
}

.financial-item.highlight strong {
    color: #1769d1;
}

.financial-item.balance {
    background: #fff7ed;
    border-color: #ffe2bc;
}

.financial-item.balance strong {
    color: #d97706;
}

.discount-value {
    color: #15945b !important;
}

.fine-value {
    color: #dc6b31 !important;
}

.additional-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 14px;
    background: #f8fafc;
    border-radius: 10px;
    font-size: 13px;
}

.additional-row span {
    color: #707b8b;
}

.additional-row strong {
    color: #273346;
}

.remarks-box {
    margin-top: 12px;
    padding: 15px;
    border-radius: 10px;
    background: #f8fafc;
}

.remarks-title {
    font-size: 12px;
    font-weight: 700;
    color: #596678;
    margin-bottom: 7px;
}

.remarks-text {
    font-size: 13px;
    color: #657080;
    line-height: 1.6;
}

.screen-footer {
    text-align: center;
    color: #9aa3b0;
    font-size: 12px;
    padding: 5px 0 25px;
}

.screen-footer i {
    color: #15945b;
    margin-right: 4px;
}


/* ============================================================
   PRINT RECEIPT - HIDDEN ON SCREEN
   ============================================================ */

.print-receipt {
    display: none;
}


/* ============================================================
   RESPONSIVE SCREEN
   ============================================================ */

@media (max-width: 900px) {

    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .summary-box:nth-child(2) {
        border-right: 0;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }

    .financial-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .payment-screen {
        padding: 15px;
    }

    .transaction-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .btn-light-action,
    .btn-print {
        flex: 1;
        justify-content: center;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .summary-box {
        border-right: 0;
        border-bottom: 1px solid #eef2f7;
    }

    .financial-grid {
        grid-template-columns: 1fr;
    }

}


/* ============================================================
   PRINT ONLY
   ============================================================ */

@media print {

    @page {
        size: A4;
        margin: 8mm;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }

    /*
     * Hide the ENTIRE Laravel app layout.
     * This removes navbar, sidebar, header and normal screen content.
     */
    body > * {
        visibility: hidden !important;
    }

    /*
     * Make only our receipt visible.
     */
    .print-receipt {
        display: block !important;
        visibility: visible !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }

    .print-receipt * {
        visibility: visible !important;
    }

    .payment-screen {
        display: none !important;
    }

    .receipt-paper {
        width: 100%;
        background: #ffffff;
        color: #1d2735;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }

    .receipt-school-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding-bottom: 13px;
        border-bottom: 2px solid #1c6fd1;
    }

    .school-brand {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .school-logo {
        width: 62px;
        height: 62px;
        object-fit: contain;
    }

    .school-details {
        min-width: 0;
    }

    .school-name {
        font-size: 21px;
        font-weight: 800;
        color: #155eaa;
        margin-bottom: 4px;
    }

    .school-contact {
        color: #5c6877;
        line-height: 1.4;
        max-width: 480px;
    }

    .school-contact-line {
        display: flex;
        gap: 15px;
        margin-top: 3px;
        color: #667282;
        font-size: 9px;
    }

    .receipt-title-box {
        text-align: right;
        flex-shrink: 0;
    }

    .receipt-title {
        font-size: 16px;
        font-weight: 800;
        color: #222d3d;
        letter-spacing: .06em;
    }

    .receipt-status {
        display: inline-block;
        margin-top: 6px;
        padding: 4px 10px;
        border: 1px solid #1c9b5a;
        color: #16864d;
        font-weight: 700;
        font-size: 8px;
        letter-spacing: .08em;
    }

    .receipt-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border: 1px solid #dce2e9;
        margin-top: 13px;
    }

    .receipt-meta > div {
        padding: 8px 10px;
        border-right: 1px solid #dce2e9;
    }

    .receipt-meta > div:last-child {
        border-right: 0;
    }

    .receipt-meta span,
    .receipt-info-table span {
        display: block;
        font-size: 8px;
        color: #788494;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 3px;
    }

    .receipt-meta strong,
    .receipt-info-table strong {
        display: block;
        color: #263346;
        font-size: 10px;
    }

    .print-section-title {
        font-size: 9px;
        font-weight: 800;
        color: #155eaa;
        letter-spacing: .08em;
        margin-top: 14px;
        margin-bottom: 6px;
        padding-bottom: 4px;
        border-bottom: 1px solid #dfe5eb;
    }

    .receipt-info-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #dce2e9;
    }

    .receipt-info-table td {
        width: 33.333%;
        padding: 8px 10px;
        border-right: 1px solid #dce2e9;
        border-bottom: 1px solid #dce2e9;
        vertical-align: top;
    }

    .receipt-info-table td:last-child {
        border-right: 0;
    }

    .receipt-info-table tr:last-child td {
        border-bottom: 0;
    }

    .receipt-fee-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #dce2e9;
    }

    .receipt-fee-table th {
        background: #f2f6fa;
        color: #465568;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 7px 9px;
        border: 1px solid #dce2e9;
        text-align: left;
    }

    .receipt-fee-table td {
        padding: 7px 9px;
        border: 1px solid #e1e6ec;
        color: #394657;
        font-size: 9px;
    }

    .receipt-fee-table tfoot td {
        background: #f5f8fb;
        font-weight: 800;
        color: #263346;
    }

    .print-right {
        text-align: right !important;
    }

    .print-empty {
        text-align: center;
        color: #7c8794 !important;
    }

    .print-payment-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        page-break-inside: avoid;
    }

    .payment-info-print,
    .summary-print {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #dce2e9;
    }

    .payment-info-print td,
    .summary-print td {
        padding: 7px 9px;
        border-bottom: 1px solid #e2e7ed;
        font-size: 9px;
    }

    .payment-info-print td:first-child {
        width: 43%;
        color: #707c8b;
    }

    .payment-info-print td:last-child {
        color: #293547;
        font-weight: 600;
    }

    .summary-print td:first-child {
        color: #657181;
    }

    .summary-print td:last-child {
        text-align: right;
        font-weight: 700;
        color: #283446;
    }

    .summary-print tr:last-child td {
        border-bottom: 0;
    }

    .current-payment-row td {
        background: #eef5ff;
        color: #155eaa !important;
        font-weight: 800 !important;
    }

    .balance-row td {
        background: #fff6e9;
        color: #a45b08 !important;
        font-weight: 800 !important;
    }

    .amount-paid-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 13px;
        padding: 10px 14px;
        background: #edf7f1;
        border: 1px solid #cce8d8;
        page-break-inside: avoid;
    }

    .amount-paid-banner span {
        display: block;
        color: #557064;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .amount-paid-banner strong {
        display: block;
        margin-top: 2px;
        font-size: 17px;
        color: #16864d;
    }

    .amount-status {
        font-size: 9px;
        font-weight: 800;
        color: #16864d;
        border: 1px solid #92cbaa;
        padding: 5px 10px;
    }

    .signature-area {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 70px;
        margin-top: 34px;
        page-break-inside: avoid;
    }

    .signature-box {
        text-align: center;
    }

    .signature-line {
        height: 28px;
        border-bottom: 1px solid #4e5a68;
        margin-bottom: 5px;
    }

    .signature-label {
        font-size: 8px;
        color: #5f6b7a;
    }

    .receipt-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        padding-top: 8px;
        border-top: 1px solid #dce2e9;
        color: #697585;
        font-size: 8px;
    }

    .receipt-note {
        text-align: center;
        color: #9aa3ae;
        font-size: 7px;
        margin-top: 6px;
    }

}


/* ============================================================
   PRINT SAFETY
   ============================================================ */

@media print {

    nav,
    aside,
    header,
    footer,
    .sidebar,
    .navbar,
    .app-sidebar,
    .app-header,
    .topbar,
    .sidebar-wrapper,
    .navbar-wrapper {
        display: none !important;
    }

}

</style>

@endsection