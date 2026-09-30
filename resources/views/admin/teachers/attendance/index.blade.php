@extends('layouts.app')

@section('title', 'Teacher Attendance')

@section('page-title', 'Teacher Attendance')

@section('content')

<style>
    .attendance-page {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
        min-height: calc(100vh - 80px);
    }

    /* HEADER */
    .attendance-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
    }

    .attendance-header h2 {
        margin: 0 0 6px;
        font-size: 25px;
        font-weight: 700;
    }

    .attendance-header p {
        margin: 0;
        opacity: .9;
    }

    /* SUCCESS MESSAGE */
    .success-message {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 12px 16px;
        border-radius: 9px;
        margin-bottom: 18px;
    }

    /* SUMMARY */
    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .05);
        border: 1px solid #edf0f5;
    }

    .summary-card strong {
        display: block;
        font-size: 25px;
        font-weight: 700;
        color: #147cf5;
        margin-bottom: 5px;
    }

    .summary-card span {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
    }

    /* MAIN CARD */
    .attendance-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .06);
        overflow: hidden;
    }

    /* TOOLBAR */
    .attendance-toolbar {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
        flex-wrap: wrap;
    }

    .date-group {
        min-width: 240px;
    }

    .date-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .date-group input {
        width: 100%;
        padding: 10px 13px;
        border: 1px solid #d8dee9;
        border-radius: 9px;
        outline: none;
    }

    .date-group input:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .1);
    }

    /* SAVE BUTTON */
    .btn-save {
        border: none;
        background: #147cf5;
        color: #fff;
        padding: 11px 22px;
        border-radius: 9px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .btn-save:hover {
        background: #1268ca;
    }

    /* TABLE */
    .attendance-table-wrapper {
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table th {
        background: #f8faff;
        color: #4b5563;
        font-size: 13px;
        font-weight: 700;
        padding: 15px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .attendance-table td {
        padding: 14px 18px;
        border-top: 1px solid #edf0f5;
        color: #374151;
        vertical-align: middle;
    }

    .teacher-name {
        font-weight: 600;
        color: #1f2937;
    }

    .teacher-id {
        color: #6b7280;
        font-size: 14px;
    }

    /* INPUTS */
    .status-select,
    .overtime-input {
        width: 100%;
        min-width: 140px;
        padding: 9px 11px;
        border: 1px solid #d8dee9;
        border-radius: 8px;
        background: #fff;
        outline: none;
    }

    .overtime-input {
        min-width: 110px;
    }

    .status-select:focus,
    .overtime-input:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, .1);
    }

    /* EMPTY */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .attendance-summary {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .attendance-page {
            padding: 15px;
        }

        .attendance-summary {
            grid-template-columns: repeat(2, 1fr);
        }

        .attendance-toolbar {
            align-items: stretch;
        }

        .date-group {
            width: 100%;
        }

        .btn-save {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .attendance-summary {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="attendance-page">

    {{-- HEADER --}}
    <div class="attendance-header">
        <h2>Teacher Attendance</h2>
        <p>Manage daily attendance and overtime hours for teachers.</p>
    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif


    {{-- SUMMARY --}}
    <div class="attendance-summary">

        <div class="summary-card">
            <strong>{{ $summary['present'] }}</strong>
            <span>Present</span>
        </div>

        <div class="summary-card">
            <strong>{{ $summary['absent'] }}</strong>
            <span>Absent</span>
        </div>

        <div class="summary-card">
            <strong>{{ $summary['half_day'] }}</strong>
            <span>Half Day</span>
        </div>

        <div class="summary-card">
            <strong>{{ $summary['late'] }}</strong>
            <span>Late</span>
        </div>

        <div class="summary-card">
            <strong>{{ number_format($summary['overtime'], 2) }}</strong>
            <span>OT Hours</span>
        </div>

    </div>


    {{-- ATTENDANCE CARD --}}
    <div class="attendance-card">

        {{-- DATE FILTER --}}
        <div class="attendance-toolbar">

            <div class="date-group">

                <form method="GET"
                      action="{{ route('admin.teachers.attendance.index') }}">

                    <label for="attendance_date">
                        Attendance Date
                    </label>

                    <input
                        type="date"
                        id="attendance_date"
                        name="date"
                        value="{{ $date }}"
                        required
                        onchange="this.form.submit()"
                    >

                </form>

            </div>

        </div>


        {{-- SAVE FORM --}}
        <form method="POST"
              action="{{ route('admin.teachers.attendance.store') }}">

            @csrf

            {{-- Keep selected date --}}
            <input
                type="hidden"
                name="attendance_date"
                value="{{ $date }}"
            >


            {{-- TABLE --}}
            <div class="attendance-table-wrapper">

                <table class="attendance-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teacher</th>
                            <th>Teacher ID</th>
                            <th>Attendance Status</th>
                            <th>Overtime Hours</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($teachers as $index => $teacher)

                            @php
                                $currentAttendance = $attendance[$teacher->id] ?? null;
                                $currentStatus = $currentAttendance->status ?? 'Present';
                                $currentOvertime = $currentAttendance->overtime_hours ?? 0;
                            @endphp

                            <tr>

                                {{-- NUMBER --}}
                                <td>
                                    {{ $index + 1 }}
                                </td>


                                {{-- TEACHER --}}
                                <td>
                                    <div class="teacher-name">
                                        {{ $teacher->first_name }}
                                        {{ $teacher->last_name }}
                                    </div>
                                </td>


                                {{-- TEACHER ID --}}
                                <td>
                                    <div class="teacher-id">
                                        {{ $teacher->teacher_id }}
                                    </div>
                                </td>


                                {{-- STATUS --}}
                                <td>

                                    <select
                                        name="attendance[{{ $teacher->id }}]"
                                        class="status-select"
                                        required
                                    >

                                        <option value="Present"
                                            {{ $currentStatus === 'Present' ? 'selected' : '' }}>
                                            Present
                                        </option>

                                        <option value="Absent"
                                            {{ $currentStatus === 'Absent' ? 'selected' : '' }}>
                                            Absent
                                        </option>

                                        <option value="Half Day"
                                            {{ $currentStatus === 'Half Day' ? 'selected' : '' }}>
                                            Half Day
                                        </option>

                                        <option value="Late"
                                            {{ $currentStatus === 'Late' ? 'selected' : '' }}>
                                            Late
                                        </option>

                                    </select>

                                </td>


                                {{-- OVERTIME --}}
                                <td>

                                    <input
                                        type="number"
                                        name="overtime[{{ $teacher->id }}]"
                                        value="{{ $currentOvertime }}"
                                        min="0"
                                        step="0.5"
                                        placeholder="0.0"
                                        class="overtime-input"
                                    >

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="empty-state">
                                        No teachers found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- SAVE BUTTON --}}
            <div style="padding: 20px 24px; text-align: right; border-top: 1px solid #edf0f5;">

                <button type="submit" class="btn-save">
                    Save Attendance
                </button>

            </div>

        </form>

    </div>

</div>

@endsection