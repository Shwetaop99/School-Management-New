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

    public function generate(Request $request, Exam $exam)
    {
        $request->validate(['replace_existing' => ['nullable', 'boolean']]);
        $replaceExisting = $request->boolean('replace_existing');

        if (!$exam->start_date || !$exam->end_date) {
            throw ValidationException::withMessages(['replace_existing' => 'Configure the exam start and end dates first.']);
        }
        $startDate = Carbon::parse($exam->start_date)->startOfDay();
        $endDate = Carbon::parse($exam->end_date)->startOfDay();
        if ($startDate->gt($endDate)) {
            throw ValidationException::withMessages(['replace_existing' => 'Exam start date cannot be after exam end date.']);
        }

        $existingCount = ExamTimetable::where('exam_id', $exam->id)->count();
        if ($existingCount && !$replaceExisting) {
            throw ValidationException::withMessages(['replace_existing' => "This exam already has {$existingCount} timetable entries. Select Replace Existing Timetable to regenerate it."]);
        }

        $examClasses = ExamClass::with('schoolClass')->where('exam_id', $exam->id)->orderBy('class_id')->get();
        if ($examClasses->isEmpty()) {
            throw ValidationException::withMessages(['replace_existing' => 'No classes have been added to this examination.']);
        }
        $classIds = $examClasses->pluck('class_id')->filter()->unique()->values();
        $subjectsByClass = ExamSubject::with(['schoolClass', 'subject'])
            ->where('exam_id', $exam->id)->whereIn('class_id', $classIds)
            ->where(fn ($q) => $q->whereNull('status')->orWhere('status', 1))
            ->orderBy('class_id')->orderBy('id')->get()
            ->groupBy('class_id')->map(fn (Collection $items) => $items->sortBy('id')->values());

        $missingClasses = [];
        foreach ($classIds as $classId) {
            if ($subjectsByClass->get($classId, collect())->isEmpty()) {
                $missingClasses[] = $this->getClassDisplayName($examClasses->firstWhere('class_id', $classId)?->schoolClass);
            }
        }
        if ($missingClasses) {
            throw ValidationException::withMessages(['replace_existing' => 'No active exam subjects configured for: ' . implode(', ', $missingClasses) . '.']);
        }

        $examType = strtolower(str_replace(['-', '_'], ' ', trim(($exam->exam_type ?? '') . ' ' . ($exam->exam_name ?? ''))));
        $examType = preg_replace('/\s+/', ' ', $examType);
        if (str_contains($examType, 'unit test') || preg_match('/\but\b/', $examType)) {
            $maxDays = 3;
        } elseif (str_contains($examType, 'mid sem') || str_contains($examType, 'midsemester') || str_contains($examType, 'mid term') || str_contains($examType, 'midterm')) {
            $maxDays = 7;
        } else {
            $maxDays = 10;
        }

        $holidays = ExamHoliday::where('exam_id', $exam->id)
            ->where(fn ($q) => $q->whereNull('status')->orWhere('status', 1))
            ->pluck('holiday_date')->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))->unique()->all();
        $dates = [];
        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $date = $cursor->format('Y-m-d');
            if (!$cursor->isWeekend() && !in_array($date, $holidays, true)) {
                $dates[] = $cursor->copy();
            }
            $cursor->addDay();
        }
        $dates = array_slice($dates, 0, $maxDays);
        if (!$dates) {
            throw ValidationException::withMessages(['replace_existing' => 'No usable examination dates found. Weekends and configured holidays are skipped.']);
        }

        $dayCount = count($dates);
        $dailyCounts = [];
        $capacityErrors = [];
        foreach ($subjectsByClass as $classId => $subjects) {
            $count = $subjects->count();
            $counts = array_fill(0, $dayCount, 0);
            if ($count <= $dayCount) {
                for ($i = 0; $i < $count; $i++) $counts[$i] = 1;
            } else {
                $base = intdiv($count, $dayCount);
                $extra = $count % $dayCount;
                for ($i = 0; $i < $dayCount; $i++) $counts[$i] = $base + ($i >= $dayCount - $extra ? 1 : 0);
            }
            if (max($counts) > 3) {
                $name = $this->getClassDisplayName($examClasses->firstWhere('class_id', $classId)?->schoolClass);
                $capacityErrors[] = "{$name} has {$count} subjects but the selected exam period allows no more than 3 papers per day.";
            }
            $dailyCounts[$classId] = $counts;
        }
        if ($capacityErrors) throw ValidationException::withMessages(['replace_existing' => $capacityErrors]);

        $planned = [];
        $durationErrors = [];
        foreach ($dates as $dayIndex => $date) {
            foreach ($classIds as $classId) {
                $subjects = $subjectsByClass->get($classId, collect());
                $papersToday = $dailyCounts[$classId][$dayIndex] ?? 0;
                $subjectIndex = array_sum(array_slice($dailyCounts[$classId], 0, $dayIndex));
                $time = Carbon::createFromFormat('H:i', '10:00');
                for ($i = 0; $i < $papersToday; $i++, $subjectIndex++) {
                    $subject = $subjects->get($subjectIndex);
                    if (!$subject) continue;
                    $duration = (int) ($subject->duration_minutes ?? 60);
                    if ($duration <= 0) $duration = 60;
                    $end = $time->copy()->addMinutes($duration);
                    if ($end->gt(Carbon::createFromFormat('H:i', '17:00'))) {
                        $className = $this->getClassDisplayName($subject->schoolClass);
                        $subjectName = $subject->subject?->subject_name ?? 'Unknown Subject';
                        $durationErrors[] = "{$className} - {$subjectName} ({$duration} minutes) does not fit before 5:00 PM on {$date->format('d M Y')}. Extend the exam period or adjust the duration.";
                        break;
                    }
                    $planned[] = [
                        'exam_id' => $exam->id,
                        'class_id' => $classId,
                        'exam_subject_id' => $subject->id,
                        'session_id' => null,
                        'teacher_id' => null,
                        'exam_date' => $date->format('Y-m-d'),
                        'start_time' => $time->format('H:i:s'),
                        'end_time' => $end->format('H:i:s'),
                        'maximum_marks' => (int) ($subject->maximum_marks ?? 0),
                        'duration_minutes' => $duration,
                        'status' => '1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // Keep a 30-minute break between papers for the same class.
                    $time = $end->copy()->addMinutes(30);
                }
            }
        }
        if ($durationErrors) throw ValidationException::withMessages(['replace_existing' => array_values(array_unique($durationErrors))]);

        $scheduledCounts = collect($planned)->countBy('class_id');
        $incomplete = [];
        foreach ($subjectsByClass as $classId => $subjects) {
            if ((int) ($scheduledCounts[$classId] ?? 0) !== $subjects->count()) {
                $name = $this->getClassDisplayName($examClasses->firstWhere('class_id', $classId)?->schoolClass);
                $incomplete[] = "{$name}: not all configured subjects could be scheduled.";
            }
        }
        if ($incomplete) throw ValidationException::withMessages(['replace_existing' => $incomplete]);

        usort($planned, fn ($a, $b) => strcmp($a['exam_date'], $b['exam_date']) ?: strcmp($a['start_time'], $b['start_time']) ?: ($a['class_id'] <=> $b['class_id']));
        try {
            DB::transaction(function () use ($exam, $planned, $replaceExisting) {
                if ($replaceExisting) ExamTimetable::where('exam_id', $exam->id)->delete();
                foreach (array_chunk($planned, 500) as $chunk) ExamTimetable::insert($chunk);
            });
        } catch (Throwable $e) {
            report($e);
            throw ValidationException::withMessages(['replace_existing' => 'Timetable generation failed and no changes were saved. Check the application log for details.']);
        }

        return redirect()->route('admin.exam-schedules.index', $exam)
            ->with('success', 'Examination timetable generated successfully using class-specific subject counts and saved paper durations.');
    }


  private function getAutomaticTimeSlots(
    int $papersPerDay
): array {
    return match ($papersPerDay) {
        1 => [
            ['start' => '10:00'],
        ],

        2 => [
            ['start' => '10:00'],
            ['start' => '13:00'],
        ],

        3 => [
            ['start' => '10:00'],
            ['start' => '12:00'],
            ['start' => '14:00'],
        ],

        default => throw ValidationException::withMessages([
            'papers_per_day' => 'Papers per day must be 1, 2, or 3.',
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

$schoolSetting = $school;

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
        'schoolSetting',
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
    'schoolSetting',
    'logoData'
)
            );


        $pdf->setPaper(
            'A4',
            'portrait'
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

/*
|--------------------------------------------------------------------------
| BULK PRINT TIMETABLE
|--------------------------------------------------------------------------
*/

public function bulkPrint(Request $request, Exam $exam)
{
    [$timetables, $school, $logoData] =
        $this->getBulkTimetableData($request, $exam);

    // Normalize the result into individual timetable records.
    $rows = collect($timetables)->flatMap(function ($item) {
        return $item instanceof \Illuminate\Support\Collection
            ? $item
            : [$item];
    });

    // Sort and group by examination date.
    $timetables = $rows
        ->sortBy(function ($row) {
            return $row->exam_date;
        })
        ->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row->exam_date)
                ->format('Y-m-d');
        });

    return view(
        'admin.exams.exam-schedules.bulk-print',
        [
            'exam' => $exam,
            'timetables' => $timetables,
            'school' => $school,
            'logoData' => $logoData,
            'printedDate' => now()->format('d/m/Y h:i A'),
            'isPdf' => false,
        ]
    );
}


/*
|--------------------------------------------------------------------------
| BULK PDF TIMETABLE
|--------------------------------------------------------------------------
*/

public function bulkPdf(Request $request, Exam $exam)
{
    [$timetables, $school, $logoData] =
        $this->getBulkTimetableData($request, $exam);

    // Normalize the result into individual timetable records.
    $rows = collect($timetables)->flatMap(function ($item) {
        return $item instanceof \Illuminate\Support\Collection
            ? $item
            : [$item];
    });

    // Sort and group by examination date.
    $timetables = $rows
        ->sortBy(function ($row) {
            return $row->exam_date;
        })
        ->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row->exam_date)
                ->format('Y-m-d');
        });

    $pdf = Pdf::loadView(
        'admin.exams.exam-schedules.bulk-print',
        [
            'exam' => $exam,
            'timetables' => $timetables,
            'school' => $school,
            'logoData' => $logoData,
            'printedDate' => now()->format('d/m/Y h:i A'),
            'isPdf' => true,
        ]
    )->setPaper('a4', 'portrait');

    return $pdf->download(
        'bulk-exam-timetable-' . $exam->id . '.pdf'
    );
}

private function getBulkTimetableData(Request $request, Exam $exam): array
{
    $validated = $request->validate([
        'class_ids' => ['sometimes', 'array'],
        'class_ids.*' => ['integer'],
    ]);

    $query = ExamTimetable::with(['schoolClass', 'examSubject.subject', 'teacher'])
        ->where('exam_id', $exam->id);

    if (!empty($validated['class_ids'])) {
        $query->whereIn('class_id', $validated['class_ids']);
    }

    $rows = $query->orderBy('exam_date')
        ->orderBy('start_time')
        ->orderBy('class_id')
        ->get();

    abort_if($rows->isEmpty(), 404, 'No saved timetable entries were found for this examination and class selection.');

    $school = SchoolSetting::first();
    $logoData = $this->getSchoolLogoData($school);

    return [$rows->groupBy(fn ($row) => \Carbon\Carbon::parse($row->exam_date)->format('Y-m-d')), $school, $logoData];
}
}