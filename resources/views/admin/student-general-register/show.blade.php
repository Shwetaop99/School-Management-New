
@extends('layouts.app')

@section('title', 'Student Details')

@section('content')

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center mb-4 gap-3">

        <div>
            <h2 class="fw-bold mb-1">Student Details</h2>
            <p class="text-muted mb-0">
                Complete student general register information.
            </p>
        </div>

        <a href="{{ route('admin.student-general-register.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Register
        </a>

    </div>

    {{-- STUDENT PROFILE --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap align-items-center gap-4">

                @if($student->profile_image)
                    <img src="{{ $student->profile_image }}"
                         alt="Student Photo"
                         class="student-profile-image">
                @else
                    <div class="student-profile-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                @endif

                <div>
                    <h4 class="fw-bold mb-1">
                        {{ $student->full_name
                            ?: trim(($student->first_name ?? '') . ' '
                                . ($student->middle_name ?? '') . ' '
                                . ($student->last_name ?? '')) }}
                    </h4>

                    @if($student->marathi_name)
                        <p class="text-muted mb-2">
                            {{ $student->marathi_name }}
                        </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            Student ID: {{ $student->student_id ?: '—' }}
                        </span>

                        <span class="badge bg-success">
                            {{ ucfirst($student->status ?? 'Unknown') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BASIC INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-person-vcard me-2 text-primary"></i>
                Basic Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="detail-label">First Name</div>
                    <div class="detail-value">{{ $student->first_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Middle Name</div>
                    <div class="detail-value">{{ $student->middle_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Last Name</div>
                    <div class="detail-value">{{ $student->last_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Gender</div>
                    <div class="detail-value">{{ $student->gender ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Date of Birth</div>
                    <div class="detail-value">
                        {{ $student->date_of_birth
                            ? \Illuminate\Support\Carbon::parse($student->date_of_birth)->format('d-m-Y')
                            : '—' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Birth Place</div>
                    <div class="detail-value">{{ $student->birth_place ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Aadhaar Number</div>
                    <div class="detail-value">{{ $student->aadhar_card_no ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Nationality</div>
                    <div class="detail-value">{{ $student->nationality ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Mother Tongue</div>
                    <div class="detail-value">{{ $student->mother_tongue ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Religion</div>
                    <div class="detail-value">{{ $student->religion ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Caste</div>
                    <div class="detail-value">{{ $student->caste ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Sub Caste</div>
                    <div class="detail-value">{{ $student->sub_caste ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- ACADEMIC INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-mortarboard me-2 text-primary"></i>
                Academic Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="detail-label">Academic Year</div>
                    <div class="detail-value">{{ $student->academic_year ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Admission Class</div>
                    <div class="detail-value">{{ $student->admission_class ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Current Class</div>
                    <div class="detail-value">{{ $student->class ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Section</div>
                    <div class="detail-value">{{ $student->section ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Admission Date</div>
                    <div class="detail-value">
                        {{ $student->admission_date
                            ? \Illuminate\Support\Carbon::parse($student->admission_date)->format('d-m-Y')
                            : '—' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Register Number</div>
                    <div class="detail-value">{{ $student->register_no ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Book Number</div>
                    <div class="detail-value">{{ $student->book_no ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Roll Number</div>
                    <div class="detail-value">{{ $student->roll_number ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Medium</div>
                    <div class="detail-value">{{ $student->medium ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- PARENT INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-people me-2 text-primary"></i>
                Parent and Guardian Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="detail-label">Father's Name</div>
                    <div class="detail-value">{{ $student->father_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Father's Phone</div>
                    <div class="detail-value">{{ $student->father_phone ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Father's Occupation</div>
                    <div class="detail-value">{{ $student->father_occupation ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Mother's Name</div>
                    <div class="detail-value">{{ $student->mother_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Mother's Phone</div>
                    <div class="detail-value">{{ $student->mother_phone ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Mother's Occupation</div>
                    <div class="detail-value">{{ $student->mother_occupation ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Guardian Name</div>
                    <div class="detail-value">{{ $student->guardian_name ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Guardian Relationship</div>
                    <div class="detail-value">{{ $student->guardian_relation ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Guardian Phone</div>
                    <div class="detail-value">{{ $student->guardian_phone ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- PREVIOUS SCHOOL --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-building me-2 text-primary"></i>
                Previous School Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-6">
                    <div class="detail-label">Previous School Name</div>
                    <div class="detail-value">{{ $student->previous_school_name ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Previous School Class</div>
                    <div class="detail-value">{{ $student->previous_school_class ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">School Address</div>
                    <div class="detail-value">{{ $student->previous_school_address ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Medium</div>
                    <div class="detail-value">{{ $student->previous_school_medium ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Board</div>
                    <div class="detail-value">{{ $student->previous_school_board ?: '—' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Previous Result</div>
                    <div class="detail-value">{{ $student->previous_school_result ?: '—' }}</div>
                </div>

                <div class="col-12">
                    <div class="detail-label">Remarks</div>
                    <div class="detail-value">{{ $student->previous_school_remarks ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- ADDRESS INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-geo-alt me-2 text-primary"></i>
                Address Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-12">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">
                        {!! nl2br(e($student->address ?: '—')) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">City / Village</div>
                    <div class="detail-value">{{ $student->city_village ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Taluka</div>
                    <div class="detail-value">{{ $student->taluka ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">District</div>
                    <div class="detail-value">{{ $student->district ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">State</div>
                    <div class="detail-value">{{ $student->state ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Country</div>
                    <div class="detail-value">{{ $student->country ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">PIN Code</div>
                    <div class="detail-value">{{ $student->pincode ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- CONTACT INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-telephone me-2 text-primary"></i>
                Contact Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="detail-label">Student Phone</div>
                    <div class="detail-value">{{ $student->phone ?: '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $student->email ?: '—' }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER ACTION --}}
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('admin.student-general-register.index') }}"
           class="btn btn-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to General Register
        </a>
    </div>

</div>

<style>
    .student-profile-image,
    .student-profile-placeholder {
        width: 115px;
        height: 115px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .student-profile-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: #1677f0;
        font-size: 48px;
    }

    .detail-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #212529;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
</style>

@endsection
