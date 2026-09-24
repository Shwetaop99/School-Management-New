@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Librarians</h2>
            <p class="text-muted mb-0">
                View and manage all librarians
            </p>
        </div>

        <a href="{{ route('admin.other-staff.create') }}"
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add Staff
        </a>
    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="fas fa-users"></i>
                    </div>

                    <div class="ms-3">
                        <div class="text-muted small">Total Librarians</div>
                        <h3 class="fw-bold mb-0">
                            {{ $librarians->total() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="fas fa-user-check"></i>
                    </div>

                    <div class="ms-3">
                        <div class="text-muted small">Active Librarians</div>
                        <h3 class="fw-bold mb-0">
                            {{ $librarians->where('status', 'Active')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-secondary-subtle text-secondary">
                        <i class="fas fa-user-slash"></i>
                    </div>

                    <div class="ms-3">
                        <div class="text-muted small">Inactive Librarians</div>
                        <h3 class="fw-bold mb-0">
                            {{ $librarians->where('status', 'Inactive')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Librarian List --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">
                        All Librarians
                    </h5>

                    <p class="text-muted small mb-0">
                        Librarians added through Other Staff
                    </p>
                </div>

                <i class="fas fa-book-reader text-primary fs-4"></i>
            </div>
        </div>


        <div class="card-body p-0">

            @if($librarians->count())

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Librarian</th>
                                <th>Staff ID</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th class="text-end px-4">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($librarians as $librarian)

                                <tr>

                                    {{-- Librarian --}}
                                    <td class="px-4">

                                        <div class="d-flex align-items-center">

                                            @if($librarian->profile_photo)

                                                <img src="{{ asset('storage/' . $librarian->profile_photo) }}"
                                                     alt="{{ $librarian->name }}"
                                                     class="rounded-circle me-3"
                                                     style="width:48px;height:48px;object-fit:cover;">

                                            @else

                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                                     style="width:48px;height:48px;">
                                                    <i class="fas fa-user"></i>
                                                </div>

                                            @endif

                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $librarian->name }}
                                                </div>

                                                <div class="text-muted small">
                                                    {{ $librarian->designation }}
                                                </div>
                                            </div>

                                        </div>

                                    </td>


                                    {{-- Staff ID --}}
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $librarian->staff_id }}
                                        </span>
                                    </td>


                                    {{-- Phone --}}
                                    <td>
                                        {{ $librarian->phone ?? '—' }}
                                    </td>


                                    {{-- Department --}}
                                    <td>
                                        {{ $librarian->department ?? '—' }}
                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($librarian->status === 'Active')

                                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end px-4">

                                        <a href="{{ route('admin.library.librarian.show', $librarian) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View Profile
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($librarians->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $librarians->links() }}
                    </div>
                @endif


            @else

                {{-- Empty State --}}
                <div class="text-center py-5 px-4">

                    <div class="mb-3">
                        <i class="fas fa-user-tie fa-4x text-muted"></i>
                    </div>

                    <h4 class="fw-bold">
                        No Librarians Found
                    </h4>

                    <p class="text-muted mb-4">
                        No staff member has been added with the
                        designation <strong>Librarian</strong>.
                    </p>

                    <a href="{{ route('admin.other-staff.create') }}"
                       class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add Librarian
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


<style>

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Page heading */
    h2 {
        font-size: 28px !important;
    }

    /* Card headings */
    .card h5 {
        font-size: 15px !important;
    }

    /* Table text */
    .table {
        font-size: 14px;
    }

    .table th {
        font-size: 13px;
    }

    .table td {
        font-size: 14px;
    }

    .table > :not(caption) > * > * {
        padding-top: 13px;
        padding-bottom: 13px;
    }

    .table tbody tr {
        transition: 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8faff;
    }

</style>

@endsection