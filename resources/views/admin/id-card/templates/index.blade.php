```blade
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-card-image text-primary me-2"></i>
                ID Card Templates
            </h3>

            <p class="text-muted mb-0">
                Manage the ID card designs uploaded by the administrator.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.id-card.index') }}"
               class="btn btn-light border">
                <i class="bi bi-person-vcard me-1"></i>
                ID Cards
            </a>

            <a href="{{ route('admin.id-card.templates.create') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Template
            </a>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-collection"></i>
                </div>

                <div>
                    <small class="text-muted">
                        Total Templates
                    </small>

                    <h4 class="fw-bold mb-0">
                        {{ $totalTemplates }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <small class="text-muted">
                        Active Templates
                    </small>

                    <h4 class="fw-bold mb-0">
                        {{ $activeTemplates }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-secondary-subtle text-secondary">
                    <i class="bi bi-pause-circle"></i>
                </div>

                <div>
                    <small class="text-muted">
                        Inactive Templates
                    </small>

                    <h4 class="fw-bold mb-0">
                        {{ $inactiveTemplates }}
                    </h4>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         SEARCH
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.id-card.templates.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-7">

                        <label class="form-label fw-semibold">
                            Search Template
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Search by template name, slug or academic year">

                        </div>

                    </div>


                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i>
                        </button>

                        <a href="{{ route('admin.id-card.templates.index') }}"
                           class="btn btn-light border">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         TEMPLATE GRID
    ========================================================== --}}

    @if($templates->count())

        <div class="row g-4">

            @foreach($templates as $template)

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="template-card h-100">

                        {{-- Image --}}
                        <div class="template-image-wrapper">

                            @if($template->template_image)

                                <img
                                    src="{{ asset('storage/' . $template->template_image) }}"
                                    alt="{{ $template->name }}"
                                    class="template-image"
                                    loading="lazy"
                                >

                            @else

                                <div class="template-no-image">

                                    <i class="bi bi-card-image"></i>

                                    <span>
                                        No Template Image
                                    </span>

                                </div>

                            @endif


                            {{-- Status --}}
                            <div class="template-status">

                                @if($template->status === 'active')

                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Details --}}
                        <div class="p-3">

                            <h5 class="fw-bold mb-1">
                                {{ $template->name }}
                            </h5>

                            <div class="small text-muted mb-3">
                                <code>{{ $template->slug }}</code>
                            </div>


                            <div class="template-info">

                                <div>
                                    <span>
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Academic Year
                                    </span>

                                    <strong>
                                        {{ $template->academic_year ?: 'Not specified' }}
                                    </strong>
                                </div>

                                <div>
                                    <span>
                                        <i class="bi bi-image me-1"></i>
                                        Image
                                    </span>

                                    <strong>
                                        {{ $template->template_image ? 'Uploaded' : 'Missing' }}
                                    </strong>
                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="d-flex gap-2 mt-3">

                                <a href="{{ route('admin.id-card.templates.edit', $template) }}"
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>


                                <form method="POST"
                                      action="{{ route('admin.id-card.templates.toggle-status', $template) }}"
                                      class="flex-fill">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-success w-100">

                                        @if($template->status === 'active')
                                            <i class="bi bi-pause me-1"></i>
                                            Disable
                                        @else
                                            <i class="bi bi-check2 me-1"></i>
                                            Activate
                                        @endif

                                    </button>

                                </form>


                                <form method="POST"
                                      action="{{ route('admin.id-card.templates.destroy', $template) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this template?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Pagination --}}
        <div class="mt-4">
            {{ $templates->links() }}
        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon">
                <i class="bi bi-card-image"></i>
            </div>

            <h5 class="fw-bold mt-3">
                No ID Card Templates Found
            </h5>

            <p class="text-muted">
                Upload your first ID card template to start generating cards.
            </p>

            <a href="{{ route('admin.id-card.templates.create') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Template
            </a>

        </div>

    @endif

</div>


<style>
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 18px rgba(0,0,0,.05);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .template-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #edf0f4;
        box-shadow: 0 5px 20px rgba(0,0,0,.05);
        transition: .2s ease;
    }

    .template-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,.10);
    }

    .template-image-wrapper {
        height: 240px;
        background: #f4f6f9;
        position: relative;
        overflow: hidden;
    }

    .template-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .template-no-image {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9aa2ad;
        gap: 8px;
    }

    .template-no-image i {
        font-size: 45px;
    }

    .template-status {
        position: absolute;
        top: 12px;
        right: 12px;
    }

    .template-info {
        background: #f8f9fb;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .template-info > div {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 4px 0;
        font-size: 12px;
    }

    .template-info span {
        color: #727b87;
    }

    .template-info strong {
        color: #343a40;
        text-align: right;
    }

    .empty-state {
        background: #fff;
        border-radius: 16px;
        padding: 70px 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        border-radius: 20px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: auto;
    }
</style>

@endsection
