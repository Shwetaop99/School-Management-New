@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">School Details</h3>
            <p class="text-muted mb-0">
                View registered school information.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('super-admin.schools.index') }}"
               class="btn btn-outline-secondary">
                Back to Schools
            </a>

            <a href="{{ route('super-admin.schools.edit', $school->id) }}"
               class="btn btn-primary">
                Edit School
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">{{ $school->name }}</h5>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="text-muted small">School Name</label>
                    <h6>{{ $school->name }}</h6>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">School ID</label>
                    <h6>#{{ $school->id }}</h6>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">School Code</label>
                    <h6>{{ $school->school_code }}</h6>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">Status</label>
                    <h6>
                        <span class="badge {{ strtolower($school->status) === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($school->status) }}
                        </span>
                    </h6>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">Registered On</label>
                    <h6>{{ $school->created_at?->format('d M Y') ?? 'N/A' }}</h6>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small">Last Updated</label>
                    <h6>{{ $school->updated_at?->format('d M Y') ?? 'N/A' }}</h6>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
