<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamClassController extends Controller
{
    /**
     * Display all classes assigned to an exam.
     */
    public function index(Exam $exam)
    {
        $examClasses = $exam->examClasses()
            ->orderBy('sort_order')
            ->orderBy('class_name')
            ->get();

        return view(
            'admin.exam-classes.index',
            compact('exam', 'examClasses')
        );
    }

    /**
     * Show create class form.
     */
    public function create(Exam $exam)
    {
        return view(
            'admin.exam-classes.create',
            compact('exam')
        );
    }

    /**
     * Store one class.
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'class_name' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $className = $validated['class_name'];

        $exists = $exam->examClasses()
            ->where('class_name', $className)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_name' =>
                        'This class is already assigned to this exam.',
                ]);
        }

        $lastSortOrder = $exam->examClasses()->max('sort_order') ?? 0;

        ExamClass::create([
            'exam_id' => $exam->id,
            'class_name' => $className,
            'sort_order' => $lastSortOrder + 1,
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.exam-classes.index', $exam->id)
            ->with('success', 'Class added successfully.');
    }

    /**
     * Add all classes from Nursery to Class 12.
     *
     * Existing classes are skipped.
     */
    public function addAll(Exam $exam)
    {
        $classes = [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        DB::transaction(function () use ($exam, $classes) {

            $lastSortOrder = $exam->examClasses()
                ->max('sort_order') ?? 0;

            foreach ($classes as $className) {

                $exists = $exam->examClasses()
                    ->where('class_name', $className)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $lastSortOrder++;

                ExamClass::create([
                    'exam_id' => $exam->id,
                    'class_name' => $className,
                    'sort_order' => $lastSortOrder,
                    'status' => 'active',
                ]);
            }
        });

        return redirect()
            ->route('admin.exam-classes.index', $exam->id)
            ->with(
                'success',
                'All classes from Nursery to Class 12 have been added successfully.'
            );
    }

    /**
     * Show edit class form.
     */
    public function edit(Exam $exam, ExamClass $examClass)
    {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        return view(
            'admin.exam-classes.edit',
            compact('exam', 'examClass')
        );
    }

    /**
     * Update class.
     */
    public function update(
        Request $request,
        Exam $exam,
        ExamClass $examClass
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        $validated = $request->validate([
            'class_name' => [
                'required',
                'string',
                'max:50',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        $duplicate = $exam->examClasses()
            ->where('class_name', $validated['class_name'])
            ->where('id', '!=', $examClass->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_name' =>
                        'This class is already assigned to this exam.',
                ]);
        }

        $examClass->update($validated);

        return redirect()
            ->route('admin.exam-classes.index', $exam->id)
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Delete class from exam.
     */
    public function destroy(
        Exam $exam,
        ExamClass $examClass
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        $examClass->delete();

        return redirect()
            ->route('admin.exam-classes.index', $exam->id)
            ->with(
                'success',
                'Class removed from exam successfully.'
            );
    }
}
