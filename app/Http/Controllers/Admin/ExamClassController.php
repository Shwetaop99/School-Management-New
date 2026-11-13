<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;

class ExamClassController extends Controller
{
    /**
     * Display classes available for this exam.
     */
    public function index(Exam $exam)
    {
        $classes = SchoolClass::where('status', true)
            ->where('academic_year', $exam->academic_year)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        $selectedClassIds = $exam->examClasses()
            ->pluck('class_id')
            ->toArray();

        return view(
            'admin.exams.classes.index',
            compact(
                'exam',
                'classes',
                'selectedClassIds'
            )
        );
    }

    /**
     * Save selected classes for the exam.
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'class_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'class_ids.*' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
        ]);

        $classIds = SchoolClass::whereIn(
                'id',
                $validated['class_ids']
            )
            ->where('status', true)
            ->where('academic_year', $exam->academic_year)
            ->pluck('id')
            ->toArray();

        if (count($classIds) !== count($validated['class_ids'])) {
            return back()
                ->withErrors([
                    'class_ids' =>
                        'One or more selected classes are invalid for this academic year.',
                ])
                ->withInput();
        }

        $exam->examClasses()->delete();

        foreach ($classIds as $classId) {
            ExamClass::create([
                'exam_id' => $exam->id,
                'class_id' => $classId,
            ]);
        }

        return redirect()
            ->route(
                'admin.exams.show',
                $exam
            )
            ->with(
                'success',
                'Exam classes saved successfully.'
            );
    }
}