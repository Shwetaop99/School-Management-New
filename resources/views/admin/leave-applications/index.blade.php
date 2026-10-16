
@extends('layouts.app')

@section('title', 'Leave Management')
@section('page-title', 'Leave Management')

@section('content')

<style>
    /* =========================================================
       LEAVE MANAGEMENT PAGE
    ========================================================= */

    .leave-page {
        width: 100%;
        min-height: calc(100vh - 64px);
        padding: 28px;
        background: #f4f7fb;
    }

    .leave-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .leave-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .leave-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .leave-header-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        box-shadow: 0 8px 20px rgba(20, 124, 245, .18);
    }

    .leave-header-icon svg {
        width: 24px;
        height: 24px;
    }

    .leave-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #172033;
        letter-spacing: -.3px;
    }

    .leave-subtitle {
        margin: 4px 0 0;
        font-size: 14px;
        color: #718096;
    }

    .apply-leave-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 10px;
        background: linear-gradient(135deg, #147cf5, #1769d1);
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(20, 124, 245, .20);
        transition: all .2s ease;
    }

    .apply-leave-btn:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(20, 124, 245, .28);
    }

    /* =========================================================
       SUCCESS MESSAGE
    ========================================================= */

    .success-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        background: #f0fdf4;
        color: #166534;
        font-size: 14px;
        font-weight: 500;
    }

    .success-alert svg {
        flex-shrink: 0;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .leave-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(30, 55, 90, .05);
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f7;
    }

    .leave-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #202938;
    }

    .leave-card-description {
        margin: 4px 0 0;
        font-size: 13px;
        color: #7b8798;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .leave-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    /* =========================================================
       TABLE HEADER
    ========================================================= */

    .leave-table thead th {
        padding: 14px 18px;
        background: #f7f9fc;
        border-bottom: 1px solid #e7ecf3;
        color: #596579;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .45px;
        white-space: nowrap;
        text-align: left;
    }

    .leave-table thead th.center {
        text-align: center;
    }

    /* =========================================================
       TABLE BODY
    ========================================================= */

    .leave-table tbody tr {
        border-bottom: 1px solid #edf1f5;
        transition: background .18s ease;
    }

    .leave-table tbody tr:last-child {
        border-bottom: none;
    }

    .leave-table tbody tr:hover {
        background: #fafcff;
    }

    .leave-table tbody td {
        padding: 15px 18px;
        color: #3b4555;
        font-size: 14px;
        vertical-align: middle;
    }

    .leave-table tbody td.center {
        text-align: center;
    }

    /* =========================================================
       TEACHER
    ========================================================= */

    .teacher-cell {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 170px;
    }

    .teacher-avatar {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #e9f3ff, #eeeaff);
        color: #1769d1;
        font-size: 14px;
        font-weight: 700;
    }

    .teacher-name {
        color: #202938;
        font-weight: 600;
        line-height: 1.3;
    }

    /* =========================================================
       LEAVE TYPE
    ========================================================= */

    .leave-type {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 7px;
        background: #f4f6fb;
        color: #4b5565;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* =========================================================
       DAYS
    ========================================================= */

    .days-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 28px;
        padding: 0 8px;
        border-radius: 7px;
        background: #eef6ff;
        color: #1477df;
        font-weight: 700;
        font-size: 13px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 82px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-approved {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .status-rejected {
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
    }

    .status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 9px;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .action-btn svg {
        width: 17px;
        height: 17px;
    }

    .view-btn {
        background: #eef6ff;
        color: #1477df;
        border: 1px solid #d9eaff;
    }

    .view-btn:hover {
        background: #1477df;
        color: #fff;
        border-color: #1477df;
    }

    .edit-btn {
        background: #f2efff;
        color: #6c63ff;
        border: 1px solid #e3defe;
    }

    .edit-btn:hover {
        background: #6c63ff;
        color: #fff;
        border-color: #6c63ff;
    }

    .delete-btn {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe0e5;
    }

    .delete-btn:hover {
        background: #e11d48;
        color: #fff;
        border-color: #e11d48;
    }

    .delete-form {
        margin: 0;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 55px 20px !important;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .empty-title {
        margin: 0 0 5px;
        color: #334155;
        font-size: 15px;
        font-weight: 700;
    }

    .empty-text {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .leave-page {
            padding: 18px 14px;
        }

        .leave-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .apply-leave-btn {
            width: 100%;
        }

        .leave-title {
            font-size: 21px;
        }

        .leave-card-header {
            padding: 16px;
        }

        .leave-table thead th,
        .leave-table tbody td {
            padding: 12px 14px;
        }
    }

    @media (max-width: 480px) {

        .leave-header-left {
            align-items: flex-start;
        }

        .leave-header-icon {
            width: 42px;
            height: 42px;
        }

        .leave-header-icon svg {
            width: 21px;
            height: 21px;
        }

        .leave-title {
            font-size: 19px;
        }

        .leave-subtitle {
            font-size: 12px;
        }
    }
</style>


<div class="leave-page">

    <div class="leave-container">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="leave-header">

            <div class="leave-header-left">

                <div class="leave-header-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M6 2h9l3 3v17H6z"/>
                        <path d="M15 2v4h4"/>
                        <path d="M9 11h6"/>
                        <path d="M9 15h6"/>
                        <path d="M9 19h3"/>

                    </svg>
                </div>

                <div>
                    <h2 class="leave-title">Leave Applications</h2>

                    <p class="leave-subtitle">
                        Manage and track teacher leave applications
                    </p>
                </div>

            </div>


            <a href="{{ route('admin.leave-applications.create') }}"
               class="apply-leave-btn">

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

                Apply Leave

            </a>

        </div>


        {{-- =====================================================
             SUCCESS MESSAGE
        ====================================================== --}}

        @if(session('success'))

            <div class="success-alert">

                <svg width="18"
                     height="18"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <path d="m9 11 3 3L22 4"/>

                </svg>

                <span>{{ session('success') }}</span>

            </div>

        @endif


        {{-- =====================================================
             TABLE CARD
        ====================================================== --}}

        <div class="leave-card">

            <div class="leave-card-header">

                <div>
                    <h3 class="leave-card-title">
                        Leave Records
                    </h3>

                    <p class="leave-card-description">
                        View, edit, or delete submitted leave applications
                    </p>
                </div>

            </div>


            <div class="table-wrapper">

                <table class="leave-table">

                    <thead>

                        <tr>

                            <th>Teacher</th>

                            <th>Leave Type</th>

                            <th class="center">From</th>

                            <th class="center">To</th>

                            <th class="center">Days</th>

                            <th class="center">Status</th>

                            <th class="center">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($leaves as $leave)

                            @php
                                $teacherName = trim(
                                    ($leave->teacher->first_name ?? '') . ' ' .
                                    ($leave->teacher->last_name ?? '')
                                );

                                $initials = collect(
                                    preg_split('/\s+/', $teacherName)
                                )
                                ->filter()
                                ->map(fn($name) => strtoupper(substr($name, 0, 1)))
                                ->take(2)
                                ->implode('');

                                $statusClass = match($leave->status) {
                                    'Approved' => 'status-approved',
                                    'Rejected' => 'status-rejected',
                                    default => 'status-pending',
                                };
                            @endphp

                            <tr>

                                {{-- TEACHER --}}
                                <td>

                                    <div class="teacher-cell">

                                        <div class="teacher-avatar">
                                            {{ $initials ?: 'T' }}
                                        </div>

                                        <div class="teacher-name">
                                            {{ $teacherName ?: 'Unknown Teacher' }}
                                        </div>

                                    </div>

                                </td>


                                {{-- LEAVE TYPE --}}
                                <td>

                                    <span class="leave-type">
                                        {{ $leave->leave_type }}
                                    </span>

                                </td>


                                {{-- FROM --}}
                                <td class="center">

                                    {{ $leave->from_date
                                        ? $leave->from_date->format('d M Y')
                                        : '-' }}

                                </td>


                                {{-- TO --}}
                                <td class="center">

                                    {{ $leave->to_date
                                        ? $leave->to_date->format('d M Y')
                                        : '-' }}

                                </td>


                                {{-- DAYS --}}
                                <td class="center">

                                    <span class="days-count">
                                        {{ $leave->total_days }}
                                    </span>

                                </td>


                                {{-- STATUS --}}
                                <td class="center">

                                    <span class="status-badge {{ $statusClass }}">

                                        <span class="status-dot"></span>

                                        {{ $leave->status }}

                                    </span>

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="action-buttons">

                                        {{-- VIEW --}}
                                        <a href="{{ route('admin.leave-applications.show', $leave) }}"
                                           class="action-btn view-btn"
                                           title="View Leave"
                                           aria-label="View Leave">

                                            <svg viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">

                                                <path d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>

                                                <circle cx="12"
                                                        cy="12"
                                                        r="3"/>

                                            </svg>

                                        </a>


                                        {{-- EDIT --}}
                                        <a href="{{ route('admin.leave-applications.edit', $leave) }}"
                                           class="action-btn edit-btn"
                                           title="Edit Leave"
                                           aria-label="Edit Leave">

                                            <svg viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">

                                                <path d="M4 16.5V20h3.5L18.81 8.69l-3.5-3.5L4 16.5Z"/>

                                                <path d="M14.81 5.19l3.5 3.5"/>

                                            </svg>

                                        </a>


                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.leave-applications.destroy', $leave) }}"
                                              method="POST"
                                              class="delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete-btn"
                                                    title="Delete Leave"
                                                    aria-label="Delete Leave"
                                                    onclick="return confirm('Delete this leave application?')">

                                                <svg viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M4 7h16"/>

                                                    <path d="M9 7V4h6v3"/>

                                                    <path d="M7 7l1 13h8l1-13"/>

                                                    <path d="M10 11v5"/>

                                                    <path d="M14 11v5"/>

                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="empty-state">

                                    <div class="empty-icon">

                                        <svg width="26"
                                             height="26"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">

                                            <path d="M6 2h9l3 3v17H6z"/>

                                            <path d="M15 2v4h4"/>

                                            <path d="M9 12h6"/>

                                            <path d="M9 16h4"/>

                                        </svg>

                                    </div>

                                    <h4 class="empty-title">
                                        No Leave Applications
                                    </h4>

                                    <p class="empty-text">
                                        There are currently no leave applications to display.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

