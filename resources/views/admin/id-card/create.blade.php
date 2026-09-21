@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

```
{{-- =========================================================
     HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="bi bi-person-vcard text-primary me-2"></i>
            Generate ID Card
        </h3>
        <p class="text-muted mb-0">
            Select a student and an uploaded ID-card design.
        </p>
    </div>

    <a href="{{ route('admin.id-card.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to ID Cards
    </a>
</div>


{{-- =========================================================
     MESSAGES
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


@if($errors->any())
    <div class="alert alert-danger">
        <strong>
            Please correct the following errors:
        </strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
     FORM
========================================================== --}}
<form action="{{ route('admin.id-card.store') }}"
      method="POST"
      id="generateCardForm">

    @csrf

    <div class="row g-4">


        {{-- =================================================
             LEFT SIDE
        ================================================== --}}
        <div class="col-xl-5">


            {{-- =================================================
                 STUDENT SEARCH
            ================================================== --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-search text-primary me-2"></i>
                        Select Student
                    </h5>

                    <small class="text-muted">
                        Search using Student ID, name, GR No, PEN No or APAAR ID.
                    </small>

                </div>


                <div class="card-body p-4">

                    <div class="input-group">

                        <input type="text"
                               id="studentSearch"
                               class="form-control form-control-lg"
                               placeholder="Search student...">

                        <button type="button"
                                id="searchStudentBtn"
                                class="btn btn-primary px-4">

                            <i class="bi bi-search"></i>

                        </button>

                    </div>


                    <div id="searchResults"
                         class="mt-3">
                    </div>


                    {{-- Selected Student --}}
                    <div id="selectedStudent"
                         class="selected-student mt-4"
                         style="display:none;">

                        <div class="d-flex align-items-center">

                            <div class="student-photo-wrapper">

                                <img id="selectedStudentPhoto"
                                     src=""
                                     alt="Student"
                                     class="student-photo">

                            </div>


                            <div class="ms-3 flex-grow-1">

                                <h6 id="selectedStudentName"
                                    class="fw-bold mb-1">
                                </h6>


                                <div class="small text-muted">

                                    Student ID:

                                    <strong id="selectedStudentId">
                                    </strong>

                                </div>


                                <div class="small text-muted">

                                    Class:

                                    <strong id="selectedStudentClass">
                                    </strong>

                                    <span class="mx-1">•</span>

                                    Section:

                                    <strong id="selectedStudentSection">
                                    </strong>

                                </div>

                            </div>


                            <button type="button"
                                    id="clearStudent"
                                    class="btn btn-sm btn-outline-danger">

                                <i class="bi bi-x"></i>

                            </button>

                        </div>

                    </div>


                    <input type="hidden"
                           name="student_id"
                           id="studentId">

                </div>

            </div>



            {{-- =================================================
                 TEMPLATE SELECTION
            ================================================== --}}
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-images text-primary me-2"></i>
                        Select ID Card Design
                    </h5>

                    <small class="text-muted">
                        Select one of the uploaded and configured designs.
                    </small>

                </div>


                <div class="card-body p-4">


                    @if($templates->count() === 0)

                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            No active ID card templates are available.

                            Please upload and configure a template first.

                        </div>

                    @else

                        <div class="row g-3">

                            @foreach($templates as $template)

                                @php

                                    $image = $template->template_image;

                                    if (
                                        $image &&
                                        !str_starts_with($image, 'http://') &&
                                        !str_starts_with($image, 'https://')
                                    ) {

                                        $image = asset(
                                            'storage/' .
                                            ltrim($image, '/')
                                        );

                                    }

                                    $fieldPositions =
                                        $template->field_positions ?? [];

                                @endphp


                                <div class="col-md-6">

                                    <label class="template-card">

                                        {{-- IMPORTANT:
                                             Submit TEMPLATE ID, not slug --}}
                                        <input type="radio"
                                               name="template_id"
                                               value="{{ $template->id }}"
                                               class="template-radio"

                                               data-template-id="{{ $template->id }}"

                                               data-template-name="{{ $template->name }}"

                                               data-template-image="{{ $image }}"

                                               data-academic-year="{{ $template->academic_year }}"

                                               data-field-positions="{{ e(json_encode($fieldPositions)) }}"

                                               {{ old('template_id') == $template->id ? 'checked' : '' }}>


                                        <div class="template-card-inner">


                                            <div class="template-image-box">

                                                @if($image)

                                                    <img src="{{ $image }}"
                                                         alt="{{ $template->name }}">

                                                @else

                                                    <div class="text-muted text-center">

                                                        <i class="bi bi-image display-6 d-block"></i>

                                                        No Image

                                                    </div>

                                                @endif

                                            </div>


                                            <div class="p-3">

                                                <div class="d-flex justify-content-between align-items-start">

                                                    <div>

                                                        <div class="fw-bold">

                                                            {{ $template->name }}

                                                        </div>


                                                        <small class="text-muted">

                                                            {{ $template->academic_year }}

                                                        </small>

                                                    </div>


                                                    @if(count($fieldPositions) > 0)

                                                        <span class="badge bg-success">

                                                            Configured

                                                        </span>

                                                    @else

                                                        <span class="badge bg-warning text-dark">

                                                            Not Configured

                                                        </span>

                                                    @endif

                                                </div>


                                            </div>

                                        </div>

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    @endif


                    {{-- Academic Year --}}
                    <input type="hidden"
                           name="academic_year"
                           id="academicYear"
                           value="{{ old('academic_year') }}">


                    {{-- Selected template display --}}
                    <div id="selectedTemplateInfo"
                         class="alert alert-primary mt-3"
                         style="display:none;">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-check-circle-fill me-2"></i>

                            <div>

                                <strong>Selected Template:</strong>

                                <span id="selectedTemplateName">
                                </span>

                            </div>

                        </div>

                    </div>


                    <button type="submit"
                            id="generateButton"
                            class="btn btn-primary btn-lg w-100 mt-4"
                            disabled>

                        <i class="bi bi-magic me-2"></i>

                        Generate ID Card

                    </button>

                </div>

            </div>

        </div>



        {{-- =================================================
             RIGHT SIDE - PREVIEW
        ================================================== --}}
        <div class="col-xl-7">

            <div class="card border-0 shadow-sm rounded-4 h-100">


                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-eye text-primary me-2"></i>

                                ID Card Preview

                            </h5>

                            <small class="text-muted">

                                Preview of the selected uploaded design.

                            </small>

                        </div>


                        <span id="previewStatus"
                              class="badge bg-secondary">

                            Select student & template

                        </span>

                    </div>

                </div>


                <div class="card-body preview-area">


                    {{-- Empty Preview --}}
                    <div id="previewEmpty"
                         class="text-center text-muted">

                        <i class="bi bi-person-vcard display-1 d-block mb-3"></i>

                        <h5 class="fw-semibold">
                            ID Card Preview
                        </h5>

                        <p class="mb-0">
                            Select a student and template to continue.
                        </p>

                    </div>


                    {{-- Actual Preview --}}
                    <div id="cardPreview"
                         class="card-preview"
                         style="display:none;">

                        {{-- EXACT UPLOADED DESIGN --}}
                        <img id="previewTemplateImage"
                             src=""
                             alt="ID Card Design">


                        {{-- STUDENT DATA --}}
                        <div id="previewFields"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>
```

</div>

{{-- =========================================================
CSS
========================================================= --}}

<style>

.selected-student {
    padding: 16px;
    border-radius: 14px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
}

.student-photo-wrapper {
    width: 62px;
    height: 62px;
    flex-shrink: 0;
}

.student-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #dee2e6;
}

.search-result {
    padding: 12px;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: .2s;
}

.search-result:hover {
    background: #f8fbff;
    border-color: #86b7fe;
}

.template-card {
    display: block;
    cursor: pointer;
    height: 100%;
}

.template-card input {
    display: none;
}

.template-card-inner {
    height: 100%;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    background: #fff;
    transition: .2s;
}

.template-card:hover .template-card-inner {
    border-color: #86b7fe;
}

.template-card input:checked + .template-card-inner {
    border-color: #0d6efd;
    box-shadow:
        0 8px 25px rgba(13,110,253,.15);
}

.template-image-box {
    height: 180px;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.template-image-box img {
    max-width: 100%;
    max-height: 160px;
    object-fit: contain;
}

.preview-area {
    min-height: 650px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.card-preview {
    position: relative;
    width: 420px;
    max-width: 90%;
    box-shadow:
        0 15px 45px rgba(0,0,0,.15);
    overflow: hidden;
    line-height: 1;
}

.card-preview > img {
    display: block;
    width: 100%;
    height: auto;
}

#previewFields {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.preview-field {
    position: absolute;
    overflow: hidden;
    word-break: break-word;
    z-index: 10;
    display: flex;
    align-items: center;
    line-height: 1.2;
}

.preview-photo {
    object-fit: cover;
}

</style>

{{-- =========================================================
JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================== */

    const searchInput =
        document.getElementById('studentSearch');

    const searchButton =
        document.getElementById('searchStudentBtn');

    const searchResults =
        document.getElementById('searchResults');

    const selectedStudent =
        document.getElementById('selectedStudent');

    const studentId =
        document.getElementById('studentId');

    const generateButton =
        document.getElementById('generateButton');

    const academicYear =
        document.getElementById('academicYear');

    const previewEmpty =
        document.getElementById('previewEmpty');

    const cardPreview =
        document.getElementById('cardPreview');

    const previewImage =
        document.getElementById('previewTemplateImage');

    const previewFields =
        document.getElementById('previewFields');

    const selectedTemplateInfo =
        document.getElementById('selectedTemplateInfo');

    const selectedTemplateName =
        document.getElementById('selectedTemplateName');


    let currentStudent = null;
    let currentTemplate = null;



    /* =========================================================
       SEARCH STUDENTS
    ========================================================== */

    async function searchStudents() {

        const value =
            searchInput.value.trim();

        if (!value) {

            searchResults.innerHTML = '';

            return;
        }


        searchResults.innerHTML = `
            <div class="text-center text-muted py-3">

                <span class="spinner-border spinner-border-sm me-2"></span>

                Searching...

            </div>
        `;


        try {

            const response =
                await fetch(
                    "{{ route('admin.id-card.search') }}?q=" +
                    encodeURIComponent(value),
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Search request failed.'
                );

            }


            const students =
                await response.json();


            if (
                !Array.isArray(students) ||
                !students.length
            ) {

                searchResults.innerHTML = `
                    <div class="alert alert-light text-center">

                        <i class="bi bi-person-x me-1"></i>

                        No students found.

                    </div>
                `;

                return;
            }


            searchResults.innerHTML = '';


            students.forEach(function (student) {

                const item =
                    document.createElement('div');


                item.className =
                    'search-result';


                item.innerHTML = `

                    <div class="fw-bold">

                        ${escapeHtml(
                            student.full_name ||
                            buildStudentName(student) ||
                            'Student'
                        )}

                    </div>


                    <div class="small text-muted">

                        Student ID:

                        ${escapeHtml(
                            student.student_id || '-'
                        )}

                        <span class="mx-1">•</span>

                        Class:

                        ${escapeHtml(
                            student.class || '-'
                        )}

                        <span class="mx-1">•</span>

                        Section:

                        ${escapeHtml(
                            student.section || '-'
                        )}

                    </div>

                `;


                item.addEventListener(
                    'click',
                    function () {

                        selectStudent(student);

                    }
                );


                searchResults.appendChild(item);

            });


        } catch (error) {

            console.error(error);


            searchResults.innerHTML = `

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    Unable to search students.

                </div>

            `;

        }

    }


    searchButton.addEventListener(
        'click',
        searchStudents
    );


    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                searchStudents();

            }

        }
    );



    /* =========================================================
       SELECT STUDENT
    ========================================================== */

    function selectStudent(student) {

        currentStudent = student;


        studentId.value =
            student.id;


        document.getElementById(
            'selectedStudentName'
        ).textContent =
            student.full_name ||
            buildStudentName(student) ||
            'Student';


        document.getElementById(
            'selectedStudentId'
        ).textContent =
            student.student_id || '-';


        document.getElementById(
            'selectedStudentClass'
        ).textContent =
            student.class || '-';


        document.getElementById(
            'selectedStudentSection'
        ).textContent =
            student.section || '-';



        /* =====================================================
           STUDENT PHOTO
        ====================================================== */

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


        if (!image) {

            image =
                'data:image/svg+xml,' +
                encodeURIComponent(`

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="100"
                         height="100">

                        <rect width="100%"
                              height="100%"
                              fill="#e9ecef"/>

                        <text x="50%"
                              y="50%"
                              text-anchor="middle"
                              dominant-baseline="middle"
                              fill="#6c757d"
                              font-size="12">

                            No Photo

                        </text>

                    </svg>

                `);

        }


        document.getElementById(
            'selectedStudentPhoto'
        ).src = image;


        selectedStudent.style.display =
            'block';


        searchResults.innerHTML = '';


        updateGenerateButton();

        renderPreview();

    }



    /* =========================================================
       CLEAR STUDENT
    ========================================================== */

    document
        .getElementById('clearStudent')
        .addEventListener(
            'click',
            function () {

                currentStudent = null;

                studentId.value = '';

                selectedStudent.style.display =
                    'none';

                updateGenerateButton();

                renderPreview();

            }
        );



    /* =========================================================
       TEMPLATE SELECTION
    ========================================================== */

    document
        .querySelectorAll('.template-radio')
        .forEach(function (radio) {

            radio.addEventListener(
                'change',
                function () {

                    if (!this.checked) {
                        return;
                    }


                    currentTemplate =
                        this;


                    /* IMPORTANT:
                       Academic year comes from selected template */
                    academicYear.value =
                        this.dataset.academicYear || '';


                    /* Show selected template */
                    selectedTemplateName.textContent =
                        this.dataset.templateName || '';


                    selectedTemplateInfo.style.display =
                        'block';


                    renderPreview();

                    updateGenerateButton();

                }
            );

        });



    /* =========================================================
       GET TEMPLATE FIELDS
    ========================================================== */

    function getTemplateFields() {

        if (!currentTemplate) {

            return [];

        }


        try {

            const fields =
                JSON.parse(
                    currentTemplate.dataset.fieldPositions || '[]'
                );


            if (Array.isArray(fields)) {

                return fields;

            }


            return [];

        } catch (error) {

            console.error(
                'Unable to parse template fields:',
                error
            );

            return [];

        }

    }



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
       GET STUDENT FIELD VALUE
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
       GET STUDENT PHOTO
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
       APPLY FIELD POSITION
    ========================================================== */

    function applyFieldPosition(element, fieldData) {

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


        element.style.display =
            'flex';


        element.style.alignItems =
            'center';


        element.style.lineHeight =
            '1.2';


        element.style.zIndex =
            '10';

    }



    /* =========================================================
       RENDER PREVIEW
    ========================================================== */

    function renderPreview() {

        if (
            !currentStudent ||
            !currentTemplate
        ) {

            previewEmpty.style.display =
                'block';

            cardPreview.style.display =
                'none';

            return;

        }


        previewEmpty.style.display =
            'none';


        cardPreview.style.display =
            'block';



        /* =====================================================
           USE EXACT SELECTED TEMPLATE IMAGE
        ====================================================== */

        previewImage.src =
            currentTemplate.dataset.templateImage || '';


        previewFields.innerHTML =
            '';


        const fieldPositions =
            getTemplateFields();



        /* =====================================================
           TEMPLATE NOT CONFIGURED
        ====================================================== */

        if (
            !Array.isArray(fieldPositions) ||
            fieldPositions.length === 0
        ) {

            const message =
                document.createElement('div');


            message.className =
                'position-absolute top-50 start-50 translate-middle bg-white px-4 py-3 rounded-3 shadow text-center';


            message.style.zIndex =
                '100';


            message.innerHTML = `

                <div class="fw-semibold text-warning mb-1">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Template fields are not configured

                </div>


                <small class="text-muted">

                    Configure this uploaded design first.

                </small>

            `;


            previewFields.appendChild(message);


            return;

        }



        /* =====================================================
           RENDER ONLY CONFIGURED TEMPLATE FIELDS
        ====================================================== */

        fieldPositions.forEach(
            function (fieldData) {

                if (
                    !fieldData ||
                    !fieldData.key
                ) {

                    return;

                }


                const key =
                    fieldData.key;



                /* =================================================
                   PHOTO FIELD
                ================================================== */

                if (
                    key === 'profile_image' ||
                    key === 'photo'
                ) {

                    const photo =
                        getStudentImage(
                            currentStudent
                        );


                    const image =
                        document.createElement('img');


                    image.className =
                        'preview-field preview-photo';


                    if (photo) {

                        image.src =
                            photo;

                    } else {

                        image.src =
                            'data:image/svg+xml,' +
                            encodeURIComponent(`

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="150"
                                     height="150">

                                    <rect width="100%"
                                          height="100%"
                                          fill="#e9ecef"/>

                                    <text x="50%"
                                          y="50%"
                                          text-anchor="middle"
                                          dominant-baseline="middle"
                                          fill="#6c757d"
                                          font-size="16">

                                        No Photo

                                    </text>

                                </svg>

                            `);

                    }


                    applyFieldPosition(
                        image,
                        fieldData
                    );


                    previewFields.appendChild(
                        image
                    );


                    return;

                }



                /* =================================================
                   TEXT FIELD
                ================================================== */

                let value =
                    getStudentFieldValue(
                        currentStudent,
                        key
                    );


                if (
                    value === null ||
                    value === undefined ||
                    String(value).trim() === ''
                ) {

                    return;

                }


                const field =
                    document.createElement('div');


                field.className =
                    'preview-field';


                field.textContent =
                    String(value);


                applyFieldPosition(
                    field,
                    fieldData
                );


                previewFields.appendChild(
                    field
                );

            }
        );

    }



    /* =========================================================
       GENERATE BUTTON
    ========================================================== */

    function updateGenerateButton() {

        const hasStudent =
            !!currentStudent;


        const hasTemplate =
            !!currentTemplate;


        const fields =
            getTemplateFields();


        const hasConfiguredFields =
            Array.isArray(fields) &&
            fields.length > 0;


        generateButton.disabled =
            !hasStudent ||
            !hasTemplate ||
            !hasConfiguredFields;



        const status =
            document.getElementById(
                'previewStatus'
            );


        if (
            hasStudent &&
            hasTemplate &&
            hasConfiguredFields
        ) {

            status.className =
                'badge bg-success';


            status.textContent =
                'Ready to generate';

        } else if (
            hasStudent &&
            hasTemplate
        ) {

            status.className =
                'badge bg-warning text-dark';


            status.textContent =
                'Template not configured';

        } else if (hasTemplate) {

            status.className =
                'badge bg-info text-dark';


            status.textContent =
                'Select student';

        } else if (hasStudent) {

            status.className =
                'badge bg-info text-dark';


            status.textContent =
                'Select template';

        } else {

            status.className =
                'badge bg-secondary';


            status.textContent =
                'Select student & template';

        }

    }



    /* =========================================================
       ESCAPE HTML
    ========================================================== */

    function escapeHtml(value) {

        return String(value ?? '')

            .replaceAll('&', '&amp;')

            .replaceAll('<', '&lt;')

            .replaceAll('>', '&gt;')

            .replaceAll('"', '&quot;')

            .replaceAll("'", '&#039;');

    }



    /* =========================================================
       INITIAL TEMPLATE
    ========================================================== */

    const checkedTemplate =
        document.querySelector(
            '.template-radio:checked'
        );


    if (checkedTemplate) {

        currentTemplate =
            checkedTemplate;


        academicYear.value =
            checkedTemplate.dataset.academicYear || '';


        selectedTemplateName.textContent =
            checkedTemplate.dataset.templateName || '';


        selectedTemplateInfo.style.display =
            'block';

    }



    /* =========================================================
       INITIAL STATE
    ========================================================== */

    updateGenerateButton();

    renderPreview();

});

</script>

@endsection
