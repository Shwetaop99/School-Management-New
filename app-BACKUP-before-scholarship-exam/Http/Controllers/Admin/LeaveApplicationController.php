<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\Teacher;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    /**
     * Display all leave applications.
     */
    public function index()
    {
        $leaves = LeaveApplication::with('teacher')
            ->latest()
            ->get();

        return view('admin.leave-applications.index', compact('leaves'));
    }

    /**
     * Show create leave form.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('first_name')->get();

        return view('admin.leave-applications.create', compact('teachers'));
    }

    /**
     * Store a new leave application.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'teacher_id' => 'required|exists:teachers,id',

        'leave_type' => 'required|string|max:100',

        'from_date' => 'required|date',

        'to_date' => 'required|date|after_or_equal:from_date',

        'total_days' => 'required|numeric|min:1',

        'reason' => 'required|string|max:1000',
    ]);

    $leaveApplication = new LeaveApplication();

    $leaveApplication->teacher_id =
        $validated['teacher_id'];

    $leaveApplication->leave_type =
        $validated['leave_type'];

    $leaveApplication->from_date =
        $validated['from_date'];

    $leaveApplication->to_date =
        $validated['to_date'];

    $leaveApplication->total_days =
        $validated['total_days'];

    $leaveApplication->reason =
        $validated['reason'];

    $leaveApplication->save();

    return redirect()
        ->route('admin.leave-applications.index')
        ->with(
            'success',
            'Leave application submitted successfully.'
        );
}



    /**
     * Show a leave application.
     */
    public function show(LeaveApplication $leaveApplication)
    {
        $leaveApplication->load('teacher');

        return view(
            'admin.leave-applications.show',
            compact('leaveApplication')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(LeaveApplication $leaveApplication)
    {
        $teachers = Teacher::orderBy('first_name')->get();

        return view(
            'admin.leave-applications.edit',
            compact('leaveApplication', 'teachers')
        );
    }

    /**
     * Update leave application.
     */

public function update(Request $request, LeaveApplication $leaveApplication)
{
    $validated = $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
        'leave_type' => 'required|string|max:100',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'total_days' => 'required|numeric|min:1',
        'reason' => 'required|string|max:1000',
    ]);

    $leaveApplication->teacher_id = $validated['teacher_id'];
    $leaveApplication->leave_type = $validated['leave_type'];
    $leaveApplication->from_date = $validated['from_date'];
    $leaveApplication->to_date = $validated['to_date'];
    $leaveApplication->total_days = $validated['total_days'];
    $leaveApplication->reason = $validated['reason'];

    $leaveApplication->save();

    return redirect()
        ->route('admin.leave-applications.index')
        ->with('success', 'Leave application updated successfully.');
}


    /**
     * Delete leave application.
     */
    public function destroy(LeaveApplication $leaveApplication)
    {
        $leaveApplication->delete();

        return redirect()
            ->route('admin.leave-applications.index')
            ->with('success', 'Leave application deleted successfully.');
    }
}