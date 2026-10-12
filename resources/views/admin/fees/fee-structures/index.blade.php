@extends('layouts.app')

@section('title', 'Fee Structures')

@section('content')

<style>
    .dashboard-container {
        padding: 25px;
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
    }

    /* Welcome */
    .welcome-card {
        padding: 28px;
        margin-bottom: 20px;
        border-radius: 15px;
        background: linear-gradient(135deg, #1769d1, #159cc7);
        color: white;
    }

    .welcome-icon {
        width: 42px;
        height: 42px;
        margin-bottom: 10px;
        border-radius: 10px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome-card h2 {
        margin: 0 0 5px;
        font-size: 26px;
        font-weight: 700;
    }

    .welcome-card p {
        margin: 0;
        font-size: 14px;
    }

    /* Statistics */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .stat-card {
        padding: 22px;
        border-radius: 14px;
        color: white;
    }

    .stat-blue {
        background: #1769d1;
    }

    .stat-orange {
        background: #ed9208;
    }

    .stat-red {
        background: #e94d47;
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
    }

    .stat-title {
        font-size: 13px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    /* Cards */
    .dashboard-card {
        margin-bottom: 20px;
        background: white;
        border: 1px solid #e5ebf3;
        border-radius: 14px;
        overflow: hidden;
    }

    .card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-title i {
        color: #1769d1;
    }

    .card-title h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .card-subtitle {
        margin: 4px 0 0 25px;
        color: #718096;
        font-size: 12px;
    }

    .card-body {
        padding: 20px;
    }

    /* Filters */
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #536078;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border: 1px solid #dce4ef;
        border-radius: 8px;
        font-size: 13px;
        box-shadow: none;
    }

    .filter-actions {
        display: flex;
        gap: 7px;
    }

    .btn-search,
    .btn-add {
        min-height: 42px;
        padding: 0 16px;
        border: 0;
        border-radius: 8px;
        background: #1769d1;
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-search:hover,
    .btn-add:hover {
        background: #125ab5;
        color: white;
    }

    .btn-reset {
        min-height: 42px;
        padding: 0 15px;
        border-radius: 8px;
        background: #f1f4f8;
        color: #566176;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    /* Table */
    .table-wrapper {
        overflow-x: auto;
    }

    .fee-table {
        width: 100%;
        min-width: 1000px;
        border-collapse: collapse;
    }

    .fee-table th {
        padding: 13px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e7edf5;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .fee-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #eef2f7;
        color: #39465d;
        font-size: 13px;
        vertical-align: middle;
    }

    .fee-table tbody tr:hover td {
        background: #f8fbff;
    }

    /* Structure */
    .structure-box {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .structure-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .structure-name {
        color: #172033;
        font-weight: 700;
    }

    .structure-id {
        color: #94a3b8;
        font-size: 11px;
    }

    /* Simple labels */
    .serial-box {
        color: #64748b;
        font-weight: 700;
    }

    .academic-year {
        color: #1769d1;
        font-weight: 700;
        white-space: nowrap;
    }

    .class-badge {
        color: #7c3aed;
        font-weight: 700;
        white-space: nowrap;
    }

    .section-box {
        color: #536078;
        font-weight: 600;
        white-space: nowrap;
    }

    .fee-count {
        color: #0369a1;
        font-weight: 700;
        white-space: nowrap;
    }

    .amount {
        color: #15803d;
        font-weight: 800;
        white-space: nowrap;
    }

    /* Status */
    .status-badge {
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-active {
        color: #15803d;
    }

    .status-inactive {
        color: #dc2626;
    }

    /* Actions */
    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
    }

    .action-view {
        background: #dbeafe;
        color: #1769d1;
    }

    .action-edit {
        background: #ede9fe;
        color: #7c3aed;
    }

    .action-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .payment-count {
        padding: 5px 9px;
        border-radius: 15px;
        background: #eef4ff;
        color: #1769d1;
        font-size: 11px;
        font-weight: 700;
    }

    /* Empty */
    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 35px;
        color: #1769d1;
        margin-bottom: 10px;
    }

    .empty-state-title {
        font-weight: 700;
        color: #172033;
    }

    .empty-state-text {
        color: #94a3b8;
        font-size: 13px;
        margin: 5px 0 15px;
    }

    .pagination-wrapper {
        padding: 17px 20px;
        border-top: 1px solid #edf1f6;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 700px) {
        .dashboard-container {
            padding: 15px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
        }

        .card-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="dashboard-container">

    {{-- WELCOME --}}
    <div class="welcome-card">

        <div class="welcome-icon">
            <i class="bi bi-diagram-3"></i>
        </div>

        <h2>Fee Structures</h2>

        <p>
            Create and manage class-wise and section-wise fee structures.
        </p>

    </div>


    {{-- STATISTICS --}}
    <div class="stats-grid">

        <div class="stat-card stat-blue">
            <div class="stat-top">
                <div>
                    <div class="stat-number">
                        {{ $totalStructures }}
                    </div>

                    <div class="stat-title">
                        Total Structures
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>
            </div>
        </div>


        <div class="stat-card stat-orange">
            <div class="stat-top">
                <div>
                    <div class="stat-number">
                        {{ $activeStructures }}
                    </div>

                    <div class="stat-title">
                        Active Structures
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>


        <div class="stat-card stat-red">
            <div class="stat-top">
                <div>
                    <div class="stat-number">
                        {{ $inactiveStructures }}
                    </div>

                    <div class="stat-title">
                        Inactive Structures
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- FILTERS --}}
    <div class="dashboard-card">

        <div class="card-header">

            <div>
                <div class="card-title">
                    <i class="bi bi-funnel-fill"></i>
                    <h3>Fee Structure Filters</h3>
                </div>

                <div class="card-subtitle">
                    Search structures by name, academic year or status.
                </div>
            </div>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.fees.fee-structures.index') }}"
            >

                <div class="filter-grid">

                    <div>
                        <label class="form-label">Search</label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Structure name, academic year, class or section"
                        >
                    </div>


                    <div>
                        <label class="form-label">Academic Year</label>

                        <select name="academic_year" class="form-select">

                            <option value="">
                                All Academic Years
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


                    <div>
                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Active"
                                {{ request('status') == 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ request('status') == 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="filter-actions">

                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.fees.fee-structures.index') }}"
                            class="btn-reset"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- FEE STRUCTURES --}}
    <div class="dashboard-card">

        <div class="card-header">

            <div>

                <div class="card-title">
                    <i class="bi bi-receipt-cutoff"></i>
                    <h3>All Fee Structures</h3>
                </div>

                <div class="card-subtitle">
                    Complete list of class-wise and section-wise fee structures.
                </div>

            </div>


            <div class="d-flex align-items-center gap-2">

                <span class="payment-count">
                    {{ $feeStructures->total() }} record(s)
                </span>

                <a
                    href="{{ route('admin.fees.fee-structures.create') }}"
                    class="btn-add"
                >
                    <i class="bi bi-plus-lg"></i>
                    Add Structure
                </a>

            </div>

        </div>


        <div class="table-wrapper">

            @if($feeStructures->count())

                <table class="fee-table">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Fee Structure</th>
                            <th>Academic Year</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Fee Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($feeStructures as $feeStructure)

                            @php
                                $totalAmount = $feeStructure->items->sum('amount');
                                $feeCount = $feeStructure->items->count();
                            @endphp

                            <tr>

                                <td>
                                    <span class="serial-box">
                                        {{ $feeStructures->firstItem() + $loop->index }}
                                    </span>
                                </td>


                                <td>

                                    <div class="structure-box">

                                        <div class="structure-icon">
                                            <i class="bi bi-receipt"></i>
                                        </div>

                                        <div>

                                            <div class="structure-name">
                                                {{ $feeStructure->structure_name }}
                                            </div>

                                            <div class="structure-id">
                                                Structure #{{ $feeStructure->id }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <span class="academic-year">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $feeStructure->academic_year }}
                                    </span>
                                </td>


                                <td>
                                    <span class="class-badge">
                                        <i class="bi bi-mortarboard"></i>
                                        {{ $feeStructure->schoolClass->class_name ?? 'N/A' }}
                                    </span>
                                </td>


                                <td>
                                    <span class="section-box">
                                        <i class="bi bi-people"></i>
                                        {{ $feeStructure->section->section_name ?? 'All Sections' }}
                                    </span>
                                </td>


                                <td>
                                    <span class="fee-count">
                                        <i class="bi bi-list-check"></i>
                                        {{ $feeCount }}
                                        {{ $feeCount == 1 ? 'Fee' : 'Fees' }}
                                    </span>
                                </td>


                                <td>
                                    <span class="amount">
                                        ₹{{ number_format((float) $totalAmount, 2) }}
                                    </span>
                                </td>


                                <td>

                                    @if($feeStructure->status === 'Active')

                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle-fill"></i>
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.fees.fee-structures.show', $feeStructure) }}"
                                            class="action-btn action-view"
                                            title="View"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <a
                                            href="{{ route('admin.fees.fee-structures.edit', $feeStructure) }}"
                                            class="action-btn action-edit"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        <form
                                            action="{{ route('admin.fees.fee-structures.destroy', $feeStructure) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this fee structure?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn action-delete"
                                                title="Delete"
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
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <div class="empty-state-title">
                        No Fee Structures Found
                    </div>

                    <div class="empty-state-text">
                        No fee structures match your current filters.
                    </div>

                    <a
                        href="{{ route('admin.fees.fee-structures.create') }}"
                        class="btn-add"
                    >
                        <i class="bi bi-plus-lg"></i>
                        Create Fee Structure
                    </a>

                </div>

            @endif

        </div>


        @if($feeStructures->hasPages())

            <div class="pagination-wrapper">
                {{ $feeStructures->links() }}
            </div>

        @endif

    </div>

</div>

@endsection