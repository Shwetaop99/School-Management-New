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
    /**
     * Public result verification page.
     */
    public function index()
    {
        $school = SchoolSetting::first();

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

        $captcha = $this->generateCaptcha();

        return view(
            'public.result',
            compact('school', 'captcha')
        );
    }


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
    {
        $characters =
            'ABCDEFGHJKLMNPQRSTUVWXYZ' .
            'abcdefghijkmnopqrstuvwxyz' .
            '23456789' .
            '@#$%&*';

        $captcha = '';

        for ($i = 0; $i < 6; $i++) {
            $captcha .= $characters[
                random_int(
                    0,
                    strlen($characters) - 1
                )
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Store CAPTCHA in Session
        |--------------------------------------------------------------------------
        */

        Session::put(
            'result_captcha',
            $captcha
        );

        return $captcha;
    }


    /**
     * Refresh CAPTCHA using AJAX.
     */
    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptcha();

        return response()->json([
            'captcha' => $captcha,
        ]);
    }


    /**
     * Verify student result.
     *
     * Student ID + Mother's Name + CAPTCHA
     */
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
        | Verify CAPTCHA
        |--------------------------------------------------------------------------
        */

        $enteredCaptcha = trim(
            $request->captcha
        );

        $storedCaptcha = Session::get(
            'result_captcha'
        );


        /*
        |--------------------------------------------------------------------------
        | CAPTCHA must exist
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Compare CAPTCHA
        |--------------------------------------------------------------------------
        |
        | hash_equals() performs a safe string comparison.
        |
        */

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
        | Remove CAPTCHA after successful verification
        |--------------------------------------------------------------------------
        |
        | This prevents the same CAPTCHA from being reused.
        |
        */

        Session::forget(
            'result_captcha'
        );


        /*
        |--------------------------------------------------------------------------
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'student_id',
            trim($request->student_id)
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
        | Verify Mother's Name
        |--------------------------------------------------------------------------
        */

        $enteredMotherName = trim(
            $request->mother_name
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
        | Find Latest Published Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
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
        | Store Verification Session
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Store the student's public Student ID,
        | not the database ID.
        |
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
        | Redirect to Verified Result
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'result.public.show'
        );
    }


    /**
     * Show verified result.
     */
    public function show()
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        /*
        |--------------------------------------------------------------------------
        | Check Verification Session
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
        | Get Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
        ])
            ->find($verifiedResultId);


        if (
            !$result ||
            $result->publication_status !== 'published'
        ) {

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            return redirect()
                ->route('result.public')
                ->with(
                    'error',
                    'This result is not available.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Student
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
        | School Information
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | Show Result
        |--------------------------------------------------------------------------
        */

        return view(
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
    public function pdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );


        if (!$verifiedResultId) {
            abort(
                403,
                'Please verify your result first.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Published Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
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


        /*
        |--------------------------------------------------------------------------
        | Verify URL Student ID
        |--------------------------------------------------------------------------
        */

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

        $pdf = Pdf::loadView(
            'public.result-pdf',
            compact(
                'result',
                'school'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | Stream PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->stream(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }


    /**
     * Download verified result PDF.
     */
    public function downloadPdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );


        if (!$verifiedResultId) {
            abort(
                403,
                'Please verify your result first.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Published Result
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details',
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


        /*
        |--------------------------------------------------------------------------
        | Verify URL Student ID
        |--------------------------------------------------------------------------
        */

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

        $pdf = Pdf::loadView(
            'public.result-pdf',
            compact(
                'result',
                'school'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }
}
