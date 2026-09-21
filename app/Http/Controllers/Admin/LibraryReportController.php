<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookIssue;
use Illuminate\Http\Request;

class LibraryReportController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $status = $request->status;
        $studentId = $request->student_id;
        $bookId = $request->book_id;

        /*
        |--------------------------------------------------------------------------
        | Library Overview
        |--------------------------------------------------------------------------
        */

        $totalBooks = Book::count();

        $totalCopies = Book::sum('quantity');

        $availableCopies = Book::sum('available_quantity');

        $issuedCopies = $totalCopies - $availableCopies;

        $returnedBooks = BookIssue::whereNotNull('return_date')->count();

        $overdueBooks = BookIssue::whereNull('return_date')
            ->whereDate('due_date', '<', today())
            ->count();

        $totalFines = BookIssue::sum('fine');

        $pendingFines = BookIssue::where('fine', '>', 0)
    ->whereNull('return_date')
    ->sum('fine');


        /*
        |--------------------------------------------------------------------------
        | Transactions
        |--------------------------------------------------------------------------
        */

        $transactionsQuery = BookIssue::with('book')
            ->latest('issue_date');


        // From date
        if ($fromDate) {
            $transactionsQuery->whereDate('issue_date', '>=', $fromDate);
        }

        // To date
        if ($toDate) {
            $transactionsQuery->whereDate('issue_date', '<=', $toDate);
        }

        // Student
        if ($studentId) {
            $transactionsQuery->where('student_id', 'like', '%' . $studentId . '%');
        }

        // Book
        if ($bookId) {
            $transactionsQuery->where('book_id', $bookId);
        }

        // Status
        if ($status) {

            if ($status === 'Overdue') {

                $transactionsQuery
                    ->whereNull('return_date')
                    ->whereDate('due_date', '<', today());

            } else {

                $transactionsQuery->where('status', $status);

            }
        }


        $transactions = $transactionsQuery
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Books for Filter
        |--------------------------------------------------------------------------
        */

        $books = Book::orderBy('title')->get();
        $bookStock = Book::orderBy('title')->get();


        return view('admin.library.reports.index', compact(
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'issuedCopies',
            'returnedBooks',
            'overdueBooks',
            'totalFines',
            'pendingFines',
            'transactions',
            'books',
            'fromDate',
            'toDate',
            'status',
            'studentId',
            'bookId',
            'bookStock'
        ));
    }
}