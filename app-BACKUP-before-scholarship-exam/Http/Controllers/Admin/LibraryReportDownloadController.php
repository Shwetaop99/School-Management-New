<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookIssue;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibraryReportExport;
use App\Models\Book;

class LibraryReportDownloadController extends Controller
{
   public function pdf(Request $request)
{
    $query = BookIssue::with('book')->latest('issue_date');

    if ($request->filled('from_date')) {
        $query->whereDate('issue_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('issue_date', '<=', $request->to_date);
    }

    if ($request->filled('student_id')) {
        $query->where(
            'student_id',
            'like',
            '%' . $request->student_id . '%'
        );
    }

    if ($request->filled('book_id')) {
        $query->where('book_id', $request->book_id);
    }

    if ($request->filled('status')) {

        if ($request->status === 'Overdue') {

            $query->whereNull('return_date')
                  ->whereDate('due_date', '<', today());

        } else {

            $query->where('status', $request->status);
        }
    }

    // Transactions
    $transactions = $query->get();

    // Book stock
    $bookStock = Book::orderBy('title')->get();

    // Generate PDF
    $pdf = Pdf::loadView(
        'admin.library.reports.pdf',
        compact('transactions', 'bookStock')
    );

    return $pdf->download('library-report.pdf');
}


    public function excel(Request $request)
    {
        return Excel::download(
            new LibraryReportExport($request),
            'library-report.xlsx'
        );
    }
}


