@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="bi bi-pencil-square text-primary me-2"></i>

                Configure ID Card Template

            </h3>

            <p class="text-muted mb-0">

                Configure the fields required for this uploaded design.

            </p>

        </div>


        <a href="{{ route('admin.id-card.templates.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Templates

        </a>

    </div>


    {{-- Success --}}
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


    {{-- Error --}}
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

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Template Info --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="d-flex align-items-center">

                        <div class="template-icon me-3">

                            <i class="bi bi-card-image"></i>

                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                {{ $template->name }}
                            </h5>

                            <div class="text-muted small">

                                Academic Year:
                                <strong>
                                    {{ $template->academic_year }}
                                </strong>

                                <span class="mx-2">•</span>

                                Template:
                                <strong>
                                    {{ $template->slug }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                    <span class="badge rounded-pill
                        {{ $template->status === 'active'
                            ? 'bg-success'
                            : 'bg-secondary' }} px-3 py-2">

                        {{ ucfirst($template->status) }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- ======================================================
             LEFT: AVAILABLE FIELDS
        ======================================================= --}}
        <div class="col-xl-3 col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-list-check text-primary me-2"></i>

                        Student Fields

                    </h5>

                    <small class="text-muted">

                        Select only fields that exist in this design.

                    </small>

                </div>


                <div class="card-body p-3">

                    <div id="fieldList">


                        {{-- Photo --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="profile_image"
                                   data-label="Student Photo"
                                   class="field-check">

                            <span>

                                <i class="bi bi-person-bounding-box"></i>

                                Student Photo

                            </span>

                        </label>


                        {{-- Name --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="full_name"
                                   data-label="Student Name"
                                   class="field-check">

                            <span>

                                <i class="bi bi-person"></i>

                                Student Name

                            </span>

                        </label>


                        {{-- Student ID --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="student_id"
                                   data-label="Student ID"
                                   class="field-check">

                            <span>

                                <i class="bi bi-person-vcard"></i>

                                Student ID

                            </span>

                        </label>


                        {{-- GR --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="register_no"
                                   data-label="GR No"
                                   class="field-check">

                            <span>

                                <i class="bi bi-journal-text"></i>

                                GR No

                            </span>

                        </label>


                        {{-- PEN --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="pen_no"
                                   data-label="PEN No"
                                   class="field-check">

                            <span>

                                <i class="bi bi-upc-scan"></i>

                                PEN No

                            </span>

                        </label>


                        {{-- APAAR --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="appar_id"
                                   data-label="APAAR ID"
                                   class="field-check">

                            <span>

                                <i class="bi bi-fingerprint"></i>

                                APAAR ID

                            </span>

                        </label>


                        {{-- Class --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="class"
                                   data-label="Class"
                                   class="field-check">

                            <span>

                                <i class="bi bi-building"></i>

                                Class

                            </span>

                        </label>


                        {{-- Section --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="section"
                                   data-label="Section"
                                   class="field-check">

                            <span>

                                <i class="bi bi-diagram-3"></i>

                                Section

                            </span>

                        </label>


                        {{-- Roll Number --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="roll_number"
                                   data-label="Roll Number"
                                   class="field-check">

                            <span>

                                <i class="bi bi-123"></i>

                                Roll Number

                            </span>

                        </label>


                        {{-- DOB --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="date_of_birth"
                                   data-label="Date of Birth"
                                   class="field-check">

                            <span>

                                <i class="bi bi-calendar-event"></i>

                                Date of Birth

                            </span>

                        </label>


                        {{-- Blood --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="blood_group"
                                   data-label="Blood Group"
                                   class="field-check">

                            <span>

                                <i class="bi bi-droplet"></i>

                                Blood Group

                            </span>

                        </label>


                        {{-- Phone --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="phone"
                                   data-label="Phone"
                                   class="field-check">

                            <span>

                                <i class="bi bi-telephone"></i>

                                Phone

                            </span>

                        </label>


                        {{-- Father --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="father_name"
                                   data-label="Father Name"
                                   class="field-check">

                            <span>

                                <i class="bi bi-person"></i>

                                Father Name

                            </span>

                        </label>


                        {{-- Mother --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="mother_name"
                                   data-label="Mother Name"
                                   class="field-check">

                            <span>

                                <i class="bi bi-person"></i>

                                Mother Name

                            </span>

                        </label>


                        {{-- Address --}}
                        <label class="field-option">

                            <input type="checkbox"
                                   value="address"
                                   data-label="Address"
                                   class="field-check">

                            <span>

                                <i class="bi bi-geo-alt"></i>

                                Address

                            </span>

                        </label>


                    </div>


                    <div class="alert alert-light border mt-3 mb-0 small">

                        <i class="bi bi-lightbulb me-1"></i>

                        Select only the information that has a
                        designated space on this uploaded design.

                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             CENTER: DESIGN CANVAS
        ======================================================= --}}
        <div class="col-xl-6 col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-layout-text-window-reverse text-primary me-2"></i>

                                Uploaded Design

                            </h5>

                            <small class="text-muted">

                                Drag selected fields onto the correct location.

                            </small>

                        </div>

                        <span class="badge bg-primary-subtle text-primary">

                            {{ strtoupper(pathinfo($template->template_image, PATHINFO_EXTENSION)) }}

                        </span>

                    </div>

                </div>


                <div class="card-body p-4">

                    <div id="cardCanvas"
                         class="card-canvas">


                        @php

                            $templateImage =
                                $template->template_image ?? '';

                            if (
                                str_starts_with(
                                    $templateImage,
                                    'http://'
                                ) ||
                                str_starts_with(
                                    $templateImage,
                                    'https://'
                                )
                            ) {

                                $templateImageUrl =
                                    $templateImage;

                            } else {

                                $templateImageUrl =
                                    asset(
                                        'storage/' .
                                        ltrim(
                                            $templateImage,
                                            '/'
                                        )
                                    );
                            }

                        @endphp


                        <img src="{{ $templateImageUrl }}"
                             id="templateImage"
                             alt="{{ $template->name }}">


                        <div id="configuredFields"></div>


                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             RIGHT: FIELD SETTINGS
        ======================================================= --}}
        <div class="col-xl-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-sliders text-primary me-2"></i>

                        Field Settings

                    </h5>

                    <small class="text-muted">

                        Select a field to configure it.

                    </small>

                </div>


                <div class="card-body p-4">

                    <div id="noFieldSelected"
                         class="text-center text-muted py-5">

                        <i class="bi bi-cursor display-5 d-block mb-3"></i>

                        <div class="fw-semibold">
                            No field selected
                        </div>

                        <small>
                            Select a field on the design.
                        </small>

                    </div>


                    <div id="fieldSettings"
                         style="display:none;">


                        {{-- Field Name --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Field
                            </label>

                            <input type="text"
                                   id="settingLabel"
                                   class="form-control"
                                   readonly>

                        </div>


                        {{-- X --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                X Position (%)
                            </label>

                            <input type="number"
                                   id="settingX"
                                   class="form-control"
                                   min="0"
                                   max="100"
                                   step="0.1">

                        </div>


                        {{-- Y --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Y Position (%)
                            </label>

                            <input type="number"
                                   id="settingY"
                                   class="form-control"
                                   min="0"
                                   max="100"
                                   step="0.1">

                        </div>


                        {{-- Width --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Width (%)
                            </label>

                            <input type="number"
                                   id="settingWidth"
                                   class="form-control"
                                   min="0"
                                   max="100"
                                   step="0.1">

                        </div>


                        {{-- Height --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Height (%)
                            </label>

                            <input type="number"
                                   id="settingHeight"
                                   class="form-control"
                                   min="0"
                                   max="100"
                                   step="0.1">

                        </div>


                        {{-- Font --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Font Size
                            </label>

                            <input type="number"
                                   id="settingFontSize"
                                   class="form-control"
                                   min="1"
                                   max="100"
                                   step="1">

                        </div>


                        {{-- Weight --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Font Weight
                            </label>

                            <select id="settingFontWeight"
                                    class="form-select">

                                <option value="400">
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


                        {{-- Align --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Text Align
                            </label>

                            <select id="settingTextAlign"
                                    class="form-select">

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


                        <button type="button"
                                id="removeField"
                                class="btn btn-outline-danger w-100">

                            <i class="bi bi-trash me-1"></i>

                            Remove Field

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Bottom Save --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <h6 class="fw-bold mb-1">

                        <i class="bi bi-check2-circle text-success me-2"></i>

                        Save Template Configuration

                    </h6>

                    <small class="text-muted">

                        The selected fields and their positions belong only
                        to this uploaded design.

                    </small>

                </div>


                <button type="button"
                        id="saveConfiguration"
                        class="btn btn-primary btn-lg px-4">

                    <i class="bi bi-save me-2"></i>

                    Save Template

                </button>

            </div>

        </div>

    </div>

</div>


<style>

.template-icon {

    width: 52px;
    height: 52px;

    border-radius: 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eef4ff;

    color: #0d6efd;

    font-size: 24px;
}


.field-option {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 11px 12px;

    margin-bottom: 7px;

    border: 1px solid #e9ecef;

    border-radius: 10px;

    cursor: pointer;

    transition: .2s;

    background: #fff;
}


.field-option:hover {

    border-color: #86b7fe;

    background: #f8fbff;

}


.field-option input {

    width: 17px;
    height: 17px;

}


.field-option span {

    display: flex;

    align-items: center;

    gap: 8px;

    font-size: 14px;

    font-weight: 500;

}


.card-canvas {

    position: relative;

    width: 100%;

    max-width: 600px;

    margin: auto;

    background: #f8f9fa;

    border-radius: 14px;

    overflow: hidden;

    box-shadow:
        0 10px 30px rgba(0,0,0,.10);

}


.card-canvas > img {

    display: block;

    width: 100%;

    height: auto;

    user-select: none;

}


.configured-field {

    position: absolute;

    border: 2px dashed #0d6efd;

    background: rgba(13,110,253,.10);

    min-width: 50px;

    min-height: 25px;

    padding: 3px 6px;

    cursor: move;

    z-index: 10;

    user-select: none;

    overflow: hidden;

}


.configured-field.selected {

    border-color: #dc3545;

    background: rgba(220,53,69,.12);

}


.configured-field .field-label {

    pointer-events: none;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

}


</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Existing configuration
    |--------------------------------------------------------------------------
    */

    let fieldPositions =
        @json($fieldPositions ?? []);


    /*
    |--------------------------------------------------------------------------
    | Convert old/simple format to array
    |--------------------------------------------------------------------------
    */

    if (
        fieldPositions &&
        !Array.isArray(fieldPositions) &&
        Array.isArray(fieldPositions.fields)
    ) {
        fieldPositions =
            fieldPositions.fields;
    }


    if (!Array.isArray(fieldPositions)) {
        fieldPositions = [];
    }


    /*
    |--------------------------------------------------------------------------
    | DOM
    |--------------------------------------------------------------------------
    */

    const canvas =
        document.getElementById('cardCanvas');

    const configuredFields =
        document.getElementById('configuredFields');

    const noFieldSelected =
        document.getElementById('noFieldSelected');

    const fieldSettings =
        document.getElementById('fieldSettings');


    let selectedIndex = null;


    /*
    |--------------------------------------------------------------------------
    | Default field position
    |--------------------------------------------------------------------------
    */

    function defaultPosition(key, label) {

        return {

            key: key,

            label: label,

            x: 10,

            y: 10,

            width: 30,

            height: 8,

            font_size: 14,

            font_weight: '400',

            text_align: 'left'
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Render fields
    |--------------------------------------------------------------------------
    */

    function renderFields() {

        configuredFields.innerHTML = '';

        document
            .querySelectorAll('.field-check')
            .forEach(function (checkbox) {

                checkbox.checked = false;

            });


        fieldPositions.forEach(function (position, index) {

            const element =
                document.createElement('div');

            element.className =
                'configured-field';

            element.dataset.index =
                index;


            element.innerHTML = `

                <div
                    class="field-label"
                    style="
                        font-size:${position.font_size || 14}px;
                        font-weight:${position.font_weight || 400};
                        text-align:${position.text_align || 'left'};
                    "
                >
                    ${escapeHtml(position.label || position.key)}
                </div>

            `;


            element.style.left =
                `${position.x}%`;

            element.style.top =
                `${position.y}%`;

            element.style.width =
                `${position.width || 30}%`;

            element.style.height =
                `${position.height || 8}%`;


            element.addEventListener(
                'mousedown',
                function (event) {

                    event.stopPropagation();

                    selectField(index);

                    startDrag(
                        event,
                        element,
                        index
                    );
                }
            );


            element.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                    selectField(index);
                }
            );


            configuredFields.appendChild(element);


            const checkbox =
                document.querySelector(
                    `.field-check[value="${position.key}"]`
                );

            if (checkbox) {
                checkbox.checked = true;
            }

        });


        if (selectedIndex !== null) {

            const selectedElement =
                document.querySelector(
                    `.configured-field[data-index="${selectedIndex}"]`
                );

            if (selectedElement) {
                selectedElement.classList.add('selected');
            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | HTML escape
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | Select field
    |--------------------------------------------------------------------------
    */

    function selectField(index) {

        selectedIndex = index;

        document
            .querySelectorAll('.configured-field')
            .forEach(function (element) {

                element.classList.remove(
                    'selected'
                );

            });


        const element =
            document.querySelector(
                `.configured-field[data-index="${index}"]`
            );

        if (element) {

            element.classList.add(
                'selected'
            );

        }


        const field =
            fieldPositions[index];

        if (!field) {
            return;
        }


        noFieldSelected.style.display =
            'none';

        fieldSettings.style.display =
            'block';


        document.getElementById(
            'settingLabel'
        ).value =
            field.label || field.key;


        document.getElementById(
            'settingX'
        ).value =
            field.x ?? 10;


        document.getElementById(
            'settingY'
        ).value =
            field.y ?? 10;


        document.getElementById(
            'settingWidth'
        ).value =
            field.width ?? 30;


        document.getElementById(
            'settingHeight'
        ).value =
            field.height ?? 8;


        document.getElementById(
            'settingFontSize'
        ).value =
            field.font_size ?? 14;


        document.getElementById(
            'settingFontWeight'
        ).value =
            field.font_weight ?? '400';


        document.getElementById(
            'settingTextAlign'
        ).value =
            field.text_align ?? 'left';

    }


    /*
    |--------------------------------------------------------------------------
    | Add field
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.field-check')
        .forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    const key =
                        this.value;

                    const label =
                        this.dataset.label;


                    if (this.checked) {

                        const exists =
                            fieldPositions.some(
                                function (field) {
                                    return field.key === key;
                                }
                            );


                        if (!exists) {

                            fieldPositions.push(
                                defaultPosition(
                                    key,
                                    label
                                )
                            );

                        }

                    } else {

                        fieldPositions =
                            fieldPositions.filter(
                                function (field) {
                                    return field.key !== key;
                                }
                            );

                        selectedIndex = null;

                        noFieldSelected.style.display =
                            'block';

                        fieldSettings.style.display =
                            'none';
                    }


                    renderFields();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Setting change
    |--------------------------------------------------------------------------
    */

    function updateSetting(id, property) {

        document
            .getElementById(id)
            .addEventListener(
                'input',
                function () {

                    if (
                        selectedIndex === null ||
                        !fieldPositions[selectedIndex]
                    ) {
                        return;
                    }


                    fieldPositions[
                        selectedIndex
                    ][property] =
                        this.value;


                    renderFields();

                    selectField(
                        selectedIndex
                    );

                }
            );

    }


    updateSetting(
        'settingX',
        'x'
    );

    updateSetting(
        'settingY',
        'y'
    );

    updateSetting(
        'settingWidth',
        'width'
    );

    updateSetting(
        'settingHeight',
        'height'
    );

    updateSetting(
        'settingFontSize',
        'font_size'
    );

    updateSetting(
        'settingFontWeight',
        'font_weight'
    );

    updateSetting(
        'settingTextAlign',
        'text_align'
    );


    /*
    |--------------------------------------------------------------------------
    | Remove selected field
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('removeField')
        .addEventListener(
            'click',
            function () {

                if (
                    selectedIndex === null
                ) {
                    return;
                }


                fieldPositions.splice(
                    selectedIndex,
                    1
                );


                selectedIndex = null;


                noFieldSelected.style.display =
                    'block';

                fieldSettings.style.display =
                    'none';


                renderFields();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Drag field
    |--------------------------------------------------------------------------
    */

    function startDrag(
        event,
        element,
        index
    ) {

        event.preventDefault();

        const canvasRect =
            canvas.getBoundingClientRect();


        function moveHandler(e) {

            let x =
                ((e.clientX -
                    canvasRect.left)
                    /
                    canvasRect.width)
                    * 100;


            let y =
                ((e.clientY -
                    canvasRect.top)
                    /
                    canvasRect.height)
                    * 100;


            x = Math.max(
                0,
                Math.min(
                    100,
                    x
                )
            );


            y = Math.max(
                0,
                Math.min(
                    100,
                    y
                )
            );


            fieldPositions[index].x =
                Number(x.toFixed(2));


            fieldPositions[index].y =
                Number(y.toFixed(2));


            element.style.left =
                `${fieldPositions[index].x}%`;

            element.style.top =
                `${fieldPositions[index].y}%`;


            if (
                selectedIndex === index
            ) {

                document.getElementById(
                    'settingX'
                ).value =
                    fieldPositions[index].x;


                document.getElementById(
                    'settingY'
                ).value =
                    fieldPositions[index].y;

            }

        }


        function stopDrag() {

            document.removeEventListener(
                'mousemove',
                moveHandler
            );

            document.removeEventListener(
                'mouseup',
                stopDrag
            );

        }


        document.addEventListener(
            'mousemove',
            moveHandler
        );

        document.addEventListener(
            'mouseup',
            stopDrag
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('saveConfiguration')
        .addEventListener(
            'click',
            async function () {

                const button =
                    this;


                if (
                    fieldPositions.length === 0
                ) {

                    alert(
                        'Please select at least one field for this ID card design.'
                    );

                    return;
                }


                button.disabled =
                    true;


                button.innerHTML = `

                    <span
                        class="spinner-border spinner-border-sm me-2">
                    </span>

                    Saving...

                `;


                try {

                    const response =
                        await fetch(
                            "{{ route('admin.id-card.templates.save-positions', $template) }}",
                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        "{{ csrf_token() }}"

                                },

                                body: JSON.stringify({

                                    field_positions:
                                        fieldPositions

                                })

                            }
                        );


                    const data =
                        await response.json();


                    if (
                        response.ok &&
                        data.success
                    ) {

                        alert(
                            data.message
                        );

                        button.innerHTML = `

                            <i class="bi bi-check-circle me-2"></i>

                            Saved

                        `;


                        setTimeout(
                            function () {

                                button.innerHTML = `

                                    <i class="bi bi-save me-2"></i>

                                    Save Template

                                `;

                                button.disabled =
                                    false;

                            },
                            1500
                        );


                    } else {

                        throw new Error(
                            data.message ||
                            'Unable to save configuration.'
                        );

                    }

                } catch (error) {

                    console.error(
                        error
                    );

                    alert(
                        error.message ||
                        'Something went wrong while saving.'
                    );


                    button.innerHTML = `

                        <i class="bi bi-save me-2"></i>

                        Save Template

                    `;

                    button.disabled =
                        false;

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Initial render
    |--------------------------------------------------------------------------
    */

    renderFields();

});

</script>

@endsection