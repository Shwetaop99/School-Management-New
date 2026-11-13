@extends('layouts.app')

@section('title', 'Student Health Record | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — STUDENT HEALTH RECORD
    ========================================================= */

    .health-show-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .health-show-page *,
    .health-show-page *::before,
    .health-show-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .health-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .health-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .health-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .health-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .health-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .health-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .health-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .health-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .health-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .health-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    .health-header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-back,
    .btn-scheme-edit,
    .btn-scheme-print {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-back {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-back:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-scheme-edit {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-edit:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-print {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c5dcc8;
    }

    .btn-scheme-print:hover {
        background: #dce9d9;
        border-color: #83aa8a;
    }

    /* ---------- CARDS ---------- */
    .health-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .health-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
        border-bottom: 1px solid #e9e5d9;
    }

    .health-card-header.accent {
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-bottom: none;
    }

    .health-card-header.accent .health-card-title,
    .health-card-header.accent .health-card-subtitle {
        color: #fffefa;
    }

    .health-card-header.accent .health-card-icon {
        background: rgba(255,255,255,.15);
        border-color: rgba(255,255,255,.25);
        color: #fffefa;
    }

    .health-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 10px;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .health-card-title {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
    }

    .health-card-subtitle {
        margin: 3px 0 0;
        color: #7a857d;
        font-size: 10px;
    }

    .health-card-body {
        padding: 20px;
    }

    /* ---------- STUDENT PROFILE STRIP ---------- */
    .profile-strip {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-photo {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fffefa;
        outline: 3px solid #c5dcc8;
        background: #fffefa;
        box-shadow: 0 4px 14px rgba(38, 59, 53, .12);
        flex-shrink: 0;
    }

    .profile-photo-placeholder {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(145deg, #eaf3e9, #d8e8d8);
        border: 4px solid #fffefa;
        outline: 3px solid #c5dcc8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        color: #34775a;
        box-shadow: 0 4px 14px rgba(38, 59, 53, .1);
        flex-shrink: 0;
    }

    .profile-name {
        margin: 0 0 10px;
        color: #263b35;
        font-size: 18px;
        font-weight: 750;
        line-height: 1.3;
    }

    .profile-meta {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px 20px;
    }

    .meta-item label {
        display: block;
        margin-bottom: 4px;
        color: #82897e;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .meta-item span {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
    }

    /* ---------- INFO GRID ---------- */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        overflow: hidden;
    }

    .info-item {
        padding: 14px 15px;
        border-right: 1px solid #e9e5d9;
        border-bottom: 1px solid #e9e5d9;
        min-height: 72px;
        background: #fffefa;
        transition: background .15s ease;
    }

    .info-item:hover {
        background: #faf9f3;
    }

    .info-item:nth-child(4n) {
        border-right: 0;
    }

    .info-item.span-2 {
        grid-column: span 2;
    }

    .info-item.span-full {
        grid-column: 1 / -1;
    }

    .info-label {
        display: block;
        margin-bottom: 6px;
        color: #82897e;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .info-value {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
        word-break: break-word;
        line-height: 1.4;
    }

    .info-value.muted {
        color: #a0a397;
        font-weight: 500;
    }

    /* ---------- TEXT BOX ---------- */
    .text-box {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 9px;
        padding: 12px 14px;
        color: #405149;
        font-size: 12px;
        line-height: 1.6;
        min-height: 48px;
        white-space: pre-line;
    }

    /* ---------- VISION TABLE ---------- */
    .vision-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        overflow: hidden;
    }

    .vision-table thead th {
        padding: 12px 14px;
        background: #f8f6ed;
        border-bottom: 1px solid #e5e1d3;
        color: #5c6b62;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        text-align: left;
    }

    .vision-table tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #efecdf;
        color: #263b35;
        font-weight: 600;
    }

    .vision-table tbody tr:last-child td {
        border-bottom: none;
    }

    .vision-table tbody tr:hover td {
        background: #faf9f3;
    }

    /* ---------- STATUS BADGE ---------- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.3;
    }

    .status-badge.good {
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
    }

    .status-badge.neutral {
        color: #6b726c;
        background: #f0eee6;
        border: 1px solid #ddd9cb;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 991.98px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-item:nth-child(4n) {
            border-right: 1px solid #e9e5d9;
        }

        .info-item:nth-child(2n) {
            border-right: 0;
        }

        .profile-meta {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .health-header {
            padding: 18px 16px;
        }

        .health-title {
            font-size: 19px;
        }

        .health-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .health-header-content {
            flex-direction: column;
        }

        .health-header-actions {
            width: 100%;
        }

        .btn-scheme-back,
        .btn-scheme-edit,
        .btn-scheme-print {
            flex: 1;
        }

        .profile-strip {
            flex-direction: column;
            text-align: center;
        }

        .profile-meta {
            width: 100%;
            text-align: left;
        }

        .health-card-body {
            padding: 16px;
        }
    }

    @media (max-width: 480px) {
        .health-title {
            font-size: 17px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            border-right: 0 !important;
        }

        .info-item.span-2 {
            grid-column: auto;
        }

        .health-header-actions {
            flex-direction: column-reverse;
        }

        .btn-scheme-back,
        .btn-scheme-edit,
        .btn-scheme-print {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .health-show-page *,
        .health-show-page *::before,
        .health-show-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="health-show-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="health-header">
        <div class="health-header-content">
            <div>
                <div class="health-breadcrumb">
                    <a href="{{ route('admin.student-health.index') }}">Health Records</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Record Details</span>
                </div>

                <div class="health-title-row">
                    <div class="health-title-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div>
                        <h1 class="health-title">Student Health Record</h1>
                        <p class="health-subtitle">Annual health checkup details</p>
                    </div>
                </div>
            </div>

            <div class="health-header-actions">
                <a href="{{ route('admin.student-health.index') }}" class="btn-scheme-back">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
                <a href="{{ route('admin.student-health.edit', $healthRecord) }}" class="btn-scheme-edit">
                    <i class="bi bi-pencil"></i>
                    Edit
                </a>
                <a href="{{ route('admin.student-health.print', $healthRecord) }}"
                   target="_blank"
                   class="btn-scheme-print">
                    <i class="bi bi-printer"></i>
                    Print
                </a>
            </div>
        </div>
    </div>

    {{-- ===================== STUDENT PROFILE ===================== --}}
    <div class="health-card">
        <div class="health-card-header accent">
            <div class="health-card-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <div>
                <h5 class="health-card-title">Student Profile</h5>
                <p class="health-card-subtitle">Basic student identification</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="profile-strip">
                @if($healthRecord->student->profile_image)
                    <img src="{{ $healthRecord->student->profile_image }}"
                         alt="Student Photo"
                         class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                @endif

                <div style="flex:1; min-width:0;">
                    <h4 class="profile-name">
                        {{ $healthRecord->student->first_name }}
                        {{ $healthRecord->student->middle_name }}
                        {{ $healthRecord->student->last_name }}
                    </h4>

                    <div class="profile-meta">
                        <div class="meta-item">
                            <label>Student ID</label>
                            <span>{{ $healthRecord->student->student_id ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Class</label>
                            <span>{{ $healthRecord->student->class ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Section</label>
                            <span>{{ $healthRecord->student->section ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Roll Number</label>
                            <span>{{ $healthRecord->student->roll_number ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== CHECKUP INFORMATION ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <h5 class="health-card-title">Checkup Information</h5>
                <p class="health-card-subtitle">When and where the checkup was conducted</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Academic Year</span>
                    <div class="info-value {{ $healthRecord->academic_year ? '' : 'muted' }}">
                        {{ $healthRecord->academic_year ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Checkup Date</span>
                    <div class="info-value {{ $healthRecord->checkup_date ? '' : 'muted' }}">
                        {{ $healthRecord->checkup_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Checkup Type</span>
                    <div class="info-value {{ $healthRecord->checkup_type ? '' : 'muted' }}">
                        {{ $healthRecord->checkup_type ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Doctor</span>
                    <div class="info-value {{ $healthRecord->doctor_name ? '' : 'muted' }}">
                        {{ $healthRecord->doctor_name ?: '—' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Health Center</span>
                    <div class="info-value {{ $healthRecord->health_center ? '' : 'muted' }}">
                        {{ $healthRecord->health_center ?: '—' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Conducted By</span>
                    <div class="info-value {{ $healthRecord->conducted_by ? '' : 'muted' }}">
                        {{ $healthRecord->conducted_by ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== PHYSICAL MEASUREMENTS ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-rulers"></i>
            </div>
            <div>
                <h5 class="health-card-title">Physical Measurements</h5>
                <p class="health-card-subtitle">Height, weight, BMI and vital signs</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Height</span>
                    <div class="info-value {{ $healthRecord->height ? '' : 'muted' }}">
                        {{ $healthRecord->height ? $healthRecord->height . ' cm' : '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Weight</span>
                    <div class="info-value {{ $healthRecord->weight ? '' : 'muted' }}">
                        {{ $healthRecord->weight ? $healthRecord->weight . ' kg' : '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">BMI</span>
                    <div class="info-value {{ $healthRecord->bmi ? '' : 'muted' }}">
                        {{ $healthRecord->bmi ?? '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Pulse Rate</span>
                    <div class="info-value {{ $healthRecord->pulse_rate ? '' : 'muted' }}">
                        {{ $healthRecord->pulse_rate ? $healthRecord->pulse_rate . ' bpm' : '—' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Blood Pressure</span>
                    <div class="info-value {{ $healthRecord->blood_pressure ? '' : 'muted' }}">
                        {{ $healthRecord->blood_pressure ?: '—' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Temperature</span>
                    <div class="info-value {{ $healthRecord->temperature ? '' : 'muted' }}">
                        {{ $healthRecord->temperature ? $healthRecord->temperature . ' °C' : '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== VISION ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-eye"></i>
            </div>
            <div>
                <h5 class="health-card-title">Vision Examination</h5>
                <p class="health-card-subtitle">Distance and near vision results</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="table-responsive mb-3">
                <table class="vision-table">
                    <thead>
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

            <div class="info-grid">
                <div class="info-item span-2">
                    <span class="info-label">Uses Spectacles</span>
                    <div class="info-value">
                        {{ $healthRecord->uses_spectacles ? 'Yes' : 'No' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Spectacle Power</span>
                    <div class="info-value {{ $healthRecord->spectacle_power ? '' : 'muted' }}">
                        {{ $healthRecord->spectacle_power ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== DENTAL ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-emoji-smile"></i>
            </div>
            <div>
                <h5 class="health-card-title">Dental Examination</h5>
                <p class="health-card-subtitle">Oral and dental health status</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Dental Status</span>
                    <div class="info-value {{ $healthRecord->dental_status ? '' : 'muted' }}">
                        {{ $healthRecord->dental_status ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Dental Caries</span>
                    <div class="info-value {{ $healthRecord->dental_caries ? '' : 'muted' }}">
                        {{ $healthRecord->dental_caries ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Gum Problem</span>
                    <div class="info-value {{ $healthRecord->gum_problem ? '' : 'muted' }}">
                        {{ $healthRecord->gum_problem ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Oral Hygiene</span>
                    <div class="info-value {{ $healthRecord->oral_hygiene ? '' : 'muted' }}">
                        {{ $healthRecord->oral_hygiene ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== ENT ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-ear"></i>
            </div>
            <div>
                <h5 class="health-card-title">ENT Examination</h5>
                <p class="health-card-subtitle">Ear, nose and throat findings</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Right Ear</span>
                    <div class="info-value {{ $healthRecord->right_ear ? '' : 'muted' }}">
                        {{ $healthRecord->right_ear ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Left Ear</span>
                    <div class="info-value {{ $healthRecord->left_ear ? '' : 'muted' }}">
                        {{ $healthRecord->left_ear ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Hearing Problem</span>
                    <div class="info-value {{ $healthRecord->hearing_problem ? '' : 'muted' }}">
                        {{ $healthRecord->hearing_problem ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Nose</span>
                    <div class="info-value {{ $healthRecord->nose_status ? '' : 'muted' }}">
                        {{ $healthRecord->nose_status ?: '—' }}
                    </div>
                </div>
                <div class="info-item span-2">
                    <span class="info-label">Throat</span>
                    <div class="info-value {{ $healthRecord->throat_status ? '' : 'muted' }}">
                        {{ $healthRecord->throat_status ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== GENERAL HEALTH ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-heart-pulse"></i>
            </div>
            <div>
                <h5 class="health-card-title">General Health Examination</h5>
                <p class="health-card-subtitle">System-wise health assessment</p>
            </div>
        </div>

        <div class="health-card-body">
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

            <div class="info-grid">
                @foreach($generalFields as $field => $label)
                    <div class="info-item">
                        <span class="info-label">{{ $label }}</span>
                        <div class="info-value {{ $healthRecord->{$field} ? '' : 'muted' }}">
                            {{ $healthRecord->{$field} ?: '—' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===================== MEDICAL HISTORY ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-journal-medical"></i>
            </div>
            <div>
                <h5 class="health-card-title">Medical History</h5>
                <p class="health-card-subtitle">Known conditions, allergies and medications</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <span class="info-label">Known Health Condition</span>
                    <div class="text-box">{{ $healthRecord->known_health_condition ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">Allergy</span>
                    <div class="text-box">{{ $healthRecord->allergy ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">Current Medication</span>
                    <div class="text-box">{{ $healthRecord->current_medication ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">Medical History</span>
                    <div class="text-box">{{ $healthRecord->medical_history ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== REFERRAL ===================== --}}
    <div class="health-card">
        <div class="health-card-header">
            <div class="health-card-icon">
                <i class="bi bi-hospital"></i>
            </div>
            <div>
                <h5 class="health-card-title">Referral & Follow-up</h5>
                <p class="health-card-subtitle">Referral details and follow-up plan</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="info-grid mb-3">
                <div class="info-item">
                    <span class="info-label">Referral Required</span>
                    <div class="info-value">
                        {{ $healthRecord->referral_required ? 'Yes' : 'No' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Referred To</span>
                    <div class="info-value {{ $healthRecord->referral_to ? '' : 'muted' }}">
                        {{ $healthRecord->referral_to ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Referral Date</span>
                    <div class="info-value {{ $healthRecord->referral_date ? '' : 'muted' }}">
                        {{ $healthRecord->referral_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Follow-up Date</span>
                    <div class="info-value {{ $healthRecord->follow_up_date ? '' : 'muted' }}">
                        {{ $healthRecord->follow_up_date?->format('d-m-Y') ?: '—' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <span class="info-label">Treatment Advised</span>
                    <div class="text-box">{{ $healthRecord->treatment_advised ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">Follow-up Status</span>
                    <div class="text-box">{{ $healthRecord->follow_up_status ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <span class="info-label">Follow-up Remarks</span>
                    <div class="text-box">{{ $healthRecord->follow_up_remarks ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== FINAL ASSESSMENT ===================== --}}
    <div class="health-card">
        <div class="health-card-header accent">
            <div class="health-card-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <h5 class="health-card-title">Final Assessment</h5>
                <p class="health-card-subtitle">Overall status and remarks</p>
            </div>
        </div>

        <div class="health-card-body">
            <div class="mb-4">
                <span class="info-label">Overall Health Status</span>
                <div class="mt-2">
                    @if($healthRecord->overall_health_status)
                        <span class="status-badge good">
                            {{ $healthRecord->overall_health_status }}
                        </span>
                    @else
                        <span class="info-value muted">—</span>
                    @endif
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <span class="info-label">Doctor's Remarks</span>
                    <div class="text-box">{{ $healthRecord->doctor_remarks ?: '—' }}</div>
                </div>
                <div class="col-md-4">
                    <span class="info-label">Teacher's Remarks</span>
                    <div class="text-box">{{ $healthRecord->teacher_remarks ?: '—' }}</div>
                </div>
                <div class="col-md-4">
                    <span class="info-label">Parent's Remarks</span>
                    <div class="text-box">{{ $healthRecord->parent_remarks ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection