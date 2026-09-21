@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="page-title mb-1">Librarian Profile</h2>
            <p class="page-subtitle mb-0">
                View librarian personal and employment information
            </p>
        </div>

        <a href="{{ route('admin.library.librarian.index') }}"
           class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Librarians
        </a>

    </div>


    {{-- Profile Header --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="profile-header">

            {{-- Profile Photo --}}
            <div class="profile-photo">

                @if($librarian->profile_photo)

                    <img src="{{ asset('storage/' . $librarian->profile_photo) }}"
                         alt="{{ $librarian->name }}">

                @else

                    <div class="profile-placeholder">
                        <i class="fas fa-user"></i>
                    </div>

                @endif

            </div>


            {{-- Main Profile Info --}}
            <div class="profile-info">

                <h3 class="mb-1">
                    {{ $librarian->name }}
                </h3>

                <div class="designation mb-2">
                    <i class="fas fa-book-reader me-2"></i>
                    {{ $librarian->designation }}
                </div>

                <div class="profile-meta">

                    <span>
                        <i class="fas fa-id-card me-1"></i>
                        {{ $librarian->staff_id }}
                    </span>

                    @if($librarian->department)

                        <span>
                            <i class="fas fa-building me-1"></i>
                            {{ $librarian->department }}
                        </span>

                    @endif

                    @if($librarian->status === 'Active')

                        <span class="status-badge active">
                            <i class="fas fa-circle"></i>
                            Active
                        </span>

                    @else

                        <span class="status-badge inactive">
                            <i class="fas fa-circle"></i>
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Information Cards --}}
    <div class="row g-4">

        {{-- Personal Information --}}
        <div class="col-lg-6">

            <div class="card info-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 px-4 pt-4">

                    <h5 class="section-title mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Personal Information
                    </h5>

                </div>

                <div class="card-body px-4">

                    <div class="info-row">
                        <span>Full Name</span>
                        <strong>{{ $librarian->name }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Staff ID</span>
                        <strong>{{ $librarian->staff_id }}</strong>
                    </div>

                    <div class="info-row">
                        <span>Gender</span>
                        <strong>
                            {{ $librarian->gender ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Date of Birth</span>
                        <strong>
                            {{ $librarian->date_of_birth
                                ? $librarian->date_of_birth->format('d M Y')
                                : 'Not provided' }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Contact Information --}}
        <div class="col-lg-6">

            <div class="card info-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 px-4 pt-4">

                    <h5 class="section-title mb-0">
                        <i class="fas fa-address-book text-primary me-2"></i>
                        Contact Information
                    </h5>

                </div>

                <div class="card-body px-4">

                    <div class="info-row">
                        <span>Phone</span>
                        <strong>
                            {{ $librarian->phone ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Email</span>
                        <strong>
                            {{ $librarian->email ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Address</span>
                        <strong class="text-end">
                            {{ $librarian->address ?? 'Not provided' }}
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Employment Information --}}
        <div class="col-lg-6">

            <div class="card info-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 px-4 pt-4">

                    <h5 class="section-title mb-0">
                        <i class="fas fa-briefcase text-primary me-2"></i>
                        Employment Information
                    </h5>

                </div>

                <div class="card-body px-4">

                    <div class="info-row">
                        <span>Designation</span>
                        <strong>
                            {{ $librarian->designation }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Department</span>
                        <strong>
                            {{ $librarian->department ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Qualification</span>
                        <strong>
                            {{ $librarian->qualification ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Joining Date</span>
                        <strong>
                            {{ $librarian->joining_date
                                ? $librarian->joining_date->format('d M Y')
                                : 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-row">
                        <span>Status</span>

                        @if($librarian->status === 'Active')

                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Library Role --}}
        <div class="col-lg-6">

            <div class="card info-card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 px-4 pt-4">

                    <h5 class="section-title mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Library Role
                    </h5>

                </div>

                <div class="card-body px-4">

                    <div class="role-box">

                        <div class="role-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Librarian
                            </h6>

                            <p class="text-muted mb-0">
                                Responsible for managing books,
                                issues, returns and library records.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="mt-4 d-flex gap-2">

        <a href="{{ route('admin.other-staff.edit', $librarian->id) }}"
           class="btn btn-primary px-4">

            <i class="fas fa-edit me-2"></i>
            Edit Details

        </a>

        <a href="{{ route('admin.library.librarian.index') }}"
           class="btn btn-light border px-4">

            <i class="fas fa-arrow-left me-2"></i>
            Back

        </a>

    </div>

</div>


<style>

    /* Page Typography */

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #12233f;
    }

    .page-subtitle {
        font-size: 14px;
        color: #718096;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
    }


    /* Profile Header */

    .profile-header {
        padding: 28px 32px;
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        color: white;
        display: flex;
        align-items: center;
        gap: 24px;
    }


    .profile-photo img,
    .profile-placeholder {
        width: 105px;
        height: 105px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,.9);
        box-shadow: 0 6px 20px rgba(0,0,0,.18);
    }


    .profile-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #1976d2;
        font-size: 36px;
    }


    .profile-info h3 {
        font-size: 24px;
        font-weight: 700;
    }


    .designation {
        font-size: 15px;
    }


    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        font-size: 13px;
    }


    /* Status */

    .status-badge {
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
    }

    .status-badge.active {
        background: rgba(25, 135, 84, .9);
    }

    .status-badge.inactive {
        background: rgba(108, 117, 125, .9);
    }

    .status-badge i {
        font-size: 6px;
        margin-right: 4px;
    }


    /* Information */

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #eef1f5;
        font-size: 14px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row > span:first-child {
        color: #718096;
        font-weight: 500;
    }

    .info-row strong {
        color: #1f2937;
        text-align: right;
        max-width: 65%;
        font-weight: 600;
    }


    /* Library Role */

    .role-box {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border-radius: 14px;
        background: #f5f8ff;
    }

    .role-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8f0ff;
        color: #0d6efd;
        font-size: 20px;
    }


    /* Mobile */

    @media (max-width: 576px) {

        .page-title {
            font-size: 24px;
        }

        .profile-header {
            padding: 24px 20px;
            flex-direction: column;
            text-align: center;
        }

        .profile-meta {
            justify-content: center;
        }

        .info-row {
            flex-direction: column;
            gap: 4px;
        }

        .info-row strong {
            text-align: left;
            max-width: 100%;
        }

    }

</style>

@endsection