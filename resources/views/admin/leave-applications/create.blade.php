
@extends('layouts.app')

@section('title', 'Apply Leave')
@section('page-title', 'Apply Leave')

@section('content')

<style>
    .leave-create-page {
        width: 100%;
        min-height: calc(100vh - 80px);
        padding: 28px;
        background: #f4f7fb;
    }

    .leave-form-card {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        border: 1px solid #e8edf5;
        box-shadow: 0 5px 20px rgba(30, 55, 90, .06);
    }

    .leave-form-header {
        margin-bottom: 26px;
    }

    .leave-form-header h2 {
        margin: 0;
        color: #1e293b;
        font-size: 24px;
        font-weight: 700;
    }

    .leave-form-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        width: 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 12px;
        border: 1px solid #dbe2ea;
        border-radius: 9px;
        background: #fff;
        color: #334155;
        font-size: 14px;
        outline: none;
        transition: all .2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .10);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .reason-section {
        margin-top: 20px;
    }

    .button-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(135deg, #147cf5, #1769d1);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(20, 124, 245, .18);
        transition: all .2s ease;
    }

    .submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(20, 124, 245, .25);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 22px;
        border-radius: 9px;
        background: #e2e8f0;
        color: #334155;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .back-btn:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    /* Validation errors */
    .error-message {
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .form-errors {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 9px;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        font-size: 13px;
    }

    .form-errors ul {
        margin: 0;
        padding-left: 18px;
    }

    /* Responsive */
    @media (max-width: 700px) {

        .leave-create-page {
            padding: 18px 14px;
        }

        .leave-form-card {
            padding: 22px 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .button-row {
            flex-direction: column;
            align-items: stretch;
        }

        .submit-btn,
        .back-btn {
            width: 100%;
        }
    }
</style>


<div class="leave-create-page">

    <div class="leave-form-card">

        {{-- Header --}}
        <div class="leave-form-header">

            <h2>Apply Leave</h2>

            <p>
                Enter the leave application details below
            </p>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="form-errors">

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <form action="{{ route('admin.leave-applications.store') }}"
              method="POST">

            @csrf


            {{-- Main Fields --}}
            <div class="form-grid">

                {{-- Teacher --}}
                <div class="form-group">

                    <label class="form-label">
                        Teacher
                    </label>

                    <select name="teacher_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Teacher
                        </option>

                        @foreach($teachers as $teacher)

                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->first_name }}
                                {{ $teacher->last_name }}

                            </option>

                        @endforeach

                    </select>

                    @error('teacher_id')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Leave Type --}}
                <div class="form-group">

                    <label class="form-label">
                        Leave Type
                    </label>

                    <select name="leave_type"
                            class="form-select"
                            required>

                        <option value="">
                            Select Leave Type
                        </option>

                        @foreach([
                            'Casual Leave',
                            'Sick Leave',
                            'Earned Leave',
                            'Emergency Leave',
                            'Other'
                        ] as $type)

                            <option value="{{ $type }}"
                                {{ old('leave_type') == $type ? 'selected' : '' }}>

                                {{ $type }}

                            </option>

                        @endforeach

                    </select>

                    @error('leave_type')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- From Date --}}
                <div class="form-group">

                    <label class="form-label">
                        From Date
                    </label>

                    <input type="date"
                           name="from_date"
                           id="from_date"
                           value="{{ old('from_date') }}"
                           class="form-control"
                           required>

                    @error('from_date')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- To Date --}}
                <div class="form-group">

                    <label class="form-label">
                        To Date
                    </label>

                    <input type="date"
                           name="to_date"
                           id="to_date"
                           value="{{ old('to_date') }}"
                           class="form-control"
                           required>

                    @error('to_date')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Total Days --}}
                <div class="form-group">

                    <label class="form-label">
                        Total Days
                    </label>

                    <input type="number"
                           name="total_days"
                           id="total_days"
                           value="{{ old('total_days') }}"
                           min="1"
                           readonly
                           required
                           placeholder="Auto calculated"
                           class="form-control">

                    @error('total_days')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Reason --}}
            <div class="reason-section">

                <div class="form-group">

                    <label class="form-label">
                        Reason
                    </label>

                    <textarea name="reason"
                              rows="4"
                              class="form-control"
                              placeholder="Enter reason for leave...">{{ old('reason') }}</textarea>

                    @error('reason')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Buttons --}}
            <div class="button-row">

                <button type="submit"
                        class="submit-btn">

                    <svg width="17"
                         height="17"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>

                    </svg>

                    Submit Leave

                </button>


                <a href="{{ route('admin.leave-applications.index') }}"
                   class="back-btn">

                    ← Back

                </a>

            </div>

        </form>

    </div>

</div>


<script>
    function calculateDays() {

        const fromValue = document.getElementById('from_date').value;
        const toValue = document.getElementById('to_date').value;
        const totalDays = document.getElementById('total_days');

        if (!fromValue || !toValue) {
            totalDays.value = '';
            return;
        }

        const from = new Date(fromValue);
        const to = new Date(toValue);

        if (to >= from) {

            const days = Math.ceil(
                (to - from) / (1000 * 60 * 60 * 24)
            ) + 1;

            totalDays.value = days;

        } else {

            totalDays.value = '';

        }
    }


    document.getElementById('from_date')
        .addEventListener('change', calculateDays);

    document.getElementById('to_date')
        .addEventListener('change', calculateDays);

    calculateDays();
</script>

@endsection

s