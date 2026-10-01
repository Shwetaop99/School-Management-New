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
    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'teacher_id' => [
            'nullable',
            'exists:teachers,id',
            'required_unless:period_type,Break,Lunch,Activity',
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
            'nullable',
            'string',
            'max:20',
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

        'class' => [
            'nullable',
            'string',
            'max:100',
            'required_unless:period_type,Break,Lunch,Activity',
        ],

        'section' => [
            'nullable',
            'string',
            'max:50',
        ],

        'subject' => [
            'nullable',
            'string',
            'max:100',
        ],

        'subject_type' => [
            'nullable',
            Rule::in([
                'Theory',
                'Practical',
                'Activity',
            ]),
        ],

        'start_time' => [
            'required',
            'date_format:H:i',
        ],

        'end_time' => [
            'nullable',
            'date_format:H:i',
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

    $date = Carbon::parse($validated['timetable_date']);

    $day = $date->format('l');

    if ($day === 'Sunday') {
        return back()
            ->withInput()
            ->withErrors([
                'timetable_date' => 'Sunday timetable is not allowed.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGULAR
    |--------------------------------------------------------------------------
    */

    if ($validated['period_type'] === 'Regular') {

        if (empty($validated['teacher_id'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' => 'Please select a teacher.',
                ]);
        }

        if (empty($validated['class'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'class' => 'Please select a class.',
                ]);
        }

        if (empty($validated['subject'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject' => 'Please enter a subject.',
                ]);
        }

        if (empty($validated['subject_type'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_type' => 'Please select subject type.',
                ]);
        }

        if (empty($validated['lecture_type'])) {
            $validated['lecture_type'] = 'regular';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY
    |--------------------------------------------------------------------------
    |
    | Activity:
    | Teacher = NULL
    | Class = NULL
    | Section = NULL
    | Subject = selected activity
    | Subject Type = Activity
    |
    */

    if ($validated['period_type'] === 'Activity') {

        if (empty($validated['subject'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject' => 'Please select an activity.',
                ]);
        }

        $validated['teacher_id'] = null;
        $validated['class'] = null;
        $validated['section'] = null;
        $validated['subject_type'] = 'Activity';
        $validated['lecture_type'] = 'activity';
        $validated['room'] = null;
    }

    /*
    |--------------------------------------------------------------------------
    | BREAK
    |--------------------------------------------------------------------------
    */

    if ($validated['period_type'] === 'Break') {

        $validated['teacher_id'] = null;
        $validated['class'] = null;
        $validated['section'] = null;
        $validated['subject'] = 'Break';
        $validated['subject_type'] = 'Activity';
        $validated['lecture_type'] = 'activity';
        $validated['room'] = null;
    }

    /*
    |--------------------------------------------------------------------------
    | LUNCH
    |--------------------------------------------------------------------------
    */

    if ($validated['period_type'] === 'Lunch') {

        $validated['teacher_id'] = null;
        $validated['class'] = null;
        $validated['section'] = null;
        $validated['subject'] = 'Lunch';
        $validated['subject_type'] = 'Activity';
        $validated['lecture_type'] = 'activity';
        $validated['room'] = null;
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE END TIME
    |--------------------------------------------------------------------------
    */

    $startTime = Carbon::createFromFormat(
        'H:i',
        $validated['start_time']
    );

    /*
    | Break = 15 minutes
    */

    if ($validated['period_type'] === 'Break') {

        $endTime = $startTime->copy()->addMinutes(15);

        $validated['end_time'] = $endTime->format('H:i');
    }

    /*
    | Lunch = 60 minutes
    */

    elseif ($validated['period_type'] === 'Lunch') {

        $endTime = $startTime->copy()->addMinutes(60);

        $validated['end_time'] = $endTime->format('H:i');
    }

    /*
    | Activity = 45 minutes if end time is empty
    */

    elseif (
        $validated['period_type'] === 'Activity' &&
        empty($validated['end_time'])
    ) {

        $endTime = $startTime->copy()->addMinutes(45);

        $validated['end_time'] = $endTime->format('H:i');
    }

    /*
    | Regular = 45 minutes if end time is empty
    */

    elseif (
        $validated['period_type'] === 'Regular' &&
        empty($validated['end_time'])
    ) {

        $endTime = $startTime->copy()->addMinutes(45);

        $validated['end_time'] = $endTime->format('H:i');
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK END TIME
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['end_time'])) {

        $endTime = Carbon::createFromFormat(
            'H:i',
            $validated['end_time']
        );

        if ($endTime->lessThanOrEqualTo($startTime)) {

            return back()
                ->withInput()
                ->withErrors([
                    'end_time' =>
                        'End time must be after start time.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGULAR TEACHER CONFLICT
    |--------------------------------------------------------------------------
    */

    if ($validated['period_type'] === 'Regular') {

        $teacherConflict = TeacherTimetable::where(
                'teacher_id',
                $validated['teacher_id']
            )
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'day',
                $day
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
                        'This teacher already has a lecture during this time.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CLASS / SECTION CONFLICT
        |--------------------------------------------------------------------------
        */

        $classQuery = TeacherTimetable::where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'day',
                $day
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
            });

        if ($classQuery->exists()) {

            return back()
                ->withInput()
                ->withErrors([
                    'class' =>
                        'This class and section already have a lecture during this time.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT use TeacherTimetable::create($validated)
    | because the form contains fields that are not database columns.
    |
    */

    $timetable = new TeacherTimetable();

    $timetable->teacher_id = $validated['teacher_id'] ?? null;

    $timetable->academic_year = $validated['academic_year'];

    $timetable->day = $day;

    $timetable->period_number = $validated['period_number'];

    $timetable->period_type = $validated['period_type'];

    $timetable->class = $validated['class'] ?? null;

    $timetable->section = $validated['section'] ?? null;

    $timetable->subject = $validated['subject'] ?? null;

    $timetable->subject_type = $validated['subject_type'] ?? null;

    $timetable->start_time = $validated['start_time'];

    $timetable->end_time = $validated['end_time'] ?? null;

    $timetable->room = $validated['room'] ?? null;

    /*
    |--------------------------------------------------------------------------
    | SAVE TO DATABASE
    |--------------------------------------------------------------------------
    */

    $timetable->save();

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.timetable.index')
        ->with(
            'success',
            'Timetable added successfully.'
        );
}

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateTimetable(Request $request)
    {
        return $request->validate([

            'teacher_id' => [
                'nullable',
                'exists:teachers,id',
                'required_unless:period_type,Break,Lunch,Activity',
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
                'nullable',
                'string',
                'max:20',
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

            'class' => [
                'nullable',
                'string',
                'max:100',
                'required_unless:period_type,Break,Lunch,Activity',
            ],

            'section' => [
                'nullable',
                'string',
                'max:50',
            ],

            'subject' => [
                'nullable',
                'string',
                'max:100',
                'required_unless:period_type,Break,Lunch,Activity',
            ],

            'subject_type' => [
                'nullable',
                Rule::in([
                    'Theory',
                    'Practical',
                    'Activity',
                ]),
                'required_unless:period_type,Break,Lunch,Activity',
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
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE BREAK / LUNCH
    |--------------------------------------------------------------------------
    */

    private function prepareSpecialPeriod(array $validated)
    {
        $periodType = $validated['period_type'];

        $validated['teacher_id'] = null;
        $validated['class'] = null;
        $validated['section'] = null;

        $validated['subject'] = $periodType;
        $validated['subject_type'] = 'Activity';
        $validated['lecture_type'] = 'activity';
        $validated['room'] = null;

        return $validated;
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
        |--------------------------------------------------------------------------
        | BREAK
        |--------------------------------------------------------------------------
        */

        if ($validated['period_type'] === 'Break') {

            $validated['end_time'] = $startTime
                ->copy()
                ->addMinutes(15)
                ->format('H:i');

            $validated['duration_minutes'] = 15;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | LUNCH
        |--------------------------------------------------------------------------
        */

        if ($validated['period_type'] === 'Lunch') {

            $validated['end_time'] = $startTime
                ->copy()
                ->addMinutes(60)
                ->format('H:i');

            $validated['duration_minutes'] = 60;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | REGULAR
        |--------------------------------------------------------------------------
        */

        if (
            $validated['period_type'] === 'Regular' &&
            empty($validated['end_time'])
        ) {

            $validated['end_time'] = $startTime
                ->copy()
                ->addMinutes(45)
                ->format('H:i');

            $validated['duration_minutes'] = 45;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY
        |--------------------------------------------------------------------------
        */

        if (
            $validated['period_type'] === 'Activity' &&
            empty($validated['end_time'])
        ) {

            $validated['end_time'] = $startTime
                ->copy()
                ->addMinutes(45)
                ->format('H:i');

            $validated['duration_minutes'] = 45;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOM END TIME
        |--------------------------------------------------------------------------
        */

        if (empty($validated['end_time'])) {

            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' =>
                    'Please enter an end time for this lecture.',
            ]);
        }

        $endTime = Carbon::createFromFormat(
            'H:i',
            $validated['end_time']
        );

        if ($endTime->lessThanOrEqualTo($startTime)) {

            throw \Illuminate\Validation\ValidationException::withMessages([
                'end_time' =>
                    'End time must be after start time.',
            ]);
        }

        $validated['duration_minutes'] =
            $startTime->diffInMinutes($endTime);
    }

    /*
    |--------------------------------------------------------------------------
    | CONFLICT CHECK
    |--------------------------------------------------------------------------
    */

    private function checkConflicts(
        array $validated,
        $ignoreId = null
    ) {
        /*
        |--------------------------------------------------------------------------
        | TEACHER CONFLICT
        |--------------------------------------------------------------------------
        */

        $teacherQuery = TeacherTimetable::where(
                'teacher_id',
                $validated['teacher_id']
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

                $query->where(
                    'start_time',
                    '<',
                    $validated['end_time']
                )->where(
                    'end_time',
                    '>',
                    $validated['start_time']
                );
            });

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        |
        | Only use timetable_date if your table has this column.
        |
        */

        if (
            \Schema::hasColumn(
                'teacher_timetables',
                'timetable_date'
            )
        ) {
            $teacherQuery->where(
                'timetable_date',
                $validated['timetable_date']
            );
        }

        if ($ignoreId) {
            $teacherQuery->where(
                'id',
                '!=',
                $ignoreId
            );
        }

        if ($teacherQuery->exists()) {
            return [
                'field' => 'teacher_id',
                'message' =>
                    'This teacher already has a lecture during this time.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CLASS / SECTION CONFLICT
        |--------------------------------------------------------------------------
        */

        $classQuery = TeacherTimetable::where(
                'academic_year',
                $validated['academic_year']
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

                $query->where(
                    'start_time',
                    '<',
                    $validated['end_time']
                )->where(
                    'end_time',
                    '>',
                    $validated['start_time']
                );
            });

        if (
            \Schema::hasColumn(
                'teacher_timetables',
                'timetable_date'
            )
        ) {
            $classQuery->where(
                'timetable_date',
                $validated['timetable_date']
            );
        }

        if ($ignoreId) {
            $classQuery->where(
                'id',
                '!=',
                $ignoreId
            );
        }

        if ($classQuery->exists()) {
            return [
                'field' => 'class',
                'message' =>
                    'This class and section already have a lecture during this time.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ROOM CONFLICT
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['room'])) {

            $roomQuery = TeacherTimetable::where(
                    'academic_year',
                    $validated['academic_year']
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

                    $query->where(
                        'start_time',
                        '<',
                        $validated['end_time']
                    )->where(
                        'end_time',
                        '>',
                        $validated['start_time']
                    );
                });

            if (
                \Schema::hasColumn(
                    'teacher_timetables',
                    'timetable_date'
                )
            ) {
                $roomQuery->where(
                    'timetable_date',
                    $validated['timetable_date']
                );
            }

            if ($ignoreId) {
                $roomQuery->where(
                    'id',
                    '!=',
                    $ignoreId
                );
            }

            if ($roomQuery->exists()) {
                return [
                    'field' => 'room',
                    'message' =>
                        'This room is already occupied during this time.',
                ];
            }
        }

        return null;
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

    public function classTimetable(Request $request)
    {
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

    public function classPdf(Request $request)
    {
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

                $query->orWhereIn(
                    'period_type',
                    [
                        'Break',
                        'Lunch',
                        'Activity',
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

    public function classExcel(Request $request)
    {
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

    public function nextTime(Request $request)
    {
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

        if (
            $request->filled('timetable_date') &&
            \Schema::hasColumn(
                'teacher_timetables',
                'timetable_date'
            )
        ) {
            $query->where(
                'timetable_date',
                $request->timetable_date
            );
        }

        if ($request->filled('teacher_id')) {

            $query->where(
                'teacher_id',
                $request->teacher_id
            );
        }

        if ($request->filled('class')) {

            $query->where(
                'class',
                $request->class
            );
        }

        if ($request->filled('section')) {

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

    public function teacherTimetable(Request $request)
    {
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

                $timetables = TeacherTimetable::where(
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

    public function show(TeacherTimetable $timetable)
    {
        $timetable->load('teacher');

        return view(
            'admin.timetable.show',
            compact('timetable')
        );
    }
}