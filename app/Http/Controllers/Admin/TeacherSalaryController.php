<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use Illuminate\Http\Request;
use App\Models\OtherStaff;

class TeacherSalaryController extends Controller
{
public function index()
{
    $salaries = \App\Models\TeacherSalary::with('teacher')
        ->latest()
        ->get();

    return view('admin.salary.index', compact('salaries'));
}

    public function create()
{
    $teachers = Teacher::where('status', 'Active')
        ->orderBy('first_name')
        ->get();

    $otherStaff = OtherStaff::where('status', 'Active')
        ->orderBy('name')
        ->get();

    return view(
        'admin.salary.create',
        compact('teachers', 'otherStaff')
    );
}

  
public function store(Request $request)
{
    $staffType = $request->input('staff_type');

    $rules = [
        'staff_type' => 'required|in:Teacher,Driver,Peon,Librarian,Accountant,Receptionist,OtherStaff',
        'salary_month' => 'required|date_format:Y-m',
        'basic_salary' => 'required|numeric|min:0',
        'allowances' => 'nullable|numeric|min:0',
        'deduction_rate' => 'nullable|numeric|min:0|max:100',
        'payment_status' => 'required|in:Pending,Paid',
        'payment_date' => 'nullable|date',
        'remarks' => 'nullable|string|max:1000',
    ];

    // Validate the correct staff ID based on staff type.
    if ($staffType === 'Teacher') {
        $rules['teacher_id'] = 'required|exists:teachers,id';
        $rules['other_staff_id'] = 'nullable';
    } else {
        $rules['other_staff_id'] = 'required|exists:other_staff,id';
        $rules['teacher_id'] = 'nullable';
    }

    $validated = $request->validate($rules);

    // Calculate salary.
    $basic = (float) $validated['basic_salary'];
    $allowances = (float) ($validated['allowances'] ?? 0);
    $rate = (float) ($validated['deduction_rate'] ?? 0);

    $deductions = round($basic * $rate / 100, 2);
    $netSalary = round($basic + $allowances - $deductions, 2);

    $salaryData = [
        'salary_month' => $validated['salary_month'],
        'basic_salary' => $basic,
        'allowances' => $allowances,
        'deduction_rate' => $rate,
        'deductions' => $deductions,
        'net_salary' => $netSalary,
        'payment_status' => $validated['payment_status'],
        'payment_date' => $validated['payment_date'] ?? null,
        'remarks' => $validated['remarks'] ?? null,
    ];

    if ($staffType === 'Teacher') {
        $salaryData['teacher_id'] = $validated['teacher_id'];
    } else {
        $salaryData['other_staff_id'] = $validated['other_staff_id'];
    }

    TeacherSalary::create($salaryData);

    return redirect()
        ->route('admin.teachers.salary.index')
        ->with('success', 'Salary generated successfully.');
}

  public function show(TeacherSalary $salary)
{
    $salary->load('teacher');

    return view('admin.salary.show', [
        'teacherSalary' => $salary,
    ]);
}

public function edit(TeacherSalary $salary)
{
    $teachers = Teacher::orderBy('first_name')->get();

    return view('admin.salary.edit', [
        'teacherSalary' => $salary,
        'teachers' => $teachers,
    ]);
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