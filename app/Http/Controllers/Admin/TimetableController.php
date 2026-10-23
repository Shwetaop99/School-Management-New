<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherTimetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassTimetableExport;
use Carbon\Carbon;
use App\Exports\WeeklyTimetableExport;
use App\Imports\WeeklyTimetableImport;

class TimetableController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $timetables = TeacherTimetable::with('teacher')
            ->latest()
            ->get();

        $classes = DB::table('classes')
            ->orderBy('id')
            ->get();

        $sections = DB::table('sections')
            ->orderBy('id')
            ->get();

        return view(
            'admin.timetable.index',
            compact(
                'timetables',
                'classes',
                'sections'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */


public function create()
{
    $teachers = Teacher::where('status', 'Active')
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    $classes = DB::table('classes')
        ->orderBy('id')
        ->get();

    $sections = DB::table('sections')
        ->orderBy('class_id')
        ->orderBy('id')
        ->get();

    $subjects = DB::table('subjects')
        ->where('status', 1)
        ->orderBy('subject_name')
        ->get();

    return view('admin.timetable.create', compact(
        'teachers',
        'classes',
        'sections',
        'subjects'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */


public function store(Request $request)
{
    $validated = $request->validate([
        'academic_year' => ['required', 'string', 'max:20'],
        'class' => ['required', 'string', 'max:100'],
        'section' => ['required', 'string', 'max:50'],
        'week_start_date' => ['required', 'date'],

        'timetable' => ['required', 'array'],
        'timetable.Monday' => ['required', 'array'],
        'timetable.Tuesday' => ['required', 'array'],
        'timetable.Wednesday' => ['required', 'array'],
        'timetable.Thursday' => ['required', 'array'],
        'timetable.Friday' => ['required', 'array'],
        'timetable.Saturday' => ['required', 'array'],

        'timetable.*.*.period_type' => [
            'required',
            Rule::in(['Regular', 'Break', 'Lunch', 'Activity']),
        ],
        'timetable.*.*.teacher_id' => [
            'nullable',
            'integer',
            'exists:teachers,id',
        ],
        'timetable.*.*.subject' => ['nullable', 'string', 'max:100'],
        'timetable.*.*.subject_type' => [
            'nullable',
            Rule::in(['Theory', 'Practical', 'Activity']),
        ],
        'timetable.*.*.lecture_type' => [
            'nullable',
            Rule::in(['regular', 'extra', 'practical', 'activity']),
        ],
        'timetable.*.*.start_time' => ['required', 'date_format:H:i'],
        'timetable.*.*.end_time' => ['nullable', 'date_format:H:i'],
        'timetable.*.*.room' => ['nullable', 'string', 'max:100'],
    ]);
$weekStartValue = $first['week_start_date'];

if (is_numeric($weekStartValue)) {
    $weekStart = Carbon::instance(
        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
            (float) $weekStartValue
        )
    )->startOfDay();
} else {
    $weekStart = Carbon::parse($weekStartValue)->startOfDay();
}

    if ($weekStart->format('l') !== 'Monday') {
        return back()
            ->withInput()
            ->withErrors([
                'week_start_date' => 'Please select a Monday as the week starting date.',
            ]);
    }

    $days = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    $schedule = $validated['timetable'];

    if (count($schedule) !== 6) {
        return back()->withInput()->withErrors([
            'timetable' => 'Please submit the complete Monday-to-Saturday timetable.',
        ]);
    }

    $rows = [];

    /*
    |--------------------------------------------------------------------------
    | BUILD ALL 48 PERIODS
    |--------------------------------------------------------------------------
    */

    foreach ($days as $dayIndex => $day) {
        if (!isset($schedule[$day]) || count($schedule[$day]) !== 8) {
            return back()->withInput()->withErrors([
                'timetable' => "$day must contain exactly 8 periods.",
            ]);
        }

        $date = $weekStart->copy()->addDays($dayIndex)->toDateString();

        foreach (range(1, 8) as $periodNumber) {
            if (!isset($schedule[$day][$periodNumber])) {
                return back()->withInput()->withErrors([
                    'timetable' => "$day is missing period $periodNumber.",
                ]);
            }

            $slot = $schedule[$day][$periodNumber];
            $type = $slot['period_type'];

            /*
             * Fixed durations:
             * Regular lecture = 45 minutes
             * Activity = 45 minutes
             * Break = 15 minutes
             * Lunch = 60 minutes
             */
            $duration = match ($type) {
                'Regular', 'Activity' => 45,
                'Break' => 15,
                'Lunch' => 60,
            };

            $start = Carbon::createFromFormat(
                'H:i',
                $slot['start_time']
            );

            $end = $start->copy()->addMinutes($duration);

            $isBreakOrLunch = in_array($type, ['Break', 'Lunch']);

            if (!$isBreakOrLunch) {
                if (empty($slot['teacher_id'])) {
                    return back()->withInput()->withErrors([
                        "timetable.$day.$periodNumber.teacher_id" =>
                            "$day, period $periodNumber requires a teacher.",
                    ]);
                }

                if (empty($slot['subject'])) {
                    return back()->withInput()->withErrors([
                        "timetable.$day.$periodNumber.subject" =>
                            "$day, period $periodNumber requires a subject.",
                    ]);
                }

                if (empty($slot['subject_type'])) {
                    return back()->withInput()->withErrors([
                        "timetable.$day.$periodNumber.subject_type" =>
                            "$day, period $periodNumber requires a subject type.",
                    ]);
                }
            }

            $rows[] = [
                'academic_year' => $validated['academic_year'],
                'timetable_date' => $date,
                'day' => $day,
                'period_number' => $periodNumber,
                'period_type' => $type,

                'teacher_id' => $isBreakOrLunch
                    ? null
                    : $slot['teacher_id'],

                'class' => $isBreakOrLunch
                    ? null
                    : $validated['class'],

                'section' => $isBreakOrLunch
                    ? null
                    : $validated['section'],

                'subject' => $isBreakOrLunch
                    ? $type
                    : $slot['subject'],

                'subject_type' => $isBreakOrLunch
                    ? 'Activity'
                    : ($type === 'Activity'
                        ? 'Activity'
                        : $slot['subject_type']),

                'lecture_type' => $isBreakOrLunch
                    ? 'activity'
                    : ($type === 'Activity'
                        ? 'activity'
                        : ($slot['lecture_type'] ?? 'regular')),

                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'duration_minutes' => $duration,

                'room' => $isBreakOrLunch
                    ? null
                    : ($slot['room'] ?? null),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK CONFLICTS WITH OTHER TIMETABLE RECORDS
    |--------------------------------------------------------------------------
    */

    foreach ($rows as $row) {
        if (in_array($row['period_type'], ['Break', 'Lunch'])) {
            continue;
        }

        $overlap = function ($query) use ($row) {
            $query->where('start_time', '<', $row['end_time'])
                ->where('end_time', '>', $row['start_time']);
        };

        $teacherConflict = TeacherTimetable::where(
                'academic_year',
                $row['academic_year']
            )
            ->where('timetable_date', $row['timetable_date'])
            ->where('teacher_id', $row['teacher_id'])
            ->where($overlap)
            ->exists();

        if ($teacherConflict) {
            return back()->withInput()->withErrors([
                'timetable' =>
                    "Teacher conflict on {$row['day']}, period {$row['period_number']}.",
            ]);
        }

        $classConflict = TeacherTimetable::where(
                'academic_year',
                $row['academic_year']
            )
            ->where('timetable_date', $row['timetable_date'])
            ->where('class', $row['class'])
            ->where('section', $row['section'])
            ->where($overlap)
            ->exists();

        if ($classConflict) {
            return back()->withInput()->withErrors([
                'timetable' =>
                    "Class/section conflict on {$row['day']}, period {$row['period_number']}.",
            ]);
        }

        if (!empty($row['room'])) {
            $roomConflict = TeacherTimetable::where(
                    'academic_year',
                    $row['academic_year']
                )
                ->where('timetable_date', $row['timetable_date'])
                ->where('room', $row['room'])
                ->where($overlap)
                ->exists();

            if ($roomConflict) {
                return back()->withInput()->withErrors([
                    'timetable' =>
                        "Room conflict on {$row['day']}, period {$row['period_number']}.",
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OVERLAPPING PERIODS WITHIN THIS SUBMISSION
    |--------------------------------------------------------------------------
    */

    foreach ($rows as $index => $row) {
        if (in_array($row['period_type'], ['Break', 'Lunch'])) {
            continue;
        }

        foreach ($rows as $otherIndex => $other) {
            if (
                $index === $otherIndex ||
                $row['timetable_date'] !== $other['timetable_date'] ||
                in_array($other['period_type'], ['Break', 'Lunch'])
            ) {
                continue;
            }

            $overlap =
                $row['start_time'] < $other['end_time'] &&
                $row['end_time'] > $other['start_time'];

            if (!$overlap) {
                continue;
            }

            $sameTeacher =
                $row['teacher_id'] === $other['teacher_id'];

            $sameClass =
                $row['class'] === $other['class'] &&
                $row['section'] === $other['section'];

            $sameRoom =
                !empty($row['room']) &&
                $row['room'] === $other['room'];

            if ($sameTeacher || $sameClass || $sameRoom) {
                return back()->withInput()->withErrors([
                    'timetable' =>
                        "Overlapping periods found on {$row['day']}. Please check the start times.",
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE ALL 48 RECORDS TOGETHER
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($rows) {
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
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $classes = DB::table('classes')
            ->orderBy('id')
            ->get();

        $sections = DB::table('sections')
            ->orderBy('class_id')
            ->orderBy('id')
            ->get();

        return view(
            'admin.timetable.edit',
            compact(
                'timetable',
                'teachers',
                'classes',
                'sections'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TeacherTimetable $timetable
    ) {
        $validated = $request->validate([

            /*
            | Teacher is required for Regular and Activity.
            */
            'teacher_id' => [
                'nullable',
                'exists:teachers,id',
                'required_unless:period_type,Break,Lunch',
            ],

            'timetable_date' => [
                'required',
                'date',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'day' => [
                'required',
                Rule::in([
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ]),
            ],

            'period_number' => [
                'required',
                'integer',
                'min:1',
                'max:15',
            ],

            'period_type' => [
                'required',
                Rule::in([
                    'Regular',
                    'Break',
                    'Lunch',
                    'Activity',
                ]),
            ],

            'lecture_type' => [
                'nullable',
                Rule::in([
                    'regular',
                    'extra',
                    'practical',
                    'activity',
                ]),
            ],

            /*
            | Class is required for Regular and Activity.
            */
            'class' => [
                'nullable',
                'string',
                'max:100',
                'required_unless:period_type,Break,Lunch',
            ],

            /*
            | Section is required for Regular and Activity.
            */
            'section' => [
                'nullable',
                'string',
                'max:50',
                'required_unless:period_type,Break,Lunch',
            ],

            /*
            | Subject is required for Regular and Activity.
            */
            'subject' => [
                'nullable',
                'string',
                'max:100',
                'required_unless:period_type,Break,Lunch',
            ],

            /*
            | Subject type is required for Regular and Activity.
            */
            'subject_type' => [
                'nullable',
                Rule::in([
                    'Theory',
                    'Practical',
                    'Activity',
                ]),
                'required_unless:period_type,Break,Lunch',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:5',
                'max:300',
            ],

            'room' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CALCULATE DAY FROM DATE
        |--------------------------------------------------------------------------
        */

        $calculatedDay = Carbon::parse(
            $validated['timetable_date']
        )->format('l');

        if (
            !in_array(
                $calculatedDay,
                [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ]
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'timetable_date' =>
                        'Sunday timetable is not allowed.',
                ]);
        }

        $validated['day'] = $calculatedDay;

        /*
        |--------------------------------------------------------------------------
        | CALCULATE LECTURE TIME
        |--------------------------------------------------------------------------
        */

        $this->calculateLectureTime($validated);

        /*
        |--------------------------------------------------------------------------
        | BREAK / LUNCH
        |--------------------------------------------------------------------------
        */

        if (
            $validated['period_type'] === 'Break' ||
            $validated['period_type'] === 'Lunch'
        ) {
            $validated['teacher_id'] = null;
            $validated['class'] = null;
            $validated['section'] = null;

            $validated['subject'] =
                $validated['period_type'];

            $validated['subject_type'] = 'Activity';
            $validated['room'] = null;
            $validated['lecture_type'] = 'activity';
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Keep Teacher, Class and Section.
        |
        */

        if ($validated['period_type'] === 'Activity') {

            /*
            | DO NOT clear:
            | teacher_id
            | class
            | section
            */

            $validated['subject_type'] = 'Activity';
            $validated['lecture_type'] = 'activity';
        }

        /*
        |--------------------------------------------------------------------------
        | CONFLICT CHECKS
        |--------------------------------------------------------------------------
        */

        if (
            $validated['period_type'] !== 'Break' &&
            $validated['period_type'] !== 'Lunch'
        ) {

            /*
            |--------------------------------------------------------------------------
            | TEACHER CONFLICT
            |--------------------------------------------------------------------------
            */

            $teacherConflict = TeacherTimetable::where(
                    'teacher_id',
                    $validated['teacher_id']
                )
                ->where(
                    'timetable_date',
                    $validated['timetable_date']
                )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->where(
                    'day',
                    $validated['day']
                )
                ->where(
                    'id',
                    '!=',
                    $timetable->id
                )
                ->where(function ($query) use ($validated) {

                    $query
                        ->where(
                            'start_time',
                            '<',
                            $validated['end_time']
                        )
                        ->where(
                            'end_time',
                            '>',
                            $validated['start_time']
                        );
                })
                ->exists();

            if ($teacherConflict) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'This teacher already has a timetable entry on this date during this time.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CLASS / SECTION CONFLICT
            |--------------------------------------------------------------------------
            */

            $classConflict = TeacherTimetable::where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->where(
                    'timetable_date',
                    $validated['timetable_date']
                )
                ->where(
                    'day',
                    $validated['day']
                )
                ->where(
                    'class',
                    $validated['class']
                )
                ->where(
                    'id',
                    '!=',
                    $timetable->id
                )
                ->where(function ($query) use ($validated) {

                    if (!empty($validated['section'])) {

                        $query->where(
                            'section',
                            $validated['section']
                        );

                    } else {

                        $query->whereNull('section');
                    }
                })
                ->where(function ($query) use ($validated) {

                    $query
                        ->where(
                            'start_time',
                            '<',
                            $validated['end_time']
                        )
                        ->where(
                            'end_time',
                            '>',
                            $validated['start_time']
                        );
                })
                ->exists();

            if ($classConflict) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'class' =>
                            'This class and section already have a timetable entry on this date during this time.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | ROOM CONFLICT
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['room'])) {

                $roomConflict = TeacherTimetable::where(
                        'academic_year',
                        $validated['academic_year']
                    )
                    ->where(
                        'timetable_date',
                        $validated['timetable_date']
                    )
                    ->where(
                        'day',
                        $validated['day']
                    )
                    ->where(
                        'room',
                        $validated['room']
                    )
                    ->where(
                        'id',
                        '!=',
                        $timetable->id
                    )
                    ->where(function ($query) use ($validated) {

                        $query
                            ->where(
                                'start_time',
                                '<',
                                $validated['end_time']
                            )
                            ->where(
                                'end_time',
                                '>',
                                $validated['start_time']
                            );
                    })
                    ->exists();

                if ($roomConflict) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'room' =>
                                'This room is already occupied on this date during this time.',
                        ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE RECORD
        |--------------------------------------------------------------------------
        */

        $timetable->update($validated);

        return redirect()
            ->route('admin.timetable.index')
            ->with(
                'success',
                'Timetable updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TIME CALCULATION
    |--------------------------------------------------------------------------
    */

    private function calculateLectureTime(array &$validated)
    {
        $startTime = Carbon::createFromFormat(
            'H:i',
            $validated['start_time']
        );

        /*
        | Break = 15 minutes
        */

        if ($validated['period_type'] === 'Break') {

            $endTime = $startTime
                ->copy()
                ->addMinutes(15);

            $validated['end_time'] =
                $endTime->format('H:i');

            $validated['duration_minutes'] = 15;

            return;
        }

        /*
        | Lunch = 60 minutes
        */

        if ($validated['period_type'] === 'Lunch') {

            $endTime = $startTime
                ->copy()
                ->addMinutes(60);

            $validated['end_time'] =
                $endTime->format('H:i');

            $validated['duration_minutes'] = 60;

            return;
        }

        /*
        | Regular lecture = 45 minutes
        */

        if (
            ($validated['lecture_type'] ?? 'regular') === 'regular' &&
            empty($validated['end_time'])
        ) {

            $endTime = $startTime
                ->copy()
                ->addMinutes(45);

            $validated['end_time'] =
                $endTime->format('H:i');

            $validated['duration_minutes'] = 45;

            return;
        }

        /*
        | Other lectures require end time.
        */

        if (empty($validated['end_time'])) {

            abort(
                redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'end_time' =>
                            'Please enter an end time for this lecture.',
                    ])
            );
        }

        $endTime = Carbon::createFromFormat(
            'H:i',
            $validated['end_time']
        );

        /*
        | End time must be after start time.
        */

        if (
            $endTime->lessThanOrEqualTo($startTime)
        ) {

            abort(
                redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'end_time' =>
                            'End time must be after start time.',
                    ])
            );
        }

        /*
        | Calculate duration automatically.
        */

        $validated['duration_minutes'] =
            $startTime->diffInMinutes($endTime);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        TeacherTimetable $timetable
    ) {
        $timetable->delete();

        return redirect()
            ->route('admin.timetable.index')
            ->with(
                'success',
                'Timetable deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function classTimetable(
        Request $request
    ) {
        $classes = DB::table('classes')
            ->orderBy('id')
            ->pluck('class_name');

        $sections = collect();
        $timetables = collect();

        if ($request->filled('class')) {

            $class = DB::table('classes')
                ->where(
                    'class_name',
                    $request->class
                )
                ->first();

            if ($class) {

                $sections = DB::table('sections')
                    ->where(
                        'class_id',
                        $class->id
                    )
                    ->orderBy('id')
                    ->pluck('section_name');

                $query = TeacherTimetable::with('teacher')
                    ->where(
                        'class',
                        $request->class
                    );

                if ($request->filled('section')) {

                    $query->where(
                        'section',
                        $request->section
                    );
                }

                $timetables = $query
                    ->orderBy('period_number')
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
                    ->orderBy('start_time')
                    ->get();
            }
        }

        return view(
            'admin.timetable.class',
            compact(
                'classes',
                'sections',
                'timetables'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS PDF
    |--------------------------------------------------------------------------
    */

    public function classPdf(
        Request $request
    ) {
        $request->validate([
            'class' => 'required|string',
            'section' => 'nullable|string',
        ]);

        $query = TeacherTimetable::with('teacher')
            ->where(function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where(
                        'class',
                        $request->class
                    );

                    if ($request->filled('section')) {

                        $q->where(
                            'section',
                            $request->section
                        );
                    }
                });

                /*
                | Include Break and Lunch.
                */

                $query->orWhereIn(
                    'period_type',
                    [
                        'Break',
                        'Lunch',
                    ]
                );
            });

        $timetables = $query
            ->orderBy('period_number')
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
            ->orderBy('start_time')
            ->get();

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        $periods = $timetables
            ->groupBy('period_number')
            ->sortKeys();

        $pdf = Pdf::loadView(
            'admin.timetable.pdf',
            compact(
                'timetables',
                'days',
                'periods'
            )
        );

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'class-timetable-' .
            $request->class .
            '-' .
            ($request->section ?? 'all') .
            '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS EXCEL
    |--------------------------------------------------------------------------
    */

    public function classExcel(
        Request $request
    ) {
        $request->validate([
            'class' => 'required|string',
            'section' => 'nullable|string',
        ]);

        return Excel::download(
            new ClassTimetableExport(
                $request->class,
                $request->section
            ),
            'class-timetable-' .
            $request->class .
            '-' .
            ($request->section ?? 'all') .
            '.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NEXT TIME
    |--------------------------------------------------------------------------
    */

    public function nextTime(
        Request $request
    ) {
        $request->validate([
            'academic_year' => 'required|string',
            'day' => 'required|string',
            'timetable_date' => 'nullable|date',
            'teacher_id' => 'nullable|integer',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
        ]);

        $query = TeacherTimetable::where(
            'academic_year',
            $request->academic_year
        )
        ->where(
            'day',
            $request->day
        )
        ->whereNotIn(
            'period_type',
            [
                'Break',
                'Lunch',
                'Activity',
            ]
        );

        if ($request->filled('timetable_date')) {

            $query->where(
                'timetable_date',
                $request->timetable_date
            );
        }

        if ($request->teacher_id) {

            $query->where(
                'teacher_id',
                $request->teacher_id
            );
        }

        if ($request->class) {

            $query->where(
                'class',
                $request->class
            );
        }

        if ($request->section) {

            $query->where(
                'section',
                $request->section
            );
        }

        $lastLecture = $query
            ->orderByDesc('end_time')
            ->first();

        return response()->json([
            'next_start_time' =>
                $lastLecture?->end_time,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function teacherTimetable(
        Request $request
    ) {
        /*
        | Use Active consistently.
        */

        $teachers = Teacher::where(
            'status',
            'Active'
        )
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $selectedTeacher = null;
        $timetables = collect();

        if ($request->filled('teacher_id')) {

            $selectedTeacher = Teacher::find(
                $request->teacher_id
            );

            if ($selectedTeacher) {

                $timetables = TeacherTimetable::with('teacher')
                    ->where(
                        'teacher_id',
                        $selectedTeacher->id
                    )
                    ->orderByRaw("
                        FIELD(
                            day,
                            'Monday',
                            'Tuesday',
                            'Wednesday',
                            'Thursday',
                            'Friday',
                            'Saturday',
                            'Sunday'
                        )
                    ")
                    ->orderBy('start_time')
                    ->get();
            }
        }

        return view(
            'admin.timetable.teacher',
            compact(
                'teachers',
                'selectedTeacher',
                'timetables'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        TeacherTimetable $timetable
    ) {
        $timetable->load('teacher');

        return view(
            'admin.timetable.show',
            compact('timetable')
        );
    }

public function exportWeekly(Request $request)
{
    $validated = $request->validate([
        'academic_year' => ['required', 'string', 'max:30'],
        'class' => ['required', 'string', 'max:100'],
        'section' => ['required', 'string', 'max:50'],
        'week_start_date' => ['required', 'date'],
    ]);

    $weekStart = Carbon::parse($validated['week_start_date']);

    if ($weekStart->dayOfWeekIso !== 1) {
    return back()->withErrors([
        'file' => 'Debug: Excel date = '
            . $weekStart->format('Y-m-d')
            . ', weekday = '
            . $weekStart->format('l')
            . '. Expected Monday.',
    ]);
}

    $filename = 'weekly-timetable-' .
        $weekStart->format('Y-m-d') . '.xlsx';

    return Excel::download(
        new WeeklyTimetableExport(
            $validated['class'],
            $validated['section'],
            $validated['academic_year'],
            $weekStart->format('Y-m-d')
        ),
        $filename
    );
}

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
            return back()->withErrors([
                'file' => 'The uploaded Excel file contains no timetable rows.',
            ]);
        }

        // These headings match WeeklyTimetableExport.
        $requiredHeadings = [
            'academic_year',
            'date',
            'day',
            'period',
            'period_type',
            'class',
            'section',
            'teacher_id',
            'subject',
            'start_time',
            'end_time',
            'room',
        ];

        $missing = array_diff(
            $requiredHeadings,
            array_keys($rows->first()->toArray())
        );

        if (!empty($missing)) {
            return back()->withErrors([
                'file' => 'Excel headings are missing or incorrect: '
                    . implode(', ', $missing),
            ]);
        }

        // Parse Excel dates and common text date formats.
        $parseDate = function ($value): Carbon {
            if ($value === null || trim((string) $value) === '') {
                throw new \RuntimeException('Date is missing.');
            }

            if (is_numeric($value)) {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                        (float) $value
                    )
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
                    // Try the next supported date format.
                }
            }

            throw new \RuntimeException(
                "Invalid date '{$value}'. Use DD-MM-YYYY or a valid Excel date."
            );
        };

        // Accept Excel time values and text such as 09:00 or 09:00:00.
       $periodTypeValue = trim(
    (string) ($row['period_type'] ?? '')
);

$periodTypeKey = strtolower($periodTypeValue);

// Accept common Excel labels.
$periodTypeKey = preg_replace('/\s+/', ' ', $periodTypeKey);
$periodTypeKey = trim($periodTypeKey);

$periodTypeMap = [
    'regular'       => 'Regular',
    'regular class' => 'Regular',
    'class'         => 'Regular',
    'lecture'       => 'Regular',
    'break'         => 'Break',
    'lunch'         => 'Lunch',
    'activity'      => 'Activity',
];

if (!isset($periodTypeMap[$periodTypeKey])) {
    throw new \RuntimeException(
        "Excel row {$excelRow}: Invalid Period Type '{$periodTypeValue}'. "
        . 'Use Regular, Regular Class, Class, Break, Lunch or Activity.'
    );
}

$periodType = $periodTypeMap[$periodTypeKey];
       // Use the first populated class/section row because Break/Lunch
        // rows may have blank class and section cells.
        $classRow = $rows->first(function ($row) {
            return trim((string) ($row['class'] ?? '')) !== ''
                && trim((string) ($row['section'] ?? '')) !== '';
        });

        if (!$classRow) {
            throw new \RuntimeException(
                'No populated Class and Section were found in the Excel file.'
            );
        }

        $academicYear = trim((string) $rows->first()['academic_year']);
        $class = trim((string) $classRow['class']);
        $section = trim((string) $classRow['section']);

        if ($academicYear === '') {
            throw new \RuntimeException('Academic Year is missing.');
        }

        // Determine the week from the actual Date column.
        // Week Start Date is not required in Excel.
        $firstDate = $parseDate($rows->first()['date']);
        $weekStart = $firstDate->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->addDays(5)->endOfDay();

        $records = [];

        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;

            $rowAcademicYear = trim((string) ($row['academic_year'] ?? ''));
            $rowClass = trim((string) ($row['class'] ?? ''));
            $rowSection = trim((string) ($row['section'] ?? ''));

            if ($rowAcademicYear !== $academicYear) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Academic Year must match the other rows."
                );
            }

            if (
                ($rowClass !== '' && $rowClass !== $class) ||
                ($rowSection !== '' && $rowSection !== $section)
            ) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Class and Section must match the other rows."
                );
            }

            $date = $parseDate($row['date']);

            if ($date->lt($weekStart) || $date->gt($weekEnd)) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Date must be within the same Monday-to-Saturday week."
                );
            }

            $day = $date->format('l');
            $excelDay = trim((string) ($row['day'] ?? ''));

            if ($excelDay !== '' && strcasecmp($excelDay, $day) !== 0) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Day does not match the Date. Expected {$day}."
                );
            }

$periodTypeValue = trim(
    (string) ($row['period_type'] ?? '')
);

$periodTypeKey = strtolower($periodTypeValue);

// Accept common Excel labels.
$periodTypeKey = preg_replace('/\s+/', ' ', $periodTypeKey);
$periodTypeKey = trim($periodTypeKey);

$periodTypeMap = [
    'regular'       => 'Regular',
    'regular class' => 'Regular',
    'class'         => 'Regular',
    'lecture'       => 'Regular',
    'break'         => 'Break',
    'lunch'         => 'Lunch',
    'activity'      => 'Activity',
];

if (!isset($periodTypeMap[$periodTypeKey])) {
    throw new \RuntimeException(
        "Excel row {$excelRow}: Invalid Period Type '{$periodTypeValue}'. "
        . 'Use Regular, Regular Class, Class, Break, Lunch or Activity.'
    );
}

$periodType = $periodTypeMap[$periodTypeKey];
            $period = filter_var(
                $row['period'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($period === false || $period < 1 || $period > 8) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Period must be between 1 and 8."
                );
            }

            $isBreakOrLunch = in_array(
                $periodType,
                ['Break', 'Lunch'],
                true
            );

            $startTime = $parseTime(
                $row['start_time'] ?? null,
                'Start Time'
            );

            $endTime = $parseTime(
                $row['end_time'] ?? null,
                'End Time'
            );

            $start = $startTime->format('H:i:s');
            $end = $endTime->format('H:i:s');

            if ($endTime->lessThanOrEqualTo($startTime)) {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: End Time must be later than Start Time."
                );
            }

            $duration = $startTime->diffInMinutes($endTime);

            $teacherId = null;

            if (!$isBreakOrLunch) {
                $teacherValue = trim((string) ($row['teacher_id'] ?? ''));

                if (
                    $teacherValue === '' ||
                    !ctype_digit($teacherValue) ||
                    !Teacher::whereKey((int) $teacherValue)->exists()
                ) {
                    throw new \RuntimeException(
                        "Excel row {$excelRow}: Teacher ID is missing or does not exist."
                    );
                }

                $teacherId = (int) $teacherValue;
            }

            $subject = trim((string) ($row['subject'] ?? ''));

            if (!$isBreakOrLunch && $subject === '') {
                throw new \RuntimeException(
                    "Excel row {$excelRow}: Subject is required."
                );
            }

            $records[] = [
                'academic_year' => $academicYear,
                'timetable_date' => $date->toDateString(),
                'day' => $day,
                'period_number' => $period,
                'period_type' => $periodType,
                'teacher_id' => $teacherId,
                'class' => $isBreakOrLunch ? null : $class,
                'section' => $isBreakOrLunch ? null : $section,
                'subject' => $isBreakOrLunch ? $periodType : $subject,

                // These fields remain in the database but are no longer
                // required as Excel columns.
                'subject_type' => $isBreakOrLunch
                    ? 'Activity'
                    : ($periodType === 'Activity' ? 'Activity' : 'Theory'),

                'lecture_type' => $periodType === 'Activity'
                    ? 'activity'
                    : 'regular',

                'start_time' => $start,
                'end_time' => $end,
                'duration_minutes' => $duration,
                'room' => $isBreakOrLunch
                    ? null
                    : (trim((string) ($row['room'] ?? '')) ?: null),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                TeacherTimetable::updateOrCreate(
                    [
                        'academic_year' => $record['academic_year'],
                        'timetable_date' => $record['timetable_date'],
                        'period_number' => $record['period_number'],
                        'class' => $record['class'],
                        'section' => $record['section'],
                    ],
                    $record
                );
            }
        });

        return redirect()
            ->route('admin.timetable.index')
            ->with(
                'success',
                count($records) . ' timetable rows imported successfully.'
            );

    } catch (\Throwable $e) {
        report($e);

        return back()->withErrors([
            'file' => 'Import failed: ' . $e->getMessage(),
        ]);
    }
}


/*
|--------------------------------------------------------------------------
| WEEKLY TIMETABLE PDF
|--------------------------------------------------------------------------
*/

public function weeklyPdf(Request $request)
{
    $validated = $request->validate([
        'academic_year' => ['required', 'string', 'max:30'],
        'class' => ['required', 'string', 'max:100'],
        'section' => ['required', 'string', 'max:50'],
        'week_start_date' => ['required', 'date'],
    ]);

    $weekStartDate = Carbon::parse(
        $validated['week_start_date']
    )->startOfDay();

    // The weekly timetable must start on Monday.
    if ($weekStartDate->dayOfWeekIso !== 1) {
        return back()->withErrors([
            'week_start_date' => 'Please select a Monday as the week starting date.',
        ]);
    }

    $weekEndDate = $weekStartDate->copy()
        ->addDays(5)
        ->endOfDay();

    $records = TeacherTimetable::with('teacher')
        ->whereBetween('timetable_date', [
            $weekStartDate->toDateString(),
            $weekEndDate->toDateString(),
        ])
        ->where('academic_year', $validated['academic_year'])
        ->where(function ($query) use ($validated) {
            $query->where(function ($q) use ($validated) {
                $q->where('class', $validated['class'])
                    ->where('section', $validated['section']);
            })->orWhereIn('period_type', ['Break', 'Lunch']);
        })
        ->orderBy('timetable_date')
        ->orderBy('period_number')
        ->orderBy('start_time')
        ->get();

    $class = $validated['class'];
    $section = $validated['section'];
    $academicYear = $validated['academic_year'];

    $pdf = Pdf::loadView(
        'admin.timetable.weekly-pdf',
        compact(
            'records',
            'class',
            'section',
            'academicYear',
            'weekStartDate'
        )
    );

    $pdf->setPaper('a4', 'landscape');

    $filename = 'weekly-timetable-'
        . preg_replace('/[^A-Za-z0-9_-]/', '-', $class)
        . '-'
        . preg_replace('/[^A-Za-z0-9_-]/', '-', $section)
        . '-'
        . $weekStartDate->format('Y-m-d')
        . '.pdf';

    return $pdf->download($filename);
}

}
