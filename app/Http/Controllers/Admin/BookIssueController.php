<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookIssueController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ISSUE / RETURN TRANSACTIONS PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $issues = BookIssue::with('book')
            ->latest()
            ->paginate(15);

        return view(
            'admin.library.issues.index',
            compact('issues')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ISSUE BOOK PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $books = Book::where('available_quantity', '>', 0)
            ->where('status', 'Available')
            ->orderBy('title')
            ->get();

        return view(
            'admin.library.issues.create',
            compact('books')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ISSUE BOOK
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|integer',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'remarks' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {

            $book = Book::lockForUpdate()
                ->findOrFail($validated['book_id']);

            /*
            |--------------------------------------------------------------------------
            | Check Availability
            |--------------------------------------------------------------------------
            */

            if ($book->available_quantity <= 0) {
                throw new \RuntimeException(
                    'This book is currently unavailable.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Issue Record
            |--------------------------------------------------------------------------
            */

            BookIssue::create([
                'book_id' => $book->id,
                'student_id' => $validated['student_id'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'status' => 'Issued',
                'fine' => 0,
                'fine_type' => 'None',
                'fine_reason' => null,
                'remarks' => $validated['remarks'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Reduce Available Quantity
            |--------------------------------------------------------------------------
            */

            $book->decrement('available_quantity');

            $book->refresh();


            /*
            |--------------------------------------------------------------------------
            | Update Book Status
            |--------------------------------------------------------------------------
            */

            $book->update([
                'status' => $book->available_quantity > 0
                    ? 'Available'
                    : 'Unavailable',
            ]);
        });

        return redirect()
            ->route('admin.library.issues.index')
            ->with('success', 'Book issued successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | RETURN BOOKS PAGE
    |--------------------------------------------------------------------------
    */

    public function returns()
    {
        /*
        |--------------------------------------------------------------------------
        | Automatically mark overdue books
        |--------------------------------------------------------------------------
        */

        BookIssue::whereNull('return_date')
            ->where('status', 'Issued')
            ->whereDate('due_date', '<', today())
            ->update([
                'status' => 'Overdue',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Get All Currently Unreturned Books
        |--------------------------------------------------------------------------
        */

        $returns = BookIssue::with('book')
            ->whereNull('return_date')
            ->latest()
            ->paginate(15);

        return view(
            'admin.library.returns.index',
            compact('returns')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FINES PAGE
    |--------------------------------------------------------------------------
    */

    public function fines()
    {
        $fines = BookIssue::with('book')
            ->where('fine', '>', 0)
            ->latest()
            ->paginate(15);

        return view(
            'admin.library.fines.index',
            compact('fines')
        );
    }

    /*
|--------------------------------------------------------------------------
| MARK FINE PAID / PENDING
|--------------------------------------------------------------------------
*/

public function updateFineStatus(Request $request, BookIssue $issue)
{
    $validated = $request->validate([
        'fine_status' => 'required|in:Pending,Paid',
    ]);

    // Only transactions with an actual fine can have a payment status.
    if ((float) $issue->fine <= 0) {
        return back()
            ->with('error', 'This book does not have a fine.');
    }

    $issue->update([
        'fine_status' => $validated['fine_status'],
    ]);

    return back()->with(
        'success',
        $validated['fine_status'] === 'Paid'
            ? 'Fine marked as paid successfully.'
            : 'Fine marked as pending successfully.'
    );
}


    /*
    |--------------------------------------------------------------------------
    | RETURN BOOK
    |--------------------------------------------------------------------------
    */

    public function returnBook(Request $request, BookIssue $issue)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Return
        |--------------------------------------------------------------------------
        */

        if ($issue->status === 'Returned' || $issue->return_date !== null) {
            return back()
                ->with('error', 'This book has already been returned.');
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Return Form
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'fine_type' => [
                'required',
                'in:None,Late Return,Book Damaged,Book Lost,Pages Damaged,Cover Damaged,Other',
            ],

            'fine' => [
                'required',
                'numeric',
                'min:0',
            ],

            'fine_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Late Fine
        |--------------------------------------------------------------------------
        */

        $returnDate = today();

        $lateDays = 0;
        $lateFine = 0;

        if (
            $issue->due_date &&
            $returnDate->greaterThan($issue->due_date)
        ) {
            $lateDays = $issue->due_date->diffInDays($returnDate);

            // ₹5 per late day
            $lateFine = $lateDays * 5;
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Final Fine
        |--------------------------------------------------------------------------
        */

        $fine = (float) $validated['fine'];

        /*
        | If Late Return is selected and Admin leaves the amount as 0,
        | automatically apply ₹5 per late day.
        */

        if (
            $validated['fine_type'] === 'Late Return' &&
            $fine <= 0
        ) {
            $fine = $lateFine;
        }


        /*
        |--------------------------------------------------------------------------
        | Automatically Create Late Fine Reason
        |--------------------------------------------------------------------------
        */

        $fineReason = $validated['fine_reason'] ?? null;

        if (
            $validated['fine_type'] === 'Late Return' &&
            $lateDays > 0 &&
            empty($fineReason)
        ) {
            $fineReason =
                'Book returned ' .
                $lateDays .
                ' day(s) late. Late fine calculated at ₹5 per day.';
        }


        /*
        |--------------------------------------------------------------------------
        | Update Issue + Book
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $issue,
            $returnDate,
            $fine,
            $validated,
            $fineReason
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Issue Record
            |--------------------------------------------------------------------------
            */

            $issue = BookIssue::lockForUpdate()
                ->findOrFail($issue->id);


            /*
            |--------------------------------------------------------------------------
            | Double-Check Return Status
            |--------------------------------------------------------------------------
            */

            if (
                $issue->status === 'Returned' ||
                $issue->return_date !== null
            ) {
                throw new \RuntimeException(
                    'This book has already been returned.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Issue
            |--------------------------------------------------------------------------
            */

           $issue->update([
    'return_date' => $returnDate,
    'status' => 'Returned',
    'fine' => $fine,
    'fine_type' => $validated['fine_type'],
    'fine_reason' => $fineReason,
    'fine_status' => $fine > 0 ? 'Pending' : 'Paid',
    'remarks' => $validated['remarks'] ?? $issue->remarks,
]);


            /*
            |--------------------------------------------------------------------------
            | Increase Available Quantity
            |--------------------------------------------------------------------------
            */

            $book = Book::lockForUpdate()
                ->find($issue->book_id);

            if ($book) {

                $book->increment('available_quantity');

                $book->refresh();


                /*
                |--------------------------------------------------------------------------
                | Update Book Status
                |--------------------------------------------------------------------------
                */

                $book->update([
                    'status' => $book->available_quantity > 0
                        ? 'Available'
                        : 'Unavailable',
                ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Return To Return Books Page
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.library.returns.index')
            ->with(
                'success',
                'Book returned successfully.'
            );
    }
}