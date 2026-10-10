@extends('layouts.app')

@section('title', 'Teacher Profile')

@section('content')

<style>

/* =========================================================
   TEACHER PROFILE PAGE
========================================================= */

.teacher-profile-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
}

/* =========================================================
   PAGE HEADER
========================================================= */

.teacher-profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    margin-bottom: 24px;
    padding: 24px 28px;

    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);

    animation: profileSlideDown .5s ease;
}

.teacher-profile-header h2 {
    margin: 0 0 6px;
    color: #172033;
    font-size: 28px;
    font-weight: 800;
}

.teacher-profile-header p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

/* =========================================================
   HEADER ACTIONS
========================================================= */

.teacher-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.profile-header-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    padding: 10px 16px;

    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;

    transition: all .25s ease;
}

.profile-header-btn i {
    transition: transform .25s ease;
}

.profile-back-btn {
    background: #f8fafc;
    border: 1px solid #dfe7f0;
    color: #475569;
}

.profile-back-btn:hover {
    background: #e9eef5;
    color: #334155;
    transform: translateY(-2px);
}

.profile-back-btn:hover i {
    transform: translateX(-4px);
}

.profile-edit-btn {
    background: linear-gradient(135deg, #1769d1, #159cc7);
    color: #fff;
    box-shadow: 0 5px 14px rgba(23, 105, 209, .18);
}

.profile-edit-btn:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 9px 20px rgba(23, 105, 209, .25);
}

.profile-edit-btn:hover i {
    transform: rotate(-8deg) scale(1.1);
}

/* =========================================================
   PROFILE HERO
========================================================= */

.teacher-profile-hero {
    overflow: hidden;
    position: relative;

    margin-bottom: 22px;

    background: #fff;

    border: 1px solid #e5ebf3;
    border-radius: 16px;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);

    animation: profileFadeUp .6s ease;
}

.teacher-profile-hero-top {
    height: 125px;

    position: relative;

    background:
        radial-gradient(circle at 15% 20%, rgba(255,255,255,.16), transparent 30%),
        radial-gradient(circle at 85% 80%, rgba(255,255,255,.12), transparent 35%),
        linear-gradient(135deg, #1769d1, #159cc7);

    overflow: hidden;
}

.teacher-profile-hero-top::before,
.teacher-profile-hero-top::after {
    content: "";
    position: absolute;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}

.teacher-profile-hero-top::before {
    width: 220px;
    height: 220px;
    right: -60px;
    top: -130px;
}

.teacher-profile-hero-top::after {
    width: 160px;
    height: 160px;
    left: 25%;
    bottom: -120px;
}

/* =========================================================
   PROFILE MAIN
========================================================= */

.teacher-profile-main {
    display: grid;

    grid-template-columns:
        140px
        minmax(0, 1fr)
        auto;

    align-items: center;
    gap: 24px;

    padding: 0 28px 28px;
}

/* =========================================================
   PROFILE IMAGE
========================================================= */

.teacher-profile-image-wrapper {
    margin-top: -55px;

    width: 130px;
    height: 130px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #fff;

    border-radius: 50%;

    box-shadow: 0 8px 25px rgba(15, 23, 42, .15);

    position: relative;
    z-index: 2;

    transition: all .3s ease;
}

.teacher-profile-image-wrapper:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 15px 35px rgba(15, 23, 42, .18);
}

.teacher-profile-image {
    width: 116px;
    height: 116px;

    object-fit: cover;

    border-radius: 50%;

    transition: transform .35s ease;
}

.teacher-profile-image-wrapper:hover .teacher-profile-image {
    transform: scale(1.06);
}

.teacher-profile-placeholder {
    width: 116px;
    height: 116px;

    border-radius: 50%;

    background: #e7f0ff;
    color: #1769d1;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 42px;

    transition: all .3s ease;
}

.teacher-profile-image-wrapper:hover
.teacher-profile-placeholder {
    background: #dcecff;
    transform: scale(1.04);
}

/* =========================================================
   BASIC INFORMATION
========================================================= */

.teacher-basic-info {
    padding-top: 22px;
}

.teacher-basic-info h3 {
    margin: 0 0 5px;

    color: #172033;

    font-size: 25px;
    font-weight: 800;
}

.teacher-designation {
    margin: 0 0 13px;

    color: #718096;
    font-size: 13px;
}

.teacher-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 18px;
}

.teacher-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    color: #64748b;
    font-size: 12px;

    cursor: pointer;

    transition: color .2s ease;
}

.teacher-meta-item:hover {
    color: #1769d1;
}

.teacher-meta-item i {
    color: #1769d1;
}

/* =========================================================
   COPYABLE META
========================================================= */

.copyable {
    position: relative;
}

.copyable::after {
    content: "Click to copy";

    position: absolute;
    left: 0;
    bottom: calc(100% + 8px);

    padding: 5px 8px;

    background: #172033;
    color: #fff;

    border-radius: 6px;

    font-size: 10px;
    white-space: nowrap;

    opacity: 0;
    pointer-events: none;

    transform: translateY(4px);

    transition: all .2s ease;
}

.copyable:hover::after {
    opacity: 1;
    transform: translateY(0);
}

/* =========================================================
   STATUS
========================================================= */

.teacher-status-area {
    padding-top: 22px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 8px 13px;

    border-radius: 20px;

    font-size: 12px;
    font-weight: 700;

    transition: all .25s ease;
}

.status-active {
    background: #dcfce7;
    color: #15803d;
}

.status-active:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(21, 128, 61, .15);
}

.status-inactive {
    background: #f1f5f9;
    color: #64748b;
}

.status-inactive:hover {
    transform: translateY(-2px);
}

.status-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;
    background: currentColor;
}

.status-active .status-dot {
    animation: statusPulse 1.8s infinite;
}

/* =========================================================
   INFORMATION GRID
========================================================= */

.teacher-information-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 22px;

    margin-bottom: 22px;
}

.teacher-info-card {
    overflow: hidden;

    background: #fff;

    border: 1px solid #e5ebf3;
    border-radius: 16px;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;

    animation: profileFadeUp .65s ease;
}

.teacher-info-card:hover {
    transform: translateY(-4px);

    border-color: #d6e5f7;

    box-shadow:
        0 12px 30px rgba(15, 23, 42, .09);
}

.teacher-info-card.full-width {
    grid-column: 1 / -1;
}

/* =========================================================
   CARD HEADER
========================================================= */

.teacher-info-header {
    min-height: 68px;

    display: flex;
    align-items: center;
    gap: 11px;

    padding: 16px 22px;

    border-bottom: 1px solid #edf1f6;

    background: linear-gradient(
        to right,
        #fff,
        #fbfdff
    );
}

.teacher-info-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #e7f0ff;
    color: #1769d1;

    font-size: 16px;

    transition: all .25s ease;
}

.teacher-info-card:hover .teacher-info-icon {
    background: #1769d1;
    color: #fff;
    transform: rotate(-4deg) scale(1.05);
}

.teacher-info-header h3 {
    margin: 0;

    color: #172033;

    font-size: 16px;
    font-weight: 700;
}

.teacher-info-header p {
    margin: 3px 0 0;

    color: #94a3b8;

    font-size: 11px;
}

/* =========================================================
   CARD BODY
========================================================= */

.teacher-info-body {
    padding: 22px;
}

.teacher-detail-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 20px 25px;
}

.teacher-detail {
    padding: 10px 12px;

    border-radius: 10px;

    transition: background .2s ease;
}

.teacher-detail:hover {
    background: #f8fbff;
}

.teacher-detail.full {
    grid-column: 1 / -1;
}

.teacher-detail-label {
    display: block;

    margin-bottom: 6px;

    color: #94a3b8;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .3px;
}

.teacher-detail-value {
    color: #334155;

    font-size: 13px;
    font-weight: 600;

    word-break: break-word;
}

.teacher-detail-value.empty {
    color: #a0aec0;
    font-weight: 400;
}

/* =========================================================
   VALUE WITH ICON
========================================================= */

.teacher-value-with-icon {
    display: flex;
    align-items: center;
    gap: 8px;
}

.teacher-value-with-icon i {
    color: #1769d1;
    font-size: 14px;
}

/* =========================================================
   DELETE CARD
========================================================= */

.teacher-delete-card {
    margin-bottom: 25px;

    background: #fff;

    border: 1px solid #fee2e2;
    border-radius: 16px;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .04);

    transition: all .25s ease;
}

.teacher-delete-card:hover {
    border-color: #fecaca;
    box-shadow: 0 10px 25px rgba(220, 53, 69, .07);
}

.teacher-delete-body {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 22px;
}

.teacher-delete-title {
    margin: 0 0 5px;

    color: #dc2626;

    font-size: 16px;
    font-weight: 700;
}

.teacher-delete-description {
    margin: 0;

    color: #718096;

    font-size: 12px;
}

.teacher-delete-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    flex-shrink: 0;

    padding: 10px 17px;

    background: #dc3545;

    border: none;
    border-radius: 9px;

    color: #fff;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;

    transition: all .25s ease;
}

.teacher-delete-btn:hover {
    background: #c82333;

    transform: translateY(-2px);

    box-shadow:
        0 6px 15px rgba(220, 53, 69, .20);
}

.teacher-delete-btn i {
    transition: transform .25s ease;
}

.teacher-delete-btn:hover i {
    transform: rotate(-10deg) scale(1.1);
}

/* =========================================================
   COPY TOAST
========================================================= */

.copy-toast {
    position: fixed;

    right: 25px;
    bottom: 25px;

    display: flex;
    align-items: center;
    gap: 8px;

    padding: 11px 16px;

    background: #172033;
    color: #fff;

    border-radius: 10px;

    font-size: 12px;
    font-weight: 600;

    box-shadow: 0 10px 30px rgba(15, 23, 42, .20);

    opacity: 0;
    visibility: hidden;

    transform: translateY(15px);

    transition: all .25s ease;

    z-index: 9999;
}

.copy-toast.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.copy-toast i {
    color: #4ade80;
}

/* =========================================================
   ANIMATIONS
========================================================= */

@keyframes profileFadeUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes profileSlideDown {
    from {
        opacity: 0;
        transform: translateY(-12px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes statusPulse {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(21, 128, 61, .35);
    }

    50% {
        box-shadow: 0 0 0 5px rgba(21, 128, 61, 0);
    }
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 950px) {

    .teacher-profile-main {
        grid-template-columns:
            120px
            minmax(0, 1fr);
    }

    .teacher-status-area {
        grid-column: 2;
        padding-top: 0;
    }

    .teacher-information-grid {
        grid-template-columns: 1fr;
    }

    .teacher-info-card.full-width {
        grid-column: auto;
    }
}

@media (max-width: 700px) {

    .teacher-profile-container {
        padding: 18px;
    }

    .teacher-profile-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
    }

    .teacher-profile-header h2 {
        font-size: 24px;
    }

    .teacher-header-actions {
        width: 100%;
    }

    .profile-header-btn {
        flex: 1;
    }

    .teacher-profile-main {
        grid-template-columns: 1fr;

        text-align: center;

        justify-items: center;

        padding: 0 20px 25px;
    }

    .teacher-basic-info {
        padding-top: 5px;
    }

    .teacher-meta {
        justify-content: center;
    }

    .teacher-status-area {
        grid-column: auto;
        padding-top: 0;
    }

    .teacher-detail-grid {
        grid-template-columns: 1fr;
    }

    .teacher-detail.full {
        grid-column: auto;
    }

    .teacher-info-body {
        padding: 18px;
    }

    .teacher-delete-body {
        flex-direction: column;
        align-items: flex-start;
    }

    .teacher-delete-btn {
        width: 100%;
    }
}

</style>

<div class="teacher-profile-container">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="teacher-profile-header">

    <div>

        <h2>
            Teacher Profile
        </h2>

        <p>
            View teacher information and professional details
        </p>

    </div>


    <div class="teacher-header-actions">

        <a
            href="{{ route('admin.teachers.index') }}"
            class="profile-header-btn profile-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>


        <a
            href="{{ route('admin.teachers.edit', $teacher) }}"
            class="profile-header-btn profile-edit-btn"
        >
            <i class="bi bi-pencil-square"></i>
            Edit Teacher
        </a>

    </div>

</div>


{{-- =====================================================
     PROFILE HERO
====================================================== --}}

<div class="teacher-profile-hero">

    <div class="teacher-profile-hero-top"></div>


    <div class="teacher-profile-main">

        {{-- PROFILE IMAGE --}}

        <div class="teacher-profile-image-wrapper">

            @if($teacher->profile_image)

                <img
                    src="{{ $teacher->profile_image }}"
                    alt="{{ $teacher->first_name }} {{ $teacher->last_name }}"
                    class="teacher-profile-image"
                >

            @else

                <div class="teacher-profile-placeholder">
                    <i class="bi bi-person-fill"></i>
                </div>

            @endif

        </div>


        {{-- BASIC INFORMATION --}}

        <div class="teacher-basic-info">

            <h3>
                {{ $teacher->first_name }}
                {{ $teacher->last_name }}
            </h3>

            <p class="teacher-designation">
                {{ $teacher->qualification ?? 'Teacher' }}
            </p>


            <div class="teacher-meta">

                {{-- TEACHER ID --}}

                <span
                    class="teacher-meta-item copyable"
                    data-copy="{{ $teacher->teacher_id }}"
                    title="Click to copy Teacher ID"
                >
                    <i class="bi bi-person-badge"></i>

                    Teacher ID:
                    {{ $teacher->teacher_id }}
                </span>


                {{-- EMAIL --}}

                <span
                    class="teacher-meta-item copyable"
                    data-copy="{{ $teacher->email }}"
                    title="Click to copy email"
                >
                    <i class="bi bi-envelope"></i>

                    {{ $teacher->email }}
                </span>

            </div>

        </div>


        {{-- STATUS --}}

        <div class="teacher-status-area">

            @if($teacher->status === 'Active')

                <span class="status-badge status-active">

                    <span class="status-dot"></span>

                    Active

                </span>

            @else

                <span class="status-badge status-inactive">

                    <span class="status-dot"></span>

                    Inactive

                </span>

            @endif

        </div>

    </div>

</div>


{{-- =====================================================
     INFORMATION CARDS
====================================================== --}}

<div class="teacher-information-grid">


    {{-- =================================================
         PERSONAL INFORMATION
    ================================================== --}}

    <div class="teacher-info-card">

        <div class="teacher-info-header">

            <div class="teacher-info-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <div>

                <h3>
                    Personal Information
                </h3>

                <p>
                    Basic personal details
                </p>

            </div>

        </div>


        <div class="teacher-info-body">

            <div class="teacher-detail-grid">


                {{-- FIRST NAME --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        First Name
                    </span>

                    <div class="teacher-detail-value">
                        {{ $teacher->first_name }}
                    </div>

                </div>


                {{-- LAST NAME --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Last Name
                    </span>

                    <div class="teacher-detail-value">
                        {{ $teacher->last_name }}
                    </div>

                </div>


                {{-- DATE OF BIRTH --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Date of Birth
                    </span>

                    <div
                        class="teacher-detail-value {{ !$teacher->date_of_birth ? 'empty' : '' }}"
                    >

                        {{ $teacher->date_of_birth
                            ? $teacher->date_of_birth->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                {{-- GENDER --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Gender
                    </span>

                    <div
                        class="teacher-detail-value {{ !$teacher->gender ? 'empty' : '' }}"
                    >

                        {{ $teacher->gender ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         CONTACT INFORMATION
    ================================================== --}}

    <div class="teacher-info-card">

        <div class="teacher-info-header">

            <div class="teacher-info-icon">
                <i class="bi bi-telephone-fill"></i>
            </div>

            <div>

                <h3>
                    Contact Information
                </h3>

                <p>
                    Contact and address details
                </p>

            </div>

        </div>


        <div class="teacher-info-body">

            <div class="teacher-detail-grid">


                {{-- EMAIL --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Email
                    </span>

                    <div class="teacher-detail-value teacher-value-with-icon">

                        <i class="bi bi-envelope"></i>

                        <span>
                            {{ $teacher->email }}
                        </span>

                    </div>

                </div>


                {{-- PHONE --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Phone
                    </span>

                    @if($teacher->phone)

                        <div
                            class="teacher-detail-value teacher-value-with-icon copyable"
                            data-copy="{{ $teacher->phone }}"
                            style="cursor:pointer;"
                        >

                            <i class="bi bi-telephone"></i>

                            <span>
                                {{ $teacher->phone }}
                            </span>

                        </div>

                    @else

                        <div class="teacher-detail-value empty">
                            -
                        </div>

                    @endif

                </div>


                {{-- ADDRESS --}}

                <div class="teacher-detail full">

                    <span class="teacher-detail-label">
                        Address
                    </span>

                    <div
                        class="teacher-detail-value {{ !$teacher->address ? 'empty' : '' }}"
                    >

                        {{ $teacher->address ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         PROFESSIONAL INFORMATION
    ================================================== --}}

    <div class="teacher-info-card full-width">

        <div class="teacher-info-header">

            <div class="teacher-info-icon">
                <i class="bi bi-briefcase-fill"></i>
            </div>

            <div>

                <h3>
                    Professional Information
                </h3>

                <p>
                    Employment and qualification details
                </p>

            </div>

        </div>


        <div class="teacher-info-body">

            <div class="teacher-detail-grid">


                {{-- TEACHER ID --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Teacher ID
                    </span>

                    <div
                        class="teacher-detail-value teacher-value-with-icon copyable"
                        data-copy="{{ $teacher->teacher_id }}"
                        style="cursor:pointer;"
                    >

                        <i class="bi bi-person-vcard"></i>

                        <span>
                            {{ $teacher->teacher_id }}
                        </span>

                    </div>

                </div>


                {{-- QUALIFICATION --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Qualification
                    </span>

                    <div
                        class="teacher-detail-value {{ !$teacher->qualification ? 'empty' : '' }}"
                    >

                        {{ $teacher->qualification ?? '-' }}

                    </div>

                </div>


                {{-- JOINING DATE --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Joining Date
                    </span>

                    <div
                        class="teacher-detail-value {{ !$teacher->joining_date ? 'empty' : '' }}"
                    >

                        {{ $teacher->joining_date
                            ? $teacher->joining_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="teacher-detail">

                    <span class="teacher-detail-label">
                        Status
                    </span>

                    <div>

                        @if($teacher->status === 'Active')

                            <span class="status-badge status-active">

                                <span class="status-dot"></span>

                                Active

                            </span>

                        @else

                            <span class="status-badge status-inactive">

                                <span class="status-dot"></span>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     DELETE TEACHER
====================================================== --}}

<div class="teacher-delete-card">

    <div class="teacher-delete-body">

        <div>

            <h4 class="teacher-delete-title">
                Delete Teacher
            </h4>

            <p class="teacher-delete-description">
                This action will permanently remove this teacher.
            </p>

        </div>


        <form
            action="{{ route('admin.teachers.destroy', $teacher) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="teacher-delete-btn"
            >

                <i class="bi bi-trash3"></i>

                Delete Teacher

            </button>

        </form>

    </div>

</div>


</div>

{{-- =========================================================
COPY TOAST
========================================================= --}}

<div class="copy-toast" id="copyToast">


<i class="bi bi-check-circle-fill"></i>

<span id="copyToastText">
    Copied successfully
</span>
```

</div>

<script>

/* =========================================================
   COPY TO CLIPBOARD
========================================================= */

document.querySelectorAll('.copyable').forEach(function(element) {

    element.addEventListener('click', function() {

        const value = this.dataset.copy;

        if (!value) {
            return;
        }

        navigator.clipboard.writeText(value)
            .then(function() {

                showCopyToast('Copied successfully');

            })
            .catch(function() {

                showCopyToast('Unable to copy');

            });

    });

});


/* =========================================================
   COPY TOAST
========================================================= */

function showCopyToast(message) {

    const toast = document.getElementById('copyToast');
    const toastText = document.getElementById('copyToastText');

    toastText.textContent = message;

    toast.classList.add('show');

    setTimeout(function() {

        toast.classList.remove('show');

    }, 1800);

}

</script>

@endsection
