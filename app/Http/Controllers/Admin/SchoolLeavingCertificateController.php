<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolLeavingCertificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\SchoolSetting;

class SchoolLeavingCertificateController extends Controller
{
    /**
     * Display all school leaving certificates.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $certificates = SchoolLeavingCertificate::with('student')
            ->when($search, function ($query) use ($search) {

                $query->where('certificate_no', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($studentQuery) use ($search) {

                        $studentQuery
                            ->where('student_id', 'like', "%{$search}%")
                            ->orWhere('register_no', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.school-leaving-certificate.index',
            compact('certificates', 'search')
        );
    }


    /**
     * Show form for creating a new certificate.
     */
    public function create(Request $request)
    {
        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        $selectedStudent = null;

        if ($request->filled('student_id')) {
            $selectedStudent = Student::where('id', $request->student_id)
                ->first();
        }

        return view(
            'admin.school-leaving-certificate.create',
            compact('students', 'selectedStudent')
        );
    }


    /**
     * Store a newly created certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'certificate_no' => [
                'nullable',
                'string',
                'max:100',
                'unique:school_leaving_certificates,certificate_no',
            ],

            'leaving_date' => [
                'required',
                'date',
            ],

            'progress' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conduct' => [
                'nullable',
                'string',
                'max:255',
            ],

            'class_studying_since' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reason_for_leaving' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],

            'certificate_date' => [
                'nullable',
                'date',
            ],

            'class_teacher_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'principal_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'draft',
                    'issued',
                    'cancelled',
                ]),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Default Certificate Date
        |--------------------------------------------------------------------------
        */

        $validated['certificate_date']
            = $validated['certificate_date']
            ?? now()->toDateString();


        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $validated['status']
            = $validated['status']
            ?? 'issued';


        /*
        |--------------------------------------------------------------------------
        | Default Reason
        |--------------------------------------------------------------------------
        */

        $validated['reason_for_leaving']
            = $validated['reason_for_leaving']
            ?? 'Graduated';


        /*
        |--------------------------------------------------------------------------
        | Default Progress
        |--------------------------------------------------------------------------
        */

        $validated['progress']
            = $validated['progress']
            ?? 'Excellent';


        /*
        |--------------------------------------------------------------------------
        | Default Conduct
        |--------------------------------------------------------------------------
        */

        $validated['conduct']
            = $validated['conduct']
            ?? 'Excellent';


        /*
        |--------------------------------------------------------------------------
        | Create Certificate
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (&$validated) {

            /*
            |--------------------------------------------------------------------------
            | Generate Certificate Number
            |--------------------------------------------------------------------------
            */

            if (empty($validated['certificate_no'])) {

                $validated['certificate_no']
                    = $this->generateCertificateNumber();
            }


            SchoolLeavingCertificate::create($validated);
        });


        return redirect()
            ->route(
                'admin.school-leaving-certificate.show',
                SchoolLeavingCertificate::where(
                    'certificate_no',
                    $validated['certificate_no']
                )->first()
            )
            ->with(
                'success',
                'School Leaving Certificate created successfully.'
            );
    }


    /**
     * Display the specified certificate.
     */
    public function show(SchoolLeavingCertificate $schoolLeavingCertificate)
    {
        $schoolLeavingCertificate->load('student');

        return view(
            'admin.school-leaving-certificate.show',
            [
                'certificate' => $schoolLeavingCertificate,
            ]
        );
    }


    /**
     * Show form for editing certificate.
     */
    public function edit(
        SchoolLeavingCertificate $schoolLeavingCertificate
    ) {
        $schoolLeavingCertificate->load('student');

        $students = Student::query()
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'admin.school-leaving-certificate.edit',
            [
                'certificate' => $schoolLeavingCertificate,
                'students' => $students,
            ]
        );
    }


    /**
     * Update certificate.
     */
    public function update(
        Request $request,
        SchoolLeavingCertificate $schoolLeavingCertificate
    ) {
        $validated = $request->validate([

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'certificate_no' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(
                    'school_leaving_certificates',
                    'certificate_no'
                )->ignore($schoolLeavingCertificate->id),
            ],

            'leaving_date' => [
                'required',
                'date',
            ],

            'progress' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conduct' => [
                'nullable',
                'string',
                'max:255',
            ],

            'class_studying_since' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reason_for_leaving' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],

            'certificate_date' => [
                'nullable',
                'date',
            ],

            'class_teacher_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'principal_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'draft',
                    'issued',
                    'cancelled',
                ]),
            ],
        ]);


        $validated['certificate_date']
            = $validated['certificate_date']
            ?? now()->toDateString();


        $validated['status']
            = $validated['status']
            ?? $schoolLeavingCertificate->status
            ?? 'issued';


        $validated['reason_for_leaving']
            = $validated['reason_for_leaving']
            ?? 'Graduated';


        $validated['progress']
            = $validated['progress']
            ?? 'Excellent';


        $validated['conduct']
            = $validated['conduct']
            ?? 'Excellent';


        $schoolLeavingCertificate->update($validated);


        return redirect()
            ->route(
                'admin.school-leaving-certificate.show',
                $schoolLeavingCertificate
            )
            ->with(
                'success',
                'School Leaving Certificate updated successfully.'
            );
    }


    /**
     * Delete certificate.
     */
    public function destroy(
        SchoolLeavingCertificate $schoolLeavingCertificate
    ) {
        $schoolLeavingCertificate->delete();

        return redirect()
            ->route('admin.school-leaving-certificate.index')
            ->with(
                'success',
                'School Leaving Certificate deleted successfully.'
            );
    }


    /**
     * Print certificate.
     */
    public function print(
    SchoolLeavingCertificate $schoolLeavingCertificate
) {
    $schoolLeavingCertificate->load('student');

    $school = SchoolSetting::first();

    return view(
        'admin.school-leaving-certificate.print',
        [
            'certificate' => $schoolLeavingCertificate,
            'school' => $school,
        ]
    );
}


    /**
     * Generate unique certificate number.
     */
    private function generateCertificateNumber(): string
    {
        $year = now()->format('Y');

        $lastCertificate = SchoolLeavingCertificate::query()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();

        if (!$lastCertificate) {
            $number = 1;
        } else {

            $lastNumber = (int) preg_replace(
                '/[^0-9]/',
                '',
                $lastCertificate->certificate_no
            );

            $number = $lastNumber + 1;
        }

        do {

            $certificateNo =
                'LC-' .
                $year .
                '-' .
                str_pad(
                    $number,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = SchoolLeavingCertificate::where(
                'certificate_no',
                $certificateNo
            )->exists();

            $number++;

        } while ($exists);


        return $certificateNo;
    }
}