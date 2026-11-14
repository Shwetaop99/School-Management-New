<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentMarksRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow teachers/admins who have permission
        return $this->user()->can('update-marks');
    }

    public function rules(): array
    {
        return [
            'student_id'   => ['required', 'integer', 'exists:students,id'],
            'subject_id'   => ['required', 'integer', 'exists:subjects,id'],
            'marks'        => ['required', 'numeric', 'min:0', 'max:100'],
            'exam_type'    => ['required', 'string', 'in:midterm,final,quiz'],
            'remarks'      => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'marks.max' => 'Marks cannot be more than 100',
            'marks.min' => 'Marks cannot be less than 0',
        ];
    }
}