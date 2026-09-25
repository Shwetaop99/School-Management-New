<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamSubject;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSubjectController extends Controller
{
    /**
     * Show subjects for all classes selected for this exam.
     */
    public function index(Exam $exam)
    {
        $examClasses = ExamClass::with([
                'schoolClass.subjects' => function ($query) {
                    $query->where('status', true)
                        ->orderBy('subject_name');
                },
            ])
            ->where('exam_id', $exam->id)
            ->get();

        $examSubjects = ExamSubject::where('exam_id', $exam->id)
            ->get()
            ->keyBy(function ($item) {
                return $item->class_id . '_' . $item->subject_id;
            });

        return view(
            'admin.exams.subjects.index',
            compact(
                'exam',
                'examClasses',
                'examSubjects'
            )
        );
    }

    /**
     * Save subjects, marks and duration.
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'subjects' => [
                'required',
                'array',
                'min:1',
            ],

            'subjects.*.class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],

            'subjects.*.subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

            'subjects.*.maximum_marks' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],

            'subjects.*.passing_marks' => [
                'required',
                'integer',
                'min:0',
                'max:1000',
            ],

            'subjects.*.duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:600',
            ],

            'subjects.*.status' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get classes actually selected for this exam
        |--------------------------------------------------------------------------
        */

        $selectedClassIds = $exam->examClasses()
            ->pluck('class_id')
            ->toArray();

        if (empty($selectedClassIds)) {
            return back()
                ->withErrors([
                    'subjects' =>
                        'Please select at least one class for this exam first.',
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $validated,
            $exam,
            $selectedClassIds
        ) {
            foreach ($validated['subjects'] as $subjectData) {

                $classId = (int) $subjectData['class_id'];
                $subjectId = (int) $subjectData['subject_id'];

                /*
                |--------------------------------------------------------------------------
                | Make sure class belongs to this exam
                |--------------------------------------------------------------------------
                */

                if (!in_array($classId, $selectedClassIds, true)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Make sure subject actually belongs to selected class
                |--------------------------------------------------------------------------
                */

                $subjectExists = DB::table('subjects')
                    ->where('id', $subjectId)
                    ->where('class_id', $classId)
                    ->where('status', true)
                    ->exists();

                if (!$subjectExists) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Passing marks cannot be greater than maximum marks
                |--------------------------------------------------------------------------
                */

                $maximumMarks = (int) $subjectData['maximum_marks'];
                $passingMarks = (int) $subjectData['passing_marks'];

                if ($passingMarks > $maximumMarks) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Save / update exam subject configuration
                |--------------------------------------------------------------------------
                */

                ExamSubject::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'class_id' => $classId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'maximum_marks' => $maximumMarks,
                        'passing_marks' => $passingMarks,
                        'duration_minutes' => (int) $subjectData['duration_minutes'],
                        'status' => isset($subjectData['status'])
                            ? (bool) $subjectData['status']
                            : true,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with(
                'success',
                'Exam subjects, marks and durations saved successfully.'
            );
    }
}