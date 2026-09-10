<?php

namespace App\Http\Controllers\Class;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::latest()->get();

        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:100',
            'section' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'status' => 'required|boolean',
        ]);

        SchoolClass::create($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        return view('admin.classes.show', compact('schoolClass'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        return view('admin.classes.edit', compact('schoolClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:100',
            'section' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'status' => 'required|boolean',
        ]);

        $schoolClass = SchoolClass::findOrFail($id);

        $schoolClass->update($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);

        $schoolClass->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}