@extends('layouts.app')

@section('title', 'Teacher Allocations')
@section('page-title', 'Teacher Allocations')

@section('content')

<style>

/* =========================================================
   TEACHER ALLOCATION PAGE
========================================================= */

.teacher-allocation {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
}


/* =========================================================
   HEADER
========================================================= */

.ta-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.ta-title h2 {
    margin: 0 0 6px;
    color: #172033;
    font-size: 28px;
    font-weight: 800;
}

.ta-title p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

.ta-btn {
    padding: 10px 16px;
    border-radius: 9px;
    background: #1769d1;
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 5px 14px rgba(23,105,209,.18);
    transition: .2s ease;
}

.ta-btn:hover {
    background: #1268ca;
    color: #fff;
    transform: translateY(-2px);
}


/* =========================================================
   STAT CARDS
========================================================= */

.ta-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.ta-stat {
    position: relative;
    overflow: hidden;

    height: 115px;
    min-height: 115px;

    padding: 18px 20px;

    border-radius: 15px;

    color: #fff;

    display: flex;
    flex-direction: column;
    justify-content: center;

    box-shadow: 0 7px 18px rgba(15,23,42,.11);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.ta-stat:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(15,23,42,.16);
}


/* Decorative circles */

.ta-stat::before {
    content: "";

    position: absolute;

    width: 110px;
    height: 110px;

    right: -35px;
    top: -55px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);
}

.ta-stat::after {
    content: "";

    position: absolute;

    width: 60px;
    height: 60px;

    right: 15px;
    bottom: -35px;

    border-radius: 50%;

    background: rgba(255,255,255,.08);
}


/* =========================================================
   CARD COLORS
========================================================= */

.ta-stat.blue {
    background: linear-gradient(
        135deg,
        #1769d1,
        #237de0
    );
}

.ta-stat.orange {
    background: linear-gradient(
        135deg,
        #ed9208,
        #f7aa25
    );
}

.ta-stat.cyan {
    background: linear-gradient(
        135deg,
        #079dbd,
        #16b5d0
    );
}

.ta-stat small {
    position: relative;
    z-index: 2;

    margin-bottom: 6px;

    color: rgba(255,255,255,.95);

    font-size: 13px;
    font-weight: 600;
}

.ta-stat strong {
    position: relative;
    z-index: 2;

    color: #fff;

    font-size: 27px;
    line-height: 1;

    font-weight: 800;
}


/* =========================================================
   TABLE CARD
========================================================= */

.ta-card {
    overflow: hidden;

    background: #fff;

    border: 1px solid #e5ebf3;

    border-radius: 16px;

    box-shadow: 0 5px 20px rgba(15,23,42,.06);
}


/* =========================================================
   TABLE
========================================================= */

.ta-table {
    width: 100%;
    border-collapse: collapse;
}

.ta-table th,
.ta-table td {
    padding: 14px 18px;

    text-align: left;

    border-bottom: 1px solid #edf1f6;
}

.ta-table th {
    background: #f8fafc;

    color: #374151;

    font-size: 12px;
    font-weight: 700;
}

.ta-table td {
    color: #4b5563;

    font-size: 14px;
}

.ta-table tbody tr {
    transition: .2s ease;
}

.ta-table tbody tr:hover {
    background: #f8fbff;
}


/* =========================================================
   TEACHER NAME
========================================================= */

.ta-teacher {
    color: #172033 !important;
    font-weight: 700;
}


/* =========================================================
   CLASS BADGE
========================================================= */

.ta-class {
    display: inline-block;

    padding: 5px 10px;

    border-radius: 7px;

    background: #e7f0ff;

    color: #1769d1;

    font-size: 12px;
    font-weight: 700;
}


/* =========================================================
   ACTION BUTTONS
========================================================= */

.ta-actions {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.ta-actions form {
    display: inline-flex !important;
    margin: 0 !important;
    padding: 0 !important;
}


/* COMMON ACTION BUTTON */

.ta-view,
.ta-edit,
.ta-delete {
    width: 40px !important;
    height: 40px !important;

    min-width: 40px !important;
    min-height: 40px !important;

    padding: 0 !important;
    margin: 0 !important;

    display: inline-flex !important;

    align-items: center !important;
    justify-content: center !important;

    border: none !important;

    border-radius: 10px !important;

    text-decoration: none !important;

    cursor: pointer !important;

    line-height: 1 !important;

    box-shadow: none !important;

    transition:
        transform .2s ease,
        background .2s ease,
        color .2s ease !important;
}


/* =========================================================
   VIEW
========================================================= */

.ta-view {
    background: #eaf3ff !important;
    color: #1477df !important;
}

.ta-view:hover {
    background: #1477df !important;
    color: #ffffff !important;

    transform: translateY(-2px);
}


/* =========================================================
   EDIT
========================================================= */

.ta-edit {
    background: #f0edff !important;
    color: #6c63ff !important;
}

.ta-edit:hover {
    background: #6c63ff !important;
    color: #ffffff !important;

    transform: translateY(-2px);
}


/* =========================================================
   DELETE
========================================================= */

.ta-delete {
    background: #fff0f0 !important;
    color: #ef2028 !important;
}

.ta-delete:hover {
    background: #ef2028 !important;
    color: #ffffff !important;

    transform: translateY(-2px);
}


/* =========================================================
   ACTION ICONS
   INLINE SVG - NO BOOTSTRAP ICON DEPENDENCY
========================================================= */

.ta-actions svg {
    width: 15px !important;
    height: 15px !important;

    display: block !important;

    fill: none !important;

    stroke: currentColor !important;
    stroke-width: 2 !important;

    stroke-linecap: round !important;
    stroke-linejoin: round !important;

    flex-shrink: 0 !important;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.ta-empty {
    padding: 40px !important;

    text-align: center !important;

    color: #94a3b8 !important;

    font-size: 13px !important;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 850px) {

    .teacher-allocation {
        padding: 18px;
    }

    .ta-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .ta-stats {
        grid-template-columns: 1fr;
    }

    .ta-stat {
        height: 105px;
        min-height: 105px;
    }

    .ta-card {
        overflow-x: auto;
    }

    .ta-table {
        min-width: 850px;
    }
}


/* =========================================================
   MOBILE ACTION BUTTONS
========================================================= */

@media (max-width: 650px) {

    .ta-title h2 {
        font-size: 24px;
    }

    .ta-actions {
        gap: 8px !important;
    }

    .ta-view,
    .ta-edit,
    .ta-delete {
        width: 36px !important;
        height: 36px !important;

        min-width: 36px !important;
        min-height: 36px !important;

        border-radius: 9px !important;
    }

    .ta-actions svg {
        width: 14px !important;
        height: 14px !important;
    }

}

</style>


<div class="teacher-allocation">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="ta-header">

        <div class="ta-title">

            <h2>
                Teacher Allocations
            </h2>

            <p>
                Manage teachers allocated to classes and sections.
            </p>

        </div>


        <a
            href="{{ route('admin.teachers.assign-class.create') }}"
            class="ta-btn"
        >
            + Allocate Teacher
        </a>

    </div>



    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="ta-stats">


        {{-- TOTAL ALLOCATIONS --}}

        <div class="ta-stat blue">

            <small>
                Total Allocations
            </small>

            <strong>
                {{ $assignments->count() }}
            </strong>

        </div>


        {{-- TEACHERS --}}

        <div class="ta-stat orange">

            <small>
                Teachers
            </small>

            <strong>
                {{ $assignments->unique('teacher_id')->count() }}
            </strong>

        </div>


        {{-- CLASSES --}}

        <div class="ta-stat cyan">

            <small>
                Classes
            </small>

            <strong>
                {{ $assignments->unique('class_id')->count() }}
            </strong>

        </div>


    </div>



    {{-- =====================================================
         ALLOCATION TABLE
    ====================================================== --}}

    <div class="ta-card">

        <table class="ta-table">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Teacher</th>

                    <th>Class</th>

                    <th>Section</th>

                    <th>Academic Year</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>

                @forelse($assignments as $assignment)

                    <tr>


                        {{-- NUMBER --}}

                        <td>
                            {{ $loop->iteration }}
                        </td>



                        {{-- TEACHER --}}

                        <td class="ta-teacher">

                            {{ $assignment->first_name }}
                            {{ $assignment->last_name }}

                        </td>



                        {{-- CLASS --}}

                        <td>

                            <span class="ta-class">
                                {{ $assignment->class_name }}
                            </span>

                        </td>



                        {{-- SECTION --}}

                        <td>
                            {{ $assignment->section_name }}
                        </td>



                        {{-- ACADEMIC YEAR --}}

                        <td>
                            {{ $assignment->academic_year ?: '—' }}
                        </td>



                        {{-- ACTIONS --}}

                        <td>

                            <div class="ta-actions">


                                {{-- VIEW TEACHER --}}

                                <a
                                    href="{{ route('admin.teachers.show', $assignment->teacher_id) }}"
                                    class="ta-view"
                                    title="View Teacher"
                                    aria-label="View Teacher"
                                >

                                    {{-- Eye icon --}}

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                        />
                                    </svg>

                                </a>



                                {{-- EDIT ALLOCATION --}}

                                <a
                                    href="{{ route('admin.teachers.assign-class.edit', $assignment->id) }}"
                                    class="ta-edit"
                                    title="Edit Allocation"
                                    aria-label="Edit Allocation"
                                >

                                    {{-- Pencil icon --}}

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M12 20h9"
                                        />

                                        <path
                                            d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"
                                        />
                                    </svg>

                                </a>



                                {{-- DELETE ALLOCATION --}}

                                <form
                                    action="{{ route('admin.teachers.assign-class.destroy', $assignment->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this teacher allocation?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="ta-delete"
                                        title="Delete Allocation"
                                        aria-label="Delete Allocation"
                                    >

                                        {{-- Trash icon --}}

                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M3 6h18"
                                            />

                                            <path
                                                d="M8 6V4h8v2"
                                            />

                                            <path
                                                d="M19 6l-1 14H6L5 6"
                                            />

                                            <path
                                                d="M10 11v5"
                                            />

                                            <path
                                                d="M14 11v5"
                                            />
                                        </svg>

                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="ta-empty"
                        >
                            No teacher allocations found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


</div>

@endsection