<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonafideCertificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class BonafideCertificateController extends Controller
{
    /**
     * Display all bonafide certificates.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $certificates = BonafideCertificate::with('student')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('certificate_no', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%")
                        ->orWhereHas('student', function ($studentQuery) use ($search) {

                            $studentQuery
                                ->where('student_id', 'like', "%{$search}%")
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('middle_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.bonafide-certificate.index',
            compact('certificates', 'search')
        );
    }

    /**
     * Show create certificate form.
     */
    public function create()
    {
        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.bonafide-certificate.create',
            compact('students')
        );
    }

    /**
     * Store a new bonafide certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'student_id' => [
                    'required',
                    'integer',
                    'exists:students,id',
                ],

                'issue_date' => [
                    'required',
                    'date',
                ],

                'reason' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'status' => [
                    'required',
                    'in:active,inactive',
                ],
            ],
            [
                'student_id.required' =>
                    'Please select a student.',

                'student_id.integer' =>
                    'Invalid student selected.',

                'student_id.exists' =>
                    'The selected student does not exist.',

                'issue_date.required' =>
                    'Please select the certificate issue date.',

                'issue_date.date' =>
                    'Please enter a valid issue date.',

                'reason.required' =>
                    'Please enter the reason for issuing the certificate.',

                'reason.string' =>
                    'The reason must be valid text.',

                'reason.max' =>
                    'The reason cannot contain more than 500 characters.',

                'status.required' =>
                    'Please select the certificate status.',

                'status.in' =>
                    'The selected certificate status is invalid.',
            ]
        );

        /*
         * Only active students can receive a certificate.
         */
        $student = Student::where('id', $validated['student_id'])
            ->where('status', 'active')
            ->first();

        if (!$student) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'The selected student is not active or does not exist.',
                ]);
        }

        try {

            $certificate = DB::transaction(function () use ($validated) {

                /*
                 * Generate unique certificate number.
                 * Example: BON-2026-A8K4P2
                 */
                do {
                    $certificateNumber =
                        'BON-' .
                        now()->format('Y') .
                        '-' .
                        strtoupper(Str::random(6));

                } while (
                    BonafideCertificate::where(
                        'certificate_no',
                        $certificateNumber
                    )->exists()
                );

                return BonafideCertificate::create([
                    'student_id' => $validated['student_id'],

                    'certificate_no' => $certificateNumber,

                    'issue_date' => $validated['issue_date'],

                    'reason' => trim($validated['reason']),

                    'status' => $validated['status'],
                ]);
            });

            return redirect()
                ->route(
                    'admin.bonafide.show',
                    $certificate->id
                )
                ->with(
                    'success',
                    'Bonafide certificate created successfully.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Bonafide certificate creation failed.',
                [
                    'student_id' =>
                        $validated['student_id'] ?? null,

                    'error' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create the bonafide certificate. Please check the form and try again.'
                );
        }
    }

    /**
     * Display a certificate.
     */
    public function show($id)
    {
        $certificate = BonafideCertificate::with('student')
            ->findOrFail($id);

        return view(
            'admin.bonafide-certificate.show',
            compact('certificate')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $certificate = BonafideCertificate::with('student')
            ->findOrFail($id);

        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.bonafide-certificate.edit',
            compact('certificate', 'students')
        );
    }

    /**
     * Update certificate.
     */
    public function update(Request $request, $id)
    {
        $certificate = BonafideCertificate::findOrFail($id);

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        $student = Student::where('id', $validated['student_id'])
            ->where('status', 'active')
            ->first();

        if (!$student) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'The selected student is not active.',
                ]);
        }

        try {

            $certificate->update([
                'student_id' => $validated['student_id'],
                'issue_date' => $validated['issue_date'],
                'reason' => trim($validated['reason']),
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route(
                    'admin.bonafide.show',
                    $certificate->id
                )
                ->with(
                    'success',
                    'Bonafide certificate updated successfully.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Bonafide certificate update failed.',
                [
                    'certificate_id' => $id,
                    'error' => $e->getMessage(),
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the bonafide certificate.'
                );
        }
    }

    /**
     * Delete certificate.
     */
    public function destroy($id)
    {
        try {

            $certificate =
                BonafideCertificate::findOrFail($id);

            $certificate->delete();

            return redirect()
                ->route('admin.bonafide.index')
                ->with(
                    'success',
                    'Bonafide certificate deleted successfully.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Bonafide certificate deletion failed.',
                [
                    'certificate_id' => $id,
                    'error' => $e->getMessage(),
                ]
            );

            return back()
                ->with(
                    'error',
                    'Unable to delete the bonafide certificate.'
                );
        }
    }
}