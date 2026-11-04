<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Throwable;

class MarkController extends Controller
{
    public function index(Request $request)
    {
        $query = Mark::with(['student', 'subject']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('class')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        $marks = $query->latest()->paginate(20)->withQueryString();

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.marks.index', compact('marks', 'students', 'subjects'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')
            ->orderBy('class')
            ->orderBy('first_name')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        return view('admin.marks.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMark($request);

        try {
            Mark::updateOrCreate(
                [
                    'student_id'    => $validated['student_id'],
                    'subject_id'    => $validated['subject_id'],
                    'exam_type'     => $validated['exam_type'],
                    'academic_year' => $validated['academic_year'] ?? null,
                ],
                [
                    'marks'     => $validated['marks'],
                    'max_marks' => $validated['max_marks'] ?? 100,
                    'remarks'   => $validated['remarks'] ?? null,
                ]
            );

            return redirect()
                ->route('admin.marks.index')
                ->with('success', 'Marks saved successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to save marks. Please try again.');
        }
    }

    public function edit(Mark $mark)
    {
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.marks.edit', compact('mark', 'students', 'subjects'));
    }

    public function update(Request $request, Mark $mark)
    {
        $validated = $this->validateMark($request);

        try {
            $mark->update([
                'student_id'    => $validated['student_id'],
                'subject_id'    => $validated['subject_id'],
                'exam_type'     => $validated['exam_type'],
                'marks'         => $validated['marks'],
                'max_marks'     => $validated['max_marks'] ?? 100,
                'remarks'       => $validated['remarks'] ?? null,
                'academic_year' => $validated['academic_year'] ?? null,
            ]);

            return redirect()
                ->route('admin.marks.index')
                ->with('success', 'Marks updated successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Failed to update marks.');
        }
    }

    public function destroy(Mark $mark)
    {
        $mark->delete();

        return redirect()
            ->route('admin.marks.index')
            ->with('success', 'Marks deleted successfully.');
    }

    private function validateMark(Request $request): array
    {
        return $request->validate([
            'student_id'    => ['required', 'integer', 'exists:students,id'],
            'subject_id'    => ['required', 'integer', 'exists:subjects,id'],
            'exam_type'     => ['required', 'string', 'in:midterm,final,quiz,assignment,unit_test,other'],
            'marks'         => ['required', 'numeric', 'min:0', 'max:100'],
            'max_marks'     => ['nullable', 'numeric', 'min:1', 'max:100'],
            'remarks'       => ['nullable', 'string', 'max:500'],
            'academic_year' => ['nullable', 'string', 'max:20'],
        ], [
            'student_id.required' => 'Please select a student.',
            'student_id.exists'   => 'Selected student does not exist.',
            'subject_id.required' => 'Please select a subject.',
            'subject_id.exists'   => 'Selected subject does not exist.',
            'exam_type.required'  => 'Please select exam type.',
            'exam_type.in'        => 'Invalid exam type selected.',
            'marks.required'      => 'Marks are required.',
            'marks.numeric'       => 'Marks must be a number.',
            'marks.min'           => 'Marks cannot be less than 0.',
            'marks.max'           => 'Marks cannot be more than 100.',
        ]);
    }
}