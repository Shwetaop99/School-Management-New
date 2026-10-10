@extends('layouts.app')

@section('content')

<style>
    .health-form-page {
        padding: 24px 0;
    }

    .page-header {
        background: linear-gradient(135deg, #198754, #157347);
        color: #fff;
        border-radius: 16px;
        padding: 22px 26px;
        margin-bottom: 22px;
        box-shadow: 0 8px 24px rgba(0,0,0,.08);
    }

    .page-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .page-header p {
        margin: 5px 0 0;
        font-size: 13px;
        opacity: .9;
    }

    .student-card,
    .form-card {
        background: #fff;
        border: 1px solid #edf0f2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,.05);
        margin-bottom: 20px;
    }

    .student-card {
        padding: 20px;
    }

    .student-profile {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .student-photo {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e8f5ee;
    }

    .student-placeholder {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #e8f5ee;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        font-weight: 700;
    }

    .student-name {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
    }

    .student-id {
        color: #6c757d;
        font-size: 13px;
        margin-top: 3px;
    }

    .student-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 8px;
    }

    .meta-badge {
        padding: 5px 9px;
        border-radius: 7px;
        background: #f1f3f5;
        color: #495057;
        font-size: 11px;
        font-weight: 600;
    }

    .form-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #e8f5ee;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .form-card-body {
        padding: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 9px;
        border: 1px solid #dee2e6;
        font-size: 13px;
    }

    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 .15rem rgba(25,135,84,.12);
    }

    .required {
        color: #dc3545;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-actions {
        background: #fff;
        border: 1px solid #edf0f2;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        box-shadow: 0 4px 18px rgba(0,0,0,.05);
    }

    .btn-save,
    .btn-cancel {
        min-height: 42px;
        border-radius: 9px;
        padding: 0 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .calculated-note {
        font-size: 11px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>

<div class="container-fluid health-form-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-header">
        <h2>
            <i class="fas fa-heart-pulse me-2"></i>
            Annual Health Checkup
        </h2>

        <p>
            Record the student's yearly health examination details.
        </p>
    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())
        <div class="alert alert-danger rounded-3">
            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         STUDENT INFORMATION
    ========================================================== --}}
    <div class="student-card">

        <div class="student-profile">

            @if(!empty($student->profile_image))

                <img
                    src="{{ $student->profile_image }}"
                    alt="{{ $student->first_name }}"
                    class="student-photo"
                >

            @else

                <div class="student-placeholder">
                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                </div>

            @endif

            <div>

                <div class="student-name">
                    {{ trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    ) }}
                </div>

                <div class="student-id">
                    Student ID: {{ $student->student_id }}
                </div>

                <div class="student-meta">

                    <span class="meta-badge">
                        Class {{ $student->class }}
                    </span>

                    <span class="meta-badge">
                        Section {{ $student->section }}
                    </span>

                    <span class="meta-badge">
                        Roll No. {{ $student->roll_number ?? '-' }}
                    </span>

                    <span class="meta-badge">
                        DOB:
                        {{ $student->date_of_birth?->format('d M Y') ?? '-' }}
                    </span>

                    <span class="meta-badge">
                        Gender: {{ $student->gender ?? '-' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FORM
    ========================================================== --}}
    <form
        method="POST"
        action="{{ route('admin.student-health.store', $student) }}"
    >

        @csrf


        {{-- =====================================================
             1. CHECKUP INFORMATION
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <h5>
                    Checkup Information
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Academic Year
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="academic_year"
                            class="form-control"
                            value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                            placeholder="2026-2027"
                            required
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Checkup Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="checkup_date"
                            class="form-control"
                            value="{{ old('checkup_date', date('Y-m-d')) }}"
                            required
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Checkup Type
                        </label>

                        <select
                            name="checkup_type"
                            class="form-select"
                        >
                            <option value="">Select Type</option>
                            <option value="Annual Health Checkup"
                                {{ old('checkup_type') == 'Annual Health Checkup' ? 'selected' : '' }}>
                                Annual Health Checkup
                            </option>
                            <option value="General Checkup"
                                {{ old('checkup_type') == 'General Checkup' ? 'selected' : '' }}>
                                General Checkup
                            </option>
                            <option value="Follow-up"
                                {{ old('checkup_type') == 'Follow-up' ? 'selected' : '' }}>
                                Follow-up
                            </option>
                            <option value="Other"
                                {{ old('checkup_type') == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Doctor / Medical Officer
                        </label>

                        <input
                            type="text"
                            name="doctor_name"
                            class="form-control"
                            value="{{ old('doctor_name') }}"
                            placeholder="Enter doctor name"
                        >
                    </div>


                    <div class="col-lg-6 col-md-6">
                        <label class="form-label">
                            Health Center / Hospital
                        </label>

                        <input
                            type="text"
                            name="health_center"
                            class="form-control"
                            value="{{ old('health_center') }}"
                            placeholder="Enter health center"
                        >
                    </div>


                    <div class="col-lg-6 col-md-6">
                        <label class="form-label">
                            Conducted By
                        </label>

                        <input
                            type="text"
                            name="conducted_by"
                            class="form-control"
                            value="{{ old('conducted_by') }}"
                            placeholder="Doctor / Nurse / Health Worker"
                        >
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             2. PHYSICAL MEASUREMENTS
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-ruler-vertical"></i>
                </div>

                <h5>
                    Physical Measurements
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Height (cm)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="height"
                            id="height"
                            class="form-control"
                            value="{{ old('height') }}"
                            placeholder="e.g. 125.50"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Weight (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="weight"
                            id="weight"
                            class="form-control"
                            value="{{ old('weight') }}"
                            placeholder="e.g. 25.50"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            BMI
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="bmi"
                            id="bmi"
                            class="form-control"
                            value="{{ old('bmi') }}"
                            placeholder="Enter BMI"
                        >

                        <div class="calculated-note">
                            BMI can be entered/verified by the health professional.
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Pulse Rate (bpm)
                        </label>

                        <input
                            type="number"
                            min="0"
                            name="pulse_rate"
                            class="form-control"
                            value="{{ old('pulse_rate') }}"
                            placeholder="e.g. 80"
                        >
                    </div>


                    <div class="col-lg-6 col-md-6">
                        <label class="form-label">
                            Blood Pressure
                        </label>

                        <input
                            type="text"
                            name="blood_pressure"
                            class="form-control"
                            value="{{ old('blood_pressure') }}"
                            placeholder="e.g. 110/70"
                        >
                    </div>


                    <div class="col-lg-6 col-md-6">
                        <label class="form-label">
                            Temperature (°C)
                        </label>

                        <input
                            type="number"
                            step="0.1"
                            name="temperature"
                            class="form-control"
                            value="{{ old('temperature') }}"
                            placeholder="e.g. 36.8"
                        >
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             3. VISION
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-eye"></i>
                </div>

                <h5>
                    Vision Examination
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Right Eye - Distance Vision
                        </label>

                        <input
                            type="text"
                            name="vision_right"
                            class="form-control"
                            value="{{ old('vision_right') }}"
                            placeholder="e.g. 6/6"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Left Eye - Distance Vision
                        </label>

                        <input
                            type="text"
                            name="vision_left"
                            class="form-control"
                            value="{{ old('vision_left') }}"
                            placeholder="e.g. 6/6"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Right Eye - Near Vision
                        </label>

                        <input
                            type="text"
                            name="near_vision_right"
                            class="form-control"
                            value="{{ old('near_vision_right') }}"
                            placeholder="Enter result"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Left Eye - Near Vision
                        </label>

                        <input
                            type="text"
                            name="near_vision_left"
                            class="form-control"
                            value="{{ old('near_vision_left') }}"
                            placeholder="Enter result"
                        >
                    </div>


                    <div class="col-lg-4 col-md-6">

                        <label class="form-label d-block">
                            Uses Spectacles
                        </label>

                        <div class="form-check form-check-inline mt-2">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="uses_spectacles"
                                id="spectacles_yes"
                                value="1"
                                {{ old('uses_spectacles') == '1' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="spectacles_yes"
                            >
                                Yes
                            </label>

                        </div>

                        <div class="form-check form-check-inline mt-2">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="uses_spectacles"
                                id="spectacles_no"
                                value="0"
                                {{ old('uses_spectacles') === '0' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="spectacles_no"
                            >
                                No
                            </label>

                        </div>

                    </div>


                    <div class="col-lg-8 col-md-6">
                        <label class="form-label">
                            Spectacle Power
                        </label>

                        <input
                            type="text"
                            name="spectacle_power"
                            class="form-control"
                            value="{{ old('spectacle_power') }}"
                            placeholder="Enter spectacle power if applicable"
                        >
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             4. DENTAL
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-tooth"></i>
                </div>

                <h5>
                    Dental Examination
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Dental Status
                        </label>

                        <select name="dental_status" class="form-select">
                            <option value="">Select</option>
                            <option value="Normal" {{ old('dental_status') == 'Normal' ? 'selected' : '' }}>
                                Normal
                            </option>
                            <option value="Needs Attention" {{ old('dental_status') == 'Needs Attention' ? 'selected' : '' }}>
                                Needs Attention
                            </option>
                            <option value="Requires Treatment" {{ old('dental_status') == 'Requires Treatment' ? 'selected' : '' }}>
                                Requires Treatment
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Dental Caries
                        </label>

                        <select name="dental_caries" class="form-select">
                            <option value="">Select</option>
                            <option value="No" {{ old('dental_caries') == 'No' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="Yes" {{ old('dental_caries') == 'Yes' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Gum Problem
                        </label>

                        <select name="gum_problem" class="form-select">
                            <option value="">Select</option>
                            <option value="No" {{ old('gum_problem') == 'No' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="Yes" {{ old('gum_problem') == 'Yes' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Oral Hygiene
                        </label>

                        <select name="oral_hygiene" class="form-select">
                            <option value="">Select</option>
                            <option value="Good" {{ old('oral_hygiene') == 'Good' ? 'selected' : '' }}>
                                Good
                            </option>
                            <option value="Average" {{ old('oral_hygiene') == 'Average' ? 'selected' : '' }}>
                                Average
                            </option>
                            <option value="Needs Improvement" {{ old('oral_hygiene') == 'Needs Improvement' ? 'selected' : '' }}>
                                Needs Improvement
                            </option>
                        </select>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             5. ENT
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-ear-listen"></i>
                </div>

                <h5>
                    ENT Examination
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Right Ear
                        </label>

                        <input
                            type="text"
                            name="right_ear"
                            class="form-control"
                            value="{{ old('right_ear') }}"
                            placeholder="Enter finding"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Left Ear
                        </label>

                        <input
                            type="text"
                            name="left_ear"
                            class="form-control"
                            value="{{ old('left_ear') }}"
                            placeholder="Enter finding"
                        >
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Hearing Problem
                        </label>

                        <select name="hearing_problem" class="form-select">
                            <option value="">Select</option>
                            <option value="No" {{ old('hearing_problem') == 'No' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="Yes" {{ old('hearing_problem') == 'Yes' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">
                            Nose Status
                        </label>

                        <input
                            type="text"
                            name="nose_status"
                            class="form-control"
                            value="{{ old('nose_status') }}"
                            placeholder="Enter finding"
                        >
                    </div>


                    <div class="col-lg-6">
                        <label class="form-label">
                            Throat Status
                        </label>

                        <input
                            type="text"
                            name="throat_status"
                            class="form-control"
                            value="{{ old('throat_status') }}"
                            placeholder="Enter finding"
                        >
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             6. GENERAL HEALTH
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>

                <h5>
                    General Health Examination
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    @php
                        $healthSelects = [
                            'general_health' => 'General Health',
                            'skin_status' => 'Skin',
                            'respiratory_status' => 'Respiratory System',
                            'heart_status' => 'Heart',
                            'abdomen_status' => 'Abdomen',
                            'musculoskeletal_status' => 'Musculoskeletal',
                        ];
                    @endphp

                    @foreach($healthSelects as $field => $label)

                        <div class="col-lg-4 col-md-6">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <select
                                name="{{ $field }}"
                                class="form-select"
                            >
                                <option value="">Select</option>

                                <option
                                    value="Normal"
                                    {{ old($field) == 'Normal' ? 'selected' : '' }}
                                >
                                    Normal
                                </option>

                                <option
                                    value="Needs Attention"
                                    {{ old($field) == 'Needs Attention' ? 'selected' : '' }}
                                >
                                    Needs Attention
                                </option>

                                <option
                                    value="Requires Medical Attention"
                                    {{ old($field) == 'Requires Medical Attention' ? 'selected' : '' }}
                                >
                                    Requires Medical Attention
                                </option>

                            </select>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =====================================================
             7. NUTRITION
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-apple-whole"></i>
                </div>

                <h5>
                    Nutrition & Anemia Screening
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-6">
                        <label class="form-label">
                            Nutritional Status
                        </label>

                        <select
                            name="nutritional_status"
                            class="form-select"
                        >
                            <option value="">Select</option>
                            <option value="Normal" {{ old('nutritional_status') == 'Normal' ? 'selected' : '' }}>
                                Normal
                            </option>
                            <option value="Needs Attention" {{ old('nutritional_status') == 'Needs Attention' ? 'selected' : '' }}>
                                Needs Attention
                            </option>
                            <option value="Malnutrition Suspected" {{ old('nutritional_status') == 'Malnutrition Suspected' ? 'selected' : '' }}>
                                Malnutrition Suspected
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-6">
                        <label class="form-label">
                            Anemia Screening
                        </label>

                        <select
                            name="anemia_screening"
                            class="form-select"
                        >
                            <option value="">Select</option>
                            <option value="Normal" {{ old('anemia_screening') == 'Normal' ? 'selected' : '' }}>
                                Normal
                            </option>
                            <option value="Suspected" {{ old('anemia_screening') == 'Suspected' ? 'selected' : '' }}>
                                Suspected
                            </option>
                            <option value="Confirmed" {{ old('anemia_screening') == 'Confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>
                        </select>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             8. MEDICAL HISTORY
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-file-medical"></i>
                </div>

                <h5>
                    Medical History
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-4">
                        <label class="form-label">
                            Known Health Condition
                        </label>

                        <textarea
                            name="known_health_condition"
                            class="form-control"
                            placeholder="Enter known condition, if any"
                        >{{ old('known_health_condition') }}</textarea>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Allergy
                        </label>

                        <textarea
                            name="allergy"
                            class="form-control"
                            placeholder="Enter allergy information"
                        >{{ old('allergy') }}</textarea>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Current Medication
                        </label>

                        <textarea
                            name="current_medication"
                            class="form-control"
                            placeholder="Enter current medication"
                        >{{ old('current_medication') }}</textarea>
                    </div>


                    <div class="col-12">
                        <label class="form-label">
                            Medical History / Additional Information
                        </label>

                        <textarea
                            name="medical_history"
                            class="form-control"
                            placeholder="Enter relevant medical history"
                        >{{ old('medical_history') }}</textarea>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             9. REFERRAL & FOLLOW-UP
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-hospital"></i>
                </div>

                <h5>
                    Referral & Follow-up
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label d-block">
                            Referral Required
                        </label>

                        <div class="form-check form-check-inline mt-2">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="referral_required"
                                value="1"
                                id="referral_yes"
                                {{ old('referral_required') == '1' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="referral_yes"
                            >
                                Yes
                            </label>

                        </div>

                        <div class="form-check form-check-inline mt-2">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="referral_required"
                                value="0"
                                id="referral_no"
                                {{ old('referral_required') === '0' ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="referral_no"
                            >
                                No
                            </label>

                        </div>

                    </div>


                    <div class="col-lg-5 col-md-6">
                        <label class="form-label">
                            Referred To
                        </label>

                        <input
                            type="text"
                            name="referral_to"
                            class="form-control"
                            value="{{ old('referral_to') }}"
                            placeholder="Hospital / Specialist / Health Center"
                        >
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            Referral Date
                        </label>

                        <input
                            type="date"
                            name="referral_date"
                            class="form-control"
                            value="{{ old('referral_date') }}"
                        >
                    </div>


                    <div class="col-12">
                        <label class="form-label">
                            Treatment Advised
                        </label>

                        <textarea
                            name="treatment_advised"
                            class="form-control"
                            placeholder="Enter treatment or advice provided"
                        >{{ old('treatment_advised') }}</textarea>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Follow-up Date
                        </label>

                        <input
                            type="date"
                            name="follow_up_date"
                            class="form-control"
                            value="{{ old('follow_up_date') }}"
                        >
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Follow-up Status
                        </label>

                        <select
                            name="follow_up_status"
                            class="form-select"
                        >
                            <option value="">Select</option>
                            <option value="Pending" {{ old('follow_up_status') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="Completed" {{ old('follow_up_status') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="Not Required" {{ old('follow_up_status') == 'Not Required' ? 'selected' : '' }}>
                                Not Required
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Follow-up Remarks
                        </label>

                        <input
                            type="text"
                            name="follow_up_remarks"
                            class="form-control"
                            value="{{ old('follow_up_remarks') }}"
                            placeholder="Enter remarks"
                        >
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             10. FINAL ASSESSMENT
        ====================================================== --}}
        <div class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <h5>
                    Final Assessment & Remarks
                </h5>

            </div>

            <div class="form-card-body">

                <div class="row g-3">

                    <div class="col-lg-4">
                        <label class="form-label">
                            Overall Health Status
                        </label>

                        <select
                            name="overall_health_status"
                            class="form-select"
                        >
                            <option value="">Select Status</option>

                            <option
                                value="Healthy"
                                {{ old('overall_health_status') == 'Healthy' ? 'selected' : '' }}
                            >
                                Healthy
                            </option>

                            <option
                                value="Needs Attention"
                                {{ old('overall_health_status') == 'Needs Attention' ? 'selected' : '' }}
                            >
                                Needs Attention
                            </option>

                            <option
                                value="Requires Medical Attention"
                                {{ old('overall_health_status') == 'Requires Medical Attention' ? 'selected' : '' }}
                            >
                                Requires Medical Attention
                            </option>

                        </select>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Doctor Remarks
                        </label>

                        <textarea
                            name="doctor_remarks"
                            class="form-control"
                            placeholder="Doctor / medical officer remarks"
                        >{{ old('doctor_remarks') }}</textarea>
                    </div>


                    <div class="col-lg-4">
                        <label class="form-label">
                            Teacher Remarks
                        </label>

                        <textarea
                            name="teacher_remarks"
                            class="form-control"
                            placeholder="Teacher remarks"
                        >{{ old('teacher_remarks') }}</textarea>
                    </div>


                    <div class="col-12">
                        <label class="form-label">
                            Parent Remarks
                        </label>

                        <textarea
                            name="parent_remarks"
                            class="form-control"
                            placeholder="Parent / guardian remarks"
                        >{{ old('parent_remarks') }}</textarea>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}
        <div class="form-actions">

            <a
                href="{{ route('admin.student-health.index') }}"
                class="btn btn-outline-secondary btn-cancel"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-success btn-save"
            >
                <i class="fas fa-save me-1"></i>
                Save Health Record
            </button>

        </div>

    </form>

</div>

@endsection