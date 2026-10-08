@extends('layouts.app')

@section('title', 'Government Schemes')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>
            <h4 class="mb-1">
                Government Schemes
            </h4>

            <p class="text-muted mb-0">
                Manage government student supply schemes
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- Kit Distribution --}}
            <a href="{{ route('admin.student-supply-kits.distribution.create') }}"
               class="btn btn-primary">
                <i class="bi bi-box-seam me-1"></i>
                Kit Distribution
            </a>

            {{-- Add Scheme --}}
            <a href="{{ route('admin.government-schemes.create') }}"
               class="btn btn-outline-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Scheme
            </a>

        </div>
    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.government-schemes.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Search scheme, code, government...">

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                class="form-select">

                            <option value="">
                                All Academic Years
                            </option>

                            @foreach($academicYears as $year)

                                <option value="{{ $year }}"
                                    @selected(request('academic_year') == $year)>

                                    {{ $year }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

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


                    {{-- Search Button --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bi bi-search me-1"></i>
                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SCHEMES TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Scheme
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Academic Year
                            </th>

                            <th>
                                Government
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Kits
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($schemes as $scheme)

                        <tr>

                            {{-- Number --}}
                            <td class="px-3">

                                {{ $schemes->firstItem() + $loop->index }}

                            </td>


                            {{-- Scheme --}}
                            <td>

                                <strong>
                                    {{ $scheme->scheme_name }}
                                </strong>

                            </td>


                            {{-- Code --}}
                            <td>

                                {{ $scheme->scheme_code ?? '—' }}

                            </td>


                            {{-- Academic Year --}}
                            <td>

                                {{ $scheme->academic_year }}

                            </td>


                            {{-- Government --}}
                            <td>

                                {{ $scheme->government ?? '—' }}

                            </td>


                            {{-- Department --}}
                            <td>

                                {{ $scheme->department ?? '—' }}

                            </td>


                            {{-- Kits --}}
                            <td>

                                <span class="badge bg-info">

                                    {{ $scheme->kit_templates_count }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($scheme->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end px-3">

                                <div class="dropdown">

                                    <button type="button"
                                            class="btn btn-sm btn-light"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                        <i class="bi bi-three-dots-vertical"></i>

                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">

                                        {{-- View --}}
                                        <li>

                                            <a class="dropdown-item"
                                               href="{{ route('admin.government-schemes.show', $scheme) }}">

                                                <i class="bi bi-eye me-2"></i>
                                                View

                                            </a>

                                        </li>


                                        {{-- Edit --}}
                                        <li>

                                            <a class="dropdown-item"
                                               href="{{ route('admin.government-schemes.edit', $scheme) }}">

                                                <i class="bi bi-pencil me-2"></i>
                                                Edit

                                            </a>

                                        </li>


                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>


                                        {{-- Delete --}}
                                        <li>

                                            <form method="POST"
                                                  action="{{ route('admin.government-schemes.destroy', $scheme) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this scheme?');">

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

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>

                                    No government schemes found.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($schemes->hasPages())

            <div class="card-footer bg-white">

                {{ $schemes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection