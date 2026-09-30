<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    /**
     * Display sessions for an exam.
     */
    public function index(Exam $exam)
    {
        $sessions = $exam->examSessions()
            ->orderBy('start_time')
            ->get();

        return view(
            'admin.exams.sessions.index',
            compact('exam', 'sessions')
        );
    }

    /**
     * Show create session form.
     */
    public function create(Exam $exam)
    {
        return view(
            'admin.exams.sessions.create',
            compact('exam')
        );
    }

    /**
     * Store a new session.
     */
    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'session_name' => [
                'required',
                'string',
                'max:100',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        ExamSession::create([
            'exam_id' => $exam->id,
            'session_name' => $validated['session_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.exam-sessions.index', $exam)
            ->with(
                'success',
                'Exam session created successfully.'
            );
    }

    /**
     * Show edit session form.
     */
    public function edit(
        Exam $exam,
        ExamSession $session
    ) {
        abort_unless(
            $session->exam_id === $exam->id,
            404
        );

        return view(
            'admin.exams.sessions.edit',
            compact('exam', 'session')
        );
    }

    /**
     * Update session.
     */
    public function update(
        Request $request,
        Exam $exam,
        ExamSession $session
    ) {
        abort_unless(
            $session->exam_id === $exam->id,
            404
        );

        $validated = $request->validate([
            'session_name' => [
                'required',
                'string',
                'max:100',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        $session->update($validated);

        return redirect()
            ->route('admin.exam-sessions.index', $exam)
            ->with(
                'success',
                'Exam session updated successfully.'
            );
    }

    /**
     * Delete session.
     */
    public function destroy(
        Exam $exam,
        ExamSession $session
    ) {
        abort_unless(
            $session->exam_id === $exam->id,
            404
        );

        $session->delete();

        return redirect()
            ->route('admin.exam-sessions.index', $exam)
            ->with(
                'success',
                'Exam session deleted successfully.'
            );
    }
}
