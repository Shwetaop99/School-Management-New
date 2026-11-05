@extends('layouts.app')

@section('content')

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM — ANNUAL HEALTH CHECKUP
========================================================= */
.health-form-page {
    --diary-bg: #f7f6f0;
    --diary-chalk: #f5f3e9;
    --diary-paper: #fffefa;
    --diary-ink: #263b35;
    --diary-dark: #1d302b;
    --diary-soft: #40574a;
    --diary-text: #39483e;
    --diary-muted: #7b857b;
    --diary-line: #e5e1d5;
    --diary-sage: #527f70;
    --diary-blue: #426b8a;
    --diary-amber: #a87543;
    --diary-green: #43835d;
    --diary-red: #b95b50;
    padding: 24px;
    background: var(--diary-bg);
    color: var(--diary-text);
    font-family: "Public Sans", Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-size: 13px;
    line-height: 1.6;
    max-width: 1650px;
    margin: 0 auto;
}
.health-form-page * { box-sizing: border-box; }
.health-form-page .page-header {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    min-height: 176px;
    padding: 30px 34px;
    margin-bottom: 22px;
    color: #f5f3e9;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 15px;
    background: linear-gradient(115deg, #344f43 0%, #20352c 100%);
    box-shadow: 0 12px 28px rgba(29,48,43,.12);
}
.health-form-page .page-header::before,
.health-form-page .page-header::after {
    content: "";
    position: absolute;
    z-index: -1;
    border: 1px solid rgba(245,243,233,.13);
    border-radius: 50%;
    pointer-events: none;
}
.health-form-page .page-header::before { width: 230px; height: 230px; right: 48px; top: -120px; }
.health-form-page .page-header::after { width: 160px; height: 160px; right: 112px; bottom: -112px; }
.health-form-page .page-header h2 {
    margin: 0;
    color: #fffefa;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 34px);
    font-weight: 700;
    letter-spacing: -.35px;
    line-height: 1.25;
}
.health-form-page .page-header h2 i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    margin-right: 10px !important;
    color: #f1ead8;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 12px;
    font-size: 20px;
    vertical-align: middle;
}
.health-form-page .page-header p {
    max-width: 650px;
    margin: 9px 0 0;
    color: rgba(245,243,233,.82);
    font-size: 13px;
    line-height: 1.7;
}
.health-form-page .student-card,
.health-form-page .form-card,
.health-form-page .form-actions {
    background: var(--diary-paper);
    border: 1px solid var(--diary-line);
    border-radius: 15px;
    box-shadow: 0 5px 16px rgba(38,59,53,.045);
    margin-bottom: 18px;
}
.health-form-page .student-card { padding: 22px 24px; }
.health-form-page .student-profile { display: flex; align-items: center; gap: 17px; }
.health-form-page .student-photo,
.health-form-page .student-placeholder {
    flex: 0 0 72px;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e5ece3;
}
.health-form-page .student-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf0e7;
    color: var(--diary-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 26px;
    font-weight: 700;
}
.health-form-page .student-name { color: var(--diary-ink); font-family: Georgia, "Times New Roman", serif; font-size: 20px; font-weight: 700; line-height: 1.35; }
.health-form-page .student-id { margin-top: 4px; color: var(--diary-muted); font-size: 12px; }
.health-form-page .student-meta { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 10px; }
.health-form-page .meta-badge {
    padding: 5px 9px;
    border: 1px solid #e4e7dc;
    border-radius: 7px;
    background: #f5f4ec;
    color: var(--diary-soft);
    font-size: 11px;
    font-weight: 650;
}
.health-form-page .form-card { overflow: hidden; }
.health-form-page .form-card-header {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--diary-line);
    background: linear-gradient(90deg, #f8f7f0, #fffefa);
}
.health-form-page .section-icon {
    display: flex;
    flex: 0 0 36px;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    color: var(--diary-sage);
    background: #eaf0e7;
    border: 1px solid #dfe7db;
    border-radius: 10px;
    font-size: 15px;
}
.health-form-page .form-card-header h5 {
    margin: 0;
    color: var(--diary-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.4;
}
.health-form-page .form-card-body { padding: 22px; }
.health-form-page .form-label {
    display: inline-block;
    margin-bottom: 7px;
    color: var(--diary-soft);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .05px;
}
.health-form-page .form-control,
.health-form-page .form-select {
    width: 100%;
    min-height: 43px;
    border: 1px solid #dcded3;
    border-radius: 9px;
    background-color: #fffefa;
    color: var(--diary-text);
    font-family: inherit;
    font-size: 13px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
}
.health-form-page .form-control { padding: 10px 12px; }
.health-form-page .form-select { padding: 9px 36px 9px 12px; }
.health-form-page textarea.form-control { min-height: 94px; resize: vertical; }
.health-form-page .form-control::placeholder { color: #9a9e93; opacity: 1; }
.health-form-page .form-control:hover,
.health-form-page .form-select:hover { border-color: #bdc9bc; }
.health-form-page .form-control:focus,
.health-form-page .form-select:focus {
    border-color: var(--diary-sage);
    background-color: #fffefa;
    box-shadow: 0 0 0 3px rgba(82,127,112,.13);
    outline: none;
}
.health-form-page .required { color: var(--diary-red); font-weight: 800; }
.health-form-page .form-check { min-height: 1.35rem; }
.health-form-page .form-check-input {
    border-color: #c9cec2;
    cursor: pointer;
}
.health-form-page .form-check-input:checked {
    background-color: var(--diary-sage);
    border-color: var(--diary-sage);
}
.health-form-page .form-check-input:focus { border-color: var(--diary-sage); box-shadow: 0 0 0 3px rgba(82,127,112,.13); }
.health-form-page .form-check-label { color: var(--diary-text); font-size: 13px; cursor: pointer; }
.health-form-page .calculated-note { margin-top: 6px; color: var(--diary-muted); font-size: 11px; line-height: 1.55; }
.health-form-page .alert-danger {
    margin-bottom: 20px;
    padding: 15px 18px;
    color: #803d36;
    background: #fbefec;
    border: 1px solid #efd3ce;
    border-radius: 11px !important;
    font-size: 12px;
}
.health-form-page .alert-danger ul { padding-left: 20px; }
.health-form-page .form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    margin-bottom: 0;
}
.health-form-page .btn-save,
.health-form-page .btn-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 43px;
    padding: 0 19px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .18s ease, background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
}
.health-form-page .btn-save {
    color: #fffefa;
    background: var(--diary-ink);
    border: 1px solid var(--diary-ink);
    box-shadow: 0 4px 10px rgba(38,59,53,.12);
}
.health-form-page .btn-save:hover { color: #fffefa; background: #1d302b; border-color: #1d302b; transform: translateY(-1px); }
.health-form-page .btn-cancel {
    color: var(--diary-soft);
    background: #fffefa;
    border: 1px solid #dcded3;
}
.health-form-page .btn-cancel:hover { color: var(--diary-ink); background: #f5f3e9; border-color: #c8cdbf; }
.health-form-page .btn:focus-visible { outline: 3px solid rgba(82,127,112,.25); outline-offset: 2px; }
@media (max-width: 991.98px) {
    .health-form-page { padding: 18px; }
    .health-form-page .page-header { padding: 26px; }
    .health-form-page .form-card-body { padding: 19px; }
}
@media (max-width: 767.98px) {
    .health-form-page { padding: 14px; }
    .health-form-page .page-header { min-height: 0; padding: 23px 20px; }
    .health-form-page .page-header h2 { font-size: 25px; }
    .health-form-page .student-card { padding: 18px; }
    .health-form-page .student-profile { align-items: flex-start; gap: 13px; }
    .health-form-page .student-photo,
    .health-form-page .student-placeholder { flex-basis: 60px; width: 60px; height: 60px; }
    .health-form-page .student-name { font-size: 17px; }
    .health-form-page .form-card-header { padding: 14px 16px; }
    .health-form-page .form-card-body { padding: 16px; }
    .health-form-page .form-actions { flex-direction: column-reverse; align-items: stretch; padding: 14px; }
    .health-form-page .btn-save,
    .health-form-page .btn-cancel { width: 100%; }
}
@media (max-width: 480px) {
    .health-form-page { padding: 10px; font-size: 12px; }
    .health-form-page .page-header { padding: 20px 16px; border-radius: 12px; }
    .health-form-page .page-header h2 { font-size: 22px; }
    .health-form-page .page-header h2 i { width: 36px; height: 36px; font-size: 16px; }
    .health-form-page .student-profile { align-items: flex-start; }
    .health-form-page .student-photo,
    .health-form-page .student-placeholder { flex-basis: 52px; width: 52px; height: 52px; }
    .health-form-page .student-meta { gap: 5px; }
    .health-form-page .meta-badge { padding: 4px 7px; font-size: 10px; }
    .health-form-page .form-card-body { padding: 13px; }
}
@media (prefers-reduced-motion: reduce) {
    .health-form-page *,
    .health-form-page *::before,
    .health-form-page *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
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
