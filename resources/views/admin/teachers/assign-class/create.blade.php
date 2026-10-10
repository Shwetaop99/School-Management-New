
@extends('layouts.app')

@section('title', 'Teacher Allocation')

@section('content')

<style>
.allocation-page {
    max-width: 850px;
    margin: 25px auto;
}

.allocation-page h2 {
    margin-bottom: 5px;
    color: #1f2937;
}

.allocation-page p {
    color: #6b7280;
    margin-bottom: 20px;
}

.allocation-card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
}

.field {
    margin-bottom: 20px;
}

.field label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    color: #374151;
}

.field select,
.field input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
}

.error {
    color: #ef4444;
    font-size: 13px;
    margin-top: 5px;
}

.buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.allocate-btn,
.cancel-btn {
    padding: 10px 18px;
    border: 0;
    border-radius: 7px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
}

.allocate-btn {
    background: #147cf5;
    color: #fff;
}

.cancel-btn {
    background: #e5e7eb;
    color: #374151;
}
</style>

<div class="allocation-page">

    <h2>Teacher Allocation</h2>

    <p>Allocate a teacher to a class and section.</p>

    <div class="allocation-card">

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

                    <option value="1" {{ old('class_id') == 1 ? 'selected' : '' }}>
                        1st Standard
                    </option>

                    <option value="2" {{ old('class_id') == 2 ? 'selected' : '' }}>
                        2nd Standard
                    </option>

                    <option value="3" {{ old('class_id') == 3 ? 'selected' : '' }}>
                        3rd Standard
                    </option>

                    <option value="4" {{ old('class_id') == 4 ? 'selected' : '' }}>
                        4th Standard
                    </option>

                    <option value="5" {{ old('class_id') == 5 ? 'selected' : '' }}>
                        5th Standard
                    </option>

                    <option value="6" {{ old('class_id') == 6 ? 'selected' : '' }}>
                        6th Standard
                    </option>

                    <option value="7" {{ old('class_id') == 7 ? 'selected' : '' }}>
                        7th Standard
                    </option>

                    <option value="8" {{ old('class_id') == 8 ? 'selected' : '' }}>
                        8th Standard
                    </option>

                    <option value="9" {{ old('class_id') == 9 ? 'selected' : '' }}>
                        9th Standard
                    </option>

                    <option value="10" {{ old('class_id') == 10 ? 'selected' : '' }}>
                        10th Standard
                    </option>
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

                <button type="submit" class="allocate-btn">
                    Allocate Teacher
                </button>

                <a
                    href="{{ route('admin.teachers.assign-class.index') }}"
                    class="cancel-btn">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection

