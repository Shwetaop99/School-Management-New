<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display all exams.
     */
    public function index()
    {
        $exams = Exam::with('creator')
            ->latest()
            ->paginate(10);

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
     * Store new exam.
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
                'max:255',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive,completed',
            ],
        ]);

        $validated['created_by'] = Auth::id();

        Exam::create($validated);

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    /**
     * Display a single exam.
     */
    public function show(Exam $exam)
    {
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show edit form.
     */
    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Update exam.
     */
    public function update(Request $request, Exam $exam)
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
                'max:255',
            ],

            'exam_type' => [
                'required',
                'string',
                'max:100',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive,completed',
            ],
        ]);

        $exam->update($validated);

        return redirect()
            ->route('admin.exams.index')
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