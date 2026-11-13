<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $schoolId = auth()->user()?->school_id;

        // Never export all schools' teachers if no school is assigned.
        if (!$schoolId) {
            return collect();
        }

        return Teacher::query()
            ->where('school_id', $schoolId)
            ->select([
                'teacher_id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'qualification',
                'joining_date',
                'address',
                'subject',
                'status',
            ])
            ->orderBy('first_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'teacher_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'qualification',
            'joining_date',
            'address',
            'subject',
            'status',
        ];
    }
}
