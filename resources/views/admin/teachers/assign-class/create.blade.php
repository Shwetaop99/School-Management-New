@extends('layouts.app')

@section('title', 'Teacher Allocation')
@section('page-title', 'Teacher Allocation')

@section('content')

<style>
    .alloc-form-page{max-width:780px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .form-hero{
        margin-bottom:22px;
        padding:24px 28px;
        position:relative;
        overflow:hidden;
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
        font-size:24px;
        font-weight:700;
        color:var(--ink);
        margin:0 0 6px;
    }
    .form-hero p{
        margin:0;
        color:var(--muted);
        font-size:13.5px;
    }

    /* Card */
    .form-card{padding:28px 30px}

    .field{margin-bottom:20px}
    .field label{
        display:block;
        margin-bottom:7px;
        font-size:12.5px;
        font-weight:700;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:.3px;
    }
    .field select,
    .field input{
        width:100%;
        height:42px;
        padding:0 14px;
        border:1px solid #cfc8b6;
        border-radius:8px;
        background:#fff;
        color:var(--text);
        font-size:14px;
        outline:none;
        transition:border-color .15s, box-shadow .15s;
    }
    .field select:focus,
    .field input:focus{
        border-color:var(--ink);
        box-shadow:0 0 0 3px rgba(29,58,51,.10);
    }
    .error{
        margin-top:6px;
        color:#e4572e;
        font-size:12.5px;
    }

    .buttons{
        display:flex;
        gap:12px;
        margin-top:28px;
        flex-wrap:wrap;
    }
    .btn-solid,.btn-line{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:11px 20px;
        border-radius:8px;
        font-size:13.5px;
        font-weight:600;
        text-decoration:none;
        cursor:pointer;
        border:1px solid transparent;
    }
    .btn-solid{
        background:var(--ink);
        color:#fff;
        border-color:var(--ink);
    }
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-line{
        background:#fff;
        border-color:#cfc8b6;
        color:var(--text);
    }
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}
</style>

<div class="alloc-form-page">

    {{-- Header --}}
    <section class="paper form-hero">
        <h1>Teacher Allocation</h1>
        <p>Allocate a teacher to a class and section.</p>
    </section>

    {{-- Form Card --}}
    <section class="paper form-card">
        <form action="{{ route('admin.teachers.assign-class.store') }}" method="POST">
            @csrf

            {{-- Teacher --}}
            <div class="field">
                <label for="teacher_id">Teacher *</label>
                <select name="teacher_id" id="teacher_id" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Class --}}
            <div class="field">
                <label for="class_id">Class *</label>
                <select name="class_id" id="class_id" required>
                    <option value="">Select Class</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('class_id') == $i ? 'selected' : '' }}>
                            {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Standard
                        </option>
                    @endfor
                </select>
                @error('class_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Section --}}
            <div class="field">
                <label for="section_id">Section *</label>
                <select name="section_id" id="section_id" required>
                    <option value="">Select Section</option>
                    <option value="1" {{ old('section_id') == 1 ? 'selected' : '' }}>A</option>
                    <option value="2" {{ old('section_id') == 2 ? 'selected' : '' }}>B</option>
                    <option value="3" {{ old('section_id') == 3 ? 'selected' : '' }}>C</option>
                    <option value="4" {{ old('section_id') == 4 ? 'selected' : '' }}>D</option>
                </select>
                @error('section_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Academic Year --}}
            <div class="field">
                <label for="academic_year">Academic Year</label>
                <input
                    type="text"
                    name="academic_year"
                    id="academic_year"
                    value="{{ old('academic_year') }}"
                    placeholder="Example: 2026-27"
                >
                @error('academic_year')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="buttons">
                <button type="submit" class="btn-solid">
                    <i class="bi bi-check2"></i>
                    Allocate Teacher
                </button>

                <a href="{{ route('admin.teachers.assign-class.index') }}" class="btn-line">
                    Cancel
                </a>
            </div>
        </form>
    </section>

</div>

@endsection