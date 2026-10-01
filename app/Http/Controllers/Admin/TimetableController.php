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

        return view(
            'admin.timetable.create',
            compact(
                'teachers',
                'classes',
                'sections'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            | Teacher is required for Regular and Activity.
            | Break and Lunch do not need a teacher.
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

        /*
        | Sunday is not allowed.
        */

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

        /*
        | Always use the actual day calculated from date.
        */

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
        | Activity keeps:
        | - Teacher
        | - Class
        | - Section
        | - Subject / Activity name
        |
        | Only subject type and lecture type are automatically set.
        |
        */

        if ($validated['period_type'] === 'Activity') {

            /*
            | DO NOT set teacher_id to null.
            | DO NOT set class to null.
            | DO NOT set section to null.
            */

            $validated['subject_type'] = 'Activity';
            $validated['lecture_type'] = 'activity';
        }

        /*
        |--------------------------------------------------------------------------
        | CONFLICT CHECKS
        |--------------------------------------------------------------------------
        |
        | Conflict checking is performed for Regular and Activity.
        | Break and Lunch are excluded.
        |
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
        | SAVE
        |--------------------------------------------------------------------------
        */

        TeacherTimetable::create($validated);

        return redirect()
            ->route('admin.timetable.index')
            ->with(
                'success',
                'Timetable added successfully.'
            );
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
}
