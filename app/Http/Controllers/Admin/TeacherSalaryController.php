<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use Illuminate\Http\Request;

class TeacherSalaryController extends Controller
{
    public function index()
{
    $salaries = TeacherSalary::with('teacher')
        ->latest()
        ->paginate(10);

    return view('admin.salary.index', compact('salaries'));
}

    public function create()
    {
        $teachers = Teacher::orderBy('first_name')->get();

        return view(
            'admin.salary.create',
            compact('teachers')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'salary_month' => 'required|string|max:20',

            'basic_salary' => 'required|numeric|min:0',

            'allowances' => 'nullable|numeric|min:0',

            'deduction_rate' => 'nullable|numeric|min:0|max:100',

            'payment_status' => 'required|in:Pending,Paid',

            'payment_date' => 'nullable|date',

            'remarks' => 'nullable|string|max:1000',
        ]);

        $basic = (float) ($validated['basic_salary'] ?? 0);

        $allowances = (float) ($validated['allowances'] ?? 0);

        $deductionRate = (float) (
            $validated['deduction_rate'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Deduction Amount
        |--------------------------------------------------------------------------
        */

        $deductionAmount =
            ($basic * $deductionRate) / 100;

        /*
        |--------------------------------------------------------------------------
        | Calculate Net Salary
        |--------------------------------------------------------------------------
        */

        $netSalary =
            $basic
            + $allowances
            - $deductionAmount;

        /*
        |--------------------------------------------------------------------------
        | Store Values
        |--------------------------------------------------------------------------
        */

        $validated['allowances'] = $allowances;

        $validated['deduction_rate'] = $deductionRate;

        $validated['deductions'] = $deductionAmount;

        $validated['net_salary'] = $netSalary;

        TeacherSalary::create($validated);

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with(
                'success',
                'Salary generated successfully.'
            );
    }

    public function show(TeacherSalary $teacherSalary)
    {
        $teacherSalary->load('teacher');

        return view(
            'admin.salary.show',
            compact('teacherSalary')
        );
    }

    public function edit(TeacherSalary $teacherSalary)
    {
        $teachers = Teacher::orderBy('first_name')->get();

        return view(
            'admin.salary.edit',
            compact(
                'teacherSalary',
                'teachers'
            )
        );
    }

    public function update(
        Request $request,
        TeacherSalary $teacherSalary
    ) {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'salary_month' => 'required|string|max:20',

            'basic_salary' => 'required|numeric|min:0',

            'allowances' => 'nullable|numeric|min:0',

            'deduction_rate' => 'nullable|numeric|min:0|max:100',

            'payment_status' => 'required|in:Pending,Paid',

            'payment_date' => 'nullable|date',

            'remarks' => 'nullable|string|max:1000',
        ]);

        $basic = (float) ($validated['basic_salary'] ?? 0);

        $allowances = (float) ($validated['allowances'] ?? 0);

        $deductionRate = (float) (
            $validated['deduction_rate'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Deduction Amount
        |--------------------------------------------------------------------------
        */

        $deductionAmount =
            ($basic * $deductionRate) / 100;

        /*
        |--------------------------------------------------------------------------
        | Calculate Net Salary
        |--------------------------------------------------------------------------
        */

        $netSalary =
            $basic
            + $allowances
            - $deductionAmount;

        /*
        |--------------------------------------------------------------------------
        | Store Values
        |--------------------------------------------------------------------------
        */

        $validated['allowances'] = $allowances;

        $validated['deduction_rate'] = $deductionRate;

        $validated['deductions'] = $deductionAmount;

        $validated['net_salary'] = $netSalary;

        $teacherSalary->update($validated);

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with(
                'success',
                'Salary updated successfully.'
            );
    }

    public function destroy(TeacherSalary $teacherSalary)
    {
        $teacherSalary->delete();

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with(
                'success',
                'Salary deleted successfully.'
            );
    }
}