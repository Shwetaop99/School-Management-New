@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

{{-- =========================================================
     HEADER
========================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-building-fill text-primary me-2"></i>
            School Profile
        </h3>

        <p class="text-muted mb-0">
            Manage school information.
        </p>
    </div>

    {{-- ADD NEW SCHOOL --}}
    <a href="{{ route('admin.settings.create') }}"
       class="btn btn-primary">

        <i class="bi bi-plus-circle me-1"></i>
        Add New School

    </a>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
========================================================== --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show shadow-sm">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     ERROR MESSAGE
========================================================== --}}

@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show shadow-sm">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     SCHOOL EXISTS
========================================================== --}}

@if($school)

    <div class="row">

        <div class="col-xl-10 col-xxl-9">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">


                {{-- =================================================
                     SCHOOL HEADER
                ================================================== --}}

                <div class="school-card-header">

                    <div class="d-flex align-items-center gap-4">

                        <div class="school-logo">

                            <img
                                src="{{ $school->logo
                                    ? asset('storage/' . $school->logo)
                                    : asset('images/gurukullogo.png') }}"
                                alt="School Logo"
                                onerror="this.src='{{ asset('images/gurukullogo.png') }}'"
                            >

                        </div>


                        <div>

                            <h2 class="fw-bold mb-1">
                                {{ $school->school_name }}
                            </h2>

                            <div class="text-muted">

                                {{ $school->city }}

                                @if($school->district)
                                    , {{ $school->district }}
                                @endif

                                @if($school->state)
                                    , {{ $school->state }}
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BODY
                ================================================== --}}

                <div class="card-body p-4">

                    <div class="row g-4">


                        {{-- SCHOOL CODE --}}

                        <div class="col-md-4">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-building"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        School Code
                                    </small>

                                    <div class="fw-semibold">
                                        {{ $school->school_code ?: 'Not available' }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- UDISE CODE --}}

                        <div class="col-md-4">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-upc-scan"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        UDISE Code
                                    </small>

                                    <div class="fw-semibold">
                                        {{ $school->udise_code ?: 'Not available' }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- PRINCIPAL --}}

                        <div class="col-md-4">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>

                                <div>

                                    <small class="text-muted">
                                        Principal
                                    </small>

                                    <div class="fw-semibold">
                                        {{ $school->principal_name ?: 'Not available' }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ADDRESS --}}

                        <div class="col-md-6">

                            <div class="detail-card">

                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    Address
                                </h6>

                                <p class="text-muted mb-0">

                                    {{ $school->address ?: 'Not available' }}

                                    @if($school->city)
                                        <br>{{ $school->city }}
                                    @endif

                                    @if($school->district)
                                        , {{ $school->district }}
                                    @endif

                                    @if($school->state)
                                        , {{ $school->state }}
                                    @endif

                                    @if($school->pincode)
                                        - {{ $school->pincode }}
                                    @endif

                                </p>

                            </div>

                        </div>


                        {{-- CONTACT --}}

                        <div class="col-md-6">

                            <div class="detail-card">

                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    Contact Information
                                </h6>


                                @if($school->phone)

                                    <div class="mb-2">
                                        <i class="bi bi-phone me-2"></i>
                                        {{ $school->phone }}
                                    </div>

                                @endif


                                @if($school->email)

                                    <div class="mb-2">
                                        <i class="bi bi-envelope me-2"></i>
                                        {{ $school->email }}
                                    </div>

                                @endif


                                @if($school->website)

                                    <div>

                                        <i class="bi bi-globe me-2"></i>

                                        <a href="{{ $school->website }}"
                                           target="_blank"
                                           rel="noopener">

                                            {{ $school->website }}

                                        </a>

                                    </div>

                                @endif


                                @if(!$school->phone && !$school->email && !$school->website)

                                    <span class="text-muted">
                                        No contact information available.
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- SCHOOL INFORMATION --}}

                        <div class="col-md-6">

                            <div class="detail-card">

                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    School Information
                                </h6>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <small class="text-muted">
                                            Established Year
                                        </small>

                                        <div class="fw-semibold">
                                            {{ $school->established_year ?: 'Not available' }}
                                        </div>

                                    </div>


                                    <div class="col-sm-6">

                                        <small class="text-muted">
                                            State
                                        </small>

                                        <div class="fw-semibold">
                                            {{ $school->state ?: 'Not available' }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                     {{-- PROFILE ACTIONS --}}
<div class="col-md-6">

    <div class="detail-card">

        <h6 class="fw-bold mb-3">
            <i class="bi bi-gear text-primary me-2"></i>
            Profile Actions
        </h6>

        <div class="d-flex flex-wrap gap-2">

            {{-- EDIT SCHOOL --}}
            <a href="{{ route('admin.settings.edit', $school->id) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil-square me-1"></i>
                Edit School

            </a>


            {{-- DELETE SCHOOL --}}
            <form action="{{ route('admin.settings.destroy', $school->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirmDeleteSchool();">

                @csrf

                @method('DELETE')

                <button type="submit"
                        class="btn btn-outline-danger">

                    <i class="bi bi-trash me-1"></i>
                    Delete School

                </button>

            </form>

        </div>

        <div class="mt-3">

            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>

                Delete the current school profile if you want to
                register a different school.
            </small>

        </div>

    </div>

</div>


{{-- =========================================================
     NO SCHOOL
========================================================== --}}

@else

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body text-center py-5">

            <div class="empty-icon mb-3">

                <i class="bi bi-building"></i>

            </div>

            <h4 class="fw-bold">
                No School Profile Found
            </h4>

            <p class="text-muted mb-4">

                Add your school's profile to use school information
                throughout certificates, reports and other modules.

            </p>

            <a href="{{ route('admin.settings.create') }}"
               class="btn btn-primary px-4">

                <i class="bi bi-plus-circle me-1"></i>
                Add School

            </a>

        </div>

    </div>

@endif

</div>

<style>

.school-card-header {
    padding: 30px;
    background: linear-gradient(135deg, #f5f9ff, #ffffff);
    border-bottom: 1px solid #edf1f5;
}

.school-logo {
    width: 110px;
    height: 110px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
}

.school-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}

.info-box {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px;
    border: 1px solid #edf1f5;
    border-radius: 14px;
    background: #fff;
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: #eef5ff;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.detail-card {
    height: 100%;
    padding: 20px;
    border: 1px solid #edf1f5;
    border-radius: 14px;
    background: #fff;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #eef5ff;
    color: #0d6efd;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 35px;
}

</style>

@endsection
