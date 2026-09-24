```blade
@extends('layouts.app')

@section('title', 'Teacher Salary')

@section('content')

<style>

/* =========================================================
   TEACHER SALARY PAGE
========================================================= */

.salary-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
}

/* =========================================================
   WELCOME CARD
========================================================= */

.salary-welcome-card {
    position: relative;
    overflow: hidden;
    min-height: 150px;
    padding: 30px 34px;
    margin-bottom: 24px;
    border-radius: 20px;
    background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
    color: #fff;
    box-shadow: 0 12px 30px rgba(23, 105, 209, .16);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.salary-welcome-card::before,
.salary-welcome-card::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.salary-welcome-card::before {
    width: 220px;
    height: 220px;
    right: -60px;
    top: -125px;
    background: rgba(255, 255, 255, .09);
}

.salary-welcome-card::after {
    width: 140px;
    height: 140px;
    right: 125px;
    bottom: -100px;
    background: rgba(255, 255, 255, .10);
}

.salary-welcome-content {
    position: relative;
    z-index: 2;
}

.salary-welcome-content h2 {
    margin: 0 0 8px;
    font-size: 29px;
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -.4px;
}

.salary-welcome-content p {
    margin: 0;
    color: rgba(255, 255, 255, .92);
    font-size: 14px;
    line-height: 1.6;
}

.salary-create-button {
    position: relative;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    border: 1px solid rgba(255, 255, 255, .8);
    border-radius: 10px;
    background: #fff;
    color: #1769d1 !important;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none !important;
    box-shadow: 0 6px 18px rgba(0, 0, 0, .10);
    transition: all .22s ease;
}

.salary-create-button:hover {
    transform: translateY(-2px);
    color: #1258b5 !important;
    box-shadow: 0 10px 24px rgba(0, 0, 0, .16);
}

.salary-create-plus {
    font-size: 18px;
    line-height: 1;
    font-weight: 500;
}

/* =========================================================
   SUCCESS MESSAGE
========================================================= */

.salary-success {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    background: #f0fdf4;
    color: #15803d;
    font-size: 13px;
    font-weight: 700;
}

/* =========================================================
   STATISTICS
========================================================= */

.salary-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.salary-stat-card {
    position: relative;
    overflow: hidden;
    min-height: 145px;
    padding: 24px 25px;
    border-radius: 18px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .10);
    transition: transform .25s ease, box-shadow .25s ease;
}

.salary-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 32px rgba(15, 23, 42, .15);
}

.salary-stat-card::before,
.salary-stat-card::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.salary-stat-card::before {
    width: 160px;
    height: 160px;
    right: -55px;
    top: -70px;
    background: rgba(255, 255, 255, .10);
}

.salary-stat-card::after {
    width: 85px;
    height: 85px;
    right: -20px;
    bottom: -42px;
    background: rgba(255, 255, 255, .08);
}

.salary-stat-card.blue {
    background: linear-gradient(135deg, #1769d1, #237de0);
}

.salary-stat-card.orange {
    background: linear-gradient(135deg, #ed9208, #f7aa25);
}

.salary-stat-card.green {
    background: linear-gradient(135deg, #16a34a, #22c55e);
}

.salary-stat-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.salary-stat-number {
    margin-bottom: 8px;
    font-size: 32px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.5px;
}

.salary-stat-title {
    color: rgba(255, 255, 255, .94);
    font-size: 13px;
    font-weight: 700;
}

.salary-stat-icon {
    position: relative;
    z-index: 2;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: rgba(255, 255, 255, .14);
    color: rgba(255, 255, 255, .95);
    font-size: 23px;
    font-weight: 800;
}

/* =========================================================
   MAIN CARD
========================================================= */

.salary-card {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 18px;
    box-shadow: 0 6px 22px rgba(15, 23, 42, .055);
}

/* =========================================================
   CARD HEADER
========================================================= */

.salary-card-header {
    min-height: 78px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.salary-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.salary-card-title-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #eef6ff;
    color: #1769d1;
    font-size: 16px;
    font-weight: 800;
}

.salary-card-title h3 {
    margin: 0;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.salary-card-subtitle {
    margin: 5px 0 0 44px;
    color: #7b8798;
    font-size: 12px;
}

/* =========================================================
   SEARCH
========================================================= */

.salary-search-box {
    width: 330px;
    height: 40px;
    display: flex;
    align-items: center;
    border: 1px solid #e1e8f0;
    background: #f8fafc;
    border-radius: 10px;
    transition: all .2s ease;
}

.salary-search-box:focus-within {
    border-color: #8bbcf2;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
}

.salary-search-box-icon {
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 17px;
}

.salary-search-box input {
    width: 100%;
    height: 100%;
    padding: 0 12px 0 0;
    border: none;
    outline: none;
    background: transparent;
    color: #334155;
    font-size: 12px;
}

.salary-search-box input::placeholder {
    color: #94a3b8;
}

/* =========================================================
   TABLE WRAPPER
========================================================= */

.salary-table-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
}

/* =========================================================
   TABLE
========================================================= */

.salary-table {
    width: 100%;
    min-width: 1180px;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed;
}

.salary-table th:nth-child(1),
.salary-table td:nth-child(1) {
    width: 58px;
    text-align: center;
}

.salary-table th:nth-child(2),
.salary-table td:nth-child(2) {
    width: 220px;
}

.salary-table th:nth-child(3),
.salary-table td:nth-child(3) {
    width: 135px;
}

.salary-table th:nth-child(4),
.salary-table td:nth-child(4),
.salary-table th:nth-child(5),
.salary-table td:nth-child(5),
.salary-table th:nth-child(6),
.salary-table td:nth-child(6) {
    width: 125px;
}

.salary-table th:nth-child(7),
.salary-table td:nth-child(7) {
    width: 140px;
}

.salary-table th:nth-child(8),
.salary-table td:nth-child(8) {
    width: 110px;
}

.salary-table th:nth-child(9),
.salary-table td:nth-child(9) {
    width: 130px;
}

.salary-table th:nth-child(10),
.salary-table td:nth-child(10) {
    width: 145px;
}

/* =========================================================
   TABLE HEADER
========================================================= */

.salary-table thead th {
    padding: 14px;
    background: #f8fafc;
    border-top: 1px solid #edf1f6;
    border-bottom: 1px solid #e5eaf1;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .55px;
    white-space: nowrap;
    text-align: left;
}

/* =========================================================
   TABLE BODY
========================================================= */

.salary-table tbody td {
    padding: 15px 14px;
    background: #fff;
    border-bottom: 1px solid #edf1f6;
    color: #334155;
    font-size: 12px;
    vertical-align: middle;
    white-space: nowrap;
}

.salary-table tbody tr {
    transition: background .2s ease;
}

.salary-table tbody tr:hover td {
    background: #f8fbff;
}

.salary-table tbody tr:last-child td {
    border-bottom: none;
}

/* =========================================================
   SERIAL NUMBER
========================================================= */

.salary-number {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f1f6ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================================
   TEACHER
========================================================= */

.salary-teacher-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.salary-teacher-avatar {
    width: 40px;
    height: 40px;
    min-width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: linear-gradient(135deg, #eaf3ff, #eef0ff);
    color: #1769d1;
    font-size: 13px;
    font-weight: 800;
}

.salary-teacher-info {
    min-width: 0;
}

.salary-teacher-name {
    overflow: hidden;
    color: #172033;
    font-size: 13px;
    font-weight: 800;
    text-overflow: ellipsis;
}

.salary-teacher-id {
    margin-top: 4px;
    color: #1769d1;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .2px;
}

/* =========================================================
   MONTH
========================================================= */

.salary-month-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid #e7edf4;
    border-radius: 8px;
    background: #f5f8fc;
    color: #475467;
    font-size: 11px;
    font-weight: 700;
}

.salary-month-badge i {
    color: #1769d1;
    font-size: 11px;
}

/* =========================================================
   MONEY
========================================================= */

.salary-money-cell {
    color: #344054;
    font-size: 12px;
    font-weight: 700;
}

.salary-deduction {
    color: #dc2626;
    font-weight: 800;
}

/* =========================================================
   NET SALARY
========================================================= */

.salary-net-box {
    display: inline-flex;
    align-items: center;
    padding: 7px 10px;
    border: 1px solid #d1fadf;
    border-radius: 8px;
    background: #f0fdf4;
    color: #15803d;
    font-size: 12px;
    font-weight: 800;
}

/* =========================================================
   STATUS
========================================================= */

.salary-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.salary-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.salary-status.paid {
    background: #ecfdf3;
    color: #15803d;
    border: 1px solid #ccefd9;
}

.salary-status.paid .salary-status-dot {
    background: #16a34a;
}

.salary-status.pending {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
}

.salary-status.pending .salary-status-dot {
    background: #f97316;
}

/* =========================================================
   PAYMENT DATE
========================================================= */

.salary-payment-date {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #475467;
    font-size: 11px;
    font-weight: 600;
}

.salary-payment-date i {
    color: #94a3b8;
}

/* =========================================================
   ACTIONS
========================================================= */

.salary-actions {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 7px !important;
    white-space: nowrap;
}

.salary-actions form {
    display: inline-flex !important;
    margin: 0 !important;
    padding: 0 !important;
}

.salary-actions .salary-action {
    width: 34px !important;
    height: 34px !important;
    min-width: 34px !important;
    min-height: 34px !important;
    padding: 0 !important;
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid transparent !important;
    border-radius: 9px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all .2s ease;
}

.salary-actions .salary-action:hover {
    transform: translateY(-2px);
}

.salary-actions .salary-view-action {
    background: #eef6ff !important;
    color: #1477df !important;
    border-color: #d9eaff !important;
}

.salary-actions .salary-view-action:hover {
    background: #1477df !important;
    color: #fff !important;
    border-color: #1477df !important;
}

.salary-actions .salary-edit-action {
    background: #f2efff !important;
    color: #6c63ff !important;
    border-color: #e3defe !important;
}

.salary-actions .salary-edit-action:hover {
    background: #6c63ff !important;
    color: #fff !important;
    border-color: #6c63ff !important;
}

.salary-actions .salary-delete-action {
    background: #fff1f2 !important;
    color: #e11d48 !important;
    border-color: #ffe0e5 !important;
}

.salary-actions .salary-delete-action:hover {
    background: #e11d48 !important;
    color: #fff !important;
    border-color: #e11d48 !important;
}

.salary-actions .salary-action svg {
    width: 15px !important;
    height: 15px !important;
    display: block !important;
    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 2 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.salary-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.salary-empty-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    background: #eef5ff;
    color: #1769d1;
    font-size: 25px;
    font-weight: 800;
}

.salary-empty-state h4 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 16px;
    font-weight: 800;
}

.salary-empty-state p {
    margin: 0 0 18px;
    color: #94a3b8;
    font-size: 12px;
}

.salary-empty-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 15px;
    border-radius: 8px;
    background: #1769d1;
    color: #fff !important;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all .2s ease;
}

.salary-empty-button:hover {
    background: #1258b5;
    color: #fff !important;
    transform: translateY(-2px);
}

/* =========================================================
   CUSTOM PAGINATION
========================================================= */

.salary-pagination {
    padding: 16px 22px;
    border-top: 1px solid #edf1f6;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.salary-pagination-info {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.salary-pagination-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
}

.salary-page-button {
    width: 34px;
    height: 34px;
    min-width: 34px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
    box-sizing: border-box;
    transition: all .2s ease;
}

a.salary-page-button:hover {
    background: #1769d1;
    border-color: #1769d1;
    color: #ffffff;
    text-decoration: none;
    transform: translateY(-1px);
}

.salary-page-button.active {
    background: #1769d1;
    border-color: #1769d1;
    color: #ffffff;
    cursor: default;
}

.salary-page-button.disabled {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
}

.salary-page-arrow {
    font-size: 18px;
    font-weight: 500;
    line-height: 1;
    margin-top: -1px;
}

@media (max-width: 650px) {
    .salary-pagination {
        padding: 14px 15px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .salary-pagination-info {
        font-size: 11px;
    }

    .salary-pagination-buttons {
        gap: 4px;
    }

    .salary-page-button {
        width: 32px;
        height: 32px;
        min-width: 32px;
    }

    .salary-page-arrow {
        font-size: 17px;
    }
}

</style>


<div class="salary-container">

    {{-- =====================================================
         WELCOME CARD
    ====================================================== --}}

    <div class="salary-welcome-card">

        <div class="salary-welcome-content">

            <h2>
                Teacher Salary 💰
            </h2>

            <p>
                Manage teacher salaries, payments and salary records.
            </p>

        </div>

        <a
            href="{{ route('admin.teachers.salary.create') }}"
            class="salary-create-button"
        >
            <span class="salary-create-plus">+</span>
            Generate Salary
        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="salary-success">

            <span>✓</span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="salary-stats-grid">

        {{-- TOTAL --}}
        <div class="salary-stat-card blue">

            <div class="salary-stat-top">

                <div>

                    <div class="salary-stat-number">
                        {{ number_format($totalSalaries ?? $salaries->total()) }}
                    </div>

                    <div class="salary-stat-title">
                        Total Salary Records
                    </div>

                </div>

                <span class="salary-stat-icon">
                    ₹
                </span>

            </div>

        </div>


        {{-- PENDING --}}
        <div class="salary-stat-card orange">

            <div class="salary-stat-top">

                <div>

                    <div class="salary-stat-number">
                        {{ number_format($pendingSalaries ?? 0) }}
                    </div>

                    <div class="salary-stat-title">
                        Pending Payments
                    </div>

                </div>

                <span class="salary-stat-icon">
                    ₹
                </span>

            </div>

        </div>


        {{-- PAID --}}
        <div class="salary-stat-card green">

            <div class="salary-stat-top">

                <div>

                    <div class="salary-stat-number">
                        {{ number_format($paidSalaries ?? 0) }}
                    </div>

                    <div class="salary-stat-title">
                        Paid Salaries
                    </div>

                </div>

                <span class="salary-stat-icon">
                    ✓
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SALARY RECORDS CARD
    ====================================================== --}}

    <div class="salary-card">

        {{-- CARD HEADER --}}

        <div class="salary-card-header">

            <div>

                <div class="salary-card-title">

                    <span class="salary-card-title-icon">
                        ₹
                    </span>

                    <h3>
                        Salary Records
                    </h3>

                </div>

                <div class="salary-card-subtitle">
                    All teacher salary records
                </div>

            </div>


            {{-- SEARCH --}}

            <div class="salary-search-box">

                <span class="salary-search-box-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    id="salarySearch"
                    placeholder="Search teacher, ID, month..."
                    autocomplete="off"
                >

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        <div class="salary-table-wrapper">

            <table
                class="salary-table"
                id="salaryTable"
            >

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Teacher</th>

                        <th>Salary Month</th>

                        <th>Basic Salary</th>

                        <th>Allowances</th>

                        <th>Deductions</th>

                        <th>Net Salary</th>

                        <th>Status</th>

                        <th>Payment Date</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($salaries as $salary)

                        <tr>

                            {{-- NUMBER --}}

                            <td>

                                <span class="salary-number">

                                    {{ $salaries->firstItem() + $loop->index }}

                                </span>

                            </td>


                            {{-- TEACHER --}}

                            <td>

                                @if($salary->teacher)

                                    <div class="salary-teacher-cell">

                                        <div class="salary-teacher-avatar">

                                            {{ strtoupper(
                                                substr(
                                                    $salary->teacher->first_name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <div class="salary-teacher-info">

                                            <div class="salary-teacher-name">

                                                {{ $salary->teacher->first_name }}

                                                {{ $salary->teacher->last_name }}

                                            </div>

                                            <div class="salary-teacher-id">

                                                ID:
                                                {{ $salary->teacher->teacher_id }}

                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <span style="color:#94a3b8;">
                                        Teacher Deleted
                                    </span>

                                @endif

                            </td>


                            {{-- SALARY MONTH --}}

                            <td>

                                @if($salary->salary_month)

                                    <span class="salary-month-badge">

                                        <i class="fas fa-calendar-alt"></i>

                                        {{ \Carbon\Carbon::parse(
                                            $salary->salary_month
                                        )->format('M Y') }}

                                    </span>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- BASIC SALARY --}}

                            <td>

                                <span class="salary-money-cell">

                                    ₹{{ number_format(
                                        (float) $salary->basic_salary,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- ALLOWANCES --}}

                            <td>

                                <span class="salary-money-cell">

                                    ₹{{ number_format(
                                        (float) $salary->allowances,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- DEDUCTIONS --}}

                            <td>

                                <span class="salary-deduction">

                                    ₹{{ number_format(
                                        (float) $salary->deductions,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- NET SALARY --}}

                            <td>

                                <span class="salary-net-box">

                                    ₹{{ number_format(
                                        (float) $salary->net_salary,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($salary->payment_status === 'Paid')

                                    <span class="salary-status paid">

                                        <span class="salary-status-dot"></span>

                                        Paid

                                    </span>

                                @else

                                    <span class="salary-status pending">

                                        <span class="salary-status-dot"></span>

                                        Pending

                                    </span>

                                @endif

                            </td>


                            {{-- PAYMENT DATE --}}

                            <td>

                                @if($salary->payment_date)

                                    <span class="salary-payment-date">

                                        <i class="fas fa-calendar-check"></i>

                                        {{ \Carbon\Carbon::parse(
                                            $salary->payment_date
                                        )->format('d M Y') }}

                                    </span>

                                @else

                                    <span style="color:#94a3b8;">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="salary-actions">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'admin.teachers.salary.show',
                                            $salary->id
                                        ) }}"
                                        class="salary-action salary-view-action"
                                        title="View Salary"
                                        aria-label="View Salary"
                                    >

                                        <svg viewBox="0 0 24 24">

                                            <path d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                            ></circle>

                                        </svg>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.teachers.salary.edit',
                                            $salary->id
                                        ) }}"
                                        class="salary-action salary-edit-action"
                                        title="Edit Salary"
                                        aria-label="Edit Salary"
                                    >

                                        <svg viewBox="0 0 24 24">

                                            <path d="M4 16.5V20h3.5L18.81 8.69l-3.5-3.5L4 16.5Z"></path>

                                            <path d="M14.81 5.19l3.5 3.5"></path>

                                        </svg>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'admin.teachers.salary.destroy',
                                            $salary->id
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this salary record?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="salary-action salary-delete-action"
                                            title="Delete Salary"
                                            aria-label="Delete Salary"
                                        >

                                            <svg viewBox="0 0 24 24">

                                                <path d="M4 7h16"></path>

                                                <path d="M9 7V4h6v3"></path>

                                                <path d="M7 7l1 13h8l1-13"></path>

                                                <path d="M10 11v5"></path>

                                                <path d="M14 11v5"></path>

                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10">

                                <div class="salary-empty-state">

                                    <div class="salary-empty-icon">
                                        ₹
                                    </div>

                                    <h4>
                                        No Salary Records Found
                                    </h4>

                                    <p>
                                        No teacher salary records have been created yet.
                                    </p>

                                    <a
                                        href="{{ route('admin.teachers.salary.create') }}"
                                        class="salary-empty-button"
                                    >
                                        + Generate First Salary
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}

        @if($salaries->hasPages())

            <div class="salary-pagination">

                <div class="salary-pagination-info">
                    Showing
                    {{ $salaries->firstItem() }}
                    to
                    {{ $salaries->lastItem() }}
                    of
                    {{ $salaries->total() }}
                    results
                </div>

                <div class="salary-pagination-buttons">

                    @if($salaries->onFirstPage())
                        <span class="salary-page-button disabled" aria-disabled="true">
                            <span class="salary-page-arrow">‹</span>
                        </span>
                    @else
                        <a
                            href="{{ $salaries->previousPageUrl() }}"
                            class="salary-page-button"
                            aria-label="Previous page"
                        >
                            <span class="salary-page-arrow">‹</span>
                        </a>
                    @endif

                    @for($page = 1; $page <= $salaries->lastPage(); $page++)
                        @if($page == $salaries->currentPage())
                            <span class="salary-page-button active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $salaries->url($page) }}"
                                class="salary-page-button"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if($salaries->hasMorePages())
                        <a
                            href="{{ $salaries->nextPageUrl() }}"
                            class="salary-page-button"
                            aria-label="Next page"
                        >
                            <span class="salary-page-arrow">›</span>
                        </a>
                    @else
                        <span class="salary-page-button disabled" aria-disabled="true">
                            <span class="salary-page-arrow">›</span>
                        </span>
                    @endif

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     SEARCH SCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('salarySearch');
    const table = document.getElementById('salaryTable');

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener('input', function () {

        const searchValue = this.value
            .toLowerCase()
            .trim();

        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(function (row) {

            const rowText = row.textContent
                .toLowerCase();

            row.style.display =
                rowText.includes(searchValue)
                    ? ''
                    : 'none';

        });

    });

});

</script>

@endsection