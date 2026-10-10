@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-heartbeat text-success me-2"></i>
                Student Health Record
            </h3>
            <p class="text-muted mb-0">
                Annual health checkup details
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.student-health.index') }}"
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            <a href="{{ route('admin.student-health.edit', $healthRecord) }}"
               class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>
                Edit
            </a>

            <a href="{{ route('admin.student-health.print', $healthRecord) }}"
               target="_blank"
               class="btn btn-success">
                <i class="fas fa-print me-1"></i>
                Print
            </a>
        </div>
    </div>

    {{-- Student Profile --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-user-graduate me-2"></i>
                Student Profile
            </h5>
        </div>

        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-2 text-center mb-3 mb-md-0">

                    @if($healthRecord->student->profile_image)
                        <img src="{{ $healthRecord->student->profile_image }}"
                             alt="Student Photo"
                             class="rounded-circle border"
                             style="width:110px;height:110px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto"
                             style="width:110px;height:110px;">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                    @endif

                </div>

                <div class="col-md-10">

                    <h4 class="fw-bold mb-3">
                        {{ $healthRecord->student->first_name }}
                        {{ $healthRecord->student->middle_name }}
                        {{ $healthRecord->student->last_name }}
                    </h4>

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <small class="text-muted d-block">
                                Student ID
                            </small>
                            <strong>
                                {{ $healthRecord->student->student_id }}
                            </strong>
                        </div>

                        <div class="col-md-3 mb-3">
                            <small class="text-muted d-block">
                                Class
                            </small>
                            <strong>
                                {{ $healthRecord->student->class }}
                            </strong>
                        </div>

                        <div class="col-md-3 mb-3">
                            <small class="text-muted d-block">
                                Section
                            </small>
                            <strong>
                                {{ $healthRecord->student->section }}
                            </strong>
                        </div>

                        <div class="col-md-3 mb-3">
                            <small class="text-muted d-block">
                                Roll Number
                            </small>
                            <strong>
                                {{ $healthRecord->student->roll_number }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- Checkup Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-calendar-check text-success me-2"></i>
                Checkup Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Academic Year</label>
                    <div class="info-value">
                        {{ $healthRecord->academic_year ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Checkup Date</label>
                    <div class="info-value">
                        {{ $healthRecord->checkup_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Checkup Type</label>
                    <div class="info-value">
                        {{ $healthRecord->checkup_type ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Doctor</label>
                    <div class="info-value">
                        {{ $healthRecord->doctor_name ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Health Center</label>
                    <div class="info-value">
                        {{ $healthRecord->health_center ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Conducted By</label>
                    <div class="info-value">
                        {{ $healthRecord->conducted_by ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Physical Measurements --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-ruler text-success me-2"></i>
                Physical Measurements
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Height</label>
                    <div class="info-value">
                        {{ $healthRecord->height ?? '—' }}
                        @if($healthRecord->height)
                            cm
                        @endif
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Weight</label>
                    <div class="info-value">
                        {{ $healthRecord->weight ?? '—' }}
                        @if($healthRecord->weight)
                            kg
                        @endif
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>BMI</label>
                    <div class="info-value">
                        {{ $healthRecord->bmi ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Pulse Rate</label>
                    <div class="info-value">
                        {{ $healthRecord->pulse_rate ?? '—' }}
                        @if($healthRecord->pulse_rate)
                            bpm
                        @endif
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Blood Pressure</label>
                    <div class="info-value">
                        {{ $healthRecord->blood_pressure ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Temperature</label>
                    <div class="info-value">
                        {{ $healthRecord->temperature ?? '—' }}
                        @if($healthRecord->temperature)
                            °C
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Vision --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-eye text-success me-2"></i>
                Vision Examination
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Examination</th>
                            <th>Right Eye</th>
                            <th>Left Eye</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Distance Vision</td>
                            <td>{{ $healthRecord->vision_right ?: '—' }}</td>
                            <td>{{ $healthRecord->vision_left ?: '—' }}</td>
                        </tr>

                        <tr>
                            <td>Near Vision</td>
                            <td>{{ $healthRecord->near_vision_right ?: '—' }}</td>
                            <td>{{ $healthRecord->near_vision_left ?: '—' }}</td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="row mt-3">

                <div class="col-md-6">
                    <label>Uses Spectacles</label>
                    <div class="info-value">
                        {{ $healthRecord->uses_spectacles ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label>Spectacle Power</label>
                    <div class="info-value">
                        {{ $healthRecord->spectacle_power ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Dental --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-tooth text-success me-2"></i>
                Dental Examination
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Dental Status</label>
                    <div class="info-value">
                        {{ $healthRecord->dental_status ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Dental Caries</label>
                    <div class="info-value">
                        {{ $healthRecord->dental_caries ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Gum Problem</label>
                    <div class="info-value">
                        {{ $healthRecord->gum_problem ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Oral Hygiene</label>
                    <div class="info-value">
                        {{ $healthRecord->oral_hygiene ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- ENT --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-deaf text-success me-2"></i>
                ENT Examination
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Right Ear</label>
                    <div class="info-value">
                        {{ $healthRecord->right_ear ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Left Ear</label>
                    <div class="info-value">
                        {{ $healthRecord->left_ear ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Hearing Problem</label>
                    <div class="info-value">
                        {{ $healthRecord->hearing_problem ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Nose</label>
                    <div class="info-value">
                        {{ $healthRecord->nose_status ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Throat</label>
                    <div class="info-value">
                        {{ $healthRecord->throat_status ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- General Health --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-heartbeat text-success me-2"></i>
                General Health Examination
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                @php
                    $generalFields = [
                        'general_health' => 'General Health',
                        'skin_status' => 'Skin',
                        'respiratory_status' => 'Respiratory System',
                        'heart_status' => 'Heart',
                        'abdomen_status' => 'Abdomen',
                        'musculoskeletal_status' => 'Musculoskeletal',
                        'nutritional_status' => 'Nutritional Status',
                        'anemia_screening' => 'Anemia Screening',
                    ];
                @endphp

                @foreach($generalFields as $field => $label)

                    <div class="col-md-3 mb-3">

                        <label>{{ $label }}</label>

                        <div class="info-value">
                            {{ $healthRecord->{$field} ?: '—' }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>
    </div>


    {{-- Medical History --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-notes-medical text-success me-2"></i>
                Medical History
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Known Health Condition</label>
                    <div class="info-box">
                        {{ $healthRecord->known_health_condition ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Allergy</label>
                    <div class="info-box">
                        {{ $healthRecord->allergy ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Current Medication</label>
                    <div class="info-box">
                        {{ $healthRecord->current_medication ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Medical History</label>
                    <div class="info-box">
                        {{ $healthRecord->medical_history ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Referral --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-hospital text-success me-2"></i>
                Referral & Follow-up
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Referral Required</label>
                    <div class="info-value">
                        {{ $healthRecord->referral_required ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Referred To</label>
                    <div class="info-value">
                        {{ $healthRecord->referral_to ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Referral Date</label>
                    <div class="info-value">
                        {{ $healthRecord->referral_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Follow-up Date</label>
                    <div class="info-value">
                        {{ $healthRecord->follow_up_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Treatment Advised</label>
                    <div class="info-box">
                        {{ $healthRecord->treatment_advised ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Follow-up Status</label>
                    <div class="info-box">
                        {{ $healthRecord->follow_up_status ?: '—' }}
                    </div>
                </div>

                <div class="col-12">
                    <label>Follow-up Remarks</label>
                    <div class="info-box">
                        {{ $healthRecord->follow_up_remarks ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- Final Assessment --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Final Assessment
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <label>Overall Health Status</label>

                <div class="mt-2">
                    @if($healthRecord->overall_health_status)
                        <span class="badge bg-success fs-6 px-3 py-2">
                            {{ $healthRecord->overall_health_status }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Doctor's Remarks</label>
                    <div class="info-box">
                        {{ $healthRecord->doctor_remarks ?: '—' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Teacher's Remarks</label>
                    <div class="info-box">
                        {{ $healthRecord->teacher_remarks ?: '—' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Parent's Remarks</label>
                    <div class="info-box">
                        {{ $healthRecord->parent_remarks ?: '—' }}
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>


<style>

    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }

    label {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
        display: block;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #212529;
        min-height: 24px;
    }

    .info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 12px;
        min-height: 45px;
        white-space: pre-line;
    }

    .table th {
        font-weight: 600;
    }

</style>

@endsection