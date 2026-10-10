<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display all exams.
     */
    public function index()
    {
        $exams = Exam::latest()->get();

        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Show create exam form.
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a new exam.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'exam_name' => [
                'required',
                'string',
                'max:150',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                'in:draft,scheduled,completed,cancelled',
            ],
        ]);

        $exam = Exam::create($validated);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    /**
     * Display exam details.
     */
    public function show(Exam $exam)
    {
        $exam->loadCount([
            'examClasses',
            'examSubjects',
            'examSessions',
            'examHolidays',
            'examTimetables',
        ]);

        return view(
            'admin.exams.show',
            compact('exam')
        );
    }

    /**
     * Show edit exam form.
     */
    public function edit(Exam $exam)
    {
        return view(
            'admin.exams.edit',
            compact('exam')
        );
    }

    /**
     * Update exam.
     */
    public function update(
        Request $request,
        Exam $exam
    ) {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'exam_name' => [
                'required',
                'string',
                'max:150',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                'in:draft,scheduled,completed,cancelled',
            ],
        ]);

        $exam->update($validated);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    /**
     * Delete exam.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}