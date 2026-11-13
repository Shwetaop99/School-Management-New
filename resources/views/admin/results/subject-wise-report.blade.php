
@extends('layouts.app')

@section('title', 'Subject-wise Marks Report')

@section('content')
<div class="container-fluid py-4">

    <style>
        .report-card {
            border: 1px solid #e5eaf1;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .report-heading {
            background: #f7faff;
            border-bottom: 1px solid #e5eaf1;
            padding: 20px;
        }

        .report-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .report-table {
            min-width: 1000px;
            margin-bottom: 0;
            font-size: 13px;
            white-space: nowrap;
        }

        .report-table th {
            background: #f1f6fd;
            color: #24364b;
            text-align: center;
            vertical-align: middle;
            padding: 12px 10px;
            border: 1px solid #e0e7f0;
        }

        .report-table td {
            text-align: center;
            vertical-align: middle;
            padding: 11px 10px;
            border: 1px solid #e8edf4;
        }

        .report-table .student-info {
            text-align: left;
        }

        .report-table tbody tr:hover {
            background: #f8fbff;
        }

        .report-table tfoot td {
            background: #f1f6fd;
            font-weight: 700;
        }

        .filter-label {
            font-weight: 600;
            color: #344054;
            margin-bottom: 7px;
        }

        .report-summary {
            border: 1px solid #e5eaf1;
            border-radius: 10px;
            padding: 15px;
            height: 100%;
        }

        @media print {
            .no-print,
            .sidebar,
            header,
            nav {
                display: none !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .report-card {
                border: none;
            }

            .report-table {
                font-size: 9px;
            }

            .report-table th,
            .report-table td {
                padding: 5px 4px;
            }
        }
    </style>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Subject-wise Marks Report</h4>
            <div class="text-muted">
                View Internal and Theory marks for all students in a class.
            </div>
        </div>

        @if($selectedExam && $selectedClass && $subjects->isNotEmpty())
            <button
                type="button"
                class="btn btn-outline-primary no-print"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print Report
            </button>
        @endif
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4 no-print">
        <div class="card-body p-4">
            <form
                method="GET"
                action="{{ route('admin.results.subject-wise-report') }}"
            >
                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label for="exam_id" class="filter-label">
                            Examination
                        </label>

                        <select
                            name="exam_id"
                            id="exam_id"
                            class="form-select"
                            required
                        >
                            <option value="">Select Examination</option>

                            @foreach($exams as $examOption)
                                <option
                                    value="{{ $examOption->id }}"
                                    @selected(
                                        (string) request('exam_id') ===
                                        (string) $examOption->id
                                    )
                                >
                                    {{ $examOption->exam_name }}
                                    ({{ $examOption->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="class_id" class="filter-label">
                            Class / Section
                        </label>

                        <select
                            name="class_id"
                            id="class_id"
                            class="form-select"
                            required
                            {{ !$selectedExam ? 'disabled' : '' }}
                        >
                            <option value="">Select Class</option>

                            @foreach($examClasses as $examClassOption)
                                <option
                                    value="{{ $examClassOption->class_id }}"
                                    @selected(
                                        (string) request('class_id') ===
                                        (string) $examClassOption->class_id
                                    )
                                >
                                    {{ $examClassOption->schoolClass->class_name }}
                                    @if($examClassOption->schoolClass->section)
                                        - Section
                                        {{ $examClassOption->schoolClass->section }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-search me-1"></i>
                            View
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Error / empty states --}}
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if($selectedExam && $selectedClass)

        <div class="report-card mb-4">
            <div class="report-heading">
                <h5 class="fw-bold mb-2">
                    {{ $selectedExam->exam_name }}
                </h5>

                <div class="text-muted">
                    <span class="me-3">
                        <strong>Academic Year:</strong>
                        {{ $selectedExam->academic_year }}
                    </span>

                    <span>
                        <strong>Class:</strong>
                        {{ $selectedClass->class_name }}
                        @if($selectedClass->section)
                            - {{ $selectedClass->section }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="card-body border-bottom">
                <div class="row g-3">

                    <div class="col-sm-4">
                        <div class="report-summary">
                            <div class="text-muted small">
                                Active Students
                            </div>
                            <div class="fs-4 fw-bold">
                                {{ $students->count() }}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="report-summary">
                            <div class="text-muted small">
                                Subjects
                            </div>
                            <div class="fs-4 fw-bold">
                                {{ $subjects->count() }}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="report-summary">
                            <div class="text-muted small">
                                Marks Components
                            </div>
                            <div class="fs-4 fw-bold">
                                Internal + Theory
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if($subjects->isEmpty())
                <div class="alert alert-warning m-3">
                    No active subjects are assigned to this class
                    for the selected examination.
                </div>
            @elseif($students->isEmpty())
                <div class="alert alert-warning m-3">
                    No active students were found for this class,
                    section, and academic year.
                </div>
            @else

                @php
                    $overallMaximum = $subjects->sum(
                        fn ($subject) => (float) $subject->maximum_marks
                    );
                @endphp

                <div class="report-table-wrapper">
                    <table class="table report-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr. No.</th>
                                <th rowspan="2">Student ID</th>
                                <th rowspan="2">Roll No.</th>
                                <th rowspan="2" class="student-info">
                                    Student Name
                                </th>

                                @foreach($subjects as $examSubject)
                                    <th colspan="3">
                                        {{ $examSubject->subject->subject_name }}
                                        <div class="small fw-normal">
                                            Out of
                                            {{ $examSubject->maximum_marks }}
                                        </div>
                                    </th>
                                @endforeach

                                <th rowspan="2">Obtained</th>
                                <th rowspan="2">Maximum</th>
                                <th rowspan="2">Percentage</th>
                            </tr>

                            <tr>
                                @foreach($subjects as $examSubject)
                                    <th>Internal</th>
                                    <th>Theory</th>
                                    <th>Total</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $index => $student)
                                @php
                                    $studentTotal = 0;
                                    $studentMaximum = 0;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $student->student_id ?? $student->id }}
                                    </td>

                                    <td>
                                        {{ $student->roll_number ?: '-' }}
                                    </td>

                                    <td class="student-info">
                                        {{ collect([
                                            $student->first_name,
                                            $student->middle_name,
                                            $student->last_name
                                        ])->filter()->implode(' ') }}
                                    </td>

                                    @foreach($subjects as $examSubject)
                                        @php
                                            $markKey =
                                                $student->id . '-' .
                                                $examSubject->subject_id;

                                            $mark = $marks->get($markKey);

                                            $status = $mark->status ?? null;

                                            $internal = (float) (
                                                $mark->internal_marks ?? 0
                                            );

                                            $theory = (float) (
                                                $mark->theory_marks ?? 0
                                            );

                                            // Never include practical marks.
                                            $subjectTotal = $internal + $theory;

                                            $subjectMaximum = (float)
                                                $examSubject->maximum_marks;

                                            if ($status === 'present') {
                                                $studentTotal += $subjectTotal;
                                                $studentMaximum += $subjectMaximum;
                                            } elseif ($status === 'absent') {
                                                $studentMaximum += $subjectMaximum;
                                            }
                                        @endphp

                                        @if(!$mark)
                                            <td colspan="3" class="text-muted">
                                                —
                                            </td>
                                        @elseif($status === 'absent')
                                            <td colspan="3">
                                                <span class="badge bg-danger">
                                                    Absent
                                                </span>
                                            </td>
                                        @elseif($status === 'na')
                                            <td colspan="3">
                                                <span class="badge bg-secondary">
                                                    N/A
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                {{ rtrim(rtrim(number_format($internal, 2, '.', ''), '0'), '.') }}
                                            </td>

                                            <td>
                                                {{ rtrim(rtrim(number_format($theory, 2, '.', ''), '0'), '.') }}
                                            </td>

                                            <td class="fw-semibold">
                                                {{ rtrim(rtrim(number_format($subjectTotal, 2, '.', ''), '0'), '.') }}
                                            </td>
                                        @endif
                                    @endforeach

                                    @php
                                        $percentage = $studentMaximum > 0
                                            ? ($studentTotal / $studentMaximum) * 100
                                            : 0;
                                    @endphp

                                    <td class="fw-bold">
                                        {{ rtrim(rtrim(number_format($studentTotal, 2, '.', ''), '0'), '.') }}
                                    </td>

                                    <td>
                                        {{ rtrim(rtrim(number_format($studentMaximum, 2, '.', ''), '0'), '.') }}
                                    </td>

                                    <td>
                                        {{ number_format($percentage, 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-start">
                                    Subject Maximum Marks
                                </td>

                                @foreach($subjects as $examSubject)
                                    <td colspan="3">
                                        {{ $examSubject->maximum_marks }}
                                    </td>
                                @endforeach

                                <td colspan="2">
                                    Maximum per student:
                                    {{ $overallMaximum }}
                                </td>

                                <td>—</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="p-3 text-muted small">
                    <strong>Note:</strong>
                    Subject totals are calculated as Internal + Theory.
                    Practical marks are excluded. A dash means marks
                    have not been entered.
                </div>
            @endif
        </div>

    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-table fs-1 text-primary"></i>

                <h5 class="mt-3">Select an Examination and Class</h5>

                <p class="text-muted mb-0">
                    Choose the examination and class above, then click
                    View to display subject-wise marks for all students.
                </p>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');

    if (examSelect && classSelect) {
        examSelect.addEventListener('change', function () {
            // Reset the class when the examination changes.
            classSelect.value = '';
            classSelect.disabled = !examSelect.value;

            // Load the classes assigned to the new examination.
            const url = new URL(window.location.href);

            url.searchParams.set('exam_id', examSelect.value);
            url.searchParams.delete('class_id');

            if (examSelect.value) {
                window.location.href = url.toString();
            } else {
                window.location.href =
                    '{{ route('admin.results.subject-wise-report') }}';
            }
        });
    }
});
</script>
@endsection