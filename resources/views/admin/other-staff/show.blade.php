@extends('layouts.app')

@section('title', 'Staff Profile')

@section('content')

<style>
    .staff-profile-page {
        padding: 24px;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #172554;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .btn-back {
        background: #fff;
        color: #475569;
        border-color: #dbe3ef;
    }

    .btn-edit {
        background: #2563eb;
        color: #fff;
    }

    .btn-edit:hover {
        background: #1d4ed8;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .profile-top {
        padding: 30px;
        display: flex;
        align-items: center;
        gap: 25px;
        background: linear-gradient(135deg, #eff6ff, #ffffff);
        border-bottom: 1px solid #e5e7eb;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 18px;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .12);
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-placeholder {
        font-size: 45px;
        color: #94a3b8;
    }

    .profile-name {
        margin: 0 0 7px;
        font-size: 25px;
        font-weight: 700;
        color: #172554;
    }

    .profile-designation {
        margin: 0 0 12px;
        color: #475569;
        font-size: 15px;
    }

    .staff-id {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 7px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
    }

    .section-title {
        padding: 17px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        font-size: 16px;
        font-weight: 700;
        color: #172554;
    }

    .details-grid {
        padding: 24px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px 35px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }

    .detail-item.full {
        grid-column: 1 / -1;
    }

    .status-badge {
        display: inline-flex;
        width: fit-content;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-value {
        color: #94a3b8;
        font-weight: 500;
    }

    @media (max-width: 768px) {

        .staff-profile-page {
            padding: 15px;
        }

        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .profile-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full {
            grid-column: auto;
        }
    }
</style>


<div class="staff-profile-page">

    {{-- HEADER --}}
    <div class="profile-header">

        <div>
            <h1 class="page-title">Staff Profile</h1>

            <p class="page-subtitle">
                View complete staff information.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.other-staff.index') }}"
                class="btn btn-back"
            >
                <i class="fas fa-arrow-left"></i>
                Back to Staff
            </a>

            <a
                href="{{ route('admin.other-staff.edit', $otherStaff->id) }}"
                class="btn btn-edit"
            >
                <i class="fas fa-edit"></i>
                Edit Staff
            </a>

        </div>

    </div>


    {{-- PROFILE HEADER CARD --}}
    <div class="profile-card">

        <div class="profile-top">

            <div class="profile-photo">

                @if($otherStaff->profile_photo)

                    <img
                        src="{{ asset('storage/' . $otherStaff->profile_photo) }}"
                        alt="{{ $otherStaff->name }}"
                    >

                @else

                    <span class="photo-placeholder">
                        <i class="fas fa-user"></i>
                    </span>

                @endif

            </div>


            <div>

                <h2 class="profile-name">
                    {{ $otherStaff->name }}
                </h2>

                <p class="profile-designation">
                    {{ $otherStaff->designation }}

                    @if($otherStaff->department)
                        · {{ $otherStaff->department }}
                    @endif
                </p>

                <span class="staff-id">
                    <i class="fas fa-id-badge"></i>
                    {{ $otherStaff->staff_id }}
                </span>

            </div>

        </div>


        {{-- PERSONAL INFORMATION --}}
        <div class="section-title">
            <i class="fas fa-user"></i>
            &nbsp; Personal Information
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Full Name
                </span>

                <span class="detail-value">
                    {{ $otherStaff->name }}
                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Gender
                </span>

                <span class="detail-value">
                    @if($otherStaff->gender)
                        {{ $otherStaff->gender }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif
                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Date of Birth
                </span>

                <span class="detail-value">

                    @if($otherStaff->date_of_birth)
                        {{ $otherStaff->date_of_birth->format('d M Y') }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Staff ID
                </span>

                <span class="detail-value">
                    {{ $otherStaff->staff_id }}
                </span>

            </div>

        </div>


        {{-- CONTACT INFORMATION --}}
        <div class="section-title">
            <i class="fas fa-address-book"></i>
            &nbsp; Contact Information
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Phone
                </span>

                <span class="detail-value">

                    @if($otherStaff->phone)
                        {{ $otherStaff->phone }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <span class="detail-value">

                    @if($otherStaff->email)
                        {{ $otherStaff->email }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item full">

                <span class="detail-label">
                    Address
                </span>

                <span class="detail-value">

                    @if($otherStaff->address)
                        {{ $otherStaff->address }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>

        </div>


        {{-- EMPLOYMENT INFORMATION --}}
        <div class="section-title">
            <i class="fas fa-briefcase"></i>
            &nbsp; Employment Information
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Designation
                </span>

                <span class="detail-value">
                    {{ $otherStaff->designation }}
                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Department
                </span>

                <span class="detail-value">

                    @if($otherStaff->department)
                        {{ $otherStaff->department }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Qualification
                </span>

                <span class="detail-value">

                    @if($otherStaff->qualification)
                        {{ $otherStaff->qualification }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Joining Date
                </span>

                <span class="detail-value">

                    @if($otherStaff->joining_date)
                        {{ $otherStaff->joining_date->format('d M Y') }}
                    @else
                        <span class="empty-value">Not provided</span>
                    @endif

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span class="detail-value">

                    @if($otherStaff->status === 'Active')

                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i>
                            &nbsp; Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <i class="fas fa-times-circle"></i>
                            &nbsp; Inactive
                        </span>

                    @endif

                </span>

            </div>

        </div>

    </div>

</div>

@endsection