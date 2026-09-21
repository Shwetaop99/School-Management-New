
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a href="{{ route(
                    'admin.exam-classes.index',
                    $exam->id
                ) }}"
                   class="text-decoration-none text-muted">

                    <i class="bi bi-arrow-left"></i>

                    Classes

                </a>

                <span class="text-muted">/</span>

                <span class="text-muted">
                    {{ $examClass->class_name }}
                </span>

            </div>


            <h3 class="fw-bold mb-1">

                <i class="bi bi-diagram-3-fill text-primary me-2"></i>

                Manage Sections

            </h3>


            <p class="text-muted mb-0">

                {{ $exam->exam_name }}

                &nbsp;•&nbsp;

                @if(in_array($examClass->class_name, ['Nursery', 'LKG', 'UKG']))
                    {{ $examClass->class_name }}
                @else
                    Class {{ $examClass->class_name }}
                @endif

            </p>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Classes --}}
            <a href="{{ route(
                'admin.exam-classes.index',
                $exam->id
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Classes

            </a>


            {{-- ADD ALL SECTIONS --}}
            <form method="POST"
                  action="{{ route(
                      'admin.exam-class-sections.add-all',
                      [
                          'exam' => $exam->id,
                          'examClass' => $examClass->id
                      ]
                  ) }}"
                  onsubmit="return confirm(
                      'Add all sections A to F to this class? Existing sections will be skipped.'
                  );">

                @csrf

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-plus-square me-1"></i>

                    Add All Sections

                </button>

            </form>


            {{-- ADD INDIVIDUAL SECTION --}}
            <a href="{{ route(
                'admin.exam-class-sections.create',
                [
                    'exam' => $exam->id,
                    'examClass' => $examClass->id
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Section

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <strong>

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Please fix the following:

            </strong>


            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>


            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        CLASS INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Examination
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->exam_name }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $exam->academic_year }}
                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Class
                    </div>

                    <div class="fw-semibold">

                        @if(in_array($examClass->class_name, ['Nursery', 'LKG', 'UKG']))
                            {{ $examClass->class_name }}
                        @else
                            Class {{ $examClass->class_name }}
                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Total Sections
                    </div>

                    <div class="fw-semibold">

                        <span class="badge bg-primary fs-6">
                            {{ $sections->count() }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        QUICK SETUP INFORMATION
    ========================================================== --}}

    <div class="alert alert-info border-0 shadow-sm mb-4">

        <div class="d-flex align-items-start">

            <i class="bi bi-info-circle-fill fs-5 me-2"></i>

            <div>

                <strong>Quick Setup</strong>

                <div class="small mt-1">

                    Click
                    <strong>Add All Sections</strong>
                    to create sections
                    <strong>A, B, C, D, E and F</strong>
                    for this class at once.

                    Existing sections will automatically be skipped.

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECTIONS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-list-ul text-primary me-2"></i>

                        Sections

                    </h5>

                    <small class="text-muted">

                        Sections assigned to

                        @if(in_array($examClass->class_name, ['Nursery', 'LKG', 'UKG']))
                            {{ $examClass->class_name }}
                        @else
                            Class {{ $examClass->class_name }}
                        @endif

                    </small>

                </div>


                <span class="badge bg-primary">

                    {{ $sections->count() }}

                    {{ $sections->count() == 1 ? 'Section' : 'Sections' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($sections->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4"
                                    style="width: 80px;">

                                    #

                                </th>

                                <th>
                                    Section
                                </th>

                                <th>
                                    Sort Order
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end px-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($sections as $index => $section)

                                <tr>

                                    {{-- Number --}}
                                    <td class="px-4">

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Section --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="rounded-circle bg-primary bg-opacity-10
                                                        text-primary d-flex align-items-center
                                                        justify-content-center me-3"
                                                 style="width:40px;height:40px;">

                                                <i class="bi bi-grid-3x3-gap-fill"></i>

                                            </div>


                                            <div>

                                                <div class="fw-bold">

                                                    Section
                                                    {{ $section->section_name }}

                                                </div>

                                                <small class="text-muted">

                                                    @if(in_array($examClass->class_name, ['Nursery', 'LKG', 'UKG']))
                                                        {{ $examClass->class_name }}
                                                    @else
                                                        Class {{ $examClass->class_name }}
                                                    @endif

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Sort Order --}}
                                    <td>

                                        {{ $section->sort_order }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($section->status === 'active')

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                <i class="bi bi-dash-circle me-1"></i>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                         ACTION DROPDOWN
                                    ================================================== --}}

                                    <td class="text-end px-4">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-light border"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                                {{-- Edit --}}
                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'admin.exam-class-sections.edit',
                                                           [
                                                               'exam' => $exam->id,
                                                               'examClass' => $examClass->id,
                                                               'section' => $section->id
                                                           ]
                                                       ) }}">

                                                        <i class="bi bi-pencil-square text-primary me-2"></i>

                                                        Edit

                                                    </a>

                                                </li>


                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form method="POST"
                                                          action="{{ route(
                                                              'admin.exam-class-sections.destroy',
                                                              [
                                                                  'exam' => $exam->id,
                                                                  'examClass' => $examClass->id,
                                                                  'section' => $section->id
                                                              ]
                                                          ) }}"
                                                          onsubmit="return confirm(
                                                              'Are you sure you want to remove Section {{ $section->section_name }} from this class?'
                                                          );">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete

                                                        </button>

                                                    </form>

                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-diagram-3 text-muted"
                           style="font-size: 4rem;">
                        </i>

                    </div>


                    <h5 class="fw-bold">

                        No Sections Added

                    </h5>


                    <p class="text-muted mb-4">

                        No sections have been assigned to

                        @if(in_array($examClass->class_name, ['Nursery', 'LKG', 'UKG']))
                            {{ $examClass->class_name }}
                        @else
                            Class {{ $examClass->class_name }}
                        @endif

                        yet.

                    </p>


                    <div class="d-flex justify-content-center gap-2">

                        {{-- Add All Sections --}}
                        <form method="POST"
                              action="{{ route(
                                  'admin.exam-class-sections.add-all',
                                  [
                                      'exam' => $exam->id,
                                      'examClass' => $examClass->id
                                  ]
                              ) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-plus-square me-1"></i>

                                Add All Sections

                            </button>

                        </form>


                        {{-- Add First Section --}}
                        <a href="{{ route(
                            'admin.exam-class-sections.create',
                            [
                                'exam' => $exam->id,
                                'examClass' => $examClass->id
                            ]
                        ) }}"
                           class="btn btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>

                            Add First Section

                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
