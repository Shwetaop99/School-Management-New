@extends('layouts.app')

@section('title', 'Teacher Reports')
@section('page-title', 'Teacher Reports')

@section('content')

<style>
/* =========================================================
   TEACHER REPORTS PAGE
   Strongly scoped CSS to prevent layout conflicts
========================================================= */

.teacher-reports-page {
    width: 100% !important;
    max-width: 1600px !important;
    margin: 0 auto !important;
    padding: 28px !important;
    background: #f4f7fb !important;
    min-height: calc(100vh - 120px) !important;
    box-sizing: border-box !important;
}

/* =========================================================
   HEADER
========================================================= */

.teacher-reports-page .reports-header {
    width: 100% !important;
    background: linear-gradient(135deg, #147cf5, #6c63ff) !important;
    border-radius: 16px !important;
    padding: 28px 32px !important;
    margin-bottom: 25px !important;
    color: #ffffff !important;
    box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15) !important;
    box-sizing: border-box !important;
}

.teacher-reports-page .reports-header h2 {
    margin: 0 0 8px 0 !important;
    padding: 0 !important;
    color: #ffffff !important;
    font-size: 26px !important;
    font-weight: 700 !important;
    line-height: 1.3 !important;
}

.teacher-reports-page .reports-header p {
    margin: 0 !important;
    padding: 0 !important;
    color: rgba(255,255,255,0.9) !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
}

/* =========================================================
   MAIN REPORT CARD
========================================================= */

.teacher-reports-page .reports-main-card {
    width: 100% !important;
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 25px !important;
    margin: 0 !important;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
    box-sizing: border-box !important;
}

.teacher-reports-page .reports-main-card h3 {
    margin: 0 0 6px 0 !important;
    padding: 0 !important;
    color: #1e293b !important;
    font-size: 19px !important;
    font-weight: 700 !important;
}

.teacher-reports-page .reports-main-card > p {
    margin: 0 0 22px 0 !important;
    padding: 0 !important;
    color: #64748b !important;
    font-size: 13px !important;
}

/* =========================================================
   TEACHER GRID
========================================================= */

.teacher-reports-page .reports-teacher-grid {
    width: 100% !important;
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(285px, 1fr)) !important;
    gap: 18px !important;
}

/* =========================================================
   TEACHER CARD
========================================================= */

.teacher-reports-page .reports-teacher-card {
    width: 100% !important;
    min-width: 0 !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 18px !important;
    margin: 0 !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}

.teacher-reports-page .reports-teacher-card:hover {
    border-color: #147cf5 !important;
    box-shadow: 0 7px 20px rgba(20, 124, 245, 0.10) !important;
    transform: translateY(-2px) !important;
}

/* =========================================================
   TEACHER INFORMATION
========================================================= */

.teacher-reports-page .reports-teacher-info {
    display: flex !important;
    align-items: center !important;
    gap: 13px !important;
    margin-bottom: 12px !important;
}

.teacher-reports-page .reports-avatar {
    width: 50px !important;
    height: 50px !important;
    min-width: 50px !important;
    border-radius: 50% !important;
    overflow: hidden !important;
    background: #eaf3ff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #147cf5 !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.teacher-reports-page .reports-avatar img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

.teacher-reports-page .reports-teacher-name {
    margin: 0 0 3px 0 !important;
    padding: 0 !important;
    color: #1e293b !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    line-height: 1.4 !important;
}

.teacher-reports-page .reports-teacher-id {
    margin: 0 !important;
    padding: 0 !important;
    color: #64748b !important;
    font-size: 12px !important;
}

/* =========================================================
   STATUS
========================================================= */

.teacher-reports-page .reports-status {
    display: inline-block !important;
    padding: 5px 10px !important;
    margin: 0 0 15px 0 !important;
    border-radius: 20px !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
}

.teacher-reports-page .reports-status-active {
    background: #dcfce7 !important;
    color: #15803d !important;
}

.teacher-reports-page .reports-status-inactive {
    background: #fee2e2 !important;
    color: #b91c1c !important;
}

/* =========================================================
   REPORT DESCRIPTION
========================================================= */

.teacher-reports-page .reports-description {
    width: 100% !important;
    background: #f8fafc !important;
    border: 1px solid #eef2f7 !important;
    border-radius: 10px !important;
    padding: 12px !important;
    margin: 0 0 16px 0 !important;
    box-sizing: border-box !important;
}

.teacher-reports-page .reports-description-title {
    margin: 0 0 5px 0 !important;
    color: #334155 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.teacher-reports-page .reports-description-text {
    margin: 0 !important;
    color: #64748b !important;
    font-size: 11px !important;
    line-height: 1.6 !important;
}

/* =========================================================
   ACTION AREA
========================================================= */

.teacher-reports-page .reports-actions {
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
    margin: 0 !important;
}

/* =========================================================
   REPORT BUTTONS
   !important prevents AdminLTE/layout button overrides
========================================================= */

.teacher-reports-page .reports-action-btn,
.teacher-reports-page a.reports-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;

    width: auto !important;
    min-width: 0 !important;
    height: auto !important;

    margin: 0 !important;
    padding: 9px 12px !important;

    border: 0 !important;
    border-radius: 8px !important;

    font-family: inherit !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;

    text-decoration: none !important;
    box-shadow: none !important;

    cursor: pointer !important;
    transition: all 0.2s ease !important;
}

.teacher-reports-page .reports-action-btn i {
    font-size: 12px !important;
    line-height: 1 !important;
}

/* Profile */

.teacher-reports-page .reports-profile-btn {
    background: #eaf3ff !important;
    color: #147cf5 !important;
}

.teacher-reports-page .reports-profile-btn:hover {
    background: #147cf5 !important;
    color: #ffffff !important;
}

/* Timetable */

.teacher-reports-page .reports-timetable-btn {
    background: #f1efff !important;
    color: #6c63ff !important;
}

.teacher-reports-page .reports-timetable-btn:hover {
    background: #6c63ff !important;
    color: #ffffff !important;
}

/* Complete Report */

.teacher-reports-page .reports-complete-btn {
    background: #ecfdf5 !important;
    color: #059669 !important;
}

.teacher-reports-page .reports-complete-btn:hover {
    background: #059669 !important;
    color: #ffffff !important;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.teacher-reports-page .reports-empty {
    width: 100% !important;
    text-align: center !important;
    padding: 55px 20px !important;
    box-sizing: border-box !important;
}

.teacher-reports-page .reports-empty i {
    display: block !important;
    margin-bottom: 12px !important;
    color: #94a3b8 !important;
    font-size: 45px !important;
}

.teacher-reports-page .reports-empty h4 {
    margin: 0 0 7px 0 !important;
    color: #334155 !important;
    font-size: 17px !important;
}

.teacher-reports-page .reports-empty p {
    margin: 0 !important;
    color: #64748b !important;
    font-size: 13px !important;
}

/* =========================================================
   FACULTY REPORT FILTERS
========================================================= */

.teacher-reports-page .teacher-report-filters {
    width: 100%;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 25px;
    box-sizing: border-box;
}

.teacher-reports-page .filter-row {
    width: 100%;
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1.5fr 1fr;
    gap: 14px;
    align-items: end;
}

.teacher-reports-page .filter-group {
    min-width: 0;
}

.teacher-reports-page .filter-group label {
    display: block;
    margin-bottom: 6px;
    color: #334155;
    font-size: 12px;
    font-weight: 700;
}

.teacher-reports-page .filter-group input,
.teacher-reports-page .filter-group select {
    width: 100%;
    height: 40px;
    padding: 8px 11px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    color: #334155;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
}

.teacher-reports-page .filter-group input:focus,
.teacher-reports-page .filter-group select:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10);
}

.teacher-reports-page .filter-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 16px;
}

.teacher-reports-page .filter-apply-btn,
.teacher-reports-page .filter-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 40px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
}

.teacher-reports-page .filter-apply-btn {
    border: 0;
    background: #147cf5;
    color: #ffffff;
}

.teacher-reports-page .filter-apply-btn:hover {
    background: #0f6bd6;
}

.teacher-reports-page .filter-reset-btn {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
}

.teacher-reports-page .filter-reset-btn:hover {
    background: #f1f5f9;
}


/* =========================================================
   FILTER RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .teacher-reports-page .filter-row {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 700px) {

    .teacher-reports-page .filter-row {
        grid-template-columns: 1fr;
    }

    .teacher-reports-page .filter-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .teacher-reports-page .filter-apply-btn,
    .teacher-reports-page .filter-reset-btn {
        width: 100%;
    }
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .teacher-reports-page {
        padding: 20px !important;
    }

    .teacher-reports-page .reports-teacher-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

@media (max-width: 600px) {

    .teacher-reports-page {
        padding: 15px !important;
    }

    .teacher-reports-page .reports-header {
        padding: 22px !important;
    }

    .teacher-reports-page .reports-header h2 {
        font-size: 22px !important;
    }

    .teacher-reports-page .reports-main-card {
        padding: 18px !important;
    }

    .teacher-reports-page .reports-teacher-grid {
        grid-template-columns: 1fr !important;
    }

    .teacher-reports-page .reports-actions {
        flex-direction: column !important;
        align-items: stretch !important;
    }

    .teacher-reports-page .reports-action-btn {
        width: 100% !important;
    }
}
</style>


<div class="teacher-reports-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="reports-header">

        <h2>
            <i class="bi bi-file-earmark-bar-graph"></i>
            Teacher Reports
        </h2>

        <p>
            View teacher information, timetable and complete reports.
        </p>

    </div>


    {{-- =====================================================
         MAIN CARD
    ====================================================== --}}

    <div class="reports-main-card">

        <h3>
            <i class="bi bi-people"></i>
            Teacher Reports
        </h3>

        <p>
            Select a teacher to view their available reports.
        </p>

        {{-- =====================================================
     FACULTY REPORT FILTERS
====================================================== --}}

<div class="teacher-report-filters">

    <form method="GET" action="{{ route('admin.teachers.reports.index') }}">

        <div class="filter-row">

            {{-- Faculty Search --}}
            <div class="filter-group search-group">
                <label for="search">Faculty Search</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, ID, email, phone..."
                >
            </div>

            {{-- From Date --}}
            <div class="filter-group">
                <label for="from_date">From Date</label>
                <input
                    type="date"
                    id="from_date"
                    name="from_date"
                    value="{{ request('from_date') }}"
                >
            </div>

            {{-- To Date --}}
            <div class="filter-group">
                <label for="to_date">To Date</label>
                <input
                    type="date"
                    id="to_date"
                    name="to_date"
                    value="{{ request('to_date') }}"
                >
            </div>

            {{-- Gender --}}
            <div class="filter-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">All Genders</option>

                    @foreach($genders ?? [] as $gender)
                        <option
                            value="{{ $gender }}"
                            {{ request('gender') == $gender ? 'selected' : '' }}
                        >
                            {{ $gender }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Subject --}}
            <div class="filter-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject">
                    <option value="">All Subjects</option>

                    @foreach($subjects ?? [] as $subject)
                        <option
                            value="{{ $subject }}"
                            {{ request('subject') == $subject ? 'selected' : '' }}
                        >
                            {{ $subject }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="filter-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <div class="filter-actions">

            <button type="submit" class="filter-apply-btn">
                <i class="bi bi-funnel"></i>
                Apply Filters
            </button>

            <a
                href="{{ route('admin.teachers.reports.index') }}"
                class="filter-reset-btn"
            >
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset
            </a>

        </div>

    </form>

</div>


        @if($teachers->count() > 0)

            <div class="reports-teacher-grid">

                @foreach($teachers as $teacher)

                    <div class="reports-teacher-card">

                        {{-- Teacher information --}}

                        <div class="reports-teacher-info">

                            <div class="reports-avatar">

                                @if($teacher->profile_image)

                                    <img
                                        src="{{ $teacher->profile_image }}"
                                        alt="{{ $teacher->first_name }}"
                                    >

                                @else

                                    {{ strtoupper(substr($teacher->first_name, 0, 1)) }}

                                @endif

                            </div>


                            <div>

                                <div class="reports-teacher-name">
                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}
                                </div>

                                <div class="reports-teacher-id">
                                    Teacher ID: {{ $teacher->teacher_id }}
                                </div>

                            </div>

                        </div>


                        {{-- Status --}}

                        @if($teacher->status === 'Active')

                            <span class="reports-status reports-status-active">
                                Active
                            </span>

                        @else

                            <span class="reports-status reports-status-inactive">
                                Inactive
                            </span>

                        @endif


                        {{-- Report information --}}

                        <div class="reports-description">

                            <div class="reports-description-title">
                                Complete Teacher Report
                            </div>

                            <div class="reports-description-text">
                                Teacher information, lectures,
                                working hours, leaves, timetable,
                                attendance and other available records.
                            </div>

                        </div>


                        {{-- Actions --}}

                        <div class="reports-actions">

                            {{-- Profile --}}

                            <a
                                href="{{ route('admin.teachers.show', $teacher->id) }}"
                                class="reports-action-btn reports-profile-btn"
                            >
                                <i class="bi bi-person"></i>
                                Profile
                            </a>


                            {{-- Timetable --}}

                            <a
                                href="{{ route('admin.timetable.index') }}"
                                class="reports-action-btn reports-timetable-btn"
                            >
                                <i class="bi bi-calendar3"></i>
                                Timetable
                            </a>


                            {{-- Complete Report --}}

                            <a
                                href="{{ route('admin.teachers.reports.show', $teacher->id) }}"
                                class="reports-action-btn reports-complete-btn"
                            >
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                Complete Report
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            {{-- Empty state --}}

            <div class="reports-empty">

                <i class="bi bi-people"></i>

                <h4>
                    No Teachers Found
                </h4>

                <p>
                    Add a teacher first to generate teacher reports.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection