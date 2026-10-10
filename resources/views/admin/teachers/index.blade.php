@extends('layouts.app')

@section('title', 'All Teachers')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

/* =========================================================
   TEACHERS PAGE
========================================================= */

.teachers-container {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.teachers-welcome-card {
    position: relative;
    overflow: hidden;
    min-height: 145px;
    padding: 30px 34px;
    margin-bottom: 24px;
    border-radius: 18px;
    background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
    color: #fff;
    box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.teachers-welcome-card::before {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    right: -45px;
    top: -105px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.teachers-welcome-card::after {
    content: "";
    position: absolute;
    width: 120px;
    height: 120px;
    right: 100px;
    bottom: -82px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
}

.teachers-welcome-content {
    position: relative;
    z-index: 2;
}

.teachers-welcome-content h2 {
    margin: 0 0 7px;
    font-size: 30px;
    font-weight: 800;
}

.teachers-welcome-content p {
    margin: 0;
    font-size: 15px;
    color: rgba(255,255,255,.94);
}

.teachers-add-button {
    position: relative;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 10px;
    background: #fff;
    color: #1769d1;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(0,0,0,.10);
    transition: .2s ease;
}

.teachers-add-button:hover {
    color: #1769d1;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
}

/* =========================================================
   STATISTICS
========================================================= */

.teacher-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.teacher-stat-card {
    position: relative;
    overflow: hidden;
    min-height: 145px;
    padding: 24px 25px;
    border-radius: 17px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 8px 22px rgba(15,23,42,.12);
    transition: transform .25s ease, box-shadow .25s ease;
}

.teacher-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 32px rgba(15,23,42,.18);
}

.teacher-stat-card::before {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    right: -50px;
    top: -65px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}

.teacher-stat-card::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    right: -20px;
    bottom: -38px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}

.teacher-stat-card.blue {
    background: linear-gradient(135deg, #1769d1, #237de0);
}

.teacher-stat-card.orange {
    background: linear-gradient(135deg, #ed9208, #f7aa25);
}

.teacher-stat-card.red {
    background: linear-gradient(135deg, #e94d47, #f75d56);
}

.teacher-stat-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.teacher-stat-number {
    margin: 0 0 7px;
    font-size: 32px;
    line-height: 1;
    font-weight: 800;
}

.teacher-stat-title {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,.95);
}

.teacher-stat-icon {
    position: relative;
    z-index: 2;
    font-size: 38px;
    color: rgba(255,255,255,.90);
}

/* =========================================================
   MAIN CARD
========================================================= */

.teachers-card {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15,23,42,.06);
}

/* =========================================================
   CARD HEADER
========================================================= */

.teachers-card-header {
    min-height: 75px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.teachers-card-title {
    display: flex;
    align-items: center;
    gap: 9px;
}

.teachers-card-title i {
    color: #1769d1;
    font-size: 19px;
}

.teachers-card-title h3 {
    margin: 0;
    color: #172033;
    font-size: 17px;
    font-weight: 700;
}

.teachers-card-subtitle {
    margin: 4px 0 0 28px;
    color: #718096;
    font-size: 12px;
}

/* =========================================================
   SEARCH
========================================================= */

.teacher-search-box {
    width: 330px;
    display: flex;
    align-items: center;
    border: 1px solid #e3eaf2;
    background: #f8fafc;
    border-radius: 9px;
    overflow: hidden;
}

.teacher-search-box i {
    padding-left: 13px;
    color: #94a3b8;
    font-size: 13px;
}

.teacher-search-box input {
    width: 100%;
    padding: 10px 12px;
    border: none;
    outline: none;
    background: transparent;
    color: #334155;
    font-size: 12px;
}

.teacher-search-box input::placeholder {
    color: #94a3b8;
}

/* =========================================================
   TABLE
========================================================= */

.teachers-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.teachers-table {
    width: 100%;
    min-width: 1250px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed;
}

/* # */
.teachers-table th:nth-child(1),
.teachers-table td:nth-child(1) {
    width: 55px;
    text-align: center;
}

/* Teacher */
.teachers-table th:nth-child(2),
.teachers-table td:nth-child(2) {
    width: 220px;
}

/* Teacher ID */
.teachers-table th:nth-child(3),
.teachers-table td:nth-child(3) {
    width: 120px;
}

/* Email */
.teachers-table th:nth-child(4),
.teachers-table td:nth-child(4) {
    width: 220px;
}

/* Phone */
.teachers-table th:nth-child(5),
.teachers-table td:nth-child(5) {
    width: 135px;
}

/* Qualification */
.teachers-table th:nth-child(6),
.teachers-table td:nth-child(6) {
    width: 150px;
}

/* Subject */
.teachers-table th:nth-child(7),
.teachers-table td:nth-child(7) {
    width: 150px;
}

/* Joining Date */
.teachers-table th:nth-child(8),
.teachers-table td:nth-child(8) {
    width: 125px;
}

/* Status */
.teachers-table th:nth-child(9),
.teachers-table td:nth-child(9) {
    width: 120px;
}

/* Actions */
.teachers-table th:nth-child(10),
.teachers-table td:nth-child(10) {
    width: 150px;
}

/* =========================================================
   TABLE HEADER
========================================================= */

.teachers-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    padding: 15px 14px;
    background: #f8fafc;
    border-top: 1px solid #edf1f6;
    border-bottom: 1px solid #e5eaf1;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
    text-align: left;
}

/* =========================================================
   TABLE BODY
========================================================= */

.teachers-table tbody td {
    padding: 16px 14px;
    background: #fff;
    border-bottom: 1px solid #edf1f6;
    color: #334155;
    font-size: 12px;
    vertical-align: middle;
    white-space: nowrap;
}

.teachers-table tbody tr {
    transition: background .2s ease;
}

.teachers-table tbody tr:hover td {
    background: #f8fbff;
}

.teachers-table tbody tr:last-child td {
    border-bottom: none;
}

/* =========================================================
   SERIAL NUMBER
========================================================= */

.teacher-number {
    width: 29px;
    height: 29px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eef5ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================================
   TEACHER PROFILE
========================================================= */

.teacher-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.teacher-avatar,
.teacher-avatar-placeholder {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 50%;
}

.teacher-avatar {
    object-fit: cover;
    box-shadow: 0 3px 8px rgba(15,23,42,.10);
}

.teacher-avatar-placeholder {
    background: linear-gradient(135deg, #1769d1, #159cc7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 800;
}

.teacher-profile-info {
    min-width: 0;
}

.teacher-name {
    margin-bottom: 3px;
    overflow: hidden;
    color: #172033;
    font-size: 13px;
    font-weight: 800;
    text-overflow: ellipsis;
}

.teacher-gender {
    color: #94a3b8;
    font-size: 10px;
    text-transform: capitalize;
}

/* =========================================================
   TEACHER ID
========================================================= */

.teacher-id {
    display: inline-flex;
    padding: 6px 9px;
    border-radius: 7px;
    background: #eef5ff;
    color: #1769d1;
    font-size: 11px;
    font-weight: 800;
}

/* =========================================================
   EMAIL
========================================================= */

.teacher-email {
    color: #475569;
    font-size: 11px;
}

/* =========================================================
   SUBJECT
========================================================= */

.teacher-subject {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 7px;
    background: #f0edff;
    color: #6c63ff;
    font-size: 11px;
    font-weight: 700;
}

/* =========================================================
   STATUS
========================================================= */

.teacher-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.teacher-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.teacher-status.active {
    background: #dcfce7;
    color: #16a34a;
}

.teacher-status.active .teacher-status-dot {
    background: #16a34a;
}

.teacher-status.inactive {
    background: #fee2e2;
    color: #dc2626;
}

.teacher-status.inactive .teacher-status-dot {
    background: #dc2626;
}

/* =========================================================
   ACTIONS
========================================================= */

.teachers-table .teacher-actions {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    white-space: nowrap;
}

.teachers-table .teacher-actions form {
    display: inline-flex !important;
    margin: 0 !important;
    padding: 0 !important;
}

.teachers-table .teacher-action {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    min-height: 36px !important;
    padding: 0 !important;
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid transparent !important;
    border-radius: 9px !important;
    text-decoration: none !important;
    cursor: pointer !important;
    font-size: 15px !important;
    line-height: 1 !important;
    transition:
        transform .2s ease,
        background .2s ease,
        color .2s ease !important;
}

.teachers-table .teacher-view-action {
    background: #eaf3ff !important;
    color: #1477df !important;
    border-color: #d9eaff !important;
}

.teachers-table .teacher-view-action:hover {
    background: #1477df !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.teachers-table .teacher-edit-action {
    background: #f0edff !important;
    color: #6c63ff !important;
    border-color: #e3defe !important;
}

.teachers-table .teacher-edit-action:hover {
    background: #6c63ff !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.teachers-table .teacher-delete-action {
    background: #fff0f0 !important;
    color: #ef2028 !important;
    border-color: #ffe0e0 !important;
}

.teachers-table .teacher-delete-action:hover {
    background: #ef2028 !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.teachers-table .teacher-action i {
    display: block !important;
    color: inherit !important;
    font-size: 15px !important;
    line-height: 1 !important;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.teacher-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.teacher-empty-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 16px;
    border-radius: 18px;
    background: #eef5ff;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 27px;
}

.teacher-empty-state h4 {
    margin: 0 0 7px;
    color: #172033;
    font-size: 16px;
    font-weight: 800;
}

.teacher-empty-state p {
    margin: 0 0 18px;
    color: #94a3b8;
    font-size: 12px;
}

.teacher-empty-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 15px;
    border-radius: 8px;
    background: #1769d1;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: .2s ease;
}

.teacher-empty-button:hover {
    background: #1258b5;
    color: #fff;
    transform: translateY(-2px);
}

/* =========================================================
   SUCCESS MESSAGE
========================================================= */

.teacher-success {
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    background: #f0fdf4;
    color: #15803d;
    font-size: 13px;
    font-weight: 600;
}

/* =========================================================
   PAGINATION
========================================================= */

.teacher-pagination {
    padding: 17px 22px;
    border-top: 1px solid #edf1f6;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.teacher-pagination-info {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.teacher-pagination nav > div:first-child {
    display: none;
}

.teacher-pagination nav > div:last-child {
    display: flex;
    align-items: center;
}

.teacher-pagination nav a,
.teacher-pagination nav span {
    min-width: 34px;
    height: 34px;
    margin-left: 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 9px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: all .2s ease;
}

.teacher-pagination nav a:hover {
    background: #1769d1;
    border-color: #1769d1;
    color: #fff;
    transform: translateY(-1px);
}

.teacher-pagination nav span[aria-current="page"] {
    background: #1769d1;
    border-color: #1769d1;
    color: #fff;
}

.teacher-pagination nav span[aria-disabled="true"] {
    background: #f8fafc;
    color: #cbd5e1;
    cursor: not-allowed;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .teacher-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 850px) {

    .teachers-container {
        padding: 18px;
    }

    .teachers-welcome-card {
        padding: 25px;
    }

    .teachers-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .teacher-search-box {
        width: 100%;
    }

    .teacher-pagination {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (max-width: 650px) {

    .teachers-container {
        padding: 15px;
    }

    .teacher-stats-grid {
        grid-template-columns: 1fr;
    }

    .teachers-welcome-card {
        min-height: 135px;
        align-items: flex-start;
        flex-direction: column;
        gap: 18px;
    }

    .teachers-welcome-content h2 {
        font-size: 24px;
    }

    .teachers-welcome-content p {
        font-size: 13px;
    }

    .teachers-add-button {
        padding: 9px 14px;
    }

    .teachers-table {
        min-width: 1250px;
    }

    .teacher-pagination {
        padding: 15px;
    }
}

</style>


<div class="teachers-container">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="teachers-welcome-card">

        <div class="teachers-welcome-content">

            <h2>All Teachers 👨‍🏫</h2>

            <p>
                Manage teachers, faculty information and teacher records.
            </p>

        </div>

        <a href="{{ route('admin.teachers.create') }}"
           class="teachers-add-button">

            <i class="bi bi-person-plus-fill"></i>

            Add Teacher

        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="teacher-success">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="teacher-stats-grid">

        {{-- TOTAL --}}

        <div class="teacher-stat-card blue">

            <div class="teacher-stat-top">

                <div>

                    <div class="teacher-stat-number">

                        {{ number_format(
                            $totalTeachers ?? $teachers->count()
                        ) }}

                    </div>

                    <div class="teacher-stat-title">
                        Total Teachers
                    </div>

                </div>

                <i class="bi bi-people-fill teacher-stat-icon"></i>

            </div>

        </div>


        {{-- ACTIVE --}}

        <div class="teacher-stat-card orange">

            <div class="teacher-stat-top">

                <div>

                    <div class="teacher-stat-number">

                        {{ number_format(
                            $activeTeachers
                            ?? $teachers->where('status', 'Active')->count()
                        ) }}

                    </div>

                    <div class="teacher-stat-title">
                        Active Teachers
                    </div>

                </div>

                <i class="bi bi-person-check-fill teacher-stat-icon"></i>

            </div>

        </div>


        {{-- INACTIVE --}}

        <div class="teacher-stat-card red">

            <div class="teacher-stat-top">

                <div>

                    <div class="teacher-stat-number">

                        {{ number_format(
                            $inactiveTeachers
                            ?? $teachers->where('status', 'Inactive')->count()
                        ) }}

                    </div>

                    <div class="teacher-stat-title">
                        Inactive Teachers
                    </div>

                </div>

                <i class="bi bi-person-x-fill teacher-stat-icon"></i>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TEACHER LIST CARD
    ====================================================== --}}

    <div class="teachers-card">

        {{-- CARD HEADER --}}

        <div class="teachers-card-header">

            <div>

                <div class="teachers-card-title">

                    <i class="bi bi-person-workspace"></i>

                    <h3>Teacher List</h3>

                </div>

                <div class="teachers-card-subtitle">

                    All registered teachers in the school

                </div>

            </div>


            {{-- SEARCH --}}

            <div class="teacher-search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="teacherSearch"
                    placeholder="Search teacher, ID, email, phone, subject..."
                    autocomplete="off"
                >

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        <div class="teachers-table-wrapper">

            <table class="teachers-table" id="teachersTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Teacher</th>

                        <th>Teacher ID</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Qualification</th>

                        <th>Subject</th>

                        <th>Joining Date</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($teachers as $teacher)

                        <tr>

                            {{-- NUMBER --}}

                            <td>

                                <span class="teacher-number">

                                    {{ method_exists($teachers, 'firstItem')
                                        ? $teachers->firstItem() + $loop->index
                                        : $loop->iteration }}

                                </span>

                            </td>


                            {{-- TEACHER --}}

                            <td>

                                <div class="teacher-profile">

                                    @if(!empty($teacher->profile_image))

                                        <img
                                            src="{{ $teacher->profile_image }}"
                                            alt="{{ $teacher->first_name }}"
                                            class="teacher-avatar"
                                        >

                                    @else

                                        <div class="teacher-avatar-placeholder">

                                            {{ strtoupper(
                                                substr(
                                                    $teacher->first_name ?? 'T',
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                    @endif


                                    <div class="teacher-profile-info">

                                        <div class="teacher-name">

                                            {{ $teacher->first_name ?? '' }}
                                            {{ $teacher->last_name ?? '' }}

                                        </div>

                                        <div class="teacher-gender">

                                            {{ $teacher->gender ?? 'Not specified' }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- TEACHER ID --}}

                            <td>

                                <span class="teacher-id">

                                    {{ $teacher->teacher_id ?? '—' }}

                                </span>

                            </td>


                            {{-- EMAIL --}}

                            <td>

                                <span class="teacher-email">

                                    {{ $teacher->email ?? '—' }}

                                </span>

                            </td>


                            {{-- PHONE --}}

                            <td>

                                {{ $teacher->phone ?? '—' }}

                            </td>


                            {{-- QUALIFICATION --}}

                            <td>

                                {{ $teacher->qualification ?? '—' }}

                            </td>


                            {{-- SUBJECT --}}

                            <td>

                                @if(!empty($teacher->subject))

                                    <span class="teacher-subject">

                                        {{ $teacher->subject }}

                                    </span>

                                @else

                                    <span style="color:#94a3b8;">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- JOINING DATE --}}

                            <td>

                                @if($teacher->joining_date)

                                    {{ \Carbon\Carbon::parse(
                                        $teacher->joining_date
                                    )->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if(strtolower($teacher->status ?? '') === 'active')

                                    <span class="teacher-status active">

                                        <span class="teacher-status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="teacher-status inactive">

                                        <span class="teacher-status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="teacher-actions">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'admin.teachers.show',
                                            $teacher->id
                                        ) }}"
                                        class="teacher-action teacher-view-action"
                                        title="View Profile"
                                        aria-label="View Profile"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.teachers.edit',
                                            $teacher->id
                                        ) }}"
                                        class="teacher-action teacher-edit-action"
                                        title="Edit Teacher"
                                        aria-label="Edit Teacher"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'admin.teachers.destroy',
                                            $teacher->id
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this teacher?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="teacher-action teacher-delete-action"
                                            title="Delete Teacher"
                                            aria-label="Delete Teacher"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10">

                                <div class="teacher-empty-state">

                                    <div class="teacher-empty-icon">

                                        <i class="bi bi-person-workspace"></i>

                                    </div>

                                    <h4>
                                        No Teachers Found
                                    </h4>

                                    <p>
                                        No teachers have been added yet.
                                    </p>

                                    <a
                                        href="{{ route('admin.teachers.create') }}"
                                        class="teacher-empty-button"
                                    >

                                        <i class="bi bi-person-plus-fill"></i>

                                        Add First Teacher

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}

        @if(
            method_exists($teachers, 'hasPages')
            && $teachers->hasPages()
        )

            <div class="teacher-pagination">

                <div class="teacher-pagination-info">

                    Showing

                    {{ $teachers->firstItem() }}

                    to

                    {{ $teachers->lastItem() }}

                    of

                    {{ $teachers->total() }}

                    teachers

                </div>


                <div>

                    {{ $teachers->links() }}

                </div>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     SEARCH SCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('teacherSearch');
    const table = document.getElementById('teachersTable');

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener('input', function () {

        const searchValue = this.value.toLowerCase().trim();

        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(function (row) {

            const rowText = row.textContent.toLowerCase();

            row.style.display =
                rowText.includes(searchValue)
                    ? ''
                    : 'none';

        });

    });

});

</script>

@endsection