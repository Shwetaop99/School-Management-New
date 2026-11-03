
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Create School Administrator</h3>
                    <p class="text-muted mb-0">
                        Create login credentials for a registered school.
                    </p>
                </div>

                <a href="{{ route('super-admin.schools.index') }}"
                   class="btn btn-outline-secondary">
                    Back to Schools
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please correct the following:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Administrator Details</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST"
                          action="{{ route('super-admin.schools.admin.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="school_id" class="form-label">
                                School <span class="text-danger">*</span>
                            </label>

                            <select name="school_id"
                                    id="school_id"
                                    class="form-select @error('school_id') is-invalid @enderror"
                                    required>
                                <option value="">Select a school</option>

                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}"
                                        @selected(old('school_id') == $school->id)>
                                        {{ $school->name }}
                                        ({{ $school->school_code }})
                                    </option>
                                @endforeach
                            </select>

                            @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($schools->isEmpty())
                                <small class="text-danger">
                                    No active schools are available. Register or activate a school first.
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Administrator Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   maxlength="255"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="login_id" class="form-label">
                                Login ID <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="login_id"
                                   id="login_id"
                                   class="form-control @error('login_id') is-invalid @enderror"
                                   value="{{ old('login_id') }}"
                                   maxlength="255"
                                   pattern="[A-Za-z0-9_-]+"
                                   autocomplete="username"
                                   required>

                            <small class="text-muted">
                                Use letters, numbers, underscores, or hyphens.
                            </small>

                            @error('login_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   maxlength="255"
                                   autocomplete="email"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Initial Password <span class="text-danger">*</span>
                            </label>

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>

                            <small class="text-muted">
                                Use at least 8 characters.
                            </small>

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password <span class="text-danger">*</span>
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="alert alert-light border">
                            <strong>Assigned role:</strong> Admin<br>
                            <strong>Initial status:</strong> Active
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="btn btn-primary"
                                    @disabled($schools->isEmpty())>
                                Create Administrator
                            </button>

                            <a href="{{ route('super-admin.schools.index') }}"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
