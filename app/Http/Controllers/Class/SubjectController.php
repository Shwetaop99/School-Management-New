<?php

namespace App\Http\Controllers\Class;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Class\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('schoolClass')
            ->latest()
            ->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = SchoolClass::where('status', true)
            ->orderBy('class_name')
            ->get();

        return view('admin.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:100',
            'subject_code' => 'nullable|string|max:50',
            'class_id' => 'required|exists:school_classes,id',
            'status' => 'required|boolean',
        ]);

        Subject::create($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject added successfully.');
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

        return view('admin.subjects.edit', compact('subject', 'classes'));
    }

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