<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeeklyTimetableImport implements ToCollection, WithHeadingRow
{
    public Collection $rows;

    public function __construct()
    {
        $this->rows = collect();
    }

    /**
     * Read the Excel rows using the first row as column headings.
     */
    public function collection(Collection $rows): void
    {
        $this->rows = $rows;
    }
}
