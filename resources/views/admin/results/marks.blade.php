@extends('layouts.app')

@section('title', 'Enter Marks')
@section('page-title', 'Enter Marks')

@section('content')

<style>
    .marks-page {
        padding: 20px;
        background: #f4f7fb;
        min-height: calc(100vh - 100px);
    }

    .marks-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    }

    .marks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .marks-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 24px;
        font-weight: 700;
    }

    .marks-header p {
        margin: 5px 0 0;
        color: #6b7280;
    }

    .info-box {
        background: #eef6ff;
        border: 1px solid #d7e9ff;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
        color: #1e4f8a;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .marks-table {
        width: 100%;
        border-collapse: collapse;
    }

    .marks-table th {
        background: #f4f7fb;
        padding: 13px;
        text-align: left;
        color: #374151;
        white-space: nowrap;
    }

    .marks-table td {
        padding: 11px 13px;
        border-top: 1px solid #edf0f4;
        vertical-align: middle;
    }

    .marks-input {
        width: 110px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        padding: 8px 10px;
    }

    .subject-heading {
        background: #f8fafc;
        font-weight: 700;
        color: #1769d1;
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
        font-weight: 600;
        cursor: pointer;
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
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .error-box {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 9px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }
</style>

<div class="marks-page">

    <div class="marks-card">

        <div class="marks-header">

            <div>
                <h2>Enter Student Marks</h2>

                <p>
                    {{ $exam->exam_name ?? 'Examination' }}
                    @if(isset($schoolClass))
                        — {{ $schoolClass->class_name }}
                    @endif
                </p>
            </div>

            <a href="{{ route('admin.results.generate') }}"
               class="btn-secondary-custom">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

        @if($errors->any())

            <div class="error-box">

                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif

        <div class="info-box">

            <strong>Instructions:</strong>

            Enter marks for each student and subject.
            Leave a field empty if marks are not available.

        </div>

        <form method="POST"
              action="{{ route('admin.results.store') }}">

            @csrf

            <input type="hidden"
                   name="exam_id"
                   value="{{ $exam->id }}">

            <input type="hidden"
                   name="class_id"
                   value="{{ $schoolClass->id }}">

            <div class="table-responsive">

                <table class="marks-table">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>

                            @foreach($examSubjects as $examSubject)

                                <th>
                                    {{ optional($examSubject->subject)->subject_name ?? 'Subject' }}

                                    <br>

                                    <small>
                                        Max:
                                        {{ $examSubject->maximum_marks }}
                                        |
                                        Pass:
                                        {{ $examSubject->passing_marks }}
                                    </small>
                                </th>

                            @endforeach

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $student)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $student->student_id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $student->first_name }}
                                        {{ $student->middle_name }}
                                        {{ $student->last_name }}
                                    </strong>
                                </td>

                                @foreach($examSubjects as $examSubject)

                                    @php
                                        $key = $student->id . '_' . $examSubject->id;
                                        $existing = $existingResults[$key] ?? null;
                                    @endphp

                                    <td>

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="{{ $examSubject->maximum_marks }}"
                                            class="marks-input"
                                            name="marks[{{ $student->id }}][{{ $examSubject->id }}]"
                                            value="{{ old(
                                                'marks.' . $student->id . '.' . $examSubject->id,
                                                $existing->marks_obtained ?? ''
                                            ) }}"
                                            placeholder="Marks"
                                        >

                                    </td>

                                @endforeach

                            </tr>

                        @empty

                            <tr>

                                <td colspan="{{ 3 + $examSubjects->count() }}"
                                    style="text-align:center;padding:40px;color:#6b7280;">

                                    No active students found for this class.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($students->count())

                <div class="actions">

                    <a href="{{ route('admin.results.generate') }}"
                       class="btn-secondary-custom">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn-primary-custom">

                        <i class="bi bi-check-circle"></i>
                        Save Marks

                    </button>

                </div>

            @endif

        </form>

    </div>

</div>

@endsection