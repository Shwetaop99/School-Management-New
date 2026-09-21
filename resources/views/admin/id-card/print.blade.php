@extends('layouts.app')

@section('content')

@php

    $templateImage =
        $template->template_image ?? '';


    if (
        $templateImage &&
        !str_starts_with($templateImage, 'http://') &&
        !str_starts_with($templateImage, 'https://')
    ) {

        $templateImage =
            asset(
                'storage/' .
                ltrim(
                    $templateImage,
                    '/'
                )
            );
    }


    $student =
        $idCard->student;


    $studentPhoto =
        $student->profile_image ?? '';


    if (
        $studentPhoto &&
        !str_starts_with($studentPhoto, 'http://') &&
        !str_starts_with($studentPhoto, 'https://')
    ) {

        $studentPhoto =
            asset(
                'storage/' .
                ltrim(
                    $studentPhoto,
                    '/'
                )
            );
    }


    $fields =
        $fieldPositions ?? [];


    if (
        is_array($fields) &&
        isset($fields['fields']) &&
        is_array($fields['fields'])
    ) {

        $fields =
            $fields['fields'];

    }

@endphp


<div class="print-toolbar">

    <button type="button"
            onclick="window.print()"
            class="btn btn-primary">

        <i class="bi bi-printer me-1"></i>

        Print ID Card

    </button>


    <a href="{{ route('admin.id-card.show', $idCard->id) }}"
       class="btn btn-outline-secondary">

        Back

    </a>

</div>


<div class="print-page">

    <div class="print-card">

        {{-- Exact uploaded design --}}

        <img src="{{ $templateImage }}"
             class="print-background"
             alt="{{ $template->name }}">


        {{-- Template-specific fields --}}

        <div class="print-fields">

            @foreach($fields as $field)

                @php

                    $key =
                        $field['key'] ?? null;

                    $x =
                        $field['x'] ?? 0;

                    $y =
                        $field['y'] ?? 0;

                    $width =
                        $field['width'] ?? 30;

                    $height =
                        $field['height'] ?? 8;

                    $fontSize =
                        $field['font_size'] ?? 14;

                    $fontWeight =
                        $field['font_weight'] ?? '400';

                    $textAlign =
                        $field['text_align'] ?? 'left';


                    $value = '-';


                    if ($key === 'profile_image') {

                        $value =
                            $studentPhoto;

                    } elseif ($key === 'full_name') {

                        $value =
                            $student->full_name ?? '-';

                    } elseif ($key === 'student_id') {

                        $value =
                            $student->student_id ?? '-';

                    } elseif ($key === 'register_no') {

                        $value =
                            $student->register_no ?? '-';

                    } elseif ($key === 'pen_no') {

                        $value =
                            $student->pen_no ?? '-';

                    } elseif ($key === 'appar_id') {

                        $value =
                            $student->appar_id ?? '-';

                    } elseif ($key === 'class') {

                        $value =
                            $student->class ?? '-';

                    } elseif ($key === 'section') {

                        $value =
                            $student->section ?? '-';

                    } elseif ($key === 'roll_number') {

                        $value =
                            $student->roll_number ?? '-';

                    } elseif ($key === 'date_of_birth') {

                        $value =
                            $student->date_of_birth
                            ? \Carbon\Carbon::parse(
                                $student->date_of_birth
                            )->format('d-m-Y')
                            : '-';

                    } elseif ($key === 'blood_group') {

                        $value =
                            $student->blood_group ?? '-';

                    } elseif ($key === 'phone') {

                        $value =
                            $student->phone ?? '-';

                    } elseif ($key === 'father_name') {

                        $value =
                            $student->father_name ?? '-';

                    } elseif ($key === 'mother_name') {

                        $value =
                            $student->mother_name ?? '-';

                    } elseif ($key === 'address') {

                        $value =
                            $student->address ?? '-';

                    }

                @endphp


                @if($key === 'profile_image')

                    @if($value)

                        <img src="{{ $value }}"
                             class="print-field print-photo"
                             style="
                                left:{{ $x }}%;
                                top:{{ $y }}%;
                                width:{{ $width }}%;
                                height:{{ $height }}%;
                             "
                             alt="Student Photo">

                    @endif

                @else

                    <div class="print-field print-text"
                         style="
                            left:{{ $x }}%;
                            top:{{ $y }}%;
                            width:{{ $width }}%;
                            height:{{ $height }}%;
                            font-size:{{ $fontSize }}px;
                            font-weight:{{ $fontWeight }};
                            text-align:{{ $textAlign }};
                         ">

                        {{ $value }}

                    </div>

                @endif

            @endforeach

        </div>

    </div>

</div>


<style>

body {

    background: #f1f3f5;

}


.print-toolbar {

    display: flex;

    justify-content: center;

    gap: 10px;

    padding: 20px;

}


.print-page {

    display: flex;

    justify-content: center;

    padding: 20px;

}


.print-card {

    position: relative;

    width: 500px;

    max-width: 90vw;

    overflow: hidden;

}


.print-background {

    display: block;

    width: 100%;

    height: auto;

}


.print-fields {

    position: absolute;

    inset: 0;

}


.print-field {

    position: absolute;

    box-sizing: border-box;

    overflow: hidden;

}


.print-text {

    line-height: 1.2;

    word-break: break-word;

}


.print-photo {

    object-fit: cover;

}


@media print {

    @page {

        size: A4;

        margin: 0;

    }


    body {

        background: white !important;

    }


    .print-toolbar {

        display: none !important;

    }


    .print-page {

        padding: 0;

        width: 100%;

        min-height: 100vh;

        display: flex;

        justify-content: center;

        align-items: flex-start;

    }


    .print-card {

        width: 85mm;

        max-width: none;

        margin-top: 15mm;

    }

}

</style>

@endsection