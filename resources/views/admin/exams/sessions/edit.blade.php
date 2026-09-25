
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Exam Session
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
                • {{ $exam->academic_year }}
            </div>
        </div>

        <a href="{{ route('admin.exam-sessions.index', $exam) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Sessions

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <strong>
                Session Information
            </strong>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.exam-sessions.update',
                      [$exam, $session]
                  ) }}">

                @csrf
                @method('PUT')


                <div class="row g-4">


                    {{-- Session Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Session Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="session_name"
                               class="form-control"
                               value="{{ old(
                                   'session_name',
                                   $session->session_name
                               ) }}"
                               required>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="1"
                                {{ old(
                                    'status',
                                    $session->status ? '1' : '0'
                                ) == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old(
                                    'status',
                                    $session->status ? '1' : '0'
                                ) == '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Start Time --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Start Time
                            <span class="text-danger">*</span>
                        </label>

                        <input type="time"
                               name="start_time"
                               class="form-control"
                               value="{{ old(
                                   'start_time',
                                   \Carbon\Carbon::parse(
                                       $session->start_time
                                   )->format('H:i')
                               ) }}"
                               required>

                    </div>


                    {{-- End Time --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            End Time
                            <span class="text-danger">*</span>
                        </label>

                        <input type="time"
                               name="end_time"
                               class="form-control"
                               value="{{ old(
                                   'end_time',
                                   \Carbon\Carbon::parse(
                                       $session->end_time
                                   )->format('H:i')
                               ) }}"
                               required>

                    </div>


                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.exam-sessions.index',
                        $exam
                    ) }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Session

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
