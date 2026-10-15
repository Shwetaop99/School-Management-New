@extends('layouts.app')

@section('title', 'Teacher Salary')
@section('page-title', 'Teacher Salary')

@section('content')

<div class="teacher-salary-page">
    <div class="ts-container">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="ts-alert" role="status">
                <span class="ts-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="ts-hero">
            <div class="ts-hero-content">
                <div class="ts-hero-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>

                <div class="ts-hero-text">
                    <div class="ts-breadcrumb">
                        <span>Teacher Management</span>
                        <i class="bi bi-chevron-right"></i>
                        <span>Teacher Salary</span>
                    </div>

                    <h2>Teacher Salary</h2>

                    <p>
                        Manage staff salary records, basic pay, allowances,
                        deductions, payment status and salary payments.
                    </p>

                    <div class="ts-hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>School Payroll &amp; Salary Management</span>
                    </div>
                </div>
            </div>

            <div class="ts-hero-actions">
                <div class="ts-hero-decoration">
                    <i class="bi bi-wallet2"></i>
                </div>

                <div class="ts-handwritten">
                    Fair Salaries. Happy Staff.
                </div>

                <p>
                    Keep salary records accurate and payment statuses
                    up to date every month.
                </p>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="ts-kpis">

            <article class="ts-kpi blue" data-tab="RECORDS">
                <div class="ts-kpi-top">
                    <span class="ts-kpi-label">Total Salary Records</span>
                    <span class="ts-kpi-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                </div>

                <div class="ts-kpi-value">
                    {{ number_format($salaries->count()) }}
                </div>

                <div class="ts-kpi-caption">All salary records</div>
            </article>

            <article class="ts-kpi amber" data-tab="PENDING">
                <div class="ts-kpi-top">
                    <span class="ts-kpi-label">Pending Payments</span>
                    <span class="ts-kpi-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>
                </div>

                <div class="ts-kpi-value">
                    {{ number_format($salaries->where('payment_status', 'Pending')->count()) }}
                </div>

                <div class="ts-kpi-caption">Salaries awaiting payment</div>
            </article>

            <article class="ts-kpi green" data-tab="PAID">
                <div class="ts-kpi-top">
                    <span class="ts-kpi-label">Paid Salaries</span>
                    <span class="ts-kpi-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>
                </div>

                <div class="ts-kpi-value">
                    {{ number_format($salaries->where('payment_status', 'Paid')->count()) }}
                </div>

                <div class="ts-kpi-caption">Salaries successfully paid</div>
            </article>

            <article class="ts-kpi red" data-tab="PAYROLL">
                <div class="ts-kpi-top">
                    <span class="ts-kpi-label">Net Salary Value</span>
                    <span class="ts-kpi-icon">
                        <i class="bi bi-currency-rupee"></i>
                    </span>
                </div>

                <div class="ts-kpi-value">
                    ₹{{ number_format((float) $salaries->sum('net_salary'), 2) }}
                </div>

                <div class="ts-kpi-caption">Total net salary across records</div>
            </article>

        </section>

        {{-- STAFF CATEGORIES AND SALARY ACTIONS --}}
        <section class="ts-paper-panel">

            <div class="ts-panel-header">
                <div class="ts-title-wrap">
                    <div class="ts-title-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <h3>Staff Categories</h3>
                        <p>Generate and manage salary records by staff category</p>
                    </div>
                </div>
            </div>

            <div class="ts-table-wrapper">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Staff Type</th>
                            <th>Category</th>
                            <th>Records</th>
                            <th>Pending</th>
                            <th>Paid</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $staffCategories = [
                                [
                                    'key' => 'Teacher',
                                    'label' => 'Teachers',
                                    'icon' => 'bi-person-badge',
                                    'desc' => 'Teaching Staff',
                                ],
                                [
                                    'key' => 'Driver',
                                    'label' => 'Drivers',
                                    'icon' => 'bi-truck',
                                    'desc' => 'Transport Staff',
                                ],
                                [
                                    'key' => 'Peon',
                                    'label' => 'Peons',
                                    'icon' => 'bi-person-workspace',
                                    'desc' => 'Support Staff',
                                ],
                                [
                                    'key' => 'Librarian',
                                    'label' => 'Librarians',
                                    'icon' => 'bi-book',
                                    'desc' => 'Library Staff',
                                ],
                                [
                                    'key' => 'Accountant',
                                    'label' => 'Accountants',
                                    'icon' => 'bi-calculator',
                                    'desc' => 'Accounts Staff',
                                ],
                                [
                                    'key' => 'Receptionist',
                                    'label' => 'Receptionists',
                                    'icon' => 'bi-headset',
                                    'desc' => 'Front Desk Staff',
                                ],
                                [
                                    'key' => 'OtherStaff',
                                    'label' => 'Other Staff',
                                    'icon' => 'bi-people',
                                    'desc' => 'Other Employees',
                                ],
                            ];
                        @endphp

                        @foreach($staffCategories as $cat)
                            @php
                                // Safe filter — no otherStaff relation
                                $catSalaries = $salaries->filter(function ($salary) use ($cat) {
                                    if (!empty($salary->staff_type)) {
                                        return $salary->staff_type === $cat['key'];
                                    }

                                    if ($cat['key'] === 'Teacher') {
                                        return !empty($salary->teacher_id);
                                    }

                                    return false;
                                });

                                $totalRecords = $catSalaries->count();
                                $pendingCount = $catSalaries->where('payment_status', 'Pending')->count();
                                $paidCount    = $catSalaries->where('payment_status', 'Paid')->count();
                                $latestSalary = $catSalaries->sortByDesc('created_at')->first();
                            @endphp

                            <tr>
                                <td>
                                    <div class="ts-staff-type">
                                        <div class="ts-staff-avatar">
                                            <i class="bi {{ $cat['icon'] }}"></i>
                                        </div>

                                        <div class="ts-staff-details">
                                            <div class="ts-staff-name">
                                                {{ $cat['label'] }}
                                            </div>
                                            <div class="ts-staff-desc">
                                                {{ $cat['desc'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="ts-badge">
                                        {{ $cat['key'] }}
                                    </span>
                                </td>

                                <td>
                                    <div class="ts-count-cell">
                                        <i class="bi bi-receipt"></i>
                                        <span>{{ $totalRecords }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ts-count-cell pending">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $pendingCount }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ts-count-cell paid">
                                        <i class="bi bi-check-circle"></i>
                                        <span>{{ $paidCount }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ts-actions">

                                        {{-- GENERATE (always) --}}
                                        <a href="{{ route('admin.teachers.salary.create', ['staff_type' => $cat['key']]) }}"
                                           class="ts-mark-btn"
                                           title="Generate Salary">
                                            <i class="bi bi-plus-lg"></i>
                                            Generate
                                        </a>

                                        {{-- Spacer between Generate and other actions --}}
                                        <span class="ts-actions-gap" aria-hidden="true"></span>

                                        {{-- VIEW --}}
                                        @if($latestSalary)
                                            <a href="{{ route('admin.teachers.salary.show', $latestSalary->id) }}"
                                               class="ts-record-btn ts-view-btn"
                                               title="View Latest Salary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @else
                                            <span class="ts-record-btn ts-view-btn ts-disabled"
                                                  title="No salary record yet">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        @endif

                                        {{-- EDIT --}}
                                        @if($latestSalary)
                                            <a href="{{ route('admin.teachers.salary.edit', $latestSalary->id) }}"
                                               class="ts-record-btn ts-edit-btn"
                                               title="Edit Latest Salary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @else
                                            <span class="ts-record-btn ts-edit-btn ts-disabled"
                                                  title="No salary record yet">
                                                <i class="bi bi-pencil-square"></i>
                                            </span>
                                        @endif

                                        {{-- DELETE --}}
                                        @if($latestSalary)
                                            <form action="{{ route('admin.teachers.salary.destroy', $latestSalary->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this salary record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="ts-record-btn ts-delete-btn"
                                                        title="Delete Latest Salary">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="ts-record-btn ts-delete-btn ts-disabled"
                                                  title="No salary record yet">
                                                <i class="bi bi-trash"></i>
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ts-card-footer">
                <div>
                    Showing <strong>{{ count($staffCategories) }}</strong>
                    staff categories
                </div>

                <div class="ts-footer-status">
                    <i class="bi bi-info-circle"></i>
                    View / Edit / Delete apply to the latest salary record in each category
                </div>
            </div>

        </section>

    </div>
</div>

<style>
.teacher-salary-page {
    --ts-ink: #263b35;
    --ts-text: #39483e;
    --ts-muted: #7b857b;
    --ts-line: #e5e1d5;
    --ts-paper: #fffefa;
    --ts-chalk: #f5f3e9;
    --ts-blue: #426b8a;
    --ts-green: #43835d;
    --ts-amber: #a87543;
    --ts-red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--ts-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.teacher-salary-page *,
.teacher-salary-page *::before,
.teacher-salary-page *::after {
    box-sizing: border-box;
}

.teacher-salary-page a,
.teacher-salary-page button,
.teacher-salary-page .ts-kpi {
    transition: all .2s ease;
}

.teacher-salary-page .ts-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.teacher-salary-page .ts-hero,
.teacher-salary-page .ts-paper-panel {
    border: 1px solid var(--ts-line);
    border-radius: 15px;
    background: var(--ts-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.teacher-salary-page .ts-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.teacher-salary-page .ts-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    padding: 35px 30px;
}

.teacher-salary-page .ts-hero-icon,
.teacher-salary-page .ts-title-icon,
.teacher-salary-page .ts-staff-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid #e2ddcf;
    border-radius: 12px;
    color: var(--ts-ink);
    background: var(--ts-chalk);
}

.teacher-salary-page .ts-hero-icon {
    width: 64px;
    height: 64px;
    font-size: 25px;
}

.teacher-salary-page .ts-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.teacher-salary-page .ts-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ts-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(26px, 2.2vw, 34px);
}

.teacher-salary-page .ts-hero-text p,
.teacher-salary-page .ts-hero-actions p {
    color: var(--ts-muted);
    font-size: 12px;
    line-height: 1.9;
}

.teacher-salary-page .ts-hero-text p {
    max-width: 540px;
    margin: 0;
}

.teacher-salary-page .ts-hero-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
}

.teacher-salary-page .ts-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 30px 25px;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43, #20352c);
    text-align: center;
}

.teacher-salary-page .ts-hero-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px;
    color: #ead7aa;
    font-size: 18px;
}

.teacher-salary-page .ts-handwritten {
    color: #f0e5c9;
    font-family: "Caveat", cursive;
    font-size: 23px;
}

.teacher-salary-page .ts-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
}

/* SUCCESS ALERT */

.teacher-salary-page .ts-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-weight: 700;
}

.teacher-salary-page .ts-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: #deeedb;
}

/* KPI CARDS */

.teacher-salary-page .ts-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.teacher-salary-page .ts-kpi {
    --kpi-color: #426b8a;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--ts-line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--ts-paper);
}

.teacher-salary-page .ts-kpi.blue { --kpi-color: #426b8a; }
.teacher-salary-page .ts-kpi.amber { --kpi-color: #a87543; }
.teacher-salary-page .ts-kpi.green { --kpi-color: #43835d; }
.teacher-salary-page .ts-kpi.red { --kpi-color: #b95b50; }

.teacher-salary-page .ts-kpi::before {
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: white;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
    content: attr(data-tab);
    align-self: flex-start;
    margin-top: -34px;
    margin-bottom: -6px;
}

.teacher-salary-page .ts-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.teacher-salary-page .ts-kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.teacher-salary-page .ts-kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.teacher-salary-page .ts-kpi-value {
    color: var(--ts-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(22px, 2vw, 35px);
    font-weight: 700;
    overflow-wrap: anywhere;
}

.teacher-salary-page .ts-kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
}

/* PANEL */

.teacher-salary-page .ts-paper-panel {
    overflow: hidden;
    margin-bottom: 28px;
}

.teacher-salary-page .ts-panel-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.teacher-salary-page .ts-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
}

.teacher-salary-page .ts-title-icon {
    width: 43px;
    height: 43px;
    background: #f1f2e8;
}

.teacher-salary-page .ts-title-wrap h3 {
    margin: 0;
    color: var(--ts-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
}

.teacher-salary-page .ts-title-wrap p {
    margin: 5px 0 0;
    color: var(--ts-muted);
    font-size: 11px;
}

/* STAFF */

.teacher-salary-page .ts-staff-type {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 180px;
}

.teacher-salary-page .ts-staff-avatar {
    width: 42px;
    height: 42px;
    background: #edf1e7;
}

.teacher-salary-page .ts-staff-name {
    color: #293d32;
    font-weight: 800;
}

.teacher-salary-page .ts-staff-desc {
    margin-top: 3px;
    color: #828c7f;
    font-size: 10px;
}

.teacher-salary-page .ts-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    border-radius: 7px;
    color: #5b4a8a;
    background: #f0eaf8;
    font-size: 10px;
    font-weight: 800;
}

.teacher-salary-page .ts-count-cell {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #34443a;
    font-weight: 700;
}

.teacher-salary-page .ts-count-cell.pending i {
    color: var(--ts-amber);
}

.teacher-salary-page .ts-count-cell.paid i {
    color: var(--ts-green);
}

/* TABLE */

.teacher-salary-page .ts-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.teacher-salary-page .ts-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.teacher-salary-page .ts-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.teacher-salary-page .ts-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    font-size: 11px;
    vertical-align: middle;
    white-space: nowrap;
}

.teacher-salary-page .ts-table tbody tr:hover {
    background: #f8f8f1;
}

.teacher-salary-page .ts-table tbody tr:last-child td {
    border-bottom: none;
}

/* ACTIONS — more space between Generate and icons */

.teacher-salary-page .ts-actions {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: nowrap;
    gap: 8px;
    min-width: 280px;
}

.teacher-salary-page .ts-actions-gap {
    display: inline-block;
    width: 14px;          /* extra space between Generate and View/Edit/Delete */
    flex-shrink: 0;
}

.teacher-salary-page .ts-actions form {
    display: inline-flex;
    margin: 0;
}

/* GENERATE BUTTON */

.teacher-salary-page .ts-mark-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 34px;
    padding: 8px 14px;
    border: 1px solid #1e3329;
    border-radius: 8px;
    color: white;
    background: #1e3329;
    font-size: 10px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.teacher-salary-page .ts-mark-btn:hover {
    color: white;
    background: #2a4638;
}

/* VIEW / EDIT / DELETE — icon only for cleaner look */

.teacher-salary-page .ts-record-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid transparent;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    text-decoration: none;
    cursor: pointer;
}

.teacher-salary-page .ts-view-btn {
    color: #426b8a;
    background: #edf4f9;
    border-color: #d7e5ef;
}

.teacher-salary-page .ts-edit-btn {
    color: #8a682b;
    background: #fbf3df;
    border-color: #eee0b9;
}

.teacher-salary-page .ts-delete-btn {
    color: #a64040;
    background: #fbeaea;
    border-color: #f0d0d0;
}

.teacher-salary-page .ts-record-btn:hover:not(.ts-disabled) {
    filter: brightness(.96);
    transform: translateY(-1px);
}

.teacher-salary-page .ts-record-btn.ts-disabled {
    opacity: .4;
    cursor: not-allowed;
    pointer-events: none;
}

/* FOOTER */

.teacher-salary-page .ts-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.teacher-salary-page .ts-card-footer strong {
    color: var(--ts-ink);
}

.teacher-salary-page .ts-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-weight: 700;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .teacher-salary-page .ts-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 35px;
    }
}

@media (max-width: 900px) {
    .teacher-salary-page .ts-hero {
        grid-template-columns: 1fr;
    }

    .teacher-salary-page .ts-hero-content {
        padding: 29px;
    }
}

@media (max-width: 650px) {
    .teacher-salary-page .ts-container {
        padding: 18px 14px;
    }

    .teacher-salary-page .ts-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px;
    }

    .teacher-salary-page .ts-hero-icon {
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .teacher-salary-page .ts-hero-text h2 {
        font-size: 24px;
    }

    .teacher-salary-page .ts-hero-actions {
        padding: 24px 16px;
    }

    .teacher-salary-page .ts-kpis {
        column-gap: 12px;
        row-gap: 35px;
    }

    .teacher-salary-page .ts-kpi {
        padding: 19px 13px 15px;
    }

    .teacher-salary-page .ts-kpi-icon {
        width: 36px;
        height: 36px;
    }

    .teacher-salary-page .ts-kpi-value {
        font-size: 24px;
    }

    .teacher-salary-page .ts-panel-header {
        padding: 16px;
    }

    .teacher-salary-page .ts-title-wrap h3 {
        font-size: 16px;
    }

    .teacher-salary-page .ts-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }
}

@media (max-width: 400px) {
    .teacher-salary-page .ts-kpis {
        grid-template-columns: 1fr;
    }
}

.teacher-salary-page a:focus-visible,
.teacher-salary-page button:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .teacher-salary-page *,
    .teacher-salary-page *::before,
    .teacher-salary-page *::after {
        transition: none !important;
        animation: none !important;
    }
}
</style>

@endsection