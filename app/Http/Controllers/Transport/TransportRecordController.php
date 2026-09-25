<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Transport\TransportRecord;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransportRecordController extends Controller
{
    /**
     * Display all transport records.
     */
    public function index(Request $request)
    {
        $query = TransportRecord::with('student')
            ->latest();

        // =====================================================
        // SEARCH
        // =====================================================

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('route', 'like', '%' . $search . '%')
                    ->orWhere('vehicle', 'like', '%' . $search . '%')
                    ->orWhere('pickup_point', 'like', '%' . $search . '%')
                    ->orWhere('drop_point', 'like', '%' . $search . '%')

                    ->orWhereHas('student', function ($studentQuery) use ($search) {

                        $studentQuery
                            ->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('middle_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('student_id', 'like', '%' . $search . '%')
                            ->orWhere('roll_number', 'like', '%' . $search . '%');

                    });

            });
        }

        // =====================================================
        // STATUS FILTER
        // =====================================================

        if ($request->filled('status')) {

            $query->where(
                'transport_status',
                $request->status
            );

        }

        // =====================================================
        // PAGINATION
        // =====================================================

        $records = $query
            ->paginate(10)
            ->withQueryString();

        // =====================================================
        // SUMMARY COUNTS
        // =====================================================

        $totalRecords = TransportRecord::count();

        $activeRecords = TransportRecord::where(
            'transport_status',
            'active'
        )->count();

        $inactiveRecords = TransportRecord::where(
            'transport_status',
            'inactive'
        )->count();

        $assignedStudents = TransportRecord::whereNotNull(
            'student_id'
        )->count();

        // =====================================================
        // VIEW
        // =====================================================

        return view(
            'admin.transport.records.index',
            compact(
                'records',
                'totalRecords',
                'activeRecords',
                'inactiveRecords',
                'assignedStudents'
            )
        );
    }


    /**
     * Show the create transport record form.
     */
    public function create()
    {
        $students = Student::orderBy('first_name')->get();

        return view(
            'admin.transport.records.create',
            compact('students')
        );
    }


    /**
     * Store a new transport record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // =====================================================
            // STUDENT INFORMATION
            // =====================================================

            'student_id' => 'required|exists:students,id',

            // =====================================================
            // TRANSPORT INFORMATION
            // =====================================================

            'route' => 'nullable|string|max:255',

            'vehicle' => 'nullable|string|max:255',

            'pickup_point' => 'nullable|string|max:255',

            'drop_point' => 'nullable|string|max:255',

            'transport_status' => 'required|in:active,inactive',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',

            // =====================================================
            // TRAVEL DETAILS
            // =====================================================

            'transport_type' => [
                'nullable',
                'in:school_bus,van,private,other',
            ],

            'pickup_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'drop_time' => [
                'nullable',
                'date_format:H:i',
            ],

            // =====================================================
            // FEE INFORMATION
            // =====================================================

            'transport_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fee_frequency' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending,partially_paid',
            ],

            // =====================================================
            // ADDITIONAL INFORMATION
            // =====================================================

            'remarks' => 'nullable|string',
        ]);

        TransportRecord::create($validated);

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record added successfully.'
            );
    }


    /**
     * Display a specific transport record.
     */
    public function show(TransportRecord $transportRecord)
    {
        $transportRecord->load('student');

        return view(
            'admin.transport.records.show',
            compact('transportRecord')
        );
    }


    /**
     * Show the edit transport record form.
     */
    public function edit(TransportRecord $transportRecord)
    {
        $students = Student::orderBy('first_name')->get();

        return view(
            'admin.transport.records.edit',
            compact(
                'transportRecord',
                'students'
            )
        );
    }


    /**
     * Update an existing transport record.
     */
    public function update(
        Request $request,
        TransportRecord $transportRecord
    ) {
        $validated = $request->validate([

            // =====================================================
            // STUDENT INFORMATION
            // =====================================================

            'student_id' => 'required|exists:students,id',

            // =====================================================
            // TRANSPORT INFORMATION
            // =====================================================

            'route' => 'nullable|string|max:255',

            'vehicle' => 'nullable|string|max:255',

            'pickup_point' => 'nullable|string|max:255',

            'drop_point' => 'nullable|string|max:255',

            'transport_status' => 'required|in:active,inactive',

            'start_date' => 'nullable|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',

            // =====================================================
            // TRAVEL DETAILS
            // =====================================================

            'transport_type' => [
                'nullable',
                'in:school_bus,van,private,other',
            ],

            'pickup_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'drop_time' => [
                'nullable',
                'date_format:H:i',
            ],

            // =====================================================
            // FEE INFORMATION
            // =====================================================

            'transport_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fee_frequency' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'payment_status' => [
                'required',
                'in:paid,pending,partially_paid',
            ],

            // =====================================================
            // ADDITIONAL INFORMATION
            // =====================================================

            'remarks' => 'nullable|string',
        ]);

        $transportRecord->update($validated);

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record updated successfully.'
            );
    }


    /**
     * Download transport record as PDF.
     */
    public function downloadPdf(
        TransportRecord $transportRecord
    ) {
        $transportRecord->load('student');

        $pdf = Pdf::loadView(
            'admin.transport.records.pdf',
            compact('transportRecord')
        );

        $studentName =
            $transportRecord->student?->full_name
            ?? 'Student';

        $cleanStudentName = preg_replace(
            '/[^A-Za-z0-9\- ]/',
            '',
            $studentName
        );

        $fileName =
            'Transport-Record-'
            . trim($cleanStudentName)
            . '.pdf';

        return $pdf->download($fileName);
    }


    /**
     * Delete a transport record.
     */
    public function destroy(
        TransportRecord $transportRecord
    ) {
        $transportRecord->delete();

        return redirect()
            ->route('admin.transport.records.index')
            ->with(
                'success',
                'Transport record deleted successfully.'
            );
    }
}