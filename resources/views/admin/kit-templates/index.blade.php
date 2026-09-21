```blade
@extends('layouts.app')

@section('title', 'Government Kit Templates')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Government Kit Templates
            </h3>

            <p class="text-muted mb-0">
                Manage government-provided student supply kits and their standard items.
            </p>
        </div>

        <a href="{{ route('admin.kit-templates.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Create Government Kit
        </a>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-primary bg-opacity-10
                                text-primary p-3 me-3">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Total Government Kits
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $stats['total'] ?? 0 }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-success bg-opacity-10
                                text-success p-3 me-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Active Kits
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $stats['active'] ?? 0 }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-warning bg-opacity-10
                                text-warning p-3 me-3">
                        <i class="bi bi-pause-circle fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Inactive Kits
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $stats['inactive'] ?? 0 }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-info bg-opacity-10
                                text-info p-3 me-3">
                        <i class="bi bi-boxes fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Total Kit Items
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $stats['items'] ?? 0 }}
                        </h4>
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

            <form method="GET"
                  action="{{ route('admin.kit-templates.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search Government Kit
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Kit name, class or academic year"
                                   value="{{ request('search') }}">

                        </div>

                    </div>


                    {{-- Class --}}
                    <div class="col-lg-2 col-md-3">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select name="class"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class }}"
                                    {{ request('class') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-lg-2 col-md-3">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                class="form-select">

                            <option value="">
                                All Years
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2 col-md-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">

                            <i class="bi bi-funnel me-1"></i>
                            Filter

                        </button>

                        <a href="{{ route('admin.kit-templates.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        GOVERNMENT KIT TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        Government Student Supply Kits
                    </h5>

                    <small class="text-muted">
                        Standard kits received from the government for student distribution.
                    </small>
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $kitTemplates->total() }} Kits
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($kitTemplates->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Government Kit
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Items
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($kitTemplates as $kitTemplate)

                                <tr>

                                    {{-- Serial --}}
                                    <td class="ps-4 text-muted">
                                        {{ $kitTemplates->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Kit Name --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="rounded-3 bg-primary bg-opacity-10
                                                        text-primary p-2 me-3">

                                                <i class="bi bi-box-seam"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $kitTemplate->kit_name }}
                                                </div>

                                                @if($kitTemplate->description)

                                                    <small class="text-muted">
                                                        {{ Str::limit($kitTemplate->description, 70) }}
                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Class --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">
                                            Class {{ $kitTemplate->class }}
                                        </span>

                                    </td>


                                    {{-- Academic Year --}}
                                    <td>
                                        {{ $kitTemplate->academic_year ?: '—' }}
                                    </td>


                                    {{-- Items --}}
                                    <td>

                                        <span class="badge bg-info bg-opacity-10
                                                     text-info border border-info">

                                            <i class="bi bi-boxes me-1"></i>

                                            {{ $kitTemplate->items_count ?? $kitTemplate->items->count() }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($kitTemplate->status === 'active')

                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                <i class="bi bi-pause-circle me-1"></i>
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-light border"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.kit-templates.show', $kitTemplate) }}">

                                                        <i class="bi bi-eye text-primary me-2"></i>
                                                        View Government Kit

                                                    </a>

                                                </li>

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.kit-templates.edit', $kitTemplate) }}">

                                                        <i class="bi bi-pencil text-warning me-2"></i>
                                                        Edit Government Kit

                                                    </a>

                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <form method="POST"
                                                          action="{{ route('admin.kit-templates.destroy', $kitTemplate) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this government kit template?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>
                                                            Delete

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

            @else

                {{-- Empty State --}}
                <div class="text-center py-5 px-3">

                    <div class="rounded-circle bg-light d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:80px;height:80px;">

                        <i class="bi bi-box-seam text-muted fs-1"></i>

                    </div>

                    <h5 class="fw-bold">
                        No Government Kit Templates Found
                    </h5>

                    <p class="text-muted mb-4">

                        No government student supply kits have been created yet.
                        Create a kit template to define the items distributed to students.

                    </p>

                    <a href="{{ route('admin.kit-templates.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>
                        Create Government Kit

                    </a>

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if($kitTemplates->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $kitTemplates->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
    SMALL PAGE STYLES
============================================================= --}}
<style>

    .card {
        border-radius: 14px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        font-size: 14px;
    }

    .dropdown-menu {
        border: 0;
        border-radius: 10px;
    }

    .dropdown-item {
        padding: 9px 14px;
    }

    .dropdown-item:hover {
        background-color: #f5f7fb;
    }

</style>

@endsection
