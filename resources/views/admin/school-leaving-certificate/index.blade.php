@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>
                School Leaving Certificates
            </h3>

            <p class="text-muted mb-0">
                Manage all school leaving certificates.
            </p>
        </div>

        <a
            href="{{ route('admin.school-leaving-certificate.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Create Certificate
        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show shadow-sm"
            role="alert"
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show shadow-sm"
            role="alert"
        >

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
         SEARCH
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.school-leaving-certificate.index') }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-9">

                        <label class="form-label fw-semibold">
                            Search Certificate
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search ?? request('search') }}"
                                class="form-control"
                                placeholder="Search by certificate number, student ID or student name..."
                            >

                        </div>

                    </div>


                    <div class="col-md-3 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary flex-grow-1"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.school-leaving-certificate.index') }}"
                            class="btn btn-outline-secondary"
                            title="Clear Search"
                        >
                            <i class="bi bi-x-lg"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         CERTIFICATE TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-list-ul text-primary me-2"></i>

                    Certificate Records

                </h5>

                <span class="badge bg-light text-dark border">
                    {{ $certificates->total() }} Records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($certificates->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    #
                                </th>

                                <th>
                                    Certificate No.
                                </th>

                                <th>
                                    Student
                                </th>

                                <th>
                                    Student ID
                                </th>

                                <th>
                                    Class
                                </th>

                                <th>
                                    Issue Date
                                </th>

                                <th>
                                    Leaving Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($certificates as $certificate)

                                @php

                                    $student = $certificate->student;

                                    $studentName = $student
                                        ? trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        )
                                        : 'Student Deleted';

                                @endphp

                                <tr>

                                    {{-- Number --}}
                                    <td class="px-3">

                                        {{ $certificates->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Certificate Number --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $certificate->certificate_no }}
                                        </span>

                                    </td>


                                    {{-- Student --}}
                                    <td>

                                        @if($student)

                                            <div class="fw-semibold">
                                                {{ $studentName }}
                                            </div>

                                        @else

                                            <span class="text-danger">
                                                Student Deleted
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Student ID --}}
                                    <td>

                                        @if($student)

                                            <span class="badge bg-light text-dark border">
                                                {{ $student->student_id }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Class --}}
                                    <td>

                                        @if($student)

                                            {{ $student->class ?? '—' }}

                                            @if(!empty($student->section))

                                                <span class="text-muted">
                                                    - {{ $student->section }}
                                                </span>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Issue Date --}}
                                    <td>

                                        @if($certificate->issue_date)

                                            {{ $certificate->issue_date->format('d-m-Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Leaving Date --}}
                                    <td>

                                        @if($certificate->leaving_date)

                                            <span class="fw-semibold">
                                                {{ $certificate->leaving_date->format('d-m-Y') }}
                                            </span>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @switch($certificate->status)

                                            @case('issued')

                                                <span class="badge bg-success">
                                                    Issued
                                                </span>

                                                @break

                                            @case('cancelled')

                                                <span class="badge bg-danger">
                                                    Cancelled
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-warning text-dark">
                                                    Draft
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <div class="btn-group">

                                            {{-- View --}}
                                            <a
                                                href="{{ route(
                                                    'admin.school-leaving-certificate.show',
                                                    $certificate
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>


                                            {{-- Edit --}}
                                            <a
                                                href="{{ route(
                                                    'admin.school-leaving-certificate.edit',
                                                    $certificate
                                                ) }}"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>


                                            {{-- Print --}}
                                            <a
                                                href="{{ route(
                                                    'admin.school-leaving-certificate.print',
                                                    $certificate
                                                ) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-success"
                                                title="Print"
                                            >
                                                <i class="bi bi-printer"></i>
                                            </a>


                                            {{-- Delete --}}
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                                onclick="confirmDelete({{ $certificate->id }})"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>


                                        {{-- Hidden Delete Form --}}
                                        <form
                                            id="delete-form-{{ $certificate->id }}"
                                            action="{{ route(
                                                'admin.school-leaving-certificate.destroy',
                                                $certificate
                                            ) }}"
                                            method="POST"
                                            class="d-none"
                                        >

                                            @csrf

                                            @method('DELETE')

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}
                @if($certificates->hasPages())

                    <div class="p-3 border-top">

                        {{ $certificates->links() }}

                    </div>

                @endif


            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-file-earmark-x text-muted"
                            style="font-size: 3rem;"
                        ></i>

                    </div>

                    <h5 class="fw-bold">
                        No Certificates Found
                    </h5>

                    @if($search)

                        <p class="text-muted mb-3">
                            No certificate matched
                            <strong>"{{ $search }}"</strong>.
                        </p>

                        <a
                            href="{{ route('admin.school-leaving-certificate.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Clear Search
                        </a>

                    @else

                        <p class="text-muted mb-3">
                            No school leaving certificates have been created yet.
                        </p>

                        <a
                            href="{{ route('admin.school-leaving-certificate.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Create First Certificate
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
     DELETE CONFIRMATION
============================================================= --}}
@push('scripts')

<script>

function confirmDelete(id)
{
    const confirmed = confirm(
        'Are you sure you want to delete this School Leaving Certificate?'
    );

    if (confirmed) {

        document
            .getElementById('delete-form-' + id)
            .submit();

    }
}

</script>

@endpush

@endsection