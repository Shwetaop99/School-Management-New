```blade
@extends('layouts.app')

@section('title', 'Student Kit Distribution')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary"
                    style="width:52px;height:52px;"
                >
                    <i class="bi bi-box-seam-fill fs-4"></i>
                </div>

                <div>

                    <h3 class="fw-bold mb-1">
                        Student Kit Distribution
                    </h3>

                    <p class="text-muted mb-0">
                        Distribute and manage government supplied kits for students
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('admin.student-supply-kits.create') }}"
            class="btn btn-primary px-4"
        >
            <i class="bi bi-plus-circle-fill me-1"></i>
            Distribute Government Kit
        </a>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-4">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small fw-semibold mb-1">
                                Total Distributions
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $stats['total'] ?? 0 }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width:46px;height:46px;"
                        >
                            <i class="bi bi-box-seam-fill fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Issued --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small fw-semibold mb-1">
                                Issued
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                {{ $stats['issued'] ?? 0 }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                            style="width:46px;height:46px;"
                        >
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small fw-semibold mb-1">
                                Pending
                            </div>

                            <div class="fs-3 fw-bold text-warning">
                                {{ $stats['pending'] ?? 0 }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                            style="width:46px;height:46px;"
                        >
                            <i class="bi bi-clock-fill fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small fw-semibold mb-1">
                                Cancelled
                            </div>

                            <div class="fs-3 fw-bold text-danger">
                                {{ $stats['cancelled'] ?? 0 }}
                            </div>

                        </div>

                        <div
                            class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                            style="width:46px;height:46px;"
                        >
                            <i class="bi bi-x-circle-fill fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.student-supply-kits.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Search Student
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Student ID or student name..."
                            >

                        </div>

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select
                            name="academic_year"
                            class="form-select"
                        >

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


                    {{-- Status --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="issued"
                                {{ request('status') === 'issued' ? 'selected' : '' }}
                            >
                                Issued
                            </option>

                            <option
                                value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="cancelled"
                                {{ request('status') === 'cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                            >
                                <i class="bi bi-filter me-1"></i>
                                Filter
                            </button>

                            <a
                                href="{{ route('admin.student-supply-kits.index') }}"
                                class="btn btn-outline-secondary"
                                title="Clear Filters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        DISTRIBUTION TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Government Kit Distributions
                    </h5>

                    <small class="text-muted">
                        Students who have received government supplied kits
                    </small>

                </div>

                <span class="badge bg-light text-dark border">
                    {{ $studentSupplyKits->total() }} Records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($studentSupplyKits->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th
                                    class="ps-4"
                                    style="width:70px;"
                                >
                                    #
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Government Kit
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Distribution Date
                                </th>

                                <th class="text-center">
                                    Items
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th
                                    class="text-center pe-4"
                                    style="width:90px;"
                                >
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($studentSupplyKits as $index => $distribution)

                                @php

                                    $student = $distribution->student;

                                    $studentName = $student
                                        ? collect([
                                            $student->first_name,
                                            $student->middle_name,
                                            $student->last_name
                                        ])->filter()->implode(' ')
                                        : 'Student Deleted';

                                @endphp


                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4 text-muted">

                                        {{ $studentSupplyKits->firstItem() + $index }}

                                    </td>


                                    {{-- Student --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:42px;height:42px;"
                                            >
                                                <i class="bi bi-person-fill"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $studentName }}
                                                </div>

                                                <div class="small text-muted">

                                                    ID:
                                                    {{ $student?->student_id ?? '-' }}

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Class --}}
                                    <td>

                                        @if($student?->class)

                                            <span class="badge bg-light text-dark border">
                                                Class {{ $student->class }}
                                            </span>

                                            @if($student?->section)
                                                <span class="text-muted small">
                                                    / {{ $student->section }}
                                                </span>
                                            @endif

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Government Kit --}}
                                    <td>

                                        @if($distribution->kitTemplate)

                                            <div class="fw-semibold">

                                                {{
                                                    $distribution->kitTemplate->kit_name
                                                }}

                                            </div>

                                            <div class="small text-muted">

                                                Government Kit

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Kit Deleted
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Academic Year --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $distribution->academic_year ?? '-' }}

                                        </span>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        @if($distribution->issue_date)

                                            <div class="fw-semibold">

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $distribution->issue_date
                                                    )->format('d M Y')
                                                }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Items --}}
                                    <td class="text-center">

                                        <span class="badge bg-primary bg-opacity-10 text-primary">

                                            {{ $distribution->items->count() }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        @if($distribution->status === 'issued')

                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Issued
                                            </span>

                                        @elseif($distribution->status === 'pending')

                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>
                                                Pending
                                            </span>

                                        @elseif($distribution->status === 'cancelled')

                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Cancelled
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ ucfirst($distribution->status ?? 'Unknown') }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-center pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                                {{-- View --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.student-supply-kits.show',
                                                            $distribution
                                                        ) }}"
                                                    >
                                                        <i class="bi bi-eye text-primary me-2"></i>
                                                        View Distribution
                                                    </a>

                                                </li>


                                                {{-- Edit --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.student-supply-kits.edit',
                                                            $distribution
                                                        ) }}"
                                                    >
                                                        <i class="bi bi-pencil-square text-warning me-2"></i>
                                                        Edit Distribution
                                                    </a>

                                                </li>


                                                {{-- Print --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.student-supply-kits.print',
                                                            $distribution
                                                        ) }}"
                                                        target="_blank"
                                                    >
                                                        <i class="bi bi-printer text-secondary me-2"></i>
                                                        Print Distribution
                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.student-supply-kits.destroy',
                                                            $distribution
                                                        ) }}"
                                                        onsubmit="return confirm(
                                                            'Are you sure you want to delete this distribution? If it was issued, the stock will be restored.'
                                                        )"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                        >

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete Distribution

                                                        </button>

                                                    </form>

                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    PAGINATION
                ================================================== --}}
                @if($studentSupplyKits->hasPages())

                    <div class="card-footer bg-white border-top">

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                            <div class="text-muted small">

                                Showing
                                <strong>
                                    {{ $studentSupplyKits->firstItem() }}
                                </strong>
                                to
                                <strong>
                                    {{ $studentSupplyKits->lastItem() }}
                                </strong>
                                of
                                <strong>
                                    {{ $studentSupplyKits->total() }}
                                </strong>
                                distributions

                            </div>

                            <div>

                                {{ $studentSupplyKits->links() }}

                            </div>

                        </div>

                    </div>

                @endif

            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div class="text-center py-5 px-3">

                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-secondary mb-3"
                        style="width:80px;height:80px;"
                    >
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>


                    <h5 class="fw-bold">
                        No Student Kit Distributions Found
                    </h5>


                    @if(
                        request('search')
                        || request('academic_year')
                        || request('status')
                    )

                        <p class="text-muted mb-3">
                            No distribution records match your current filters.
                        </p>

                        <a
                            href="{{ route('admin.student-supply-kits.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Clear Filters
                        </a>

                    @else

                        <p class="text-muted mb-3">
                            No government supply kits have been distributed to students yet.
                        </p>

                        <a
                            href="{{ route('admin.student-supply-kits.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Distribute First Government Kit
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
