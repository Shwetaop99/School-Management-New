<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FeePaymentController extends Controller
{
    // Allowed payment methods (one place, used everywhere)
    private const METHODS = [
        'Cash',
        'UPI',
        'Card',
        'Bank Transfer',
        'Cheque',
    ];

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // UPDATE 1: check the filters first
        $request->validate([
            'search'         => ['nullable', 'string', 'max:100'],
            'payment_method' => ['nullable', Rule::in(self::METHODS)],
            'payment_date'   => ['nullable', 'date_format:Y-m-d'],
        ]);

        $query = FeePayment::with([
            'studentFee.student',
            'studentFee.feeStructure.schoolClass',
            'studentFee.feeStructure.section',
        ]);

        // SEARCH
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhere('transaction_reference', 'like', "%{$search}%")
                    ->orWhereHas(
                        'studentFee.student',
                        function ($studentQuery) use ($search) {

                            $studentQuery
                                ->where('student_id', 'like', "%{$search}%")
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        }
                    );
            });
        }

        // PAYMENT METHOD FILTER
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // PAYMENT DATE FILTER
        if ($request->filled('payment_date')) {
            $query->whereDate('payment_date', $request->payment_date);
        }

        $payments = $query
            ->latest('payment_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // SUMMARY
        $totalPayments = FeePayment::count();

        $totalCollected = FeePayment::sum('amount');

        $todayCollected = FeePayment::whereDate(
            'payment_date',
            now()->toDateString()
        )->sum('amount');

        $cashCollected = FeePayment::where('payment_method', 'Cash')
            ->sum('amount');

        return view(
            'admin.fees.payment-history.index',
            compact(
                'payments',
                'totalPayments',
                'totalCollected',
                'todayCollected',
                'cashCollected'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PAYMENT
    |--------------------------------------------------------------------------
    */

    public function create(StudentFee $studentFee)
    {
        $studentFee->load([
            'student',
            'feeStructure.schoolClass',
            'feeStructure.section',
            'feeStructure.items.feeType',
            'payments',
        ]);

        return view(
            'admin.fees.student-fees.payment',
            compact('studentFee')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PAYMENT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, StudentFee $studentFee)
    {
        // UPDATE 2: stronger checks (date, remarks, decimals)
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],

            'payment_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],

            'payment_method' => [
                'required',
                Rule::in(self::METHODS),
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use ($validated, $studentFee) {

            // UPDATE 3: lock this fee row so nobody else can pay at the
            // same moment. Then read the REAL balance again.
            $fee = StudentFee::whereKey($studentFee->id)
                ->lockForUpdate()
                ->firstOrFail();

            $balance       = round((float) $fee->balance_amount, 2);
            $paymentAmount = round((float) $validated['amount'], 2);

            // Amount can never be more than the balance
            if ($paymentAmount > $balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Amount cannot be more than the balance ('
                        . number_format($balance, 2) . ').',
                ]);
            }

            // Unique receipt number
            do {
                $receiptNumber =
                    'REC-' .
                    now()->format('YmdHis') .
                    '-' .
                    random_int(100, 999);

            } while (
                FeePayment::where('receipt_number', $receiptNumber)->exists()
            );

            // Create payment
            FeePayment::create([
                'student_fee_id'        => $fee->id,
                'receipt_number'        => $receiptNumber,
                'amount'                => $paymentAmount,
                'payment_date'          => $validated['payment_date'],
                'payment_method'        => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'remarks'               => $validated['remarks'] ?? null,
            ]);

            // New amounts
            $newPaidAmount = round((float) $fee->paid_amount + $paymentAmount, 2);
            $newBalance    = round(max(0, $balance - $paymentAmount), 2);

            $newStatus = $newBalance <= 0 ? 'Paid' : 'Partial';

            // Update student fee
            $fee->update([
                'paid_amount'    => $newPaidAmount,
                'balance_amount' => $newBalance,
                'status'         => $newStatus,
            ]);
        });

        return redirect()
            ->route('admin.fees.student-fees.show', $studentFee)
            ->with('success', 'Payment recorded successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PAYMENT
    |--------------------------------------------------------------------------
    */

    public function show(FeePayment $feePayment)
    {
        $feePayment->load([
            'studentFee.student',
            'studentFee.feeStructure.schoolClass',
            'studentFee.feeStructure.section',
            'studentFee.feeStructure.items.feeType',
        ]);

        return view(
            'admin.fees.payment-history.show',
            compact('feePayment')
        );
    }
    /**
 * Bulk delete multiple records.
 */

}