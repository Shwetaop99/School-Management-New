<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamHoliday;
use App\Models\ExamSession;
use App\Models\ExamSubject;
use App\Models\ExamTimetable;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{
    /**
     * Display timetable.
     */
    public function index(Request $request, Exam $exam)
    {
        $query = ExamTimetable::with([
            'schoolClass',
            'examSubject.subject',
            'session',
            'teacher',
        ])
            ->where('exam_id', $exam->id)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderBy('class_id');

        if ($request->filled('class_id')) {
            $query->where(
                'class_id',
                $request->integer('class_id')
            );
        }

        if ($request->filled('exam_date')) {
            $query->whereDate(
                'exam_date',
                $request->input('exam_date')
            );
        }

        $schedules = $query->get();

        $classes = SchoolClass::whereIn(
            'id',
            $exam->examClasses()->pluck('class_id')
        )
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view(
            'admin.exams.schedule.index',
            compact(
                'exam',
                'schedules',
                'classes'
            )
        );
    }

    /**
     * Show generate timetable page.
     */
    public function generateForm(Exam $exam)
    {
        $examClasses = ExamClass::with([
            'schoolClass.subjects' => function ($query) {
                $query
                    ->where('status', true)
                    ->orderBy('subject_name');
            },
        ])
            ->where('exam_id', $exam->id)
            ->get();

        $examSubjects = ExamSubject::with([
            'schoolClass',
            'subject',
        ])
            ->where('exam_id', $exam->id)
            ->where('status', true)
            ->orderBy('class_id')
            ->orderBy('subject_id')
            ->get();

        $sessions = ExamSession::where('exam_id', $exam->id)
            ->where('status', true)
            ->orderBy('start_time')
            ->get();

        $holidays = ExamHoliday::where('exam_id', $exam->id)
            ->where('status', true)
            ->orderBy('holiday_date')
            ->get();

        $activeTeachers = Teacher::where('status', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.exams.schedule.generate',
            compact(
                'exam',
                'examClasses',
                'examSubjects',
                'sessions',
                'holidays',
                'activeTeachers'
            )
        );
    }

    public function generate(Request $request, Exam $exam)
{
    $validated = $request->validate([
        'papers_per_day' => [
            'required',
            'integer',
            'in:1,2,3',
        ],

        'replace_existing' => [
            'nullable',
            'boolean',
        ],

        'teacher_ids' => [
            'required',
            'array',
            'min:1',
        ],

        'teacher_ids.*' => [
            'integer',
            'exists:teachers,id',
        ],
    ]);

    $papersPerDay = (int) $validated['papers_per_day'];

    /*
    |--------------------------------------------------------------------------
    | Selected Teachers
    |--------------------------------------------------------------------------
    */

    $teacherIds = collect($validated['teacher_ids'])
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values()
        ->toArray();

    $teachers = Teacher::whereIn('id', $teacherIds)
        ->where('status', true)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    if ($teachers->isEmpty()) {
        return back()
            ->withErrors([
                'teacher_ids' => 'Please select at least one active teacher.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Period
    |--------------------------------------------------------------------------
    */

    $startDate = Carbon::parse($exam->start_date)->startOfDay();
    $endDate = Carbon::parse($exam->end_date)->startOfDay();

    if ($endDate->lt($startDate)) {
        return back()
            ->withErrors([
                'generate' => 'Exam end date cannot be before the start date.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Selected Classes
    |--------------------------------------------------------------------------
    */

    $examClasses = ExamClass::with('schoolClass')
        ->where('exam_id', $exam->id)
        ->get();

    if ($examClasses->isEmpty()) {
        return back()
            ->withErrors([
                'generate' =>
                    'Please select at least one class before generating the timetable.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Subjects
    |--------------------------------------------------------------------------
    |
    | Subjects are already configured in Exam Subjects.
    | Every class can have its own marks and duration.
    |
    */

    $examSubjects = ExamSubject::with([
        'schoolClass',
        'subject',
    ])
        ->where('exam_id', $exam->id)
        ->where('status', true)
        ->orderBy('class_id')
        ->orderBy('subject_id')
        ->get();

    if ($examSubjects->isEmpty()) {
        return back()
            ->withErrors([
                'generate' =>
                    'Please configure subjects, marks and duration before generating the timetable.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Sessions
    |--------------------------------------------------------------------------
    */

    $sessions = ExamSession::where('exam_id', $exam->id)
        ->where('status', true)
        ->orderBy('start_time')
        ->get();

    if ($sessions->isEmpty()) {
        return back()
            ->withErrors([
                'generate' =>
                    'Please configure at least one active exam session.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Holidays
    |--------------------------------------------------------------------------
    */

    $holidayDates = ExamHoliday::where('exam_id', $exam->id)
        ->where('status', true)
        ->pluck('holiday_date')
        ->map(
            fn ($date) => Carbon::parse($date)->format('Y-m-d')
        )
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | Existing Timetable
    |--------------------------------------------------------------------------
    */

    $existingCount = ExamTimetable::where('exam_id', $exam->id)
        ->count();

    if (
        $existingCount > 0 &&
        !$request->boolean('replace_existing')
    ) {
        return back()
            ->withErrors([
                'generate' =>
                    'A timetable already exists. Please select "Replace Existing Timetable" to generate it again.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Group Subjects By Class
    |--------------------------------------------------------------------------
    */

    $subjectsByClass = $examSubjects->groupBy('class_id');

    /*
    |--------------------------------------------------------------------------
    | Validate Every Class
    |--------------------------------------------------------------------------
    */

    foreach ($examClasses as $examClass) {

        $classId = $examClass->class_id;

        $classSubjects = $subjectsByClass->get(
            $classId,
            collect()
        );

        $schoolClass = $examClass->schoolClass;

        $className =
            optional($schoolClass)->class_name
            ?? 'Class';

        $section =
            optional($schoolClass)->section;

        $displayClass =
            $className .
            ($section ? ' - ' . $section : '');

        if ($classSubjects->isEmpty()) {
            return back()
                ->withErrors([
                    'generate' =>
                        'No subjects are configured for ' .
                        $displayClass .
                        '. Please configure the subjects first.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Subject Durations
        |--------------------------------------------------------------------------
        */

        foreach ($classSubjects as $examSubject) {

            $duration = (int) $examSubject->duration_minutes;

            $subjectName =
                optional($examSubject->subject)->subject_name
                ?? 'Unknown Subject';

            if ($duration <= 0) {
                return back()
                    ->withErrors([
                        'generate' =>
                            $subjectName .
                            ' for ' .
                            $displayClass .
                            ' does not have a valid duration.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | Make Sure Subject Can Fit At Least One Session
            |--------------------------------------------------------------------------
            */

            $canFit = false;

            foreach ($sessions as $session) {

                $sessionStart =
                    Carbon::parse($session->start_time);

                $sessionEnd =
                    Carbon::parse($session->end_time);

                $sessionMinutes =
                    $sessionStart->diffInMinutes(
                        $sessionEnd
                    );

                if ($duration <= $sessionMinutes) {
                    $canFit = true;
                    break;
                }
            }

            if (!$canFit) {
                return back()
                    ->withErrors([
                        'generate' =>
                            $subjectName .
                            ' for ' .
                            $displayClass .
                            ' requires ' .
                            $duration .
                            ' minutes, but no configured exam session is long enough.',
                    ])
                    ->withInput();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE TIMETABLE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | The timetable is generated DATE + SESSION first.
    |
    | Example:
    |
    | 10:00 - 11:00
    |   Class 8  -> English
    |   Class 9  -> Mathematics
    |   Class 10 -> Science
    |
    | All classes therefore progress together.
    |
    */

    try {

        DB::transaction(function () use (
            $exam,
            $examClasses,
            $subjectsByClass,
            $sessions,
            $teachers,
            $holidayDates,
            $papersPerDay,
            $startDate,
            $endDate
        ) {

            /*
            |--------------------------------------------------------------------------
            | Remove Existing Timetable
            |--------------------------------------------------------------------------
            */

            ExamTimetable::where('exam_id', $exam->id)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | Prepare Class Queues
            |--------------------------------------------------------------------------
            */

            $classQueues = [];

            foreach ($examClasses as $examClass) {

                $classId = $examClass->class_id;

                $subjects = $subjectsByClass
                    ->get($classId, collect())
                    ->values();

                $classQueues[$classId] = [
                    'exam_class' => $examClass,
                    'subjects' => $subjects,
                    'index' => 0,
                    'papers_today' => 0,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Teacher Rotation
            |--------------------------------------------------------------------------
            */

            $rotationIndex = 0;

            /*
            |--------------------------------------------------------------------------
            | Current Date
            |--------------------------------------------------------------------------
            */

            $currentDate = $startDate->copy();

            /*
            |--------------------------------------------------------------------------
            | Main Date Loop
            |--------------------------------------------------------------------------
            */

            while ($currentDate->lte($endDate)) {

                $dateString = $currentDate->format('Y-m-d');

                /*
                |--------------------------------------------------------------------------
                | Skip Holiday
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $dateString,
                        $holidayDates,
                        true
                    )
                ) {
                    $currentDate->addDay();
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Reset Daily Paper Counter
                |--------------------------------------------------------------------------
                */

                foreach ($classQueues as &$queue) {
                    $queue['papers_today'] = 0;
                }

                unset($queue);

                /*
                |--------------------------------------------------------------------------
                | Process Every Session
                |--------------------------------------------------------------------------
                */

                foreach ($sessions as $session) {

                    /*
                    |--------------------------------------------------------------------------
                    | Check Whether Every Class Is Finished
                    |--------------------------------------------------------------------------
                    */

                    $allFinished = true;

                    foreach ($classQueues as $queue) {

                        if (
                            $queue['index'] <
                            $queue['subjects']->count()
                        ) {
                            $allFinished = false;
                            break;
                        }
                    }

                    if ($allFinished) {
                        break;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Session Time
                    |--------------------------------------------------------------------------
                    */

                    $sessionStart =
                        Carbon::parse(
                            $session->start_time
                        );

                    $sessionEnd =
                        Carbon::parse(
                            $session->end_time
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Schedule Each Class IN PARALLEL
                    |--------------------------------------------------------------------------
                    */

                    foreach ($classQueues as $classId => &$queue) {

                        /*
                        |--------------------------------------------------------------------------
                        | Skip Finished Class
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $queue['index'] >=
                            $queue['subjects']->count()
                        ) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Respect Papers Per Day
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $queue['papers_today'] >=
                            $papersPerDay
                        ) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Current Subject
                        |--------------------------------------------------------------------------
                        */

                        $examSubject =
                            $queue['subjects'][
                                $queue['index']
                            ];

                        $duration =
                            (int)
                            $examSubject->duration_minutes;

                        /*
                        |--------------------------------------------------------------------------
                        | IMPORTANT:
                        |
                        | Every class starts its paper at the
                        | beginning of the same session.
                        |
                        |--------------------------------------------------------------------------
                        */

                        $paperStart =
                            $sessionStart->copy();

                        $paperEnd =
                            $paperStart
                                ->copy()
                                ->addMinutes(
                                    $duration
                                );

                        /*
                        |--------------------------------------------------------------------------
                        | Make Sure It Fits
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $paperEnd->gt(
                                $sessionEnd
                            )
                        ) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Find Teachers Already Busy At This Time
                        |--------------------------------------------------------------------------
                        */

                        $usedTeacherIds =
                            ExamTimetable::where(
                                'exam_id',
                                $exam->id
                            )
                                ->whereDate(
                                    'exam_date',
                                    $dateString
                                )
                                ->where(
                                    'session_id',
                                    $session->id
                                )
                                ->where(
                                    function ($query) use (
                                        $paperStart,
                                        $paperEnd
                                    ) {

                                        $query
                                            ->where(
                                                'start_time',
                                                '<',
                                                $paperEnd->format(
                                                    'H:i:s'
                                                )
                                            )
                                            ->where(
                                                'end_time',
                                                '>',
                                                $paperStart->format(
                                                    'H:i:s'
                                                )
                                            );
                                    }
                                )
                                ->pluck('teacher_id')
                                ->filter()
                                ->flip()
                                ->toArray();

                        /*
                        |--------------------------------------------------------------------------
                        | Assign Available Teacher
                        |--------------------------------------------------------------------------
                        */

                        $teacher =
                            $this->findAvailableTeacher(
                                $teachers,
                                $usedTeacherIds,
                                $rotationIndex
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | No Teacher Available
                        |--------------------------------------------------------------------------
                        |
                        | Do not consume the subject.
                        | It will be attempted again in the next session.
                        |
                        */

                        if (!$teacher) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Create Timetable Entry
                        |--------------------------------------------------------------------------
                        */

                        ExamTimetable::create([

                            'exam_id' =>
                                $exam->id,

                            'class_id' =>
                                $classId,

                            'exam_subject_id' =>
                                $examSubject->id,

                            'session_id' =>
                                $session->id,

                            'teacher_id' =>
                                $teacher->id,

                            'exam_date' =>
                                $dateString,

                            'start_time' =>
                                $paperStart->format(
                                    'H:i:s'
                                ),

                            'end_time' =>
                                $paperEnd->format(
                                    'H:i:s'
                                ),

                            'maximum_marks' =>
                                (int)
                                $examSubject
                                    ->maximum_marks,

                            'duration_minutes' =>
                                $duration,

                            'status' =>
                                true,
                        ]);

                        /*
                        |--------------------------------------------------------------------------
                        | Move Class To Next Subject
                        |--------------------------------------------------------------------------
                        */

                        $queue['index']++;

                        $queue['papers_today']++;
                    }

                    unset($queue);
                }

                /*
                |--------------------------------------------------------------------------
                | Next Examination Day
                |--------------------------------------------------------------------------
                */

                $currentDate->addDay();
            }

            /*
            |--------------------------------------------------------------------------
            | Verify That Every Class Was Fully Scheduled
            |--------------------------------------------------------------------------
            */

            foreach ($classQueues as $queue) {

                if (
                    $queue['index'] <
                    $queue['subjects']->count()
                ) {

                    $schoolClass =
                        $queue['exam_class']->schoolClass;

                    $className =
                        optional($schoolClass)->class_name
                        ?? 'Class';

                    $section =
                        optional($schoolClass)->section;

                    $displayClass =
                        $className .
                        ($section
                            ? ' - ' . $section
                            : '');

                    $remainingSubject =
                        $queue['subjects'][
                            $queue['index']
                        ];

                    $subjectName =
                        optional(
                            $remainingSubject->subject
                        )->subject_name
                        ?? 'Unknown Subject';

                    throw new \RuntimeException(
                        'Unable to schedule ' .
                        $subjectName .
                        ' for ' .
                        $displayClass .
                        ' within the examination period. ' .
                        'Please increase the examination period, ' .
                        'add more sessions, ' .
                        'increase papers per day, ' .
                        'or select more invigilating teachers.'
                    );
                }
            }
        });

    } catch (\RuntimeException $e) {

        return back()
            ->withErrors([
                'generate' => $e->getMessage(),
            ])
            ->withInput();

    } catch (\Throwable $e) {

        report($e);

        return back()
            ->withErrors([
                'generate' =>
                    'The timetable could not be generated. Please check your exam dates, sessions, subject durations and selected teachers.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'admin.exam-schedules.index',
            $exam
        )
        ->with(
            'success',
            'Examination timetable generated successfully for all selected classes.'
        );
}
    /**
     * Find an available selected teacher.
     */
    private function findAvailableTeacher(
        Collection $teachers,
        array $usedTeacherIds,
        int &$rotationIndex
    ): ?Teacher {

        $teacherCount =
            $teachers->count();

        if ($teacherCount === 0) {
            return null;
        }

        for (
            $attempt = 0;
            $attempt < $teacherCount;
            $attempt++
        ) {

            $index =
                (
                    $rotationIndex +
                    $attempt
                ) % $teacherCount;

            $teacher =
                $teachers[$index];

            if (
                !isset(
                    $usedTeacherIds[
                        $teacher->id
                    ]
                )
            ) {

                $rotationIndex =
                    (
                        $index + 1
                    ) % $teacherCount;

                return $teacher;
            }
        }

        return null;
    }

    /**
     * Print timetable.
     */
    public function print(
        Request $request,
        Exam $exam
    ) {

        $query = ExamTimetable::with([
            'schoolClass',
            'examSubject.subject',
            'session',
            'teacher',
        ])
            ->where(
                'exam_id',
                $exam->id
            );

        if ($request->filled('class_id')) {

            $query->where(
                'class_id',
                $request->integer(
                    'class_id'
                )
            );
        }

        if ($request->filled('exam_date')) {

            $query->whereDate(
                'exam_date',
                $request->input(
                    'exam_date'
                )
            );
        }

        $timetables =
            $query
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->orderBy('class_id')
                ->get();

        $school =
            SchoolSetting::first();

        $logoData =
            $this->getSchoolLogoData(
                $school
            );

        $selectedClass = null;

        if ($request->filled('class_id')) {

            $selectedClass =
                SchoolClass::find(
                    $request->integer(
                        'class_id'
                    )
                );
        }

        return view(
            'admin.exams.schedule.print',
            compact(
                'exam',
                'timetables',
                'school',
                'logoData',
                'selectedClass'
            )
        );
    }

    /**
     * Download PDF.
     */
    public function pdf(
        Request $request,
        Exam $exam
    ) {

        $query = ExamTimetable::with([
            'schoolClass',
            'examSubject.subject',
            'session',
            'teacher',
        ])
            ->where(
                'exam_id',
                $exam->id
            )
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderBy('class_id');

        $selectedClass = null;

        if ($request->filled('class_id')) {

            $classId =
                $request->integer(
                    'class_id'
                );

            $query->where(
                'class_id',
                $classId
            );

            $selectedClass =
                SchoolClass::find(
                    $classId
                );
        }

        if ($request->filled('exam_date')) {

            $query->whereDate(
                'exam_date',
                $request->input(
                    'exam_date'
                )
            );
        }

        $schedules =
            $query->get();

        if ($schedules->isEmpty()) {

            return back()
                ->withErrors([
                    'pdf' =>
                        'No timetable entries are available for the selected filter.',
                ]);
        }

        $school =
            SchoolSetting::first();

        $logoData =
            $this->getSchoolLogoData(
                $school
            );

        $pdf =
            Pdf::loadView(
                'admin.exams.schedule.pdf',
                [
                    'exam' =>
                        $exam,

                    'schedules' =>
                        $schedules,

                    'school' =>
                        $school,

                    'selectedClass' =>
                        $selectedClass,

                    'logoData' =>
                        $logoData,

                    'selectedDate' =>
                        $request->input(
                            'exam_date'
                        ),
                ]
            );

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        $pdf->setOptions([
            'isHtml5ParserEnabled' =>
                true,

            'isRemoteEnabled' =>
                true,

            'defaultFont' =>
                'DejaVu Sans',
        ]);

        $className =
            $selectedClass
                ? preg_replace(
                    '/[^A-Za-z0-9_-]+/',
                    '-',
                    $selectedClass->class_name .
                    '-' .
                    $selectedClass->section
                )
                : 'All-Classes';

        $examName =
            preg_replace(
                '/[^A-Za-z0-9_-]+/',
                '-',
                $exam->exam_name
            );

        $filename =
            $examName .
            '-' .
            $className .
            '-Timetable.pdf';

        return $pdf->download(
            $filename
        );
    }

    /**
     * Edit timetable entry.
     */
    public function edit(
        Exam $exam,
        ExamTimetable $schedule
    ) {

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );

        $schedule->load([
            'schoolClass',
            'examSubject.subject',
            'session',
            'teacher',
        ]);

        $teachers =
            Teacher::where(
                'status',
                true
            )
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

        return view(
            'admin.exams.schedule.edit',
            compact(
                'exam',
                'schedule',
                'teachers'
            )
        );
    }

    /**
     * Update timetable entry.
     */
    public function update(
        Request $request,
        Exam $exam,
        ExamTimetable $schedule
    ) {

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );

        $validated =
            $request->validate([
                'teacher_id' => [
                    'required',
                    'integer',
                    'exists:teachers,id',
                ],

                'exam_date' => [
                    'required',
                    'date',
                ],

                'start_time' => [
                    'required',
                    'date_format:H:i',
                ],

                'end_time' => [
                    'required',
                    'date_format:H:i',
                    'after:start_time',
                ],
            ]);

        $examDate =
            Carbon::parse(
                $validated['exam_date']
            );

        $examStart =
            Carbon::parse(
                $exam->start_date
            );

        $examEnd =
            Carbon::parse(
                $exam->end_date
            );

        if (
            $examDate->lt($examStart) ||
            $examDate->gt($examEnd)
        ) {

            return back()
                ->withErrors([
                    'exam_date' =>
                        'The timetable date must be inside the examination period.',
                ])
                ->withInput();
        }

        $isHoliday =
            ExamHoliday::where(
                'exam_id',
                $exam->id
            )
                ->whereDate(
                    'holiday_date',
                    $examDate->format(
                        'Y-m-d'
                    )
                )
                ->where(
                    'status',
                    true
                )
                ->exists();

        if ($isHoliday) {

            return back()
                ->withErrors([
                    'exam_date' =>
                        'The selected date is configured as an examination holiday.',
                ])
                ->withInput();
        }

        $teacherExists =
            Teacher::where(
                'id',
                $validated['teacher_id']
            )
                ->where(
                    'status',
                    true
                )
                ->exists();

        if (!$teacherExists) {

            return back()
                ->withErrors([
                    'teacher_id' =>
                        'Selected teacher is not active.',
                ])
                ->withInput();
        }

        $conflict =
            ExamTimetable::where(
                'exam_id',
                $exam->id
            )
                ->whereDate(
                    'exam_date',
                    $validated['exam_date']
                )
                ->where(
                    'session_id',
                    $schedule->session_id
                )
                ->where(
                    'teacher_id',
                    $validated['teacher_id']
                )
                ->where(
                    'id',
                    '!=',
                    $schedule->id
                )
                ->exists();

        if ($conflict) {

            return back()
                ->withErrors([
                    'teacher_id' =>
                        'This teacher is already supervising another class in the same session.',
                ])
                ->withInput();
        }

        $session =
            ExamSession::find(
                $schedule->session_id
            );

        if (!$session) {

            return back()
                ->withErrors([
                    'start_time' =>
                        'The selected exam session could not be found.',
                ])
                ->withInput();
        }

        $startTime =
            Carbon::createFromFormat(
                'H:i',
                $validated['start_time']
            );

        $endTime =
            Carbon::createFromFormat(
                'H:i',
                $validated['end_time']
            );

        $sessionStart =
            Carbon::parse(
                $session->start_time
            );

        $sessionEnd =
            Carbon::parse(
                $session->end_time
            );

        if (
            $startTime->lt(
                $sessionStart
            ) ||
            $endTime->gt(
                $sessionEnd
            )
        ) {

            return back()
                ->withErrors([
                    'start_time' =>
                        'The timetable time must remain inside the selected exam session.',
                ])
                ->withInput();
        }

        $schedule->update([
            'teacher_id' =>
                $validated['teacher_id'],

            'exam_date' =>
                $validated['exam_date'],

            'start_time' =>
                $validated['start_time'],

            'end_time' =>
                $validated['end_time'],

            'duration_minutes' =>
                $startTime->diffInMinutes(
                    $endTime
                ),
        ]);

        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam
            )
            ->with(
                'success',
                'Timetable entry updated successfully.'
            );
    }

    /**
     * Convert school logo to base64 data URI.
     */
    private function getSchoolLogoData(
        ?SchoolSetting $school
    ): ?string {

        if (
            !$school ||
            empty($school->logo_url)
        ) {
            return null;
        }

        $logoUrl =
            trim(
                $school->logo_url
            );

        if ($logoUrl === '') {
            return null;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Local image
            |--------------------------------------------------------------------------
            */

            if (
                !preg_match(
                    '/^https?:\/\//i',
                    $logoUrl
                )
            ) {

                $relativePath =
                    ltrim(
                        $logoUrl,
                        '/'
                    );

                $possiblePaths = [

                    public_path(
                        $relativePath
                    ),

                    public_path(
                        'storage/' .
                        $relativePath
                    ),

                    storage_path(
                        'app/public/' .
                        $relativePath
                    ),
                ];

                foreach (
                    $possiblePaths
                    as $path
                ) {

                    if (
                        is_file($path) &&
                        is_readable($path)
                    ) {

                        $imageData =
                            file_get_contents(
                                $path
                            );

                        if (
                            $imageData !==
                            false
                        ) {

                            $mimeType =
                                mime_content_type(
                                    $path
                                );

                            if (!$mimeType) {
                                $mimeType =
                                    'image/png';
                            }

                            return
                                'data:' .
                                $mimeType .
                                ';base64,' .
                                base64_encode(
                                    $imageData
                                );
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Remote image
            |--------------------------------------------------------------------------
            */

            if (
                preg_match(
                    '/^https?:\/\//i',
                    $logoUrl
                )
            ) {

                $ch =
                    curl_init(
                        $logoUrl
                    );

                curl_setopt_array(
                    $ch,
                    [
                        CURLOPT_RETURNTRANSFER =>
                            true,

                        CURLOPT_FOLLOWLOCATION =>
                            true,

                        CURLOPT_MAXREDIRS =>
                            5,

                        CURLOPT_CONNECTTIMEOUT =>
                            10,

                        CURLOPT_TIMEOUT =>
                            20,

                        CURLOPT_SSL_VERIFYPEER =>
                            true,

                        CURLOPT_SSL_VERIFYHOST =>
                            2,

                        CURLOPT_USERAGENT =>
                            'School Management System',
                    ]
                );

                $imageData =
                    curl_exec($ch);

                $httpCode =
                    curl_getinfo(
                        $ch,
                        CURLINFO_HTTP_CODE
                    );

                $contentType =
                    curl_getinfo(
                        $ch,
                        CURLINFO_CONTENT_TYPE
                    );

                curl_close($ch);

                if (
                    $imageData !== false &&
                    !empty($imageData) &&
                    $httpCode >= 200 &&
                    $httpCode < 300
                ) {

                    if (
                        empty($contentType) ||
                        !str_starts_with(
                            strtolower(
                                $contentType
                            ),
                            'image/'
                        )
                    ) {

                        $extension =
                            strtolower(
                                pathinfo(
                                    parse_url(
                                        $logoUrl,
                                        PHP_URL_PATH
                                    ),
                                    PATHINFO_EXTENSION
                                )
                            );

                        $mimeMap = [
                            'jpg' =>
                                'image/jpeg',

                            'jpeg' =>
                                'image/jpeg',

                            'png' =>
                                'image/png',

                            'gif' =>
                                'image/gif',

                            'webp' =>
                                'image/webp',
                        ];

                        $contentType =
                            $mimeMap[
                                $extension
                            ] ??
                            'image/png';
                    }

                    $contentType =
                        trim(
                            explode(
                                ';',
                                $contentType
                            )[0]
                        );

                    return
                        'data:' .
                        $contentType .
                        ';base64,' .
                        base64_encode(
                            $imageData
                        );
                }
            }

        } catch (\Throwable $e) {

            report($e);
        }

        /*
        |--------------------------------------------------------------------------
        | Default logo
        |--------------------------------------------------------------------------
        */

        $fallbackPath =
            public_path(
                'images/gurukullogo.png'
            );

        if (
            is_file($fallbackPath) &&
            is_readable($fallbackPath)
        ) {

            try {

                $imageData =
                    file_get_contents(
                        $fallbackPath
                    );

                if (
                    $imageData !== false
                ) {

                    return
                        'data:image/png;base64,' .
                        base64_encode(
                            $imageData
                        );
                }

            } catch (\Throwable $e) {

                report($e);
            }
        }

        return null;
    }
}