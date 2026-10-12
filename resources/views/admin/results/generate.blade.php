@extends('layouts.app')

@section('title', 'Generate Result')
@section('page-title', 'Generate Result')

@section('content')

<style>
    .generate-page {
        padding: 20px;
        background: #f4f7fb;
        min-height: calc(100vh - 100px);
    }

    .generate-card {
        max-width: 850px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    }

    .generate-header {
        margin-bottom: 25px;
    }

    .generate-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 25px;
        font-weight: 700;
    }

    .generate-header p {
        color: #6b7280;
        margin-top: 6px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-select {
        width: 100%;
        border: 1px solid #dbe2ea;
        border-radius: 9px;
        padding: 11px 13px;
        background: white;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: none;
        border-radius: 9px;
        padding: 11px 20px;
        background: #1769d1;
        color: white;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary-custom:hover {
        color: white;
        background: #125bb7;
    }

    .btn-secondary-custom {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid #dbe2ea;
        border-radius: 9px;
        padding: 11px 20px;
        background: white;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
    }

    .actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .error-box {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 9px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }
</style>

<div class="generate-page">

    <div class="generate-card">

        <div class="generate-header">
            <h2>Generate Student Result</h2>
            <p>Select an examination and class to enter student marks.</p>
        </div>

        @if($errors->any())

            <div class="error-box">

                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif

        <form method="GET" action="{{ route('admin.results.marks') }}">

            <div class="form-group">

                <label class="form-label">
                    Examination
                </label>

                <select name="exam_id"
                        class="form-select"
                        required>

                    <option value="">
                        Select Examination
                    </option>

                    @foreach($exams as $exam)

                        <option value="{{ $exam->id }}"
                            {{ request('exam_id') == $exam->id ? 'selected' : '' }}>

                            {{ $exam->exam_name }}

                            @if($exam->academic_year)
                                - {{ $exam->academic_year }}
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label class="form-label">
                    Class
                </label>

                <select name="class_id"
                        class="form-select"
                        required>

                    <option value="">
                        Select Class
                    </option>

                    @foreach($classes as $class)

                        <option value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}>

                            {{ $class->class_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="actions">

                <button type="submit"
                        class="btn-primary-custom">

                    <i class="bi bi-pencil-square"></i>
                    Enter Marks

                </button>

                <a href="{{ route('admin.results.index') }}"
                   class="btn-secondary-custom">

                    <i class="bi bi-arrow-left"></i>
                    Back

                </a>

            </div>

        </form>

    </div>

</div>

@endsection