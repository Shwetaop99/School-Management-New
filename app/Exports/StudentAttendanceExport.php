<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAttendanceExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected string $month;

    protected ?string $academicYear;

    protected ?string $className;

    protected ?string $section;

    protected Carbon $monthDate;

    protected Collection $students;

    protected array $attendanceMap = [];

    public function __construct(
        string $month,
        ?string $academicYear = null,
        ?string $className = null,
        ?string $section = null
    ) {
        $this->month = $month;
        $this->academicYear = $academicYear;
        $this->className = $className;
        $this->section = $section;

        try {
            $this->monthDate = Carbon::createFromFormat(
                'Y-m',
                $month
            )->startOfMonth();
        } catch (\Exception $e) {
            $this->monthDate = now()->startOfMonth();
            $this->month = $this->monthDate->format('Y-m');
        }

        $this->loadData();
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS + ATTENDANCE
    |--------------------------------------------------------------------------
    */

    protected function loadData(): void
    {
        $query = Student::query()
            ->where('status', 'Active')
            ->select([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'academic_year',
                'class',
                'section',
            ])
            ->orderBy('roll_number')
            ->orderBy('first_name');

        if (!empty($this->academicYear)) {
            $query->where(
                'academic_year',
                $this->academicYear
            );
        }

        if (!empty($this->className)) {
            $query->where(
                'class',
                $this->className
            );
        }

        if (!empty($this->section)) {
            $query->where(
                'section',
                $this->section
            );
        }

        $this->students = $query->get();

        if ($this->students->isEmpty()) {
            return;
        }

        $studentIds = $this->students->pluck('id');

        $records = Attendance::query()
            ->whereIn('student_id', $studentIds)
            ->whereBetween(
                'attendance_date',
                [
                    $this->monthDate
                        ->copy()
                        ->startOfMonth()
                        ->toDateString(),

                    $this->monthDate
                        ->copy()
                        ->endOfMonth()
                        ->toDateString(),
                ]
            )
            ->get([
                'student_id',
                'attendance_date',
                'status',
            ]);

        foreach ($records as $record) {

            $dateKey = Carbon::parse(
                $record->attendance_date
            )->format('Y-m-d');

            $this->attendanceMap[
                $record->student_id
            ][$dateKey] = strtolower(
                (string) $record->status
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EXCEL DATA
    |--------------------------------------------------------------------------
    */

    public function collection()
    {
        $rows = collect();

        $dates = [];

        $current = $this->monthDate
            ->copy()
            ->startOfMonth();

        $end = $this->monthDate
            ->copy()
            ->endOfMonth();

        while ($current->lte($end)) {

            $dates[] = $current->copy();

            $current->addDay();
        }

        foreach ($this->students as $student) {

            $present = 0;
            $absent = 0;

            $studentName = trim(
                ($student->first_name ?? '') . ' ' .
                ($student->middle_name ?? '') . ' ' .
                ($student->last_name ?? '')
            );

            $row = [
                $student->student_id ?? '',
                $student->roll_number ?? '',
                $studentName,
            ];

            foreach ($dates as $date) {

                $dateKey = $date->format('Y-m-d');

                $status =
                    $this->attendanceMap[
                        $student->id
                    ][$dateKey] ?? '';

                /*
                |--------------------------------------------------------------------------
                | SUNDAY
                |--------------------------------------------------------------------------
                */

                if ($date->isSunday()) {

                    $value = 'SUN';

                /*
                |--------------------------------------------------------------------------
                | HOLIDAY
                |--------------------------------------------------------------------------
                */

                } elseif ($status === 'holiday') {

                    $value = 'HOL';

                /*
                |--------------------------------------------------------------------------
                | PRESENT
                |--------------------------------------------------------------------------
                */

                } elseif ($status === 'present') {

                    $value = 'P';

                    $present++;

                /*
                |--------------------------------------------------------------------------
                | ABSENT
                |--------------------------------------------------------------------------
                */

                } elseif ($status === 'absent') {

                    $value = 'A';

                    $absent++;

                /*
                |--------------------------------------------------------------------------
                | NOT MARKED
                |--------------------------------------------------------------------------
                */

                } else {

                    $value = '-';
                }

                $row[] = $value;
            }

            $totalMarked = $present + $absent;

            $percentage = $totalMarked > 0
                ? round(
                    ($present / $totalMarked) * 100,
                    2
                )
                : 0;

            $row[] = $present;
            $row[] = $absent;
            $row[] = $percentage . '%';

            $rows->push($row);
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE HEADINGS
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        $headings = [
            'Student ID',
            'Roll No.',
            'Student Name',
        ];

        $current = $this->monthDate
            ->copy()
            ->startOfMonth();

        $end = $this->monthDate
            ->copy()
            ->endOfMonth();

        while ($current->lte($end)) {

            $headings[] =
                $current->format('d') .
                "\n" .
                $current->format('D');

            $current->addDay();
        }

        $headings[] = 'Present';
        $headings[] = 'Absent';
        $headings[] = 'Attendance %';

        return $headings;
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER STYLE
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                ],

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' => true,
                ],

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' => 'DCE6F1',
                    ],
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' => '8C9AA6',
                        ],
                    ],
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | COLUMN WIDTHS
    |--------------------------------------------------------------------------
    */

    public function columnWidths(): array
    {
        $widths = [
            'A' => 16,
            'B' => 10,
            'C' => 28,
        ];

        $daysInMonth =
            $this->monthDate->daysInMonth;

        for (
            $i = 0;
            $i < $daysInMonth;
            $i++
        ) {

            $widths[
                $this->getColumnLetter(4 + $i)
            ] = 11;
        }

        $summaryStart =
            4 + $daysInMonth;

        $widths[
            $this->getColumnLetter(
                $summaryStart
            )
        ] = 12;

        $widths[
            $this->getColumnLetter(
                $summaryStart + 1
            )
        ] = 12;

        $widths[
            $this->getColumnLetter(
                $summaryStart + 2
            )
        ] = 16;

        return $widths;
    }

    /*
    |--------------------------------------------------------------------------
    | AFTER SHEET
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event->sheet->getDelegate();

                $daysInMonth =
                    $this->monthDate->daysInMonth;

                $lastColumnNumber =
                    3 + $daysInMonth + 3;

                $lastColumn =
                    $this->getColumnLetter(
                        $lastColumnNumber
                    );

                $lastRow =
                    $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | TABLE BORDERS
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A1:{$lastColumn}{$lastRow}"
                )->applyFromArray([

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                                Border::BORDER_THIN,

                            'color' => [

                                'rgb' =>
                                    'B8C2CC',
                            ],
                        ],
                    ],

                    'alignment' => [

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A1:{$lastColumn}1"
                )->applyFromArray([

                    'font' => [

                        'bold' => true,

                        'size' => 10,
                    ],

                    'alignment' => [

                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],

                    'fill' => [

                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [

                            'rgb' =>
                                'DCE6F1',
                        ],
                    ],

                    'borders' => [

                        'allBorders' => [

                            'borderStyle' =>
                                Border::BORDER_THIN,

                            'color' => [

                                'rgb' =>
                                    '8C9AA6',
                            ],
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | STUDENT NAME LEFT ALIGN
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= 2) {

                    $sheet->getStyle(
                        "C2:C{$lastRow}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_LEFT
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | WEEKEND / SUNDAY COLUMNS
                |--------------------------------------------------------------------------
                */

                for (
                    $i = 0;
                    $i < $daysInMonth;
                    $i++
                ) {

                    $date =
                        $this->monthDate
                            ->copy()
                            ->startOfMonth()
                            ->addDays($i);

                    $column =
                        $this->getColumnLetter(
                            4 + $i
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | SUNDAY
                    |--------------------------------------------------------------------------
                    */

                    if ($date->isSunday()) {

                        $sheet->getStyle(
                            "{$column}1:{$column}{$lastRow}"
                        )->applyFromArray([

                            'fill' => [

                                'fillType' =>
                                    Fill::FILL_SOLID,

                                'startColor' => [

                                    'rgb' =>
                                        'FCE4E4',
                                ],
                            ],
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SATURDAY
                    |--------------------------------------------------------------------------
                    */

                    elseif ($date->isSaturday()) {

                        $sheet->getStyle(
                            "{$column}1:{$column}{$lastRow}"
                        )->applyFromArray([

                            'fill' => [

                                'fillType' =>
                                    Fill::FILL_SOLID,

                                'startColor' => [

                                    'rgb' =>
                                        'F2F2F2',
                                ],
                            ],
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | FREEZE STUDENT INFORMATION
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('D2');

                /*
                |--------------------------------------------------------------------------
                | HEADER ROW HEIGHT
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(1)
                    ->setRowHeight(35);

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                $sheet->setAutoFilter(
                    "A1:{$lastColumn}{$lastRow}"
                );

                /*
                |--------------------------------------------------------------------------
                | LANDSCAPE A4
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::
                        ORIENTATION_LANDSCAPE
                    );

                $sheet->getPageSetup()
                    ->setPaperSize(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::
                        PAPERSIZE_A4
                    );

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageMargins()
                    ->setTop(0.4)
                    ->setBottom(0.4)
                    ->setLeft(0.25)
                    ->setRight(0.25);

                /*
                |--------------------------------------------------------------------------
                | FOOTER ONLY
                |--------------------------------------------------------------------------
                */

                $sheet->getHeaderFooter()
                    ->setOddFooter(
                        '&LStudent Attendance' .
                        '&CPage &P of &N' .
                        '&RGenerated: ' .
                        now()->format('d-m-Y H:i')
                    );
            },
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | COLUMN LETTER
    |--------------------------------------------------------------------------
    */

    protected function getColumnLetter(
        int $columnNumber
    ): string {

        $letter = '';

        while ($columnNumber > 0) {

            $remainder =
                ($columnNumber - 1) % 26;

            $letter =
                chr(65 + $remainder) .
                $letter;

            $columnNumber =
                intdiv(
                    $columnNumber - 1,
                    26
                );
        }

        return $letter;
    }
}