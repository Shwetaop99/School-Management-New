```blade
@extends('layouts.app')

@section('title', 'Student Kit Distribution Details')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary"
                    style="width:52px;height:52px;"
                >
                    <i class="bi bi-box-seam-fill fs-4"></i>
                </div>

                <div>

                    <h3 class="fw-bold mb-1">
                        Student Kit Distribution Details
                    </h3>

                    <p class="text-muted mb-0">
                        Government supplied kit distribution record
                    </p>

                </div>

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route(
                    'admin.student-supply-kits.print',
                    $studentSupplyKit
                ) }}"
                target="_blank"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-printer me-1"></i>
                Print
            </a>


            <a
                href="{{ route(
                    'admin.student-supply-kits.edit',
                    $studentSupplyKit
                ) }}"
                class="btn btn-warning"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </a>


            <a
                href="{{ route('admin.student-supply-kits.index') }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        STATUS BANNER
    ========================================================== --}}
    @if($studentSupplyKit->status === 'issued')

        <div class="alert alert-success border-0 shadow-sm">

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white"
                    style="width:42px;height:42px;"
                >
                    <i class="bi bi-check-lg fs-5"></i>
                </div>

                <div>

                    <div class="fw-bold">
                        Government Kit Issued
                    </div>

                    <div class="small">
                        This government supply kit has been distributed to the student.
                    </div>

                </div>

            </div>

        </div>

    @elseif($studentSupplyKit->status === 'pending')

        <div class="alert alert-warning border-0 shadow-sm">

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark"
                    style="width:42px;height:42px;"
                >
                    <i class="bi bi-clock-fill"></i>
                </div>

                <div>

                    <div class="fw-bold">
                        Distribution Pending
                    </div>

                    <div class="small">
                        This government kit has not yet been marked as issued.
                    </div>

                </div>

            </div>

        </div>

    @elseif($studentSupplyKit->status === 'cancelled')

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="d-flex align-items-center gap-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-circle bg-danger text-white"
                    style="width:42px;height:42px;"
                >
                    <i class="bi bi-x-lg fs-5"></i>
                </div>

                <div>

                    <div class="fw-bold">
                        Distribution Cancelled
                    </div>

                    <div class="small">
                        This government kit distribution has been cancelled.
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        STUDENT INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-person-vcard-fill text-primary fs-5"></i>

                <div>

                    <h5 class="fw-bold mb-0">
                        Student Information
                    </h5>

                    <small class="text-muted">
                        Student who received the government kit
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            @php

                $student = $studentSupplyKit->student;

                $studentName = $student
                    ? collect([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name
                    ])->filter()->implode(' ')
                    : 'Student Not Available';

            @endphp


            <div class="row g-4">

                {{-- Student --}}
                <div class="col-lg-5">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width:64px;height:64px;"
                        >
                            <i class="bi bi-person-fill fs-3"></i>
                        </div>

                        <div>

                            <div class="text-muted small">
                                Student Name
                            </div>

                            <div class="fw-bold fs-5">
                                {{ $studentName }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Student ID --}}
                <div class="col-lg-3">

                    <div class="text-muted small mb-1">
                        Student ID
                    </div>

                    <div class="fw-semibold">
                        {{ $student?->student_id ?? '-' }}
                    </div>

                </div>


                {{-- Class --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Class
                    </div>

                    <div class="fw-semibold">

                        {{ $student?->class ?? '-' }}

                        @if($student?->section)
                            <span class="text-muted">
                                / {{ $student->section }}
                            </span>
                        @endif

                    </div>

                </div>


                {{-- Phone --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Phone
                    </div>

                    <div class="fw-semibold">
                        {{ $student?->phone ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DISTRIBUTION INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-clipboard-check-fill text-primary fs-5"></i>

                <div>

                    <h5 class="fw-bold mb-0">
                        Distribution Information
                    </h5>

                    <small class="text-muted">
                        Government kit distribution details
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <div class="row g-4">

                {{-- Government Kit --}}
                <div class="col-lg-4">

                    <div class="text-muted small mb-1">
                        Government Kit
                    </div>

                    <div class="fw-bold">

                        {{ $studentSupplyKit->kitTemplate?->kit_name ?? 'Kit Not Available' }}

                    </div>

                </div>


                {{-- Applicable Class --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Kit Class
                    </div>

                    <div class="fw-semibold">

                        {{ $studentSupplyKit->kitTemplate?->class ?? '-' }}

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Academic Year
                    </div>

                    <div>

                        <span class="badge bg-light text-dark border">
                            {{ $studentSupplyKit->academic_year ?? '-' }}
                        </span>

                    </div>

                </div>


                {{-- Distribution Date --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Distribution Date
                    </div>

                    <div class="fw-semibold">

                        @if($studentSupplyKit->issue_date)

                            {{ \Carbon\Carbon::parse(
                                $studentSupplyKit->issue_date
                            )->format('d M Y') }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-lg-2">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <div>

                        @if($studentSupplyKit->status === 'issued')

                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Issued
                            </span>

                        @elseif($studentSupplyKit->status === 'pending')

                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>
                                Pending
                            </span>

                        @elseif($studentSupplyKit->status === 'cancelled')

                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ ucfirst($studentSupplyKit->status ?? 'Unknown') }}
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Remarks --}}
                @if($studentSupplyKit->remarks)

                    <div class="col-12">

                        <div class="border rounded p-3 bg-light">

                            <div class="text-muted small mb-1">
                                Remarks
                            </div>

                            <div>
                                {{ $studentSupplyKit->remarks }}
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        DISTRIBUTED ITEMS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-boxes text-primary fs-5"></i>

                        <h5 class="fw-bold mb-0">
                            Distributed Items
                        </h5>

                    </div>

                    <small class="text-muted">
                        Items actually provided to the student
                    </small>

                </div>


                <span class="badge bg-primary bg-opacity-10 text-primary">

                    {{ $studentSupplyKit->items->count() }}

                    {{ $studentSupplyKit->items->count() === 1 ? 'Item' : 'Items' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($studentSupplyKit->items->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th
                                    class="ps-4"
                                    style="width:70px;"
                                >
                                    #
                                </th>

                                <th>
                                    Supply Item
                                </th>

                                <th>
                                    Item Code
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th class="text-center">
                                    Quantity
                                </th>

                                <th class="text-center">
                                    Condition
                                </th>

                                <th class="pe-4">
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($studentSupplyKit->items as $index => $distributionItem)

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4 text-muted">

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Supply Item --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:40px;height:40px;"
                                            >
                                                <i class="bi bi-box-seam"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold">

                                                    {{
                                                        $distributionItem->supplyItem?->item_name
                                                        ?? 'Item Not Available'
                                                    }}

                                                </div>

                                                @if($distributionItem->supplyItem?->category)

                                                    <div class="small text-muted">

                                                        {{
                                                            $distributionItem->supplyItem->category
                                                        }}

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Code --}}
                                    <td>

                                        @if($distributionItem->supplyItem?->item_code)

                                            <span class="badge bg-light text-dark border">

                                                {{
                                                    $distributionItem->supplyItem->item_code
                                                }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Unit --}}
                                    <td>

                                        {{ $distributionItem->supplyItem?->unit ?? '-' }}

                                    </td>


                                    {{-- Quantity --}}
                                    <td class="text-center">

                                        <span class="badge bg-primary">

                                            {{ $distributionItem->quantity }}

                                        </span>

                                    </td>


                                    {{-- Condition --}}
                                    <td class="text-center">

                                        @php
                                            $condition = $distributionItem->condition ?? 'New';
                                        @endphp


                                        @if(strtolower($condition) === 'new')

                                            <span class="badge bg-success">
                                                New
                                            </span>

                                        @elseif(strtolower($condition) === 'good')

                                            <span class="badge bg-primary">
                                                Good
                                            </span>

                                        @elseif(strtolower($condition) === 'damaged')

                                            <span class="badge bg-danger">
                                                Damaged
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $condition }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Remarks --}}
                                    <td class="pe-4">

                                        @if($distributionItem->remarks)

                                            <span>
                                                {{ $distributionItem->remarks }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    TOTAL
                ================================================== --}}
                <div class="border-top bg-light px-4 py-3">

                    <div class="row align-items-center">

                        <div class="col-md-8">

                            <span class="text-muted">
                                Total quantity distributed:
                            </span>

                            <strong class="ms-1">

                                {{
                                    $studentSupplyKit->items->sum('quantity')
                                }}

                            </strong>

                        </div>


                        <div class="col-md-4 text-md-end mt-2 mt-md-0">

                            <span class="text-muted me-2">
                                Distribution Status:
                            </span>

                            @if($studentSupplyKit->status === 'issued')

                                <span class="badge bg-success">
                                    Issued
                                </span>

                            @elseif($studentSupplyKit->status === 'pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Cancelled
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-box2 text-muted fs-1"></i>

                    <h6 class="fw-bold mt-3">
                        No Distribution Items
                    </h6>

                    <p class="text-muted mb-0">
                        No items are attached to this distribution record.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        RECORD INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Distribution Record ID
                    </div>

                    <div class="fw-semibold">
                        #{{ $studentSupplyKit->id }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Created On
                    </div>

                    <div class="fw-semibold">

                        @if($studentSupplyKit->created_at)

                            {{ $studentSupplyKit->created_at->format('d M Y, h:i A') }}

                        @else

                            -

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Last Updated
                    </div>

                    <div class="fw-semibold">

                        @if($studentSupplyKit->updated_at)

                            {{ $studentSupplyKit->updated_at->format('d M Y, h:i A') }}

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BOTTOM ACTIONS
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

        <a
            href="{{ route('admin.student-supply-kits.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Distribution
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.student-supply-kits.edit',
                    $studentSupplyKit
                ) }}"
                class="btn btn-warning"
            >
                <i class="bi bi-pencil-square me-1"></i>
                Edit Distribution
            </a>


            <a
                href="{{ route(
                    'admin.student-supply-kits.print',
                    $studentSupplyKit
                ) }}"
                target="_blank"
                class="btn btn-primary"
            >
                <i class="bi bi-printer me-1"></i>
                Print Distribution
            </a>

        </div>

    </div>

</div>

@endsection
