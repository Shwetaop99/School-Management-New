
@extends('super-admin.layouts.app')

@section('title', 'School Reports')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>School Reports</h2>
            <p class="text-muted">
                View and analyze registered schools across the platform.
            </p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Schools</h6>
                    <h3>{{ number_format($totalSchools) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Active Schools</h6>
                    <h3 class="text-success">
                        {{ number_format($activeSchools) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Inactive Schools</h6>
                    <h3 class="text-danger">
                        {{ number_format($inactiveSchools) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total School Users</h6>
                    <h3>{{ number_format($totalUsers) }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <h5 class="mb-0">Filter School Reports</h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('super-admin.reports.schools') }}">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Search School</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="School name, code, city..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>

                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>School Type</label>
                        <select name="school_type" class="form-control">
                            <option value="">All Types</option>

                            @foreach($schoolTypes as $type)
                                <option value="{{ $type }}"
                                    @selected(request('school_type') === $type)>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Education Board</label>
                        <select name="education_board" class="form-control">
                            <option value="">All Boards</option>

                            @foreach($educationBoards as $board)
                                <option value="{{ $board }}"
                                    @selected(request('education_board') === $board)>
                                    {{ $board }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>From Date</label>
                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>To Date</label>
                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                    </div>

                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>

                    <a href="{{ route('super-admin.reports.schools') }}"
                       class="btn btn-secondary">
                        Reset
                    </a>
                </div>

            </form>

        </div>
    </div>

    {{-- School Report Table --}}
    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">Registered Schools</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>School Name</th>
                            <th>School Code</th>
                            <th>Location</th>
                            <th>School Type</th>
                            <th>Total Users</th>
                            <th>Active Users</th>
                            <th>Inactive Users</th>
                            <th>Status</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($schools as $school)

                            <tr>
                                <td>
                                    {{ $schools->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>{{ $school->name }}</strong>
                                </td>

                                <td>{{ $school->school_code }}</td>

                                <td>
                                    {{ $school->city ?? 'N/A' }},
                                    {{ $school->state ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $school->school_type ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $school->total_users }}
                                </td>

                                <td>
                                    <span class="text-success">
                                        {{ $school->active_users }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-danger">
                                        {{ $school->inactive_users }}
                                    </span>
                                </td>

                                <td>
                                    @if($school->status === 'active')
                                        <span class="badge badge-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($school->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $school->created_at
                                        ? $school->created_at->format('d M Y')
                                        : 'N/A' }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    No school records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $schools->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
