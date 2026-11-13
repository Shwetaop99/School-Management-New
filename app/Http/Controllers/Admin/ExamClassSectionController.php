<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamClassSectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Exam $exam, ExamClass $examClass)
    {
        // Make sure the class belongs to this exam
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        $sections = $examClass->sections()
            ->orderBy('sort_order')
            ->orderBy('section_name')
            ->get();

        return view(
            'admin.exam-class-sections.index',
            compact(
                'exam',
                'examClass',
                'sections'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Exam $exam, ExamClass $examClass)
    {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        return view(
            'admin.exam-class-sections.create',
            compact(
                'exam',
                'examClass'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Exam $exam,
        ExamClass $examClass
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        $validated = $request->validate([
            'section_name' => [
                'required',
                'string',
                'max:20',
            ],
        ]);

        $sectionName = strtoupper(
            trim($validated['section_name'])
        );

        $exists = $examClass->sections()
            ->where('section_name', $sectionName)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'section_name' =>
                        'This section is already assigned to this class.',
                ]);
        }

        $lastSortOrder = $examClass->sections()
            ->max('sort_order') ?? 0;

        ExamClassSection::create([
            'exam_class_id' => $examClass->id,
            'section_name' => $sectionName,
            'sort_order' => $lastSortOrder + 1,
            'status' => 'active',
        ]);

        return redirect()
            ->route(
                'admin.exam-class-sections.index',
                [
                    $exam->id,
                    $examClass->id,
                ]
            )
            ->with(
                'success',
                'Section added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADD ALL SECTIONS
    |--------------------------------------------------------------------------
    */

    public function addAll(
        Exam $exam,
        ExamClass $examClass
    ) {
        // Make sure this class belongs to this exam
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        $sections = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
        ];

        DB::transaction(function () use (
            $examClass,
            $sections
        ) {

            $existingSections = $examClass->sections()
                ->pluck('section_name')
                ->map(fn ($section) => strtoupper($section))
                ->toArray();

            $lastSortOrder = $examClass->sections()
                ->max('sort_order') ?? 0;

            foreach ($sections as $sectionName) {

                // Skip section if already exists
                if (in_array(
                    $sectionName,
                    $existingSections
                )) {
                    continue;
                }

                $lastSortOrder++;

                ExamClassSection::create([
                    'exam_class_id' => $examClass->id,
                    'section_name' => $sectionName,
                    'sort_order' => $lastSortOrder,
                    'status' => 'active',
                ]);
            }
        });

        return redirect()
            ->route(
                'admin.exam-class-sections.index',
                [
                    $exam->id,
                    $examClass->id,
                ]
            )
            ->with(
                'success',
                'All sections A to F have been added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Exam $exam,
        ExamClass $examClass,
        ExamClassSection $section
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        abort_unless(
            $section->exam_class_id === $examClass->id,
            404
        );

        return view(
            'admin.exam-class-sections.edit',
            compact(
                'exam',
                'examClass',
                'section'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Exam $exam,
        ExamClass $examClass,
        ExamClassSection $section
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        abort_unless(
            $section->exam_class_id === $examClass->id,
            404
        );

        $validated = $request->validate([
            'section_name' => [
                'required',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        $sectionName = strtoupper(
            trim($validated['section_name'])
        );

        $duplicate = $examClass->sections()
            ->where('section_name', $sectionName)
            ->where('id', '!=', $section->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'section_name' =>
                        'This section already exists for this class.',
                ]);
        }

        $section->update([
            'section_name' => $sectionName,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route(
                'admin.exam-class-sections.index',
                [
                    $exam->id,
                    $examClass->id,
                ]
            )
            ->with(
                'success',
                'Section updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Exam $exam,
        ExamClass $examClass,
        ExamClassSection $section
    ) {
        abort_unless(
            $examClass->exam_id === $exam->id,
            404
        );

        abort_unless(
            $section->exam_class_id === $examClass->id,
            404
        );

        $section->delete();

        return redirect()
            ->route(
                'admin.exam-class-sections.index',
                [
                    $exam->id,
                    $examClass->id,
                ]
            )
            ->with(
                'success',
                'Section removed successfully.'
            );
    }
}
