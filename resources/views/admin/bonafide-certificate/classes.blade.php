@extends('layouts.app')

@section('title', 'Generate Bonafide Certificate')

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

    .class-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
    }

    .class-card {
        display: block;
        text-decoration: none;
        background: #fff;
        border: 1px solid #e5eaf0;
        border-radius: 10px;
        padding: 18px;
        transition: 0.2s ease;
    }

    .class-card:hover {
        transform: translateY(-2px);
        border-color: #1677f0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
    }

    .class-icon {
        width: 42px;
        height: 42px;
        border-radius: 9px;
        background: #eef6ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .class-name {
        color: #1f2937;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .class-action {
        color: #6b7280;
        font-size: 13px;
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

        .class-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="bonafide-page">

    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-file-earmark-text me-2"></i>
            Generate Bonafide Certificate
        </h1>

        <p class="page-subtitle">
            Select a class to view students and generate a bonafide certificate.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($classes->count())

        <div class="class-grid">

            @foreach($classes as $item)

                <a
                    href="{{ route('admin.bonafide.students', ['class' => $item->class]) }}"
                    class="class-card"
                >

                    <div class="class-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="class-name">
                        {{ $item->class }}
                    </div>

                    <div class="class-action">
                        Click to view students
                        <i class="bi bi-arrow-right ms-1"></i>
                    </div>

                </a>

            @endforeach

        </div>

    @else

        <div class="empty-state">

            <i class="bi bi-people"></i>

            <h5 class="mt-2">
                No Classes Found
            </h5>

            <p class="mb-0">
                There are no active students with a class assigned.
            </p>

        </div>

    @endif

</div>

@endsection