<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamClassSection;
use App\Models\ExamSchedule;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class ExamTimetableController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL CLASS TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Exam $exam
    ) {
        /*
        |--------------------------------------------------------------------------
        | Get All Active Exam Classes
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
        | Selected Class / Section Filters
        |--------------------------------------------------------------------------
        */

        $selectedClass = null;

        if ($request->filled('exam_class_id')) {

            $selectedClass = ExamClass::where(
                    'id',
                    $request->exam_class_id
                )
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->first();
        }


        $selectedSection = null;

        if (
            $selectedClass &&
            $request->filled('exam_class_section_id')
        ) {

            $selectedSection = ExamClassSection::where(
                    'id',
                    $request->exam_class_section_id
                )
                ->where(
                    'exam_class_id',
                    $selectedClass->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Get Timetable Schedules
        |--------------------------------------------------------------------------
        |
        | No class selected:
        |     → Show ALL classes
        |
        | Class selected:
        |     → Show selected class
        |
        | Class + section selected:
        |     → Show selected section
        |
        */

        $query = ExamSchedule::with([
                'examClass',
                'examClassSection',
            ])
            ->where(
                'exam_id',
                $exam->id
            );


        /*
        |--------------------------------------------------------------------------
        | Filter By Class
        |--------------------------------------------------------------------------
        */

        if ($selectedClass) {

            $query->where(
                'exam_class_id',
                $selectedClass->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter By Section
        |--------------------------------------------------------------------------
        */

        if ($selectedSection) {

            $query->where(
                'exam_class_section_id',
                $selectedSection->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Schedules
        |--------------------------------------------------------------------------
        */

        $schedules = $query
            ->orderBy(
                'exam_date'
            )
            ->orderBy(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.exam-timetable.index',
            compact(
                'exam',
                'examClasses',
                'selectedClass',
                'selectedSection',
                'schedules'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function print(
        Request $request,
        Exam $exam
    ) {
        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Selected Class
        |--------------------------------------------------------------------------
        */

        $selectedClass = null;

        if ($request->filled('exam_class_id')) {

            $selectedClass = ExamClass::where(
                    'id',
                    $request->exam_class_id
                )
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Section
        |--------------------------------------------------------------------------
        */

        $selectedSection = null;

        if (
            $selectedClass &&
            $request->filled('exam_class_section_id')
        ) {

            $selectedSection = ExamClassSection::where(
                    'id',
                    $request->exam_class_section_id
                )
                ->where(
                    'exam_class_id',
                    $selectedClass->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Get Schedules
        |--------------------------------------------------------------------------
        |
        | No class selected:
        |     → Print ALL classes
        |
        | Class selected:
        |     → Print selected class
        |
        | Class + section:
        |     → Print selected section
        |
        */

        $query = ExamSchedule::with([
                'examClass',
                'examClassSection',
            ])
            ->where(
                'exam_id',
                $exam->id
            );


        /*
        |--------------------------------------------------------------------------
        | Class Filter
        |--------------------------------------------------------------------------
        */

        if ($selectedClass) {

            $query->where(
                'exam_class_id',
                $selectedClass->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Section Filter
        |--------------------------------------------------------------------------
        */

        if ($selectedSection) {

            $query->where(
                'exam_class_section_id',
                $selectedSection->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Schedules
        |--------------------------------------------------------------------------
        */

        $schedules = $query
            ->orderBy(
                'exam_class_id'
            )
            ->orderBy(
                'exam_class_section_id'
            )
            ->orderBy(
                'exam_date'
            )
            ->orderBy(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Print View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.exam-timetable.print',
            compact(
                'exam',
                'school',
                'selectedClass',
                'selectedSection',
                'schedules'
            )
        );
    }
}
