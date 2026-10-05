
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Exam Holiday
            </h4>

            <div class="text-muted">
                {{ $exam->exam_name }}
                • {{ $exam->academic_year }}
            </div>
        </div>

        <a href="{{ route('admin.exam-holidays.index', $exam) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Holidays

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
                Holiday Information
            </strong>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.exam-holidays.update',
                      [$exam, $holiday]
                  ) }}">

                @csrf
                @method('PUT')


                <div class="row g-4">

                    {{-- Holiday Date --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Holiday Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="holiday_date"
                               class="form-control"
                               value="{{ old(
                                   'holiday_date',
                                   $holiday->holiday_date->format('Y-m-d')
                               ) }}"
                               min="{{ $exam->start_date->format('Y-m-d') }}"
                               max="{{ $exam->end_date
                                    ? $exam->end_date->format('Y-m-d')
                                    : $exam->start_date->format('Y-m-d') }}"
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
                                    $holiday->status ? '1' : '0'
                                ) == '1'
                                    ? 'selected'
                                    : '' }}>
                                Active - Non-working
                            </option>

                            <option value="0"
                                {{ old(
                                    'status',
                                    $holiday->status ? '1' : '0'
                                ) == '0'
                                    ? 'selected'
                                    : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Reason --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Reason
                        </label>

                        <input type="text"
                               name="reason"
                               class="form-control"
                               value="{{ old(
                                   'reason',
                                   $holiday->reason
                               ) }}"
                               placeholder="Example: Sunday, Diwali Holiday, Public Holiday">

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.exam-holidays.index',
                        $exam
                    ) }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Holiday

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
