<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        | If 2FA is already verified, don't show setup again
        |--------------------------------------------------------------------------
        */

        if (session('two_factor_verified') === true) {
            return redirect()->route('admin.dashboard');
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
        | Invalid code
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
        | 2FA successful
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'two_factor_verified',
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect to dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()->route('admin.dashboard');
    }
}