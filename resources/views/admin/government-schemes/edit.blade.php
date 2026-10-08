@extends('layouts.app')

@section('title', 'Edit Government Scheme')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Government Scheme</h4>
            <p class="text-muted mb-0">
                Update government scheme information.
            </p>
        </div>

        <a href="{{ route('admin.government-schemes.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>
                Scheme Details
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.government-schemes.update', $governmentScheme) }}">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Scheme Name --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Scheme Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="scheme_name"
                               value="{{ old('scheme_name', $governmentScheme->scheme_name) }}"
                               class="form-control @error('scheme_name') is-invalid @enderror"
                               required>

                        @error('scheme_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Scheme Code --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Scheme Code
                        </label>

                        <input type="text"
                               name="scheme_code"
                               value="{{ old('scheme_code', $governmentScheme->scheme_code) }}"
                               class="form-control @error('scheme_code') is-invalid @enderror">

                        @error('scheme_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Government --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Government
                        </label>

                        <input type="text"
                               name="government"
                               value="{{ old('government', $governmentScheme->government) }}"
                               class="form-control @error('government') is-invalid @enderror">

                        @error('government')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Department
                        </label>

                        <input type="text"
                               name="department"
                               value="{{ old('department', $governmentScheme->department) }}"
                               class="form-control @error('department') is-invalid @enderror">

                        @error('department')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="academic_year"
                               value="{{ old('academic_year', $governmentScheme->academic_year) }}"
                               class="form-control @error('academic_year') is-invalid @enderror"
                               required>

                        @error('academic_year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="active"
                                {{ old('status', $governmentScheme->status) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $governmentScheme->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Start Date
                        </label>

                        <input type="date"
                               name="start_date"
                               value="{{ old('start_date', optional($governmentScheme->start_date)->format('Y-m-d')) }}"
                               class="form-control @error('start_date') is-invalid @enderror">

                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            End Date
                        </label>

                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date', optional($governmentScheme->end_date)->format('Y-m-d')) }}"
                               class="form-control @error('end_date') is-invalid @enderror">

                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $governmentScheme->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.government-schemes.index') }}"
                       class="btn btn-light border">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Scheme
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection