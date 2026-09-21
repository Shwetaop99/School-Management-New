<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamClassSection;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Display all schedules for a particular examination.
    |
    */

public function index(Exam $exam)
{
    /*
    |--------------------------------------------------------------------------
    | Get Exam Classes
    |--------------------------------------------------------------------------
    */

    $examClasses = $exam->examClasses()
        ->where('status', 'active')
        ->with([
            'sections' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('sort_order')
                    ->orderBy('section_name');
            }
        ])
        ->orderBy('sort_order')
        ->orderBy('class_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Get Exam Schedules
    |--------------------------------------------------------------------------
    */

    $schedules = ExamSchedule::with([
            'examClass',
            'examClassSection',
        ])
        ->where('exam_id', $exam->id)
        ->orderBy('exam_date')
        ->orderBy('start_time')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.exam-schedules.index',
        compact(
            'exam',
            'examClasses',
            'schedules'
        )
    );
}



    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Show create schedule form.
    |
    */

    public function create(Exam $exam)
    {
        /*
        |--------------------------------------------------------------------------
        | Get classes assigned to this exam
        |--------------------------------------------------------------------------
        */

        $examClasses = $exam->examClasses()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('class_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Get sections for all exam classes
        |--------------------------------------------------------------------------
        |
        | Used by the Create page when class is selected.
        |
        */

        $sections = ExamClassSection::whereIn(
                'exam_class_id',
                $examClasses->pluck('id')
            )
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('section_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        |
        | Subjects are currently skipped.
        |
        | Later, when the Subject/Class module is completed,
        | this section can be connected to the existing subject
        | assignment system.
        |
        */

        $subjects = collect();


        return view(
            'admin.exam-schedules.create',
            compact(
                'exam',
                'examClasses',
                'sections',
                'subjects'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Save a new examination schedule.
    |
    */

    public function store(
        Request $request,
        Exam $exam
    ) {
        $validated = $request->validate([

            'exam_class_id' => [
                'required',
                'integer',
                'exists:exam_classes,id',
            ],

            'exam_class_section_id' => [
                'nullable',
                'integer',
                'exists:exam_class_sections,id',
            ],

            'subject_id' => [
                'nullable',
                'integer',
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

            'max_marks' => [
                'required',
                'integer',
                'min:1',
            ],

            'pass_marks' => [
                'required',
                'integer',
                'min:0',
                'lte:max_marks',
            ],

            'room_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'instructions' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:scheduled,completed,cancelled',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Exam Class belongs to this Exam
        |--------------------------------------------------------------------------
        */

        $examClass = ExamClass::where('id', $validated['exam_class_id'])
            ->where('exam_id', $exam->id)
            ->first();

        if (!$examClass) {

            return back()
                ->withInput()
                ->withErrors([
                    'exam_class_id' =>
                        'The selected class does not belong to this examination.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Section belongs to selected Exam Class
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['exam_class_section_id'])) {

            $section = ExamClassSection::where(
                    'id',
                    $validated['exam_class_section_id']
                )
                ->where(
                    'exam_class_id',
                    $examClass->id
                )
                ->first();

            if (!$section) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'exam_class_section_id' =>
                            'The selected section does not belong to the selected class.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Subject
        |--------------------------------------------------------------------------
        |
        | Subject module is currently skipped.
        | Therefore subject_id remains nullable.
        |
        */


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate schedule
        |--------------------------------------------------------------------------
        |
        | Same exam + class + section + date + time should not be
        | accidentally scheduled twice.
        |
        */

        $duplicate = ExamSchedule::where(
                'exam_id',
                $exam->id
            )
            ->where(
                'exam_class_id',
                $examClass->id
            )
            ->where(
                'exam_class_section_id',
                $validated['exam_class_section_id'] ?? null
            )
            ->where(
                'exam_date',
                $validated['exam_date']
            )
            ->where(
                'start_time',
                $validated['start_time']
            )
            ->exists();


        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'exam_date' =>
                        'A schedule already exists for this class, section, date and start time.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Schedule
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $exam,
            $examClass
        ) {

            ExamSchedule::create([

                'exam_id' =>
                    $exam->id,

                'exam_class_id' =>
                    $examClass->id,

                'exam_class_section_id' =>
                    $validated['exam_class_section_id'] ?? null,

                'subject_id' =>
                    $validated['subject_id'] ?? null,

                'exam_date' =>
                    $validated['exam_date'],

                'start_time' =>
                    $validated['start_time'],

                'end_time' =>
                    $validated['end_time'],

                'max_marks' =>
                    $validated['max_marks'],

                'pass_marks' =>
                    $validated['pass_marks'],

                'room_no' =>
                    $validated['room_no'] ?? null,

                'instructions' =>
                    $validated['instructions'] ?? null,

                'status' =>
                    $validated['status'],

            ]);
        });


        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam->id
            )
            ->with(
                'success',
                'Exam schedule created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Show edit schedule form.
    |
    */

    public function edit(
        Exam $exam,
        ExamSchedule $schedule
    ) {

        /*
        |--------------------------------------------------------------------------
        | Verify schedule belongs to exam
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $examClasses = $exam->examClasses()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('class_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = ExamClassSection::whereIn(
                'exam_class_id',
                $examClasses->pluck('id')
            )
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('section_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        |
        | Currently skipped.
        |
        */

        $subjects = collect();


        return view(
            'admin.exam-schedules.edit',
            compact(
                'exam',
                'schedule',
                'examClasses',
                'sections',
                'subjects'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Update an existing schedule.
    |
    */

    public function update(
        Request $request,
        Exam $exam,
        ExamSchedule $schedule
    ) {

        /*
        |--------------------------------------------------------------------------
        | Verify schedule belongs to exam
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'exam_class_id' => [
                'required',
                'integer',
                'exists:exam_classes,id',
            ],

            'exam_class_section_id' => [
                'nullable',
                'integer',
                'exists:exam_class_sections,id',
            ],

            'subject_id' => [
                'nullable',
                'integer',
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

            'max_marks' => [
                'required',
                'integer',
                'min:1',
            ],

            'pass_marks' => [
                'required',
                'integer',
                'min:0',
                'lte:max_marks',
            ],

            'room_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'instructions' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:scheduled,completed,cancelled',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Class belongs to Exam
        |--------------------------------------------------------------------------
        */

        $examClass = ExamClass::where(
                'id',
                $validated['exam_class_id']
            )
            ->where(
                'exam_id',
                $exam->id
            )
            ->first();

        if (!$examClass) {

            return back()
                ->withInput()
                ->withErrors([
                    'exam_class_id' =>
                        'The selected class does not belong to this examination.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Section
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['exam_class_section_id'])) {

            $section = ExamClassSection::where(
                    'id',
                    $validated['exam_class_section_id']
                )
                ->where(
                    'exam_class_id',
                    $examClass->id
                )
                ->first();

            if (!$section) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'exam_class_section_id' =>
                            'The selected section does not belong to the selected class.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Schedule
        |--------------------------------------------------------------------------
        */

        $duplicate = ExamSchedule::where(
                'exam_id',
                $exam->id
            )
            ->where(
                'exam_class_id',
                $examClass->id
            )
            ->where(
                'exam_class_section_id',
                $validated['exam_class_section_id'] ?? null
            )
            ->where(
                'exam_date',
                $validated['exam_date']
            )
            ->where(
                'start_time',
                $validated['start_time']
            )
            ->where(
                'id',
                '!=',
                $schedule->id
            )
            ->exists();


        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'exam_date' =>
                        'Another schedule already exists for this class, section, date and start time.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Schedule
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $schedule,
            $examClass
        ) {

            $schedule->update([

                'exam_class_id' =>
                    $examClass->id,

                'exam_class_section_id' =>
                    $validated['exam_class_section_id'] ?? null,

                'subject_id' =>
                    $validated['subject_id'] ?? null,

                'exam_date' =>
                    $validated['exam_date'],

                'start_time' =>
                    $validated['start_time'],

                'end_time' =>
                    $validated['end_time'],

                'max_marks' =>
                    $validated['max_marks'],

                'pass_marks' =>
                    $validated['pass_marks'],

                'room_no' =>
                    $validated['room_no'] ?? null,

                'instructions' =>
                    $validated['instructions'] ?? null,

                'status' =>
                    $validated['status'],

            ]);
        });


        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam->id
            )
            ->with(
                'success',
                'Exam schedule updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Delete schedule.
    |
    */

    public function destroy(
        Exam $exam,
        ExamSchedule $schedule
    ) {

        /*
        |--------------------------------------------------------------------------
        | Verify schedule belongs to exam
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );


        $schedule->delete();


        return redirect()
            ->route(
                'admin.exam-schedules.index',
                $exam->id
            )
            ->with(
                'success',
                'Exam schedule deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    |
    | Print one schedule.
    |
    */

    public function print(
        Exam $exam,
        ExamSchedule $schedule
    ) {

        /*
        |--------------------------------------------------------------------------
        | Verify schedule belongs to exam
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $schedule->exam_id === $exam->id,
            404
        );


        $schedule->load([
            'examClass',
            'examClassSection',
        ]);


        return view(
            'admin.exam-schedules.print',
            compact(
                'exam',
                'schedule'
            )
        );
    }
}
