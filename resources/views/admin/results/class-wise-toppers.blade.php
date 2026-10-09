```blade
@extends('layouts.app')

@section('title', 'Class-wise Toppers')

@section('content')

<style>
    .toppers-page {
        --primary: #1677f0;
        --border: #e5e7eb;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --soft-blue: #eef6ff;
        padding-bottom: 30px;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
    }

    .page-header p {
        margin: 5px 0 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .filter-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 14px;
    }

    .form-select {
        min-height: 42px;
        border-radius: 8px;
    }

    .btn-find {
        min-height: 42px;
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        padding: 0 22px;
    }

    .btn-find:hover {
        background: #0d5fc7;
        border-color: #0d5fc7;
        color: #fff;
    }

    .exam-heading {
        background: var(--soft-blue);
        border: 1px solid #d9eaff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .exam-heading h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
    }

    .exam-heading span {
        color: var(--text-muted);
        font-size: 14px;
    }

    .class-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 25px;
        overflow: hidden;
    }

    .class-card-header {
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .class-card-header h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .class-card-header .badge {
        background: var(--soft-blue);
        color: var(--primary);
        border: 1px solid #d9eaff;
        font-weight: 600;
        padding: 7px 10px;
    }

    .topper-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding: 12px;
}

.topper-card {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    position: relative;
    background: #fff;
}

.rank-number {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 7px;
    background: var(--soft-blue);
    color: var(--primary);
    font-weight: 700;
    font-size: 12px;
}

.student-photo,
.student-placeholder {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin: 0 auto 7px;
}

.student-photo {
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.student-placeholder {
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 16px;
}

.student-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2px;
}

.student-id {
    color: var(--text-muted);
    font-size: 10px;
    margin-bottom: 7px;
}

.percentage {
    font-size: 18px;
    font-weight: 800;
    color: var(--primary);
}

.grade {
    color: var(--text-muted);
    font-size: 10px;
    margin-top: 1px;
}

    .empty-card {
        background: #fff;
        border: 1px dashed #d1d5db;
        border-radius: 12px;
        padding: 45px 20px;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-card i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        color: #9ca3af;
    }

    @media (max-width: 991px) {
        .topper-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="toppers-page">

    {{-- Page Header --}}
    <div class="page-header">
        <h4>
            <i class="fas fa-trophy me-2"></i>
            Class-wise Toppers
        </h4>

        <p>
            View the top students from every class for the selected examination.
        </p>
    </div>


    {{-- Filters --}}
    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('admin.results.class-wise-toppers') }}"
        >

            <div class="row g-3 align-items-end">

                {{-- Academic Year --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Academic Year
                    </label>

                    <select
                        name="academic_year"
                        class="form-select"
                    >

                        <option value="">
                            Select Academic Year
                        </option>

                        @foreach($academicYears as $year)

                            <option
                                value="{{ $year }}"
                                {{ request('academic_year') == $year ? 'selected' : '' }}
                            >
                                {{ $year }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Examination --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Examination
                    </label>

                    <select
                        name="exam_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Examination
                        </option>

                        @foreach($exams as $exam)

                            <option
                                value="{{ $exam->id }}"
                                {{ request('exam_id') == $exam->id ? 'selected' : '' }}
                            >
                                {{ $exam->exam_name }}
                                @if($exam->academic_year)
                                    ({{ $exam->academic_year }})
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Find Button --}}
                <div class="col-md-4">

                    <button
                        type="submit"
                        class="btn btn-find w-100"
                    >
                        <i class="fas fa-search me-2"></i>
                        Find All Class Toppers
                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- Results --}}
    @if($selectedExam)

        {{-- Exam Information --}}
        <div class="exam-heading">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h5>
                        {{ $selectedExam->exam_name }}
                    </h5>

                    <span>
                        Academic Year:
                        {{ $selectedExam->academic_year ?? '-' }}
                    </span>
                </div>

                <div>
                    <span class="badge">
                        {{ $classWiseToppers->count() }} Classes
                    </span>
                </div>

            </div>

        </div>


        @if($classWiseToppers->count())

            {{-- All Classes --}}
            @foreach($classWiseToppers as $classData)

                <div class="class-card">

                    {{-- Class Header --}}
                    <div class="class-card-header">

                        <h5>
                            <i class="fas fa-school me-2"></i>
                            {{ $classData['class_name'] }}
                        </h5>

                        <span class="badge">
                            Top 3
                        </span>

                    </div>


                    {{-- Topper Cards --}}
                    <div class="topper-grid">

                        @foreach($classData['toppers'] as $topper)

                            @php
                                $student = $topper->student;
                            @endphp

                            <div class="topper-card">

                                {{-- Rank --}}
                                <div class="rank-number">
                                    {{ $topper->topper_rank }}
                                </div>


                                {{-- Student Photo --}}
                                @if(!empty($student?->profile_image))

                                    <img
                                        src="{{ $student->profile_image }}"
                                        alt="{{ $student->first_name ?? 'Student' }}"
                                        class="student-photo"
                                    >

                                @else

                                    <div class="student-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>

                                @endif


                                {{-- Student Name --}}
                                <div class="student-name">

                                    {{ trim(
                                        ($student->first_name ?? '') . ' ' .
                                        ($student->middle_name ?? '') . ' ' .
                                        ($student->last_name ?? '')
                                    ) ?: 'Student' }}

                                </div>


                                {{-- Student ID --}}
                                <div class="student-id">

                                    Student ID:
                                    {{ $student->student_id ?? '-' }}

                                </div>


                                {{-- Percentage --}}
                                <div class="percentage">

                                    {{ number_format(
                                        (float) ($topper->percentage ?? 0),
                                        2
                                    ) }}%

                                </div>


                                {{-- Grade --}}
                                <div class="grade">

                                    Grade:
                                    {{ $topper->grade ?? '-' }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        @else

            <div class="empty-card">

                <i class="fas fa-trophy"></i>

                <strong>
                    No topper data found.
                </strong>

                <div class="mt-1">
                    There are no passed results available for this examination.
                </div>

            </div>

        @endif

    @else

        {{-- Initial Empty State --}}
        <div class="empty-card">

            <i class="fas fa-trophy"></i>

            <strong>
                Select an examination
            </strong>

            <div class="mt-1">
                Select an examination above to view the top 3 students from every class.
            </div>

        </div>

    @endif

</div>

@endsection
