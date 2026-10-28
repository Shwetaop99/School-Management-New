<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClassTimetableExport;
use App\Exports\WeeklyTimetableExport;
use App\Http\Controllers\Controller;
use App\Imports\WeeklyTimetableImport;
use App\Models\Teacher;
use App\Models\TeacherTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TimetableController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHARED QUERIES
    |--------------------------------------------------------------------------
    */

    private function activeClasses()
    {
        return DB::table('school_classes')
            ->where('status', 1)
            ->selectRaw('MIN(id) as id, class_name')
            ->groupBy('class_name')
            ->orderBy('class_name')
            ->get();
    }

    private function classSections()
    {
        return DB::table('school_classes')
            ->where('status', 1)
            ->select('id', 'class_name', 'section as section_name')
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();
    }

    private function activeTeachers()
    {
        return Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $timetables = TeacherTimetable::with('teacher')->latest()->get();
        $classes    = $this->activeClasses();
        $sections   = DB::table('sections')->orderBy('id')->get();

        return view('admin.timetable.index', compact('timetables', 'classes', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $teachers = $this->activeTeachers();
        $classes  = $this->activeClasses();
        $sections = $this->classSections();

        $subjects = DB::table('subjects')
            ->where('status', 1)
            ->orderBy('subject_name')
            ->get();

        return view('admin.timetable.create', compact('teachers', 'classes', 'sections', 'subjects'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (weekly timetable, Monday to Saturday)
    |--------------------------------------------------------------------------
    | Break / Lunch rows are saved WITH class + section, so every class keeps
    | its own breaks.
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year'   => ['required', 'string', 'max:20'],
            'class'           => ['required', 'string', 'max:100'],
            'section'         => ['required', 'string', 'max:50'],
            'week_start_date' => ['required', 'date'],

            'timetable'           => ['required', 'array'],
            'timetable.Monday'    => ['required', 'array'],
            'timetable.Tuesday'   => ['required', 'array'],
            'timetable.Wednesday' => ['required', 'array'],
            'timetable.Thursday'  => ['required', 'array'],
            'timetable.Friday'    => ['required', 'array'],
            'timetable.Saturday'  => ['required', 'array'],

            'timetable.*.*.period_type'  => ['required', Rule::in(['Regular', 'Break', 'Lunch', 'Activity'])],
            'timetable.*.*.teacher_id'   => ['nullable', 'integer', 'exists:teachers,id'],
            'timetable.*.*.subject'      => ['nullable', 'string', 'max:100'],
            'timetable.*.*.subject_type' => ['nullable', Rule::in(['Theory', 'Practical', 'Activity'])],
            'timetable.*.*.lecture_type' => ['nullable', 'string', Rule::in(['regular', 'extra', 'practical', 'activity'])],
            'timetable.*.*.start_time'   => ['required', 'date_format:H:i'],
            'timetable.*.*.end_time'     => ['nullable', 'date_format:H:i'],
            'timetable.*.*.room'         => ['nullable', 'string', 'max:100'],
        ]);

        $weekStart = Carbon::parse($validated['week_start_date'])->startOfDay();

        if ($weekStart->dayOfWeekIso !== 1) {
            return back()->withInput()->withErrors([
                'week_start_date' => 'Please select a Monday as the week starting date.',
            ]);
        }

        $days     = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $schedule = $validated['timetable'];

        $rows      = [];
        $labels    = [];   // human label for each row, e.g. "Monday P3"
        $weekDates = [];

        foreach ($days as $dayIndex => $day) {
            if (empty($schedule[$day])) {
                return back()->withInput()->withErrors([
                    'timetable' => "$day must contain at least one timetable row.",
                ]);
            }

            $date        = $weekStart->copy()->addDays($dayIndex)->toDateString();
            $weekDates[] = $date;

            ksort($schedule[$day], SORT_NUMERIC);

            $lectureNo = 0;

            foreach ($schedule[$day] as $position => $slot) {
                $type           = $slot['period_type'] ?? '';
                $isBreakOrLunch = in_array($type, ['Break', 'Lunch'], true);

                if (!$isBreakOrLunch) {
                    $lectureNo++;
                }

                // What the user sees on screen: "P3", "Break", "Lunch"
                $label = $isBreakOrLunch ? $type : "P{$lectureNo}";

                if (empty($slot['start_time']) || empty($slot['end_time'])) {
                    return back()->withInput()->withErrors([
                        'timetable' => "$day $label requires a start and end time.",
                    ]);
                }

                $start = Carbon::createFromFormat('H:i', $slot['start_time']);
                $end   = Carbon::createFromFormat('H:i', $slot['end_time']);

                if ($end->lessThanOrEqualTo($start)) {
                    return back()->withInput()->withErrors([
                        'timetable' => "$day $label must end after it starts.",
                    ]);
                }

                if (!$isBreakOrLunch) {
                    if (empty($slot['teacher_id'])) {
                        return back()->withInput()->withErrors([
                            'timetable' => "$day $label requires a teacher.",
                        ]);
                    }

                    if (empty($slot['subject'])) {
                        return back()->withInput()->withErrors([
                            'timetable' => "$day $label requires a subject.",
                        ]);
                    }
                }

                $rows[] = [
                    'academic_year'    => $validated['academic_year'],
                    'timetable_date'   => $date,
                    'day'              => $day,
                    'period_number'    => (int) $position,   // position in the day (breaks included)
                    'period_type'      => $type,
                    'teacher_id'       => $isBreakOrLunch ? null : (int) $slot['teacher_id'],
                    'class'            => $validated['class'],
                    'section'          => $validated['section'],
                    'subject'          => $isBreakOrLunch ? $type : $slot['subject'],
                    'subject_type'     => $isBreakOrLunch
                        ? 'Activity'
                        : ($type === 'Activity' ? 'Activity' : ($slot['subject_type'] ?? 'Theory')),
                    'lecture_type'     => $isBreakOrLunch
                        ? 'activity'
                        : ($type === 'Activity' ? 'activity' : ($slot['lecture_type'] ?? 'regular')),
                    'start_time'       => $start->format('H:i:s'),
                    'end_time'         => $end->format('H:i:s'),
                    'duration_minutes' => (int) abs($start->diffInMinutes($end)),
                    'room'             => $isBreakOrLunch ? null : (($slot['room'] ?? '') ?: null),
                ];

                $labels[] = "$day $label";
            }
        }

        if ($message = $this->findOverlapInRows($rows, $labels)) {
            return back()->withInput()->withErrors(['timetable' => $message]);
        }

        if ($message = $this->findExternalConflict($rows, $labels)) {
            return back()->withInput()->withErrors(['timetable' => $message]);
        }

        DB::transaction(function () use ($rows, $validated, $weekDates) {
            TeacherTimetable::where('academic_year', $validated['academic_year'])
                ->where('class', $validated['class'])
                ->where('section', $validated['section'])
                ->whereIn('timetable_date', $weekDates)
                ->delete();

            foreach ($rows as $row) {
                TeacherTimetable::create($row);
            }
        });

        return redirect()
            ->route('admin.timetable.index')
            ->with('success', 'Weekly timetable saved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(TeacherTimetable $timetable)
    {
        $teachers = $this->activeTeachers();
        $classes  = $this->activeClasses();
        $sections = $this->classSections();

        return view('admin.timetable.edit', compact('timetable', 'teachers', 'classes', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE (single row)
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, TeacherTimetable $timetable)
    {
        $validated = $request->validate([
            'teacher_id'     => ['nullable', 'exists:teachers,id', 'required_unless:period_type,Break,Lunch'],
            'timetable_date' => ['required', 'date'],
            'academic_year'  => ['required', 'string', 'max:20'],
            'day'            => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])],
            'period_number'  => ['required', 'integer', 'min:1', 'max:30'],
            'period_type'    => ['required', Rule::in(['Regular', 'Break', 'Lunch', 'Activity'])],
            'lecture_type'   => ['nullable', Rule::in(['regular', 'extra', 'practical', 'activity'])],
            'class'          => ['nullable', 'string', 'max:100', 'required_unless:period_type,Break,Lunch'],
            'section'        => ['nullable', 'string', 'max:50', 'required_unless:period_type,Break,Lunch'],
            'subject'        => ['nullable', 'string', 'max:100', 'required_unless:period_type,Break,Lunch'],
            'subject_type'   => ['nullable', Rule::in(['Theory', 'Practical', 'Activity']), 'required_unless:period_type,Break,Lunch'],
            'start_time'     => ['required', 'date_format:H:i'],
            'end_time'       => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:300'],
            'room'           => ['nullable', 'string', 'max:100'],
        ]);

        // Day is always calculated from the date.
        $calculatedDay = Carbon::parse($validated['timetable_date'])->format('l');

        if ($calculatedDay === 'Sunday') {
            return back()->withInput()->withErrors([
                'timetable_date' => 'Sunday timetable is not allowed.',
            ]);
        }

        $validated['day'] = $calculatedDay;

        $this->calculateLectureTime($validated);

        $isBreakOrLunch = in_array($validated['period_type'], ['Break', 'Lunch'], true);

        if ($isBreakOrLunch) {
            // Break / Lunch keep their class + section (each class has its own breaks).
            $validated['teacher_id']   = null;
            $validated['class']        = $validated['class'] ?: $timetable->class;
            $validated['section']      = $validated['section'] ?: $timetable->section;
            $validated['subject']      = $validated['period_type'];
            $validated['subject_type'] = 'Activity';
            $validated['lecture_type'] = 'activity';
            $validated['room']         = null;
        }

        if ($validated['period_type'] === 'Activity') {
            $validated['subject_type'] = 'Activity';
            $validated['lecture_type'] = 'activity';
        }

        if (!$isBreakOrLunch) {
            $overlap = function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
            };

            // Teacher conflict
            $teacherConflict = TeacherTimetable::where('teacher_id', $validated['teacher_id'])
                ->where('timetable_date', $validated['timetable_date'])
                ->where('academic_year', $validated['academic_year'])
                ->where('id', '!=', $timetable->id)
                ->where($overlap)
                ->exists();

            if ($teacherConflict) {
                return back()->withInput()->withErrors([
                    'teacher_id' => 'This teacher already has a timetable entry on this date during this time.',
                ]);
            }

            // Class / section conflict
            $classConflict = TeacherTimetable::where('academic_year', $validated['academic_year'])
                ->where('timetable_date', $validated['timetable_date'])
                ->where('class', $validated['class'])
                ->where('id', '!=', $timetable->id)
                ->where(function ($query) use ($validated) {
                    if (!empty($validated['section'])) {
                        $query->where('section', $validated['section']);
                    } else {
                        $query->whereNull('section');
                    }
                })
                ->where($overlap)
                ->exists();

            if ($classConflict) {
                return back()->withInput()->withErrors([
                    'class' => 'This class and section already have a timetable entry on this date during this time.',
                ]);
            }

            // Room conflict
            if (!empty($validated['room'])) {
                $roomConflict = TeacherTimetable::where('academic_year', $validated['academic_year'])
                    ->where('timetable_date', $validated['timetable_date'])
                    ->where('room', $validated['room'])
                    ->where('id', '!=', $timetable->id)
                    ->where($overlap)
                    ->exists();

                if ($roomConflict) {
                    return back()->withInput()->withErrors([
                        'room' => 'This room is already occupied on this date during this time.',
                    ]);
                }
            }
        }

        $timetable->update($validated);

        return redirect()
            ->route('admin.timetable.index')
            ->with('success', 'Timetable updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | TIME CALCULATION (used by update)
    |--------------------------------------------------------------------------
    */

    private function calculateLectureTime(array &$validated)
    {
        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);

        if ($validated['period_type'] === 'Break') {
            $validated['end_time']         = $startTime->copy()->addMinutes(15)->format('H:i');
            $validated['duration_minutes'] = 15;
            return;
        }

        if ($validated['period_type'] === 'Lunch') {
            $validated['end_time']         = $startTime->copy()->addMinutes(60)->format('H:i');
            $validated['duration_minutes'] = 60;
            return;
        }

        if (($validated['lecture_type'] ?? 'regular') === 'regular' && empty($validated['end_time'])) {
            $validated['end_time']         = $startTime->copy()->addMinutes(45)->format('H:i');
            $validated['duration_minutes'] = 45;
            return;
        }

        if (empty($validated['end_time'])) {
            abort(redirect()->back()->withInput()->withErrors([
                'end_time' => 'Please enter an end time for this lecture.',
            ]));
        }

        $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            abort(redirect()->back()->withInput()->withErrors([
                'end_time' => 'End time must be after start time.',
            ]));
        }

        $validated['duration_minutes'] = (int) abs($startTime->diffInMinutes($endTime));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(TeacherTimetable $timetable)
    {
        $timetable->delete();

        return redirect()
            ->route('admin.timetable.index')
            ->with('success', 'Timetable deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function classTimetable(Request $request)
    {
        $classes = DB::table('school_classes')
            ->where('status', 1)
            ->select('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        $sections   = collect();
        $timetables = collect();

        if ($request->filled('class')) {
            $classExists = DB::table('school_classes')
                ->where('status', 1)
                ->where('class_name', $request->class)
                ->exists();

            if ($classExists) {
                $sections = DB::table('school_classes')
                    ->where('status', 1)
                    ->where('class_name', $request->class)
                    ->orderBy('section')
                    ->pluck('section')
                    ->unique()
                    ->values();

                $query = TeacherTimetable::with('teacher')->where('class', $request->class);

                if ($request->filled('section')) {
                    $query->where('section', $request->section);
                }

                $timetables = $query
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
                    ->get();
            }
        }

        return view('admin.timetable.class', compact('classes', 'sections', 'timetables'));
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS PDF (breaks belong to the class now, so no extra Break/Lunch query)
    |--------------------------------------------------------------------------
    */

    public function classPdf(Request $request)
    {
        $request->validate([
            'class'   => 'required|string',
            'section' => 'nullable|string',
        ]);

        $query = TeacherTimetable::with('teacher')->where('class', $request->class);

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $timetables = $query
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
            ->get();

        $days    = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $periods = $timetables->groupBy('period_number')->sortKeys();

        $pdf = Pdf::loadView('admin.timetable.pdf', compact('timetables', 'days', 'periods'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'class-timetable-' . $request->class . '-' . ($request->section ?? 'all') . '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS EXCEL
    |--------------------------------------------------------------------------
    */

    public function classExcel(Request $request)
    {
        $request->validate([
            'class'   => 'required|string',
            'section' => 'nullable|string',
        ]);

        return Excel::download(
            new ClassTimetableExport($request->class, $request->section),
            'class-timetable-' . $request->class . '-' . ($request->section ?? 'all') . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NEXT TIME
    |--------------------------------------------------------------------------
    */

    public function nextTime(Request $request)
    {
        $request->validate([
            'academic_year'  => 'required|string',
            'day'            => 'required|string',
            'timetable_date' => 'nullable|date',
            'teacher_id'     => 'nullable|integer',
            'class'          => 'nullable|string',
            'section'        => 'nullable|string',
        ]);

        $query = TeacherTimetable::where('academic_year', $request->academic_year)
            ->where('day', $request->day)
            ->whereNotIn('period_type', ['Break', 'Lunch', 'Activity']);

        if ($request->filled('timetable_date')) {
            $query->where('timetable_date', $request->timetable_date);
        }

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->class) {
            $query->where('class', $request->class);
        }

        if ($request->section) {
            $query->where('section', $request->section);
        }

        $lastLecture = $query->orderByDesc('end_time')->first();

        return response()->json([
            'next_start_time' => $lastLecture?->end_time,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function teacherTimetable(Request $request)
    {
        $teachers        = $this->activeTeachers();
        $selectedTeacher = null;
        $timetables      = collect();

        if ($request->filled('teacher_id')) {
            $selectedTeacher = Teacher::find($request->teacher_id);

            if ($selectedTeacher) {
                $timetables = TeacherTimetable::with('teacher')
                    ->where('teacher_id', $selectedTeacher->id)
                    ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                    ->orderBy('start_time')
                    ->get();
            }
        }

        return view('admin.timetable.teacher', compact('teachers', 'selectedTeacher', 'timetables'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(TeacherTimetable $timetable)
    {
        $timetable->load('teacher');

        return view('admin.timetable.show', compact('timetable'));
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH SAVED WEEK ("Load saved" button)
    | Route admin.timetable.weekly.fetch must point to this method.
    |--------------------------------------------------------------------------
    */

    public function fetchWeekly(Request $request)
    {
        $validated = $request->validate([
            'academic_year'   => ['required', 'string', 'max:30'],
            'class'           => ['required', 'string', 'max:100'],
            'section'         => ['required', 'string', 'max:50'],
            'week_start_date' => ['required', 'date'],
        ]);

        $weekStart = Carbon::parse($validated['week_start_date'])->startOfDay();

        if ($weekStart->dayOfWeekIso !== 1) {
            return response()->json(['message' => 'Please select a Monday as the week starting date.'], 422);
        }

        $records = TeacherTimetable::where('academic_year', $validated['academic_year'])
            ->where('class', $validated['class'])
            ->where('section', $validated['section'])
            ->whereBetween('timetable_date', [
                $weekStart->toDateString(),
                $weekStart->copy()->addDays(5)->toDateString(),
            ])
            ->orderBy('timetable_date')
            ->orderBy('start_time')
            ->get()
            ->map(fn ($r) => [
                'day'           => $r->day,
                'period_number' => $r->period_number,
                'period_type'   => $r->period_type,
                'teacher_id'    => $r->teacher_id,
                'subject'       => $r->subject,
                'subject_type'  => $r->subject_type,
                'lecture_type'  => $r->lecture_type,
                'start_time'    => substr((string) $r->start_time, 0, 5),
                'end_time'      => substr((string) $r->end_time, 0, 5),
                'minutes'       => $r->duration_minutes,
                'room'          => $r->room,
            ])
            ->values();

        return response()->json(['records' => $records]);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportWeekly(Request $request)
    {
        $validated = $request->validate([
            'academic_year'   => ['required', 'string', 'max:30'],
            'class'           => ['required', 'string', 'max:100'],
            'section'         => ['required', 'string', 'max:50'],
            'week_start_date' => ['required', 'date'],
        ]);

        $weekStart = Carbon::parse($validated['week_start_date'])->startOfDay();

        if ($weekStart->dayOfWeekIso !== 1) {
            return back()->withErrors([
                'week_start_date' => 'Please select a Monday as the week starting date.',
            ]);
        }

        return Excel::download(
            new WeeklyTimetableExport(
                $validated['class'],
                $validated['section'],
                $validated['academic_year'],
                $weekStart->format('Y-m-d')
            ),
            'weekly-timetable-' . $weekStart->format('Y-m-d') . '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function importWeekly(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        try {
            $import = new WeeklyTimetableImport();
            Excel::import($import, $request->file('file'));

            $rows = $import->rows;

            if ($rows->isEmpty()) {
                return back()->withErrors(['file' => 'The uploaded Excel file contains no timetable rows.']);
            }

            $requiredHeadings = [
                'academic_year', 'date', 'day', 'period', 'period_type', 'class',
                'section', 'teacher_id', 'subject', 'start_time', 'end_time', 'room',
            ];

            $missing = array_diff($requiredHeadings, array_keys($rows->first()->toArray()));

            if (!empty($missing)) {
                return back()->withErrors([
                    'file' => 'Excel headings are missing or incorrect: ' . implode(', ', $missing),
                ]);
            }

            /* ---------- helpers ---------- */

            $parseDate = function ($value): Carbon {
                if ($value === null || trim((string) $value) === '') {
                    throw new \RuntimeException('Date is missing.');
                }

                if (is_numeric($value)) {
                    return Carbon::instance(
                        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)
                    )->startOfDay();
                }

                $value = trim((string) $value);

                foreach (['!d-m-Y', '!d/m/Y', '!Y-m-d', '!Y/m/d'] as $format) {
                    try {
                        $date = Carbon::createFromFormat($format, $value);

                        if ($date && $date->format(substr($format, 1)) === $value) {
                            return $date->startOfDay();
                        }
                    } catch (\Throwable $e) {
                        // try next format
                    }
                }

                throw new \RuntimeException("Invalid date '{$value}'. Use DD-MM-YYYY or a valid Excel date.");
            };

            // Accepts Excel time values (0.375) and text such as 09:00, 09:00:00, 9:00 AM.
            $parseTime = function ($value, string $label): Carbon {
                if ($value === null || trim((string) $value) === '') {
                    throw new \RuntimeException("{$label} is missing.");
                }

                if (is_numeric($value)) {
                    $fraction = (float) $value - floor((float) $value);
                    $seconds  = min(86399, (int) round($fraction * 86400));

                    return Carbon::create(1970, 1, 1, intdiv($seconds, 3600), intdiv($seconds % 3600, 60), 0);
                }

                $value = trim((string) $value);

                foreach (['!H:i:s', '!H:i', '!h:i A', '!h:i a', '!g:i A', '!g:i a'] as $format) {
                    try {
                        $time = Carbon::createFromFormat($format, $value);

                        if ($time) {
                            return $time;
                        }
                    } catch (\Throwable $e) {
                        // try next format
                    }
                }

                throw new \RuntimeException("{$label} '{$value}' is not a valid time. Use HH:MM.");
            };

            $periodTypeMap = [
                'regular'       => 'Regular',
                'regular class' => 'Regular',
                'class'         => 'Regular',
                'lecture'       => 'Regular',
                'break'         => 'Break',
                'lunch'         => 'Lunch',
                'activity'      => 'Activity',
            ];

            /* ---------- class / section / week ---------- */

            // Break/Lunch rows may have blank class and section cells.
            $classRow = $rows->first(function ($row) {
                return trim((string) ($row['class'] ?? '')) !== ''
                    && trim((string) ($row['section'] ?? '')) !== '';
            });

            if (!$classRow) {
                throw new \RuntimeException('No populated Class and Section were found in the Excel file.');
            }

            $academicYear = trim((string) $rows->first()['academic_year']);
            $class        = trim((string) $classRow['class']);
            $section      = trim((string) $classRow['section']);

            if ($academicYear === '') {
                throw new \RuntimeException('Academic Year is missing.');
            }

            $firstDate = $parseDate($rows->first()['date']);
            $weekStart = $firstDate->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd   = $weekStart->copy()->addDays(5)->endOfDay();

            /* ---------- build records ---------- */

            $records = [];
            $labels  = [];

            foreach ($rows as $index => $row) {
                $excelRow = $index + 2;

                if (trim((string) ($row['academic_year'] ?? '')) !== $academicYear) {
                    throw new \RuntimeException("Excel row {$excelRow}: Academic Year must match the other rows.");
                }

                $rowClass   = trim((string) ($row['class'] ?? ''));
                $rowSection = trim((string) ($row['section'] ?? ''));

                if (($rowClass !== '' && $rowClass !== $class) || ($rowSection !== '' && $rowSection !== $section)) {
                    throw new \RuntimeException("Excel row {$excelRow}: Class and Section must match the other rows.");
                }

                $date = $parseDate($row['date']);

                if ($date->lt($weekStart) || $date->gt($weekEnd)) {
                    throw new \RuntimeException("Excel row {$excelRow}: Date must be within the same Monday-to-Saturday week.");
                }

                $day      = $date->format('l');
                $excelDay = trim((string) ($row['day'] ?? ''));

                if ($excelDay !== '' && strcasecmp($excelDay, $day) !== 0) {
                    throw new \RuntimeException("Excel row {$excelRow}: Day does not match the Date. Expected {$day}.");
                }

                $periodTypeValue = trim((string) ($row['period_type'] ?? ''));
                $periodTypeKey   = trim(preg_replace('/\s+/', ' ', strtolower($periodTypeValue)));

                if (!isset($periodTypeMap[$periodTypeKey])) {
                    throw new \RuntimeException(
                        "Excel row {$excelRow}: Invalid Period Type '{$periodTypeValue}'. "
                        . 'Use Regular, Regular Class, Class, Break, Lunch or Activity.'
                    );
                }

                $periodType     = $periodTypeMap[$periodTypeKey];
                $isBreakOrLunch = in_array($periodType, ['Break', 'Lunch'], true);

                $period = filter_var($row['period'] ?? null, FILTER_VALIDATE_INT);

                if ($period === false || $period < 1 || $period > 30) {
                    throw new \RuntimeException("Excel row {$excelRow}: Period must be a whole number between 1 and 30.");
                }

                $startTime = $parseTime($row['start_time'] ?? null, 'Start Time');
                $endTime   = $parseTime($row['end_time'] ?? null, 'End Time');

                if ($endTime->lessThanOrEqualTo($startTime)) {
                    throw new \RuntimeException("Excel row {$excelRow}: End Time must be later than Start Time.");
                }

                $teacherId = null;

                if (!$isBreakOrLunch) {
                    $teacherValue = trim((string) ($row['teacher_id'] ?? ''));

                    if ($teacherValue === '' || !ctype_digit($teacherValue) || !Teacher::whereKey((int) $teacherValue)->exists()) {
                        throw new \RuntimeException("Excel row {$excelRow}: Teacher ID is missing or does not exist.");
                    }

                    $teacherId = (int) $teacherValue;
                }

                $subject = trim((string) ($row['subject'] ?? ''));

                if (!$isBreakOrLunch && $subject === '') {
                    throw new \RuntimeException("Excel row {$excelRow}: Subject is required.");
                }

                $records[] = [
                    'academic_year'    => $academicYear,
                    'timetable_date'   => $date->toDateString(),
                    'day'              => $day,
                    'period_number'    => $period,
                    'period_type'      => $periodType,
                    'teacher_id'       => $teacherId,
                    'class'            => $class,
                    'section'          => $section,
                    'subject'          => $isBreakOrLunch ? $periodType : $subject,
                    'subject_type'     => ($isBreakOrLunch || $periodType === 'Activity') ? 'Activity' : 'Theory',
                    'lecture_type'     => ($isBreakOrLunch || $periodType === 'Activity') ? 'activity' : 'regular',
                    'start_time'       => $startTime->format('H:i:s'),
                    'end_time'         => $endTime->format('H:i:s'),
                    'duration_minutes' => (int) abs($startTime->diffInMinutes($endTime)),
                    'room'             => $isBreakOrLunch ? null : (trim((string) ($row['room'] ?? '')) ?: null),
                ];

                $labels[] = "Excel row {$excelRow}";
            }

            if ($message = $this->findOverlapInRows($records, $labels)) {
                throw new \RuntimeException($message);
            }

            if ($message = $this->findExternalConflict($records, $labels)) {
                throw new \RuntimeException($message);
            }

            $weekDates = collect(range(0, 5))
                ->map(fn ($i) => $weekStart->copy()->addDays($i)->toDateString())
                ->all();

            DB::transaction(function () use ($records, $academicYear, $class, $section, $weekDates) {
                TeacherTimetable::where('academic_year', $academicYear)
                    ->where('class', $class)
                    ->where('section', $section)
                    ->whereIn('timetable_date', $weekDates)
                    ->delete();

                foreach ($records as $record) {
                    TeacherTimetable::create($record);
                }
            });

            return redirect()
                ->route('admin.timetable.index')
                ->with('success', count($records) . ' timetable rows imported successfully.');

        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | WEEKLY PDF
    |--------------------------------------------------------------------------
    */

    public function weeklyPdf(Request $request)
    {
        $validated = $request->validate([
            'academic_year'   => ['required', 'string', 'max:30'],
            'class'           => ['required', 'string', 'max:100'],
            'section'         => ['required', 'string', 'max:50'],
            'week_start_date' => ['required', 'date'],
        ]);

        $weekStartDate = Carbon::parse($validated['week_start_date'])->startOfDay();

        if ($weekStartDate->dayOfWeekIso !== 1) {
            return back()->withErrors([
                'week_start_date' => 'Please select a Monday as the week starting date.',
            ]);
        }

        $records = TeacherTimetable::with('teacher')
            ->whereBetween('timetable_date', [
                $weekStartDate->toDateString(),
                $weekStartDate->copy()->addDays(5)->toDateString(),
            ])
            ->where('academic_year', $validated['academic_year'])
            ->where('class', $validated['class'])
            ->where('section', $validated['section'])
            ->orderBy('timetable_date')
            ->orderBy('start_time')
            ->get();

        $class        = $validated['class'];
        $section      = $validated['section'];
        $academicYear = $validated['academic_year'];

        $pdf = Pdf::loadView(
            'admin.timetable.weekly-pdf',
            compact('records', 'class', 'section', 'academicYear', 'weekStartDate')
        );

        $pdf->setPaper('a4', 'landscape');

        $filename = 'weekly-timetable-'
            . preg_replace('/[^A-Za-z0-9_-]/', '-', $class) . '-'
            . preg_replace('/[^A-Za-z0-9_-]/', '-', $section) . '-'
            . $weekStartDate->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE HELPERS (shared by store() and importWeekly())
    |--------------------------------------------------------------------------
    */

    /** Two rows on the same date that overlap in time (a class can't be in two places). */
    private function findOverlapInRows(array $rows, array $labels): ?string
    {
        foreach ($rows as $i => $row) {
            foreach ($rows as $j => $other) {
                if ($j <= $i || $row['timetable_date'] !== $other['timetable_date']) {
                    continue;
                }

                if ($row['start_time'] < $other['end_time'] && $row['end_time'] > $other['start_time']) {
                    return "Overlapping periods on {$row['day']}: {$labels[$i]} "
                        . '(' . substr($row['start_time'], 0, 5) . '-' . substr($row['end_time'], 0, 5) . ') and '
                        . "{$labels[$j]} (" . substr($other['start_time'], 0, 5) . '-' . substr($other['end_time'], 0, 5) . '). '
                        . 'Please check the start times and minutes.';
                }
            }
        }

        return null;
    }

    /** Teacher or room already used by ANOTHER class/section at the same time. */
    private function findExternalConflict(array $rows, array $labels): ?string
    {
        foreach ($rows as $i => $row) {
            if (in_array($row['period_type'], ['Break', 'Lunch'], true)) {
                continue;
            }

            $when = substr($row['start_time'], 0, 5) . '-' . substr($row['end_time'], 0, 5);

            // Same year + date, a DIFFERENT class/section, overlapping time
            $others = function () use ($row) {
                return TeacherTimetable::where('academic_year', $row['academic_year'])
                    ->where('timetable_date', $row['timetable_date'])
                    ->whereNotIn('period_type', ['Break', 'Lunch'])
                    ->whereNotNull('class')
                    ->where(function ($q) use ($row) {
                        $q->where('class', '!=', $row['class'])
                          ->orWhere('section', '!=', $row['section']);
                    })
                    ->where('start_time', '<', $row['end_time'])
                    ->where('end_time', '>', $row['start_time']);
            };

            $teacherClash = $others()->where('teacher_id', $row['teacher_id'])->first();

            if ($teacherClash) {
                $teacher = Teacher::find($row['teacher_id']);
                $name    = $teacher ? trim($teacher->first_name . ' ' . $teacher->last_name) : 'This teacher';

                return "Teacher conflict on {$labels[$i]} ({$when}): {$name} is already teaching "
                    . "Class {$teacherClash->class}-{$teacherClash->section} "
                    . '(' . substr($teacherClash->start_time, 0, 5) . '-' . substr($teacherClash->end_time, 0, 5) . '). '
                    . 'Choose another teacher or change the time.';
            }

            if (!empty($row['room'])) {
                $roomClash = $others()->where('room', $row['room'])->first();

                if ($roomClash) {
                    return "Room conflict on {$labels[$i]} ({$when}): room {$row['room']} is already used by "
                        . "Class {$roomClash->class}-{$roomClash->section}.";
                }
            }
        }

        return null;
    }
}