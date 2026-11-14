<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year'    => ['required', 'string', 'max:20'],
            'class'            => ['required', 'string', 'max:50'],
            'section'          => ['required', 'string', 'max:20'],
            'month'            => ['nullable', 'date_format:Y-m'],
            'attendance_month' => ['nullable', 'date_format:Y-m'],
            'attendance'       => ['required', 'array'],
            'attendance.*'     => ['array'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('attendance', []) as $studentId => $dates) {

                if (!ctype_digit((string) $studentId)) {
                    $validator->errors()->add('attendance', 'Invalid student ID.');
                    return;
                }

                foreach ((array) $dates as $date => $status) {

                    if (!Carbon::hasFormat((string) $date, 'Y-m-d')) {
                        $validator->errors()->add('attendance', 'Invalid date.');
                        return;
                    }

                    $status = strtolower(trim((string) $status));

                    if (!in_array($status, ['', 'present', 'absent', 'holiday'], true)) {
                        $validator->errors()->add('attendance', 'Invalid status.');
                        return;
                    }
                }
            }
        });
    }
}