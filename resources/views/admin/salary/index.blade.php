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

    min-height: 145px;
    padding: 30px 34px;
    margin-bottom: 24px;

    border-radius: 18px;

    background: linear-gradient(
        135deg,
        #1769d1 0%,
        #159cc7 100%
    );

    color: #ffffff;

    box-shadow: 0 10px 28px rgba(23,105,209,.18);

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.salary-welcome-card::before {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    right: -45px;
    top: -105px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.salary-welcome-card::after {
    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    right: 100px;
    bottom: -82px;

    border-radius: 50%;

    background: rgba(255,255,255,.12);
}

.salary-welcome-content {
    position: relative;
    z-index: 2;
}

.salary-welcome-content h2 {
    margin: 0 0 7px;

    font-size: 30px;
    font-weight: 800;
}

.salary-welcome-content p {
    margin: 0;

    font-size: 15px;

    color: rgba(255,255,255,.94);
}

.salary-create-button {
    position: relative;
    z-index: 3;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    padding: 11px 18px;

    border-radius: 10px;

    background: #ffffff;
    color: #1769d1;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    box-shadow: 0 5px 15px rgba(0,0,0,.10);

    transition: .2s ease;
}

.salary-create-button:hover {
    color: #1769d1;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(0,0,0,.15);
}

/* =========================================================
   SUCCESS MESSAGE
========================================================= */

.salary-success {
    margin-bottom: 20px;

    padding: 13px 16px;

    border: 1px solid #bbf7d0;
    border-radius: 10px;

    background: #f0fdf4;
    color: #15803d;

    font-size: 13px;
    font-weight: 600;
}

/* =========================================================
   STATISTICS
========================================================= */

.salary-stats-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0,1fr));

    gap: 20px;

    margin-bottom: 24px;
}

.salary-stat-card {
    position: relative;
    overflow: hidden;

    min-height: 145px;

    padding: 24px 25px;

    border-radius: 17px;

    color: #ffffff;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    box-shadow: 0 8px 22px rgba(15,23,42,.12);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.salary-stat-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 15px 32px rgba(15,23,42,.18);
}

.salary-stat-card::before {
    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    right: -50px;
    top: -65px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.salary-stat-card::after {
    content: "";

    position: absolute;

    width: 80px;
    height: 80px;

    right: -20px;
    bottom: -38px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}

.salary-stat-card.blue {
    background:
        linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
}

.salary-stat-card.orange {
    background:
        linear-gradient(
            135deg,
            #ed9208,
            #f7aa25
        );
}

.salary-stat-card.green {
    background:
        linear-gradient(
            135deg,
            #16a34a,
            #22c55e
        );
}

.salary-stat-top {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.salary-stat-number {
    margin: 0 0 7px;

    font-size: 32px;
    line-height: 1;

    font-weight: 800;
}

.salary-stat-title {
    font-size: 14px;
    font-weight: 600;

    color: rgba(255,255,255,.95);
}

.salary-stat-icon {
    position: relative;
    z-index: 2;

    font-size: 38px;

    color: rgba(255,255,255,.90);
}

/* =========================================================
   MAIN CARD
========================================================= */

.salary-card {
    overflow: hidden;

    background: #ffffff;

    border: 1px solid #e5ebf3;

    border-radius: 16px;

    box-shadow:
        0 5px 20px rgba(15,23,42,.06);
}

/* =========================================================
   CARD HEADER
========================================================= */

.salary-card-header {
    min-height: 75px;

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

    gap: 9px;
}

.salary-card-title-icon {
    color: #1769d1;

    font-size: 19px;
}

.salary-card-title h3 {
    margin: 0;

    color: #172033;

    font-size: 17px;
    font-weight: 700;
}

.salary-card-subtitle {
    margin: 4px 0 0 28px;

    color: #718096;

    font-size: 12px;
}

/* =========================================================
   SEARCH
========================================================= */

.salary-search-box {
    width: 330px;

    display: flex;
    align-items: center;

    border: 1px solid #e3eaf2;

    background: #f8fafc;

    border-radius: 9px;

    overflow: hidden;
}

.salary-search-box-icon {
    padding-left: 13px;

    color: #94a3b8;

    font-size: 15px;
}

.salary-search-box input {
    width: 100%;

    padding: 10px 12px;

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

/* =========================================================
   COLUMN WIDTHS
========================================================= */

.salary-table th:nth-child(1),
.salary-table td:nth-child(1) {
    width: 55px;

    text-align: center;
}

.salary-table th:nth-child(2),
.salary-table td:nth-child(2) {
    width: 215px;
}

.salary-table th:nth-child(3),
.salary-table td:nth-child(3) {
    width: 125px;
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
    width: 135px;
}

.salary-table th:nth-child(8),
.salary-table td:nth-child(8) {
    width: 110px;
}

.salary-table th:nth-child(9),
.salary-table td:nth-child(9) {
    width: 125px;
}

.salary-table th:nth-child(10),
.salary-table td:nth-child(10) {
    width: 145px;
}

/* =========================================================
   TABLE HEADER
========================================================= */

.salary-table thead th {
    padding: 15px 14px;

    background: #f8fafc;

    border-top: 1px solid #edf1f6;
    border-bottom: 1px solid #e5eaf1;

    color: #64748b;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .5px;

    white-space: nowrap;

    text-align: left;
}

/* =========================================================
   TABLE BODY
========================================================= */

.salary-table tbody td {
    padding: 16px 14px;

    background: #ffffff;

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
    width: 28px;
    height: 28px;

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
    width: 39px;
    height: 39px;

    min-width: 39px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #eaf3ff,
            #eef0ff
        );

    color: #1769d1;

    font-size: 14px;

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
    margin-top: 3px;

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

    border-radius: 8px;

    background: #f5f8fc;

    border: 1px solid #e7edf4;

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

    font-weight: 700;
}

/* =========================================================
   NET SALARY
========================================================= */

.salary-net-box {
    display: inline-flex;

    align-items: center;

    padding: 7px 10px;

    border-radius: 8px;

    background: #f0fdf4;

    border: 1px solid #d1fadf;

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

    border-radius: 8px !important;

    border: 1px solid transparent !important;

    cursor: pointer !important;

    text-decoration: none !important;

    transition:
        transform .2s ease,
        background .2s ease,
        color .2s ease,
        border-color .2s ease !important;
}

/* VIEW */

.salary-actions .salary-view-action {
    background: #eef6ff !important;

    color: #1477df !important;

    border-color: #d9eaff !important;
}

.salary-actions .salary-view-action:hover {
    background: #1477df !important;

    color: #ffffff !important;

    border-color: #1477df !important;

    transform: translateY(-2px);
}

/* EDIT */

.salary-actions .salary-edit-action {
    background: #f2efff !important;

    color: #6c63ff !important;

    border-color: #e3defe !important;
}

.salary-actions .salary-edit-action:hover {
    background: #6c63ff !important;

    color: #ffffff !important;

    border-color: #6c63ff !important;

    transform: translateY(-2px);
}

/* DELETE */

.salary-actions .salary-delete-action {
    background: #fff1f2 !important;

    color: #e11d48 !important;

    border-color: #ffe0e5 !important;
}

.salary-actions .salary-delete-action:hover {
    background: #e11d48 !important;

    color: #ffffff !important;

    border-color: #e11d48 !important;

    transform: translateY(-2px);
}

/* =========================================================
   ACTION SVG
========================================================= */

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

    font-size: 26px;

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

    color: #ffffff;

    font-size: 12px;

    font-weight: 700;

    text-decoration: none;

    transition: .2s ease;
}

.salary-empty-button:hover {
    background: #1258b5;

    color: #ffffff;

    transform: translateY(-2px);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .salary-stats-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

@media (max-width: 850px) {

    .salary-container {
        padding: 18px;
    }

    .salary-welcome-card {
        padding: 25px;
    }

    .salary-card-header {
        align-items: flex-start;

        flex-direction: column;
    }

    .salary-search-box {
        width: 100%;
    }
}

@media (max-width: 650px) {

    .salary-container {
        padding: 15px;
    }

    .salary-stats-grid {
        grid-template-columns: 1fr;
    }

    .salary-welcome-card {
        min-height: 135px;

        align-items: flex-start;

        flex-direction: column;

        gap: 18px;
    }

    .salary-welcome-content h2 {
        font-size: 24px;
    }

    .salary-welcome-content p {
        font-size: 13px;
    }

    .salary-create-button {
        padding: 9px 14px;
    }

    .salary-table {
        min-width: 1050px;
    }

    .salary-actions {
        gap: 8px !important;
    }

    .salary-actions .salary-action {
        width: 36px !important;
        height: 36px !important;

        min-width: 36px !important;
        min-height: 36px !important;
    }
}
/* =========================================================
   PAGINATION
========================================================= */

.salary-pagination {
    padding: 18px 22px;

    border-top: 1px solid #edf1f6;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    background: #ffffff;
}

.salary-pagination-info {
    color: #64748b;

    font-size: 12px;
    font-weight: 600;
}

.salary-pagination nav {
    display: flex;
    align-items: center;
}

.salary-pagination nav > div:first-child {
    display: none;
}

.salary-pagination nav > div:last-child {
    display: flex;
    align-items: center;
}

.salary-pagination nav a,
.salary-pagination nav span {
    min-width: 34px;
    height: 34px;

    margin-left: 5px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    border: 1px solid #e2e8f0;
    border-radius: 8px;

    background: #ffffff;

    color: #475569;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    transition: all .2s ease;
}

.salary-pagination nav a:hover {
    background: #1769d1;

    border-color: #1769d1;

    color: #ffffff;

    transform: translateY(-1px);
}

.salary-pagination nav span[aria-current="page"] {
    background: #1769d1;

    border-color: #1769d1;

    color: #ffffff;
}

.salary-pagination nav span[aria-disabled="true"] {
    color: #cbd5e1;

    background: #f8fafc;

    cursor: not-allowed;
}

@media (max-width: 650px) {

    .salary-pagination {
        flex-direction: column;

        align-items: flex-start;
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

            <span style="font-size:18px;">+</span>

            Generate Salary

        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="salary-success">

            ✓

            {{ session('success') }}

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
                        {{ number_format($salaries->count()) }}
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
                        {{ number_format($salaries->where('payment_status', 'Pending')->count()) }}
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
                        {{ number_format($salaries->where('payment_status', 'Paid')->count()) }}
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

                        <th>
                            Teacher
                        </th>

                        <th>
                            Salary Month
                        </th>

                        <th>
                            Basic Salary
                        </th>

                        <th>
                            Allowances
                        </th>

                        <th>
                            Deductions
                        </th>

                        <th>
                            Net Salary
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($salaries as $salary)

                        <tr>

                            {{-- NUMBER --}}

                            <td>

                                <span class="salary-number">
                                    {{ $loop->iteration }}
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

                                            <path
                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                            ></path>

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

                                            <path
                                                d="M4 16.5V20h3.5L18.81 8.69l-3.5-3.5L4 16.5Z"
                                            ></path>

                                            <path
                                                d="M14.81 5.19l3.5 3.5"
                                            ></path>

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

                                                <path
                                                    d="M4 7h16"
                                                ></path>

                                                <path
                                                    d="M9 7V4h6v3"
                                                ></path>

                                                <path
                                                    d="M7 7l1 13h8l1-13"
                                                ></path>

                                                <path
                                                    d="M10 11v5"
                                                ></path>

                                                <path
                                                    d="M14 11v5"
                                                ></path>

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
            <div style="
    padding: 18px 22px;
    border-top: 1px solid #edf1f6;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    background: #ffffff;
">
    {{ $salaries->links() }}
</div>

        </div>

    </div>

</div>


{{-- =========================================================
     SEARCH SCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const searchInput =
            document.getElementById('salarySearch');

        const table =
            document.getElementById('salaryTable');

        if (!searchInput || !table) {
            return;
        }

        searchInput.addEventListener(
            'input',
            function () {

                const searchValue =
                    this.value
                        .toLowerCase()
                        .trim();

                const rows =
                    table.querySelectorAll(
                        'tbody tr'
                    );

                rows.forEach(
                    function (row) {

                        const rowText =
                            row.textContent
                                .toLowerCase();

                        row.style.display =
                            rowText.includes(searchValue)
                                ? ''
                                : 'none';

                    }
                );

            }
        );

    }
);

</script>

@endsection