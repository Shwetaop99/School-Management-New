 @extends('layouts.app')

@section('title', 'Staff Reports | Admin')
@section('page-title', 'Staff Reports')

@section('content')

<div class="payroll-simple staff-reports-page">
    <div class="container-fluid py-4">

        @php
            $selectedSearch      = request('search');
            $selectedDesignation = request('designation');
            $selectedDepartment  = request('department');
            $selectedGender      = request('gender');
            $selectedStatus      = request('status');

            // School identity for print
            $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();
            $schoolName    = trim((string) ($schoolSetting->school_name ?? ''));
            $schoolName    = $schoolName !== '' ? $schoolName : 'School Management System';
            $schoolLogo    = $schoolSetting->logo_url ?? null;
            $schoolAddress = collect([
                $schoolSetting->address ?? null,
                $schoolSetting->city ?? null,
                $schoolSetting->district ?? null,
                $schoolSetting->state ?? null,
                $schoolSetting->pincode ?? null,
            ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');
            $schoolPhone   = trim((string) ($schoolSetting->phone ?? ''));
            $schoolEmail   = trim((string) ($schoolSetting->email ?? ''));
        @endphp

        {{-- PRINT ONLY SCHOOL BRANDING --}}
        <div class="print-only">
            <div class="print-header">
                <div class="print-brand">
                    @if (!empty($schoolLogo))
                        <img class="print-school-logo" src="{{ $schoolLogo }}" alt="School Logo">
                    @endif
                    <div class="print-school-identity">
                        <div class="print-school-name">{{ $schoolName }}</div>
                        @if ($schoolAddress !== '')
                            <div class="print-school-contact">{{ $schoolAddress }}</div>
                        @endif
                        @if ($schoolPhone !== '' || $schoolEmail !== '')
                            <div class="print-school-contact">
                                @if ($schoolPhone !== '') Phone: {{ $schoolPhone }} @endif
                                @if ($schoolPhone !== '' && $schoolEmail !== '') &nbsp; | &nbsp; @endif
                                @if ($schoolEmail !== '') Email: {{ $schoolEmail }} @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="print-report-title">
                    <div class="print-brand-name">Staff Reports</div>
                    <div class="print-brand-sub">Official Non-Teaching Staff Report</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Records:</span>
                    <span class="print-value">{{ number_format($staff->total()) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="staff-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        {{-- HERO SECTION --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="hero-text">
                    <div class="hero-breadcrumb">Reports / Staff Reports</div>
                    <h2>Staff Reports</h2>
                    <p>Generate and view detailed information about non-teaching staff members.</p>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-handwritten">Staff. Records. Reports.</div>
                <p>Filter staff and export complete reports instantly.</p>
            </div>
        </section>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.reports.staff') }}" class="filters">
            <div class="filter-item search-item">
                <label>Search</label>
                <input type="text" name="search" value="{{ $selectedSearch }}" placeholder="Name, ID, phone or email...">
            </div>

            <div class="filter-item">
                <label>Designation</label>
                <select name="designation">
                    <option value="">All Designations</option>
                    @foreach($designations ?? [] as $designation)
                        <option value="{{ $designation }}" @selected($selectedDesignation == $designation)>{{ $designation }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Department</label>
                <select name="department">
                    <option value="">All Departments</option>
                    @foreach($departments ?? [] as $department)
                        <option value="{{ $department }}" @selected($selectedDepartment == $department)>{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Gender</label>
                <select name="gender">
                    <option value="">All Genders</option>
                    @foreach($genders ?? [] as $gender)
                        <option value="{{ $gender }}" @selected($selectedGender == $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" @selected($selectedStatus === 'active')>Active</option>
                    <option value="inactive" @selected($selectedStatus === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.reports.staff') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Staff ID</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Qualification</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $member)
                        <tr>
                            <td>{{ ($staff->firstItem() ?? 0) + $loop->index }}</td>

                            <td>
                                <a href="{{ route('admin.reports.staff.show', $member->id) }}"
                                   class="staff-profile-link">
                                    <div class="emp-name">
                                        {{ $member->name ?? '—' }}
                                    </div>
                                </a>
                                <div class="emp-id">ID: {{ $member->staff_id ?? 'N/A' }}</div>
                            </td>

                            <td><strong>{{ $member->staff_id ?? '—' }}</strong></td>
                            <td>{{ $member->designation ?? '—' }}</td>
                            <td>{{ $member->department ?? '—' }}</td>
                            <td>{{ $member->qualification ?? '—' }}</td>
                            <td>{{ $member->gender ?? '—' }}</td>
                            <td>{{ $member->phone ?? '—' }}</td>

                            <td>
                                {{ $member->joining_date ? $member->joining_date->format('d M Y') : '—' }}
                            </td>

                            <td>
                                @php
                                    $status = strtolower(trim($member->status ?? ''));
                                    $statusClass = $status === 'active' ? 'paid' : 'unpaid';
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    {{ $member->status ? ucfirst($member->status) : 'Not set' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="empty">
                                No staff records found. Try changing the filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($staff->hasPages())
            <div class="pagination-bar">
                <span>
                    Showing {{ $staff->firstItem() ?? 0 }} to {{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }} records
                </span>
                <div>{{ $staff->withQueryString()->links() }}</div>
            </div>
        @elseif($staff->count())
            <div class="pagination-bar">
                <span>Showing {{ $staff->count() }} of {{ $staff->total() }} records</span>
            </div>
        @endif

    </div>
</div>

<style>
.payroll-simple {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --net: #1a6b3c;
    background: var(--bg);
    color: var(--text);
    min-height: calc(100vh - 70px);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.payroll-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.payroll-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.payroll-simple .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

.payroll-simple .btn-back:hover {
    background: #f0eee8;
}

.payroll-simple .btn-primary,
.payroll-simple .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    text-decoration: none;
}

.payroll-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.payroll-simple .btn-primary:hover {
    background: var(--primary-hover);
}

.payroll-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.payroll-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* Hero Section */
.payroll-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}

.payroll-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}

.payroll-simple .hero-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: #f3ead8;
    border: 1px solid #e5d9c0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5c4a32;
    font-size: 22px;
    flex-shrink: 0;
}

.payroll-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.payroll-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.payroll-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}

/* Cream / Pearl Right Side */
.payroll-simple .hero-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(145deg, #f8f1e3 0%, #f0e6d2 50%, #e8dcc6 100%);
    border-left: 1px solid #e5d9c4;
}

.payroll-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}

.payroll-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}

.payroll-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}

.payroll-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.payroll-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

.payroll-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.payroll-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Filters */
.payroll-simple .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
    padding: 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 20px;
}

.payroll-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.payroll-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.payroll-simple .filter-item input,
.payroll-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 140px;
    background: #fff;
}

.payroll-simple .filter-item.search-item input {
    min-width: 220px;
}

.payroll-simple .filter-item input:focus,
.payroll-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.payroll-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.payroll-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.payroll-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1100px;
}

.payroll-simple th {
    background: #f0eee8;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

.payroll-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.payroll-simple tbody tr:last-child td {
    border-bottom: none;
}

.payroll-simple tbody tr:hover td {
    background: #faf9f5;
}

.payroll-simple .emp-name {
    font-weight: 600;
}

/* Clickable individual staff profile name */
.payroll-simple .staff-profile-link {
    display: inline-block;
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s ease;
}

.payroll-simple .staff-profile-link:hover {
    color: #9c7b4a;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.payroll-simple .staff-profile-link:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 3px;
    border-radius: 2px;
}

.payroll-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.payroll-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.payroll-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.payroll-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.payroll-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.payroll-simple .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--muted);
}

/* Print only */
.payroll-simple .print-only {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .payroll-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .payroll-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}

@media (max-width: 768px) {
    .payroll-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .payroll-simple .filter-item input,
    .payroll-simple .filter-item select {
        width: 100%;
        min-width: 0;
    }
    .payroll-simple .hero-left {
        padding: 22px 20px;
    }
    .payroll-simple .hero-text h2 {
        font-size: 20px;
    }
}

/* ================= PRINT — Professional Layout ================= */
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm 12mm 12mm 12mm;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body * {
        visibility: hidden;
    }

    .payroll-simple,
    .payroll-simple * {
        visibility: visible;
    }

    .payroll-simple {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff !important;
        color: #1a1a1a;
        font-family: "Inter", "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 10.5px;
        line-height: 1.35;
    }

    .payroll-simple .top-bar,
    .payroll-simple .hero-section,
    .payroll-simple .filters,
    .payroll-simple .pagination-bar,
    .payroll-simple .print-hide {
        display: none !important;
    }

    .payroll-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .payroll-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .payroll-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .payroll-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .payroll-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .payroll-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .payroll-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .payroll-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .payroll-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .payroll-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .payroll-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .payroll-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .payroll-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .payroll-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .payroll-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    .payroll-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .payroll-simple table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
        min-width: 0 !important;
    }

    .payroll-simple th {
        background: #2c3e36 !important;
        color: #fff !important;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 7px 8px;
        border: 1px solid #1e2a25;
        text-align: left;
        white-space: nowrap;
    }

    .payroll-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .payroll-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .payroll-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .payroll-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .payroll-simple .staff-profile-link,
    .payroll-simple .staff-profile-link:hover {
        color: #1a1a1a !important;
        text-decoration: none !important;
    }

    .payroll-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }

    .payroll-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .payroll-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .payroll-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .payroll-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    .payroll-simple .table-wrap::after {
        content: "This is a system-generated staff report. For official use only.";
        display: block;
        margin-top: 10px;
        font-size: 8.5px;
        color: #777;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 6px;
    }
}
</style>



@endsection