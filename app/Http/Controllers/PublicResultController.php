<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicResultController extends Controller
{
<<<<<<< HEAD
    /**
     * Public result verification page.
     */
=======
    /*
    |--------------------------------------------------------------------------
    | PUBLIC RESULT VERIFICATION PAGE
    |--------------------------------------------------------------------------
    */

>>>>>>> origin/main
    public function index()
    {
        $school = SchoolSetting::first();

<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Generate Mixed CAPTCHA
        |--------------------------------------------------------------------------
        |
        | Example:
        | K7@p#3
        | A9&xP2
        | m4#Q8@
        |
        */

=======
>>>>>>> origin/main
        $captcha = $this->generateCaptcha();

        return view(
            'public.result',
            compact('school', 'captcha')
        );
    }


<<<<<<< HEAD
    /**
     * Generate mixed CAPTCHA.
     *
     * Contains:
     * - Uppercase letters
     * - Lowercase letters
     * - Numbers
     * - Special symbols
     */
    private function generateCaptcha()
=======
    /*
    |--------------------------------------------------------------------------
    | GENERATE CAPTCHA
    |--------------------------------------------------------------------------
    */

    private function generateCaptcha(): string
>>>>>>> origin/main
    {
        $characters =
            'ABCDEFGHJKLMNPQRSTUVWXYZ' .
            'abcdefghijkmnopqrstuvwxyz' .
            '23456789' .
            '@#$%&*';

        $captcha = '';

        for ($i = 0; $i < 6; $i++) {
<<<<<<< HEAD
=======

>>>>>>> origin/main
            $captcha .= $characters[
                random_int(
                    0,
                    strlen($characters) - 1
                )
            ];
        }

<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Store CAPTCHA in Session
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        Session::put(
            'result_captcha',
            $captcha
        );

        return $captcha;
    }


<<<<<<< HEAD
    /**
     * Refresh CAPTCHA using AJAX.
     */
=======
    /*
    |--------------------------------------------------------------------------
    | REFRESH CAPTCHA
    |--------------------------------------------------------------------------
    */

>>>>>>> origin/main
    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptcha();

        return response()->json([
            'captcha' => $captcha,
        ]);
    }


<<<<<<< HEAD
    /**
     * Verify student result.
     *
     * Student ID + Mother's Name + CAPTCHA
     */
=======
    /*
    |--------------------------------------------------------------------------
    | VERIFY RESULT
    |--------------------------------------------------------------------------
    */

>>>>>>> origin/main
    public function search(Request $request)
    {
        $request->validate([
            'student_id' => [
                'required',
                'string',
            ],

            'mother_name' => [
                'required',
                'string',
            ],

            'captcha' => [
                'required',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Verify CAPTCHA
=======
        | CAPTCHA CHECK
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        $enteredCaptcha = trim(
<<<<<<< HEAD
            $request->captcha
=======
            $request->input('captcha')
>>>>>>> origin/main
        );

        $storedCaptcha = Session::get(
            'result_captcha'
        );


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | CAPTCHA must exist
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        if (!$storedCaptcha) {

            return back()
                ->withInput(
                    $request->only([
                        'student_id',
                        'mother_name',
                    ])
                )
                ->withErrors([
                    'captcha' =>
                        'CAPTCHA has expired. Please refresh and try again.',
                ]);
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Compare CAPTCHA
        |--------------------------------------------------------------------------
        |
        | hash_equals() performs a safe string comparison.
        |
        */

=======
>>>>>>> origin/main
        if (
            !hash_equals(
                $storedCaptcha,
                $enteredCaptcha
            )
        ) {

            return back()
                ->withInput(
                    $request->only([
                        'student_id',
                        'mother_name',
                    ])
                )
                ->withErrors([
                    'captcha' =>
                        'Incorrect security verification.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Remove CAPTCHA after successful verification
        |--------------------------------------------------------------------------
        |
        | This prevents the same CAPTCHA from being reused.
        |
=======
        | REMOVE USED CAPTCHA
        |--------------------------------------------------------------------------
>>>>>>> origin/main
        */

        Session::forget(
            'result_captcha'
        );


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'student_id',
            trim($request->student_id)
=======
        | FIND STUDENT
        |--------------------------------------------------------------------------
        */

        $studentId = trim(
            $request->input('student_id')
        );

        $student = Student::where(
            'student_id',
            $studentId
>>>>>>> origin/main
        )->first();


        if (!$student) {

            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Student not found.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Verify Mother's Name
=======
        | MOTHER NAME CHECK
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        $enteredMotherName = trim(
<<<<<<< HEAD
            $request->mother_name
=======
            $request->input('mother_name')
>>>>>>> origin/main
        );

        $storedMotherName = trim(
            $student->mother_name ?? ''
        );


        if (
            $storedMotherName === '' ||
            strcasecmp(
                $enteredMotherName,
                $storedMotherName
            ) !== 0
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'mother_name' =>
                        'Mother\'s name does not match our records.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Find Latest Published Result
=======
        | FIND LATEST PUBLISHED RESULT
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
<<<<<<< HEAD
            'details',
=======
            'details.subject',
>>>>>>> origin/main
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->where(
                'publication_status',
                'published'
            )
            ->latest('id')
            ->first();


        if (!$result) {

            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'No published result is available for this student.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Store Verification Session
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Store the student's public Student ID,
        | not the database ID.
        |
=======
        | STORE VERIFIED RESULT IN SESSION
        |--------------------------------------------------------------------------
>>>>>>> origin/main
        */

        Session::put(
            'verified_result_id',
            $result->id
        );

        Session::put(
            'verified_student_id',
            $student->student_id
        );


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Redirect to Verified Result
=======
        | REDIRECT TO RESULT DISPLAY
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
<<<<<<< HEAD
            'result.public.show'
        );
    }


    /**
     * Show verified result.
     */
    public function show()
    {
=======
    'result.public.show',
    ['student' => $student->student_id]
);
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY VERIFIED RESULT
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, $student)
    {
        /*
        |--------------------------------------------------------------------------
        | GET VERIFIED SESSION DATA
        |--------------------------------------------------------------------------
        */

>>>>>>> origin/main
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Check Verification Session
=======
        | USER HAS NOT VERIFIED
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        if (
            !$verifiedResultId ||
            !$verifiedStudentId
        ) {

            return redirect()
                ->route('result.public')
                ->with(
                    'error',
                    'Please verify your result first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Get Result
=======
        | LOAD RESULT
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
<<<<<<< HEAD
            'details',
        ])
            ->find($verifiedResultId);


        if (
            !$result ||
            $result->publication_status !== 'published'
        ) {
=======
            'details.subject',
        ])
            ->where(
                'id',
                $verifiedResultId
            )
            ->where(
                'publication_status',
                'published'
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | RESULT NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (!$result) {
>>>>>>> origin/main

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            return redirect()
                ->route('result.public')
                ->with(
                    'error',
<<<<<<< HEAD
                    'This result is not available.'
=======
                    'This result is no longer available.'
>>>>>>> origin/main
                );
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Verify Student
=======
        | VERIFY STUDENT SESSION AGAIN
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        if (
            !$result->student ||
            (string) $result->student->student_id !==
            (string) $verifiedStudentId
        ) {

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            return redirect()
                ->route('result.public')
                ->with(
                    'error',
                    'Invalid result verification.'
                );
        }


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | School Information
=======
        | SCHOOL PROFILE
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
<<<<<<< HEAD
        | Show Result
=======
        | RESULT DETAILS
        |--------------------------------------------------------------------------
        */

        $details = $result->details ?? collect();


        /*
        |--------------------------------------------------------------------------
        | DISPLAY PAGE
>>>>>>> origin/main
        |--------------------------------------------------------------------------
        */

        return view(
<<<<<<< HEAD
            'public.result-show',
            compact(
                'result',
                'school'
            )
        );
    }


    /**
     * View verified result PDF in browser.
     */
=======
    'public.result-show',
    compact(
        'result',
        'details',
        'school'
    )
);
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW PDF
    |--------------------------------------------------------------------------
    */

>>>>>>> origin/main
    public function pdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

<<<<<<< HEAD

        if (!$verifiedResultId) {
=======
        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        if (
            !$verifiedResultId ||
            !$verifiedStudentId
        ) {

>>>>>>> origin/main
            abort(
                403,
                'Please verify your result first.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Get Published Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
=======
        $result = Result::with([
            'student',
            'exam',
            'details.subject',
>>>>>>> origin/main
        ])
            ->where(
                'id',
                $verifiedResultId
            )
            ->where(
                'publication_status',
                'published'
            )
            ->first();


        if (!$result) {

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            abort(
                403,
                'This result is not published.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Verify URL Student ID
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        if (
            !$result->student ||
            strcasecmp(
                trim($result->student->student_id),
                trim($student)
            ) !== 0
        ) {

            abort(
                403,
                'Invalid result verification.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

=======
        $school = SchoolSetting::first();


>>>>>>> origin/main
        $pdf = Pdf::loadView(
            'public.result-pdf',
            compact(
                'result',
                'school'
            )
        );

<<<<<<< HEAD
=======

>>>>>>> origin/main
        $pdf->setPaper(
            'A4',
            'portrait'
        );


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Stream PDF
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        return $pdf->stream(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }


<<<<<<< HEAD
    /**
     * Download verified result PDF.
     */
=======
    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

>>>>>>> origin/main
    public function downloadPdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

<<<<<<< HEAD

        if (!$verifiedResultId) {
=======
        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        if (
            !$verifiedResultId ||
            !$verifiedStudentId
        ) {

>>>>>>> origin/main
            abort(
                403,
                'Please verify your result first.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Get Published Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
=======
        $result = Result::with([
            'student',
            'exam',
            'details.subject',
>>>>>>> origin/main
        ])
            ->where(
                'id',
                $verifiedResultId
            )
            ->where(
                'publication_status',
                'published'
            )
            ->first();


        if (!$result) {

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            abort(
                403,
                'This result is not published.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Verify URL Student ID
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        if (
            !$result->student ||
            strcasecmp(
                trim($result->student->student_id),
                trim($student)
            ) !== 0
        ) {

            abort(
                403,
                'Invalid result verification.'
            );
        }


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | School Information
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

=======
        $school = SchoolSetting::first();


>>>>>>> origin/main
        $pdf = Pdf::loadView(
            'public.result-pdf',
            compact(
                'result',
                'school'
            )
        );

<<<<<<< HEAD
=======

>>>>>>> origin/main
        $pdf->setPaper(
            'A4',
            'portrait'
        );


<<<<<<< HEAD
        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */

=======
>>>>>>> origin/main
        return $pdf->download(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }
}
