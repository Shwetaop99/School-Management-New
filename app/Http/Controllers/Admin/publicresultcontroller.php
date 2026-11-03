<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PublicResultController extends Controller
{
    // Column in the students table that stores the mother's name.
    // Change this if your column has a different name.
    private const MOTHER_NAME_FIELD = 'mother_name';

    // PROTECTION 4: one message for every wrong try
    private const GENERIC_ERROR =
        'The details you entered do not match our records, or no result has been published yet.';

    private const NO_CACHE = 'no-store, no-cache, must-revalidate, private';

    /*
    |--------------------------------------------------------------------------
    | SHOW THE FORM
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // PROTECTION 2: new captcha question every time the form opens
        $a = random_int(1, 9);
        $b = random_int(1, 9);

        session([
            'result_captcha' => [
                'answer'  => $a + $b,
                'expires' => now()->addMinutes(10)->timestamp,
            ],
        ]);

        return response()
            ->view('public.publish', [
                'captchaQuestion' => "{$a} + {$b}",
                'results'         => null,
                'student'         => null,
            ])
            ->header('Cache-Control', self::NO_CACHE);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK DETAILS AND SHOW RESULT
    |--------------------------------------------------------------------------
    */

    public function show(Request $request)
    {
        $request->validate([
            'student_id'  => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'min:3', 'max:100'],
            'captcha'     => ['required', 'string', 'max:5'],
        ]);

        $studentId = trim((string) $request->student_id);
        $ip        = $request->ip();

        // PROTECTION 1: limits (the route also has throttle:5,1)
        $studentKey = 'public-result-student:' . sha1(mb_strtolower($studentId));
        $ipKey      = 'public-result-ip:' . $ip;

        if (
            RateLimiter::tooManyAttempts($studentKey, 10) ||
            RateLimiter::tooManyAttempts($ipKey, 30)
        ) {
            Log::warning('Public result: blocked (too many attempts)', [
                'ip'         => $ip,
                'student_id' => mb_substr($studentId, 0, 50),
            ]);

            return $this->fail(
                $request,
                'Too many attempts. Please try again after some time.'
            );
        }

        // PROTECTION 2: captcha is checked on the server, one use only
        $captcha = session()->pull('result_captcha');

        $captchaOk =
            is_array($captcha) &&
            ($captcha['expires'] ?? 0) >= now()->timestamp &&
            hash_equals(
                (string) ($captcha['answer'] ?? ''),
                trim((string) $request->captcha)
            );

        if (!$captchaOk) {
            RateLimiter::hit($ipKey, 3600);

            return $this->fail(
                $request,
                'The security answer is wrong. Please try again.'
            );
        }

        // PROTECTION 3: mother's name is checked on the server
        $student = Student::query()
            ->where('student_id', $studentId)
            ->first();

        $nameOk = false;

        if ($student) {
            $stored = $this->normalizeName($student->{self::MOTHER_NAME_FIELD});
            $given  = $this->normalizeName($request->mother_name);

            // an empty stored name must never match
            $nameOk = mb_strlen($stored) >= 3 && hash_equals($stored, $given);
        }

        // PROTECTION 5: only published results can be seen
        $results = collect();

        if ($nameOk) {
            $results = Result::with(['exam', 'details'])
                ->where('student_id', $student->id)
                ->where('publication_status', 'published')
                ->orderByDesc('published_at')
                ->get();
        }

        if (!$nameOk || $results->isEmpty()) {

            RateLimiter::hit($studentKey, 3600);
            RateLimiter::hit($ipKey, 3600);

            Log::warning('Public result: failed attempt', [
                'ip'         => $ip,
                'student_id' => mb_substr($studentId, 0, 50),
            ]);

            // PROTECTION 4: same message for wrong ID, wrong name and no result
            return $this->fail($request, self::GENERIC_ERROR);
        }

        RateLimiter::clear($studentKey);

        // PROTECTION 6: every successful view is written to the log
        $logData = [
            'ip'         => $ip,
            'student_id' => $student->student_id,
            'result_ids' => $results->pluck('id')->all(),
        ];

        Log::info('Public result viewed', $logData);

        try {
            activity()
                ->withProperties($logData)
                ->log('Public result viewed');
        } catch (\Throwable $e) {
            // logging must never stop the parent from seeing the result
        }

        return response()
            ->view('public.result', [
                'captchaQuestion' => null,
                'results'         => $results,
                'student'         => $student,
            ])
            ->header('Cache-Control', self::NO_CACHE);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function fail(Request $request, string $message)
    {
        // the mother's name is never sent back to the form
        return redirect()
            ->route('result.public')
            ->withInput($request->only('student_id'))
            ->withErrors(['result' => $message]);
    }

    // "  Sunita  D. Patil " -> "sunita d patil" (letters, marks and numbers only)
    private function normalizeName(?string $name): string
    {
        $name = mb_strtolower(trim((string) $name));

        $name = preg_replace('/[^\p{L}\p{M}\p{N}\s]/u', '', $name);

        return trim(preg_replace('/\s+/u', ' ', $name));
    }
}