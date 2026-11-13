@extends('layouts.app')

@section('title', 'Government Scheme Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Government Scheme Details</h4>
            <p class="text-muted mb-0">
                View scheme information and linked kit templates.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.government-schemes.edit', $governmentScheme) }}"
               class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>

            <a href="{{ route('admin.government-schemes.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Scheme Information --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-building me-2"></i>
                Scheme Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Scheme Name --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Scheme Name
                    </div>

                    <div class="fw-semibold">
                        {{ $governmentScheme->scheme_name }}
                    </div>

                </div>


                {{-- Scheme Code --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Scheme Code
                    </div>

                    <div class="fw-semibold">
                        {{ $governmentScheme->scheme_code ?: '—' }}
                    </div>

                </div>


                {{-- Government --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Government
                    </div>

                    <div>
                        {{ $governmentScheme->government ?: '—' }}
                    </div>

                </div>


                {{-- Department --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Department
                    </div>

                    <div>
                        {{ $governmentScheme->department ?: '—' }}
                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Academic Year
                    </div>

                    <div>
                        {{ $governmentScheme->academic_year }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    @if ($governmentScheme->status === 'active')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Inactive
                        </span>

                    @endif

                </div>


                {{-- Start Date --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Start Date
                    </div>

                    <div>
                        {{ $governmentScheme->start_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                {{-- End Date --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        End Date
                    </div>

                    <div>
                        {{ $governmentScheme->end_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Description
                    </div>

                    <div>
                        {{ $governmentScheme->description ?: 'No description added.' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Kit Templates --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>
                Kit Templates
            </h5>

            <span class="badge bg-primary">
                {{ $governmentScheme->kitTemplates->count() }}
            </span>

        </div>

        <div class="card-body p-0">

            @if ($governmentScheme->kitTemplates->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-3">#</th>
                                <th>Kit Name</th>
                                <th>Class</th>
                                <th>Academic Year</th>
                                <th>Items</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($governmentScheme->kitTemplates as $index => $template)

                                <tr>

                                    <td class="ps-3">
                                        {{ $index + 1 }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $template->kit_name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $template->class ?: 'All Classes' }}
                                    </td>

                                    <td>
                                        {{ $template->academic_year }}
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $template->items_count }}
                                        </span>
                                    </td>

                                    <td>

                                        @if ($template->status === 'active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-box-seam fs-1 text-muted"></i>

                    <h6 class="mt-3">
                        No Kit Templates
                    </h6>

                    <p class="text-muted mb-0">
                        No kit templates are linked to this government scheme yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection