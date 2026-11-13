@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-person-vcard text-primary me-2"></i>
                Generated ID Card
            </h3>

            <p class="text-muted mb-0">
                Preview the generated ID card using the selected template.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.id-card.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route('admin.id-card.print', $idCard->id) }}"
               target="_blank"
               class="btn btn-primary">

                <i class="bi bi-printer me-1"></i>
                Print ID Card

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">


        {{-- =====================================================
             LEFT - CARD PREVIEW
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-eye text-primary me-2"></i>

                                ID Card Preview

                            </h5>

                            <small class="text-muted">

                                Generated using the selected uploaded design.

                            </small>

                        </div>


                        <span class="badge bg-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Generated

                        </span>

                    </div>

                </div>


                <div class="card-body preview-area">


                    @php

                        $templateImage =
                            $template->template_image ?? null;

                        if (
                            $templateImage &&
                            !str_starts_with($templateImage, 'http://') &&
                            !str_starts_with($templateImage, 'https://')
                        ) {

                            $templateImage =
                                asset(
                                    'storage/' .
                                    ltrim($templateImage, '/')
                                );

                        }

                    @endphp


                    {{-- =================================================
                         ACTUAL GENERATED CARD
                    ================================================== --}}

                    <div id="idCardPreview"
                         class="id-card-preview">


                        {{-- EXACT UPLOADED TEMPLATE --}}

                        <img src="{{ $templateImage }}"
                             alt="{{ $template->name }}"
                             class="template-background">


                        {{-- STUDENT DATA --}}

                        <div id="cardFields"></div>


                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             RIGHT - INFORMATION
        ====================================================== --}}

        <div class="col-xl-4">


            {{-- =================================================
                 STUDENT INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-person text-primary me-2"></i>

                        Student Information

                    </h5>

                </div>


                <div class="card-body p-4">


                    @php

                        $studentImage =
                            $idCard->student->profile_image ?? null;

                    @endphp


                    <div class="text-center mb-4">

                        @if($studentImage)

                            @if(
                                str_starts_with($studentImage, 'http://') ||
                                str_starts_with($studentImage, 'https://')
                            )

                                <img src="{{ $studentImage }}"
                                     class="student-profile-photo"
                                     alt="Student">

                            @else

                                <img src="{{ asset('storage/' . ltrim($studentImage, '/')) }}"
                                     class="student-profile-photo"
                                     alt="Student">

                            @endif

                        @else

                            <div class="student-profile-placeholder">

                                <i class="bi bi-person"></i>

                            </div>

                        @endif

                    </div>


                    <h5 class="text-center fw-bold mb-1">

                        {{ $idCard->student->full_name
                            ?? trim(
                                ($idCard->student->first_name ?? '') . ' ' .
                                ($idCard->student->middle_name ?? '') . ' ' .
                                ($idCard->student->last_name ?? '')
                            )
                        }}

                    </h5>


                    <p class="text-center text-muted mb-4">

                        {{ $idCard->student->student_id ?? '-' }}

                    </p>


                    <div class="student-details">


                        <div class="detail-row">

                            <span>
                                <i class="bi bi-building me-2"></i>
                                Class
                            </span>

                            <strong>
                                {{ $idCard->student->class ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-row">

                            <span>
                                <i class="bi bi-grid me-2"></i>
                                Section
                            </span>

                            <strong>
                                {{ $idCard->student->section ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-row">

                            <span>
                                <i class="bi bi-sort-numeric-down me-2"></i>
                                Roll No
                            </span>

                            <strong>
                                {{ $idCard->student->roll_number ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-row">

                            <span>
                                <i class="bi bi-card-text me-2"></i>
                                GR No
                            </span>

                            <strong>
                                {{ $idCard->student->register_no ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-row">

                            <span>
                                <i class="bi bi-calendar3 me-2"></i>
                                Academic Year
                            </span>

                            <strong>
                                {{ $idCard->academic_year ?? '-' }}
                            </strong>

                        </div>


                    </div>

                </div>

            </div>



            {{-- =================================================
                 CARD INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-info-circle text-primary me-2"></i>

                        Card Information

                    </h5>

                </div>


                <div class="card-body p-4">


                    <div class="detail-row">

                        <span>
                            Card Number
                        </span>

                        <strong>
                            {{ $idCard->card_number }}
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Template
                        </span>

                        <strong>
                            {{ $template->name }}
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Academic Year
                        </span>

                        <strong>
                            {{ $idCard->academic_year ?? '-' }}
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Issued Date
                        </span>

                        <strong>
                            {{ $idCard->issued_date
                                ? $idCard->issued_date->format('d M Y')
                                : '-'
                            }}
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Status
                        </span>

                        @if($idCard->status === 'active')

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>



{{-- =========================================================
     CSS
========================================================= --}}

<style>

.preview-area {
    min-height: 700px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 0 0 16px 16px;
}


/*
|--------------------------------------------------------------------------
| IMPORTANT
|--------------------------------------------------------------------------
| This container uses the exact dimensions of the uploaded image.
|--------------------------------------------------------------------------
*/

.id-card-preview {

    position: relative;

    width: 420px;

    max-width: 90%;

    overflow: hidden;

    background: #fff;

    box-shadow:
        0 18px 50px rgba(0, 0, 0, .16);

}


/*
|--------------------------------------------------------------------------
| Uploaded Design
|--------------------------------------------------------------------------
*/

.template-background {

    display: block;

    width: 100%;

    height: auto;

}


/*
|--------------------------------------------------------------------------
| Student Fields Layer
|--------------------------------------------------------------------------
*/

#cardFields {

    position: absolute;

    inset: 0;

    width: 100%;

    height: 100%;

    pointer-events: none;

}


/*
|--------------------------------------------------------------------------
| Dynamic Fields
|--------------------------------------------------------------------------
*/

.card-field {



    position: absolute;

    overflow: hidden;

    word-break: break-word;

    display: flex;

    align-items: center;

    line-height: 1.2;

    z-index: 10;

}

.card-field strong {
    font-weight: 600;
    margin-right: 4px;
}


/*
|--------------------------------------------------------------------------
| Photo
|--------------------------------------------------------------------------
*/

.card-field-photo {
    position: absolute;
    object-fit: cover;
    display: block;
}

.card-field-photo.photo-circle {
    border-radius: 50%;
    overflow: hidden;
}

.card-field-photo.photo-square {
    border-radius: 0;
    overflow: hidden;
}


/*
|--------------------------------------------------------------------------
| Student Profile
|--------------------------------------------------------------------------
*/

.student-profile-photo {

    width: 90px;

    height: 90px;

    object-fit: cover;

    border-radius: 50%;

    border: 4px solid #fff;

    box-shadow:
        0 5px 18px rgba(0, 0, 0, .12);

}


.student-profile-placeholder {

    width: 90px;

    height: 90px;

    border-radius: 50%;

    background: #e9ecef;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: 0 auto;

    font-size: 38px;

    color: #6c757d;

}


/*
|--------------------------------------------------------------------------
| Details
|--------------------------------------------------------------------------
*/

.student-details {

    border-top: 1px solid #edf0f2;

}


.detail-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    padding: 12px 0;

    border-bottom: 1px solid #edf0f2;

    font-size: 14px;

}


.detail-row:last-child {

    border-bottom: 0;

}


.detail-row span {

    color: #6c757d;

}


.detail-row strong {

    color: #212529;

    text-align: right;

    word-break: break-word;

}


@media (max-width: 767px) {

    .id-card-preview {

        width: 360px;

        max-width: 95%;

    }

    .preview-area {

        min-height: 550px;

    }

}

</style>



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const student =
        @json($idCard->student);


    const fieldPositions =
        @json($fieldPositions ?? []);


    const cardFields =
        document.getElementById('cardFields');



    /* =========================================================
       BUILD STUDENT NAME
    ========================================================== */

    function buildStudentName(student) {

        return [

            student.first_name,

            student.middle_name,

            student.last_name

        ]
        .filter(function (value) {

            return value !== null &&
                   value !== undefined &&
                   String(value).trim() !== '';

        })
        .join(' ');

    }



    /* =========================================================
       FIELD VALUES
    ========================================================== */

    function getStudentFieldValue(student, key) {


        const aliases = {

            profile_image: [
                'profile_image'
            ],

            photo: [
                'profile_image'
            ],

            full_name: [
                'full_name'
            ],

            student_name: [
                'full_name'
            ],

            first_name: [
                'first_name'
            ],

            middle_name: [
                'middle_name'
            ],

            last_name: [
                'last_name'
            ],

            marathi_name: [
                'marathi_name'
            ],

            student_id: [
                'student_id'
            ],

            register_no: [
                'register_no'
            ],

            gr_no: [
                'register_no',
                'gr_no'
            ],

            book_no: [
                'book_no'
            ],

            appar_id: [
                'appar_id',
                'apaar_id'
            ],

            apaar_id: [
                'apaar_id',
                'appar_id'
            ],

            pen_no: [
                'pen_no'
            ],

            class: [
                'class'
            ],

            section: [
                'section'
            ],

            roll_number: [
                'roll_number',
                'roll_no'
            ],

            roll_no: [
                'roll_no',
                'roll_number'
            ],

            date_of_birth: [
                'date_of_birth',
                'dob'
            ],

            dob: [
                'dob',
                'date_of_birth'
            ],

            blood_group: [
                'blood_group'
            ],

            gender: [
                'gender'
            ],

            phone: [
                'phone',
                'mobile',
                'contact_no'
            ],

            father_name: [
                'father_name'
            ],

            father_phone: [
                'father_phone'
            ],

            mother_name: [
                'mother_name'
            ],

            mother_phone: [
                'mother_phone'
            ],

            guardian_name: [
                'guardian_name'
            ],

            guardian_phone: [
                'guardian_phone'
            ],

            parent_name: [
                'parent_name',
                'father_name',
                'guardian_name'
            ],

            address: [
                'address'
            ],

            city_village: [
                'city_village'
            ],

            pincode: [
                'pincode'
            ],

            nationality: [
                'nationality'
            ],

            mother_tongue: [
                'mother_tongue'
            ],

            religion: [
                'religion'
            ],

            caste: [
                'caste'
            ],

            sub_caste: [
                'sub_caste'
            ],

            medium: [
                'medium'
            ],

            academic_year: [
                'academic_year'
            ],

            admission_class: [
                'admission_class'
            ],

            admission_date: [
                'admission_date'
            ]

        };


        const candidates =
            aliases[key] || [key];


        for (const candidate of candidates) {

            if (
                student[candidate] !== undefined &&
                student[candidate] !== null &&
                String(student[candidate]).trim() !== ''
            ) {

                return student[candidate];

            }

        }


        if (
            key === 'full_name' ||
            key === 'student_name'
        ) {

            return buildStudentName(student);

        }


        return '';

    }



    /* =========================================================
       STUDENT IMAGE
    ========================================================== */

    function getStudentImage(student) {

        let image =
            student.profile_image || '';


        if (
            image &&
            !image.startsWith('http://') &&
            !image.startsWith('https://')
        ) {

            image =
                "{{ asset('storage') }}/" +
                image.replace(/^\/+/, '');

        }


        return image;

    }



    /* =========================================================
       APPLY POSITION
    ========================================================== */

    function applyFieldPosition(
        element,
        fieldData
    ) {


        element.style.left =
            `${Number(fieldData.x || 0)}%`;


        element.style.top =
            `${Number(fieldData.y || 0)}%`;


        if (
            fieldData.width !== undefined &&
            fieldData.width !== null &&
            fieldData.width !== ''
        ) {

            element.style.width =
                `${Number(fieldData.width)}%`;

        }


        if (
            fieldData.height !== undefined &&
            fieldData.height !== null &&
            fieldData.height !== ''
        ) {

            element.style.height =
                `${Number(fieldData.height)}%`;

        }


        if (
            fieldData.font_size !== undefined &&
            fieldData.font_size !== null &&
            fieldData.font_size !== ''
        ) {

            element.style.fontSize =
                `${Number(fieldData.font_size)}px`;

        }


        if (fieldData.font_weight) {

            element.style.fontWeight =
                fieldData.font_weight;

        }


        element.style.textAlign =
            fieldData.text_align || 'left';


        element.style.zIndex =
            '10';

    }



    /* =========================================================
       RENDER CARD
    ========================================================== */

    function renderCard() {


        cardFields.innerHTML = '';


        if (
            !Array.isArray(fieldPositions) ||
            fieldPositions.length === 0
        ) {

            return;

        }


        fieldPositions.forEach(function (fieldData) {


            if (
                !fieldData ||
                !fieldData.key
            ) {

                return;

            }


            const key =
                fieldData.key;



            /* =================================================
               PHOTO
            ================================================== */

            if (
                key === 'profile_image' ||
                key === 'photo'
            ) {


                const image =
                    document.createElement('img');


                const photoShape =
    fieldData.photo_shape ||
    fieldData.photoShape ||
    'circle';

image.className =
    'card-field card-field-photo ' +
    (photoShape === 'square'
        ? 'photo-square'
        : 'photo-circle');


                const photo =
                    getStudentImage(student);


                if (photo) {

                    image.src =
                        photo;

                }


                applyFieldPosition(
                    image,
                    fieldData
                );


                cardFields.appendChild(
                    image
                );


                return;

            }



            /* =================================================
               TEXT
            ================================================== */

            /* =================================================
   TEXT
================================================== */

const value =
    getStudentFieldValue(
        student,
        key
    );

if (
    value === null ||
    value === undefined ||
    String(value).trim() === ''
) {
    return;
}


/*
|--------------------------------------------------------------------------
| FORMAT DATE VALUES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| FORMAT DATE VALUES
|--------------------------------------------------------------------------
*/

let displayValue = String(value);

if (
    key === 'date_of_birth' ||
    key === 'dob'
) {

    let dateString =
        String(value).trim();

    /*
     * Laravel can return:
     * 2013-12-30T18:30:00.000000Z
     *
     * JavaScript may not parse the
     * 6-digit microseconds correctly.
     *
     * Convert:
     * .000000Z
     * to:
     * .000Z
     */
    dateString =
        dateString.replace(
            /\.(\d{3})\d+Z$/,
            '.$1Z'
        );

    const match =
        dateString.match(
            /^(\d{4})-(\d{2})-(\d{2})/
        );

    if (match) {

        displayValue =
            match[3] +
            '/' +
            match[2] +
            '/' +
            match[1];

    }

}


/*
|--------------------------------------------------------------------------
| FALLBACK LABELS
|--------------------------------------------------------------------------
*/

const fallbackLabels = {

    full_name: 'Student Name',
    student_name: 'Student Name',

    first_name: 'First Name',
    middle_name: 'Middle Name',
    last_name: 'Last Name',

    student_id: 'Student ID',

    register_no: 'GR No',
    gr_no: 'GR No',

    class: 'Class',
    section: 'Section',

    roll_number: 'Roll No',
    roll_no: 'Roll No',

    date_of_birth: 'DOB',
    dob: 'DOB',

    blood_group: 'Blood Group',

    gender: 'Gender',

    father_name: 'Father Name',
    mother_name: 'Mother Name',

    phone: 'Phone',

    address: 'Address',

    academic_year: 'Academic Year'
};


/*
|--------------------------------------------------------------------------
| USE SAVED LABEL
|--------------------------------------------------------------------------
*/

let label =
    String(fieldData.label || '').trim();


/*
|--------------------------------------------------------------------------
| If saved label is missing,
| use automatic fallback
|--------------------------------------------------------------------------
*/

if (!label) {

    label =
        fallbackLabels[key] || '';

}


/*
|--------------------------------------------------------------------------
| Remove duplicate colon
|--------------------------------------------------------------------------
*/

label =
    label.replace(/:\s*$/, '');


/*
|--------------------------------------------------------------------------
| CREATE FIELD
|--------------------------------------------------------------------------
*/

const element =
    document.createElement('div');

element.className =
    'card-field';


/*
|--------------------------------------------------------------------------
| RENDER LABEL + VALUE
|--------------------------------------------------------------------------
*/

if (label) {

    const labelElement =
        document.createElement('strong');

    labelElement.textContent =
        label + ':';

    const valueElement =
        document.createElement('span');

    valueElement.textContent =
        ' ' + displayValue;

    element.appendChild(
        labelElement
    );

    element.appendChild(
        valueElement
    );

} else {

    element.textContent =
        displayValue;

}


/*
|--------------------------------------------------------------------------
| APPLY POSITION
|--------------------------------------------------------------------------
*/

applyFieldPosition(
    element,
    fieldData
);


/*
|--------------------------------------------------------------------------
| ADD TO CARD
|--------------------------------------------------------------------------
*/

cardFields.appendChild(
    element
);
    }



    renderCard();

});
</script>

@endsection