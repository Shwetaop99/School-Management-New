@extends('layouts.app')

@section('title', 'Results Management')
@section('page-title', 'Results Management')

@section('content')

<style>
    .results-page {
        padding: 20px;
        background: #f4f7fb;
        min-height: calc(100vh - 100px);
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .results-title h2 {
        margin: 0;
        color: #1f2937;
        font-size: 26px;
        font-weight: 700;
    }

    .results-title p {
        margin: 5px 0 0;
        color: #6b7280;
    }

    .result-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        margin-bottom: 20px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        width: 100%;
        border: 1px solid #dbe2ea;
        border-radius: 9px;
        padding: 10px 12px;
        background: #fff;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: none;
        border-radius: 9px;
        padding: 10px 18px;
        background: #1769d1;
        color: white;
        text-decoration: none;
        font-weight: 600;
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
        padding: 10px 18px;
        background: white;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .results-table th {
        background: #f4f7fb;
        color: #374151;
        font-weight: 700;
        padding: 13px;
        text-align: left;
        white-space: nowrap;
    }

    .results-table td {
        padding: 13px;
        border-top: 1px solid #edf0f4;
        color: #4b5563;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-pass {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-fail {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty-state {
        text-align: center;
        padding: 45px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 42px;
        color: #9ca3af;
        display: block;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr;
        }

        .results-page {
            padding: 12px;
        }
    }
</style>

<div class="results-page">

    <div class="results-header">
        <div class="results-title">
            <h2>Results Management</h2>
            <p>View and manage student examination results.</p>
        </div>

        <a href="{{ route('admin.results.generate') }}"
           class="btn-primary-custom">
            <i class="bi bi-plus-circle"></i>
            Generate Result
        </a>
    </div>

    <div class="result-card">

        <form method="GET" action="{{ route('admin.results.index') }}">

            <div class="filter-row">

                <div>
                    <label class="form-label">Exam</label>

                    <select name="exam_id" class="form-select">
                        <option value="">All Exams</option>

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

                <div>
                    <label class="form-label">Class</label>

                    <select name="class_id" class="form-select">
                        <option value="">All Classes</option>

                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div style="margin-top: 15px; display:flex; gap:10px;">

                <button type="submit" class="btn-primary-custom">
                    <i class="bi bi-search"></i>
                    Filter
                </button>

                <a href="{{ route('admin.results.index') }}"
                   class="btn-secondary-custom">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>

            </div>

        </form>

    </div>

    <div class="result-card">

        <div class="table-responsive">

            @if($results->count())

                <table class="results-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Exam</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Grade</th>
                            <th>Result</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($results as $result)

                            @php
                                $passingMarks = optional($result->examSubject)->passing_marks;
                                $marks = $result->marks_obtained;
                                $isPass = $marks !== null &&
                                          ($passingMarks === null || $marks >= $passingMarks);
                            @endphp

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $result->student->first_name ?? '' }}
                                    {{ $result->student->middle_name ?? '' }}
                                    {{ $result->student->last_name ?? '' }}
                                </td>

                                <td>
                                    {{ $result->student->student_id ?? '-' }}
                                </td>

                                <td>
                                    {{ $result->exam->exam_name ?? '-' }}
                                </td>

                                <td>
                                    {{ optional(optional($result->examSubject)->subject)->subject_name ?? '-' }}
                                </td>

                                <td>
                                    @if($marks !== null)
                                        {{ $marks }}
                                        /
                                        {{ $passingMarks ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $result->grade ?? '-' }}
                                </td>

                                <td>
                                    @if($marks === null)
                                        <span class="badge">
                                            Not Entered
                                        </span>
                                    @elseif($isPass)
                                        <span class="badge badge-pass">
                                            Pass
                                        </span>
                                    @else
                                        <span class="badge badge-fail">
                                            Fail
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <i class="bi bi-journal-x"></i>

                    <h4>No Results Found</h4>

                    <p>
                        No result records have been entered yet.
                    </p>

                    <a href="{{ route('admin.results.generate') }}"
                       class="btn-primary-custom">
                        <i class="bi bi-plus-circle"></i>
                        Generate Result
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection