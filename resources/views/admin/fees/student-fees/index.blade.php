@extends('layouts.app')

@section('title', 'Student Fees')

@section('content')

<style>
    .dashboard-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
    }

    /* =========================
       BLUE HEADER
    ========================== */

    .welcome-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1769d1, #159cc7);
        border-radius: 18px;
        padding: 25px 28px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(23, 105, 209, .18);
    }

    .welcome-card::before,
    .welcome-card::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        pointer-events: none;
    }

    .welcome-card::before {
        width: 180px;
        height: 180px;
        right: -45px;
        top: -80px;
    }

    .welcome-card::after {
        width: 130px;
        height: 130px;
        right: 100px;
        bottom: -85px;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .welcome-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .welcome-icon {
        width: 56px;
        height: 56px;
        border-radius: 15px;
        background: rgba(255, 255, 255, .16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    .welcome-text h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 750;
        color: #fff;
    }

    .welcome-text p {
        margin: 5px 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, .88);
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #fff;
        color: #1769d1;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        border: none;
        transition: .2s;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #f5f9ff;
        color: #0d5fbe;
        transform: translateY(-1px);
    }

    /* =========================
       STAT CARDS
    ========================== */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        min-height: 125px;
        border-radius: 16px;
        padding: 20px;
        color: #fff;
        box-shadow: 0 8px 22px rgba(30, 55, 90, .10);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 105px;
        height: 105px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .09);
        right: -28px;
        bottom: -48px;
    }

    .stat-card.blue {
        background: linear-gradient(135deg, #1769d1, #237de0);
    }

    .stat-card.orange {
        background: linear-gradient(135deg, #ed9208, #f7aa25);
    }

    .stat-card.cyan {
        background: linear-gradient(135deg, #079dbd, #16b5d0);
    }

    .stat-card.red {
        background: linear-gradient(135deg, #e94d47, #f75d56);
    }

    .stat-content {
        position: relative;
        z-index: 2;
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-label {
        margin-top: 15px;
        font-size: 12px;
        color: rgba(255, 255, 255, .84);
    }

    .stat-value {
        margin-top: 3px;
        font-size: 25px;
        font-weight: 750;
        color: #fff;
    }

    /* =========================
       ALERTS
    ========================== */

    .alert-success-custom,
    .alert-danger-custom {
        border: none;
        border-radius: 11px;
        padding: 12px 15px;
        margin-bottom: 18px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-success-custom {
        background: #e7f8ef;
        color: #147b55;
    }

    .alert-danger-custom {
        background: #fff0f0;
        color: #c03939;
    }

    /* =========================
       DASHBOARD CARD
    ========================== */

    .dashboard-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf5;
        box-shadow: 0 5px 18px rgba(30, 55, 90, .05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header-custom {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .card-title i {
        color: #1769d1;
    }

    /* =========================
       FILTERS
    ========================== */

    .filter-body {
        padding: 20px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr auto auto;
        gap: 13px;
        align-items: end;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 12px;
        font-weight: 650;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 42px;
        border: 1px solid #dbe2ec;
        border-radius: 9px;
        padding: 0 12px;
        background: #fff;
        color: #344054;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .08);
    }

    .btn-filter,
    .btn-reset {
        height: 42px;
        padding: 0 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-filter {
        border: none;
        background: #1769d1;
        color: #fff;
    }

    .btn-filter:hover {
        background: #0d5fbe;
        color: #fff;
    }

    .btn-reset {
        border: 1px solid #dbe2ec;
        background: #fff;
        color: #526071;
    }

    .btn-reset:hover {
        background: #f7f9fc;
        color: #344054;
    }

    /* =========================
       TABLE
    ========================== */

    .table-wrapper {
        overflow-x: auto;
    }

    .fees-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
    }

    .fees-table th {
        background: #f8faff;
        color: #667085;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 700;
        padding: 13px 14px;
        border-bottom: 1px solid #e8edf5;
        white-space: nowrap;
    }

    .fees-table td {
        padding: 14px;
        border-bottom: 1px solid #edf1f6;
        color: #344054;
        font-size: 13px;
        vertical-align: middle;
    }

    .fees-table tbody tr {
        transition: .15s;
    }

    .fees-table tbody tr:hover {
        background: #fbfcff;
    }

    /* =========================
       STUDENT
    ========================== */

    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #e9f2ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 750;
        font-size: 13px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .student-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .student-name {
        font-weight: 700;
        color: #172033;
        margin-bottom: 2px;
        white-space: nowrap;
    }

    .student-id {
        font-size: 11px;
        color: #8792a2;
    }

    /* =========================
       FEE STRUCTURE
    ========================== */

    .structure-name {
        font-weight: 650;
        color: #344054;
        margin-bottom: 3px;
    }

    .structure-meta {
        color: #8792a2;
        font-size: 11px;
    }

    .academic-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #eef4ff;
        color: #1769d1;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================
       AMOUNTS
    ========================== */

    .amount {
        font-weight: 700;
        color: #172033;
        white-space: nowrap;
    }

    .concession {
        color: #6c63ff;
        font-weight: 650;
        white-space: nowrap;
    }

    .fine {
        color: #d97706;
        font-weight: 650;
        white-space: nowrap;
    }

    .paid {
        color: #168557;
        font-weight: 750;
        white-space: nowrap;
    }

    .remaining {
        color: #d94841;
        font-weight: 750;
        white-space: nowrap;
    }

    .muted {
        color: #8a95a5;
        font-size: 12px;
    }

    .fee-currency {
        white-space: nowrap;
    }

    /* =========================
       STATUS
    ========================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff3df;
        color: #b96b00;
    }

    .status-partial {
        background: #eeeaff;
        color: #665bd8;
    }

    .status-paid {
        background: #e7f8ef;
        color: #168557;
    }

    .status-overdue {
        background: #ffe9e9;
        color: #d13c3c;
    }

    /* =========================
       ACTION BUTTONS
    ========================== */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #e1e7ef;
        background: #fff;
        font-size: 14px;
        cursor: pointer;
        transition: .2s;
    }

    .action-view {
        color: #1769d1;
    }

    .action-view:hover {
        background: #eaf3ff;
        border-color: #bcd9ff;
        color: #1769d1;
    }

    .action-delete {
        color: #e04b4b;
    }

    .action-delete:hover {
        background: #fff0f0;
        border-color: #ffcaca;
        color: #d63c3c;
    }

    /* =========================
       EMPTY STATE
    ========================== */

    .empty-state {
        text-align: center;
        padding: 65px 20px;
    }

    .empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 15px;
        border-radius: 19px;
        background: #eef4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .empty-state h5 {
        margin: 0 0 7px;
        color: #344054;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0 0 18px;
        color: #8792a2;
        font-size: 13px;
    }

    /* =========================
       PAGINATION
    ========================== */

    .pagination-wrapper {
        padding: 17px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        border-top: 1px solid #edf1f6;
    }

    .pagination-info {
        color: #7b8798;
        font-size: 12px;
    }

    .pagination-wrapper nav {
        margin-left: auto;
    }


    .remaining-amount {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 8px;
    background: #fff0f0;
    color: #d94841;
    font-weight: 750;
    white-space: nowrap;
}

.due-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 8px;
    background: #eef4ff;
    color: #1769d1;
    font-weight: 700;
    white-space: nowrap;
}

.due-date i {
    font-size: 12px;
}
    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .dashboard-container {
            padding: 15px;
        }

        .welcome-card {
            padding: 20px;
        }

        .welcome-content {
            align-items: flex-start;
        }

        .welcome-left {
            align-items: flex-start;
        }

        .welcome-text h2 {
            font-size: 21px;
        }

        .welcome-text p {
            line-height: 1.5;
        }

        .btn-add {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .pagination-wrapper nav {
            margin-left: 0;
        }
    }
</style>

<div class="dashboard-container">

    {{-- =========================
         BLUE HEADER
    ========================== --}}

    <div class="welcome-card">

        <div class="welcome-content">

            <div class="welcome-left">

                <div class="welcome-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>

                <div class="welcome-text">
                    <h2>Student Fees</h2>
                    <p>
                        Manage student fee assignments, payments and outstanding balances.
                    </p>
                </div>

            </div>

            <a
                href="{{ route('admin.fees.student-fees.create') }}"
                class="btn-add"
            >
                <i class="bi bi-plus-lg"></i>
                Assign Fee
            </a>

        </div>

    </div>


    {{-- =========================
         SUCCESS / ERROR
    ========================== --}}

    @if(session('success'))

        <div class="alert-success-custom">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>

    @endif

    @if(session('error'))

        <div class="alert-danger-custom">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>

    @endif


    {{-- =========================
         STATISTICS
    ========================== --}}

    <div class="stats-grid">

        {{-- Total --}}
        <div class="stat-card blue">

            <div class="stat-content">

                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total Student Fees</div>

                        <div class="stat-value">
                            {{ $totalFees }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="stat-card orange">

            <div class="stat-content">

                <div class="stat-top">
                    <div>
                        <div class="stat-label">Pending Fees</div>

                        <div class="stat-value">
                            {{ $pendingFees }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>

            </div>

        </div>


        {{-- Partial --}}
        <div class="stat-card cyan">

            <div class="stat-content">

                <div class="stat-top">
                    <div>
                        <div class="stat-label">Partial Payments</div>

                        <div class="stat-value">
                            {{ $partialFees }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>

            </div>

        </div>


        {{-- Paid --}}
        <div class="stat-card red">

            <div class="stat-content">

                <div class="stat-top">
                    <div>
                        <div class="stat-label">Paid Fees</div>

                        <div class="stat-value">
                            {{ $paidFees }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         FILTER CARD
    ========================== --}}

    <div class="dashboard-card">

        <div class="card-header-custom">

            <h5 class="card-title">
                <i class="bi bi-funnel-fill"></i>
                Filter Student Fees
            </h5>

        </div>

        <div class="filter-body">

            <form
                method="GET"
                action="{{ route('admin.fees.student-fees.index') }}"
                class="filter-form"
            >

                {{-- Search --}}
                <div class="form-group">

                    <label for="search">
                        Search Student
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Student ID or name..."
                    >

                </div>


                {{-- Academic Year --}}
                <div class="form-group">

                    <label for="academic_year">
                        Academic Year
                    </label>

                    <select
                        id="academic_year"
                        name="academic_year"
                        class="form-select"
                    >

                        <option value="">
                            All Years
                        </option>

                        @foreach($academicYears as $year)

                            <option
                                value="{{ $year }}"
                                {{ request('academic_year') == $year ? 'selected' : '' }}
                            >
                                {{ $year }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="Pending"
                            {{ request('status') == 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Partial"
                            {{ request('status') == 'Partial' ? 'selected' : '' }}
                        >
                            Partial
                        </option>

                        <option
                            value="Paid"
                            {{ request('status') == 'Paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="Overdue"
                            {{ request('status') == 'Overdue' ? 'selected' : '' }}
                        >
                            Overdue
                        </option>

                    </select>

                </div>


                {{-- Filter --}}
                <button
                    type="submit"
                    class="btn-filter"
                >
                    <i class="bi bi-search"></i>
                    Filter
                </button>


                {{-- Reset --}}
                <a
                    href="{{ route('admin.fees.student-fees.index') }}"
                    class="btn-reset"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>

            </form>

        </div>

    </div>


    {{-- =========================
         STUDENT FEE TABLE
    ========================== --}}

    <div class="dashboard-card">

        <div class="card-header-custom">

            <h5 class="card-title">
                <i class="bi bi-list-ul"></i>
                Student Fee Records
            </h5>

            @if($studentFees->count())

                <span class="muted">
                    {{ $studentFees->total() }} records
                </span>

            @endif

        </div>


        <div class="table-wrapper">

            @if($studentFees->count())

                <table class="fees-table">

                    <thead>

                        <tr>
                            <th>Student</th>
                            <th>Academic Year</th>
                            <th>Fee Structure</th>
                            <th>Total</th>
                            <th>Concession</th>
                            <th>Late Fine</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($studentFees as $studentFee)

                            <tr>

                                {{-- Student --}}
                                <td>

                                    <div class="student-info">

                                        <div class="student-avatar">

                                            @if(
                                                $studentFee->student &&
                                                !empty($studentFee->student->profile_image)
                                            )

                                                <img
                                                    src="{{ asset('storage/' . $studentFee->student->profile_image) }}"
                                                    alt="Student"
                                                >

                                            @else

                                                {{ strtoupper(
                                                    substr(
                                                        $studentFee->student->first_name ?? 'S',
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            @endif

                                        </div>


                                        <div>

                                            <div class="student-name">

                                                {{ $studentFee->student->first_name ?? '' }}

                                                {{ $studentFee->student->middle_name ?? '' }}

                                                {{ $studentFee->student->last_name ?? '' }}

                                            </div>

                                            <div class="student-id">
                                                {{ $studentFee->student->student_id ?? 'N/A' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Academic Year --}}
                                <td>

                                    <span class="academic-badge">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $studentFee->academic_year }}
                                    </span>

                                </td>


                                {{-- Fee Structure --}}
                                <td>

                                    @if($studentFee->feeStructure)

                                        <div class="structure-name">
                                            {{ $studentFee->feeStructure->structure_name }}
                                        </div>

                                        <div class="structure-meta">

                                            {{ $studentFee->feeStructure->schoolClass->class_name ?? 'N/A' }}

                                            @if($studentFee->feeStructure->section)

                                                ·
                                                {{ $studentFee->feeStructure->section->section_name }}

                                            @else

                                                · All Sections

                                            @endif

                                        </div>

                                    @else

                                        <span class="muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Total --}}
                                <td>

                                    <span class="amount fee-currency">
                                        &#8377;{{ number_format((float) $studentFee->total_amount, 2) }}
                                    </span>

                                </td>


                                {{-- Concession --}}
                                <td>

                                    <span class="concession fee-currency">
                                        &#8377;{{ number_format((float) $studentFee->discount_amount, 2) }}
                                    </span>

                                </td>


                                {{-- Fine --}}
                                <td>

                                    <span class="fine fee-currency">
                                        &#8377;{{ number_format((float) $studentFee->fine_amount, 2) }}
                                    </span>

                                </td>


                                {{-- Paid --}}
                                <td>

                                    <span class="paid fee-currency">
                                        &#8377;{{ number_format((float) $studentFee->paid_amount, 2) }}
                                    </span>

 {{-- Remaining --}}
<td>
    <span class="remaining-amount">
        &#8377;{{ number_format((float) $studentFee->balance_amount, 2) }}
    </span>
</td>

{{-- Due Date --}}
<td>
    @if($studentFee->due_date)
        <span class="due-date">
            <i class="bi bi-calendar-event"></i>
            {{ $studentFee->due_date->format('d M Y') }}
        </span>
    @else
        <span class="muted">
            Not set
        </span>
    @endif
</td>

                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match($studentFee->status) {

                                            'Paid' => 'status-paid',

                                            'Partial' => 'status-partial',

                                            'Overdue' => 'status-overdue',

                                            default => 'status-pending',

                                        };

                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">

                                        @if($studentFee->status === 'Paid')
                                            <i class="bi bi-check-circle-fill"></i>
                                        @elseif($studentFee->status === 'Partial')
                                            <i class="bi bi-hourglass-split"></i>
                                        @elseif($studentFee->status === 'Overdue')
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                        @else
                                            <i class="bi bi-clock-fill"></i>
                                        @endif

                                        {{ $studentFee->status }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.fees.student-fees.show', $studentFee) }}"
                                            class="action-btn action-view"
                                            title="View Student Fee"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <form
                                            action="{{ route('admin.fees.student-fees.destroy', $studentFee) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this student fee?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn action-delete"
                                                title="Delete Student Fee"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-receipt"></i>
                    </div>

                    <h5>
                        No Student Fees Found
                    </h5>

                    <p>
                        No student fee records are available for the selected filters.
                    </p>

                    <a
                        href="{{ route('admin.fees.student-fees.create') }}"
                        class="btn-add"
                    >
                        <i class="bi bi-plus-lg"></i>
                        Assign First Fee
                    </a>

                </div>

            @endif

        </div>


        {{-- =========================
             PAGINATION
        ========================== --}}

        @if($studentFees->hasPages())

            <div class="pagination-wrapper">

                <div class="pagination-info">

                    Showing
                    {{ $studentFees->firstItem() ?? 0 }}
                    to
                    {{ $studentFees->lastItem() ?? 0 }}
                    of
                    {{ $studentFees->total() }}
                    records

                </div>

                <div>
                    {{ $studentFees->links() }}
                </div>

            </div>

        @endif

    </div>

</div>

@endsection