<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = FeePayment::with([
            'studentFee.student',
            'studentFee.feeStructure.schoolClass',
            'studentFee.feeStructure.section',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'receipt_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'transaction_reference',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'studentFee.student',
                    function ($studentQuery) use ($search) {

                        $studentQuery
                            ->where(
                                'student_id',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'middle_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_method')) {

            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_date')) {

            $query->whereDate(
                'payment_date',
                $request->payment_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        $payments = $query
            ->latest('payment_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalPayments = FeePayment::count();

        $totalCollected = FeePayment::sum('amount');

        $todayCollected = FeePayment::whereDate(
            'payment_date',
            now()->toDateString()
        )->sum('amount');

        $cashCollected = FeePayment::where(
            'payment_method',
            'Cash'
        )->sum('amount');

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

    public function store(
        Request $request,
        StudentFee $studentFee
    ) {
        $studentFee->load('payments');

        /*
        |--------------------------------------------------------------------------
        | CURRENT BALANCE
        |--------------------------------------------------------------------------
        */

        $balance = (float) $studentFee->balance_amount;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'lte:' . $balance,
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'in:Cash,UPI,Card,Bank Transfer,Cheque',
            ],

            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE PAYMENT + UPDATE STUDENT FEE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $studentFee
        ) {

            $paymentAmount = (float) $validated['amount'];

            /*
            |--------------------------------------------------------------------------
            | GENERATE UNIQUE RECEIPT NUMBER
            |--------------------------------------------------------------------------
            */

            do {

                $receiptNumber =
                    'REC-' .
                    now()->format('YmdHis') .
                    '-' .
                    random_int(100, 999);

            } while (
                FeePayment::where(
                    'receipt_number',
                    $receiptNumber
                )->exists()
            );

            /*
            |--------------------------------------------------------------------------
            | CREATE PAYMENT
            |--------------------------------------------------------------------------
            */

            FeePayment::create([
                'student_fee_id' =>
                    $studentFee->id,

                'receipt_number' =>
                    $receiptNumber,

                'amount' =>
                    $paymentAmount,

                'payment_date' =>
                    $validated['payment_date'],

                'payment_method' =>
                    $validated['payment_method'],

                'transaction_reference' =>
                    $validated['transaction_reference']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE PAID AMOUNT
            |--------------------------------------------------------------------------
            */

            $newPaidAmount =
                (float) $studentFee->paid_amount
                + $paymentAmount;

            /*
            |--------------------------------------------------------------------------
            | CALCULATE NEW BALANCE
            |--------------------------------------------------------------------------
            */

            $newBalance =
                max(
                    0,
                    (float) $studentFee->balance_amount
                    - $paymentAmount
                );

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            if ($newBalance <= 0) {

                $newStatus = 'Paid';

            } elseif ($newPaidAmount > 0) {

                $newStatus = 'Partial';

            } else {

                $newStatus = 'Pending';
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE STUDENT FEE
            |--------------------------------------------------------------------------
            */

            $studentFee->update([

                'paid_amount' =>
                    $newPaidAmount,

                'balance_amount' =>
                    $newBalance,

                'status' =>
                    $newStatus,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.fees.student-fees.show',
                $studentFee
            )
            ->with(
                'success',
                'Payment recorded successfully.'
            );
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
}
