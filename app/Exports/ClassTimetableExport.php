<?php

namespace App\Exports;

use App\Models\TeacherTimetable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassTimetableExport implements FromCollection, WithHeadings
{
    protected $class;
    protected $section;

    public function __construct($class, $section = null)
    {
        $this->class = $class;
        $this->section = $section;
    }

    public function collection()
    {
        return TeacherTimetable::with('teacher')
            ->where(function ($query) {

                $query->where(function ($q) {

                    $q->where('class', $this->class);

                    if ($this->section) {
                        $q->where('section', $this->section);
                    }

                });

                // Include common Break and Lunch periods
                $query->orWhereIn('period_type', ['Break', 'Lunch']);
            })
            ->orderByRaw("
                CASE day
                    WHEN 'Monday' THEN 1
                    WHEN 'Tuesday' THEN 2
                    WHEN 'Wednesday' THEN 3
                    WHEN 'Thursday' THEN 4
                    WHEN 'Friday' THEN 5
                    WHEN 'Saturday' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('period_number')
            ->orderBy('start_time')
            ->get()
            ->map(function ($timetable) {

                return [
                    'Day' => $timetable->day,

                    'Period' => $timetable->period_number,

                    'Period Type' => $timetable->period_type,

                    'Start Time' => $timetable->start_time,

                    'End Time' => $timetable->end_time,

                    'Class' => $timetable->class ?? '-',

                    'Section' => $timetable->section ?? '-',

                    'Subject' => $timetable->subject,

                    'Subject Type' => $timetable->subject_type ?? '-',

                    'Teacher' => $timetable->teacher
                        ? $timetable->teacher->first_name . ' ' .
                          $timetable->teacher->last_name
                        : '-',

                    'Room' => $timetable->room ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Day',
            'Period',
            'Period Type',
            'Start Time',
            'End Time',
            'Class',
            'Section',
            'Subject',
            'Subject Type',
            'Teacher',
            'Room',
        ];
    }
}
