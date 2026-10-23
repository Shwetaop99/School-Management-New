@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Result Report</h2>
            <p class="text-muted mb-0">
                View and analyse student examination marks.
            </p>
        </div>

        <button type="button" class="btn btn-outline-primary"
                onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    {{-- Summary cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Result Records</p>
                    <h3>{{ $summary->total_records ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Students</p>
                    <h3>{{ $summary->total_students ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Marks Obtained</p>
                    <h3>{{ number_format((float) ($summary->total_obtained ?? 0), 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Average Marks</p>
                    <h3>
                        {{ $summary->average_marks !== null
                            ? number_format((float) $summary->average_marks, 2)
                            : '—' }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Filter Results</strong>
        </div>

        <div class="card-body">
            <form method="GET"
                  action="{{ route('admin.reports.results.index') }}"
                  class="row align-items-end">

                <div class="col-md-3 mb-3">
                    <label for="search">Search student</label>
                    <input type="text"
                           id="search"
                           name="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Name, student ID or roll no.">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="exam_id">Exam</label>
                    <select id="exam_id" name="exam_id" class="form-control">
                        <option value="">All Exams</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}"
                                @selected((string) request('exam_id') === (string) $exam->id)>
                                {{ $exam->exam_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label for="academic_year">Academic Year</label>
                    <select id="academic_year" name="academic_year" class="form-control">
                        <option value="">All Years</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year }}"
                                @selected(request('academic_year') == $year)>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label for="class">Class</label>
                    <select id="class" name="class" class="form-control">
                        <option value="">All Classes</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class }}"
                                @selected(request('class') == $class)>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3 d-flex">
                    <button type="submit" class="btn btn-primary mr-2">
                        Filter
                    </button>

                    <a href="{{ route('admin.reports.results.index') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Results table --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Student Results</strong>
            <span class="text-muted">
                {{ $results->total() }} records
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Roll No.</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Exam</th>
                            <th>Academic Year</th>
                            <th>Subject</th>
                            <th>Marks Obtained</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td>{{ $results->firstItem() + $loop->index }}</td>
                                <td>{{ $result->student_code }}</td>
                                <td>{{ $result->roll_number }}</td>
                                <td>
                                    {{ trim(
                                        $result->first_name . ' ' .
                                        ($result->middle_name ?? '') . ' ' .
                                        $result->last_name
                                    ) }}
                                </td>
                                <td>{{ $result->class }}</td>
                                <td>{{ $result->section }}</td>
                                <td>{{ $result->exam_name }}</td>
                                <td>{{ $result->academic_year }}</td>
                                <td>
                                    {{ $subjectNames[$result->exam_subject_id]->subject_name
    ?? 'Subject #' . $result->exam_subject_id }}
                                </td>
                                <td>
                                    {{ $result->marks_obtained !== null
                                        ? number_format((float) $result->marks_obtained, 2)
                                        : '—' }}
                                </td>
                                <td>{{ $result->grade ?? '—' }}</td>
                                <td>{{ $result->remarks ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-5">
                                    <h5>No result records found</h5>
                                    <p class="text-muted mb-0">
                                        Results will appear here once marks are saved.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($results->hasPages())
            <div class="card-footer">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>

<style>
@media print {
    .sidebar, .main-sidebar, .navbar, .main-header,
    .card:first-of-type button, form, .card-footer,
    .btn, footer {
        display: none !important;
    }

    .content-wrapper, .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    table {
        font-size: 10px;
    }
}
</style>
@endsection
