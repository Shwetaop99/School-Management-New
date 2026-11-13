@extends('layouts.app')

@section('title', 'Class Timetable')
@section('page-title', 'Class Timetable')

@section('content')

<style>
    .ct-page{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .ct-hero{
        margin-bottom:22px;
        padding:24px 28px;
        position:relative;
        overflow:hidden;
    }
    .ct-hero::before{
        content:"";
        position:absolute;
        left:0;top:0;bottom:0;
        width:4px;
        background:rgba(228,87,46,.55);
        border-radius:10px 0 0 10px;
    }
    .ct-hero h1{
        font-family:var(--serif);
        font-size:24px;font-weight:700;
        color:var(--ink);margin:0 0 6px;
    }
    .ct-hero p{margin:0;color:var(--muted);font-size:13.5px}

    /* Selection */
    .select-card{padding:22px 24px;margin-bottom:22px}
    .select-card h3{
        font-family:var(--serif);
        font-size:16px;font-weight:700;
        color:var(--ink);margin:0 0 4px;
    }
    .select-card > p{margin:0 0 16px;color:var(--muted);font-size:12.5px}

    .select-form{
        display:grid;
        grid-template-columns:1fr 1fr auto;
        gap:14px;
        align-items:end;
    }
    .field-group label{
        display:block;margin-bottom:6px;
        font-size:11.5px;font-weight:700;
        color:var(--muted);text-transform:uppercase;letter-spacing:.3px;
    }
    .field-select{
        width:100%;height:40px;
        padding:0 12px;
        border:1px solid #cfc8b6;border-radius:8px;
        background:#fff;color:var(--text);font-size:13px;outline:none;
    }
    .field-select:focus{
        border-color:var(--ink);
        box-shadow:0 0 0 3px rgba(29,58,51,.08);
    }
    .btn-solid{
        display:inline-flex;align-items:center;gap:7px;
        height:40px;padding:0 18px;
        border-radius:8px;border:1px solid var(--ink);
        background:var(--ink);color:#fff;
        font-size:13px;font-weight:600;cursor:pointer;
        white-space:nowrap;
    }
    .btn-solid:hover{background:var(--ink-2);color:#fff}

    /* Result */
    .result-card{padding:20px 22px;margin-bottom:22px}
    .result-head{
        display:flex;align-items:center;justify-content:space-between;
        gap:14px;margin-bottom:16px;flex-wrap:wrap;
    }
    .result-head h3{
        font-family:var(--serif);
        font-size:17px;font-weight:700;
        color:var(--ink);margin:0;
    }
    .result-head p{margin:3px 0 0;color:var(--muted);font-size:12px}

    .download-actions{display:flex;gap:8px}
    .dl-btn{
        display:inline-flex;align-items:center;gap:5px;
        height:34px;padding:0 12px;border-radius:7px;
        font-size:11px;font-weight:700;text-decoration:none;
        border:1px solid transparent;
    }
    .dl-pdf{background:color-mix(in srgb,#e4572e 12%,#fff);color:#e4572e}
    .dl-pdf:hover{background:#e4572e;color:#fff}
    .dl-excel{background:color-mix(in srgb,#3a9d6b 12%,#fff);color:#3a9d6b}
    .dl-excel:hover{background:#3a9d6b;color:#fff}

    /* Table */
    .table-wrap{width:100%;overflow-x:auto;border:1px solid #e6e0d2;border-radius:8px}
    .weekly-table{
        width:100%;min-width:900px;
        border-collapse:separate;border-spacing:0;
        table-layout:fixed;
    }
    .weekly-table th{
        padding:10px 8px;
        background:var(--ink);color:#fff;
        text-align:center;font-size:11px;font-weight:700;
        border-right:1px solid rgba(255,255,255,.2);
        white-space:nowrap;
    }
    .weekly-table th:first-child{border-radius:8px 0 0 0}
    .weekly-table th:last-child{border-right:none;border-radius:0 8px 0 0}
    .period-title{font-size:11px;font-weight:700}
    .period-time{margin-top:3px;font-size:9px;opacity:.85}

    .day-col{
        width:90px;min-width:90px;
        background:#f8f6f0 !important;
        color:var(--ink) !important;
        text-align:center;font-size:12px;font-weight:700;
        padding:10px 6px !important;
        border-right:1px solid #e6e0d2;
        border-bottom:1px solid #e6e0d2;
    }

    .weekly-table td{
        min-width:120px;height:92px;
        padding:6px;
        background:#fff;
        border-right:1px solid #f0ebe0;
        border-bottom:1px solid #f0ebe0;
        vertical-align:middle;
    }
    .weekly-table td:last-child{border-right:none}

    .subject-cell{
        min-height:70px;padding:8px;border-radius:7px;
        background:#f5f8fc;
        border-left:3px solid #2b5d8a;
    }
    .subject-name{color:#2b5d8a;font-size:12px;font-weight:700;margin-bottom:3px;line-height:1.2}
    .teacher-name{color:var(--muted);font-size:10.5px;margin-bottom:2px}
    .room-name{color:var(--muted);font-size:10px;margin-bottom:3px}
    .subject-type{
        display:inline-block;padding:2px 6px;border-radius:10px;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;font-size:9px;font-weight:700;
    }

    .special{
        min-height:70px;padding:7px;border-radius:7px;
        text-align:center;
        display:flex;flex-direction:column;justify-content:center;align-items:center;
    }
    .special-icon{font-size:16px;line-height:1}
    .special-title{margin-top:4px;font-size:11px;font-weight:700}
    .special-time{margin-top:3px;font-size:9px;color:var(--muted)}

    .break-cell{background:#fff8e8;border:1px solid #f0d890}
    .break-cell .special-title{color:#a16207}
    .lunch-cell{background:#f0faf3;border:1px solid #c5e6d0}
    .lunch-cell .special-title{color:#2b7a52}
    .activity-cell{background:#f5f3ff;border:1px solid #ddd6fe}
    .activity-cell .special-title{color:#6d28d9}

    .free-cell{
        min-height:70px;
        display:flex;justify-content:center;align-items:center;
        color:var(--muted);font-size:11px;font-style:italic;
    }

    /* Legend */
    .legend{
        display:flex;flex-wrap:wrap;gap:14px;
        margin-top:14px;padding-top:12px;
        border-top:1px dashed #d8d1bf;
    }
    .legend-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--muted)}
    .legend-box{width:10px;height:10px;border-radius:3px}
    .lb-regular{background:#2b5d8a}
    .lb-break{background:#f0d890}
    .lb-lunch{background:#86efac}
    .lb-activity{background:#c4b5fd}
    .lb-free{background:#cbd5e1}

    /* Empty / Initial */
    .state-box{
        padding:48px 20px;text-align:center;
        margin-bottom:22px;
    }
    .state-box i{font-size:32px;color:var(--muted);display:block;margin-bottom:10px}
    .state-box h3{
        font-family:var(--serif);
        font-size:17px;font-weight:700;
        color:var(--ink);margin:0 0 6px;
    }
    .state-box p{margin:0;color:var(--muted);font-size:13px}

    @media (max-width:900px){
        .select-form{grid-template-columns:1fr}
        .btn-solid{width:100%;justify-content:center}
        .result-head{flex-direction:column;align-items:flex-start}
        .download-actions{width:100%}
        .dl-btn{flex:1;justify-content:center}
    }
</style>

<div class="ct-page">

    {{-- Header --}}
    <section class="paper ct-hero">
        <h1>Class Timetable</h1>
        <p>View the weekly timetable for a selected class and section.</p>
    </section>

    {{-- Selection --}}
    <section class="paper select-card">
        <h3>Select Class & Section</h3>
        <p>Choose a class and section to view its weekly timetable.</p>

        <form method="GET" action="{{ route('admin.timetable.class') }}" class="select-form">
            <div class="field-group">
                <label for="class">Class</label>
                <select name="class" id="class" class="field-select" required
                        onchange="loadSections(this.value)">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}"
                            {{ request('class') == $class ? 'selected' : '' }}>
                            {{ $class }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="section">Section</label>
                <select name="section" id="section" class="field-select"
                        {{ request('class') ? '' : 'disabled' }} required>
                    <option value="">-- Select Section --</option>
                    @foreach($sections as $section)
                        <option value="{{ $section }}"
                            {{ request('section') == $section ? 'selected' : '' }}>
                            {{ $section }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <button type="submit" class="btn-solid">
                    <i class="bi bi-calendar3"></i> View Timetable
                </button>
            </div>
        </form>
    </section>

    {{-- Result --}}
    @if(request('class') && request('section'))

        @if($timetables->count())
            <section class="paper result-card">
                <div class="result-head">
                    <div>
                        <h3>{{ request('class') }} – Section {{ request('section') }}</h3>
                        <p>Weekly Class Timetable</p>
                    </div>
                    <div class="download-actions">
                        <a href="{{ route('admin.timetable.class.pdf', [
                            'class' => request('class'),
                            'section' => request('section')
                        ]) }}" class="dl-btn dl-pdf" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.timetable.class.excel', [
                            'class' => request('class'),
                            'section' => request('section')
                        ]) }}" class="dl-btn dl-excel">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                        </a>
                    </div>
                </div>

                @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                    $periods = $timetables->groupBy('period_number')->sortKeys();
                @endphp

                <div class="table-wrap">
                    <table class="weekly-table">
                        <thead>
                            <tr>
                                <th class="day-col">Day</th>
                                @foreach($periods as $periodNumber => $periodEntries)
                                    @php $period = $periodEntries->first(); @endphp
                                    <th>
                                        <div class="period-title">P{{ $periodNumber }}</div>
                                        @if($period->start_time && $period->end_time)
                                            <div class="period-time">
                                                {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}
                                                –
                                                {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}
                                            </div>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($days as $day)
                                <tr>
                                    <td class="day-col">{{ $day }}</td>
                                    @foreach($periods as $periodNumber => $periodEntries)
                                        @php $entry = $periodEntries->firstWhere('day', $day); @endphp
                                        <td>
                                            @if($entry)
                                                @if($entry->period_type === 'Break')
                                                    <div class="special break-cell">
                                                        <div class="special-icon">☕</div>
                                                        <div class="special-title">Break</div>
                                                        @if($entry->start_time && $entry->end_time)
                                                            <div class="special-time">
                                                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                                                –
                                                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($entry->period_type === 'Lunch')
                                                    <div class="special lunch-cell">
                                                        <div class="special-icon">🍱</div>
                                                        <div class="special-title">Lunch</div>
                                                        @if($entry->start_time && $entry->end_time)
                                                            <div class="special-time">
                                                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                                                –
                                                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($entry->period_type === 'Activity')
                                                    <div class="special activity-cell">
                                                        <div class="special-icon">🎨</div>
                                                        <div class="special-title">{{ $entry->subject ?: 'Activity' }}</div>
                                                        @if($entry->teacher)
                                                            <div class="teacher-name">
                                                                {{ $entry->teacher->first_name }} {{ $entry->teacher->last_name }}
                                                            </div>
                                                        @endif
                                                        @if($entry->room)
                                                            <div class="room-name">Room: {{ $entry->room }}</div>
                                                        @endif
                                                        @if($entry->start_time && $entry->end_time)
                                                            <div class="special-time">
                                                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                                                –
                                                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="subject-cell">
                                                        <div class="subject-name">{{ $entry->subject }}</div>
                                                        @if($entry->teacher)
                                                            <div class="teacher-name">
                                                                {{ $entry->teacher->first_name }} {{ $entry->teacher->last_name }}
                                                            </div>
                                                        @endif
                                                        @if($entry->room)
                                                            <div class="room-name">Room: {{ $entry->room }}</div>
                                                        @endif
                                                        @if($entry->subject_type)
                                                            <div class="subject-type">{{ $entry->subject_type }}</div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <div class="free-cell">Free</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="legend">
                    <div class="legend-item"><span class="legend-box lb-regular"></span> Regular</div>
                    <div class="legend-item"><span class="legend-box lb-break"></span> Break</div>
                    <div class="legend-item"><span class="legend-box lb-lunch"></span> Lunch</div>
                    <div class="legend-item"><span class="legend-box lb-activity"></span> Activity</div>
                    <div class="legend-item"><span class="legend-box lb-free"></span> Free</div>
                </div>
            </section>
        @else
            <section class="paper state-box">
                <i class="bi bi-calendar-x"></i>
                <h3>No Timetable Found</h3>
                <p>
                    No timetable has been created for
                    <strong>{{ request('class') }}</strong> –
                    <strong>Section {{ request('section') }}</strong>
                </p>
            </section>
        @endif

    @else
        <section class="paper state-box">
            <i class="bi bi-journal-bookmark"></i>
            <h3>Select a Class and Section</h3>
            <p>Select a class and section above to view the weekly timetable.</p>
        </section>
    @endif

</div>

<script>
function loadSections(classValue) {
    if (!classValue) {
        window.location.href = "{{ route('admin.timetable.class') }}";
        return;
    }
    const url = new URL("{{ route('admin.timetable.class') }}", window.location.origin);
    url.searchParams.set('class', classValue);
    window.location.href = url.toString();
}
</script>

@endsection