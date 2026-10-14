
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Fee Report</h2>
            <p class="text-muted mb-0">
                Student-wise fee collection and outstanding balances
            </p>
        </div>

        <button type="button" onclick="window.print()"
                class="btn btn-outline-secondary">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Total Fees Assigned</div>
                    <h3 class="fw-bold mt-2">
                        ₹{{ number_format($totalAssigned, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Total Amount Collected</div>
                    <h3 class="fw-bold text-success mt-2">
                        ₹{{ number_format($totalPaid, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted">Outstanding Balance</div>
                    <h3 class="fw-bold text-danger mt-2">
                        ₹{{ number_format($totalBalance, 2) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Filter Fee Records</h5>
        </div>

        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.reports.fees') }}">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Search Student</label>
                        <input type="text" name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Student ID or name">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year" class="form-select">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fee Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(['paid', 'partial', 'pending', 'overdue'] as $status)
                                <option value="{{ $status }}"
                                    @selected(request('status') == $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Due From</label>
                        <input type="date" name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Due To</label>
                        <input type="date" name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>

                        <a href="{{ route('admin.reports.fees') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Report table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between">
            <h5 class="mb-0">Student Fee Details</h5>
            <span class="text-muted">
                {{ $fees->total() }} records
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Academic Year</th>
                            <th>Fee Structure</th>
                            <th>Total Fee</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($fees as $fee)
                            <tr>
                                <td>
                                    {{ $fees->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $fee->student->first_name ?? 'N/A' }}
                                        {{ $fee->student->last_name ?? '' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $fee->student->student_id ?? '' }}
                                    </small>
                                </td>

                                <td>{{ $fee->academic_year ?? 'N/A' }}</td>

                                <td>
                                    {{ $fee->feeStructure->structure_name ?? 'N/A' }}
                                </td>

                                <td>
                                    ₹{{ number_format((float) $fee->total_amount, 2) }}
                                </td>

                                <td class="text-success">
                                    ₹{{ number_format((float) $fee->paid_amount, 2) }}
                                </td>

                                <td class="text-danger">
                                    ₹{{ number_format((float) $fee->balance_amount, 2) }}
                                </td>

                                <td>
                                    {{ $fee->due_date?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($fee->status ?? 'pending');
                                        $badge = match($status) {
                                            'paid' => 'success',
                                            'partial' => 'warning',
                                            'overdue' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9"
                                    class="text-center py-5 text-muted">
                                    No fee records found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            {{ $fees->links() }}
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, nav, .btn, form, .card-footer {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .container-fluid {
        width: 100% !important;
    }
}
</style>
@endsection
