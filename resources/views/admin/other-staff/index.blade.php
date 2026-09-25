@extends('layouts.app')

@section('content')

<style>
    .staff-page {
        padding: 24px;
    }

    .staff-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 20px;
    }

    .staff-title h1 {
        margin: 0;
        color: #172033;
        font-size: 24px;
        font-weight: 700;
    }

    .staff-title p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .add-staff-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #1976d2;
        color: #fff;
        text-decoration: none;
        padding: 11px 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .add-staff-btn:hover {
        background: #1565c0;
    }

    /* =========================
       STAT CARDS
    ========================= */

    .staff-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 28px;
    }

    .staff-stat-card {
        position: relative;
        overflow: hidden;
        min-height: 145px;
        padding: 24px 26px;
        border-radius: 16px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(25, 45, 75, .13);
        transition: transform .25s ease, box-shadow .25s ease;
        isolation: isolate;
    }

    .staff-stat-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 16px 30px rgba(25, 45, 75, .20);
    }

    .staff-stat-card::before {
        content: "";
        position: absolute;
        width: 125px;
        height: 125px;
        top: -55px;
        right: -25px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
        z-index: -1;
    }

    .staff-stat-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        right: -28px;
        bottom: -45px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
        z-index: -1;
    }

    .stat-blue {
        background: linear-gradient(135deg, #1769d1, #338be5);
    }

    .stat-green {
        background: linear-gradient(135deg, #ef9808, #ffb52f);
    }

    .stat-orange {
        background: linear-gradient(135deg, #ef5350, #ff6868);
    }

    .stat-purple {
        background: linear-gradient(135deg, #8e2bb5, #a844c5);
    }

    .stat-icon {
        position: absolute;
        top: 24px;
        right: 26px;
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 34px;
        line-height: 1;
    }

    .stat-icon svg {
        width: 42px;
        height: 42px;
        fill: currentColor;
    }

    .stat-number {
        display: block;
        position: relative;
        z-index: 2;
        margin-top: 3px;
        font-size: 34px;
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .stat-label {
        display: block;
        position: relative;
        z-index: 2;
        margin-top: 8px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .1px;
        text-transform: none;
    }

    /* =========================
       ALERTS
    ========================= */

    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    /* =========================
       FILTER CARD
    ========================= */

    .filter-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 7px;
        color: #596579;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        height: 40px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        padding: 0 12px;
        color: #343d50;
        background: #fff;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #1976d2;
    }

    .filter-btn {
        height: 40px;
        border: none;
        background: #172033;
        color: #fff;
        padding: 0 18px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: #0f1728;
    }

    .clear-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 15px;
        border-radius: 7px;
        background: #f1f3f7;
        color: #596579;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    /* =========================
       TABLE
    ========================= */

    .staff-table-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
    }

    .table-heading {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .table-heading h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
    }

    .table-heading p {
        margin: 5px 0 0;
        color: #8a93a5;
        font-size: 12px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .staff-table th {
        background: #f8fafc;
        color: #70798c;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 700;
        padding: 13px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .staff-table td {
        padding: 14px 18px;
        border-top: 1px solid #edf0f5;
        color: #343d50;
        font-size: 13px;
        white-space: nowrap;
    }

    .staff-info {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .staff-photo {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e2e7ef;
    }

    .staff-placeholder {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf2ff;
        color: #1976d2;
        font-weight: 700;
        font-size: 15px;
    }

    .staff-name {
        color: #172033;
        font-weight: 650;
    }

    .staff-id {
        color: #8a93a5;
        font-size: 11px;
        margin-top: 3px;
    }

    .designation-badge {
        display: inline-block;
        background: #eef4ff;
        color: #1769d1;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 650;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-inactive {
        background: #ffebee;
        color: #d32f2f;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 650;
        border: none;
        cursor: pointer;
    }

    .view-btn {
        background: #eef4ff;
        color: #1769d1;
    }

    .edit-btn {
        background: #fff4df;
        color: #ef6c00;
    }

    .delete-btn {
        background: #ffebee;
        color: #d32f2f;
    }

    .action-btn:hover {
        opacity: .82;
    }

    .delete-form {
        display: inline;
    }

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7b8497;
    }

    .empty-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .empty-state h4 {
        margin: 0 0 7px;
        color: #343d50;
        font-size: 16px;
    }

    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    /* =========================
       PAGINATION
    ========================= */

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f5;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1100px) {
        .staff-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 650px) {
        .staff-page {
            padding: 15px;
        }

        .staff-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .staff-stats {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="staff-page">

    {{-- Header --}}
    <div class="staff-header">

        <div class="staff-title">
            <h1>Other Staff</h1>
            <p>Manage librarians, accountants, receptionists, drivers and other staff members.</p>
</div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Statistics --}}
    <div class="staff-stats">

        <div class="staff-stat-card stat-blue">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.91 1.97 3.45V19h7v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
            </div>
            <span class="stat-number">{{ $totalStaff }}</span>
            <span class="stat-label">Total Staff</span>
        </div>

        <div class="staff-stat-card stat-green">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.91 1.97 3.45V19h7v-2.5c0-2.33-4.67-3.5-7-3.5Zm4.7-4.3-1.4-1.4-2.3 2.29-1.3-1.29-1.4 1.41 2.7 2.7 3.7-3.71Z"/></svg>
            </div>
            <span class="stat-number">{{ $activeStaff }}</span>
            <span class="stat-label">Active Staff</span>
        </div>

        <div class="staff-stat-card stat-orange">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.91 1.97 3.45V19h7v-2.5c0-2.33-4.67-3.5-7-3.5Zm3.5-5.5 1.4 1.4-1.4 1.4-1.4-1.4-1.4 1.4-1.4-1.4 1.4-1.4-1.4-1.4 1.4-1.4 1.4 1.4 1.4-1.4 1.4 1.4Z"/></svg>
            </div>
            <span class="stat-number">{{ $inactiveStaff }}</span>
            <span class="stat-label">Inactive Staff</span>
        </div>

        <div class="staff-stat-card stat-purple">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M5 3h10v14H5V3Zm2 2v10h6V5H7Zm10 2h2v12h-2V7Zm-3 14H3v-2h11v2Zm8-3h-2v-6h2v6Z"/></svg>
            </div>
            <span class="stat-number">{{ $librarians }}</span>
            <span class="stat-label">Active Librarians</span>
        </div>

    </div>


    {{-- Filters --}}
    <div class="filter-card">

        <form method="GET"
              action="{{ route('admin.other-staff.index') }}"
              class="filter-form">

            <div class="filter-group">

                <label>Search</label>

                <input
                    type="text"
                    name="search"
                    class="filter-input"
                    placeholder="Search name, ID, phone..."
                    value="{{ request('search') }}"
                >

            </div>


            <div class="filter-group">

                <label>Designation</label>

                <select name="designation"
                        class="filter-select">

                    <option value="">All Designations</option>

                    @foreach($designations as $designation)

                        <option
                            value="{{ $designation }}"
                            {{ request('designation') === $designation ? 'selected' : '' }}
                        >
                            {{ $designation }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="filter-group">

                <label>Status</label>

                <select name="status"
                        class="filter-select">

                    <option value="">All Status</option>

                    <option value="Active"
                        {{ request('status') === 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive"
                        {{ request('status') === 'Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>


            <div style="display:flex; gap:7px;">

                <button type="submit"
                        class="filter-btn">
                    Search
                </button>

                <a href="{{ route('admin.other-staff.index') }}"
                   class="clear-btn">
                    Clear
                </a>

            </div>

        </form>

    </div>


    {{-- Staff Table --}}
    <div class="staff-table-card">

        <div class="table-heading">

            <h3>Staff Members</h3>

            <p>
                All non-teaching staff members registered in the school.
            </p>

        </div>


        @if($staff->count())

            <div class="table-wrapper">

                <table class="staff-table">

                    <thead>

                        <tr>
                            <th>Staff</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Phone</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($staff as $member)

                            <tr>

                                {{-- Staff --}}
                                <td>

                                    <div class="staff-info">

                                        @if($member->profile_photo)

                                            <img
                                                src="{{ asset('storage/' . $member->profile_photo) }}"
                                                alt="{{ $member->name }}"
                                                class="staff-photo"
                                            >

                                        @else

                                            <div class="staff-placeholder">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>

                                        @endif


                                        <div>

                                            <div class="staff-name">
                                                {{ $member->name }}
                                            </div>

                                            <div class="staff-id">
                                                {{ $member->staff_id }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Designation --}}
                                <td>

                                    <span class="designation-badge">
                                        {{ $member->designation }}
                                    </span>

                                </td>


                                {{-- Department --}}
                                <td>
                                    {{ $member->department ?: '—' }}
                                </td>


                                {{-- Phone --}}
                                <td>
                                    {{ $member->phone ?: '—' }}
                                </td>


                                {{-- Joining Date --}}
                                <td>

                                    @if($member->joining_date)

                                        {{ $member->joining_date->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="status-badge
                                        {{ $member->status === 'Active'
                                            ? 'status-active'
                                            : 'status-inactive' }}">

                                        {{ $member->status }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.other-staff.show', $member) }}"
                                            class="action-btn view-btn"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route('admin.other-staff.edit', $member) }}"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            action="{{ route('admin.other-staff.destroy', $member) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete this staff member?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="pagination-wrapper">

                {{ $staff->links() }}

            </div>


        @else

            <div class="empty-state">

                <div class="empty-icon">
                    👥
                </div>

                <h4>No staff members found</h4>

                <p>
                    Add your first non-teaching staff member using the
                    <strong>+ Add Staff</strong> button.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection