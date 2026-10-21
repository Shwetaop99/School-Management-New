@extends('layouts.app')

@section('content')

<style>
    .student-page {
        background: #f3f5f9;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

    /* =========================================
       TOP BAR
    ========================================= */
    .page-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 15px;
    }

    .page-title {
        font-size: 23px;
        font-weight: 700;
        color: #172033;
        margin: 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #7b8494;
        margin-top: 3px;
    }

    .top-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* =========================================
       MAIN PROFILE
    ========================================= */
    .student-layout {
        display: grid;
        grid-template-columns: 280px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    /* =========================================
       LEFT PROFILE
    ========================================= */
    .identity-panel {
        background: #2058a1;
        color: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(15,23,42,.12);
        position: sticky;
        top: 20px;
    }

    .identity-cover {
        height: 85px;
        background:
            radial-gradient(circle at 20% 30%, rgba(255,255,255,.10), transparent 30%),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,.08), transparent 30%),
            #8ba4c6;
    }

    .identity-body {
        padding: 0 20px 22px;
        text-align: center;
    }

    .profile-image-area {
        margin-top: -52px;
        margin-bottom: 13px;
    }

    .profile-image {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #111827;
        outline: 3px solid #fff;
        background: #fff;
    }

    .profile-placeholder {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        margin: auto;
        background: #374151;
        border: 5px solid #111827;
        outline: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        color: #d1d5db;
    }

    .identity-name {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.3;
    }

    .identity-marathi {
        color: #d1d5db;
        font-size: 14px;
        margin-top: 5px;
    }

    .identity-class {
        color: #cbd5e1;
        font-size: 13px;
        margin-top: 9px;
    }

    .identity-status {
        margin-top: 14px;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11px;
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

    .identity-divider {
        height: 1px;
        background: rgba(255,255,255,.10);
        margin: 20px 0;
    }

    .identity-row {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        text-align: left;
        margin-bottom: 15px;
    }

    .identity-row:last-child {
        margin-bottom: 0;
    }

    .identity-row-icon {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 9px;
        background: rgba(255,255,255,.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
    }

    .identity-row-label {
        color: #9ca3af;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .5px;
    }

    .identity-row-value {
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        margin-top: 2px;
        word-break: break-word;
    }

    /* =========================================
       RIGHT CONTENT
    ========================================= */
    .content-area {
        min-width: 0;
    }

    .content-card {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 16px;
        margin-bottom: 18px;
        box-shadow: 0 4px 16px rgba(15,23,42,.045);
        overflow: hidden;
    }

    .content-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .content-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #f1f5f9;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .content-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #172033;
    }

    .content-body {
        padding: 20px;
    }

    /* =========================================
       INFORMATION GRID
    ========================================= */
    .information-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border: 1px solid #e9edf2;
        border-radius: 12px;
        overflow: hidden;
    }

    .information-item {
        padding: 15px;
        border-right: 1px solid #e9edf2;
        border-bottom: 1px solid #e9edf2;
        min-height: 76px;
    }

    .information-item:nth-child(4n) {
        border-right: 0;
    }

    .information-label {
        color: #8a93a2;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .55px;
        margin-bottom: 6px;
    }

    .information-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .empty-value {
        color: #b1b7c1;
        font-weight: 500;
    }

    /* =========================================
       ID STRIP
    ========================================= */
    .id-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 18px;
    }

    .id-item {
        border: 1px solid #e4e8ee;
        border-radius: 12px;
        padding: 14px;
        background: #fafbfc;
    }

    .id-label {
        color: #8a93a2;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .5px;
        margin-bottom: 5px;
    }

    .id-value {
        color: #172033;
        font-size: 13px;
        font-weight: 700;
        word-break: break-word;
    }

    /* =========================================
       PARENT BLOCKS
    ========================================= */
    .person-block {
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 17px;
        height: 100%;
        background: #fbfcfd;
    }

    .person-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13px;
        font-weight: 700;
        color: #172033;
        margin-bottom: 15px;
    }

    .person-heading-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
    }

    .person-info {
        margin-bottom: 12px;
    }

    .person-info:last-child {
        margin-bottom: 0;
    }

    .person-label {
        color: #8a93a2;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .person-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
    }

    /* =========================================
       TEXT BOX
    ========================================= */
    .text-section {
        margin-bottom: 16px;
    }

    .text-section:last-child {
        margin-bottom: 0;
    }

    .text-label {
        color: #8a93a2;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .5px;
        margin-bottom: 7px;
    }

    .text-box {
        background: #f8fafc;
        border: 1px solid #e5e9ef;
        border-radius: 10px;
        padding: 14px;
        color: #374151;
        font-size: 13px;
        line-height: 1.7;
        min-height: 48px;
        white-space: pre-line;
    }

    /* =========================================
       FOOTER
    ========================================= */
    .bottom-bar {
        background: #fff;
        border: 1px solid #e5e9ef;
        border-radius: 15px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 4px 16px rgba(15,23,42,.04);
    }

    .bottom-note {
        color: #8a93a2;
        font-size: 11px;
    }

    .bottom-actions {
        display: flex;
        gap: 8px;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */
    @media(max-width: 1100px) {

        .student-layout {
            grid-template-columns: 240px minmax(0, 1fr);
        }

        .information-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .information-item:nth-child(4n) {
            border-right: 1px solid #e9edf2;
        }

        .information-item:nth-child(3n) {
            border-right: 0;
        }
    }

    @media(max-width: 900px) {

        .student-layout {
            grid-template-columns: 1fr;
        }

        .identity-panel {
            position: static;
        }

        .identity-body {
            text-align: left;
        }

        .profile-image-area {
            text-align: center;
        }

        .identity-name,
        .identity-marathi,
        .identity-class,
        .identity-status {
            text-align: center;
        }

        .identity-row {
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .id-strip {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 650px) {

        .student-page {
            padding: 12px;
        }

        .page-topbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-actions {
            width: 100%;
        }

        .information-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .information-item:nth-child(3n) {
            border-right: 1px solid #e9edf2;
        }

        .information-item:nth-child(2n) {
            border-right: 0;
        }

        .id-strip {
            grid-template-columns: 1fr;
        }

        .bottom-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .bottom-actions {
            width: 100%;
        }

        .bottom-actions .btn {
            flex: 1;
        }
    }
</style>


<div class="student-page">

    {{-- =========================================
         PAGE HEADER
    ========================================== --}}
    <div class="page-topbar">

        <div>
            <h1 class="page-title">
                Student Profile
            </h1>

            <div class="page-subtitle">
                Complete student registration details
            </div>
        </div>


        <div class="top-actions">

            <a href="{{ route('admin.students.index') }}"
               class="btn btn-light border btn-sm px-3">

                <i class="bi bi-arrow-left me-1"></i>
                Students

            </a>


            <a href="{{ route('admin.students.edit', $student) }}"
               class="btn btn-warning btn-sm px-3">

                <i class="bi bi-pencil me-1"></i>
                Edit Student

            </a>

        </div>

    </div>


    {{-- =========================================
         MAIN LAYOUT
    ========================================== --}}
    <div class="student-layout">


        {{-- =====================================
             LEFT IDENTITY PANEL
        ====================================== --}}
        <div class="identity-panel">

            <div class="identity-cover"></div>

            <div class="identity-body">


                {{-- PHOTO --}}
                <div class="profile-image-area">

                    @if($student->profile_image)

                        <img
                            src="{{ $student->profile_image }}"
                            alt="{{ $student->first_name }} {{ $student->last_name }}"
                            class="profile-image"
                        >

                    @else

                        <div class="profile-placeholder">
                            <i class="bi bi-person"></i>
                        </div>

                    @endif

                </div>


                {{-- NAME --}}
                <div class="identity-name">

                    {{ $student->first_name }}
                    {{ $student->middle_name }}
                    {{ $student->last_name }}

                </div>


                @if($student->marathi_name)

                    <div class="identity-marathi">
                        {{ $student->marathi_name }}
                    </div>

                @endif


                <div class="identity-class">

                    <i class="bi bi-mortarboard me-1"></i>

                    Class {{ $student->class ?? '-' }}

                    @if($student->section)
                        &nbsp;•&nbsp; Section {{ $student->section }}
                    @endif

                </div>


                {{-- STATUS --}}
                <div class="identity-status">

                    @if($student->status === 'active')

                        <span class="status status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            Active Student
                        </span>

                    @else

                        <span class="status status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive Student
                        </span>

                    @endif

                </div>


                <div class="identity-divider"></div>


                {{-- STUDENT ID --}}
                <div class="identity-row">

                    <div class="identity-row-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>

                    <div>
                        <div class="identity-row-label">
                            Student ID
                        </div>

                        <div class="identity-row-value">
                            {{ $student->student_id ?? '-' }}
                        </div>
                    </div>

                </div>


                {{-- ROLL NUMBER --}}
                <div class="identity-row">

                    <div class="identity-row-icon">
                        <i class="bi bi-list-ol"></i>
                    </div>

                    <div>
                        <div class="identity-row-label">
                            Roll Number
                        </div>

                        <div class="identity-row-value">
                            {{ $student->roll_number ?? '-' }}
                        </div>
                    </div>

                </div>


                {{-- ACADEMIC YEAR --}}
                <div class="identity-row">

                    <div class="identity-row-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div>
                        <div class="identity-row-label">
                            Academic Year
                        </div>

                        <div class="identity-row-value">
                            {{ $student->academic_year ?? '-' }}
                        </div>
                    </div>

                </div>


                {{-- PHONE --}}
                <div class="identity-row">

                    <div class="identity-row-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <div>
                        <div class="identity-row-label">
                            Phone
                        </div>

                        <div class="identity-row-value">
                            {{ $student->phone ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================
             RIGHT CONTENT
        ====================================== --}}
        <div class="content-area">


            {{-- =================================
                 BASIC INFORMATION
            ================================== --}}
            <div class="content-card">

                <div class="content-header">

                    <div class="content-header-left">

                        <div class="content-icon">
                            <i class="bi bi-person-vcard"></i>
                        </div>

                        <h5 class="content-title">
                            Basic Information
                        </h5>

                    </div>

                </div>


                <div class="content-body">

                    <div class="information-grid">


                        <div class="information-item">
                            <div class="information-label">First Name</div>
                            <div class="information-value">
                                {{ $student->first_name ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Middle Name</div>
                            <div class="information-value">
                                {{ $student->middle_name ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Last Name</div>
                            <div class="information-value">
                                {{ $student->last_name ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Gender</div>
                            <div class="information-value">
                                {{ $student->gender ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Date of Birth</div>
                            <div class="information-value">

                                {{ $student->date_of_birth
                                    ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y')
                                    : '-' }}

                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Aadhar Number</div>
                            <div class="information-value">
                                {{ $student->aadhar_card_no ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Phone</div>
                            <div class="information-value">
                                {{ $student->phone ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Email</div>
                            <div class="information-value">
                                {{ $student->email ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Marathi Name</div>
                            <div class="information-value">
                                {{ $student->marathi_name ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Nationality</div>
                            <div class="information-value">
                                {{ $student->nationality ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Religion</div>
                            <div class="information-value">
                                {{ $student->religion ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Status</div>

                            <div class="information-value">

                                @if($student->status === 'active')

                                    <span class="status status-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>

                                @else

                                    <span class="status status-inactive">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 ACADEMIC INFORMATION
            ================================== --}}
            <div class="content-card">

                <div class="content-header">

                    <div class="content-header-left">

                        <div class="content-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>

                        <h5 class="content-title">
                            Academic Information
                        </h5>

                    </div>

                </div>


                <div class="content-body">


                    {{-- IMPORTANT IDs --}}
                    <div class="id-strip">

                        <div class="id-item">

                            <div class="id-label">
                                Register No.
                            </div>

                            <div class="id-value">
                                {{ $student->register_no ?? '-' }}
                            </div>

                        </div>


                        <div class="id-item">

                            <div class="id-label">
                                Book No.
                            </div>

                            <div class="id-value">
                                {{ $student->book_no ?? '-' }}
                            </div>

                        </div>


                        <div class="id-item">

                            <div class="id-label">
                                APAR ID
                            </div>

                            <div class="id-value">
                                {{ $student->appar_id ?? '-' }}
                            </div>

                        </div>


                        <div class="id-item">

                            <div class="id-label">
                                PEN No.
                            </div>

                            <div class="id-value">
                                {{ $student->pen_no ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <div class="information-grid">


                        <div class="information-item">
                            <div class="information-label">Academic Year</div>
                            <div class="information-value">
                                {{ $student->academic_year ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Class</div>
                            <div class="information-value">
                                {{ $student->class ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Section</div>
                            <div class="information-value">
                                {{ $student->section ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Roll Number</div>
                            <div class="information-value">
                                {{ $student->roll_number ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Admission Class</div>
                            <div class="information-value">
                                {{ $student->admission_class ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Admission Date</div>
                            <div class="information-value">

                                {{ $student->admission_date
                                    ? \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y')
                                    : '-' }}

                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Medium</div>
                            <div class="information-value">
                                {{ $student->medium ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Mother Tongue</div>
                            <div class="information-value">
                                {{ $student->mother_tongue ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Nationality</div>
                            <div class="information-value">
                                {{ $student->nationality ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Religion</div>
                            <div class="information-value">
                                {{ $student->religion ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Caste</div>
                            <div class="information-value">
                                {{ $student->caste ?? '-' }}
                            </div>
                        </div>


                        <div class="information-item">
                            <div class="information-label">Sub Caste</div>
                            <div class="information-value">
                                {{ $student->sub_caste ?? '-' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 PARENTS INFORMATION
            ================================== --}}
            <div class="content-card">

                <div class="content-header">

                    <div class="content-header-left">

                        <div class="content-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <h5 class="content-title">
                            Parents & Guardian
                        </h5>

                    </div>

                </div>


                <div class="content-body">

                    <div class="row g-3">


                        {{-- FATHER --}}
                        <div class="col-lg-4">

                            <div class="person-block">

                                <div class="person-heading">

                                    <div class="person-heading-icon">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    Father

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Name
                                    </div>

                                    <div class="person-value">
                                        {{ $student->father_name ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Phone
                                    </div>

                                    <div class="person-value">
                                        {{ $student->father_phone ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Occupation
                                    </div>

                                    <div class="person-value">
                                        {{ $student->father_occupation ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- MOTHER --}}
                        <div class="col-lg-4">

                            <div class="person-block">

                                <div class="person-heading">

                                    <div class="person-heading-icon">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    Mother

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Name
                                    </div>

                                    <div class="person-value">
                                        {{ $student->mother_name ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Phone
                                    </div>

                                    <div class="person-value">
                                        {{ $student->mother_phone ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Occupation
                                    </div>

                                    <div class="person-value">
                                        {{ $student->mother_occupation ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- GUARDIAN --}}
                        <div class="col-lg-4">

                            <div class="person-block">

                                <div class="person-heading">

                                    <div class="person-heading-icon">
                                        <i class="bi bi-person-vcard"></i>
                                    </div>

                                    Guardian

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Name
                                    </div>

                                    <div class="person-value">
                                        {{ $student->guardian_name ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Relation
                                    </div>

                                    <div class="person-value">
                                        {{ $student->guardian_relation ?? '-' }}
                                    </div>

                                </div>


                                <div class="person-info">

                                    <div class="person-label">
                                        Phone
                                    </div>

                                    <div class="person-value">
                                        {{ $student->guardian_phone ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 PREVIOUS SCHOOL
            ================================== --}}
            <div class="content-card">

                <div class="content-header">

                    <div class="content-header-left">

                        <div class="content-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <h5 class="content-title">
                            Previous School Information
                        </h5>

                    </div>

                </div>


                <div class="content-body">

                    <div class="information-grid">


                        <div class="information-item">

                            <div class="information-label">
                                School Name
                            </div>

                            <div class="information-value">
                                {{ $student->previous_school_name ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Class
                            </div>

                            <div class="information-value">
                                {{ $student->previous_school_class ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Medium
                            </div>

                            <div class="information-value">
                                {{ $student->previous_school_medium ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Board
                            </div>

                            <div class="information-value">
                                {{ $student->previous_school_board ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Result
                            </div>

                            <div class="information-value">
                                {{ $student->previous_school_result ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <div class="mt-3">

                        <div class="text-section">

                            <div class="text-label">
                                School Address
                            </div>

                            <div class="text-box">
                                {{ $student->previous_school_address ?? '-' }}
                            </div>

                        </div>


                        <div class="text-section">

                            <div class="text-label">
                                Remarks
                            </div>

                            <div class="text-box">
                                {{ $student->previous_school_remarks ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 ADDRESS
            ================================== --}}
            <div class="content-card">

                <div class="content-header">

                    <div class="content-header-left">

                        <div class="content-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <h5 class="content-title">
                            Address Information
                        </h5>

                    </div>

                </div>


                <div class="content-body">

                    <div class="information-grid">


                        <div class="information-item">

                            <div class="information-label">
                                Country
                            </div>

                            <div class="information-value">
                                {{ $student->country ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                State
                            </div>

                            <div class="information-value">
                                {{ $student->state ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                District
                            </div>

                            <div class="information-value">
                                {{ $student->district ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Taluka
                            </div>

                            <div class="information-value">
                                {{ $student->taluka ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                City / Village
                            </div>

                            <div class="information-value">
                                {{ $student->city_village ?? '-' }}
                            </div>

                        </div>


                        <div class="information-item">

                            <div class="information-label">
                                Pincode
                            </div>

                            <div class="information-value">
                                {{ $student->pincode ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <div class="mt-3">

                        <div class="text-label">
                            Full Address
                        </div>

                        <div class="text-box">
                            {{ $student->address ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 BOTTOM ACTIONS
            ================================== --}}
            <div class="bottom-bar">

                <div class="bottom-note">

                    <i class="bi bi-info-circle me-1"></i>

                    Student profile and registration information

                </div>


                <div class="bottom-actions">

                    <a href="{{ route('admin.students.index') }}"
                       class="btn btn-secondary btn-sm px-3">

                        <i class="bi bi-arrow-left me-1"></i>
                        Back

                    </a>


                    <a href="{{ route('admin.students.edit', $student) }}"
                       class="btn btn-warning btn-sm px-3">

                        <i class="bi bi-pencil me-1"></i>
                        Edit

                    </a>


                    <form
                        action="{{ route('admin.students.destroy', $student) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this student?');"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger btn-sm px-3">

                            <i class="bi bi-trash me-1"></i>
                            Delete

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection