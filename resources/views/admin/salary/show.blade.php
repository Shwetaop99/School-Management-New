@extends('layouts.app')

@section('title', 'View Salary')
@section('page-title', 'Salary Details')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Works for every staff type (Teacher, Driver, Peon, Librarian, ...)
    |--------------------------------------------------------------------------
    */
    $staffTypeLabels = [
        'Teacher'      => 'Teacher',
        'Driver'       => 'Driver',
        'Peon'         => 'Peon',
        'Librarian'    => 'Librarian',
        'Accountant'   => 'Accountant',
        'Receptionist' => 'Receptionist',
        'OtherStaff'   => 'Other Staff',
    ];

    $isTeacher = $teacherSalary->staff_type === 'Teacher'
        || (empty($teacherSalary->staff_type) && $teacherSalary->teacher_id);

    $typeKey   = $isTeacher ? 'Teacher' : ($teacherSalary->staff_type ?: 'OtherStaff');
    $typeLabel = $staffTypeLabels[$typeKey] ?? $typeKey;

    $person = $isTeacher ? $teacherSalary->teacher : $teacherSalary->otherStaff;

    $personName = $isTeacher
        ? trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? ''))
        : trim($person->name ?? '');
    $personName = $personName !== '' ? $personName : 'N/A';

    $personCode  = $isTeacher ? ($person->teacher_id ?? null) : ($person->staff_id ?? null);
    $personEmail = $person->email ?? null;
    $personPhone = $person->phone ?? null;
    $personImage = $isTeacher
        ? ($person->profile_image ?? null)
        : ($person->photo ?? $person->profile_image ?? null);
    $designation = $isTeacher ? 'Teacher' : ($person->designation ?? $typeLabel);

    // Safe month formatting ("2026-10" -> "October 2026")
    $formatMonth = function ($value, $format = 'F Y') {
        try {
            return \Carbon\Carbon::createFromFormat('!Y-m', $value)->format($format);
        } catch (\Throwable $e) {
            try {
                return \Carbon\Carbon::parse($value)->format($format);
            } catch (\Throwable $e2) {
                return $value ?: 'N/A';
            }
        }
    };

    $salaryOptions = $salaryOptions ?? collect();
@endphp

<style>
/* =========================================================
   SALARY DETAILS PAGE
========================================================= */

.salary-page {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
}

.salary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.salary-header-left h2 {
    margin: 0;
    font-size: 23px;
    font-weight: 700;
    color: #172033;
}

.salary-header-left p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #7b8496;
}

.salary-header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.salary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 7px;
    border: none;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.salary-btn:hover {
    transform: translateY(-1px);
}

.btn-back {
    background: #eef2f7;
    color: #475569;
}

.btn-edit {
    background: #147cf5;
    color: #fff;
}

.btn-receipt {
    background: #16a34a;
    color: #fff;
}

.salary-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e7ebf2;
    box-shadow: 0 4px 18px rgba(20, 45, 80, .06);
    overflow: hidden;
    margin-bottom: 18px;
}

.salary-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #edf0f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

.salary-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.salary-card-title h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #172033;
}

.salary-card-title span {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eef5ff;
    color: #147cf5;
}

.type-chip {
    display: inline-flex;
    align-items: center;
    padding: 5px 11px;
    border-radius: 20px;
    background: #f0eaf8;
    color: #5b4a8a;
    font-size: 11px;
    font-weight: 800;
}

.salary-content {
    padding: 18px 20px;
}

.salary-section {
    margin-bottom: 18px;
}

.salary-section:last-child {
    margin-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 11px;
    padding-bottom: 7px;
    border-bottom: 1px solid #edf0f5;
    color: #334155;
    font-size: 14px;
    font-weight: 700;
}

.section-title i {
    color: #147cf5;
}

/* =========================================================
   FINDER (department dropdown + search + record dropdown)
========================================================= */

.finder-body {
    padding: 18px 20px;
}

.finder-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr 1.6fr;
    gap: 12px;
}

.finder-field label {
    display: block;
    margin-bottom: 6px;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.finder-control {
    width: 100%;
    height: 42px;
    padding: 0 12px;
    border: 1px solid #dbe2ec;
    border-radius: 8px;
    background: #fff;
    color: #273247;
    font-family: inherit;
    font-size: 13px;
    outline: none;
}

.finder-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, .12);
}

.finder-search-wrap {
    position: relative;
}

.finder-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}

.finder-search-wrap .finder-control {
    padding-left: 34px;
}

.finder-hint {
    margin-top: 10px;
    color: #8a94a6;
    font-size: 11px;
}

.finder-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-top: 14px;
}

.finder-stat {
    padding: 11px 13px;
    border: 1px solid #e8edf4;
    border-radius: 8px;
    background: #f8fbff;
}

.finder-stat-label {
    display: block;
    margin-bottom: 4px;
    color: #7d8798;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
}

.finder-stat-value {
    color: #172033;
    font-size: 16px;
    font-weight: 700;
}

.finder-stat.pending .finder-stat-value { color: #b45309; }
.finder-stat.paid .finder-stat-value { color: #15803d; }

/* =========================================================
   STAFF BOX
========================================================= */

.teacher-box {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 13px;
    background: #f8fbff;
    border: 1px solid #e7effa;
    border-radius: 9px;
    flex-wrap: wrap;
}

.teacher-image,
.teacher-placeholder {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    flex-shrink: 0;
}

.teacher-image {
    object-fit: cover;
    border: 2px solid #dcecff;
}

.teacher-placeholder {
    background: #eaf3ff;
    color: #147cf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    font-weight: 700;
}

.teacher-name {
    margin-bottom: 3px;
    font-size: 16px;
    font-weight: 700;
    color: #172033;
}

.teacher-id {
    font-size: 12px;
    color: #7b8496;
}

.teacher-contact {
    margin-left: auto;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.contact-item {
    font-size: 12px;
    color: #64748b;
}

.contact-item i {
    color: #147cf5;
    margin-right: 5px;
}

.salary-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}

.salary-item {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #e8edf4;
}

.salary-item.net {
    background: #f0fdf4;
    border-color: #ccefd7;
}

.salary-label {
    font-size: 11px;
    color: #7d8798;
    font-weight: 600;
    margin-bottom: 5px;
}

.salary-value {
    font-size: 15px;
    font-weight: 700;
    color: #172033;
}

.salary-item.net .salary-value {
    color: #15803d;
    font-size: 17px;
}

.payment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.info-item {
    padding: 11px 13px;
    border: 1px solid #e9edf3;
    border-radius: 8px;
}

.info-label {
    display: block;
    margin-bottom: 4px;
    color: #8992a3;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.info-value {
    color: #273247;
    font-size: 13px;
    font-weight: 600;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.status-paid {
    background: #dcfce7;
    color: #15803d;
}

.status-pending {
    background: #fef3c7;
    color: #b45309;
}

.remarks-box {
    padding: 12px 14px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e7ebf1;
    color: #526074;
    font-size: 13px;
    line-height: 1.5;
}

.salary-footer {
    padding: 12px 20px;
    background: #fafbfd;
    border-top: 1px solid #edf0f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.salary-footer small {
    color: #8a94a6;
    font-size: 11px;
}

/* =========================================================
   PROFESSIONAL RECEIPT
========================================================= */

.receipt-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15, 23, 42, .72);
    padding: 25px;
    overflow-y: auto;
}

.receipt-controls {
    width: 820px;
    max-width: 100%;
    margin: 0 auto 12px;
    display: flex;
    justify-content: flex-end;
    gap: 7px;
    flex-wrap: wrap;
}

.receipt-controls button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    padding: 8px 13px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
}

.receipt-print-btn {
    background: #147cf5;
    color: #fff;
}

.receipt-share-btn {
    background: #6c63ff;
    color: #fff;
}

.receipt-close-btn {
    background: #fff;
    color: #475569;
}

.receipt-paper {
    width: 820px;
    max-width: 100%;
    min-height: 1120px;
    margin: 0 auto;
    padding: 38px 42px;
    background: #fff;
    color: #172033;
    box-shadow: 0 15px 50px rgba(0,0,0,.25);
}

.receipt-main-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 18px;
    border-bottom: 2px solid #147cf5;
    flex-wrap: wrap;
    gap: 15px;
}

.school-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.school-logo {
    width: 54px;
    height: 54px;
    object-fit: contain;
}

.school-name h1 {
    margin: 0;
    font-size: 21px;
    font-weight: 800;
    color: #172033;
}

.school-name p {
    margin: 3px 0 0;
    font-size: 11px;
    color: #64748b;
}

.receipt-heading {
    text-align: right;
}

.receipt-heading h2 {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #147cf5;
    letter-spacing: .7px;
}

.receipt-heading span {
    display: block;
    margin-top: 5px;
    font-size: 11px;
    color: #64748b;
}

.receipt-meta-box {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    margin: 20px 0;
    background: #dfe5ec;
    border: 1px solid #dfe5ec;
}

.receipt-meta-item {
    padding: 10px 12px;
    background: #f8fafc;
}

.receipt-meta-label {
    display: block;
    margin-bottom: 4px;
    font-size: 9px;
    font-weight: 700;
    color: #7b8496;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.receipt-meta-value {
    font-size: 12px;
    font-weight: 700;
    color: #172033;
}

.receipt-block {
    margin-top: 19px;
}

.receipt-block-title {
    margin-bottom: 8px;
    padding: 7px 10px;
    background: #f1f6fc;
    border-left: 3px solid #147cf5;
    font-size: 11px;
    font-weight: 800;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.receipt-info-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.receipt-info-table td {
    border: 1px solid #e1e6ec;
    padding: 8px 10px;
}

.receipt-info-table td:first-child,
.receipt-info-table td:nth-child(3) {
    width: 18%;
    background: #fafbfd;
    font-weight: 700;
    color: #64748b;
}

.receipt-info-table td:nth-child(2),
.receipt-info-table td:nth-child(4) {
    width: 32%;
    color: #172033;
    font-weight: 600;
}

.receipt-salary-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.receipt-salary-table th {
    padding: 9px 10px;
    background: #172033;
    color: #fff;
    text-align: left;
    font-size: 10px;
    text-transform: uppercase;
}

.receipt-salary-table th:last-child,
.receipt-salary-table td:last-child {
    text-align: right;
}

.receipt-salary-table td {
    padding: 9px 10px;
    border: 1px solid #dfe4ea;
}

.receipt-salary-table .deduction td {
    color: #b45309;
}

.receipt-salary-table .net-row td {
    background: #edf9f1;
    color: #15803d;
    font-weight: 800;
    font-size: 13px;
    border-top: 2px solid #16a34a;
}

.net-pay-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding: 13px 15px;
    border: 1px solid #cce8d4;
    background: #f0fdf4;
}

.net-pay-label {
    font-size: 10px;
    font-weight: 800;
    color: #166534;
    text-transform: uppercase;
}

.net-pay-amount {
    font-size: 19px;
    font-weight: 800;
    color: #15803d;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.payment-paid {
    color: #15803d;
    background: #dcfce7;
}

.payment-pending {
    color: #b45309;
    background: #fef3c7;
}

.receipt-declaration {
    margin-top: 20px;
    padding: 10px 12px;
    background: #fafbfd;
    border: 1px solid #e4e8ee;
    font-size: 10px;
    line-height: 1.6;
    color: #64748b;
}

.receipt-signatures {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 100px;
    margin-top: 70px;
}

.signature-box {
    text-align: center;
}

.signature-line {
    border-top: 1px solid #475569;
    margin-bottom: 6px;
}

.signature-box span {
    font-size: 10px;
    font-weight: 600;
    color: #475569;
}

.receipt-paper-footer {
    margin-top: 35px;
    padding-top: 10px;
    border-top: 1px solid #dfe4ea;
    text-align: center;
    font-size: 9px;
    color: #8a94a6;
}

@media (max-width: 1100px) {
    .salary-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .teacher-contact {
        display: none;
    }
    .finder-grid {
        grid-template-columns: 1fr 1fr;
    }
    .finder-grid .finder-field:last-child {
        grid-column: 1 / -1;
    }
}

@media (max-width: 700px) {
    .salary-page {
        padding: 12px;
    }
    .salary-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .salary-header-actions {
        width: 100%;
    }
    .salary-grid,
    .payment-grid,
    .finder-grid {
        grid-template-columns: 1fr;
    }
    .finder-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    .salary-content,
    .finder-body {
        padding: 14px;
    }
    .receipt-overlay {
        padding: 8px;
    }
    .receipt-paper {
        padding: 20px;
    }
    .receipt-main-header {
        flex-direction: column;
    }
    .receipt-heading {
        text-align: left;
    }
    .receipt-meta-box {
        grid-template-columns: 1fr;
    }
    .receipt-signatures {
        gap: 30px;
    }
}

@media print {
    body * {
        visibility: hidden !important;
    }
    #salaryReceipt,
    #salaryReceipt * {
        visibility: visible !important;
    }
    #salaryReceipt {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff;
        padding: 0;
        margin: 0;
    }
    .receipt-controls {
        display: none !important;
    }
    .receipt-paper {
        width: 100%;
        min-height: auto;
        max-width: none;
        margin: 0;
        padding: 10mm;
        box-shadow: none;
    }
    @page {
        size: A4 portrait;
        margin: 0;
    }
}
</style>

<div class="salary-page">

    {{-- HEADER --}}
    <div class="salary-header">
        <div class="salary-header-left">
            <h2>Salary Details</h2>
            <p>View salary and payment information department wise</p>
        </div>

        <div class="salary-header-actions">
            <a href="{{ route('admin.salary.index') }}" class="salary-btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.salary.edit', ['salary' => $teacherSalary->id]) }}"
               class="salary-btn btn-edit">
                <i class="fas fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    {{-- FIND A SALARY RECORD --}}
    <div class="salary-card">
        <div class="salary-card-header">
            <div class="salary-card-title">
                <span><i class="fas fa-search"></i></span>
                <h3>Find Salary Record</h3>
            </div>
        </div>

        <div class="finder-body">
            <div class="finder-grid">

                <div class="finder-field">
                    <label for="finderDepartment">Department</label>
                    <select id="finderDepartment" class="finder-control">
                        <option value="">All Departments</option>
                        @foreach($staffTypeLabels as $key => $label)
                            <option value="{{ $key }}" {{ $typeKey === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="finder-field">
                    <label for="finderSearch">Search</label>
                    <div class="finder-search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="search"
                               id="finderSearch"
                               class="finder-control"
                               placeholder="Name, staff ID, month or status"
                               autocomplete="off">
                    </div>
                </div>

                <div class="finder-field">
                    <label for="finderRecord">Salary Record</label>
                    <select id="finderRecord" class="finder-control"></select>
                </div>

            </div>

            <div class="finder-hint" id="finderHint"></div>

            <div class="finder-stats">
                <div class="finder-stat">
                    <span class="finder-stat-label">Records</span>
                    <span class="finder-stat-value" id="statRecords">0</span>
                </div>
                <div class="finder-stat pending">
                    <span class="finder-stat-label">Pending</span>
                    <span class="finder-stat-value" id="statPending">0</span>
                </div>
                <div class="finder-stat paid">
                    <span class="finder-stat-label">Paid</span>
                    <span class="finder-stat-value" id="statPaid">0</span>
                </div>
                <div class="finder-stat">
                    <span class="finder-stat-label">Net Salary Total</span>
                    <span class="finder-stat-value" id="statNet">₹0.00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="salary-card">
        <div class="salary-card-header">
            <div class="salary-card-title">
                <span><i class="fas fa-money-check-alt"></i></span>
                <h3>{{ $typeLabel }} Salary Information</h3>
            </div>

            <span class="type-chip">{{ $typeLabel }}</span>
        </div>

        <div class="salary-content">

            {{-- STAFF --}}
            <div class="salary-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    {{ $typeLabel }} Information
                </div>

                <div class="teacher-box">
                    @if($personImage)
                        <img src="{{ $personImage }}" class="teacher-image" alt="{{ $typeLabel }}">
                    @else
                        <div class="teacher-placeholder">
                            {{ strtoupper(substr($personName, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <div class="teacher-name">{{ $personName }}</div>
                        <div class="teacher-id">
                            {{ $typeLabel }} ID: {{ $personCode ?: 'N/A' }}
                            @if(!$isTeacher && $designation)
                                &nbsp;•&nbsp; {{ $designation }}
                            @endif
                        </div>
                    </div>

                    <div class="teacher-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            {{ $personEmail ?: 'N/A' }}
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            {{ $personPhone ?: 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- SALARY --}}
            <div class="salary-section">
                <div class="section-title">
                    <i class="fas fa-wallet"></i>
                    Salary Information
                </div>

                <div class="salary-grid">
                    <div class="salary-item">
                        <div class="salary-label">Salary Month</div>
                        <div class="salary-value">
                            {{ $formatMonth($teacherSalary->salary_month) }}
                        </div>
                    </div>

                    <div class="salary-item">
                        <div class="salary-label">Basic Salary</div>
                        <div class="salary-value">
                            ₹{{ number_format((float) $teacherSalary->basic_salary, 2) }}
                        </div>
                    </div>

                    <div class="salary-item">
                        <div class="salary-label">Allowances</div>
                        <div class="salary-value">
                            ₹{{ number_format((float) $teacherSalary->allowances, 2) }}
                        </div>
                    </div>

                    <div class="salary-item">
                        <div class="salary-label">Deductions</div>
                        <div class="salary-value">
                            ₹{{ number_format((float) $teacherSalary->deductions, 2) }}
                        </div>
                    </div>

                    <div class="salary-item net">
                        <div class="salary-label">Net Salary</div>
                        <div class="salary-value">
                            ₹{{ number_format((float) $teacherSalary->net_salary, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- PAYMENT --}}
            <div class="salary-section">
                <div class="section-title">
                    <i class="fas fa-credit-card"></i>
                    Payment Information
                </div>

                <div class="payment-grid">
                    <div class="info-item">
                        <span class="info-label">Payment Status</span>
                        <span class="status-badge {{ $teacherSalary->payment_status === 'Paid' ? 'status-paid' : 'status-pending' }}">
                            <i class="fas {{ $teacherSalary->payment_status === 'Paid' ? 'fa-check-circle' : 'fa-clock' }}"
                               style="margin-right:5px;"></i>
                            {{ $teacherSalary->payment_status }}
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Payment Date</span>
                        <span class="info-value">
                            @if($teacherSalary->payment_date)
                                {{ \Carbon\Carbon::parse($teacherSalary->payment_date)->format('d M Y') }}
                            @else
                                Not Paid
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Salary ID</span>
                        <span class="info-value">
                            #SAL-{{ str_pad($teacherSalary->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- REMARKS --}}
            @if($teacherSalary->remarks)
                <div class="salary-section">
                    <div class="section-title">
                        <i class="fas fa-comment-alt"></i>
                        Remarks
                    </div>
                    <div class="remarks-box">
                        {{ $teacherSalary->remarks }}
                    </div>
                </div>
            @endif
        </div>

        <div class="salary-footer">
            <small>
                Salary record created:
                {{ $teacherSalary->created_at ? $teacherSalary->created_at->format('d M Y, h:i A') : 'N/A' }}
            </small>

            <button type="button" class="salary-btn btn-receipt" onclick="generateReceipt()">
                <i class="fas fa-receipt"></i>
                Generate Receipt
            </button>
        </div>
    </div>
</div>

{{-- RECEIPT OVERLAY --}}
<div id="salaryReceipt" class="receipt-overlay">
    <div class="receipt-controls">
        <button type="button" class="receipt-print-btn" onclick="printReceipt()">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <button type="button" class="receipt-share-btn" onclick="shareReceipt()">
            <i class="fas fa-share-alt"></i> Share
        </button>
        <button type="button" class="receipt-close-btn" onclick="closeReceipt()">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <div class="receipt-paper">
        <div class="receipt-main-header">
            <div class="school-brand">
                @if(file_exists(public_path('images/gurukullogo.png')))
                    <img src="{{ asset('images/gurukullogo.png') }}" class="school-logo" alt="Gurukul Vidyalaya">
                @endif
                <div class="school-name">
                    <h1>GURUKUL VIDYALAYA</h1>
                    <p>School Management System</p>
                </div>
            </div>
            <div class="receipt-heading">
                <h2>SALARY RECEIPT</h2>
                <span>Official Employee Payment Record</span>
            </div>
        </div>

        <div class="receipt-meta-box">
            <div class="receipt-meta-item">
                <span class="receipt-meta-label">Receipt Number</span>
                <span class="receipt-meta-value">SAL-{{ str_pad($teacherSalary->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="receipt-meta-item">
                <span class="receipt-meta-label">Salary Period</span>
                <span class="receipt-meta-value">{{ $formatMonth($teacherSalary->salary_month) }}</span>
            </div>
            <div class="receipt-meta-item">
                <span class="receipt-meta-label">Issue Date</span>
                <span class="receipt-meta-value">{{ now()->format('d M Y') }}</span>
            </div>
        </div>

        <div class="receipt-block">
            <div class="receipt-block-title">Employee Information</div>
            <table class="receipt-info-table">
                <tr>
                    <td>Employee Name</td>
                    <td>{{ $personName }}</td>
                    <td>{{ $typeLabel }} ID</td>
                    <td>{{ $personCode ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>{{ $typeLabel }}</td>
                    <td>Designation</td>
                    <td>{{ $designation ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $personEmail ?: 'N/A' }}</td>
                    <td>Phone</td>
                    <td>{{ $personPhone ?: 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="receipt-block">
            <div class="receipt-block-title">Salary Breakdown</div>
            <table class="receipt-salary-table">
                <thead>
                    <tr>
                        <th>Salary Component</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td>₹{{ number_format((float) $teacherSalary->basic_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Allowances</td>
                        <td>₹{{ number_format((float) $teacherSalary->allowances, 2) }}</td>
                    </tr>
                    <tr class="deduction">
                        <td>Deductions</td>
                        <td>- ₹{{ number_format((float) $teacherSalary->deductions, 2) }}</td>
                    </tr>
                    <tr class="net-row">
                        <td>NET SALARY PAYABLE</td>
                        <td>₹{{ number_format((float) $teacherSalary->net_salary, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="net-pay-box">
                <span class="net-pay-label">Net Amount Paid</span>
                <span class="net-pay-amount">₹{{ number_format((float) $teacherSalary->net_salary, 2) }}</span>
            </div>
        </div>

        <div class="receipt-block">
            <div class="receipt-block-title">Payment Information</div>
            <table class="receipt-info-table">
                <tr>
                    <td>Payment Status</td>
                    <td>
                        @if($teacherSalary->payment_status === 'Paid')
                            <span class="payment-status payment-paid">
                                <i class="fas fa-check-circle"></i>&nbsp; Paid
                            </span>
                        @else
                            <span class="payment-status payment-pending">
                                <i class="fas fa-clock"></i>&nbsp; Pending
                            </span>
                        @endif
                    </td>
                    <td>Payment Date</td>
                    <td>
                        @if($teacherSalary->payment_date)
                            {{ \Carbon\Carbon::parse($teacherSalary->payment_date)->format('d M Y') }}
                        @else
                            Not Paid
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        @if($teacherSalary->remarks)
            <div class="receipt-block">
                <div class="receipt-block-title">Remarks</div>
                <div style="padding:10px 12px;border:1px solid #e1e6ec;font-size:10px;color:#475569;line-height:1.5;">
                    {{ $teacherSalary->remarks }}
                </div>
            </div>
        @endif

        <div class="receipt-declaration">
            <strong>Declaration:</strong>
            This salary receipt is issued as an official record of the salary processed
            for the above-mentioned employee for the specified salary period.
            Please retain this receipt for future reference.
        </div>

        <div class="receipt-signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <span>Employee Signature</span>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <span>Authorized Signatory</span>
            </div>
        </div>

        <div class="receipt-paper-footer">
            This is a computer-generated salary receipt and does not require a physical stamp
            unless required by the institution.
            <br>
            Gurukul Vidyalaya • School Management System
        </div>
    </div>
</div>

<script>
/* =========================================================
   RECEIPT
========================================================= */
function generateReceipt() {
    document.getElementById('salaryReceipt').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function printReceipt() {
    window.print();
}

function closeReceipt() {
    document.getElementById('salaryReceipt').style.display = 'none';
    document.body.style.overflow = '';
}

function shareReceipt() {
    const receiptNumber = 'SAL-{{ str_pad($teacherSalary->id, 5, '0', STR_PAD_LEFT) }}';
    const staffName = @json($personName);
    const department = @json($typeLabel);
    const amount = '₹{{ number_format((float) $teacherSalary->net_salary, 2) }}';
    const text = 'Salary Receipt ' + receiptNumber +
        '\n' + department + ': ' + staffName +
        '\nNet Salary: ' + amount;

    if (navigator.share) {
        navigator.share({ title: 'Salary Receipt', text: text });
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
        alert('Salary receipt details copied to clipboard.');
    } else {
        alert(text);
    }
}

window.addEventListener('afterprint', function () {
    document.getElementById('salaryReceipt').style.display = 'none';
    document.body.style.overflow = '';
});

/* =========================================================
   FINDER: department dropdown + search + record dropdown
========================================================= */
(function () {
    const records = @json($salaryOptions);
    const currentId = {{ (int) $teacherSalary->id }};

    const deptSelect = document.getElementById('finderDepartment');
    const searchInput = document.getElementById('finderSearch');
    const recordSelect = document.getElementById('finderRecord');
    const hint = document.getElementById('finderHint');

    const statRecords = document.getElementById('statRecords');
    const statPending = document.getElementById('statPending');
    const statPaid = document.getElementById('statPaid');
    const statNet = document.getElementById('statNet');

    const typeNames = @json($staffTypeLabels);

    function money(value) {
        return '₹' + Number(value || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function sameStatus(value, status) {
        return String(value || '').toLowerCase() === status;
    }

    // Show record counts inside the department dropdown
    function labelDepartments() {
        Array.from(deptSelect.options).forEach(function (option) {
            const count = option.value === ''
                ? records.length
                : records.filter(r => r.type === option.value).length;

            const base = option.value === ''
                ? 'All Departments'
                : (typeNames[option.value] || option.value);

            option.textContent = base + ' (' + count + ')';
        });
    }

    function departmentRecords() {
        const dept = deptSelect.value;
        return dept ? records.filter(r => r.type === dept) : records;
    }

    function updateStats(list) {
        statRecords.textContent = list.length;
        statPending.textContent = list.filter(r => sameStatus(r.status, 'pending')).length;
        statPaid.textContent = list.filter(r => sameStatus(r.status, 'paid')).length;
        statNet.textContent = money(list.reduce((sum, r) => sum + Number(r.net || 0), 0));
    }

    function matchesSearch(record, term) {
        if (!term) {
            return true;
        }

        const haystack = [
            record.name,
            record.code,
            record.month,
            record.month_label,
            record.status,
            typeNames[record.type] || record.type,
            '#SAL-' + String(record.id).padStart(5, '0')
        ].join(' ').toLowerCase();

        return haystack.indexOf(term) !== -1;
    }

    function render() {
        const deptList = departmentRecords();
        const term = searchInput.value.trim().toLowerCase();
        const filtered = deptList.filter(r => matchesSearch(r, term));

        updateStats(deptList);

        recordSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = filtered.length
            ? 'Select a salary record (' + filtered.length + ' found)'
            : 'No salary records found';
        recordSelect.appendChild(placeholder);

        filtered.forEach(function (record) {
            const option = document.createElement('option');
            option.value = record.url;

            let text = record.name;
            if (record.code) {
                text += ' (' + record.code + ')';
            }
            text += ' — ' + record.month_label + ' — ' + record.status;

            option.textContent = text;

            if (record.id === currentId) {
                option.selected = true;
            }

            recordSelect.appendChild(option);
        });

        const dept = deptSelect.value ? (typeNames[deptSelect.value] || deptSelect.value) : 'all departments';
        hint.textContent = 'Showing ' + filtered.length + ' of ' + deptList.length +
            ' salary record(s) for ' + dept + '. Pick one to open it.';
    }

    recordSelect.addEventListener('change', function () {
        if (recordSelect.value) {
            window.location.href = recordSelect.value;
        }
    });

    deptSelect.addEventListener('change', render);
    searchInput.addEventListener('input', render);

    // Press Enter in the search box to open the first match
    searchInput.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();

        const first = Array.from(recordSelect.options).find(o => o.value !== '');
        if (first) {
            window.location.href = first.value;
        }
    });

    labelDepartments();
    render();
})();
</script>

@endsection