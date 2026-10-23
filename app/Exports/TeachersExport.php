<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Teacher::query()
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