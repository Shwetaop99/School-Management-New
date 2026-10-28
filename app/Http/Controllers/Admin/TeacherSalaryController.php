<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeacherSalaryController extends Controller
{
    private const STAFF_TYPES = [
        'Teacher',
        'Driver',
        'Peon',
        'Librarian',
        'Accountant',
        'Receptionist',
        'OtherStaff',
    ];

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $salaries = TeacherSalary::with(['teacher', 'otherStaff'])
            ->latest()
            ->get();

        return view('admin.salary.index', compact('salaries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        $otherStaff = OtherStaff::where('status', 'Active')
            ->orderBy('name')
            ->get();

        // Comes from the Generate button: ?staff_type=Driver
        $selectedType = in_array($request->query('staff_type'), self::STAFF_TYPES, true)
            ? $request->query('staff_type')
            : 'Teacher';

        return view(
            'admin.salary.create',
            compact('teachers', 'otherStaff', 'selectedType')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $staffType = $request->input('staff_type');

        $rules = [
            'staff_type'     => 'required|in:' . implode(',', self::STAFF_TYPES),
            'salary_month'   => 'required|date_format:Y-m',
            'basic_salary'   => 'required|numeric|min:0',
            'allowances'     => 'nullable|numeric|min:0',
            'deduction_rate' => 'nullable|numeric|min:0|max:100',
            'payment_status' => 'required|in:Pending,Paid',
            'payment_date'   => 'nullable|date',
            'remarks'        => 'nullable|string|max:1000',
        ];

        // Validate the correct staff ID based on staff type.
        if ($staffType === 'Teacher') {
            $rules['teacher_id']     = 'required|exists:teachers,id';
            $rules['other_staff_id'] = 'nullable';
        } else {
            $rules['other_staff_id'] = 'required|exists:other_staff,id';
            $rules['teacher_id']     = 'nullable';
        }

        $validated = $request->validate($rules);

        $teacherId    = $staffType === 'Teacher' ? $validated['teacher_id'] : null;
        $otherStaffId = $staffType !== 'Teacher' ? $validated['other_staff_id'] : null;

        // Do not allow two salary records for the same person and month.
        $duplicate = TeacherSalary::where('salary_month', $validated['salary_month'])
            ->when(
                $teacherId,
                fn ($q) => $q->where('teacher_id', $teacherId),
                fn ($q) => $q->where('other_staff_id', $otherStaffId)
            )
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'salary_month' => 'A salary record already exists for this staff member and month.',
            ]);
        }

        [$basic, $allowances, $rate, $deductions, $netSalary] = $this->calculate($validated);

        TeacherSalary::create([
            'staff_type'     => $staffType,
            'teacher_id'     => $teacherId,
            'other_staff_id' => $otherStaffId,
            'salary_month'   => $validated['salary_month'],
            'basic_salary'   => $basic,
            'allowances'     => $allowances,
            'deduction_rate' => $rate,
            'deductions'     => $deductions,
            'net_salary'     => $netSalary,
            'payment_status' => $validated['payment_status'],
            'payment_date'   => $validated['payment_date'] ?? null,
            'remarks'        => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('admin.salary.index')
            ->with('success', 'Salary generated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */
    public function show(TeacherSalary $salary)
    {
        $salary->load(['teacher', 'otherStaff']);

        // Light-weight list of every salary record for the department
        // dropdown, search box and record dropdown on the show page.
        $salaryOptions = TeacherSalary::with(['teacher', 'otherStaff'])
            ->latest()
            ->get()
            ->map(function ($row) {
                $isTeacher = $row->staff_type === 'Teacher'
                    || (empty($row->staff_type) && $row->teacher_id);

                $type = $isTeacher ? 'Teacher' : ($row->staff_type ?: 'OtherStaff');

                if ($isTeacher) {
                    $name = trim(($row->teacher->first_name ?? '') . ' ' . ($row->teacher->last_name ?? ''));
                    $code = $row->teacher->teacher_id ?? null;
                } else {
                    $name = trim($row->otherStaff->name ?? '');
                    $code = $row->otherStaff->staff_id ?? null;
                }

                return [
                    'id'          => $row->id,
                    'type'        => $type,
                    'name'        => $name !== '' ? $name : 'Unknown staff',
                    'code'        => $code,
                    'month'       => $row->salary_month,
                    'month_label' => $this->monthLabel($row->salary_month),
                    'status'      => $row->payment_status,
                    'net'         => (float) $row->net_salary,
                    'url'         => route('admin.salary.show', $row->id),
                ];
            })
            ->values();

        return view('admin.salary.show', [
            'teacherSalary' => $salary,
            'salaryOptions' => $salaryOptions,
        ]);
    }

    private function monthLabel($value): string
    {
        try {
            return \Carbon\Carbon::createFromFormat('!Y-m', $value)->format('M Y');
        } catch (\Throwable $e) {
            try {
                return \Carbon\Carbon::parse($value)->format('M Y');
            } catch (\Throwable $e2) {
                return (string) $value;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
    public function edit(TeacherSalary $salary)
    {
        $salary->load(['teacher', 'otherStaff']);

        $teachers   = Teacher::orderBy('first_name')->get();
        $otherStaff = OtherStaff::orderBy('name')->get();

        return view('admin.salary.edit', [
            'teacherSalary' => $salary,
            'teachers'      => $teachers,
            'otherStaff'    => $otherStaff,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, TeacherSalary $salary)
    {
        // If the edit form does not send staff_type, keep the saved one.
        $staffType = $request->input(
            'staff_type',
            $salary->staff_type ?: 'Teacher'
        );

        $validated = $request->validate([
            'staff_type'     => 'nullable|in:' . implode(',', self::STAFF_TYPES),
            'teacher_id'     => 'nullable|exists:teachers,id',
            'other_staff_id' => 'nullable|exists:other_staff,id',
            'salary_month'   => 'required|string|max:20',
            'basic_salary'   => 'required|numeric|min:0',
            'allowances'     => 'nullable|numeric|min:0',
            'deduction_rate' => 'nullable|numeric|min:0|max:100',
            'payment_status' => 'required|in:Pending,Paid',
            'payment_date'   => 'nullable|date',
            'remarks'        => 'nullable|string|max:1000',
        ]);

        if ($staffType === 'Teacher') {
            $teacherId    = $validated['teacher_id'] ?? $salary->teacher_id;
            $otherStaffId = null;

            if (!$teacherId) {
                throw ValidationException::withMessages([
                    'teacher_id' => 'Please select a teacher.',
                ]);
            }
        } else {
            $teacherId    = null;
            $otherStaffId = $validated['other_staff_id'] ?? $salary->other_staff_id;

            if (!$otherStaffId) {
                throw ValidationException::withMessages([
                    'other_staff_id' => 'Please select a staff member.',
                ]);
            }
        }

        [$basic, $allowances, $rate, $deductions, $netSalary] = $this->calculate($validated);

        $salary->update([
            'staff_type'     => $staffType,
            'teacher_id'     => $teacherId,
            'other_staff_id' => $otherStaffId,
            'salary_month'   => $validated['salary_month'],
            'basic_salary'   => $basic,
            'allowances'     => $allowances,
            'deduction_rate' => $rate,
            'deductions'     => $deductions,
            'net_salary'     => $netSalary,
            'payment_status' => $validated['payment_status'],
            'payment_date'   => $validated['payment_date'] ?? null,
            'remarks'        => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('admin.salary.index')
            ->with('success', 'Salary updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */
    public function destroy(TeacherSalary $salary)
    {
        $salary->delete();

        return redirect()
            ->route('admin.salary.index')
            ->with('success', 'Salary deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Salary calculation (shared by store and update)
    |--------------------------------------------------------------------------
    */
    private function calculate(array $data): array
    {
        $basic      = (float) ($data['basic_salary'] ?? 0);
        $allowances = (float) ($data['allowances'] ?? 0);
        $rate       = (float) ($data['deduction_rate'] ?? 0);

        $deductions = round($basic * $rate / 100, 2);
        $netSalary  = round($basic + $allowances - $deductions, 2);

        return [$basic, $allowances, $rate, $deductions, $netSalary];
    }
}


