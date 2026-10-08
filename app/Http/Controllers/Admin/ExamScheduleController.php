<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamHoliday;
use App\Models\ExamSubject;
use App\Models\ExamTimetable;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class ExamScheduleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EXAM TIMETABLE INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Exam $exam)
    {
        $timetables = ExamTimetable::with([
            'schoolClass',
            'examSubject.subject',
            'teacher',
        ])
            ->where('exam_id', $exam->id)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->orderBy('class_id')
            ->get();

        return view(
            'admin.exams.exam-schedules.index',
            compact(
                'exam',
                'timetables'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE FORM
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | No manually configured sessions.
    | No teacher selection.
    | No teacher IDs during automatic generation.
    |
    */

    public function generateForm(Exam $exam)
    {
        /*
        |--------------------------------------------------------------------------
        | Load Exam Classes
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | exam_classes table does NOT have a status column.
        |
        */

        $examClasses = ExamClass::with([
            'schoolClass',
        ])
            ->where('exam_id', $exam->id)
            ->orderBy('class_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Load Exam Subjects
        |--------------------------------------------------------------------------
        */

        $examSubjects = ExamSubject::with([
            'schoolClass',
            'subject',
        ])
            ->where('exam_id', $exam->id)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 1);
            })
            ->orderBy('class_id')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Load Examination Holidays
        |--------------------------------------------------------------------------
        */

        $holidays = ExamHoliday::where(
            'exam_id',
            $exam->id
        )
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 1);
            })
            ->orderBy('holiday_date')
            ->get();


        return view(
            'admin.exams.exam-schedules.generate',
            compact(
                'exam',
                'examClasses',
                'examSubjects',
                'holidays'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE EXAM TIMETABLE
    |--------------------------------------------------------------------------
    |
    | Automatic generation logic:
    |
    | DATE
    |   -> PAPER SLOT
    |       -> CLASS
    |           -> NEXT SUBJECT OF THAT CLASS
    |
    | Each class has its own independent subject queue.
    |
    */

    public function generate(
        Request $request,
        Exam $exam
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

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
        ]);


        /*
        |--------------------------------------------------------------------------
        | Basic Exam Date Validation
        |--------------------------------------------------------------------------
        */

        if (
            !$exam->start_date ||
            !$exam->end_date
        ) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'Exam start date and end date must be configured before generating the timetable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Convert Exam Dates
        |--------------------------------------------------------------------------
        */

        $examStartDate = Carbon::parse(
            $exam->start_date
        )->startOfDay();

        $examEndDate = Carbon::parse(
            $exam->end_date
        )->startOfDay();


        if ($examStartDate->gt($examEndDate)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'Exam start date cannot be after exam end date.',
            ]);
        }


        $papersPerDay = (int) $validated['papers_per_day'];

        $replaceExisting = $request->boolean(
            'replace_existing'
        );


        /*
        |--------------------------------------------------------------------------
        | Load Exam Classes
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | exam_classes table does NOT contain a status column.
        |
        */

        $examClasses = ExamClass::with([
            'schoolClass',
        ])
            ->where('exam_id', $exam->id)
            ->orderBy('class_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Make Sure Classes Exist
        |--------------------------------------------------------------------------
        */

        if ($examClasses->isEmpty()) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'No classes have been added to this examination.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Exam Subjects
        |--------------------------------------------------------------------------
        */

        $examSubjects = ExamSubject::with([
            'schoolClass',
            'subject',
        ])
            ->where('exam_id', $exam->id)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 1);
            })
            ->orderBy('class_id')
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Make Sure Subjects Exist
        |--------------------------------------------------------------------------
        */

        if ($examSubjects->isEmpty()) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'No examination subjects have been configured.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Selected Class IDs
        |--------------------------------------------------------------------------
        */

        $classIds = $examClasses
            ->pluck('class_id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Group Subjects By Class
        |--------------------------------------------------------------------------
        |
        | Each class gets its own ordered subject queue.
        |
        */

        $subjectsByClass = $examSubjects
            ->whereIn(
                'class_id',
                $classIds
            )
            ->groupBy('class_id')
            ->map(function (Collection $subjects) {
                return $subjects
                    ->sortBy('id')
                    ->values();
            });


        /*
        |--------------------------------------------------------------------------
        | Make Sure Every Selected Class Has Subjects
        |--------------------------------------------------------------------------
        */

        $classesWithoutSubjects = [];

        foreach ($classIds as $classId) {

            if (
                !$subjectsByClass->has($classId) ||
                $subjectsByClass
                    ->get($classId)
                    ->isEmpty()
            ) {
                $examClass = $examClasses->firstWhere(
                    'class_id',
                    $classId
                );

                $classesWithoutSubjects[] =
                    $this->getClassDisplayName(
                        $examClass?->schoolClass
                    );
            }
        }


        if (!empty($classesWithoutSubjects)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'The following classes do not have any exam subjects configured: '
                    . implode(
                        ', ',
                        $classesWithoutSubjects
                    ),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Examination Holidays
        |--------------------------------------------------------------------------
        */

        $holidayDates = ExamHoliday::where(
            'exam_id',
            $exam->id
        )
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 1);
            })
            ->pluck('holiday_date')
            ->map(function ($date) {
                return Carbon::parse($date)
                    ->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Get Available Examination Dates
        |--------------------------------------------------------------------------
        |
        | Saturday and Sunday are skipped.
        | Configured examination holidays are skipped.
        |
        */

        $availableDates = [];

        $currentDate = $examStartDate->copy();

        while ($currentDate->lte($examEndDate)) {

            $dateString = $currentDate->format(
                'Y-m-d'
            );

            $isWeekend =
                $currentDate->isSaturday() ||
                $currentDate->isSunday();

            $isHoliday = in_array(
                $dateString,
                $holidayDates,
                true
            );

            if (
                !$isWeekend &&
                !$isHoliday
            ) {
                $availableDates[] =
                    $currentDate->copy();
            }

            $currentDate->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | No Available Dates
        |--------------------------------------------------------------------------
        */

        if (empty($availableDates)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'There are no available examination days between the exam start date and end date. Weekends and configured holidays are excluded.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Automatic Paper Time Slots
        |--------------------------------------------------------------------------
        |
        | 1 paper:
        | 10:00 AM
        |
        | 2 papers:
        | 10:00 AM
        | 01:00 PM
        |
        | 3 papers:
        | 10:00 AM
        | 01:00 PM
        | 03:00 PM
        |
        */

        $automaticSlots =
            $this->getAutomaticTimeSlots(
                $papersPerDay
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Subject Durations
        |--------------------------------------------------------------------------
        */

        $durationErrors = [];

        foreach (
            $subjectsByClass
            as $classId => $subjects
        ) {

            foreach (
                $subjects
                as $examSubject
            ) {

                $duration = (int) (
                    $examSubject->duration_minutes
                    ?? 60
                );

                if ($duration <= 0) {
                    $duration = 60;
                }


                foreach (
                    $automaticSlots
                    as $slotIndex => $slot
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Determine Maximum Allowed End Time
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset(
                            $automaticSlots[
                                $slotIndex + 1
                            ]
                        )
                    ) {

                        $maximumEnd =
                            Carbon::createFromFormat(
                                'H:i',
                                $automaticSlots[
                                    $slotIndex + 1
                                ]['start']
                            );

                    } else {

                        $maximumEnd =
                            Carbon::createFromFormat(
                                'H:i',
                                '17:00'
                            );
                    }


                    $slotStart =
                        Carbon::createFromFormat(
                            'H:i',
                            $slot['start']
                        );


                    $calculatedEnd =
                        $slotStart
                            ->copy()
                            ->addMinutes(
                                $duration
                            );


                    if (
                        $calculatedEnd
                            ->gt($maximumEnd)
                    ) {

                        $className =
                            $this->getClassDisplayName(
                                $examSubject
                                    ->schoolClass
                            );

                        $subjectName =
                            $examSubject
                                ->subject
                                ?->subject_name
                            ?? 'Unknown Subject';


                        /*
                        |--------------------------------------------------------------------------
                        | Only report if no slot can fit
                        |--------------------------------------------------------------------------
                        */

                        $hasValidSlot = false;

                        foreach (
                            $automaticSlots
                            as $checkSlot
                        ) {

                            $checkStart =
                                Carbon::createFromFormat(
                                    'H:i',
                                    $checkSlot['start']
                                );

                            $checkEnd =
                                $checkStart
                                    ->copy()
                                    ->addMinutes(
                                        $duration
                                    );

                            /*
                            |--------------------------------------------------------------------------
                            | Determine Maximum End For This Slot
                            |--------------------------------------------------------------------------
                            */

                            if (
                                isset(
                                    $automaticSlots[
                                        array_search(
                                            $checkSlot,
                                            $automaticSlots,
                                            true
                                        ) + 1
                                    ]
                                )
                            ) {

                                $nextIndex =
                                    array_search(
                                        $checkSlot,
                                        $automaticSlots,
                                        true
                                    ) + 1;

                                $checkMaximumEnd =
                                    Carbon::createFromFormat(
                                        'H:i',
                                        $automaticSlots[
                                            $nextIndex
                                        ]['start']
                                    );

                            } else {

                                $checkMaximumEnd =
                                    Carbon::createFromFormat(
                                        'H:i',
                                        '17:00'
                                    );
                            }


                            if (
                                $checkEnd
                                    ->lte(
                                        $checkMaximumEnd
                                    )
                            ) {
                                $hasValidSlot = true;
                                break;
                            }
                        }


                        if (!$hasValidSlot) {

                            $durationErrors[] =
                                "{$className} - {$subjectName} requires {$duration} minutes and cannot fit into any automatic paper slot.";
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Stop After First Suitable Slot
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $calculatedEnd
                            ->lte($maximumEnd)
                    ) {
                        break;
                    }
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Duration Errors
        |--------------------------------------------------------------------------
        */

        if (!empty($durationErrors)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    $durationErrors,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Capacity Validation
        |--------------------------------------------------------------------------
        |
        | Each class gets:
        |
        | available days × papers per day
        |
        */

        $availableDayCount =
            count($availableDates);

        $capacityErrors = [];

        foreach (
            $subjectsByClass
            as $classId => $subjects
        ) {

            $requiredPapers =
                $subjects->count();

            $availableCapacity =
                $availableDayCount *
                $papersPerDay;


            if (
                $requiredPapers >
                $availableCapacity
            ) {

                $examClass =
                    $examClasses->firstWhere(
                        'class_id',
                        $classId
                    );

                $className =
                    $this->getClassDisplayName(
                        $examClass?->schoolClass
                    );


                $capacityErrors[] =
                    "{$className} requires {$requiredPapers} papers, but only {$availableCapacity} paper slots are available between the selected dates.";
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Capacity Errors
        |--------------------------------------------------------------------------
        */

        if (!empty($capacityErrors)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    $capacityErrors,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Build Subject Pointers
        |--------------------------------------------------------------------------
        |
        | Every class has its own pointer.
        |
        */

        $subjectPointers = [];

        foreach ($classIds as $classId) {
            $subjectPointers[$classId] = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Entire Timetable In Memory
        |--------------------------------------------------------------------------
        |
        | DATE
        |   -> SLOT
        |       -> CLASS
        |           -> SUBJECT
        |
        */

        $plannedRows = [];

        foreach (
            $availableDates
            as $examDate
        ) {

            foreach (
                $automaticSlots
                as $slotIndex => $slot
            ) {

                foreach (
                    $classIds
                    as $classId
                ) {

                    $classSubjects =
                        $subjectsByClass
                            ->get($classId);

                    $pointer =
                        $subjectPointers[
                            $classId
                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | Class Already Finished
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !$classSubjects ||
                        $pointer >=
                        $classSubjects->count()
                    ) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Get Next Subject
                    |--------------------------------------------------------------------------
                    */

                    $examSubject =
                        $classSubjects->get(
                            $pointer
                        );

                    if (!$examSubject) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Subject Duration
                    |--------------------------------------------------------------------------
                    */

                    $duration = (int) (
                        $examSubject
                            ->duration_minutes
                        ?? 60
                    );

                    if ($duration <= 0) {
                        $duration = 60;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Automatic Start Time
                    |--------------------------------------------------------------------------
                    */

                    $startTime =
                        Carbon::createFromFormat(
                            'H:i',
                            $slot['start']
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Automatic End Time
                    |--------------------------------------------------------------------------
                    */

                    $endTime =
                        $startTime
                            ->copy()
                            ->addMinutes(
                                $duration
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | Check Against Next Paper
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset(
                            $automaticSlots[
                                $slotIndex + 1
                            ]
                        )
                    ) {

                        $nextSlotStart =
                            Carbon::createFromFormat(
                                'H:i',
                                $automaticSlots[
                                    $slotIndex + 1
                                ]['start']
                            );


                        if (
                            $endTime
                                ->gt($nextSlotStart)
                        ) {

                            $className =
                                $this->getClassDisplayName(
                                    $examSubject
                                        ->schoolClass
                                );

                            $subjectName =
                                $examSubject
                                    ->subject
                                    ?->subject_name
                                ?? 'Unknown Subject';


                            throw ValidationException::withMessages([
                                'papers_per_day' =>
                                    "{$className} - {$subjectName} ({$duration} minutes) overlaps the next automatic paper slot.",
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Last Slot Must Finish By 5 PM
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !isset(
                            $automaticSlots[
                                $slotIndex + 1
                            ]
                        )
                    ) {

                        $latestEnd =
                            Carbon::createFromFormat(
                                'H:i',
                                '17:00'
                            );


                        if (
                            $endTime->gt(
                                $latestEnd
                            )
                        ) {

                            $className =
                                $this->getClassDisplayName(
                                    $examSubject
                                        ->schoolClass
                                );

                            $subjectName =
                                $examSubject
                                    ->subject
                                    ?->subject_name
                                ?? 'Unknown Subject';


                            throw ValidationException::withMessages([
                                'papers_per_day' =>
                                    "{$className} - {$subjectName} cannot fit into the final automatic paper slot because it ends after 5:00 PM.",
                            ]);
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Planned Row
                    |--------------------------------------------------------------------------
                    |
                    | session_id = null
                    | teacher_id = null
                    |
                    */

                    $plannedRows[] = [
                        'exam_id' =>
                            $exam->id,

                        'class_id' =>
                            $classId,

                        'exam_subject_id' =>
                            $examSubject->id,

                        'session_id' =>
                            null,

                        'teacher_id' =>
                            null,

                        'exam_date' =>
                            $examDate->format(
                                'Y-m-d'
                            ),

                        'start_time' =>
                            $startTime->format(
                                'H:i:s'
                            ),

                        'end_time' =>
                            $endTime->format(
                                'H:i:s'
                            ),

                        'maximum_marks' =>
                            (int) (
                                $examSubject
                                    ->maximum_marks
                                ?? 0
                            ),

                        'duration_minutes' =>
                            $duration,

                        'status' =>
                            true,

                        'created_at' =>
                            now(),

                        'updated_at' =>
                            now(),
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | Move Class To Next Subject
                    |--------------------------------------------------------------------------
                    */

                    $subjectPointers[
                        $classId
                    ]++;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Final Completeness Check
        |--------------------------------------------------------------------------
        */

        $remainingErrors = [];

        foreach (
            $subjectsByClass
            as $classId => $subjects
        ) {

            $scheduledCount =
                $subjectPointers[
                    $classId
                ] ?? 0;

            $requiredCount =
                $subjects->count();


            if (
                $scheduledCount <
                $requiredCount
            ) {

                $examClass =
                    $examClasses->firstWhere(
                        'class_id',
                        $classId
                    );

                $className =
                    $this->getClassDisplayName(
                        $examClass?->schoolClass
                    );

                $remaining =
                    $requiredCount -
                    $scheduledCount;


                $remainingErrors[] =
                    "{$className}: {$remaining} subject(s) could not be scheduled.";
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Stop If Any Subject Remains
        |--------------------------------------------------------------------------
        */

        if (!empty($remainingErrors)) {
            throw ValidationException::withMessages([
                'papers_per_day' =>
                    $remainingErrors,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Sort Planned Timetable
        |--------------------------------------------------------------------------
        */

        usort(
            $plannedRows,
            function ($a, $b) {

                $dateCompare =
                    strcmp(
                        $a['exam_date'],
                        $b['exam_date']
                    );

                if ($dateCompare !== 0) {
                    return $dateCompare;
                }


                $timeCompare =
                    strcmp(
                        $a['start_time'],
                        $b['start_time']
                    );

                if ($timeCompare !== 0) {
                    return $timeCompare;
                }


                return
                    $a['class_id']
                    <=>
                    $b['class_id'];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Save Everything In One Transaction
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(
                function () use (
                    $exam,
                    $plannedRows,
                    $replaceExisting
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Delete Existing Timetable If Requested
                    |--------------------------------------------------------------------------
                    */

                    if ($replaceExisting) {

                        ExamTimetable::where(
                            'exam_id',
                            $exam->id
                        )->delete();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Insert New Timetable
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        array_chunk(
                            $plannedRows,
                            500
                        )
                        as $chunk
                    ) {

                        ExamTimetable::insert(
                            $chunk
                        );
                    }
                }
            );

        } catch (Throwable $e) {

            report($e);

            throw ValidationException::withMessages([
                'papers_per_day' =>
                    'The examination timetable could not be generated. '
                    . 'No timetable changes were saved. '
                    . $e->getMessage(),
            ]);
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
                'Examination timetable generated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOMATIC TIME SLOTS
    |--------------------------------------------------------------------------
    */

    private function getAutomaticTimeSlots(
        int $papersPerDay
    ): array {

        return match ($papersPerDay) {

            1 => [
                [
                    'start' => '10:00',
                ],
            ],

            2 => [
                [
                    'start' => '10:00',
                ],
                [
                    'start' => '13:00',
                ],
            ],

            3 => [
                [
                    'start' => '10:00',
                ],
                [
                    'start' => '13:00',
                ],
                [
                    'start' => '15:00',
                ],
            ],

            default => throw ValidationException::withMessages([
                'papers_per_day' =>
                    'Papers per day must be 1, 2, or 3.',
            ]),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | CLASS DISPLAY NAME
    |--------------------------------------------------------------------------
    */

    private function getClassDisplayName(
        $schoolClass
    ): string {

        if (!$schoolClass) {
            return 'Unknown Class';
        }


        $className =
            $schoolClass->class_name
            ?? $schoolClass->name
            ?? 'Unknown Class';


        $section =
            $schoolClass->section
            ?? null;


        if ($section) {
            return
                $className .
                ' - ' .
                $section;
        }


        return $className;
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function edit(
        Exam $exam,
        ExamTimetable $schedule
    ) {

        abort_unless(
            $schedule->exam_id == $exam->id,
            404
        );


        $schedule->load([
            'schoolClass',
            'examSubject.subject',
            'teacher',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Teachers Are Only Used During Manual Editing
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::where(function ($query) {
            $query->whereNull('status')
                ->orWhere('status', 1);
        })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();


        return view(
            'admin.exams.exam-schedules.edit',
            compact(
                'exam',
                'schedule',
                'teachers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TIMETABLE
    |--------------------------------------------------------------------------
    |
    | Manual editing can assign a teacher.
    |
    */

    public function update(
        Request $request,
        Exam $exam,
        ExamTimetable $schedule
    ) {

        abort_unless(
            $schedule->exam_id == $exam->id,
            404
        );


        $validated = $request->validate([
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

            'teacher_id' => [
                'nullable',
                'exists:teachers,id',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Exam Period Validation
        |--------------------------------------------------------------------------
        */

        $examStartDate =
            Carbon::parse(
                $exam->start_date
            )->startOfDay();


        $examEndDate =
            Carbon::parse(
                $exam->end_date
            )->startOfDay();


        $scheduleDate =
            Carbon::parse(
                $validated['exam_date']
            )->startOfDay();


        if (
            $scheduleDate->lt(
                $examStartDate
            ) ||
            $scheduleDate->gt(
                $examEndDate
            )
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'The timetable date must be within the examination start and end dates.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Weekend Scheduling
        |--------------------------------------------------------------------------
        */

        if (
            $scheduleDate->isSaturday() ||
            $scheduleDate->isSunday()
        ) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'Examination papers cannot be scheduled on Saturday or Sunday.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Holiday Scheduling
        |--------------------------------------------------------------------------
        */

        $isHoliday =
            ExamHoliday::where(
                'exam_id',
                $exam->id
            )
                ->whereDate(
                    'holiday_date',
                    $scheduleDate->format(
                        'Y-m-d'
                    )
                )
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', 1);
                })
                ->exists();


        if ($isHoliday) {

            throw ValidationException::withMessages([
                'exam_date' =>
                    'The selected date is configured as an examination holiday.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Check Same-Class Time Conflict
        |--------------------------------------------------------------------------
        */

        $classConflict =
            ExamTimetable::where(
                'exam_id',
                $exam->id
            )
                ->where(
                    'class_id',
                    $schedule->class_id
                )
                ->where(
                    'id',
                    '!=',
                    $schedule->id
                )
                ->whereDate(
                    'exam_date',
                    $validated['exam_date']
                )
                ->where(function ($query) use (
                    $validated
                ) {

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

            throw ValidationException::withMessages([
                'start_time' =>
                    'This class already has another examination paper during the selected time.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Duration
        |--------------------------------------------------------------------------
        */

        $start =
            Carbon::createFromFormat(
                'H:i',
                $validated['start_time']
            );

        $end =
            Carbon::createFromFormat(
                'H:i',
                $validated['end_time']
            );


        $duration =
            $start->diffInMinutes(
                $end
            );


        /*
        |--------------------------------------------------------------------------
        | Update Timetable
        |--------------------------------------------------------------------------
        */

        $schedule->update([
            'exam_date' =>
                $validated['exam_date'],

            'start_time' =>
                $validated['start_time'],

            'end_time' =>
                $validated['end_time'],

            'teacher_id' =>
                $validated['teacher_id']
                ?? null,

            'duration_minutes' =>
                $duration,

            'status' =>
                array_key_exists(
                    'status',
                    $validated
                )
                    ? (bool) $validated['status']
                    : $schedule->status,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam
            )
            ->with(
                'success',
                'Examination timetable updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Exam $exam,
        ExamTimetable $schedule
    ) {

        abort_unless(
            $schedule->exam_id == $exam->id,
            404
        );


        $schedule->delete();


        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam
            )
            ->with(
                'success',
                'Examination timetable deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function print(
        Exam $exam
    ) {

        $timetables =
            ExamTimetable::with([
                'schoolClass',
                'examSubject.subject',
                'teacher',
            ])
                ->where(
                    'exam_id',
                    $exam->id
                )
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


        return view(
            'admin.exams.exam-schedules.print',
            compact(
                'exam',
                'timetables',
                'school',
                'logoData'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function pdf(
        Exam $exam
    ) {

        $timetables =
            ExamTimetable::with([
                'schoolClass',
                'examSubject.subject',
                'teacher',
            ])
                ->where(
                    'exam_id',
                    $exam->id
                )
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


        $pdf =
            Pdf::loadView(
                'admin.exams.exam-schedules.print',
                compact(
                    'exam',
                    'timetables',
                    'school',
                    'logoData'
                )
            );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'exam-timetable-' .
            $exam->id .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCHOOL LOGO DATA
    |--------------------------------------------------------------------------
    */

    private function getSchoolLogoData(
        ?SchoolSetting $school
    ): ?string {

        if (
            !$school ||
            !$school->logo
        ) {
            return null;
        }


        $logo =
            $school->logo;


        /*
        |--------------------------------------------------------------------------
        | Already Base64
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $logo,
                'data:image'
            )
        ) {
            return $logo;
        }


        /*
        |--------------------------------------------------------------------------
        | URL / Cloudinary
        |--------------------------------------------------------------------------
        */

        if (
            filter_var(
                $logo,
                FILTER_VALIDATE_URL
            )
        ) {

            try {

                $imageContents =
                    @file_get_contents(
                        $logo
                    );


                if (
                    $imageContents !== false
                ) {

                    $extension =
                        pathinfo(
                            parse_url(
                                $logo,
                                PHP_URL_PATH
                            ),
                            PATHINFO_EXTENSION
                        );


                    $extension =
                        strtolower(
                            $extension
                        );


                    $mimeType = match (
                        $extension
                    ) {

                        'jpg',
                        'jpeg' =>
                            'image/jpeg',

                        'png' =>
                            'image/png',

                        'gif' =>
                            'image/gif',

                        'webp' =>
                            'image/webp',

                        default =>
                            'image/png',
                    };


                    return
                        'data:' .
                        $mimeType .
                        ';base64,' .
                        base64_encode(
                            $imageContents
                        );
                }

            } catch (Throwable $e) {

                report($e);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Local Storage
        |--------------------------------------------------------------------------
        */

        try {

            $possiblePaths = [

                storage_path(
                    'app/public/' .
                    ltrim(
                        $logo,
                        '/'
                    )
                ),

                public_path(
                    ltrim(
                        $logo,
                        '/'
                    )
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

                    $extension =
                        strtolower(
                            pathinfo(
                                $path,
                                PATHINFO_EXTENSION
                            )
                        );


                    $mimeType = match (
                        $extension
                    ) {

                        'jpg',
                        'jpeg' =>
                            'image/jpeg',

                        'png' =>
                            'image/png',

                        'gif' =>
                            'image/gif',

                        'webp' =>
                            'image/webp',

                        default =>
                            'image/png',
                    };


                    return
                        'data:' .
                        $mimeType .
                        ';base64,' .
                        base64_encode(
                            file_get_contents(
                                $path
                            )
                        );
                }
            }

        } catch (Throwable $e) {

            report($e);
        }


        return null;
    }
}