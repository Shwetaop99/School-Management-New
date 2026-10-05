
@extends('layouts.app')

@section('title', 'Collect Student Fee Payment')

@section('content')

<style>
    .payment-page {
        background: #f4f7fb;
        min-height: calc(100vh - 80px);
        padding: 24px;
    }

    .payment-header {
        background: linear-gradient(135deg, #1769d1, #6c63ff);
        color: #fff;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(23, 105, 209, .15);
    }

    .payment-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .payment-header p {
        margin: 7px 0 0;
        font-size: 14px;
        opacity: .9;
    }

    .payment-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 20px;
        align-items: start;
    }

    .payment-card,
    .summary-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(31, 45, 61, .07);
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
        padding: 22px;
    }

    .student-box {
        background: #f7faff;
        border: 1px solid #e5edf9;
        border-radius: 13px;
        padding: 17px;
        margin-bottom: 22px;
    }

    .student-name {
        font-size: 18px;
        font-weight: 800;
        color: #1f2937;
    }

    .student-id {
        color: #667085;
        font-size: 13px;
        margin-top: 4px;
    }

    .student-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 15px;
    }

    .detail-item {
        background: #fff;
        border: 1px solid #edf1f7;
        border-radius: 10px;
        padding: 11px;
    }

    .detail-label {
        color: #7b8494;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .detail-value {
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #344054;
        margin-bottom: 7px;
    }

    .form-control-custom {
        width: 100%;
        border: 1px solid #d8dee9;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
        color: #344054;
        background: #fff;
        outline: none;
        transition: .2s;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .10);
    }

    textarea.form-control-custom {
        min-height: 105px;
        resize: vertical;
    }

    .amount-input {
        font-size: 20px;
        font-weight: 700;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 9px;
    }

    .method-option {
        position: relative;
    }

    .method-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .method-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 58px;
        padding: 7px;
        text-align: center;
        border: 1px solid #dce2ec;
        border-radius: 10px;
        background: #fff;
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .method-option input:checked + label {
        border-color: #1769d1;
        background: #eff6ff;
        color: #1769d1;
        box-shadow: 0 0 0 2px rgba(23, 105, 209, .08);
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }

    .summary-body {
        padding: 20px;
    }

    .summary-student {
        text-align: center;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf0f5;
    }

    .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 24px;
    }

    .summary-student strong {
        display: block;
        font-size: 16px;
        color: #1f2937;
    }

    .summary-student span {
        color: #7b8494;
        font-size: 12px;
    }

    .amount-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0f5;
        font-size: 13px;
    }

    .amount-row:last-child {
        border-bottom: none;
    }

    .amount-row .label {
        color: #667085;
    }

    .amount-row .value {
        font-weight: 700;
        color: #344054;
    }

    .balance-row {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 11px;
        padding: 14px;
        margin-top: 15px;
    }

    .balance-row .label {
        color: #c2410c;
        font-size: 12px;
        font-weight: 700;
    }

    .balance-row .value {
        color: #ea580c;
        font-size: 22px;
        font-weight: 800;
        margin-top: 3px;
    }

    .buttons {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .btn-custom {
        border: none;
        border-radius: 10px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        transition: .2s;
    }

    .btn-save {
        background: #1769d1;
        color: #fff;
        flex: 1;
    }

    .btn-save:hover {
        background: #125bb7;
    }

    .btn-cancel {
        background: #eef2f7;
        color: #475467;
    }

    .btn-custom:hover {
        transform: translateY(-1px);
    }

    .success-message {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    @media (max-width: 1050px) {
        .payment-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .payment-page {
            padding: 14px;
        }

        .student-details {
            grid-template-columns: 1fr;
        }

        .payment-methods {
            grid-template-columns: repeat(2, 1fr);
        }

        .payment-header {
            padding: 20px;
        }
    }
</style>

@php
    $student = $studentFee->student;

    $studentName = trim(
        ($student->first_name ?? '') . ' ' .
        ($student->middle_name ?? '') . ' ' .
        ($student->last_name ?? '')
    );

    $structure = $studentFee->feeStructure;

    $className = optional(optional($structure)->schoolClass)->class_name ?? '-';

    $sectionName = optional(optional($structure)->section)->section_name
        ?? 'All Sections';

    $balance = (float) $studentFee->balance_amount;
@endphp

<div class="payment-page">

    {{-- HEADER --}}
    <div class="payment-header">

        <h2>
            <i class="bi bi-credit-card me-2"></i>
            Collect Student Fee Payment
        </h2>

        <p>
            Record a payment for the selected student fee.
        </p>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="success-message"
             style="background:#fef2f2;color:#b91c1c;border-color:#fecaca;">

            <strong>Please correct the following:</strong>

            <ul style="margin:8px 0 0 18px;padding:0;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="payment-layout">

        {{-- PAYMENT FORM --}}
        <div class="payment-card">

            <div class="card-title">

                <i class="bi bi-wallet2"></i>

                Payment Information

            </div>

            <div class="card-body">

                {{-- STUDENT --}}
                <div class="student-box">

                    <div class="student-name">
                        {{ $studentName ?: '-' }}
                    </div>

                    <div class="student-id">
                        Student ID:
                        {{ $student->student_id ?? '-' }}
                    </div>

                    <div class="student-details">

                        <div class="detail-item">

                            <div class="detail-label">
                                Class
                            </div>

                            <div class="detail-value">
                                {{ $className }}
                            </div>

                        </div>

                        <div class="detail-item">

                            <div class="detail-label">
                                Section
                            </div>

                            <div class="detail-value">
                                {{ $sectionName }}
                            </div>

                        </div>

                        <div class="detail-item">

                            <div class="detail-label">
                                Academic Year
                            </div>

                            <div class="detail-value">
                                {{ $studentFee->academic_year }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- FORM --}}
                <form
                    method="POST"
                    action="{{ route(
                        'admin.fees.student-fees.payment.store',
                        $studentFee
                    ) }}"
                >

                    @csrf

                    {{-- AMOUNT --}}
                    <div class="form-group">

                        <label class="form-label">
                            Payment Amount
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="paymentAmount"
                            class="form-control-custom amount-input"
                            value="{{ old('amount') }}"
                            min="0.01"
                            max="{{ $balance }}"
                            step="0.01"
                            placeholder="Enter payment amount"
                            required
                        >

                        <div style="margin-top:6px;font-size:12px;color:#7b8494;">
                            Maximum payable amount:
                            <strong>
                                &#8377;{{ number_format($balance, 2) }}
                            </strong>
                        </div>

                        @error('amount')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- DATE --}}
                    <div class="form-group">

                        <label class="form-label">
                            Payment Date
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            class="form-control-custom"
                            value="{{ old(
                                'payment_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('payment_date')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- PAYMENT METHOD --}}
                    <div class="form-group">

                        <label class="form-label">
                            Payment Method
                            <span style="color:#dc2626;">*</span>
                        </label>

                        <div class="payment-methods">

                            @foreach([
                                'Cash',
                                'UPI',
                                'Card',
                                'Bank Transfer',
                                'Cheque'
                            ] as $method)

                                <div class="method-option">

                                    <input
                                        type="radio"
                                        name="payment_method"
                                        id="method_{{ Str::slug($method) }}"
                                        value="{{ $method }}"
                                        {{ old(
                                            'payment_method',
                                            'Cash'
                                        ) === $method ? 'checked' : '' }}
                                    >

                                    <label
                                        for="method_{{ Str::slug($method) }}"
                                    >
                                        {{ $method }}
                                    </label>

                                </div>

                            @endforeach

                        </div>

                        @error('payment_method')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- TRANSACTION REFERENCE --}}
                    <div class="form-group">

                        <label class="form-label">
                            Transaction Reference
                            <span style="font-weight:400;color:#98a2b3;">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            name="transaction_reference"
                            class="form-control-custom"
                            value="{{ old('transaction_reference') }}"
                            maxlength="255"
                            placeholder="UPI ID, cheque number, bank reference, etc."
                        >

                        @error('transaction_reference')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- REMARKS --}}
                    <div class="form-group">

                        <label class="form-label">
                            Remarks
                            <span style="font-weight:400;color:#98a2b3;">
                                (Optional)
                            </span>
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control-custom"
                            placeholder="Add any payment remarks..."
                        >{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- BUTTONS --}}
                    <div class="buttons">

                        <a
                            href="{{ route(
                                'admin.fees.student-fees.show',
                                $studentFee
                            ) }}"
                            class="btn-custom btn-cancel"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn-custom btn-save"
                            {{ $balance <= 0 ? 'disabled' : '' }}
                        >
                            <i class="bi bi-check-circle"></i>
                            Save Payment
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="summary-card">

            <div class="card-title">

                <i class="bi bi-receipt"></i>

                Fee Summary

            </div>

            <div class="summary-body">

                <div class="summary-student">

                    <div class="avatar">
                        <i class="bi bi-person"></i>
                    </div>

                    <strong>
                        {{ $studentName ?: '-' }}
                    </strong>

                    <span>
                        {{ $student->student_id ?? '-' }}
                    </span>

                </div>


                <div class="amount-row">

                    <span class="label">
                        Total Fee
                    </span>

                    <span class="value">
                        &#8377;{{ number_format(
                            (float) $studentFee->total_amount,
                            2
                        ) }}
                    </span>

                </div>


                <div class="amount-row">

                    <span class="label">
                        Concession
                    </span>

                    <span class="value">
                        - &#8377;{{ number_format(
                            (float) $studentFee->discount_amount,
                            2
                        ) }}
                    </span>

                </div>


                <div class="amount-row">

                    <span class="label">
                        Late Fine
                    </span>

                    <span class="value">
                        + &#8377;{{ number_format(
                            (float) $studentFee->fine_amount,
                            2
                        ) }}
                    </span>

                </div>


                <div class="amount-row">

                    <span class="label">
                        Already Paid
                    </span>

                    <span class="value">
                        &#8377;{{ number_format(
                            (float) $studentFee->paid_amount,
                            2
                        ) }}
                    </span>

                </div>


                <div class="balance-row">

                    <div class="label">
                        REMAINING BALANCE
                    </div>

                    <div class="value"
                         id="remainingBalance">

                        &#8377;{{ number_format(
                            $balance,
                            2
                        ) }}

                    </div>

                </div>


                <div style="
                    margin-top:15px;
                    font-size:12px;
                    color:#7b8494;
                    line-height:1.6;
                ">

                    After saving this payment, the paid amount,
                    balance and fee status will be updated automatically.

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const amountInput =
            document.getElementById('paymentAmount');

        const balanceDisplay =
            document.getElementById('remainingBalance');

        const currentBalance =
            {{ $balance }};

        if (!amountInput || !balanceDisplay) {
            return;
        }

        amountInput.addEventListener('input', function () {

            let payment =
                parseFloat(this.value) || 0;

            if (payment < 0) {
                payment = 0;
            }

            if (payment > currentBalance) {
                payment = currentBalance;
                this.value = currentBalance.toFixed(2);
            }

            const remaining =
                Math.max(
                    0,
                    currentBalance - payment
                );

            balanceDisplay.innerHTML =
                '&#8377;' +
                remaining.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        });

    });
</script>

@endsection
