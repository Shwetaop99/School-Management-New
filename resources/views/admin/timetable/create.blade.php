@extends('layouts.app')

@section('title', 'Create Weekly Timetable')
@section('page-title', 'Create Weekly Timetable')

@section('content')
@php
    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $periodNumbers = range(1, 8);
@endphp

<style>
    .tt-page {
        --tt-bg: #f7f6f0;
        --tt-paper: #fffefa;
        --tt-ink: #263b35;
        --tt-ink-dark: #1d302b;
        --tt-green: #456854;
        --tt-cream: #f1ead8;
        --tt-border: #e5e1d5;
        --tt-muted: #777d74;
        --tt-wood: #a87543;
        --tt-blue: #2b5d8a;
        --tt-red: #bd5148;
        --tt-shadow: 0 5px 18px rgba(38, 59, 53, .055);

        max-width: 1800px;
        margin: 0 auto;
        padding: 8px 0 26px;
        color: var(--tt-ink);
        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .tt-page *, .tt-page *::before, .tt-page *::after { box-sizing: border-box; }

    .tt-card {
        background: var(--tt-paper);
        border: 1px solid var(--tt-border);
        border-radius: 13px;
        box-shadow: var(--tt-shadow);
        margin-bottom: 18px;
        overflow: hidden;
    }

    .tt-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        padding: 27px 28px;
        color: #fffefa;
        background: linear-gradient(120deg, #344f43 0%, #263b35 58%, #20352c 100%);
    }
    .tt-hero::after {
        content: "";
        position: absolute;
        width: 220px; height: 220px;
        right: 10%; top: -145px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 25px rgba(255,255,255,.025), 0 0 0 50px rgba(255,255,255,.02);
        pointer-events: none;
    }
    .tt-hero-content, .tt-hero-actions { position: relative; z-index: 1; }
    .tt-eyebrow {
        display: inline-flex; align-items: center; gap: 7px;
        margin-bottom: 8px; color: #e5ddc8;
        font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.8px;
    }
    .tt-hero h1 {
        margin: 0; color: #fffefa;
        font-size: clamp(23px, 2.5vw, 31px); font-weight: 750; letter-spacing: -.8px;
    }
    .tt-hero p { margin: 8px 0 0; max-width: 600px; color: #e0e5dc; font-size: 13px; line-height: 1.7; }
    .tt-hero-actions { display: flex; flex-wrap: wrap; gap: 9px; }

    .tt-btn {
        display: inline-flex; justify-content: center; align-items: center; gap: 8px;
        min-height: 39px; padding: 9px 15px;
        border: 1px solid var(--tt-border); border-radius: 7px;
        background: var(--tt-paper); color: var(--tt-ink);
        font-size: 12px; font-weight: 700; text-decoration: none !important; cursor: pointer;
    }
    .tt-btn:hover { background: var(--tt-cream); color: var(--tt-ink-dark); }
    .tt-btn-primary { background: var(--tt-ink); color: #fffefa; border-color: var(--tt-ink); }
    .tt-btn-primary:hover { background: var(--tt-green); color: #fffefa; }
    .tt-btn-hero { background: var(--tt-cream); color: var(--tt-ink); border-color: var(--tt-cream); }
    .tt-btn:disabled { opacity: .65; cursor: wait; }

    .tt-section { padding: 23px 25px; border-bottom: 1px solid var(--tt-border); }
    .tt-section:last-child { border-bottom: 0; }
    .tt-section-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; margin-bottom: 19px; }
    .tt-section-title { display: flex; align-items: center; gap: 11px; margin: 0; color: var(--tt-ink); font-size: 16px; font-weight: 800; }
    .tt-section-number {
        display: grid; place-items: center; flex: 0 0 31px;
        width: 31px; height: 31px; border-radius: 8px;
        background: var(--tt-cream); color: var(--tt-ink); font-size: 12px; font-weight: 800;
    }
    .tt-section-subtitle { margin: 5px 0 0 42px; color: var(--tt-muted); font-size: 12px; line-height: 1.6; }

    .tt-meta { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 17px; }
    .tt-field label { display: block; margin-bottom: 7px; color: var(--tt-ink); font-size: 12px; font-weight: 750; }
    .tt-required { color: var(--tt-red); }
    .tt-control {
        width: 100%; min-width: 0; height: 37px; padding: 0 10px;
        border: 1px solid #dcd8cb; border-radius: 6px; outline: none;
        background: #fffefa; color: var(--tt-ink); font: inherit; font-size: 12px;
    }
    .tt-control:focus { border-color: var(--tt-green); box-shadow: 0 0 0 3px rgba(69, 104, 84, .11); }
    .tt-control:disabled { background: #efede5; color: #888b81; cursor: not-allowed; }
    .tt-help { margin-top: 6px; color: var(--tt-muted); font-size: 11px; }

    .tt-alert { padding: 14px 17px; margin-bottom: 17px; border: 1px solid #e6c8bf; border-radius: 8px; background: #fbefeb; color: #8d3932; font-size: 12px; }
    .tt-success { padding: 13px 17px; margin-bottom: 17px; border: 1px solid #cadbc9; border-radius: 8px; background: #eef5eb; color: #315c3e; font-size: 12px; }

    .tt-hint {
        display: flex; align-items: flex-start; gap: 11px;
        padding: 13px 15px; margin-bottom: 17px;
        border: 1px solid #e3ddcb; border-radius: 8px;
        background: #f6f1e4; color: #554e3d; font-size: 12px; line-height: 1.8;
    }
    .tt-hint-icon { color: var(--tt-wood); font-size: 17px; }

    .tt-legend { display: flex; flex-wrap: wrap; gap: 9px 16px; margin-bottom: 16px; }
    .tt-legend-item { display: inline-flex; align-items: center; gap: 6px; color: #656b61; font-size: 11px; font-weight: 650; }
    .tt-legend-dot { width: 9px; height: 9px; border-radius: 3px; }
    .tt-dot-class { background: var(--tt-green); }
    .tt-dot-break { background: var(--tt-wood); }
    .tt-dot-lunch { background: #c29b55; }
    .tt-dot-activity { background: var(--tt-blue); }

    .tt-grid-wrap {
        width: 100%; overflow: auto;
        border: 1px solid #dcd8cb; border-radius: 9px;
        background: #fffefa;
    }
    .tt-grid {
        width: 100%; min-width: 1600px;
        table-layout: fixed; border-collapse: separate; border-spacing: 0;
    }
    .tt-grid th {
        position: sticky; top: 0; z-index: 3;
        height: 53px; padding: 10px 7px;
        border-right: 1px solid #52685c; border-bottom: 1px solid #52685c;
        background: var(--tt-ink); color: #fffefa;
        text-align: center; font-size: 11px; font-weight: 800;
    }
    .tt-grid th:first-child { position: sticky; left: 0; z-index: 5; width: 112px; min-width: 112px; background: #20352c; }
    .tt-period-number { display: block; margin-top: 3px; color: #e4ddcb; font-size: 9px; }

    .tt-grid td { padding: 0; vertical-align: top; border-right: 1px solid #e4e0d5; border-bottom: 1px solid #e4e0d5; background: #fffefa; }
    .tt-grid tbody tr:nth-child(even) td:not(.tt-day) { background: #faf9f4; }

    .tt-grid .tt-day {
        position: sticky; left: 0; z-index: 2;
        width: 112px; min-width: 112px; padding: 13px 9px;
        background: #f0ede2; color: var(--tt-ink);
        text-align: center; vertical-align: middle;
    }
    .tt-day-name { display: block; font-size: 12px; font-weight: 800; }
    .tt-day-date { display: block; margin-top: 6px; color: #777d70; font-size: 10px; }
    .tt-day-count {
        display: inline-block; margin-top: 9px; padding: 3px 7px;
        border: 1px solid #ded8c8; border-radius: 4px;
        background: #fffefa; color: #656b61; font-size: 9px; font-weight: 750;
    }

    .tt-grid-cell { min-width: 180px; padding: 9px; }
    .tt-cell { display: flex; flex-direction: column; gap: 7px; }

    .tt-cell .tt-control { height: 32px; padding: 0 7px; border-color: #dedace; border-radius: 5px; font-size: 11px; }
    .tt-cell .period-type { height: 29px; background: #f0ede3; font-size: 10px; font-weight: 800; }

    .tt-subject-row { display: grid; grid-template-columns: minmax(0, 1fr) 29px; gap: 4px; }
    .tt-add-subject {
        display: grid; place-items: center;
        width: 29px; height: 32px;
        border: 1px solid #d6d0bf; border-radius: 5px;
        background: var(--tt-cream); color: var(--tt-ink); cursor: pointer;
    }

    .tt-time-label { margin: 1px 0 -3px; color: #797c71; font-size: 9px; font-weight: 750; text-transform: uppercase; }
    .tt-time-row { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
    .tt-time-row .tt-control { font-size: 10px; padding: 0 4px; }

    .tt-room-row { display: flex; align-items: center; gap: 5px; }
    .tt-room-row i { color: #858779; font-size: 12px; }

    .tt-cell.tt-special { padding: 7px; border-left: 3px solid var(--tt-wood); background: #f6f0e3; }
    .tt-cell.tt-special .period-type { background: #e9dfca; }
    .tt-cell.tt-activity { border-left: 3px solid var(--tt-blue); }
    .tt-cell.tt-activity .period-type { background: #eaf0f5; color: #2b5d8a; }

    .tt-special-icons {
        display: none;
        text-align: center;
        padding: 12px 6px;
        background: #f6f0e3;
        border-radius: 6px;
    }
    .tt-special-icons .icon-wrap { display: none; }
    .tt-special-icons i { font-size: 26px; }
    .tt-special-icons .icon-title { font-size: 13px; font-weight: 800; margin-top: 6px; color: #786448; }

    .tt-footer {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; padding: 17px 25px;
        border-top: 1px solid var(--tt-border); background: #faf9f4;
    }
    .tt-footer-note { display: flex; align-items: center; gap: 8px; color: var(--tt-muted); font-size: 11px; }
    .tt-footer-actions { display: flex; gap: 8px; }

    .tt-modal-backdrop {
        display: none; position: fixed; inset: 0; z-index: 99999;
        align-items: center; justify-content: center; padding: 20px;
        background: rgba(29, 48, 43, .55);
    }
    .tt-modal-backdrop.open { display: flex; }
    .tt-modal {
        width: min(420px, 100%); padding: 25px;
        border: 1px solid var(--tt-border); border-radius: 12px;
        background: var(--tt-paper);
    }
    .tt-modal h3 { margin: 0; font-size: 20px; font-weight: 800; }
    .tt-modal p { margin: 8px 0 18px; color: var(--tt-muted); font-size: 12px; }
    .tt-modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }

    @media (max-width: 1100px) {
        .tt-meta { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .tt-meta { grid-template-columns: 1fr; }
        .tt-hero-actions { width: 100%; }
        .tt-footer-actions { width: 100%; }
        .tt-footer-actions .tt-btn { flex: 1; }
    }
</style>

<div class="tt-page">

    <div class="tt-card tt-hero">
        <div class="tt-hero-content">
            <div class="tt-eyebrow">
                <i class="bi bi-calendar3-week"></i> Academic Planning
            </div>
            <h1>Create Weekly Timetable</h1>
            <p>Fill start & end time yourself. Break / Lunch show icons only.</p>
        </div>
        <div class="tt-hero-actions">
            <a href="{{ route('admin.timetable.index') }}" class="tt-btn tt-btn-hero">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="tt-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="tt-alert">
            <strong><i class="bi bi-exclamation-circle"></i> Please fix these errors:</strong>
            <ul style="margin:8px 0 0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.timetable.save') }}" method="POST" id="weeklyTimetableForm">
        @csrf

        <div class="tt-card">

            {{-- DETAILS --}}
            <div class="tt-section">
                <div class="tt-section-heading">
                    <div>
                        <h2 class="tt-section-title">
                            <span class="tt-section-number">01</span>
                            Timetable Details
                        </h2>
                        <p class="tt-section-subtitle">Choose class, section and week starting date.</p>
                    </div>
                </div>

                <div class="tt-meta">
                    <div class="tt-field">
                        <label>Academic Year <span class="tt-required">*</span></label>
                        <input class="tt-control" type="text" name="academic_year" id="academic_year"
                               value="{{ old('academic_year', '2026-2027') }}" required>
                    </div>

                    <div class="tt-field">
                        <label>Class <span class="tt-required">*</span></label>
                        <select class="tt-control" name="class" id="class" required>
                            <option value="">Select Class</option>
                            @foreach ($classes as $classItem)
                                @php $classValue = $classItem->class_name ?? $classItem->name ?? ''; @endphp
                                <option value="{{ $classValue }}" data-class-id="{{ $classItem->id }}"
                                        @selected(old('class') == $classValue)>
                                    {{ $classValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="tt-field">
                        <label>Section <span class="tt-required">*</span></label>
                        <select class="tt-control" name="section" id="section" required>
                            <option value="">Select Section</option>
                        </select>
                        <div class="tt-help" id="sectionHelp">Select a class first.</div>
                    </div>

                    <div class="tt-field">
                        <label>Week Starting (Monday) <span class="tt-required">*</span></label>
                        <input class="tt-control" type="date" name="week_start_date" id="week_start_date"
                               value="{{ old('week_start_date', now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')) }}" required>
                        <div class="tt-help" id="weekDateHelp">Select a Monday.</div>
                    </div>
                </div>
            </div>

            {{-- GRID --}}
            <div class="tt-section">
                <div class="tt-section-heading">
                    <div>
                        <h2 class="tt-section-title">
                            <span class="tt-section-number">02</span>
                            Weekly Schedule
                        </h2>
                        <p class="tt-section-subtitle">No fixed times. Fill times yourself.</p>
                    </div>
                </div>

                <div class="tt-hint">
                    <span class="tt-hint-icon"><i class="bi bi-info-circle"></i></span>
                    <div>
                        <strong>How to use</strong><br>
                        • Select <strong>Break</strong> or <strong>Lunch</strong> → time fields hide and icon appears<br>
                        • Class / Activity → you must fill Subject, Teacher and Start/End time<br>
                        • Make sure times do not overlap on the same day
                    </div>
                </div>

                <div class="tt-legend">
                    <span class="tt-legend-item"><span class="tt-legend-dot tt-dot-class"></span> Class</span>
                    <span class="tt-legend-item"><span class="tt-legend-dot tt-dot-break"></span> Break</span>
                    <span class="tt-legend-item"><span class="tt-legend-dot tt-dot-lunch"></span> Lunch</span>
                    <span class="tt-legend-item"><span class="tt-legend-dot tt-dot-activity"></span> Activity</span>
                </div>

                <div class="tt-grid-wrap">
                    <table class="tt-grid">
                        <thead>
                            <tr>
                                <th>DAY</th>
                                @foreach ($periodNumbers as $p)
                                    <th>PERIOD {{ $p }} <span class="tt-period-number">P{{ $p }}</span></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($weekDays as $dayIndex => $day)
                                <tr>
                                    <td class="tt-day">
                                        <span class="tt-day-name">{{ $day }}</span>
                                        <span class="tt-day-date day-date" data-day-index="{{ $dayIndex }}">--</span>
                                        <span class="tt-day-count">8 PERIODS</span>
                                    </td>

                                    @foreach ($periodNumbers as $periodNumber)
                                        @php
                                            $old = old("timetable.{$day}.{$periodNumber}", []);
                                            if (!is_array($old)) $old = [];
                                            $prefix = "timetable[{$day}][{$periodNumber}]";
                                            $type = $old['period_type'] ?? 'Regular';
                                        @endphp

                                        <td>
                                            <div class="tt-grid-cell">
                                                <div class="tt-cell {{ in_array($type, ['Break','Lunch']) ? 'tt-special' : '' }} {{ $type === 'Activity' ? 'tt-activity' : '' }}">

                                                    <select class="tt-control period-type" name="{{ $prefix }}[period_type]" required>
                                                        <option value="Regular"  @selected($type === 'Regular')>Class</option>
                                                        <option value="Break"    @selected($type === 'Break')>Break</option>
                                                        <option value="Lunch"    @selected($type === 'Lunch')>Lunch</option>
                                                        <option value="Activity" @selected($type === 'Activity')>Activity</option>
                                                    </select>

                                                    <div class="tt-subject-row">
                                                        <select class="tt-control period-subject" name="{{ $prefix }}[subject]">
                                                            <option value="">Select Subject</option>
                                                            @foreach (($subjects ?? []) as $sub)
                                                                <option value="{{ $sub->subject_name }}"
                                                                        data-subject-name="{{ $sub->subject_name }}"
                                                                        data-class-id="{{ $sub->class_id }}"
                                                                        @selected(($old['subject'] ?? '') === $sub->subject_name)>
                                                                    {{ $sub->subject_name }}
                                                                </option>
                                                            @endforeach
                                                            <option value="__add_subject__">+ Add subject</option>
                                                        </select>
                                                        <button type="button" class="tt-add-subject">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>
                                                    </div>

                                                    <input type="hidden" class="period-subject-type"
                                                           name="{{ $prefix }}[subject_type]"
                                                           value="{{ $type === 'Activity' ? 'Activity' : 'Theory' }}">

                                                    <select class="tt-control period-teacher" name="{{ $prefix }}[teacher_id]">
                                                        <option value="">Select Teacher</option>
                                                        @foreach ($teachers as $t)
                                                            @php
                                                                $name = trim(($t->first_name ?? '') . ' ' . ($t->last_name ?? ''));
                                                                $subs = $t->subject ?? [];
                                                                if (is_string($subs)) $subs = array_map('trim', explode(',', $subs));
                                                                if (!is_array($subs)) $subs = [];
                                                            @endphp
                                                            <option value="{{ $t->id }}"
                                                                    data-subjects="{{ implode('|', $subs) }}"
                                                                    @selected((string)($old['teacher_id'] ?? '') === (string)$t->id)>
                                                                {{ $name ?: ($t->name ?? 'Teacher') }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- TIME --}}
                                                    <div class="tt-time-label period-time-label">Start / End</div>
                                                    <div class="tt-time-row period-time-row">
                                                        <input class="tt-control period-start" type="time"
                                                               name="{{ $prefix }}[start_time]"
                                                               value="{{ $old['start_time'] ?? '' }}">
                                                        <input class="tt-control period-end" type="time"
                                                               name="{{ $prefix }}[end_time]"
                                                               value="{{ $old['end_time'] ?? '' }}">
                                                    </div>

                                                    {{-- ICONS --}}
                                                    <div class="tt-special-icons">
                                                        <div class="icon-wrap break-icon">
                                                            <i class="bi bi-cup-hot" style="color:#a87543;"></i>
                                                            <div class="icon-title">Break</div>
                                                        </div>
                                                        <div class="icon-wrap lunch-icon">
                                                            <i class="bi bi-egg-fried" style="color:#c29b55;"></i>
                                                            <div class="icon-title">Lunch</div>
                                                        </div>
                                                    </div>

                                                    <div class="tt-room-row period-room-row">
                                                        <i class="bi bi-geo-alt"></i>
                                                        <input class="tt-control period-room" type="text"
                                                               name="{{ $prefix }}[room]"
                                                               value="{{ $old['room'] ?? '' }}"
                                                               placeholder="Room (optional)">
                                                    </div>

                                                    <input type="hidden" name="{{ $prefix }}[day]" value="{{ $day }}">
                                                    <input type="hidden" name="{{ $prefix }}[period_number]" value="{{ $periodNumber }}">
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tt-footer">
                <div class="tt-footer-note">
                    <i class="bi bi-info-circle"></i>
                    Make sure no two periods overlap on the same day.
                </div>
                <div class="tt-footer-actions">
                    <a href="{{ route('admin.timetable.index') }}" class="tt-btn">Cancel</a>
                    <button type="submit" class="tt-btn tt-btn-primary" id="saveWeeklyBtn">
                        <i class="bi bi-check2-circle"></i> Save Weekly Timetable
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- MODAL --}}
<div class="tt-modal-backdrop" id="addSubjectModal">
    <div class="tt-modal">
        <h3>Add Subject</h3>
        <p>Only for this form (not saved permanently).</p>
        <input class="tt-control" type="text" id="newSubjectName" placeholder="Subject name" maxlength="100">
        <div class="tt-modal-actions">
            <button type="button" class="tt-btn" id="cancelAddSubject">Cancel</button>
            <button type="button" class="tt-btn tt-btn-primary" id="confirmAddSubject">Add Subject</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSelect   = document.getElementById('class');
    const sectionSelect = document.getElementById('section');
    const sectionHelp   = document.getElementById('sectionHelp');
    const weekStart     = document.getElementById('week_start_date');
    const weekDateHelp  = document.getElementById('weekDateHelp');
    const form          = document.getElementById('weeklyTimetableForm');
    const saveBtn       = document.getElementById('saveWeeklyBtn');
    const modal         = document.getElementById('addSubjectModal');
    const subjectInput  = document.getElementById('newSubjectName');

    const allClasses  = @json($classes);
    const allSections = @json($sections);
    const oldSection  = @json(old('section'));

    let activeSubjectSelect = null;

    function loadSections(className, selected = '') {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        if (!className) {
            sectionHelp.textContent = 'Select a class first.';
            return;
        }
        const cls = allClasses.find(c => String(c.class_name ?? c.name ?? '').trim() === String(className).trim());
        if (!cls) return;

        const sections = allSections.filter(s => String(s.class_id) === String(cls.id));
        sections.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.section_name;
            opt.textContent = s.section_name;
            if (String(s.section_name) === String(selected)) opt.selected = true;
            sectionSelect.appendChild(opt);
        });
        sectionHelp.textContent = sections.length ? sections.length + ' section(s) available.' : 'No sections found.';
    }

    function formatDate(d) {
        return new Intl.DateTimeFormat('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }).format(d);
    }

    function updateWeekDates() {
        if (!weekStart.value) return;
        const start = new Date(weekStart.value + 'T12:00:00');
        const isMon = start.getDay() === 1;
        weekDateHelp.textContent = isMon
            ? formatDate(start) + ' – ' + formatDate(new Date(start.getTime() + 5 * 86400000))
            : 'Please select a Monday.';
        weekDateHelp.style.color = isMon ? '#777d74' : '#bd5148';

        document.querySelectorAll('.day-date').forEach(el => {
            el.textContent = formatDate(new Date(start.getTime() + Number(el.dataset.dayIndex) * 86400000));
        });
    }

    function getSubject(select) {
        if (!select || select.selectedIndex < 0) return '';
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value || opt.value === '__add_subject__') return '';
        return (opt.dataset.subjectName || opt.textContent).trim();
    }

    function filterTeachers(cell) {
        const subSel = cell.querySelector('.period-subject');
        const teaSel = cell.querySelector('.period-teacher');
        if (!subSel || !teaSel) return;

        const subject = getSubject(subSel).toLowerCase();
        let matches = [];

        Array.from(teaSel.options).forEach((opt, i) => {
            if (i === 0) { opt.hidden = false; opt.disabled = false; return; }
            const subs = (opt.dataset.subjects || '').split('|').flatMap(v => v.split(',')).map(v => v.trim().toLowerCase()).filter(Boolean);
            const ok = !subject || subs.includes(subject);
            opt.hidden = !ok;
            opt.disabled = !ok;
            if (ok) matches.push(opt);
        });

        if (subject && matches.length === 1) {
            teaSel.value = matches[0].value;
        } else if (teaSel.value && !matches.some(m => m.value === teaSel.value)) {
            teaSel.value = '';
        }
    }

    function filterSubjectsByClass() {
        const opt = classSelect.options[classSelect.selectedIndex];
        const classId = opt ? (opt.dataset.classId || '') : '';

        document.querySelectorAll('.tt-cell').forEach(cell => {
            const subSel = cell.querySelector('.period-subject');
            Array.from(subSel.options).forEach(o => {
                if (!o.dataset.classId) return;
                const ok = !classId || String(o.dataset.classId) === String(classId);
                o.hidden = !ok;
                o.disabled = !ok;
                if (!ok && o.selected) {
                    subSel.value = '';
                    cell.querySelector('.period-teacher').value = '';
                }
            });
            filterTeachers(cell);
        });
    }

    function configureCell(cell) {
        const typeSelect    = cell.querySelector('.period-type');
        const subjectSelect = cell.querySelector('.period-subject');
        const teacherSelect = cell.querySelector('.period-teacher');
        const roomInput     = cell.querySelector('.period-room');
        const subjectType   = cell.querySelector('.period-subject-type');
        const timeLabel     = cell.querySelector('.period-time-label');
        const timeRow       = cell.querySelector('.period-time-row');
        const roomRow       = cell.querySelector('.period-room-row');
        const specialIcons  = cell.querySelector('.tt-special-icons');
        const breakIcon     = cell.querySelector('.break-icon');
        const lunchIcon     = cell.querySelector('.lunch-icon');
        const startInput    = cell.querySelector('.period-start');
        const endInput      = cell.querySelector('.period-end');

        const type = typeSelect.value;
        const isSpecial = ['Break', 'Lunch'].includes(type);

        cell.classList.toggle('tt-special', isSpecial);
        cell.classList.toggle('tt-activity', type === 'Activity');

        if (subjectType) {
            subjectType.value = type === 'Activity' ? 'Activity' : 'Theory';
        }

        // Required rules
        subjectSelect.required = !isSpecial;
        teacherSelect.required = !isSpecial;
        startInput.required    = !isSpecial;
        endInput.required      = !isSpecial;

        [subjectSelect, teacherSelect, roomInput].forEach(f => {
            f.disabled = isSpecial;
        });

        if (isSpecial) {
            timeLabel.style.display = 'none';
            timeRow.style.display   = 'none';
            roomRow.style.display   = 'none';
            specialIcons.style.display = 'block';

            breakIcon.style.display = (type === 'Break') ? 'block' : 'none';
            lunchIcon.style.display = (type === 'Lunch') ? 'block' : 'none';

            subjectSelect.value = '';
            teacherSelect.value = '';
            roomInput.value = '';
        } else {
            timeLabel.style.display = 'block';
            timeRow.style.display   = 'grid';
            roomRow.style.display   = 'flex';
            specialIcons.style.display = 'none';
            filterTeachers(cell);
        }
    }

    // Wire all cells
    document.querySelectorAll('.tt-cell').forEach(cell => {
        const typeSelect    = cell.querySelector('.period-type');
        const subjectSelect = cell.querySelector('.period-subject');
        const start         = cell.querySelector('.period-start');
        const end           = cell.querySelector('.period-end');
        const addBtn        = cell.querySelector('.tt-add-subject');

        typeSelect.addEventListener('change', () => configureCell(cell));

        subjectSelect.addEventListener('change', function () {
            if (this.value === '__add_subject__') {
                this.value = '';
                activeSubjectSelect = this;
                modal.classList.add('open');
                subjectInput.value = '';
                subjectInput.focus();
                return;
            }
            filterTeachers(cell);
        });

        addBtn.addEventListener('click', () => {
            activeSubjectSelect = subjectSelect;
            modal.classList.add('open');
            subjectInput.value = '';
            subjectInput.focus();
        });

        end.addEventListener('change', () => {
            end.setCustomValidity(
                start.value && end.value && end.value <= start.value
                    ? 'End time must be after start time'
                    : ''
            );
        });

        configureCell(cell);
    });

    // Modal
    document.getElementById('cancelAddSubject').onclick = () => {
        modal.classList.remove('open');
        activeSubjectSelect = null;
    };
    modal.onclick = e => { if (e.target === modal) modal.classList.remove('open'); };

    document.getElementById('confirmAddSubject').onclick = () => {
        const name = subjectInput.value.trim();
        if (!name || !activeSubjectSelect) {
            alert('Enter subject name');
            return;
        }

        const exists = Array.from(activeSubjectSelect.options).find(o =>
            o.value !== '__add_subject__' &&
            (o.dataset.subjectName || o.textContent).trim().toLowerCase() === name.toLowerCase()
        );

        if (exists) {
            activeSubjectSelect.value = exists.value;
        } else {
            const opt = document.createElement('option');
            opt.value = name;
            opt.textContent = name;
            opt.dataset.subjectName = name;
            activeSubjectSelect.insertBefore(opt, activeSubjectSelect.querySelector('option[value="__add_subject__"]'));
            activeSubjectSelect.value = name;
        }
        filterTeachers(activeSubjectSelect.closest('.tt-cell'));
        modal.classList.remove('open');
        activeSubjectSelect = null;
    };

    // Events
    classSelect.addEventListener('change', () => {
        loadSections(classSelect.value);
        filterSubjectsByClass();
    });
    weekStart.addEventListener('change', updateWeekDates);

    // Form validation (fixed)
    form.addEventListener('submit', function (e) {
        if (!weekStart.value || new Date(weekStart.value + 'T12:00:00').getDay() !== 1) {
            e.preventDefault();
            alert('Please select a Monday');
            weekStart.focus();
            return;
        }
        if (!classSelect.value || !sectionSelect.value) {
            e.preventDefault();
            alert('Please select Class and Section');
            return;
        }

        let bad = null;

        document.querySelectorAll('.tt-cell').forEach(cell => {
            if (bad) return;

            const type    = cell.querySelector('.period-type').value;
            const subject = cell.querySelector('.period-subject');
            const teacher = cell.querySelector('.period-teacher');
            const start   = cell.querySelector('.period-start');
            const end     = cell.querySelector('.period-end');

            // Skip validation for Break & Lunch
            if (['Break', 'Lunch'].includes(type)) {
                return;
            }

            // Class / Activity must have everything
            if (!start.value) {
                bad = start;
            } else if (!end.value || end.value <= start.value) {
                bad = end;
            } else if (!subject.value) {
                bad = subject;
            } else if (!teacher.value) {
                bad = teacher;
            }
        });

        if (bad) {
            e.preventDefault();
            alert('Please fill Subject, Teacher and valid Start/End times for all Class and Activity periods.\nBreak and Lunch do not need them.');
            bad.focus();
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
    });

    // Init
    if (classSelect.value) loadSections(classSelect.value, oldSection);
    filterSubjectsByClass();
    updateWeekDates();
});
</script>
@endsection