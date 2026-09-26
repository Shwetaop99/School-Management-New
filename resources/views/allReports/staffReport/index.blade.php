@extends('layouts.app')

@section('title', 'Staff Reports')
@section('page-title', 'Staff Reports')

@section('content')

<style>

    .report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.report-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.report-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.pdf-btn {
    background: #dc2626;
    color: white;
}

.excel-btn {
    background: #16a34a;
    color: white;
}

.print-btn {
    background: #0f766e;
    color: white;
}

    .staff-report-page {
        padding: 25px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }

    /* Header */
    .report-header {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #fff;
        padding: 24px 28px;
        border-radius: 16px;
        margin-bottom: 22px;
    }

    .report-header h2 {
        margin: 0 0 5px;
        font-size: 24px;
        font-weight: 700;
    }

    .report-header p {
        margin: 0;
        font-size: 14px;
        opacity: .9;
    }

    /* Filter */
    .filter-card {
        background: #fff;
        padding: 22px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 22px;
    }

    .filter-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .filter-title-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #eff6ff;
        color: #2563eb;
    }

    .filter-title h5 {
        margin: 0;
        font-size: 16px;
        color: #1f2937;
    }

    .filter-label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .filter-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dbe1ea;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        color: #334155;
        outline: none;
    }

    .filter-control:focus {
        border-color: #2563eb;
    }

    .filter-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-filter,
    .btn-reset {
        height: 42px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-filter {
        border: none;
        background: #2563eb;
        color: #fff;
    }

    .btn-filter:hover {
        background: #1d4ed8;
    }

    .btn-reset {
        border: 1px solid #dbe1ea;
        background: #fff;
        color: #475569;
    }

    .btn-reset:hover {
        background: #f8fafc;
    }

    /* Table */
    .table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .table-header {
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-header h5 {
        margin: 0;
        font-size: 16px;
        color: #1f2937;
    }

    .table-header p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .staff-count {
        padding: 6px 11px;
        border-radius: 20px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .staff-report-table {
        width: 100%;
        min-width: 1000px;
        border-collapse: collapse;
    }

    .staff-report-table th {
        padding: 13px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        text-align: left;
        white-space: nowrap;
    }

    .staff-report-table td {
        padding: 13px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 13px;
        white-space: nowrap;
    }

    .staff-report-table tbody tr:hover {
        background: #fafcff;
    }

    /* Staff */
    .staff-info {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .staff-avatar {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        overflow: hidden;
        flex-shrink: 0;
    }

    .staff-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .staff-name {
        font-weight: 600;
        color: #1f2937;
    }

    .staff-id {
        margin-top: 2px;
        font-size: 11px;
        color: #94a3b8;
    }

    /* Badges */
    .status-badge,
    .gender-badge,
    .designation-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .gender-badge,
    .designation-badge {
        background: #f1f5f9;
        color: #475569;
    }

    /* Empty */
    .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        display: block;
        margin-bottom: 10px;
        font-size: 40px;
        color: #cbd5e1;
    }

    .empty-state h6 {
        margin-bottom: 4px;
        color: #475569;
        font-size: 15px;
    }

    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .staff-report-page {
            padding: 15px;
        }

        .filter-card {
            padding: 18px;
        }

        .table-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }

    }

    @media print {

    body {
        background: white !important;
    }

    .staff-report-page {
        padding: 0 !important;
        background: white !important;
    }

    .report-header {
        background: white !important;
        color: #000 !important;
        border: none !important;
        padding: 0 0 15px !important;
    }

    .report-header p {
        color: #555 !important;
    }

    .report-actions,
    .filter-card,
    .staff-report-page > .table-card .staff-count,
    .staff-report-page .table-header p,
    .staff-report-page .pagination {
        display: none !important;
    }

    .table-card {
        border: none !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    .staff-report-table {
        min-width: 0 !important;
        width: 100% !important;
    }

    .staff-report-table th,
    .staff-report-table td {
        font-size: 9px !important;
        padding: 6px !important;
    }

    .staff-report-table th:last-child,
    .staff-report-table td:last-child {
        display: none !important;
    }

    .staff-report-table {
        page-break-inside: auto;
    }

    .staff-report-table tr {
        page-break-inside: avoid;
    }

    @page {
        size: A4 landscape;
        margin: 10mm;
    }
}
</style>

<div class="staff-report-page">

    <div class="report-header">

    <div>
        <h2>Staff Reports</h2>
        <p>Generate and view detailed information about non-teaching staff.</p>
    </div>

    <div class="report-actions">

        <a
            href="{{ route('admin.reports.staff.pdf', request()->query()) }}"
            class="report-action pdf-btn"
        >
            <i class="bi bi-file-earmark-pdf"></i>
            PDF
        </a>

        <a
            href="{{ route('admin.reports.staff.excel', request()->query()) }}"
            class="report-action excel-btn"
        >
            <i class="bi bi-file-earmark-excel"></i>
            Excel
        </a>

        <button
            type="button"
            onclick="window.print()"
            class="report-action print-btn"
        >
            <i class="bi bi-printer"></i>
            Print
        </button>

    </div>

</div>

    <div class="filter-card">

        <div class="filter-title">
            <div class="filter-title-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>
            <h5>Filter Staff Reports</h5>
        </div>

        <form method="GET" action="{{ route('admin.reports.staff') }}">

            <div class="row g-3">

                <div class="col-lg-4 col-md-6">
                    <label class="filter-label">Staff Search</label>
                    <input
                        type="text"
                        name="search"
                        class="filter-control"
                        placeholder="Name, ID, phone or email..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="filter-label">Designation</label>
                    <select name="designation" class="filter-control">
                        <option value="">All Designations</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation }}" {{ request('designation') == $designation ? 'selected' : '' }}>
                                {{ $designation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="filter-label">Department</label>
                    <select name="department" class="filter-control">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="filter-label">Gender</label>
                    <select name="gender" class="filter-control">
                        <option value="">All Genders</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender }}" {{ request('gender') == $gender ? 'selected' : '' }}>
                                {{ $gender }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-lg-4 col-md-8">
                    <label class="filter-label">&nbsp;</label>

                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.reports.staff') }}" class="btn-reset">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </div>

            </div>

        </form>

    </div>

    <div class="table-card">

        <div class="table-header">
            <div>
                <h5>Staff Report</h5>
                <p>Staff information based on the selected filters.</p>
            </div>

            <div class="staff-count">
                {{ $staff->total() }} Staff
            </div>
        </div>

        <div class="table-responsive">

            <table class="staff-report-table">

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
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($staff as $member)

                        <tr
                            onclick="window.location='{{ route('admin.reports.staff.show', $member->id) }}'"
                            style="cursor: pointer;"
                        >

                            <td>{{ $staff->firstItem() + $loop->index }}</td>

                            <td>
    <div class="staff-info">

        <div class="staff-avatar">
            @if($member->profile_photo)
                <img
                    src="{{ asset('storage/' . $member->profile_photo) }}"
                    alt="{{ $member->name }}"
                >
            @else
                {{ strtoupper(substr($member->name ?? 'S', 0, 1)) }}
            @endif
        </div>

        <div>
            <div class="staff-name">
                {{ $member->name ?? '-' }}
            </div>

            <div class="staff-id">
                {{ $member->staff_id ?? '-' }}
            </div>
        </div>

    </div>
</td>
                            <td>{{ $member->staff_id ?? '-' }}</td>

                            <td>
                                @if($member->designation)
                                    <span class="designation-badge">
                                        {{ $member->designation }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $member->department ?? '-' }}</td>
                            <td>{{ $member->qualification ?? '-' }}</td>

                            <td>
                                @if($member->gender)
                                    <span class="gender-badge">
                                        {{ $member->gender }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $member->phone ?? '-' }}</td>

                            <td>
                                @if($member->joining_date)
                                    {{ $member->joining_date->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if(strtolower($member->status ?? '') === 'active')
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">
                                        {{ ucfirst($member->status ?? 'Inactive') }}
                                    </span>
                                @endif
                            </td>

                            <td>
    <a
        href="{{ route('admin.reports.staff.show', $member->id) }}"
        class="btn btn-sm btn-primary"
        title="View Staff Report"
    >
        <i class="bi bi-eye"></i>
    </a>
</td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="bi bi-person-badge"></i>
                                    <h6>No staff found</h6>
                                    <p>No staff records match the selected filters.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($staff->hasPages())
            <div style="padding:20px;border-top:1px solid #edf1f6;">
                {{ $staff->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
