@extends('layouts.app')

@section('title', 'Select Student - Bonafide Certificate')

@section('content')

<style>
    .bonafide-page {
        padding: 20px;
    }

    .page-header {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #1677f0;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .back-btn:hover {
        color: #0d5fc7;
    }

    .page-title {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #1f2937;
    }

    .page-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .student-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 14px;
    }

    .student-card {
        background: #fff;
        border: 1px solid #e5eaf0;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: 0.2s ease;
    }

    .student-card:hover {
        border-color: #1677f0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .student-photo,
    .student-placeholder {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        object-fit: cover;
    }

    .student-placeholder {
        background: #eef6ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .student-info {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #1f2937;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .student-id {
        color: #6b7280;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .student-details {
        color: #6b7280;
        font-size: 12px;
    }

    .select-btn {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 7px;
        background: #1677f0;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .select-btn:hover {
        background: #0d5fc7;
        color: #fff;
    }

    .empty-state {
        background: #fff;
        border: 1px solid #e5eaf0;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 36px;
        margin-bottom: 10px;
    }

    @media (max-width: 576px) {
        .bonafide-page {
            padding: 12px;
        }

        .student-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="bonafide-page">

    <div class="page-header">

        <a
            href="{{ route('admin.bonafide.classes') }}"
            class="back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            Back to Classes
        </a>

        <h1 class="page-title">
            <i class="bi bi-people-fill me-2"></i>
            {{ $class }}
        </h1>

        <p class="page-subtitle">
            Select a student to generate a bonafide certificate.
        </p>

    </div>


    @if($students->count())

        <div class="student-grid">

            @foreach($students as $student)

                @php
                    $studentName = trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    );
                @endphp

                <div class="student-card">

                    {{-- Student Photo --}}
                    @if(!empty($student->profile_image))

                        <img
                            src="{{ $student->profile_image }}"
                            alt="{{ $studentName }}"
                            class="student-photo"
                        >

                    @else

                        <div class="student-placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>

                    @endif


                    {{-- Student Information --}}
                    <div class="student-info">

                        <div class="student-name">
                            {{ $studentName ?: 'Student' }}
                        </div>

                        <div class="student-id">
                            Student ID:
                            {{ $student->student_id ?? '-' }}
                        </div>

                        <div class="student-details">

                            @if(!empty($student->section))
                                Section {{ $student->section }}
                            @endif

                            @if(!empty($student->roll_number))
                                @if(!empty($student->section))
                                    &nbsp; | &nbsp;
                                @endif

                                Roll No. {{ $student->roll_number }}
                            @endif

                        </div>

                    </div>


                    {{-- Select Student --}}
                    <a
                        href="{{ route('admin.bonafide.create', ['student_id' => $student->id]) }}"
                        class="select-btn"
                        title="Generate Bonafide Certificate"
                    >
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty-state">

            <i class="bi bi-person-x"></i>

            <h5 class="mt-2">
                No Students Found
            </h5>

            <p class="mb-0">
                There are no active students in {{ $class }}.
            </p>

        </div>

    @endif

</div>

@endsection