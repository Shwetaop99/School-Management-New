@extends('layouts.app')

@section('title', 'Staff Report')
@section('page-title', 'Staff Report')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Staff Report</h2>
            <p class="text-muted mb-0">
                Complete details of {{ $otherStaff->name }}
            </p>
        </div>

        <a href="{{ route('reports.staff') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>


    <!-- Staff Information -->
    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-person-badge"></i>
                Staff Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <!-- Profile Photo -->
                <div class="col-md-3 text-center mb-3">

                    @if($otherStaff->profile_photo)

                        <img
                            src="{{ asset('storage/' . $otherStaff->profile_photo) }}"
                            alt="Staff Photo"
                            class="img-fluid rounded"
                            style="width:150px;height:150px;object-fit:cover;"
                        >

                    @else

                        <div
                            class="bg-light rounded d-flex align-items-center justify-content-center mx-auto"
                            style="width:150px;height:150px;"
                        >
                            <i class="bi bi-person fs-1 text-secondary"></i>
                        </div>

                    @endif

                </div>


                <!-- Details -->
                <div class="col-md-9">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <strong>Staff ID</strong>
                            <div>{{ $otherStaff->staff_id ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Name</strong>
                            <div>{{ $otherStaff->name ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Designation</strong>
                            <div>{{ $otherStaff->designation ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Department</strong>
                            <div>{{ $otherStaff->department ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Qualification</strong>
                            <div>{{ $otherStaff->qualification ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Gender</strong>
                            <div>{{ $otherStaff->gender ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Phone</strong>
                            <div>{{ $otherStaff->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Email</strong>
                            <div>{{ $otherStaff->email ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Date of Birth</strong>
                            <div>
                                {{ $otherStaff->date_of_birth?->format('d M Y') ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Joining Date</strong>
                            <div>
                                {{ $otherStaff->joining_date?->format('d M Y') ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Status</strong>
                            <div>{{ $otherStaff->status ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <strong>Address</strong>
                            <div>{{ $otherStaff->address ?? 'N/A' }}</div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection