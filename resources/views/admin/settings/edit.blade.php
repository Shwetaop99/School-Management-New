@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit School Profile
            </h3>

            <p class="text-muted mb-0">
                Update your school's information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.settings.show', $schoolSetting->id) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-eye me-1"></i>
                View Profile

            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <form
        action="{{ route('admin.settings.update', $schoolSetting->id) }}"
        method="POST"
        enctype="multipart/form-data"
        id="schoolForm"
    >

        @csrf
        @method('PUT')


        @include('admin.settings._form', [
            'school' => $schoolSetting,
            'submitRoute' => 'admin.settings.update',
            'submitMethod' => 'PUT',
            'buttonText' => 'Update School Profile'
        ])

    </form>

</div>

@endsection