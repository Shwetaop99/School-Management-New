<?php

namespace App\Exports;

use App\Models\BookIssue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LibraryReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = BookIssue::with('book')
            ->latest('issue_date');

        // From Date
        if ($this->request->filled('from_date')) {
            $query->whereDate(
                'issue_date',
                '>=',
                $this->request->from_date
            );
        }

        // To Date
        if ($this->request->filled('to_date')) {
            $query->whereDate(
                'issue_date',
                '<=',
                $this->request->to_date
            );
        }

        // Student ID
        if ($this->request->filled('student_id')) {
            $query->where(
                'student_id',
                'like',
                '%' . $this->request->student_id . '%'
            );
        }

        // Book
        if ($this->request->filled('book_id')) {
            $query->where(
                'book_id',
                $this->request->book_id
            );
        }

        // Status
        if ($this->request->filled('status')) {

            if ($this->request->status === 'Overdue') {

                $query->whereNull('return_date')
                    ->whereDate('due_date', '<', today());

            } else {

                $query->where(
                    'status',
                    $this->request->status
                );
            }
        }

        return $query->get();
    }


    public function headings(): array
    {
        return [
            'Book',
            'Author',
            'Student ID',
            'Issue Date',
            'Due Date',
            'Return Date',
            'Status',
            'Fine',
            'Fine Type',
            'Fine Reason',
            'Remarks',
        ];
    }


    public function map($issue): array
    {
        $status = $issue->status;

        // Dynamically identify overdue transactions
        if (
            !$issue->return_date &&
            $issue->due_date &&
            $issue->due_date->isPast()
        ) {
            $status = 'Overdue';
        }

        return [
            $issue->book->title ?? 'Deleted Book',
            $issue->book->author ?? '—',
            $issue->student_id,
            $issue->issue_date
                ? $issue->issue_date->format('d M Y')
                : '—',
            $issue->due_date
                ? $issue->due_date->format('d M Y')
                : '—',
            $issue->return_date
                ? $issue->return_date->format('d M Y')
                : '—',
            $status,
            $issue->fine ?? 0,
            $issue->fine_type ?? 'None',
            $issue->fine_reason ?? '—',
            $issue->remarks ?? '—',
        ];
    }
}