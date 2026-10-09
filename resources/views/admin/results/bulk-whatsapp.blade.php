@extends('layouts.app')

@section('title', 'WhatsApp Group Result Message')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Basic Data
    |--------------------------------------------------------------------------
    */

    $resultUrl = route('result.public');

    $className =
        $schoolClass->class_name
        ?? 'Class';

    $section =
        $schoolClass->section
        ?? request('section')
        ?? '';

    $examName =
        $exam->exam_name
        ?? 'Examination';

    $academicYear =
        $exam->academic_year
        ?? '';

    /*
    |--------------------------------------------------------------------------
    | Count Published Results
    |--------------------------------------------------------------------------
    */

    $publishedCount = $students->filter(function ($student) use ($results) {

        $result = $results->get($student->id);

        return $result
            && $result->publication_status === 'published';

    })->count();

@endphp


<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">

            <div class="page-icon">

                <i class="bi bi-whatsapp"></i>

            </div>

            <div>

                <h4 class="fw-bold mb-1">
                    WhatsApp Group Result Message
                </h4>

                <div class="text-muted">

                    Send one common result notification
                    to the school WhatsApp group.

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

                    {{ $className }}

                    @if(!empty($section))

                        - Section {{ $section }}

                    @endif

                </div>


                <div class="small text-muted">

                    {{ $examName }}

                    @if(!empty($academicYear))

                        <span class="mx-1">•</span>

                        {{ $academicYear }}

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        GROUP MESSAGE CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-chat-left-text me-2 text-success"></i>

                        Group Message

                    </h5>

                    <div class="small text-muted">

                        This message is common for all parents/students
                        in the selected class.

                    </div>

                </div>


                <span class="badge bg-success-subtle text-success px-3 py-2">

                    <i class="bi bi-check-circle me-1"></i>

                    {{ $publishedCount }} Published Results

                </span>

            </div>


            {{-- =================================================
                IMPORTANT INFORMATION
            ================================================== --}}

            <div class="group-info-alert mb-4">

                <div class="d-flex gap-3">

                    <div class="alert-icon">

                        <i class="bi bi-info-circle-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold mb-1">

                            How this works

                        </div>

                        <div class="small">

                            Click <strong>Open WhatsApp</strong>.
                            WhatsApp will open with this message already
                            prepared. Select your school WhatsApp group
                            and press <strong>Send</strong> once.

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                MESSAGE PREVIEW
            ================================================== --}}

            <label
                for="groupMessage"
                class="form-label fw-bold"
            >

                WhatsApp Message

            </label>


            <textarea
                id="groupMessage"
                class="form-control message-box"
                rows="14"
            >📢 Result Published

Dear Parents/Students,

The result for {{ $examName }}{{ !empty($section) ? ' - ' . $className . ' Section ' . $section : ' - ' . $className }}{{ !empty($academicYear) ? ', Academic Year ' . $academicYear : '' }} has been published online.

Please check the result using the link below:

{{ $resultUrl }}

To view the result, enter:

• Student ID
• Mother's Name
• CAPTCHA

Please use the Student ID and Mother's Name registered with the school.

Regards,
School Administration</textarea>


            {{-- =================================================
                CHARACTER COUNT
            ================================================== --}}

            <div class="d-flex justify-content-between align-items-center mt-2">

                <div class="small text-muted">

                    <i class="bi bi-shield-check me-1"></i>

                    Common message for the WhatsApp group

                </div>


                <div
                    class="small text-muted"
                    id="messageLength"
                >

                    0 characters

                </div>

            </div>


            {{-- =================================================
                ACTION BUTTONS
            ================================================== --}}

            <div class="d-flex flex-wrap gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-success px-4 rounded-3"
                    onclick="openWhatsAppGroup()"
                >

                    <i class="bi bi-whatsapp me-2"></i>

                    Open WhatsApp

                </button>


                <button
                    type="button"
                    class="btn btn-outline-primary px-4 rounded-3"
                    onclick="copyGroupMessage()"
                >

                    <i class="bi bi-copy me-2"></i>

                    Copy Message

                </button>


                <button
                    type="button"
                    class="btn btn-outline-secondary px-4 rounded-3"
                    onclick="resetGroupMessage()"
                >

                    <i class="bi bi-arrow-counterclockwise me-2"></i>

                    Reset Message

                </button>

            </div>


            {{-- =================================================
                STATUS
            ================================================== --}}

            <div
                id="groupMessageStatus"
                class="group-message-status d-none mt-4"
            >

                <i class="bi bi-check-circle-fill me-2"></i>

                Message copied successfully.

            </div>

        </div>

    </div>


    {{-- =========================================================
        STUDENT / RESULT PREVIEW
    ========================================================== --}}

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-people me-2 text-primary"></i>

                        Result Preview

                    </h5>

                    <div class="small text-muted">

                        Students included in this class/exam result view.

                    </div>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    <span class="badge bg-success-subtle text-success px-3 py-2">

                        Published:
                        {{ $publishedCount }}

                    </span>


                    <span class="badge bg-light text-dark border px-3 py-2">

                        Total:
                        {{ $students->count() }}

                    </span>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle mb-0 student-table">

                <thead>

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Result
                        </th>

                        <th>
                            Publication
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($students as $index => $student)

                    @php

                        $result =
                            $results->get($student->id);

                        $studentName =
                            collect([
                                $student->first_name ?? null,
                                $student->middle_name ?? null,
                                $student->last_name ?? null,
                            ])
                            ->filter()
                            ->implode(' ');

                        $studentName =
                            $studentName ?: 'Student';


                        $initials =
                            collect(
                                preg_split(
                                    '/\s+/',
                                    trim($studentName)
                                )
                            )
                            ->filter()
                            ->take(2)
                            ->map(
                                fn($name) =>
                                    strtoupper(
                                        substr($name, 0, 1)
                                    )
                            )
                            ->implode('');


                        $publicationStatus =
                            $result?->publication_status
                            ?? 'pending';

                    @endphp


                    <tr>

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


                        {{-- RESULT --}}

                        <td>

                            @if($result)

                                <div class="fw-semibold text-success">

                                    <i class="bi bi-check-circle-fill me-1"></i>

                                    Result Available

                                </div>

                                <div class="small text-muted">

                                    Online Result

                                </div>

                            @else

                                <span class="text-muted">

                                    <i class="bi bi-clock me-1"></i>

                                    Pending

                                </span>

                            @endif

                        </td>


                        {{-- PUBLICATION --}}

                        <td>

                            @php

                                $publicationClass =
                                    match($publicationStatus) {

                                        'published' =>
                                            'status-published',

                                        'approved' =>
                                            'status-approved',

                                        'verified' =>
                                            'status-verified',

                                        'generated' =>
                                            'status-generated',

                                        default =>
                                            'status-pending',

                                    };

                            @endphp


                            <span
                                class="status-pill {{ $publicationClass }}"
                            >

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

                    </tr>

                @empty

                    <tr>

                        <td colspan="5">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-people"></i>

                                </div>


                                <h5 class="fw-bold mt-3">

                                    No Students Found

                                </h5>


                                <p class="text-muted mb-0">

                                    There are no students available
                                    for this class.

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

                    This page creates one common message.
                    It does not send individual messages to parents.

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


    .group-info-alert {

        background: #f0fff6;

        border: 1px solid #cceedd;

        border-radius: 14px;

        padding: 16px 18px;

        color: #315b45;

    }


    .alert-icon {

        width: 35px;
        height: 35px;

        min-width: 35px;

        border-radius: 10px;

        background: #dff8ea;

        color: #16834a;

        display: flex;

        align-items: center;

        justify-content: center;

    }


    .message-box {

        border: 1px solid #dce3eb;

        border-radius: 14px;

        padding: 16px;

        font-size: 14px;

        line-height: 1.65;

        resize: vertical;

        min-height: 280px;

        color: var(--text-dark);

    }


    .message-box:focus {

        border-color: var(--primary-blue);

        box-shadow: 0 0 0 .2rem rgba(22, 119, 240, .10);

    }


    .group-message-status {

        background: #e9fff3;

        border: 1px solid #bce8ce;

        color: #16834a;

        border-radius: 12px;

        padding: 12px 15px;

        font-size: 13px;

        font-weight: 600;

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

        .message-box {

            min-height: 350px;

        }

    }

</style>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | Update Message Character Count
    |--------------------------------------------------------------------------
    */

    function updateMessageLength() {

        const message =
            document.getElementById('groupMessage');

        const counter =
            document.getElementById('messageLength');


        if (!message || !counter) {

            return;

        }


        counter.innerText =
            `${message.value.length} characters`;

    }


    /*
    |--------------------------------------------------------------------------
    | Copy Group Message
    |--------------------------------------------------------------------------
    */

    async function copyGroupMessage() {

        const message =
            document.getElementById('groupMessage');


        if (!message) {

            return;

        }


        const text =
            message.value.trim();


        if (!text) {

            alert('Message is empty.');

            return;

        }


        try {

            await navigator.clipboard.writeText(text);

            showGroupMessageStatus(
                'Message copied successfully.'
            );

        } catch (error) {

            /*
            |------------------------------------------------------------------
            | Fallback for browsers where Clipboard API is unavailable
            |------------------------------------------------------------------
            */

            message.focus();

            message.select();

            document.execCommand('copy');

            showGroupMessageStatus(
                'Message copied successfully.'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Open WhatsApp
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | WhatsApp does not allow this page to automatically select a
    | particular group.
    |
    | WhatsApp opens with the common message prepared.
    | The user selects the school group and presses Send.
    |
    */

    function openWhatsAppGroup() {

        const message =
            document.getElementById('groupMessage');


        if (!message) {

            return;

        }


        const text =
            message.value.trim();


        if (!text) {

            alert('Message is empty.');

            return;

        }


        const whatsappUrl =
            `https://wa.me/?text=${encodeURIComponent(text)}`;


        window.open(
            whatsappUrl,
            '_blank'
        );


        showGroupMessageStatus(
            'WhatsApp opened. Select the school group and press Send.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Reset Message
    |--------------------------------------------------------------------------
    */

    function resetGroupMessage() {

        const message =
            document.getElementById('groupMessage');


        if (!message) {

            return;

        }


        const defaultMessage =
`📢 Result Published

Dear Parents/Students,

The result for {{ $examName }}{{ !empty($section) ? ' - ' . $className . ' Section ' . $section : ' - ' . $className }}{{ !empty($academicYear) ? ', Academic Year ' . $academicYear : '' }} has been published online.

Please check the result using the link below:

{{ $resultUrl }}

To view the result, enter:

• Student ID
• Mother's Name
• CAPTCHA

Please use the Student ID and Mother's Name registered with the school.

Regards,
School Administration`;


        message.value =
            defaultMessage;


        updateMessageLength();


        showGroupMessageStatus(
            'Message restored to the default format.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Status Message
    |--------------------------------------------------------------------------
    */

    function showGroupMessageStatus(text) {

        const status =
            document.getElementById(
                'groupMessageStatus'
            );


        if (!status) {

            return;

        }


        status.innerHTML =
            `<i class="bi bi-check-circle-fill me-2"></i>${text}`;


        status.classList.remove('d-none');


        clearTimeout(
            window.groupMessageStatusTimer
        );


        window.groupMessageStatusTimer =
            setTimeout(function () {

                status.classList.add('d-none');

            }, 5000);

    }


    /*
    |--------------------------------------------------------------------------
    | Message Input Listener
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const message =
                document.getElementById(
                    'groupMessage'
                );


            if (message) {

                updateMessageLength();


                message.addEventListener(
                    'input',
                    updateMessageLength
                );

            }

        }
    );

</script>

@endsection