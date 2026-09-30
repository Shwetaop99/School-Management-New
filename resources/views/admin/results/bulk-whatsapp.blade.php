
@extends('layouts.app')

@section('title', 'Bulk WhatsApp Result Notification')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">

            <a href="{{ route('admin.results.class-results', [
                'exam_id' => request('exam_id'),
                'exam_class_id' => request('exam_class_id'),
                'class_id' => request('class_id'),
                'section' => request('section'),
            ]) }}"
               class="btn btn-light border rounded-3">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div class="page-icon">
                <i class="bi bi-whatsapp"></i>
            </div>

            <div>
                <h4 class="fw-bold mb-1">
                    Bulk WhatsApp Result Notification
                </h4>

                <div class="text-muted small">
                    Send online result links to selected parents
                </div>
            </div>

        </div>

        <div>
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="bi bi-people me-1"></i>
                {{ $students->count() }} Students
            </span>
        </div>

    </div>


    {{-- =========================================================
        CLASS / EXAM INFORMATION
    ========================================================== --}}
    <div class="info-card mb-4">

        <div class="d-flex align-items-center gap-3">

            <div class="info-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>

                <div class="fw-bold">

                    {{ $schoolClass->class_name ?? 'Class' }}

                    @if(!empty($schoolClass->section))
                        - Section {{ $schoolClass->section }}
                    @endif

                </div>

                <div class="small text-muted">

                    {{ $exam->exam_name ?? 'Examination' }}

                    •

                    {{ $exam->academic_year ?? '' }}

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        PROGRESS CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">

                <div>

                    <div class="small text-muted">
                        Bulk Sending Progress
                    </div>

                    <h5 class="fw-bold mb-0" id="progressTitle">
                        Ready to Start
                    </h5>

                </div>

                <div class="text-end">

                    <div class="fw-bold fs-5" id="progressCount">
                        0 / 0
                    </div>

                    <div class="small text-muted">
                        Students completed
                    </div>

                </div>

            </div>


            <div class="progress progress-custom mb-3">

                <div
                    class="progress-bar"
                    id="bulkProgress"
                    role="progressbar"
                    style="width: 0%;">
                </div>

            </div>


            <div class="d-flex flex-wrap gap-2">

                <button
                    type="button"
                    id="startBulkBtn"
                    class="btn btn-success px-4 rounded-3"
                    onclick="startBulkWhatsApp()">

                    <i class="bi bi-whatsapp me-2"></i>
                    WhatsApp

                </button>


                <button
                    type="button"
                    id="nextStudentBtn"
                    class="btn btn-primary px-4 rounded-3 d-none"
                    onclick="openNextAfterSent()">

                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Mark Sent & Next

                </button>


                <button
                    type="button"
                    id="pauseBulkBtn"
                    class="btn btn-outline-secondary px-4 rounded-3 d-none"
                    onclick="pauseBulkWhatsApp()">

                    <i class="bi bi-pause-circle me-2"></i>
                    Pause

                </button>


                <button
                    type="button"
                    id="resumeBulkBtn"
                    class="btn btn-outline-primary px-4 rounded-3 d-none"
                    onclick="resumeBulkWhatsApp()">

                    <i class="bi bi-play-circle me-2"></i>
                    Resume

                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CURRENT STUDENT CARD
    ========================================================== --}}
    <div
        class="current-student-card d-none mb-4"
        id="currentStudentCard">

        <div class="current-student-inner">

            <div class="current-avatar" id="currentAvatar">
                --
            </div>

            <div class="flex-grow-1">

                <div class="small text-muted">
                    Currently Processing
                </div>

                <h5 class="fw-bold mb-1" id="currentStudentName">
                    -
                </h5>

                <div class="small text-muted">

                    Student ID:

                    <strong id="currentStudentId">-</strong>

                    <span class="mx-2">•</span>

                    Parent:

                    <strong id="currentStudentPhone">-</strong>

                </div>

            </div>

            <div class="text-end">

                <div
                    class="badge bg-success-subtle text-success px-3 py-2"
                    id="currentStatus">

                    Ready

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STUDENT LIST
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-people me-2 text-primary"></i>

                        Students

                    </h5>

                    <div class="small text-muted">

                        Select the students who should receive the result link.

                    </div>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary rounded-3"
                        onclick="selectAllStudents()">

                        <i class="bi bi-check2-square me-1"></i>
                        Select All

                    </button>


                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary rounded-3"
                        onclick="clearAllStudents()">

                        <i class="bi bi-x-square me-1"></i>
                        Clear

                    </button>


                    <span
                        class="badge bg-primary-subtle text-primary px-3 py-2"
                        id="selectedCount">

                        0 Selected

                    </span>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle mb-0 student-table">

                <thead>

                    <tr>

                        <th width="55">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="selectAllCheckbox"
                                onchange="toggleAllStudents(this)">

                        </th>

                        <th>#</th>

                        <th>Student</th>

                        <th>Student ID</th>

                        <th>Parent WhatsApp</th>

                        <th>Result</th>

                        <th>Publication</th>

                        <th>Status</th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="studentTableBody">

                @forelse($students as $index => $student)

                    @php

                        $result = $results->get($student->id);

                        $studentName = collect([
                            $student->first_name ?? null,
                            $student->middle_name ?? null,
                            $student->last_name ?? null,
                        ])
                        ->filter()
                        ->implode(' ');

                        $studentName = $studentName ?: 'Student';


                        $initials = collect(
                            preg_split('/\s+/', trim($studentName))
                        )
                        ->filter()
                        ->take(2)
                        ->map(fn($name) => strtoupper(substr($name, 0, 1)))
                        ->implode('');


                        $phone =
                            $student->parent_phone
                            ?? $student->father_phone
                            ?? $student->mother_phone
                            ?? $student->phone
                            ?? '';


                        $publicationStatus =
                            $result?->publication_status ?? 'pending';


                        $hasValidPhone = !empty($phone);

                    @endphp


                    <tr
                        class="student-row"

                        data-student-id="{{ $student->id }}"

                        data-name="{{ $studentName }}"

                        data-student-code="{{ $student->student_id ?? $student->id }}"

                        data-phone="{{ $phone }}"

                        data-publication="{{ $publicationStatus }}"

                        data-result-url="{{ route('result.public') }}"
                    >


                        {{-- CHECKBOX --}}
                        <td>

                            <input
                                type="checkbox"
                                class="form-check-input student-checkbox"

                                value="{{ $student->id }}"

                                onchange="updateSelectedCount()"

                                {{ !$hasValidPhone ? 'disabled' : '' }}
                            >

                        </td>


                        {{-- NUMBER --}}
                        <td class="text-muted fw-semibold">

                            {{ $index + 1 }}

                        </td>


                        {{-- STUDENT --}}
                        <td>

                            <div class="d-flex align-items-center gap-3">

                                <div class="student-avatar">

                                    {{ $initials ?: 'S' }}

                                </div>

                                <div>

                                    <div class="fw-semibold">

                                        {{ $studentName }}

                                    </div>

                                    <div class="small text-muted">

                                        Roll No.
                                        {{ $student->roll_number ?? '—' }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- STUDENT ID --}}
                        <td>

                            <span class="student-id">

                                {{ $student->student_id ?? $student->id }}

                            </span>

                        </td>


                        {{-- PARENT WHATSAPP --}}
                        <td>

                            @if($hasValidPhone)

                                <div class="d-flex align-items-center gap-2">

                                    <span class="whatsapp-dot"></span>

                                    <span>
                                        {{ $phone }}
                                    </span>

                                </div>

                            @else

                                <span class="text-danger small">

                                    <i class="bi bi-exclamation-circle me-1"></i>

                                    No number

                                </span>

                            @endif

                        </td>


                        {{-- RESULT --}}
                        <td>

                            @if($result)

                                <div class="fw-semibold text-success">

                                    Result Available

                                </div>

                                <div class="small text-muted">

                                    Online Result

                                </div>

                            @else

                                <span class="text-muted">

                                    Pending

                                </span>

                            @endif

                        </td>


                        {{-- PUBLICATION --}}
                        <td>

                            @php

                                $publicationClass = match($publicationStatus) {

                                    'published' => 'status-published',

                                    'approved' => 'status-approved',

                                    'verified' => 'status-verified',

                                    'generated' => 'status-generated',

                                    default => 'status-pending',

                                };

                            @endphp


                            <span class="status-pill {{ $publicationClass }}">

                                @if($publicationStatus === 'published')

                                    <i class="bi bi-check-circle-fill"></i>

                                @elseif($publicationStatus === 'approved')

                                    <i class="bi bi-shield-check"></i>

                                @elseif($publicationStatus === 'verified')

                                    <i class="bi bi-patch-check"></i>

                                @elseif($publicationStatus === 'generated')

                                    <i class="bi bi-file-earmark-check"></i>

                                @else

                                    <i class="bi bi-clock"></i>

                                @endif

                                {{ ucfirst($publicationStatus) }}

                            </span>

                        </td>


                        {{-- SEND STATUS --}}
                        <td>

                            <span
                                class="student-send-status pending-status"

                                data-status-for="{{ $student->id }}"
                            >

                                <i class="bi bi-clock me-1"></i>

                                Pending

                            </span>

                        </td>


                        {{-- ACTION --}}
                        <td class="text-end">

                            @if($hasValidPhone)

                                <button
                                    type="button"
                                    class="btn btn-sm btn-success rounded-3"

                                    onclick="openIndividualStudent({{ $student->id }})"

                                    title="Send WhatsApp Result Link"
                                >

                                    <i class="bi bi-whatsapp"></i>

                                </button>

                            @else

                                <button
                                    type="button"
                                    class="btn btn-sm btn-light border rounded-3"

                                    disabled

                                    title="Parent WhatsApp number unavailable"
                                >

                                    <i class="bi bi-slash-circle"></i>

                                </button>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                                <h5 class="fw-bold mt-3">

                                    No Students Found

                                </h5>

                                <p class="text-muted mb-0">

                                    There are no students available for this class.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}
        <div class="card-footer bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="small text-muted">

                    <i class="bi bi-info-circle me-1"></i>

                    The WhatsApp message contains only the student's
                    name, Student ID and online result link.

                    The parent must press Send in WhatsApp.

                </div>


                <a
                    href="{{ route('admin.results.class-results', [
                        'exam_id' => request('exam_id'),
                        'exam_class_id' => request('exam_class_id'),
                        'class_id' => request('class_id'),
                        'section' => request('section'),
                    ]) }}"

                    class="btn btn-light border rounded-3"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Class Results

                </a>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
<style>

    :root {

        --primary-blue: #1677f0;
        --primary-dark: #0f5dcc;
        --border-color: #e8edf3;
        --text-dark: #172033;
        --muted: #718096;

    }


    .page-icon {

        width: 48px;
        height: 48px;

        border-radius: 14px;

        background: #e9fff3;

        color: #25d366;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 23px;

    }


    .info-card {

        background: #fff;

        border: 1px solid var(--border-color);

        border-radius: 18px;

        padding: 18px 22px;

        box-shadow: 0 4px 18px rgba(25, 42, 70, .05);

    }


    .info-icon {

        width: 45px;
        height: 45px;

        border-radius: 13px;

        background: #edf5ff;

        color: var(--primary-blue);

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 20px;

    }


    .progress-custom {

        height: 10px;

        background: #edf1f6;

        border-radius: 20px;

        overflow: hidden;

    }


    .progress-custom .progress-bar {

        background: #25d366;

        border-radius: 20px;

        transition: width .35s ease;

    }


    .current-student-card {

        border: 1px solid #cceedd;

        background: #f4fff8;

        border-radius: 18px;

        padding: 5px;

    }


    .current-student-inner {

        display: flex;

        align-items: center;

        gap: 15px;

        padding: 18px;

    }


    .current-avatar {

        width: 54px;
        height: 54px;

        border-radius: 15px;

        background: #25d366;

        color: #fff;

        display: flex;

        align-items: center;
        justify-content: center;

        font-weight: 700;

        font-size: 18px;

    }


    .student-table thead th {

        background: #f8fafc;

        color: #667085;

        font-size: 12px;

        text-transform: uppercase;

        letter-spacing: .04em;

        font-weight: 700;

        padding: 14px 16px;

        border-bottom: 1px solid var(--border-color);

        white-space: nowrap;

    }


    .student-table tbody td {

        padding: 15px 16px;

        border-bottom: 1px solid #f0f2f5;

    }


    .student-table tbody tr {

        transition: background .2s ease;

    }


    .student-table tbody tr:hover {

        background: #fafcff;

    }


    .student-avatar {

        width: 42px;
        height: 42px;

        min-width: 42px;

        border-radius: 12px;

        background: #edf5ff;

        color: var(--primary-blue);

        display: flex;

        align-items: center;
        justify-content: center;

        font-weight: 700;

        font-size: 13px;

    }


    .student-id {

        font-family: monospace;

        font-size: 13px;

        background: #f5f7fa;

        padding: 5px 8px;

        border-radius: 7px;

    }


    .whatsapp-dot {

        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #25d366;

        display: inline-block;

    }


    .status-pill {

        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;

    }


    .status-generated {

        background: #edf5ff;

        color: #1677f0;

    }


    .status-verified {

        background: #fff8e6;

        color: #b77900;

    }


    .status-approved {

        background: #f1edff;

        color: #6f42c1;

    }


    .status-published {

        background: #e9fff3;

        color: #16834a;

    }


    .status-pending {

        background: #f3f4f6;

        color: #6b7280;

    }


    .student-send-status {

        font-size: 12px;

        font-weight: 600;

        white-space: nowrap;

    }


    .pending-status {

        color: #98a2b3;

    }


    .opened-status {

        color: #1677f0;

    }


    .sent-status {

        color: #16834a;

    }


    .skipped-status {

        color: #b77900;

    }


    .empty-state {

        text-align: center;

        padding: 70px 20px;

    }


    .empty-icon {

        width: 65px;
        height: 65px;

        border-radius: 18px;

        background: #f3f6fa;

        color: #98a2b3;

        display: flex;

        align-items: center;
        justify-content: center;

        margin: auto;

        font-size: 28px;

    }


    .btn {

        font-weight: 600;

    }


    @media (max-width: 768px) {

        .current-student-inner {

            align-items: flex-start;

            flex-wrap: wrap;

        }


        .current-student-inner .text-end {

            width: 100%;

            text-align: left !important;

        }


        .student-table {

            min-width: 1050px;

        }

    }

</style>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | Bulk WhatsApp State
    |--------------------------------------------------------------------------
    */

    let selectedStudents = [];

    let currentIndex = -1;

    let completedStudents = new Set();

    let openedStudents = new Set();

    let paused = false;


    /*
    |--------------------------------------------------------------------------
    | Public Result URL
    |--------------------------------------------------------------------------
    */

    const PUBLIC_RESULT_URL = @json(route('result.public'));


    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    function toggleAllStudents(checkbox) {

        const checkboxes =
            document.querySelectorAll(
                '.student-checkbox:not(:disabled)'
            );

        checkboxes.forEach(cb => {

            cb.checked = checkbox.checked;

        });

        updateSelectedCount();

    }


    function selectAllStudents() {

        const master =
            document.getElementById('selectAllCheckbox');

        master.checked = true;

        toggleAllStudents(master);

    }


    function clearAllStudents() {

        document
            .querySelectorAll('.student-checkbox')
            .forEach(cb => {

                cb.checked = false;

            });

        document
            .getElementById('selectAllCheckbox')
            .checked = false;

        updateSelectedCount();

    }


    /*
    |--------------------------------------------------------------------------
    | Get Selected Students
    |--------------------------------------------------------------------------
    */

    function getSelectedStudents() {

        const rows = [];

        document
            .querySelectorAll('.student-checkbox:checked')
            .forEach(checkbox => {

                const row =
                    checkbox.closest('.student-row');

                if (row) {

                    rows.push(row);

                }

            });

        return rows;

    }


    /*
    |--------------------------------------------------------------------------
    | Update Selected Count
    |--------------------------------------------------------------------------
    */

    function updateSelectedCount() {

        const selected =
            getSelectedStudents();

        selectedStudents =
            selected.map(
                row => row.dataset.studentId
            );

        document
            .getElementById('selectedCount')
            .innerText =
                `${selected.length} Selected`;

    }


    /*
    |--------------------------------------------------------------------------
    | Start Bulk WhatsApp
    |--------------------------------------------------------------------------
    */

    function startBulkWhatsApp() {

        updateSelectedCount();

        if (selectedStudents.length === 0) {

            alert(
                'Please select at least one student.'
            );

            return;

        }


        completedStudents.clear();

        openedStudents.clear();

        currentIndex = -1;

        paused = false;


        document
            .getElementById('startBulkBtn')
            .classList.add('d-none');


        document
            .getElementById('pauseBulkBtn')
            .classList.remove('d-none');


        document
            .getElementById('nextStudentBtn')
            .classList.remove('d-none');


        document
            .getElementById('progressTitle')
            .innerText =
                'Bulk WhatsApp Started';


        updateProgress();


        /*
         * Open first student immediately.
         */

        openNextStudent();

    }


    /*
    |--------------------------------------------------------------------------
    | Open Next Student
    |--------------------------------------------------------------------------
    */

    function openNextStudent() {

        if (paused) {

            return;

        }


        if (selectedStudents.length === 0) {

            return;

        }


        let nextIndex = -1;


        /*
         * Find next incomplete student.
         */

        for (
            let i = currentIndex + 1;
            i < selectedStudents.length;
            i++
        ) {

            if (
                !completedStudents.has(
                    selectedStudents[i]
                )
            ) {

                nextIndex = i;

                break;

            }

        }


        /*
         * If nothing found after current index,
         * search from beginning.
         */

        if (nextIndex === -1) {

            for (
                let i = 0;
                i < selectedStudents.length;
                i++
            ) {

                if (
                    !completedStudents.has(
                        selectedStudents[i]
                    )
                ) {

                    nextIndex = i;

                    break;

                }

            }

        }


        /*
         * All completed.
         */

        if (nextIndex === -1) {

            finishBulkWhatsApp();

            return;

        }


        currentIndex = nextIndex;


        const studentId =
            selectedStudents[currentIndex];


        const row =
            document.querySelector(
                `.student-row[data-student-id="${studentId}"]`
            );


        if (!row) {

            completedStudents.add(studentId);

            openNextStudent();

            return;

        }


        /*
         * Check phone number.
         */

        const phone =
            row.dataset.phone || '';


        if (!phone.trim()) {

            markStudentSkipped(
                studentId,
                'No WhatsApp number'
            );


            setTimeout(() => {

                openNextStudent();

            }, 300);


            return;

        }


        /*
         * Show current student.
         */

        showCurrentStudent(row);


        /*
         * Mark WhatsApp opened.
         */

        markStudentOpened(studentId);


        /*
         * Build WhatsApp URL.
         */

        const whatsappUrl =
            buildWhatsAppUrl(row);


        /*
         * Open WhatsApp.
         */

        window.open(
            whatsappUrl,
            '_blank'
        );


        /*
         * Admin must click this after sending.
         */

        document
            .getElementById('nextStudentBtn')
            .innerHTML =
                '<i class="bi bi-arrow-right-circle me-2"></i> Mark Sent & Next';


        document
            .getElementById('progressTitle')
            .innerText =
                `Student ${currentIndex + 1} is ready`;

    }


    /*
    |--------------------------------------------------------------------------
    | Show Current Student
    |--------------------------------------------------------------------------
    */

    function showCurrentStudent(row) {

        const card =
            document.getElementById(
                'currentStudentCard'
            );

        card.classList.remove('d-none');


        const name =
            row.dataset.name || 'Student';


        const studentCode =
            row.dataset.studentCode ||
            row.dataset.studentId;


        const phone =
            row.dataset.phone || '-';


        document
            .getElementById('currentStudentName')
            .innerText =
                name;


        document
            .getElementById('currentStudentId')
            .innerText =
                studentCode;


        document
            .getElementById('currentStudentPhone')
            .innerText =
                phone;


        const words =
            name.trim().split(/\s+/);


        const initials =
            words
                .slice(0, 2)
                .map(word =>
                    word
                        .substring(0, 1)
                        .toUpperCase()
                )
                .join('');


        document
            .getElementById('currentAvatar')
            .innerText =
                initials || 'S';


        document
            .getElementById('currentStatus')
            .innerText =
                'WhatsApp Opened';


        document
            .getElementById('currentStatus')
            .className =
                'badge bg-primary-subtle text-primary px-3 py-2';


        /*
         * Scroll current row into view.
         */

        row.scrollIntoView({

            behavior: 'smooth',

            block: 'center'

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Build WhatsApp Message
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | The WhatsApp message contains ONLY:
    |
    | 1. Student name
    | 2. Student ID
    | 3. Online result link
    |
    | Marks, Percentage and Grade are NOT included.
    |--------------------------------------------------------------------------
    */

    function buildWhatsAppUrl(row) {

        const name =
            row.dataset.name || 'Student';


        const studentId =
            row.dataset.studentCode ||
            row.dataset.studentId;


        const phone =
            normalizePhone(
                row.dataset.phone || ''
            );


        const message =
`Dear Parent,

The result of ${name} has been published online.

Student ID: ${studentId}

You can view the complete result using the link below:

${PUBLIC_RESULT_URL}

Please use the Student ID and Mother's Name to access the result.

Regards,
School Management`;


        return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Indian Phone Number
    |--------------------------------------------------------------------------
    */

    function normalizePhone(phone) {

        phone =
            phone.replace(/\D/g, '');


        /*
         * 10 digit Indian number
         */

        if (phone.length === 10) {

            phone = '91' + phone;

        }


        /*
         * Remove leading 0 from 091XXXXXXXXXX
         */

        if (
            phone.startsWith('091') &&
            phone.length === 13
        ) {

            phone =
                phone.substring(1);

        }


        return phone;

    }


    /*
    |--------------------------------------------------------------------------
    | Mark Student Opened
    |--------------------------------------------------------------------------
    */

    function markStudentOpened(studentId) {

        openedStudents.add(studentId);


        const status =
            document.querySelector(
                `[data-status-for="${studentId}"]`
            );


        if (!status) {

            return;

        }


        status.className =
            'student-send-status opened-status';


        status.innerHTML =
            '<i class="bi bi-whatsapp me-1"></i> WhatsApp Opened';

    }


    /*
    |--------------------------------------------------------------------------
    | Mark Student Sent + Move Next
    |--------------------------------------------------------------------------
    */

    function openNextAfterSent() {

        if (currentIndex < 0) {

            return;

        }


        const studentId =
            selectedStudents[currentIndex];


        /*
         * Mark current student completed.
         */

        completedStudents.add(studentId);


        const status =
            document.querySelector(
                `[data-status-for="${studentId}"]`
            );


        if (status) {

            status.className =
                'student-send-status sent-status';


            status.innerHTML =
                '<i class="bi bi-check-circle-fill me-1"></i> Sent';

        }


        updateProgress();


        /*
         * Open next student.
         */

        setTimeout(() => {

            openNextStudent();

        }, 400);

    }


    /*
    |--------------------------------------------------------------------------
    | Pause
    |--------------------------------------------------------------------------
    */

    function pauseBulkWhatsApp() {

        paused = true;


        document
            .getElementById('pauseBulkBtn')
            .classList.add('d-none');


        document
            .getElementById('resumeBulkBtn')
            .classList.remove('d-none');


        document
            .getElementById('progressTitle')
            .innerText =
                'Bulk WhatsApp Paused';

    }


    /*
    |--------------------------------------------------------------------------
    | Resume
    |--------------------------------------------------------------------------
    */

    function resumeBulkWhatsApp() {

        paused = false;


        document
            .getElementById('resumeBulkBtn')
            .classList.add('d-none');


        document
            .getElementById('pauseBulkBtn')
            .classList.remove('d-none');


        document
            .getElementById('progressTitle')
            .innerText =
                'Bulk WhatsApp Resumed';


        openNextStudent();

    }


    /*
    |--------------------------------------------------------------------------
    | Skip Student
    |--------------------------------------------------------------------------
    */

    function markStudentSkipped(
        studentId,
        reason = 'Skipped'
    ) {

        completedStudents.add(studentId);


        const status =
            document.querySelector(
                `[data-status-for="${studentId}"]`
            );


        if (status) {

            status.className =
                'student-send-status skipped-status';


            status.innerHTML =
                `<i class="bi bi-exclamation-circle me-1"></i> ${reason}`;

        }


        updateProgress();

    }


    /*
    |--------------------------------------------------------------------------
    | Update Progress
    |--------------------------------------------------------------------------
    */

    function updateProgress() {

        const total =
            selectedStudents.length;


        const completed =
            completedStudents.size;


        const percentage =
            total > 0
                ? Math.round(
                    (completed / total) * 100
                )
                : 0;


        document
            .getElementById('bulkProgress')
            .style.width =
                percentage + '%';


        document
            .getElementById('progressCount')
            .innerText =
                `${completed} / ${total}`;


        if (total > 0) {

            document
                .getElementById('progressTitle')
                .innerText =
                    `${percentage}% Completed`;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Finish Bulk WhatsApp
    |--------------------------------------------------------------------------
    */

    function finishBulkWhatsApp() {

        paused = true;


        updateProgress();


        document
            .getElementById('progressTitle')
            .innerText =
                'Bulk WhatsApp Completed';


        document
            .getElementById('currentStatus')
            .innerText =
                'Completed';


        document
            .getElementById('currentStatus')
            .className =
                'badge bg-success-subtle text-success px-3 py-2';


        document
            .getElementById('nextStudentBtn')
            .classList.add('d-none');


        document
            .getElementById('pauseBulkBtn')
            .classList.add('d-none');


        document
            .getElementById('resumeBulkBtn')
            .classList.add('d-none');


        alert(
            'Bulk WhatsApp process completed.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Individual Student
    |--------------------------------------------------------------------------
    */

    function openIndividualStudent(studentId) {

        const row =
            document.querySelector(
                `.student-row[data-student-id="${studentId}"]`
            );


        if (!row) {

            return;

        }


        const phone =
            row.dataset.phone || '';


        if (!phone.trim()) {

            alert(
                'WhatsApp number is not available for this student.'
            );

            return;

        }


        window.open(

            buildWhatsAppUrl(row),

            '_blank'

        );

    }

</script>

@endsection
