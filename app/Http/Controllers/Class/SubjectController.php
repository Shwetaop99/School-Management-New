<?php

namespace App\Http\Controllers\Class;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Class\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->get();

        $subjectsQuery = Subject::with('schoolClass')
            ->latest();

        if ($request->filled('class_id')) {
            $subjectsQuery->where('class_id', $request->class_id);
        }

        $subjects = $subjectsQuery->get();

        return view('admin.subjects.index', compact(
            'subjects',
            'classes'
        ));
    }

    public function create()
    {
        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->get();

        return view('admin.subjects.create', compact('classes'));
    }

    /**
     * Store multiple subjects at once.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'status' => 'required|boolean',

            'subjects' => 'required|array|min:1',
            'subjects.*.subject_name' => 'required|string|max:100',
            'subjects.*.subject_code' => 'nullable|string|max:50',
        ], [
            'subjects.required' => 'Please add at least one subject.',
            'subjects.array' => 'Invalid subject data.',
            'subjects.min' => 'Please add at least one subject.',
            'subjects.*.subject_name.required' => 'The subject name field is required.',
            'subjects.*.subject_name.string' => 'Subject name must be a valid text.',
            'subjects.*.subject_name.max' => 'Subject name may not be greater than 100 characters.',
            'subjects.*.subject_code.max' => 'Subject code may not be greater than 50 characters.',
        ]);

        DB::transaction(function () use ($validated) {

            foreach ($validated['subjects'] as $subjectData) {

                Subject::create([
                    'subject_name' => $subjectData['subject_name'],
                    'subject_code' => $subjectData['subject_code'] ?? null,
                    'class_id' => $validated['class_id'],
                    'status' => $validated['status'],
                ]);
            }
        });

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', count($validated['subjects']) . ' subjects added successfully.');
    }

    public function show(string $id)
    {
        $subject = Subject::with('schoolClass')
            ->findOrFail($id);

        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(string $id)
    {
        $subject = Subject::findOrFail($id);

        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->get();

        return view('admin.subjects.edit', compact(
            'subject',
            'classes'
        ));
    }

    /**
     * Update one subject.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:100',
            'subject_code' => 'nullable|string|max:50',
            'class_id' => 'required|exists:school_classes,id',
            'status' => 'required|boolean',
        ]);

        $subject = Subject::findOrFail($id);

        $subject->update($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);

        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}