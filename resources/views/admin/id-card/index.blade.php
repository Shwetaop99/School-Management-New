@extends('layouts.app')

@section('content')

<div class="container-fluid py-4 id-card-index">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-header mb-4">

        <div>
            <div class="eyebrow">
                <i class="bi bi-person-vcard-fill"></i>
                STUDENT DOCUMENTS
            </div>

            <h2 class="page-title">
                Student ID Cards
            </h2>

            <p class="page-subtitle mb-0">
                Manage, view, print and generate student identity cards.
            </p>
        </div>

        <div class="header-actions">

            <a href="{{ route('admin.id-card.templates.index') }}"
               class="btn btn-light border">
                <i class="bi bi-grid-3x3-gap me-1"></i>
                Templates
            </a>

            <a href="{{ route('admin.id-card.create') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Generate ID Card
            </a>

        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    @php

        $totalCards = $idCards->total();

        $activeCards = $idCards->getCollection()
            ->where('status', 'active')
            ->count();

        $inactiveCards = $idCards->getCollection()
            ->where('status', 'inactive')
            ->count();

        $currentYear = now()->year . '-' . (now()->year + 1);

        $currentYearCards = $idCards->getCollection()
            ->where('academic_year', $currentYear)
            ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon blue">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>

                <div>
                    <span class="stat-label">
                        Total ID Cards
                    </span>

                    <strong class="stat-value">
                        {{ $totalCards }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>
                    <span class="stat-label">
                        Active Cards
                    </span>

                    <strong class="stat-value">
                        {{ $activeCards }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon orange">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>

                <div>
                    <span class="stat-label">
                        Inactive Cards
                    </span>

                    <strong class="stat-value">
                        {{ $inactiveCards }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Academic Year --}}
        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon purple">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <span class="stat-label">
                        {{ $currentYear }}
                    </span>

                    <strong class="stat-value">
                        {{ $currentYearCards }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN CARD
    ========================================================== --}}

    <div class="content-card">

        {{-- Card Header --}}
        <div class="content-card-header">

            <div>

                <h5>
                    <i class="bi bi-card-list me-2"></i>
                    Generated ID Cards
                </h5>

                <p>
                    View and manage all student ID cards.
                </p>

            </div>

            <div class="header-count">
                {{ $totalCards }} Cards
            </div>

        </div>


        {{-- =====================================================
             SEARCH / FILTER
        ====================================================== --}}

        <div class="filter-bar">

            <div class="filter-search">

                <i class="bi bi-search"></i>

                <input type="text"
                       id="cardSearch"
                       class="form-control"
                       placeholder="Search student, ID or card number...">

            </div>

            <div class="filter-select">

                <select id="statusFilter"
                        class="form-select">

                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>

                </select>

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="table-responsive">

            <table class="table id-card-table mb-0">

                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Card Number
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Template
                        </th>

                        <th>
                            Academic Year
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody id="idCardTableBody">

                @forelse($idCards as $index => $idCard)

                    @php
                        $student = $idCard->student;
                    @endphp

                    <tr class="card-row"
                        data-status="{{ $idCard->status }}"
                        data-search="
                            {{ strtolower(
                                ($student->full_name ?? '') . ' ' .
                                ($student->student_id ?? '') . ' ' .
                                ($idCard->card_number ?? '')
                            ) }}
                        ">

                        {{-- Number --}}
                        <td class="serial-number">

                            {{ $idCards->firstItem() + $index }}

                        </td>


                        {{-- Student --}}
                        <td>

                            <div class="student-cell">

                                <div class="student-avatar">

                                    @if(!empty($student?->profile_image))

                                        <img src="{{ $student->profile_image }}"
                                             alt="{{ $student->full_name }}">

                                    @else

                                        <i class="bi bi-person-fill"></i>

                                    @endif

                                </div>

                                <div>

                                    <div class="student-name">
                                        {{ $student->full_name ?? 'Student' }}
                                    </div>

                                    <div class="student-phone">

                                        {{ $student->phone ?? 'No phone number' }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- Student ID --}}
                        <td>

                            <span class="student-id-badge">

                                {{ $student->student_id ?? '—' }}

                            </span>

                        </td>


                        {{-- Card Number --}}
                        <td>

                            <span class="card-number">

                                {{ $idCard->card_number }}

                            </span>

                        </td>


                        {{-- Class --}}
                        <td>

                            <div class="class-info">

                                <strong>
                                    {{ $student->class ?? '—' }}
                                </strong>

                                @if(!empty($student->section))

                                    <span>
                                        Section {{ $student->section }}
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- Template --}}
                        <td>

                            <span class="template-badge
                                template-{{ strtolower($idCard->template) }}">

                                <i class="bi bi-layout-text-window-reverse"></i>

                                {{ ucfirst(str_replace('_', ' ', $idCard->template)) }}

                            </span>

                        </td>


                        {{-- Academic Year --}}
                        <td>

                            <span class="academic-year">

                                <i class="bi bi-calendar3 me-1"></i>

                                {{ $idCard->academic_year ?? '—' }}

                            </span>

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($idCard->status === 'active')

                                <span class="status-badge active">

                                    <span class="status-dot"></span>

                                    Active

                                </span>

                            @else

                                <span class="status-badge inactive">

                                    <span class="status-dot"></span>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="action-buttons">

                                <a href="{{ route('admin.id-card.show', $idCard->id) }}"
                                   class="action-btn view"
                                   title="View">

                                    <i class="bi bi-eye"></i>

                                </a>


                                <a href="{{ route('admin.id-card.print', $idCard->id) }}"
                                   target="_blank"
                                   class="action-btn print"
                                   title="Print">

                                    <i class="bi bi-printer"></i>

                                </a>


                                <form action="{{ route('admin.id-card.destroy', $idCard->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this ID card?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="action-btn delete"
                                            title="Delete">

                                        <i class="bi bi-trash3"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr id="emptyRow">

                        <td colspan="9">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-person-vcard"></i>

                                </div>

                                <h5>
                                    No ID Cards Generated
                                </h5>

                                <p>
                                    Start by searching for a student and
                                    generating their ID card.
                                </p>

                                <a href="{{ route('admin.id-card.create') }}"
                                   class="btn btn-primary">

                                    <i class="bi bi-plus-lg me-1"></i>
                                    Generate First ID Card

                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($idCards->hasPages())

            <div class="pagination-wrapper">

                <div class="pagination-info">

                    Showing
                    <strong>{{ $idCards->firstItem() }}</strong>
                    to
                    <strong>{{ $idCards->lastItem() }}</strong>
                    of
                    <strong>{{ $idCards->total() }}</strong>
                    cards

                </div>

                <div>

                    {{ $idCards->links() }}

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
         INFORMATION BAR
    ========================================================== --}}

    <div class="info-bar mt-4">

        <div class="info-icon">
            <i class="bi bi-lightbulb-fill"></i>
        </div>

        <div>

            <strong>
                ID Card Management
            </strong>

            <p>
                Student information is linked directly to the student record.
                Changes made to the student profile will automatically be
                reflected when the card is viewed again.
            </p>

        </div>

    </div>

</div>


{{-- =============================================================
     STYLES
============================================================= --}}
<style>

.id-card-index {
    max-width: 1600px;
}


/* =============================================================
   HEADER
============================================================= */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.eyebrow {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: #64748b;
    margin-bottom: 5px;
}

.page-title {
    font-weight: 800;
    color: #172033;
    margin-bottom: 4px;
}

.page-subtitle {
    color: #64748b;
}

.header-actions {
    display: flex;
    gap: 10px;
}


/* =============================================================
   STAT CARDS
============================================================= */

.stat-card {
    background: #fff;
    border: 1px solid #e7ebf2;
    border-radius: 16px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
    height: 100%;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-icon.blue {
    background: #eef4ff;
    color: #2563eb;
}

.stat-icon.green {
    background: #ecfdf3;
    color: #16a34a;
}

.stat-icon.orange {
    background: #fff7ed;
    color: #ea580c;
}

.stat-icon.purple {
    background: #f3eefe;
    color: #7c3aed;
}

.stat-label {
    display: block;
    color: #8a93a2;
    font-size: 11px;
    margin-bottom: 2px;
}

.stat-value {
    display: block;
    color: #172033;
    font-size: 23px;
    font-weight: 800;
}


/* =============================================================
   CONTENT CARD
============================================================= */

.content-card {
    background: #fff;
    border: 1px solid #e7ebf2;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
    overflow: hidden;
}

.content-card-header {
    padding: 20px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #edf0f5;
}

.content-card-header h5 {
    margin: 0 0 4px;
    font-weight: 800;
    color: #172033;
}

.content-card-header h5 i {
    color: #2563eb;
}

.content-card-header p {
    margin: 0;
    font-size: 12px;
    color: #8a93a2;
}

.header-count {
    padding: 7px 12px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
}


/* =============================================================
   FILTER
============================================================= */

.filter-bar {
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fafbfc;
    border-bottom: 1px solid #edf0f5;
}

.filter-search {
    position: relative;
    flex: 1;
}

.filter-search i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.filter-search input {
    padding-left: 38px;
}

.filter-select {
    width: 160px;
}


/* =============================================================
   TABLE
============================================================= */

.id-card-table {
    min-width: 1100px;
}

.id-card-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .6px;
    font-weight: 800;
    padding: 13px 14px;
    border-bottom: 1px solid #e7ebf2;
    white-space: nowrap;
}

.id-card-table tbody td {
    padding: 15px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f5;
}

.id-card-table tbody tr {
    transition: background .15s ease;
}

.id-card-table tbody tr:hover {
    background: #fbfdff;
}

.serial-number {
    color: #94a3b8;
    font-size: 12px;
    font-weight: 700;
}


/* =============================================================
   STUDENT
============================================================= */

.student-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.student-avatar {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: #eef2f7;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    color: #94a3b8;
    font-size: 20px;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-name {
    color: #263247;
    font-size: 13px;
    font-weight: 750;
    margin-bottom: 3px;
}

.student-phone {
    color: #94a3b8;
    font-size: 10px;
}

.student-id-badge {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 7px;
    background: #eef4ff;
    color: #2563eb;
    font-size: 11px;
    font-weight: 700;
}

.card-number {
    color: #475569;
    font-family: monospace;
    font-size: 11px;
    font-weight: 700;
}

.class-info strong {
    display: block;
    color: #374151;
    font-size: 12px;
}

.class-info span {
    display: block;
    color: #94a3b8;
    font-size: 10px;
    margin-top: 2px;
}

.academic-year {
    color: #64748b;
    font-size: 11px;
    white-space: nowrap;
}


/* =============================================================
   TEMPLATE BADGES
============================================================= */

.template-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
}

.template-modern {
    background: #eef2ff;
    color: #4f46e5;
}

.template-compact {
    background: #f0fdfa;
    color: #0f766e;
}

.template-front_back {
    background: #fdf4ff;
    color: #a21caf;
}


/* =============================================================
   STATUS
============================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
}

.status-badge.active {
    background: #ecfdf3;
    color: #15803d;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #b91c1c;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}


/* =============================================================
   ACTION BUTTONS
============================================================= */

.action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.action-btn {
    width: 31px;
    height: 31px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 8px;
    text-decoration: none;
    transition: .15s ease;
}

.action-btn.view {
    background: #eef4ff;
    color: #2563eb;
}

.action-btn.print {
    background: #f0fdf4;
    color: #15803d;
}

.action-btn.delete {
    background: #fef2f2;
    color: #dc2626;
}

.action-btn:hover {
    transform: translateY(-1px);
    filter: brightness(.96);
}


/* =============================================================
   EMPTY STATE
============================================================= */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    border-radius: 20px;
    background: #eef4ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.empty-state h5 {
    font-weight: 800;
    color: #263247;
}

.empty-state p {
    color: #8992a3;
    font-size: 13px;
    margin-bottom: 20px;
}


/* =============================================================
   PAGINATION
============================================================= */

.pagination-wrapper {
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-top: 1px solid #edf0f5;
}

.pagination-info {
    color: #8a93a2;
    font-size: 11px;
}

.pagination-info strong {
    color: #475569;
}


/* =============================================================
   INFO BAR
============================================================= */

.info-bar {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px 17px;
    border: 1px solid #dbeafe;
    background: #eff6ff;
    border-radius: 14px;
}

.info-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #dbeafe;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-bar strong {
    display: block;
    color: #1e3a8a;
    font-size: 12px;
    margin-bottom: 2px;
}

.info-bar p {
    color: #52647c;
    font-size: 11px;
    margin: 0;
    line-height: 1.5;
}


/* =============================================================
   RESPONSIVE
============================================================= */

@media (max-width: 767px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions .btn {
        flex: 1;
    }

    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-select {
        width: 100%;
    }

    .content-card-header {
        align-items: flex-start;
        gap: 10px;
    }

    .pagination-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

}

</style>


{{-- =============================================================
     SEARCH / FILTER SCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('cardSearch');

    const statusFilter =
        document.getElementById('statusFilter');

    const rows =
        document.querySelectorAll('.card-row');


    function filterCards() {

        const search =
            searchInput.value
                .toLowerCase()
                .trim();

        const status =
            statusFilter.value
                .toLowerCase()
                .trim();

        let visibleCount = 0;


        rows.forEach(function (row) {

            const rowSearch =
                row.dataset.search
                    .toLowerCase();

            const rowStatus =
                row.dataset.status
                    .toLowerCase();


            const matchesSearch =
                !search ||
                rowSearch.includes(search);


            const matchesStatus =
                !status ||
                rowStatus === status;


            if (
                matchesSearch &&
                matchesStatus
            ) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        let noResults =
            document.getElementById(
                'filterNoResults'
            );


        if (visibleCount === 0 && rows.length > 0) {

            if (!noResults) {

                noResults =
                    document.createElement('tr');

                noResults.id =
                    'filterNoResults';

                noResults.innerHTML = `
                    <td colspan="9">
                        <div class="empty-state py-5">

                            <div class="empty-icon">
                                <i class="bi bi-search"></i>
                            </div>

                            <h5>No matching ID cards</h5>

                            <p>
                                Try a different student name,
                                Student ID or card number.
                            </p>

                        </div>
                    </td>
                `;

                document
                    .getElementById('idCardTableBody')
                    .appendChild(noResults);

            }

            noResults.style.display = '';

        } else if (noResults) {

            noResults.style.display = 'none';

        }

    }


    searchInput.addEventListener(
        'input',
        filterCards
    );


    statusFilter.addEventListener(
        'change',
        filterCards
    );

});

</script>

@endsection
