@extends('layouts.app')

@section('title', 'Timetable Management')
@section('page-title', 'Timetable Management')

@section('content')

<style>
    .tt-page{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .tt-hero{
        display:grid;
        grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);
        margin-bottom:22px;
        overflow:hidden;
        position:relative;
    }
    .tt-hero::before{
        content:"";
        position:absolute;
        left:34px;top:0;bottom:0;
        width:2px;
        background:rgba(228,87,46,.35);
    }
    .tt-hero-left{padding:26px 28px 26px 56px}
    .tt-hero-left h1{
        font-family:var(--serif);
        font-size:28px;font-weight:700;
        color:var(--ink);margin:0 0 6px;
    }
    .tt-hero-left p{
        color:var(--muted);margin:0 0 18px;
        font-size:14px;line-height:1.5;
    }
    .hero-btns{display:flex;gap:10px;flex-wrap:wrap}
    .btn-solid,.btn-line{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:500;
        text-decoration:none;border:1px solid transparent;
    }
    .btn-solid{background:var(--ink);color:#fff;border-color:var(--ink)}
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-line{background:#fff;border-color:#cfc8b6;color:var(--text)}
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}

    .tt-hero-right{
        background:var(--ink);color:#e9efe2;
        padding:26px 28px;
        display:flex;flex-direction:column;justify-content:center;gap:8px;
        background-image:radial-gradient(ellipse at 20% 0,rgba(255,255,255,.09),transparent 60%);
    }
    .tt-hero-right .label{font-family:var(--hand);font-size:22px;color:var(--chalk);line-height:1}
    .tt-hero-right .value{font-family:var(--serif);font-size:26px;font-weight:700;color:#fff;line-height:1.15}
    .tt-hero-right .sub{font-size:13px;color:#c5d0bd}

    /* KPI */
    .kpi-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:18px;margin-bottom:22px;
    }
    .kpi{
        transition:transform .22s ease,box-shadow .22s ease;
        position:relative;padding:22px 22px 16px;margin-top:10px;
        border-top:4px solid var(--c);
        display:flex;flex-direction:column;gap:14px;
    }
    .kpi:hover{transform:translateY(-8px);box-shadow:0 16px 28px rgba(29,58,51,.16)}
    .kpi-tab{
        position:absolute;top:-24px;left:16px;
        padding:3px 12px;background:var(--c);color:#fff;
        font-size:12px;font-weight:600;border-radius:7px 7px 0 0;
    }
    .kpi-top{display:flex;align-items:center;justify-content:space-between;gap:12px}
    .kpi-value{font-family:var(--serif);font-size:34px;font-weight:700;line-height:1;color:var(--ink)}
    .kpi-icon{
        width:46px;height:46px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;
        font-size:20px;flex-shrink:0;
        background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c);
    }
    .kpi-label{padding-top:12px;border-top:1px dashed #d8d1bf;font-size:13px;color:var(--muted)}
    .c-blue{--c:#2b5d8a}
    .c-amber{--c:#d58a0b}
    .c-red{--c:#e4572e}
    .c-violet{--c:#7a4fb5}

    /* Panel */
    .panel{overflow:hidden;margin-bottom:22px}
    .panel-head{
        display:flex;justify-content:space-between;align-items:center;
        gap:12px;padding:15px 22px;border-bottom:1px dashed #d8d1bf;flex-wrap:wrap;
    }
    .panel-head h3{font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .panel-head span{font-size:12.5px;color:var(--muted)}

    /* Filters */
    .filter-area{padding:18px 22px;border-bottom:1px dashed #d8d1bf;background:#faf9f6}
    .filter-grid{
        display:grid;
        grid-template-columns:2fr repeat(5,1fr);
        gap:12px;
    }
    .filter-group label{
        display:block;margin-bottom:5px;
        font-size:11px;font-weight:700;color:var(--muted);
        text-transform:uppercase;letter-spacing:.3px;
    }
    .filter-control{
        width:100%;height:38px;
        padding:0 12px;border:1px solid #cfc8b6;border-radius:8px;
        background:#fff;color:var(--text);font-size:13px;outline:none;
    }
    .filter-control:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(29,58,51,.08)}
    .clear-filter{
        margin-top:14px;border:none;background:transparent;
        color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;padding:0;
    }
    .clear-filter:hover{color:var(--ink)}

    /* Table */
    .table-wrap{width:100%;overflow-x:auto}
    .tt-table{width:100%;min-width:1400px;border-collapse:collapse}
    .tt-table thead th{
        padding:13px 14px;background:#f8f6f0;
        border-bottom:1px solid #e6e0d2;
        color:var(--muted);font-size:10px;font-weight:700;
        text-transform:uppercase;letter-spacing:.4px;
        white-space:nowrap;text-align:left;
    }
    .tt-table tbody td{
        padding:13px 14px;border-bottom:1px solid #f0ebe0;
        color:var(--text);font-size:12.5px;vertical-align:middle;white-space:nowrap;
    }
    .tt-table tbody tr:hover td{background:#fffdf6}
    .tt-table tbody tr:last-child td{border-bottom:none}

    .teacher-cell{display:flex;align-items:center;gap:10px}
    .teacher-avatar{
        width:34px;height:34px;border-radius:8px;
        display:flex;align-items:center;justify-content:center;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;font-size:12px;font-weight:700;flex-shrink:0;
    }
    .teacher-name{font-weight:700;color:var(--ink);font-size:13px}
    .teacher-id{font-size:10.5px;color:var(--muted);margin-top:1px}

    .badge{
        display:inline-flex;align-items:center;gap:4px;
        padding:4px 8px;border-radius:6px;
        font-size:10.5px;font-weight:700;
    }
    .badge-date{background:color-mix(in srgb,#2b5d8a 10%,#fff);color:#2b5d8a}
    .badge-day{background:color-mix(in srgb,#2b5d8a 10%,#fff);color:#2b5d8a}
    .badge-period{background:color-mix(in srgb,#7a4fb5 10%,#fff);color:#7a4fb5}
    .badge-regular{background:color-mix(in srgb,#3a9d6b 12%,#fff);color:#3a9d6b}
    .badge-break{background:color-mix(in srgb,#d58a0b 12%,#fff);color:#d58a0b}
    .badge-lunch{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e}
    .badge-activity{background:color-mix(in srgb,#7a4fb5 12%,#fff);color:#7a4fb5}

    .actions-cell{display:flex;align-items:center;gap:6px}
    .action-btn{
        width:32px;height:32px;
        display:inline-flex;align-items:center;justify-content:center;
        border-radius:7px;border:1px solid transparent;
        font-size:13px;text-decoration:none;cursor:pointer;transition:all .15s;
    }
    .action-view{background:color-mix(in srgb,#2b5d8a 12%,#fff);color:#2b5d8a}
    .action-view:hover{background:#2b5d8a;color:#fff}
    .action-edit{background:color-mix(in srgb,#7a4fb5 12%,#fff);color:#7a4fb5}
    .action-edit:hover{background:#7a4fb5;color:#fff}
    .action-delete{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e;border:none}
    .action-delete:hover{background:#e4572e;color:#fff}

    .empty{padding:50px 20px;text-align:center}
    .empty i{font-size:28px;color:var(--muted);display:block;margin-bottom:10px}
    .empty h4{font-family:var(--serif);font-size:16px;font-weight:700;color:var(--ink);margin:0 0 6px}
    .empty p{margin:0 0 16px;color:var(--muted);font-size:13px}

    .success-msg,.error-msg{
        margin-bottom:18px;padding:12px 16px;border-radius:8px;
        font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;
    }
    .success-msg{background:#f0faf3;border:1px solid #c5e6d0;color:#2b7a52}
    .error-msg{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c}

    @media (max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr)}.filter-grid{grid-template-columns:repeat(3,1fr)}}
    @media (max-width:900px){
        .tt-hero{grid-template-columns:1fr}
        .tt-hero-left{padding:22px 20px 22px 44px}
        .tt-hero::before{left:24px}
        .filter-grid{grid-template-columns:repeat(2,1fr)}
    }
    @media (max-width:650px){
        .kpi-grid{grid-template-columns:1fr}
        .filter-grid{grid-template-columns:1fr}
        .tt-hero-left h1{font-size:23px}
    }
</style>

@php
    $totalEntries = $timetables->count();
    $regularEntries = $timetables->where('period_type', 'Regular')->count();
    $breakEntries = $timetables->whereIn('period_type', ['Break', 'Lunch'])->count();
    $activityEntries = $timetables->where('period_type', 'Activity')->count();
@endphp

<div class="tt-page">

    @if(session('success'))
        <div class="success-msg">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-msg">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Hero --}}
    <section class="paper tt-hero">
        <div class="tt-hero-left">
            <h1>Timetable Management</h1>
            <p>Manage teacher schedules, classes, subjects, activities and periods.</p>
            <div class="hero-btns">
                <a href="{{ route('admin.timetable.create') }}" class="btn-solid">
                    <i class="bi bi-plus-circle-fill"></i> Add Timetable
                </a>
            </div>
        </div>
        <div class="tt-hero-right">
            <div class="label">Schedule</div>
            <div class="value">{{ $totalEntries }} Entries</div>
            <div class="sub">Total timetable records</div>
        </div>
    </section>

    {{-- KPI --}}
    <div class="kpi-grid">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Total</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $totalEntries }}</div>
                <div class="kpi-icon"><i class="bi bi-calendar3"></i></div>
            </div>
            <div class="kpi-label">Total entries</div>
        </div>
        <div class="paper kpi c-amber">
            <span class="kpi-tab">Regular</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $regularEntries }}</div>
                <div class="kpi-icon"><i class="bi bi-book"></i></div>
            </div>
            <div class="kpi-label">Regular classes</div>
        </div>
        <div class="paper kpi c-red">
            <span class="kpi-tab">Break</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $breakEntries }}</div>
                <div class="kpi-icon"><i class="bi bi-cup-hot"></i></div>
            </div>
            <div class="kpi-label">Break / Lunch</div>
        </div>
        <div class="paper kpi c-violet">
            <span class="kpi-tab">Activity</span>
            <div class="kpi-top">
                <div class="kpi-value">{{ $activityEntries }}</div>
                <div class="kpi-icon"><i class="bi bi-stars"></i></div>
            </div>
            <div class="kpi-label">Activities</div>
        </div>
    </div>

    {{-- Table --}}
    <section class="paper panel">
        <div class="panel-head">
            <div>
                <h3>Timetable Entries</h3>
                <span>View and manage all scheduled periods</span>
            </div>
            <span>Showing <span id="visibleCount">{{ $totalEntries }}</span> entries</span>
        </div>

        {{-- Filters --}}
        <div class="filter-area">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="searchInput" class="filter-control"
                           placeholder="Teacher, class, subject, room...">
                </div>
                <div class="filter-group">
                    <label>Date</label>
                    <input type="date" id="dateFilter" class="filter-control">
                </div>
                <div class="filter-group">
                    <label>Day</label>
                    <select id="dayFilter" class="filter-control">
                        <option value="">All Days</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Period Type</label>
                    <select id="periodTypeFilter" class="filter-control">
                        <option value="">All Types</option>
                        <option value="Regular">Regular</option>
                        <option value="Break">Break</option>
                        <option value="Lunch">Lunch</option>
                        <option value="Activity">Activity</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Academic Year</label>
                    <select id="academicYearFilter" class="filter-control">
                        <option value="">All Years</option>
                        @foreach($timetables->pluck('academic_year')->filter()->unique()->sort() as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Class</label>
                    <select id="classFilter" class="filter-control">
                        <option value="">All Classes</option>
                        @foreach($timetables->pluck('class')->filter()->unique()->sort() as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" id="clearFilters" class="clear-filter">
                <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
            </button>
        </div>

        <div class="table-wrap">
            <table class="tt-table" id="timetableTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Teacher</th>
                        <th>Academic Year</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Period</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Subject / Activity</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timetables as $timetable)
                        @php
                            $teacher = $timetable->teacher;
                            $teacherName = $teacher
                                ? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''))
                                : '';
                            $teacherName = $teacherName ?: 'Teacher';

                            $initials = 'T';
                            if ($teacher) {
                                $initials = strtoupper(substr(trim($teacher->first_name ?? ''), 0, 1)
                                    . substr(trim($teacher->last_name ?? ''), 0, 1));
                            }

                            $searchDate = '';
                            if ($timetable->timetable_date) {
                                try {
                                    $searchDate = \Carbon\Carbon::parse($timetable->timetable_date)->format('Y-m-d');
                                } catch (\Throwable $e) {}
                            }

                            $periodType = trim((string)($timetable->period_type ?? ''));
                            $activityName = trim((string)($timetable->subject ?? ''));
                            if ($periodType === 'Activity' && $activityName === '') $activityName = 'Activity';

                            if ($periodType === 'Activity') {
                                $displaySubject = $activityName;
                            } elseif ($periodType === 'Break') {
                                $displaySubject = $timetable->subject ?: 'Break';
                            } elseif ($periodType === 'Lunch') {
                                $displaySubject = $timetable->subject ?: 'Lunch';
                            } else {
                                $displaySubject = $timetable->subject ?: '—';
                            }

                            $className = trim((string)($timetable->class ?? ''));
                            $sectionName = trim((string)($timetable->section ?? ''));

                            $searchText = strtolower(implode(' ', [
                                $teacherName,
                                $teacher->teacher_id ?? '',
                                $timetable->teacher_id ?? '',
                                $className,
                                $sectionName,
                                $displaySubject,
                                $timetable->subject_type ?? '',
                                $periodType,
                                $timetable->room ?? '',
                                $timetable->academic_year ?? '',
                                $timetable->day ?? '',
                                $searchDate,
                            ]));

                            $typeClass = match($periodType) {
                                'Regular' => 'badge-regular',
                                'Break' => 'badge-break',
                                'Lunch' => 'badge-lunch',
                                'Activity' => 'badge-activity',
                                default => 'badge-regular',
                            };
                        @endphp

                        <tr class="timetable-row"
                            data-search="{{ $searchText }}"
                            data-date="{{ $searchDate }}"
                            data-day="{{ $timetable->day ?? '' }}"
                            data-period-type="{{ $periodType }}"
                            data-academic-year="{{ $timetable->academic_year ?? '' }}"
                            data-class="{{ $className }}">

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="teacher-cell">
                                    <div class="teacher-avatar">{{ $initials }}</div>
                                    <div>
                                        <div class="teacher-name">{{ $teacherName }}</div>
                                        <div class="teacher-id">
                                            {{ $teacher->teacher_id ?? ($timetable->teacher_id ?? '—') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $timetable->academic_year ?: '—' }}</td>

                            <td>
                                @if($searchDate)
                                    <span class="badge badge-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ \Carbon\Carbon::parse($searchDate)->format('d M Y') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-day">{{ $timetable->day ?: '—' }}</span>
                            </td>

                            <td>
                                <span class="badge badge-period">
                                    Period {{ $timetable->period_number ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $typeClass }}">
                                    {{ $periodType ?: '—' }}
                                </span>
                            </td>

                            <td>{{ $className ?: '—' }}</td>
                            <td>{{ $sectionName ?: '—' }}</td>

                            <td>
                                <strong>{{ $displaySubject }}</strong>
                                @if($periodType === 'Regular' && $timetable->subject_type)
                                    <div style="font-size:10.5px;color:var(--muted);margin-top:2px">
                                        {{ $timetable->subject_type }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($timetable->start_time)
                                    {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                                @else
                                    —
                                @endif
                                –
                                @if($timetable->end_time)
                                    {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                @else
                                    —
                                @endif
                                @if($timetable->duration_minutes)
                                    <div style="font-size:10.5px;color:var(--muted);margin-top:2px">
                                        {{ $timetable->duration_minutes }} min
                                    </div>
                                @endif
                            </td>

                            <td>{{ $timetable->room ?: '—' }}</td>

                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.timetable.show', $timetable->id) }}"
                                       class="action-btn action-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.timetable.edit', $timetable->id) }}"
                                       class="action-btn action-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.timetable.destroy', $timetable->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this timetable entry?');"
                                          style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13">
                                <div class="empty">
                                    <i class="bi bi-calendar-x"></i>
                                    <h4>No Timetable Entries</h4>
                                    <p>Create your first timetable entry to start managing the school schedule.</p>
                                    <a href="{{ route('admin.timetable.create') }}" class="btn-solid">
                                        <i class="bi bi-plus-circle-fill"></i> Create Timetable
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noResultsRow" style="display:none">
                        <td colspan="13">
                            <div class="empty">
                                <i class="bi bi-search"></i>
                                <h4>No Matching Timetable</h4>
                                <p>Try changing your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const dayFilter = document.getElementById('dayFilter');
    const periodTypeFilter = document.getElementById('periodTypeFilter');
    const academicYearFilter = document.getElementById('academicYearFilter');
    const classFilter = document.getElementById('classFilter');
    const clearFilters = document.getElementById('clearFilters');
    const rows = document.querySelectorAll('.timetable-row');
    const visibleCount = document.getElementById('visibleCount');
    const noResultsRow = document.getElementById('noResultsRow');

    function filterTimetable() {
        const search = searchInput.value.toLowerCase().trim();
        const selectedDate = dateFilter.value;
        const selectedDay = dayFilter.value;
        const selectedPeriodType = periodTypeFilter.value;
        const selectedAcademicYear = academicYearFilter.value;
        const selectedClass = classFilter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const show =
                (!search || (row.dataset.search || '').includes(search)) &&
                (!selectedDate || row.dataset.date === selectedDate) &&
                (!selectedDay || row.dataset.day === selectedDay) &&
                (!selectedPeriodType || row.dataset.periodType === selectedPeriodType) &&
                (!selectedAcademicYear || row.dataset.academicYear === selectedAcademicYear) &&
                (!selectedClass || row.dataset.class === selectedClass);

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (visibleCount) visibleCount.textContent = visible;
        if (noResultsRow) {
            noResultsRow.style.display = (visible === 0 && rows.length > 0) ? '' : 'none';
        }
    }

    [searchInput, dateFilter, dayFilter, periodTypeFilter, academicYearFilter, classFilter]
        .forEach(el => el && el.addEventListener(el.tagName === 'INPUT' && el.type !== 'date' ? 'input' : 'change', filterTimetable));

    if (clearFilters) {
        clearFilters.addEventListener('click', function () {
            searchInput.value = '';
            dateFilter.value = '';
            dayFilter.value = '';
            periodTypeFilter.value = '';
            academicYearFilter.value = '';
            classFilter.value = '';
            filterTimetable();
        });
    }

    filterTimetable();
});
</script>

@endsection