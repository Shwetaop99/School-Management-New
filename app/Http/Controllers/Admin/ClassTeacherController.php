<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTeacherAssignment;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassTeacherController extends Controller
{
    // =========================================================
    // SHOW ALL TEACHER ALLOCATIONS
    // =========================================================

    public function index()
    {
        $assignments = ClassTeacherAssignment::query()
            ->join(
                'teachers',
                'class_teacher_assignments.teacher_id',
                '=',
                'teachers.id'
            )
            ->join(
                'classes',
                'class_teacher_assignments.class_id',
                '=',
                'classes.id'
            )
            ->join(
                'sections',
                'class_teacher_assignments.section_id',
                '=',
                'sections.id'
            )
            ->select(
                'class_teacher_assignments.*',
                'teachers.first_name',
                'teachers.last_name',
                'classes.class_name',
                'sections.section_name'
            )
            ->latest('class_teacher_assignments.id')
            ->get();

        return view(
            'admin.teachers.assign-class.index',
            compact('assignments')
        );
    }

    // =========================================================
    // SHOW CREATE PAGE
    // =========================================================

    public function create()
    {
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $classes = DB::table('classes')
            ->orderBy('id')
            ->get();

        $sections = DB::table('sections')
            ->orderBy('id')
            ->get();

        return view(
            'admin.teachers.assign-class.create',
            compact(
                'teachers',
                'classes',
                'sections'
            )
        );
    }

    // =========================================================
    // STORE TEACHER ALLOCATION
    // =========================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'class_id' => [
                'required',
                'exists:classes,id',
            ],

            'section_id' => [
                'required',
                'integer',
                'exists:sections,id',
            ],

            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        ClassTeacherAssignment::create($validated);

        return redirect()
            ->route('admin.teachers.assign-class.index')
            ->with(
                'success',
                'Teacher allocated successfully.'
            );
    }

    // =========================================================
    // EDIT TEACHER ALLOCATION
    // =========================================================

    public function edit(ClassTeacherAssignment $classTeacher)
    {
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $classes = DB::table('classes')
            ->orderBy('id')
            ->get();

        $sections = DB::table('sections')
            ->orderBy('id')
            ->get();

        return view(
            'admin.teachers.assign-class.edit',
            compact(
                'classTeacher',
                'teachers',
                'classes',
                'sections'
            )
        );
    }

    // =========================================================
    // UPDATE TEACHER ALLOCATION
    // =========================================================

    public function update(
        Request $request,
        ClassTeacherAssignment $classTeacher
    ) {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'class_id' => [
                'required',
                'exists:classes,id',
            ],

            'section_id' => [
                'required',
                'integer',
                'exists:sections,id',
            ],

            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        $classTeacher->update($validated);

        return redirect()
            ->route('admin.teachers.assign-class.index')
            ->with(
                'success',
                'Teacher allocation updated successfully.'
            );
    }

    // =========================================================
    // DELETE TEACHER ALLOCATION
    // =========================================================

    public function destroy(ClassTeacherAssignment $classTeacher)
    {
        $classTeacher->delete();

        return redirect()
            ->route('admin.teachers.assign-class.index')
            ->with(
                'success',
                'Teacher allocation deleted successfully.'
            );
    }
}
