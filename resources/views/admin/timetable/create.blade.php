@extends('layouts.app')

@section('title', 'Create Weekly Timetable')
@section('page-title', 'Create Weekly Timetable')

@section('content')

@php
    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    // Build the option lists once; the rows are created by the script below.
    $subjectNames = collect($subjects ?? [])
        ->map(fn ($s) => is_string($s) ? $s : ($s->subject_name ?? $s->name ?? $s->subject ?? ''))
        ->filter()
        ->unique()
        ->values();

    $teacherList = collect($teachers)->map(function ($t) {
        $name = trim(($t->first_name ?? '') . ' ' . ($t->last_name ?? ''));
        $subs = $t->subject ?? $t->subjects ?? $t->subject_names ?? [];

        if (is_string($subs)) {
            $subs = array_map('trim', explode(',', $subs));
        }
        if (!is_array($subs)) {
            $subs = [];
        }

        return [
            'id'       => $t->id,
            'name'     => $name !== '' ? $name : ($t->name ?? 'Teacher'),
            'subjects' => implode('|', $subs),
        ];
    })->values();
@endphp

<style>
    .tt-page{
        --t-ink:var(--ink,#1d3a33);
        --t-ink2:var(--ink-2,#16302a);
        --t-line:var(--line,#d8d1bf);
        --t-muted:var(--muted,#6b7c75);
        --t-text:var(--text,#2c3e3a);
        --t-accent:var(--accent,#e4572e);
        --t-serif:var(--serif,Georgia,serif);
        max-width:1500px;margin:0 auto;padding-bottom:28px;color:var(--t-text);font-size:13.5px
    }
    .tt-page *,.tt-page *::before,.tt-page *::after{box-sizing:border-box}
    .tt-page a{text-decoration:none}
    .tt-page [hidden]{display:none!important}

    .tt-paper{background:#fff;border:1px solid var(--t-line);border-radius:10px}

    /* Hero */
    .tt-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding:22px 28px 22px 56px;margin-bottom:18px}
    .tt-hero::before{content:"";position:absolute;left:34px;top:0;bottom:0;width:2px;background:rgba(228,87,46,.35)}
    .tt-eyebrow{margin-bottom:6px;font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--t-accent)}
    .tt-hero h1{margin:0 0 4px;font-family:var(--t-serif);font-size:26px;font-weight:700;color:var(--t-ink)}
    .tt-hero p{margin:0;color:var(--t-muted)}

    /* Buttons */
    .tt-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:9px 15px;border:1px solid transparent;border-radius:8px;font-family:inherit;font-size:13px;font-weight:600;line-height:1.3;white-space:nowrap;cursor:pointer;transition:background .15s,border-color .15s,color .15s}
    .tt-btn-solid{background:var(--t-ink);border-color:var(--t-ink);color:#fff}
    .tt-btn-solid:hover{background:var(--t-ink2);border-color:var(--t-ink2);color:#fff}
    .tt-btn-line{background:#fff;border-color:#cfc8b6;color:var(--t-text)}
    .tt-btn-line:hover{border-color:var(--t-ink);color:var(--t-ink)}
    .tt-btn-sm{padding:6px 11px;font-size:12px}
    .tt-btn:disabled{opacity:.6;cursor:wait}

    /* Alerts */
    .tt-alert{margin-bottom:16px;padding:13px 16px;border-radius:10px;font-size:13px;line-height:1.6}
    .tt-alert.ok{border:1px solid #cfe5d3;background:#edf7ed;color:#2f6b45}
    .tt-alert.bad{border:1px solid #f0d5ce;background:#fff4ef;color:#a84840}
    .tt-alert ul{margin:6px 0 0;padding-left:20px}

    /* Card + steps */
    .tt-card{overflow:hidden}
    .tt-step{padding:22px 24px}
    .tt-step + .tt-step{border-top:1px dashed var(--t-line)}
    .tt-step-head{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .tt-step-no{display:flex;align-items:center;justify-content:center;flex:0 0 32px;width:32px;height:32px;border-radius:50%;background:var(--t-ink);color:#fff;font-family:var(--t-serif);font-size:14px;font-weight:700}
    .tt-step-head h2{margin:0;font-family:var(--t-serif);font-size:17px;font-weight:700;color:var(--t-ink)}
    .tt-step-head p{margin:2px 0 0;font-size:12.5px;color:var(--t-muted)}

    /* Form fields */
    .tt-fields{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px}
    .tt-field{display:flex;flex-direction:column;gap:6px;min-width:0}
    .tt-label{font-size:12px;font-weight:600;color:var(--t-ink)}
    .tt-label b{color:#dc2626;font-weight:600}
    .tt-control{width:100%;min-width:0;height:38px;padding:0 10px;border:1px solid #cfc8b6;border-radius:8px;background:#fff;color:#1e293b;font-family:inherit;font-size:13px;outline:0}
    .tt-control:focus{border-color:var(--t-ink);box-shadow:0 0 0 3px rgba(29,58,51,.1)}
    .tt-control:disabled{background:#f1eee6;color:#a0a79f;font-style:italic;cursor:not-allowed}
    .tt-control[readonly]{background:#f8f6ef;color:#55635d}
    .tt-help{font-size:11.5px;line-height:1.45;color:var(--t-muted)}
    .tt-error{font-size:11.5px;color:#dc2626}

    /* Tools row */
    .tt-tools{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-top:20px;padding-top:16px;border-top:1px dashed var(--t-line)}
    .tt-tools span{margin-right:4px;font-size:12.5px;color:var(--t-muted)}

    /* Day layout builder */
    .tt-setup{margin-bottom:16px;border:1px dashed #cfc8b6;border-radius:10px;background:#fffdf6}
    .tt-setup summary{display:flex;align-items:center;gap:8px;padding:12px 16px;font-size:13px;font-weight:600;color:var(--t-ink);cursor:pointer;list-style:none}
    .tt-setup summary::-webkit-details-marker{display:none}
    .tt-setup summary small{font-weight:400;color:var(--t-muted)}
    .tt-setup summary::after{content:"\F282";margin-left:auto;font-family:"bootstrap-icons";font-size:12px;color:var(--t-muted);transition:transform .15s}
    .tt-setup[open] summary::after{transform:rotate(180deg)}
    .tt-setup-body{padding:4px 16px 16px;border-top:1px dashed #e8d9b8}
    .tt-setup-grid{display:grid;grid-template-columns:repeat(3,minmax(0,200px));gap:15px;margin-top:14px}
    .tt-break-head,.tt-break-row{display:grid;grid-template-columns:150px 140px 110px 38px;gap:10px;align-items:center}
    .tt-break-head{margin-bottom:6px;font-size:10.5px;font-weight:800;letter-spacing:.6px;text-transform:uppercase;color:#68766d}
    .tt-break-row{margin-bottom:8px}
    .tt-break-row .brk-remove{width:38px;height:38px;padding:0}
    .tt-days{display:flex;flex-wrap:wrap;gap:8px}
    .tt-check{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border:1px solid #cfc8b6;border-radius:20px;background:#fff;font-size:12.5px;cursor:pointer;user-select:none}
    .tt-check input{accent-color:var(--t-ink)}
    .tt-setup-preview{margin-top:14px;padding:9px 12px;border:1px solid #cfe5d3;border-radius:8px;background:#f0f7f1;color:#2f6b45;font-size:12.5px;line-height:1.5}
    .tt-setup-preview.bad{border-color:#f0d5ce;background:#fff4ef;color:#a84840}
    .tt-setup-preview.info{border-color:var(--t-line);background:#faf8f3;color:var(--t-muted)}
    .tt-timeline{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px}
    .tt-timeline:empty{display:none}
    .tt-chip{padding:4px 9px;border:1px solid var(--t-line);border-radius:20px;background:#fff;font-size:11.5px;color:var(--t-text);white-space:nowrap}
    .tt-chip.special{border-color:#fed7aa;background:#fff7ed;color:#9a3412}
    .tt-setup-actions{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-top:12px}

    /* Day tabs */
    .tt-tabs{display:flex;flex-wrap:wrap;gap:6px;border-bottom:1px solid var(--t-line)}
    .tt-tab{display:flex;flex-direction:column;align-items:center;min-width:112px;padding:9px 16px;border:1px solid transparent;border-bottom:0;border-radius:8px 8px 0 0;background:transparent;color:var(--t-muted);font-family:inherit;cursor:pointer;margin-bottom:-1px}
    .tt-tab strong{font-family:var(--t-serif);font-size:13px;color:inherit}
    .tt-tab small{margin-top:2px;font-size:10.5px}
    .tt-tab:hover{color:var(--t-ink);background:#faf8f3}
    .tt-tab.active{border-color:var(--t-line);background:#fff;color:var(--t-ink);box-shadow:inset 0 3px 0 var(--t-accent)}

    /* Day panel + period rows */
    .tt-day-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:16px 0 12px}
    .tt-day-head h3{margin:0;font-family:var(--t-serif);font-size:16px;color:var(--t-ink)}
    .tt-day-head h3 span{margin-left:8px;font-family:inherit;font-size:12px;font-weight:400;color:var(--t-muted)}
    .tt-rows{border:1px solid var(--t-line);border-radius:10px;overflow:hidden}
    .tt-row{display:grid;grid-template-columns:78px 112px minmax(0,1.2fr) minmax(0,1.2fr) 74px 96px 96px minmax(0,.9fr) 150px;gap:10px;align-items:center;padding:10px 14px;border-bottom:1px solid #efe9db}
    .tt-body .tt-row:last-child{border-bottom:0}
    .tt-row-head{padding:10px 14px;background:#f4f1ea;font-size:10.5px;font-weight:800;letter-spacing:.6px;text-transform:uppercase;color:#68766d}
    .tt-slot.tt-special{background:#fff7ed}
    .tt-period{display:inline-flex;align-items:center;justify-content:center;min-width:40px;height:30px;padding:0 9px;border-radius:8px;background:#f5f4ec;border:1px solid var(--t-line);font-size:12px;font-weight:700;color:var(--t-ink)}
    .tt-special .tt-period{background:#ffedd5;border-color:#fed7aa;color:#9a3412}
    .tt-col::before{display:none}
    .tt-actions{display:flex;justify-content:flex-end;gap:4px}
    .tt-icon{display:inline-flex;align-items:center;justify-content:center;width:26px;height:30px;padding:0;border:1px solid #e2dcc9;border-radius:6px;background:#fff;color:#657369;font-size:12px;cursor:pointer;transition:background .15s,border-color .15s,color .15s}
    .tt-icon:hover:not(:disabled){border-color:var(--t-ink);color:var(--t-ink);background:#f4f1ea}
    .tt-icon.js-remove:hover:not(:disabled){border-color:#e0b8ae;background:#fdecea;color:#a84840}
    .tt-icon:disabled{opacity:.3;cursor:not-allowed}
    .tt-hint{margin:12px 0 0;font-size:12px;color:var(--t-muted)}

    /* Save bar */
    .tt-footer{position:sticky;bottom:0;z-index:5;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 24px;border-top:1px solid var(--t-line);background:rgba(255,255,255,.97);box-shadow:0 -5px 15px rgba(29,58,51,.04)}
    .tt-footer p{margin:0;font-size:12px;color:var(--t-muted)}
    .tt-footer-actions{display:flex;gap:9px}

    /* Responsive */
    @media (max-width:1200px){
        .tt-fields{grid-template-columns:repeat(2,minmax(0,1fr))}
        .tt-row{grid-template-columns:repeat(12,minmax(0,1fr))}
        .tt-row .tt-period-cell{grid-column:span 2}
        .tt-row .tt-col-type{grid-column:span 3}
        .tt-row .tt-col-subject{grid-column:span 4}
        .tt-row .tt-col-teacher{grid-column:span 3}
        .tt-row .tt-col-min{grid-column:span 2}
        .tt-row .tt-col-start{grid-column:span 2}
        .tt-row .tt-col-end{grid-column:span 2}
        .tt-row .tt-col-room{grid-column:span 3}
        .tt-row .tt-actions{grid-column:span 3}
        .tt-row-head{display:none}
    }
    @media (max-width:760px){
        .tt-hero{padding:18px 16px 18px 40px}
        .tt-hero::before{left:22px}
        .tt-hero h1{font-size:22px}
        .tt-step{padding:18px 16px}
        .tt-fields{grid-template-columns:1fr}
        .tt-tabs{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;border-bottom:0}
        .tt-tab{min-width:0;padding:8px 4px;border:1px solid var(--t-line);border-radius:8px;margin-bottom:0}
        .tt-tab.active{box-shadow:none;background:var(--t-ink);border-color:var(--t-ink);color:#fff}
        .tt-row{grid-template-columns:repeat(6,minmax(0,1fr));padding:12px}
        .tt-row .tt-period-cell,.tt-row .tt-col-type,.tt-row .tt-col-subject,.tt-row .tt-col-teacher,
        .tt-row .tt-col-room,.tt-row .tt-actions{grid-column:1 / -1}
        .tt-row .tt-col-min,.tt-row .tt-col-start,.tt-row .tt-col-end{grid-column:span 2}
        .tt-icon{flex:1;height:36px}
        .tt-col::before{display:block;margin-bottom:4px;content:attr(data-label);font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:#68766d}
        .tt-tools .tt-btn{flex:1 1 calc(50% - 10px)}
        .tt-tools span{flex-basis:100%}
        .tt-footer{position:static;padding:14px 16px;flex-direction:column;align-items:stretch}
        .tt-footer-actions .tt-btn{flex:1}
        .tt-setup-grid{grid-template-columns:1fr 1fr}
        .tt-break-head{display:none}
        .tt-break-row{grid-template-columns:1fr 1fr 1fr 38px;gap:8px}
        .tt-day-head .tt-btn{flex:1}
    }
    @media (prefers-reduced-motion:reduce){.tt-page *{transition:none!important}}
</style>

<div class="tt-page">

    {{-- Header --}}
    <header class="tt-paper tt-hero">
        <div>
            <div class="tt-eyebrow">Academic Management</div>
            <h1>Create Weekly Timetable</h1>
            <p>Build each day your way: choose the lectures, breaks and timings, then fill in subjects and teachers.</p>
        </div>

        <a href="{{ route('admin.timetable.index') }}" class="tt-btn tt-btn-line">
            <i class="bi bi-arrow-left"></i> Back to Timetable
        </a>
    </header>

    @if (session('success'))
        <div class="tt-alert ok" role="status">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="tt-alert bad" role="alert">
            <strong><i class="bi bi-exclamation-triangle"></i> Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.timetable.save') }}" method="POST" id="weeklyTimetableForm" novalidate>
        @csrf

        <div class="tt-paper tt-card">

            {{-- STEP 1: choose class and week --}}
            <section class="tt-step">
                <div class="tt-step-head">
                    <span class="tt-step-no">1</span>
                    <div>
                        <h2>Choose class and week</h2>
                        <p>Select the academic year, class, section and the Monday the week starts.</p>
                    </div>
                </div>

                <div class="tt-fields">
                    <div class="tt-field">
                        <label class="tt-label" for="academic_year">Academic year <b>*</b></label>
                        <input class="tt-control" type="text" id="academic_year" name="academic_year"
                               value="{{ old('academic_year', '2026-2027') }}" placeholder="2026-2027">
                        @error('academic_year') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="class">Class <b>*</b></label>
                        <select class="tt-control" id="class" name="class">
                            <option value="">Select class</option>
                            @foreach ($classes as $classItem)
                                @php $classValue = $classItem->class_name ?? $classItem->name ?? ''; @endphp
                                <option value="{{ $classValue }}" @selected(old('class') == $classValue)>{{ $classValue }}</option>
                            @endforeach
                        </select>
                        @error('class') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="section">Section <b>*</b></label>
                        <select class="tt-control" id="section" name="section">
                            <option value="">Select section</option>
                        </select>
                        <div id="sectionHelp" class="tt-help">Select a class to load its sections.</div>
                        @error('section') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="week_start_date">Week starting (Monday) <b>*</b></label>
                        <input class="tt-control" type="date" id="week_start_date" name="week_start_date"
                               value="{{ old('week_start_date', now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')) }}">
                        <div id="weekDateHelp" class="tt-help">Choose a Monday.</div>
                        @error('week_start_date') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="tt-tools">
                    <span>Already have a timetable?</span>

                    <button type="button" class="tt-btn tt-btn-line tt-btn-sm" id="fetchBtn">
                        <i class="bi bi-arrow-repeat"></i> Load saved
                    </button>
                    <button type="button" class="tt-btn tt-btn-line tt-btn-sm" id="importBtn">
                        <i class="bi bi-upload"></i> Import Excel
                    </button>
                    <button type="button" class="tt-btn tt-btn-line tt-btn-sm" id="excelBtn">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>
                    <button type="button" class="tt-btn tt-btn-line tt-btn-sm" id="pdfBtn">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </button>
                </div>
            </section>

            {{-- STEP 2: layout, timings and periods --}}
            <section class="tt-step">
                <div class="tt-step-head">
                    <span class="tt-step-no">2</span>
                    <div>
                        <h2>Build the days</h2>
                        <p>Start with a quick layout for the days you choose, then adjust any day exactly how you want it.</p>
                    </div>
                </div>

                {{-- Quick layout generator --}}
                <details class="tt-setup" open>
                    <summary>
                        <i class="bi bi-sliders"></i> Quick layout
                        <small>(number of lectures, first lecture timing and breaks)</small>
                    </summary>

                    <div class="tt-setup-body">
                        <div class="tt-setup-grid">
                            <div class="tt-field">
                                <label class="tt-label" for="setupCount">Number of lectures</label>
                                <input class="tt-control" type="number" id="setupCount" min="1" max="12" value="8">
                            </div>

                            <div class="tt-field">
                                <label class="tt-label" for="setupStart">First lecture starts</label>
                                <input class="tt-control" type="time" id="setupStart" value="11:00">
                            </div>

                            <div class="tt-field">
                                <label class="tt-label" for="setupEnd">First lecture ends</label>
                                <input class="tt-control" type="time" id="setupEnd">
                            </div>
                        </div>

                        <p class="tt-help" style="margin:8px 0 0">
                            The length of the first lecture is used for every other lecture.
                            For example 11:00 to 11:30 makes every lecture 30 minutes. You can change any block's minutes afterwards.
                        </p>

                        <div class="tt-label" style="margin:18px 0 8px">Breaks</div>

                        <div class="tt-break-head">
                            <span>Placed</span><span>Type</span><span>Minutes</span><span></span>
                        </div>

                        <div id="breakList"></div>

                        <div class="tt-label" style="margin:14px 0 8px">Apply to</div>
                        <div class="tt-days">
                            @foreach ($weekDays as $day)
                                <label class="tt-check">
                                    <input type="checkbox" class="apply-day" value="{{ $day }}" checked>
                                    {{ substr($day, 0, 3) }}
                                </label>
                            @endforeach
                        </div>

                        <div id="setupPreview" class="tt-setup-preview" role="status"></div>
                        <div id="setupTimeline" class="tt-timeline"></div>

                        <div class="tt-setup-actions">
                            <button type="button" class="tt-btn tt-btn-line tt-btn-sm" id="addBreakBtn">
                                <i class="bi bi-plus-lg"></i> Add break
                            </button>
                            <button type="button" class="tt-btn tt-btn-solid tt-btn-sm" id="applySetupBtn">
                                <i class="bi bi-magic"></i> Apply to selected days
                            </button>
                            <span id="setupMsg" class="tt-help"></span>
                        </div>

                        <p class="tt-hint">
                            Applying replaces the layout and times of the selected days; subjects and teachers on lectures are kept.
                        </p>
                    </div>
                </details>

                <div class="tt-tabs" role="tablist" aria-label="Days of the week">
                    @foreach ($weekDays as $dayIndex => $day)
                        <button type="button" role="tab"
                                class="tt-tab {{ $loop->first ? 'active' : '' }}"
                                data-day="{{ $day }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <strong>{{ substr($day, 0, 3) }}</strong>
                            <small class="day-date" data-day-index="{{ $dayIndex }}">--</small>
                        </button>
                    @endforeach
                </div>

                @foreach ($weekDays as $dayIndex => $day)
                    <div class="tt-day-panel" role="tabpanel" data-day="{{ $day }}" @if(!$loop->first) hidden @endif>

                        <div class="tt-day-head">
                            <h3>{{ $day }} <span class="day-date" data-day-index="{{ $dayIndex }}"></span></h3>

                            <button type="button" class="tt-btn tt-btn-line tt-btn-sm js-copy-day">
                                <i class="bi bi-files"></i> Copy to all days
                            </button>
                        </div>

                        <div class="tt-rows">
                            <div class="tt-row tt-row-head">
                                <div>Period</div><div>Type</div><div>Subject</div><div>Teacher</div>
                                <div>Min</div><div>Start</div><div>End</div><div>Room</div><div></div>
                            </div>

                            <div class="tt-body"></div>
                        </div>
                    </div>
                @endforeach

                <p class="tt-hint">
                    <i class="bi bi-info-circle"></i>
                    Set the first period's start time and each block's minutes; the other times follow automatically.
                    Use the buttons on a row to move it, add a lecture or break below it, or remove it.
                    Choosing <strong>Break</strong> or <strong>Lunch</strong> blocks the subject, teacher and room.
                </p>
            </section>

            {{-- Save bar --}}
            <footer class="tt-footer">
                <p>All six days are saved together. Loading or importing does not save until you click Save.</p>

                <div class="tt-footer-actions">
                    <a href="{{ route('admin.timetable.index') }}" class="tt-btn tt-btn-line">Cancel</a>
                    <button type="submit" class="tt-btn tt-btn-solid" id="saveBtn">
                        <i class="bi bi-check-circle"></i> Save Weekly Timetable
                    </button>
                </div>
            </footer>
        </div>
    </form>

    {{-- Import form (kept outside the main form: forms cannot be nested) --}}
    <form id="weeklyImportForm" action="{{ route('admin.timetable.weekly.import') }}"
          method="POST" enctype="multipart/form-data" hidden>
        @csrf
        <input type="hidden" name="academic_year" id="import_academic_year">
        <input type="hidden" name="class" id="import_class">
        <input type="hidden" name="section" id="import_section">
        <input type="hidden" name="week_start_date" id="import_week_start_date">
        <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $ = (id) => document.getElementById(id);

    const classSelect   = $('class');
    const sectionSelect = $('section');
    const sectionHelp   = $('sectionHelp');
    const weekStart     = $('week_start_date');
    const weekHelp      = $('weekDateHelp');
    const form          = $('weeklyTimetableForm');
    const saveBtn       = $('saveBtn');

    const allClasses   = @json($classes);
    const allSections  = @json($sections);
    const oldSection   = @json(old('section'));
    const oldTimetable = @json(old('timetable', []));
    const subjectNames = @json($subjectNames);
    const teacherList  = @json($teacherList);

    const urls = {
        fetch: @json(route('admin.timetable.weekly.fetch')),
        excel: @json(route('admin.timetable.weekly.excel')),
        pdf:   @json(route('admin.timetable.weekly.pdf')),
    };

    const DEFAULT_LECTURES = 8;
    const SPECIAL = ['Break', 'Lunch'];
    const isSpecial = (type) => SPECIAL.includes(type);

    // Lengths in minutes. Class stays 0 (unknown) until the admin enters the first lecture's
    // start and end time, so nothing is guessed. Break and Lunch follow the break rows below.
    const durations = { Break: 10, Lunch: 30, Class: 0 };

    const tabs   = Array.from(document.querySelectorAll('.tt-tab'));
    const panels = Array.from(document.querySelectorAll('.tt-day-panel'));
    const allSlots = () => Array.from(document.querySelectorAll('.tt-slot'));

    const esc = (value) => String(value == null ? '' : value)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    const toTime = (total) =>
        String(Math.floor(total / 60)).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');

    const toMinutes = (value) => {
        const [hh, mm] = value.split(':').map(Number);
        return hh * 60 + mm;
    };

    const to12 = (total) => {
        const h = Math.floor(total / 60), m = total % 60;
        return (h % 12 || 12) + ':' + String(m).padStart(2, '0') + ' ' + (h >= 12 ? 'PM' : 'AM');
    };

    const subjectOptions = subjectNames.map(function (name) {
        return '<option value="' + esc(name) + '">' + esc(name) + '</option>';
    }).join('');

    const teacherOptions = teacherList.map(function (t) {
        return '<option value="' + esc(t.id) + '" data-subjects="' + esc(t.subjects) + '">' + esc(t.name) + '</option>';
    }).join('');

    /* ---------- Filters ---------- */
    function getFilters() {
        return {
            academic_year: $('academic_year').value.trim(),
            class: classSelect.value,
            section: sectionSelect.value,
            week_start_date: weekStart.value,
        };
    }

    function validFilters(filters) {
        if (!filters.academic_year || !filters.class || !filters.section || !filters.week_start_date) {
            alert('Please select the academic year, class, section and week start date.');
            return false;
        }
        if (new Date(filters.week_start_date + 'T12:00:00').getDay() !== 1) {
            alert('Please choose a Monday as the week start date.');
            weekStart.focus();
            return false;
        }
        return true;
    }

    function loadSections(className, selected = '') {
        sectionSelect.innerHTML = '<option value="">Select section</option>';

        if (!className) {
            sectionHelp.textContent = 'Select a class to load its sections.';
            return;
        }

        const cls = allClasses.find(c => String(c.class_name ?? c.name ?? '').trim() === String(className).trim());
        if (!cls) {
            sectionHelp.textContent = 'Could not find the selected class record.';
            return;
        }

        const sections = allSections.filter(s => String(s.class_id) === String(cls.id));
        sections.forEach(function (s) {
            const option = new Option(s.section_name, s.section_name);
            option.selected = String(s.section_name) === String(selected);
            sectionSelect.appendChild(option);
        });

        sectionHelp.textContent = sections.length
            ? 'Choose the section for this class.'
            : 'No sections are available for this class.';
    }

    function formatDate(date) {
        return new Intl.DateTimeFormat('en-IN', { day: '2-digit', month: 'short' }).format(date);
    }

    function updateWeekDates() {
        if (!weekStart.value) return;

        const start    = new Date(weekStart.value + 'T12:00:00');
        const isMonday = start.getDay() === 1;

        weekHelp.textContent = isMonday
            ? 'Monday to Saturday: ' + formatDate(start) + ' – ' + formatDate(new Date(start.getTime() + 5 * 86400000))
            : 'Please choose a Monday.';
        weekHelp.style.color = isMonday ? '' : '#dc2626';

        document.querySelectorAll('.day-date').forEach(function (el) {
            el.textContent = formatDate(new Date(start.getTime() + Number(el.dataset.dayIndex) * 86400000));
        });
    }

    /* ---------- Day tabs ---------- */
    function showDay(day) {
        tabs.forEach(function (tab) {
            const on = tab.dataset.day === day;
            tab.classList.toggle('active', on);
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach(function (panel) { panel.hidden = panel.dataset.day !== day; });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () { showDay(tab.dataset.day); });
    });

    /* ---------- Period rows ---------- */
    const ariaNames = {
        period_type: 'type', subject: 'subject', teacher_id: 'teacher',
        start_time: 'start time', end_time: 'end time', room: 'room',
    };

    function fields(slot) {
        const q = (sel) => slot.querySelector(sel);
        return {
            type: q('.period-type'),
            subject: q('.period-subject'),
            teacher: q('.period-teacher'),
            subjectType: q('.period-subject-type'),
            lectureType: q('.period-lecture-type'),
            minutes: q('.period-minutes'),
            start: q('.period-start'),
            end: q('.period-end'),
            room: q('.period-room'),
        };
    }

    function rowHtml() {
        return '' +
            '<div class="tt-row tt-slot">' +
                '<div class="tt-period-cell"><span class="tt-period"></span></div>' +
                '<div class="tt-col tt-col-type" data-label="Type"><select class="tt-control period-type" data-f="period_type">' +
                    '<option value="Regular">Class</option><option value="Activity">Activity</option>' +
                    '<option value="Break">Break</option><option value="Lunch">Lunch</option></select></div>' +
                '<div class="tt-col tt-col-subject" data-label="Subject"><select class="tt-control period-subject" data-f="subject"><option value="">Select subject</option>' + subjectOptions + '</select></div>' +
                '<div class="tt-col tt-col-teacher" data-label="Teacher"><select class="tt-control period-teacher" data-f="teacher_id"><option value="">Select teacher</option>' + teacherOptions + '</select></div>' +
                '<div class="tt-col tt-col-min" data-label="Minutes"><input class="tt-control period-minutes" type="number" min="5" max="240" step="1" placeholder="min" aria-label="Minutes"></div>' +
                '<div class="tt-col tt-col-start" data-label="Start"><input class="tt-control period-start" type="time" data-f="start_time"></div>' +
                '<div class="tt-col tt-col-end" data-label="End"><input class="tt-control period-end" type="time" data-f="end_time" readonly tabindex="-1"></div>' +
                '<div class="tt-col tt-col-room" data-label="Room"><input class="tt-control period-room" type="text" maxlength="100" placeholder="Optional" data-f="room"></div>' +
                '<div class="tt-actions">' +
                    '<button type="button" class="tt-icon js-up" title="Move up" aria-label="Move up"><i class="bi bi-arrow-up"></i></button>' +
                    '<button type="button" class="tt-icon js-down" title="Move down" aria-label="Move down"><i class="bi bi-arrow-down"></i></button>' +
                    '<button type="button" class="tt-icon js-add-lecture" title="Add a lecture below" aria-label="Add a lecture below"><i class="bi bi-plus-circle"></i></button>' +
                    '<button type="button" class="tt-icon js-add-break" title="Add a break below" aria-label="Add a break below"><i class="bi bi-pause-circle"></i></button>' +
                    '<button type="button" class="tt-icon js-remove" title="Remove this row" aria-label="Remove this row"><i class="bi bi-trash"></i></button>' +
                '</div>' +
                '<input type="hidden" class="period-subject-type" data-f="subject_type">' +
                '<input type="hidden" class="period-lecture-type" data-f="lecture_type">' +
                '<input type="hidden" data-f="day">' +
                '<input type="hidden" data-f="period_number">' +
            '</div>';
    }

    // Field names and labels depend on the row's position, so they are rewritten whenever the
    // layout changes. period_number is the row's position in the day (breaks included).
    function renumber(panel) {
        const day  = panel.dataset.day;
        const rows = Array.from(panel.querySelectorAll('.tt-slot'));
        let lectureNo = 0;

        rows.forEach(function (slot, index) {
            const position = index + 1;
            const type     = fields(slot).type.value;
            const special  = isSpecial(type);

            if (!special) lectureNo++;

            const label = special ? type : 'Period ' + lectureNo;
            slot.dataset.label = label;
            slot.querySelector('.tt-period').textContent = special ? type : 'P' + lectureNo;

            slot.querySelectorAll('[data-f]').forEach(function (el) {
                const name = el.dataset.f;
                el.name = 'timetable[' + day + '][' + position + '][' + name + ']';

                if (name === 'day') el.value = day;
                if (name === 'period_number') el.value = position;
                if (ariaNames[name]) el.setAttribute('aria-label', day + ' ' + label + ' ' + ariaNames[name]);
            });

            // Only the first row's start time is typed; every other time follows from it.
            fields(slot).start.readOnly = index > 0;
            slot.querySelector('.js-up').disabled     = index === 0;
            slot.querySelector('.js-down').disabled   = index === rows.length - 1;
            slot.querySelector('.js-remove').disabled = rows.length === 1;
        });
    }

    function filterTeachers(slot) {
        const f = fields(slot);
        const chosen = f.subject.value.trim().toLowerCase();

        Array.from(f.teacher.options).forEach(function (option, index) {
            if (index === 0) return;
            const mapped = (option.dataset.subjects || '').split('|')
                .map(v => v.trim().toLowerCase()).filter(Boolean);
            option.hidden = option.disabled = Boolean(chosen && mapped.length && !mapped.includes(chosen));
        });

        const selected = f.teacher.options[f.teacher.selectedIndex];
        if (f.teacher.value && selected && selected.disabled) f.teacher.value = '';
    }

    // Break and Lunch need no subject, teacher or room: block them and say so.
    function configureSlot(slot) {
        const f        = fields(slot);
        const special  = isSpecial(f.type.value);
        const activity = f.type.value === 'Activity';

        slot.classList.toggle('tt-special', special);
        slot.dataset.special = special ? '1' : '0';

        [f.subject, f.teacher, f.room].forEach(function (el) { el.disabled = special; });
        f.subject.options[0].textContent = special ? 'Not needed' : 'Select subject';
        f.teacher.options[0].textContent = special ? 'Not needed' : 'Select teacher';
        f.room.placeholder = special ? 'Not needed' : 'Optional';

        if (special) {
            f.subject.value = '';
            f.teacher.value = '';
            f.room.value = '';
            f.subjectType.value = 'Activity';
            f.lectureType.value = f.type.value.toLowerCase();
        } else {
            f.subjectType.value = activity ? 'Activity' : 'Theory';
            f.lectureType.value = activity ? 'activity' : 'regular';
            filterTeachers(slot);
        }
    }

    function setSubject(select, value) {
        value = value == null ? '' : String(value);
        if (value && !Array.from(select.options).some(o => o.value === value)) {
            select.add(new Option(value, value));
        }
        select.value = value;
    }

    // Minutes between a row's start and end time (0 if either is missing).
    function minutesBetween(slot) {
        const f = fields(slot);
        return f.start.value && f.end.value && f.end.value > f.start.value
            ? toMinutes(f.end.value) - toMinutes(f.start.value)
            : 0;
    }

    // One place that fills a row. Used by "Load saved", "Copy to all days", the layout generator and form restore.
    function writeSlot(slot, data) {
        const f = fields(slot);

        f.type.value = data.period_type || '';
        if (!f.type.value) f.type.selectedIndex = 0;
        configureSlot(slot);

        const special = isSpecial(f.type.value);

        if (!special) {
            setSubject(f.subject, data.subject);
            f.teacher.value = data.teacher_id == null ? '' : String(data.teacher_id);
            if (data.subject_type) f.subjectType.value = data.subject_type;
            if (data.lecture_type) f.lectureType.value = data.lecture_type;
            filterTeachers(slot);
        }

        f.start.value = data.start_time ? String(data.start_time).slice(0, 5) : '';
        f.end.value   = data.end_time ? String(data.end_time).slice(0, 5) : '';
        f.room.value  = special ? '' : (data.room || '');
        f.minutes.value = minutesBetween(slot) || '';
    }

    function readSlot(slot) {
        const f = fields(slot);
        return {
            period_type: f.type.value,
            subject: f.subject.value,
            teacher_id: f.teacher.value,
            start_time: f.start.value,
            end_time: f.end.value,
            room: f.room.value,
        };
    }

    const readDay = (panel) => Array.from(panel.querySelectorAll('.tt-slot')).map(readSlot);

    const lectureRows = (panel) =>
        Array.from(panel.querySelectorAll('.tt-slot')).filter(s => !isSpecial(fields(s).type.value));

    const rowMinutes = (slot) => {
        const value = parseInt(fields(slot).minutes.value, 10);
        return value > 0 ? value : 0;
    };

    // How long a new block of this type should be.
    function defaultMinutes(panel, type) {
        if (isSpecial(type)) return durations[type] || 10;
        if (durations.Class) return durations.Class;

        const first = lectureRows(panel).map(rowMinutes).find(Boolean);
        return first || 0;
    }

    // Times always run in sequence: the first row's start time is typed, and every row then starts
    // when the one before it ends. Each row keeps its own minutes, so moving, adding or removing
    // a block just shifts everything after it.
    function rechain(panel) {
        const rows = Array.from(panel.querySelectorAll('.tt-slot'));
        if (!rows.length) return;

        const first = fields(rows[0]);
        if (!first.start.value) return;                      // nothing to build from yet

        let clock = toMinutes(first.start.value);

        rows.forEach(function (slot, index) {
            const f = fields(slot);

            if (clock === null) {
                if (index > 0) f.start.value = '';
                f.end.value = '';
                return;
            }

            let length = rowMinutes(slot);

            if (!length) {
                length = defaultMinutes(panel, f.type.value);
                f.minutes.value = length || '';
            }

            if (index > 0) f.start.value = toTime(clock);

            if (!length || clock + length >= 1440) {
                f.end.value = '';
                clock = null;
                return;
            }

            f.end.value = toTime(clock + length);
            clock += length;
        });
    }

    function layoutChanged(panel) {
        renumber(panel);
        rechain(panel);
    }

    function addRowAfter(panel, slot, type) {
        const row = createRow(panel);
        slot.after(row);
        writeSlot(row, { period_type: type });
        fields(row).minutes.value = defaultMinutes(panel, type) || '';
        layoutChanged(panel);
        fields(row).type.focus();
    }

    function createRow(panel) {
        const holder = document.createElement('template');
        holder.innerHTML = rowHtml().trim();
        const slot = holder.content.firstElementChild;
        const f = fields(slot);

        f.type.addEventListener('change', function () {
            const wasSpecial = slot.dataset.special === '1';
            configureSlot(slot);

            // Changing between a lecture and a break resets the length to that type's usual one.
            if (wasSpecial !== isSpecial(f.type.value)) {
                f.minutes.value = defaultMinutes(panel, f.type.value) || f.minutes.value;
            }

            layoutChanged(panel);
        });

        f.subject.addEventListener('change', function () { filterTeachers(slot); });
        f.start.addEventListener('change', function () { rechain(panel); });
        f.minutes.addEventListener('change', function () { rechain(panel); });

        slot.querySelector('.js-up').addEventListener('click', function () {
            const previous = slot.previousElementSibling;
            if (previous) {
                slot.parentNode.insertBefore(slot, previous);
                layoutChanged(panel);
            }
        });

        slot.querySelector('.js-down').addEventListener('click', function () {
            const next = slot.nextElementSibling;
            if (next) {
                slot.parentNode.insertBefore(next, slot);
                layoutChanged(panel);
            }
        });

        slot.querySelector('.js-add-lecture').addEventListener('click', function () { addRowAfter(panel, slot, 'Regular'); });
        slot.querySelector('.js-add-break').addEventListener('click', function () { addRowAfter(panel, slot, 'Break'); });

        slot.querySelector('.js-remove').addEventListener('click', function () {
            if (panel.querySelectorAll('.tt-slot').length <= 1) return;
            slot.remove();
            layoutChanged(panel);
        });

        return slot;
    }

    // Rebuilds one day from a list of row data.
    function renderDay(panel, rows) {
        const body = panel.querySelector('.tt-body');
        body.innerHTML = '';

        rows.forEach(function (data) {
            const slot = createRow(panel);
            body.appendChild(slot);
            writeSlot(slot, data);
        });

        renumber(panel);
    }

    /* ---------- Copy one day to all days ---------- */
    document.querySelectorAll('.js-copy-day').forEach(function (button) {
        button.addEventListener('click', function () {
            const source = button.closest('.tt-day-panel');

            if (!confirm('Copy ' + source.dataset.day + ' to all other days? Their current entries will be replaced.')) return;

            const rows = readDay(source);

            panels.forEach(function (panel) {
                if (panel !== source) renderDay(panel, rows);
            });
        });
    });

    /* ---------- Quick layout generator ---------- */
    const setupCount    = $('setupCount');
    const setupStart    = $('setupStart');
    const setupEnd      = $('setupEnd');
    const breakList     = $('breakList');
    const setupMsg      = $('setupMsg');
    const setupPreview  = $('setupPreview');
    const setupTimeline = $('setupTimeline');

    const lectureCount = () => {
        const value = parseInt(setupCount.value, 10);
        return value >= 1 && value <= 12 ? value : 0;
    };

    function placeOptionsHtml(count) {
        let html = '';
        for (let i = 1; i < count; i++) {
            html += '<option value="' + i + '">After period ' + i + '</option>';
        }
        return html;
    }

    // Break places depend on how many lectures there are.
    function refreshPlaceOptions() {
        const count = lectureCount() || DEFAULT_LECTURES;

        breakList.querySelectorAll('.brk-after').forEach(function (select) {
            const previous = Number(select.value) || 1;
            select.innerHTML = placeOptionsHtml(count);
            select.value = String(Math.min(previous, Math.max(count - 1, 1)));
        });
    }

    function addBreakRow(after, type, minutes) {
        const row = document.createElement('div');
        row.className = 'tt-break-row';
        row.innerHTML =
            '<select class="tt-control brk-after" aria-label="Break placement">' + placeOptionsHtml(lectureCount() || DEFAULT_LECTURES) + '</select>' +
            '<select class="tt-control brk-type" aria-label="Break type">' +
                '<option value="Break">Break</option><option value="Lunch">Lunch</option></select>' +
            '<input class="tt-control brk-mins" type="number" min="5" max="180" aria-label="Break minutes">' +
            '<button type="button" class="tt-btn tt-btn-line brk-remove" aria-label="Remove break">' +
                '<i class="bi bi-x-lg"></i></button>';

        row.querySelector('.brk-after').value = String(after);
        row.querySelector('.brk-type').value  = type;
        row.querySelector('.brk-mins').value  = minutes;

        row.querySelector('.brk-remove').addEventListener('click', function () {
            row.remove();
            updatePreview();
        });

        // Suggest a sensible length when the type changes.
        row.querySelector('.brk-type').addEventListener('change', function () {
            row.querySelector('.brk-mins').value = this.value === 'Lunch' ? 30 : 10;
        });

        breakList.appendChild(row);
    }

    // The first break row of each type decides how long that type is when a row is changed by hand.
    function syncBreakDurations() {
        const seen = {};

        breakList.querySelectorAll('.tt-break-row').forEach(function (row) {
            const type = row.querySelector('.brk-type').value;
            const mins = parseInt(row.querySelector('.brk-mins').value, 10);

            if (mins >= 5 && !seen[type]) {
                durations[type] = mins;
                seen[type] = true;
            }
        });
    }

    // Lays out one day: the chosen number of lectures, each as long as the first lecture,
    // with the breaks placed after the lectures the admin picked.
    function buildPlan() {
        const count = lectureCount();

        if (!count) {
            return { error: 'Number of lectures must be between 1 and 12.' };
        }

        if (!setupStart.value || !setupEnd.value) {
            return { pending: true, error: 'Enter when the first lecture starts and ends. Its length is used for all the other lectures.' };
        }

        const startMin = toMinutes(setupStart.value);
        const length   = toMinutes(setupEnd.value) - startMin;

        if (length < 5) {
            return { error: 'The first lecture must end at least 5 minutes after it starts.' };
        }
        if (length > 180) {
            return { error: 'The first lecture is longer than 3 hours. Please check the times.' };
        }

        const breaks = {};
        let error = '';

        breakList.querySelectorAll('.tt-break-row').forEach(function (row) {
            const after   = Number(row.querySelector('.brk-after').value);
            const minutes = parseInt(row.querySelector('.brk-mins').value, 10);

            if (!after || after >= count) {
                error = 'Place each break after one of the lectures (not after the last one).';
            } else if (!minutes || minutes < 5) {
                error = 'Each break needs at least 5 minutes.';
            } else if (breaks[after]) {
                error = 'Two breaks are placed after the same period. Give each break a different place.';
            }

            breaks[after] = { type: row.querySelector('.brk-type').value, minutes: minutes || 0 };
        });

        if (error) return { error: error };

        const timeline = [];
        let clock = startMin;

        for (let n = 1; n <= count; n++) {
            timeline.push({ label: 'P' + n, type: 'Regular', special: false, start: clock, end: clock + length });
            clock += length;

            if (breaks[n]) {
                timeline.push({ label: breaks[n].type, type: breaks[n].type, special: true, start: clock, end: clock + breaks[n].minutes });
                clock += breaks[n].minutes;
            }
        }

        if (clock >= 1440) {
            return { error: 'With these times the day runs past midnight. Shorten the lectures or breaks.' };
        }

        return { timeline: timeline, count: count, length: length, breaks: breaks, startMin: startMin, endMin: clock };
    }

    function updatePreview() {
        syncBreakDurations();
        setupTimeline.innerHTML = '';
        setupPreview.classList.remove('bad', 'info');

        const plan = buildPlan();

        if (plan.error) {
            setupPreview.textContent = plan.error;
            setupPreview.classList.add(plan.pending ? 'info' : 'bad');
            if (plan.pending) durations.Class = 0;
            return;
        }

        durations.Class = plan.length;

        const breakCount = Object.keys(plan.breaks).length;

        setupPreview.textContent =
            'Every lecture is ' + plan.length + ' minutes: ' + plan.count + ' lectures and ' +
            breakCount + (breakCount === 1 ? ' break' : ' breaks') + '. The day runs ' +
            to12(plan.startMin) + ' to ' + to12(plan.endMin) + '.';

        plan.timeline.forEach(function (item) {
            const chip = document.createElement('span');
            chip.className = 'tt-chip' + (item.special ? ' special' : '');
            chip.textContent = item.label + '  ' + to12(item.start) + ' – ' + to12(item.end);
            setupTimeline.appendChild(chip);
        });
    }

    function applySetup() {
        const plan = buildPlan();

        if (plan.error) {
            alert(plan.error);
            return;
        }

        const chosen = Array.from(document.querySelectorAll('.apply-day:checked')).map(el => el.value);

        if (!chosen.length) {
            alert('Choose at least one day to apply this layout to.');
            return;
        }

        if (!confirm('Apply this layout to ' + chosen.join(', ') + '? Their layout and times will be replaced. Subjects and teachers on lectures are kept.')) {
            return;
        }

        durations.Class = plan.length;

        panels.filter(panel => chosen.includes(panel.dataset.day)).forEach(function (panel) {
            // Keep what is already chosen on the lectures, in order.
            const kept = lectureRows(panel).map(readSlot);
            let lecture = 0;

            const rows = plan.timeline.map(function (item) {
                const data = item.special
                    ? { period_type: item.type }
                    : Object.assign({}, kept[lecture++] || { period_type: 'Regular' });

                data.start_time = toTime(item.start);
                data.end_time   = toTime(item.end);
                return data;
            });

            renderDay(panel, rows);
        });

        setupMsg.textContent = 'Applied to ' + chosen.length + (chosen.length === 1 ? ' day.' : ' days.') + ' Now choose the subjects and teachers.';
    }

    $('addBreakBtn').addEventListener('click', function () {
        addBreakRow(1, 'Break', 10);
        updatePreview();
    });
    $('applySetupBtn').addEventListener('click', applySetup);

    // Example starting point: two small breaks and one lunch.
    addBreakRow(2, 'Break', 10);
    addBreakRow(4, 'Lunch', 30);
    addBreakRow(6, 'Break', 10);

    setupCount.addEventListener('input', function () {
        refreshPlaceOptions();
        updatePreview();
    });
    [setupStart, setupEnd].forEach(function (el) { el.addEventListener('input', updatePreview); });
    breakList.addEventListener('input', updatePreview);
    breakList.addEventListener('change', updatePreview);
    updatePreview();

    /* ---------- Build the days on page load ---------- */
    panels.forEach(function (panel) {
        const saved = oldTimetable && oldTimetable[panel.dataset.day];
        let rows = [];

        // After a failed save, restore exactly what was submitted.
        if (saved && typeof saved === 'object') {
            rows = Object.keys(saved).map(Number).sort((a, b) => a - b).map(function (key) {
                return saved[key] || {};
            });
        }

        if (!rows.length) {
            rows = Array.from({ length: DEFAULT_LECTURES }, function () { return { period_type: 'Regular' }; });
        }

        renderDay(panel, rows);
    });

    /* ---------- Load saved records ---------- */
    $('fetchBtn').addEventListener('click', async function () {
        const filters = getFilters();
        if (!validFilters(filters)) return;

        const button  = this;
        const oldHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';

        try {
            const response = await fetch(urls.fetch + '?' + new URLSearchParams(filters), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            const result = await response.json();

            if (!response.ok) throw new Error(result.message || 'Unable to fetch saved timetable records.');

            const records = result.records || [];
            if (!records.length) {
                alert('No saved records were found for this class, section and week.');
                return;
            }

            const byDay = {};
            records.forEach(function (record) {
                (byDay[record.day] = byDay[record.day] || []).push(record);
            });

            panels.forEach(function (panel) {
                const list = byDay[panel.dataset.day];
                if (!list) return;

                list.sort((a, b) => Number(a.period_number) - Number(b.period_number));
                renderDay(panel, list);
            });

            alert('Saved records loaded. Review the days, then click Save Weekly Timetable to submit any changes.');
        } catch (error) {
            alert(error.message || 'An error occurred while loading timetable records.');
        } finally {
            button.disabled = false;
            button.innerHTML = oldHtml;
        }
    });

    /* ---------- Export ---------- */
    function download(url) {
        const filters = getFilters();
        if (!validFilters(filters)) return;
        window.location.href = url + '?' + new URLSearchParams(filters).toString();
    }

    $('excelBtn').addEventListener('click', function () { download(urls.excel); });
    $('pdfBtn').addEventListener('click', function () { download(urls.pdf); });

    /* ---------- Import ---------- */
    const importForm = $('weeklyImportForm');
    const importFile = $('importFile');
    const importBtn  = $('importBtn');

    importBtn.addEventListener('click', function () {
        if (validFilters(getFilters())) importFile.click();
    });

    importFile.addEventListener('change', function () {
        if (!this.files || !this.files.length) return;

        if (!confirm('Importing will replace records for this selected class, section and week. Continue?')) {
            this.value = '';
            return;
        }

        const filters = getFilters();
        $('import_academic_year').value   = filters.academic_year;
        $('import_class').value           = filters.class;
        $('import_section').value         = filters.section;
        $('import_week_start_date').value = filters.week_start_date;

        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Importing...';
        importForm.submit();
    });

    /* ---------- Save validation ---------- */
    form.addEventListener('submit', function (event) {
        const filters = getFilters();

        function stop(message, field, panel) {
            event.preventDefault();
            if (panel) showDay(panel.dataset.day);
            alert(message);
            if (field) field.focus();
        }

        if (!filters.academic_year || !filters.class || !filters.section) {
            return stop('Please choose the academic year, class and section.',
                !filters.academic_year ? $('academic_year') : (!filters.class ? classSelect : sectionSelect));
        }

        if (!filters.week_start_date || new Date(filters.week_start_date + 'T12:00:00').getDay() !== 1) {
            return stop('The week start date must be a Monday.', weekStart);
        }

        for (const slot of allSlots()) {
            const f       = fields(slot);
            const special = isSpecial(f.type.value);
            const panel   = slot.closest('.tt-day-panel');
            const label   = panel.dataset.day + ', ' + slot.dataset.label + ': ';

            if (!f.start.value) {
                return stop(label + 'enter the start time. Set the first period\'s start time and the others follow.',
                    fields(panel.querySelector('.tt-slot')).start, panel);
            }
            if (!f.end.value || f.end.value <= f.start.value) {
                return stop(label + 'enter the minutes so the end time is after the start time.', f.minutes, panel);
            }
            if (!special && !f.subject.value.trim()) {
                return stop(label + 'choose a subject.', f.subject, panel);
            }
            if (!special && !f.teacher.value) {
                return stop(label + 'choose a teacher.', f.teacher, panel);
            }
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
    });

    /* ---------- Init ---------- */
    classSelect.addEventListener('change', function () { loadSections(classSelect.value); });
    weekStart.addEventListener('change', updateWeekDates);

    if (classSelect.value) loadSections(classSelect.value, oldSection);
    updateWeekDates();
});
</script>

@endsection