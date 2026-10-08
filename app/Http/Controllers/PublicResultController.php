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
    /*
    |--------------------------------------------------------------------------
    | PUBLIC RESULT VERIFICATION PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $school = SchoolSetting::first();

        $captcha = $this->generateCaptcha();

        return view(
            'public.result',
            compact('school', 'captcha')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE CAPTCHA
    |--------------------------------------------------------------------------
    */

    private function generateCaptcha(): string
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

        Session::put(
            'result_captcha',
            $captcha
        );

        return $captcha;
    }


    /*
    |--------------------------------------------------------------------------
    | REFRESH CAPTCHA
    |--------------------------------------------------------------------------
    */

    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptcha();

        return response()->json([
            'captcha' => $captcha,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY RESULT
    |--------------------------------------------------------------------------
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
        | CAPTCHA CHECK
        |--------------------------------------------------------------------------
        */

        $enteredCaptcha = trim(
            $request->input('captcha')
        );

        $storedCaptcha = Session::get(
            'result_captcha'
        );


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
        | REMOVE USED CAPTCHA
        |--------------------------------------------------------------------------
        */

        Session::forget(
            'result_captcha'
        );


        /*
        |--------------------------------------------------------------------------
        | FIND STUDENT
        |--------------------------------------------------------------------------
        */

        $studentId = trim(
            $request->input('student_id')
        );

        $student = Student::where(
            'student_id',
            $studentId
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
        | MOTHER NAME CHECK
        |--------------------------------------------------------------------------
        */

        $enteredMotherName = trim(
            $request->input('mother_name')
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
        | FIND LATEST PUBLISHED RESULT
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
            'details.subject',
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
        | STORE VERIFIED RESULT IN SESSION
        |--------------------------------------------------------------------------
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
        | REDIRECT TO RESULT DISPLAY
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
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

        $verifiedResultId = Session::get(
            'verified_result_id'
        );

        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        /*
        |--------------------------------------------------------------------------
        | USER HAS NOT VERIFIED
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
        | LOAD RESULT
        |--------------------------------------------------------------------------
        */

        $result = Result::with([
            'student',
            'exam',
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

            Session::forget([
                'verified_result_id',
                'verified_student_id',
            ]);

            return redirect()
                ->route('result.public')
                ->with(
                    'error',
                    'This result is no longer available.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY STUDENT SESSION AGAIN
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
        | SCHOOL PROFILE
        |--------------------------------------------------------------------------
        */

        $school = SchoolSetting::first();


        /*
        |--------------------------------------------------------------------------
        | RESULT DETAILS
        |--------------------------------------------------------------------------
        */

        $details = $result->details ?? collect();


        /*
        |--------------------------------------------------------------------------
        | DISPLAY PAGE
        |--------------------------------------------------------------------------
        */

        return view(
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

    public function pdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        if (
            !$verifiedResultId ||
            !$verifiedStudentId
        ) {

            abort(
                403,
                'Please verify your result first.'
            );
        }


        $result = Result::with([
            'student',
            'exam',
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


        $school = SchoolSetting::first();


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


        return $pdf->stream(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(string $student)
    {
        $verifiedResultId = Session::get(
            'verified_result_id'
        );

        $verifiedStudentId = Session::get(
            'verified_student_id'
        );


        if (
            !$verifiedResultId ||
            !$verifiedStudentId
        ) {

            abort(
                403,
                'Please verify your result first.'
            );
        }


        $result = Result::with([
            'student',
            'exam',
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


        $school = SchoolSetting::first();


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


        return $pdf->download(
            'student-result-' .
            $result->student->student_id .
            '.pdf'
        );
    }
}
