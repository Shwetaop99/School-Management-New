@extends('layouts.app')

@section('title', 'Edit Timetable')
@section('page-title', 'Edit Timetable')

@section('content')

<style>
    .tt-form-page{max-width:980px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .form-hero{
        margin-bottom:22px;
        padding:24px 28px;
        position:relative;
        overflow:hidden;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        flex-wrap:wrap;
    }
    .form-hero::before{
        content:"";
        position:absolute;
        left:0;top:0;bottom:0;
        width:4px;
        background:rgba(228,87,46,.55);
        border-radius:10px 0 0 10px;
    }
    .form-hero h1{
        font-family:var(--serif);
        font-size:24px;font-weight:700;
        color:var(--ink);margin:0 0 6px;
    }
    .form-hero p{margin:0;color:var(--muted);font-size:13.5px}

    .btn-line{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:500;
        text-decoration:none;
        background:#fff;border:1px solid #cfc8b6;color:var(--text);
    }
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}
    .btn-solid{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 20px;border-radius:8px;
        font-size:13.5px;font-weight:600;
        background:var(--ink);color:#fff;border:1px solid var(--ink);
        cursor:pointer;
    }
    .btn-solid:hover{background:var(--ink-2);color:#fff}

    /* Form */
    .form-card{overflow:hidden}
    .form-body{padding:26px 28px}
    .form-footer{
        display:flex;justify-content:flex-end;gap:12px;
        padding:18px 28px;
        border-top:1px dashed #d8d1bf;
        background:#faf9f6;
        flex-wrap:wrap;
    }

    .section-title{
        margin:0 0 18px;
        padding-bottom:10px;
        border-bottom:1px dashed #d8d1bf;
        font-family:var(--serif);
        font-size:15px;font-weight:700;
        color:var(--ink);
        display:flex;align-items:center;gap:8px;
    }
    .section-title i{color:#2b5d8a}

    .form-row{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:18px;
        margin-bottom:18px;
    }
    .form-group{width:100%}
    .form-group.full{grid-column:1 / -1}

    .field-label{
        display:block;margin-bottom:6px;
        font-size:12px;font-weight:700;
        color:var(--muted);
        text-transform:uppercase;letter-spacing:.3px;
    }
    .required{color:#e4572e}

    .field-input,.field-select{
        width:100%;height:42px;
        padding:0 14px;
        border:1px solid #cfc8b6;border-radius:8px;
        background:#fff;color:var(--text);
        font-size:14px;outline:none;
        transition:border-color .15s,box-shadow .15s;
        box-sizing:border-box;
    }
    .field-input:focus,.field-select:focus{
        border-color:var(--ink);
        box-shadow:0 0 0 3px rgba(29,58,51,.10);
    }
    .field-input[readonly],.field-input:disabled,.field-select:disabled{
        background:#f8f6f0;color:var(--muted);cursor:not-allowed;
    }
    .field-error{margin-top:5px;color:#e4572e;font-size:12px}
    .field-help{margin-top:5px;font-size:11.5px;color:var(--muted)}

    .alert-error{
        margin-bottom:18px;padding:14px 18px;
        border-radius:8px;font-size:13px;
        background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;
    }
    .alert-error ul{margin:6px 0 0;padding-left:18px}

    @media (max-width:700px){
        .form-row{grid-template-columns:1fr}
        .form-hero{flex-direction:column;align-items:flex-start}
        .form-footer{flex-direction:column}
        .btn-solid,.btn-line{width:100%;justify-content:center}
    }
</style>

<div class="tt-form-page">

    {{-- Header --}}
    <section class="paper form-hero">
        <div>
            <h1>Edit Timetable</h1>
            <p>Update the teacher's class, subject and teaching schedule.</p>
        </div>
        <a href="{{ route('admin.timetable.index') }}" class="btn-line">
            <i class="bi bi-arrow-left"></i> Back to Timetable
        </a>
    </section>

    @if($errors->any())
        <div class="alert-error">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.timetable.update', $timetable->id) }}"
          method="POST"
          class="paper form-card">
        @csrf
        @method('PUT')

        <div class="form-body">

            {{-- Teacher & Class --}}
            <div class="section-title">
                <i class="bi bi-person-workspace"></i>
                Teacher & Class Information
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="teacher_id" class="field-label">
                        Teacher <span class="required">*</span>
                    </label>
                    <select name="teacher_id" id="teacher_id" class="field-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }} — {{ $teacher->teacher_id }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="timetable_date" class="field-label">
                        Timetable Date <span class="required">*</span>
                    </label>
                    <input type="date" name="timetable_date" id="timetable_date"
                           class="field-input"
                           value="{{ old('timetable_date', $timetable->timetable_date ? \Carbon\Carbon::parse($timetable->timetable_date)->format('Y-m-d') : '') }}"
                           required>
                    <div class="field-help">Select the date for this timetable entry.</div>
                    @error('timetable_date')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="class" class="field-label">
                        Class <span class="required">*</span>
                    </label>
                    <select name="class" id="class" class="field-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->class_name }}"
                                {{ old('class', $timetable->class) == $class->class_name ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="day" class="field-label">
                        Day <span class="required">*</span>
                    </label>
                    <input type="text" name="day" id="day" class="field-input"
                           value="{{ old('day', $timetable->day) }}" readonly>
                    <div class="field-help">Day is automatically calculated from the selected date.</div>
                    @error('day')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="section" class="field-label">
                        Section <span class="required">*</span>
                    </label>
                    <select name="section" id="section" class="field-select" required>
                        <option value="">Select Section</option>
                    </select>
                    <div class="field-help">Sections are loaded according to the selected class.</div>
                    @error('section')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Timetable Details --}}
            <div class="section-title">
                <i class="bi bi-calendar3"></i>
                Timetable Details
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="academic_year" class="field-label">
                        Academic Year <span class="required">*</span>
                    </label>
                    <input type="text" name="academic_year" id="academic_year"
                           class="field-input"
                           value="{{ old('academic_year', $timetable->academic_year) }}"
                           placeholder="Example: 2026-27" required>
                    @error('academic_year')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="period_number" class="field-label">
                        Period Number <span class="required">*</span>
                    </label>
                    <input type="number" name="period_number" id="period_number"
                           class="field-input"
                           value="{{ old('period_number', $timetable->period_number) }}"
                           min="1" placeholder="Example: 1" required>
                    @error('period_number')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="period_type" class="field-label">
                        Period Type <span class="required">*</span>
                    </label>
                    <select name="period_type" id="period_type" class="field-select" required>
                        <option value="">Select Period Type</option>
                        @foreach(['Regular','Break','Lunch'] as $type)
                            <option value="{{ $type }}"
                                {{ old('period_type', $timetable->period_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('period_type')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="subject_type" class="field-label">
                        Subject Type <span class="required">*</span>
                    </label>
                    <select name="subject_type" id="subject_type" class="field-select">
                        <option value="">Select Subject Type</option>
                        @foreach(['Theory','Practical','Activity'] as $subjectType)
                            <option value="{{ $subjectType }}"
                                {{ old('subject_type', $timetable->subject_type) == $subjectType ? 'selected' : '' }}>
                                {{ $subjectType }}
                            </option>
                        @endforeach
                    </select>
                    <div class="field-help">Not required for Break or Lunch.</div>
                    @error('subject_type')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Subject --}}
            <div class="section-title">
                <i class="bi bi-book"></i>
                Subject Information
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="subject" class="field-label">
                        Subject <span class="required">*</span>
                    </label>
                    <input type="text" name="subject" id="subject"
                           class="field-input"
                           value="{{ old('subject', $timetable->subject) }}"
                           placeholder="Example: Mathematics" required>
                    @error('subject')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="room" class="field-label">Room</label>
                    <input type="text" name="room" id="room"
                           class="field-input"
                           value="{{ old('room', $timetable->room) }}"
                           placeholder="Example: Room 101">
                    <div class="field-help">Optional</div>
                    @error('room')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Timing --}}
            <div class="section-title">
                <i class="bi bi-clock"></i>
                Class Timing
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_time" class="field-label">
                        Start Time <span class="required">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time"
                           class="field-input"
                           value="{{ old('start_time', \Carbon\Carbon::parse($timetable->start_time)->format('H:i')) }}"
                           required>
                    @error('start_time')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="end_time" class="field-label">
                        End Time <span class="required">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time"
                           class="field-input"
                           value="{{ old('end_time', \Carbon\Carbon::parse($timetable->end_time)->format('H:i')) }}"
                           required>
                    @error('end_time')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.timetable.index') }}" class="btn-line">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button type="submit" class="btn-solid">
                <i class="bi bi-check-circle-fill"></i> Update Timetable
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Date → Day
    const timetableDate = document.getElementById('timetable_date');
    const dayInput = document.getElementById('day');

    function updateDayFromDate() {
        if (!timetableDate.value) {
            dayInput.value = '';
            return;
        }
        const date = new Date(timetableDate.value + 'T00:00:00');
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        dayInput.value = days[date.getDay()];
    }
    updateDayFromDate();
    timetableDate.addEventListener('change', updateDayFromDate);

    // Class → Section
    const classSelect = document.getElementById('class');
    const sectionSelect = document.getElementById('section');
    const allSections = @json($sections);
    const allClasses = @json($classes);
    const currentSection = @json(old('section', $timetable->section));

    function loadSections(className, selectedSection = '') {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        if (!className) {
            sectionSelect.disabled = true;
            return;
        }
        const selectedClass = allClasses.find(item => item.class_name === className);
        if (!selectedClass) {
            sectionSelect.disabled = true;
            return;
        }
        const classSections = allSections.filter(s => s.class_id == selectedClass.id);
        if (classSections.length === 0) {
            sectionSelect.disabled = true;
            return;
        }
        sectionSelect.disabled = false;
        classSections.forEach(function (section) {
            const option = document.createElement('option');
            option.value = section.section_name;
            option.textContent = section.section_name;
            if (section.section_name === selectedSection) option.selected = true;
            sectionSelect.appendChild(option);
        });
    }

    classSelect.addEventListener('change', function () {
        loadSections(this.value);
    });

    if (classSelect.value) {
        loadSections(classSelect.value, currentSection);
    } else {
        sectionSelect.disabled = true;
    }

    // Period type → Subject fields
    const periodType = document.getElementById('period_type');
    const subject = document.getElementById('subject');
    const subjectType = document.getElementById('subject_type');

    function toggleFields() {
        const type = periodType.value;
        if (type === 'Break' || type === 'Lunch') {
            subject.disabled = true;
            subjectType.disabled = true;
            subject.removeAttribute('required');
            subjectType.removeAttribute('required');
            subject.value = type;
        } else {
            subject.disabled = false;
            subjectType.disabled = false;
            subject.setAttribute('required', 'required');
            subjectType.setAttribute('required', 'required');
            if (subject.value === 'Break' || subject.value === 'Lunch') {
                subject.value = '';
            }
        }
    }
    periodType.addEventListener('change', toggleFields);
    toggleFields();
});
</script>

@endsection