<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentFeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT FEES INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = StudentFee::with([
            'student',
            'feeStructure.schoolClass',
            'feeStructure.section',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH STUDENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('student', function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ACADEMIC YEAR FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $studentFees = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | ACADEMIC YEARS
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalFees = StudentFee::count();

        $pendingFees = StudentFee::where(
            'status',
            'Pending'
        )->count();

        $partialFees = StudentFee::where(
            'status',
            'Partial'
        )->count();

        $paidFees = StudentFee::where(
            'status',
            'Paid'
        )->count();

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
        /*
        |--------------------------------------------------------------------------
        | FETCH ACTUAL STUDENTS FROM STUDENTS TABLE
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ACADEMIC YEARS FROM STUDENTS
        |--------------------------------------------------------------------------
        */

        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        /*
        |--------------------------------------------------------------------------
        | CLASSES
        |--------------------------------------------------------------------------
        */

        $classes = DB::table('classes')
            ->orderBy('class_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ACTIVE FEE STRUCTURES
        |--------------------------------------------------------------------------
        */

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
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
            ],

            'fee_structure_id' => [
                'required',
                'exists:fee_structures,id',
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
            ],

            'fine_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET SELECTED FEE STRUCTURE
        |--------------------------------------------------------------------------
        */

        $feeStructure = FeeStructure::with([
            'items',
        ])->findOrFail(
            $validated['fee_structure_id']
        );

        /*
        |--------------------------------------------------------------------------
        | CALCULATE TOTAL
        |--------------------------------------------------------------------------
        */

        $totalAmount = $feeStructure->items->sum(
            function ($item) {
                return (float) $item->amount;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | DISCOUNT + FINE
        |--------------------------------------------------------------------------
        */

        $discountAmount = (float) (
            $validated['discount_amount'] ?? 0
        );

        $fineAmount = (float) (
            $validated['fine_amount'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | CALCULATE BALANCE
        |--------------------------------------------------------------------------
        */

        $balanceAmount = max(
            0,
            $totalAmount
                - $discountAmount
                + $fineAmount
        );

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $status = $balanceAmount > 0
            ? 'Pending'
            : 'Paid';

        /*
        |--------------------------------------------------------------------------
        | CREATE STUDENT FEE
        |--------------------------------------------------------------------------
        */

        $studentFee = StudentFee::create([
            'student_id' => $validated['student_id'],

            'fee_structure_id' =>
                $validated['fee_structure_id'],

            'academic_year' =>
                $validated['academic_year'],

            'total_amount' =>
                $totalAmount,

            'discount_amount' =>
                $discountAmount,

            'fine_amount' =>
                $fineAmount,

            'paid_amount' =>
                0,

            'balance_amount' =>
                $balanceAmount,

            'status' =>
                $status,

            'due_date' =>
                $validated['due_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.fees.student-fees.show',
                $studentFee
            )
            ->with(
                'success',
                'Student fee assigned successfully.'
            );
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
        $studentFee->delete();

        return redirect()
            ->route('admin.fees.student-fees.index')
            ->with(
                'success',
                'Student fee deleted successfully.'
            );
    }
}
