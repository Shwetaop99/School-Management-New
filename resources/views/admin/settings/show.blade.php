@extends('layouts.app')

@section('title', 'School Profile')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-building me-2"></i>
                School Profile
            </h4>

            <p class="text-muted mb-0">
                View your school information and profile details.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.settings.edit', $schoolSetting) }}"
               class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Profile
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- School Profile Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="row g-4">

                {{-- Logo --}}
                <div class="col-md-3 text-center">

                    <div class="mb-3">

                        @if($schoolSetting->logo)

                            <img src="{{ asset('storage/' . $schoolSetting->logo) }}"
                                 alt="{{ $schoolSetting->school_name }}"
                                 class="img-fluid rounded border p-2"
                                 style="
                                    width: 180px;
                                    height: 180px;
                                    object-fit: contain;
                                 ">

                        @else

                            <div class="border rounded d-flex align-items-center justify-content-center mx-auto"
                                 style="
                                    width: 180px;
                                    height: 180px;
                                    background: #f8f9fa;
                                 ">

                                <div class="text-muted text-center">
                                    <i class="bi bi-building fs-1"></i>
                                    <div>No Logo</div>
                                </div>

                            </div>

                        @endif

                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $schoolSetting->school_name }}
                    </h5>

                    @if($schoolSetting->school_code)
                        <small class="text-muted">
                            School Code:
                            {{ $schoolSetting->school_code }}
                        </small>
                    @endif

                </div>


                {{-- School Information --}}
                <div class="col-md-9">

                    <div class="row g-3">

                        {{-- School Name --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>School Name</label>
                                <div>
                                    {{ $schoolSetting->school_name ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- School Code --}}
                        <div class="col-md-3">
                            <div class="info-box">
                                <label>School Code</label>
                                <div>
                                    {{ $schoolSetting->school_code ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- UDISE Code --}}
                        <div class="col-md-3">
                            <div class="info-box">
                                <label>UDISE Code</label>
                                <div>
                                    {{ $schoolSetting->udise_code ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Address --}}
                        <div class="col-md-12">
                            <div class="info-box">
                                <label>Address</label>
                                <div style="white-space: pre-line;">
                                    {{ $schoolSetting->address ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- City --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>City</label>
                                <div>
                                    {{ $schoolSetting->city ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- District --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>District</label>
                                <div>
                                    {{ $schoolSetting->district ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- State --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>State</label>
                                <div>
                                    {{ $schoolSetting->state ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Pincode --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>Pincode</label>
                                <div>
                                    {{ $schoolSetting->pincode ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Phone --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>Phone</label>
                                <div>
                                    {{ $schoolSetting->phone ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Email --}}
                        <div class="col-md-4">
                            <div class="info-box">
                                <label>Email</label>
                                <div>
                                    {{ $schoolSetting->email ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Website --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>Website</label>

                                @if($schoolSetting->website)

                                    <div>
                                        <a href="{{ $schoolSetting->website }}"
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            {{ $schoolSetting->website }}
                                        </a>
                                    </div>

                                @else

                                    <div>—</div>

                                @endif

                            </div>
                        </div>


                        {{-- Principal --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>Principal Name</label>
                                <div>
                                    {{ $schoolSetting->principal_name ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Established Year --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>Established Year</label>
                                <div>
                                    {{ $schoolSetting->established_year ?: '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Created --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>Profile Created</label>
                                <div>
                                    {{ $schoolSetting->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </div>
                            </div>
                        </div>


                        {{-- Updated --}}
                        <div class="col-md-6">
                            <div class="info-box">
                                <label>Last Updated</label>
                                <div>
                                    {{ $schoolSetting->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

    .info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px 16px;
        height: 100%;
    }

    .info-box label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 5px;
    }

    .info-box div {
        font-size: 15px;
        font-weight: 500;
        color: #212529;
        word-break: break-word;
    }

</style>

@endsection