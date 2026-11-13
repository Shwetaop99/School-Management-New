@extends('layouts.app')

@section('title', 'Student Profile | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — STUDENT PROFILE (ENHANCED)
    ========================================================= */

    .student-profile-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .student-profile-page *,
    .student-profile-page *::before,
    .student-profile-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .student-profile-header {
        position: relative;
        overflow: hidden;
        padding: 24px 26px;
        margin-bottom: 22px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .student-profile-header::after {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(52, 119, 90, .06) 0%, transparent 70%);
        pointer-events: none;
    }

    .profile-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .profile-header-left {
        min-width: 0;
    }

    .profile-breadcrumb {
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

    .profile-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .profile-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .profile-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .profile-title-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .profile-title-icon {
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

    .profile-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .profile-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    .profile-header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-back,
    .btn-scheme-edit,
    .btn-scheme-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 10px;
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
        border-color: #113a28;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-delete {
        color: #bd5148;
        background: #fffefa;
        border-color: #e0b8b2;
    }

    .btn-scheme-delete:hover {
        color: #a23d36;
        background: #fff1ed;
        border-color: #dba39a;
    }

    /* ---------- LAYOUT ---------- */
    .student-layout {
        display: grid;
        grid-template-columns: 290px minmax(0, 1fr);
        gap: 20px;
        align-items: start;
    }

    /* ---------- LEFT IDENTITY PANEL ---------- */
    .identity-panel {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 22px rgba(22, 40, 35, 0.07);
        position: sticky;
        top: 20px;
    }

    .identity-cover {
        height: 88px;
        background:
            radial-gradient(circle at 20% 30%, rgba(255,255,255,.14), transparent 40%),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,.08), transparent 35%),
            linear-gradient(135deg, #093120 0%, #205d43 100%);
        position: relative;
    }

    .identity-cover::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 28px;
        background: linear-gradient(to top, #fffefa, transparent);
    }

    .identity-body {
        padding: 0 18px 24px;
        text-align: center;
        margin-top: -8px;
        position: relative;
        z-index: 1;
    }

    .profile-image-area {
        margin-top: -52px;
        margin-bottom: 14px;
    }

    .profile-image {
        width: 102px;
        height: 102px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fffefa;
        outline: 3px solid #c5dcc8;
        background: #fffefa;
        box-shadow: 0 6px 18px rgba(38, 59, 53, .15);
    }

    .profile-placeholder {
        width: 102px;
        height: 102px;
        border-radius: 50%;
        margin: 0 auto;
        background: linear-gradient(145deg, #eaf3e9, #d8e8d8);
        border: 4px solid #fffefa;
        outline: 3px solid #c5dcc8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #34775a;
        box-shadow: 0 6px 18px rgba(38, 59, 53, .12);
    }

    .identity-name {
        font-size: 17px;
        font-weight: 750;
        color: #263b35;
        line-height: 1.3;
        letter-spacing: -.2px;
    }

    .identity-marathi {
        color: #7a857d;
        font-size: 12px;
        margin-top: 5px;
    }

    .identity-class {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        padding: 5px 12px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #d4e4d1;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 650;
    }

    .identity-status {
        margin-top: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
    }

    .status-badge.active {
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
    }

    .status-badge.inactive {
        color: #6b726c;
        background: #f0eee6;
        border: 1px solid #ddd9cb;
    }

    .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .identity-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e1d3, transparent);
        margin: 18px 0;
    }

    .identity-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        text-align: left;
        margin-bottom: 14px;
        padding: 8px 10px;
        border-radius: 9px;
        transition: background .15s ease;
    }

    .identity-row:hover {
        background: #f8f6ed;
    }

    .identity-row:last-child {
        margin-bottom: 0;
    }

    .identity-row-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 9px;
        background: linear-gradient(145deg, #eaf3e9, #e0eedf);
        border: 1px solid #d4e4d1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #34775a;
        font-size: 14px;
    }

    .identity-row-label {
        color: #82897e;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .4px;
    }

    .identity-row-value {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
        margin-top: 2px;
        word-break: break-word;
    }

    /* ---------- RIGHT CONTENT ---------- */
    .content-area {
        min-width: 0;
    }

    .content-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        margin-bottom: 18px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
        transition: box-shadow .2s ease;
    }

    .content-card:hover {
        box-shadow: 0 6px 20px rgba(38, 59, 53, .07);
    }

    .content-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e9e5d9;
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
    }

    .content-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .content-title {
        margin: 0;
        font-size: 13px;
        font-weight: 750;
        color: #263b35;
    }

    .content-subtitle {
        margin: 3px 0 0;
        color: #7a857d;
        font-size: 10px;
    }

    .content-body {
        padding: 20px;
    }

    /* ---------- INFORMATION GRID ---------- */
    .information-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        overflow: hidden;
        background: #fffefa;
    }

    .information-item {
        padding: 14px 15px;
        border-right: 1px solid #e9e5d9;
        border-bottom: 1px solid #e9e5d9;
        min-height: 74px;
        transition: background .15s ease;
    }

    .information-item:hover {
        background: #faf9f3;
    }

    .information-item:nth-child(4n) {
        border-right: 0;
    }

    .information-label {
        color: #82897e;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .45px;
        margin-bottom: 6px;
    }

    .information-value {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
        word-break: break-word;
        line-height: 1.4;
    }

    .empty-value {
        color: #a8aea4;
        font-weight: 500;
    }

    /* ---------- ID STRIP ---------- */
    .id-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 11px;
        margin-bottom: 18px;
    }

    .id-item {
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        padding: 14px 15px;
        background: linear-gradient(180deg, #faf8f0 0%, #f5f3e9 100%);
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .id-item:hover {
        border-color: #c5d1c2;
        box-shadow: 0 2px 8px rgba(52, 119, 90, .06);
    }

    .id-label {
        color: #82897e;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .4px;
        margin-bottom: 5px;
    }

    .id-value {
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
        word-break: break-word;
    }

    /* ---------- PARENT BLOCKS ---------- */
    .person-block {
        border: 1px solid #e5e1d3;
        border-radius: 11px;
        padding: 16px;
        height: 100%;
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .person-block:hover {
        border-color: #c5d1c2;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .05);
    }

    .person-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 750;
        color: #263b35;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9e5d9;
    }

    .person-heading-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #34775a;
        font-size: 13px;
    }

    .person-info {
        margin-bottom: 11px;
    }

    .person-info:last-child {
        margin-bottom: 0;
    }

    .person-label {
        color: #82897e;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 3px;
        letter-spacing: .3px;
    }

    .person-value {
        color: #263b35;
        font-size: 12px;
        font-weight: 650;
    }

    /* ---------- TEXT BOX ---------- */
    .text-section {
        margin-bottom: 14px;
    }

    .text-section:last-child {
        margin-bottom: 0;
    }

    .text-label {
        color: #82897e;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .4px;
        margin-bottom: 6px;
    }

    .text-box {
        background: linear-gradient(180deg, #faf8f0 0%, #f7f5ec 100%);
        border: 1px solid #e5e1d3;
        border-radius: 9px;
        padding: 13px 15px;
        color: #405149;
        font-size: 12px;
        line-height: 1.65;
        min-height: 48px;
        white-space: pre-line;
    }

    /* ---------- BOTTOM BAR ---------- */
    .bottom-bar {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .bottom-note {
        color: #7a857d;
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .bottom-note i {
        color: #34775a;
    }

    .bottom-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 1100px) {
        .student-layout {
            grid-template-columns: 260px minmax(0, 1fr);
        }

        .information-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .information-item:nth-child(4n) {
            border-right: 1px solid #e9e5d9;
        }

        .information-item:nth-child(3n) {
            border-right: 0;
        }
    }

    @media (max-width: 900px) {
        .student-layout {
            grid-template-columns: 1fr;
        }

        .identity-panel {
            position: static;
        }

        .id-strip {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .student-profile-header {
            padding: 18px 16px;
            margin-bottom: 16px;
        }

        .profile-title {
            font-size: 19px;
        }

        .profile-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .profile-header-content {
            flex-direction: column;
        }

        .profile-header-actions {
            width: 100%;
        }

        .btn-scheme-back,
        .btn-scheme-edit {
            flex: 1;
        }

        .information-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .information-item:nth-child(3n) {
            border-right: 1px solid #e9e5d9;
        }

        .information-item:nth-child(2n) {
            border-right: 0;
        }

        .content-body {
            padding: 16px;
        }
    }

    @media (max-width: 480px) {
        .profile-title {
            font-size: 17px;
        }

        .profile-subtitle {
            font-size: 10px;
        }

        .profile-breadcrumb {
            font-size: 9px;
        }

        .profile-header-actions {
            flex-direction: column-reverse;
        }

        .btn-scheme-back,
        .btn-scheme-edit {
            width: 100%;
        }

        .id-strip {
            grid-template-columns: 1fr;
        }

        .information-grid {
            grid-template-columns: 1fr;
        }

        .information-item {
            border-right: 0 !important;
        }

        .bottom-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .bottom-actions {
            width: 100%;
        }

        .bottom-actions .btn-scheme-back,
        .bottom-actions .btn-scheme-edit,
        .bottom-actions .btn-scheme-delete,
        .bottom-actions form {
            flex: 1;
        }

        .bottom-actions form button {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .student-profile-page *,
        .student-profile-page *::before,
        .student-profile-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="student-profile-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="student-profile-header">
        <div class="profile-header-content">
            <div class="profile-header-left">
                <div class="profile-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Student Profile</span>
                </div>

                <div class="profile-title-row">
                    <div class="profile-title-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <h1 class="profile-title">Student Profile</h1>
                        <p class="profile-subtitle">Complete student registration details</p>
                    </div>
                </div>
            </div>

            <div class="profile-header-actions">
                <a href="{{ route('admin.students.index') }}" class="btn-scheme-back">
                    <i class="bi bi-arrow-left"></i>
                    Students
                </a>
                <a href="{{ route('admin.students.edit', $student) }}" class="btn-scheme-edit">
                    <i class="bi bi-pencil"></i>
                    Edit Student
                </a>
            </div>
        </div>
    </div>

    {{-- ===================== MAIN LAYOUT ===================== --}}
    <div class="student-layout">

        {{-- LEFT IDENTITY PANEL --}}
        <div class="identity-panel">
            <div class="identity-cover"></div>

            <div class="identity-body">
                <div class="profile-image-area">
                    @if($student->profile_image)
                        <img src="{{ $student->profile_image }}"
                             alt="{{ $student->first_name }} {{ $student->last_name }}"
                             class="profile-image">
                    @else
                        <div class="profile-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                </div>

                <div class="identity-name">
                    {{ $student->first_name }}
                    {{ $student->middle_name }}
                    {{ $student->last_name }}
                </div>

                @if($student->marathi_name)
                    <div class="identity-marathi">{{ $student->marathi_name }}</div>
                @endif

                <div class="identity-class">
                    <i class="bi bi-mortarboard"></i>
                    Class {{ $student->class ?? '—' }}
                    @if($student->section)
                        · Section {{ $student->section }}
                    @endif
                </div>

                <div class="identity-status">
                    @if($student->status === 'active')
                        <span class="status-badge active">
                            <span class="dot"></span>
                            Active Student
                        </span>
                    @else
                        <span class="status-badge inactive">
                            <span class="dot"></span>
                            Inactive Student
                        </span>
                    @endif
                </div>

                <div class="identity-divider"></div>

                <div class="identity-row">
                    <div class="identity-row-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="identity-row-label">Student ID</div>
                        <div class="identity-row-value">{{ $student->student_id ?? '—' }}</div>
                    </div>
                </div>

                <div class="identity-row">
                    <div class="identity-row-icon">
                        <i class="bi bi-list-ol"></i>
                    </div>
                    <div>
                        <div class="identity-row-label">Roll Number</div>
                        <div class="identity-row-value">{{ $student->roll_number ?? '—' }}</div>
                    </div>
                </div>

                <div class="identity-row">
                    <div class="identity-row-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="identity-row-label">Academic Year</div>
                        <div class="identity-row-value">{{ $student->academic_year ?? '—' }}</div>
                    </div>
                </div>

                <div class="identity-row">
                    <div class="identity-row-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <div class="identity-row-label">Phone</div>
                        <div class="identity-row-value">{{ $student->phone ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="content-area">

            {{-- BASIC INFORMATION --}}
            <div class="content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <div>
                        <h5 class="content-title">Basic Information</h5>
                        <p class="content-subtitle">Personal details of the student</p>
                    </div>
                </div>

                <div class="content-body">
                    <div class="information-grid">
                        <div class="information-item">
                            <div class="information-label">First Name</div>
                            <div class="information-value">{{ $student->first_name ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Middle Name</div>
                            <div class="information-value {{ $student->middle_name ? '' : 'empty-value' }}">{{ $student->middle_name ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Last Name</div>
                            <div class="information-value">{{ $student->last_name ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Gender</div>
                            <div class="information-value">{{ $student->gender ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Date of Birth</div>
                            <div class="information-value">
                                {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : '—' }}
                            </div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Aadhar Number</div>
                            <div class="information-value">{{ $student->aadhar_card_no ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Phone</div>
                            <div class="information-value">{{ $student->phone ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Email</div>
                            <div class="information-value">{{ $student->email ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Marathi Name</div>
                            <div class="information-value">{{ $student->marathi_name ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Nationality</div>
                            <div class="information-value">{{ $student->nationality ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Religion</div>
                            <div class="information-value">{{ $student->religion ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Status</div>
                            <div class="information-value">
                                @if($student->status === 'active')
                                    <span class="status-badge active">
                                        <span class="dot"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        <span class="dot"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACADEMIC INFORMATION --}}
            <div class="content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div>
                        <h5 class="content-title">Academic Information</h5>
                        <p class="content-subtitle">Class, admission and official IDs</p>
                    </div>
                </div>

                <div class="content-body">
                    <div class="id-strip">
                        <div class="id-item">
                            <div class="id-label">Register No.</div>
                            <div class="id-value">{{ $student->register_no ?? '—' }}</div>
                        </div>
                        <div class="id-item">
                            <div class="id-label">Book No.</div>
                            <div class="id-value">{{ $student->book_no ?? '—' }}</div>
                        </div>
                        <div class="id-item">
                            <div class="id-label">APAR ID</div>
                            <div class="id-value">{{ $student->appar_id ?? '—' }}</div>
                        </div>
                        <div class="id-item">
                            <div class="id-label">PEN No.</div>
                            <div class="id-value">{{ $student->pen_no ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="information-grid">
                        <div class="information-item">
                            <div class="information-label">Academic Year</div>
                            <div class="information-value">{{ $student->academic_year ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Class</div>
                            <div class="information-value">{{ $student->class ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Section</div>
                            <div class="information-value">{{ $student->section ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Roll Number</div>
                            <div class="information-value">{{ $student->roll_number ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Admission Class</div>
                            <div class="information-value">{{ $student->admission_class ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Admission Date</div>
                            <div class="information-value">
                                {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') : '—' }}
                            </div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Medium</div>
                            <div class="information-value">{{ $student->medium ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Mother Tongue</div>
                            <div class="information-value">{{ $student->mother_tongue ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Nationality</div>
                            <div class="information-value">{{ $student->nationality ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Religion</div>
                            <div class="information-value">{{ $student->religion ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Caste</div>
                            <div class="information-value">{{ $student->caste ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Sub Caste</div>
                            <div class="information-value">{{ $student->sub_caste ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARENTS & GUARDIAN --}}
            <div class="content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h5 class="content-title">Parents & Guardian</h5>
                        <p class="content-subtitle">Family contact information</p>
                    </div>
                </div>

                <div class="content-body">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="person-block">
                                <div class="person-heading">
                                    <div class="person-heading-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    Father
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Name</div>
                                    <div class="person-value">{{ $student->father_name ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Phone</div>
                                    <div class="person-value">{{ $student->father_phone ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Occupation</div>
                                    <div class="person-value">{{ $student->father_occupation ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="person-block">
                                <div class="person-heading">
                                    <div class="person-heading-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    Mother
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Name</div>
                                    <div class="person-value">{{ $student->mother_name ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Phone</div>
                                    <div class="person-value">{{ $student->mother_phone ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Occupation</div>
                                    <div class="person-value">{{ $student->mother_occupation ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="person-block">
                                <div class="person-heading">
                                    <div class="person-heading-icon">
                                        <i class="bi bi-person-vcard"></i>
                                    </div>
                                    Guardian
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Name</div>
                                    <div class="person-value">{{ $student->guardian_name ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Relation</div>
                                    <div class="person-value">{{ $student->guardian_relation ?? '—' }}</div>
                                </div>
                                <div class="person-info">
                                    <div class="person-label">Phone</div>
                                    <div class="person-value">{{ $student->guardian_phone ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PREVIOUS SCHOOL --}}
            <div class="content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h5 class="content-title">Previous School Information</h5>
                        <p class="content-subtitle">Details of the last attended school</p>
                    </div>
                </div>

                <div class="content-body">
                    <div class="information-grid">
                        <div class="information-item">
                            <div class="information-label">School Name</div>
                            <div class="information-value">{{ $student->previous_school_name ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Class</div>
                            <div class="information-value">{{ $student->previous_school_class ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Medium</div>
                            <div class="information-value">{{ $student->previous_school_medium ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Board</div>
                            <div class="information-value">{{ $student->previous_school_board ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Result</div>
                            <div class="information-value">{{ $student->previous_school_result ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-section">
                            <div class="text-label">School Address</div>
                            <div class="text-box">{{ $student->previous_school_address ?? '—' }}</div>
                        </div>
                        <div class="text-section">
                            <div class="text-label">Remarks</div>
                            <div class="text-box">{{ $student->previous_school_remarks ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADDRESS --}}
            <div class="content-card">
                <div class="content-header">
                    <div class="content-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <h5 class="content-title">Address Information</h5>
                        <p class="content-subtitle">Current residential address</p>
                    </div>
                </div>

                <div class="content-body">
                    <div class="information-grid">
                        <div class="information-item">
                            <div class="information-label">Country</div>
                            <div class="information-value">{{ $student->country ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">State</div>
                            <div class="information-value">{{ $student->state ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">District</div>
                            <div class="information-value">{{ $student->district ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Taluka</div>
                            <div class="information-value">{{ $student->taluka ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">City / Village</div>
                            <div class="information-value">{{ $student->city_village ?? '—' }}</div>
                        </div>
                        <div class="information-item">
                            <div class="information-label">Pincode</div>
                            <div class="information-value">{{ $student->pincode ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-label">Full Address</div>
                        <div class="text-box">{{ $student->address ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- BOTTOM ACTIONS --}}
            <div class="bottom-bar">
                <div class="bottom-note">
                    <i class="bi bi-info-circle"></i>
                    Student profile and registration information
                </div>

                <div class="bottom-actions">
                    <a href="{{ route('admin.students.index') }}" class="btn-scheme-back">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.students.edit', $student) }}" class="btn-scheme-edit">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>

                    <form action="{{ route('admin.students.destroy', $student) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this student?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-scheme-delete">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection