<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TeacherReportExport implements FromView
{
    public function __construct(public Teacher $teacher)
    {
    }

    public function view(): View
    {
        return view('admin.reports.excel', [
            'teacher' => $this->teacher,
        ]);
    }
}