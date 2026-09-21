@extends('layouts.app')

@section('title', 'Sports Achievements | Admin')

@section('content')

<div class="sports-achievements-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
        ========================================================== --}}
        <div class="achievement-page-header">

            <div class="achievement-heading-area">

                <div class="achievement-heading-icon">
                    <i class="fas fa-trophy"></i>
                </div>

                <div>
                    <div class="achievement-breadcrumb">
                        Sports
                        <span>/</span>
                        Achievements
                    </div>

                    <h1>Sports Achievements</h1>

                    <p>
                        Manage and track student sports achievements,
                        competitions and positions.
                    </p>
                </div>

            </div>

            <div class="achievement-header-action">
                <a href="{{ route('admin.sports.achievements.create') }}"
                   class="achievement-add-btn">
                    <i class="fas fa-plus"></i>
                    <span>Add Achievement</span>
                </a>
            </div>

        </div>


        {{-- =========================================================
             SUCCESS MESSAGE
        ========================================================== --}}
        @if(session('success'))
            <div class="achievement-alert success-alert">
                <div class="alert-icon">
                    <i class="fas fa-check"></i>
                </div>

                <div class="alert-content">
                    <strong>Success</strong>
                    <span>{{ session('success') }}</span>
                </div>

                <button type="button"
                        class="alert-close"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif


        {{-- =========================================================
             SUMMARY CARDS
        ========================================================== --}}
        <div class="achievement-summary-grid">

            <div class="achievement-summary-card blue-card">
                <div class="summary-content">
                    <span>Total Achievements</span>
                    <strong>{{ $achievements->total() }}</strong>
                    <small>
                        <i class="fas fa-trophy"></i>
                        Recorded achievements
                    </small>
                </div>

                <div class="summary-icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>


            <div class="achievement-summary-card orange-card">
                <div class="summary-content">
                    <span>Active</span>
                    <strong>
                        {{ \App\Models\Sports\Achievements\Achievement::where('status', 'active')->count() }}
                    </strong>
                    <small>
                        <i class="fas fa-check-circle"></i>
                        Currently active
                    </small>
                </div>

                <div class="summary-icon">
                    <i class="fas fa-award"></i>
                </div>
            </div>


            <div class="achievement-summary-card cyan-card">
                <div class="summary-content">
                    <span>Sports</span>
                    <strong>{{ $sports->count() }}</strong>
                    <small>
                        <i class="fas fa-running"></i>
                        Sports represented
                    </small>
                </div>

                <div class="summary-icon">
                    <i class="fas fa-running"></i>
                </div>
            </div>


            <div class="achievement-summary-card red-card">
                <div class="summary-content">
                    <span>Inactive</span>
                    <strong>
                        {{ \App\Models\Sports\Achievements\Achievement::where('status', 'inactive')->count() }}
                    </strong>
                    <small>
                        <i class="fas fa-ban"></i>
                        Inactive records
                    </small>
                </div>

                <div class="summary-icon">
                    <i class="fas fa-ban"></i>
                </div>
            </div>

        </div>


        {{-- =========================================================
             FILTER CARD
        ========================================================== --}}
        <div class="achievement-filter-card">

            <div class="filter-card-header">

                <div class="filter-title">
                    <div class="filter-title-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>

                    <div>
                        <h3>Search & Filters</h3>
                        <p>Find achievements quickly using the available filters.</p>
                    </div>
                </div>

                @if(request()->hasAny([
                    'search',
                    'status',
                    'sport_name',
                    'academic_year'
                ]))
                    <a href="{{ route('admin.sports.achievements.index') }}"
                       class="clear-filter-btn">
                        <i class="fas fa-redo"></i>
                        Reset Filters
                    </a>
                @endif

            </div>


            <form method="GET"
                  action="{{ route('admin.sports.achievements.index') }}">

                <div class="achievement-filter-grid">

                    {{-- Search --}}
                    <div class="filter-field search-field">

                        <label for="search">
                            Search
                        </label>

                        <div class="input-icon-wrapper">
                            <i class="fas fa-search"></i>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Student, achievement, competition..."
                            >
                        </div>

                    </div>


                    {{-- Sport --}}
                    <div class="filter-field">

                        <label for="sport_name">
                            Sport
                        </label>

                        <select name="sport_name"
                                id="sport_name"
                                class="form-control">

                            <option value="">All Sports</option>

                            @foreach($sports as $sport)
                                <option value="{{ $sport }}"
                                    {{ request('sport_name') == $sport ? 'selected' : '' }}>
                                    {{ $sport }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- Academic Year --}}
                    <div class="filter-field">

                        <label for="academic_year">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                id="academic_year"
                                class="form-control">

                            <option value="">All Years</option>

                            @foreach($academicYears as $year)
                                <option value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="filter-field">

                        <label for="status">
                            Status
                        </label>

                        <select name="status"
                                id="status"
                                class="form-control">

                            <option value="">All Status</option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Button --}}
                    <div class="filter-submit-field">

                        <button type="submit"
                                class="filter-search-btn">

                            <i class="fas fa-search"></i>
                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- =========================================================
             ACHIEVEMENTS TABLE CARD
        ========================================================== --}}
        <div class="achievement-table-card">

            <div class="table-card-header">

                <div class="table-heading">

                    <div class="table-heading-icon">
                        <i class="fas fa-medal"></i>
                    </div>

                    <div>
                        <h3>Achievement Records</h3>

                        <p>
                            Showing
                            <strong>{{ $achievements->firstItem() ?? 0 }}</strong>
                            –
                            <strong>{{ $achievements->lastItem() ?? 0 }}</strong>
                            of
                            <strong>{{ $achievements->total() }}</strong>
                            achievements
                        </p>
                    </div>

                </div>

                <div class="record-count">
                    <i class="fas fa-list"></i>
                    {{ $achievements->total() }} Records
                </div>

            </div>


            <div class="achievement-table-wrapper">

                @if($achievements->count())

                    <table class="achievement-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Achievement</th>
                                <th>Student</th>
                                <th>Sport</th>
                                <th>Competition</th>
                                <th>Position</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="action-column">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($achievements as $achievement)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        <span class="record-number">
                                            {{ $achievements->firstItem() + $loop->index }}
                                        </span>
                                    </td>


                                    {{-- Achievement --}}
                                    <td>

                                        <div class="achievement-info">

                                            <div class="achievement-mini-icon">
                                                <i class="fas fa-trophy"></i>
                                            </div>

                                            <div>
                                                <div class="achievement-name">
                                                    {{ $achievement->title }}
                                                </div>

                                                @if($achievement->achievement_type)
                                                    <div class="achievement-type">
                                                        {{ $achievement->achievement_type }}
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                    </td>


                                    {{-- Student --}}
                                    <td>

                                        @if($achievement->student_name)

                                            <div class="student-info">

                                                <div class="student-avatar">
                                                    {{ strtoupper(substr($achievement->student_name, 0, 1)) }}
                                                </div>

                                                <span>
                                                    {{ $achievement->student_name }}
                                                </span>

                                            </div>

                                        @else

                                            <span class="empty-value">
                                                Not specified
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Sport --}}
                                    <td>

                                        <span class="sport-badge">
                                            <i class="fas fa-running"></i>
                                            {{ $achievement->sport_name }}
                                        </span>

                                    </td>


                                    {{-- Competition --}}
                                    <td>

                                        @if($achievement->competition_name)

                                            <div class="competition-info">
                                                <i class="fas fa-flag"></i>
                                                <span>
                                                    {{ $achievement->competition_name }}
                                                </span>
                                            </div>

                                        @else

                                            <span class="empty-value">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Position --}}
                                    <td>

                                        @if($achievement->position)

                                            <span class="position-badge">
                                                <i class="fas fa-award"></i>
                                                {{ $achievement->position }}
                                            </span>

                                        @else

                                            <span class="empty-value">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        @if($achievement->achievement_date)

                                            <div class="date-info">

                                                <i class="far fa-calendar-alt"></i>

                                                <span>
                                                    {{ $achievement->achievement_date->format('d M Y') }}
                                                </span>

                                            </div>

                                        @else

                                            <span class="empty-value">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($achievement->status === 'active')

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

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="table-actions">

                                            <a href="{{ route('admin.sports.achievements.show', $achievement) }}"
                                               class="action-btn view-btn"
                                               title="View Achievement">
                                                <i class="fas fa-eye"></i>
                                            </a>


                                            <a href="{{ route('admin.sports.achievements.edit', $achievement) }}"
                                               class="action-btn edit-btn"
                                               title="Edit Achievement">
                                                <i class="fas fa-edit"></i>
                                            </a>


                                            <form method="POST"
                                                  action="{{ route('admin.sports.achievements.destroy', $achievement) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this achievement?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="action-btn delete-btn"
                                                        title="Delete Achievement">

                                                    <i class="fas fa-trash-alt"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    {{-- Empty State --}}
                    <div class="achievement-empty-state">

                        <div class="empty-icon">
                            <i class="fas fa-trophy"></i>
                        </div>

                        <h3>No Achievements Found</h3>

                        <p>
                            There are no achievement records matching your
                            current search or filters.
                        </p>

                        <a href="{{ route('admin.sports.achievements.create') }}"
                           class="empty-add-btn">
                            <i class="fas fa-plus"></i>
                            Add First Achievement
                        </a>

                    </div>

                @endif

            </div>


            {{-- Pagination --}}
            @if($achievements->hasPages())

                <div class="achievement-pagination">

                    {{ $achievements->onEachSide(1)->links() }}

                </div>

            @endif

        </div>

    </div>

</div>


<style>

/* =========================================================
   SPORTS ACHIEVEMENTS PAGE
========================================================= */

.sports-achievements-page {
    background: #f6f8fb;
    min-height: calc(100vh - 60px);
    color: #172033;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.achievement-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.achievement-heading-area {
    display: flex;
    align-items: center;
    gap: 15px;
}

.achievement-heading-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    font-size: 21px;
    box-shadow: 0 7px 18px rgba(20, 124, 245, .20);
}

.achievement-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 3px;
}

.achievement-breadcrumb span {
    color: #cbd5e1;
}

.achievement-heading-area h1 {
    margin: 0;
    color: #172033;
    font-size: 25px;
    line-height: 1.25;
    font-weight: 700;
    letter-spacing: -.3px;
}

.achievement-heading-area p {
    margin: 5px 0 0;
    color: #718096;
    font-size: 13px;
}

.achievement-header-action {
    flex-shrink: 0;
}

.achievement-add-btn {
    height: 42px;
    padding: 0 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 9px;
    color: #fff !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 600;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    box-shadow: 0 6px 15px rgba(20, 124, 245, .18);
    transition: all .2s ease;
}

.achievement-add-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 9px 20px rgba(20, 124, 245, .24);
}


/* =========================================================
   ALERT
========================================================= */

.achievement-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px 15px;
    margin-bottom: 20px;
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 11px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
}

.success-alert {
    border-left: 4px solid #20b26b;
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: #20b26b;
    font-size: 13px;
}

.alert-content {
    display: flex;
    flex-direction: column;
    gap: 1px;
    flex: 1;
}

.alert-content strong {
    color: #172033;
    font-size: 13px;
}

.alert-content span {
    color: #718096;
    font-size: 12px;
}

.alert-close {
    border: 0;
    background: transparent;
    color: #94a3b8;
    cursor: pointer;
    font-size: 13px;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.achievement-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.achievement-summary-card {
    position: relative;
    overflow: hidden;
    min-height: 165px;
    padding: 24px 25px;
    border-radius: 17px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 7px 22px rgba(15, 23, 42, .07);
}

.achievement-summary-card::after {
    content: "";
    position: absolute;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    right: -25px;
    bottom: -35px;
    background: rgba(255,255,255,.09);
}

.blue-card {
    background: linear-gradient(135deg, #147cf5, #1268ca);
}

.orange-card {
    background: linear-gradient(135deg, #ffb238, #ff9d1c);
}

.cyan-card {
    background: linear-gradient(135deg, #2bcfe8, #18b5d5);
}

.red-card {
    background: linear-gradient(135deg, #ff6d61, #f65343);
}

.summary-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
}

.summary-content span {
    font-size: 12px;
    font-weight: 600;
    opacity: .88;
}

.summary-content strong {
    margin: 5px 0;
    font-size: 29px;
    line-height: 1;
    font-weight: 700;
}

.summary-content small {
    font-size: 10px;
    opacity: .85;
}

.summary-content small i {
    margin-right: 3px;
}

.summary-icon {
    position: relative;
    z-index: 2;
    width: 46px;
    height: 46px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.17);
    font-size: 19px;
}


/* =========================================================
   FILTER CARD
========================================================= */

.achievement-filter-card,
.achievement-table-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .055);
}

.achievement-filter-card {
    padding: 19px 20px;
    margin-bottom: 20px;
}

.filter-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 17px;
}

.filter-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.filter-title-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf6ff;
    color: #147cf5;
    font-size: 14px;
}

.filter-title h3 {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.filter-title p {
    margin: 2px 0 0;
    color: #94a3b8;
    font-size: 11px;
}

.clear-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #64748b !important;
    text-decoration: none !important;
    font-size: 11px;
    font-weight: 600;
}

.clear-filter-btn:hover {
    color: #147cf5 !important;
}

.achievement-filter-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr auto;
    align-items: end;
    gap: 12px;
}

.filter-field label {
    display: block;
    margin-bottom: 6px;
    color: #475569;
    font-size: 11px;
    font-weight: 600;
}

.filter-field .form-control {
    height: 40px;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    color: #334155;
    background: #fff;
    font-size: 12px;
    box-shadow: none;
    transition: all .18s ease;
}

.filter-field .form-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20,124,245,.08);
}

.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper > i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 12px;
    z-index: 2;
}

.input-icon-wrapper .form-control {
    padding-left: 35px;
}

.filter-search-btn {
    height: 40px;
    padding: 0 17px;
    border: 0;
    border-radius: 8px;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 5px 13px rgba(20,124,245,.16);
    transition: all .2s ease;
}

.filter-search-btn:hover {
    transform: translateY(-1px);
}


/* =========================================================
   TABLE CARD
========================================================= */

.table-card-header {
    min-height: 72px;
    padding: 15px 19px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.table-heading {
    display: flex;
    align-items: center;
    gap: 11px;
}

.table-heading-icon {
    width: 37px;
    height: 37px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff5df;
    color: #f5a623;
    font-size: 14px;
}

.table-heading h3 {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 700;
}

.table-heading p {
    margin: 3px 0 0;
    color: #94a3b8;
    font-size: 11px;
}

.table-heading p strong {
    color: #64748b;
}

.record-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 7px;
    background: #f6f8fb;
    color: #64748b;
    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   TABLE
========================================================= */

.achievement-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.achievement-table {
    width: 100%;
    min-width: 1050px;
    margin: 0;
    border-collapse: collapse;
}

.achievement-table thead th {
    padding: 12px 13px;
    background: #fafbfc;
    border-bottom: 1px solid #edf0f5;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .35px;
    white-space: nowrap;
}

.achievement-table tbody td {
    padding: 13px;
    border-bottom: 1px solid #f0f2f5;
    color: #475569;
    font-size: 11px;
    vertical-align: middle;
}

.achievement-table tbody tr {
    transition: background .15s ease;
}

.achievement-table tbody tr:hover {
    background: #fbfdff;
}

.achievement-table tbody tr:last-child td {
    border-bottom: 0;
}

.record-number {
    color: #94a3b8;
    font-size: 10px;
    font-weight: 600;
}


/* Achievement info */

.achievement-info {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 180px;
}

.achievement-mini-icon {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef6ff;
    color: #147cf5;
    font-size: 13px;
}

.achievement-name {
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #172033;
    font-size: 11px;
    font-weight: 700;
}

.achievement-type {
    margin-top: 2px;
    color: #94a3b8;
    font-size: 9px;
}


/* Student */

.student-info {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.student-avatar {
    width: 29px;
    height: 29px;
    min-width: 29px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf6ff;
    color: #147cf5;
    font-size: 10px;
    font-weight: 700;
}

.student-info span {
    color: #334155;
    font-weight: 600;
}


/* Sport */

.sport-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 6px;
    background: #eefbfd;
    color: #159db6;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}


/* Competition */

.competition-info {
    display: flex;
    align-items: center;
    gap: 6px;
    max-width: 150px;
}

.competition-info i {
    color: #94a3b8;
    font-size: 10px;
}

.competition-info span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/* Position */

.position-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 6px;
    background: #fff7e8;
    color: #e69516;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.position-badge i {
    font-size: 9px;
}


/* Date */

.date-info {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    color: #64748b;
}

.date-info i {
    color: #94a3b8;
}


/* Empty */

.empty-value {
    color: #b0b8c4;
    font-size: 10px;
}


/* Status */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 8px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.status-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
}

.status-active {
    background: #eafaf2;
    color: #15945c;
}

.status-active .status-dot {
    background: #20b26b;
}

.status-inactive {
    background: #fff0ee;
    color: #df5b50;
}

.status-inactive .status-dot {
    background: #f65343;
}


/* =========================================================
   ACTIONS
========================================================= */

.action-column {
    text-align: center;
}

.table-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.table-actions form {
    margin: 0;
}

.action-btn {
    width: 29px;
    height: 29px;
    padding: 0;
    border: 1px solid #e8edf3;
    border-radius: 7px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    cursor: pointer;
    font-size: 10px;
    transition: all .18s ease;
}

.view-btn {
    color: #147cf5;
}

.view-btn:hover {
    background: #edf6ff;
    border-color: #cfe5ff;
}

.edit-btn {
    color: #e59a19;
}

.edit-btn:hover {
    background: #fff7e8;
    border-color: #ffe0a5;
}

.delete-btn {
    color: #e65a50;
}

.delete-btn:hover {
    background: #fff0ee;
    border-color: #ffd2cd;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.achievement-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 15px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef6ff;
    color: #147cf5;
    font-size: 25px;
}

.achievement-empty-state h3 {
    margin: 0;
    color: #172033;
    font-size: 16px;
    font-weight: 700;
}

.achievement-empty-state p {
    max-width: 430px;
    margin: 7px auto 18px;
    color: #94a3b8;
    font-size: 12px;
}

.empty-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 14px;
    border-radius: 8px;
    background: #147cf5;
    color: #fff !important;
    text-decoration: none !important;
    font-size: 11px;
    font-weight: 600;
}


/* =========================================================
   PAGINATION
========================================================= */

.achievement-pagination {
    padding: 15px 19px;
    border-top: 1px solid #edf0f5;
}

.achievement-pagination nav {
    display: flex;
    justify-content: flex-end;
}

.achievement-pagination .pagination {
    margin: 0;
    gap: 4px;
}

.achievement-pagination .page-link {
    min-width: 30px;
    height: 30px;
    padding: 0 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5eaf0;
    border-radius: 7px !important;
    color: #64748b;
    background: #fff;
    font-size: 10px;
    box-shadow: none;
}

.achievement-pagination .page-link:hover {
    background: #edf6ff;
    border-color: #cfe5ff;
    color: #147cf5;
}

.achievement-pagination .page-item.active .page-link {
    border-color: #147cf5;
    background: #147cf5;
    color: #fff;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .achievement-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .achievement-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-submit-field {
        grid-column: span 2;
    }

    .filter-search-btn {
        width: 100%;
    }
}


@media (max-width: 768px) {

    .sports-achievements-page .container-fluid {
        padding-top: 18px !important;
    }

    .achievement-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .achievement-header-action {
        width: 100%;
    }

    .achievement-add-btn {
        width: 100%;
    }

    .achievement-heading-area h1 {
        font-size: 21px;
    }

    .achievement-summary-grid {
        grid-template-columns: 1fr;
    }

    .achievement-filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-submit-field {
        grid-column: auto;
    }

    .filter-card-header,
    .table-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .record-count {
        align-self: flex-start;
    }

    .achievement-pagination nav {
        justify-content: center;
    }
}


@media (max-width: 480px) {

    .achievement-page-header {
        margin-bottom: 18px;
    }

    .achievement-heading-area {
        align-items: flex-start;
    }

    .achievement-heading-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        font-size: 18px;
    }

    .achievement-heading-area p {
        font-size: 11px;
    }

    .achievement-summary-card {
        min-height: 115px;
    }

    .achievement-filter-card {
        padding: 15px;
    }

    .table-card-header {
        padding: 14px;
    }

}

</style>

@endsection