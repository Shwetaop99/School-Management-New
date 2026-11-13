<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification page during login.
     *
     * This page NEVER displays the QR code or secret.
     */
    public function showSetup()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | If 2FA is disabled
        |--------------------------------------------------------------------------
        |
        | User does not need to enter an authenticator code during login.
        |
        */

        if (!$user->two_factor_enabled) {
            session()->put('two_factor_verified', true);

            return $this->redirectByRole($user);
        }

        /*
        |--------------------------------------------------------------------------
        | If 2FA has already been verified
        |--------------------------------------------------------------------------
        */

        if (session('two_factor_verified') === true) {
            return $this->redirectByRole($user);
        }

        /*
        |--------------------------------------------------------------------------
        | Show login verification page
        |--------------------------------------------------------------------------
        |
        | We reuse the existing 2FA Blade page but DO NOT pass the secret
        | or QR code to it.
        |
        */

        return view('admin.auth.2fa-setup', [
            'secret'    => null,
            'qrCodeUrl' => null,
            'loginMode' => true,
        ]);
    }


    /**
     * Verify the 6-digit authenticator code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure 2FA is actually enabled
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_enabled) {
            $request->session()->put(
                'two_factor_verified',
                true
            );

            return $this->redirectByRole($user);
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure account is still active
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'Active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'This account is inactive. Please contact the administrator.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure role is still active
        |--------------------------------------------------------------------------
        */

        $user->load('role');

        if (!$user->role || $user->role->status !== 'Active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Your assigned role is inactive or unavailable. Please contact the administrator.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure secret exists
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_secret) {

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Two-factor authentication is not configured correctly. Please contact the administrator.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Google Authenticator code
        |--------------------------------------------------------------------------
        */

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        /*
        |--------------------------------------------------------------------------
        | Invalid Code
        |--------------------------------------------------------------------------
        */

        if (!$valid) {

            return back()
                ->withErrors([
                    'code' => 'Invalid verification code. Please try again.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | 2FA Successful
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'two_factor_verified',
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Store Current Role
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'user_role',
            $user->role->name
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect According To Role
        |--------------------------------------------------------------------------
        */

        return $this->redirectByRole($user);
    }


    /**
     * Show Security / Two-Factor Authentication settings.
     */
   public function security()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('admin.login');
    }

    $google2fa = new Google2FA();

    /*
    |--------------------------------------------------------------------------
    | Generate secret if the account does not have one
    |--------------------------------------------------------------------------
    */

    if (!$user->two_factor_secret) {

        $user->two_factor_secret = $google2fa->generateSecretKey();

        $user->save();
    }

    $secret = null;
    $qrCode = null;

    /*
    |--------------------------------------------------------------------------
    | Generate QR code while 2FA is disabled
    |--------------------------------------------------------------------------
    */

    if (!$user->two_factor_enabled) {

        $secret = $user->two_factor_secret;

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Gurukul Vidyalaya',
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrCode = $writer->writeString($qrCodeUrl);
    }

    return view('admin.settings.security', [
        'user'      => $user,
        'secret'    => $secret,
        'qrCodeUrl' => $qrCode,
    ]);
}


    /**
     * Enable / complete 2FA setup.
     *
     * First-time setup generates a secret and requires the user
     * to verify the authenticator code before enabling 2FA.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | If no secret exists, create one.
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_secret) {

            $google2fa = new Google2FA();

            $user->two_factor_secret = $google2fa->generateSecretKey();

            $user->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Verify authenticator code
        |--------------------------------------------------------------------------
        */

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {

            return back()
                ->withErrors([
                    'code' => 'Invalid authenticator code. Please try again.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Enable 2FA
        |--------------------------------------------------------------------------
        */

        $user->two_factor_enabled = true;
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Mark current session as verified
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'two_factor_verified',
            true
        );

        return redirect()
            ->route('admin.2fa.security')
            ->with(
                'success',
                'Two-factor authentication has been enabled successfully.'
            );
    }


    /**
     * Disable 2FA.
     *
     * The current authenticator code is required.
     *
     * IMPORTANT:
     * The secret is intentionally NOT deleted.
     *
     * This allows 2FA to be re-enabled later without scanning
     * a new QR code.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure 2FA is currently enabled
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_enabled) {

            return redirect()
                ->route('admin.2fa.security')
                ->with(
                    'error',
                    'Two-factor authentication is already disabled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify current authenticator code
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_secret) {

            return redirect()
                ->route('admin.2fa.security')
                ->with(
                    'error',
                    'Two-factor authentication is not configured correctly.'
                );
        }

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {

            return back()
                ->withErrors([
                    'code' => 'Invalid authenticator code. 2FA was not disabled.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Disable 2FA
        |--------------------------------------------------------------------------
        |
        | Keep the secret so it can be reused when 2FA is enabled again.
        |
        */

        $user->two_factor_enabled = false;
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Reset current session verification
        |--------------------------------------------------------------------------
        */

        $request->session()->forget('two_factor_verified');

        return redirect()
            ->route('admin.2fa.security')
            ->with(
                'success',
                'Two-factor authentication has been disabled.'
            );
    }


    /**
     * Redirect the authenticated user according to their assigned role.
     */
    private function redirectByRole($user)
    {
        $user->load('role');

        /*
        |--------------------------------------------------------------------------
        | Make sure a role exists
        |--------------------------------------------------------------------------
        */

        if (!$user->role) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No role is assigned to this account.');
        }

        /*
        |--------------------------------------------------------------------------
        | Role-based destinations
        |--------------------------------------------------------------------------
        */

        $roleRoutes = [

            // Super Admin
            'super_admin' => 'admin.dashboard',

            // Admin
            'admin' => 'admin.dashboard',

            // Librarian
            'librarian' => 'admin.library.librarian.index',

            // Teacher
            'teacher' => 'admin.faculty.index',

            // Accountant
            'accountant' => 'admin.fees.index',

            // Receptionist
            'receptionist' => 'admin.other-staff.index',

            // Other Staff
            'other_staff' => 'admin.other-staff.index',
        ];

        $roleName = $user->role->name;

        $destination = $roleRoutes[$roleName] ?? 'admin.dashboard';

        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        if (!Route::has($destination)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route($destination);
    }
}