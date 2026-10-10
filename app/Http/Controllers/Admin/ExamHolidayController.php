<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamHoliday;
use Illuminate\Http\Request;

class ExamHolidayController extends Controller
{
    /**
     * Display holidays for an exam.
     */
    public function index(Exam $exam)
    {
        $holidays = $exam->examHolidays()
            ->orderBy('holiday_date')
            ->get();

        return view(
            'admin.exams.holidays.index',
            compact('exam', 'holidays')
        );
    }

    /**
     * Show create holiday form.
     */
    public function create(Exam $exam)
    {
        return view(
            'admin.exams.holidays.create',
            compact('exam')
        );
    }

    /**
     * Store holiday.
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'holiday_date' => [
                'required',
                'date',
                'after_or_equal:' . $exam->start_date->format('Y-m-d'),
                'before_or_equal:' . (
                    $exam->end_date
                        ? $exam->end_date->format('Y-m-d')
                        : $exam->start_date->format('Y-m-d')
                ),
            ],

            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ], [
            'holiday_date.after_or_equal' =>
                'Holiday date cannot be before the exam start date.',

            'holiday_date.before_or_equal' =>
                'Holiday date cannot be after the exam end date.',
        ]);

        $alreadyExists = ExamHoliday::where('exam_id', $exam->id)
            ->whereDate('holiday_date', $validated['holiday_date'])
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors([
                    'holiday_date' =>
                        'This date is already added as a holiday.'
                ])
                ->withInput();
        }

        ExamHoliday::create([
            'exam_id' => $exam->id,
            'holiday_date' => $validated['holiday_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.exam-holidays.index', $exam)
            ->with(
                'success',
                'Exam holiday added successfully.'
            );
    }

    /**
     * Show edit holiday form.
     */
    public function edit(
        Exam $exam,
        ExamHoliday $holiday
    ) {
        abort_unless(
            $holiday->exam_id === $exam->id,
            404
        );

        return view(
            'admin.exams.holidays.edit',
            compact('exam', 'holiday')
        );
    }

    /**
     * Update holiday.
     */
    public function update(
        Request $request,
        Exam $exam,
        ExamHoliday $holiday
    ) {
        abort_unless(
            $holiday->exam_id === $exam->id,
            404
        );

        $validated = $request->validate([
            'holiday_date' => [
                'required',
                'date',
                'after_or_equal:' . $exam->start_date->format('Y-m-d'),
                'before_or_equal:' . (
                    $exam->end_date
                        ? $exam->end_date->format('Y-m-d')
                        : $exam->start_date->format('Y-m-d')
                ),
            ],

            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        $alreadyExists = ExamHoliday::where('exam_id', $exam->id)
            ->whereDate('holiday_date', $validated['holiday_date'])
            ->where('id', '!=', $holiday->id)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors([
                    'holiday_date' =>
                        'This date is already added as a holiday.'
                ])
                ->withInput();
        }

        $holiday->update([
            'holiday_date' => $validated['holiday_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.exam-holidays.index', $exam)
            ->with(
                'success',
                'Exam holiday updated successfully.'
            );
    }

    /**
     * Delete holiday.
     */
    public function destroy(
        Exam $exam,
        ExamHoliday $holiday
    ) {
        abort_unless(
            $holiday->exam_id === $exam->id,
            404
        );

        $holiday->delete();

        return redirect()
            ->route('admin.exam-holidays.index', $exam)
            ->with(
                'success',
                'Exam holiday deleted successfully.'
            );
    }
}
