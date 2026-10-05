@extends('layouts.app')

@section('title', 'Scholarship Rules | Admin')

@section('page-title', 'Scholarship Rules')

@section('content')

<style>
    .scholarship-rule-page {
        min-height: calc(100vh - 70px);
        padding: 26px;
        background:
            radial-gradient(circle at 5% 10%, rgba(23,105,209,.035), transparent 25%),
            radial-gradient(circle at 95% 20%, rgba(21,156,199,.035), transparent 25%),
            #f4f7fb;
    }

    .scholarship-rule-header {
        background: linear-gradient(135deg, #1769d1, #159cc7);
        border-radius: 18px;
        padding: 26px 30px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 12px 30px rgba(23,105,209,.15);
    }

    .scholarship-rule-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
    }

    .scholarship-rule-header p {
        margin: 7px 0 0;
        opacity: .9;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 18px;
        padding: 9px 15px;
        border-radius: 9px;
        background: rgba(255,255,255,.16);
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,.22);
    }

    .back-btn:hover {
        color: #fff;
        background: rgba(255,255,255,.24);
    }

    .rule-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        padding: 26px;
        box-shadow: 0 7px 22px rgba(32,56,85,.05);
        margin-bottom: 22px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #edf1f6;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf3ff;
        color: #1769d1;
        font-size: 17px;
    }

    .section-title h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: #182433;
    }

    .section-title span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        color: #7b8797;
        font-weight: 500;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #344054;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #dce3ec;
        border-radius: 9px;
        font-size: 13px;
        color: #263445;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23,105,209,.08);
    }

    textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .required {
        color: #e94d47;
    }

    .help-text {
        margin-top: 6px;
        font-size: 11px;
        color: #8a95a5;
    }

    .rule-preview {
        background: linear-gradient(135deg, #f7fbff, #eef9fc);
        border: 1px solid #dcecf5;
        border-radius: 14px;
        padding: 20px;
        margin-top: 5px;
    }

    .rule-preview h6 {
        margin: 0 0 15px;
        font-size: 14px;
        font-weight: 800;
        color: #203044;
    }

    .slab {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 11px 13px;
        background: #fff;
        border: 1px solid #e4ebf2;
        border-radius: 9px;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .slab:last-child {
        margin-bottom: 0;
    }

    .slab-percent {
        font-weight: 800;
        color: #1769d1;
    }

    .slab-scholarship {
        font-weight: 800;
        color: #079dbd;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #edf1f6;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 43px;
        padding: 0 18px;
        border-radius: 9px;
        border: 1px solid #dce3ec;
        background: #fff;
        color: #596579;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 0 20px;
        border-radius: 9px;
        border: 0;
        background: #1769d1;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #125bb8;
        color: #fff;
    }

    .alert {
        border-radius: 10px;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .scholarship-rule-page {
            padding: 16px;
        }

        .scholarship-rule-header {
            padding: 22px;
        }

        .rule-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="scholarship-rule-page">

    <div class="scholarship-rule-header">

        <h2>
            <i class="fas fa-award me-2"></i>
            Scholarship Rules
        </h2>

        <p>
            Define scholarship percentage based on the student's Annual Exam percentage.
        </p>

        <a
            href="{{ route('admin.scholarship.merit.concession.index') }}"
            class="back-btn"
        >
            <i class="fas fa-arrow-left"></i>
            Back to Scholarship
        </a>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('admin.scholarship.merit.concession.store') }}"
        method="POST"
    >

        @csrf

        <div class="rule-card">

            <div class="section-title">

                <div class="section-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div>
                    <h5>Scholarship Rule</h5>

                    <span>
                        Set the minimum Annual Exam percentage and scholarship percentage.
                    </span>
                </div>

            </div>

            <div class="row g-4">

                {{-- Academic Year --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Academic Year <span class="required">*</span>
                    </label>

                    <select
                        name="academic_year"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Academic Year
                        </option>

                        @forelse ($academicYears as $academicYear)

                            <option
                                value="{{ $academicYear }}"
                                {{ old('academic_year') == $academicYear ? 'selected' : '' }}
                            >
                                {{ $academicYear }}
                            </option>

                        @empty

                            <option value="">
                                No Annual Exam Academic Year Available
                            </option>

                        @endforelse

                    </select>

                    <div class="help-text">
                        Academic years are fetched from existing Annual Exam results.
                    </div>

                </div>

                {{-- Minimum Percentage --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Minimum Percentage <span class="required">*</span>
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="minimum_percentage"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old('minimum_percentage') }}"
                            placeholder="Example: 80"
                            required
                        >

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                    <div class="help-text">
                        Students with this Annual Exam percentage or above will qualify for this rule.
                    </div>

                </div>

                {{-- Scholarship Percentage --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Scholarship Percentage <span class="required">*</span>
                    </label>

                    <div class="input-group">

                        <input
                            type="number"
                            name="scholarship_percentage"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old('scholarship_percentage') }}"
                            placeholder="Example: 35"
                            required
                        >

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                    <div class="help-text">
                        Scholarship concession percentage assigned to eligible students.
                    </div>

                </div>

                {{-- Status --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>

                {{-- Description --}}
                <div class="col-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        placeholder="Enter any additional details about this scholarship rule..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>

        </div>

        {{-- Scholarship Slabs --}}
        <div class="rule-card">

            <div class="section-title">

                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>

                <div>

                    <h5>Scholarship Concession Slabs</h5>

                    <span>
                        Scholarship concession is calculated from the Annual Exam percentage.
                    </span>

                </div>

            </div>

            <div class="rule-preview">

                <h6>
                    Annual Exam Percentage → Scholarship Concession
                </h6>

                {{-- 90+ --}}
                <div class="slab">

                    <span>
                        90% and above
                    </span>

                    <span class="slab-scholarship">
                        50% Scholarship
                    </span>

                </div>

                {{-- 80 - 89.99 --}}
                <div class="slab">

                    <span>
                        80% – 89.99%
                    </span>

                    <span class="slab-scholarship">
                        35% Scholarship
                    </span>

                </div>

                {{-- 70 - 79.99 --}}
                <div class="slab">

                    <span>
                        70% – 79.99%
                    </span>

                    <span class="slab-scholarship">
                        25% Scholarship
                    </span>

                </div>

                {{-- 60 - 69.99 --}}
                <div class="slab">

                    <span>
                        60% – 69.99%
                    </span>

                    <span class="slab-scholarship">
                        10% Scholarship
                    </span>

                </div>

                {{-- Below 60 --}}
                <div class="slab">

                    <span>
                        Below 60%
                    </span>

                    <span class="slab-percent">
                        Not Eligible
                    </span>

                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="form-actions">

            <a
                href="{{ route('admin.scholarship.merit.concession.index') }}"
                class="btn-cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn-save"
            >
                <i class="fas fa-save"></i>
                Save Scholarship Rule
            </button>

        </div>

    </form>

</div>

@endsection
