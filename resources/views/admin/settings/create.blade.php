@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-building-fill text-primary me-2"></i>

                Add New School

            </h3>

            <p class="text-muted mb-0">
                Create a school profile for this school.
            </p>

        </div>


        <a href="{{ route('admin.settings.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to School Profiles

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Please correct the following errors:

            </div>


            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         CREATE SCHOOL FORM
    ========================================================== --}}

    <form
        action="{{ route('admin.settings.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="schoolForm"
    >

        @csrf


        {{-- =====================================================
             COMMON SCHOOL FORM
        ====================================================== --}}

        @include('admin.settings._form', [

            'school' => null,

            'submitRoute' => 'admin.settings.store',

            'submitMethod' => 'POST',

            'buttonText' => 'Save School'

        ])


    </form>

</div>

@endsection