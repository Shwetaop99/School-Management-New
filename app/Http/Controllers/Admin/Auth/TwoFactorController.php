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
     * Show the 2FA setup page.
     */
    public function showSetup()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Make sure a user is authenticated
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | If 2FA is already verified
        |--------------------------------------------------------------------------
        */

        if (session('two_factor_verified') === true) {
            return $this->redirectByRole($user);
        }

        $google2fa = new Google2FA();

        /*
        |--------------------------------------------------------------------------
        | Generate secret
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_secret) {

            $secret = $google2fa->generateSecretKey();

            $user->two_factor_secret = $secret;
            $user->save();

        } else {

            $secret = $user->two_factor_secret;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Google Authenticator QR URL
        |--------------------------------------------------------------------------
        */

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Gurukul Vidyalaya',
            $user->email,
            $secret
        );

        /*
        |--------------------------------------------------------------------------
        | Generate SVG QR Code
        |--------------------------------------------------------------------------
        */

        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrCode = $writer->writeString($qrCodeUrl);

        return view('admin.auth.2fa-setup', [
            'secret'   => $secret,
            'qrCodeUrl' => $qrCode,
        ]);
    }


    /**
     * Verify the 6-digit authenticator code.
     */
    public function verify(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate input
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get authenticated user
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure the account is still active
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'Active') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'login_id' => 'This account is inactive. Please contact the administrator.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure the user still has an active role
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
                    'login_id' => 'Your assigned role is inactive or unavailable. Please contact the administrator.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Google Authenticator Code
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
        |
        | These route names can be changed later when each module's
        | dedicated landing page is created.
        |
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
        |
        | Prevent Laravel from throwing RouteNotFoundException if a module's
        | landing route has not been created yet.
        |
        */

        if (!Route::has($destination)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route($destination);
    }
}