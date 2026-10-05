@extends('layouts.app')

@section('title', 'ID Card Template Editor')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | TEMPLATE IMAGE
    |--------------------------------------------------------------------------
    */

    $templateImage = $template->template_image ?? '';

    if (
        $templateImage &&
        !str_starts_with($templateImage, 'http://') &&
        !str_starts_with($templateImage, 'https://')
    ) {
        $templateImage = asset(
            'storage/' . ltrim($templateImage, '/')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ANALYZED FIELDS
    |--------------------------------------------------------------------------
    */

    $detectedFields = $analysis['fields'] ?? [];

    if (!is_array($detectedFields)) {
        $detectedFields = [];
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO AREA
    |--------------------------------------------------------------------------
    */

    $photo = $analysis['photo'] ?? null;


    /*
    |--------------------------------------------------------------------------
    | AVAILABLE STUDENT DATA
    |--------------------------------------------------------------------------
    */

    $studentFields = [

        'full_name' => 'Student Name',

        'first_name' => 'First Name',

        'middle_name' => 'Middle Name',

        'last_name' => 'Last Name',

        'student_id' => 'Student ID',

        'register_no' => 'GR / Register No',

        'roll_number' => 'Roll Number',

        'class' => 'Class',

        'section' => 'Section',

        'academic_year' => 'Academic Year',

        'date_of_birth' => 'Date of Birth',

        'gender' => 'Gender',

        'phone' => 'Phone',

        'father_name' => 'Father Name',

        'mother_name' => 'Mother Name',

        'guardian_name' => 'Guardian Name',

        'address' => 'Address',

        'city_village' => 'City / Village',

        'pincode' => 'Pincode',

        'religion' => 'Religion',

        'caste' => 'Caste',

        'sub_caste' => 'Sub Caste',

        'nationality' => 'Nationality',

        'mother_tongue' => 'Mother Tongue',

        'medium' => 'Medium',

        'admission_class' => 'Admission Class',

        'admission_date' => 'Admission Date',

        'pen_no' => 'PEN No',

        'appar_id' => 'APAAR ID',

        'profile_image' => 'Student Photo',

    ];

@endphp


<div class="container-fluid py-4">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                ID Card Template Editor
            </h3>

            <p class="text-muted mb-0">
                {{ $template->name }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.id-card.templates.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS / ERROR
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
         EDITOR
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
             LEFT SIDE - CARD PREVIEW
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <div>

                        <strong>
                            Template Preview
                        </strong>

                        <div class="small text-muted">
                            Design the empty area once. Student data will be filled automatically when an ID is generated.
                        </div>

                    </div>


                    <span class="badge bg-primary">
                        Visual Editor
                    </span>

                </div>


                <div class="card-body bg-light">

                    <div
                        id="editorWrapper"
                        class="editor-wrapper"
                    >

                        <div
                            id="cardCanvas"
                            class="card-canvas"
                        >

                            {{-- BACKGROUND --}}

                            <img
                                src="{{ $templateImage }}"
                                id="templateImage"
                                class="template-background"
                                alt="{{ $template->name }}"
                            >

                            {{-- =================================================
                                 DETECTED FIELDS
                            ================================================== --}}

                            <div id="fieldLayer">

                                @foreach($detectedFields as $index => $field)

                                    @php

                                        $key =
                                            $field['key']
                                            ?? 'full_name';

                                        $label =
                                            $field['label']
                                            ?? $key;

                                        $x =
                                            $field['x']
                                            ?? 10;

                                        $y =
                                            $field['y']
                                            ?? 10;

                                        $width =
                                            $field['width']
                                            ?? 30;

                                        $height =
                                            $field['height']
                                            ?? 5;

                                        $fontSize =
                                            $field['font_size']
                                            ?? 14;

                                        $fontWeight =
                                            $field['font_weight']
                                            ?? 'normal';

                                        $textAlign =
                                            $field['text_align']
                                            ?? 'left';

                                        $photoShape =
                                            $field['photo_shape']
                                            ?? 'circle';

                                    @endphp


                                    <div
                                        class="editor-field"
                                        data-index="{{ $index }}"
                                        data-key="{{ $key }}"
                                        data-label="{{ $label }}"
                                        data-x="{{ $x }}"
                                        data-y="{{ $y }}"
                                        data-width="{{ $width }}"
                                        data-height="{{ $height }}"
                                        data-font-size="{{ $fontSize }}"
                                        data-font-weight="{{ $fontWeight }}"
                                        data-text-align="{{ $textAlign }}"
                                        data-photo-shape="{{ $photoShape }}"

                                        style="
                                            left:{{ $x }}%;
                                            top:{{ $y }}%;
                                            width:{{ $width }}%;
                                            height:{{ $height }}%;
                                            font-size:{{ $fontSize }}px;
                                            font-weight:{{ $fontWeight }};
                                            text-align:{{ $textAlign }};
                                        "
                                    >

                                        <span class="field-value">
                                            <span class="field-label">{{ $label }}</span>
                                            <span class="field-preview-value">[{{ $studentFields[$key] ?? $key }}]</span>
                                        </span>


                                        <span class="resize-handle"></span>

                                    </div>

                                @endforeach


                                {{-- =============================================
                                     PHOTO
                                ============================================== --}}

                                @if($photo)

                                    @php

                                        $photoX =
                                            $photo['x']
                                            ?? 35;

                                        $photoY =
                                            $photo['y']
                                            ?? 15;

                                        $photoWidth =
                                            $photo['width']
                                            ?? 25;

                                        $photoHeight =
                                            $photo['height']
                                            ?? 25;

                                    @endphp


                                    <div
                                        class="editor-field editor-photo {{ ($photo['shape'] ?? 'circle') === 'square' ? 'photo-square' : 'photo-circle' }}"
                                        data-index="photo"
                                        data-key="profile_image"
                                        data-label="Student Photo"
                                        data-x="{{ $photoX }}"
                                        data-y="{{ $photoY }}"
                                        data-width="{{ $photoWidth }}"
                                        data-height="{{ $photoHeight }}"
                                        data-font-size="0"
                                        data-font-weight="normal"
                                        data-text-align="center"
                                        data-photo-shape="{{ $photo['shape'] ?? 'circle' }}"

                                        style="
                                            left:{{ $photoX }}%;
                                            top:{{ $photoY }}%;
                                            width:{{ $photoWidth }}%;
                                            height:{{ $photoHeight }}%;
                                        "
                                    >

                                        <div class="photo-placeholder">

                                            <i class="bi bi-person-bounding-box"></i>

                                            <span>
                                                Student Photo
                                            </span>

                                        </div>

                                        <span class="resize-handle"></span>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             RIGHT SIDE - CONTROLS
        ====================================================== --}}

        <div class="col-xl-4">


            {{-- =================================================
                 FIELD LIST
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Template Fields
                    </strong>

                    <div class="small text-muted">
                        Select a field to edit its data mapping.
                    </div>

                </div>


                <div class="card-body">

                    <div id="fieldList">

                        @if(count($detectedFields) === 0 && !$photo)
                            <div class="empty-field-message text-muted small text-center py-2">
                                No fields added yet.
                            </div>
                        @endif

                        @foreach($detectedFields as $index => $field)

                            @php

                                $key =
                                    $field['key']
                                    ?? 'full_name';

                                $label =
                                    $field['label']
                                    ?? $key;

                            @endphp


                            <button
                                type="button"
                                class="field-list-item"
                                data-target="{{ $index }}"
                            >

                                <span>

                                    <strong>
                                        {{ $label }}
                                    </strong>

                                    <small>
                                        {{ $studentFields[$key] ?? $key }}
                                    </small>

                                </span>


                                <i class="bi bi-chevron-right"></i>

                            </button>

                        @endforeach


                        @if($photo)

                            <button
                                type="button"
                                class="field-list-item"
                                data-target="photo"
                            >

                                <span>

                                    <strong>
                                        Student Photo
                                    </strong>

                                    <small>
                                        Student Profile Image
                                    </small>

                                </span>

                                <i class="bi bi-chevron-right"></i>

                            </button>

                        @endif

                    </div>


                    @if(count($detectedFields) === 0 && !$photo)

                        <div class="alert alert-warning mb-0">

                            No fields have been added yet.

                            Use “Add Student Field” to start designing this template.

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                 FIELD EDITOR
            ================================================== --}}

            <div
                class="card border-0 shadow-sm mb-4"
                id="fieldEditor"
                style="display:none;"
            >

                <div class="card-header bg-white">

                    <strong>
                        Edit Field
                    </strong>

                </div>


                <div class="card-body">


                    {{-- DATA MAPPING --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Student Data
                        </label>

                        <select
                            id="fieldKey"
                            class="form-select"
                        >

                            @foreach($studentFields as $value => $name)

                                <option value="{{ $value }}">
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- LABEL --}}

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Field Label
                        </label>

                        <input
                            type="text"
                            id="fieldLabel"
                            class="form-control"
                        >

                    </div>



                    {{-- X / Y --}}

                    <div class="row g-2 mb-3">

                        <div class="col-6">

                            <label class="form-label">
                                X Position %
                            </label>

                            <input
                                type="number"
                                id="fieldX"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.1"
                            >

                        </div>


                        <div class="col-6">

                            <label class="form-label">
                                Y Position %
                            </label>

                            <input
                                type="number"
                                id="fieldY"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.1"
                            >

                        </div>

                    </div>



                    {{-- WIDTH / HEIGHT --}}

                    <div class="row g-2 mb-3">

                        <div class="col-6">

                            <label class="form-label">
                                Width %
                            </label>

                            <input
                                type="number"
                                id="fieldWidth"
                                class="form-control"
                                min="1"
                                max="100"
                                step="0.1"
                            >

                        </div>


                        <div class="col-6">

                            <label class="form-label">
                                Height %
                            </label>

                            <input
                                type="number"
                                id="fieldHeight"
                                class="form-control"
                                min="1"
                                max="100"
                                step="0.1"
                            >

                        </div>

                    </div>



                    {{-- FONT --}}

                    <div class="row g-2 mb-3">

                        <div class="col-6">

                            <label class="form-label">
                                Font Size
                            </label>

                            <input
                                type="number"
                                id="fieldFontSize"
                                class="form-control"
                                min="1"
                                max="100"
                            >

                        </div>


                        <div class="col-6">

                            <label class="form-label">
                                Font Weight
                            </label>

                            <select
                                id="fieldFontWeight"
                                class="form-select"
                            >

                                <option value="normal">
                                    Normal
                                </option>

                                <option value="500">
                                    Medium
                                </option>

                                <option value="600">
                                    Semi Bold
                                </option>

                                <option value="700">
                                    Bold
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- ALIGNMENT --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Text Alignment
                        </label>

                        <select
                            id="fieldTextAlign"
                            class="form-select"
                        >

                            <option value="left">
                                Left
                            </option>

                            <option value="center">
                                Center
                            </option>

                            <option value="right">
                                Right
                            </option>

                        </select>

                    </div>


                    {{-- PHOTO SHAPE --}}

                    <div
                        class="mb-3"
                        id="photoShapeGroup"
                        style="display:none;"
                    >

                        <label class="form-label fw-semibold">
                            Photo Shape
                        </label>

                        <div class="photo-shape-options">
                            <label class="photo-shape-option">
                                <input type="radio" name="photoShape" value="circle" id="photoShapeCircle">
                                <span class="shape-option-content"><span class="shape-preview shape-preview-circle"><i class="bi bi-person"></i></span><span><strong>Circle</strong><small>Round photo</small></span></span>
                            </label>
                            <label class="photo-shape-option">
                                <input type="radio" name="photoShape" value="square" id="photoShapeSquare">
                                <span class="shape-option-content"><span class="shape-preview shape-preview-square"><i class="bi bi-person"></i></span><span><strong>Square</strong><small>Square photo</small></span></span>
                            </label>
                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn btn-outline-danger w-100"
                        id="removeField"
                    >

                        <i class="bi bi-trash me-1"></i>

                        Remove Field

                    </button>

                </div>

            </div>



            {{-- =================================================
                 ADD FIELD
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <button
                        type="button"
                        id="addField"
                        class="btn btn-outline-primary w-100"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Student Field
                    </button>

                    <div class="small text-muted mt-2">
                        Add only the fields you want on this school's ID card.
                    </div>

                </div>

            </div>


            {{-- =================================================
                 SAVE
            ================================================== --}}

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <button
                        type="button"
                        id="saveTemplate"
                        class="btn btn-primary w-100"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Save Template

                    </button>


                    <div
                        id="saveMessage"
                        class="mt-3"
                    ></div>

                </div>

            </div>

        </div>

    </div>

</div>



<style>

/* =========================================================
   EDITOR
========================================================= */

.editor-wrapper {

    display: flex;

    justify-content: center;

    align-items: flex-start;

    padding: 25px;

    overflow: auto;

}


.card-canvas {

    position: relative;

    display: inline-block;

    width: min(620px, 100%);

    line-height: 0;

}


.template-background {

    display: block;

    width: 100%;

    height: auto;

    border-radius: 8px;

    box-shadow:
        0 10px 30px rgba(0,0,0,.12);

}


/* =========================================================
   FIELD
========================================================= */

.editor-field {

    position: absolute;

    box-sizing: border-box;

    border: 2px dashed #0d6efd;

    background: rgba(13,110,253,.10);

    color: #111827;

    line-height: 1.2;

    padding: 3px 5px;

    cursor: move;

    user-select: none;

    min-width: 30px;

    min-height: 15px;

    z-index: 10;

}


.editor-field:hover {

    background: rgba(13,110,253,.18);

}


.editor-field.selected {

    border: 2px solid #0d6efd;

    box-shadow:
        0 0 0 3px rgba(13,110,253,.15);

}


.field-value {
    display: flex;
    align-items: baseline;
    gap: 6px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    line-height: 1.2;
}

.field-label {
    flex: 0 0 auto;
}

.field-preview-value {
    opacity: .75;
    font-weight: normal;
}

.photo-circle {
    border-radius: 50%;
    overflow: hidden;
}

.photo-square {
    border-radius: 0;
    overflow: hidden;
}

.photo-shape-options { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.photo-shape-option { position:relative; display:block; cursor:pointer; margin:0; }
.photo-shape-option input { position:absolute; opacity:0; pointer-events:none; }
.shape-option-content { display:flex; align-items:center; gap:10px; padding:10px; border:1px solid #e5e7eb; border-radius:10px; background:#fff; transition:.2s; }
.photo-shape-option:hover .shape-option-content { border-color:#0d6efd; background:#f8faff; }
.photo-shape-option input:checked + .shape-option-content { border-color:#0d6efd; background:#eef5ff; box-shadow:0 0 0 2px rgba(13,110,253,.12); }
.shape-option-content > span:last-child { display:flex; flex-direction:column; line-height:1.2; }
.shape-option-content small { margin-top:3px; color:#6b7280; font-size:11px; }
.shape-preview { width:38px; height:38px; flex:0 0 38px; display:flex; align-items:center; justify-content:center; background:#e9f2ff; color:#0d6efd; font-size:20px; }
.shape-preview-circle { border-radius:50%; }
.shape-preview-square { border-radius:5px; }
@media(max-width:576px){ .photo-shape-options{grid-template-columns:1fr;} }


/* =========================================================
   RESIZE
========================================================= */

.resize-handle {

    position: absolute;

    width: 10px;

    height: 10px;

    right: -5px;

    bottom: -5px;

    background: #0d6efd;

    border-radius: 2px;

    cursor: nwse-resize;

}


/* =========================================================
   PHOTO
========================================================= */

.editor-photo {

    border-color: #198754;

    background: rgba(25,135,84,.12);

}


.editor-photo .resize-handle {

    background: #198754;

}


.photo-placeholder {

    width: 100%;

    height: 100%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    color: #198754;

    font-size: 12px;

    line-height: 1.2;

}


.photo-placeholder i {

    font-size: 25px;

    margin-bottom: 4px;

}


/* =========================================================
   FIELD LIST
========================================================= */

.field-list-item {

    width: 100%;

    display: flex;

    justify-content: space-between;

    align-items: center;

    border: 1px solid #e5e7eb;

    background: white;

    border-radius: 10px;

    padding: 12px;

    margin-bottom: 8px;

    text-align: left;

}


.field-list-item:hover {

    background: #f8faff;

    border-color: #0d6efd;

}


.field-list-item span {

    display: flex;

    flex-direction: column;

}


.field-list-item small {

    color: #6b7280;

    margin-top: 3px;

}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width: 768px) {

    .editor-wrapper {

        padding: 10px;

    }

    .card-canvas {

        width: 100%;

    }

}

</style>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        let fields = @json($detectedFields);

        let photo = @json($photo);

        // OCR words detected from the uploaded ID-card image.
        let ocrWords = @json($analysis['words'] ?? []);

        // Original image dimensions used by OCR.
        let imageWidth = Number(@json($analysis['image']['width'] ?? 1));
        let imageHeight = Number(@json($analysis['image']['height'] ?? 1));


        /*
        |--------------------------------------------------------------------------
        | DOM
        |--------------------------------------------------------------------------
        */

        const canvas =
            document.getElementById('cardCanvas');

        const fieldLayer =
            document.getElementById('fieldLayer');

        const editor =
            document.getElementById('fieldEditor');

        const fieldKey =
            document.getElementById('fieldKey');

        const fieldLabel =
            document.getElementById('fieldLabel');

        const fieldX =
            document.getElementById('fieldX');

        const fieldY =
            document.getElementById('fieldY');

        const fieldWidth =
            document.getElementById('fieldWidth');

        const fieldHeight =
            document.getElementById('fieldHeight');

        const fieldFontSize =
            document.getElementById('fieldFontSize');

        const fieldFontWeight =
            document.getElementById('fieldFontWeight');

        const fieldTextAlign =
            document.getElementById('fieldTextAlign');

        const photoShapeGroup =
            document.getElementById('photoShapeGroup');

        const photoShapeCircle =
            document.getElementById('photoShapeCircle');

        const photoShapeSquare =
            document.getElementById('photoShapeSquare');

        const removeField =
            document.getElementById('removeField');

        const saveTemplate =
            document.getElementById('saveTemplate');

        const saveMessage =
            document.getElementById('saveMessage');


        let selectedElement = null;

        let selectedIndex = null;

        let isDragging = false;

        let isResizing = false;

        let startX = 0;

        let startY = 0;

        let startLeft = 0;

        let startTop = 0;

        let startWidth = 0;

        let startHeight = 0;



        /*
        |--------------------------------------------------------------------------
        | SELECT FIELD
        |--------------------------------------------------------------------------
        */


        function bindEditorField(element) {

            element.addEventListener('mousedown', function (event) {

                if (event.target.classList.contains('resize-handle')) {
                    return;
                }

                selectElement(element);
            });
        }


        function selectElement(element) {

            document
                .querySelectorAll('.editor-field')
                .forEach(function (item) {

                    item.classList.remove('selected');

                });


            selectedElement = element;

            selectedElement.classList.add(
                'selected'
            );


            selectedIndex =
                selectedElement.dataset.index;


            editor.style.display =
                'block';


            fieldKey.value =
                selectedElement.dataset.key
                || 'full_name';


            fieldLabel.value =
                selectedElement.dataset.label
                || '';


            fieldX.value =
                selectedElement.dataset.x
                || 0;


            fieldY.value =
                selectedElement.dataset.y
                || 0;


            fieldWidth.value =
                selectedElement.dataset.width
                || 30;


            fieldHeight.value =
                selectedElement.dataset.height
                || 8;


            fieldFontSize.value =
                selectedElement.dataset.fontSize
                || 14;


            fieldFontWeight.value =
                selectedElement.dataset.fontWeight
                || 'normal';


            fieldTextAlign.value =
                selectedElement.dataset.textAlign
                || 'left';

            const isPhoto =
                selectedElement.dataset.key === 'profile_image';

            if (photoShapeGroup) {
                photoShapeGroup.style.display =
                    isPhoto ? 'block' : 'none';
            }

            const selectedPhotoShape =
                selectedElement.dataset.photoShape || 'circle';

            if (photoShapeCircle) {
                photoShapeCircle.checked = selectedPhotoShape === 'circle';
            }

            if (photoShapeSquare) {
                photoShapeSquare.checked = selectedPhotoShape === 'square';
            }


            if (
                selectedElement.dataset.key ===
                'profile_image'
            ) {

                fieldKey.value =
                    'profile_image';

                fieldLabel.value =
                    'Student Photo';

                fieldFontSize.value =
                    0;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | FIELD LIST CLICK
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.field-list-item')
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const target =
                            button.dataset.target;

                        const element =
                            document.querySelector(
                                `.editor-field[data-index="${target}"]`
                            );

                        if (element) {

                            selectElement(
                                element
                            );

                            element.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                        }

                    }
                );

            });



        /*
        |--------------------------------------------------------------------------
        | FIELD CLICK
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.editor-field')
            .forEach(function (element) {
                bindEditorField(element);
            });



        /*
        |--------------------------------------------------------------------------
        | DRAGGING
        |--------------------------------------------------------------------------
        */

        fieldLayer.addEventListener(
            'mousedown',
            function (event) {

                const element =
                    event.target.closest(
                        '.editor-field'
                    );


                if (!element) {

                    return;

                }


                if (
                    event.target.classList
                        .contains('resize-handle')
                ) {

                    isResizing = true;

                } else {

                    isDragging = true;

                }


                selectElement(element);


                const rect =
                    canvas.getBoundingClientRect();


                startX =
                    event.clientX;

                startY =
                    event.clientY;


                startLeft =
                    parseFloat(
                        element.dataset.x
                    );


                startTop =
                    parseFloat(
                        element.dataset.y
                    );


                startWidth =
                    parseFloat(
                        element.dataset.width
                    );


                startHeight =
                    parseFloat(
                        element.dataset.height
                    );


                event.preventDefault();

            }
        );



        document.addEventListener(
            'mousemove',
            function (event) {

                if (
                    !selectedElement
                ) {

                    return;

                }


                if (
                    !isDragging &&
                    !isResizing
                ) {

                    return;

                }


                const rect =
                    canvas.getBoundingClientRect();


                const deltaX =
                    (
                        event.clientX -
                        startX
                    ) / rect.width * 100;


                const deltaY =
                    (
                        event.clientY -
                        startY
                    ) / rect.height * 100;


                if (isDragging) {

                    const newX =
                        Math.max(
                            0,
                            Math.min(
                                100 - startWidth,
                                startLeft + deltaX
                            )
                        );


                    const newY =
                        Math.max(
                            0,
                            Math.min(
                                100 - startHeight,
                                startTop + deltaY
                            )
                        );


                    updateElementPosition(
                        newX,
                        newY
                    );

                }


                if (isResizing) {

                    const newWidth =
                        Math.max(
                            2,
                            Math.min(
                                100 - startLeft,
                                startWidth + deltaX
                            )
                        );


                    const newHeight =
                        Math.max(
                            2,
                            Math.min(
                                100 - startTop,
                                startHeight + deltaY
                            )
                        );


                    updateElementSize(
                        newWidth,
                        newHeight
                    );

                }

            }
        );



        document.addEventListener(
            'mouseup',
            function () {

                isDragging = false;

                isResizing = false;

            }
        );



        /*
        |--------------------------------------------------------------------------
        | UPDATE POSITION
        |--------------------------------------------------------------------------
        */

        function updateElementPosition(
            x,
            y
        ) {

            selectedElement.dataset.x =
                x.toFixed(2);

            selectedElement.dataset.y =
                y.toFixed(2);


            selectedElement.style.left =
                x + '%';

            selectedElement.style.top =
                y + '%';


            fieldX.value =
                x.toFixed(2);

            fieldY.value =
                y.toFixed(2);

        }



        /*
        |--------------------------------------------------------------------------
        | UPDATE SIZE
        |--------------------------------------------------------------------------
        */

        function updateElementSize(
            width,
            height
        ) {

            selectedElement.dataset.width =
                width.toFixed(2);

            selectedElement.dataset.height =
                height.toFixed(2);


            selectedElement.style.width =
                width + '%';

            selectedElement.style.height =
                height + '%';


            fieldWidth.value =
                width.toFixed(2);

            fieldHeight.value =
                height.toFixed(2);

        }



        /*
        |--------------------------------------------------------------------------
        | FORM CONTROL CHANGES
        |--------------------------------------------------------------------------
        */

        fieldKey.addEventListener(
    'change',
    function () {

        if (!selectedElement) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Save selected student-data key
        |--------------------------------------------------------------------------
        */

        selectedElement.dataset.key = this.value;

        /*
        |--------------------------------------------------------------------------
        | Get selected option text
        |--------------------------------------------------------------------------
        */

        const selectedOption =
            this.options[this.selectedIndex];

        const selectedLabel =
            selectedOption
                ? selectedOption.text.trim()
                : 'Student Field';

        /*
        |--------------------------------------------------------------------------
        | Update field label
        |--------------------------------------------------------------------------
        */

        selectedElement.dataset.label =
            selectedLabel;

        /*
        |--------------------------------------------------------------------------
        | Update visible field text
        |--------------------------------------------------------------------------
        */

        const labelElement =
            selectedElement.querySelector('.field-label');

        const previewElement =
            selectedElement.querySelector('.field-preview-value');

        if (labelElement) {
            labelElement.textContent = selectedLabel;
        }

        if (previewElement) {
            previewElement.textContent =
                '[' + selectedLabel + ']';
        }

        /*
        |--------------------------------------------------------------------------
        | Photo field
        |--------------------------------------------------------------------------
        */

        if (
            this.value ===
            'profile_image'
        ) {

            selectedElement.classList.add(
                'editor-photo',
                'photo-circle'
            );

            selectedElement.classList.remove(
                'photo-square'
            );

            selectedElement.dataset.label =
                'Student Photo';

            selectedElement.dataset.photoShape =
                selectedElement.dataset.photoShape || 'circle';

            selectedElement.innerHTML = `
                <div class="photo-placeholder">
                    <i class="bi bi-person-bounding-box"></i>
                    <span>Student Photo</span>
                </div>
                <span class="resize-handle"></span>
            `;

            if (photoShapeGroup) {
                photoShapeGroup.style.display = 'block';
            }

            if (photoShape) {
                photoShape.value =
                    selectedElement.dataset.photoShape;
            }

        } else {

            selectedElement.classList.remove(
                'editor-photo',
                'photo-circle',
                'photo-square'
            );

            const currentLabel =
                selectedElement.dataset.label
                || getStudentFieldLabel(this.value);

            selectedElement.innerHTML = `
                <span class="field-value">
                    <span class="field-label">${escapeHtml(currentLabel)}</span>
                    <span class="field-preview-value">[${escapeHtml(getStudentFieldLabel(this.value))}]</span>
                </span>
                <span class="resize-handle"></span>
            `;

            if (photoShapeGroup) {
                photoShapeGroup.style.display = 'none';
            }
        }

        updateFieldListItem(selectedElement);

    }
);


        fieldLabel.addEventListener(
    'input',
    function () {

        if (!selectedElement) {
            return;
        }

        selectedElement.dataset.label =
            this.value;

        const labelElement =
            selectedElement.querySelector('.field-label');

        if (labelElement) {
            labelElement.textContent = this.value;
        }

        updateFieldListItem(selectedElement);

    }
);



        fieldX.addEventListener(
            'input',
            function () {

                if (!selectedElement) return;

                updateElementPosition(
                    parseFloat(this.value) || 0,
                    parseFloat(fieldY.value) || 0
                );

            }
        );



        fieldY.addEventListener(
            'input',
            function () {

                if (!selectedElement) return;

                updateElementPosition(
                    parseFloat(fieldX.value) || 0,
                    parseFloat(this.value) || 0
                );

            }
        );



        fieldWidth.addEventListener(
            'input',
            function () {

                if (!selectedElement) return;

                updateElementSize(
                    parseFloat(this.value) || 2,
                    parseFloat(fieldHeight.value) || 2
                );

            }
        );



        fieldHeight.addEventListener(
            'input',
            function () {

                if (!selectedElement) return;

                updateElementSize(
                    parseFloat(fieldWidth.value) || 2,
                    parseFloat(this.value) || 2
                );

            }
        );



        fieldFontSize.addEventListener(
            'input',
            function () {

                if (!selectedElement) return;

                selectedElement.dataset.fontSize =
                    this.value;

                selectedElement.style.fontSize =
                    this.value + 'px';

            }
        );



        fieldFontWeight.addEventListener(
            'change',
            function () {

                if (!selectedElement) return;

                selectedElement.dataset.fontWeight =
                    this.value;

                selectedElement.style.fontWeight =
                    this.value;

            }
        );



        fieldTextAlign.addEventListener(
            'change',
            function () {

                if (!selectedElement) return;

                selectedElement.dataset.textAlign =
                    this.value;

                selectedElement.style.textAlign =
                    this.value;

            }
        );


        function applyPhotoShape(shape) {

            if (!selectedElement) return;
            if (selectedElement.dataset.key !== 'profile_image') return;

            shape = shape === 'square' ? 'square' : 'circle';
            selectedElement.dataset.photoShape = shape;

            selectedElement.classList.toggle('photo-circle', shape === 'circle');
            selectedElement.classList.toggle('photo-square', shape === 'square');
        }

        if (photoShapeCircle) {
            photoShapeCircle.addEventListener('change', function () {
                if (this.checked) applyPhotoShape('circle');
            });
        }

        if (photoShapeSquare) {
            photoShapeSquare.addEventListener('change', function () {
                if (this.checked) applyPhotoShape('square');
            });
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE FIELD
        |--------------------------------------------------------------------------
        */

        removeField.addEventListener(
            'click',
            function () {

                if (!selectedElement) {

                    return;

                }


                const removedIndex =
                    selectedElement.dataset.index;

                const listItem =
                    document.querySelector(
                        `.field-list-item[data-target="${removedIndex}"]`
                    );

                if (listItem) {
                    listItem.remove();
                }

                selectedElement.remove();


                selectedElement = null;

                selectedIndex = null;

                editor.style.display =
                    'none';

            }
        );



        /*
        |--------------------------------------------------------------------------
        | ADD FIELD
        |--------------------------------------------------------------------------
        */

        const addField =
            document.getElementById('addField');

        if (addField) {

            addField.addEventListener('click', function () {

                const index =
                    'new_' + Date.now();

                const element =
                    document.createElement('div');

                element.className = 'editor-field';

                element.dataset.index = index;
                element.dataset.key = 'full_name';
                element.dataset.label = 'Student Name';

                element.dataset.x = '25';
                element.dataset.y = '35';
                element.dataset.width = '50';
                element.dataset.height = '8';

                element.dataset.fontSize = '14';
                element.dataset.fontWeight = 'normal';
                element.dataset.textAlign = 'left';
                element.dataset.photoShape = 'circle';
                element.dataset.sampleText = '';

                element.style.left = '25%';
                element.style.top = '35%';
                element.style.width = '50%';
                element.style.height = '8%';
                element.style.fontSize = '14px';

                element.innerHTML = `
                    <span class="field-value">
                        <span class="field-label">Student Name</span>
                        <span class="field-preview-value">[Student Name]</span>
                    </span>
                    <span class="resize-handle"></span>
                `;

                fieldLayer.appendChild(element);

                bindEditorField(element);
                addFieldToList(element);
                selectElement(element);
            });
        }


        /*
        |--------------------------------------------------------------------------
        | DYNAMIC FIELD LIST
        |--------------------------------------------------------------------------
        */

        function addFieldToList(element) {

            const list =
                document.getElementById('fieldList');

            if (!list) return;

            const empty =
                list.querySelector('.empty-field-message');

            if (empty) empty.remove();

            const button =
                document.createElement('button');

            button.type = 'button';
            button.className = 'field-list-item';
            button.dataset.target = element.dataset.index;

            button.innerHTML = `
                <span>
                    <strong class="field-list-label">
                        ${escapeHtml(element.dataset.label)}
                    </strong>
                    <small class="field-list-key">
                        ${escapeHtml(getStudentFieldLabel(element.dataset.key))}
                    </small>
                </span>
                <i class="bi bi-chevron-right"></i>
            `;

            button.addEventListener('click', function () {
                selectElement(element);
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });

            list.appendChild(button);
        }


        function getStudentFieldLabel(key) {

            const labels = @json($studentFields);

            return labels[key] || key;
        }


        function updateFieldListItem(element) {

            const button =
                document.querySelector(
                    `.field-list-item[data-target="${element.dataset.index}"]`
                );

            if (!button) return;

            const label =
                button.querySelector('.field-list-label');

            const key =
                button.querySelector('.field-list-key');

            if (label) {
                label.textContent =
                    element.dataset.label || 'Student Field';
            }

            if (key) {
                key.textContent =
                    getStudentFieldLabel(element.dataset.key);
            }
        }


        function escapeHtml(value) {

            const div =
                document.createElement('div');

            div.textContent = value ?? '';

            return div.innerHTML;
        }


        /*
         |--------------------------------------------------------------------------
         | GET OCR TEXT INSIDE FIELD
         |--------------------------------------------------------------------------
        */

        function getSampleTextForField(element) {

            if (!Array.isArray(ocrWords) || !ocrWords.length) {
                return '';
            }

            const x = parseFloat(element.dataset.x) || 0;
            const y = parseFloat(element.dataset.y) || 0;
            const width = parseFloat(element.dataset.width) || 0;
            const height = parseFloat(element.dataset.height) || 0;

            if (!imageWidth || !imageHeight || width <= 0 || height <= 0) {
                return '';
            }

            const fieldLeft = (x / 100) * imageWidth;
            const fieldTop = (y / 100) * imageHeight;
            const fieldRight = ((x + width) / 100) * imageWidth;
            const fieldBottom = ((y + height) / 100) * imageHeight;

            const matchedWords = ocrWords.filter(function (word) {

                if (!word || !String(word.text ?? '').trim()) {
                    return false;
                }

                const wordLeft = Number(word.left) || 0;
                const wordTop = Number(word.top) || 0;
                const wordWidth = Number(word.width) || 0;
                const wordHeight = Number(word.height) || 0;
                const wordRight = Number(word.right) || (wordLeft + wordWidth);
                const wordBottom = Number(word.bottom) || (wordTop + wordHeight);

                return (
                    wordRight > fieldLeft &&
                    wordLeft < fieldRight &&
                    wordBottom > fieldTop &&
                    wordTop < fieldBottom
                );
            });

            matchedWords.sort(function (a, b) {
                const topA = Number(a.top) || 0;
                const topB = Number(b.top) || 0;

                if (Math.abs(topA - topB) > 10) {
                    return topA - topB;
                }

                return (Number(a.left) || 0) - (Number(b.left) || 0);
            });

            return matchedWords
                .map(function (word) {
                    return String(word.text ?? '').trim();
                })
                .filter(Boolean)
                .join(' ');
        }


        /*
         |--------------------------------------------------------------------------
         | SAVE TEMPLATE
         |--------------------------------------------------------------------------
        */

        saveTemplate.addEventListener(
            'click',
            async function () {

                saveMessage.innerHTML = `
                    <div class="alert alert-info">
                        Saving template...
                    </div>
                `;

                const elements = Array.from(
                    document.querySelectorAll('.editor-field')
                );

                const payload = elements.map(function (element) {
                    return {
                        key: element.dataset.key || 'full_name',
                        label: element.dataset.label || '',
                        sample_text: getSampleTextForField(element),
                        x: parseFloat(element.dataset.x) || 0,
                        y: parseFloat(element.dataset.y) || 0,
                        width: parseFloat(element.dataset.width) || 30,
                        height: parseFloat(element.dataset.height) || 8,
                        font_size: parseFloat(element.dataset.fontSize) || 14,
                        font_weight: element.dataset.fontWeight || 'normal',
                        text_align: element.dataset.textAlign || 'left',
                        photo_shape: element.dataset.photoShape || 'circle'
                    };
                });

                try {
                    const response = await fetch(
                        "{{ route('admin.id-card.templates.save-positions', ['template' => $template->id]) }}",
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]'
                                )?.content
                            },
                            body: JSON.stringify({
                                field_positions: payload
                            })
                        }
                    );

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            result.message || 'Unable to save template.'
                        );
                    }

                    saveMessage.innerHTML = `
                        <div class="alert alert-success">
                            Template saved successfully.
                        </div>
                    `;

                } catch (error) {
                    console.error('Template save error:', error);

                    saveMessage.innerHTML = `
                        <div class="alert alert-danger">
                            ${error.message}
                        </div>
                    `;
                }
            }
        );

    });

</script>

@endsection