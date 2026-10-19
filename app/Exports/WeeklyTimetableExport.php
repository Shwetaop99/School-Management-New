<?php

namespace App\Exports;

use App\Models\TeacherTimetable;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WeeklyTimetableExport implements FromCollection, WithHeadings
{
    protected string $class;
    protected string $section;
    protected string $academicYear;
    protected string $weekStartDate;

    public function __construct(
        $class,
        $section,
        $academicYear,
        $weekStartDate
    ) {
        $this->class = $class;
        $this->section = $section;
        $this->academicYear = $academicYear;
        $this->weekStartDate = Carbon::parse($weekStartDate)
            ->startOfWeek(Carbon::MONDAY)
            ->toDateString();
    }

    public function headings(): array
    {
        return [
            'Academic Year',
            'Date',
            'Day',
            'Period',
            'Period Type',
            'Class',
            'Section',
            'Teacher ID',
            'Subject',
            'Start Time',
            'End Time',
            'Room',
        ];
    }

    public function collection()
    {
        $startDate = Carbon::parse($this->weekStartDate)->startOfDay();
        $endDate = $startDate->copy()->addDays(5)->endOfDay();

        return TeacherTimetable::query()
            ->where('academic_year', $this->academicYear)
            ->whereBetween('timetable_date', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('class', $this->class)
                      ->where('section', $this->section);
                })->orWhereIn('period_type', ['Break', 'Lunch']);
            })
            ->orderBy('timetable_date')
            ->orderBy('period_number')
            ->get()
            ->map(function ($record) {
                return [
                    $record->academic_year,
                    $record->timetable_date
                        ? Carbon::parse($record->timetable_date)->format('d-m-Y')
                        : '',
                    $record->timetable_date
                        ? Carbon::parse($record->timetable_date)->format('l')
                        : '',
                    $record->period_number,
                    $record->period_type,
                    $record->class,
                    $record->section,
                    $record->teacher_id,
                    $record->subject,
                    $record->start_time
                        ? Carbon::parse($record->start_time)->format('H:i')
                        : '',
                    $record->end_time
                        ? Carbon::parse($record->end_time)->format('H:i')
                        : '',
                    $record->room,
                ];
            });
    }
}