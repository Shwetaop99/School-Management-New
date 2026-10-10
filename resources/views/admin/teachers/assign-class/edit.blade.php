```blade
@extends('layouts.app')

@section('title', 'Edit Teacher Allocation')

@section('content')

<style>

.edit-allocation-page {
    max-width: 900px;
    margin: 30px auto;
}

.edit-header {
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    padding: 25px 30px;
    border-radius: 12px 12px 0 0;
}

.edit-header h2 {
    margin: 0 0 6px;
    font-size: 23px;
}

.edit-header p {
    margin: 0;
    font-size: 14px;
    opacity: .9;
}

.edit-card {
    background: #fff;
    padding: 30px;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.field {
    margin-bottom: 5px;
}

.field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.field select,
.field input {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
}

.field select:focus,
.field input:focus {
    outline: none;
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20,124,245,.10);
}

.error {
    margin-top: 6px;
    color: #ef4444;
    font-size: 13px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.update-btn,
.cancel-btn {
    padding: 11px 20px;
    border-radius: 8px;
    border: none;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.update-btn {
    background: #147cf5;
    color: #fff;
}

.update-btn:hover {
    background: #1268ca;
}

.cancel-btn {
    background: #f3f4f6;
    color: #374151;
}

.cancel-btn:hover {
    background: #e5e7eb;
}

@media (max-width: 700px) {

    .edit-allocation-page {
        margin: 15px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .update-btn,
    .cancel-btn {
        width: 100%;
        text-align: center;
    }

}

</style>


<div class="edit-allocation-page">

    {{-- Header --}}
    <div class="edit-header">

        <h2>
            Edit Teacher Allocation
        </h2>

        <p>
            Update teacher, class, section and academic year.
        </p>

    </div>


    <div class="edit-card">

        <form
            action="{{ route('admin.teachers.assign-class.update', $assignment->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="form-grid">

                {{-- Teacher --}}
                <div class="field">

                    <label for="teacher_id">
                        Teacher *
                    </label>

                    <select
                        name="teacher_id"
                        id="teacher_id"
                        required
                    >

                        <option value="">
                            Select Teacher
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ old('teacher_id', $assignment->teacher_id) == $teacher->id ? 'selected' : '' }}
                            >
                                {{ $teacher->first_name }}
                                {{ $teacher->last_name }}
                            </option>

                        @endforeach

                    </select>

                    @error('teacher_id')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Class --}}
                <div class="field">

                    <label for="class_id">
                        Class *
                    </label>

                    <select
                        name="class_id"
                        id="class_id"
                        required
                    >

                        <option value="">
                            Select Class
                        </option>

                       @foreach($sections as $section)
    <option
        value="{{ $section->id }}"
        {{ old('section_id', $assignment->section_id) == $section->id ? 'selected' : '' }}
    >
        {{ $section->section_name }}
    </option>
@endforeach
                    </select>

                    @error('class_id')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Section --}}
                <div class="field">

                    <label for="section_id">
                        Section *
                    </label>

                    <select
                        name="section_id"
                        id="section_id"
                        required
                    >

                        <option value="">
                            Select Section
                        </option>

                        @foreach($sections as $section)

                            <option
    value="{{ $section->id }}"
    {{ old('section_id', $assignment->section_id) == $section->id ? 'selected' : '' }}
>
    {{ $section->section_name }}
</option>

                        @endforeach

                    </select>

                    @error('section_id')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Academic Year --}}
                <div class="field">

                    <label for="academic_year">
                        Academic Year
                    </label>

                    <input
                        type="text"
                        name="academic_year"
                        id="academic_year"
                        value="{{ old('academic_year', $assignment->academic_year) }}"
                        placeholder="Example: 2026-2027"
                    >

                    @error('academic_year')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Buttons --}}
            <div class="form-actions">

                <a
                    href="{{ route('admin.teachers.assign-class.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="update-btn"
                >
                    Update Allocation
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
