@extends('layouts.app')

@section('content')

<style>
    .notice-page {
        padding: 10px 5px;
    }

    /* =========================
       PAGE HEADER
    ========================== */

    .notice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .notice-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .notice-title p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 14px;
    }

    .create-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #1976d2;
        color: #fff;
        padding: 12px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 5px 14px rgba(25, 118, 210, 0.22);
        transition: 0.2s;
    }

    .create-btn:hover {
        background: #1565c0;
        color: #fff;
        transform: translateY(-1px);
    }


    /* =========================
       SUCCESS MESSAGE
    ========================== */

    .success-alert {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 14px;
        font-weight: 600;
    }


    /* =========================
       STATISTICS
    ========================== */

    .notice-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
        border: 1px solid #edf0f5;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 24px;
        height: 24px;
    }

    .total-icon {
        background: #e3f2fd;
        color: #1976d2;
    }

    .published-icon {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .draft-icon {
        background: #fff3e0;
        color: #ef6c00;
    }

    .expired-icon {
        background: #ffebee;
        color: #d32f2f;
    }

    .stat-info span {
        display: block;
        color: #7b8497;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .stat-info strong {
        font-size: 24px;
        color: #172033;
    }


    /* =========================
       MAIN CARD
    ========================== */

    .notice-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
        border: 1px solid #edf0f5;
        overflow: hidden;
    }


    /* =========================
       FILTERS
    ========================== */

    .notice-filters {
        padding: 20px;
        border-bottom: 1px solid #edf0f5;
        display: grid;
        grid-template-columns: 1fr 180px 180px 170px auto;
        gap: 12px;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-box svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        color: #8a94a6;
        pointer-events: none;
    }

    .search-box input,
    .notice-filters select {
        width: 100%;
        height: 44px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #fafbfc;
        padding: 0 13px;
        outline: none;
        color: #273247;
        font-size: 13px;
        box-sizing: border-box;
    }

    .search-box input {
        padding-left: 42px;
    }

    .search-box input:focus,
    .notice-filters select:focus {
        border-color: #1976d2;
        background: #fff;
    }

    .filter-btn {
        height: 44px;
        padding: 0 16px;
        border: none;
        border-radius: 9px;
        background: #1976d2;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: #1565c0;
    }

    .clear-filter {
        height: 44px;
        padding: 0 14px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        background: #fff;
        color: #697386;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
    }

    .clear-filter:hover {
        background: #f5f7fa;
        color: #1976d2;
    }


    /* =========================
       TABLE
    ========================== */

    .table-wrapper {
        overflow-x: auto;
    }

    .notice-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .notice-table th {
        background: #f8f9fc;
        color: #687287;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        padding: 15px 20px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .notice-table td {
        padding: 17px 20px;
        border-top: 1px solid #edf0f5;
        color: #4d576b;
        font-size: 13px;
        vertical-align: middle;
    }

    .notice-table tbody tr {
        transition: 0.15s;
    }

    .notice-table tbody tr:hover {
        background: #fafcff;
    }


    /* =========================
       NOTICE TITLE
    ========================== */

    .notice-title-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .notice-mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #e3f2fd;
        color: #1976d2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notice-mini-icon svg {
        width: 19px;
        height: 19px;
    }

    .notice-name {
        font-weight: 600;
        color: #202a3b;
        margin-bottom: 3px;
    }

    .notice-subtitle {
        font-size: 11px;
        color: #9099a9;
    }


    /* =========================
       CATEGORY
    ========================== */

    .category-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 7px;
        background: #eef4ff;
        color: #356bc5;
        font-size: 11px;
        font-weight: 600;
    }


    /* =========================
       STATUS
    ========================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-published {
        background: #e9f7ed;
        color: #2e7d32;
    }

    .status-published .status-dot {
        background: #2e7d32;
    }

    .status-draft {
        background: #fff4e5;
        color: #ef6c00;
    }

    .status-draft .status-dot {
        background: #ef6c00;
    }

    .status-expired {
        background: #ffebee;
        color: #d32f2f;
    }

    .status-expired .status-dot {
        background: #d32f2f;
    }


    /* =========================
       PRIORITY
    ========================== */

    .priority {
        font-size: 12px;
        font-weight: 600;
    }

    .priority-normal {
        color: #607d8b;
    }

    .priority-important {
        color: #ef6c00;
    }

    .priority-urgent {
        color: #d32f2f;
    }


    /* =========================
       ACTION BUTTONS
    ========================== */

    .action-buttons {
        display: flex;
        gap: 7px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border: 1px solid #e1e5ec;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #697386;
        transition: 0.2s;
    }

    .action-btn:hover {
        background: #f4f7fb;
        color: #1976d2;
        border-color: #c9d8ed;
    }

    .action-btn.delete:hover {
        color: #d32f2f;
        background: #fff5f5;
        border-color: #f0caca;
    }

    .action-btn svg {
        width: 16px;
        height: 16px;
    }


    /* =========================
       EMPTY STATE
    ========================== */

    .empty-state {
        text-align: center;
        padding: 60px 20px !important;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: #eef4ff;
        color: #1976d2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .empty-state strong {
        display: block;
        color: #354052;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .empty-state span {
        color: #9099a9;
        font-size: 12px;
    }


    /* =========================
       FOOTER / PAGINATION
    ========================== */

    .table-footer {
        padding: 16px 20px;
        border-top: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #7b8497;
        font-size: 12px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border: 1px solid #e1e5ec;
        background: #fff;
        border-radius: 7px;
        color: #697386;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    .pagination a:hover {
        background: #f4f7fb;
        color: #1976d2;
        border-color: #c9d8ed;
    }

    .pagination .active span {
        background: #1976d2;
        border-color: #1976d2;
        color: #fff;
    }

    .pagination .disabled span {
        opacity: 0.45;
        cursor: not-allowed;
    }


    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 1200px) {

        .notice-filters {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .filter-btn,
        .clear-filter {
            width: 100%;
        }
    }


    @media (max-width: 1000px) {

        .notice-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .notice-filters {
            grid-template-columns: 1fr 1fr;
        }
    }


    @media (max-width: 600px) {

        .notice-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .notice-stats {
            grid-template-columns: 1fr;
        }

        .notice-filters {
            grid-template-columns: 1fr;
        }

        .table-footer {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>


<div class="notice-page">

    {{-- =========================
         SUCCESS MESSAGE
    ========================== --}}

    @if(session('success'))

        <div class="success-alert">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================
         PAGE HEADER
    ========================== --}}

    <div class="notice-header">

        <div class="notice-title">

            <h1>Notice Management</h1>

            <p>
                Manage school announcements, notices and important updates.
            </p>

        </div>


        <a href="{{ route('admin.notices.create') }}"
           class="create-btn">

            <svg width="18"
                 height="18"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <path d="M12 5v14"/>
                <path d="M5 12h14"/>

            </svg>

            Create Notice

        </a>

    </div>


    {{-- =========================
         STATISTICS
    ========================== --}}

    <div class="notice-stats">


        {{-- Total --}}

        <div class="stat-card">

            <div class="stat-icon total-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path d="M4 4h16v16H4z"/>
                    <path d="M8 8h8"/>
                    <path d="M8 12h8"/>
                    <path d="M8 16h5"/>

                </svg>

            </div>

            <div class="stat-info">

                <span>Total Notices</span>

                <strong>{{ $total }}</strong>

            </div>

        </div>


        {{-- Published --}}

        <div class="stat-card">

            <div class="stat-icon published-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path d="M20 6L9 17l-5-5"/>

                </svg>

            </div>

            <div class="stat-info">

                <span>Published</span>

                <strong>{{ $published }}</strong>

            </div>

        </div>


        {{-- Draft --}}

        <div class="stat-card">

            <div class="stat-icon draft-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path d="M12 20h9"/>

                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>

                </svg>

            </div>

            <div class="stat-info">

                <span>Drafts</span>

                <strong>{{ $draft }}</strong>

            </div>

        </div>


        {{-- Expired --}}

        <div class="stat-card">

            <div class="stat-icon expired-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <circle cx="12"
                            cy="12"
                            r="9"/>

                    <path d="M12 7v5l3 2"/>

                </svg>

            </div>

            <div class="stat-info">

                <span>Expired</span>

                <strong>{{ $expired }}</strong>

            </div>

        </div>

    </div>


    {{-- =========================
         NOTICE CARD
    ========================== --}}

    <div class="notice-card">


        {{-- =========================
             SEARCH & FILTER
        ========================== --}}

        <form method="GET"
              action="{{ route('admin.notices.index') }}"
              class="notice-filters">


            {{-- Search --}}

            <div class="search-box">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <circle cx="11"
                            cy="11"
                            r="7"/>

                    <path d="m20 20-4-4"/>

                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search notices..."
                >

            </div>


            {{-- Category --}}

            <select name="category">

                <option value="">
                    All Categories
                </option>

                @foreach([
                    'Academic',
                    'Events',
                    'Examination',
                    'Holiday',
                    'General'
                ] as $category)

                    <option value="{{ $category }}"
                        {{ request('category') === $category ? 'selected' : '' }}>

                        {{ $category }}

                    </option>

                @endforeach

            </select>


            {{-- Status --}}

            <select name="status">

                <option value="">
                    All Status
                </option>

                <option value="Published"
                    {{ request('status') === 'Published' ? 'selected' : '' }}>
                    Published
                </option>

                <option value="Draft"
                    {{ request('status') === 'Draft' ? 'selected' : '' }}>
                    Draft
                </option>

                <option value="Expired"
                    {{ request('status') === 'Expired' ? 'selected' : '' }}>
                    Expired
                </option>

            </select>


            {{-- Date --}}

            <select name="date_filter">

                <option value="">
                    All Dates
                </option>

                <option value="today"
                    {{ request('date_filter') === 'today' ? 'selected' : '' }}>
                    Today
                </option>

                <option value="week"
                    {{ request('date_filter') === 'week' ? 'selected' : '' }}>
                    This Week
                </option>

                <option value="month"
                    {{ request('date_filter') === 'month' ? 'selected' : '' }}>
                    This Month
                </option>

            </select>


            {{-- Filter Button --}}

            <button type="submit"
                    class="filter-btn">

                Filter

            </button>


            {{-- Clear --}}

            @if(request()->hasAny([
                'search',
                'category',
                'status',
                'date_filter'
            ]))

                <a href="{{ route('admin.notices.index') }}"
                   class="clear-filter">

                    Clear

                </a>

            @endif

        </form>


        {{-- =========================
             TABLE
        ========================== --}}

        <div class="table-wrapper">

            <table class="notice-table">

                <thead>

                    <tr>

                        <th>Notice</th>

                        <th>Category</th>

                        <th>Publish Date</th>

                        <th>Priority</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                    @forelse($notices as $notice)

                        <tr>


                            {{-- Notice --}}

                            <td>

                                <div class="notice-title-cell">

                                    <div class="notice-mini-icon">

                                        <svg viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path d="M4 4h16v16H4z"/>

                                            <path d="M8 8h8"/>

                                            <path d="M8 12h6"/>

                                        </svg>

                                    </div>


                                    <div>

                                        <div class="notice-name">

                                            {{ $notice->title }}

                                        </div>

                                        <div class="notice-subtitle">

                                            @if($notice->created_by)

                                                Created by Admin

                                            @else

                                                Created by Admin

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}

                            <td>

                                <span class="category-badge">

                                    {{ $notice->category }}

                                </span>

                            </td>


                            {{-- Publish Date --}}

                            <td>

                                @if($notice->publish_date)

                                    {{ $notice->publish_date->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Priority --}}

                            <td>

                                <span class="priority
                                    priority-{{ strtolower($notice->priority) }}">

                                    {{ $notice->priority }}

                                </span>

                            </td>


                            {{-- Status --}}

                            <td>

                                @if($notice->status === 'Published')

                                    <span class="status-badge status-published">

                                        <span class="status-dot"></span>

                                        Published

                                    </span>

                                @elseif($notice->status === 'Draft')

                                    <span class="status-badge status-draft">

                                        <span class="status-dot"></span>

                                        Draft

                                    </span>

                                @else

                                    <span class="status-badge status-expired">

                                        <span class="status-dot"></span>

                                        Expired

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="action-buttons">


                                    {{-- View --}}

                                    <a href="{{ route('admin.notices.show', $notice) }}"
   class="action-btn"
   title="View">

    <svg viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2">

        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>

        <circle cx="12"
                cy="12"
                r="3"/>

    </svg>

</a>


                                    {{-- Edit --}}

                                    <a href="{{ route('admin.notices.edit', $notice) }}"
   class="action-btn"
   title="Edit">

    <svg viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2">

        <path d="M12 20h9"/>

        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>

    </svg>

</a>


                                    {{-- Delete --}}

                                    <form method="POST"
      action="{{ route('admin.notices.destroy', $notice) }}"
      onsubmit="return confirm('Are you sure you want to delete this notice?');"
      style="display: inline;">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="action-btn delete"
            title="Delete">

        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">

            <path d="M3 6h18"/>
            <path d="M8 6V4h8v2"/>
            <path d="M19 6l-1 14H6L5 6"/>
            <path d="M10 11v5"/>
            <path d="M14 11v5"/>

        </svg>

    </button>

</form>

                                </div>

                            </td>

                        </tr>


                    @empty


                        {{-- Empty State --}}

                        <tr>

                            <td colspan="6"
                                class="empty-state">

                                <div class="empty-icon">

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path d="M4 4h16v16H4z"/>

                                        <path d="M8 9h8"/>

                                        <path d="M8 13h5"/>

                                    </svg>

                                </div>

                                <strong>
                                    No notices found
                                </strong>

                                <span>
                                    Try changing your search or filters,
                                    or create a new notice.
                                </span>

                            </td>

                        </tr>


                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================
             FOOTER
        ========================== --}}

        <div class="table-footer">

            <span>

                Showing
                {{ $notices->firstItem() ?? 0 }}
                –
                {{ $notices->lastItem() ?? 0 }}
                of
                {{ $notices->total() }}
                notices

            </span>


            @if($notices->hasPages())

                <div class="pagination">

                    {{ $notices->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection