<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentFeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT FEES INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // UPDATE 1: check the filters first
        $request->validate([
            'search'        => ['nullable', 'string', 'max:100'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'status'        => ['nullable', Rule::in(['Pending', 'Partial', 'Paid'])],
        ]);

        $query = StudentFee::with([
            'student',
            'feeStructure.schoolClass',
            'feeStructure.section',
        ]);

        // SEARCH STUDENT
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('student', function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // ACADEMIC YEAR FILTER
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $studentFees = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // ACADEMIC YEARS
        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        // SUMMARY
        $totalFees   = StudentFee::count();
        $pendingFees = StudentFee::where('status', 'Pending')->count();
        $partialFees = StudentFee::where('status', 'Partial')->count();
        $paidFees    = StudentFee::where('status', 'Paid')->count();

        return view(
            'admin.fees.student-fees.index',
            compact(
                'studentFees',
                'academicYears',
                'totalFees',
                'pendingFees',
                'partialFees',
                'paidFees'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE STUDENT FEE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        $feeStructures = FeeStructure::with([
            'schoolClass',
            'section',
            'items.feeType',
        ])
            ->where('status', 'Active')
            ->orderBy('academic_year', 'desc')
            ->orderBy('structure_name')
            ->get();

        return view(
            'admin.fees.student-fees.create',
            compact(
                'students',
                'academicYears',
                'classes',
                'feeStructures'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE STUDENT FEE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        // UPDATE 2: stronger checks
        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            // only ACTIVE fee structures are allowed
            'fee_structure_id' => [
                'required',
                'integer',
                Rule::exists('fee_structures', 'id')
                    ->where('status', 'Active'),
            ],

            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999',
                'decimal:0,2',
            ],

            'fine_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999',
                'decimal:0,2',
            ],

            'due_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $feeStructure = FeeStructure::with('items')
            ->findOrFail($validated['fee_structure_id']);

        // UPDATE 3: same fee cannot be given twice to the same student
        $alreadyAssigned = StudentFee::where('student_id', $validated['student_id'])
            ->where('fee_structure_id', $validated['fee_structure_id'])
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_structure_id' => 'This fee is already assigned to this student.',
                ]);
        }

        // CALCULATE TOTAL
        $totalAmount = round(
            (float) $feeStructure->items->sum(
                fn ($item) => (float) $item->amount
            ),
            2
        );

        $discountAmount = round((float) ($validated['discount_amount'] ?? 0), 2);
        $fineAmount     = round((float) ($validated['fine_amount'] ?? 0), 2);

        // UPDATE 4: discount can never be more than the total fee
        if ($discountAmount > $totalAmount) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_amount' => 'Discount cannot be more than the total fee ('
                        . number_format($totalAmount, 2) . ').',
                ]);
        }

        $balanceAmount = round($totalAmount - $discountAmount + $fineAmount, 2);

        $status = $balanceAmount > 0 ? 'Pending' : 'Paid';

        // UPDATE 5: academic year comes from the fee structure
        $academicYear = $feeStructure->academic_year
            ?: $validated['academic_year'];

        $studentFee = StudentFee::create([
            'student_id'       => $validated['student_id'],
            'fee_structure_id' => $validated['fee_structure_id'],
            'academic_year'    => $academicYear,
            'total_amount'     => $totalAmount,
            'discount_amount'  => $discountAmount,
            'fine_amount'      => $fineAmount,
            'paid_amount'      => 0,
            'balance_amount'   => $balanceAmount,
            'status'           => $status,
            'due_date'         => $validated['due_date'] ?? null,
            'remarks'          => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('admin.fees.student-fees.show', $studentFee)
            ->with('success', 'Student fee assigned successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(StudentFee $studentFee)
    {
        $studentFee->load([
            'student',
            'feeStructure.schoolClass',
            'feeStructure.section',
            'feeStructure.items.feeType',
            'payments',
        ]);

        return view(
            'admin.fees.student-fees.show',
            compact('studentFee')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(StudentFee $studentFee)
    {
        // UPDATE 6: a fee with payments must never be deleted
        if (
            $studentFee->payments()->exists() ||
            (float) $studentFee->paid_amount > 0
        ) {
            return back()->with(
                'error',
                'This fee already has payments, so it cannot be deleted.'
            );
        }

        $studentFee->delete();

        return redirect()
            ->route('admin.fees.student-fees.index')
            ->with('success', 'Student fee deleted successfully.');
    }
}